<?php

/**
 * This file is part of the mimmi20/laminasviewrenderer-bootstrap-form package.
 *
 * Copyright (c) 2021-2025, Thomas Mueller <mimmi20@live.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Mimmi20Test\LaminasView\BootstrapForm;

use IntlDateFormatter;
use Laminas\Form\Element\DateSelect as DateSelectElement;
use Laminas\Form\Element\Text;
use Laminas\Form\Exception\DomainException;
use Laminas\Form\Exception\ExtensionNotLoadedException;
use Laminas\Form\Exception\InvalidArgumentException;
use Mimmi20\LaminasView\BootstrapForm\FormDateSelect;
use Override;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;

use function assert;
use function sprintf;

#[Group('form-date-select')]
final class FormDateSelectTest extends TestCase
{
    private FormDateSelect $helper;

    /** @throws ExtensionNotLoadedException */
    #[Override]
    protected function setUp(): void
    {
        $this->helper = new FormDateSelect();
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
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
                'Mimmi20\LaminasView\BootstrapForm\FormDateSelect::render',
                DateSelectElement::class,
            ),
        );
        $this->expectExceptionCode(0);

        $this->helper->render($element);
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
     */
    public function testRenderWithoutName(): void
    {
        $element = $this->createMock(DateSelectElement::class);
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
            ->method('shouldCreateEmptyOption');

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage(
            sprintf(
                '%s requires that the element has an assigned name; none discovered',
                'Mimmi20\LaminasView\BootstrapForm\FormDateSelect::render',
            ),
        );
        $this->expectExceptionCode(0);

        $this->helper->render($element);
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
     */
    public function testInvokeWithoutName1(): void
    {
        $element = $this->createMock(DateSelectElement::class);
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
            ->method('shouldCreateEmptyOption');

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage(
            sprintf(
                '%s requires that the element has an assigned name; none discovered',
                'Mimmi20\LaminasView\BootstrapForm\FormDateSelect::render',
            ),
        );
        $this->expectExceptionCode(0);

        $helperObject = ($this->helper)();

        assert($helperObject instanceof FormDateSelect);

        $helperObject->render($element);
    }

    /** @throws Exception */
    public function testInvokeWithoutName2(): void
    {
        $element = $this->createMock(DateSelectElement::class);
        $element->expects(self::once())
            ->method('getName')
            ->willReturn(null);
        $element->expects(self::never())
            ->method('shouldRenderDelimiters');

        $locale = 'de_DE';

        try {
            ($this->helper)($element, IntlDateFormatter::FULL, $locale);
            self::fail('expecting throwing an exception');
        } catch (DomainException) {
            self::assertSame(IntlDateFormatter::LONG, $this->helper->getDateType());
            self::assertSame($locale, $this->helper->getLocale());
        }
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
