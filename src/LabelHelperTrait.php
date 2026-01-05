<?php

/**
 * This file is part of the mimmi20/laminasviewrenderer-bootstrap-form package.
 *
 * Copyright (c) 2021-2026, Thomas Mueller <mimmi20@live.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Mimmi20\LaminasView\BootstrapForm;

use Laminas\View\Renderer\RendererInterface;

use function method_exists;

trait LabelHelperTrait
{
    /**
     * Form label helper instance
     */
    private FormLabel | null $labelHelper = null;

    /**
     * Retrieve the FormLabel helper
     *
     * @throws void
     */
    private function getLabelHelper(): FormLabel
    {
        if ($this->labelHelper) {
            return $this->labelHelper;
        }

        if ($this->view instanceof RendererInterface && method_exists($this->view, 'plugin')) {
            $this->labelHelper = $this->view->plugin('form_label');
        }

        if (!$this->labelHelper instanceof FormLabel) {
            $this->labelHelper = new FormLabel();
        }

        return $this->labelHelper;
    }
}
