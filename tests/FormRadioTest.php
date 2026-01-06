<?php

/**
 * This file is part of the mimmi20/laminasviewrenderer-bootstrap-form package.
 *
 * Copyright (c) 2021-2026, Thomas Mueller <mimmi20@live.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Mimmi20Test\LaminasView\BootstrapForm;

use Laminas\Form\Element\MultiCheckbox as MultiCheckboxElement;
use Laminas\Form\Element\Radio;
use Laminas\Form\Element\Text;
use Laminas\Form\Exception\DomainException;
use Laminas\Form\Exception\InvalidArgumentException;
use Laminas\Form\View\Helper\FormRow as BaseFormRow;
use Mimmi20\LaminasView\BootstrapForm\FormRadio;
use PHPUnit\Event\NoPreviousThrowableException;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[Group('form-radio')]
final class FormRadioTest extends TestCase
{
    /** @throws Exception */
    public function testSetGetLabelAttributes(): void
    {
        $helper = new FormRadio();

        self::assertSame([], $helper->getLabelAttributes());

        $labelAttributes = ['class' => 'test-class', 'aria-label' => 'test'];

        self::assertSame($helper, $helper->setLabelAttributes($labelAttributes));
        self::assertSame($labelAttributes, $helper->getLabelAttributes());
    }

    /** @throws Exception */
    public function testSetGetSeperator(): void
    {
        $helper = new FormRadio();

        self::assertSame('', $helper->getSeparator());

        $seperator = '::test::';

        self::assertSame($helper, $helper->setSeparator($seperator));
        self::assertSame($seperator, $helper->getSeparator());
    }

    /** @throws InvalidArgumentException */
    public function testSetWrongLabelPosition(): void
    {
        $helper = new FormRadio();

        $labelPosition = 'abc';

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            sprintf(
                '%s expects either %s::LABEL_APPEND or %s::LABEL_PREPEND; received "%s"',
                'Mimmi20\LaminasView\BootstrapForm\LabelPositionTrait::setLabelPosition',
                BaseFormRow::class,
                BaseFormRow::class,
                $labelPosition,
            ),
        );
        $this->expectExceptionCode(0);

        $helper->setLabelPosition($labelPosition);
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetGetLabelPosition(): void
    {
        $helper = new FormRadio();

        $labelPosition = BaseFormRow::LABEL_PREPEND;

        self::assertSame(BaseFormRow::LABEL_APPEND, $helper->getLabelPosition());

        $helper->setLabelPosition($labelPosition);

        self::assertSame($labelPosition, $helper->getLabelPosition());
    }

    /** @throws Exception */
    public function testSetGetUseHiddenElement(): void
    {
        $helper = new FormRadio();

        self::assertFalse($helper->getUseHiddenElement());

        $helper->setUseHiddenElement(true);

        self::assertTrue($helper->getUseHiddenElement());
    }

    /** @throws Exception */
    public function testSetGetUncheckedValue(): void
    {
        $helper = new FormRadio();

        $uncheckedValue = '0';

        self::assertSame('', $helper->getUncheckedValue());

        $helper->setUncheckedValue($uncheckedValue);

        self::assertSame($uncheckedValue, $helper->getUncheckedValue());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws NoPreviousThrowableException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testRenderWithWrongElement(): void
    {
        $helper = new FormRadio();

        $element = $this->createMock(Text::class);
        $element->expects(self::never())
            ->method('getName');
        $element->expects(self::never())
            ->method('getAttributes');
        $element->expects(self::never())
            ->method('getValue');
        $element->expects(self::never())
            ->method('getLabelAttributes');
        $element->expects(self::never())
            ->method('getOption');
        $element->expects(self::never())
            ->method('getLabelOption');
        $element->expects(self::never())
            ->method('hasLabelOption');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            sprintf(
                '%s requires that the element is of type %s',
                'Mimmi20\LaminasView\BootstrapForm\AbstractFormMultiCheckbox::render',
                MultiCheckboxElement::class,
            ),
        );
        $this->expectExceptionCode(0);

        $helper->render($element);
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws NoPreviousThrowableException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testRenderWithoutName(): void
    {
        $helper = new FormRadio();

        $element = $this->createMock(Radio::class);
        $element->expects(self::once())
            ->method('getName')
            ->willReturn(null);
        $element->expects(self::never())
            ->method('getValueOptions');
        $element->expects(self::never())
            ->method('getAttributes');
        $element->expects(self::never())
            ->method('getValue');
        $element->expects(self::never())
            ->method('useHiddenElement');
        $element->expects(self::never())
            ->method('getLabelAttributes');
        $element->expects(self::never())
            ->method('getOption');
        $element->expects(self::never())
            ->method('getLabelOption');
        $element->expects(self::never())
            ->method('hasLabelOption');
        $element->expects(self::never())
            ->method('getUncheckedValue');

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage(
            sprintf(
                '%s requires that the element has an assigned name; none discovered',
                'Mimmi20\LaminasView\BootstrapForm\FormRadio::getName',
            ),
        );
        $this->expectExceptionCode(0);

        $helper->render($element);
    }

    /** @throws Exception */
    public function testSetGetIndent1(): void
    {
        $helper = new FormRadio();

        self::assertSame($helper, $helper->setIndent(4));
        self::assertSame('    ', $helper->getIndent());
    }

    /** @throws Exception */
    public function testSetGetIndent2(): void
    {
        $helper = new FormRadio();

        self::assertSame($helper, $helper->setIndent('  '));
        self::assertSame('  ', $helper->getIndent());
    }
}
