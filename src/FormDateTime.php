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

use Laminas\Form\ElementInterface;
use Override;

/** @deprecated This element is deprecated as it has been removed from WHATWG HTML, use {@see FormDateTimeLocal} instead */
final class FormDateTime extends AbstractFormInput
{
    /**
     * Attributes valid for the input tag type="datetime"
     *
     * @var array<string, bool>
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint
     */
    protected $validTagAttributes = [
        'name' => true,
        'autocomplete' => true,
        'autofocus' => true,
        'disabled' => true,
        'form' => true,
        'list' => true,
        'max' => true,
        'min' => true,
        'readonly' => true,
        'required' => true,
        'step' => true,
        'type' => true,
        'value' => true,
    ];

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
        return 'datetime';
    }
}
