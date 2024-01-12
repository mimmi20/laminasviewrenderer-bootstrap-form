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

use Laminas\Form\ElementInterface;
use Laminas\Form\Exception\DomainException;
use Laminas\Form\View\Helper\FormElement;
use Laminas\View\Exception\InvalidArgumentException;
use Laminas\View\Helper\HelperInterface;
use Laminas\View\Renderer\PhpRenderer;
use Mimmi20\LaminasView\BootstrapForm\FormElementErrorsInterface;
use Mimmi20\LaminasView\BootstrapForm\FormElementInterface;
use Mimmi20\LaminasView\BootstrapForm\FormHtmlInterface;
use Mimmi20\LaminasView\BootstrapForm\FormRow;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;

use const PHP_EOL;

#[Group('form-row')]
final class FormRow2Test extends TestCase
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
     * @throws InvalidArgumentException
     * @throws \Laminas\Form\Exception\InvalidArgumentException
     */
    public function testRenderPartialWithLabelAndTranslatorWithoutFormOption(): void
    {
        $label           = 'test-label';
        $messages        = [];
        $type            = 'hidden';
        $indent          = '<!-- -->  ';
        $expectedElement = '<hidden></hidden>';
        $partial         = 'test-partial';
        $renderErrors    = false;
        $textDomain      = 'text-domain';

        $element = $this->createMock(ElementInterface::class);
        $element->expects(self::once())
            ->method('getOption')
            ->with('form')
            ->willReturn(null);
        $element->expects(self::once())
            ->method('getName')
            ->willReturn('element-name');
        $element->expects(self::once())
            ->method('hasAttribute')
            ->with('required')
            ->willReturn(false);
        $element->expects(self::never())
            ->method('setAttribute');
        $element->expects(self::once())
            ->method('getAttribute')
            ->with('type')
            ->willReturn($type);
        $element->expects(self::once())
            ->method('getLabel')
            ->willReturn($label);
        $element->expects(self::once())
            ->method('getMessages')
            ->willReturn($messages);

        $formElement = $this->createMock(FormElementInterface::class);
        $formElement->expects(self::never())
            ->method('setIndent');
        $formElement->expects(self::never())
            ->method('render');

        $formElementErrors = $this->createMock(FormElementErrorsInterface::class);
        $formElementErrors->expects(self::never())
            ->method('setIndent');
        $formElementErrors->expects(self::never())
            ->method('render');

        $renderer = $this->createMock(PhpRenderer::class);
        $renderer->expects(self::never())
            ->method('getHelperPluginManager');
        $renderer->expects(self::never())
            ->method('plugin');
        $renderer->expects(self::once())
            ->method('render')
            ->with(
                $partial,
                [
                    'element' => $element,
                    'label' => $label,
                    'labelAttributes' => [],
                    'labelPosition' => \Laminas\Form\View\Helper\FormRow::LABEL_PREPEND,
                    'renderErrors' => $renderErrors,
                    'indent' => $indent,
                ],
            )
            ->willReturn($expectedElement);

        $this->helper->setView($renderer);
        $this->helper->setIndent($indent);
        $this->helper->setRenderErrors($renderErrors);
        $this->helper->setPartial($partial);
        $this->helper->setTranslatorTextDomain($textDomain);

        self::assertSame($expectedElement, $this->helper->render($element));
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws InvalidArgumentException
     * @throws \Laminas\Form\Exception\InvalidArgumentException
     */
    public function testRenderPartialWithLabelAndTranslatorWithoutFormOption2(): void
    {
        $label           = 'test-label';
        $messages        = ['x' => 'y'];
        $type            = 'hidden';
        $indent          = '<!-- -->  ';
        $expectedElement = '<hidden></hidden>';
        $partial         = 'test-partial';
        $renderErrors    = false;
        $textDomain      = 'text-domain';

        $element = $this->createMock(ElementInterface::class);
        $matcher = self::exactly(2);
        $element->expects($matcher)
            ->method('getOption')
            ->willReturnCallback(
                static function (string $option) use ($matcher): mixed {
                    $invocation = $matcher->numberOfInvocations();

                    match ($invocation) {
                        1 => self::assertSame(
                            'form',
                            $option,
                            (string) $invocation,
                        ),
                        default => self::assertSame(
                            'error-class',
                            $option,
                            (string) $invocation,
                        ),
                    };

                    return null;
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
                    $invocation = $matcher->numberOfInvocations();

                    match ($invocation) {
                        1 => self::assertSame(
                            'required',
                            $key,
                            (string) $invocation,
                        ),
                        default => self::assertSame(
                            'class',
                            $key,
                            (string) $invocation,
                        ),
                    };

                    return false;
                },
            );
        $element->expects(self::once())
            ->method('setAttribute')
            ->with('class', 'is-invalid');
        $element->expects(self::once())
            ->method('getAttribute')
            ->with('type')
            ->willReturn($type);
        $element->expects(self::once())
            ->method('getLabel')
            ->willReturn($label);
        $element->expects(self::once())
            ->method('getMessages')
            ->willReturn($messages);

        $formElement = $this->createMock(FormElementInterface::class);
        $formElement->expects(self::never())
            ->method('setIndent');
        $formElement->expects(self::never())
            ->method('render');

        $formElementErrors = $this->createMock(FormElementErrorsInterface::class);
        $formElementErrors->expects(self::never())
            ->method('setIndent');
        $formElementErrors->expects(self::never())
            ->method('render');

        $renderer = $this->createMock(PhpRenderer::class);
        $renderer->expects(self::never())
            ->method('getHelperPluginManager');
        $renderer->expects(self::never())
            ->method('plugin');
        $renderer->expects(self::once())
            ->method('render')
            ->with(
                $partial,
                [
                    'element' => $element,
                    'label' => $label,
                    'labelAttributes' => [],
                    'labelPosition' => \Laminas\Form\View\Helper\FormRow::LABEL_PREPEND,
                    'renderErrors' => $renderErrors,
                    'indent' => $indent,
                ],
            )
            ->willReturn($expectedElement);

        $this->helper->setView($renderer);
        $this->helper->setIndent($indent);
        $this->helper->setRenderErrors($renderErrors);
        $this->helper->setPartial($partial);
        $this->helper->setTranslatorTextDomain($textDomain);

        self::assertSame($expectedElement, $this->helper->render($element));
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws InvalidArgumentException
     * @throws \Laminas\Form\Exception\InvalidArgumentException
     */
    public function testRenderPartialWithLabelAndTranslatorWithoutFormOption3(): void
    {
        $label           = 'test-label';
        $messages        = ['x' => 'y'];
        $type            = 'hidden';
        $class           = 'test-class';
        $indent          = '<!-- -->  ';
        $expectedElement = '<hidden></hidden>';
        $partial         = 'test-partial';
        $renderErrors    = false;
        $textDomain      = 'text-domain';

        $element = $this->createMock(ElementInterface::class);
        $matcher = self::exactly(2);
        $element->expects($matcher)
            ->method('getOption')
            ->willReturnCallback(
                static function (string $option) use ($matcher): mixed {
                    $invocation = $matcher->numberOfInvocations();

                    match ($invocation) {
                        1 => self::assertSame(
                            'form',
                            $option,
                            (string) $invocation,
                        ),
                        default => self::assertSame(
                            'error-class',
                            $option,
                            (string) $invocation,
                        ),
                    };

                    return null;
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
                    $invocation = $matcher->numberOfInvocations();

                    match ($invocation) {
                        1 => self::assertSame(
                            'required',
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
        $element->expects(self::once())
            ->method('setAttribute')
            ->with('class', $class . ' is-invalid');
        $element->expects(self::exactly(2))
            ->method('getAttribute')
            ->willReturnMap(
                [
                    ['type', $type],
                    ['class', $class],
                ],
            );
        $element->expects(self::once())
            ->method('getLabel')
            ->willReturn($label);
        $element->expects(self::once())
            ->method('getMessages')
            ->willReturn($messages);

        $formElement = $this->createMock(FormElementInterface::class);
        $formElement->expects(self::never())
            ->method('setIndent');
        $formElement->expects(self::never())
            ->method('render');

        $formElementErrors = $this->createMock(FormElementErrorsInterface::class);
        $formElementErrors->expects(self::never())
            ->method('setIndent');
        $formElementErrors->expects(self::never())
            ->method('render');

        $renderer = $this->createMock(PhpRenderer::class);
        $renderer->expects(self::never())
            ->method('getHelperPluginManager');
        $renderer->expects(self::never())
            ->method('plugin');
        $renderer->expects(self::once())
            ->method('render')
            ->with(
                $partial,
                [
                    'element' => $element,
                    'label' => $label,
                    'labelAttributes' => [],
                    'labelPosition' => \Laminas\Form\View\Helper\FormRow::LABEL_PREPEND,
                    'renderErrors' => $renderErrors,
                    'indent' => $indent,
                ],
            )
            ->willReturn($expectedElement);

        $this->helper->setView($renderer);
        $this->helper->setIndent($indent);
        $this->helper->setRenderErrors($renderErrors);
        $this->helper->setPartial($partial);
        $this->helper->setTranslatorTextDomain($textDomain);

        self::assertSame($expectedElement, $this->helper->render($element));
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws InvalidArgumentException
     * @throws \Laminas\Form\Exception\InvalidArgumentException
     */
    public function testRenderTextWithoutFormOptionAndLabel(): void
    {
        $label            = '';
        $messages         = [];
        $type             = 'text';
        $indent           = '<!-- -->  ';
        $expectedElement  = '<text></text>';
        $expectedRow      = '<div></div>';
        $renderErrors     = false;
        $required         = true;
        $showRequiredMark = false;
        $layout           = null;
        $helpContent      = null;
        $form             = null;
        $textDomain       = 'text-domain';

        $element = $this->createMock(ElementInterface::class);
        $matcher = self::exactly(10);
        $element->expects($matcher)
            ->method('getOption')
            ->willReturnCallback(
                static function (string $option) use ($matcher, $form, $showRequiredMark, $layout, $helpContent): bool | null {
                    $invocation = $matcher->numberOfInvocations();

                    match ($invocation) {
                        1, 5, 7 => self::assertSame(
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
                        9 => self::assertSame(
                            'messages',
                            $option,
                            (string) $invocation,
                        ),
                        10 => self::assertSame(
                            'help_content',
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
                        1, 5, 7 => $form,
                        2 => $showRequiredMark,
                        3 => $layout,
                        8 => false,
                        9 => $helpContent,
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
                    $invocation = $matcher->numberOfInvocations();

                    match ($invocation) {
                        1 => self::assertSame(
                            'required',
                            $key,
                            (string) $invocation,
                        ),
                        default => self::assertSame(
                            'id',
                            $key,
                            (string) $invocation,
                        ),
                    };

                    return false;
                },
            );
        $element->expects(self::never())
            ->method('setAttribute');
        $element->expects(self::exactly(3))
            ->method('getAttribute')
            ->willReturnMap(
                [
                    ['type', $type],
                    ['required', $required],
                ],
            );
        $element->expects(self::once())
            ->method('getLabel')
            ->willReturn($label);
        $element->expects(self::once())
            ->method('getMessages')
            ->willReturn($messages);

        $formHtml = $this->createMock(FormHtmlInterface::class);
        $formHtml->expects(self::never())
            ->method('setIndent');
        $formHtml->expects(self::once())
            ->method('render')
            ->with('div', [], PHP_EOL . $expectedElement . PHP_EOL . $indent)
            ->willReturn($expectedRow);

        $formElement = $this->createMock(FormElement::class);
        $formElement->expects(self::once())
            ->method('render')
            ->with($element)
            ->willReturn($expectedElement);
        $formElement->expects(self::never())
            ->method('__invoke');

        $renderer = $this->createMock(PhpRenderer::class);
        $renderer->expects(self::never())
            ->method('getHelperPluginManager');
        $matcher = self::exactly(2);
        $renderer->expects($matcher)
            ->method('plugin')
            ->willReturnCallback(
                static function (string $name, array | null $options = null) use ($matcher, $formHtml, $formElement): HelperInterface | null {
                    $invocation = $matcher->numberOfInvocations();

                    match ($invocation) {
                        1 => self::assertSame(
                            'form_html',
                            $name,
                            (string) $invocation,
                        ),
                        2 => self::assertSame(
                            'form_element',
                            $name,
                            (string) $invocation,
                        ),
                        default => self::assertSame(
                            'class',
                            $name,
                            (string) $invocation,
                        ),
                    };

                    self::assertNull($options);

                    return match ($invocation) {
                        1 => $formHtml,
                        2 => $formElement,
                        default => null,
                    };
                },
            );
        $renderer->expects(self::never())
            ->method('render');

        $this->helper->setView($renderer);
        $this->helper->setIndent($indent);
        $this->helper->setRenderErrors($renderErrors);
        $this->helper->setTranslatorTextDomain($textDomain);

        self::assertSame($indent . $expectedRow, $this->helper->render($element));
    }
}
