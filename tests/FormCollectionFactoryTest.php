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
use Laminas\I18n\View\Helper\Translate;
use Laminas\View\Helper\EscapeHtml;
use Laminas\View\HelperPluginManager;
use Mimmi20\LaminasView\BootstrapForm\FormCollection;
use Mimmi20\LaminasView\BootstrapForm\FormCollectionFactory;
use Mimmi20\LaminasView\BootstrapForm\FormRowInterface;
use Mimmi20\LaminasView\Helper\HtmlElement\Helper\HtmlElementInterface;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

use function assert;

final class FormCollectionFactoryTest extends TestCase
{
    private FormCollectionFactory $factory;

    /**
     * @throws void
     *
     * @psalm-suppress ReservedWord
     */
    protected function setUp(): void
    {
        $this->factory = new FormCollectionFactory();
    }

    /** @throws Exception */
    public function testInvocationWithTranslator(): void
    {
        $translatePlugin = $this->createMock(Translate::class);
        $escapeHtml      = $this->createMock(EscapeHtml::class);
        $formRow         = $this->createMock(FormRowInterface::class);
        $htmlElement     = $this->createMock(HtmlElementInterface::class);

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
                    [FormRowInterface::class, null, $formRow],
                    [EscapeHtml::class, null, $escapeHtml],
                ],
            );

        $container = $this->getMockBuilder(ContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $matcher   = self::exactly(2);
        $container->expects($matcher)
            ->method('get')
            ->willReturnCallback(
                static function (string $id) use ($matcher, $helperPluginManager, $htmlElement): mixed {
                    match ($matcher->numberOfInvocations()) {
                        1 => self::assertSame(
                            HelperPluginManager::class,
                            $id,
                            (string) $matcher->numberOfInvocations(),
                        ),
                        default => self::assertSame(
                            HtmlElementInterface::class,
                            $id,
                            (string) $matcher->numberOfInvocations(),
                        ),
                    };

                    return match ($matcher->numberOfInvocations()) {
                        1 => $helperPluginManager,
                        default => $htmlElement,
                    };
                },
            );

        assert($container instanceof ContainerInterface);
        $helper = ($this->factory)($container);

        self::assertInstanceOf(FormCollection::class, $helper);
    }

    /** @throws Exception */
    public function testInvocationWithoutTranslator(): void
    {
        $escapeHtml  = $this->createMock(EscapeHtml::class);
        $formRow     = $this->createMock(FormRowInterface::class);
        $htmlElement = $this->createMock(HtmlElementInterface::class);

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
                    [FormRowInterface::class, null, $formRow],
                    [EscapeHtml::class, null, $escapeHtml],
                ],
            );

        $container = $this->getMockBuilder(ContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $matcher   = self::exactly(2);
        $container->expects($matcher)
            ->method('get')
            ->willReturnCallback(
                static function (string $id) use ($matcher, $helperPluginManager, $htmlElement): mixed {
                    match ($matcher->numberOfInvocations()) {
                        1 => self::assertSame(
                            HelperPluginManager::class,
                            $id,
                            (string) $matcher->numberOfInvocations(),
                        ),
                        default => self::assertSame(
                            HtmlElementInterface::class,
                            $id,
                            (string) $matcher->numberOfInvocations(),
                        ),
                    };

                    return match ($matcher->numberOfInvocations()) {
                        1 => $helperPluginManager,
                        default => $htmlElement,
                    };
                },
            );

        assert($container instanceof ContainerInterface);
        $helper = ($this->factory)($container);

        self::assertInstanceOf(FormCollection::class, $helper);
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
        $this->expectExceptionMessage(
            '$plugin should be an Instance of Laminas\View\HelperPluginManager, but was bool',
        );

        ($this->factory)($container);
    }
}
