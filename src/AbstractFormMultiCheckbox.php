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

use Laminas\Form\Element\Hidden;
use Laminas\Form\Element\MultiCheckbox as MultiCheckboxElement;
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
use function in_array;
use function is_array;
use function is_scalar;
use function is_string;
use function sprintf;

use const ARRAY_FILTER_USE_KEY;
use const PHP_EOL;

abstract class AbstractFormMultiCheckbox extends AbstractFormInput implements FormRenderInterface
{
    use HiddenHelperTrait;
    use HtmlHelperTrait;
    use LabelHelperTrait;
    use LabelPositionTrait;
    use UseHiddenElementTrait;

    /**
     * The attributes applied to option label
     *
     * @var array<int|string, bool|string>
     */
    private array $labelAttributes = [];

    /**
     * Separator for checkbox elements
     */
    private string $separator = '';

    /**
     * Invoke helper as functor
     *
     * Proxies to {@link render()}.
     *
     * @return self|string
     *
     * @throws InvalidArgumentException
     * @throws DomainException
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingNativeTypeHint
     */
    #[Override]
    public function __invoke(ElementInterface | null $element = null, string | null $labelPosition = null)
    {
        if ($element === null) {
            return $this;
        }

        if ($labelPosition !== null) {
            $this->setLabelPosition($labelPosition);
        }

        return $this->render($element);
    }

    /**
     * Render a form <input> element from the provided $element
     *
     * @throws InvalidArgumentException
     * @throws DomainException
     */
    #[Override]
    public function render(ElementInterface $element): string
    {
        if (!$element instanceof MultiCheckboxElement) {
            throw new InvalidArgumentException(
                sprintf(
                    '%s requires that the element is of type %s, but was %s',
                    __METHOD__,
                    MultiCheckboxElement::class,
                    get_debug_type($element),
                ),
            );
        }

        $name       = static::getName($element);
        $options    = $element->getValueOptions();
        $attributes = $element->getAttributes();

        $attributes['name'] = $name;
        $attributes['type'] = $this->getInputType();

        /** @phpstan-var array<int|string, string> $selectedOptions */
        $selectedOptions = (array) $element->getValue();
        $indent          = $this->getIndent();

        $rendered = $indent . $this->renderOptions($element, $options, $selectedOptions, $attributes);

        // Render hidden element
        $useHiddenElement = $element->useHiddenElement() || $this->useHiddenElement;

        if ($useHiddenElement) {
            $rendered = $indent . $this->renderHiddenElement($element) . PHP_EOL . $rendered;
        }

        return $rendered;
    }

    /**
     * Sets the attributes applied to option label.
     *
     * @param array<int|string, bool|string> $attributes
     *
     * @throws void
     *
     * @api
     */
    public function setLabelAttributes(array $attributes): self
    {
        $this->labelAttributes = $attributes;

        return $this;
    }

    /**
     * Returns the attributes applied to each option label.
     *
     * @return array<int|string, bool|string>
     *
     * @throws void
     *
     * @api
     */
    public function getLabelAttributes(): array
    {
        return $this->labelAttributes;
    }

    /**
     * Set separator string for checkbox elements
     *
     * @throws void
     *
     * @api
     */
    public function setSeparator(string $separator): self
    {
        $this->separator = $separator;

        return $this;
    }

    /**
     * Get separator for checkbox elements
     *
     * @throws void
     *
     * @api
     */
    public function getSeparator(): string
    {
        return $this->separator;
    }

    /**
     * Return input type
     *
     * @throws void
     */
    abstract protected function getInputType(): string;

    /**
     * Get element name
     *
     * @throws DomainException
     */
    abstract protected static function getName(ElementInterface $element): string;

    /**
     * Render a hidden element for empty/unchecked value
     *
     * @throws InvalidArgumentException
     * @throws DomainException
     */
    private function renderHiddenElement(MultiCheckboxElement $element): string
    {
        $uncheckedValue = $element->getUncheckedValue() ?? $this->uncheckedValue;
        assert(is_string($uncheckedValue));

        $hiddenElement = new Hidden($element->getName());
        $hiddenElement->setValue($uncheckedValue);

        $hiddenHelper = $this->getHiddenHelper();

        return $hiddenHelper->render($hiddenElement);
    }

