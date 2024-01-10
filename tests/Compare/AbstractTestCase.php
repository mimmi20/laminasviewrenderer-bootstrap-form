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

namespace Mimmi20Test\LaminasView\BootstrapForm\Compare;

use Laminas\ServiceManager\Exception\ContainerModificationsNotAllowedException;
use Laminas\ServiceManager\ServiceManager;
use Laminas\View\HelperPluginManager;
use Laminas\View\Renderer\PhpRenderer;
use Laminas\View\Renderer\RendererInterface;
use Mimmi20\LaminasView\BootstrapForm\ConfigProvider;
use Mimmi20\LaminasView\Helper\HtmlElement\Helper\HtmlElementFactory;
use Mimmi20\LaminasView\Helper\HtmlElement\Helper\HtmlElementInterface;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

use function file_get_contents;
use function sprintf;
use function str_replace;

use const PHP_EOL;

/**
 * Base class for navigation view helper tests
 */
abstract class AbstractTestCase extends TestCase
{
    protected ServiceManager $serviceManager;

    /**
     * Path to files needed for test
     */
    protected string $files;

    /**
     * Prepares the environment before running a test
     *
     * @throws ContainerModificationsNotAllowedException
     */
    protected function setUp(): void
    {
        $cwd = __DIR__;

        // read navigation config
        $this->files = $cwd . '/_files';

        $sm = $this->serviceManager = new ServiceManager();
        $sm->setAllowOverride(true);

        $sm->setFactory(HtmlElementInterface::class, HtmlElementFactory::class);

        $config          = new ConfigProvider();
        $linksConfig     = new \Mimmi20\Form\Links\ConfigProvider();
        $paragraphConfig = new \Mimmi20\Form\Paragraph\ConfigProvider();

        $sm->setService('config', $config());

        $sm->setFactory(
            HelperPluginManager::class,
            /** @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter */
            static function (ContainerInterface $container, string $requestedName, array | null $options = null) use ($sm, $config, $linksConfig, $paragraphConfig): HelperPluginManager {
                $manager = new HelperPluginManager(
                    $sm,
                    $config->getViewHelperConfig(),
                );

                $manager->configure($linksConfig->getViewHelperConfig());
                $manager->configure($paragraphConfig->getViewHelperConfig());

                return $manager;
            },
        );

        $sm->setFactory(
            RendererInterface::class,
            /** @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter */
            static fn (ContainerInterface $container, string $requestedName, array | null $options = null): PhpRenderer => new PhpRenderer(),
        );

        $sm->setAllowOverride(false);
    }

    /**
     * Returns the contens of the expected $file
     *
     * @throws Exception
     */
    protected function getExpected(string $file): string
    {
        $content = file_get_contents($this->files . '/expected/' . $file);

        static::assertIsString(
            $content,
            sprintf('could not load file %s', $this->files . '/expected/' . $file),
        );

        return str_replace(
            ["\r\n", "\n", "\r", '##lb##'],
            ['##lb##', '##lb##', '##lb##', PHP_EOL],
            $content,
        );
    }
}
