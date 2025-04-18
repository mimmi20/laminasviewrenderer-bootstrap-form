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

final class FormImage extends AbstractFormInput implements FormRenderInterface
{
    /**
     * Attributes valid for the input tag type="image"
     *
     * @var array<string, bool>
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint
     */
    protected $validTagAttributes = [
        'name' => true,
        'alt' => true,
        'autofocus' => true,
        'disabled' => true,
        'form' => true,
        'formaction' => true,
        'formenctype' => true,
        'formmethod' => true,
        'formnovalidate' => true,
        'formtarget' => true,
        'height' => true,
        'src' => true,
        'type' => true,
        'width' => true,
    ];

    /**
     * Render a form <input> element from the provided $element
     *
     * @throws DomainException
     */
    #[Override]
    public function render(ElementInterface $element): string
    {
        $src = $element->getAttribute('src');

        if (empty($src)) {
            throw new DomainException(
                sprintf(
                    '%s requires that the element has an assigned src; none discovered',
                    __METHOD__,
                ),
            );
        }

        return parent::render($element);
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
        return 'image';
    }
}
