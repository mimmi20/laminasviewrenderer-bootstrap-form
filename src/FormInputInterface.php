<?php
/**
 * This file is part of the mimmi20/laminasviewrenderer-bootstrap-form package.
 *
 * Copyright (c) 2021, Thomas Mueller <mimmi20@live.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Mimmi20\LaminasView\BootstrapForm;

interface FormInputInterface extends FormIndentInterface, FormRenderInterface
{
    /**
     * Get the closing bracket for an inline tag
     *
     * Closes as either "/>" for XHTML doctypes or ">" otherwise.
     */
    public function getInlineClosingBracket(): string;
}
