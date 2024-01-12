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

use Laminas\Form\Element\Collection as CollectionElement;
use Laminas\Form\ElementInterface;
use Laminas\Form\Exception\DomainException;
use Laminas\Form\FieldsetInterface;
use Laminas\Form\FormInterface;
use Laminas\Form\LabelAwareInterface;
use Laminas\Form\View\Helper\FormCollection as BaseFormCollection;
use Laminas\View\Exception\InvalidArgumentException;
use Laminas\View\Exception\RuntimeException;
use Laminas\View\Helper\HelperInterface;

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

use const PHP_EOL;

final class FormCollection extends BaseFormCollection implements FormCollectionInterface
{
    use FormTrait;
    use HtmlHelperTrait;

    /**
     * Render a collection by iterating through all fieldsets and elements
     *
     * @throws DomainException
     * @throws RuntimeException
     * @throws InvalidArgumentException
     * @throws \Laminas\Form\Exception\InvalidArgumentException
     */
    public function render(ElementInterface $element): string
    {
        if (!$element instanceof FieldsetInterface) {
            throw new \Laminas\Form\Exception\InvalidArgumentException(
                sprintf(
                    '%s requires that the element is of type %s, but was %s',
                    __METHOD__,
                    FieldsetInterface::class,
                    get_debug_type($element),
                ),
            );
        }

        $markup         = '';
        $templateMarkup = '';
        $indent         = $this->getIndent();
        $baseIndent     = $indent;
        $asCard         = $element->getOption('as-card');

        if ($this->shouldWrap && $asCard) {
            $indent .= $this->getWhitespace(8);
        }

        if ($element instanceof CollectionElement && $element->shouldCreateTemplate()) {
            $templateMarkup = $this->renderTemplate($element, $indent);
        }

        $form     = $element->getOption('form');
        $layout   = $element->getOption('layout');
        $floating = $element->getOption('floating');

        try {
            $elementHelper = $this->getElementHelper();
        } catch (\RuntimeException $e) {
            throw new RuntimeException($e->getMessage(), 0, $e);
        }

        $fieldsetHelper = $this->getFieldsetHelper();

        foreach ($element->getIterator() as $elementOrFieldset) {
            assert($elementOrFieldset instanceof ElementInterface);

            if ($form !== null && !$elementOrFieldset->getOption('form')) {
                $elementOrFieldset->setOption('form', $form);
            }

            if ($layout !== null && !$elementOrFieldset->getOption('layout')) {
                $elementOrFieldset->setOption('layout', $layout);
            }

            if ($floating) {
                $elementOrFieldset->setOption('floating', true);
            }

            if (
                $element->getOption('show-required-mark')
                && $element->getOption('field-required-mark')
            ) {
                $elementOrFieldset->setOption('show-required-mark', true);
                $elementOrFieldset->setOption(
                    'field-required-mark',
                    $element->getOption('field-required-mark'),
                );
            }

            if ($elementOrFieldset instanceof FieldsetInterface) {
                if ($fieldsetHelper instanceof FormIndentInterface) {
                    $fieldsetHelper->setIndent($indent . $this->getWhitespace(4));
                }

                if ($fieldsetHelper instanceof BaseFormCollection) {
                    $fieldsetHelper->setShouldWrap($this->shouldWrap());

                    $markup .= $fieldsetHelper->render($elementOrFieldset) . PHP_EOL;
                }
            } else {
                $elementOrFieldset->setOption('fieldset', $element);

                if ($elementHelper instanceof FormIndentInterface) {
                    $elementHelper->setIndent($indent . $this->getWhitespace(4));
                }

                if ($elementHelper instanceof FormRenderInterface) {
                    $markup .= $elementHelper->render($elementOrFieldset) . PHP_EOL;
                }
            }
        }

        if (!$this->shouldWrap) {
            return $markup . $templateMarkup;
        }

        // Every collection is wrapped by a fieldset if needed
        $attributes = $element->getAttributes();

        unset($attributes['name']);

        $label      = $element->getLabel() ?? '';
        $legend     = '';
        $htmlHelper = $this->getHtmlHelper();

        if ($label !== '') {
            $translator = $this->getTranslator();

            if ($translator !== null) {
                $label = $translator->translate(
                    $label,
                    $this->getTranslatorTextDomain(),
                );
            }

            if (
                !$element instanceof LabelAwareInterface
                || !$element->getLabelOption('disable_html_escape')
            ) {
                $escapeHtmlHelper = $this->getEscapeHtmlHelper();
                $label            = $escapeHtmlHelper($label);
            }

            assert(is_string($label));

            if (
                $label !== '' && !$element->hasAttribute('id')
                || ($element instanceof LabelAwareInterface && $element->getLabelOption('always_wrap'))
            ) {
                $label = '<span>' . $label . '</span>';
            }

            $labelClasses    = [];
            $labelAttributes = $element->getOption('label_attributes') ?? [];

            assert(is_array($labelAttributes));

            if (array_key_exists('class', $labelAttributes)) {
                $labelClasses = explode(' ', (string) $labelAttributes['class']);
            }

            $labelAttributes['class'] = trim(implode(' ', array_unique($labelClasses)));

            if ($asCard) {
                $labelAttributes = $this->mergeFormAttributes(
                    $element,
                    'col_attributes',
                    ['card-title'],
                    $labelAttributes,
                );
            }

            $legend = PHP_EOL . $indent . $this->getWhitespace(4) . $htmlHelper->render(
                'legend',
                $labelAttributes,
                $label,
            );
        }

        if ($asCard) {
            $classes = ['card-body'];

            if (array_key_exists('class', $attributes) && is_string($attributes['class'])) {
                $classes = array_merge(
                    $classes,
                    explode(' ', $attributes['class']),
                );
            }

            $attributes['class'] = trim(implode(' ', array_unique($classes)));
        }

        $markup = $baseIndent . $htmlHelper->render(
            'fieldset',
            $attributes,
            $legend . PHP_EOL . $markup . $templateMarkup . $indent,
        );

        if ($asCard) {
            $markup = PHP_EOL . $baseIndent . $this->getWhitespace(4) . $htmlHelper->render(
                'div',
                $this->mergeAttributes($element, 'card_attributes', ['card']),
                PHP_EOL . $baseIndent . $this->getWhitespace(
                    4,
                ) . $markup . PHP_EOL . $baseIndent . $this->getWhitespace(
                    4,
                ),
            );

            $markup = $baseIndent . $htmlHelper->render(
                'div',
                $this->mergeAttributes($element, 'col_attributes', []),
                $markup . PHP_EOL . $baseIndent,
            );
        }

        return $markup;
    }

