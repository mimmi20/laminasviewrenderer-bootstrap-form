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
use Laminas\View\HelperPluginManager;
use Mimmi20\LaminasView\BootstrapForm\FormDateTimeSelect;
use Mimmi20\LaminasView\BootstrapForm\FormDateTimeSelectFactory;
use Mimmi20\LaminasView\BootstrapForm\FormSelectInterface;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

use function assert;

final class FormDateTimeSelectFactoryTest extends TestCase
{
    private FormDateTimeSelectFactory $factory;

    /**
     * @throws void
     *
     * @psalm-suppress ReservedWord
     */
    protected function setUp(): void
    {
        $this->factory = new FormDateTimeSelectFactory();
    }

    /** @throws Exception */
    public function testInvocation(): void
    {
        $selectHelper = $this->createMock(FormSelectInterface::class);

        $helperPluginManager = $this->getMockBuilder(HelperPluginManager::class)
            ->disableOriginalConstructor()
            ->getMock();
        $helperPluginManager->expects(self::never())
            ->method('has');
        $helperPluginManager->expects(self::once())
            ->method('get')
            ->with(FormSelectInterface::class)
            ->willReturn($selectHelper);

        $container = $this->getMockBuilder(ContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $container->expects(self::once())
            ->method('get')
            ->with(HelperPluginManager::class)
            ->willReturn($helperPluginManager);

        assert($container instanceof ContainerInterface);
        $helper = ($this->factory)($container);

        self::assertInstanceOf(FormDateTimeSelect::class, $helper);
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
