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

use AssertionError;
use Laminas\Form\ElementInterface;
use Laminas\Form\Exception\DomainException;
use Laminas\View\Exception\InvalidArgumentException;
use Laminas\View\Renderer\PhpRenderer;
use Mimmi20\LaminasView\BootstrapForm\FormHiddenInterface;
use Mimmi20\LaminasView\BootstrapForm\FormRow;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;

#[Group('form-row')]
final class FormRow1Test extends TestCase
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
    public function testRenderWithWrongFormOption(): void
    {
        $element = $this->createMock(ElementInterface::class);
        $element->expects(self::once())
            ->method('getOption')
            ->with('form')
            ->willReturn(true);
        $element->expects(self::once())
            ->method('getName')
            ->willReturn('element-name');
        $element->expects(self::once())
            ->method('hasAttribute')
            ->with('required')
            ->willReturn(false);
        $element->expects(self::never())
            ->method('setAttribute');
        $element->expects(self::never())
            ->method('getAttribute');
        $element->expects(self::never())
            ->method('getLabel');
        $element->expects(self::never())
            ->method('getMessages');

        $this->expectException(AssertionError::class);
        $this->expectExceptionCode(1);
        $this->expectExceptionMessage(
            '$form should be an Instance of Laminas\Form\FormInterface or null, but was bool',
        );

        $this->helper->render($element);
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws InvalidArgumentException
     * @throws \Laminas\Form\Exception\InvalidArgumentException
     */
    public function testRenderHiddenWithoutFormOptionAndLabel(): void
    {
        $label        = '';
        $messages     = [];
        $type         = 'hidden';
        $indent       = '<!-- -->  ';
        $expected     = '<hidden></hidden>';
        $renderErrors = false;

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

        $formHidden = $this->createMock(FormHiddenInterface::class);
        $formHidden->expects(self::once())
            ->method('setIndent')
            ->with($indent);
        $formHidden->expects(self::once())
            ->method('render')
            ->with($element)
            ->willReturn($expected);

        $renderer = $this->createMock(PhpRenderer::class);
        $renderer->expects(self::never())
            ->method('getHelperPluginManager');
        $renderer->expects(self::once())
            ->method('plugin')
            ->with('form_hidden', null)
            ->willReturn($formHidden);
        $renderer->expects(self::never())
            ->method('render');

        $this->helper->setView($renderer);
        $this->helper->setIndent($indent);
        $this->helper->setRenderErrors($renderErrors);

        self::assertSame($expected, $this->helper->render($element));
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws InvalidArgumentException
     * @throws \Laminas\Form\Exception\InvalidArgumentException
     */
    public function testRenderPartialWithoutFormOptionAndLabel(): void
    {
        $label        = '';
        $messages     = [];
        $type         = 'hidden';
        $indent       = '<!-- -->  ';
        $expected     = '<hidden></hidden>';
        $partial      = 'test-partial';
        $renderErrors = false;

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
            ->willReturn($expected);

        $this->helper->setView($renderer);
        $this->helper->setIndent($indent);
        $this->helper->setRenderErrors($renderErrors);
        $this->helper->setPartial($partial);

        self::assertSame($expected, $this->helper->render($element));
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws InvalidArgumentException
     * @throws \Laminas\Form\Exception\InvalidArgumentException
     */
    public function testRenderPartialWithoutFormOptionAndLabel2(): void
    {
        $label        = '';
        $messages     = ['x' => 'y'];
        $type         = 'hidden';
        $indent       = '<!-- -->  ';
        $expected     = '<hidden></hidden>';
        $partial      = 'test-partial';
        $renderErrors = false;

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
            ->willReturn($expected);

        $this->helper->setView($renderer);
        $this->helper->setIndent($indent);
        $this->helper->setRenderErrors($renderErrors);
        $this->helper->setPartial($partial);

        self::assertSame($expected, $this->helper->render($element));
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws InvalidArgumentException
     * @throws \Laminas\Form\Exception\InvalidArgumentException
     */
    public function testRenderPartialWithoutFormOptionAndLabel3(): void
    {
        $label        = '';
        $messages     = ['x' => 'y'];
        $type         = 'hidden';
        $class        = 'test-class';
        $indent       = '<!-- -->  ';
        $expected     = '<hidden></hidden>';
        $partial      = 'test-partial';
        $renderErrors = false;

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
            ->willReturn($expected);

        $this->helper->setView($renderer);
        $this->helper->setIndent($indent);
        $this->helper->setRenderErrors($renderErrors);
        $this->helper->setPartial($partial);

        self::assertSame($expected, $this->helper->render($element));
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws InvalidArgumentException
     * @throws \Laminas\Form\Exception\InvalidArgumentException
     */
    public function testRenderHiddenWithLabelWithoutFormOption(): void
    {
        $label        = 'test-label';
        $messages     = [];
        $type         = 'hidden';
        $indent       = '<!-- -->  ';
        $expected     = '<hidden></hidden>';
        $renderErrors = false;

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

        $formHidden = $this->createMock(FormHiddenInterface::class);
        $formHidden->expects(self::once())
            ->method('setIndent')
            ->with($indent);
        $formHidden->expects(self::once())
            ->method('render')
            ->with($element)
            ->willReturn($expected);

        $renderer = $this->createMock(PhpRenderer::class);
        $renderer->expects(self::never())
            ->method('getHelperPluginManager');
        $renderer->expects(self::once())
            ->method('plugin')
            ->with('form_hidden', null)
            ->willReturn($formHidden);
        $renderer->expects(self::never())
            ->method('render');

        $this->helper->setView($renderer);
        $this->helper->setIndent($indent);
        $this->helper->setRenderErrors($renderErrors);

        self::assertSame($expected, $this->helper->render($element));
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws InvalidArgumentException
     * @throws \Laminas\Form\Exception\InvalidArgumentException
     */
    public function testRenderPartialWithLabelWithoutFormOption(): void
    {
        $label        = 'test-label';
        $messages     = [];
        $type         = 'hidden';
        $indent       = '<!-- -->  ';
        $expected     = '<hidden></hidden>';
        $partial      = 'test-partial';
        $renderErrors = false;

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
            ->willReturn($expected);

        $this->helper->setView($renderer);
        $this->helper->setIndent($indent);
        $this->helper->setRenderErrors($renderErrors);
        $this->helper->setPartial($partial);

        self::assertSame($expected, $this->helper->render($element));
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws InvalidArgumentException
     * @throws \Laminas\Form\Exception\InvalidArgumentException
     */
    public function testRenderPartialWithLabelWithoutFormOption2(): void
    {
        $label        = 'test-label';
        $messages     = ['x' => 'y'];
        $type         = 'hidden';
        $indent       = '<!-- -->  ';
        $expected     = '<hidden></hidden>';
        $partial      = 'test-partial';
        $renderErrors = false;

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
            ->willReturn($expected);

        $this->helper->setView($renderer);
        $this->helper->setIndent($indent);
        $this->helper->setRenderErrors($renderErrors);
        $this->helper->setPartial($partial);

        self::assertSame($expected, $this->helper->render($element));
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws InvalidArgumentException
     * @throws \Laminas\Form\Exception\InvalidArgumentException
     */
    public function testRenderPartialWithLabelWithoutFormOption3(): void
    {
        $label        = 'test-label';
        $messages     = ['x' => 'y'];
        $type         = 'hidden';
        $class        = 'test-class';
        $indent       = '<!-- -->  ';
        $expected     = '<hidden></hidden>';
        $partial      = 'test-partial';
        $renderErrors = false;

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
            ->willReturn($expected);

        $this->helper->setView($renderer);
        $this->helper->setIndent($indent);
        $this->helper->setRenderErrors($renderErrors);
        $this->helper->setPartial($partial);

        self::assertSame($expected, $this->helper->render($element));
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws InvalidArgumentException
     * @throws \Laminas\Form\Exception\InvalidArgumentException
     */
    public function testRenderHiddenWithLabelAndTranslatorWithoutFormOption(): void
    {
        $label        = 'test-label';
        $messages     = [];
        $type         = 'hidden';
        $indent       = '<!-- -->  ';
        $expected     = '<hidden></hidden>';
        $renderErrors = false;
        $textDomain   = 'text-domain';

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

        $formHidden = $this->createMock(FormHiddenInterface::class);
        $formHidden->expects(self::once())
            ->method('setIndent')
            ->with($indent);
        $formHidden->expects(self::once())
            ->method('render')
            ->with($element)
            ->willReturn($expected);

        $renderer = $this->createMock(PhpRenderer::class);
        $renderer->expects(self::never())
            ->method('getHelperPluginManager');
        $renderer->expects(self::once())
            ->method('plugin')
            ->with('form_hidden', null)
            ->willReturn($formHidden);
        $renderer->expects(self::never())
            ->method('render');

        $this->helper->setView($renderer);
        $this->helper->setIndent($indent);
        $this->helper->setRenderErrors($renderErrors);
        $this->helper->setTranslatorTextDomain($textDomain);

        self::assertSame($expected, $this->helper->render($element));
    }
}
