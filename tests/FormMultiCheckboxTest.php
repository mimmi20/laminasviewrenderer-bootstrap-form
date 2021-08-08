<?php
/**
 * This file is part of the mimmi20/laminasviewrenderer-bootstrap-form package.
 *
 * Copyright (c) 2021, Thomas Mueller <mimmi20@live.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Mimmi20Test\LaminasView\BootstrapForm;

use Laminas\Form\Element\Hidden;
use Laminas\Form\Element\MultiCheckbox as MultiCheckboxElement;
use Laminas\Form\Element\Radio;
use Laminas\Form\Element\Text;
use Laminas\Form\Exception\DomainException;
use Laminas\Form\Exception\InvalidArgumentException;
use Laminas\Form\View\Helper\FormRow as BaseFormRow;
use Laminas\I18n\View\Helper\Translate;
use Laminas\View\Helper\Doctype;
use Laminas\View\Helper\EscapeHtml;
use Laminas\View\Helper\EscapeHtmlAttr;
use Mimmi20\LaminasView\BootstrapForm\Form;
use Mimmi20\LaminasView\BootstrapForm\FormHiddenInterface;
use Mimmi20\LaminasView\BootstrapForm\FormLabelInterface;
use Mimmi20\LaminasView\BootstrapForm\FormMultiCheckbox;
use Mimmi20\LaminasView\Helper\HtmlElement\Helper\HtmlElementInterface;
use PHPUnit\Framework\Constraint\IsInstanceOf;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;

use function assert;
use function sprintf;

use const PHP_EOL;

final class FormMultiCheckboxTest extends TestCase
{
    /**
     * @throws Exception
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testSetGetLabelAttributes(): void
    {
        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::never())
            ->method('__invoke');

        $escapeHtmlAttr = $this->getMockBuilder(EscapeHtmlAttr::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtmlAttr->expects(self::never())
            ->method('__invoke');

        $doctype = $this->getMockBuilder(Doctype::class)
            ->disableOriginalConstructor()
            ->getMock();
        $doctype->expects(self::never())
            ->method('__invoke');

        $formLabel = $this->getMockBuilder(FormLabelInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formLabel->expects(self::never())
            ->method('openTag');
        $formLabel->expects(self::never())
            ->method('closeTag');

        $htmlElement = $this->getMockBuilder(HtmlElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $htmlElement->expects(self::never())
            ->method('toHtml');

        $formHidden = $this->getMockBuilder(FormHiddenInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formHidden->expects(self::never())
            ->method('render');

        $helper = new FormMultiCheckbox($escapeHtml, $escapeHtmlAttr, $doctype, $formLabel, $htmlElement, $formHidden, null);

        self::assertSame([], $helper->getLabelAttributes());

        $labelAttributes = ['class' => 'test-class', 'aria-label' => 'test'];

        self::assertSame($helper, $helper->setLabelAttributes($labelAttributes));
        self::assertSame($labelAttributes, $helper->getLabelAttributes());
    }

    /**
     * @throws Exception
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testSetGetSeperator(): void
    {
        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::never())
            ->method('__invoke');

        $escapeHtmlAttr = $this->getMockBuilder(EscapeHtmlAttr::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtmlAttr->expects(self::never())
            ->method('__invoke');

        $doctype = $this->getMockBuilder(Doctype::class)
            ->disableOriginalConstructor()
            ->getMock();
        $doctype->expects(self::never())
            ->method('__invoke');

        $formLabel = $this->getMockBuilder(FormLabelInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formLabel->expects(self::never())
            ->method('openTag');
        $formLabel->expects(self::never())
            ->method('closeTag');

        $htmlElement = $this->getMockBuilder(HtmlElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $htmlElement->expects(self::never())
            ->method('toHtml');

        $formHidden = $this->getMockBuilder(FormHiddenInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formHidden->expects(self::never())
            ->method('render');

        $helper = new FormMultiCheckbox($escapeHtml, $escapeHtmlAttr, $doctype, $formLabel, $htmlElement, $formHidden, null);

        self::assertSame('', $helper->getSeparator());

        $seperator = '::test::';

        self::assertSame($helper, $helper->setSeparator($seperator));
        self::assertSame($seperator, $helper->getSeparator());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetWrongLabelPosition(): void
    {
        $labelPosition = 'abc';

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::never())
            ->method('__invoke');

        $escapeHtmlAttr = $this->getMockBuilder(EscapeHtmlAttr::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtmlAttr->expects(self::never())
            ->method('__invoke');

        $doctype = $this->getMockBuilder(Doctype::class)
            ->disableOriginalConstructor()
            ->getMock();
        $doctype->expects(self::never())
            ->method('__invoke');

        $formLabel = $this->getMockBuilder(FormLabelInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formLabel->expects(self::never())
            ->method('openTag');
        $formLabel->expects(self::never())
            ->method('closeTag');

        $htmlElement = $this->getMockBuilder(HtmlElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $htmlElement->expects(self::never())
            ->method('toHtml');

        $formHidden = $this->getMockBuilder(FormHiddenInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formHidden->expects(self::never())
            ->method('render');

        $helper = new FormMultiCheckbox($escapeHtml, $escapeHtmlAttr, $doctype, $formLabel, $htmlElement, $formHidden, null);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            sprintf(
                '%s expects either %s::LABEL_APPEND or %s::LABEL_PREPEND; received "%s"',
                'Mimmi20\LaminasView\BootstrapForm\LabelPositionTrait::setLabelPosition',
                BaseFormRow::class,
                BaseFormRow::class,
                $labelPosition
            )
        );
        $this->expectExceptionCode(0);

        $helper->setLabelPosition($labelPosition);
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testSetGetLabelPosition(): void
    {
        $labelPosition = BaseFormRow::LABEL_PREPEND;

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::never())
            ->method('__invoke');

        $escapeHtmlAttr = $this->getMockBuilder(EscapeHtmlAttr::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtmlAttr->expects(self::never())
            ->method('__invoke');

        $doctype = $this->getMockBuilder(Doctype::class)
            ->disableOriginalConstructor()
            ->getMock();
        $doctype->expects(self::never())
            ->method('__invoke');

        $formLabel = $this->getMockBuilder(FormLabelInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formLabel->expects(self::never())
            ->method('openTag');
        $formLabel->expects(self::never())
            ->method('closeTag');

        $htmlElement = $this->getMockBuilder(HtmlElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $htmlElement->expects(self::never())
            ->method('toHtml');

        $formHidden = $this->getMockBuilder(FormHiddenInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formHidden->expects(self::never())
            ->method('render');

        $helper = new FormMultiCheckbox($escapeHtml, $escapeHtmlAttr, $doctype, $formLabel, $htmlElement, $formHidden, null);

        $helper->setLabelPosition($labelPosition);

        self::assertSame($labelPosition, $helper->getLabelPosition());
    }

    /**
     * @throws Exception
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testSetGetUseHiddenElement(): void
    {
        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::never())
            ->method('__invoke');

        $escapeHtmlAttr = $this->getMockBuilder(EscapeHtmlAttr::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtmlAttr->expects(self::never())
            ->method('__invoke');

        $doctype = $this->getMockBuilder(Doctype::class)
            ->disableOriginalConstructor()
            ->getMock();
        $doctype->expects(self::never())
            ->method('__invoke');
        $doctype->expects(self::never())
            ->method('isXhtml');

        $formLabel = $this->getMockBuilder(FormLabelInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formLabel->expects(self::never())
            ->method('openTag');
        $formLabel->expects(self::never())
            ->method('closeTag');

        $htmlElement = $this->getMockBuilder(HtmlElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $htmlElement->expects(self::never())
            ->method('toHtml');

        $formHidden = $this->getMockBuilder(FormHiddenInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formHidden->expects(self::never())
            ->method('render');

        $helper = new FormMultiCheckbox($escapeHtml, $escapeHtmlAttr, $doctype, $formLabel, $htmlElement, $formHidden, null);

        self::assertFalse($helper->getUseHiddenElement());

        $helper->setUseHiddenElement(true);

        self::assertTrue($helper->getUseHiddenElement());
    }

    /**
     * @throws Exception
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testSetGetUncheckedValue(): void
    {
        $uncheckedValue = '0';

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::never())
            ->method('__invoke');

        $escapeHtmlAttr = $this->getMockBuilder(EscapeHtmlAttr::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtmlAttr->expects(self::never())
            ->method('__invoke');

        $doctype = $this->getMockBuilder(Doctype::class)
            ->disableOriginalConstructor()
            ->getMock();
        $doctype->expects(self::never())
            ->method('__invoke');
        $doctype->expects(self::never())
            ->method('isXhtml');

        $formLabel = $this->getMockBuilder(FormLabelInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formLabel->expects(self::never())
            ->method('openTag');
        $formLabel->expects(self::never())
            ->method('closeTag');

        $htmlElement = $this->getMockBuilder(HtmlElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $htmlElement->expects(self::never())
            ->method('toHtml');

        $formHidden = $this->getMockBuilder(FormHiddenInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formHidden->expects(self::never())
            ->method('render');

        $helper = new FormMultiCheckbox($escapeHtml, $escapeHtmlAttr, $doctype, $formLabel, $htmlElement, $formHidden, null);

        self::assertSame('', $helper->getUncheckedValue());

        $helper->setUncheckedValue($uncheckedValue);

        self::assertSame($uncheckedValue, $helper->getUncheckedValue());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
     */
    public function testRenderWithWrongElement(): void
    {
        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::never())
            ->method('__invoke');

        $escapeHtmlAttr = $this->getMockBuilder(EscapeHtmlAttr::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtmlAttr->expects(self::never())
            ->method('__invoke');

        $doctype = $this->getMockBuilder(Doctype::class)
            ->disableOriginalConstructor()
            ->getMock();
        $doctype->expects(self::never())
            ->method('__invoke');

        $formLabel = $this->getMockBuilder(FormLabelInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formLabel->expects(self::never())
            ->method('openTag');
        $formLabel->expects(self::never())
            ->method('closeTag');

        $htmlElement = $this->getMockBuilder(HtmlElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $htmlElement->expects(self::never())
            ->method('toHtml');

        $formHidden = $this->getMockBuilder(FormHiddenInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formHidden->expects(self::never())
            ->method('render');

        $helper = new FormMultiCheckbox($escapeHtml, $escapeHtmlAttr, $doctype, $formLabel, $htmlElement, $formHidden, null);

        $element = $this->getMockBuilder(Text::class)
            ->disableOriginalConstructor()
            ->getMock();
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
                MultiCheckboxElement::class
            )
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
        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::never())
            ->method('__invoke');

        $escapeHtmlAttr = $this->getMockBuilder(EscapeHtmlAttr::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtmlAttr->expects(self::never())
            ->method('__invoke');

        $doctype = $this->getMockBuilder(Doctype::class)
            ->disableOriginalConstructor()
            ->getMock();
        $doctype->expects(self::never())
            ->method('__invoke');
        $doctype->expects(self::never())
            ->method('isXhtml');

        $formLabel = $this->getMockBuilder(FormLabelInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formLabel->expects(self::never())
            ->method('openTag');
        $formLabel->expects(self::never())
            ->method('closeTag');

        $htmlElement = $this->getMockBuilder(HtmlElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $htmlElement->expects(self::never())
            ->method('toHtml');

        $formHidden = $this->getMockBuilder(FormHiddenInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formHidden->expects(self::never())
            ->method('render');

        $helper = new FormMultiCheckbox($escapeHtml, $escapeHtmlAttr, $doctype, $formLabel, $htmlElement, $formHidden, null);

        $element = $this->getMockBuilder(Radio::class)
            ->disableOriginalConstructor()
            ->getMock();
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
                'Mimmi20\LaminasView\BootstrapForm\FormMultiCheckbox::getName'
            )
        );
        $this->expectExceptionCode(0);

        $helper->render($element);
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testRenderWithoutId(): void
    {
        $name            = 'test-name';
        $value1          = 'xyz';
        $value2          = 'def';
        $value2Escaped   = 'def-escaped';
        $value3          = 'abc';
        $class           = 'test-class';
        $ariaLabel       = 'test';
        $labelClass      = 'xyz';
        $valueOptions    = [$value3 => $value2];
        $attributes      = ['class' => $class, 'aria-label' => $ariaLabel];
        $labelAttributes = ['class' => $labelClass];
        $labelStart      = '<label>';
        $labelEnd        = '</label>';
        $renderedField   = PHP_EOL .
            '    ' . $labelStart . PHP_EOL .
            '        ' . sprintf('<input class="form-check-input&#x20;%s" aria-label="%s" name="%s&#x5B;&#x5D;" type="checkbox" value="%s">', $class, $ariaLabel, $name, $value3) . PHP_EOL .
            '        ' . sprintf('<span>%s</span>', $value2Escaped) . PHP_EOL .
            '    ' . $labelEnd . PHP_EOL .
            '    ';
        $expected        = '<div></div>';

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::once())
            ->method('__invoke')
            ->with($value2)
            ->willReturn($value2Escaped);

        $escapeHtmlAttr = $this->getMockBuilder(EscapeHtmlAttr::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtmlAttr->expects(self::never())
            ->method('__invoke');

        $doctype = $this->getMockBuilder(Doctype::class)
            ->disableOriginalConstructor()
            ->getMock();
        $doctype->expects(self::never())
            ->method('__invoke');
        $doctype->expects(self::once())
            ->method('isXhtml')
            ->willReturn(false);

        $formLabel = $this->getMockBuilder(FormLabelInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formLabel->expects(self::once())
            ->method('openTag')
            ->with(
                [
                    'class' => sprintf('form-check-label %s', $labelClass),
                ]
            )
            ->willReturn($labelStart);
        $formLabel->expects(self::once())
            ->method('closeTag')
            ->willReturn($labelEnd);

        $htmlElement = $this->getMockBuilder(HtmlElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $htmlElement->expects(self::once())
            ->method('toHtml')
            ->with('div', ['class' => ['form-check']], $renderedField)
            ->willReturn($expected);

        $formHidden = $this->getMockBuilder(FormHiddenInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formHidden->expects(self::never())
            ->method('render');

        $helper = new FormMultiCheckbox($escapeHtml, $escapeHtmlAttr, $doctype, $formLabel, $htmlElement, $formHidden, null);

        $element = $this->getMockBuilder(Radio::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::once())
            ->method('getName')
            ->willReturn($name);
        $element->expects(self::once())
            ->method('getValueOptions')
            ->willReturn($valueOptions);
        $element->expects(self::once())
            ->method('getAttributes')
            ->willReturn($attributes);
        $element->expects(self::once())
            ->method('getValue')
            ->willReturn($value1);
        $element->expects(self::once())
            ->method('useHiddenElement')
            ->willReturn(false);
        $element->expects(self::once())
            ->method('getLabelAttributes')
            ->willReturn($labelAttributes);
        $element->expects(self::once())
            ->method('getOption')
            ->with('layout')
            ->willReturn(Form::LAYOUT_VERTICAL);
        $element->expects(self::once())
            ->method('getLabelOption')
            ->with('disable_html_escape')
            ->willReturn(false);
        $element->expects(self::once())
            ->method('hasLabelOption')
            ->with('label_position')
            ->willReturn(false);
        $element->expects(self::never())
            ->method('getUncheckedValue');

        self::assertSame('    ' . $expected, $helper->render($element));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testRenderWithIdAndNoWarp(): void
    {
        $name            = 'test-name';
        $id              = 'test-id';
        $value1          = 'xyz';
        $value2          = 'def';
        $value2Escaped   = 'def-escaped';
        $value3          = 'abc';
        $class           = 'test-class';
        $ariaLabel       = 'test';
        $labelClass      = 'xyz';
        $valueOptions    = [$value3 => $value2];
        $attributes      = ['class' => $class, 'aria-label' => $ariaLabel, 'id' => $id];
        $labelAttributes = ['class' => $labelClass];
        $labelStart      = '<label>';
        $labelEnd        = '</label>';
        $expected        = '<div></div>';
        $renderedField   = PHP_EOL .
            '        ' . sprintf('<input class="form-check-input&#x20;%s" aria-label="%s" id="%s" name="%s&#x5B;&#x5D;" type="checkbox" value="%s">', $class, $ariaLabel, $id, $name, $value3) . PHP_EOL .
            '        ' . $labelStart . $value2Escaped . $labelEnd . PHP_EOL .
            '    ';
        $wrap            = false;

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::once())
            ->method('__invoke')
            ->with($value2)
            ->willReturn($value2Escaped);

        $escapeHtmlAttr = $this->getMockBuilder(EscapeHtmlAttr::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtmlAttr->expects(self::never())
            ->method('__invoke');

        $doctype = $this->getMockBuilder(Doctype::class)
            ->disableOriginalConstructor()
            ->getMock();
        $doctype->expects(self::never())
            ->method('__invoke');
        $doctype->expects(self::once())
            ->method('isXhtml')
            ->willReturn(false);

        $formLabel = $this->getMockBuilder(FormLabelInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formLabel->expects(self::once())
            ->method('openTag')
            ->with(
                [
                    'class' => sprintf('form-check-label %s', $labelClass),
                    'for' => $id,
                ]
            )
            ->willReturn($labelStart);
        $formLabel->expects(self::once())
            ->method('closeTag')
            ->willReturn($labelEnd);

        $htmlElement = $this->getMockBuilder(HtmlElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $htmlElement->expects(self::once())
            ->method('toHtml')
            ->with('div', ['class' => ['form-check']], $renderedField)
            ->willReturn($expected);

        $formHidden = $this->getMockBuilder(FormHiddenInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formHidden->expects(self::never())
            ->method('render');

        $helper = new FormMultiCheckbox($escapeHtml, $escapeHtmlAttr, $doctype, $formLabel, $htmlElement, $formHidden, null);

        $element = $this->getMockBuilder(Radio::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::once())
            ->method('getName')
            ->willReturn($name);
        $element->expects(self::once())
            ->method('getValueOptions')
            ->willReturn($valueOptions);
        $element->expects(self::once())
            ->method('getAttributes')
            ->willReturn($attributes);
        $element->expects(self::once())
            ->method('getValue')
            ->willReturn($value1);
        $element->expects(self::once())
            ->method('useHiddenElement')
            ->willReturn(false);
        $element->expects(self::once())
            ->method('getLabelAttributes')
            ->willReturn($labelAttributes);
        $element->expects(self::once())
            ->method('getOption')
            ->with('layout')
            ->willReturn(Form::LAYOUT_VERTICAL);
        $element->expects(self::exactly(4))
            ->method('getLabelOption')
            ->withConsecutive(['label_position'], ['disable_html_escape'], ['always_wrap'], ['always_wrap'])
            ->willReturnOnConsecutiveCalls(BaseFormRow::LABEL_APPEND, false, $wrap, $wrap);
        $element->expects(self::once())
            ->method('hasLabelOption')
            ->with('label_position')
            ->willReturn(true);
        $element->expects(self::never())
            ->method('getUncheckedValue');

        self::assertSame('    ' . $expected, $helper->render($element));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testRenderPrependWithoutId(): void
    {
        $name            = 'test-name';
        $value1          = 'xyz';
        $value2          = 'def';
        $value2Escaped   = 'def-escaped';
        $value3          = 'abc';
        $class           = 'test-class';
        $ariaLabel       = 'test';
        $labelClass      = 'xyz';
        $valueOptions    = [$value3 => $value2];
        $attributes      = ['class' => $class, 'aria-label' => $ariaLabel];
        $labelAttributes = ['class' => $labelClass];
        $labelStart      = '<label>';
        $labelEnd        = '</label>';
        $renderedField   = PHP_EOL .
            '    ' . $labelStart . PHP_EOL .
            '        ' . sprintf('<span>%s</span>', $value2Escaped) . PHP_EOL .
            '        ' . sprintf('<input class="form-check-input&#x20;%s" aria-label="%s" name="%s&#x5B;&#x5D;" type="checkbox" value="%s">', $class, $ariaLabel, $name, $value3) . PHP_EOL .
            '    ' . $labelEnd . PHP_EOL .
            '    ';
        $expected        = '<div></div>';

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::once())
            ->method('__invoke')
            ->with($value2)
            ->willReturn($value2Escaped);

        $escapeHtmlAttr = $this->getMockBuilder(EscapeHtmlAttr::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtmlAttr->expects(self::never())
            ->method('__invoke');

        $doctype = $this->getMockBuilder(Doctype::class)
            ->disableOriginalConstructor()
            ->getMock();
        $doctype->expects(self::never())
            ->method('__invoke');
        $doctype->expects(self::once())
            ->method('isXhtml')
            ->willReturn(false);

        $formLabel = $this->getMockBuilder(FormLabelInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formLabel->expects(self::once())
            ->method('openTag')
            ->with(
                [
                    'class' => sprintf('form-check-label %s', $labelClass),
                ]
            )
            ->willReturn($labelStart);
        $formLabel->expects(self::once())
            ->method('closeTag')
            ->willReturn($labelEnd);

        $htmlElement = $this->getMockBuilder(HtmlElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $htmlElement->expects(self::once())
            ->method('toHtml')
            ->with('div', ['class' => ['form-check']], $renderedField)
            ->willReturn($expected);

        $formHidden = $this->getMockBuilder(FormHiddenInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formHidden->expects(self::never())
            ->method('render');

        $helper = new FormMultiCheckbox($escapeHtml, $escapeHtmlAttr, $doctype, $formLabel, $htmlElement, $formHidden, null);

        $element = $this->getMockBuilder(Radio::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::once())
            ->method('getName')
            ->willReturn($name);
        $element->expects(self::once())
            ->method('getValueOptions')
            ->willReturn($valueOptions);
        $element->expects(self::once())
            ->method('getAttributes')
            ->willReturn($attributes);
        $element->expects(self::once())
            ->method('getValue')
            ->willReturn($value1);
        $element->expects(self::once())
            ->method('useHiddenElement')
            ->willReturn(false);
        $element->expects(self::once())
            ->method('getLabelAttributes')
            ->willReturn($labelAttributes);
        $element->expects(self::once())
            ->method('getOption')
            ->with('layout')
            ->willReturn(Form::LAYOUT_VERTICAL);
        $element->expects(self::once())
            ->method('getLabelOption')
            ->with('disable_html_escape')
            ->willReturn(false);
        $element->expects(self::once())
            ->method('hasLabelOption')
            ->with('label_position')
            ->willReturn(false);
        $element->expects(self::never())
            ->method('getUncheckedValue');

        $helper->setLabelPosition(BaseFormRow::LABEL_PREPEND);

        self::assertSame('    ' . $expected, $helper->render($element));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testRenderPrependWithIdAndNoWarp(): void
    {
        $name            = 'test-name';
        $id              = 'test-id';
        $value1          = 'xyz';
        $value2          = 'def';
        $value2Escaped   = 'def-escaped';
        $value3          = 'abc';
        $class           = 'test-class';
        $ariaLabel       = 'test';
        $labelClass      = 'xyz';
        $valueOptions    = [$value3 => $value2];
        $attributes      = ['class' => $class, 'aria-label' => $ariaLabel, 'id' => $id];
        $labelAttributes = ['class' => $labelClass];
        $labelStart      = '<label>';
        $labelEnd        = '</label>';
        $expected        = '<div></div>';
        $renderedField   = PHP_EOL .
            '        ' . $labelStart . $value2Escaped . $labelEnd . PHP_EOL .
            '        ' . sprintf('<input class="form-check-input&#x20;%s" aria-label="%s" id="%s" name="%s&#x5B;&#x5D;" type="checkbox" value="%s">', $class, $ariaLabel, $id, $name, $value3) . PHP_EOL .
            '    ';
        $wrap            = false;

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::once())
            ->method('__invoke')
            ->with($value2)
            ->willReturn($value2Escaped);

        $escapeHtmlAttr = $this->getMockBuilder(EscapeHtmlAttr::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtmlAttr->expects(self::never())
            ->method('__invoke');

        $doctype = $this->getMockBuilder(Doctype::class)
            ->disableOriginalConstructor()
            ->getMock();
        $doctype->expects(self::never())
            ->method('__invoke');
        $doctype->expects(self::once())
            ->method('isXhtml')
            ->willReturn(false);

        $formLabel = $this->getMockBuilder(FormLabelInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formLabel->expects(self::once())
            ->method('openTag')
            ->with(
                [
                    'class' => sprintf('form-check-label %s', $labelClass),
                    'for' => $id,
                ]
            )
            ->willReturn($labelStart);
        $formLabel->expects(self::once())
            ->method('closeTag')
            ->willReturn($labelEnd);

        $htmlElement = $this->getMockBuilder(HtmlElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $htmlElement->expects(self::once())
            ->method('toHtml')
            ->with('div', ['class' => ['form-check']], $renderedField)
            ->willReturn($expected);

        $formHidden = $this->getMockBuilder(FormHiddenInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formHidden->expects(self::never())
            ->method('render');

        $helper = new FormMultiCheckbox($escapeHtml, $escapeHtmlAttr, $doctype, $formLabel, $htmlElement, $formHidden, null);

        $element = $this->getMockBuilder(Radio::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::once())
            ->method('getName')
            ->willReturn($name);
        $element->expects(self::once())
            ->method('getValueOptions')
            ->willReturn($valueOptions);
        $element->expects(self::once())
            ->method('getAttributes')
            ->willReturn($attributes);
        $element->expects(self::once())
            ->method('getValue')
            ->willReturn($value1);
        $element->expects(self::once())
            ->method('useHiddenElement')
            ->willReturn(false);
        $element->expects(self::once())
            ->method('getLabelAttributes')
            ->willReturn($labelAttributes);
        $element->expects(self::once())
            ->method('getOption')
            ->with('layout')
            ->willReturn(Form::LAYOUT_VERTICAL);
        $element->expects(self::exactly(4))
            ->method('getLabelOption')
            ->withConsecutive(['label_position'], ['disable_html_escape'], ['always_wrap'], ['always_wrap'])
            ->willReturnOnConsecutiveCalls(BaseFormRow::LABEL_PREPEND, false, $wrap, $wrap);
        $element->expects(self::once())
            ->method('hasLabelOption')
            ->with('label_position')
            ->willReturn(true);
        $element->expects(self::never())
            ->method('getUncheckedValue');

        $helper->setLabelPosition(BaseFormRow::LABEL_PREPEND);

        self::assertSame('    ' . $expected, $helper->render($element));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testRenderWithIdAndNoWarpWithoutEscape(): void
    {
        $name            = 'test-name';
        $id              = 'test-id';
        $value1          = 'xyz';
        $value2          = 'def';
        $value3          = 'abc';
        $class           = 'test-class';
        $ariaLabel       = 'test';
        $labelClass      = 'xyz';
        $valueOptions    = [$value3 => $value2];
        $attributes      = ['class' => $class, 'aria-label' => $ariaLabel, 'id' => $id];
        $labelAttributes = ['class' => $labelClass, 'test'];
        $labelStart      = '<label>';
        $labelEnd        = '</label>';
        $expected        = '<div></div>';
        $renderedField   = PHP_EOL .
            '        ' . sprintf('<input class="form-check-input&#x20;%s" aria-label="%s" id="%s" name="%s&#x5B;&#x5D;" type="checkbox" value="%s">', $class, $ariaLabel, $id, $name, $value3) . PHP_EOL .
            '        ' . $labelStart . $value2 . $labelEnd . PHP_EOL .
            '    ';
        $wrap            = false;

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::never())
            ->method('__invoke');

        $escapeHtmlAttr = $this->getMockBuilder(EscapeHtmlAttr::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtmlAttr->expects(self::never())
            ->method('__invoke');

        $doctype = $this->getMockBuilder(Doctype::class)
            ->disableOriginalConstructor()
            ->getMock();
        $doctype->expects(self::never())
            ->method('__invoke');
        $doctype->expects(self::once())
            ->method('isXhtml')
            ->willReturn(false);

        $formLabel = $this->getMockBuilder(FormLabelInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formLabel->expects(self::once())
            ->method('openTag')
            ->with(
                [
                    'class' => sprintf('form-check-label %s', $labelClass),
                    'for' => $id,
                ]
            )
            ->willReturn($labelStart);
        $formLabel->expects(self::once())
            ->method('closeTag')
            ->willReturn($labelEnd);

        $htmlElement = $this->getMockBuilder(HtmlElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $htmlElement->expects(self::once())
            ->method('toHtml')
            ->with('div', ['class' => ['form-check']], $renderedField)
            ->willReturn($expected);

        $formHidden = $this->getMockBuilder(FormHiddenInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formHidden->expects(self::never())
            ->method('render');

        $helper = new FormMultiCheckbox($escapeHtml, $escapeHtmlAttr, $doctype, $formLabel, $htmlElement, $formHidden, null);

        $element = $this->getMockBuilder(Radio::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::once())
            ->method('getName')
            ->willReturn($name);
        $element->expects(self::once())
            ->method('getValueOptions')
            ->willReturn($valueOptions);
        $element->expects(self::once())
            ->method('getAttributes')
            ->willReturn($attributes);
        $element->expects(self::once())
            ->method('getValue')
            ->willReturn($value1);
        $element->expects(self::once())
            ->method('useHiddenElement')
            ->willReturn(false);
        $element->expects(self::once())
            ->method('getLabelAttributes')
            ->willReturn($labelAttributes);
        $element->expects(self::once())
            ->method('getOption')
            ->with('layout')
            ->willReturn(Form::LAYOUT_VERTICAL);
        $element->expects(self::exactly(4))
            ->method('getLabelOption')
            ->withConsecutive(['label_position'], ['disable_html_escape'], ['always_wrap'], ['always_wrap'])
            ->willReturnOnConsecutiveCalls(BaseFormRow::LABEL_APPEND, true, $wrap, $wrap);
        $element->expects(self::once())
            ->method('hasLabelOption')
            ->with('label_position')
            ->willReturn(true);
        $element->expects(self::never())
            ->method('getUncheckedValue');

        $helper->setLabelPosition(BaseFormRow::LABEL_APPEND);

        self::assertSame('    ' . $expected, $helper->render($element));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testRenderXhtmlWithTranslator(): void
    {
        $name                    = 'test-name';
        $id                      = 'test-id';
        $value1                  = 'xyz';
        $value2                  = 'def';
        $value2Translated        = 'def-translated';
        $value2TranslatedEscaped = 'def-translated-escaped';
        $value3                  = 'abc';
        $class                   = 'test-class';
        $ariaLabel               = 'test';
        $labelClass              = 'xyz';
        $valueOptions            = [$value3 => $value2];
        $attributes              = ['class' => $class, 'aria-label' => $ariaLabel, 'id' => $id];
        $labelAttributes         = ['class' => $labelClass, 'test'];
        $labelStart              = '<label>';
        $labelEnd                = '</label>';
        $expected                = '<div></div>';
        $textDomain              = 'test-domain';
        $renderedField           = PHP_EOL .
            '        ' . sprintf('<input class="form-check-input&#x20;%s" aria-label="%s" id="%s" name="%s&#x5B;&#x5D;" type="checkbox" value="%s"/>', $class, $ariaLabel, $id, $name, $value3) . PHP_EOL .
            '        ' . $labelStart . $value2TranslatedEscaped . $labelEnd . PHP_EOL .
            '    ';
        $wrap                    = false;

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::once())
            ->method('__invoke')
            ->with($value2Translated)
            ->willReturn($value2TranslatedEscaped);

        $escapeHtmlAttr = $this->getMockBuilder(EscapeHtmlAttr::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtmlAttr->expects(self::never())
            ->method('__invoke');

        $doctype = $this->getMockBuilder(Doctype::class)
            ->disableOriginalConstructor()
            ->getMock();
        $doctype->expects(self::never())
            ->method('__invoke');
        $doctype->expects(self::once())
            ->method('isXhtml')
            ->willReturn(true);

        $formLabel = $this->getMockBuilder(FormLabelInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formLabel->expects(self::once())
            ->method('openTag')
            ->with(
                [
                    'class' => sprintf('form-check-label %s', $labelClass),
                    'for' => $id,
                ]
            )
            ->willReturn($labelStart);
        $formLabel->expects(self::once())
            ->method('closeTag')
            ->willReturn($labelEnd);

        $htmlElement = $this->getMockBuilder(HtmlElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $htmlElement->expects(self::once())
            ->method('toHtml')
            ->with('div', ['class' => ['form-check']], $renderedField)
            ->willReturn($expected);

        $translator = $this->getMockBuilder(Translate::class)
            ->disableOriginalConstructor()
            ->getMock();
        $translator->expects(self::once())
            ->method('__invoke')
            ->with($value2, $textDomain)
            ->willReturn($value2Translated);

        $formHidden = $this->getMockBuilder(FormHiddenInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formHidden->expects(self::never())
            ->method('render');

        $helper = new FormMultiCheckbox($escapeHtml, $escapeHtmlAttr, $doctype, $formLabel, $htmlElement, $formHidden, $translator);

        $element = $this->getMockBuilder(Radio::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::once())
            ->method('getName')
            ->willReturn($name);
        $element->expects(self::once())
            ->method('getValueOptions')
            ->willReturn($valueOptions);
        $element->expects(self::once())
            ->method('getAttributes')
            ->willReturn($attributes);
        $element->expects(self::once())
            ->method('getValue')
            ->willReturn($value1);
        $element->expects(self::once())
            ->method('useHiddenElement')
            ->willReturn(false);
        $element->expects(self::once())
            ->method('getLabelAttributes')
            ->willReturn($labelAttributes);
        $element->expects(self::once())
            ->method('getOption')
            ->with('layout')
            ->willReturn(Form::LAYOUT_VERTICAL);
        $element->expects(self::exactly(4))
            ->method('getLabelOption')
            ->withConsecutive(['label_position'], ['disable_html_escape'], ['always_wrap'], ['always_wrap'])
            ->willReturnOnConsecutiveCalls(BaseFormRow::LABEL_APPEND, false, $wrap, $wrap);
        $element->expects(self::once())
            ->method('hasLabelOption')
            ->with('label_position')
            ->willReturn(true);
        $element->expects(self::never())
            ->method('getUncheckedValue');

        $helper->setLabelPosition(BaseFormRow::LABEL_APPEND);
        $helper->setTranslatorTextDomain($textDomain);

        self::assertSame('    ' . $expected, $helper->render($element));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testRenderMultiOption(): void
    {
        $name                    = 'test-name';
        $id                      = 'test-id';
        $value1                  = 'xyz';
        $value2                  = 'def';
        $value2Translated        = 'def-translated';
        $value2TranslatedEscaped = 'def-translated-escaped';
        $value3                  = 'abc';
        $value3Translated        = 'abc-translated';
        $value3TranslatedEscaped = 'abc-translated-escaped';
        $name4                   = 'ghj';
        $name4Translated         = 'ghj-translated';
        $name4TranslatedEscaped  = 'ghj-translated-escaped';
        $value4                  = 'jkl';
        $class                   = 'test-class';
        $ariaLabel               = 'test';
        $labelClass              = 'xyz';
        $valueOptions            = [
            [
                'value' => $value3,
                'label' => $value2,
                'selected' => false,
                'disabled' => false,
                'label_attributes' => ['class' => 'rst'],
                'attributes' => [
                    'class' => 'efg',
                    'id' => $id,
                ],
            ],
            [
                'value' => $value1,
                'label' => $value3,
                'selected' => false,
                'label_attributes' => ['class' => 'rst2'],
                'attributes' => [
                    'class' => 'efg2',
                    'aria-disabled' => 'true',
                    'id' => 'test-id2',
                ],
            ],
            [
                'value' => $value4,
                'label' => $name4,
                'disabled' => false,
                'label_attributes' => ['class' => 'rst3'],
                'attributes' => [
                    'class' => 'efg3',
                    'aria-disabled' => 'false',
                    'id' => 'test-id3',
                ],
            ],
        ];
        $attributes              = ['class' => $class, 'aria-label' => $ariaLabel, 'disabled' => true, 'selected' => true];
        $labelAttributes         = ['class' => $labelClass, 'test'];
        $labelStart              = '<label>';
        $labelEnd                = '</label>';
        $expected                = '<div></div>';
        $expectedSummary         = '    <div></div>' . PHP_EOL . '    <div></div>' . PHP_EOL . '    <div></div>';
        $textDomain              = 'test-domain';
        $renderedField1          = PHP_EOL .
            '        ' . sprintf('<input class="form-check-input&#x20;%s&#x20;efg" aria-label="%s" name="%s&#x5B;&#x5D;" type="checkbox" id="%s" value="%s"/>', $class, $ariaLabel, $name, $id, $value3) . PHP_EOL .
            '        ' . $labelStart . $value2TranslatedEscaped . $labelEnd . PHP_EOL .
            '    ';
        $renderedField2          = PHP_EOL .
            '        ' . sprintf('<input class="form-check-input&#x20;%s&#x20;efg2" aria-label="%s" disabled="disabled" name="%s&#x5B;&#x5D;" type="checkbox" aria-disabled="true" id="test-id2" value="%s" checked="checked"/>', $class, $ariaLabel, $name, $value1) . PHP_EOL .
            '        ' . $labelStart . $value3TranslatedEscaped . $labelEnd . PHP_EOL .
            '    ';
        $renderedField3          = PHP_EOL .
            '        ' . sprintf('<input class="form-check-input&#x20;%s&#x20;efg3" aria-label="%s" name="%s&#x5B;&#x5D;" type="checkbox" aria-disabled="false" id="test-id3" value="%s" checked="checked"/>', $class, $ariaLabel, $name, $value4) . PHP_EOL .
            '        ' . $labelStart . $name4TranslatedEscaped . $labelEnd . PHP_EOL .
            '    ';
        $wrap                    = false;
        $disableEscape           = false;

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::exactly(3))
            ->method('__invoke')
            ->withConsecutive([$value2Translated], [$value3Translated], [$name4Translated])
            ->willReturnOnConsecutiveCalls($value2TranslatedEscaped, $value3TranslatedEscaped, $name4TranslatedEscaped);

        $escapeHtmlAttr = $this->getMockBuilder(EscapeHtmlAttr::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtmlAttr->expects(self::never())
            ->method('__invoke');

        $doctype = $this->getMockBuilder(Doctype::class)
            ->disableOriginalConstructor()
            ->getMock();
        $doctype->expects(self::never())
            ->method('__invoke');
        $doctype->expects(self::once())
            ->method('isXhtml')
            ->willReturn(true);

        $formLabel = $this->getMockBuilder(FormLabelInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formLabel->expects(self::exactly(3))
            ->method('openTag')
            ->withConsecutive(
                [
                    [
                        'class' => sprintf('form-check-label %s rst', $labelClass),
                        'for' => $id,
                    ],
                ],
                [
                    [
                        'class' => sprintf('form-check-label %s rst2', $labelClass),
                        'for' => 'test-id2',
                    ],
                ],
                [
                    [
                        'class' => sprintf('form-check-label %s rst3', $labelClass),
                        'for' => 'test-id3',
                    ],
                ]
            )
            ->willReturn($labelStart);
        $formLabel->expects(self::exactly(3))
            ->method('closeTag')
            ->willReturn($labelEnd);

        $htmlElement = $this->getMockBuilder(HtmlElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $htmlElement->expects(self::exactly(3))
            ->method('toHtml')
            ->withConsecutive(
                ['div', ['class' => ['form-check']], $renderedField1],
                ['div', ['class' => ['form-check']], $renderedField2],
                ['div', ['class' => ['form-check']], $renderedField3]
            )
            ->willReturn($expected);

        $translator = $this->getMockBuilder(Translate::class)
            ->disableOriginalConstructor()
            ->getMock();
        $translator->expects(self::exactly(3))
            ->method('__invoke')
            ->withConsecutive([$value2, $textDomain], [$value3, $textDomain], [$name4, $textDomain])
            ->willReturnOnConsecutiveCalls($value2Translated, $value3Translated, $name4Translated);

        $formHidden = $this->getMockBuilder(FormHiddenInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formHidden->expects(self::never())
            ->method('render');

        $helper = new FormMultiCheckbox($escapeHtml, $escapeHtmlAttr, $doctype, $formLabel, $htmlElement, $formHidden, $translator);

        $element = $this->getMockBuilder(Radio::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::once())
            ->method('getName')
            ->willReturn($name);
        $element->expects(self::once())
            ->method('getValueOptions')
            ->willReturn($valueOptions);
        $element->expects(self::once())
            ->method('getAttributes')
            ->willReturn($attributes);
        $element->expects(self::once())
            ->method('getValue')
            ->willReturn($value1);
        $element->expects(self::once())
            ->method('useHiddenElement')
            ->willReturn(false);
        $element->expects(self::once())
            ->method('getLabelAttributes')
            ->willReturn($labelAttributes);
        $element->expects(self::once())
            ->method('getOption')
            ->with('layout')
            ->willReturn(Form::LAYOUT_VERTICAL);
        $element->expects(self::exactly(9))
            ->method('getLabelOption')
            ->withConsecutive(['disable_html_escape'], ['always_wrap'], ['always_wrap'], ['disable_html_escape'], ['always_wrap'], ['always_wrap'], ['disable_html_escape'], ['always_wrap'], ['always_wrap'])
            ->willReturnOnConsecutiveCalls($disableEscape, $wrap, $wrap, $disableEscape, $wrap, $wrap, $disableEscape, $wrap, $wrap);
        $element->expects(self::once())
            ->method('hasLabelOption')
            ->with('label_position')
            ->willReturn(false);
        $element->expects(self::never())
            ->method('getUncheckedValue');

        $helper->setLabelPosition(BaseFormRow::LABEL_APPEND);
        $helper->setTranslatorTextDomain($textDomain);

        self::assertSame($expectedSummary, $helper->render($element));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testRenderMultiOptionInlineWithHiddenField1(): void
    {
        $name                    = 'test-name';
        $id                      = 'test-id';
        $value1                  = 'xyz';
        $value2                  = 'def';
        $value2Translated        = 'def-translated';
        $value2TranslatedEscaped = 'def-translated-escaped';
        $value3                  = 'abc';
        $value3Translated        = 'abc-translated';
        $value3TranslatedEscaped = 'abc-translated-escaped';
        $name4                   = 'ghj';
        $name4Translated         = 'ghj-translated';
        $name4TranslatedEscaped  = 'ghj-translated-escaped';
        $value4                  = 'jkl';
        $class                   = 'test-class';
        $ariaLabel               = 'test';
        $labelClass              = 'xyz';
        $valueOptions            = [
            [
                'value' => $value3,
                'label' => $value2,
                'selected' => false,
                'disabled' => false,
                'label_attributes' => ['class' => 'rst'],
                'attributes' => [
                    'class' => 'efg',
                    'id' => $id,
                ],
            ],
            [
                'value' => $value1,
                'label' => $value3,
                'selected' => false,
                'label_attributes' => ['class' => 'rst2'],
                'attributes' => [
                    'class' => 'efg2',
                    'aria-disabled' => 'true',
                    'id' => 'test-id2',
                ],
            ],
            [
                'value' => $value4,
                'label' => $name4,
                'disabled' => false,
                'label_attributes' => ['class' => 'rst3'],
                'attributes' => [
                    'class' => 'efg3',
                    'aria-disabled' => 'false',
                    'id' => 'test-id3',
                ],
            ],
        ];
        $attributes              = ['class' => $class, 'aria-label' => $ariaLabel, 'disabled' => true, 'selected' => true];
        $labelAttributes         = ['class' => $labelClass, 'test'];
        $labelStart              = '<label>';
        $labelEnd                = '</label>';
        $expected                = '<div></div>';
        $uncheckedValue          = '0';
        $expectedSummary         = '    ' . sprintf('<input type="hidden" name="%s" value="%s"/>', $name, $uncheckedValue) . PHP_EOL . '    <div></div>' . PHP_EOL . '    <div></div>' . PHP_EOL . '    <div></div>';
        $textDomain              = 'test-domain';
        $renderedField1          = PHP_EOL .
            '        ' . sprintf('<input class="form-check-input&#x20;%s&#x20;efg" aria-label="%s" name="%s&#x5B;&#x5D;" type="checkbox" id="%s" value="%s"/>', $class, $ariaLabel, $name, $id, $value3) . PHP_EOL .
            '        ' . $labelStart . $value2TranslatedEscaped . $labelEnd . PHP_EOL .
            '    ';
        $renderedField2          = PHP_EOL .
            '        ' . sprintf('<input class="form-check-input&#x20;%s&#x20;efg2" aria-label="%s" disabled="disabled" name="%s&#x5B;&#x5D;" type="checkbox" aria-disabled="true" id="test-id2" value="%s" checked="checked"/>', $class, $ariaLabel, $name, $value1) . PHP_EOL .
            '        ' . $labelStart . $value3TranslatedEscaped . $labelEnd . PHP_EOL .
            '    ';
        $renderedField3          = PHP_EOL .
            '        ' . sprintf('<input class="form-check-input&#x20;%s&#x20;efg3" aria-label="%s" name="%s&#x5B;&#x5D;" type="checkbox" aria-disabled="false" id="test-id3" value="%s" checked="checked"/>', $class, $ariaLabel, $name, $value4) . PHP_EOL .
            '        ' . $labelStart . $name4TranslatedEscaped . $labelEnd . PHP_EOL .
            '    ';
        $wrap                    = false;
        $disableEscape           = false;

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::exactly(3))
            ->method('__invoke')
            ->withConsecutive([$value2Translated], [$value3Translated], [$name4Translated])
            ->willReturnOnConsecutiveCalls($value2TranslatedEscaped, $value3TranslatedEscaped, $name4TranslatedEscaped);

        $escapeHtmlAttr = $this->getMockBuilder(EscapeHtmlAttr::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtmlAttr->expects(self::never())
            ->method('__invoke');

        $doctype = $this->getMockBuilder(Doctype::class)
            ->disableOriginalConstructor()
            ->getMock();
        $doctype->expects(self::never())
            ->method('__invoke');
        $doctype->expects(self::once())
            ->method('isXhtml')
            ->willReturn(true);

        $formLabel = $this->getMockBuilder(FormLabelInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formLabel->expects(self::exactly(3))
            ->method('openTag')
            ->withConsecutive(
                [
                    [
                        'class' => sprintf('form-check-label %s rst', $labelClass),
                        'for' => $id,
                    ],
                ],
                [
                    [
                        'class' => sprintf('form-check-label %s rst2', $labelClass),
                        'for' => 'test-id2',
                    ],
                ],
                [
                    [
                        'class' => sprintf('form-check-label %s rst3', $labelClass),
                        'for' => 'test-id3',
                    ],
                ]
            )
            ->willReturn($labelStart);
        $formLabel->expects(self::exactly(3))
            ->method('closeTag')
            ->willReturn($labelEnd);

        $htmlElement = $this->getMockBuilder(HtmlElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $htmlElement->expects(self::exactly(3))
            ->method('toHtml')
            ->withConsecutive(
                ['div', ['class' => ['form-check', 'form-check-inline']], $renderedField1],
                ['div', ['class' => ['form-check', 'form-check-inline']], $renderedField2],
                ['div', ['class' => ['form-check', 'form-check-inline']], $renderedField3]
            )
            ->willReturn($expected);

        $translator = $this->getMockBuilder(Translate::class)
            ->disableOriginalConstructor()
            ->getMock();
        $translator->expects(self::exactly(3))
            ->method('__invoke')
            ->withConsecutive([$value2, $textDomain], [$value3, $textDomain], [$name4, $textDomain])
            ->willReturnOnConsecutiveCalls($value2Translated, $value3Translated, $name4Translated);

        $formHidden = $this->getMockBuilder(FormHiddenInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formHidden->expects(self::once())
            ->method('render')
            ->with(new IsInstanceOf(Hidden::class))
            ->willReturn(sprintf('<input type="hidden" name="%s" value="%s"/>', $name, $uncheckedValue));

        $helper = new FormMultiCheckbox($escapeHtml, $escapeHtmlAttr, $doctype, $formLabel, $htmlElement, $formHidden, $translator);

        $element = $this->getMockBuilder(Radio::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::exactly(2))
            ->method('getName')
            ->willReturn($name);
        $element->expects(self::once())
            ->method('getValueOptions')
            ->willReturn($valueOptions);
        $element->expects(self::once())
            ->method('getAttributes')
            ->willReturn($attributes);
        $element->expects(self::once())
            ->method('getValue')
            ->willReturn($value1);
        $element->expects(self::exactly(2))
            ->method('useHiddenElement')
            ->willReturn(true);
        $element->expects(self::once())
            ->method('getLabelAttributes')
            ->willReturn($labelAttributes);
        $element->expects(self::once())
            ->method('getOption')
            ->with('layout')
            ->willReturn(Form::LAYOUT_INLINE);
        $element->expects(self::exactly(9))
            ->method('getLabelOption')
            ->withConsecutive(['disable_html_escape'], ['always_wrap'], ['always_wrap'], ['disable_html_escape'], ['always_wrap'], ['always_wrap'], ['disable_html_escape'], ['always_wrap'], ['always_wrap'])
            ->willReturnOnConsecutiveCalls($disableEscape, $wrap, $wrap, $disableEscape, $wrap, $wrap, $disableEscape, $wrap, $wrap);
        $element->expects(self::once())
            ->method('hasLabelOption')
            ->with('label_position')
            ->willReturn(false);
        $element->expects(self::once())
            ->method('getUncheckedValue')
            ->willReturn('');

        $helper->setLabelPosition(BaseFormRow::LABEL_APPEND);
        $helper->setTranslatorTextDomain($textDomain);
        $helper->setUncheckedValue($uncheckedValue);

        self::assertSame($expectedSummary, $helper->render($element));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testRenderMultiOptionInlineWithHiddenField2(): void
    {
        $name                    = 'test-name';
        $id                      = 'test-id';
        $value1                  = 'xyz';
        $value2                  = 'def';
        $value2Translated        = 'def-translated';
        $value2TranslatedEscaped = 'def-translated-escaped';
        $value3                  = 'abc';
        $value3Translated        = 'abc-translated';
        $value3TranslatedEscaped = 'abc-translated-escaped';
        $name4                   = 'ghj';
        $name4Translated         = 'ghj-translated';
        $name4TranslatedEscaped  = 'ghj-translated-escaped';
        $value4                  = 'jkl';
        $class                   = 'test-class';
        $ariaLabel               = 'test';
        $labelClass              = 'xyz';
        $valueOptions            = [
            [
                'value' => $value3,
                'label' => $value2,
                'selected' => false,
                'disabled' => false,
                'label_attributes' => ['class' => 'rst'],
                'attributes' => [
                    'class' => 'efg',
                    'id' => $id,
                ],
            ],
            [
                'value' => $value1,
                'label' => $value3,
                'selected' => false,
                'label_attributes' => ['class' => 'rst2'],
                'attributes' => [
                    'class' => 'efg2',
                    'aria-disabled' => 'true',
                    'id' => 'test-id2',
                ],
            ],
            [
                'value' => $value4,
                'label' => $name4,
                'disabled' => false,
                'label_attributes' => ['class' => 'rst3'],
                'attributes' => [
                    'class' => 'efg3',
                    'aria-disabled' => 'false',
                    'id' => 'test-id3',
                ],
            ],
        ];
        $attributes              = ['class' => $class, 'aria-label' => $ariaLabel, 'disabled' => true, 'selected' => true];
        $labelAttributes         = ['class' => $labelClass, 'test'];
        $labelStart              = '<label>';
        $labelEnd                = '</label>';
        $expected                = '<div></div>';
        $uncheckedValue          = '0';
        $expectedSummary         = '    ' . sprintf('<input type="hidden" name="%s" value="%s"/>', $name, $uncheckedValue) . PHP_EOL . '    <div></div>' . PHP_EOL . '    <div></div>' . PHP_EOL . '    <div></div>';
        $textDomain              = 'test-domain';
        $renderedField1          = PHP_EOL .
            '        ' . sprintf('<input class="form-check-input&#x20;%s&#x20;efg" aria-label="%s" name="%s&#x5B;&#x5D;" type="checkbox" id="%s" value="%s"/>', $class, $ariaLabel, $name, $id, $value3) . PHP_EOL .
            '        ' . $labelStart . $value2TranslatedEscaped . $labelEnd . PHP_EOL .
            '    ';
        $renderedField2          = PHP_EOL .
            '        ' . sprintf('<input class="form-check-input&#x20;%s&#x20;efg2" aria-label="%s" disabled="disabled" name="%s&#x5B;&#x5D;" type="checkbox" aria-disabled="true" id="test-id2" value="%s" checked="checked"/>', $class, $ariaLabel, $name, $value1) . PHP_EOL .
            '        ' . $labelStart . $value3TranslatedEscaped . $labelEnd . PHP_EOL .
            '    ';
        $renderedField3          = PHP_EOL .
            '        ' . sprintf('<input class="form-check-input&#x20;%s&#x20;efg3" aria-label="%s" name="%s&#x5B;&#x5D;" type="checkbox" aria-disabled="false" id="test-id3" value="%s" checked="checked"/>', $class, $ariaLabel, $name, $value4) . PHP_EOL .
            '        ' . $labelStart . $name4TranslatedEscaped . $labelEnd . PHP_EOL .
            '    ';
        $wrap                    = false;
        $disableEscape           = false;

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::exactly(3))
            ->method('__invoke')
            ->withConsecutive([$value2Translated], [$value3Translated], [$name4Translated])
            ->willReturnOnConsecutiveCalls($value2TranslatedEscaped, $value3TranslatedEscaped, $name4TranslatedEscaped);

        $escapeHtmlAttr = $this->getMockBuilder(EscapeHtmlAttr::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtmlAttr->expects(self::never())
            ->method('__invoke');

        $doctype = $this->getMockBuilder(Doctype::class)
            ->disableOriginalConstructor()
            ->getMock();
        $doctype->expects(self::never())
            ->method('__invoke');
        $doctype->expects(self::once())
            ->method('isXhtml')
            ->willReturn(true);

        $formLabel = $this->getMockBuilder(FormLabelInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formLabel->expects(self::exactly(3))
            ->method('openTag')
            ->withConsecutive(
                [
                    [
                        'class' => sprintf('form-check-label %s rst', $labelClass),
                        'for' => $id,
                    ],
                ],
                [
                    [
                        'class' => sprintf('form-check-label %s rst2', $labelClass),
                        'for' => 'test-id2',
                    ],
                ],
                [
                    [
                        'class' => sprintf('form-check-label %s rst3', $labelClass),
                        'for' => 'test-id3',
                    ],
                ]
            )
            ->willReturn($labelStart);
        $formLabel->expects(self::exactly(3))
            ->method('closeTag')
            ->willReturn($labelEnd);

        $htmlElement = $this->getMockBuilder(HtmlElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $htmlElement->expects(self::exactly(3))
            ->method('toHtml')
            ->withConsecutive(
                ['div', ['class' => ['form-check', 'form-check-inline']], $renderedField1],
                ['div', ['class' => ['form-check', 'form-check-inline']], $renderedField2],
                ['div', ['class' => ['form-check', 'form-check-inline']], $renderedField3]
            )
            ->willReturn($expected);

        $translator = $this->getMockBuilder(Translate::class)
            ->disableOriginalConstructor()
            ->getMock();
        $translator->expects(self::exactly(3))
            ->method('__invoke')
            ->withConsecutive([$value2, $textDomain], [$value3, $textDomain], [$name4, $textDomain])
            ->willReturnOnConsecutiveCalls($value2Translated, $value3Translated, $name4Translated);

        $formHidden = $this->getMockBuilder(FormHiddenInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formHidden->expects(self::once())
            ->method('render')
            ->with(new IsInstanceOf(Hidden::class))
            ->willReturn(sprintf('<input type="hidden" name="%s" value="%s"/>', $name, $uncheckedValue));

        $helper = new FormMultiCheckbox($escapeHtml, $escapeHtmlAttr, $doctype, $formLabel, $htmlElement, $formHidden, $translator);

        $element = $this->getMockBuilder(Radio::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::exactly(2))
            ->method('getName')
            ->willReturn($name);
        $element->expects(self::once())
            ->method('getValueOptions')
            ->willReturn($valueOptions);
        $element->expects(self::once())
            ->method('getAttributes')
            ->willReturn($attributes);
        $element->expects(self::once())
            ->method('getValue')
            ->willReturn($value1);
        $element->expects(self::exactly(2))
            ->method('useHiddenElement')
            ->willReturn(true);
        $element->expects(self::once())
            ->method('getLabelAttributes')
            ->willReturn($labelAttributes);
        $element->expects(self::once())
            ->method('getOption')
            ->with('layout')
            ->willReturn(Form::LAYOUT_INLINE);
        $element->expects(self::exactly(9))
            ->method('getLabelOption')
            ->withConsecutive(['disable_html_escape'], ['always_wrap'], ['always_wrap'], ['disable_html_escape'], ['always_wrap'], ['always_wrap'], ['disable_html_escape'], ['always_wrap'], ['always_wrap'])
            ->willReturnOnConsecutiveCalls($disableEscape, $wrap, $wrap, $disableEscape, $wrap, $wrap, $disableEscape, $wrap, $wrap);
        $element->expects(self::once())
            ->method('hasLabelOption')
            ->with('label_position')
            ->willReturn(false);
        $element->expects(self::once())
            ->method('getUncheckedValue')
            ->willReturn($uncheckedValue);

        $helper->setLabelPosition(BaseFormRow::LABEL_APPEND);
        $helper->setTranslatorTextDomain($textDomain);

        self::assertSame($expectedSummary, $helper->render($element));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testRenderMultiOptionInlineWithHiddenField3(): void
    {
        $name                    = 'test-name';
        $id                      = 'test-id';
        $value1                  = 'xyz';
        $value2                  = 'def';
        $value2Translated        = 'def-translated';
        $value2TranslatedEscaped = 'def-translated-escaped';
        $value3                  = 'abc';
        $value3Translated        = 'abc-translated';
        $value3TranslatedEscaped = 'abc-translated-escaped';
        $name4                   = 'ghj';
        $name4Translated         = 'ghj-translated';
        $name4TranslatedEscaped  = 'ghj-translated-escaped';
        $value4                  = 'jkl';
        $class                   = 'test-class';
        $ariaLabel               = 'test';
        $labelClass              = 'xyz';
        $valueOptions            = [
            [
                'value' => $value3,
                'label' => $value2,
                'selected' => false,
                'disabled' => false,
                'label_attributes' => ['class' => 'rst'],
                'attributes' => [
                    'class' => 'efg',
                    'id' => $id,
                ],
            ],
            [
                'value' => $value1,
                'label' => $value3,
                'selected' => false,
                'label_attributes' => ['class' => 'rst2'],
                'attributes' => [
                    'class' => 'efg2',
                    'aria-disabled' => 'true',
                    'id' => 'test-id2',
                ],
            ],
            [
                'value' => $value4,
                'label' => $name4,
                'disabled' => false,
                'label_attributes' => ['class' => 'rst3'],
                'attributes' => [
                    'class' => 'efg3',
                    'aria-disabled' => 'false',
                    'id' => 'test-id3',
                ],
            ],
        ];
        $attributes              = ['class' => $class, 'aria-label' => $ariaLabel, 'disabled' => true, 'selected' => true, 'id' => 'zero-id'];
        $labelAttributes         = ['class' => $labelClass, 'test'];
        $labelStart              = '<label>';
        $labelEnd                = '</label>';
        $expected                = '<div></div>';
        $uncheckedValue          = '0';
        $expectedSummary         = '    ' . sprintf('<input type="hidden" name="%s" value="%s"/>', $name, $uncheckedValue) . PHP_EOL . '    <div></div>' . PHP_EOL . '    <div></div>' . PHP_EOL . '    <div></div>';
        $textDomain              = 'test-domain';
        $renderedField1          = PHP_EOL .
            '        ' . sprintf('<input class="form-check-input&#x20;%s&#x20;efg" aria-label="%s" id="%s" name="%s&#x5B;&#x5D;" type="checkbox" value="%s"/>', $class, $ariaLabel, $id, $name, $value3) . PHP_EOL .
            '        ' . $labelStart . $value2TranslatedEscaped . $labelEnd . PHP_EOL .
            '    ';
        $renderedField2          = PHP_EOL .
            '        ' . sprintf('<input class="form-check-input&#x20;%s&#x20;efg2" aria-label="%s" disabled="disabled" name="%s&#x5B;&#x5D;" type="checkbox" aria-disabled="true" id="test-id2" value="%s" checked="checked"/>', $class, $ariaLabel, $name, $value1) . PHP_EOL .
            '        ' . $labelStart . $value3TranslatedEscaped . $labelEnd . PHP_EOL .
            '    ';
        $renderedField3          = PHP_EOL .
            '        ' . sprintf('<input class="form-check-input&#x20;%s&#x20;efg3" aria-label="%s" name="%s&#x5B;&#x5D;" type="checkbox" aria-disabled="false" id="test-id3" value="%s" checked="checked"/>', $class, $ariaLabel, $name, $value4) . PHP_EOL .
            '        ' . $labelStart . $name4TranslatedEscaped . $labelEnd . PHP_EOL .
            '    ';
        $wrap                    = false;
        $disableEscape           = false;

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::exactly(3))
            ->method('__invoke')
            ->withConsecutive([$value2Translated], [$value3Translated], [$name4Translated])
            ->willReturnOnConsecutiveCalls($value2TranslatedEscaped, $value3TranslatedEscaped, $name4TranslatedEscaped);

        $escapeHtmlAttr = $this->getMockBuilder(EscapeHtmlAttr::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtmlAttr->expects(self::never())
            ->method('__invoke');

        $doctype = $this->getMockBuilder(Doctype::class)
            ->disableOriginalConstructor()
            ->getMock();
        $doctype->expects(self::never())
            ->method('__invoke');
        $doctype->expects(self::once())
            ->method('isXhtml')
            ->willReturn(true);

        $formLabel = $this->getMockBuilder(FormLabelInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formLabel->expects(self::exactly(3))
            ->method('openTag')
            ->withConsecutive(
                [
                    [
                        'class' => sprintf('form-check-label %s rst', $labelClass),
                        'for' => $id,
                    ],
                ],
                [
                    [
                        'class' => sprintf('form-check-label %s rst2', $labelClass),
                        'for' => 'test-id2',
                    ],
                ],
                [
                    [
                        'class' => sprintf('form-check-label %s rst3', $labelClass),
                        'for' => 'test-id3',
                    ],
                ]
            )
            ->willReturn($labelStart);
        $formLabel->expects(self::exactly(3))
            ->method('closeTag')
            ->willReturn($labelEnd);

        $htmlElement = $this->getMockBuilder(HtmlElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $htmlElement->expects(self::exactly(3))
            ->method('toHtml')
            ->withConsecutive(
                ['div', ['class' => ['form-check', 'form-check-inline']], $renderedField1],
                ['div', ['class' => ['form-check', 'form-check-inline']], $renderedField2],
                ['div', ['class' => ['form-check', 'form-check-inline']], $renderedField3]
            )
            ->willReturn($expected);

        $translator = $this->getMockBuilder(Translate::class)
            ->disableOriginalConstructor()
            ->getMock();
        $translator->expects(self::exactly(3))
            ->method('__invoke')
            ->withConsecutive([$value2, $textDomain], [$value3, $textDomain], [$name4, $textDomain])
            ->willReturnOnConsecutiveCalls($value2Translated, $value3Translated, $name4Translated);

        $formHidden = $this->getMockBuilder(FormHiddenInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formHidden->expects(self::once())
            ->method('render')
            ->with(new IsInstanceOf(Hidden::class))
            ->willReturn(sprintf('<input type="hidden" name="%s" value="%s"/>', $name, $uncheckedValue));

        $helper = new FormMultiCheckbox($escapeHtml, $escapeHtmlAttr, $doctype, $formLabel, $htmlElement, $formHidden, $translator);

        $element = $this->getMockBuilder(Radio::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::exactly(2))
            ->method('getName')
            ->willReturn($name);
        $element->expects(self::once())
            ->method('getValueOptions')
            ->willReturn($valueOptions);
        $element->expects(self::once())
            ->method('getAttributes')
            ->willReturn($attributes);
        $element->expects(self::once())
            ->method('getValue')
            ->willReturn($value1);
        $element->expects(self::exactly(2))
            ->method('useHiddenElement')
            ->willReturn(true);
        $element->expects(self::once())
            ->method('getLabelAttributes')
            ->willReturn([]);
        $element->expects(self::once())
            ->method('getOption')
            ->with('layout')
            ->willReturn(Form::LAYOUT_INLINE);
        $element->expects(self::exactly(9))
            ->method('getLabelOption')
            ->withConsecutive(['disable_html_escape'], ['always_wrap'], ['always_wrap'], ['disable_html_escape'], ['always_wrap'], ['always_wrap'], ['disable_html_escape'], ['always_wrap'], ['always_wrap'])
            ->willReturnOnConsecutiveCalls($disableEscape, $wrap, $wrap, $disableEscape, $wrap, $wrap, $disableEscape, $wrap, $wrap);
        $element->expects(self::once())
            ->method('hasLabelOption')
            ->with('label_position')
            ->willReturn(false);
        $element->expects(self::once())
            ->method('getUncheckedValue')
            ->willReturn($uncheckedValue);

        $helper->setLabelPosition(BaseFormRow::LABEL_APPEND);
        $helper->setTranslatorTextDomain($textDomain);

        self::assertSame($helper, $helper->setLabelAttributes($labelAttributes));
        self::assertSame($expectedSummary, $helper->render($element));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testInvokeMultiOptionInlineWithHiddenField1(): void
    {
        $name                    = 'test-name';
        $id                      = 'test-id';
        $value1                  = 'xyz';
        $value2                  = 'def';
        $value2Translated        = 'def-translated';
        $value2TranslatedEscaped = 'def-translated-escaped';
        $value3                  = 'abc';
        $value3Translated        = 'abc-translated';
        $value3TranslatedEscaped = 'abc-translated-escaped';
        $name4                   = 'ghj';
        $name4Translated         = 'ghj-translated';
        $name4TranslatedEscaped  = 'ghj-translated-escaped';
        $value4                  = 'jkl';
        $class                   = 'test-class';
        $ariaLabel               = 'test';
        $labelClass              = 'xyz';
        $valueOptions            = [
            [
                'value' => $value3,
                'label' => $value2,
                'selected' => false,
                'disabled' => false,
                'label_attributes' => ['class' => 'rst'],
                'attributes' => [
                    'class' => 'efg',
                    'id' => $id,
                ],
            ],
            [
                'value' => $value1,
                'label' => $value3,
                'selected' => false,
                'label_attributes' => ['class' => 'rst2'],
                'attributes' => [
                    'class' => 'efg2',
                    'aria-disabled' => 'true',
                    'id' => 'test-id2',
                ],
            ],
            [
                'value' => $value4,
                'label' => $name4,
                'disabled' => false,
                'label_attributes' => ['class' => 'rst3'],
                'attributes' => [
                    'class' => 'efg3',
                    'aria-disabled' => 'false',
                    'id' => 'test-id3',
                ],
            ],
        ];
        $attributes              = ['class' => $class, 'aria-label' => $ariaLabel, 'disabled' => true, 'selected' => true, 'id' => 'zero-id'];
        $labelAttributes         = ['class' => $labelClass, 'test'];
        $labelStart              = '<label>';
        $labelEnd                = '</label>';
        $expected                = '<div></div>';
        $uncheckedValue          = '0';
        $expectedSummary         = '    ' . sprintf('<input type="hidden" name="%s" value="%s"/>', $name, $uncheckedValue) . PHP_EOL . '    <div></div>' . PHP_EOL . '    <div></div>' . PHP_EOL . '    <div></div>';
        $textDomain              = 'test-domain';
        $renderedField1          = PHP_EOL .
            '        ' . sprintf('<input class="form-check-input&#x20;%s&#x20;efg" aria-label="%s" id="%s" name="%s&#x5B;&#x5D;" type="checkbox" value="%s"/>', $class, $ariaLabel, $id, $name, $value3) . PHP_EOL .
            '        ' . $labelStart . $value2TranslatedEscaped . $labelEnd . PHP_EOL .
            '    ';
        $renderedField2          = PHP_EOL .
            '        ' . sprintf('<input class="form-check-input&#x20;%s&#x20;efg2" aria-label="%s" disabled="disabled" name="%s&#x5B;&#x5D;" type="checkbox" aria-disabled="true" id="test-id2" value="%s" checked="checked"/>', $class, $ariaLabel, $name, $value1) . PHP_EOL .
            '        ' . $labelStart . $value3TranslatedEscaped . $labelEnd . PHP_EOL .
            '    ';
        $renderedField3          = PHP_EOL .
            '        ' . sprintf('<input class="form-check-input&#x20;%s&#x20;efg3" aria-label="%s" name="%s&#x5B;&#x5D;" type="checkbox" aria-disabled="false" id="test-id3" value="%s" checked="checked"/>', $class, $ariaLabel, $name, $value4) . PHP_EOL .
            '        ' . $labelStart . $name4TranslatedEscaped . $labelEnd . PHP_EOL .
            '    ';
        $wrap                    = false;
        $disableEscape           = false;

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::exactly(3))
            ->method('__invoke')
            ->withConsecutive([$value2Translated], [$value3Translated], [$name4Translated])
            ->willReturnOnConsecutiveCalls($value2TranslatedEscaped, $value3TranslatedEscaped, $name4TranslatedEscaped);

        $escapeHtmlAttr = $this->getMockBuilder(EscapeHtmlAttr::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtmlAttr->expects(self::never())
            ->method('__invoke');

        $doctype = $this->getMockBuilder(Doctype::class)
            ->disableOriginalConstructor()
            ->getMock();
        $doctype->expects(self::never())
            ->method('__invoke');
        $doctype->expects(self::once())
            ->method('isXhtml')
            ->willReturn(true);

        $formLabel = $this->getMockBuilder(FormLabelInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formLabel->expects(self::exactly(3))
            ->method('openTag')
            ->withConsecutive(
                [
                    [
                        'class' => sprintf('form-check-label %s rst', $labelClass),
                        'for' => $id,
                    ],
                ],
                [
                    [
                        'class' => sprintf('form-check-label %s rst2', $labelClass),
                        'for' => 'test-id2',
                    ],
                ],
                [
                    [
                        'class' => sprintf('form-check-label %s rst3', $labelClass),
                        'for' => 'test-id3',
                    ],
                ]
            )
            ->willReturn($labelStart);
        $formLabel->expects(self::exactly(3))
            ->method('closeTag')
            ->willReturn($labelEnd);

        $htmlElement = $this->getMockBuilder(HtmlElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $htmlElement->expects(self::exactly(3))
            ->method('toHtml')
            ->withConsecutive(
                ['div', ['class' => ['form-check', 'form-check-inline']], $renderedField1],
                ['div', ['class' => ['form-check', 'form-check-inline']], $renderedField2],
                ['div', ['class' => ['form-check', 'form-check-inline']], $renderedField3]
            )
            ->willReturn($expected);

        $translator = $this->getMockBuilder(Translate::class)
            ->disableOriginalConstructor()
            ->getMock();
        $translator->expects(self::exactly(3))
            ->method('__invoke')
            ->withConsecutive([$value2, $textDomain], [$value3, $textDomain], [$name4, $textDomain])
            ->willReturnOnConsecutiveCalls($value2Translated, $value3Translated, $name4Translated);

        $formHidden = $this->getMockBuilder(FormHiddenInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formHidden->expects(self::once())
            ->method('render')
            ->with(new IsInstanceOf(Hidden::class))
            ->willReturn(sprintf('<input type="hidden" name="%s" value="%s"/>', $name, $uncheckedValue));

        $helper = new FormMultiCheckbox($escapeHtml, $escapeHtmlAttr, $doctype, $formLabel, $htmlElement, $formHidden, $translator);

        $element = $this->getMockBuilder(Radio::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::exactly(2))
            ->method('getName')
            ->willReturn($name);
        $element->expects(self::once())
            ->method('getValueOptions')
            ->willReturn($valueOptions);
        $element->expects(self::once())
            ->method('getAttributes')
            ->willReturn($attributes);
        $element->expects(self::once())
            ->method('getValue')
            ->willReturn($value1);
        $element->expects(self::exactly(2))
            ->method('useHiddenElement')
            ->willReturn(true);
        $element->expects(self::once())
            ->method('getLabelAttributes')
            ->willReturn([]);
        $element->expects(self::once())
            ->method('getOption')
            ->with('layout')
            ->willReturn(Form::LAYOUT_INLINE);
        $element->expects(self::exactly(9))
            ->method('getLabelOption')
            ->withConsecutive(['disable_html_escape'], ['always_wrap'], ['always_wrap'], ['disable_html_escape'], ['always_wrap'], ['always_wrap'], ['disable_html_escape'], ['always_wrap'], ['always_wrap'])
            ->willReturnOnConsecutiveCalls($disableEscape, $wrap, $wrap, $disableEscape, $wrap, $wrap, $disableEscape, $wrap, $wrap);
        $element->expects(self::once())
            ->method('hasLabelOption')
            ->with('label_position')
            ->willReturn(false);
        $element->expects(self::once())
            ->method('getUncheckedValue')
            ->willReturn($uncheckedValue);

        $helper->setLabelPosition(BaseFormRow::LABEL_APPEND);
        $helper->setTranslatorTextDomain($textDomain);

        self::assertSame($helper, $helper->setLabelAttributes($labelAttributes));

        $helperObject = $helper();

        assert($helperObject instanceof FormMultiCheckbox);

        self::assertSame($expectedSummary, $helperObject->render($element));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testInvokeMultiOptionInlineWithHiddenField2(): void
    {
        $name                    = 'test-name';
        $id                      = 'test-id';
        $value1                  = 'xyz';
        $value2                  = 'def';
        $value2Translated        = 'def-translated';
        $value2TranslatedEscaped = 'def-translated-escaped';
        $value3                  = 'abc';
        $value3Translated        = 'abc-translated';
        $value3TranslatedEscaped = 'abc-translated-escaped';
        $name4                   = 'ghj';
        $name4Translated         = 'ghj-translated';
        $name4TranslatedEscaped  = 'ghj-translated-escaped';
        $value4                  = 'jkl';
        $class                   = 'test-class';
        $ariaLabel               = 'test';
        $labelClass              = 'xyz';
        $valueOptions            = [
            [
                'value' => $value3,
                'label' => $value2,
                'selected' => false,
                'disabled' => false,
                'label_attributes' => ['class' => 'rst'],
                'attributes' => [
                    'class' => 'efg',
                    'id' => $id,
                ],
            ],
            [
                'value' => $value1,
                'label' => $value3,
                'selected' => false,
                'label_attributes' => ['class' => 'rst2'],
                'attributes' => [
                    'class' => 'efg2',
                    'aria-disabled' => 'true',
                    'id' => 'test-id2',
                ],
            ],
            [
                'value' => $value4,
                'label' => $name4,
                'disabled' => false,
                'label_attributes' => ['class' => 'rst3'],
                'attributes' => [
                    'class' => 'efg3',
                    'aria-disabled' => 'false',
                    'id' => 'test-id3',
                ],
            ],
        ];
        $attributes              = ['class' => $class, 'aria-label' => $ariaLabel, 'disabled' => true, 'selected' => true, 'id' => 'zero-id'];
        $labelAttributes         = ['class' => $labelClass, 'test'];
        $labelStart              = '<label>';
        $labelEnd                = '</label>';
        $expected                = '<div></div>';
        $uncheckedValue          = '0';
        $expectedSummary         = '    ' . sprintf('<input type="hidden" name="%s" value="%s"/>', $name, $uncheckedValue) . PHP_EOL . '    <div></div>' . PHP_EOL . '    <div></div>' . PHP_EOL . '    <div></div>';
        $textDomain              = 'test-domain';
        $renderedField1          = PHP_EOL .
            '        ' . sprintf('<input class="form-check-input&#x20;%s&#x20;efg" aria-label="%s" id="%s" name="%s&#x5B;&#x5D;" type="checkbox" value="%s"/>', $class, $ariaLabel, $id, $name, $value3) . PHP_EOL .
            '        ' . $labelStart . $value2TranslatedEscaped . $labelEnd . PHP_EOL .
            '    ';
        $renderedField2          = PHP_EOL .
            '        ' . sprintf('<input class="form-check-input&#x20;%s&#x20;efg2" aria-label="%s" disabled="disabled" name="%s&#x5B;&#x5D;" type="checkbox" aria-disabled="true" id="test-id2" value="%s" checked="checked"/>', $class, $ariaLabel, $name, $value1) . PHP_EOL .
            '        ' . $labelStart . $value3TranslatedEscaped . $labelEnd . PHP_EOL .
            '    ';
        $renderedField3          = PHP_EOL .
            '        ' . sprintf('<input class="form-check-input&#x20;%s&#x20;efg3" aria-label="%s" name="%s&#x5B;&#x5D;" type="checkbox" aria-disabled="false" id="test-id3" value="%s" checked="checked"/>', $class, $ariaLabel, $name, $value4) . PHP_EOL .
            '        ' . $labelStart . $name4TranslatedEscaped . $labelEnd . PHP_EOL .
            '    ';
        $wrap                    = false;
        $disableEscape           = false;

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::exactly(3))
            ->method('__invoke')
            ->withConsecutive([$value2Translated], [$value3Translated], [$name4Translated])
            ->willReturnOnConsecutiveCalls($value2TranslatedEscaped, $value3TranslatedEscaped, $name4TranslatedEscaped);

        $escapeHtmlAttr = $this->getMockBuilder(EscapeHtmlAttr::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtmlAttr->expects(self::never())
            ->method('__invoke');

        $doctype = $this->getMockBuilder(Doctype::class)
            ->disableOriginalConstructor()
            ->getMock();
        $doctype->expects(self::never())
            ->method('__invoke');
        $doctype->expects(self::once())
            ->method('isXhtml')
            ->willReturn(true);

        $formLabel = $this->getMockBuilder(FormLabelInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formLabel->expects(self::exactly(3))
            ->method('openTag')
            ->withConsecutive(
                [
                    [
                        'class' => sprintf('form-check-label %s rst', $labelClass),
                        'for' => $id,
                    ],
                ],
                [
                    [
                        'class' => sprintf('form-check-label %s rst2', $labelClass),
                        'for' => 'test-id2',
                    ],
                ],
                [
                    [
                        'class' => sprintf('form-check-label %s rst3', $labelClass),
                        'for' => 'test-id3',
                    ],
                ]
            )
            ->willReturn($labelStart);
        $formLabel->expects(self::exactly(3))
            ->method('closeTag')
            ->willReturn($labelEnd);

        $htmlElement = $this->getMockBuilder(HtmlElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $htmlElement->expects(self::exactly(3))
            ->method('toHtml')
            ->withConsecutive(
                ['div', ['class' => ['form-check', 'form-check-inline']], $renderedField1],
                ['div', ['class' => ['form-check', 'form-check-inline']], $renderedField2],
                ['div', ['class' => ['form-check', 'form-check-inline']], $renderedField3]
            )
            ->willReturn($expected);

        $translator = $this->getMockBuilder(Translate::class)
            ->disableOriginalConstructor()
            ->getMock();
        $translator->expects(self::exactly(3))
            ->method('__invoke')
            ->withConsecutive([$value2, $textDomain], [$value3, $textDomain], [$name4, $textDomain])
            ->willReturnOnConsecutiveCalls($value2Translated, $value3Translated, $name4Translated);

        $formHidden = $this->getMockBuilder(FormHiddenInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formHidden->expects(self::once())
            ->method('render')
            ->with(new IsInstanceOf(Hidden::class))
            ->willReturn(sprintf('<input type="hidden" name="%s" value="%s"/>', $name, $uncheckedValue));

        $helper = new FormMultiCheckbox($escapeHtml, $escapeHtmlAttr, $doctype, $formLabel, $htmlElement, $formHidden, $translator);

        $element = $this->getMockBuilder(Radio::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::exactly(2))
            ->method('getName')
            ->willReturn($name);
        $element->expects(self::once())
            ->method('getValueOptions')
            ->willReturn($valueOptions);
        $element->expects(self::once())
            ->method('getAttributes')
            ->willReturn($attributes);
        $element->expects(self::once())
            ->method('getValue')
            ->willReturn($value1);
        $element->expects(self::exactly(2))
            ->method('useHiddenElement')
            ->willReturn(true);
        $element->expects(self::once())
            ->method('getLabelAttributes')
            ->willReturn([]);
        $element->expects(self::once())
            ->method('getOption')
            ->with('layout')
            ->willReturn(Form::LAYOUT_INLINE);
        $element->expects(self::exactly(9))
            ->method('getLabelOption')
            ->withConsecutive(['disable_html_escape'], ['always_wrap'], ['always_wrap'], ['disable_html_escape'], ['always_wrap'], ['always_wrap'], ['disable_html_escape'], ['always_wrap'], ['always_wrap'])
            ->willReturnOnConsecutiveCalls($disableEscape, $wrap, $wrap, $disableEscape, $wrap, $wrap, $disableEscape, $wrap, $wrap);
        $element->expects(self::once())
            ->method('hasLabelOption')
            ->with('label_position')
            ->willReturn(false);
        $element->expects(self::once())
            ->method('getUncheckedValue')
            ->willReturn($uncheckedValue);

        $helper->setLabelPosition(BaseFormRow::LABEL_APPEND);
        $helper->setTranslatorTextDomain($textDomain);

        self::assertSame($helper, $helper->setLabelAttributes($labelAttributes));
        self::assertSame($expectedSummary, $helper($element));
    }

    /**
     * @throws Exception
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testInvokeMultiOptionInlineWithHiddenField3(): void
    {
        $name                    = 'test-name';
        $id                      = 'test-id';
        $value1                  = 'xyz';
        $value2                  = 'def';
        $value2Translated        = 'def-translated';
        $value2TranslatedEscaped = 'def-translated-escaped';
        $value3                  = 'abc';
        $value3Translated        = 'abc-translated';
        $value3TranslatedEscaped = 'abc-translated-escaped';
        $name4                   = 'ghj';
        $name4Translated         = 'ghj-translated';
        $name4TranslatedEscaped  = 'ghj-translated-escaped';
        $value4                  = 'jkl';
        $class                   = 'test-class';
        $ariaLabel               = 'test';
        $labelClass              = 'xyz';
        $indent                  = '<!-- -->  ';
        $valueOptions            = [
            [
                'value' => $value3,
                'label' => $value2,
                'selected' => false,
                'disabled' => false,
                'label_attributes' => ['class' => 'rst'],
                'attributes' => [
                    'class' => 'efg',
                    'id' => $id,
                ],
            ],
            [
                'value' => $value1,
                'label' => $value3,
                'selected' => false,
                'label_attributes' => ['class' => 'rst2'],
                'attributes' => [
                    'class' => 'efg2',
                    'aria-disabled' => 'true',
                    'id' => 'test-id2',
                ],
            ],
            [
                'value' => $value4,
                'label' => $name4,
                'disabled' => false,
                'label_attributes' => ['class' => 'rst3'],
                'attributes' => [
                    'class' => 'efg3',
                    'aria-disabled' => 'false',
                    'id' => 'test-id3',
                ],
            ],
        ];
        $attributes              = ['class' => $class, 'aria-label' => $ariaLabel, 'disabled' => true, 'selected' => true, 'id' => 'zero-id'];
        $labelAttributes         = ['class' => $labelClass, 'test'];
        $labelStart              = '<label>';
        $labelEnd                = '</label>';
        $expected                = '<div></div>';
        $uncheckedValue          = '0';
        $expectedSummary         = $indent . '    ' . sprintf('<input type="hidden" name="%s" value="%s"/>', $name, $uncheckedValue) . PHP_EOL . $indent . '    <div></div>' . PHP_EOL . $indent . '    <div></div>' . PHP_EOL . $indent . '    <div></div>';
        $textDomain              = 'test-domain';
        $renderedField1          = PHP_EOL .
            $indent . '        ' . $labelStart . $value2TranslatedEscaped . $labelEnd . PHP_EOL .
            $indent . '        ' . sprintf('<input class="form-check-input&#x20;%s&#x20;efg" aria-label="%s" id="%s" name="%s&#x5B;&#x5D;" type="checkbox" value="%s"/>', $class, $ariaLabel, $id, $name, $value3) . PHP_EOL .
            $indent . '    ';
        $renderedField2          = PHP_EOL .
            $indent . '        ' . $labelStart . $value3TranslatedEscaped . $labelEnd . PHP_EOL .
            $indent . '        ' . sprintf('<input class="form-check-input&#x20;%s&#x20;efg2" aria-label="%s" disabled="disabled" name="%s&#x5B;&#x5D;" type="checkbox" aria-disabled="true" id="test-id2" value="%s" checked="checked"/>', $class, $ariaLabel, $name, $value1) . PHP_EOL .
            $indent . '    ';
        $renderedField3          = PHP_EOL .
            $indent . '        ' . $labelStart . $name4TranslatedEscaped . $labelEnd . PHP_EOL .
            $indent . '        ' . sprintf('<input class="form-check-input&#x20;%s&#x20;efg3" aria-label="%s" name="%s&#x5B;&#x5D;" type="checkbox" aria-disabled="false" id="test-id3" value="%s" checked="checked"/>', $class, $ariaLabel, $name, $value4) . PHP_EOL .
            $indent . '    ';
        $wrap                    = false;
        $disableEscape           = false;

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::exactly(3))
            ->method('__invoke')
            ->withConsecutive([$value2Translated], [$value3Translated], [$name4Translated])
            ->willReturnOnConsecutiveCalls($value2TranslatedEscaped, $value3TranslatedEscaped, $name4TranslatedEscaped);

        $escapeHtmlAttr = $this->getMockBuilder(EscapeHtmlAttr::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtmlAttr->expects(self::never())
            ->method('__invoke');

        $doctype = $this->getMockBuilder(Doctype::class)
            ->disableOriginalConstructor()
            ->getMock();
        $doctype->expects(self::never())
            ->method('__invoke');
        $doctype->expects(self::once())
            ->method('isXhtml')
            ->willReturn(true);

        $formLabel = $this->getMockBuilder(FormLabelInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formLabel->expects(self::exactly(3))
            ->method('openTag')
            ->withConsecutive(
                [
                    [
                        'class' => sprintf('form-check-label %s rst', $labelClass),
                        'for' => $id,
                    ],
                ],
                [
                    [
                        'class' => sprintf('form-check-label %s rst2', $labelClass),
                        'for' => 'test-id2',
                    ],
                ],
                [
                    [
                        'class' => sprintf('form-check-label %s rst3', $labelClass),
                        'for' => 'test-id3',
                    ],
                ]
            )
            ->willReturn($labelStart);
        $formLabel->expects(self::exactly(3))
            ->method('closeTag')
            ->willReturn($labelEnd);

        $htmlElement = $this->getMockBuilder(HtmlElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $htmlElement->expects(self::exactly(3))
            ->method('toHtml')
            ->withConsecutive(
                ['div', ['class' => ['form-check', 'form-check-inline']], $renderedField1],
                ['div', ['class' => ['form-check', 'form-check-inline']], $renderedField2],
                ['div', ['class' => ['form-check', 'form-check-inline']], $renderedField3]
            )
            ->willReturn($expected);

        $translator = $this->getMockBuilder(Translate::class)
            ->disableOriginalConstructor()
            ->getMock();
        $translator->expects(self::exactly(3))
            ->method('__invoke')
            ->withConsecutive([$value2, $textDomain], [$value3, $textDomain], [$name4, $textDomain])
            ->willReturnOnConsecutiveCalls($value2Translated, $value3Translated, $name4Translated);

        $formHidden = $this->getMockBuilder(FormHiddenInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formHidden->expects(self::once())
            ->method('render')
            ->with(new IsInstanceOf(Hidden::class))
            ->willReturn(sprintf('<input type="hidden" name="%s" value="%s"/>', $name, $uncheckedValue));

        $helper = new FormMultiCheckbox($escapeHtml, $escapeHtmlAttr, $doctype, $formLabel, $htmlElement, $formHidden, $translator);

        $element = $this->getMockBuilder(Radio::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::exactly(2))
            ->method('getName')
            ->willReturn($name);
        $element->expects(self::once())
            ->method('getValueOptions')
            ->willReturn($valueOptions);
        $element->expects(self::once())
            ->method('getAttributes')
            ->willReturn($attributes);
        $element->expects(self::once())
            ->method('getValue')
            ->willReturn($value1);
        $element->expects(self::exactly(2))
            ->method('useHiddenElement')
            ->willReturn(true);
        $element->expects(self::once())
            ->method('getLabelAttributes')
            ->willReturn([]);
        $element->expects(self::once())
            ->method('getOption')
            ->with('layout')
            ->willReturn(Form::LAYOUT_INLINE);
        $element->expects(self::exactly(9))
            ->method('getLabelOption')
            ->withConsecutive(['disable_html_escape'], ['always_wrap'], ['always_wrap'], ['disable_html_escape'], ['always_wrap'], ['always_wrap'], ['disable_html_escape'], ['always_wrap'], ['always_wrap'])
            ->willReturnOnConsecutiveCalls($disableEscape, $wrap, $wrap, $disableEscape, $wrap, $wrap, $disableEscape, $wrap, $wrap);
        $element->expects(self::once())
            ->method('hasLabelOption')
            ->with('label_position')
            ->willReturn(false);
        $element->expects(self::once())
            ->method('getUncheckedValue')
            ->willReturn($uncheckedValue);

        $helper->setTranslatorTextDomain($textDomain);

        $labelPosition = BaseFormRow::LABEL_PREPEND;

        self::assertSame($helper, $helper->setLabelAttributes($labelAttributes));
        self::assertSame($helper, $helper->setIndent($indent));
        self::assertSame($expectedSummary, $helper($element, $labelPosition));
        self::assertSame($labelPosition, $helper->getLabelPosition());
    }

    /**
     * @throws Exception
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testInvokeMultiOptionInlineWithHiddenField4(): void
    {
        $name                    = 'test-name';
        $id                      = 'test-id';
        $value1                  = 'xyz';
        $value2                  = 'def';
        $value2Translated        = 'def-translated';
        $value2TranslatedEscaped = 'def-translated-escaped';
        $value3                  = 'abc';
        $value3Translated        = 'abc-translated';
        $value3TranslatedEscaped = 'abc-translated-escaped';
        $name4                   = 'ghj';
        $name4Translated         = 'ghj-translated';
        $name4TranslatedEscaped  = 'ghj-translated-escaped';
        $value4                  = 'jkl';
        $class                   = 'test-class test-class ';
        $ariaLabel               = 'test';
        $labelClass              = 'xyz';
        $indent                  = '<!-- -->  ';
        $valueOptions            = [
            [
                'value' => $value3,
                'label' => $value2,
                'selected' => false,
                'disabled' => false,
                'label_attributes' => ['class' => 'rst rst-test ', 'data-img' => 'sample1'],
                'attributes' => [
                    'class' => 'efg',
                    'id' => $id,
                ],
            ],
            [
                'value' => $value1,
                'label' => $value3,
                'selected' => false,
                'label_attributes' => ['class' => 'rst2 rst2 ', 'data-vid' => 'sample2'],
                'attributes' => [
                    'class' => 'efg2 test-efg2 ',
                    'aria-disabled' => 'true',
                    'id' => 'test-id2',
                ],
            ],
            [
                'value' => $value4,
                'label' => $name4,
                'disabled' => false,
                'label_attributes' => ['class' => null, 'data-img' => 'sample3'],
                'attributes' => [
                    'class' => 'efg3 test-efg3 ',
                    'aria-disabled' => 'false',
                    'id' => 'test-id3',
                ],
            ],
        ];
        $attributes              = ['class' => $class, 'aria-label' => $ariaLabel, 'disabled' => true, 'selected' => true, 'id' => 'zero-id'];
        $labelAttributes         = ['class' => $labelClass, 'test', 'data-show' => 'yes', 'data-visible' => true];
        $labelStart              = '<label>';
        $labelEnd                = '</label>';
        $expected                = '<div></div>';
        $uncheckedValue          = '0';
        $expectedSummary         = $indent . '    ' . sprintf('<input type="hidden" name="%s" value="%s"/>', $name, $uncheckedValue) . PHP_EOL . $indent . '    <div></div>' . PHP_EOL . $indent . '    <div></div>' . PHP_EOL . $indent . '    <div></div>';
        $textDomain              = 'test-domain';
        $renderedField1          = PHP_EOL .
            $indent . '        ' . $labelStart . $value2TranslatedEscaped . $labelEnd . PHP_EOL .
            $indent . '        ' . sprintf('<input class="form-check-input&#x20;test-class&#x20;efg" aria-label="%s" id="%s" name="%s&#x5B;&#x5D;" type="checkbox" value="%s"/>', $ariaLabel, $id, $name, $value3) . PHP_EOL .
            $indent . '    ';
        $renderedField2          = PHP_EOL .
            $indent . '        ' . $labelStart . $value3TranslatedEscaped . $labelEnd . PHP_EOL .
            $indent . '        ' . sprintf('<input class="form-check-input&#x20;test-class&#x20;efg2&#x20;test-efg2" aria-label="%s" disabled="disabled" name="%s&#x5B;&#x5D;" type="checkbox" aria-disabled="true" id="test-id2" value="%s" checked="checked"/>', $ariaLabel, $name, $value1) . PHP_EOL .
            $indent . '    ';
        $renderedField3          = PHP_EOL .
            $indent . '        ' . $labelStart . $name4TranslatedEscaped . $labelEnd . PHP_EOL .
            $indent . '        ' . sprintf('<input class="form-check-input&#x20;test-class&#x20;efg3&#x20;test-efg3" aria-label="%s" name="%s&#x5B;&#x5D;" type="checkbox" aria-disabled="false" id="test-id3" value="%s" checked="checked"/>', $ariaLabel, $name, $value4) . PHP_EOL .
            $indent . '    ';
        $wrap                    = false;
        $disableEscape           = false;

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::exactly(3))
            ->method('__invoke')
            ->withConsecutive([$value2Translated], [$value3Translated], [$name4Translated])
            ->willReturnOnConsecutiveCalls($value2TranslatedEscaped, $value3TranslatedEscaped, $name4TranslatedEscaped);

        $escapeHtmlAttr = $this->getMockBuilder(EscapeHtmlAttr::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtmlAttr->expects(self::never())
            ->method('__invoke');

        $doctype = $this->getMockBuilder(Doctype::class)
            ->disableOriginalConstructor()
            ->getMock();
        $doctype->expects(self::never())
            ->method('__invoke');
        $doctype->expects(self::once())
            ->method('isXhtml')
            ->willReturn(true);

        $formLabel = $this->getMockBuilder(FormLabelInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formLabel->expects(self::exactly(3))
            ->method('openTag')
            ->withConsecutive(
                [
                    [
                        'class' => sprintf('form-check-label %s rst rst-test', $labelClass),
                        'for' => $id,
                        'data-img' => 'sample1',
                        'data-show' => 'yes',
                        'data-visible' => true,
                    ],
                ],
                [
                    [
                        'class' => sprintf('form-check-label %s rst2', $labelClass),
                        'for' => 'test-id2',
                        'data-vid' => 'sample2',
                        'data-show' => 'yes',
                        'data-visible' => true,
                    ],
                ],
                [
                    [
                        'class' => sprintf('form-check-label %s', $labelClass),
                        'for' => 'test-id3',
                        'data-img' => 'sample3',
                        'data-show' => 'yes',
                        'data-visible' => true,
                    ],
                ]
            )
            ->willReturn($labelStart);
        $formLabel->expects(self::exactly(3))
            ->method('closeTag')
            ->willReturn($labelEnd);

        $htmlElement = $this->getMockBuilder(HtmlElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $htmlElement->expects(self::exactly(3))
            ->method('toHtml')
            ->withConsecutive(
                ['div', ['class' => ['form-check', 'form-check-inline']], $renderedField1],
                ['div', ['class' => ['form-check', 'form-check-inline']], $renderedField2],
                ['div', ['class' => ['form-check', 'form-check-inline']], $renderedField3]
            )
            ->willReturn($expected);

        $translator = $this->getMockBuilder(Translate::class)
            ->disableOriginalConstructor()
            ->getMock();
        $translator->expects(self::exactly(3))
            ->method('__invoke')
            ->withConsecutive([$value2, $textDomain], [$value3, $textDomain], [$name4, $textDomain])
            ->willReturnOnConsecutiveCalls($value2Translated, $value3Translated, $name4Translated);

        $formHidden = $this->getMockBuilder(FormHiddenInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formHidden->expects(self::once())
            ->method('render')
            ->with(new IsInstanceOf(Hidden::class))
            ->willReturn(sprintf('<input type="hidden" name="%s" value="%s"/>', $name, $uncheckedValue));

        $helper = new FormMultiCheckbox($escapeHtml, $escapeHtmlAttr, $doctype, $formLabel, $htmlElement, $formHidden, $translator);

        $element = $this->getMockBuilder(Radio::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::exactly(2))
            ->method('getName')
            ->willReturn($name);
        $element->expects(self::once())
            ->method('getValueOptions')
            ->willReturn($valueOptions);
        $element->expects(self::once())
            ->method('getAttributes')
            ->willReturn($attributes);
        $element->expects(self::once())
            ->method('getValue')
            ->willReturn($value1);
        $element->expects(self::exactly(2))
            ->method('useHiddenElement')
            ->willReturn(true);
        $element->expects(self::once())
            ->method('getLabelAttributes')
            ->willReturn([]);
        $element->expects(self::once())
            ->method('getOption')
            ->with('layout')
            ->willReturn(Form::LAYOUT_INLINE);
        $element->expects(self::exactly(9))
            ->method('getLabelOption')
            ->withConsecutive(['disable_html_escape'], ['always_wrap'], ['always_wrap'], ['disable_html_escape'], ['always_wrap'], ['always_wrap'], ['disable_html_escape'], ['always_wrap'], ['always_wrap'])
            ->willReturnOnConsecutiveCalls($disableEscape, $wrap, $wrap, $disableEscape, $wrap, $wrap, $disableEscape, $wrap, $wrap);
        $element->expects(self::once())
            ->method('hasLabelOption')
            ->with('label_position')
            ->willReturn(false);
        $element->expects(self::once())
            ->method('getUncheckedValue')
            ->willReturn($uncheckedValue);

        $helper->setTranslatorTextDomain($textDomain);

        $labelPosition = BaseFormRow::LABEL_PREPEND;

        self::assertSame($helper, $helper->setLabelAttributes($labelAttributes));
        self::assertSame($helper, $helper->setIndent($indent));
        self::assertSame($expectedSummary, $helper($element, $labelPosition));
        self::assertSame($labelPosition, $helper->getLabelPosition());
    }

    /**
     * @throws Exception
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testInvokeMultiOptionInlineWithHiddenField45(): void
    {
        $name                    = 'test-name';
        $id                      = 'test-id';
        $value1                  = 'xyz';
        $value2                  = 'def';
        $value2Translated        = 'def-translated';
        $value2TranslatedEscaped = 'def-translated-escaped';
        $value3                  = 'abc';
        $value3Translated        = 'abc-translated';
        $value3TranslatedEscaped = 'abc-translated-escaped';
        $name4                   = 'ghj';
        $name4Translated         = 'ghj-translated';
        $name4TranslatedEscaped  = 'ghj-translated-escaped';
        $value4                  = 'jkl';
        $class                   = 'test-class test-class ';
        $ariaLabel               = 'test';
        $labelClass              = 'xyz';
        $indent                  = '<!-- -->  ';
        $valueOptions            = [
            [
                'value' => $value3,
                'label' => $value2,
                'selected' => false,
                'disabled' => false,
                'label_attributes' => ['class' => 'rst rst-test ', 'data-img' => 'sample1'],
                'attributes' => [
                    'class' => 'efg',
                    'id' => $id,
                ],
            ],
            [
                'value' => $value1,
                'label' => $value3,
                'selected' => false,
                'label_attributes' => ['class' => 'rst2 rst2 ', 'data-vid' => 'sample2'],
                'attributes' => [
                    'class' => 'efg2 test-efg2 ',
                    'aria-disabled' => 'true',
                    'id' => 'test-id2',
                ],
            ],
            [
                'value' => $value4,
                'label' => $name4,
                'disabled' => false,
                'label_attributes' => ['class' => null, 'data-img' => 'sample3'],
                'attributes' => [
                    'class' => 'efg3 test-efg3 ',
                    'aria-disabled' => 'false',
                    'id' => 'test-id3',
                ],
            ],
        ];
        $attributes              = ['class' => $class, 'aria-label' => $ariaLabel, 'disabled' => true, 'selected' => true, 'id' => 'zero-id'];
        $labelAttributes         = ['class' => $labelClass, 'test', 'data-show' => 'yes', 'data-visible' => true];
        $labelStart              = '<label>';
        $labelEnd                = '</label>';
        $expected                = '<div></div>';
        $uncheckedValue          = '0';
        $expectedSummary         = $indent . '    ' . sprintf('<input type="hidden" name="%s" value="%s"/>', $name, $uncheckedValue) . PHP_EOL . $indent . '    <div></div>' . PHP_EOL . $indent . '    <div></div>' . PHP_EOL . $indent . '    <div></div>';
        $textDomain              = 'test-domain';
        $renderedField1          = PHP_EOL .
            $indent . '    ' . $labelStart . PHP_EOL .
            $indent . '        <span>' . $value2TranslatedEscaped . '</span>' . PHP_EOL .
            $indent . '        ' . sprintf('<input class="form-check-input&#x20;test-class&#x20;efg" aria-label="%s" id="%s" name="%s&#x5B;&#x5D;" type="checkbox" value="%s"/>', $ariaLabel, $id, $name, $value3) . PHP_EOL .
            $indent . '    ' . $labelEnd . PHP_EOL .
            $indent . '    ';
        $renderedField2          = PHP_EOL .
            $indent . '    ' . $labelStart . PHP_EOL .
            $indent . '        <span>' . $value3TranslatedEscaped . '</span>' . PHP_EOL .
            $indent . '        ' . sprintf('<input class="form-check-input&#x20;test-class&#x20;efg2&#x20;test-efg2" aria-label="%s" disabled="disabled" name="%s&#x5B;&#x5D;" type="checkbox" aria-disabled="true" id="test-id2" value="%s" checked="checked"/>', $ariaLabel, $name, $value1) . PHP_EOL .
            $indent . '    ' . $labelEnd . PHP_EOL .
            $indent . '    ';
        $renderedField3          = PHP_EOL .
            $indent . '    ' . $labelStart . PHP_EOL .
            $indent . '        <span>' . $name4TranslatedEscaped . '</span>' . PHP_EOL .
            $indent . '        ' . sprintf('<input class="form-check-input&#x20;test-class&#x20;efg3&#x20;test-efg3" aria-label="%s" name="%s&#x5B;&#x5D;" type="checkbox" aria-disabled="false" id="test-id3" value="%s" checked="checked"/>', $ariaLabel, $name, $value4) . PHP_EOL .
            $indent . '    ' . $labelEnd . PHP_EOL .
            $indent . '    ';
        $wrap                    = true;
        $disableEscape           = false;

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::exactly(3))
            ->method('__invoke')
            ->withConsecutive([$value2Translated], [$value3Translated], [$name4Translated])
            ->willReturnOnConsecutiveCalls($value2TranslatedEscaped, $value3TranslatedEscaped, $name4TranslatedEscaped);

        $escapeHtmlAttr = $this->getMockBuilder(EscapeHtmlAttr::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtmlAttr->expects(self::never())
            ->method('__invoke');

        $doctype = $this->getMockBuilder(Doctype::class)
            ->disableOriginalConstructor()
            ->getMock();
        $doctype->expects(self::never())
            ->method('__invoke');
        $doctype->expects(self::once())
            ->method('isXhtml')
            ->willReturn(true);

        $formLabel = $this->getMockBuilder(FormLabelInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formLabel->expects(self::exactly(3))
            ->method('openTag')
            ->withConsecutive(
                [
                    [
                        'class' => sprintf('form-check-label %s rst rst-test', $labelClass),
                        'for' => $id,
                        'data-img' => 'sample1',
                        'data-show' => 'yes',
                        'data-visible' => true,
                    ],
                ],
                [
                    [
                        'class' => sprintf('form-check-label %s rst2', $labelClass),
                        'for' => 'test-id2',
                        'data-vid' => 'sample2',
                        'data-show' => 'yes',
                        'data-visible' => true,
                    ],
                ],
                [
                    [
                        'class' => sprintf('form-check-label %s', $labelClass),
                        'for' => 'test-id3',
                        'data-img' => 'sample3',
                        'data-show' => 'yes',
                        'data-visible' => true,
                    ],
                ]
            )
            ->willReturn($labelStart);
        $formLabel->expects(self::exactly(3))
            ->method('closeTag')
            ->willReturn($labelEnd);

        $htmlElement = $this->getMockBuilder(HtmlElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $htmlElement->expects(self::exactly(3))
            ->method('toHtml')
            ->withConsecutive(
                ['div', ['class' => ['form-check', 'form-check-inline']], $renderedField1],
                ['div', ['class' => ['form-check', 'form-check-inline']], $renderedField2],
                ['div', ['class' => ['form-check', 'form-check-inline']], $renderedField3]
            )
            ->willReturn($expected);

        $translator = $this->getMockBuilder(Translate::class)
            ->disableOriginalConstructor()
            ->getMock();
        $translator->expects(self::exactly(3))
            ->method('__invoke')
            ->withConsecutive([$value2, $textDomain], [$value3, $textDomain], [$name4, $textDomain])
            ->willReturnOnConsecutiveCalls($value2Translated, $value3Translated, $name4Translated);

        $formHidden = $this->getMockBuilder(FormHiddenInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formHidden->expects(self::once())
            ->method('render')
            ->with(new IsInstanceOf(Hidden::class))
            ->willReturn(sprintf('<input type="hidden" name="%s" value="%s"/>', $name, $uncheckedValue));

        $helper = new FormMultiCheckbox($escapeHtml, $escapeHtmlAttr, $doctype, $formLabel, $htmlElement, $formHidden, $translator);

        $element = $this->getMockBuilder(Radio::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::exactly(2))
            ->method('getName')
            ->willReturn($name);
        $element->expects(self::once())
            ->method('getValueOptions')
            ->willReturn($valueOptions);
        $element->expects(self::once())
            ->method('getAttributes')
            ->willReturn($attributes);
        $element->expects(self::once())
            ->method('getValue')
            ->willReturn($value1);
        $element->expects(self::exactly(2))
            ->method('useHiddenElement')
            ->willReturn(true);
        $element->expects(self::once())
            ->method('getLabelAttributes')
            ->willReturn([]);
        $element->expects(self::once())
            ->method('getOption')
            ->with('layout')
            ->willReturn(Form::LAYOUT_INLINE);
        $element->expects(self::exactly(9))
            ->method('getLabelOption')
            ->withConsecutive(['disable_html_escape'], ['always_wrap'], ['always_wrap'], ['disable_html_escape'], ['always_wrap'], ['always_wrap'], ['disable_html_escape'], ['always_wrap'], ['always_wrap'])
            ->willReturnOnConsecutiveCalls($disableEscape, $wrap, $wrap, $disableEscape, $wrap, $wrap, $disableEscape, $wrap, $wrap);
        $element->expects(self::once())
            ->method('hasLabelOption')
            ->with('label_position')
            ->willReturn(false);
        $element->expects(self::once())
            ->method('getUncheckedValue')
            ->willReturn($uncheckedValue);

        $helper->setTranslatorTextDomain($textDomain);

        $labelPosition = BaseFormRow::LABEL_PREPEND;

        self::assertSame($helper, $helper->setLabelAttributes($labelAttributes));
        self::assertSame($helper, $helper->setIndent($indent));
        self::assertSame($expectedSummary, $helper($element, $labelPosition));
        self::assertSame($labelPosition, $helper->getLabelPosition());
    }

    /**
     * @throws Exception
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testSetGetIndent1(): void
    {
        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::never())
            ->method('__invoke');

        $escapeHtmlAttr = $this->getMockBuilder(EscapeHtmlAttr::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtmlAttr->expects(self::never())
            ->method('__invoke');

        $doctype = $this->getMockBuilder(Doctype::class)
            ->disableOriginalConstructor()
            ->getMock();
        $doctype->expects(self::never())
            ->method('__invoke');

        $formLabel = $this->getMockBuilder(FormLabelInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formLabel->expects(self::never())
            ->method('openTag');
        $formLabel->expects(self::never())
            ->method('closeTag');

        $htmlElement = $this->getMockBuilder(HtmlElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $htmlElement->expects(self::never())
            ->method('toHtml');

        $formHidden = $this->getMockBuilder(FormHiddenInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formHidden->expects(self::never())
            ->method('render');

        $helper = new FormMultiCheckbox($escapeHtml, $escapeHtmlAttr, $doctype, $formLabel, $htmlElement, $formHidden, null);

        self::assertSame($helper, $helper->setIndent(4));
        self::assertSame('    ', $helper->getIndent());
    }

    /**
     * @throws Exception
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testSetGetIndent2(): void
    {
        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::never())
            ->method('__invoke');

        $escapeHtmlAttr = $this->getMockBuilder(EscapeHtmlAttr::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtmlAttr->expects(self::never())
            ->method('__invoke');

        $doctype = $this->getMockBuilder(Doctype::class)
            ->disableOriginalConstructor()
            ->getMock();
        $doctype->expects(self::never())
            ->method('__invoke');

        $formLabel = $this->getMockBuilder(FormLabelInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formLabel->expects(self::never())
            ->method('openTag');
        $formLabel->expects(self::never())
            ->method('closeTag');

        $htmlElement = $this->getMockBuilder(HtmlElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $htmlElement->expects(self::never())
            ->method('toHtml');

        $formHidden = $this->getMockBuilder(FormHiddenInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formHidden->expects(self::never())
            ->method('render');

        $helper = new FormMultiCheckbox($escapeHtml, $escapeHtmlAttr, $doctype, $formLabel, $htmlElement, $formHidden, null);

        self::assertSame($helper, $helper->setIndent('  '));
        self::assertSame('  ', $helper->getIndent());
    }
}
