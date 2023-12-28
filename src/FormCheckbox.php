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

use Laminas\Form\Element\Checkbox as CheckboxElement;
use Laminas\Form\Element\Hidden;
use Laminas\Form\ElementInterface;
use Laminas\Form\Exception;
use Laminas\Form\Exception\DomainException;
use Laminas\Form\Exception\InvalidArgumentException;
use Laminas\Form\View\Helper\FormRow as BaseFormRow;
use Laminas\I18n\Exception\RuntimeException;
use Laminas\I18n\View\Helper\Translate;
use Laminas\View\Helper\Doctype;
use Laminas\View\Helper\EscapeHtml;
use Laminas\View\Helper\EscapeHtmlAttr;
use Mimmi20\LaminasView\Helper\HtmlElement\Helper\HtmlElementInterface;

use function array_filter;
use function array_key_exists;
use function array_merge;
use function array_unique;
use function explode;
use function implode;
use function is_string;
use function sprintf;
use function trim;

use const ARRAY_FILTER_USE_KEY;
use const PHP_EOL;

final class FormCheckbox extends FormInput
{
    use LabelPositionTrait;
    use UseHiddenElementTrait;

    /** @throws void */
    public function __construct(
        EscapeHtml $escapeHtml,
        EscapeHtmlAttr $escapeHtmlAttr,
        Doctype $doctype,
        private readonly FormLabelInterface $formLabel,
        private readonly HtmlElementInterface $htmlElement,
        private readonly FormHiddenInterface $formHidden,
        private readonly Translate | null $translate = null,
    ) {
        parent::__construct($escapeHtml, $escapeHtmlAttr, $doctype);
    }

    /**
     * Render a form <input> element from the provided $element
     *
     * @throws Exception\InvalidArgumentException
     * @throws Exception\DomainException
     * @throws \Laminas\View\Exception\InvalidArgumentException
     * @throws RuntimeException
     */
    public function render(ElementInterface $element): string
    {
        if (!$element instanceof CheckboxElement) {
            throw new Exception\InvalidArgumentException(
                sprintf(
                    '%s requires that the element is of type %s',
                    __METHOD__,
                    CheckboxElement::class,
                ),
            );
        }

        $name = $element->getName();

        if (empty($name)) {
            throw new Exception\DomainException(
                sprintf(
                    '%s requires that the element has an assigned name; none discovered',
                    __METHOD__,
                ),
            );
        }

        $label = $element->getLabel();

        if (!empty($label) && $this->translate !== null) {
            $label = ($this->translate)($label, $this->getTranslatorTextDomain());
        }

        if (!empty($label) && !$element->getLabelOption('disable_html_escape')) {
            $label = ($this->escapeHtml)($label);
        }

        $id = $this->getId($element);

        $groupClasses = ['form-check'];
        $labelClasses = ['form-check-label'];
        $inputClasses = ['form-check-input'];

        if ($element->getOption('layout') === Form::LAYOUT_INLINE) {
            $groupClasses[] = 'form-check-inline';
        }

        $labelAttributes = $element->getLabelAttributes();

        $labelAttributes = [...$labelAttributes, 'for' => $id];

        if (array_key_exists('class', $labelAttributes)) {
            $labelClasses = array_merge(
                $labelClasses,
                explode(' ', (string) $labelAttributes['class']),
            );
        }

        $labelAttributes['class'] = trim(implode(' ', array_unique($labelClasses)));

        $attributes = $element->getAttributes();

        $attributes['name']  = $name;
        $attributes['type']  = $this->getInputType();
        $attributes['value'] = $element->getCheckedValue();
        $closingBracket      = $this->getInlineClosingBracket();

        if ($element->isChecked()) {
            $attributes['checked'] = true;
        }

        if (array_key_exists('class', $attributes)) {
            $inputClasses = array_merge($inputClasses, explode(' ', (string) $attributes['class']));
        }

        $attributes['class'] = trim(implode(' ', array_unique($inputClasses)));

        $indent = $this->getIndent();

        /** @var array<string, bool|string> $filteredAttributes */
        $filteredAttributes = array_filter(
            $labelAttributes,
            static fn ($key): bool => is_string($key),
            ARRAY_FILTER_USE_KEY,
        );

        $rendered = sprintf(
            '<input %s%s',
            $this->createAttributesString($attributes),
            $closingBracket,
        );

        $hidden = '';

        // Render hidden element
        $useHiddenElement = $element->useHiddenElement() || $this->useHiddenElement;

        if ($useHiddenElement) {
            $hidden = $this->renderHiddenElement($element);
        }

        if (array_key_exists('id', $attributes) && !$element->getLabelOption('always_wrap')) {
            $labelOpen  = '';
            $labelClose = '';
            $label      = $indent . $this->getWhitespace(4) . $this->formLabel->openTag(
                $filteredAttributes,
            ) . $label . $this->formLabel->closeTag();
            $rendered   = $indent . $this->getWhitespace(4) . $rendered;

            if ($useHiddenElement) {
                $hidden = $indent . $this->getWhitespace(4) . $hidden . PHP_EOL;
            }
        } else {
            $labelOpen  = $indent . $this->formLabel->openTag($filteredAttributes) . PHP_EOL;
            $labelClose = PHP_EOL . $indent . $this->formLabel->closeTag();
            $rendered   = $indent . $this->getWhitespace(4) . $rendered;

            if ($useHiddenElement) {
                $hidden = $indent . $hidden . PHP_EOL;
            }
        }

        if (
            $label !== '' && !array_key_exists('id', $attributes)
            || $element->getLabelOption('always_wrap')
        ) {
            $label = '<span>' . $label . '</span>';

            if ($labelClose !== '') {
                $label = $indent . $this->getWhitespace(4) . $label;
            }
        }

        $labelPosition = $this->getLabelPosition();

        $markup = match ($labelPosition) {
            BaseFormRow::LABEL_PREPEND => $labelOpen . $label . PHP_EOL . $rendered . $labelClose,
            default => $labelOpen . $rendered . PHP_EOL . $label . $labelClose,
        };

        return $indent . $this->htmlElement->toHtml(
            'div',
            ['class' => $groupClasses],
            PHP_EOL . $hidden . $markup . PHP_EOL . $indent,
        );
    }

    /**
     * Return input type
     *
     * @throws void
     */
    protected function getInputType(): string
    {
        return 'checkbox';
    }

    /**
     * Render a hidden element for empty/unchecked value
     *
     * @throws InvalidArgumentException
     * @throws DomainException
     */
    private function renderHiddenElement(CheckboxElement $element): string
    {
        $uncheckedValue = $element->getUncheckedValue()
            ?: $this->uncheckedValue;

        $hiddenElement = new Hidden($element->getName());
        $hiddenElement->setValue($uncheckedValue);

        return $this->formHidden->render($hiddenElement);
    }
}
