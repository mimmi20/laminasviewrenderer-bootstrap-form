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
use Laminas\Form\Element\Collection;
use Laminas\Form\Element\Text;
use Laminas\Form\ElementInterface;
use Laminas\Form\Exception\DomainException;
use Laminas\Form\Exception\InvalidArgumentException;
use Laminas\I18n\View\Helper\Translate;
use Laminas\ServiceManager\Exception\InvalidServiceException;
use Laminas\ServiceManager\Exception\ServiceNotFoundException;
use Laminas\Stdlib\PriorityList;
use Laminas\View\Exception\RuntimeException;
use Laminas\View\Helper\EscapeHtml;
use Laminas\View\Renderer\PhpRenderer;
use Mimmi20\LaminasView\BootstrapForm\Form;
use Mimmi20\LaminasView\BootstrapForm\FormCollection;
use Mimmi20\LaminasView\BootstrapForm\FormRowInterface;
use Mimmi20\LaminasView\Helper\HtmlElement\Helper\HtmlElementInterface;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;

use const PHP_EOL;

final class FormCollection6Test extends TestCase
{
    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws InvalidServiceException
     * @throws ServiceNotFoundException
     * @throws \Laminas\View\Exception\InvalidArgumentException
     * @throws RuntimeException
     * @throws \Laminas\I18n\Exception\RuntimeException
     */
    public function testRenderWithCollectionAndElementsAndOptionsAndTranslator3(): void
    {
        $form       = 'test-form';
        $layout     = Form::LAYOUT_HORIZONTAL;
        $floating   = true;
        $indent     = '<!-- -->  ';
        $textDomain = 'test-domain';

        $element = $this->createMock(Collection::class);

        $textElement = $this->createMock(Text::class);
        $matcher     = self::exactly(2);
        $textElement->expects($matcher)
            ->method('getOption')
            ->willReturnCallback(
                static function (string $option) use ($matcher, $form, $layout): mixed {
                    match ($matcher->numberOfInvocations()) {
                        1 => self::assertSame(
                            'form',
                            $option,
                            (string) $matcher->numberOfInvocations(),
                        ),
                        default => self::assertSame(
                            'layout',
                            $option,
                            (string) $matcher->numberOfInvocations(),
                        ),
                    };

                    return match ($matcher->numberOfInvocations()) {
                        1 => $form,
                        default => $layout,
                    };
                },
            );
        $matcher = self::exactly(2);
        $textElement->expects($matcher)
            ->method('setOption')
            ->willReturnCallback(
                static function (string $key, mixed $value) use ($matcher, $element): void {
                    match ($matcher->numberOfInvocations()) {
                        1 => self::assertSame(
                            'floating',
                            $key,
                            (string) $matcher->numberOfInvocations(),
                        ),
                        default => self::assertSame(
                            'fieldset',
                            $key,
                            (string) $matcher->numberOfInvocations(),
                        ),
                    };

                    match ($matcher->numberOfInvocations()) {
                        1 => self::assertTrue($value, (string) $matcher->numberOfInvocations()),
                        default => self::assertSame(
                            $element,
                            $value,
                            (string) $matcher->numberOfInvocations(),
                        ),
                    };
                },
            );

        $buttonElement = $this->createMock(Button::class);
        $matcher       = self::exactly(2);
        $buttonElement->expects($matcher)
            ->method('getOption')
            ->willReturnCallback(
                static function (string $option) use ($matcher, $form, $layout): mixed {
                    match ($matcher->numberOfInvocations()) {
                        1 => self::assertSame(
                            'form',
                            $option,
                            (string) $matcher->numberOfInvocations(),
                        ),
                        default => self::assertSame(
                            'layout',
                            $option,
                            (string) $matcher->numberOfInvocations(),
                        ),
                    };

                    return match ($matcher->numberOfInvocations()) {
                        1 => $form,
                        default => $layout,
                    };
                },
            );
        $matcher = self::exactly(2);
        $buttonElement->expects($matcher)
            ->method('setOption')
            ->willReturnCallback(
                static function (string $key, mixed $value) use ($matcher, $element): void {
                    match ($matcher->numberOfInvocations()) {
                        1 => self::assertSame(
                            'floating',
                            $key,
                            (string) $matcher->numberOfInvocations(),
                        ),
                        default => self::assertSame(
                            'fieldset',
                            $key,
                            (string) $matcher->numberOfInvocations(),
                        ),
                    };

                    match ($matcher->numberOfInvocations()) {
                        1 => self::assertTrue($value, (string) $matcher->numberOfInvocations()),
                        default => self::assertSame(
                            $element,
                            $value,
                            (string) $matcher->numberOfInvocations(),
                        ),
                    };
                },
            );

        $expectedButton = $indent . '    <button></button>';
        $expectedText   = $indent . '    <text></text>';
        $expected       = PHP_EOL . $expectedButton . PHP_EOL . $expectedText . PHP_EOL;

        $formRow = $this->createMock(FormRowInterface::class);
        $formRow->expects(self::exactly(2))
            ->method('setIndent')
            ->with($indent . '    ');
        $matcher = self::exactly(2);
        $formRow->expects($matcher)
            ->method('render')
            ->willReturnCallback(
                static function (ElementInterface $element, string | null $labelPosition = null) use ($matcher, $buttonElement, $textElement, $expectedButton, $expectedText): string {
                    match ($matcher->numberOfInvocations()) {
                        1 => self::assertSame(
                            $buttonElement,
                            $element,
                            (string) $matcher->numberOfInvocations(),
                        ),
                        default => self::assertSame(
                            $textElement,
                            $element,
                            (string) $matcher->numberOfInvocations(),
                        ),
                    };

                    self::assertNull($labelPosition, (string) $matcher->numberOfInvocations());

                    return match ($matcher->numberOfInvocations()) {
                        1 => $expectedButton,
                        default => $expectedText,
                    };
                },
            );

        $translator = $this->createMock(Translate::class);
        $translator->expects(self::never())
            ->method('__invoke');

        $escapeHtml = $this->createMock(EscapeHtml::class);
        $escapeHtml->expects(self::never())
            ->method('__invoke');

        $htmlElement = $this->createMock(HtmlElementInterface::class);
        $htmlElement->expects(self::never())
            ->method('toHtml');

        $renderer = $this->createMock(PhpRenderer::class);
        $renderer->expects(self::never())
            ->method('getHelperPluginManager');
        $renderer->expects(self::never())
            ->method('plugin');
        $renderer->expects(self::never())
            ->method('render');

        $helper = new FormCollection();
        $helper->setView($renderer);

        $innerList = new PriorityList();

        $collectionElement = $this->createMock(Collection::class);
        $matcher           = self::exactly(5);
        $collectionElement->expects($matcher)
            ->method('getOption')
            ->willReturnCallback(
                static function (string $option) use ($matcher, $form, $layout, $floating): mixed {
                    match ($matcher->numberOfInvocations()) {
                        1, 3 => self::assertSame(
                            'form',
                            $option,
                            (string) $matcher->numberOfInvocations(),
                        ),
                        5 => self::assertSame(
                            'floating',
                            $option,
                            (string) $matcher->numberOfInvocations(),
                        ),
                        default => self::assertSame(
                            'layout',
                            $option,
                            (string) $matcher->numberOfInvocations(),
                        ),
                    };

                    return match ($matcher->numberOfInvocations()) {
                        1, 3 => $form,
                        5 => $floating,
                        default => $layout,
                    };
                },
            );
        $collectionElement->expects(self::once())
            ->method('setOption')
            ->with('floating', true);
        $collectionElement->expects(self::never())
            ->method('getAttributes');
        $collectionElement->expects(self::never())
            ->method('getLabel');
        $collectionElement->expects(self::never())
            ->method('getLabelOption');
        $collectionElement->expects(self::never())
            ->method('hasAttribute');
        $collectionElement->expects(self::once())
            ->method('getIterator')
            ->willReturn($innerList);
        $collectionElement->expects(self::once())
            ->method('shouldCreateTemplate')
            ->willReturn(false);
        $collectionElement->expects(self::never())
            ->method('getTemplateElement');

        $list = new PriorityList();
        $list->insert('x', $textElement);
        $list->insert('y', $buttonElement);
        $list->insert('z', $collectionElement);

        $element->expects(self::never())
            ->method('getName');
        $element->expects(self::exactly(6))
            ->method('getOption')
            ->willReturnMap(
                [
                    ['form', $form],
                    ['layout', $layout],
                    ['floating', $floating],
                    ['show-required-mark', false],
                ],
            );
        $element->expects(self::never())
            ->method('getAttributes');
        $element->expects(self::never())
            ->method('getLabel');
        $element->expects(self::never())
            ->method('getLabelOption');
        $element->expects(self::never())
            ->method('hasAttribute');
        $element->expects(self::once())
            ->method('getIterator')
            ->willReturn($list);
        $element->expects(self::once())
            ->method('shouldCreateTemplate')
            ->willReturn(false);
        $element->expects(self::never())
            ->method('getTemplateElement');

        $helper->setIndent($indent);
        $helper->setTranslatorTextDomain($textDomain);
        $helper->setShouldWrap(false);

        self::assertSame($expected, $helper->render($element));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws InvalidServiceException
     * @throws ServiceNotFoundException
     * @throws \Laminas\View\Exception\InvalidArgumentException
     * @throws RuntimeException
     * @throws \Laminas\I18n\Exception\RuntimeException
     */
    public function testRenderWithCollectionAndElementsAndOptionsAndTranslator4(): void
    {
        $form       = 'test-form';
        $layout     = Form::LAYOUT_HORIZONTAL;
        $floating   = true;
        $indent     = '<!-- -->  ';
        $textDomain = 'test-domain';

        $element = $this->createMock(Collection::class);

        $textElement = $this->createMock(Text::class);
        $matcher     = self::exactly(2);
        $textElement->expects($matcher)
            ->method('getOption')
            ->willReturnCallback(
                static function (string $option) use ($matcher, $form, $layout): mixed {
                    match ($matcher->numberOfInvocations()) {
                        1 => self::assertSame(
                            'form',
                            $option,
                            (string) $matcher->numberOfInvocations(),
                        ),
                        default => self::assertSame(
                            'layout',
                            $option,
                            (string) $matcher->numberOfInvocations(),
                        ),
                    };

                    return match ($matcher->numberOfInvocations()) {
                        1 => $form,
                        default => $layout,
                    };
                },
            );
        $matcher = self::exactly(2);
        $textElement->expects($matcher)
            ->method('setOption')
            ->willReturnCallback(
                static function (string $key, mixed $value) use ($matcher, $element): void {
                    match ($matcher->numberOfInvocations()) {
                        1 => self::assertSame(
                            'floating',
                            $key,
                            (string) $matcher->numberOfInvocations(),
                        ),
                        default => self::assertSame(
                            'fieldset',
                            $key,
                            (string) $matcher->numberOfInvocations(),
                        ),
                    };

                    match ($matcher->numberOfInvocations()) {
                        1 => self::assertTrue($value, (string) $matcher->numberOfInvocations()),
                        default => self::assertSame(
                            $element,
                            $value,
                            (string) $matcher->numberOfInvocations(),
                        ),
                    };
                },
            );

        $buttonElement = $this->createMock(Button::class);
        $matcher       = self::exactly(2);
        $buttonElement->expects($matcher)
            ->method('getOption')
            ->willReturnCallback(
                static function (string $option) use ($matcher, $form, $layout): mixed {
                    match ($matcher->numberOfInvocations()) {
                        1 => self::assertSame(
                            'form',
                            $option,
                            (string) $matcher->numberOfInvocations(),
                        ),
                        default => self::assertSame(
                            'layout',
                            $option,
                            (string) $matcher->numberOfInvocations(),
                        ),
                    };

                    return match ($matcher->numberOfInvocations()) {
                        1 => $form,
                        default => $layout,
                    };
                },
            );
        $matcher = self::exactly(2);
        $buttonElement->expects($matcher)
            ->method('setOption')
            ->willReturnCallback(
                static function (string $key, mixed $value) use ($matcher, $element): void {
                    match ($matcher->numberOfInvocations()) {
                        1 => self::assertSame(
                            'floating',
                            $key,
                            (string) $matcher->numberOfInvocations(),
                        ),
                        default => self::assertSame(
                            'fieldset',
                            $key,
                            (string) $matcher->numberOfInvocations(),
                        ),
                    };

                    match ($matcher->numberOfInvocations()) {
                        1 => self::assertTrue($value, (string) $matcher->numberOfInvocations()),
                        default => self::assertSame(
                            $element,
                            $value,
                            (string) $matcher->numberOfInvocations(),
                        ),
                    };
                },
            );

        $expectedButton = $indent . '    <button></button>';
        $expectedText   = $indent . '    <text></text>';
        $expected       = PHP_EOL . $expectedButton . PHP_EOL . $expectedText . PHP_EOL;

        $formRow = $this->createMock(FormRowInterface::class);
        $formRow->expects(self::exactly(2))
            ->method('setIndent')
            ->with($indent . '    ');
        $matcher = self::exactly(2);
        $formRow->expects($matcher)
            ->method('render')
            ->willReturnCallback(
                static function (ElementInterface $element, string | null $labelPosition = null) use ($matcher, $buttonElement, $textElement, $expectedButton, $expectedText): string {
                    match ($matcher->numberOfInvocations()) {
                        1 => self::assertSame(
                            $buttonElement,
                            $element,
                            (string) $matcher->numberOfInvocations(),
                        ),
                        default => self::assertSame(
                            $textElement,
                            $element,
                            (string) $matcher->numberOfInvocations(),
                        ),
                    };

                    self::assertNull($labelPosition, (string) $matcher->numberOfInvocations());

                    return match ($matcher->numberOfInvocations()) {
                        1 => $expectedButton,
                        default => $expectedText,
                    };
                },
            );

        $translator = $this->createMock(Translate::class);
        $translator->expects(self::never())
            ->method('__invoke');

        $escapeHtml = $this->createMock(EscapeHtml::class);
        $escapeHtml->expects(self::never())
            ->method('__invoke');

        $htmlElement = $this->createMock(HtmlElementInterface::class);
        $htmlElement->expects(self::never())
            ->method('toHtml');

        $renderer = $this->createMock(PhpRenderer::class);
        $renderer->expects(self::never())
            ->method('getHelperPluginManager');
        $renderer->expects(self::never())
            ->method('plugin');
        $renderer->expects(self::never())
            ->method('render');

        $helper = new FormCollection();
        $helper->setView($renderer);

        $innerList = new PriorityList();

        $collectionElement = $this->createMock(Collection::class);
        $matcher           = self::exactly(5);
        $collectionElement->expects($matcher)
            ->method('getOption')
            ->willReturnCallback(
                static function (string $option) use ($matcher, $form, $layout, $floating): mixed {
                    match ($matcher->numberOfInvocations()) {
                        1, 3 => self::assertSame(
                            'form',
                            $option,
                            (string) $matcher->numberOfInvocations(),
                        ),
                        5 => self::assertSame(
                            'floating',
                            $option,
                            (string) $matcher->numberOfInvocations(),
                        ),
                        default => self::assertSame(
                            'layout',
                            $option,
                            (string) $matcher->numberOfInvocations(),
                        ),
                    };

                    return match ($matcher->numberOfInvocations()) {
                        1, 3 => $form,
                        5 => $floating,
                        default => $layout,
                    };
                },
            );
        $collectionElement->expects(self::once())
            ->method('setOption')
            ->with('floating', true);
        $collectionElement->expects(self::never())
            ->method('getAttributes');
        $collectionElement->expects(self::never())
            ->method('getLabel');
        $collectionElement->expects(self::never())
            ->method('getLabelOption');
        $collectionElement->expects(self::never())
            ->method('hasAttribute');
        $collectionElement->expects(self::once())
            ->method('getIterator')
            ->willReturn($innerList);
        $collectionElement->expects(self::once())
            ->method('shouldCreateTemplate')
            ->willReturn(false);
        $collectionElement->expects(self::never())
            ->method('getTemplateElement');

        $list = new PriorityList();
        $list->insert('x', $textElement);
        $list->insert('y', $buttonElement);
        $list->insert('z', $collectionElement);

        $element->expects(self::never())
            ->method('getName');
        $element->expects(self::exactly(6))
            ->method('getOption')
            ->willReturnMap(
                [
                    ['form', $form],
                    ['layout', $layout],
                    ['floating', $floating],
                    ['show-required-mark', false],
                ],
            );
        $element->expects(self::never())
            ->method('getAttributes');
        $element->expects(self::never())
            ->method('getLabel');
        $element->expects(self::never())
            ->method('getLabelOption');
        $element->expects(self::never())
            ->method('hasAttribute');
        $element->expects(self::once())
            ->method('getIterator')
            ->willReturn($list);
        $element->expects(self::once())
            ->method('shouldCreateTemplate')
            ->willReturn(true);
        $element->expects(self::once())
            ->method('getTemplateElement')
            ->willReturn(null);

        $helper->setIndent($indent);
        $helper->setTranslatorTextDomain($textDomain);
        $helper->setShouldWrap(false);

        self::assertSame($expected, $helper->render($element));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws InvalidServiceException
     * @throws ServiceNotFoundException
     * @throws \Laminas\View\Exception\InvalidArgumentException
     * @throws RuntimeException
     * @throws \Laminas\I18n\Exception\RuntimeException
     */
    public function testRenderWithCollectionAndElementsAndOptionsAndTranslator5(): void
    {
        $form               = 'test-form';
        $layout             = Form::LAYOUT_HORIZONTAL;
        $floating           = true;
        $indent             = '<!-- -->  ';
        $textDomain         = 'test-domain';
        $templateAttributes = ['class' => 'template-class'];

        $element = $this->createMock(Collection::class);

        $textElement = $this->createMock(Text::class);
        $matcher     = self::exactly(2);
        $textElement->expects($matcher)
            ->method('getOption')
            ->willReturnCallback(
                static function (string $option) use ($matcher, $form, $layout): mixed {
                    match ($matcher->numberOfInvocations()) {
                        1 => self::assertSame(
                            'form',
                            $option,
                            (string) $matcher->numberOfInvocations(),
                        ),
                        default => self::assertSame(
                            'layout',
                            $option,
                            (string) $matcher->numberOfInvocations(),
                        ),
                    };

                    return match ($matcher->numberOfInvocations()) {
                        1 => $form,
                        default => $layout,
                    };
                },
            );
        $matcher = self::exactly(2);
        $textElement->expects($matcher)
            ->method('setOption')
            ->willReturnCallback(
                static function (string $key, mixed $value) use ($matcher, $element): void {
                    match ($matcher->numberOfInvocations()) {
                        1 => self::assertSame(
                            'floating',
                            $key,
                            (string) $matcher->numberOfInvocations(),
                        ),
                        default => self::assertSame(
                            'fieldset',
                            $key,
                            (string) $matcher->numberOfInvocations(),
                        ),
                    };

                    match ($matcher->numberOfInvocations()) {
                        1 => self::assertTrue($value, (string) $matcher->numberOfInvocations()),
                        default => self::assertSame(
                            $element,
                            $value,
                            (string) $matcher->numberOfInvocations(),
                        ),
                    };
                },
            );

        $templateElement = $this->createMock(Text::class);
        $templateElement->expects(self::never())
            ->method('getOption');
        $templateElement->expects(self::never())
            ->method('setOption');

        $buttonElement = $this->createMock(Button::class);
        $matcher       = self::exactly(2);
        $buttonElement->expects($matcher)
            ->method('getOption')
            ->willReturnCallback(
                static function (string $option) use ($matcher, $form, $layout): mixed {
                    match ($matcher->numberOfInvocations()) {
                        1 => self::assertSame(
                            'form',
                            $option,
                            (string) $matcher->numberOfInvocations(),
                        ),
                        default => self::assertSame(
                            'layout',
                            $option,
                            (string) $matcher->numberOfInvocations(),
                        ),
                    };

                    return match ($matcher->numberOfInvocations()) {
                        1 => $form,
                        default => $layout,
                    };
                },
            );
        $matcher = self::exactly(2);
        $buttonElement->expects($matcher)
            ->method('setOption')
            ->willReturnCallback(
                static function (string $key, mixed $value) use ($matcher, $element): void {
                    match ($matcher->numberOfInvocations()) {
                        1 => self::assertSame(
                            'floating',
                            $key,
                            (string) $matcher->numberOfInvocations(),
                        ),
                        default => self::assertSame(
                            'fieldset',
                            $key,
                            (string) $matcher->numberOfInvocations(),
                        ),
                    };

                    match ($matcher->numberOfInvocations()) {
                        1 => self::assertTrue($value, (string) $matcher->numberOfInvocations()),
                        default => self::assertSame(
                            $element,
                            $value,
                            (string) $matcher->numberOfInvocations(),
                        ),
                    };
                },
            );

        $expectedButton   = $indent . '    <button></button>';
        $expectedText     = $indent . '    <text></text>';
        $expectedTemplate = $indent . '    <template></template>';
        $renderedTemplate = '<template>template-content</template>';

        $expected = PHP_EOL . $expectedButton . PHP_EOL . $expectedText . PHP_EOL . $indent . '    ' . $renderedTemplate . PHP_EOL;

        $formRow = $this->createMock(FormRowInterface::class);
        $formRow->expects(self::exactly(3))
            ->method('setIndent')
            ->with($indent . '    ');
        $formRow->expects(self::exactly(3))
            ->method('render')
            ->willReturnMap(
                [
                    [$templateElement, null, $expectedTemplate],
                    [$buttonElement, null, $expectedButton],
                    [$textElement, null, $expectedText],
                ],
            );

        $translator = $this->createMock(Translate::class);
        $translator->expects(self::never())
            ->method('__invoke');

        $escapeHtml = $this->createMock(EscapeHtml::class);
        $escapeHtml->expects(self::never())
            ->method('__invoke');

        $htmlElement = $this->createMock(HtmlElementInterface::class);
        $htmlElement->expects(self::once())
            ->method('toHtml')
            ->with('template', ['class' => 'template-class'], $expectedTemplate . PHP_EOL . $indent)
            ->willReturn($renderedTemplate);

        $renderer = $this->createMock(PhpRenderer::class);
        $renderer->expects(self::never())
            ->method('getHelperPluginManager');
        $renderer->expects(self::never())
            ->method('plugin');
        $renderer->expects(self::never())
            ->method('render');

        $helper = new FormCollection();
        $helper->setView($renderer);

        $innerList = new PriorityList();

        $collectionElement = $this->createMock(Collection::class);
        $matcher           = self::exactly(5);
        $collectionElement->expects($matcher)
            ->method('getOption')
            ->willReturnCallback(
                static function (string $option) use ($matcher, $form, $layout, $floating): mixed {
                    match ($matcher->numberOfInvocations()) {
                        1, 3 => self::assertSame(
                            'form',
                            $option,
                            (string) $matcher->numberOfInvocations(),
                        ),
                        5 => self::assertSame(
                            'floating',
                            $option,
                            (string) $matcher->numberOfInvocations(),
                        ),
                        default => self::assertSame(
                            'layout',
                            $option,
                            (string) $matcher->numberOfInvocations(),
                        ),
                    };

                    return match ($matcher->numberOfInvocations()) {
                        1, 3 => $form,
                        5 => $floating,
                        default => $layout,
                    };
                },
            );
        $collectionElement->expects(self::once())
            ->method('setOption')
            ->with('floating', true);
        $collectionElement->expects(self::never())
            ->method('getAttributes');
        $collectionElement->expects(self::never())
            ->method('getLabel');
        $collectionElement->expects(self::never())
            ->method('getLabelOption');
        $collectionElement->expects(self::never())
            ->method('hasAttribute');
        $collectionElement->expects(self::once())
            ->method('getIterator')
            ->willReturn($innerList);
        $collectionElement->expects(self::once())
            ->method('shouldCreateTemplate')
            ->willReturn(false);
        $collectionElement->expects(self::never())
            ->method('getTemplateElement');

        $list = new PriorityList();
        $list->insert('x', $textElement);
        $list->insert('y', $buttonElement);
        $list->insert('z', $collectionElement);

        $element->expects(self::never())
            ->method('getName');
        $matcher = self::exactly(7);
        $element->expects($matcher)
            ->method('getOption')
            ->willReturnCallback(
                static function (string $option) use ($matcher, $form, $layout, $floating, $templateAttributes): mixed {
                    match ($matcher->numberOfInvocations()) {
                        1 => self::assertSame(
                            'template_attributes',
                            $option,
                            (string) $matcher->numberOfInvocations(),
                        ),
                        2 => self::assertSame(
                            'form',
                            $option,
                            (string) $matcher->numberOfInvocations(),
                        ),
                        4 => self::assertSame(
                            'floating',
                            $option,
                            (string) $matcher->numberOfInvocations(),
                        ),
                        5, 6, 7 => self::assertSame(
                            'show-required-mark',
                            $option,
                            (string) $matcher->numberOfInvocations(),
                        ),
                        default => self::assertSame(
                            'layout',
                            $option,
                            (string) $matcher->numberOfInvocations(),
                        ),
                    };

                    return match ($matcher->numberOfInvocations()) {
                        1 => $templateAttributes,
                        2 => $form,
                        4 => $floating,
                        5, 6, 7 => false,
                        default => $layout,
                    };
                },
            );
        $element->expects(self::never())
            ->method('getAttributes');
        $element->expects(self::never())
            ->method('getLabel');
        $element->expects(self::never())
            ->method('getLabelOption');
        $element->expects(self::never())
            ->method('hasAttribute');
        $element->expects(self::once())
            ->method('getIterator')
            ->willReturn($list);
        $element->expects(self::once())
            ->method('shouldCreateTemplate')
            ->willReturn(true);
        $element->expects(self::once())
            ->method('getTemplateElement')
            ->willReturn($templateElement);

        $helper->setIndent($indent);
        $helper->setTranslatorTextDomain($textDomain);
        $helper->setShouldWrap(false);

        self::assertSame($expected, $helper->render($element));
    }
}
