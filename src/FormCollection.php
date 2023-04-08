<?php
/**
 * This file is part of the mimmi20/laminasviewrenderer-bootstrap-form package.
 *
 * Copyright (c) 2021-2023, Thomas Mueller <mimmi20@live.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Mimmi20\LaminasView\BootstrapForm;

use Laminas\Form\Element\Collection as CollectionElement;
use Laminas\Form\ElementInterface;
use Laminas\Form\Exception;
use Laminas\Form\FieldsetInterface;
use Laminas\Form\LabelAwareInterface;
use Laminas\Form\View\Helper\AbstractHelper;
use Laminas\I18n\View\Helper\Translate;
use Laminas\ServiceManager\Exception\InvalidServiceException;
use Laminas\ServiceManager\Exception\ServiceNotFoundException;
use Laminas\View\Exception\InvalidArgumentException;
use Laminas\View\Exception\RuntimeException;
use Laminas\View\Helper\EscapeHtml;
use Mimmi20\LaminasView\Helper\HtmlElement\Helper\HtmlElementInterface;

use function array_key_exists;
use function array_merge;
use function array_unique;
use function assert;
use function explode;
use function implode;
use function is_array;
use function is_string;
use function sprintf;
use function trim;

use const PHP_EOL;

final class FormCollection extends AbstractHelper implements FormCollectionInterface
{
    use FormTrait;

    /**
     * If set to true, collections are automatically wrapped around a fieldset
     */
    private bool $shouldWrap = true;

    /** @throws void */
    public function __construct(
        private readonly FormRowInterface $formRow,
        private readonly EscapeHtml $escapeHtml,
        private readonly HtmlElementInterface $htmlElement,
        private readonly Translate | null $translate = null,
    ) {
    }

    /**
     * Invoke helper as function
     *
     * Proxies to {@link render()}.
     *
     * @return self|string
     *
     * @throws ServiceNotFoundException
     * @throws InvalidServiceException
     * @throws Exception\DomainException
     * @throws RuntimeException
     * @throws InvalidArgumentException
     * @throws Exception\InvalidArgumentException
     */
    public function __invoke(ElementInterface | null $element = null, bool $wrap = true)
    {
        if (!$element) {
            return $this;
        }

        $this->setShouldWrap($wrap);

        return $this->render($element);
    }

    /**
     * Render a collection by iterating through all fieldsets and elements
     *
     * @throws ServiceNotFoundException
     * @throws InvalidServiceException
     * @throws Exception\DomainException
     * @throws RuntimeException
     * @throws InvalidArgumentException
     * @throws Exception\InvalidArgumentException
     */
    public function render(ElementInterface $element): string
    {
        if (!$element instanceof FieldsetInterface) {
            throw new Exception\InvalidArgumentException(
                sprintf(
                    '%s requires that the element is of type %s',
                    __METHOD__,
                    FieldsetInterface::class,
                ),
            );
        }

        $markup         = '';
        $templateMarkup = '';
        $indent         = $this->getIndent();

        if ($element instanceof CollectionElement && $element->shouldCreateTemplate()) {
            $templateMarkup = $this->renderTemplate($element);
        }

        $form     = $element->getOption('form');
        $layout   = $element->getOption('layout');
        $floating = $element->getOption('floating');

        foreach ($element->getIterator() as $elementOrFieldset) {
            assert($elementOrFieldset instanceof ElementInterface);

            if (null !== $form && !$elementOrFieldset->getOption('form')) {
                $elementOrFieldset->setOption('form', $form);
            }

            if (null !== $layout && !$elementOrFieldset->getOption('layout')) {
                $elementOrFieldset->setOption('layout', $layout);
            }

            if ($floating) {
                $elementOrFieldset->setOption('floating', true);
            }

            if ($element->getOption('show-required-mark') && $element->getOption('field-required-mark')) {
                $elementOrFieldset->setOption('show-required-mark', true);
                $elementOrFieldset->setOption('field-required-mark', $element->getOption('field-required-mark'));
            }

            if ($elementOrFieldset instanceof FieldsetInterface) {
                $this->setIndent($indent . $this->getWhitespace(4));

                $markup .= $this->render($elementOrFieldset) . PHP_EOL;

                $this->setIndent($indent);
            } else {
                $this->formRow->setIndent($indent . $this->getWhitespace(4));
                $markup .= $this->formRow->render($elementOrFieldset) . PHP_EOL;
            }
        }

        if (!$this->shouldWrap) {
            return $markup . $templateMarkup;
        }

        // Every collection is wrapped by a fieldset if needed
        $attributes = $element->getAttributes();

        unset($attributes['name']);

        $indent = $this->getIndent();
        $label  = $element->getLabel();
        $legend = '';

        if (!empty($label)) {
            if (null !== $this->translate) {
                $label = ($this->translate)(
                    $label,
                    $this->getTranslatorTextDomain(),
                );

                assert(is_string($label));
            }

            if (!$element instanceof LabelAwareInterface || !$element->getLabelOption('disable_html_escape')) {
                $label = ($this->escapeHtml)($label);

                assert(is_string($label));
            }

            if (
                '' !== $label && !$element->hasAttribute('id')
                || ($element instanceof LabelAwareInterface && $element->getLabelOption('always_wrap'))
            ) {
                $label = '<span>' . $label . '</span>';
            }

            $labelClasses    = [];
            $labelAttributes = $element->getOption('label_attributes') ?? [];

            assert(is_array($labelAttributes));

            if (array_key_exists('class', $labelAttributes)) {
                $labelClasses = array_merge($labelClasses, explode(' ', (string) $labelAttributes['class']));
            }

            $labelAttributes['class'] = trim(implode(' ', array_unique($labelClasses)));

            $legend = PHP_EOL . $indent . $this->getWhitespace(4) . $this->htmlElement->toHtml('legend', $labelAttributes, $label);
        }

        $markup = PHP_EOL . $markup;

        return $indent . $this->htmlElement->toHtml('fieldset', $attributes, $legend . $markup . $templateMarkup . $indent);
    }

    /**
     * Only render a template
     *
     * @throws ServiceNotFoundException
     * @throws InvalidServiceException
     * @throws Exception\DomainException
     * @throws RuntimeException
     * @throws InvalidArgumentException
     * @throws Exception\InvalidArgumentException
     */
    public function renderTemplate(CollectionElement $collection): string
    {
        $elementOrFieldset = $collection->getTemplateElement();

        if (!$elementOrFieldset instanceof ElementInterface) {
            return '';
        }

        $indent = $this->getIndent();

        if ($elementOrFieldset instanceof FieldsetInterface) {
            $this->setIndent($indent . $this->getWhitespace(4));

            $templateMarkup = $this->render($elementOrFieldset) . PHP_EOL;

            $this->setIndent($indent);
        } else {
            $this->formRow->setIndent($indent . $this->getWhitespace(4));
            $templateMarkup = $this->formRow->render($elementOrFieldset) . PHP_EOL;
        }

        $templateAttrbutes = $collection->getOption('template_attributes') ?? [];

        assert(is_array($templateAttrbutes));

        return $indent . $this->getWhitespace(4) . $this->htmlElement->toHtml('template', $templateAttrbutes, $templateMarkup . $indent) . PHP_EOL;
    }

    /**
     * If set to true, collections are automatically wrapped around a fieldset
     *
     * @throws void
     */
    public function setShouldWrap(bool $wrap): self
    {
        $this->shouldWrap = $wrap;

        return $this;
    }

    /**
     * Get wrapped
     *
     * @throws void
     */
    public function shouldWrap(): bool
    {
        return $this->shouldWrap;
    }
}
