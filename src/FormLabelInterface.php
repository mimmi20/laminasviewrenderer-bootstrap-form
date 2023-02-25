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
     * @return FormLabel|string
     *
     * @throws Exception\DomainException
     * @throws InvalidArgumentException
     */
    public function __invoke(
        ElementInterface | null $element = null,
        string | null $labelContent = null,
        string | null $position = null,
    );

    /**
     * Generate an opening label tag
     *
     * @param array<string, bool|string>|ElementInterface|null $attributesOrElement
     *
     * @throws Exception\InvalidArgumentException
     * @throws Exception\DomainException
     */
    public function openTag($attributesOrElement = null): string;

    /**
     * Return a closing label tag
     */
    public function closeTag(): string;
}
