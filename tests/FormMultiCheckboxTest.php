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
use Mimmi20\LaminasView\BootstrapForm\FormMultiCheckbox;
use PHPUnit\Event\NoPreviousThrowableException;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;

use function sprintf;

use const PHP_EOL;

#[Group('form-multi-checkbox')]
final class FormMultiCheckboxTest extends TestCase
{
    /** @throws Exception */
    public function testSetGetLabelAttributes(): void
    {
        $helper = new FormMultiCheckbox();

        self::assertSame([], $helper->getLabelAttributes());

        $labelAttributes = ['class' => 'test-class', 'aria-label' => 'test'];

        self::assertSame($helper, $helper->setLabelAttributes($labelAttributes));
        self::assertSame($labelAttributes, $helper->getLabelAttributes());
    }

    /** @throws Exception */
    public function testSetGetSeperator(): void
    {
        $helper = new FormMultiCheckbox();

        self::assertSame('', $helper->getSeparator());

        $seperator = '::test::';

        self::assertSame($helper, $helper->setSeparator($seperator));
        self::assertSame($seperator, $helper->getSeparator());
    }

    /** @throws InvalidArgumentException */
    public function testSetWrongLabelPosition(): void
    {
        $helper = new FormMultiCheckbox();

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
        $helper = new FormMultiCheckbox();

        $labelPosition = BaseFormRow::LABEL_PREPEND;

        self::assertSame(BaseFormRow::LABEL_APPEND, $helper->getLabelPosition());

        $helper->setLabelPosition($labelPosition);

        self::assertSame($labelPosition, $helper->getLabelPosition());
    }

    /** @throws Exception */
    public function testSetGetUseHiddenElement(): void
    {
        $helper = new FormMultiCheckbox();

        self::assertFalse($helper->getUseHiddenElement());

        $helper->setUseHiddenElement(true);

        self::assertTrue($helper->getUseHiddenElement());
    }

