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

use function sprintf;

final class FormMultiCheckbox extends AbstractFormMultiCheckbox
{
    /**
     * Return input type
     *
     * @throws void
     */
    protected function getInputType(): string
    {
        return 'checkbox';
    }

    /**
     * Get element name
     *
     * @throws DomainException
     */
    protected static function getName(ElementInterface $element): string
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

        return $name . '[]';
    }
}
