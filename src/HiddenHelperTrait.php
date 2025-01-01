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

use Laminas\View\Renderer\RendererInterface;

use function method_exists;

trait HiddenHelperTrait
{
    /**
     * Form label helper instance
     */
    private FormHiddenInterface | null $hiddenHelper = null;

    /**
     * Retrieve the FormLabel helper
     *
     * @throws void
     */
    private function getHiddenHelper(): FormHiddenInterface
    {
        if ($this->hiddenHelper) {
            return $this->hiddenHelper;
        }

        if ($this->view instanceof RendererInterface && method_exists($this->view, 'plugin')) {
            $this->hiddenHelper = $this->view->plugin('form_hidden');
        }

        if (!$this->hiddenHelper instanceof FormHiddenInterface) {
            $this->hiddenHelper = new FormHidden();
        }

        return $this->hiddenHelper;
    }
}
