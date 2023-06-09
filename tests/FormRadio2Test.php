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

use Laminas\Form\Element\Radio;
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
use Mimmi20\LaminasView\BootstrapForm\FormRadio;
use Mimmi20\LaminasView\Helper\HtmlElement\Helper\HtmlElementInterface;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;

use function sprintf;

use const PHP_EOL;

final class FormRadio2Test extends TestCase
{
    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
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
        $renderedField   = PHP_EOL
            . '    ' . $labelStart . PHP_EOL
            . '        ' . sprintf('<span>%s</span>', $value2Escaped) . PHP_EOL
            . '        ' . sprintf(
                '<input class="form-check-input&#x20;%s" aria-label="%s" name="%s" type="radio" value="%s">',
                $class,
                $ariaLabel,
                $name,
                $value3,
            ) . PHP_EOL
            . '    ' . $labelEnd . PHP_EOL
            . '    ';
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
                ],
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

        $helper = new FormRadio(
            $escapeHtml,
            $escapeHtmlAttr,
            $doctype,
            $formLabel,
            $htmlElement,
            $formHidden,
            null,
        );

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
        $renderedField   = PHP_EOL
            . '        ' . $labelStart . $value2Escaped . $labelEnd . PHP_EOL
            . '        ' . sprintf(
                '<input class="form-check-input&#x20;%s" aria-label="%s" id="%s" name="%s" type="radio" value="%s">',
                $class,
                $ariaLabel,
                $id,
                $name,
                $value3,
            ) . PHP_EOL
            . '    ';
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
                ],
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

        $helper = new FormRadio(
            $escapeHtml,
            $escapeHtmlAttr,
            $doctype,
            $formLabel,
            $htmlElement,
            $formHidden,
            null,
        );

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
            ->willReturnMap(
                [
                    ['label_position', BaseFormRow::LABEL_PREPEND],
                    ['disable_html_escape', false],
                    ['always_wrap', $wrap],
                ],
            );
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
        $renderedField   = PHP_EOL
            . '        ' . sprintf(
                '<input class="form-check-input&#x20;%s" aria-label="%s" id="%s" name="%s" type="radio" value="%s">',
                $class,
                $ariaLabel,
                $id,
                $name,
                $value3,
            ) . PHP_EOL
            . '        ' . $labelStart . $value2 . $labelEnd . PHP_EOL
            . '    ';
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
                ],
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

        $helper = new FormRadio(
            $escapeHtml,
            $escapeHtmlAttr,
            $doctype,
            $formLabel,
            $htmlElement,
            $formHidden,
            null,
        );

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
            ->willReturnMap(
                [
                    ['label_position', BaseFormRow::LABEL_APPEND],
                    ['disable_html_escape', true],
                    ['always_wrap', $wrap],
                ],
            );
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
        $renderedField           = PHP_EOL
            . '        ' . sprintf(
                '<input class="form-check-input&#x20;%s" aria-label="%s" id="%s" name="%s" type="radio" value="%s"/>',
                $class,
                $ariaLabel,
                $id,
                $name,
                $value3,
            ) . PHP_EOL
            . '        ' . $labelStart . $value2TranslatedEscaped . $labelEnd . PHP_EOL
            . '    ';
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
                ],
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

        $helper = new FormRadio(
            $escapeHtml,
            $escapeHtmlAttr,
            $doctype,
            $formLabel,
            $htmlElement,
            $formHidden,
            $translator,
        );

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
            ->willReturnMap(
                [
                    ['label_position', BaseFormRow::LABEL_APPEND],
                    ['disable_html_escape', false],
                    ['always_wrap', $wrap],
                ],
            );
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
        $renderedField1          = PHP_EOL
            . '        ' . sprintf(
                '<input class="form-check-input&#x20;%s&#x20;efg" aria-label="%s" name="%s" type="radio" id="%s" value="%s"/>',
                $class,
                $ariaLabel,
                $name,
                $id,
                $value3,
            ) . PHP_EOL
            . '        ' . $labelStart . $value2TranslatedEscaped . $labelEnd . PHP_EOL
            . '    ';
        $renderedField2          = PHP_EOL
            . '        ' . sprintf(
                '<input class="form-check-input&#x20;%s&#x20;efg2" aria-label="%s" disabled="disabled" name="%s" type="radio" aria-disabled="true" id="test-id2" value="%s" checked="checked"/>',
                $class,
                $ariaLabel,
                $name,
                $value1,
            ) . PHP_EOL
            . '        ' . $labelStart . $value3TranslatedEscaped . $labelEnd . PHP_EOL
            . '    ';
        $renderedField3          = PHP_EOL
            . '        ' . sprintf(
                '<input class="form-check-input&#x20;%s&#x20;efg3" aria-label="%s" name="%s" type="radio" aria-disabled="false" id="test-id3" value="%s"/>',
                $class,
                $ariaLabel,
                $name,
                $value4,
            ) . PHP_EOL
            . '        ' . $labelStart . $name4TranslatedEscaped . $labelEnd . PHP_EOL
            . '    ';
        $wrap                    = false;
        $disableEscape           = false;

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::exactly(3))
            ->method('__invoke')
            ->willReturnMap(
                [
                    [$value2Translated, 0, $value2TranslatedEscaped],
                    [$value3Translated, 0, $value3TranslatedEscaped],
                    [$name4Translated, 0, $name4TranslatedEscaped],
                ],
            );

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
            ->willReturnMap(
                [
                    [
                        [
                            'class' => sprintf(
                                'form-check-label %s rst',
                                $labelClass,
                            ),
                            'for' => $id,
                        ],
                        $labelStart,
                    ],
                    [
                        [
                            'class' => sprintf(
                                'form-check-label %s rst2',
                                $labelClass,
                            ),
                            'for' => 'test-id2',
                        ],
                        $labelStart,
                    ],
                    [
                        [
                            'class' => sprintf(
                                'form-check-label %s rst3',
                                $labelClass,
                            ),
                            'for' => 'test-id3',
                        ],
                        $labelStart,
                    ],
                ],
            );
        $formLabel->expects(self::exactly(3))
            ->method('closeTag')
            ->willReturn($labelEnd);

        $htmlElement = $this->getMockBuilder(HtmlElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $htmlElement->expects(self::exactly(3))
            ->method('toHtml')
            ->willReturnMap(
                [
                    ['div', ['class' => ['form-check']], $renderedField1, $expected],
                    ['div', ['class' => ['form-check']], $renderedField2, $expected],
                    ['div', ['class' => ['form-check']], $renderedField3, $expected],
                ],
            );

        $translator = $this->getMockBuilder(Translate::class)
            ->disableOriginalConstructor()
            ->getMock();
        $translator->expects(self::exactly(3))
            ->method('__invoke')
            ->willReturnMap(
                [
                    [$value2, $textDomain, null, $value2Translated],
                    [$value3, $textDomain, null, $value3Translated],
                    [$name4, $textDomain, null, $name4Translated],
                ],
            );

        $formHidden = $this->getMockBuilder(FormHiddenInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formHidden->expects(self::never())
            ->method('render');

        $helper = new FormRadio(
            $escapeHtml,
            $escapeHtmlAttr,
            $doctype,
            $formLabel,
            $htmlElement,
            $formHidden,
            $translator,
        );

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
            ->willReturnMap(
                [
                    ['disable_html_escape', $disableEscape],
                    ['always_wrap', $wrap],
                ],
            );
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
}
