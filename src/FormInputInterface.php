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

use Laminas\View\Helper\HelperInterface;

interface FormInputInterface extends FormIndentInterface, FormRenderInterface, HelperInterface
{
    /**
     * Get the closing bracket for an inline tag
     *
     * Closes as either "/>" for XHTML doctypes or ">" otherwise.
     *
     * @throws void
     */
    public function getInlineClosingBracket(): string;
}
