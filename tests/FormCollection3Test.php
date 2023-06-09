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

use Laminas\Form\Element\Button;
use Laminas\Form\Element\Collection;
use Laminas\Form\Element\Text;
use Laminas\Form\Exception\DomainException;
use Laminas\Form\Exception\InvalidArgumentException;
use Laminas\Form\Form;
use Laminas\ServiceManager\Exception\InvalidServiceException;
use Laminas\ServiceManager\Exception\ServiceNotFoundException;
use Laminas\Stdlib\PriorityList;
use Laminas\View\Exception\RuntimeException;
use Laminas\View\Helper\EscapeHtml;
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

        $formRow = $this->getMockBuilder(FormRowInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formRow->expects(self::never())
            ->method('setIndent');
        $formRow->expects(self::never())
            ->method('render');

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::once())
            ->method('__invoke')
            ->with($label, 0)
            ->willReturn($labelEscaped);

        $htmlElement = $this->getMockBuilder(HtmlElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $htmlElement->expects(self::exactly(2))
            ->method('toHtml')
            ->willReturnMap(
                [
                    [
                        'legend',
                        ['class' => ''],
                        sprintf(
                            '<span>%s</span>',
                            $labelEscaped,
                        ),
                        $expectedLegend,
                    ],
                    ['fieldset', [], PHP_EOL . '    ' . $expectedLegend . PHP_EOL, $expectedFieldset],
                ],
            );

        $helper = new FormCollection($formRow, $escapeHtml, $htmlElement, null);

        $list = new PriorityList();

        $element = $this->getMockBuilder(Form::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::never())
            ->method('getName');
        $element->expects(self::exactly(4))
            ->method('getOption')
            ->willReturnMap(
                [
                    ['form', $form],
                    ['layout', $layout],
                    ['floating', $floating],
                    ['label_attributes', $labelAttributes],
                ],
            );
        $element->expects(self::once())
            ->method('getAttributes')
            ->willReturn($attributes);
        $element->expects(self::once())
            ->method('getLabel')
            ->willReturn($label);
        $element->expects(self::exactly(2))
            ->method('getLabelOption')
            ->willReturnMap(
                [
                    ['disable_html_escape', $disableEscape],
                    ['always_wrap', $wrap],
                ],
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

        $textElement = $this->getMockBuilder(Text::class)
            ->disableOriginalConstructor()
            ->getMock();
        $textElement->expects(self::never())
            ->method('getOption');
        $textElement->expects(self::never())
            ->method('setOption');

        $buttonElement = $this->getMockBuilder(Button::class)
            ->disableOriginalConstructor()
            ->getMock();
        $buttonElement->expects(self::never())
            ->method('getOption');
        $buttonElement->expects(self::never())
            ->method('setOption');

        $expectedButton = $indent . '    <button></button>';
        $expectedText   = $indent . '    <text></text>';

        $formRow = $this->getMockBuilder(FormRowInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formRow->expects(self::exactly(2))
            ->method('setIndent')
            ->with($indent . '    ');
        $formRow->expects(self::exactly(2))
            ->method('render')
            ->willReturnMap(
                [
                    [$buttonElement, null, $expectedButton],
                    [$textElement, null, $expectedText],
                ],
            );

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::exactly(2))
            ->method('__invoke')
            ->willReturnMap(
                [
                    [$innerLabel, 0, $innerLabelEscaped],
                    [$label, 0, $labelEscaped],
                ],
            );

        // var_dump('expected: fieldset', [], PHP_EOL . '        ' . $expectedInnerLegend . PHP_EOL . '    ', $expectedInnerFieldset);

        $htmlElement = $this->getMockBuilder(HtmlElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
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

        $helper = new FormCollection($formRow, $escapeHtml, $htmlElement, null);

        $innerList = new PriorityList();

        $collectionElement = $this->getMockBuilder(Collection::class)
            ->disableOriginalConstructor()
            ->getMock();
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

        $element = $this->getMockBuilder(Form::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::never())
            ->method('getName');
        $element->expects(self::exactly(7))
            ->method('getOption')
            ->willReturnMap(
                [
                    ['form', $form],
                    ['layout', $layout],
                    ['floating', $floating],
                    ['show-required-mark', false],
                    ['label_attributes', $labelAttributes],
                ],
            );
        $element->expects(self::once())
            ->method('getAttributes')
            ->willReturn($attributes);
        $element->expects(self::once())
            ->method('getLabel')
            ->willReturn($label);
        $element->expects(self::exactly(2))
            ->method('getLabelOption')
            ->willReturnMap(
                [
                    ['disable_html_escape', $disableEscape],
                    ['always_wrap', $wrap],
                ],
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

        $textElement = $this->getMockBuilder(Text::class)
            ->disableOriginalConstructor()
            ->getMock();
        $textElement->expects(self::never())
            ->method('getOption');
        $textElement->expects(self::never())
            ->method('setOption');

        $buttonElement = $this->getMockBuilder(Button::class)
            ->disableOriginalConstructor()
            ->getMock();
        $buttonElement->expects(self::never())
            ->method('getOption');
        $buttonElement->expects(self::never())
            ->method('setOption');

        $expectedButton = $indent . '    <button></button>';
        $expectedText   = $indent . '    <text></text>';

        $formRow = $this->getMockBuilder(FormRowInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formRow->expects(self::exactly(2))
            ->method('setIndent')
            ->with($indent . '    ');
        $formRow->expects(self::exactly(2))
            ->method('render')
            ->willReturnMap(
                [
                    [$buttonElement, null, $expectedButton],
                    [$textElement, null, $expectedText],
                ],
            );

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::exactly(2))
            ->method('__invoke')
            ->willReturnMap(
                [
                    [$innerLabel, 0, $innerLabelEscaped],
                    [$label, 0, $labelEscaped],
                ],
            );

        $htmlElement = $this->getMockBuilder(HtmlElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
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

        $helper = new FormCollection($formRow, $escapeHtml, $htmlElement, null);

        $innerList = new PriorityList();

        $collectionElement = $this->getMockBuilder(Collection::class)
            ->disableOriginalConstructor()
            ->getMock();
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

        $element = $this->getMockBuilder(Collection::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::never())
            ->method('getName');
        $element->expects(self::exactly(7))
            ->method('getOption')
            ->willReturnMap(
                [
                    ['form', $form],
                    ['layout', $layout],
                    ['floating', $floating],
                    ['show-required-mark', false],
                    ['label_attributes', $labelAttributes],
                ],
            );
        $element->expects(self::once())
            ->method('getAttributes')
            ->willReturn($attributes);
        $element->expects(self::once())
            ->method('getLabel')
            ->willReturn($label);
        $element->expects(self::exactly(2))
            ->method('getLabelOption')
            ->willReturnMap(
                [
                    ['disable_html_escape', $disableEscape],
                    ['always_wrap', $wrap],
                ],
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

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws InvalidServiceException
     * @throws ServiceNotFoundException
     * @throws \Laminas\View\Exception\InvalidArgumentException
     * @throws RuntimeException
     */
    public function testRenderWithFormAndElementsAndOptions(): void
    {
        $form            = 'test-form';
        $layout          = \Mimmi20\LaminasView\BootstrapForm\Form::LAYOUT_HORIZONTAL;
        $floating        = true;
        $attributes      = [];
        $labelAttributes = [];
        $label           = 'test-label';
        $disableEscape   = true;
        $wrap            = false;
        $indent          = '<!-- -->  ';

        $innerLabel = 'inner-test-label';

        $expectedLegend   = '<legend></legend>';
        $expectedFieldset = '<fieldset></fieldset>';

        $expectedInnerLegend   = '<legend>inside</legend>';
        $expectedInnerFieldset = '<fieldset>inside</fieldset>';

        $textElement = $this->getMockBuilder(Text::class)
            ->disableOriginalConstructor()
            ->getMock();
        $textElement->expects(self::exactly(2))
            ->method('getOption')
            ->willReturnMap(
                [
                    ['form', $form],
                    ['layout', $layout],
                ],
            );
        $textElement->expects(self::once())
            ->method('setOption')
            ->with('floating', true);

        $buttonElement = $this->getMockBuilder(Button::class)
            ->disableOriginalConstructor()
            ->getMock();
        $buttonElement->expects(self::exactly(2))
            ->method('getOption')
            ->willReturnMap(
                [
                    ['form', $form],
                    ['layout', $layout],
                ],
            );
        $buttonElement->expects(self::once())
            ->method('setOption')
            ->with('floating', true);

        $expectedButton = $indent . '    <button></button>';
        $expectedText   = $indent . '    <text></text>';

        $formRow = $this->getMockBuilder(FormRowInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formRow->expects(self::exactly(2))
            ->method('setIndent')
            ->with($indent . '    ');
        $formRow->expects(self::exactly(2))
            ->method('render')
            ->willReturnMap(
                [
                    [$buttonElement, null, $expectedButton],
                    [$textElement, null, $expectedText],
                ],
            );

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::never())
            ->method('__invoke');

        $htmlElement = $this->getMockBuilder(HtmlElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $htmlElement->expects(self::exactly(4))
            ->method('toHtml')
            ->willReturnMap(
                [
                    [
                        'legend',
                        ['class' => ''],
                        sprintf(
                            '<span>%s</span>',
                            $innerLabel,
                        ),
                        $expectedInnerLegend,
                    ],
                    ['fieldset', [], PHP_EOL . $indent . '        ' . $expectedInnerLegend . PHP_EOL . $indent . '    ', $expectedInnerFieldset],
                    ['legend', ['class' => ''], $label, $expectedLegend],
                    ['fieldset', [], PHP_EOL . $indent . '    ' . $expectedLegend . PHP_EOL . $indent . '    ' . $expectedInnerFieldset . PHP_EOL . $expectedButton . PHP_EOL . $expectedText . PHP_EOL . $indent, $expectedFieldset],
                ],
            );

        $helper = new FormCollection($formRow, $escapeHtml, $htmlElement, null);

        $innerList = new PriorityList();

        $collectionElement = $this->getMockBuilder(Collection::class)
            ->disableOriginalConstructor()
            ->getMock();
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

        $element = $this->getMockBuilder(Form::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::never())
            ->method('getName');
        $element->expects(self::exactly(7))
            ->method('getOption')
            ->willReturnMap(
                [
                    ['form', $form],
                    ['layout', $layout],
                    ['floating', $floating],
                    ['show-required-mark', false],
                    ['label_attributes', $labelAttributes],
                ],
            );
        $element->expects(self::once())
            ->method('getAttributes')
            ->willReturn($attributes);
        $element->expects(self::once())
            ->method('getLabel')
            ->willReturn($label);
        $element->expects(self::exactly(2))
            ->method('getLabelOption')
            ->willReturnMap(
                [
                    ['disable_html_escape', $disableEscape],
                    ['always_wrap', $wrap],
                ],
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
     */
    public function testRenderWithCollectionAndElementsAndOptions(): void
    {
        $form            = 'test-form';
        $layout          = \Mimmi20\LaminasView\BootstrapForm\Form::LAYOUT_HORIZONTAL;
        $floating        = true;
        $attributes      = [];
        $labelAttributes = [];
        $label           = 'test-label';
        $disableEscape   = true;
        $wrap            = false;
        $indent          = '<!-- -->  ';

        $innerLabel = 'inner-test-label';

        $expectedLegend   = '<legend></legend>';
        $expectedFieldset = '<fieldset></fieldset>';

        $expectedInnerLegend   = '<legend>inside</legend>';
        $expectedInnerFieldset = '<fieldset>inside</fieldset>';

        $textElement = $this->getMockBuilder(Text::class)
            ->disableOriginalConstructor()
            ->getMock();
        $textElement->expects(self::exactly(2))
            ->method('getOption')
            ->willReturnMap(
                [
                    ['form', $form],
                    ['layout', $layout],
                ],
            );
        $textElement->expects(self::once())
            ->method('setOption')
            ->with('floating', true);

        $buttonElement = $this->getMockBuilder(Button::class)
            ->disableOriginalConstructor()
            ->getMock();
        $buttonElement->expects(self::exactly(2))
            ->method('getOption')
            ->willReturnMap(
                [
                    ['form', $form],
                    ['layout', $layout],
                ],
            );
        $buttonElement->expects(self::once())
            ->method('setOption')
            ->with('floating', true);

        $expectedButton = $indent . '    <button></button>';
        $expectedText   = $indent . '    <text></text>';

        $formRow = $this->getMockBuilder(FormRowInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formRow->expects(self::exactly(2))
            ->method('setIndent')
            ->with($indent . '    ');
        $formRow->expects(self::exactly(2))
            ->method('render')
            ->willReturnMap(
                [
                    [$buttonElement, null, $expectedButton],
                    [$textElement, null, $expectedText],
                ],
            );

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::never())
            ->method('__invoke');

        $htmlElement = $this->getMockBuilder(HtmlElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $htmlElement->expects(self::exactly(4))
            ->method('toHtml')
            ->willReturnMap(
                [
                    [
                        'legend',
                        ['class' => ''],
                        sprintf(
                            '<span>%s</span>',
                            $innerLabel,
                        ),
                        $expectedInnerLegend,
                    ],
                    ['fieldset', [], PHP_EOL . $indent . '        ' . $expectedInnerLegend . PHP_EOL . $indent . '    ', $expectedInnerFieldset],
                    ['legend', ['class' => ''], $label, $expectedLegend],
                    ['fieldset', [], PHP_EOL . $indent . '    ' . $expectedLegend . PHP_EOL . $indent . '    ' . $expectedInnerFieldset . PHP_EOL . $expectedButton . PHP_EOL . $expectedText . PHP_EOL . $indent, $expectedFieldset],
                ],
            );

        $helper = new FormCollection($formRow, $escapeHtml, $htmlElement, null);

        $innerList = new PriorityList();

        $collectionElement = $this->getMockBuilder(Collection::class)
            ->disableOriginalConstructor()
            ->getMock();
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

        $element = $this->getMockBuilder(Collection::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::never())
            ->method('getName');
        $element->expects(self::exactly(7))
            ->method('getOption')
            ->willReturnMap(
                [
                    ['form', $form],
                    ['layout', $layout],
                    ['floating', $floating],
                    ['show-required-mark', false],
                    ['label_attributes', $labelAttributes],
                ],
            );
        $element->expects(self::once())
            ->method('getAttributes')
            ->willReturn($attributes);
        $element->expects(self::once())
            ->method('getLabel')
            ->willReturn($label);
        $element->expects(self::exactly(2))
            ->method('getLabelOption')
            ->willReturnMap(
                [
                    ['disable_html_escape', $disableEscape],
                    ['always_wrap', $wrap],
                ],
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
