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

use Laminas\Form\Element\Hidden;
use Laminas\Form\Element\Radio;
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
use PHPUnit\Framework\Constraint\IsInstanceOf;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;

use function sprintf;

use const PHP_EOL;

final class FormRadio5Test extends TestCase
{
    /** @throws Exception */
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
                'label_attributes' => ['class' => 'rst2', 'data-vid' => 'sample2'],
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
                'label_attributes' => ['class' => null, 'data-img' => 'sample3'],
                'attributes' => [
                    'class' => 'efg3',
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
        $expectedSummary         = $indent . '    ' . sprintf(
            '<input type="hidden" name="%s" value="%s"/>',
            $name,
            $uncheckedValue,
        ) . PHP_EOL . $indent . '    <div></div>' . PHP_EOL . $indent . '    <div></div>' . PHP_EOL . $indent . '    <div></div>';
        $textDomain              = 'test-domain';
        $renderedField1          = PHP_EOL
            . $indent . '        ' . $labelStart . $value2TranslatedEscaped . $labelEnd . PHP_EOL
            . $indent . '        ' . sprintf(
                '<input class="form-check-input&#x20;%s&#x20;efg" aria-label="%s" id="%s" name="%s" type="radio" value="%s"/>',
                $class,
                $ariaLabel,
                $id,
                $name,
                $value3,
            ) . PHP_EOL
            . $indent . '    ';
        $renderedField2          = PHP_EOL
            . $indent . '        ' . $labelStart . $value3TranslatedEscaped . $labelEnd . PHP_EOL
            . $indent . '        ' . sprintf(
                '<input class="form-check-input&#x20;%s&#x20;efg2" aria-label="%s" disabled="disabled" name="%s" type="radio" aria-disabled="true" id="test-id2" value="%s" checked="checked"/>',
                $class,
                $ariaLabel,
                $name,
                $value1,
            ) . PHP_EOL
            . $indent . '    ';
        $renderedField3          = PHP_EOL
            . $indent . '        ' . $labelStart . $name4TranslatedEscaped . $labelEnd . PHP_EOL
            . $indent . '        ' . sprintf(
                '<input class="form-check-input&#x20;%s&#x20;efg3" aria-label="%s" name="%s" type="radio" aria-disabled="false" id="test-id3" value="%s"/>',
                $class,
                $ariaLabel,
                $name,
                $value4,
            ) . PHP_EOL
            . $indent . '    ';
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
                                'form-check-label %s rst rst-test',
                                $labelClass,
                            ),
                            'data-show' => 'yes',
                            'data-visible' => true,
                            'data-img' => 'sample1',
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
                            'data-show' => 'yes',
                            'data-visible' => true,
                            'data-vid' => 'sample2',
                            'for' => 'test-id2',
                        ],
                        $labelStart,
                    ],
                    [
                        [
                            'class' => sprintf(
                                'form-check-label %s',
                                $labelClass,
                            ),
                            'data-show' => 'yes',
                            'data-visible' => true,
                            'data-img' => 'sample3',
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
                    ['div', ['class' => ['form-check', 'form-check-inline']], $renderedField1, $expected],
                    ['div', ['class' => ['form-check', 'form-check-inline']], $renderedField2, $expected],
                    ['div', ['class' => ['form-check', 'form-check-inline']], $renderedField3, $expected],
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
        $formHidden->expects(self::once())
            ->method('render')
            ->with(new IsInstanceOf(Hidden::class))
            ->willReturn(
                sprintf('<input type="hidden" name="%s" value="%s"/>', $name, $uncheckedValue),
            );

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
        $element->expects(self::once())
            ->method('useHiddenElement')
            ->willReturn(true);
        $element->expects(self::once())
            ->method('getLabelAttributes')
            ->willReturn([]);
        $element->expects(self::once())
            ->method('getOption')
            ->with('layout')
            ->willReturn(Form::LAYOUT_INLINE);
        $matcher = self::exactly(9);
        $element->expects($matcher)
            ->method('getLabelOption')
            ->willReturnCallback(
                static function (int | string $key) use ($matcher, $disableEscape, $wrap): mixed {
                    match ($matcher->numberOfInvocations()) {
                        1, 4, 7 => self::assertSame(
                            'disable_html_escape',
                            $key,
                            (string) $matcher->numberOfInvocations(),
                        ),
                        default => self::assertSame(
                            'always_wrap',
                            $key,
                            (string) $matcher->numberOfInvocations(),
                        ),
                    };

                    return match ($matcher->numberOfInvocations()) {
                        1, 4, 7 => $disableEscape,
                        default => $wrap,
                    };
                },
            );
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

    /** @throws Exception */
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

        $helper = new FormRadio(
            $escapeHtml,
            $escapeHtmlAttr,
            $doctype,
            $formLabel,
            $htmlElement,
            $formHidden,
            null,
        );

        self::assertSame($helper, $helper->setIndent(4));
        self::assertSame('    ', $helper->getIndent());
    }

    /** @throws Exception */
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

        $helper = new FormRadio(
            $escapeHtml,
            $escapeHtmlAttr,
            $doctype,
            $formLabel,
            $htmlElement,
            $formHidden,
            null,
        );

        self::assertSame($helper, $helper->setIndent('  '));
        self::assertSame('  ', $helper->getIndent());
    }
}
