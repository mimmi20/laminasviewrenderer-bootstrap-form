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

use AssertionError;
use Laminas\I18n\View\Helper\Translate;
use Laminas\View\Helper\EscapeHtml;
use Laminas\View\HelperPluginManager;
use Laminas\View\Renderer\RendererInterface;
use Mimmi20\LaminasView\BootstrapForm\FormElementErrorsInterface;
use Mimmi20\LaminasView\BootstrapForm\FormElementInterface;
use Mimmi20\LaminasView\BootstrapForm\FormRow;
use Mimmi20\LaminasView\BootstrapForm\FormRowFactory;
use Mimmi20\LaminasView\Helper\HtmlElement\Helper\HtmlElementInterface;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

use function assert;

final class FormRowFactoryTest extends TestCase
{
    private FormRowFactory $factory;

    /** @throws void */
    protected function setUp(): void
    {
        $this->factory = new FormRowFactory();
    }

    /**
     * @throws Exception
     * @throws ContainerExceptionInterface
     */
    public function testInvocationWithTranslator(): void
    {
        $formElement       = $this->createMock(FormElementInterface::class);
        $formElementErrors = $this->createMock(FormElementErrorsInterface::class);
        $escapeHtml        = $this->createMock(EscapeHtml::class);
        $htmlElement       = $this->createMock(HtmlElementInterface::class);
        $renderer          = $this->createMock(RendererInterface::class);
        $translatePlugin   = $this->createMock(Translate::class);

        $helperPluginManager = $this->getMockBuilder(HelperPluginManager::class)
            ->disableOriginalConstructor()
            ->getMock();
        $helperPluginManager->expects(self::once())
            ->method('has')
            ->with(Translate::class)
            ->willReturn(true);
        $helperPluginManager->expects(self::exactly(4))
            ->method('get')
            ->willReturnMap(
                [
                    [Translate::class, null, $translatePlugin],
                    [FormElementInterface::class, null, $formElement],
                    [FormElementErrorsInterface::class, null, $formElementErrors],
                    [EscapeHtml::class, null, $escapeHtml],
                ],
            );

        $container = $this->getMockBuilder(ContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $container->expects(self::exactly(3))
            ->method('get')
            ->willReturnMap(
                [
                    [HelperPluginManager::class, $helperPluginManager],
                    [HtmlElementInterface::class, $htmlElement],
                    [RendererInterface::class, $renderer],
                ],
            );

        assert($container instanceof ContainerInterface);
        $helper = ($this->factory)($container);

        self::assertInstanceOf(FormRow::class, $helper);
    }

    /**
     * @throws Exception
     * @throws ContainerExceptionInterface
     */
    public function testInvocationWithoutTranslator(): void
    {
        $formElement       = $this->createMock(FormElementInterface::class);
        $formElementErrors = $this->createMock(FormElementErrorsInterface::class);
        $escapeHtml        = $this->createMock(EscapeHtml::class);
        $htmlElement       = $this->createMock(HtmlElementInterface::class);
        $renderer          = $this->createMock(RendererInterface::class);

        $helperPluginManager = $this->getMockBuilder(HelperPluginManager::class)
            ->disableOriginalConstructor()
            ->getMock();
        $helperPluginManager->expects(self::once())
            ->method('has')
            ->with(Translate::class)
            ->willReturn(false);
        $helperPluginManager->expects(self::exactly(3))
            ->method('get')
            ->willReturnMap(
                [
                    [FormElementInterface::class, null, $formElement],
                    [FormElementErrorsInterface::class, null, $formElementErrors],
                    [EscapeHtml::class, null, $escapeHtml],
                ],
            );

        $container = $this->getMockBuilder(ContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $container->expects(self::exactly(3))
            ->method('get')
            ->willReturnMap(
                [
                    [HelperPluginManager::class, $helperPluginManager],
                    [HtmlElementInterface::class, $htmlElement],
                    [RendererInterface::class, $renderer],
                ],
            );

        assert($container instanceof ContainerInterface);
        $helper = ($this->factory)($container);

        self::assertInstanceOf(FormRow::class, $helper);
    }

    /**
     * @throws Exception
     * @throws ContainerExceptionInterface
     */
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
        $this->expectExceptionMessage(
            '$plugin should be an Instance of Laminas\View\HelperPluginManager, but was bool',
        );

        ($this->factory)($container);
    }
}
