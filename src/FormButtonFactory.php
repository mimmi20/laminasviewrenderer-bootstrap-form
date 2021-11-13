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

use Interop\Container\ContainerInterface;
use Laminas\I18n\View\Helper\Translate;
use Laminas\View\Helper\Doctype;
use Laminas\View\Helper\EscapeHtml;
use Laminas\View\Helper\EscapeHtmlAttr;
use Laminas\View\HelperPluginManager;
use Psr\Container\ContainerExceptionInterface;

use function assert;
use function get_class;
use function gettype;
use function is_object;
use function sprintf;

final class FormButtonFactory
{
    /**
     * @throws ContainerExceptionInterface
     */
    public function __invoke(ContainerInterface $container): FormButton
    {
        $plugin = $container->get(HelperPluginManager::class);
        assert(
            $plugin instanceof HelperPluginManager,
            sprintf(
                '$plugin should be an Instance of %s, but was %s',
                HelperPluginManager::class,
                is_object($plugin) ? get_class($plugin) : gettype($plugin)
            )
        );

        $translator = null;

        if ($plugin->has(Translate::class)) {
            $translator = $plugin->get(Translate::class);

            assert($translator instanceof Translate);
        }

        $escapeHtml     = $plugin->get(EscapeHtml::class);
        $escapeHtmlAttr = $plugin->get(EscapeHtmlAttr::class);
        $docType        = $plugin->get(Doctype::class);

        assert($escapeHtml instanceof EscapeHtml);
        assert($escapeHtmlAttr instanceof EscapeHtmlAttr);
        assert($docType instanceof Doctype);

        return new FormButton($escapeHtml, $escapeHtmlAttr, $docType, $translator);
    }
}