    /**
     * Only render a template
     *
     * @throws DomainException
     * @throws RuntimeException
     */
    public function renderTemplate(CollectionElement $collection, string $indent = ''): string
    {
        $elementOrFieldset = $collection->getTemplateElement();

        if (!$elementOrFieldset instanceof ElementInterface) {
            return '';
        }

        $templateMarkup = '';

        if ($elementOrFieldset instanceof FieldsetInterface) {
            $fieldsetHelper = $this->getFieldsetHelper();
            assert($fieldsetHelper instanceof HelperInterface);

            if ($fieldsetHelper instanceof FormIndentInterface) {
                $fieldsetHelper->setIndent($indent . $this->getWhitespace(4));
            }

            if ($fieldsetHelper instanceof BaseFormCollection) {
                $fieldsetHelper->setShouldWrap($this->shouldWrap());

                $templateMarkup = $fieldsetHelper->render($elementOrFieldset) . PHP_EOL;
            }
        } else {
            try {
                $elementHelper = $this->getElementHelper();
            } catch (\RuntimeException $e) {
                throw new RuntimeException($e->getMessage(), 0, $e);
            }

            assert($elementHelper instanceof HelperInterface);

            if ($elementHelper instanceof FormIndentInterface) {
                $elementHelper->setIndent($indent . $this->getWhitespace(4));
            }

            if ($elementHelper instanceof FormRenderInterface) {
                $templateMarkup = $elementHelper->render($elementOrFieldset) . PHP_EOL;
            }
        }

        $templateAttrbutes = $collection->getOption('template_attributes') ?? [];

        assert(is_array($templateAttrbutes));

        $htmlHelper = $this->getHtmlHelper();

        return $indent . $this->getWhitespace(4) . $htmlHelper->render(
            'template',
            $templateAttrbutes,
            $templateMarkup . $indent,
        ) . PHP_EOL;
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

        if ($classes) {
            $attributes['class'] = implode(' ', array_unique($classes));
        }

        return $attributes;
    }

    /**
     * @param array<int, string>    $classes
     * @param array<string, string> $attributes
     *
     * @return array<string, string>
     *
     * @throws void
     */
    private function mergeFormAttributes(
        ElementInterface $element,
        string $optionName,
        array $classes = [],
        array $attributes = [],
    ): array {
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
                $classes = array_merge(explode(' ', (string) $formAttributes['class']), $classes);

                unset($formAttributes['class']);
            }

            $attributes = [...$formAttributes, ...$attributes];
        }

        if ($classes) {
            $attributes['class'] = implode(' ', array_unique($classes));
        }

        return $attributes;
    }
}
