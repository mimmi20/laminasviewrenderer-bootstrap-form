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

use Laminas\Form\Element\Button as ButtonElement;
use Laminas\Form\Element\Submit as SubmitElement;
use Laminas\Form\ElementInterface;
use Laminas\Form\Exception\DomainException;
use Laminas\Form\Exception\InvalidArgumentException;

use function array_key_exists;
use function array_merge;
use function array_unique;
use function assert;
use function explode;
use function get_debug_type;
use function implode;
use function is_array;
use function is_scalar;
use function is_string;
use function mb_strtolower;
use function sprintf;
use function trim;

final class FormButton extends AbstractFormInput implements FormRenderInterface
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

    /**
     * Invoke helper as functor
     *
     * Proxies to {@link render()}.
     *
     * @throws DomainException
     * @throws InvalidArgumentException
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
     * @throws DomainException
     * @throws InvalidArgumentException
     */
    public function render(ElementInterface $element, string | null $buttonContent = null): string
    {
        if (!$element instanceof ButtonElement && !$element instanceof SubmitElement) {
            throw new InvalidArgumentException(
                sprintf(
                    '%s requires that the element is of type %s or of type %s, but was %s',
                    __METHOD__,
                    ButtonElement::class,
                    SubmitElement::class,
                    get_debug_type($element),
                ),
            );
        }

        if ($buttonContent === null) {
            $buttonContent = $element->getLabel();

            if ($buttonContent === null) {
                throw new DomainException(
                    sprintf(
                        '%s expects either button content as the second argument, or that the element provided has a label value; neither found',
                        __METHOD__,
                    ),
                );
            }
        }

        $buttonContent = $this->translateLabel($buttonContent);
        $buttonContent = $this->escapeLabel($element, $buttonContent);

        $indent = $this->getIndent();

        $openTag = $this->openTag($element);

        return $indent . $openTag . $buttonContent . $this->closeTag();
    }

    /**
     * Generate an opening button tag
     *
     * @param array<string, bool|string>|ElementInterface|null $attributesOrElement
     *
     * @throws DomainException
     */
    public function openTag(array | ElementInterface | null $attributesOrElement = null): string
    {
        if ($attributesOrElement === null) {
            return '<button>';
        }

        if (is_array($attributesOrElement)) {
            $classes = ['btn'];

            if (
                array_key_exists('class', $attributesOrElement)
                && is_scalar($attributesOrElement['class'])
            ) {
                $classes = array_merge($classes, explode(' ', (string) $attributesOrElement['class']));
            }

            $attributesOrElement['class'] = trim(implode(' ', array_unique($classes)));

            $attributes = $this->createAttributesString($attributesOrElement);

            return sprintf('<button %s>', $attributes);
        }

        $element = $attributesOrElement;
        $name    = $element->getName();

        if (empty($name)) {
            throw new DomainException(
                sprintf(
                    '%s requires that the element has an assigned name; none discovered',
                    __METHOD__,
                ),
            );
        }

        $attributes = $element->getAttributes();

        $attributes['name'] = $name;
        $attributes['type'] = $this->getType($element);

        $classes = ['btn'];

        if (array_key_exists('class', $attributes) && is_scalar($attributes['class'])) {
            $classes = array_merge($classes, explode(' ', (string) $attributes['class']));
        }

        $attributes['class'] = trim(implode(' ', array_unique($classes)));

        $value = $element->getValue();

        if ($value) {
            $attributes['value'] = $value;
        }

        $attributesString = $this->createAttributesString($attributes);

        if (!empty($attributesString)) {
            $attributesString = ' ' . $attributesString;
        }

        return sprintf('<button%s>', $attributesString);
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
