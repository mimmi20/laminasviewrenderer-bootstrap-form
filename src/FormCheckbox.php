<?php

/**
 * This file is part of the mimmi20/laminasviewrenderer-bootstrap-form package.
 *
 * Copyright (c) 2021-2025, Thomas Mueller <mimmi20@live.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Mimmi20\LaminasView\BootstrapForm;

use Laminas\Form\Element\Checkbox as CheckboxElement;
use Laminas\Form\Element\Hidden;
use Laminas\Form\ElementInterface;
use Laminas\Form\Exception\DomainException;
use Laminas\Form\Exception\InvalidArgumentException;
use Laminas\Form\View\Helper\FormRow as BaseFormRow;
use Override;

use function array_filter;
use function array_key_exists;
use function array_merge;
use function array_unique;
use function assert;
use function explode;
use function get_debug_type;
use function implode;
use function is_array;
use function is_string;
use function sprintf;
use function trim;

use const ARRAY_FILTER_USE_KEY;
use const PHP_EOL;

final class FormCheckbox extends AbstractFormInput implements FormRenderInterface
{
    use HiddenHelperTrait;
    use HtmlHelperTrait;
    use LabelHelperTrait;
    use LabelPositionTrait;
    use UseHiddenElementTrait;

    /**
     * Render a form <input> element from the provided $element
     *
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws \Laminas\View\Exception\InvalidArgumentException
     */
    #[Override]
    public function render(ElementInterface $element): string
    {
        if (!$element instanceof CheckboxElement) {
            throw new InvalidArgumentException(
                sprintf(
                    '%s requires that the element is of type %s, but was %s',
                    __METHOD__,
                    CheckboxElement::class,
                    get_debug_type($element),
                ),
            );
        }

        $name = $element->getName();

        if (empty($name)) {
            throw new DomainException(
                sprintf(
                    '%s requires that the element has an assigned name; none discovered',
                    __METHOD__,
                ),
            );
        }

        $label = $element->getLabel() ?? '';

        if ($label !== '') {
            $label = $this->translateLabel($label);

            if (!$element->getLabelOption('disable_html_escape')) {
                $escapeHtmlHelper = $this->getEscapeHtmlHelper();
                $label            = $escapeHtmlHelper($label);

                assert(is_string($label));
            }
        }

        $id = $this->getId($element);

        $indent          = $this->getIndent();
        $groupAttributes = [];

        if ($element->getOption('as-button')) {
            $inputClasses = ['btn-check'];
            $labelClasses = ['btn'];

            $lf1Indent = $indent;
        } else {
            $groupClasses = ['form-check'];
            $labelClasses = ['form-check-label'];
            $inputClasses = ['form-check-input'];

            if ($element->getOption('layout') === Form::LAYOUT_INLINE) {
                $groupClasses[] = 'form-check-inline';
            }

            if ($element->getOption('switch')) {
                $groupClasses[] = 'form-switch';
            }

            $groupAttributes = $element->getOption('group_attributes') ?? [];
            assert(is_array($groupAttributes));

            if (array_key_exists('class', $groupAttributes) && is_string($groupAttributes['class'])) {
                $groupClasses = array_merge(
                    $groupClasses,
                    explode(' ', $groupAttributes['class']),
                );

                unset($groupAttributes['class']);
            }

            $groupAttributes['class'] = implode(' ', array_unique($groupClasses));

            $lf1Indent = $indent . $this->getWhitespace(4);
        }

        $labelAttributes = [...$element->getLabelAttributes(), 'for' => $id];

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

        /** @var array<string, bool|string> $filteredAttributes */
        $filteredAttributes = array_filter(
            $labelAttributes,
            static fn (int | string $key): bool => is_string($key),
            ARRAY_FILTER_USE_KEY,
        );

        $attributesString = $this->createAttributesString($attributes);

        if (!empty($attributesString)) {
            $attributesString = ' ' . $attributesString;
        }

        $rendered = sprintf('<input%s%s', $attributesString, $closingBracket);

        $hidden = '';

        // Render hidden element
        $useHiddenElement = $element->useHiddenElement() || $this->useHiddenElement;

        if ($useHiddenElement) {
            $hidden = $this->renderHiddenElement($element);
        }

        $labelHelper = $this->getLabelHelper();
        $htmlHelper  = $this->getHtmlHelper();

        $labelStart = $lf1Indent . $labelHelper->openTag($filteredAttributes);

        if (array_key_exists('id', $attributes) && !$element->getLabelOption('always_wrap')) {
            $labelOpen  = '';
            $labelClose = '';
            $label      = $labelStart . $label . $labelHelper->closeTag();
            $rendered   = $lf1Indent . $rendered;

            if ($useHiddenElement) {
                $hidden = $lf1Indent . $hidden . PHP_EOL;
            }
        } else {
            $labelOpen  = $labelStart . PHP_EOL;
            $labelClose = PHP_EOL . $lf1Indent . $labelHelper->closeTag();
            $rendered   = $lf1Indent . $this->getWhitespace(4) . $rendered;

            if ($useHiddenElement) {
                $hidden = $lf1Indent . $hidden . PHP_EOL;
            }
        }

        if (
            $label !== '' && !array_key_exists('id', $attributes)
            || $element->getLabelOption('always_wrap')
        ) {
            $label = '<span>' . $label . '</span>';

            if ($labelClose !== '') {
                $label = $lf1Indent . $this->getWhitespace(4) . $label;
            }
        }

        $labelPosition = $this->getLabelPosition();

        $markup = match ($labelPosition) {
            BaseFormRow::LABEL_PREPEND => $labelOpen . $label . PHP_EOL . $rendered . $labelClose,
            default => $labelOpen . $rendered . PHP_EOL . $label . $labelClose,
        };

        if ($element->getOption('as-button')) {
            return $markup;
        }

        return $indent . $htmlHelper->render(
            'div',
            $groupAttributes,
            PHP_EOL . $hidden . $markup . PHP_EOL . $indent,
        );
    }

    /**
     * Return input type
     *
     * @throws void
     */
    private function getInputType(): string
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
        $uncheckedValue = $element->getUncheckedValue() || $this->uncheckedValue;

        $hiddenElement = new Hidden($element->getName());
        $hiddenElement->setValue($uncheckedValue);

        $hiddenHelper = $this->getHiddenHelper();

        return $hiddenHelper->render($hiddenElement);
    }
}
