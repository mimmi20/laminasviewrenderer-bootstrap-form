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

use Laminas\Form\Element\Text;
use Laminas\Form\Exception\DomainException;
use Laminas\Form\Exception\InvalidArgumentException;
use Laminas\I18n\Exception\RuntimeException;
use Laminas\I18n\View\Helper\Translate;
use Laminas\View\Helper\EscapeHtml;
use Laminas\View\Renderer\PhpRenderer;
use Mimmi20\LaminasView\BootstrapForm\FormLabel;
use Mimmi20\LaminasView\BootstrapForm\FormLabelInterface;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;

use function assert;
use function get_debug_type;
use function sprintf;

final class FormLabelTest extends TestCase
{
    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
     */
    public function testRenderOpenTagWithNull(): void
    {
        $expected = '<label>';

        $escapeHtml = $this->createMock(EscapeHtml::class);
        $escapeHtml->expects(self::never())
            ->method('__invoke');

        $renderer = $this->createMock(PhpRenderer::class);
        $renderer->expects(self::never())
            ->method('getHelperPluginManager');
        $renderer->expects(self::never())
            ->method('plugin');
        $renderer->expects(self::never())
            ->method('render');

        $helper = new FormLabel();
        $helper->setView($renderer);

        self::assertSame($expected, $helper->openTag());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
     */
    public function testRenderOpenTagWithArray(): void
    {
        $for        = 'test-type';
        $attributes = ['for' => $for];
        $expected   = sprintf('<label for="%s">', $for);

        $escapeHtml = $this->createMock(EscapeHtml::class);
        $escapeHtml->expects(self::never())
            ->method('__invoke');

        $renderer = $this->createMock(PhpRenderer::class);
        $renderer->expects(self::never())
            ->method('getHelperPluginManager');
        $renderer->expects(self::never())
            ->method('plugin');
        $renderer->expects(self::never())
            ->method('render');

        $helper = new FormLabel();
        $helper->setView($renderer);

        self::assertSame($expected, $helper->openTag($attributes));
    }

    /**
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws Exception
     */
    public function testRenderOpenTagWithInt(): void
    {
        $value = 1;

        $escapeHtml = $this->createMock(EscapeHtml::class);
        $escapeHtml->expects(self::never())
            ->method('__invoke');

        $renderer = $this->createMock(PhpRenderer::class);
        $renderer->expects(self::never())
            ->method('getHelperPluginManager');
        $renderer->expects(self::never())
            ->method('plugin');
        $renderer->expects(self::never())
            ->method('render');

        $helper = new FormLabel();
        $helper->setView($renderer);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            sprintf(
                '%s expects an array or Laminas\Form\ElementInterface instance; received "%s"',
                'Mimmi20\LaminasView\BootstrapForm\FormLabel::openTag',
                get_debug_type($value),
            ),
        );
        $this->expectExceptionCode(0);
        $helper->openTag($value);
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
     */
    public function testRenderOpenTagWithElementWithoutNameAndId(): void
    {
        $element = $this->createMock(Text::class);
        $element->expects(self::once())
            ->method('getName')
            ->willReturn(null);
        $element->expects(self::never())
            ->method('getValue');
        $element->expects(self::never())
            ->method('getAttributes');
        $element->expects(self::never())
            ->method('hasAttribute');
        $element->expects(self::once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn(null);
        $element->expects(self::never())
            ->method('getLabel');
        $element->expects(self::never())
            ->method('getLabelOption');
        $element->expects(self::never())
            ->method('getLabelAttributes');

        $escapeHtml = $this->createMock(EscapeHtml::class);
        $escapeHtml->expects(self::never())
            ->method('__invoke');

        $renderer = $this->createMock(PhpRenderer::class);
        $renderer->expects(self::never())
            ->method('getHelperPluginManager');
        $renderer->expects(self::never())
            ->method('plugin');
        $renderer->expects(self::never())
            ->method('render');

        $helper = new FormLabel();
        $helper->setView($renderer);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage(
            sprintf(
                '%s expects the Element provided to have either a name or an id present; neither found',
                'Mimmi20\LaminasView\BootstrapForm\FormLabel::openTag',
            ),
        );
        $this->expectExceptionCode(0);
        $helper->openTag($element);
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
     */
    public function testRenderOpenTagWithElementWithId(): void
    {
        $for      = 'test-type';
        $class    = 'xyz';
        $expected = sprintf('<label for="%s" class="%s">', $for, $class);

        $element = $this->createMock(Text::class);
        $element->expects(self::never())
            ->method('getName');
        $element->expects(self::never())
            ->method('getValue');
        $element->expects(self::never())
            ->method('getAttributes');
        $element->expects(self::never())
            ->method('hasAttribute');
        $element->expects(self::once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn($for);
        $element->expects(self::never())
            ->method('getLabel');
        $element->expects(self::never())
            ->method('getLabelOption');
        $element->expects(self::once())
            ->method('getLabelAttributes')
            ->willReturn(['class' => $class]);

        $escapeHtml = $this->createMock(EscapeHtml::class);
        $escapeHtml->expects(self::never())
            ->method('__invoke');

        $renderer = $this->createMock(PhpRenderer::class);
        $renderer->expects(self::never())
            ->method('getHelperPluginManager');
        $renderer->expects(self::never())
            ->method('plugin');
        $renderer->expects(self::never())
            ->method('render');

        $helper = new FormLabel();
        $helper->setView($renderer);

        self::assertSame($expected, $helper->openTag($element));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
     */
    public function testRenderOpenTagWithElementWithoutId(): void
    {
        $for      = 'test-type';
        $class    = 'xyz';
        $expected = sprintf('<label for="%s" class="%s">', $for, $class);

        $element = $this->createMock(Text::class);
        $element->expects(self::once())
            ->method('getName')
            ->willReturn($for);
        $element->expects(self::never())
            ->method('getValue');
        $element->expects(self::never())
            ->method('getAttributes');
        $element->expects(self::never())
            ->method('hasAttribute');
        $element->expects(self::once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn(null);
        $element->expects(self::never())
            ->method('getLabel');
        $element->expects(self::never())
            ->method('getLabelOption');
        $element->expects(self::once())
            ->method('getLabelAttributes')
            ->willReturn(['class' => $class]);

        $escapeHtml = $this->createMock(EscapeHtml::class);
        $escapeHtml->expects(self::never())
            ->method('__invoke');

        $renderer = $this->createMock(PhpRenderer::class);
        $renderer->expects(self::never())
            ->method('getHelperPluginManager');
        $renderer->expects(self::never())
            ->method('plugin');
        $renderer->expects(self::never())
            ->method('render');

        $helper = new FormLabel();
        $helper->setView($renderer);

        self::assertSame($expected, $helper->openTag($element));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws \Laminas\View\Exception\InvalidArgumentException
     * @throws RuntimeException
     */
    public function testInvokeWithElementWithoutId(): void
    {
        $for      = 'test-type';
        $class    = 'xyz';
        $expected = sprintf('<label for="%s" class="%s">', $for, $class);

        $element = $this->createMock(Text::class);
        $element->expects(self::once())
            ->method('getName')
            ->willReturn($for);
        $element->expects(self::never())
            ->method('getValue');
        $element->expects(self::never())
            ->method('getAttributes');
        $element->expects(self::never())
            ->method('hasAttribute');
        $element->expects(self::once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn(null);
        $element->expects(self::never())
            ->method('getLabel');
        $element->expects(self::never())
            ->method('getLabelOption');
        $element->expects(self::once())
            ->method('getLabelAttributes')
            ->willReturn(['class' => $class]);

        $escapeHtml = $this->createMock(EscapeHtml::class);
        $escapeHtml->expects(self::never())
            ->method('__invoke');

        $renderer = $this->createMock(PhpRenderer::class);
        $renderer->expects(self::never())
            ->method('getHelperPluginManager');
        $renderer->expects(self::never())
            ->method('plugin');
        $renderer->expects(self::never())
            ->method('render');

        $helper = new FormLabel();
        $helper->setView($renderer);

        $helperObject = $helper();

        assert($helperObject instanceof FormLabel);

        self::assertSame($expected, $helperObject->openTag($element));
    }

    /**
     * @throws Exception
     * @throws ContainerExceptionInterface
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws \Laminas\View\Exception\InvalidArgumentException
     * @throws RuntimeException
     */
    public function testInvokeWithoutLabel(): void
    {
        $element = $this->createMock(Text::class);
        $element->expects(self::never())
            ->method('getName');
        $element->expects(self::never())
            ->method('getValue');
        $element->expects(self::never())
            ->method('getAttributes');
        $element->expects(self::never())
            ->method('hasAttribute');
        $element->expects(self::never())
            ->method('getAttribute');
        $element->expects(self::once())
            ->method('getLabel')
            ->willReturn(null);
        $element->expects(self::never())
            ->method('getLabelOption');
        $element->expects(self::never())
            ->method('getLabelAttributes');

        $escapeHtml = $this->createMock(EscapeHtml::class);
        $escapeHtml->expects(self::never())
            ->method('__invoke');

        $renderer = $this->createMock(PhpRenderer::class);
        $renderer->expects(self::never())
            ->method('getHelperPluginManager');
        $renderer->expects(self::never())
            ->method('plugin');
        $renderer->expects(self::never())
            ->method('render');

        $helper = new FormLabel();
        $helper->setView($renderer);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage(
            sprintf(
                '%s expects either label content as the second argument, or that the element provided has a label attribute; neither found',
                'Mimmi20\LaminasView\BootstrapForm\FormLabel::__invoke',
            ),
        );
        $this->expectExceptionCode(0);

        $helper($element);
    }

    /**
     * @throws Exception
     * @throws ContainerExceptionInterface
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws \Laminas\View\Exception\InvalidArgumentException
     * @throws RuntimeException
     */
    public function testInvokeWithoutLabelButWithPosition(): void
    {
        $for          = 'test-type';
        $class        = 'xyz';
        $labelContent = 'test';
        $expected     = sprintf('<label for="%s" class="%s">%s</label>', $for, $class, $labelContent);
        $position     = FormLabelInterface::APPEND;

        $element = $this->createMock(Text::class);
        $element->expects(self::once())
            ->method('getName')
            ->willReturn($for);
        $element->expects(self::never())
            ->method('getValue');
        $element->expects(self::never())
            ->method('getAttributes');
        $element->expects(self::never())
            ->method('hasAttribute');
        $element->expects(self::once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn(null);
        $element->expects(self::once())
            ->method('getLabel')
            ->willReturn(null);
        $element->expects(self::never())
            ->method('getLabelOption');
        $element->expects(self::once())
            ->method('getLabelAttributes')
            ->willReturn(['class' => $class]);

        $escapeHtml = $this->createMock(EscapeHtml::class);
        $escapeHtml->expects(self::never())
            ->method('__invoke');

        $renderer = $this->createMock(PhpRenderer::class);
        $renderer->expects(self::never())
            ->method('getHelperPluginManager');
        $renderer->expects(self::never())
            ->method('plugin');
        $renderer->expects(self::never())
            ->method('render');

        $helper = new FormLabel();
        $helper->setView($renderer);

        self::assertSame($expected, $helper($element, $labelContent, $position));
    }

    /**
     * @throws Exception
     * @throws ContainerExceptionInterface
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws \Laminas\View\Exception\InvalidArgumentException
     * @throws RuntimeException
     */
    public function testInvokeWithLabelAndPosition1(): void
    {
        $for          = 'test-type';
        $class        = 'xyz';
        $labelContent = 'test';
        $label        = 'test-label';
        $escaledLabel = 'test-label-escaped';
        $expected     = sprintf(
            '<label for="%s" class="%s">%s<span>%s</span></label>',
            $for,
            $class,
            $labelContent,
            $escaledLabel,
        );
        $position     = FormLabelInterface::APPEND;

        $element = $this->createMock(Text::class);
        $element->expects(self::once())
            ->method('getName')
            ->willReturn($for);
        $element->expects(self::never())
            ->method('getValue');
        $element->expects(self::never())
            ->method('getAttributes');
        $element->expects(self::once())
            ->method('hasAttribute')
            ->with('id')
            ->willReturn(false);
        $element->expects(self::once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn(null);
        $element->expects(self::once())
            ->method('getLabel')
            ->willReturn($label);
        $element->expects(self::once())
            ->method('getLabelOption')
            ->with('disable_html_escape')
            ->willReturn(false);
        $element->expects(self::once())
            ->method('getLabelAttributes')
            ->willReturn(['class' => $class]);

        $escapeHtml = $this->createMock(EscapeHtml::class);
        $escapeHtml->expects(self::once())
            ->method('__invoke')
            ->with($label)
            ->willReturn($escaledLabel);

        $renderer = $this->createMock(PhpRenderer::class);
        $renderer->expects(self::never())
            ->method('getHelperPluginManager');
        $renderer->expects(self::never())
            ->method('plugin');
        $renderer->expects(self::never())
            ->method('render');

        $helper = new FormLabel();
        $helper->setView($renderer);

        self::assertSame($expected, $helper($element, $labelContent, $position));
    }

    /**
     * @throws Exception
     * @throws ContainerExceptionInterface
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws \Laminas\View\Exception\InvalidArgumentException
     * @throws RuntimeException
     */
    public function testInvokeWithLabelAndPosition2(): void
    {
        $for          = 'test-type';
        $class        = 'xyz';
        $labelContent = 'test';
        $label        = 'test-label';
        $escaledLabel = 'test-label-escaped';
        $expected     = sprintf(
            '<label for="%s" class="%s">%s%s</label>',
            $for,
            $class,
            $escaledLabel,
            $labelContent,
        );
        $position     = FormLabelInterface::PREPEND;

        $element = $this->createMock(Text::class);
        $element->expects(self::never())
            ->method('getName');
        $element->expects(self::never())
            ->method('getValue');
        $element->expects(self::never())
            ->method('getAttributes');
        $element->expects(self::once())
            ->method('hasAttribute')
            ->with('id')
            ->willReturn(true);
        $element->expects(self::once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn($for);
        $element->expects(self::once())
            ->method('getLabel')
            ->willReturn($label);
        $element->expects(self::exactly(2))
            ->method('getLabelOption')
            ->willReturnMap(
                [
                    ['disable_html_escape', false],
                    ['always_wrap', false],
                ],
            );
        $element->expects(self::once())
            ->method('getLabelAttributes')
            ->willReturn(['class' => $class]);

        $escapeHtml = $this->createMock(EscapeHtml::class);
        $escapeHtml->expects(self::once())
            ->method('__invoke')
            ->with($label)
            ->willReturn($escaledLabel);

        $renderer = $this->createMock(PhpRenderer::class);
        $renderer->expects(self::never())
            ->method('getHelperPluginManager');
        $renderer->expects(self::never())
            ->method('plugin');
        $renderer->expects(self::never())
            ->method('render');

        $helper = new FormLabel();
        $helper->setView($renderer);

        self::assertSame($expected, $helper($element, $labelContent, $position));
    }

    /**
     * @throws Exception
     * @throws ContainerExceptionInterface
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws \Laminas\View\Exception\InvalidArgumentException
     * @throws RuntimeException
     */
    public function testInvokeWithLabelAndPosition3(): void
    {
        $for          = 'test-type';
        $class        = 'xyz';
        $labelContent = 'test';
        $label        = 'test-label';
        $escaledLabel = 'test-label-escaped';
        $expected     = sprintf(
            '<label for="%s" class="%s"><span>%s</span>%s</label>',
            $for,
            $class,
            $escaledLabel,
            $labelContent,
        );
        $position     = FormLabelInterface::PREPEND;

        $element = $this->createMock(Text::class);
        $element->expects(self::never())
            ->method('getName');
        $element->expects(self::never())
            ->method('getValue');
        $element->expects(self::never())
            ->method('getAttributes');
        $element->expects(self::once())
            ->method('hasAttribute')
            ->with('id')
            ->willReturn(true);
        $element->expects(self::once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn($for);
        $element->expects(self::once())
            ->method('getLabel')
            ->willReturn($label);
        $element->expects(self::exactly(2))
            ->method('getLabelOption')
            ->willReturnMap(
                [
                    ['disable_html_escape', false],
                    ['always_wrap', true],
                ],
            );
        $element->expects(self::once())
            ->method('getLabelAttributes')
            ->willReturn(['class' => $class]);

        $escapeHtml = $this->createMock(EscapeHtml::class);
        $escapeHtml->expects(self::once())
            ->method('__invoke')
            ->with($label)
            ->willReturn($escaledLabel);

        $renderer = $this->createMock(PhpRenderer::class);
        $renderer->expects(self::never())
            ->method('getHelperPluginManager');
        $renderer->expects(self::never())
            ->method('plugin');
        $renderer->expects(self::never())
            ->method('render');

        $helper = new FormLabel();
        $helper->setView($renderer);

        self::assertSame($expected, $helper($element, $labelContent, $position));
    }

    /**
     * @throws Exception
     * @throws ContainerExceptionInterface
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws \Laminas\View\Exception\InvalidArgumentException
     * @throws RuntimeException
     */
    public function testInvokeWithLabelAndPosition4(): void
    {
        $for          = 'test-type';
        $class        = 'xyz';
        $labelContent = 'test';
        $label        = 'test-label';
        $expected     = sprintf(
            '<label for="%s" class="%s">%s<span>%s</span></label>',
            $for,
            $class,
            $labelContent,
            $label,
        );
        $position     = FormLabelInterface::APPEND;

        $element = $this->createMock(Text::class);
        $element->expects(self::once())
            ->method('getName')
            ->willReturn($for);
        $element->expects(self::never())
            ->method('getValue');
        $element->expects(self::never())
            ->method('getAttributes');
        $element->expects(self::once())
            ->method('hasAttribute')
            ->with('id')
            ->willReturn(false);
        $element->expects(self::once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn(null);
        $element->expects(self::once())
            ->method('getLabel')
            ->willReturn($label);
        $element->expects(self::once())
            ->method('getLabelOption')
            ->with('disable_html_escape')
            ->willReturn(true);
        $element->expects(self::once())
            ->method('getLabelAttributes')
            ->willReturn(['class' => $class]);

        $escapeHtml = $this->createMock(EscapeHtml::class);
        $escapeHtml->expects(self::never())
            ->method('__invoke');

        $renderer = $this->createMock(PhpRenderer::class);
        $renderer->expects(self::never())
            ->method('getHelperPluginManager');
        $renderer->expects(self::never())
            ->method('plugin');
        $renderer->expects(self::never())
            ->method('render');

        $helper = new FormLabel();
        $helper->setView($renderer);

        self::assertSame($expected, $helper($element, $labelContent, $position));
    }

    /**
     * @throws Exception
     * @throws ContainerExceptionInterface
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws \Laminas\View\Exception\InvalidArgumentException
     * @throws RuntimeException
     */
    public function testInvokeWithLabelAndPosition5(): void
    {
        $for          = 'test-type';
        $class        = 'xyz';
        $labelContent = 'test';
        $label        = 'test-label';
        $expected     = sprintf(
            '<label for="%s" class="%s"><span>%s</span>%s</label>',
            $for,
            $class,
            $label,
            $labelContent,
        );
        $position     = FormLabelInterface::PREPEND;

        $element = $this->createMock(Text::class);
        $element->expects(self::never())
            ->method('getName');
        $element->expects(self::never())
            ->method('getValue');
        $element->expects(self::never())
            ->method('getAttributes');
        $element->expects(self::once())
            ->method('hasAttribute')
            ->with('id')
            ->willReturn(true);
        $element->expects(self::once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn($for);
        $element->expects(self::once())
            ->method('getLabel')
            ->willReturn($label);
        $element->expects(self::exactly(2))
            ->method('getLabelOption')
            ->willReturnMap(
                [
                    ['disable_html_escape', true],
                    ['always_wrap', true],
                ],
            );
        $element->expects(self::once())
            ->method('getLabelAttributes')
            ->willReturn(['class' => $class]);

        $escapeHtml = $this->createMock(EscapeHtml::class);
        $escapeHtml->expects(self::never())
            ->method('__invoke');

        $renderer = $this->createMock(PhpRenderer::class);
        $renderer->expects(self::never())
            ->method('getHelperPluginManager');
        $renderer->expects(self::never())
            ->method('plugin');
        $renderer->expects(self::never())
            ->method('render');

        $helper = new FormLabel();
        $helper->setView($renderer);

        self::assertSame($expected, $helper($element, $labelContent, $position));
    }

    /**
     * @throws Exception
     * @throws ContainerExceptionInterface
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws \Laminas\View\Exception\InvalidArgumentException
     * @throws RuntimeException
     */
    public function testInvokeWithLabelAndPositionAndTranslator1(): void
    {
        $for                   = 'test-type';
        $class                 = 'xyz';
        $labelContent          = 'test';
        $label                 = 'test-label';
        $textDomain            = 'text-domain';
        $tranlatedLabel        = 'test-label-translated';
        $escapedTranlatedLabel = 'test-label-translated-escaped';
        $expected              = sprintf(
            '<label for="%s" class="%s">%s%s</label>',
            $for,
            $class,
            $escapedTranlatedLabel,
            $labelContent,
        );
        $position              = FormLabelInterface::PREPEND;

        $element = $this->createMock(Text::class);
        $element->expects(self::never())
            ->method('getName');
        $element->expects(self::never())
            ->method('getValue');
        $element->expects(self::never())
            ->method('getAttributes');
        $element->expects(self::once())
            ->method('hasAttribute')
            ->with('id')
            ->willReturn(true);
        $element->expects(self::once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn($for);
        $element->expects(self::once())
            ->method('getLabel')
            ->willReturn($label);
        $element->expects(self::exactly(2))
            ->method('getLabelOption')
            ->willReturnMap(
                [
                    ['disable_html_escape', false],
                    ['always_wrap', false],
                ],
            );
        $element->expects(self::once())
            ->method('getLabelAttributes')
            ->willReturn(['class' => $class]);

        $escapeHtml = $this->createMock(EscapeHtml::class);
        $escapeHtml->expects(self::once())
            ->method('__invoke')
            ->with($tranlatedLabel)
            ->willReturn($escapedTranlatedLabel);

        $translator = $this->createMock(Translate::class);
        $translator->expects(self::once())
            ->method('__invoke')
            ->with($label, $textDomain)
            ->willReturn($tranlatedLabel);

        $renderer = $this->createMock(PhpRenderer::class);
        $renderer->expects(self::never())
            ->method('getHelperPluginManager');
        $renderer->expects(self::never())
            ->method('plugin');
        $renderer->expects(self::never())
            ->method('render');

        $helper = new FormLabel();
        $helper->setView($renderer);

        $helper->setTranslatorTextDomain($textDomain);

        self::assertSame($expected, $helper($element, $labelContent, $position));
    }

    /**
     * @throws Exception
     * @throws ContainerExceptionInterface
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws \Laminas\View\Exception\InvalidArgumentException
     * @throws RuntimeException
     */
    public function testInvokeWithLabelAndPositionAndTranslator2(): void
    {
        $for                   = 'test-type';
        $class                 = 'xyz';
        $label                 = 'test-label';
        $textDomain            = 'text-domain';
        $tranlatedLabel        = 'test-label-translated';
        $escapedTranlatedLabel = 'test-label-translated-escaped';
        $expected              = sprintf(
            '<label for="%s" class="%s">%s</label>',
            $for,
            $class,
            $escapedTranlatedLabel,
        );
        $position              = FormLabelInterface::PREPEND;

        $element = $this->createMock(Text::class);
        $element->expects(self::never())
            ->method('getName');
        $element->expects(self::never())
            ->method('getValue');
        $element->expects(self::never())
            ->method('getAttributes');
        $element->expects(self::once())
            ->method('hasAttribute')
            ->with('id')
            ->willReturn(true);
        $element->expects(self::once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn($for);
        $element->expects(self::once())
            ->method('getLabel')
            ->willReturn($label);
        $element->expects(self::exactly(2))
            ->method('getLabelOption')
            ->willReturnMap(
                [
                    ['disable_html_escape', false],
                    ['always_wrap', false],
                ],
            );
        $element->expects(self::once())
            ->method('getLabelAttributes')
            ->willReturn(['class' => $class]);

        $escapeHtml = $this->createMock(EscapeHtml::class);
        $escapeHtml->expects(self::once())
            ->method('__invoke')
            ->with($tranlatedLabel)
            ->willReturn($escapedTranlatedLabel);

        $translator = $this->createMock(Translate::class);
        $translator->expects(self::once())
            ->method('__invoke')
            ->with($label, $textDomain)
            ->willReturn($tranlatedLabel);

        $renderer = $this->createMock(PhpRenderer::class);
        $renderer->expects(self::never())
            ->method('getHelperPluginManager');
        $renderer->expects(self::never())
            ->method('plugin');
        $renderer->expects(self::never())
            ->method('render');

        $helper = new FormLabel();
        $helper->setView($renderer);

        $helper->setTranslatorTextDomain($textDomain);

        self::assertSame($expected, $helper($element, null, $position));
    }
}
