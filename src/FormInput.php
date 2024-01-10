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

use Laminas\Form\Element\Submit;
use Laminas\Form\ElementInterface;
use Laminas\Form\Exception\DomainException;
use Laminas\Form\View\Helper\FormInput as BaseFormInput;
use Laminas\View\Helper\Doctype;
use Laminas\View\Helper\EscapeHtml;
use Laminas\View\Helper\EscapeHtmlAttr;

use function array_key_exists;
use function array_merge;
use function array_unique;
use function explode;
use function implode;
use function in_array;
use function is_scalar;
use function sprintf;
use function trim;

/** @SuppressWarnings(PHPMD.NumberOfChildren) */
abstract class FormInput extends BaseFormInput implements FormInputInterface
{
    use FormTrait;

    /**
     * Render a form <input> element from the provided $element
     *
     * @throws DomainException
     */
    public function render(ElementInterface $element): string
    {
        $name = $element->getName();

        if ($name === null || $name === '') {
            throw new DomainException(
                sprintf(
                    '%s requires that the element has an assigned name; none discovered',
                    __METHOD__,
                ),
            );
        }

        $attributes = $element->getAttributes();

        $attributes['name'] = $name;
        $type               = $this->getType($element);
        $attributes['type'] = $type;

        $attributes['value'] = $type === 'password' ? '' : $element->getValue();

        if ($element instanceof Submit || in_array($type, ['submit', 'reset', 'button'], true)) {
            $classes = ['btn'];

            if (array_key_exists('class', $attributes) && is_scalar($attributes['class'])) {
                $classes = array_merge($classes, explode(' ', (string) $attributes['class']));
            }
        } elseif (isset($attributes['readonly']) && $element->getOption('plain')) {
            $classes = ['form-control-plaintext'];
        } else {
            $classes = ['form-control'];

            if (array_key_exists('class', $attributes) && is_scalar($attributes['class'])) {
                $classes = array_merge($classes, explode(' ', (string) $attributes['class']));
            }
        }

        $attributes['class'] = trim(implode(' ', array_unique($classes)));

        $attributesString = $this->createAttributesString($attributes);

        if (!empty($attributesString)) {
            $attributesString = ' ' . $attributesString;
        }

        $markup = sprintf(
            '<input%s%s',
            $attributesString,
            $this->getInlineClosingBracket(),
        );

        $indent = $this->getIndent();

        return $indent . $markup;
    }
}
