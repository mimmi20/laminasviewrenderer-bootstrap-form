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

use IntlDateFormatter;
use Laminas\Form\Element\DateTimeSelect as DateTimeSelectElement;
use Laminas\Form\Element\Text;
use Laminas\Form\Exception\DomainException;
use Laminas\Form\Exception\ExtensionNotLoadedException;
use Laminas\Form\Exception\InvalidArgumentException;
use Laminas\I18n\Exception\RuntimeException;
use Mimmi20\LaminasView\BootstrapForm\FormDateTimeSelect;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;

use function assert;
use function sprintf;

#[Group('form-datetime-select')]
final class FormDateTimeSelectTest extends TestCase
{
    private FormDateTimeSelect $helper;

    /** @throws ExtensionNotLoadedException */
    protected function setUp(): void
    {
        $this->helper = new FormDateTimeSelect();
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws \Laminas\View\Exception\InvalidArgumentException
     * @throws RuntimeException
     */
    public function testRenderWithWrongElement(): void
    {
        $element = $this->createMock(Text::class);
        $element->expects(self::never())
            ->method('getName');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            sprintf(
                '%s requires that the element is of type %s',
                'Mimmi20\LaminasView\BootstrapForm\FormDateTimeSelect::render',
                DateTimeSelectElement::class,
            ),
        );
        $this->expectExceptionCode(0);

        $this->helper->render($element);
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws \Laminas\View\Exception\InvalidArgumentException
     * @throws RuntimeException
     */
    public function testRenderWithoutName(): void
    {
        $element = $this->createMock(DateTimeSelectElement::class);
        $element->expects(self::once())
            ->method('getName')
            ->willReturn(null);
        $element->expects(self::never())
            ->method('shouldRenderDelimiters');
        $element->expects(self::never())
            ->method('getMinYear');
        $element->expects(self::never())
            ->method('getMaxYear');
        $element->expects(self::never())
            ->method('getDayElement');
        $element->expects(self::never())
            ->method('getMonthElement');
        $element->expects(self::never())
            ->method('getYearElement');
        $element->expects(self::never())
            ->method('getHourElement');
        $element->expects(self::never())
            ->method('getMinuteElement');
        $element->expects(self::never())
            ->method('getSecondElement');
        $element->expects(self::never())
            ->method('shouldCreateEmptyOption');
        $element->expects(self::never())
            ->method('shouldShowSeconds');

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage(
            sprintf(
                '%s requires that the element has an assigned name; none discovered',
                'Mimmi20\LaminasView\BootstrapForm\FormDateTimeSelect::render',
            ),
        );
        $this->expectExceptionCode(0);

        $this->helper->render($element);
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws \Laminas\View\Exception\InvalidArgumentException
     * @throws RuntimeException
     */
    public function testInvokeWithoutName1(): void
    {
        $element = $this->createMock(DateTimeSelectElement::class);
        $element->expects(self::once())
            ->method('getName')
            ->willReturn(null);
        $element->expects(self::never())
            ->method('shouldRenderDelimiters');
        $element->expects(self::never())
            ->method('getMinYear');
        $element->expects(self::never())
            ->method('getMaxYear');
        $element->expects(self::never())
            ->method('getDayElement');
        $element->expects(self::never())
            ->method('getMonthElement');
        $element->expects(self::never())
            ->method('getYearElement');
        $element->expects(self::never())
            ->method('getHourElement');
        $element->expects(self::never())
            ->method('getMinuteElement');
        $element->expects(self::never())
            ->method('getSecondElement');
        $element->expects(self::never())
            ->method('shouldCreateEmptyOption');
        $element->expects(self::never())
            ->method('shouldShowSeconds');

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage(
            sprintf(
                '%s requires that the element has an assigned name; none discovered',
                'Mimmi20\LaminasView\BootstrapForm\FormDateTimeSelect::render',
            ),
        );
        $this->expectExceptionCode(0);

        $helperObject = ($this->helper)();

        assert($helperObject instanceof FormDateTimeSelect);

        $helperObject->render($element);
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws \Laminas\View\Exception\InvalidArgumentException
     * @throws RuntimeException
     */
    public function testInvokeWithoutName2(): void
    {
        $element = $this->createMock(DateTimeSelectElement::class);
        $element->expects(self::once())
            ->method('getName')
            ->willReturn(null);
        $element->expects(self::never())
            ->method('shouldRenderDelimiters');

        $locale = 'de_DE';

        try {
            ($this->helper)($element, IntlDateFormatter::FULL, IntlDateFormatter::FULL, $locale);
            self::fail('expecting throwing an exception');
        } catch (DomainException) {
            self::assertSame(IntlDateFormatter::LONG, $this->helper->getDateType());
            self::assertSame($locale, $this->helper->getLocale());
        }
    }

    /** @throws Exception */
    public function testSetGetTimeType(): void
    {
        $type = IntlDateFormatter::MEDIUM;

        self::assertSame($this->helper, $this->helper->setTimeType($type));
        self::assertSame($type, $this->helper->getTimeType());
    }

    /** @throws Exception */
    public function testSetGetIndent1(): void
    {
        self::assertSame($this->helper, $this->helper->setIndent(4));
        self::assertSame('    ', $this->helper->getIndent());
    }

    /** @throws Exception */
    public function testSetGetIndent2(): void
    {
        self::assertSame($this->helper, $this->helper->setIndent('  '));
        self::assertSame('  ', $this->helper->getIndent());
    }
}
