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
use Laminas\Form\Element\Select;
use Laminas\Form\Element\Text;
use Laminas\Form\Exception\DomainException;
use Laminas\Form\Exception\InvalidArgumentException;
use Mimmi20\LaminasView\BootstrapForm\FormDateTimeSelect;
use Mimmi20\LaminasView\BootstrapForm\FormSelectInterface;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;

use function assert;
use function date;
use function sprintf;

use const PHP_EOL;

final class FormDateTimeSelect1Test extends TestCase
{
    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
     */
    public function testRenderWithWrongElement(): void
    {
        $selectHelper = $this->getMockBuilder(FormSelectInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $selectHelper->expects(self::never())
            ->method('setIndent');
        $selectHelper->expects(self::never())
            ->method('render');

        $helper = new FormDateTimeSelect($selectHelper);

        $element = $this->getMockBuilder(Text::class)
            ->disableOriginalConstructor()
            ->getMock();
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

        $helper->render($element);
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
     */
    public function testRenderWithoutName(): void
    {
        $selectHelper = $this->getMockBuilder(FormSelectInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $selectHelper->expects(self::never())
            ->method('setIndent');
        $selectHelper->expects(self::never())
            ->method('render');

        $helper = new FormDateTimeSelect($selectHelper);

        $element = $this->getMockBuilder(DateTimeSelectElement::class)
            ->disableOriginalConstructor()
            ->getMock();
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

        $helper->render($element);
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
     */
    public function testInvokeWithoutName1(): void
    {
        $selectHelper = $this->getMockBuilder(FormSelectInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $selectHelper->expects(self::never())
            ->method('setIndent');
        $selectHelper->expects(self::never())
            ->method('render');

        $helper = new FormDateTimeSelect($selectHelper);

        $element = $this->getMockBuilder(DateTimeSelectElement::class)
            ->disableOriginalConstructor()
            ->getMock();
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

        $helperObject = $helper();

        assert($helperObject instanceof FormDateTimeSelect);

        $helperObject->render($element);
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
     */
    public function testInvokeWithoutName2(): void
    {
        $selectHelper = $this->getMockBuilder(FormSelectInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $selectHelper->expects(self::never())
            ->method('setIndent');
        $selectHelper->expects(self::never())
            ->method('render');

        $helper = new FormDateTimeSelect($selectHelper);

        $element = $this->getMockBuilder(DateTimeSelectElement::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::once())
            ->method('getName')
            ->willReturn(null);
        $element->expects(self::never())
            ->method('shouldRenderDelimiters');

        $locale = 'de_DE';

        try {
            $helper($element, IntlDateFormatter::FULL, IntlDateFormatter::FULL, $locale);
            self::fail('expecting throwing an exception');
        } catch (DomainException) {
            self::assertSame(IntlDateFormatter::LONG, $helper->getDateType());
            self::assertSame($locale, $helper->getLocale());
        }
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
     */
    public function testRender1(): void
    {
        $name                    = 'test-name';
        $renderDelimiters        = true;
        $shouldCreateEmptyOption = true;
        $minYear                 = (int) date('Y') - 2;
        $maxYear                 = (int) date('Y') + 2;
        $renderedDay             = '<select name="day"></select>';
        $renderedMonth           = '<select name="month"></select>';
        $renderedYear            = '<select name="year"></select>';
        $renderedHour            = '<select name="hour"></select>';
        $renderedMinute          = '<select name="minute"></select>';
        $indent                  = '';

        $excpected = PHP_EOL . $renderedDay . PHP_EOL . '. ' . PHP_EOL . $renderedMonth . PHP_EOL . ' ' . PHP_EOL . $renderedYear . PHP_EOL . ' um ' . PHP_EOL . $renderedHour . PHP_EOL . ':' . PHP_EOL . $renderedMinute . PHP_EOL;

        $dayElement = $this->getMockBuilder(Select::class)
            ->disableOriginalConstructor()
            ->getMock();
        $dayElement->expects(self::once())
            ->method('setValueOptions')
            ->with(
                [
                    '01' => ['value' => '01', 'label' => '1'],
                    '02' => ['value' => '02', 'label' => '2'],
                    '03' => ['value' => '03', 'label' => '3'],
                    '04' => ['value' => '04', 'label' => '4'],
                    '05' => ['value' => '05', 'label' => '5'],
                    '06' => ['value' => '06', 'label' => '6'],
                    '07' => ['value' => '07', 'label' => '7'],
                    '08' => ['value' => '08', 'label' => '8'],
                    '09' => ['value' => '09', 'label' => '9'],
                    '10' => ['value' => '10', 'label' => '10'],
                    '11' => ['value' => '11', 'label' => '11'],
                    '12' => ['value' => '12', 'label' => '12'],
                    '13' => ['value' => '13', 'label' => '13'],
                    '14' => ['value' => '14', 'label' => '14'],
                    '15' => ['value' => '15', 'label' => '15'],
                    '16' => ['value' => '16', 'label' => '16'],
                    '17' => ['value' => '17', 'label' => '17'],
                    '18' => ['value' => '18', 'label' => '18'],
                    '19' => ['value' => '19', 'label' => '19'],
                    '20' => ['value' => '20', 'label' => '20'],
                    '21' => ['value' => '21', 'label' => '21'],
                    '22' => ['value' => '22', 'label' => '22'],
                    '23' => ['value' => '23', 'label' => '23'],
                    '24' => ['value' => '24', 'label' => '24'],
                    '25' => ['value' => '25', 'label' => '25'],
                    '26' => ['value' => '26', 'label' => '26'],
                    '27' => ['value' => '27', 'label' => '27'],
                    '28' => ['value' => '28', 'label' => '28'],
                    '29' => ['value' => '29', 'label' => '29'],
                    '30' => ['value' => '30', 'label' => '30'],
                    '31' => ['value' => '31', 'label' => '31'],
                ],
            )
            ->willReturnSelf();
        $dayElement->expects(self::once())
            ->method('setEmptyOption')
            ->with('')
            ->willReturnSelf();

        $monthElement = $this->getMockBuilder(Select::class)
            ->disableOriginalConstructor()
            ->getMock();
        $monthElement->expects(self::once())
            ->method('setValueOptions')
            ->with(
                [
                    '01' => [
                        'value' => '01',
                        'label' => 'Januar',
                    ],
                    '02' => [
                        'value' => '02',
                        'label' => 'Februar',
                    ],
                    '03' => [
                        'value' => '03',
                        'label' => 'März',
                    ],
                    '04' => [
                        'value' => '04',
                        'label' => 'April',
                    ],
                    '05' => [
                        'value' => '05',
                        'label' => 'Mai',
                    ],
                    '06' => [
                        'value' => '06',
                        'label' => 'Juni',
                    ],
                    '07' => [
                        'value' => '07',
                        'label' => 'Juli',
                    ],
                    '08' => [
                        'value' => '08',
                        'label' => 'August',
                    ],
                    '09' => [
                        'value' => '09',
                        'label' => 'September',
                    ],
                    '10' => [
                        'value' => '10',
                        'label' => 'Oktober',
                    ],
                    '11' => [
                        'value' => '11',
                        'label' => 'November',
                    ],
                    '12' => [
                        'value' => '12',
                        'label' => 'Dezember',
                    ],
                ],
            )
            ->willReturnSelf();
        $monthElement->expects(self::once())
            ->method('setEmptyOption')
            ->with('')
            ->willReturnSelf();

        $yearElement = $this->getMockBuilder(Select::class)
            ->disableOriginalConstructor()
            ->getMock();
        $yearElement->expects(self::once())
            ->method('setValueOptions')
            ->with(
                [
                    2025 => [
                        'value' => '2025',
                        'label' => '2025',
                    ],
                    2024 => [
                        'value' => '2024',
                        'label' => '2024',
                    ],
                    2023 => [
                        'value' => '2023',
                        'label' => '2023',
                    ],
                    2022 => [
                        'value' => '2022',
                        'label' => '2022',
                    ],
                    2021 => [
                        'value' => '2021',
                        'label' => '2021',
                    ],
                ],
            )
            ->willReturnSelf();
        $yearElement->expects(self::once())
            ->method('setEmptyOption')
            ->with('')
            ->willReturnSelf();

        $hourElement = $this->getMockBuilder(Select::class)
            ->disableOriginalConstructor()
            ->getMock();
        $hourElement->expects(self::once())
            ->method('setValueOptions')
            ->with(
                [
                    '00' => ['value' => '00', 'label' => '00'],
                    '01' => ['value' => '01', 'label' => '01'],
                    '02' => ['value' => '02', 'label' => '02'],
                    '03' => ['value' => '03', 'label' => '03'],
                    '04' => ['value' => '04', 'label' => '04'],
                    '05' => ['value' => '05', 'label' => '05'],
                    '06' => ['value' => '06', 'label' => '06'],
                    '07' => ['value' => '07', 'label' => '07'],
                    '08' => ['value' => '08', 'label' => '08'],
                    '09' => ['value' => '09', 'label' => '09'],
                    '10' => ['value' => '10', 'label' => '10'],
                    '11' => ['value' => '11', 'label' => '11'],
                    '12' => ['value' => '12', 'label' => '12'],
                    '13' => ['value' => '13', 'label' => '13'],
                    '14' => ['value' => '14', 'label' => '14'],
                    '15' => ['value' => '15', 'label' => '15'],
                    '16' => ['value' => '16', 'label' => '16'],
                    '17' => ['value' => '17', 'label' => '17'],
                    '18' => ['value' => '18', 'label' => '18'],
                    '19' => ['value' => '19', 'label' => '19'],
                    '20' => ['value' => '20', 'label' => '20'],
                    '21' => ['value' => '21', 'label' => '21'],
                    '22' => ['value' => '22', 'label' => '22'],
                    '23' => ['value' => '23', 'label' => '23'],
                ],
            )
            ->willReturnSelf();
        $hourElement->expects(self::once())
            ->method('setEmptyOption')
            ->with('')
            ->willReturnSelf();

        $minuteElement = $this->getMockBuilder(Select::class)
            ->disableOriginalConstructor()
            ->getMock();
        $minuteElement->expects(self::once())
            ->method('setValueOptions')
            ->with(
                [
                    '00' => ['value' => '00', 'label' => '00'],
                    '01' => ['value' => '01', 'label' => '01'],
                    '02' => ['value' => '02', 'label' => '02'],
                    '03' => ['value' => '03', 'label' => '03'],
                    '04' => ['value' => '04', 'label' => '04'],
                    '05' => ['value' => '05', 'label' => '05'],
                    '06' => ['value' => '06', 'label' => '06'],
                    '07' => ['value' => '07', 'label' => '07'],
                    '08' => ['value' => '08', 'label' => '08'],
                    '09' => ['value' => '09', 'label' => '09'],
                    '10' => ['value' => '10', 'label' => '10'],
                    '11' => ['value' => '11', 'label' => '11'],
                    '12' => ['value' => '12', 'label' => '12'],
                    '13' => ['value' => '13', 'label' => '13'],
                    '14' => ['value' => '14', 'label' => '14'],
                    '15' => ['value' => '15', 'label' => '15'],
                    '16' => ['value' => '16', 'label' => '16'],
                    '17' => ['value' => '17', 'label' => '17'],
                    '18' => ['value' => '18', 'label' => '18'],
                    '19' => ['value' => '19', 'label' => '19'],
                    '20' => ['value' => '20', 'label' => '20'],
                    '21' => ['value' => '21', 'label' => '21'],
                    '22' => ['value' => '22', 'label' => '22'],
                    '23' => ['value' => '23', 'label' => '23'],
                    '24' => ['value' => '24', 'label' => '24'],
                    '25' => ['value' => '25', 'label' => '25'],
                    '26' => ['value' => '26', 'label' => '26'],
                    '27' => ['value' => '27', 'label' => '27'],
                    '28' => ['value' => '28', 'label' => '28'],
                    '29' => ['value' => '29', 'label' => '29'],
                    '30' => ['value' => '30', 'label' => '30'],
                    '31' => ['value' => '31', 'label' => '31'],
                    '32' => ['value' => '32', 'label' => '32'],
                    '33' => ['value' => '33', 'label' => '33'],
                    '34' => ['value' => '34', 'label' => '34'],
                    '35' => ['value' => '35', 'label' => '35'],
                    '36' => ['value' => '36', 'label' => '36'],
                    '37' => ['value' => '37', 'label' => '37'],
                    '38' => ['value' => '38', 'label' => '38'],
                    '39' => ['value' => '39', 'label' => '39'],
                    '40' => ['value' => '40', 'label' => '40'],
                    '41' => ['value' => '41', 'label' => '41'],
                    '42' => ['value' => '42', 'label' => '42'],
                    '43' => ['value' => '43', 'label' => '43'],
                    '44' => ['value' => '44', 'label' => '44'],
                    '45' => ['value' => '45', 'label' => '45'],
                    '46' => ['value' => '46', 'label' => '46'],
                    '47' => ['value' => '47', 'label' => '47'],
                    '48' => ['value' => '48', 'label' => '48'],
                    '49' => ['value' => '49', 'label' => '49'],
                    '50' => ['value' => '50', 'label' => '50'],
                    '51' => ['value' => '51', 'label' => '51'],
                    '52' => ['value' => '52', 'label' => '52'],
                    '53' => ['value' => '53', 'label' => '53'],
                    '54' => ['value' => '54', 'label' => '54'],
                    '55' => ['value' => '55', 'label' => '55'],
                    '56' => ['value' => '56', 'label' => '56'],
                    '57' => ['value' => '57', 'label' => '57'],
                    '58' => ['value' => '58', 'label' => '58'],
                    '59' => ['value' => '59', 'label' => '59'],
                ],
            )
            ->willReturnSelf();
        $minuteElement->expects(self::once())
            ->method('setEmptyOption')
            ->with('')
            ->willReturnSelf();

        $selectHelper = $this->getMockBuilder(FormSelectInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $selectHelper->expects(self::once())
            ->method('setIndent')
            ->with($indent);
        $selectHelper->expects(self::exactly(5))
            ->method('render')
            ->willReturnMap(
                [
                    [$dayElement, $renderedDay],
                    [$monthElement, $renderedMonth],
                    [$yearElement, $renderedYear],
                    [$hourElement, $renderedHour],
                    [$minuteElement, $renderedMinute],
                ],
            );

        $helper = new FormDateTimeSelect($selectHelper);

        $element = $this->getMockBuilder(DateTimeSelectElement::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::once())
            ->method('getName')
            ->willReturn($name);
        $element->expects(self::once())
            ->method('shouldRenderDelimiters')
            ->willReturn($renderDelimiters);
        $element->expects(self::once())
            ->method('getMinYear')
            ->willReturn($minYear);
        $element->expects(self::once())
            ->method('getMaxYear')
            ->willReturn($maxYear);
        $element->expects(self::once())
            ->method('getDayElement')
            ->willReturn($dayElement);
        $element->expects(self::once())
            ->method('getMonthElement')
            ->willReturn($monthElement);
        $element->expects(self::once())
            ->method('getYearElement')
            ->willReturn($yearElement);
        $element->expects(self::once())
            ->method('getHourElement')
            ->willReturn($hourElement);
        $element->expects(self::once())
            ->method('getMinuteElement')
            ->willReturn($minuteElement);
        $element->expects(self::never())
            ->method('getSecondElement');
        $element->expects(self::once())
            ->method('shouldCreateEmptyOption')
            ->willReturn($shouldCreateEmptyOption);
        $element->expects(self::once())
            ->method('shouldShowSeconds')
            ->willReturn(false);

        self::assertSame($excpected, $helper->render($element));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
     */
    public function testRender2(): void
    {
        $name                    = 'test-name';
        $renderDelimiters        = false;
        $shouldCreateEmptyOption = true;
        $minYear                 = (int) date('Y') - 2;
        $maxYear                 = (int) date('Y') + 2;
        $renderedDay             = '<select name="day"></select>';
        $renderedMonth           = '<select name="month"></select>';
        $renderedYear            = '<select name="year"></select>';
        $renderedHour            = '<select name="hour"></select>';
        $renderedMinute          = '<select name="minute"></select>';
        $indent                  = '';

        $excpected = PHP_EOL . $renderedDay . PHP_EOL . $renderedMonth . PHP_EOL . $renderedYear . PHP_EOL . $renderedHour . PHP_EOL . $renderedMinute . PHP_EOL;

        $dayElement = $this->getMockBuilder(Select::class)
            ->disableOriginalConstructor()
            ->getMock();
        $dayElement->expects(self::once())
            ->method('setValueOptions')
            ->with(
                [
                    '01' => ['value' => '01', 'label' => '1'],
                    '02' => ['value' => '02', 'label' => '2'],
                    '03' => ['value' => '03', 'label' => '3'],
                    '04' => ['value' => '04', 'label' => '4'],
                    '05' => ['value' => '05', 'label' => '5'],
                    '06' => ['value' => '06', 'label' => '6'],
                    '07' => ['value' => '07', 'label' => '7'],
                    '08' => ['value' => '08', 'label' => '8'],
                    '09' => ['value' => '09', 'label' => '9'],
                    '10' => ['value' => '10', 'label' => '10'],
                    '11' => ['value' => '11', 'label' => '11'],
                    '12' => ['value' => '12', 'label' => '12'],
                    '13' => ['value' => '13', 'label' => '13'],
                    '14' => ['value' => '14', 'label' => '14'],
                    '15' => ['value' => '15', 'label' => '15'],
                    '16' => ['value' => '16', 'label' => '16'],
                    '17' => ['value' => '17', 'label' => '17'],
                    '18' => ['value' => '18', 'label' => '18'],
                    '19' => ['value' => '19', 'label' => '19'],
                    '20' => ['value' => '20', 'label' => '20'],
                    '21' => ['value' => '21', 'label' => '21'],
                    '22' => ['value' => '22', 'label' => '22'],
                    '23' => ['value' => '23', 'label' => '23'],
                    '24' => ['value' => '24', 'label' => '24'],
                    '25' => ['value' => '25', 'label' => '25'],
                    '26' => ['value' => '26', 'label' => '26'],
                    '27' => ['value' => '27', 'label' => '27'],
                    '28' => ['value' => '28', 'label' => '28'],
                    '29' => ['value' => '29', 'label' => '29'],
                    '30' => ['value' => '30', 'label' => '30'],
                    '31' => ['value' => '31', 'label' => '31'],
                ],
            )
            ->willReturnSelf();
        $dayElement->expects(self::once())
            ->method('setEmptyOption')
            ->with('')
            ->willReturnSelf();

        $monthElement = $this->getMockBuilder(Select::class)
            ->disableOriginalConstructor()
            ->getMock();
        $monthElement->expects(self::once())
            ->method('setValueOptions')
            ->with(
                [
                    '01' => [
                        'value' => '01',
                        'label' => 'Januar',
                    ],
                    '02' => [
                        'value' => '02',
                        'label' => 'Februar',
                    ],
                    '03' => [
                        'value' => '03',
                        'label' => 'März',
                    ],
                    '04' => [
                        'value' => '04',
                        'label' => 'April',
                    ],
                    '05' => [
                        'value' => '05',
                        'label' => 'Mai',
                    ],
                    '06' => [
                        'value' => '06',
                        'label' => 'Juni',
                    ],
                    '07' => [
                        'value' => '07',
                        'label' => 'Juli',
                    ],
                    '08' => [
                        'value' => '08',
                        'label' => 'August',
                    ],
                    '09' => [
                        'value' => '09',
                        'label' => 'September',
                    ],
                    '10' => [
                        'value' => '10',
                        'label' => 'Oktober',
                    ],
                    '11' => [
                        'value' => '11',
                        'label' => 'November',
                    ],
                    '12' => [
                        'value' => '12',
                        'label' => 'Dezember',
                    ],
                ],
            )
            ->willReturnSelf();
        $monthElement->expects(self::once())
            ->method('setEmptyOption')
            ->with('')
            ->willReturnSelf();

        $yearElement = $this->getMockBuilder(Select::class)
            ->disableOriginalConstructor()
            ->getMock();
        $yearElement->expects(self::once())
            ->method('setValueOptions')
            ->with(
                [
                    2025 => [
                        'value' => '2025',
                        'label' => '2025',
                    ],
                    2024 => [
                        'value' => '2024',
                        'label' => '2024',
                    ],
                    2023 => [
                        'value' => '2023',
                        'label' => '2023',
                    ],
                    2022 => [
                        'value' => '2022',
                        'label' => '2022',
                    ],
                    2021 => [
                        'value' => '2021',
                        'label' => '2021',
                    ],
                ],
            )
            ->willReturnSelf();
        $yearElement->expects(self::once())
            ->method('setEmptyOption')
            ->with('')
            ->willReturnSelf();

        $hourElement = $this->getMockBuilder(Select::class)
            ->disableOriginalConstructor()
            ->getMock();
        $hourElement->expects(self::once())
            ->method('setValueOptions')
            ->with(
                [
                    '00' => ['value' => '00', 'label' => '00'],
                    '01' => ['value' => '01', 'label' => '01'],
                    '02' => ['value' => '02', 'label' => '02'],
                    '03' => ['value' => '03', 'label' => '03'],
                    '04' => ['value' => '04', 'label' => '04'],
                    '05' => ['value' => '05', 'label' => '05'],
                    '06' => ['value' => '06', 'label' => '06'],
                    '07' => ['value' => '07', 'label' => '07'],
                    '08' => ['value' => '08', 'label' => '08'],
                    '09' => ['value' => '09', 'label' => '09'],
                    '10' => ['value' => '10', 'label' => '10'],
                    '11' => ['value' => '11', 'label' => '11'],
                    '12' => ['value' => '12', 'label' => '12'],
                    '13' => ['value' => '13', 'label' => '13'],
                    '14' => ['value' => '14', 'label' => '14'],
                    '15' => ['value' => '15', 'label' => '15'],
                    '16' => ['value' => '16', 'label' => '16'],
                    '17' => ['value' => '17', 'label' => '17'],
                    '18' => ['value' => '18', 'label' => '18'],
                    '19' => ['value' => '19', 'label' => '19'],
                    '20' => ['value' => '20', 'label' => '20'],
                    '21' => ['value' => '21', 'label' => '21'],
                    '22' => ['value' => '22', 'label' => '22'],
                    '23' => ['value' => '23', 'label' => '23'],
                ],
            )
            ->willReturnSelf();
        $hourElement->expects(self::once())
            ->method('setEmptyOption')
            ->with('')
            ->willReturnSelf();

        $minuteElement = $this->getMockBuilder(Select::class)
            ->disableOriginalConstructor()
            ->getMock();
        $minuteElement->expects(self::once())
            ->method('setValueOptions')
            ->with(
                [
                    '00' => ['value' => '00', 'label' => '00'],
                    '01' => ['value' => '01', 'label' => '01'],
                    '02' => ['value' => '02', 'label' => '02'],
                    '03' => ['value' => '03', 'label' => '03'],
                    '04' => ['value' => '04', 'label' => '04'],
                    '05' => ['value' => '05', 'label' => '05'],
                    '06' => ['value' => '06', 'label' => '06'],
                    '07' => ['value' => '07', 'label' => '07'],
                    '08' => ['value' => '08', 'label' => '08'],
                    '09' => ['value' => '09', 'label' => '09'],
                    '10' => ['value' => '10', 'label' => '10'],
                    '11' => ['value' => '11', 'label' => '11'],
                    '12' => ['value' => '12', 'label' => '12'],
                    '13' => ['value' => '13', 'label' => '13'],
                    '14' => ['value' => '14', 'label' => '14'],
                    '15' => ['value' => '15', 'label' => '15'],
                    '16' => ['value' => '16', 'label' => '16'],
                    '17' => ['value' => '17', 'label' => '17'],
                    '18' => ['value' => '18', 'label' => '18'],
                    '19' => ['value' => '19', 'label' => '19'],
                    '20' => ['value' => '20', 'label' => '20'],
                    '21' => ['value' => '21', 'label' => '21'],
                    '22' => ['value' => '22', 'label' => '22'],
                    '23' => ['value' => '23', 'label' => '23'],
                    '24' => ['value' => '24', 'label' => '24'],
                    '25' => ['value' => '25', 'label' => '25'],
                    '26' => ['value' => '26', 'label' => '26'],
                    '27' => ['value' => '27', 'label' => '27'],
                    '28' => ['value' => '28', 'label' => '28'],
                    '29' => ['value' => '29', 'label' => '29'],
                    '30' => ['value' => '30', 'label' => '30'],
                    '31' => ['value' => '31', 'label' => '31'],
                    '32' => ['value' => '32', 'label' => '32'],
                    '33' => ['value' => '33', 'label' => '33'],
                    '34' => ['value' => '34', 'label' => '34'],
                    '35' => ['value' => '35', 'label' => '35'],
                    '36' => ['value' => '36', 'label' => '36'],
                    '37' => ['value' => '37', 'label' => '37'],
                    '38' => ['value' => '38', 'label' => '38'],
                    '39' => ['value' => '39', 'label' => '39'],
                    '40' => ['value' => '40', 'label' => '40'],
                    '41' => ['value' => '41', 'label' => '41'],
                    '42' => ['value' => '42', 'label' => '42'],
                    '43' => ['value' => '43', 'label' => '43'],
                    '44' => ['value' => '44', 'label' => '44'],
                    '45' => ['value' => '45', 'label' => '45'],
                    '46' => ['value' => '46', 'label' => '46'],
                    '47' => ['value' => '47', 'label' => '47'],
                    '48' => ['value' => '48', 'label' => '48'],
                    '49' => ['value' => '49', 'label' => '49'],
                    '50' => ['value' => '50', 'label' => '50'],
                    '51' => ['value' => '51', 'label' => '51'],
                    '52' => ['value' => '52', 'label' => '52'],
                    '53' => ['value' => '53', 'label' => '53'],
                    '54' => ['value' => '54', 'label' => '54'],
                    '55' => ['value' => '55', 'label' => '55'],
                    '56' => ['value' => '56', 'label' => '56'],
                    '57' => ['value' => '57', 'label' => '57'],
                    '58' => ['value' => '58', 'label' => '58'],
                    '59' => ['value' => '59', 'label' => '59'],
                ],
            )
            ->willReturnSelf();
        $minuteElement->expects(self::once())
            ->method('setEmptyOption')
            ->with('')
            ->willReturnSelf();

        $selectHelper = $this->getMockBuilder(FormSelectInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $selectHelper->expects(self::once())
            ->method('setIndent')
            ->with($indent);
        $selectHelper->expects(self::exactly(5))
            ->method('render')
            ->willReturnMap(
                [
                    [$dayElement, $renderedDay],
                    [$monthElement, $renderedMonth],
                    [$yearElement, $renderedYear],
                    [$hourElement, $renderedHour],
                    [$minuteElement, $renderedMinute],
                ],
            );

        $helper = new FormDateTimeSelect($selectHelper);

        $element = $this->getMockBuilder(DateTimeSelectElement::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::once())
            ->method('getName')
            ->willReturn($name);
        $element->expects(self::once())
            ->method('shouldRenderDelimiters')
            ->willReturn($renderDelimiters);
        $element->expects(self::once())
            ->method('getMinYear')
            ->willReturn($minYear);
        $element->expects(self::once())
            ->method('getMaxYear')
            ->willReturn($maxYear);
        $element->expects(self::once())
            ->method('getDayElement')
            ->willReturn($dayElement);
        $element->expects(self::once())
            ->method('getMonthElement')
            ->willReturn($monthElement);
        $element->expects(self::once())
            ->method('getYearElement')
            ->willReturn($yearElement);
        $element->expects(self::once())
            ->method('getHourElement')
            ->willReturn($hourElement);
        $element->expects(self::once())
            ->method('getMinuteElement')
            ->willReturn($minuteElement);
        $element->expects(self::never())
            ->method('getSecondElement');
        $element->expects(self::once())
            ->method('shouldCreateEmptyOption')
            ->willReturn($shouldCreateEmptyOption);
        $element->expects(self::once())
            ->method('shouldShowSeconds')
            ->willReturn(false);

        self::assertSame($excpected, $helper->render($element));
    }
}
