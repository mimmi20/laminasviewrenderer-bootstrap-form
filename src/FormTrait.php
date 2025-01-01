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

use Override;

use function is_int;
use function str_repeat;

trait FormTrait
{
    private string $indent = '';

    /**
     * Set the indentation string for using in {@link render()}, optionally a
     * number of spaces to indent with
     *
     * @throws void
     */
    #[Override]
    public function setIndent(int | string $indent): self
    {
        $this->indent = $this->getWhitespace($indent);

        return $this;
    }

    /**
     * Returns indentation
     *
     * @throws void
     */
    #[Override]
    public function getIndent(): string
    {
        return $this->indent;
    }

    // Util methods:

    /**
     * Retrieve whitespace representation of $indent
     *
     * @throws void
     */
    protected function getWhitespace(int | string $indent): string
    {
        if (is_int($indent)) {
            $indent = str_repeat(' ', $indent);
        }

        return $indent;
    }
}
