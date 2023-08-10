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
use Laminas\View\Helper\Doctype;
use Laminas\View\Helper\EscapeHtml;
use Laminas\View\Helper\EscapeHtmlAttr;
use Laminas\View\Helper\HelperInterface;
use Laminas\View\HelperPluginManager;
use Mimmi20\LaminasView\BootstrapForm\FormEmail;
use Mimmi20\LaminasView\BootstrapForm\FormEmailFactory;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

use function assert;

final class FormEmailFactoryTest extends TestCase
{
    private FormEmailFactory $factory;

    /**
     * @throws void
     *
     * @psalm-suppress ReservedWord
     */
    protected function setUp(): void
    {
        $this->factory = new FormEmailFactory();
    }

    /** @throws Exception */
    public function testInvocation(): void
    {
        $escapeHtml     = $this->createMock(EscapeHtml::class);
        $escapeHtmlAttr = $this->createMock(EscapeHtmlAttr::class);
        $doctype        = $this->createMock(Doctype::class);

        $helperPluginManager = $this->getMockBuilder(HelperPluginManager::class)
            ->disableOriginalConstructor()
            ->getMock();
        $helperPluginManager->expects(self::never())
            ->method('has');
        $matcher = self::exactly(3);
        $helperPluginManager->expects($matcher)
            ->method('get')
            ->willReturnCallback(
                static function ($name, array | null $options = null) use ($matcher, $escapeHtml, $escapeHtmlAttr, $doctype): HelperInterface {
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
                        default => self::assertSame(
                            Doctype::class,
                            $name,
                            (string) $matcher->numberOfInvocations(),
                        ),
                    };

                    self::assertNull($options, (string) $matcher->numberOfInvocations());

                    return match ($matcher->numberOfInvocations()) {
                        1 => $escapeHtml,
                        2 => $escapeHtmlAttr,
                        default => $doctype,
                    };
                },
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

        self::assertInstanceOf(FormEmail::class, $helper);
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
