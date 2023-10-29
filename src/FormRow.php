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

use Laminas\Form\Element\Button;
use Laminas\Form\Element\Captcha;
use Laminas\Form\Element\Checkbox;
use Laminas\Form\Element\MonthSelect;
use Laminas\Form\Element\MultiCheckbox;
use Laminas\Form\Element\Radio;
use Laminas\Form\Element\Submit;
use Laminas\Form\ElementInterface;
use Laminas\Form\Exception;
use Laminas\Form\Fieldset;
use Laminas\Form\FieldsetInterface;
use Laminas\Form\FormInterface;
use Laminas\Form\LabelAwareInterface;
use Laminas\Form\View\Helper\FormRow as BaseFormRow;
use Laminas\I18n\Exception\RuntimeException;
use Laminas\I18n\View\Helper\Translate;
use Laminas\InputFilter\InputFilterInterface;
use Laminas\InputFilter\InputFilterProviderInterface;
use Laminas\InputFilter\InputInterface;
use Laminas\ServiceManager\Exception\InvalidServiceException;
use Laminas\ServiceManager\Exception\ServiceNotFoundException;
use Laminas\View\Exception\InvalidArgumentException;
use Laminas\View\Helper\EscapeHtml;
use Laminas\View\Renderer\RendererInterface;
use Mimmi20\LaminasView\Helper\HtmlElement\Helper\HtmlElementInterface;

use function array_key_exists;
use function array_merge;
use function array_unique;
use function assert;
use function explode;
use function get_debug_type;
use function implode;
use function is_array;
use function is_string;
use function mb_strlen;
use function mb_strpos;
use function mb_substr;
use function sprintf;
use function str_contains;
use function str_replace;
use function trim;

use const PHP_EOL;

/**
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @psalm-suppress ReservedWord
 */
final class FormRow extends BaseFormRow implements FormRowInterface
{
    use FormTrait;

    /**
     * @throws void
     *
     * @psalm-suppress ReservedWord
     */
    public function __construct(
        private readonly FormElementInterface $formElement,
        private readonly FormElementErrorsInterface $formElementErrors,
        private readonly HtmlElementInterface $htmlElement,
        private readonly EscapeHtml $escapeHtml,
        private readonly RendererInterface $renderer,
        private readonly Translate | null $translate = null,
    ) {
        // nothing to do
    }

    /**
     * Utility form helper that renders a label (if it exists), an element and errors
     *
     * @param string|null $labelPosition
     *
     * @throws ServiceNotFoundException
     * @throws InvalidServiceException
     * @throws Exception\DomainException
     * @throws InvalidArgumentException
     * @throws RuntimeException
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.NullableTypeForNullDefaultValue.NullabilityTypeMissing
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
     */
    public function render(ElementInterface $element, $labelPosition = null): string
    {
        if (!$element->hasAttribute('required')) {
            $elementName = $element->getName();

            if ($elementName !== null) {
                $form = $element->getOption('form');
                assert(
                    $form instanceof FormInterface || $form === null,
                    sprintf(
                        '$form should be an Instance of %s or null, but was %s',
                        FormInterface::class,
                        get_debug_type($form),
                    ),
                );

                if ($form !== null) {
                    $filter = $this->getInputFilter(
                        elementName: $elementName,
                        inputFilter: $form->getInputFilter(),
                        element: $element,
                    );

                    if ($filter instanceof InputInterface && $filter->isRequired()) {
                        $element->setAttribute('required', true);
                    }
                }
            }
        }

        $label = $element->getLabel() ?? '';

        if ($labelPosition === null) {
            $labelPosition = $this->getLabelPosition();
        }

        // hidden elements do not need a <label> -https://github.com/zendframework/zf2/issues/5607
        $type = $element->getAttribute('type');

        // Translate the label
        if ($label !== '' && $this->translate !== null && $type !== 'hidden') {
            $label = ($this->translate)($label, $this->getTranslatorTextDomain());
        }

        // Does this element have errors ?
        if ($element->getMessages()) {
            $classAttributes  = $element->hasAttribute('class')
                ? $element->getAttribute('class') . ' '
                : '';
            $classAttributes .= 'is-invalid';

            $element->setAttribute('class', $classAttributes);
        }

        $indent = $this->getIndent();

        if ($this->partial) {
            $vars = [
                'element' => $element,
                'label' => $label,
                'labelAttributes' => $this->labelAttributes,
                'labelPosition' => $labelPosition,
                'renderErrors' => $this->renderErrors,
                'indent' => $indent,
            ];

            return $this->renderer->render($this->partial, $vars);
        }

        if ($type === 'hidden') {
            $this->formElement->setIndent($indent);
            $markup = $this->formElement->render($element);

            if ($this->renderErrors) {
                $markup .= $this->renderFormErrors($element, $indent . $this->getWhitespace(4));
            }

            return $markup;
        }

        if (
            $label !== ''
            && (
                !$element instanceof LabelAwareInterface
                || !$element->getLabelOption('disable_html_escape')
            )
        ) {
            $label = ($this->escapeHtml)($label);

            assert(is_string($label));
        }

        if ($element->getAttribute('required') && $element->getOption('show-required-mark')) {
            $label .= $element->getOption('field-required-mark');
        }

        if ($element->getOption('layout') === Form::LAYOUT_HORIZONTAL) {
            return $this->renderHorizontalRow($element, $label);
        }

        if ($label !== '') {
            return $this->renderVerticalRow($element, $label, $labelPosition);
        }

        $errorContent = '';
        $helpContent  = '';

        if ($this->renderErrors) {
            $errorContent = $this->renderFormErrors($element, $indent . $this->getWhitespace(4));
        }

        if ($element->getOption('help_content')) {
            $helpContent = $this->renderFormHelp($element, $indent . $this->getWhitespace(4));
        }

        $this->formElement->setIndent($indent . $this->getWhitespace(4));
        $markup = $this->formElement->render($element);

        return $markup . $errorContent . $helpContent;
    }

