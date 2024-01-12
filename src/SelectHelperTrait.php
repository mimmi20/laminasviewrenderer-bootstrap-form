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

use Laminas\View\Renderer\RendererInterface;

use function method_exists;

trait SelectHelperTrait
{
    /**
     * Form label helper instance
     */
    private FormSelect | null $selectHelper = null;

    /**
     * Retrieve the FormLabel helper
     *
     * @throws void
     */
    private function getSelectHelper(): FormSelect
    {
        if ($this->selectHelper) {
            return $this->selectHelper;
        }

        if ($this->view instanceof RendererInterface && method_exists($this->view, 'plugin')) {
            $this->selectHelper = $this->view->plugin('form_select');
        }

        if (!$this->selectHelper instanceof FormSelect) {
            $this->selectHelper = new FormSelect();
        }

        return $this->selectHelper;
    }
}
