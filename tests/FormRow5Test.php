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

use Laminas\Form\Element\Button;
use Laminas\Form\Element\Radio;
use Laminas\Form\Element\Text;
use Laminas\Form\Exception\DomainException;
use Laminas\I18n\Exception\RuntimeException;
use Laminas\I18n\View\Helper\Translate;
use Laminas\ServiceManager\Exception\InvalidServiceException;
use Laminas\ServiceManager\Exception\ServiceNotFoundException;
use Laminas\View\Exception\InvalidArgumentException;
use Laminas\View\Helper\EscapeHtml;
use Laminas\View\Renderer\PhpRenderer;
use Laminas\View\Renderer\RendererInterface;
use Mimmi20\LaminasView\BootstrapForm\Form;
use Mimmi20\LaminasView\BootstrapForm\FormElementErrorsInterface;
use Mimmi20\LaminasView\BootstrapForm\FormElementInterface;
use Mimmi20\LaminasView\BootstrapForm\FormRow;
use Mimmi20\LaminasView\Helper\HtmlElement\Helper\HtmlElementInterface;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;

use const PHP_EOL;

/**
 * @group form-row
 */
final class FormRow5Test extends TestCase
{
    private FormRow $helper;

    /** @throws void */
    protected function setUp(): void
    {
        $this->helper = new FormRow();
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws ServiceNotFoundException
     * @throws InvalidServiceException
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function testRenderTextWithoutFormOptionAndLabel15(): void
    {
        $label                  = 'test-label';
        $labelTranslated        = 'test-label-translated';
        $labelTranslatedEscaped = 'test-label-translated-escaped';
        $messages               = ['x' => 'y'];
        $type                   = 'radio';
        $class                  = 'test-class';
        $indent                 = '<!-- -->  ';
        $expected               = '<hidden></hidden>';
        $expectedErrors         = '<errors></errors>';
        $renderErrors           = true;
        $required               = true;
        $showRequiredMark       = false;
        $layout                 = Form::LAYOUT_VERTICAL;
        $helpContent            = 'help';
        $helpAttributes         = ['a' => 'b'];
        $expectedHelp           = '<help></help>';
        $id                     = 'test-id';
        $aria                   = 'aria-described';
        $form                   = null;
        $colAttributes          = ['e' => 'f'];
        $labelAttributes        = ['g' => 'h'];
        $expectedCol            = '<col1></col1>';
        $textDomain             = 'text-domain';
        $disableEscape          = false;

        $element = $this->createMock(Button::class);
        $matcher = self::exactly(11);
        $element->expects($matcher)
            ->method('getOption')
            ->willReturnCallback(
                static function (string $option) use ($matcher, $form, $showRequiredMark, $layout, $helpContent, $helpAttributes, $colAttributes, $labelAttributes): mixed {
                    $invocation = $matcher->numberOfInvocations();

                    match ($invocation) {
                        1, 5, 7, 11 => self::assertSame(
                            'form',
                            $option,
                            (string) $invocation,
                        ),
                        2 => self::assertSame(
                            'show-required-mark',
                            $option,
                            (string) $invocation,
                        ),
                        3 => self::assertSame(
                            'layout',
                            $option,
                            (string) $invocation,
                        ),
                        8, 9 => self::assertSame(
                            'help_content',
                            $option,
                            (string) $invocation,
                        ),
                        10 => self::assertSame(
                            'help_attributes',
                            $option,
                            (string) $invocation,
                        ),
                        4 => self::assertSame(
                            'col_attributes',
                            $option,
                            (string) $invocation,
                        ),
                        6 => self::assertSame(
                            'label_attributes',
                            $option,
                            (string) $invocation,
                        ),
                        default => self::assertSame(
                            'fieldset',
                            $option,
                            (string) $invocation,
                        ),
                    };

                    return match ($invocation) {
                        1, 5, 7, 11 => $form,
                        2 => $showRequiredMark,
                        3 => $layout,
                        8, 9 => $helpContent,
                        10 => $helpAttributes,
                        4 => $colAttributes,
                        6 => $labelAttributes,
                        default => null,
                    };
                },
            );
        $element->expects(self::once())
            ->method('getName')
            ->willReturn('element-name');
        $matcher = self::exactly(7);
        $element->expects($matcher)
            ->method('hasAttribute')
            ->willReturnCallback(
                static function (string $key) use ($matcher): bool {
                    $invocation = $matcher->numberOfInvocations();

                    match ($invocation) {
                        1 => self::assertSame(
                            'required',
                            $key,
                            (string) $invocation,
                        ),
                        3, 4, 6 => self::assertSame(
                            'id',
                            $key,
                            (string) $invocation,
                        ),
                        5, 7 => self::assertSame(
                            'aria-describedby',
                            $key,
                            (string) $invocation,
                        ),
                        default => self::assertSame(
                            'class',
                            $key,
                            (string) $invocation,
                        ),
                    };

                    return match ($invocation) {
                        1 => false,
                        default => true,
                    };
                },
            );
        $element->expects(self::exactly(3))
            ->method('setAttribute')
            ->willReturnMap(
                [
                    ['class', $class . ' is-invalid', null],
                    ['aria-describedby', $aria . ' ' . $id . 'Feedback', null],
                    ['aria-describedby', $aria . ' ' . $id . 'Feedback ' . $id . 'Help', null],
                ],
            );
        $element->expects(self::exactly(9))
            ->method('getAttribute')
            ->willReturnMap(
                [
                    ['type', $type],
                    ['class', $class],
                    ['required', $required],
                    ['id', $id],
                    ['aria-describedby', $aria],
                ],
            );
        $element->expects(self::once())
            ->method('getLabel')
            ->willReturn($label);
        $element->expects(self::once())
            ->method('getMessages')
            ->willReturn($messages);
        $element->expects(self::never())
            ->method('hasLabelOption');
        $element->expects(self::once())
            ->method('getLabelOption')
            ->with('disable_html_escape')
            ->willReturn($disableEscape);

        $formElement = $this->createMock(FormElementInterface::class);
        $formElement->expects(self::once())
            ->method('setIndent')
            ->with($indent . '    ');
        $formElement->expects(self::once())
            ->method('render')
            ->with($element)
            ->willReturn($expected);

        $formElementErrors = $this->createMock(FormElementErrorsInterface::class);
        $formElementErrors->expects(self::once())
            ->method('setIndent')
            ->with($indent . '    ');
        $formElementErrors->expects(self::once())
            ->method('render')
            ->with($element)
            ->willReturn($expectedErrors);

        $htmlElement = $this->createMock(HtmlElementInterface::class);
        $htmlElement->expects(self::exactly(2))
            ->method('toHtml')
            ->willReturnMap(
                [
                    ['div', $helpAttributes + ['id' => $id . 'Help'], $helpContent, $expectedHelp],
                    ['div', $colAttributes, PHP_EOL . $expected . $expectedErrors . PHP_EOL . $indent . '    ' . $expectedHelp . PHP_EOL . $indent, $expectedCol],
                ],
            );

        $escapeHtml = $this->createMock(EscapeHtml::class);
        $escapeHtml->expects(self::once())
            ->method('__invoke')
            ->with($labelTranslated, 0)
            ->willReturn($labelTranslatedEscaped);

        $translator = $this->createMock(Translate::class);
        $translator->expects(self::once())
            ->method('__invoke')
            ->with($label, $textDomain, null)
            ->willReturn($labelTranslated);

        $renderer = $this->createMock(PhpRenderer::class);
        $renderer->expects(self::never())
            ->method('getHelperPluginManager');
        $renderer->expects(self::never())
            ->method('plugin');
        $renderer->expects(self::never())
            ->method('render');

        $this->helper->setView($renderer);
        $this->helper->setIndent($indent);
        $this->helper->setRenderErrors($renderErrors);
        $this->helper->setTranslatorTextDomain($textDomain);

        self::assertSame($indent . $expectedCol, $this->helper->render($element));
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws ServiceNotFoundException
     * @throws InvalidServiceException
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function testRenderTextWithoutFormOptionAndLabel16(): void
    {
        $label                  = 'test-label';
        $labelTranslated        = 'test-label-translated';
        $labelTranslatedEscaped = 'test-label-translated-escaped';
        $messages               = ['x' => 'y'];
        $type                   = 'radio';
        $class                  = 'test-class';
        $indent                 = '<!-- -->  ';
        $expected               = '<hidden></hidden>';
        $expectedErrors         = '<errors></errors>';
        $renderErrors           = true;
        $required               = true;
        $showRequiredMark       = false;
        $layout                 = Form::LAYOUT_VERTICAL;
        $helpContent            = 'help';
        $helpAttributes         = ['a' => 'b'];
        $expectedHelp           = '<help></help>';
        $id                     = 'test-id';
        $aria                   = 'aria-described';
        $form                   = null;
        $colAttributes          = ['e' => 'f'];
        $labelAttributes        = ['g' => 'h'];
        $legendAttributes       = ['i' => 'j', 'class' => 'legend-class'];
        $expectedLegend         = '<legend></legend>';
        $expectedCol            = '<col1></col1>';
        $textDomain             = 'text-domain';
        $disableEscape          = false;
        $floating               = false;

        $element = $this->createMock(Radio::class);
        $matcher = self::exactly(14);
        $element->expects($matcher)
            ->method('getOption')
            ->willReturnCallback(
                static function (string $option) use ($matcher, $form, $showRequiredMark, $layout, $helpContent, $helpAttributes, $legendAttributes, $colAttributes, $labelAttributes, $floating): mixed {
                    $invocation = $matcher->numberOfInvocations();

                    match ($invocation) {
                        1, 5, 7, 9, 13 => self::assertSame(
                            'form',
                            $option,
                            (string) $invocation,
                        ),
                        2 => self::assertSame(
                            'show-required-mark',
                            $option,
                            (string) $invocation,
                        ),
                        3 => self::assertSame(
                            'layout',
                            $option,
                            (string) $invocation,
                        ),
                        10, 11 => self::assertSame(
                            'help_content',
                            $option,
                            (string) $invocation,
                        ),
                        12 => self::assertSame(
                            'help_attributes',
                            $option,
                            (string) $invocation,
                        ),
                        8 => self::assertSame(
                            'legend_attributes',
                            $option,
                            (string) $invocation,
                        ),
                        4 => self::assertSame(
                            'col_attributes',
                            $option,
                            (string) $invocation,
                        ),
                        6 => self::assertSame(
                            'label_attributes',
                            $option,
                            (string) $invocation,
                        ),
                        14 => self::assertSame(
                            'floating',
                            $option,
                            (string) $invocation,
                        ),
                        default => self::assertSame(
                            'fieldset',
                            $option,
                            (string) $invocation,
                        ),
                    };

                    return match ($invocation) {
                        1, 5, 7, 9, 13 => $form,
                        2 => $showRequiredMark,
                        3 => $layout,
                        10, 11 => $helpContent,
                        12 => $helpAttributes,
                        8 => $legendAttributes,
                        4 => $colAttributes,
                        6 => $labelAttributes,
                        14 => $floating,
                        default => null,
                    };
                },
            );
        $element->expects(self::once())
            ->method('getName')
            ->willReturn('element-name');
        $matcher = self::exactly(7);
        $element->expects($matcher)
            ->method('hasAttribute')
            ->willReturnCallback(
                static function (string $key) use ($matcher): bool {
                    $invocation = $matcher->numberOfInvocations();

                    match ($invocation) {
                        1 => self::assertSame(
                            'required',
                            $key,
                            (string) $invocation,
                        ),
                        3, 4, 6 => self::assertSame(
                            'id',
                            $key,
                            (string) $invocation,
                        ),
                        5, 7 => self::assertSame(
                            'aria-describedby',
                            $key,
                            (string) $invocation,
                        ),
                        default => self::assertSame(
                            'class',
                            $key,
                            (string) $invocation,
                        ),
                    };

                    return match ($invocation) {
                        1 => false,
                        default => true,
                    };
                },
            );
        $element->expects(self::exactly(3))
            ->method('setAttribute')
            ->willReturnMap(
                [
                    ['class', $class . ' is-invalid', null],
                    ['aria-describedby', $aria . ' ' . $id . 'Feedback', null],
                    ['aria-describedby', $aria . ' ' . $id . 'Feedback ' . $id . 'Help', null],
                ],
            );
        $element->expects(self::exactly(9))
            ->method('getAttribute')
            ->willReturnMap(
                [
                    ['type', $type],
                    ['class', $class],
                    ['required', $required],
                    ['id', $id],
                    ['aria-describedby', $aria],
                ],
            );
        $element->expects(self::once())
            ->method('getLabel')
            ->willReturn($label);
        $element->expects(self::once())
            ->method('getMessages')
            ->willReturn($messages);
        $element->expects(self::never())
            ->method('hasLabelOption');
        $element->expects(self::once())
            ->method('getLabelOption')
            ->with('disable_html_escape')
            ->willReturn($disableEscape);

        $formElement = $this->createMock(FormElementInterface::class);
        $formElement->expects(self::once())
            ->method('setIndent')
            ->with($indent . '    ');
        $formElement->expects(self::once())
            ->method('render')
            ->with($element)
            ->willReturn($expected);

        $formElementErrors = $this->createMock(FormElementErrorsInterface::class);
        $formElementErrors->expects(self::once())
            ->method('setIndent')
            ->with($indent . '        ');
        $formElementErrors->expects(self::once())
            ->method('render')
            ->with($element)
            ->willReturn($expectedErrors);

        $htmlElement = $this->createMock(HtmlElementInterface::class);
        $htmlElement->expects(self::exactly(3))
            ->method('toHtml')
            ->willReturnMap(
                [
                    ['legend', $legendAttributes + ['class' => ''], $labelTranslatedEscaped, $expectedLegend],
                    ['div', $helpAttributes + ['id' => $id . 'Help'], $helpContent, $expectedHelp],
                    ['fieldset', $colAttributes, PHP_EOL . $indent . '    ' . $expectedLegend . PHP_EOL . $indent . '    ' . $expected . $expectedErrors . PHP_EOL . $indent . '        ' . $expectedHelp . PHP_EOL . $indent, $expectedCol],
                ],
            );

        $escapeHtml = $this->createMock(EscapeHtml::class);
        $escapeHtml->expects(self::once())
            ->method('__invoke')
            ->with($labelTranslated, 0)
            ->willReturn($labelTranslatedEscaped);

        $translator = $this->createMock(Translate::class);
        $translator->expects(self::once())
            ->method('__invoke')
            ->with($label, $textDomain, null)
            ->willReturn($labelTranslated);

        $renderer = $this->createMock(PhpRenderer::class);
        $renderer->expects(self::never())
            ->method('getHelperPluginManager');
        $renderer->expects(self::never())
            ->method('plugin');
        $renderer->expects(self::never())
            ->method('render');

        $this->helper->setView($renderer);
        $this->helper->setIndent($indent);
        $this->helper->setRenderErrors($renderErrors);
        $this->helper->setTranslatorTextDomain($textDomain);

        self::assertSame($indent . $expectedCol, $this->helper->render($element));
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws ServiceNotFoundException
     * @throws InvalidServiceException
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function testRenderTextWithoutFormOptionAndLabel17(): void
    {
        $label                  = 'test-label';
        $labelTranslated        = 'test-label-translated';
        $labelTranslatedEscaped = 'test-label-translated-escaped';
        $messages               = ['x' => 'y'];
        $type                   = 'radio';
        $class                  = 'test-class';
        $indent                 = '<!-- -->  ';
        $expected               = '<hidden></hidden>';
        $expectedErrors         = '<errors></errors>';
        $renderErrors           = true;
        $required               = true;
        $showRequiredMark       = false;
        $layout                 = Form::LAYOUT_VERTICAL;
        $helpContent            = 'help';
        $helpAttributes         = ['a' => 'b'];
        $expectedHelp           = '<help></help>';
        $id                     = 'test-id';
        $aria                   = 'aria-described';
        $form                   = null;
        $colAttributes          = ['e' => 'f'];
        $labelAttributes        = ['g' => 'h'];
        $expectedLegend         = '<legend></legend>';
        $expectedCol            = '<col1></col1>';
        $textDomain             = 'text-domain';
        $floating               = false;
        $labelPosition          = \Laminas\Form\View\Helper\FormRow::LABEL_APPEND;
        $disableEscape          = false;

        $element = $this->createMock(Text::class);
        $matcher = self::exactly(12);
        $element->expects($matcher)
            ->method('getOption')
            ->willReturnCallback(
                static function (string $option) use ($matcher, $form, $showRequiredMark, $layout, $helpContent, $helpAttributes, $colAttributes, $labelAttributes, $floating): mixed {
                    $invocation = $matcher->numberOfInvocations();

                    match ($invocation) {
                        1, 5, 7, 12 => self::assertSame(
                            'form',
                            $option,
                            (string) $invocation,
                        ),
                        2 => self::assertSame(
                            'show-required-mark',
                            $option,
                            (string) $invocation,
                        ),
                        3 => self::assertSame(
                            'layout',
                            $option,
                            (string) $invocation,
                        ),
                        9, 10 => self::assertSame(
                            'help_content',
                            $option,
                            (string) $invocation,
                        ),
                        11 => self::assertSame(
                            'help_attributes',
                            $option,
                            (string) $invocation,
                        ),
                        4 => self::assertSame(
                            'col_attributes',
                            $option,
                            (string) $invocation,
                        ),
                        6 => self::assertSame(
                            'label_attributes',
                            $option,
                            (string) $invocation,
                        ),
                        8 => self::assertSame(
                            'floating',
                            $option,
                            (string) $invocation,
                        ),
                        default => self::assertSame(
                            'fieldset',
                            $option,
                            (string) $invocation,
                        ),
                    };

                    return match ($invocation) {
                        1, 5, 7, 12 => $form,
                        2 => $showRequiredMark,
                        3 => $layout,
                        9, 10 => $helpContent,
                        11 => $helpAttributes,
                        4 => $colAttributes,
                        6 => $labelAttributes,
                        8 => $floating,
                        default => null,
                    };
                },
            );
        $element->expects(self::once())
            ->method('getName')
            ->willReturn('element-name');
        $matcher = self::exactly(7);
        $element->expects($matcher)
            ->method('hasAttribute')
            ->willReturnCallback(
                static function (string $key) use ($matcher): bool {
                    $invocation = $matcher->numberOfInvocations();

                    match ($invocation) {
                        1 => self::assertSame(
                            'required',
                            $key,
                            (string) $invocation,
                        ),
                        3, 4, 6 => self::assertSame(
                            'id',
                            $key,
                            (string) $invocation,
                        ),
                        5, 7 => self::assertSame(
                            'aria-describedby',
                            $key,
                            (string) $invocation,
                        ),
                        default => self::assertSame(
                            'class',
                            $key,
                            (string) $invocation,
                        ),
                    };

                    return match ($invocation) {
                        1 => false,
                        default => true,
                    };
                },
            );
        $element->expects(self::exactly(3))
            ->method('setAttribute')
            ->willReturnMap(
                [
                    ['class', $class . ' is-invalid', null],
                    ['aria-describedby', $aria . ' ' . $id . 'Feedback', null],
                    ['aria-describedby', $aria . ' ' . $id . 'Feedback ' . $id . 'Help', null],
                ],
            );
        $element->expects(self::exactly(9))
            ->method('getAttribute')
            ->willReturnMap(
                [
                    ['type', $type],
                    ['class', $class],
                    ['required', $required],
                    ['id', $id],
                    ['aria-describedby', $aria],
                ],
            );
        $element->expects(self::once())
            ->method('getLabel')
            ->willReturn($label);
        $element->expects(self::once())
            ->method('getMessages')
            ->willReturn($messages);
        $element->expects(self::once())
            ->method('hasLabelOption')
            ->with('label_position')
            ->willReturn(true);
        $element->expects(self::exactly(2))
            ->method('getLabelOption')
            ->willReturnMap(
                [
                    ['disable_html_escape', $disableEscape],
                    ['label_position', $labelPosition],
                ],
            );

        $formElement = $this->createMock(FormElementInterface::class);
        $formElement->expects(self::once())
            ->method('setIndent')
            ->with($indent . '    ');
        $formElement->expects(self::once())
            ->method('render')
            ->with($element)
            ->willReturn($expected);

        $formElementErrors = $this->createMock(FormElementErrorsInterface::class);
        $formElementErrors->expects(self::once())
            ->method('setIndent')
            ->with($indent . '    ');
        $formElementErrors->expects(self::once())
            ->method('render')
            ->with($element)
            ->willReturn($expectedErrors);

        $htmlElement = $this->createMock(HtmlElementInterface::class);
        $htmlElement->expects(self::exactly(3))
            ->method('toHtml')
            ->willReturnMap(
                [
                    ['label', $labelAttributes + ['class' => 'form-label', 'for' => $id], $labelTranslatedEscaped, $expectedLegend],
                    ['div', $helpAttributes + ['id' => $id . 'Help'], $helpContent, $expectedHelp],
                    ['div', $colAttributes, PHP_EOL . $expected . PHP_EOL . $indent . '    ' . $expectedLegend . $expectedErrors . PHP_EOL . $indent . '    ' . $expectedHelp . PHP_EOL . $indent, $expectedCol],
                ],
            );

        $escapeHtml = $this->createMock(EscapeHtml::class);
        $escapeHtml->expects(self::once())
            ->method('__invoke')
            ->with($labelTranslated, 0)
            ->willReturn($labelTranslatedEscaped);

        $translator = $this->createMock(Translate::class);
        $translator->expects(self::once())
            ->method('__invoke')
            ->with($label, $textDomain, null)
            ->willReturn($labelTranslated);

        $renderer = $this->createMock(PhpRenderer::class);
        $renderer->expects(self::never())
            ->method('getHelperPluginManager');
        $renderer->expects(self::never())
            ->method('plugin');
        $renderer->expects(self::never())
            ->method('render');

        $this->helper->setView($renderer);
        $this->helper->setIndent($indent);
        $this->helper->setRenderErrors($renderErrors);
        $this->helper->setTranslatorTextDomain($textDomain);

        self::assertSame($indent . $expectedCol, $this->helper->render($element));
    }
}
