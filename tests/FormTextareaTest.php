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

use Laminas\Form\Element\File;
use Laminas\Form\Exception\DomainException;
use Laminas\View\Exception\InvalidArgumentException;
use Laminas\View\Helper\EscapeHtml;
use Mimmi20\LaminasView\BootstrapForm\FormTextarea;
use Mimmi20\LaminasView\Helper\HtmlElement\Helper\HtmlElementInterface;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;

use function assert;
use function sprintf;

final class FormTextareaTest extends TestCase
{
    /**
     * @throws Exception
     * @throws DomainException
     * @throws InvalidArgumentException
     */
    public function testRenderWithoutName(): void
    {
        $htmlElement = $this->getMockBuilder(HtmlElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $htmlElement->expects(self::never())
            ->method('toHtml');

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::never())
            ->method('__invoke');

        $helper = new FormTextarea($htmlElement, $escapeHtml);

        $element = $this->getMockBuilder(File::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::once())
            ->method('getName')
            ->willReturn(null);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage(
            sprintf(
                '%s requires that the element has an assigned name; none discovered',
                'Mimmi20\LaminasView\BootstrapForm\FormTextarea::render',
            ),
        );
        $this->expectExceptionCode(0);

        $helper->render($element);
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws InvalidArgumentException
     */
    public function testRenderWithName(): void
    {
        $name         = 'name';
        $value        = 'xyz';
        $escapedValue = 'uvwxyz';
        $expected     = '<textarea class="form-control abc" name="name">uvwxyz</textarea>';

        $htmlElement = $this->getMockBuilder(HtmlElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $htmlElement->expects(self::once())
            ->method('toHtml')
            ->with(
                'textarea',
                ['class' => 'form-control abc', 'name' => $name],
                $escapedValue,
            )
            ->willReturn($expected);

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::once())
            ->method('__invoke')
            ->with($value)
            ->willReturn($escapedValue);

        $helper = new FormTextarea($htmlElement, $escapeHtml);

        $element = $this->getMockBuilder(File::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::once())
            ->method('getName')
            ->willReturn($name);
        $element->expects(self::once())
            ->method('getAttributes')
            ->willReturn(['class' => 'abc']);
        $element->expects(self::once())
            ->method('getValue')
            ->willReturn($value);

        self::assertSame($expected, $helper->render($element));
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws InvalidArgumentException
     */
    public function testInvoke1(): void
    {
        $name         = 'name';
        $value        = 'xyz';
        $escapedValue = 'uvwxyz';
        $expected     = '<textarea class="form-control abc" name="name">uvwxyz</textarea>';

        $htmlElement = $this->getMockBuilder(HtmlElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $htmlElement->expects(self::once())
            ->method('toHtml')
            ->with(
                'textarea',
                ['class' => 'form-control abc', 'name' => $name],
                $escapedValue,
            )
            ->willReturn($expected);

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::once())
            ->method('__invoke')
            ->with($value)
            ->willReturn($escapedValue);

        $helper = new FormTextarea($htmlElement, $escapeHtml);

        $element = $this->getMockBuilder(File::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::once())
            ->method('getName')
            ->willReturn($name);
        $element->expects(self::once())
            ->method('getAttributes')
            ->willReturn(['class' => 'abc']);
        $element->expects(self::once())
            ->method('getValue')
            ->willReturn($value);

        $helperObject = $helper();

        assert($helperObject instanceof FormTextarea);

        self::assertSame($expected, $helperObject->render($element));
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws ContainerExceptionInterface
     * @throws InvalidArgumentException
     */
    public function testInvoke2(): void
    {
        $name         = 'name';
        $value        = 'xyz';
        $escapedValue = 'uvwxyz';
        $expected     = '<textarea class="form-control abc" name="name">uvwxyz</textarea>';

        $htmlElement = $this->getMockBuilder(HtmlElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $htmlElement->expects(self::once())
            ->method('toHtml')
            ->with(
                'textarea',
                ['class' => 'form-control abc', 'name' => $name],
                $escapedValue,
            )
            ->willReturn($expected);

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::once())
            ->method('__invoke')
            ->with($value)
            ->willReturn($escapedValue);

        $helper = new FormTextarea($htmlElement, $escapeHtml);

        $element = $this->getMockBuilder(File::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::once())
            ->method('getName')
            ->willReturn($name);
        $element->expects(self::once())
            ->method('getAttributes')
            ->willReturn(['class' => 'abc']);
        $element->expects(self::once())
            ->method('getValue')
            ->willReturn($value);

        self::assertSame($expected, $helper($element));
    }

    /**
     * @throws Exception
     * @throws ContainerExceptionInterface
     */
    public function testSetGetIndent1(): void
    {
        $htmlElement = $this->getMockBuilder(HtmlElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $htmlElement->expects(self::never())
            ->method('toHtml');

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::never())
            ->method('__invoke');

        $helper = new FormTextarea($htmlElement, $escapeHtml);

        self::assertSame($helper, $helper->setIndent(4));
        self::assertSame('    ', $helper->getIndent());
    }

    /**
     * @throws Exception
     * @throws ContainerExceptionInterface
     */
    public function testSetGetIndent2(): void
    {
        $htmlElement = $this->getMockBuilder(HtmlElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $htmlElement->expects(self::never())
            ->method('toHtml');

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::never())
            ->method('__invoke');

        $helper = new FormTextarea($htmlElement, $escapeHtml);

        self::assertSame($helper, $helper->setIndent('  '));
        self::assertSame('  ', $helper->getIndent());
    }
}