    /** @throws Exception */
    public function testSetGetUncheckedValue(): void
    {
        $helper = new FormMultiCheckbox();

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
        $helper = new FormMultiCheckbox();

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
        $helper = new FormMultiCheckbox();

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
                'Mimmi20\LaminasView\BootstrapForm\FormMultiCheckbox::getName',
            ),
        );
        $this->expectExceptionCode(0);

        $helper->render($element);
    }

    /** @throws Exception */
    public function testSetGetIndent1(): void
    {
        $helper = new FormMultiCheckbox();

        self::assertSame($helper, $helper->setIndent(4));
        self::assertSame('    ', $helper->getIndent());
    }

    /** @throws Exception */
    public function testSetGetIndent2(): void
    {
        $helper = new FormMultiCheckbox();

        self::assertSame($helper, $helper->setIndent('  '));
        self::assertSame('  ', $helper->getIndent());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws NoPreviousThrowableException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testRenderWithName(): void
    {
        $helper = new FormMultiCheckbox();

        $name    = 'text.multi';
        $options = ['a' => 'b', 'c' => 'd'];
        $value   = 'b';

        $expected = '<div class="form-check">' . PHP_EOL . '    <label class="form-check-label">' . PHP_EOL
            . '        <input name="text.multi&#x5B;&#x5D;" type="checkbox" value="a" class="form-check-input">'
            . PHP_EOL . '        <span>b</span>' . PHP_EOL . '    </label>' . PHP_EOL . '</div>' . PHP_EOL
            . '<div class="form-check">' . PHP_EOL . '    <label class="form-check-label">' . PHP_EOL
            . '        <input name="text.multi&#x5B;&#x5D;" type="checkbox" value="c" class="form-check-input">'
            . PHP_EOL . '        <span>d</span>' . PHP_EOL . '    </label>' . PHP_EOL . '</div>';

        $element = $this->createMock(Radio::class);
        $element->expects(self::once())
            ->method('getName')
            ->willReturn($name);
        $element->expects(self::once())
            ->method('getValueOptions')
            ->willReturn($options);
        $element->expects(self::once())
            ->method('getAttributes')
            ->willReturn([]);
        $element->expects(self::once())
            ->method('getValue')
            ->willReturn($value);
        $element->expects(self::once())
            ->method('useHiddenElement')
            ->willReturn(false);
        $element->expects(self::once())
            ->method('getLabelAttributes')
            ->willReturn([]);
        $element->expects(self::exactly(13))
            ->method('getOption')
            ->willReturn(null);
        $element->expects(self::exactly(2))
            ->method('getLabelOption')
            ->with('disable_html_escape')
            ->willReturn(null);
        $element->expects(self::once())
            ->method('hasLabelOption')
            ->with('label_position')
            ->willReturn(false);
        $element->expects(self::never())
            ->method('getUncheckedValue');

        self::assertSame($expected, $helper->render($element));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
     */
    public function testInvokeWithoutParameters(): void
    {
        $helper = new FormMultiCheckbox();

        self::assertSame($helper, ($helper)());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws NoPreviousThrowableException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testInvokeWithName(): void
    {
        $helper = new FormMultiCheckbox();

        $name    = 'text.multi';
        $options = ['a' => 'b', 'c' => 'd'];
        $value   = 'b';

        $expected = '<div class="form-check">' . PHP_EOL . '    <label class="form-check-label">' . PHP_EOL
            . '        <input name="text.multi&#x5B;&#x5D;" type="checkbox" value="a" class="form-check-input">'
            . PHP_EOL . '        <span>b</span>' . PHP_EOL . '    </label>' . PHP_EOL . '</div>' . PHP_EOL
            . '<div class="form-check">' . PHP_EOL . '    <label class="form-check-label">' . PHP_EOL
            . '        <input name="text.multi&#x5B;&#x5D;" type="checkbox" value="c" class="form-check-input">'
            . PHP_EOL . '        <span>d</span>' . PHP_EOL . '    </label>' . PHP_EOL . '</div>';

        $element = $this->createMock(Radio::class);
        $element->expects(self::once())
            ->method('getName')
            ->willReturn($name);
        $element->expects(self::once())
            ->method('getValueOptions')
            ->willReturn($options);
        $element->expects(self::once())
            ->method('getAttributes')
            ->willReturn([]);
        $element->expects(self::once())
            ->method('getValue')
            ->willReturn($value);
        $element->expects(self::once())
            ->method('useHiddenElement')
            ->willReturn(false);
        $element->expects(self::once())
            ->method('getLabelAttributes')
            ->willReturn([]);
        $element->expects(self::exactly(13))
            ->method('getOption')
            ->willReturn(null);
        $element->expects(self::exactly(2))
            ->method('getLabelOption')
            ->with('disable_html_escape')
            ->willReturn(null);
        $element->expects(self::once())
            ->method('hasLabelOption')
            ->with('label_position')
            ->willReturn(false);
        $element->expects(self::never())
            ->method('getUncheckedValue');

        self::assertSame($expected, ($helper)($element));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws NoPreviousThrowableException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testInvokeWithNameAndLabelPosition(): void
    {
        $helper = new FormMultiCheckbox();

        $name    = 'text.multi';
        $options = ['a' => 'b', 'c' => 'd'];
        $value   = 'b';

        $expected = '<div class="form-check">' . PHP_EOL . '    <label class="form-check-label">'
            . PHP_EOL . '        <span>b</span>' . PHP_EOL
            . '        <input name="text.multi&#x5B;&#x5D;" type="checkbox" value="a" class="form-check-input">'
            . PHP_EOL . '    </label>' . PHP_EOL . '</div>' . PHP_EOL
            . '<div class="form-check">' . PHP_EOL . '    <label class="form-check-label">'
            . PHP_EOL . '        <span>d</span>' . PHP_EOL
            . '        <input name="text.multi&#x5B;&#x5D;" type="checkbox" value="c" class="form-check-input">'
            . PHP_EOL . '    </label>' . PHP_EOL . '</div>';

        $element = $this->createMock(Radio::class);
        $element->expects(self::once())
            ->method('getName')
            ->willReturn($name);
        $element->expects(self::once())
            ->method('getValueOptions')
            ->willReturn($options);
        $element->expects(self::once())
            ->method('getAttributes')
            ->willReturn([]);
        $element->expects(self::once())
            ->method('getValue')
            ->willReturn($value);
        $element->expects(self::once())
            ->method('useHiddenElement')
            ->willReturn(false);
        $element->expects(self::once())
            ->method('getLabelAttributes')
            ->willReturn([]);
        $element->expects(self::exactly(13))
            ->method('getOption')
            ->willReturn(null);
        $element->expects(self::exactly(2))
            ->method('getLabelOption')
            ->with('disable_html_escape')
            ->willReturn(null);
        $element->expects(self::once())
            ->method('hasLabelOption')
            ->with('label_position')
            ->willReturn(false);
        $element->expects(self::never())
            ->method('getUncheckedValue');

        self::assertSame($expected, ($helper)($element, BaseFormRow::LABEL_PREPEND));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws NoPreviousThrowableException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testInvokeWithNameAndLabelPosition2(): void
    {
        $helper = new FormMultiCheckbox();

        $name    = 'text.multi';
        $options = ['a' => 'b', 'c' => 'd'];
        $value   = 'b';

        $expected = '<div class="form-check">' . PHP_EOL . '    <label class="form-check-label">' . PHP_EOL
            . '        <input name="text.multi&#x5B;&#x5D;" type="checkbox" value="a" class="form-check-input">'
            . PHP_EOL . '        <span>b</span>' . PHP_EOL . '    </label>' . PHP_EOL . '</div>' . PHP_EOL
            . '<div class="form-check">' . PHP_EOL . '    <label class="form-check-label">' . PHP_EOL
            . '        <input name="text.multi&#x5B;&#x5D;" type="checkbox" value="c" class="form-check-input">'
            . PHP_EOL . '        <span>d</span>' . PHP_EOL . '    </label>' . PHP_EOL . '</div>';

        $element = $this->createMock(Radio::class);
        $element->expects(self::once())
            ->method('getName')
            ->willReturn($name);
        $element->expects(self::once())
            ->method('getValueOptions')
            ->willReturn($options);
        $element->expects(self::once())
            ->method('getAttributes')
            ->willReturn([]);
        $element->expects(self::once())
            ->method('getValue')
            ->willReturn($value);
        $element->expects(self::once())
            ->method('useHiddenElement')
            ->willReturn(false);
        $element->expects(self::once())
            ->method('getLabelAttributes')
            ->willReturn([]);
        $element->expects(self::exactly(13))
            ->method('getOption')
            ->willReturn(null);
        $element->expects(self::exactly(2))
            ->method('getLabelOption')
            ->with('disable_html_escape')
            ->willReturn(null);
        $element->expects(self::once())
            ->method('hasLabelOption')
            ->with('label_position')
            ->willReturn(false);
        $element->expects(self::never())
            ->method('getUncheckedValue');

        self::assertSame($expected, ($helper)($element, BaseFormRow::LABEL_APPEND));
    }
}
