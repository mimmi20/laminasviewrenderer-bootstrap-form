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

use function sprintf;

use const PHP_EOL;

final class FormCollection5Test extends TestCase
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
    public function testRenderWithCollectionAndElementsAndOptionsAndTranslator(): void
    {
        $form            = 'test-form';
        $layout          = Form::LAYOUT_HORIZONTAL;
        $floating        = true;
        $attributes      = [];
        $labelAttributes = [];
        $label           = 'test-label';
        $labelTranslated = 'test-label-translated';
        $disableEscape   = true;
        $wrap            = false;
        $indent          = '<!-- -->  ';
        $textDomain      = 'test-domain';

        $innerLabel           = 'inner-test-label';
        $innerLabelTranslated = 'inner-test-label-translated';

        $expectedLegend   = '<legend></legend>';
        $expectedFieldset = '<fieldset></fieldset>';

        $expectedInnerLegend   = '<legend>inside</legend>';
        $expectedInnerFieldset = '<fieldset>inside</fieldset>';

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

                    self::assertNull($labelPosition);

                    return match ($matcher->numberOfInvocations()) {
                        1 => $expectedButton,
                        default => $expectedText,
                    };
                },
            );

        $translator = $this->createMock(Translate::class);
        $translator->expects(self::exactly(2))
            ->method('__invoke')
            ->willReturnMap(
                [
                    [$innerLabel, $textDomain, null, $innerLabelTranslated],
                    [$label, $textDomain, null, $labelTranslated],
                ],
            );

        $escapeHtml = $this->createMock(EscapeHtml::class);
        $escapeHtml->expects(self::never())
            ->method('__invoke');

        $htmlElement = $this->createMock(HtmlElementInterface::class);
        $htmlElement->expects(self::exactly(4))
            ->method('toHtml')
            ->willReturnMap(
                [
                    [
                        'legend',
                        ['class' => ''],
                        sprintf(
                            '<span>%s</span>',
                            $innerLabelTranslated,
                        ),
                        $expectedInnerLegend,
                    ],
                    ['fieldset', [], PHP_EOL . $indent . '        ' . $expectedInnerLegend . PHP_EOL . $indent . '    ', $expectedInnerFieldset],
                    ['legend', ['class' => ''], $labelTranslated, $expectedLegend],
                    ['fieldset', [], PHP_EOL . $indent . '    ' . $expectedLegend . PHP_EOL . $indent . '    ' . $expectedInnerFieldset . PHP_EOL . $expectedButton . PHP_EOL . $expectedText . PHP_EOL . $indent, $expectedFieldset],
                ],
            );

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
        $collectionElement->expects(self::exactly(6))
            ->method('getOption')
            ->willReturnMap(
                [
                    ['form', $form],
                    ['layout', $layout],
                    ['floating', $floating],
                    ['label_attributes', []],
                ],
            );
        $collectionElement->expects(self::once())
            ->method('setOption')
            ->with('floating', true);
        $collectionElement->expects(self::once())
            ->method('getAttributes')
            ->willReturn($attributes);
        $collectionElement->expects(self::once())
            ->method('getLabel')
            ->willReturn($innerLabel);
        $collectionElement->expects(self::once())
            ->method('getLabelOption')
            ->with('disable_html_escape')
            ->willReturn($disableEscape);
        $collectionElement->expects(self::once())
            ->method('hasAttribute')
            ->with('id')
            ->willReturn(false);
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
                static function (string $option) use ($matcher, $form, $layout, $floating, $labelAttributes): mixed {
                    match ($matcher->numberOfInvocations()) {
                        1 => self::assertSame(
                            'form',
                            $option,
                            (string) $matcher->numberOfInvocations(),
                        ),
                        3 => self::assertSame(
                            'floating',
                            $option,
                            (string) $matcher->numberOfInvocations(),
                        ),
                        4, 5, 6 => self::assertSame(
                            'show-required-mark',
                            $option,
                            (string) $matcher->numberOfInvocations(),
                        ),
                        7 => self::assertSame(
                            'label_attributes',
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
                        3 => $floating,
                        4, 5, 6 => false,
                        7 => $labelAttributes,
                        default => $layout,
                    };
                },
            );
        $element->expects(self::once())
            ->method('getAttributes')
            ->willReturn($attributes);
        $element->expects(self::once())
            ->method('getLabel')
            ->willReturn($label);
        $matcher = self::exactly(2);
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
            ->method('hasAttribute')
            ->with('id')
            ->willReturn(true);
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

        self::assertSame($indent . $expectedFieldset, $helper->render($element));
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
    public function testRenderWithCollectionAndElementsAndOptionsAndTranslator2(): void
    {
        $form                   = 'test-form';
        $layout                 = Form::LAYOUT_HORIZONTAL;
        $floating               = true;
        $attributes             = [];
        $labelAttributes        = [];
        $label                  = 'test-label';
        $labelTranslated        = 'test-label-translated';
        $labelTranslatedEscaped = 'test-label-translated-escaped';
        $disableEscape          = false;
        $wrap                   = false;
        $indent                 = '<!-- -->  ';
        $textDomain             = 'test-domain';

        $innerLabel                  = 'inner-test-label';
        $innerLabelTranslated        = 'inner-test-label-translated';
        $innerLabelTranslatedEscaped = 'inner-test-label-translated-escaped';

        $expectedLegend   = '<legend></legend>';
        $expectedFieldset = '<fieldset></fieldset>';

        $expectedInnerLegend   = '<legend>inside</legend>';
        $expectedInnerFieldset = '<fieldset>inside</fieldset>';

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

                    self::assertNull($labelPosition);

                    return match ($matcher->numberOfInvocations()) {
                        1 => $expectedButton,
                        default => $expectedText,
                    };
                },
            );

        $translator = $this->createMock(Translate::class);
        $translator->expects(self::exactly(2))
            ->method('__invoke')
            ->willReturnMap(
                [
                    [$innerLabel, $textDomain, null, $innerLabelTranslated],
                    [$label, $textDomain, null, $labelTranslated],
                ],
            );

        $escapeHtml = $this->createMock(EscapeHtml::class);
        $escapeHtml->expects(self::exactly(2))
            ->method('__invoke')
            ->willReturnMap(
                [
                    [$innerLabelTranslated, 0, $innerLabelTranslatedEscaped],
                    [$labelTranslated, 0, $labelTranslatedEscaped],
                ],
            );

        $htmlElement = $this->createMock(HtmlElementInterface::class);
        $htmlElement->expects(self::exactly(4))
            ->method('toHtml')
            ->willReturnMap(
                [
                    [
                        'legend',
                        ['class' => ''],
                        sprintf(
                            '<span>%s</span>',
                            $innerLabelTranslatedEscaped,
                        ),
                        $expectedInnerLegend,
                    ],
                    ['fieldset', [], PHP_EOL . $indent . '        ' . $expectedInnerLegend . PHP_EOL . $indent . '    ', $expectedInnerFieldset],
                    ['legend', ['class' => ''], $labelTranslatedEscaped, $expectedLegend],
                    ['fieldset', [], PHP_EOL . $indent . '    ' . $expectedLegend . PHP_EOL . $indent . '    ' . $expectedInnerFieldset . PHP_EOL . $expectedButton . PHP_EOL . $expectedText . PHP_EOL . $indent, $expectedFieldset],
                ],
            );

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
        $collectionElement->expects(self::exactly(6))
            ->method('getOption')
            ->willReturnMap(
                [
                    ['form', $form],
                    ['layout', $layout],
                    ['floating', $floating],
                    ['label_attributes', []],
                ],
            );
        $collectionElement->expects(self::once())
            ->method('setOption')
            ->with('floating', true);
        $collectionElement->expects(self::once())
            ->method('getAttributes')
            ->willReturn($attributes);
        $collectionElement->expects(self::once())
            ->method('getLabel')
            ->willReturn($innerLabel);
        $collectionElement->expects(self::once())
            ->method('getLabelOption')
            ->with('disable_html_escape')
            ->willReturn($disableEscape);
        $collectionElement->expects(self::once())
            ->method('hasAttribute')
            ->with('id')
            ->willReturn(false);
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
                static function (string $option) use ($matcher, $form, $layout, $floating, $labelAttributes): mixed {
                    match ($matcher->numberOfInvocations()) {
                        1 => self::assertSame(
                            'form',
                            $option,
                            (string) $matcher->numberOfInvocations(),
                        ),
                        3 => self::assertSame(
                            'floating',
                            $option,
                            (string) $matcher->numberOfInvocations(),
                        ),
                        4, 5, 6 => self::assertSame(
                            'show-required-mark',
                            $option,
                            (string) $matcher->numberOfInvocations(),
                        ),
                        7 => self::assertSame(
                            'label_attributes',
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
                        3 => $floating,
                        4, 5, 6 => false,
                        7 => $labelAttributes,
                        default => $layout,
                    };
                },
            );
        $element->expects(self::once())
            ->method('getAttributes')
            ->willReturn($attributes);
        $element->expects(self::once())
            ->method('getLabel')
            ->willReturn($label);
        $matcher = self::exactly(2);
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
            ->method('hasAttribute')
            ->with('id')
            ->willReturn(true);
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

        self::assertSame($indent . $expectedFieldset, $helper->render($element));
    }
}
