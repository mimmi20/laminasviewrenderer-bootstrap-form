<?php

/**
 * This file is part of the mimmi20/laminasviewrenderer-bootstrap-form package.
 *
 * Copyright (c) 2021-2026, Thomas Mueller <mimmi20@live.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Mimmi20\LaminasView\BootstrapForm;

use Laminas\View\HelperPluginManager;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

use function assert;
use function is_array;

final class HelperPluginManagerFactory
{
    /**
     * Create an instance of the requested class name.
     *
     * @throws ContainerExceptionInterface
     */
    public function __invoke(ContainerInterface $container): HelperPluginManager
    {
        $config = $container->get('config');
        assert(is_array($config));

        return new HelperPluginManager($container, $config['view_helpers'] ?? []);
    }
}
