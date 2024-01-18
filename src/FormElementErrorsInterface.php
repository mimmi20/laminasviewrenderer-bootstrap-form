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

use Laminas\Form\ElementInterface;
use Laminas\Form\Exception\DomainException;

interface FormElementErrorsInterface extends FormIndentInterface
{
    /**
     * Invoke helper as functor
     *
     * Proxies to {@link render()} if an element is passed.
     *
     * @param array<string, string> $attributes
     *
     * @return self|string
     *
     * @throws DomainException
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingNativeTypeHint
     */
    public function __invoke(ElementInterface | null $element = null, array $attributes = []);

    /**
     * Render validation errors for the provided $element
     *
     * If a translator is
     * composed, messages retrieved from the element will be translated; if
     * either is not the case, they will not.
     *
     * @param array<string, string> $attributes
     *
     * @throws DomainException
     */
    public function render(ElementInterface $element, array $attributes = []): string;

    /**
     * Set the attributes that will go on the message open format
     *
     * @param array<string, string> $attributes key value pairs of attributes
     *
     * @throws void
     */
    public function setAttributes(array $attributes): self;

    /**
     * Get the attributes that will go on the message open format
     *
     * @return array<string, string>
     *
     * @throws void
     */
    public function getAttributes(): array;
}
