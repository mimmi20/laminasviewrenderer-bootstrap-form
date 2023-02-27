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

use Closure;
use Laminas\ModuleManager\Feature\ConfigProviderInterface;
use Laminas\ModuleManager\Feature\DependencyIndicatorInterface;
use Laminas\View\Helper\HelperInterface;
use Laminas\View\HelperPluginManager;
use Psr\Container\ContainerInterface;

final class Module implements ConfigProviderInterface, DependencyIndicatorInterface
{
    /**
     * Return default configuration for laminas-mvc applications.
     *
     * @return array<string, array<string, array<int|string, Closure|string>>>
     * @phpstan-return array{service_manager: array{factories: array<class-string, (Closure(ContainerInterface, string, array<mixed>|null):HelperPluginManager<(callable(): HelperInterface)|HelperInterface>)>}, view_helpers: array{aliases: array<string, class-string>, factories: array<class-string, class-string>}}
     *
     * @throws void
     */
    public function getConfig(): array
    {
        $provider = new ConfigProvider();

        return [
            'service_manager' => $provider->getDependencyConfig(),
            'view_helpers' => $provider->getViewHelperConfig(),
        ];
    }

    /**
     * Expected to return an array of modules on which the current one depends on
     *
     * @return array<int, string>
     *
     * @throws void
     */
    public function getModuleDependencies(): array
    {
        return [
            'Laminas\I18n',
            'Laminas\Form',
            'Mimmi20\Form\Element\Group',
            'Mimmi20\Form\Links',
            'Mimmi20\Form\Paragraph',
        ];
    }
}
