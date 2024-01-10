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
use Laminas\Form\Exception\InvalidArgumentException;

interface FormLabelInterface
{
    public const APPEND = 'append';

    public const PREPEND = 'prepend';

    /**
     * Generate a form label, optionally with content
     *
     * Always generates a "for" statement, as we cannot assume the form input
     * will be provided in the $labelContent.
     *
     * @throws DomainException
     * @throws InvalidArgumentException
     */
    public function __invoke(
        ElementInterface | null $element = null,
        string | null $labelContent = null,
        string | null $position = null,
    ): FormLabel | string;

    /**
     * Generate an opening label tag
     *
     * @param array<string, bool|string>|ElementInterface|null $attributesOrElement
     *
     * @throws InvalidArgumentException
     * @throws DomainException
     */
    public function openTag(array | ElementInterface | null $attributesOrElement = null): string;

    /**
     * Return a closing label tag
     *
     * @throws void
     */
    public function closeTag(): string;
}
