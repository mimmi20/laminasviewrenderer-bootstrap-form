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
use Laminas\Form\Form;
use Laminas\ServiceManager\Exception\InvalidServiceException;
use Laminas\ServiceManager\Exception\ServiceNotFoundException;
use Laminas\Stdlib\PriorityList;
use Laminas\View\Exception\RuntimeException;
use Laminas\View\Helper\EscapeHtml;
use Laminas\View\Helper\Escaper\AbstractHelper;
use Laminas\View\Renderer\PhpRenderer;
use Mimmi20\LaminasView\BootstrapForm\FormCollection;
use Mimmi20\LaminasView\BootstrapForm\FormRowInterface;
use Mimmi20\LaminasView\Helper\HtmlElement\Helper\HtmlElementInterface;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;

use function sprintf;

use const PHP_EOL;

final class FormCollection3Test extends TestCase
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
    public function testRenderWithFormWithoutOptionsAndElements(): void
    {
        $form            = null;
        $layout          = null;
        $floating        = null;
        $attributes      = [];
        $labelAttributes = [];
        $label           = 'test-label';
        $labelEscaped    = 'test-label-escaped';
        $disableEscape   = false;
        $wrap            = true;
        $indent          = '';

        $expectedLegend   = '<legend></legend>';
        $expectedFieldset = '<fieldset></fieldset>';

        $formRow = $this->createMock(FormRowInterface::class);
        $formRow->expects(self::never())
            ->method('setIndent');
        $formRow->expects(self::never())
            ->method('render');

        $escapeHtml = $this->createMock(EscapeHtml::class);
        $escapeHtml->expects(self::once())
            ->method('__invoke')
            ->with($label, 0)
            ->willReturn($labelEscaped);

        $htmlElement = $this->createMock(HtmlElementInterface::class);
        $matcher     = self::exactly(2);
        $htmlElement->expects($matcher)
            ->method('toHtml')
            ->willReturnCallback(
                static function (string $element, array $attribs, string $content) use ($matcher, $labelEscaped, $expectedLegend, $expectedFieldset): string {
                    match ($matcher->numberOfInvocations()) {
                        1 => self::assertSame(
                            'legend',
                            $element,
                            (string) $matcher->numberOfInvocations(),
                        ),
                        default => self::assertSame(
                            'fieldset',
                            $element,
                            (string) $matcher->numberOfInvocations(),
                        ),
                    };

                    match ($matcher->numberOfInvocations()) {
                        1 => self::assertSame(
                            ['class' => ''],
                            $attribs,
                            (string) $matcher->numberOfInvocations(),
                        ),
                        default => self::assertSame(
                            [],
                            $attribs,
                            (string) $matcher->numberOfInvocations(),
                        ),
                    };

                    match ($matcher->numberOfInvocations()) {
                        1 => self::assertSame(sprintf(
                            '<span>%s</span>',
                            $labelEscaped,
                        ), $content, (string) $matcher->numberOfInvocations()),
                        default => self::assertSame(
                            PHP_EOL . '    ' . $expectedLegend . PHP_EOL,
                            $content,
                            (string) $matcher->numberOfInvocations(),
                        ),
                    };

                    return match ($matcher->numberOfInvocations()) {
                        1 => $expectedLegend,
                        default => $expectedFieldset,
                    };
                },
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

        $list = new PriorityList();

        $element = $this->createMock(\Laminas\Form\Form::class);
        $element->expects(self::never())
            ->method('getName');
        $matcher = self::exactly(4);
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
                        4 => self::assertSame(
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
                        4 => $labelAttributes,
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

        $helper->setIndent($indent);

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
    public function testRenderWithFormAndElementsWithoutOptions(): void
    {
        $form            = null;
        $layout          = null;
        $floating        = null;
        $attributes      = [];
        $labelAttributes = [];
        $label           = 'test-label';
        $labelEscaped    = 'test-label-escaped';
        $disableEscape   = false;
        $wrap            = true;
        $indent          = '';

        $innerLabel        = 'inner-test-label';
        $innerLabelEscaped = 'inner-test-label-escaped';

        $expectedLegend   = '<legend></legend>';
        $expectedFieldset = '<fieldset></fieldset>';

        $expectedInnerLegend   = '<legend>inside</legend>';
        $expectedInnerFieldset = '<fieldset>inside</fieldset>';

        $element = $this->createMock(\Laminas\Form\Form::class);

        $textElement = $this->createMock(Text::class);
        $textElement->expects(self::never())
            ->method('getOption');
        $textElement->expects(self::once())
            ->method('setOption')
            ->with('fieldset', $element);

        $buttonElement = $this->createMock(Button::class);
        $buttonElement->expects(self::never())
            ->method('getOption');
        $buttonElement->expects(self::once())
            ->method('setOption')
            ->with('fieldset', $element);

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

                    self::assertNull($labelPosition, (string) $matcher->numberOfInvocations());

                    return match ($matcher->numberOfInvocations()) {
                        1 => $expectedButton,
                        default => $expectedText,
                    };
                },
            );

        $escapeHtml = $this->createMock(EscapeHtml::class);
        $matcher    = self::exactly(2);
        $escapeHtml->expects($matcher)
            ->method('__invoke')
            ->willReturnCallback(
                static function (mixed $value, int $recurse = AbstractHelper::RECURSE_NONE) use ($matcher, $innerLabel, $label, $innerLabelEscaped, $labelEscaped): mixed {
                    match ($matcher->numberOfInvocations()) {
                        1 => self::assertSame(
                            $innerLabel,
                            $value,
                            (string) $matcher->numberOfInvocations(),
                        ),
                        default => self::assertSame(
                            $label,
                            $value,
                            (string) $matcher->numberOfInvocations(),
                        ),
                    };

                    self::assertSame(0, $recurse, (string) $matcher->numberOfInvocations());

                    return match ($matcher->numberOfInvocations()) {
                        1 => $innerLabelEscaped,
                        default => $labelEscaped,
                    };
                },
            );

        // var_dump('expected: fieldset', [], PHP_EOL . '        ' . $expectedInnerLegend . PHP_EOL . '    ', $expectedInnerFieldset);

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
                            $innerLabelEscaped,
                        ),
                        $expectedInnerLegend,
                    ],
                    ['fieldset', [], PHP_EOL . '        ' . $expectedInnerLegend . PHP_EOL . '    ', $expectedInnerFieldset],
                    [
                        'legend',
                        ['class' => ''],
                        sprintf(
                            '<span>%s</span>',
                            $labelEscaped,
                        ),
                        $expectedLegend,
                    ],
                    ['fieldset', [], PHP_EOL . '    ' . $expectedLegend . PHP_EOL . '    ' . $expectedInnerFieldset . PHP_EOL . $expectedButton . PHP_EOL . $expectedText . PHP_EOL, $expectedFieldset],
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
        $collectionElement->expects(self::exactly(4))
            ->method('getOption')
            ->willReturnMap(
                [
                    ['form', $form],
                    ['layout', $layout],
                    ['floating', $floating],
                    ['label_attributes', []],
                ],
            );
        $collectionElement->expects(self::never())
            ->method('setOption');
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

        $helper->setIndent($indent);

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
    public function testRenderWithCollectionAndElementsWithoutOptions(): void
    {
        $form            = null;
        $layout          = null;
        $floating        = null;
        $attributes      = [];
        $labelAttributes = [];
        $label           = 'test-label';
        $labelEscaped    = 'test-label-escaped';
        $disableEscape   = false;
        $wrap            = true;
        $indent          = '';

        $innerLabel        = 'inner-test-label';
        $innerLabelEscaped = 'inner-test-label-escaped';

        $expectedLegend   = '<legend></legend>';
        $expectedFieldset = '<fieldset></fieldset>';

        $expectedInnerLegend   = '<legend>inside</legend>';
        $expectedInnerFieldset = '<fieldset>inside</fieldset>';

        $element = $this->createMock(Collection::class);

        $textElement = $this->createMock(Text::class);
        $textElement->expects(self::never())
            ->method('getOption');
        $textElement->expects(self::once())
            ->method('setOption')
            ->with('fieldset', $element);

        $buttonElement = $this->createMock(Button::class);
        $buttonElement->expects(self::never())
            ->method('getOption');
        $buttonElement->expects(self::once())
            ->method('setOption')
            ->with('fieldset', $element);

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

                    self::assertNull($labelPosition, (string) $matcher->numberOfInvocations());

                    return match ($matcher->numberOfInvocations()) {
                        1 => $expectedButton,
                        default => $expectedText,
                    };
                },
            );

        $escapeHtml = $this->createMock(EscapeHtml::class);
        $matcher    = self::exactly(2);
        $escapeHtml->expects($matcher)
            ->method('__invoke')
            ->willReturnCallback(
                static function (mixed $value, int $recurse = AbstractHelper::RECURSE_NONE) use ($matcher, $innerLabel, $label, $innerLabelEscaped, $labelEscaped): mixed {
                    match ($matcher->numberOfInvocations()) {
                        1 => self::assertSame(
                            $innerLabel,
                            $value,
                            (string) $matcher->numberOfInvocations(),
                        ),
                        default => self::assertSame(
                            $label,
                            $value,
                            (string) $matcher->numberOfInvocations(),
                        ),
                    };

                    self::assertSame(0, $recurse, (string) $matcher->numberOfInvocations());

                    return match ($matcher->numberOfInvocations()) {
                        1 => $innerLabelEscaped,
                        default => $labelEscaped,
                    };
                },
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
                            $innerLabelEscaped,
                        ),
                        $expectedInnerLegend,
                    ],
                    ['fieldset', [], PHP_EOL . '        ' . $expectedInnerLegend . PHP_EOL . '    ', $expectedInnerFieldset],
                    [
                        'legend',
                        ['class' => ''],
                        sprintf(
                            '<span>%s</span>',
                            $labelEscaped,
                        ),
                        $expectedLegend,
                    ],
                    ['fieldset', [], PHP_EOL . '    ' . $expectedLegend . PHP_EOL . '    ' . $expectedInnerFieldset . PHP_EOL . $expectedButton . PHP_EOL . $expectedText . PHP_EOL, $expectedFieldset],
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
        $collectionElement->expects(self::exactly(4))
            ->method('getOption')
            ->willReturnMap(
                [
                    ['form', $form],
                    ['layout', $layout],
                    ['floating', $floating],
                    ['label_attributes', []],
                ],
            );
        $collectionElement->expects(self::never())
            ->method('setOption');
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

        self::assertSame($indent . $expectedFieldset, $helper->render($element));
    }
}
