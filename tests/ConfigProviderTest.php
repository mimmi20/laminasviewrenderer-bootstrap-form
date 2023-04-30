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

namespace Mimmi20Test\LaminasView\BootstrapForm;

use Laminas\View\HelperPluginManager;
use Mimmi20\LaminasView\BootstrapForm\ConfigProvider;
use Mimmi20\LaminasView\BootstrapForm\Form;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;

final class ConfigProviderTest extends TestCase
{
    private ConfigProvider $provider;

    /**
     * @throws void
     *
     * @psalm-suppress ReservedWord
     */
    protected function setUp(): void
    {
        $this->provider = new ConfigProvider();
    }

    /** @throws Exception */
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

    /** @throws Exception */
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

    /** @throws Exception */
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
