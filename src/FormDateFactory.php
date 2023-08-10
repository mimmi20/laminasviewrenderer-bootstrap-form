<?php
/**
 * This file is part of the mimmi20/laminasviewrenderer-bootstrap-form package.
 *
 * Copyright (c) 2021-2023, Thomas Mueller <mimmi20@live.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Mimmi20\LaminasView\BootstrapForm;

use Laminas\View\Helper\Doctype;
use Laminas\View\Helper\EscapeHtml;
use Laminas\View\Helper\EscapeHtmlAttr;
use Laminas\View\HelperPluginManager;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

use function assert;
use function get_debug_type;
use function sprintf;

final class FormDateFactory
{
    /** @throws ContainerExceptionInterface */
    public function __invoke(ContainerInterface $container): FormDate
    {
        $plugin = $container->get(HelperPluginManager::class);
        assert(
            $plugin instanceof HelperPluginManager,
            sprintf(
                '$plugin should be an Instance of %s, but was %s',
                HelperPluginManager::class,
                get_debug_type($plugin),
            ),
        );

        $escapeHtml     = $plugin->get(EscapeHtml::class);
        $escapeHtmlAttr = $plugin->get(EscapeHtmlAttr::class);
        $docType        = $plugin->get(Doctype::class);

        assert(
            $escapeHtml instanceof EscapeHtml,
            sprintf(
                '$escapeHtml should be an Instance of %s, but was %s',
                EscapeHtml::class,
                get_debug_type($escapeHtml),
            ),
        );

        assert(
            $escapeHtmlAttr instanceof EscapeHtmlAttr,
            sprintf(
                '$escapeHtmlAttr should be an Instance of %s, but was %s',
                EscapeHtmlAttr::class,
                get_debug_type($escapeHtmlAttr),
            ),
        );
        assert(
            $docType instanceof Doctype,
            sprintf(
                '$docType should be an Instance of %s, but was %s',
                Doctype::class,
                get_debug_type($docType),
            ),
        );

        return new FormDate($escapeHtml, $escapeHtmlAttr, $docType);
    }
}