    /**
     * Render options
     *
     * @param array<int|string, array<string, array<string, bool|float|int|string|null>|bool|string>|string> $options
     * @param array<int|string, string>                                                                      $selectedOptions
     * @param array<string, bool|float|int|string|null>                                                      $attributes
     *
     * @throws InvalidArgumentException
     * @throws DomainException
     */
    private function renderOptions(
        MultiCheckboxElement $element,
        array $options,
        array $selectedOptions,
        array $attributes,
    ): string {
        $labelPosition = $element->hasLabelOption('label_position')
            ? $element->getLabelOption('label_position')
            : $this->getLabelPosition();

        $closingBracket        = $this->getInlineClosingBracket();
        $globalLabelAttributes = $element->getLabelAttributes();

        if (empty($globalLabelAttributes)) {
            $globalLabelAttributes = $this->labelAttributes;
        }

        $combinedMarkup = [];
        $count          = 0;
        $indent         = $this->getIndent();

        $groupClasses = ['form-check'];

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

        $labelHelper = $this->getLabelHelper();
        $htmlHelper  = $this->getHtmlHelper();

        foreach ($options as $key => $optionSpec) {
            ++$count;

            if (1 < $count && array_key_exists('id', $attributes)) {
                unset($attributes['id']);
            }

            $value           = '';
            $label           = '';
            $inputAttributes = $attributes;

            /** @var array<int|string, bool|float|int|string|null> $labelAttributes */
            $labelAttributes = $globalLabelAttributes;
            $selected        = isset($inputAttributes['selected'])
                && $inputAttributes['type'] !== 'radio'
                && $inputAttributes['selected'];
            $disabled        = isset($inputAttributes['disabled']) && $inputAttributes['disabled'];

            if (is_scalar($optionSpec)) {
                $optionSpec = [
                    'label' => $optionSpec,
                    'value' => $key,
                ];
            }

            if (isset($optionSpec['value'])) {
                $value = $optionSpec['value'];
            }

            if (isset($optionSpec['label'])) {
                $label = $optionSpec['label'];
            }

            if (isset($optionSpec['selected'])) {
                $selected = $optionSpec['selected'];
            }

            if (isset($optionSpec['disabled'])) {
                $disabled = $optionSpec['disabled'];
            }

            if ($element->getOption('as-button')) {
                $inputClasses = ['btn-check'];
                $labelClasses = ['btn'];

                $lf1Indent = $indent;
            } else {
                $labelClasses = ['form-check-label'];
                $inputClasses = ['form-check-input'];

                $lf1Indent = $indent . $this->getWhitespace(4);
            }

            if (array_key_exists('class', $labelAttributes) && is_string($labelAttributes['class'])) {
                $labelClasses = array_merge($labelClasses, explode(' ', $labelAttributes['class']));
            }

            if (array_key_exists('class', $inputAttributes) && is_string($inputAttributes['class'])) {
                $inputClasses = array_merge($inputClasses, explode(' ', $inputAttributes['class']));
            }

            if (
                array_key_exists('label_attributes', $optionSpec)
                && is_array($optionSpec['label_attributes'])
            ) {
                if (
                    array_key_exists('class', $optionSpec['label_attributes'])
                    && is_string($optionSpec['label_attributes']['class'])
                ) {
                    $labelClasses = array_merge(
                        $labelClasses,
                        explode(' ', $optionSpec['label_attributes']['class']),
                    );

                    unset($optionSpec['label_attributes']['class']);
                }

                assert(is_array($optionSpec['label_attributes']));

                $labelAttributes = array_merge($labelAttributes, $optionSpec['label_attributes']);
            }

            if (array_key_exists('attributes', $optionSpec) && is_array($optionSpec['attributes'])) {
                if (
                    array_key_exists('class', $optionSpec['attributes'])
                    && is_string($optionSpec['attributes']['class'])
                ) {
                    $inputClasses = array_merge(
                        $inputClasses,
                        explode(' ', $optionSpec['attributes']['class']),
                    );

                    unset($optionSpec['attributes']['class']);
                }

                assert(is_array($optionSpec['attributes']));

                $inputAttributes = array_merge($inputAttributes, $optionSpec['attributes']);
            }

            if (in_array($value, $selectedOptions, true)) {
                $selected = true;
            }

            $inputAttributes['value']    = $value;
            $inputAttributes['checked']  = $selected;
            $inputAttributes['disabled'] = $disabled;

            if ($disabled) {
                $inputAttributes['aria-disabled'] = 'true';
            }

            $inputAttributes['class'] = $this->combineClasses($inputClasses);
            $labelAttributes['class'] = $this->combineClasses($labelClasses);

            if (array_key_exists('id', $inputAttributes)) {
                $labelAttributes['for'] = $inputAttributes['id'];
            }

            if ($element->getOption('switch')) {
                $inputAttributes['role'] = 'switch';
            }

            $input = sprintf(
                '<input %s%s',
                $this->createAttributesString($inputAttributes),
                $closingBracket,
            );

            assert(is_string($label));

            $label = $this->translateLabel($label);
            $label = $this->escapeLabel($element, $label);

            /** @var array<string, bool|string> $filteredAttributes */
            $filteredAttributes = array_filter(
                $labelAttributes,
                is_string(...),
                ARRAY_FILTER_USE_KEY,
            );

            if (array_key_exists('id', $inputAttributes) && !$element->getLabelOption('always_wrap')) {
                $labelOpen  = '';
                $labelClose = '';
                $label      = $labelHelper->openTag(
                    $filteredAttributes,
                ) . $label . $labelHelper->closeTag();
            } else {
                $labelOpen = $labelHelper->openTag($filteredAttributes) . PHP_EOL;

                if ($element->getOption('as-button')) {
                    $labelOpen .= $lf1Indent;
                }

                $labelClose = PHP_EOL . $lf1Indent . $labelHelper->closeTag();

                $input = $this->getWhitespace(4) . $input;
            }

            $markup = $labelOpen;

            if (
                $label !== '' && !array_key_exists('id', $inputAttributes)
                || $element->getLabelOption('always_wrap')
            ) {
                $label = $this->getWhitespace(4) . '<span>' . $label . '</span>';

                if (!$element->getOption('as-button')) {
                    $markup .= $lf1Indent;
                }
            }

            if ($labelPosition === BaseFormRow::LABEL_PREPEND) {
                $markup .= $label . PHP_EOL . $lf1Indent . $input;
            } else {
                $markup .= $input . PHP_EOL . $lf1Indent . $label;
            }

            $markup .= $labelClose;

            $combinedMarkup[] = $element->getOption('as-button')
                ? $markup
                : $htmlHelper->render(
                    'div',
                    $groupAttributes,
                    PHP_EOL . $lf1Indent . $markup . PHP_EOL . $indent,
                );
        }

        return implode(PHP_EOL . $indent, $combinedMarkup);
    }

    /**
     * @param array<int|string, string> $classes
     *
     * @throws void
     */
    private function combineClasses(array $classes): string
    {
        return implode(' ', array_unique(array_filter($classes)));
    }
}
