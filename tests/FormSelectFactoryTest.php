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

use AssertionError;
use Interop\Container\ContainerInterface;
use Laminas\I18n\View\Helper\Translate;
use Laminas\View\Helper\EscapeHtml;
use Laminas\View\HelperPluginManager;
use Mimmi20\LaminasView\BootstrapForm\FormHiddenInterface;
use Mimmi20\LaminasView\BootstrapForm\FormSelect;
use Mimmi20\LaminasView\BootstrapForm\FormSelectFactory;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;

use function assert;

final class FormSelectFactoryTest extends TestCase
{
    private FormSelectFactory $factory;

    /** @throws void */
    protected function setUp(): void
    {
        $this->factory = new FormSelectFactory();
    }

    /** @throws Exception */
    public function testInvocationWithTranslator(): void
    {
        $escapeHtml      = $this->createMock(EscapeHtml::class);
        $formHidden      = $this->createMock(FormHiddenInterface::class);
        $translatePlugin = $this->createMock(Translate::class);

        $helperPluginManager = $this->getMockBuilder(HelperPluginManager::class)
            ->disableOriginalConstructor()
            ->getMock();
        $helperPluginManager->expects(self::once())
            ->method('has')
            ->with(Translate::class)
            ->willReturn(true);
        $helperPluginManager->expects(self::exactly(3))
            ->method('get')
            ->willReturnMap(
                [
                    [Translate::class, null, $translatePlugin],
                    [EscapeHtml::class, null, $escapeHtml],
                    [FormHiddenInterface::class, null, $formHidden],
                ],
            );

        $container = $this->getMockBuilder(ContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $container->expects(self::once())
            ->method('get')
            ->with(HelperPluginManager::class)
            ->willReturn($helperPluginManager);

        assert($container instanceof ContainerInterface);
        $helper = ($this->factory)($container);

        self::assertInstanceOf(FormSelect::class, $helper);
    }

    /** @throws Exception */
    public function testInvocationWithoutTranslator(): void
    {
        $escapeHtml = $this->createMock(EscapeHtml::class);
        $formHidden = $this->createMock(FormHiddenInterface::class);

        $helperPluginManager = $this->getMockBuilder(HelperPluginManager::class)
            ->disableOriginalConstructor()
            ->getMock();
        $helperPluginManager->expects(self::once())
            ->method('has')
            ->with(Translate::class)
            ->willReturn(false);
        $helperPluginManager->expects(self::exactly(2))
            ->method('get')
            ->willReturnMap(
                [
                    [EscapeHtml::class, null, $escapeHtml],
                    [FormHiddenInterface::class, null, $formHidden],
                ],
            );

        $container = $this->getMockBuilder(ContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $container->expects(self::once())
            ->method('get')
            ->with(HelperPluginManager::class)
            ->willReturn($helperPluginManager);

        assert($container instanceof ContainerInterface);
        $helper = ($this->factory)($container);

        self::assertInstanceOf(FormSelect::class, $helper);
    }

    /** @throws Exception */
    public function testInvocationWithAssertionError(): void
    {
        $container = $this->getMockBuilder(ContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $container->expects(self::once())
            ->method('get')
            ->with(HelperPluginManager::class)
            ->willReturn(true);

        assert($container instanceof ContainerInterface);

        $this->expectException(AssertionError::class);
        $this->expectExceptionCode(1);
        $this->expectExceptionMessage('$plugin should be an Instance of Laminas\View\HelperPluginManager, but was boolean');

        ($this->factory)($container);
    }
}
