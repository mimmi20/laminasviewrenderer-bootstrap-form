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

use Mimmi20\LaminasView\BootstrapForm\Form;
use Mimmi20\LaminasView\BootstrapForm\Module;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;

final class ModuleTest extends TestCase
{
    /** @throws Exception */
    public function testGetConfig(): void
    {
        $module = new Module();

        $config = $module->getConfig();

        self::assertIsArray($config);
        self::assertArrayHasKey('view_helpers', $config);

        $viewHelperConfig = $config['view_helpers'];
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
    public function testGetModuleDependencies(): void
    {
        $module = new Module();

        $config = $module->getModuleDependencies();

        self::assertIsArray($config);
        self::assertCount(5, $config);
        self::assertArrayHasKey(0, $config);
        self::assertContains('Laminas\I18n', $config);
        self::assertContains('Laminas\Form', $config);
        self::assertContains('Mimmi20\Form\Element\Group', $config);
        self::assertContains('Mimmi20\Form\Element\Links', $config);
        self::assertContains('Mimmi20\Form\Element\Paragraph', $config);
    }
}