    /**
     * @throws ServiceNotFoundException
     * @throws InvalidServiceException
     * @throws Exception\DomainException
     */
    private function renderHorizontalRow(ElementInterface $element, string $label): string
    {
        $labelClasses       = [];
        $rowAttributes      = $this->mergeAttributes($element, 'row_attributes', ['row']);
        $colAttributes      = $this->mergeAttributes($element, 'col_attributes', []);
        $labelAttributes    = $this->mergeAttributes($element, 'label_attributes', ['col-form-label']);
        $labelColAttributes = $this->mergeAttributes($element, 'label_col_attributes', []);

        if (array_key_exists('class', $labelAttributes)) {
            $labelClasses = array_merge($labelClasses, explode(' ', $labelAttributes['class']));

            unset($labelAttributes['class']);
        }

        if (array_key_exists('class', $labelColAttributes)) {
            $labelClasses = array_merge($labelClasses, explode(' ', $labelColAttributes['class']));

            unset($labelColAttributes['class']);
        }

        $labelAttributes          = [...$labelColAttributes, ...$labelAttributes];
        $labelAttributes['class'] = trim(implode(' ', array_unique($labelClasses)));

        $indent = $this->getIndent();

        // Multicheckbox elements have to be handled differently as the HTML standard does not allow nested
        // labels. The semantic way is to group them inside a fieldset
        if (
            $element instanceof Radio
            || $element instanceof MultiCheckbox
            || $element instanceof MonthSelect
            || $element instanceof Captcha
        ) {
            $legend = $indent . $this->getWhitespace(4) . $this->htmlElement->toHtml(
                'legend',
                $labelAttributes,
                $label,
            ) . PHP_EOL;

            $errorContent = '';
            $helpContent  = '';

            if ($this->renderErrors) {
                $errorContent = $this->renderFormErrors($element, $indent . $this->getWhitespace(4));
            }

            if ($element->getOption('help_content')) {
                $helpContent = $this->renderFormHelp($element, $indent . $this->getWhitespace(4));
            }

            $this->formElement->setIndent($indent . $this->getWhitespace(4));
            $elementString  = $this->formElement->render($element);
            $elementString .= $errorContent . $helpContent;

            $outerDiv = $indent . $this->getWhitespace(4) . $this->htmlElement->toHtml(
                'div',
                $colAttributes,
                PHP_EOL . $elementString . PHP_EOL . $indent . $this->getWhitespace(4),
            );

            return $indent . $this->htmlElement->toHtml(
                'fieldset',
                $rowAttributes,
                PHP_EOL . $legend . $outerDiv . PHP_EOL . $indent,
            );
        }

        if (
            $element instanceof Button
            || $element instanceof Submit
            || $element instanceof Checkbox
            || $element instanceof Fieldset
        ) {
            // this is a special case, because label is always rendered inside it
            $errorContent = '';
            $helpContent  = '';

            if ($this->renderErrors) {
                $errorContent = $this->renderFormErrors($element, $indent . $this->getWhitespace(8));
            }

            if ($element->getOption('help_content')) {
                $helpContent = $this->renderFormHelp($element, $indent . $this->getWhitespace(8));
            }

            $this->formElement->setIndent($indent . $this->getWhitespace(8));
            $elementString  = $this->formElement->render($element);
            $elementString .= $errorContent . $helpContent;

            $outerDiv = $indent . $this->getWhitespace(4) . $this->htmlElement->toHtml(
                'div',
                $colAttributes,
                PHP_EOL . $elementString . PHP_EOL . $indent . $this->getWhitespace(4),
            );

            return $indent . $this->htmlElement->toHtml(
                'div',
                $rowAttributes,
                PHP_EOL . $outerDiv . PHP_EOL . $indent,
            );
        }

        if ($element->hasAttribute('id')) {
            $id = $element->getAttribute('id');

            assert(is_string($id));

            $labelAttributes['for'] = $id;
        }

        $legend = $indent . $this->getWhitespace(4) . $this->htmlElement->toHtml(
            'label',
            $labelAttributes,
            $label,
        ) . PHP_EOL;

        $errorContent = '';
        $helpContent  = '';

        if ($this->renderErrors) {
            $errorContent = $this->renderFormErrors($element, $indent . $this->getWhitespace(8));
        }

        if ($element->getOption('help_content')) {
            $helpContent = $this->renderFormHelp($element, $indent . $this->getWhitespace(8));
        }

        $this->formElement->setIndent($indent . $this->getWhitespace(8));
        $elementString  = $this->formElement->render($element);
        $elementString .= $errorContent . $helpContent;

        $outerDiv = $indent . $this->getWhitespace(4) . $this->htmlElement->toHtml(
            'div',
            $colAttributes,
            PHP_EOL . $elementString . PHP_EOL . $indent . $this->getWhitespace(4),
        );

        return $indent . $this->htmlElement->toHtml(
            'div',
            $rowAttributes,
            PHP_EOL . $legend . $outerDiv . PHP_EOL . $indent,
        );
    }

