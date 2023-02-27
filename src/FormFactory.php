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
use Laminas\View\HelperPluginManager;
use Psr\Container\ContainerExceptionInterface;

use function assert;
use function gettype;
use function is_object;
use function sprintf;

final class FormFactory
{
    /** @throws ContainerExceptionInterface */
    public function __invoke(ContainerInterface $container): Form
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

        $collection = $plugin->get(FormCollection::class);
        $row        = $plugin->get(FormRow::class);

        assert($collection instanceof FormCollectionInterface);
        assert($row instanceof FormRowInterface);

        return new Form($collection, $row);
    }
}
