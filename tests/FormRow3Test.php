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

use Laminas\Form\ElementInterface;
use Laminas\Form\Exception\DomainException;
use Laminas\I18n\View\Helper\Translate;
use Laminas\ServiceManager\Exception\InvalidServiceException;
use Laminas\ServiceManager\Exception\ServiceNotFoundException;
use Laminas\View\Helper\EscapeHtml;
use Laminas\View\Renderer\RendererInterface;
use Mimmi20\LaminasView\BootstrapForm\FormElementErrorsInterface;
use Mimmi20\LaminasView\BootstrapForm\FormElementInterface;
use Mimmi20\LaminasView\BootstrapForm\FormRow;
use Mimmi20\LaminasView\Helper\HtmlElement\Helper\HtmlElementInterface;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;

use const PHP_EOL;

final class FormRow3Test extends TestCase
{
    /**
     * @throws Exception
     * @throws DomainException
     * @throws ServiceNotFoundException
     * @throws InvalidServiceException
     */
    public function testRenderTextWithoutFormOptionAndLabel6(): void
    {
        $label            = '';
        $messages         = ['x' => 'y'];
        $type             = 'text';
        $class            = 'test-class';
        $indent           = '<!-- -->  ';
        $expected         = '<hidden></hidden>';
        $renderErrors     = false;
        $required         = true;
        $showRequiredMark = false;
        $layout           = null;
        $helpContent      = null;
        $form             = null;
        $textDomain       = 'text-domain';

        $element = $this->getMockBuilder(ElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $matcher = self::exactly(4);
        $element->expects($matcher)
            ->method('getOption')
            ->willReturnCallback(
                static function (string $option) use ($matcher, $form, $showRequiredMark, $layout, $helpContent): mixed {
                    match ($matcher->numberOfInvocations()) {
                        1 => self::assertSame(
                            'form',
                            $option,
                            (string) $matcher->numberOfInvocations(),
                        ),
                        2 => self::assertSame(
                            'show-required-mark',
                            $option,
                            (string) $matcher->numberOfInvocations(),
                        ),
                        3 => self::assertSame(
                            'layout',
                            $option,
                            (string) $matcher->numberOfInvocations(),
                        ),
                        4 => self::assertSame(
                            'help_content',
                            $option,
                            (string) $matcher->numberOfInvocations(),
                        ),
                        default => self::assertSame(
                            'fieldset',
                            $option,
                            (string) $matcher->numberOfInvocations(),
                        ),
                    };

                    return match ($matcher->numberOfInvocations()) {
                        1 => $form,
                        2 => $showRequiredMark,
                        3 => $layout,
                        4 => $helpContent,
                        default => null,
                    };
                },
            );
        $element->expects(self::once())
            ->method('getName')
            ->willReturn('element-name');
        $matcher = self::exactly(2);
        $element->expects($matcher)
            ->method('hasAttribute')
            ->willReturnCallback(
                static function (string $key) use ($matcher): bool {
                    match ($matcher->numberOfInvocations()) {
                        1 => self::assertSame(
                            'required',
                            $key,
                            (string) $matcher->numberOfInvocations(),
                        ),
                        default => self::assertSame(
                            'class',
                            $key,
                            (string) $matcher->numberOfInvocations(),
                        ),
                    };

                    return match ($matcher->numberOfInvocations()) {
                        1 => false,
                        default => true,
                    };
                },
            );
        $element->expects(self::once())
            ->method('setAttribute')
            ->with('class', $class . ' is-invalid');
        $element->expects(self::exactly(3))
            ->method('getAttribute')
            ->willReturnMap(
                [
                    ['type', $type],
                    ['class', $class],
                    ['required', $required],
                ],
            );
        $element->expects(self::once())
            ->method('getLabel')
            ->willReturn($label);
        $element->expects(self::once())
            ->method('getMessages')
            ->willReturn($messages);

        $formElement = $this->getMockBuilder(FormElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formElement->expects(self::once())
            ->method('setIndent')
            ->with($indent . '    ');
        $formElement->expects(self::once())
            ->method('render')
            ->with($element)
            ->willReturn($expected);

        $formElementErrors = $this->getMockBuilder(FormElementErrorsInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formElementErrors->expects(self::never())
            ->method('setIndent');
        $formElementErrors->expects(self::never())
            ->method('render');

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

        $renderer = $this->getMockBuilder(RendererInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $renderer->expects(self::never())
            ->method('render');

        $translator = $this->getMockBuilder(Translate::class)
            ->disableOriginalConstructor()
            ->getMock();
        $translator->expects(self::never())
            ->method('__invoke');

        $helper = new FormRow(
            $formElement,
            $formElementErrors,
            $htmlElement,
            $escapeHtml,
            $renderer,
            $translator,
        );

        $helper->setIndent($indent);
        $helper->setRenderErrors($renderErrors);
        $helper->setTranslatorTextDomain($textDomain);

        self::assertSame($expected, $helper->render($element));
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws ServiceNotFoundException
     * @throws InvalidServiceException
     */
    public function testRenderTextWithoutFormOptionAndLabel7(): void
    {
        $label            = '';
        $messages         = ['x' => 'y'];
        $type             = 'text';
        $class            = 'test-class';
        $indent           = '<!-- -->  ';
        $expected         = '<hidden></hidden>';
        $expectedErrors   = '<errors></errors>';
        $renderErrors     = true;
        $required         = true;
        $showRequiredMark = false;
        $layout           = null;
        $helpContent      = null;
        $id               = 'test-id';
        $form             = null;
        $textDomain       = 'text-domain';

        $element = $this->getMockBuilder(ElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $matcher = self::exactly(4);
        $element->expects($matcher)
            ->method('getOption')
            ->willReturnCallback(
                static function (string $option) use ($matcher, $form, $showRequiredMark, $layout, $helpContent): mixed {
                    match ($matcher->numberOfInvocations()) {
                        1 => self::assertSame(
                            'form',
                            $option,
                            (string) $matcher->numberOfInvocations(),
                        ),
                        2 => self::assertSame(
                            'show-required-mark',
                            $option,
                            (string) $matcher->numberOfInvocations(),
                        ),
                        3 => self::assertSame(
                            'layout',
                            $option,
                            (string) $matcher->numberOfInvocations(),
                        ),
                        4 => self::assertSame(
                            'help_content',
                            $option,
                            (string) $matcher->numberOfInvocations(),
                        ),
                        default => self::assertSame(
                            'fieldset',
                            $option,
                            (string) $matcher->numberOfInvocations(),
                        ),
                    };

                    return match ($matcher->numberOfInvocations()) {
                        1 => $form,
                        2 => $showRequiredMark,
                        3 => $layout,
                        4 => $helpContent,
                        default => null,
                    };
                },
            );
        $element->expects(self::once())
            ->method('getName')
            ->willReturn('element-name');
        $matcher = self::exactly(4);
        $element->expects($matcher)
            ->method('hasAttribute')
            ->willReturnCallback(
                static function (string $key) use ($matcher): bool {
                    match ($matcher->numberOfInvocations()) {
                        1 => self::assertSame(
                            'required',
                            $key,
                            (string) $matcher->numberOfInvocations(),
                        ),
                        3 => self::assertSame('id', $key, (string) $matcher->numberOfInvocations()),
                        4 => self::assertSame(
                            'aria-describedby',
                            $key,
                            (string) $matcher->numberOfInvocations(),
                        ),
                        default => self::assertSame(
                            'class',
                            $key,
                            (string) $matcher->numberOfInvocations(),
                        ),
                    };

                    return match ($matcher->numberOfInvocations()) {
                        1, 4 => false,
                        default => true,
                    };
                },
            );
        $element->expects(self::exactly(2))
            ->method('setAttribute')
            ->willReturnMap(
                [
                    ['class', $class . ' is-invalid', null],
                    ['aria-describedby', $id . 'Feedback', null],
                ],
            );
        $element->expects(self::exactly(4))
            ->method('getAttribute')
            ->willReturnMap(
                [
                    ['type', $type],
                    ['class', $class],
                    ['required', $required],
                    ['id', $id],
                ],
            );
        $element->expects(self::once())
            ->method('getLabel')
            ->willReturn($label);
        $element->expects(self::once())
            ->method('getMessages')
            ->willReturn($messages);

        $formElement = $this->getMockBuilder(FormElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formElement->expects(self::once())
            ->method('setIndent')
            ->with($indent . '    ');
        $formElement->expects(self::once())
            ->method('render')
            ->with($element)
            ->willReturn($expected);

        $formElementErrors = $this->getMockBuilder(FormElementErrorsInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formElementErrors->expects(self::once())
            ->method('setIndent')
            ->with($indent . '    ');
        $formElementErrors->expects(self::once())
            ->method('render')
            ->with($element)
            ->willReturn($expectedErrors);

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

        $renderer = $this->getMockBuilder(RendererInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $renderer->expects(self::never())
            ->method('render');

        $translator = $this->getMockBuilder(Translate::class)
            ->disableOriginalConstructor()
            ->getMock();
        $translator->expects(self::never())
            ->method('__invoke');

        $helper = new FormRow(
            $formElement,
            $formElementErrors,
            $htmlElement,
            $escapeHtml,
            $renderer,
            $translator,
        );

        $helper->setIndent($indent);
        $helper->setRenderErrors($renderErrors);
        $helper->setTranslatorTextDomain($textDomain);

        self::assertSame($expected . $expectedErrors, $helper->render($element));
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws ServiceNotFoundException
     * @throws InvalidServiceException
     */
    public function testRenderTextWithoutFormOptionAndLabel8(): void
    {
        $label            = '';
        $messages         = ['x' => 'y'];
        $type             = 'text';
        $class            = 'test-class';
        $indent           = '<!-- -->  ';
        $expected         = '<hidden></hidden>';
        $expectedErrors   = '<errors></errors>';
        $renderErrors     = true;
        $required         = true;
        $showRequiredMark = false;
        $layout           = null;
        $helpContent      = null;
        $form             = null;
        $textDomain       = 'text-domain';

        $element = $this->getMockBuilder(ElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $matcher = self::exactly(4);
        $element->expects($matcher)
            ->method('getOption')
            ->willReturnCallback(
                static function (string $option) use ($matcher, $form, $showRequiredMark, $layout, $helpContent): mixed {
                    match ($matcher->numberOfInvocations()) {
                        1 => self::assertSame(
                            'form',
                            $option,
                            (string) $matcher->numberOfInvocations(),
                        ),
                        2 => self::assertSame(
                            'show-required-mark',
                            $option,
                            (string) $matcher->numberOfInvocations(),
                        ),
                        3 => self::assertSame(
                            'layout',
                            $option,
                            (string) $matcher->numberOfInvocations(),
                        ),
                        4 => self::assertSame(
                            'help_content',
                            $option,
                            (string) $matcher->numberOfInvocations(),
                        ),
                        default => self::assertSame(
                            'fieldset',
                            $option,
                            (string) $matcher->numberOfInvocations(),
                        ),
                    };

                    return match ($matcher->numberOfInvocations()) {
                        1 => $form,
                        2 => $showRequiredMark,
                        3 => $layout,
                        4 => $helpContent,
                        default => null,
                    };
                },
            );
        $element->expects(self::once())
            ->method('getName')
            ->willReturn('element-name');
        $matcher = self::exactly(3);
        $element->expects($matcher)
            ->method('hasAttribute')
            ->willReturnCallback(
                static function (string $key) use ($matcher): bool {
                    match ($matcher->numberOfInvocations()) {
                        1 => self::assertSame(
                            'required',
                            $key,
                            (string) $matcher->numberOfInvocations(),
                        ),
                        3 => self::assertSame('id', $key, (string) $matcher->numberOfInvocations()),
                        default => self::assertSame(
                            'class',
                            $key,
                            (string) $matcher->numberOfInvocations(),
                        ),
                    };

                    return match ($matcher->numberOfInvocations()) {
                        1, 3 => false,
                        default => true,
                    };
                },
            );
        $element->expects(self::once())
            ->method('setAttribute')
            ->with('class', $class . ' is-invalid');
        $matcher = self::exactly(3);
        $element->expects($matcher)
            ->method('getAttribute')
            ->willReturnCallback(
                static function (string $key) use ($matcher, $type, $class, $required): mixed {
                    match ($matcher->numberOfInvocations()) {
                        1 => self::assertSame('type', $key, (string) $matcher->numberOfInvocations()),
                        3 => self::assertSame(
                            'required',
                            $key,
                            (string) $matcher->numberOfInvocations(),
                        ),
                        default => self::assertSame(
                            'class',
                            $key,
                            (string) $matcher->numberOfInvocations(),
                        ),
                    };

                    return match ($matcher->numberOfInvocations()) {
                        1 => $type,
                        3 => $required,
                        default => $class,
                    };
                },
            );
        $element->expects(self::once())
            ->method('getLabel')
            ->willReturn($label);
        $element->expects(self::once())
            ->method('getMessages')
            ->willReturn($messages);

        $formElement = $this->getMockBuilder(FormElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formElement->expects(self::once())
            ->method('setIndent')
            ->with($indent . '    ');
        $formElement->expects(self::once())
            ->method('render')
            ->with($element)
            ->willReturn($expected);

        $formElementErrors = $this->getMockBuilder(FormElementErrorsInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formElementErrors->expects(self::once())
            ->method('setIndent')
            ->with($indent . '    ');
        $formElementErrors->expects(self::once())
            ->method('render')
            ->with($element)
            ->willReturn($expectedErrors);

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

        $renderer = $this->getMockBuilder(RendererInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $renderer->expects(self::never())
            ->method('render');

        $translator = $this->getMockBuilder(Translate::class)
            ->disableOriginalConstructor()
            ->getMock();
        $translator->expects(self::never())
            ->method('__invoke');

        $helper = new FormRow(
            $formElement,
            $formElementErrors,
            $htmlElement,
            $escapeHtml,
            $renderer,
            $translator,
        );

        $helper->setIndent($indent);
        $helper->setRenderErrors($renderErrors);
        $helper->setTranslatorTextDomain($textDomain);

        self::assertSame($expected . $expectedErrors, $helper->render($element));
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws ServiceNotFoundException
     * @throws InvalidServiceException
     */
    public function testRenderTextWithoutFormOptionAndLabel9(): void
    {
        $label            = '';
        $messages         = ['x' => 'y'];
        $type             = 'text';
        $class            = 'test-class';
        $indent           = '<!-- -->  ';
        $expected         = '<hidden></hidden>';
        $expectedErrors   = '<errors></errors>';
        $renderErrors     = true;
        $required         = true;
        $showRequiredMark = false;
        $layout           = null;
        $helpContent      = null;
        $id               = 'test-id';
        $aria             = 'aria-described';
        $form             = null;
        $textDomain       = 'text-domain';

        $element = $this->getMockBuilder(ElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $matcher = self::exactly(4);
        $element->expects($matcher)
            ->method('getOption')
            ->willReturnCallback(
                static function (string $option) use ($matcher, $form, $showRequiredMark, $layout, $helpContent): mixed {
                    match ($matcher->numberOfInvocations()) {
                        1 => self::assertSame(
                            'form',
                            $option,
                            (string) $matcher->numberOfInvocations(),
                        ),
                        2 => self::assertSame(
                            'show-required-mark',
                            $option,
                            (string) $matcher->numberOfInvocations(),
                        ),
                        3 => self::assertSame(
                            'layout',
                            $option,
                            (string) $matcher->numberOfInvocations(),
                        ),
                        4 => self::assertSame(
                            'help_content',
                            $option,
                            (string) $matcher->numberOfInvocations(),
                        ),
                        default => self::assertSame(
                            'fieldset',
                            $option,
                            (string) $matcher->numberOfInvocations(),
                        ),
                    };

                    return match ($matcher->numberOfInvocations()) {
                        1 => $form,
                        2 => $showRequiredMark,
                        3 => $layout,
                        4 => $helpContent,
                        default => null,
                    };
                },
            );
        $element->expects(self::once())
            ->method('getName')
            ->willReturn('element-name');
        $matcher = self::exactly(4);
        $element->expects($matcher)
            ->method('hasAttribute')
            ->willReturnCallback(
                static function (string $key) use ($matcher): bool {
                    match ($matcher->numberOfInvocations()) {
                        1 => self::assertSame(
                            'required',
                            $key,
                            (string) $matcher->numberOfInvocations(),
                        ),
                        3 => self::assertSame('id', $key, (string) $matcher->numberOfInvocations()),
                        4 => self::assertSame(
                            'aria-describedby',
                            $key,
                            (string) $matcher->numberOfInvocations(),
                        ),
                        default => self::assertSame(
                            'class',
                            $key,
                            (string) $matcher->numberOfInvocations(),
                        ),
                    };

                    return match ($matcher->numberOfInvocations()) {
                        1 => false,
                        default => true,
                    };
                },
            );
        $element->expects(self::exactly(2))
            ->method('setAttribute')
            ->willReturnMap(
                [
                    ['class', $class . ' is-invalid', null],
                    ['aria-describedby', $aria . ' ' . $id . 'Feedback', null],
                ],
            );
        $element->expects(self::exactly(5))
            ->method('getAttribute')
            ->willReturnMap(
                [
                    ['type', $type],
                    ['class', $class],
                    ['required', $required],
                    ['aria-describedby', $aria],
                    ['id', $id],
                ],
            );
        $element->expects(self::once())
            ->method('getLabel')
            ->willReturn($label);
        $element->expects(self::once())
            ->method('getMessages')
            ->willReturn($messages);

        $formElement = $this->getMockBuilder(FormElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formElement->expects(self::once())
            ->method('setIndent')
            ->with($indent . '    ');
        $formElement->expects(self::once())
            ->method('render')
            ->with($element)
            ->willReturn($expected);

        $formElementErrors = $this->getMockBuilder(FormElementErrorsInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formElementErrors->expects(self::once())
            ->method('setIndent')
            ->with($indent . '    ');
        $formElementErrors->expects(self::once())
            ->method('render')
            ->with($element)
            ->willReturn($expectedErrors);

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

        $renderer = $this->getMockBuilder(RendererInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $renderer->expects(self::never())
            ->method('render');

        $translator = $this->getMockBuilder(Translate::class)
            ->disableOriginalConstructor()
            ->getMock();
        $translator->expects(self::never())
            ->method('__invoke');

        $helper = new FormRow(
            $formElement,
            $formElementErrors,
            $htmlElement,
            $escapeHtml,
            $renderer,
            $translator,
        );

        $helper->setIndent($indent);
        $helper->setRenderErrors($renderErrors);
        $helper->setTranslatorTextDomain($textDomain);

        self::assertSame($expected . $expectedErrors, $helper->render($element));
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws ServiceNotFoundException
     * @throws InvalidServiceException
     */
    public function testRenderTextWithoutFormOptionAndLabel10(): void
    {
        $label            = '';
        $messages         = ['x' => 'y'];
        $type             = 'text';
        $class            = 'test-class';
        $indent           = '<!-- -->  ';
        $expected         = '<hidden></hidden>';
        $renderErrors     = false;
        $required         = true;
        $showRequiredMark = false;
        $layout           = null;
        $helpContent      = 'help';
        $helpAttributes   = ['a' => 'b'];
        $expectedHelp     = '<help></help>';
        $id               = 'test-id';
        $aria             = 'aria-described';
        $form             = null;
        $textDomain       = 'text-domain';

        $element = $this->getMockBuilder(ElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $matcher = self::exactly(7);
        $element->expects($matcher)
            ->method('getOption')
            ->willReturnCallback(
                static function (string $option) use ($matcher, $form, $showRequiredMark, $layout, $helpContent, $helpAttributes): mixed {
                    match ($matcher->numberOfInvocations()) {
                        1, 7 => self::assertSame(
                            'form',
                            $option,
                            (string) $matcher->numberOfInvocations(),
                        ),
                        2 => self::assertSame(
                            'show-required-mark',
                            $option,
                            (string) $matcher->numberOfInvocations(),
                        ),
                        3 => self::assertSame(
                            'layout',
                            $option,
                            (string) $matcher->numberOfInvocations(),
                        ),
                        4, 5 => self::assertSame(
                            'help_content',
                            $option,
                            (string) $matcher->numberOfInvocations(),
                        ),
                        6 => self::assertSame(
                            'help_attributes',
                            $option,
                            (string) $matcher->numberOfInvocations(),
                        ),
                        default => self::assertSame(
                            'fieldset',
                            $option,
                            (string) $matcher->numberOfInvocations(),
                        ),
                    };

                    return match ($matcher->numberOfInvocations()) {
                        1, 7 => $form,
                        2 => $showRequiredMark,
                        3 => $layout,
                        4, 5 => $helpContent,
                        6 => $helpAttributes,
                        default => null,
                    };
                },
            );
        $element->expects(self::once())
            ->method('getName')
            ->willReturn('element-name');
        $matcher = self::exactly(4);
        $element->expects($matcher)
            ->method('hasAttribute')
            ->willReturnCallback(
                static function (string $key) use ($matcher): bool {
                    match ($matcher->numberOfInvocations()) {
                        1 => self::assertSame(
                            'required',
                            $key,
                            (string) $matcher->numberOfInvocations(),
                        ),
                        3 => self::assertSame('id', $key, (string) $matcher->numberOfInvocations()),
                        4 => self::assertSame(
                            'aria-describedby',
                            $key,
                            (string) $matcher->numberOfInvocations(),
                        ),
                        default => self::assertSame(
                            'class',
                            $key,
                            (string) $matcher->numberOfInvocations(),
                        ),
                    };

                    return match ($matcher->numberOfInvocations()) {
                        1 => false,
                        default => true,
                    };
                },
            );
        $element->expects(self::exactly(2))
            ->method('setAttribute')
            ->willReturnMap(
                [
                    ['class', $class . ' is-invalid', null],
                    ['aria-describedby', $aria . ' ' . $id . 'Help', null],
                ],
            );
        $element->expects(self::exactly(6))
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

        $formElement = $this->getMockBuilder(FormElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formElement->expects(self::once())
            ->method('setIndent')
            ->with($indent . '    ');
        $formElement->expects(self::once())
            ->method('render')
            ->with($element)
            ->willReturn($expected);

        $formElementErrors = $this->getMockBuilder(FormElementErrorsInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formElementErrors->expects(self::never())
            ->method('setIndent');
        $formElementErrors->expects(self::never())
            ->method('render');

        $htmlElement = $this->getMockBuilder(HtmlElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $htmlElement->expects(self::once())
            ->method('toHtml')
            ->with('div', $helpAttributes + ['id' => $id . 'Help'], $helpContent)
            ->willReturn($expectedHelp);

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::never())
            ->method('__invoke');

        $renderer = $this->getMockBuilder(RendererInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $renderer->expects(self::never())
            ->method('render');

        $translator = $this->getMockBuilder(Translate::class)
            ->disableOriginalConstructor()
            ->getMock();
        $translator->expects(self::never())
            ->method('__invoke');

        $helper = new FormRow(
            $formElement,
            $formElementErrors,
            $htmlElement,
            $escapeHtml,
            $renderer,
            $translator,
        );

        $helper->setIndent($indent);
        $helper->setRenderErrors($renderErrors);
        $helper->setTranslatorTextDomain($textDomain);

        self::assertSame(
            $expected . PHP_EOL . $indent . '    ' . $expectedHelp,
            $helper->render($element),
        );
    }
}
