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

use Laminas\Form\Exception\ExtensionNotLoadedException;
use Laminas\View\HelperPluginManager;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

use function assert;
use function get_debug_type;
use function sprintf;

final class FormDateSelectFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws ExtensionNotLoadedException
     */
    public function __invoke(ContainerInterface $container): FormDateSelect
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

        $select = $plugin->get(FormSelectInterface::class);

        assert($select instanceof FormSelectInterface);

        return new FormDateSelect($select);
    }
}
