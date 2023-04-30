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

/**
 * FormSearch view helper
 *
 * The difference between the Text state and the Search state is primarily stylistic:
 * on platforms where search fields are distinguished from regular text fields,
 * the Search state might result in an appearance consistent with the platform's
 * search fields rather than appearing like a regular text field.
 */
final class FormSearch extends FormInput
{
    /**
     * Attributes valid for the input tag type="text"
     *
     * @var array<string, bool>
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint
     * @psalm-suppress NonInvariantDocblockPropertyType
     */
    protected $validTagAttributes = [
        'name' => true,
        'autocomplete' => true,
        'autofocus' => true,
        'dirname' => true,
        'disabled' => true,
        'form' => true,
        'list' => true,
        'maxlength' => true,
        'minlength' => true,
        'pattern' => true,
        'placeholder' => true,
        'readonly' => true,
        'required' => true,
        'size' => true,
        'type' => true,
        'value' => true,
    ];

    /**
     * Determine input type to use
     *
     * @throws void
     *
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
     * @psalm-suppress ReservedWord
     */
    protected function getType(ElementInterface $element): string
    {
        return 'search';
    }
}