    /**
     * @throws ServiceNotFoundException
     * @throws InvalidServiceException
     * @throws Exception\DomainException
     */
    private function renderVerticalRow(
        ElementInterface $element,
        string $label,
        string | null $labelPosition = null,
    ): string {
        $colAttributes   = $this->mergeAttributes($element, 'col_attributes', []);
        $labelAttributes = $this->mergeAttributes($element, 'label_attributes', ['form-label']);

        if ($element->hasAttribute('id')) {
            $id = $element->getAttribute('id');

            assert(is_string($id));

            $labelAttributes['for'] = $id;
        }

        $indent = $this->getIndent();

        // Multicheckbox elements have to be handled differently as the HTML standard does not allow nested
        // labels. The semantic way is to group them inside a fieldset
        if (
            $element instanceof Radio
            || $element instanceof MultiCheckbox
            || $element instanceof MonthSelect
            || $element instanceof Captcha
        ) {
            $legendClasses    = [];
            $legendAttributes = $this->mergeAttributes($element, 'legend_attributes', []);

            if (array_key_exists('class', $legendAttributes)) {
                $legendClasses = array_merge($legendClasses, explode(' ', $legendAttributes['class']));

                unset($legendAttributes['class']);
            }

            $legendAttributes['class'] = trim(implode(' ', array_unique($legendClasses)));

            $legend = $indent . $this->getWhitespace(4) . $this->htmlElement->toHtml(
                'legend',
                $legendAttributes,
                $label,
            ) . PHP_EOL;

            $errorContent = '';
            $helpContent  = '';

            if ($this->renderErrors) {
                $errorContent = $this->renderFormErrors($element, $indent . $this->getWhitespace(8));
            }

            if ($element->getOption('help_content')) {
                $helpContent = $this->renderFormHelp($element, $indent . $this->getWhitespace(8));
            }

            $floating   = $element->getOption('floating');
            $baseIndent = $indent;

            if ($floating) {
                $indent .= $this->getWhitespace(4);
            }

            $this->formElement->setIndent($indent . $this->getWhitespace(4));
            $elementString  = $indent . $this->getWhitespace(4) . $this->formElement->render($element);
            $elementString .= $errorContent . $helpContent;

            if ($floating) {
                $elementString = $this->htmlElement->toHtml(
                    'div',
                    ['class' => 'form-control'],
                    PHP_EOL . $elementString . PHP_EOL . $indent,
                ) . PHP_EOL . $legend;
                $elementString = $indent . $this->htmlElement->toHtml(
                    'div',
                    ['class' => 'form-floating'],
                    PHP_EOL . $elementString . $indent,
                );
            } else {
                $elementString = $legend . $elementString;
            }

            return $baseIndent . $this->htmlElement->toHtml(
                'fieldset',
                $colAttributes,
                PHP_EOL . $elementString . PHP_EOL . $baseIndent,
            );
        }

        if (
            $element instanceof Button
            || $element instanceof Submit
            || $element instanceof Checkbox
            || $element instanceof Fieldset
        ) {
            // this is a special case, because label is always rendered inside it
            $errorContent = '';
            $helpContent  = '';

            if ($this->renderErrors) {
                $errorContent = $this->renderFormErrors($element, $indent . $this->getWhitespace(4));
            }

            if ($element->getOption('help_content')) {
                $helpContent = $this->renderFormHelp($element, $indent . $this->getWhitespace(4));
            }

            $this->formElement->setIndent($indent . $this->getWhitespace(4));
            $elementString  = $this->formElement->render($element);
            $elementString .= $errorContent . $helpContent;

            return $indent . $this->htmlElement->toHtml(
                'div',
                $colAttributes,
                PHP_EOL . $elementString . PHP_EOL . $indent,
            );
        }

        $floating   = $element->getOption('floating');
        $baseIndent = $indent;

        if ($floating) {
            $indent .= $this->getWhitespace(4);
        }

        if ($element instanceof LabelAwareInterface) {
            if ($floating) {
                $labelPosition = BaseFormRow::LABEL_APPEND;
            } elseif ($element->hasLabelOption('label_position')) {
                $labelPosition = $element->getLabelOption('label_position');
            } else {
                $labelPosition = BaseFormRow::LABEL_PREPEND;
            }
        }

        $legend = $indent . $this->getWhitespace(4) . $this->htmlElement->toHtml(
            'label',
            $labelAttributes,
            $label,
        );

        $errorContent = '';
        $helpContent  = '';

        if ($this->renderErrors) {
            $errorContent = $this->renderFormErrors($element, $indent . $this->getWhitespace(4));
        }

        if ($element->getOption('help_content')) {
            $helpContent = $this->renderFormHelp($element, $indent . $this->getWhitespace(4));
        }

        $this->formElement->setIndent($indent . $this->getWhitespace(4));
        $elementString = $this->formElement->render($element);

        $rendered = match ($labelPosition) {
            BaseFormRow::LABEL_PREPEND => $legend . PHP_EOL . $elementString,
            default => $elementString . PHP_EOL . $legend,
        };

        $rendered .= $errorContent . $helpContent;

        if ($floating) {
            $rendered = $indent . $this->htmlElement->toHtml(
                'div',
                ['class' => 'form-floating'],
                PHP_EOL . $rendered . PHP_EOL . $indent,
            );
        }

        return $baseIndent . $this->htmlElement->toHtml(
            'div',
            $colAttributes,
            PHP_EOL . $rendered . PHP_EOL . $baseIndent,
        );
    }

