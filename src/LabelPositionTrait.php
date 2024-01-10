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

use Laminas\Form\Exception\InvalidArgumentException;
use Laminas\Form\View\Helper\FormRow as BaseFormRow;

use function in_array;
use function mb_strtolower;
use function sprintf;

trait LabelPositionTrait
{
    /**
     * Where will be label rendered?
     */
    private string $labelPosition = BaseFormRow::LABEL_APPEND;

    /**
     * Set value for labelPosition
     *
     * @throws InvalidArgumentException
     */
    public function setLabelPosition(string $labelPosition): self
    {
        $labelPosition = mb_strtolower($labelPosition);

        if (!in_array($labelPosition, [BaseFormRow::LABEL_APPEND, BaseFormRow::LABEL_PREPEND], true)) {
            throw new InvalidArgumentException(
                sprintf(
                    '%s expects either %s::LABEL_APPEND or %s::LABEL_PREPEND; received "%s"',
                    __METHOD__,
                    BaseFormRow::class,
                    BaseFormRow::class,
                    $labelPosition,
                ),
            );
        }

        $this->labelPosition = $labelPosition;

        return $this;
    }

    /**
     * Get position of label
     *
     * @throws void
     */
    public function getLabelPosition(): string
    {
        return $this->labelPosition;
    }
}
