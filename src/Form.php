<?php

/**
 * This file is part of the mimmi20/laminasviewrenderer-bootstrap-form package.
 *
 * Copyright (c) 2021-2026, Thomas Mueller <mimmi20@live.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Mimmi20\LaminasView\BootstrapForm;

use Laminas\Form\ElementInterface;
use Laminas\Form\Exception\DomainException;
use Laminas\Form\Exception\InvalidArgumentException;
use Laminas\Form\FieldsetInterface;
use Laminas\Form\FormInterface;
use Laminas\Form\View\Helper\Form as BaseForm;
use Laminas\ServiceManager\Exception\InvalidServiceException;
use Laminas\ServiceManager\Exception\ServiceNotFoundException;
use Laminas\View\Exception\RuntimeException;
use Laminas\View\Renderer\RendererInterface;
use Override;

use function array_merge;
use function array_unique;
use function assert;
use function explode;
use function implode;
use function is_array;
use function is_string;
use function method_exists;
use function trim;

use const PHP_EOL;

/**
 * View helper for rendering Form objects
 */
final class Form extends BaseForm implements FormIndentInterface
{
    use FormTrait;

    public const string LAYOUT_HORIZONTAL = 'horizontal';

    public const string LAYOUT_VERTICAL = 'vertical';

    public const string LAYOUT_INLINE = 'inline';

    /**
     * The view helper used to render sub elements.
     */
    private FormRowInterface | null $elementHelper = null;

    /**
     * The view helper used to render sub fieldsets.
     */
    private FormCollectionInterface | null $fieldsetHelper = null;

    /**
     * Render a form from the provided $form
     *
     * @param FormInterface<TFilteredValues> $form
     *
     * @throws DomainException
     * @throws RuntimeException
     * @throws InvalidArgumentException
     * @throws \Laminas\View\Exception\InvalidArgumentException
     * @throws InvalidServiceException
     * @throws ServiceNotFoundException
     *
     * @template TFilteredValues of object
     */
    #[Override]
    public function render(FormInterface $form): string
    {
        assert($form instanceof \Laminas\Form\Form);

        if (method_exists($form, 'prepare')) {
            $form->prepare();
        }

        $indent       = $this->getIndent();
        $formContent  = PHP_EOL . $indent . $this->openTag($form) . PHP_EOL;
        $formLayout   = $form->getOption('layout');
        $requiredMark = $form->getOption('form-required-mark');

        assert($requiredMark === null || is_string($requiredMark));

        $wasValidated = $form->getOption('was-validated');

        $elementHelper  = $this->getElementHelper();
        $fieldsetHelper = $this->getFieldsetHelper();

        foreach ($form->getIterator() as $element) {
            assert($element instanceof ElementInterface);

            $element->setOption('form', $form);

            if ($wasValidated !== null && $element->getOption('was-validated') === null) {
                $element->setOption('was-validated', $wasValidated);
            }

            if (!$element->getOption('layout')) {
                $element->setOption('layout', $formLayout);
            }

            if ($requiredMark && $form->getOption('field-required-mark')) {
                $element->setOption('show-required-mark', true);
                $element->setOption('field-required-mark', $form->getOption('field-required-mark'));
            }

            if (
                (
                    $formLayout === self::LAYOUT_VERTICAL
                    || $formLayout === self::LAYOUT_INLINE
                )
                && $form->getOption('floating-labels')
            ) {
                $element->setOption('floating', true);
            }

            if ($element instanceof FieldsetInterface) {
                $fieldsetHelper->setIndent($indent . $this->getWhitespace(4));
                $fieldsetHelper->setShouldWrap(true);

                $formContent .= $fieldsetHelper->render($element) . PHP_EOL;
            } else {
                $elementHelper->setIndent($indent . $this->getWhitespace(4));
                $formContent .= $elementHelper->render($element) . PHP_EOL;
            }
        }

        if ($requiredMark) {
            $formContent .= $indent . $this->getWhitespace(4) . $requiredMark . PHP_EOL;
        }

        return $formContent . $indent . $this->closeTag() . PHP_EOL;
    }

    /**
     * Generate an opening form tag
     *
     * @param FormInterface<TFilteredValues>|null $form
     *
     * @throws void
     *
     * @template TFilteredValues of object
     */
    #[Override]
    public function openTag(FormInterface | null $form = null): string
    {
        if ($form instanceof FormInterface) {
            // Set form role
            if (!$form->hasAttribute('role')) {
                $form->setAttribute('role', 'form');
            }

            $formLayout   = $form->getOption('layout');
            $classes      = explode(' ', trim((string) ($form->getAttribute('class') ?? '')));
            $wasValidated = false;

            try {
                $form->getData();
                $wasValidated = true;
            } catch (DomainException) {
                // do nothing
            }

            $form->setOption('was-validated', $wasValidated);

            assert(is_array($classes));

            if ($formLayout === null && $form->getOption('floating-labels')) {
                $formLayout = self::LAYOUT_VERTICAL;

                $form->setOption('layout', $formLayout);
            }

            if ($formLayout === self::LAYOUT_VERTICAL) {
                $classes[] = $form->getOption('as-card') ? 'card' : 'row';
            } elseif ($formLayout === self::LAYOUT_INLINE) {
                $classes = array_merge(
                    $classes,
                    explode(' ', 'row row-cols-lg-auto align-items-center'),
                );
            }

            $form->setAttribute('class', trim(implode(' ', array_unique($classes))));
        }

        return parent::openTag($form);
    }

    /**
     * @throws void
     *
     * @api
     */
    public function setElementHelper(FormRowInterface | null $elementHelper): void
    {
        $this->elementHelper = $elementHelper;
    }

    /**
     * @throws void
     *
     * @api
     */
    public function setFieldsetHelper(FormCollectionInterface | null $fieldsetHelper): void
    {
        $this->fieldsetHelper = $fieldsetHelper;
    }

    /**
     * Retrieve the element helper.
     *
     * @throws void
     */
    private function getElementHelper(): FormRowInterface
    {
        if ($this->elementHelper) {
            return $this->elementHelper;
        }

        if ($this->view instanceof RendererInterface && method_exists($this->view, 'plugin')) {
            $this->elementHelper = $this->view->plugin('form_row');
        }

        if (!$this->elementHelper instanceof FormRowInterface) {
            $this->elementHelper = new FormRow();
        }

        return $this->elementHelper;
    }

    /**
     * Retrieve the fieldset helper.
     *
     * @throws void
     */
    private function getFieldsetHelper(): FormCollectionInterface
    {
        if ($this->fieldsetHelper) {
            return $this->fieldsetHelper;
        }

        if ($this->view instanceof RendererInterface && method_exists($this->view, 'plugin')) {
            $this->fieldsetHelper = $this->view->plugin('form_collection');
        }

        if (!$this->fieldsetHelper instanceof FormCollectionInterface) {
            $this->fieldsetHelper = new FormCollection();
        }

        return $this->fieldsetHelper;
    }
}
