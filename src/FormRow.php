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

use Laminas\Form\Element\Button;
use Laminas\Form\Element\Captcha;
use Laminas\Form\Element\Checkbox;
use Laminas\Form\Element\MonthSelect;
use Laminas\Form\Element\MultiCheckbox;
use Laminas\Form\Element\Submit;
use Laminas\Form\ElementInterface;
use Laminas\Form\Exception\DomainException;
use Laminas\Form\Exception\InvalidArgumentException;
use Laminas\Form\Fieldset;
use Laminas\Form\FieldsetInterface;
use Laminas\Form\FormInterface;
use Laminas\Form\LabelAwareInterface;
use Laminas\Form\View\Helper\FormElement;
use Laminas\Form\View\Helper\FormRow as BaseFormRow;
use Laminas\InputFilter\InputFilterInterface;
use Laminas\InputFilter\InputFilterProviderInterface;
use Laminas\InputFilter\InputInterface;
use stdClass;

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
use function mb_strlen;
use function mb_strpos;
use function mb_substr;
use function sprintf;
use function str_contains;
use function str_replace;
use function trim;

use const PHP_EOL;

/** @SuppressWarnings(PHPMD.ExcessiveClassComplexity) */
final class FormRow extends BaseFormRow implements FormRowInterface
{
    use FormTrait;
    use HiddenHelperTrait;
    use HtmlHelperTrait;

    /**
     * The class that is added to element that have errors
     *
     * @var string
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint
     */
    protected $inputErrorClass = 'is-invalid';

