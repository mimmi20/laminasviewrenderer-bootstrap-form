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

/**  */
trait UseHiddenElementTrait
{
    /**
     * Prefixing the element with a hidden element for the unset value?
     */
    private bool $useHiddenElement = false;

    /**
     * The unchecked value used when "UseHiddenElement" is turned on
     */
    private string $uncheckedValue = '';

    /**
     * Sets the option for prefixing the element with a hidden element
     * for the unset value.
     *
     * @throws void
     */
    public function setUseHiddenElement(bool $useHiddenElement): self
    {
        $this->useHiddenElement = $useHiddenElement;

        return $this;
    }

    /**
     * Returns the option for prefixing the element with a hidden element
     * for the unset value.
     *
     * @throws void
     */
    public function getUseHiddenElement(): bool
    {
        return $this->useHiddenElement;
    }

    /**
     * Sets the unchecked value used when "UseHiddenElement" is turned on.
     *
     * @throws void
     */
    public function setUncheckedValue(string $value): self
    {
        $this->uncheckedValue = $value;

        return $this;
    }

    /**
     * Returns the unchecked value used when "UseHiddenElement" is turned on.
     *
     * @throws void
     */
    public function getUncheckedValue(): string
    {
        return $this->uncheckedValue;
    }
}
