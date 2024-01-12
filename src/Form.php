<?php
/**
 * This file is part of the mimmi20/laminasviewrenderer-bootstrap-form package.
 *
 * Copyright (c) 2021-2024, Thomas Mueller <mimmi20@live.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Mimmi20\LaminasView\BootstrapForm;

use Laminas\Form\ElementInterface;
use Laminas\Form\Exception\DomainException;
use Laminas\Form\FieldsetInterface;
use Laminas\Form\FormInterface;
use Laminas\Form\View\Helper\Form as BaseForm;
use Laminas\View\Exception\InvalidArgumentException;
use Laminas\View\Exception\RuntimeException;
use Laminas\View\Renderer\RendererInterface;

use function assert;
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
    use HtmlHelperTrait;

    public const LAYOUT_HORIZONTAL = 'horizontal';

    public const LAYOUT_VERTICAL = 'vertical';

    public const LAYOUT_INLINE = 'inline';

    /**
     * The view helper used to render sub elements.
     */
    protected FormRow | null $elementHelper = null;

    /**
     * The view helper used to render sub fieldsets.
     */
    protected FormCollection | null $fieldsetHelper = null;

    /**
     * Render a form from the provided $form
     *
     * @param FormInterface<TFilteredValues> $form
     *
     * @throws DomainException
     * @throws RuntimeException
     * @throws InvalidArgumentException
     * @throws \Laminas\Form\Exception\InvalidArgumentException
     *
     * @template TFilteredValues of object
     */
    public function render(FormInterface $form): string
    {
        if (method_exists($form, 'prepare')) {
            $form->prepare();
        }

        // Set form role
        if (!$form->hasAttribute('role')) {
            $form->setAttribute('role', 'form');
        }

        $formLayout   = $form->getOption('layout');
        $class        = $form->getAttribute('class') ?? '';
        $requiredMark = $form->getOption('form-required-mark');

        assert(is_string($class));

        if ($formLayout === null && $form->getOption('floating-labels')) {
            $formLayout = self::LAYOUT_VERTICAL;
        }

        if ($formLayout === self::LAYOUT_VERTICAL) {
            if ($form->getOption('as-card')) {
                $class .= ' card';
            } else {
                $class .= ' row';
            }
        } elseif ($formLayout === self::LAYOUT_INLINE) {
            $class .= ' row row-cols-lg-auto align-items-center';
        }

        $form->setAttribute('class', trim($class));

        $formContent = '';
        $indent      = $this->getIndent();

        $elementHelper  = $this->getElementHelper();
        $fieldsetHelper = $this->getFieldsetHelper();

        foreach ($form->getIterator() as $element) {
            assert($element instanceof ElementInterface);

            $element->setOption('form', $form);

            if ($formLayout !== null && !$element->getOption('layout')) {
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

        return $this->openTag($form) . PHP_EOL . $formContent . $indent . $this->closeTag() . PHP_EOL;
    }

    /**
     * Retrieve the element helper.
     *
     * @throws void
     */
    protected function getElementHelper(): FormRow
    {
        if ($this->elementHelper) {
            return $this->elementHelper;
        }

        if ($this->view instanceof RendererInterface && method_exists($this->view, 'plugin')) {
            $this->elementHelper = $this->view->plugin('form_row');
        }

        if (!$this->elementHelper instanceof FormRow) {
            $this->elementHelper = new FormRow();
        }

        return $this->elementHelper;
    }

    /**
     * Retrieve the fieldset helper.
     *
     * @throws void
     */
    protected function getFieldsetHelper(): FormCollection
    {
        if ($this->fieldsetHelper) {
            return $this->fieldsetHelper;
        }

        if ($this->view instanceof RendererInterface && method_exists($this->view, 'plugin')) {
            $this->fieldsetHelper = $this->view->plugin('form_collection');
        }

        if (!$this->fieldsetHelper instanceof FormCollection) {
            $this->fieldsetHelper = new FormCollection();
        }

        return $this->fieldsetHelper;
    }
}
