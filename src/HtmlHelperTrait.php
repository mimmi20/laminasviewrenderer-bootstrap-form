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

trait HtmlHelperTrait
{
    /**
     * Form html helper instance
     */
    private FormHtmlInterface | null $htmlHelper = null;

    /**
     * Retrieve the FormHtml helper
     *
     * @throws void
     */
    private function getHtmlHelper(): FormHtmlInterface
    {
        if ($this->htmlHelper) {
            return $this->htmlHelper;
        }

        if ($this->view instanceof RendererInterface && method_exists($this->view, 'plugin')) {
            $this->htmlHelper = $this->view->plugin('form_html');
        }

        if (!$this->htmlHelper instanceof FormHtmlInterface) {
            $this->htmlHelper = new FormHtml();
        }

        return $this->htmlHelper;
    }
}