    /** @throws Exception\DomainException */
    private function renderFormErrors(ElementInterface $element, string $indent): string
    {
        $this->formElementErrors->setIndent($indent);
        $elementErrors = $this->formElementErrors->render($element);

        if ($elementErrors && $element->hasAttribute('id')) {
            $ariaDesc = $element->hasAttribute('aria-describedby')
                ? $element->getAttribute('aria-describedby') . ' '
                : '';

            $ariaDesc .= $element->getAttribute('id') . 'Feedback';

            $element->setAttribute('aria-describedby', $ariaDesc);
        }

        return $elementErrors;
    }

    /**
     * @throws void
     *
     * @psalm-suppress ReservedWord
     */
    private function renderFormHelp(ElementInterface $element, string $indent): string
    {
        $helpContent = $element->getOption('help_content');
        $attributes  = $this->mergeAttributes($element, 'help_attributes', []);

        assert(is_string($helpContent));

        if ($element->hasAttribute('id')) {
            $attributes['id'] = $element->getAttribute('id') . 'Help';

            $ariaDesc = $element->hasAttribute('aria-describedby')
                ? $element->getAttribute('aria-describedby') . ' '
                : '';

            $ariaDesc .= $element->getAttribute('id') . 'Help';

            $element->setAttribute('aria-describedby', $ariaDesc);
        }

        return PHP_EOL . $indent . $this->htmlElement->toHtml('div', $attributes, $helpContent);
    }