    /**
     * Utility form helper that renders a label (if it exists), an element and errors
     *
     * @throws DomainException
     * @throws InvalidArgumentException
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.NullableTypeForNullDefaultValue.NullabilityTypeMissing
     */
    public function render(ElementInterface $element, string | null $labelPosition = null): string
    {
        $form = $element->getOption('form');
        assert(
            $form instanceof FormInterface || $form === null,
            sprintf(
                '$form should be an Instance of %s or null, but was %s',
                FormInterface::class,
                get_debug_type($form),
            ),
        );

        if (!$element->hasAttribute('required')) {
            $elementName = $element->getName();

            if ($elementName !== null) {
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
        if ($label !== '' && $type !== 'hidden') {
            $label = $this->translateLabel($label);
        }

        // Does this element have errors ?
        if ($element->getMessages() !== []) {
            $inputErrorClass = $this->getInputErrorClass();
            $classAttributes = [];

            if ($element->hasAttribute('class')) {
                $classAttributes = array_merge(
                    $classAttributes,
                    explode(' ', (string) $element->getAttribute('class')),
                );
            }

            if ($inputErrorClass) {
                $classAttributes[] = $inputErrorClass;
            }

            $errorClass = $element->getOption('error-class');

            if ($errorClass) {
                $classAttributes[] = $errorClass;
            }

            $element->setAttribute('class', implode(' ', array_unique($classAttributes)));
        } else {
            $wasValidated = $element->getOption('was-validated');

            if ($wasValidated === null && $form !== null) {
                $wasValidated = $form->getOption('was-validated');
            }

            if ($wasValidated) {
                $classAttributes = [];

                if ($element->hasAttribute('class')) {
                    $classAttributes = array_merge(
                        $classAttributes,
                        explode(' ', (string) $element->getAttribute('class')),
                    );
                }

                $validClass = $element->getOption('valid-class');

                if ($validClass) {
                    $classAttributes[] = $validClass;
                }

                $element->setAttribute('class', implode(' ', array_unique($classAttributes)));
            }
        }

        $indent = $this->getIndent();

        if ($this->view !== null && $this->partial) {
            $vars = [
                'element' => $element,
                'label' => $label,
                'labelAttributes' => $this->labelAttributes,
                'labelPosition' => $labelPosition,
                'renderErrors' => $this->renderErrors,
                'indent' => $indent,
            ];

            return $this->view->render($this->partial, $vars);
        }

        if ($type === 'hidden') {
            $hiddenHelper = $this->getHiddenHelper();
            $hiddenHelper->setIndent($indent);

            $errorContent = '';

            if ($this->renderErrors) {
                $errorContent = $this->renderFormErrors($element, $indent . $this->getWhitespace(4));
            }

            $markup = $hiddenHelper->render($element);

            return $markup . $errorContent;
        }

        $label = $this->escapeLabel($element, $label);

        assert(is_string($label));

        $layout           = $element->getOption('layout');
        $floating         = $element->getOption('floating');
        $showRequiredMark = $element->getOption('show-required-mark');
        $requiredMark     = $element->getOption('field-required-mark');

        if ($form !== null) {
            if ($layout === null) {
                $layout = $form->getOption('layout');
            }

            if (
                $floating === null
                && ($layout === Form::LAYOUT_VERTICAL || $layout === Form::LAYOUT_INLINE)
                && $form->getOption('floating-labels')
            ) {
                $element->setOption('floating', true);
            }

            if ($showRequiredMark === null) {
                $showRequiredMark = $form->getOption('form-required-mark') !== null
                    && $form->getOption('field-required-mark') !== null;
            }

            if ($showRequiredMark && $requiredMark === null) {
                $requiredMark = $form->getOption('field-required-mark');
            }
        }

        if ($showRequiredMark && is_string($requiredMark) && $element->getAttribute('required')) {
            $label .= $requiredMark;
        }

        if ($layout === Form::LAYOUT_HORIZONTAL) {
            return $this->renderHorizontalRow($element, $label);
        }

        return $this->renderVerticalRow($element, $label, $labelPosition);
    }

    /**
     * @throws DomainException
     * @throws InvalidArgumentException
     */
    private function renderHorizontalRow(ElementInterface $element, string $label): string
    {
        $rowAttributes      = $this->mergeAttributes($element, 'row_attributes', ['row']);
        $colAttributes      = $this->mergeAttributes($element, 'col_attributes', []);
        $labelColAttributes = $this->mergeAttributes(
            $element,
            'label_col_attributes',
            ['col-form-label'],
        );

        $indent     = $this->getIndent();
        $type       = $element->getAttribute('type');
        $htmlHelper = $this->getHtmlHelper();

        $elementHelper = $this->getElementHelper();
        assert($elementHelper instanceof FormElement);

        // Multicheckbox elements have to be handled differently as the HTML standard does not allow nested
        // labels. The semantic way is to group them inside a fieldset
        if (
            $element instanceof MultiCheckbox
            || $element instanceof MonthSelect
            || $element instanceof Captcha
            || in_array($type, ['multi_checkbox', 'radio'], true)
        ) {
            $baseIndent = $indent;
            $lf1Indent  = $indent . $this->getWhitespace(4);
            $lf2Indent  = $lf1Indent . $this->getWhitespace(4);
            $lf3Indent  = $lf2Indent . $this->getWhitespace(4);
            $lf4Indent  = $lf3Indent . $this->getWhitespace(4);

            $asCard        = $element->getOption('as-card');
            $asFormControl = $element->getOption('as-form-control');

            $legend = $lf1Indent . $htmlHelper->render('legend', $labelColAttributes, $label) . PHP_EOL;

            $errorContent   = '';
            $helpContent    = '';
            $messageContent = '';

            if ($this->renderErrors) {
                $errorContent = $this->renderFormErrors(
                    $element,
                    $asCard || $asFormControl ? $lf4Indent : $lf2Indent,
                );
            }

            if ($element->getOption('messages')) {
                $messageContent = $this->renderMessages(
                    $element,
                    $asCard || $asFormControl ? $lf4Indent : $lf2Indent,
                );
            }

            if ($element->getOption('help_content') !== null) {
                $helpContent = $this->renderFormHelp($element, $lf1Indent, $rowAttributes);
            }

            if ($elementHelper instanceof FormIndentInterface) {
                $elementHelper->setIndent($element->getOption('as-card') ? $lf4Indent : $lf3Indent);
            }

            $elementString  = $elementHelper->render($element);
            $elementString .= $errorContent;
            $elementString .= $messageContent;

            $elementString = $this->wrapInContainer(
                element: $element,
                elementString: $elementString,
                htmlHelper: $htmlHelper,
                indent: $lf2Indent,
            );

            $outerDiv = $lf1Indent . $htmlHelper->render(
                'div',
                $colAttributes,
                PHP_EOL . $lf2Indent . $elementString . PHP_EOL . $lf1Indent,
            );

            return $baseIndent . $htmlHelper->render(
                'fieldset',
                $rowAttributes,
                PHP_EOL . $legend . $outerDiv . $helpContent . PHP_EOL . $baseIndent,
            );
        }

        if ($element instanceof Checkbox || $type === 'checkbox') {
            // this is a special case, because label is always rendered inside it
            $errorContent   = '';
            $helpContent    = '';
            $messageContent = '';
            $baseIndent     = $indent;
            $lf1Indent      = $indent . $this->getWhitespace(4);
            $lf2Indent      = $lf1Indent . $this->getWhitespace(4);
            $lf3Indent      = $lf2Indent . $this->getWhitespace(4);
            $lf4Indent      = $lf3Indent . $this->getWhitespace(4);

            $asCard        = $element->getOption('as-card');
            $asFormControl = $element->getOption('as-form-control');

            if ($this->renderErrors) {
                $errorContent = $this->renderFormErrors(
                    $element,
                    $asCard || $asFormControl ? $lf4Indent : $lf2Indent,
                );
            }

            if ($element->getOption('messages')) {
                $messageContent = $this->renderMessages(
                    $element,
                    $asCard || $asFormControl ? $lf4Indent : $lf2Indent,
                );
            }

            if ($element->getOption('help_content') !== null) {
                $helpContent = $this->renderFormHelp($element, $lf1Indent, $rowAttributes);
            }

            if ($elementHelper instanceof FormIndentInterface) {
                $elementHelper->setIndent($element->getOption('as-card') ? $lf4Indent : $lf3Indent);
            }

            $elementString  = $elementHelper->render($element);
            $elementString .= $errorContent . $messageContent;

            $elementString = $this->wrapInContainer(
                element: $element,
                elementString: $elementString,
                htmlHelper: $htmlHelper,
                indent: $lf2Indent,
            );

            $outerDiv = $lf1Indent . $htmlHelper->render(
                'div',
                $colAttributes,
                PHP_EOL . $lf2Indent . $elementString . PHP_EOL . $lf1Indent,
            );

            return $baseIndent . $htmlHelper->render(
                'div',
                $rowAttributes,
                PHP_EOL . $outerDiv . $helpContent . PHP_EOL . $baseIndent,
            );
        }

        if (
            $element instanceof Button
            || $element instanceof Submit
            || $element instanceof Fieldset
            || in_array($type, ['button', 'submit', 'reset'], true)
        ) {
            // this is a special case, because label is always rendered inside it
            $baseIndent = $indent;
            $lf1Indent  = $indent . $this->getWhitespace(4);
            $lf2Indent  = $lf1Indent . $this->getWhitespace(4);

            if ($elementHelper instanceof FormIndentInterface) {
                $elementHelper->setIndent($lf2Indent);
            }

            $elementString = $elementHelper->render($element);

            $outerDiv = $lf1Indent . $htmlHelper->render(
                'div',
                $colAttributes,
                PHP_EOL . $elementString . PHP_EOL . $lf1Indent,
            );

            return $baseIndent . $htmlHelper->render(
                'div',
                $rowAttributes,
                PHP_EOL . $outerDiv . PHP_EOL . $baseIndent,
            );
        }

        if ($element->hasAttribute('id')) {
            $id = $element->getAttribute('id');

            assert(is_string($id));

            $labelColAttributes['for'] = $id;
        }

        $errorContent   = '';
        $helpContent    = '';
        $messageContent = '';
        $baseIndent     = $indent;
        $lf1Indent      = $indent . $this->getWhitespace(4);
        $lf2Indent      = $lf1Indent . $this->getWhitespace(4);
        $lf3Indent      = $lf2Indent . $this->getWhitespace(4);

        $labelHelper = $this->getLabelHelper();

        $legend = $lf1Indent . $labelHelper->openTag(
            $labelColAttributes,
        ) . $label . $labelHelper->closeTag();

        if ($this->renderErrors) {
            $errorContent = $this->renderFormErrors($element, $lf2Indent);
        }

        if ($element->getOption('messages')) {
            $messageContent = $this->renderMessages($element, $lf2Indent);
        }

        if ($element->getOption('help_content') !== null) {
            $helpContent = $this->renderFormHelp($element, $lf1Indent, $rowAttributes);
        }

        if ($elementHelper instanceof FormIndentInterface) {
            $elementHelper->setIndent($element->getOption('in-group') ? $lf3Indent : $lf2Indent);
        }

        $elementString = $elementHelper->render($element);

        $elementString .= $errorContent . $messageContent;

        $elementString = $this->wrapInGroup(
            element: $element,
            elementString: $elementString,
            htmlHelper: $htmlHelper,
            indent: $lf2Indent,
        );

        $outerDiv = $lf1Indent . $htmlHelper->render(
            'div',
            $colAttributes,
            PHP_EOL . $elementString . PHP_EOL . $lf1Indent,
        );

        return $baseIndent . $htmlHelper->render(
            'div',
            $rowAttributes,
            PHP_EOL . $legend . PHP_EOL . $outerDiv . $helpContent . PHP_EOL . $baseIndent,
        );
    }

    /**
     * @throws DomainException
     * @throws InvalidArgumentException
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

        $indent     = $this->getIndent();
        $htmlHelper = $this->getHtmlHelper();

        $elementHelper = $this->getElementHelper();
        assert($elementHelper instanceof FormElement);

        // Multicheckbox elements have to be handled differently as the HTML standard does not allow nested
        // labels. The semantic way is to group them inside a fieldset
        if (
            $element instanceof MultiCheckbox
            || $element instanceof MonthSelect
            || $element instanceof Captcha
        ) {
            $legendClasses    = [];
            $legendAttributes = $this->mergeAttributes($element, 'legend_attributes', ['form-label']);

            if (array_key_exists('class', $legendAttributes)) {
                $legendClasses = array_merge($legendClasses, explode(' ', $legendAttributes['class']));

                unset($legendAttributes['class']);
            }

            $legendAttributes['class'] = trim(implode(' ', array_unique($legendClasses)));

            $legend = $indent . $this->getWhitespace(4) . $htmlHelper->render(
                'legend',
                $legendAttributes,
                $label,
            );

            $errorContent   = '';
            $helpContent    = '';
            $messageContent = '';
            $floating       = $element->getOption('floating');

            $baseIndent = $indent;

            if ($floating) {
                $indent .= $this->getWhitespace(4);
            }

            $lf1Indent = $indent . $this->getWhitespace(4);
            $lf2Indent = $lf1Indent . $this->getWhitespace(4);
            $lf3Indent = $lf2Indent . $this->getWhitespace(4);

            $asCard        = $element->getOption('as-card');
            $asFormControl = $element->getOption('as-form-control');

            if ($this->renderErrors) {
                $errorContent = $this->renderFormErrors(
                    $element,
                    $asCard || $asFormControl ? $lf3Indent : $lf1Indent,
                );
            }

            if ($element->getOption('messages')) {
                $messageContent = $this->renderMessages(
                    $element,
                    $asCard || $asFormControl ? $lf3Indent : $lf1Indent,
                );
            }

            if ($element->getOption('help_content') !== null) {
                $helpContent = $this->renderFormHelp(
                    $element,
                    $floating ? $indent : $lf1Indent,
                    $colAttributes,
                );
            }

            if ($elementHelper instanceof FormIndentInterface) {
                $elementHelper->setIndent($element->getOption('as-card') ? $lf3Indent : $lf2Indent);
            }

            $elementString  = $elementHelper->render($element);
            $elementString .= $errorContent . $messageContent;

            $elementString = $this->wrapInContainer(
                element: $element,
                elementString: $elementString,
                htmlHelper: $htmlHelper,
                indent: $lf1Indent,
            );

            if ($floating) {
                $elementString = PHP_EOL . $lf1Indent . $elementString . PHP_EOL . '    ' . $legend . PHP_EOL . $indent;

                $elementString  = $indent . $htmlHelper->render(
                    'div',
                    ['class' => 'form-floating flex-fill'],
                    $elementString,
                );
                $elementString .= $helpContent;
            } else {
                $elementString = $legend . PHP_EOL . $lf1Indent . $elementString . $helpContent;
            }

            return $baseIndent . $htmlHelper->render(
                'fieldset',
                $colAttributes,
                PHP_EOL . $elementString . PHP_EOL . $baseIndent,
            );
        }

        if ($element instanceof Checkbox) {
            // this is a special case, because label is always rendered inside it
            $errorContent   = '';
            $helpContent    = '';
            $messageContent = '';
            $baseIndent     = $indent;
            $lf1Indent      = $indent . $this->getWhitespace(4);
            $lf2Indent      = $lf1Indent . $this->getWhitespace(4);
            $lf3Indent      = $lf2Indent . $this->getWhitespace(4);

            $asCard        = $element->getOption('as-card');
            $asFormControl = $element->getOption('as-form-control');

            if ($this->renderErrors) {
                $errorContent = $this->renderFormErrors(
                    $element,
                    $asCard || $asFormControl ? $lf3Indent : $lf1Indent,
                );
            }

            if ($element->getOption('messages')) {
                $messageContent = $this->renderMessages(
                    $element,
                    $asCard || $asFormControl ? $lf3Indent : $lf1Indent,
                );
            }

            if ($element->getOption('help_content') !== null) {
                $helpContent = $this->renderFormHelp($element, $lf1Indent, $colAttributes);
            }

            if ($elementHelper instanceof FormIndentInterface) {
                $elementHelper->setIndent($element->getOption('as-card') ? $lf3Indent : $lf2Indent);
            }

            $elementString  = $elementHelper->render($element);
            $elementString .= $errorContent . $messageContent;

            $elementString = $this->wrapInContainer(
                element: $element,
                elementString: $elementString,
                htmlHelper: $htmlHelper,
                indent: $lf1Indent,
            );

            return $baseIndent . $htmlHelper->render(
                'div',
                $colAttributes,
                PHP_EOL . $lf1Indent . $elementString . $helpContent . PHP_EOL . $baseIndent,
            );
        }

        $type = $element->getAttribute('type');

        if (
            $element instanceof Button
            || $element instanceof Submit
            || $element instanceof Fieldset
            || in_array($type, ['button', 'submit', 'reset'], true)
        ) {
            // this is a special case, because label is always rendered inside it
            $baseIndent = $indent;
            $lf1Indent  = $indent . $this->getWhitespace(4);

            if ($elementHelper instanceof FormIndentInterface) {
                $elementHelper->setIndent($lf1Indent);
            }

            $elementString = $elementHelper->render($element);

            return $baseIndent . $htmlHelper->render(
                'div',
                $colAttributes,
                PHP_EOL . $elementString . PHP_EOL . $baseIndent,
            );
        }

        $floating   = $element->getOption('floating');
        $baseIndent = $indent;

        if ($floating) {
            $indent .= $this->getWhitespace(4);
        }

        $lf1Indent = $indent . $this->getWhitespace(4);
        $lf2Indent = $lf1Indent . $this->getWhitespace(4);

        $errorContent   = '';
        $helpContent    = '';
        $messageContent = '';

        if ($this->renderErrors) {
            $errorContent = $this->renderFormErrors($element, $floating ? $indent : $lf1Indent);
        }

        if ($element->getOption('messages')) {
            $messageContent = $this->renderMessages($element, $floating ? $indent : $lf1Indent);
        }

        if ($element->getOption('help_content') !== null) {
            $helpContent = $this->renderFormHelp(
                $element,
                $floating ? $indent : $lf1Indent,
                $colAttributes,
            );
        }

        if ($elementHelper instanceof FormIndentInterface) {
            $elementHelper->setIndent($element->getOption('in-group') ? $lf2Indent : $lf1Indent);
        }

        $elementString = $elementHelper->render($element);

        if ($label === '') {
            $rendered = $elementString . $errorContent . $messageContent;
        } else {
            if ($element instanceof LabelAwareInterface) {
                if ($floating) {
                    $labelPosition = BaseFormRow::LABEL_APPEND;
                } elseif ($element->hasLabelOption('label_position')) {
                    $labelPosition = $element->getLabelOption('label_position');
                } else {
                    $labelPosition = BaseFormRow::LABEL_PREPEND;
                }
            }

            $labelHelper = $this->getLabelHelper();

            $legend = $labelHelper->openTag($labelAttributes) . $label . $labelHelper->closeTag();

            if ($labelPosition === BaseFormRow::LABEL_PREPEND) {
                $elementString .= $errorContent . $messageContent;
                $elementString  = $this->wrapInGroup(
                    element: $element,
                    elementString: $elementString,
                    htmlHelper: $htmlHelper,
                    indent: $lf1Indent,
                );

                $rendered = $lf1Indent . $legend . PHP_EOL . $elementString;
            } else {
                if (!$floating) {
                    $elementString .= $errorContent . $messageContent;
                    $elementString  = $this->wrapInGroup(
                        element: $element,
                        elementString: $elementString,
                        htmlHelper: $htmlHelper,
                        indent: $indent,
                    );
                }

                $rendered  = $elementString . PHP_EOL;
                $rendered .= $element->getOption('in-group') ? $lf2Indent : $lf1Indent;
                $rendered .= $legend;
            }
        }

        if ($floating) {
            $rendered  = PHP_EOL . $rendered . PHP_EOL;
            $rendered .= $element->getOption('in-group') ? $lf1Indent : $indent;
            $rendered  = $htmlHelper->render(
                'div',
                ['class' => 'form-floating flex-fill'],
                $rendered,
            );

            $rendered .= $errorContent . $messageContent;
            $rendered  = ($element->getOption('in-group') ? $lf1Indent : $indent) . $rendered;
            $rendered  = $this->wrapInGroup(
                element: $element,
                elementString: $rendered,
                htmlHelper: $htmlHelper,
                indent: $indent,
            );
        }

        $rendered .= $helpContent;

        return $baseIndent . $htmlHelper->render(
            'div',
            $colAttributes,
            PHP_EOL . $rendered . PHP_EOL . $baseIndent,
        );
    }

    /** @throws DomainException */
    private function renderFormErrors(ElementInterface $element, string $indent): string
    {
        $elementErrorsHelper = $this->getElementErrorsHelper();
        assert($elementErrorsHelper instanceof FormElementErrors);

        $elementErrorsHelper->setIndent($indent);
        $elementErrors = $elementErrorsHelper->render($element);

        if ($elementErrors !== '' && $element->hasAttribute('id')) {
            $ariaDesc = $element->hasAttribute('aria-describedby')
                ? $element->getAttribute('aria-describedby') . ' '
                : '';

            $ariaDesc .= $element->getAttribute('id') . 'Feedback';

            $element->setAttribute('aria-describedby', $ariaDesc);
        }

        return $elementErrors;
    }

    /**
     * @param array<string, array<mixed>|bool|float|int|string> $containerAttributes
     *
     * @throws void
     */
    private function renderFormHelp(ElementInterface $element, string $indent, array &$containerAttributes): string
    {
        $helpContent = $element->getOption('help_content');

        if (!is_string($helpContent) && !is_array($helpContent)) {
            return '';
        }

        if (is_string($helpContent) && $helpContent === '') {
            return '';
        }

        if (
            is_array($helpContent)
            && (
                !array_key_exists('content', $helpContent)
                || !is_string($helpContent['content'])
                || $helpContent['content'] === ''
            )
        ) {
            return '';
        }

        $classes = [];

        if (
            array_key_exists('class', $containerAttributes)
            && is_scalar($containerAttributes['class'])
        ) {
            $classes = explode(' ', (string) $containerAttributes['class']);

            unset($containerAttributes['class']);
        }

        $classes[] = 'has-help';

        $containerAttributes['class'] = implode(' ', array_unique($classes));

        $attributes = $this->mergeAttributes($element, 'help_attributes', ['toast']);

        assert(is_string($helpContent) || is_array($helpContent));

        if ($element->hasAttribute('id')) {
            $attributes['id'] = $element->getAttribute('id') . 'Help';

            $ariaDesc = $element->hasAttribute('aria-describedby')
                ? $element->getAttribute('aria-describedby') . ' '
                : '';

            $ariaDesc .= $element->getAttribute('id') . 'Help';

            $element->setAttribute('aria-describedby', $ariaDesc);
        }

        $htmlHelper = $this->getHtmlHelper();

        if (is_string($helpContent)) {
            return PHP_EOL . $indent . $htmlHelper->render('div', $attributes, $helpContent);
        }

        $lf1Indent = $indent . $this->getWhitespace(4);

        $content = $htmlHelper->render('div', ['class' => 'toast-body'], $helpContent['content']);
        $header  = '';

        if (
            array_key_exists('header', $helpContent)
            && is_string($helpContent['header'])
            && $helpContent['header'] !== ''
        ) {
            $header = $htmlHelper->render('div', ['class' => 'toast-header'], $helpContent['header']);
            $header = $lf1Indent . $header . PHP_EOL;
        }

        $content = $htmlHelper->render(
            'div',
            $attributes,
            PHP_EOL . $header . $lf1Indent . $content . PHP_EOL . $indent,
        );

        return PHP_EOL . $indent . $content;
    }

    /** @throws void */
    private function renderMessages(ElementInterface $element, string $indent): string
    {
        $messages = $element->getOption('messages');

        if (!is_array($messages)) {
            return '';
        }

        $messageContent = '';
        $htmlHelper     = $this->getHtmlHelper();

        foreach ($messages as $message) {
            assert(is_array($message));

            $content = $message['content'] ?? '';

            if ($content === '') {
                continue;
            }

            $attributes = $message['attributes'] ?? [];

            if (array_key_exists('id', $attributes)) {
                $ariaDesc = $element->hasAttribute('aria-describedby')
                    ? $element->getAttribute('aria-describedby') . ' '
                    : '';

                $ariaDesc .= $attributes['id'];

                $element->setAttribute('aria-describedby', $ariaDesc);
            }

            $messageContent .= PHP_EOL . $indent . $htmlHelper->render('div', $attributes, $content);
        }

        return $messageContent;
    }

    /**
     * @param array<int|string, array{content?: string, attributes?: array<int|string, bool|float|int|iterable<int, string>|stdClass|string|null>}> $messages
     *
     * @throws void
     */
    private function renderGroupContent(array $messages, string $indent): string
    {
        $messageContents = [];
        $htmlHelper      = $this->getHtmlHelper();

        foreach ($messages as $message) {
            assert(is_array($message));

            $content = $message['content'] ?? '';

            if ($content === '') {
                continue;
            }

            $attributes = $message['attributes'] ?? [];

            $messageContents[] = $htmlHelper->render('div', $attributes, $content);
        }

        return PHP_EOL . $indent . implode(PHP_EOL . $indent, $messageContents);
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

    /** @throws void */
    private function wrapInContainer(
        ElementInterface $element,
        string $elementString,
        FormHtmlInterface $htmlHelper,
        string $indent,
    ): string {
        $asCard        = $element->getOption('as-card');
        $asFormControl = $element->getOption('as-form-control');

        if ($asCard || $asFormControl) {
            if ($asCard) {
                $controlClasses = ['card', 'has-validation'];

                $lf1Indent = $indent . $this->getWhitespace(4);

                $elementString = $lf1Indent . $htmlHelper->render(
                    'div',
                    ['class' => 'card-body'],
                    PHP_EOL . $elementString . PHP_EOL . $lf1Indent,
                );
            } else {
                $controlClasses = ['form-control', 'has-validation'];
            }

            if ($element->getAttribute('required')) {
                $controlClasses[] = 'required';
            }

            $elementString = $htmlHelper->render(
                'div',
                ['class' => implode(' ', $controlClasses)],
                PHP_EOL . $elementString . PHP_EOL . $indent,
            );
        }

        return $elementString;
    }

    /** @throws void */
    private function wrapInGroup(
        ElementInterface $element,
        string $elementString,
        FormHtmlInterface $htmlHelper,
        string $indent,
    ): string {
        $inGroup = $element->getOption('in-group');

        if ($inGroup) {
            $prefixes = $element->getOption('group-prefixes');
            $suffixes = $element->getOption('group-suffixes');

            $lf1Indent = $indent . $this->getWhitespace(4);

            $elementString = PHP_EOL . $elementString;

            if (is_array($prefixes)) {
                $prefixContent = $this->renderGroupContent($prefixes, $lf1Indent);

                $elementString = $prefixContent . $elementString;
            }

            if (is_array($suffixes)) {
                $suffixContent = $this->renderGroupContent($suffixes, $lf1Indent);

                $elementString .= $suffixContent;
            }

            $controlClasses = ['input-group', 'has-validation'];

            if ($element->getAttribute('required')) {
                $controlClasses[] = 'required';
            }

            $elementString = $htmlHelper->render(
                'div',
                ['class' => implode(' ', $controlClasses)],
                $elementString . PHP_EOL . $indent,
            );

            $elementString = $indent . $elementString;
        }

        return $elementString;
    }
}
