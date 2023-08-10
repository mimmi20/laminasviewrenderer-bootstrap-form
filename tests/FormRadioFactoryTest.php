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
use Laminas\View\Helper\Doctype;
use Laminas\View\Helper\EscapeHtml;
use Laminas\View\Helper\EscapeHtmlAttr;
use Laminas\View\Helper\HelperInterface;
use Laminas\View\HelperPluginManager;
use Mimmi20\LaminasView\BootstrapForm\FormHiddenInterface;
use Mimmi20\LaminasView\BootstrapForm\FormLabelInterface;
use Mimmi20\LaminasView\BootstrapForm\FormRadio;
use Mimmi20\LaminasView\BootstrapForm\FormRadioFactory;
use Mimmi20\LaminasView\Helper\HtmlElement\Helper\HtmlElementInterface;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

use function assert;

final class FormRadioFactoryTest extends TestCase
{
    private FormRadioFactory $factory;

    /**
     * @throws void
     *
     * @psalm-suppress ReservedWord
     */
    protected function setUp(): void
    {
        $this->factory = new FormRadioFactory();
    }

    /** @throws Exception */
    public function testInvocationWithTranslator(): void
    {
        $escapeHtml      = $this->createMock(EscapeHtml::class);
        $escapeHtmlAttr  = $this->createMock(EscapeHtmlAttr::class);
        $doctype         = $this->createMock(Doctype::class);
        $formLabel       = $this->createMock(FormLabelInterface::class);
        $htmlElement     = $this->createMock(HtmlElementInterface::class);
        $translatePlugin = $this->createMock(Translate::class);
        $formHidden      = $this->createMock(FormHiddenInterface::class);

        $helperPluginManager = $this->getMockBuilder(HelperPluginManager::class)
            ->disableOriginalConstructor()
            ->getMock();
        $helperPluginManager->expects(self::once())
            ->method('has')
            ->with(Translate::class)
            ->willReturn(true);
        $matcher = self::exactly(6);
        $helperPluginManager->expects($matcher)
            ->method('get')
            ->willReturnCallback(
                static function ($name, array | null $options = null) use ($matcher, $translatePlugin, $escapeHtml, $escapeHtmlAttr, $doctype, $formLabel, $formHidden): HelperInterface | FormLabelInterface | FormHiddenInterface {
                    match ($matcher->numberOfInvocations()) {
                        1 => self::assertSame(
                            Translate::class,
                            $name,
                            (string) $matcher->numberOfInvocations(),
                        ),
                        2 => self::assertSame(
                            EscapeHtml::class,
                            $name,
                            (string) $matcher->numberOfInvocations(),
                        ),
                        3 => self::assertSame(
                            EscapeHtmlAttr::class,
                            $name,
                            (string) $matcher->numberOfInvocations(),
                        ),
                        4 => self::assertSame(
                            Doctype::class,
                            $name,
                            (string) $matcher->numberOfInvocations(),
                        ),
                        5 => self::assertSame(
                            FormLabelInterface::class,
                            $name,
                            (string) $matcher->numberOfInvocations(),
                        ),
                        default => self::assertSame(
                            FormHiddenInterface::class,
                            $name,
                            (string) $matcher->numberOfInvocations(),
                        ),
                    };

                    self::assertNull($options, (string) $matcher->numberOfInvocations());

                    return match ($matcher->numberOfInvocations()) {
                        1 => $translatePlugin,
                        2 => $escapeHtml,
                        3 => $escapeHtmlAttr,
                        4 => $doctype,
                        5 => $formLabel,
                        default => $formHidden,
                    };
                },
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

        self::assertInstanceOf(FormRadio::class, $helper);
    }

    /** @throws Exception */
    public function testInvocationWithoutTranslator(): void
    {
        $escapeHtml     = $this->createMock(EscapeHtml::class);
        $escapeHtmlAttr = $this->createMock(EscapeHtmlAttr::class);
        $doctype        = $this->createMock(Doctype::class);
        $formLabel      = $this->createMock(FormLabelInterface::class);
        $htmlElement    = $this->createMock(HtmlElementInterface::class);
        $formHidden     = $this->createMock(FormHiddenInterface::class);

        $helperPluginManager = $this->getMockBuilder(HelperPluginManager::class)
            ->disableOriginalConstructor()
            ->getMock();
        $helperPluginManager->expects(self::once())
            ->method('has')
            ->with(Translate::class)
            ->willReturn(false);
        $matcher = self::exactly(5);
        $helperPluginManager->expects($matcher)
            ->method('get')
            ->willReturnCallback(
                static function ($name, array | null $options = null) use ($matcher, $escapeHtml, $escapeHtmlAttr, $doctype, $formLabel, $formHidden): HelperInterface | FormLabelInterface | FormHiddenInterface {
                    match ($matcher->numberOfInvocations()) {
                        1 => self::assertSame(
                            EscapeHtml::class,
                            $name,
                            (string) $matcher->numberOfInvocations(),
                        ),
                        2 => self::assertSame(
                            EscapeHtmlAttr::class,
                            $name,
                            (string) $matcher->numberOfInvocations(),
                        ),
                        3 => self::assertSame(
                            Doctype::class,
                            $name,
                            (string) $matcher->numberOfInvocations(),
                        ),
                        4 => self::assertSame(
                            FormLabelInterface::class,
                            $name,
                            (string) $matcher->numberOfInvocations(),
                        ),
                        default => self::assertSame(
                            FormHiddenInterface::class,
                            $name,
                            (string) $matcher->numberOfInvocations(),
                        ),
                    };

                    self::assertNull($options, (string) $matcher->numberOfInvocations());

                    return match ($matcher->numberOfInvocations()) {
                        1 => $escapeHtml,
                        2 => $escapeHtmlAttr,
                        3 => $doctype,
                        4 => $formLabel,
                        default => $formHidden,
                    };
                },
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

        self::assertInstanceOf(FormRadio::class, $helper);
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