    /**
     * @param array<int, string> $classes
     *
     * @return array<string, string>
     *
     * @throws void
     */
    private function mergeAttributes(ElementInterface $element, string $optionName, array $classes = []): array
    {
        $attributes = $element->getOption($optionName) ?? [];
        assert(is_array($attributes));

        if (array_key_exists('class', $attributes)) {
            $classes = array_merge($classes, explode(' ', (string) $attributes['class']));

            unset($attributes['class']);
        }

        $form = $element->getOption('form');
        assert(
            $form instanceof FormInterface || $form === null,
            sprintf(
                '$form should be an Instance of %s or null, but was %s',
                FormInterface::class,
                get_debug_type($form),
            ),
        );

        if ($form !== null) {
            $formAttributes = $form->getOption($optionName) ?? [];

            assert(is_array($formAttributes));

            if (array_key_exists('class', $formAttributes)) {
                $classes = array_merge($classes, explode(' ', (string) $formAttributes['class']));

                unset($formAttributes['class']);
            }

            $attributes = [...$formAttributes, ...$attributes];
        }

        if ($classes) {
            $attributes['class'] = implode(' ', array_unique($classes));
        }

        return $attributes;
    }

    /**
     * @param InputFilterInterface<TFilteredValues> $inputFilter
     *
     * @return InputFilterInterface<mixed>|InputInterface|null
     *
     * @throws void
     *
     * @template TFilteredValues
     */
    private function getInputFilter(
        string $elementName,
        InputFilterInterface $inputFilter,
        ElementInterface $element,
        int $level = 0,
    ): InputInterface | InputFilterInterface | null {
        if ($inputFilter->has($elementName)) {
            $filter = $inputFilter->get($elementName);

            if ($filter instanceof InputInterface) {
                return $filter;
            }
        }

        $fieldset = $element->getOption('fieldset');
        assert(
            $fieldset instanceof FieldsetInterface || $fieldset === null,
            sprintf(
                '$fieldset should be an Instance of %s or null, but was %s',
                FieldsetInterface::class,
                get_debug_type($fieldset),
            ),
        );

        if (!$fieldset instanceof InputFilterProviderInterface || $fieldset->getName() === null) {
            return null;
        }

        $fieldsetName         = $fieldset->getName();
        $fieldsetNameOriginal = $fieldsetName;

        if (!$inputFilter->has($fieldsetNameOriginal) && str_contains($fieldsetNameOriginal, '[')) {
            $startPos = mb_strpos($fieldsetNameOriginal, '[');
            $endPos   = mb_strpos($fieldsetNameOriginal, ']', $startPos + 1);

            if ($startPos !== false && $endPos !== false) {
                $baseFieldsetName = mb_substr($fieldsetNameOriginal, 0, $startPos);
                $fieldsetName     = mb_substr(
                    $fieldsetNameOriginal,
                    $startPos + 1,
                    $endPos - $startPos - 1,
                );

                if ($inputFilter->has($baseFieldsetName)) {
                    $baseFilter = $inputFilter->get($baseFieldsetName);

                    if ($baseFilter instanceof InputFilterInterface) {
                        return $this->getInputFilter(
                            elementName: str_replace(
                                $fieldsetNameOriginal,
                                $fieldsetName,
                                $elementName,
                            ),
                            inputFilter: $baseFilter,
                            element: $element,
                            level: $level + 1,
                        );
                    }
                }
            }
        }

        if (!$inputFilter->has($fieldsetName)) {
            return null;
        }

        $filter = $inputFilter->get($fieldsetName);

        if ($filter instanceof InputInterface) {
            return $filter;
        }

        $originalElementName = mb_substr($elementName, mb_strlen($fieldsetName) + 1, -1);

        if ($filter->has($originalElementName)) {
            $subFilter = $filter->get($originalElementName);

            if ($subFilter instanceof InputInterface) {
                return $subFilter;
            }

            return $this->getInputFilter(
                elementName: $originalElementName,
                inputFilter: $subFilter,
                element: $element,
                level: $level + 1,
            );
        }

        return null;
    }
}
