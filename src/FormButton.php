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

use Laminas\Form\ElementInterface;
use Laminas\Form\Exception;
use Laminas\Form\LabelAwareInterface;
use Laminas\I18n\Exception\RuntimeException;
use Laminas\I18n\View\Helper\Translate;
use Laminas\View\Exception\InvalidArgumentException;
use Laminas\View\Helper\Doctype;
use Laminas\View\Helper\EscapeHtml;
use Laminas\View\Helper\EscapeHtmlAttr;

use function assert;
use function is_array;
use function is_string;
use function mb_strtolower;
use function sprintf;

final class FormButton extends FormInput
{
    /**
     * Attributes valid for the button tag
     *
     * @var array<string, bool>
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint
     */
    protected $validTagAttributes = [
        'name' => true,
        'autofocus' => true,
        'disabled' => true,
        'form' => true,
        'formaction' => true,
        'formenctype' => true,
        'formmethod' => true,
        'formnovalidate' => true,
        'formtarget' => true,
        'type' => true,
        'value' => true,
    ];

    /**
     * Valid values for the button type
     *
     * @var array<string, bool>
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint
     */
    protected $validTypes = [
        'button' => true,
        'reset' => true,
        'submit' => true,
    ];

    /** @throws void */
    public function __construct(
        EscapeHtml $escapeHtml,
        EscapeHtmlAttr $escapeHtmlAttr,
        Doctype $doctype,
        private readonly Translate | null $translate = null,
    ) {
        parent::__construct($escapeHtml, $escapeHtmlAttr, $doctype);
    }

    /**
     * Invoke helper as functor
     *
     * Proxies to {@link render()}.
     *
     * @throws Exception\DomainException
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function __invoke(
        ElementInterface | null $element = null,
        string | null $buttonContent = null,
    ): self | string {
        if (!$element) {
            return $this;
        }

        return $this->render($element, $buttonContent);
    }

    /**
     * Render a form <button> element from the provided $element,
     * using content from $buttonContent or the element's "label" attribute
     *
     * @throws Exception\DomainException
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function render(ElementInterface $element, string | null $buttonContent = null): string
    {
        $openTag = $this->openTag($element);

        if ($buttonContent === null) {
            $buttonContent = $element->getLabel();

            if ($buttonContent === null) {
                throw new Exception\DomainException(
                    sprintf(
                        '%s expects either button content as the second argument, or that the element provided has a label value; neither found',
                        __METHOD__,
                    ),
                );
            }
        }

        if ($this->translate !== null) {
            $buttonContent = ($this->translate)(
                $buttonContent,
                $this->getTranslatorTextDomain(),
            );
        }

        if (
            !$element instanceof LabelAwareInterface
            || !$element->getLabelOption('disable_html_escape')
        ) {
            $buttonContent = ($this->escapeHtml)($buttonContent);
        }

        $indent = $this->getIndent();

        return $indent . $openTag . $buttonContent . $this->closeTag();
    }

    /**
     * Generate an opening button tag
     *
     * @param array<string, bool|string>|ElementInterface|null $attributesOrElement
     *
     * @throws Exception\DomainException
     */
    public function openTag(array | ElementInterface | null $attributesOrElement = null): string
    {
        if ($attributesOrElement === null) {
            return '<button>';
        }

        if (is_array($attributesOrElement)) {
            $attributes = $this->createAttributesString($attributesOrElement);

            return sprintf('<button %s>', $attributes);
        }

        $element = $attributesOrElement;
        $name    = $element->getName();

        if (empty($name)) {
            throw new Exception\DomainException(
                sprintf(
                    '%s requires that the element has an assigned name; none discovered',
                    __METHOD__,
                ),
            );
        }

        $attributes = $element->getAttributes();

        $attributes['name'] = $name;
        $attributes['type'] = $this->getType($element);

        $value = $element->getValue();

        if ($value) {
            $attributes['value'] = $value;
        }

        return sprintf(
            '<button %s>',
            $this->createAttributesString($attributes),
        );
    }

    /**
     * Return a closing button tag
     *
     * @throws void
     */
    public function closeTag(): string
    {
        return '</button>';
    }

    /**
     * Determine button type to use
     *
     * @throws void
     */
    protected function getType(ElementInterface $element): string
    {
        $type = $element->getAttribute('type');

        if (empty($type)) {
            return 'submit';
        }

        assert(is_string($type));

        $type = mb_strtolower($type);

        if (!isset($this->validTypes[$type])) {
            return 'submit';
        }

        return $type;
    }
}
