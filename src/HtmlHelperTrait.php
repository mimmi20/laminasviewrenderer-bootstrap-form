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

trait HtmlHelperTrait
{
    /**
     * Form html helper instance
     *
     * @var null|FormHtmlInterface
     */
    private null|FormHtmlInterface $htmlHelper = null;

    /**
     * Retrieve the FormHtml helper
     *
     * @throws void
     */
    private function getHtmlHelper(): FormHtml
    {
        if ($this->htmlHelper) {
            return $this->htmlHelper;
        }

        if (method_exists($this->view, 'plugin')) {
            $this->htmlHelper = $this->view->plugin('form_html');
        }

        if (! $this->htmlHelper instanceof FormHtml) {
            $this->htmlHelper = new FormHtml();
        }

        return $this->htmlHelper;
    }
}
