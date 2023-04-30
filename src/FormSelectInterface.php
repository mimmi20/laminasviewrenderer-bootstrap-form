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

/** @psalm-suppress ReservedWord */
interface FormSelectInterface extends FormIndentInterface
{
    /**
     * Invoke helper as functor
     *
     * Proxies to {@link render()}.
     *
     * @return self|string
     *
     * @throws Exception\InvalidArgumentException
     * @throws Exception\DomainException
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingNativeTypeHint
     */
    public function __invoke(ElementInterface | null $element = null);

    /**
     * Render a form <select> element from the provided $element
     *
     * @throws Exception\InvalidArgumentException
     * @throws Exception\DomainException
     */
    public function render(ElementInterface $element): string;

    /**
     * Render an array of options
     *
     * Individual options should be of the form:
     *
     * <code>
     * array(
     *     'value'    => 'value',
     *     'label'    => 'label',
     *     'disabled' => $booleanFlag,
     *     'selected' => $booleanFlag,
     * )
     * </code>
     *
     * @param array<int|string, array<string, string>|string> $options
     * @param array<int|string, string>                       $selectedOptions Option values that should be marked as selected
     * @phpstan-param array<int|string, array{options?: array<mixed>, value?: string, label?: string, selected?: bool, disabled?: bool, disable_html_escape?: bool, attributes?: array<string, string>}|string> $options
     *
     * @throws void
     */
    public function renderOptions(array $options, array $selectedOptions, int $level): string;

    /**
     * @param array<string, string>|string $optionSpec
     * @param array<int|string, string>    $selectedOptions
     * @phpstan-param array{options?: array<mixed>, value?: string, label?: string, selected?: bool, disabled?: bool, disable_html_escape?: bool, attributes?: array<string, string>}|string $optionSpec
     *
     * @throws void
     */
    public function renderOption(
        int | string $key,
        array | string $optionSpec,
        array $selectedOptions,
        int $level,
    ): string;

    /**
     * Render an optgroup
     *
     * See {@link renderOptions()} for the options specification. Basically,
     * an optgroup is simply an option that has an additional "options" key
     * with an array following the specification for renderOptions().
     *
     * @param array<string, int|string> $optgroup
     * @param array<int|string, string> $selectedOptions
     *
     * @throws void
     */
    public function renderOptgroup(array $optgroup, array $selectedOptions, int $level): string;
}
