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

use Laminas\Form\ElementInterface;
use Laminas\Form\Exception\DomainException;
use Override;

use function sprintf;

final class FormHidden extends AbstractFormInput implements FormHiddenInterface
{
    /**
     * Attributes valid for the input tag type="hidden"
     *
     * @var array<string, bool>
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint
     */
    protected $validTagAttributes = [
        'name' => true,
        'disabled' => true,
        'form' => true,
        'type' => true,
        'value' => true,
        'autocomplete' => true,
    ];

    /**
     * Render a form <input> element from the provided $element
     *
     * @throws DomainException
     */
    #[Override]
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

        $attributes['name']  = $name;
        $type                = $this->getType($element);
        $attributes['type']  = $type;
        $attributes['value'] = $element->getValue();

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

    /**
     * Determine input type to use
     *
     * @throws void
     *
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
     */
    #[Override]
    protected function getType(ElementInterface $element): string
    {
        return 'hidden';
    }
}
