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

trait HiddenHelperTrait
{
    /**
     * Form label helper instance
     *
     * @var null|FormHidden
     */
    private null|FormHidden $hiddenHelper = null;

    /**
     * Retrieve the FormLabel helper
     *
     * @throws void
     */
    private function getHiddenHelper(): FormHidden
    {
        if ($this->hiddenHelper) {
            return $this->hiddenHelper;
        }

        if (method_exists($this->view, 'plugin')) {
            $this->hiddenHelper = $this->view->plugin('form_hidden');
        }

        if (! $this->hiddenHelper instanceof FormHidden) {
            $this->hiddenHelper = new FormHidden();
        }

        return $this->hiddenHelper;
    }
}
