<?php
/**
 * This file is part of the mimmi20/mezzio-form-laminasviewrenderer-bootstrap package.
 *
 * Copyright (c) 2021-2024, Thomas Mueller <mimmi20@live.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Mimmi20\LaminasView\BootstrapForm;

use function method_exists;

trait SelectHelperTrait
{
    /**
     * Form label helper instance
     *
     * @var null|FormSelect
     */
    private null|FormSelect $selectHelper = null;

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

        if (method_exists($this->view, 'plugin')) {
            $this->selectHelper = $this->view->plugin('form_select');
        }

        if (! $this->selectHelper instanceof FormSelect) {
            $this->selectHelper = new FormSelect();
        }

        return $this->selectHelper;
    }
}
