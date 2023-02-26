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

use Interop\Container\ContainerInterface;
use Laminas\I18n\View\Helper\Translate;
use Laminas\View\Helper\EscapeHtml;
use Laminas\View\HelperPluginManager;
use Laminas\View\Renderer\RendererInterface;
use Mimmi20\LaminasView\Helper\HtmlElement\Helper\HtmlElementInterface;
use Psr\Container\ContainerExceptionInterface;

use function assert;
use function gettype;
use function is_object;
use function sprintf;

final class FormRowFactory
{
    /** @throws ContainerExceptionInterface */
    public function __invoke(ContainerInterface $container): FormRow
    {
        $plugin = $container->get(HelperPluginManager::class);
        assert(
            $plugin instanceof HelperPluginManager,
            sprintf(
                '$plugin should be an Instance of %s, but was %s',
                HelperPluginManager::class,
                is_object($plugin) ? $plugin::class : gettype($plugin),
            ),
        );

        $translator = null;

        if ($plugin->has(Translate::class)) {
            $translator = $plugin->get(Translate::class);

            assert($translator instanceof Translate);
        }

        $formElement      = $plugin->get(FormElementInterface::class);
        $formElementError = $plugin->get(FormElementErrorsInterface::class);
        $htmlElement      = $container->get(HtmlElementInterface::class);
        $escapeHtml       = $plugin->get(EscapeHtml::class);
        $renderer         = $container->get(RendererInterface::class);

        assert($formElement instanceof FormElementInterface);
        assert($formElementError instanceof FormElementErrorsInterface);
        assert($htmlElement instanceof HtmlElementInterface);
        assert(
            $escapeHtml instanceof EscapeHtml,
            sprintf(
                '$escapeHtml should be an Instance of %s, but was %s',
                EscapeHtml::class,
                is_object($escapeHtml) ? $escapeHtml::class : gettype($escapeHtml),
            ),
        );
        assert($renderer instanceof RendererInterface);

        return new FormRow($formElement, $formElementError, $htmlElement, $escapeHtml, $renderer, $translator);
    }
}
