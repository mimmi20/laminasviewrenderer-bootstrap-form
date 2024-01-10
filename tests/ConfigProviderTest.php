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

namespace Mimmi20Test\LaminasView\BootstrapForm;

use Mimmi20\LaminasView\BootstrapForm\ConfigProvider;
use Mimmi20\LaminasView\BootstrapForm\Form;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;

final class ConfigProviderTest extends TestCase
{
    private ConfigProvider $provider;

    /** @throws void */
    protected function setUp(): void
    {
        $this->provider = new ConfigProvider();
    }

    /**
     * @throws Exception
     * @throws ContainerExceptionInterface
     */
    public function testProviderDefinesExpectedFactoryServices(): void
    {
        $viewHelperConfig = $this->provider->getViewHelperConfig();
        self::assertIsArray($viewHelperConfig);

        self::assertArrayHasKey('factories', $viewHelperConfig);
        $factories = $viewHelperConfig['factories'];
        self::assertIsArray($factories);
        self::assertArrayHasKey(Form::class, $factories);

        self::assertArrayHasKey('aliases', $viewHelperConfig);
        $aliases = $viewHelperConfig['aliases'];
        self::assertIsArray($aliases);
        self::assertArrayHasKey('form', $aliases);
    }

    /**
     * @throws Exception
     * @throws ContainerExceptionInterface
     */
    public function testGetDependencyConfig(): void
    {
        $dependencyConfig = $this->provider->getDependencyConfig();
        self::assertIsArray($dependencyConfig);

        self::assertArrayHasKey('factories', $dependencyConfig);
        $factories = $dependencyConfig['factories'];
        self::assertIsArray($factories);
        self::assertArrayHasKey(HelperPluginManager::class, $factories);

        self::assertArrayNotHasKey('aliases', $dependencyConfig);
    }

    /**
     * @throws Exception
     * @throws ContainerExceptionInterface
     */
    public function testGetDependencyConfig2(): void
    {
        $dependencyConfig = $this->provider->getDependencyConfig();
        self::assertIsArray($dependencyConfig);

        self::assertArrayHasKey('factories', $dependencyConfig);
        $factories = $dependencyConfig['factories'];
        self::assertIsArray($factories);
        self::assertArrayHasKey(HelperPluginManager::class, $factories);
        self::assertIsCallable($factories[HelperPluginManager::class]);

        self::assertArrayNotHasKey('aliases', $dependencyConfig);

        $callable = $factories[HelperPluginManager::class];

        $container = $this->createMock(ContainerInterface::class);
        $container->expects(self::never())
            ->method('has');
        $container->expects(self::once())
            ->method('get')
            ->with('config')
            ->willReturn(null);

        $this->expectException(AssertionError::class);
        $this->expectExceptionCode(1);
        $this->expectExceptionMessage('assert(is_array($config))');

        $callable($container, '', null);
    }

    /**
     * @throws Exception
     * @throws ContainerExceptionInterface
     */
    public function testGetDependencyConfig3(): void
    {
        $dependencyConfig = $this->provider->getDependencyConfig();
        self::assertIsArray($dependencyConfig);

        self::assertArrayHasKey('factories', $dependencyConfig);
        $factories = $dependencyConfig['factories'];
        self::assertIsArray($factories);
        self::assertArrayHasKey(HelperPluginManager::class, $factories);
        self::assertIsCallable($factories[HelperPluginManager::class]);

        self::assertArrayNotHasKey('aliases', $dependencyConfig);

        $callable = $factories[HelperPluginManager::class];

        $container = $this->createMock(ContainerInterface::class);
        $container->expects(self::never())
            ->method('has');
        $container->expects(self::once())
            ->method('get')
            ->with('config')
            ->willReturn([]);

        $result = $callable($container, '', null);

        self::assertInstanceOf(HelperPluginManager::class, $result);
    }

    /**
     * @throws Exception
     * @throws ContainerExceptionInterface
     */
    public function testInvocationReturnsArrayWithDependencies(): void
    {
        $config = ($this->provider)();

        self::assertIsArray($config);
        self::assertCount(2, $config);
        self::assertArrayHasKey('view_helpers', $config);
        self::assertArrayHasKey('dependencies', $config);

        $viewHelperConfig = $config['view_helpers'];
        self::assertArrayHasKey('factories', $viewHelperConfig);
        $factories = $viewHelperConfig['factories'];
        self::assertIsArray($factories);
        self::assertArrayHasKey(Form::class, $factories);

        self::assertArrayHasKey('aliases', $viewHelperConfig);
        $aliases = $viewHelperConfig['aliases'];
        self::assertIsArray($aliases);
        self::assertArrayHasKey('form', $aliases);

        $dependencyConfig = $config['dependencies'];
        self::assertArrayHasKey('factories', $dependencyConfig);
        $factories = $dependencyConfig['factories'];
        self::assertIsArray($factories);
        self::assertArrayHasKey(HelperPluginManager::class, $factories);
    }
}
