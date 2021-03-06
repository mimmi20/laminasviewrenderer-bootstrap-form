<?php
/**
 * This file is part of the mimmi20/laminasviewrenderer-bootstrap-form package.
 *
 * Copyright (c) 2021, Thomas Mueller <mimmi20@live.de>
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
use Laminas\Form\FieldsetInterface;
use Laminas\Form\Form;
use Laminas\I18n\View\Helper\Translate;
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

use function assert;
use function sprintf;

use const PHP_EOL;

final class FormCollectionTest extends TestCase
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
    public function testRenderWithWrongElement(): void
    {
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
        $escapeHtml->expects(self::never())
            ->method('__invoke');

        $htmlElement = $this->getMockBuilder(HtmlElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $htmlElement->expects(self::never())
            ->method('toHtml');

        $helper = new FormCollection($formRow, $escapeHtml, $htmlElement, null);

        $element = $this->getMockBuilder(Text::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::never())
            ->method('getName');
        $element->expects(self::never())
            ->method('getOption');
        $element->expects(self::never())
            ->method('getAttributes');
        $element->expects(self::never())
            ->method('getLabel');
        $element->expects(self::never())
            ->method('getLabelOption');
        $element->expects(self::never())
            ->method('hasAttribute');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            sprintf(
                '%s requires that the element is of type %s',
                'Mimmi20\LaminasView\BootstrapForm\FormCollection::render',
                FieldsetInterface::class
            )
        );
        $this->expectExceptionCode(0);

        $helper->render($element);
    }

    /**
     * @throws Exception
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testSetGetIndent1(): void
    {
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
        $escapeHtml->expects(self::never())
            ->method('__invoke');

        $htmlElement = $this->getMockBuilder(HtmlElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $htmlElement->expects(self::never())
            ->method('toHtml');

        $helper = new FormCollection($formRow, $escapeHtml, $htmlElement, null);

        self::assertSame($helper, $helper->setIndent(4));
        self::assertSame('    ', $helper->getIndent());
    }

    /**
     * @throws Exception
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testSetGetIndent2(): void
    {
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
        $escapeHtml->expects(self::never())
            ->method('__invoke');

        $htmlElement = $this->getMockBuilder(HtmlElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $htmlElement->expects(self::never())
            ->method('toHtml');

        $helper = new FormCollection($formRow, $escapeHtml, $htmlElement, null);

        self::assertSame($helper, $helper->setIndent('  '));
        self::assertSame('  ', $helper->getIndent());
    }

    /**
     * @throws Exception
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testSetShouldWrap(): void
    {
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
        $escapeHtml->expects(self::never())
            ->method('__invoke');

        $htmlElement = $this->getMockBuilder(HtmlElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $htmlElement->expects(self::never())
            ->method('toHtml');

        $helper = new FormCollection($formRow, $escapeHtml, $htmlElement, null);

        self::assertTrue($helper->shouldWrap());

        self::assertSame($helper, $helper->setShouldWrap(false));
        self::assertFalse($helper->shouldWrap());

        self::assertSame($helper, $helper->setShouldWrap(true));
        self::assertTrue($helper->shouldWrap());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws InvalidServiceException
     * @throws ServiceNotFoundException
     * @throws \Laminas\View\Exception\InvalidArgumentException
     * @throws RuntimeException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
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
            ->withConsecutive(['legend', ['class' => ''], sprintf('<span>%s</span>', $labelEscaped)], ['fieldset', [], PHP_EOL . '    ' . $expectedLegend . PHP_EOL])
            ->willReturnOnConsecutiveCalls($expectedLegend, $expectedFieldset);

        $helper = new FormCollection($formRow, $escapeHtml, $htmlElement, null);

        $list = new PriorityList();

        $element = $this->getMockBuilder(Form::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::never())
            ->method('getName');
        $element->expects(self::exactly(4))
            ->method('getOption')
            ->withConsecutive(['form'], ['layout'], ['floating'], ['label_attributes'])
            ->willReturnOnConsecutiveCalls($form, $layout, $floating, $labelAttributes);
        $element->expects(self::once())
            ->method('getAttributes')
            ->willReturn($attributes);
        $element->expects(self::once())
            ->method('getLabel')
            ->willReturn($label);
        $element->expects(self::exactly(2))
            ->method('getLabelOption')
            ->withConsecutive(['disable_html_escape'], ['always_wrap'])
            ->willReturnOnConsecutiveCalls($disableEscape, $wrap);
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
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
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
            ->withConsecutive([$buttonElement], [$textElement])
            ->willReturnOnConsecutiveCalls($expectedButton, $expectedText);

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::exactly(2))
            ->method('__invoke')
            ->withConsecutive([$innerLabel, 0], [$label, 0])
            ->willReturnOnConsecutiveCalls($innerLabelEscaped, $labelEscaped);

        $htmlElement = $this->getMockBuilder(HtmlElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $htmlElement->expects(self::exactly(4))
            ->method('toHtml')
            ->withConsecutive(['legend', ['class' => ''], sprintf('<span>%s</span>', $innerLabelEscaped)], ['fieldset', [], PHP_EOL . '        ' . $expectedInnerLegend . PHP_EOL . '    '], ['legend', ['class' => ''], sprintf('<span>%s</span>', $labelEscaped)], ['fieldset', [], PHP_EOL . '    ' . $expectedLegend . PHP_EOL . '    ' . $expectedInnerFieldset . PHP_EOL . $expectedButton . PHP_EOL . $expectedText . PHP_EOL])
            ->willReturnOnConsecutiveCalls($expectedInnerLegend, $expectedInnerFieldset, $expectedLegend, $expectedFieldset);

        $helper = new FormCollection($formRow, $escapeHtml, $htmlElement, null);

        $innerList = new PriorityList();

        $collectionElement = $this->getMockBuilder(Collection::class)
            ->disableOriginalConstructor()
            ->getMock();
        $collectionElement->expects(self::exactly(4))
            ->method('getOption')
            ->withConsecutive(['form'], ['layout'], ['floating'], ['label_attributes'])
            ->willReturnOnConsecutiveCalls($form, $layout, $floating, []);
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
            ->withConsecutive(['form'], ['layout'], ['floating'], ['show-required-mark'], ['show-required-mark'], ['show-required-mark'], ['label_attributes'])
            ->willReturnOnConsecutiveCalls($form, $layout, $floating, false, false, false, $labelAttributes);
        $element->expects(self::once())
            ->method('getAttributes')
            ->willReturn($attributes);
        $element->expects(self::once())
            ->method('getLabel')
            ->willReturn($label);
        $element->expects(self::exactly(2))
            ->method('getLabelOption')
            ->withConsecutive(['disable_html_escape'], ['always_wrap'])
            ->willReturnOnConsecutiveCalls($disableEscape, $wrap);
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
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
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
            ->withConsecutive([$buttonElement], [$textElement])
            ->willReturnOnConsecutiveCalls($expectedButton, $expectedText);

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::exactly(2))
            ->method('__invoke')
            ->withConsecutive([$innerLabel, 0], [$label, 0])
            ->willReturnOnConsecutiveCalls($innerLabelEscaped, $labelEscaped);

        $htmlElement = $this->getMockBuilder(HtmlElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $htmlElement->expects(self::exactly(4))
            ->method('toHtml')
            ->withConsecutive(['legend', ['class' => ''], sprintf('<span>%s</span>', $innerLabelEscaped)], ['fieldset', [], PHP_EOL . '        ' . $expectedInnerLegend . PHP_EOL . '    '], ['legend', ['class' => ''], sprintf('<span>%s</span>', $labelEscaped)], ['fieldset', [], PHP_EOL . '    ' . $expectedLegend . PHP_EOL . '    ' . $expectedInnerFieldset . PHP_EOL . $expectedButton . PHP_EOL . $expectedText . PHP_EOL])
            ->willReturnOnConsecutiveCalls($expectedInnerLegend, $expectedInnerFieldset, $expectedLegend, $expectedFieldset);

        $helper = new FormCollection($formRow, $escapeHtml, $htmlElement, null);

        $innerList = new PriorityList();

        $collectionElement = $this->getMockBuilder(Collection::class)
            ->disableOriginalConstructor()
            ->getMock();
        $collectionElement->expects(self::exactly(4))
            ->method('getOption')
            ->withConsecutive(['form'], ['layout'], ['floating'], ['label_attributes'])
            ->willReturnOnConsecutiveCalls($form, $layout, $floating, []);
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
            ->withConsecutive(['form'], ['layout'], ['floating'], ['show-required-mark'], ['show-required-mark'], ['show-required-mark'], ['label_attributes'])
            ->willReturnOnConsecutiveCalls($form, $layout, $floating, false, false, false, $labelAttributes);
        $element->expects(self::once())
            ->method('getAttributes')
            ->willReturn($attributes);
        $element->expects(self::once())
            ->method('getLabel')
            ->willReturn($label);
        $element->expects(self::exactly(2))
            ->method('getLabelOption')
            ->withConsecutive(['disable_html_escape'], ['always_wrap'])
            ->willReturnOnConsecutiveCalls($disableEscape, $wrap);
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
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
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
            ->withConsecutive(['form'], ['layout'])
            ->willReturnOnConsecutiveCalls($form, $layout);
        $textElement->expects(self::once())
            ->method('setOption')
            ->with('floating', true);

        $buttonElement = $this->getMockBuilder(Button::class)
            ->disableOriginalConstructor()
            ->getMock();
        $buttonElement->expects(self::exactly(2))
            ->method('getOption')
            ->withConsecutive(['form'], ['layout'])
            ->willReturnOnConsecutiveCalls($form, $layout);
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
            ->withConsecutive([$buttonElement], [$textElement])
            ->willReturnOnConsecutiveCalls($expectedButton, $expectedText);

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
            ->withConsecutive(['legend', ['class' => ''], sprintf('<span>%s</span>', $innerLabel)], ['fieldset', [], PHP_EOL . $indent . '        ' . $expectedInnerLegend . PHP_EOL . $indent . '    '], ['legend', ['class' => ''], $label], ['fieldset', [], PHP_EOL . $indent . '    ' . $expectedLegend . PHP_EOL . $indent . '    ' . $expectedInnerFieldset . PHP_EOL . $expectedButton . PHP_EOL . $expectedText . PHP_EOL . $indent])
            ->willReturnOnConsecutiveCalls($expectedInnerLegend, $expectedInnerFieldset, $expectedLegend, $expectedFieldset);

        $helper = new FormCollection($formRow, $escapeHtml, $htmlElement, null);

        $innerList = new PriorityList();

        $collectionElement = $this->getMockBuilder(Collection::class)
            ->disableOriginalConstructor()
            ->getMock();
        $collectionElement->expects(self::exactly(6))
            ->method('getOption')
            ->withConsecutive(['form'], ['layout'], ['form'], ['layout'], ['floating'], ['label_attributes'])
            ->willReturnOnConsecutiveCalls($form, $layout, $form, $layout, $floating, []);
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
            ->withConsecutive(['form'], ['layout'], ['floating'], ['show-required-mark'], ['show-required-mark'], ['show-required-mark'], ['label_attributes'])
            ->willReturnOnConsecutiveCalls($form, $layout, $floating, false, false, false, $labelAttributes);
        $element->expects(self::once())
            ->method('getAttributes')
            ->willReturn($attributes);
        $element->expects(self::once())
            ->method('getLabel')
            ->willReturn($label);
        $element->expects(self::exactly(2))
            ->method('getLabelOption')
            ->withConsecutive(['disable_html_escape'], ['always_wrap'])
            ->willReturnOnConsecutiveCalls($disableEscape, $wrap);
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
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
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
            ->withConsecutive(['form'], ['layout'])
            ->willReturnOnConsecutiveCalls($form, $layout);
        $textElement->expects(self::once())
            ->method('setOption')
            ->with('floating', true);

        $buttonElement = $this->getMockBuilder(Button::class)
            ->disableOriginalConstructor()
            ->getMock();
        $buttonElement->expects(self::exactly(2))
            ->method('getOption')
            ->withConsecutive(['form'], ['layout'])
            ->willReturnOnConsecutiveCalls($form, $layout);
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
            ->withConsecutive([$buttonElement], [$textElement])
            ->willReturnOnConsecutiveCalls($expectedButton, $expectedText);

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
            ->withConsecutive(['legend', ['class' => ''], sprintf('<span>%s</span>', $innerLabel)], ['fieldset', [], PHP_EOL . $indent . '        ' . $expectedInnerLegend . PHP_EOL . $indent . '    '], ['legend', ['class' => ''], $label], ['fieldset', [], PHP_EOL . $indent . '    ' . $expectedLegend . PHP_EOL . $indent . '    ' . $expectedInnerFieldset . PHP_EOL . $expectedButton . PHP_EOL . $expectedText . PHP_EOL . $indent])
            ->willReturnOnConsecutiveCalls($expectedInnerLegend, $expectedInnerFieldset, $expectedLegend, $expectedFieldset);

        $helper = new FormCollection($formRow, $escapeHtml, $htmlElement, null);

        $innerList = new PriorityList();

        $collectionElement = $this->getMockBuilder(Collection::class)
            ->disableOriginalConstructor()
            ->getMock();
        $collectionElement->expects(self::exactly(6))
            ->method('getOption')
            ->withConsecutive(['form'], ['layout'], ['form'], ['layout'], ['floating'], ['label_attributes'])
            ->willReturnOnConsecutiveCalls($form, $layout, $form, $layout, $floating, []);
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
            ->withConsecutive(['form'], ['layout'], ['floating'], ['show-required-mark'], ['show-required-mark'], ['show-required-mark'], ['label_attributes'])
            ->willReturnOnConsecutiveCalls($form, $layout, $floating, false, false, false, $labelAttributes);
        $element->expects(self::once())
            ->method('getAttributes')
            ->willReturn($attributes);
        $element->expects(self::once())
            ->method('getLabel')
            ->willReturn($label);
        $element->expects(self::exactly(2))
            ->method('getLabelOption')
            ->withConsecutive(['disable_html_escape'], ['always_wrap'])
            ->willReturnOnConsecutiveCalls($disableEscape, $wrap);
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
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testRenderWithCollectionAndElementsAndOptionsAndTranslator(): void
    {
        $form            = 'test-form';
        $layout          = \Mimmi20\LaminasView\BootstrapForm\Form::LAYOUT_HORIZONTAL;
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

        $textElement = $this->getMockBuilder(Text::class)
            ->disableOriginalConstructor()
            ->getMock();
        $textElement->expects(self::exactly(2))
            ->method('getOption')
            ->withConsecutive(['form'], ['layout'])
            ->willReturnOnConsecutiveCalls($form, $layout);
        $textElement->expects(self::once())
            ->method('setOption')
            ->with('floating', true);

        $buttonElement = $this->getMockBuilder(Button::class)
            ->disableOriginalConstructor()
            ->getMock();
        $buttonElement->expects(self::exactly(2))
            ->method('getOption')
            ->withConsecutive(['form'], ['layout'])
            ->willReturnOnConsecutiveCalls($form, $layout);
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
            ->withConsecutive([$buttonElement], [$textElement])
            ->willReturnOnConsecutiveCalls($expectedButton, $expectedText);

        $translator = $this->getMockBuilder(Translate::class)
            ->disableOriginalConstructor()
            ->getMock();
        $translator->expects(self::exactly(2))
            ->method('__invoke')
            ->withConsecutive([$innerLabel, $textDomain, null], [$label, $textDomain, null])
            ->willReturnOnConsecutiveCalls($innerLabelTranslated, $labelTranslated);

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
            ->withConsecutive(['legend', ['class' => ''], sprintf('<span>%s</span>', $innerLabelTranslated)], ['fieldset', [], PHP_EOL . $indent . '        ' . $expectedInnerLegend . PHP_EOL . $indent . '    '], ['legend', ['class' => ''], $labelTranslated], ['fieldset', [], PHP_EOL . $indent . '    ' . $expectedLegend . PHP_EOL . $indent . '    ' . $expectedInnerFieldset . PHP_EOL . $expectedButton . PHP_EOL . $expectedText . PHP_EOL . $indent])
            ->willReturnOnConsecutiveCalls($expectedInnerLegend, $expectedInnerFieldset, $expectedLegend, $expectedFieldset);

        $helper = new FormCollection($formRow, $escapeHtml, $htmlElement, $translator);

        $innerList = new PriorityList();

        $collectionElement = $this->getMockBuilder(Collection::class)
            ->disableOriginalConstructor()
            ->getMock();
        $collectionElement->expects(self::exactly(6))
            ->method('getOption')
            ->withConsecutive(['form'], ['layout'], ['form'], ['layout'], ['floating'], ['label_attributes'])
            ->willReturnOnConsecutiveCalls($form, $layout, $form, $layout, $floating, []);
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
            ->withConsecutive(['form'], ['layout'], ['floating'], ['show-required-mark'], ['show-required-mark'], ['show-required-mark'], ['label_attributes'])
            ->willReturnOnConsecutiveCalls($form, $layout, $floating, false, false, false, $labelAttributes);
        $element->expects(self::once())
            ->method('getAttributes')
            ->willReturn($attributes);
        $element->expects(self::once())
            ->method('getLabel')
            ->willReturn($label);
        $element->expects(self::exactly(2))
            ->method('getLabelOption')
            ->withConsecutive(['disable_html_escape'], ['always_wrap'])
            ->willReturnOnConsecutiveCalls($disableEscape, $wrap);
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
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testRenderWithCollectionAndElementsAndOptionsAndTranslator2(): void
    {
        $form                   = 'test-form';
        $layout                 = \Mimmi20\LaminasView\BootstrapForm\Form::LAYOUT_HORIZONTAL;
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

        $textElement = $this->getMockBuilder(Text::class)
            ->disableOriginalConstructor()
            ->getMock();
        $textElement->expects(self::exactly(2))
            ->method('getOption')
            ->withConsecutive(['form'], ['layout'])
            ->willReturnOnConsecutiveCalls($form, $layout);
        $textElement->expects(self::once())
            ->method('setOption')
            ->with('floating', true);

        $buttonElement = $this->getMockBuilder(Button::class)
            ->disableOriginalConstructor()
            ->getMock();
        $buttonElement->expects(self::exactly(2))
            ->method('getOption')
            ->withConsecutive(['form'], ['layout'])
            ->willReturnOnConsecutiveCalls($form, $layout);
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
            ->withConsecutive([$buttonElement], [$textElement])
            ->willReturnOnConsecutiveCalls($expectedButton, $expectedText);

        $translator = $this->getMockBuilder(Translate::class)
            ->disableOriginalConstructor()
            ->getMock();
        $translator->expects(self::exactly(2))
            ->method('__invoke')
            ->withConsecutive([$innerLabel, $textDomain, null], [$label, $textDomain, null])
            ->willReturnOnConsecutiveCalls($innerLabelTranslated, $labelTranslated);

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::exactly(2))
            ->method('__invoke')
            ->withConsecutive([$innerLabelTranslated, 0], [$labelTranslated, 0])
            ->willReturnOnConsecutiveCalls($innerLabelTranslatedEscaped, $labelTranslatedEscaped);

        $htmlElement = $this->getMockBuilder(HtmlElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $htmlElement->expects(self::exactly(4))
            ->method('toHtml')
            ->withConsecutive(['legend', ['class' => ''], sprintf('<span>%s</span>', $innerLabelTranslatedEscaped)], ['fieldset', [], PHP_EOL . $indent . '        ' . $expectedInnerLegend . PHP_EOL . $indent . '    '], ['legend', ['class' => ''], $labelTranslatedEscaped], ['fieldset', [], PHP_EOL . $indent . '    ' . $expectedLegend . PHP_EOL . $indent . '    ' . $expectedInnerFieldset . PHP_EOL . $expectedButton . PHP_EOL . $expectedText . PHP_EOL . $indent])
            ->willReturnOnConsecutiveCalls($expectedInnerLegend, $expectedInnerFieldset, $expectedLegend, $expectedFieldset);

        $helper = new FormCollection($formRow, $escapeHtml, $htmlElement, $translator);

        $innerList = new PriorityList();

        $collectionElement = $this->getMockBuilder(Collection::class)
            ->disableOriginalConstructor()
            ->getMock();
        $collectionElement->expects(self::exactly(6))
            ->method('getOption')
            ->withConsecutive(['form'], ['layout'], ['form'], ['layout'], ['floating'], ['label_attributes'])
            ->willReturnOnConsecutiveCalls($form, $layout, $form, $layout, $floating, []);
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
            ->withConsecutive(['form'], ['layout'], ['floating'], ['show-required-mark'], ['show-required-mark'], ['show-required-mark'], ['label_attributes'])
            ->willReturnOnConsecutiveCalls($form, $layout, $floating, false, false, false, $labelAttributes);
        $element->expects(self::once())
            ->method('getAttributes')
            ->willReturn($attributes);
        $element->expects(self::once())
            ->method('getLabel')
            ->willReturn($label);
        $element->expects(self::exactly(2))
            ->method('getLabelOption')
            ->withConsecutive(['disable_html_escape'], ['always_wrap'])
            ->willReturnOnConsecutiveCalls($disableEscape, $wrap);
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
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testRenderWithCollectionAndElementsAndOptionsAndTranslator3(): void
    {
        $form       = 'test-form';
        $layout     = \Mimmi20\LaminasView\BootstrapForm\Form::LAYOUT_HORIZONTAL;
        $floating   = true;
        $indent     = '<!-- -->  ';
        $textDomain = 'test-domain';

        $textElement = $this->getMockBuilder(Text::class)
            ->disableOriginalConstructor()
            ->getMock();
        $textElement->expects(self::exactly(2))
            ->method('getOption')
            ->withConsecutive(['form'], ['layout'])
            ->willReturnOnConsecutiveCalls($form, $layout);
        $textElement->expects(self::once())
            ->method('setOption')
            ->with('floating', true);

        $buttonElement = $this->getMockBuilder(Button::class)
            ->disableOriginalConstructor()
            ->getMock();
        $buttonElement->expects(self::exactly(2))
            ->method('getOption')
            ->withConsecutive(['form'], ['layout'])
            ->willReturnOnConsecutiveCalls($form, $layout);
        $buttonElement->expects(self::once())
            ->method('setOption')
            ->with('floating', true);

        $expectedButton = $indent . '    <button></button>';
        $expectedText   = $indent . '    <text></text>';
        $expected       = PHP_EOL . $expectedButton . PHP_EOL . $expectedText . PHP_EOL;

        $formRow = $this->getMockBuilder(FormRowInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formRow->expects(self::exactly(2))
            ->method('setIndent')
            ->with($indent . '    ');
        $formRow->expects(self::exactly(2))
            ->method('render')
            ->withConsecutive([$buttonElement], [$textElement])
            ->willReturnOnConsecutiveCalls($expectedButton, $expectedText);

        $translator = $this->getMockBuilder(Translate::class)
            ->disableOriginalConstructor()
            ->getMock();
        $translator->expects(self::never())
            ->method('__invoke');

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::never())
            ->method('__invoke');

        $htmlElement = $this->getMockBuilder(HtmlElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $htmlElement->expects(self::never())
            ->method('toHtml');

        $helper = new FormCollection($formRow, $escapeHtml, $htmlElement, $translator);

        $innerList = new PriorityList();

        $collectionElement = $this->getMockBuilder(Collection::class)
            ->disableOriginalConstructor()
            ->getMock();
        $collectionElement->expects(self::exactly(5))
            ->method('getOption')
            ->withConsecutive(['form'], ['layout'], ['form'], ['layout'], ['floating'])
            ->willReturnOnConsecutiveCalls($form, $layout, $form, $layout, $floating);
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

        $element = $this->getMockBuilder(Collection::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::never())
            ->method('getName');
        $element->expects(self::exactly(6))
            ->method('getOption')
            ->withConsecutive(['form'], ['layout'], ['floating'], ['show-required-mark'], ['show-required-mark'], ['show-required-mark'])
            ->willReturnOnConsecutiveCalls($form, $layout, $floating, false, false, false);
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
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testRenderWithCollectionAndElementsAndOptionsAndTranslator4(): void
    {
        $form       = 'test-form';
        $layout     = \Mimmi20\LaminasView\BootstrapForm\Form::LAYOUT_HORIZONTAL;
        $floating   = true;
        $indent     = '<!-- -->  ';
        $textDomain = 'test-domain';

        $textElement = $this->getMockBuilder(Text::class)
            ->disableOriginalConstructor()
            ->getMock();
        $textElement->expects(self::exactly(2))
            ->method('getOption')
            ->withConsecutive(['form'], ['layout'])
            ->willReturnOnConsecutiveCalls($form, $layout);
        $textElement->expects(self::once())
            ->method('setOption')
            ->with('floating', true);

        $buttonElement = $this->getMockBuilder(Button::class)
            ->disableOriginalConstructor()
            ->getMock();
        $buttonElement->expects(self::exactly(2))
            ->method('getOption')
            ->withConsecutive(['form'], ['layout'])
            ->willReturnOnConsecutiveCalls($form, $layout);
        $buttonElement->expects(self::once())
            ->method('setOption')
            ->with('floating', true);

        $expectedButton = $indent . '    <button></button>';
        $expectedText   = $indent . '    <text></text>';
        $expected       = PHP_EOL . $expectedButton . PHP_EOL . $expectedText . PHP_EOL;

        $formRow = $this->getMockBuilder(FormRowInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formRow->expects(self::exactly(2))
            ->method('setIndent')
            ->with($indent . '    ');
        $formRow->expects(self::exactly(2))
            ->method('render')
            ->withConsecutive([$buttonElement], [$textElement])
            ->willReturnOnConsecutiveCalls($expectedButton, $expectedText);

        $translator = $this->getMockBuilder(Translate::class)
            ->disableOriginalConstructor()
            ->getMock();
        $translator->expects(self::never())
            ->method('__invoke');

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::never())
            ->method('__invoke');

        $htmlElement = $this->getMockBuilder(HtmlElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $htmlElement->expects(self::never())
            ->method('toHtml');

        $helper = new FormCollection($formRow, $escapeHtml, $htmlElement, $translator);

        $innerList = new PriorityList();

        $collectionElement = $this->getMockBuilder(Collection::class)
            ->disableOriginalConstructor()
            ->getMock();
        $collectionElement->expects(self::exactly(5))
            ->method('getOption')
            ->withConsecutive(['form'], ['layout'], ['form'], ['layout'], ['floating'])
            ->willReturnOnConsecutiveCalls($form, $layout, $form, $layout, $floating);
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

        $element = $this->getMockBuilder(Collection::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::never())
            ->method('getName');
        $element->expects(self::exactly(6))
            ->method('getOption')
            ->withConsecutive(['form'], ['layout'], ['floating'], ['show-required-mark'], ['show-required-mark'], ['show-required-mark'])
            ->willReturnOnConsecutiveCalls($form, $layout, $floating, false, false, false);
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
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testRenderWithCollectionAndElementsAndOptionsAndTranslator5(): void
    {
        $form               = 'test-form';
        $layout             = \Mimmi20\LaminasView\BootstrapForm\Form::LAYOUT_HORIZONTAL;
        $floating           = true;
        $indent             = '<!-- -->  ';
        $textDomain         = 'test-domain';
        $templateAttributes = ['class' => 'template-class'];

        $textElement = $this->getMockBuilder(Text::class)
            ->disableOriginalConstructor()
            ->getMock();
        $textElement->expects(self::exactly(2))
            ->method('getOption')
            ->withConsecutive(['form'], ['layout'])
            ->willReturnOnConsecutiveCalls($form, $layout);
        $textElement->expects(self::once())
            ->method('setOption')
            ->with('floating', true);

        $templateElement = $this->getMockBuilder(Text::class)
            ->disableOriginalConstructor()
            ->getMock();
        $templateElement->expects(self::never())
            ->method('getOption');
        $templateElement->expects(self::never())
            ->method('setOption');

        $buttonElement = $this->getMockBuilder(Button::class)
            ->disableOriginalConstructor()
            ->getMock();
        $buttonElement->expects(self::exactly(2))
            ->method('getOption')
            ->withConsecutive(['form'], ['layout'])
            ->willReturnOnConsecutiveCalls($form, $layout);
        $buttonElement->expects(self::once())
            ->method('setOption')
            ->with('floating', true);

        $expectedButton   = $indent . '    <button></button>';
        $expectedText     = $indent . '    <text></text>';
        $expectedTemplate = $indent . '    <template></template>';
        $renderedTemplate = '<template>template-content</template>';

        $expected = PHP_EOL . $expectedButton . PHP_EOL . $expectedText . PHP_EOL . $indent . '    ' . $renderedTemplate . PHP_EOL;

        $formRow = $this->getMockBuilder(FormRowInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formRow->expects(self::exactly(3))
            ->method('setIndent')
            ->with($indent . '    ');
        $formRow->expects(self::exactly(3))
            ->method('render')
            ->withConsecutive([$templateElement], [$buttonElement], [$textElement])
            ->willReturnOnConsecutiveCalls($expectedTemplate, $expectedButton, $expectedText);

        $translator = $this->getMockBuilder(Translate::class)
            ->disableOriginalConstructor()
            ->getMock();
        $translator->expects(self::never())
            ->method('__invoke');

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::never())
            ->method('__invoke');

        $htmlElement = $this->getMockBuilder(HtmlElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $htmlElement->expects(self::once())
            ->method('toHtml')
            ->with('template', ['class' => 'template-class'], $expectedTemplate . PHP_EOL . $indent)
            ->willReturn($renderedTemplate);

        $helper = new FormCollection($formRow, $escapeHtml, $htmlElement, $translator);

        $innerList = new PriorityList();

        $collectionElement = $this->getMockBuilder(Collection::class)
            ->disableOriginalConstructor()
            ->getMock();
        $collectionElement->expects(self::exactly(5))
            ->method('getOption')
            ->withConsecutive(['form'], ['layout'], ['form'], ['layout'], ['floating'])
            ->willReturnOnConsecutiveCalls($form, $layout, $form, $layout, $floating);
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

        $element = $this->getMockBuilder(Collection::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::never())
            ->method('getName');
        $element->expects(self::exactly(7))
            ->method('getOption')
            ->withConsecutive(['template_attributes'], ['form'], ['layout'], ['floating'], ['show-required-mark'], ['show-required-mark'], ['show-required-mark'])
            ->willReturnOnConsecutiveCalls($templateAttributes, $form, $layout, $floating, false, false, false);
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

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws InvalidServiceException
     * @throws ServiceNotFoundException
     * @throws \Laminas\View\Exception\InvalidArgumentException
     * @throws RuntimeException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testRenderWithCollectionAndElementsAndOptionsAndTranslator6(): void
    {
        $form               = 'test-form';
        $layout             = \Mimmi20\LaminasView\BootstrapForm\Form::LAYOUT_HORIZONTAL;
        $floating           = true;
        $indent             = '<!-- -->  ';
        $textDomain         = 'test-domain';
        $templateAttributes = ['class' => 'template-class'];

        $textElement = $this->getMockBuilder(Text::class)
            ->disableOriginalConstructor()
            ->getMock();
        $textElement->expects(self::exactly(2))
            ->method('getOption')
            ->withConsecutive(['form'], ['layout'])
            ->willReturnOnConsecutiveCalls($form, $layout);
        $textElement->expects(self::once())
            ->method('setOption')
            ->with('floating', true);

        $templateList = new PriorityList();

        $templateElement = $this->getMockBuilder(Collection::class)
            ->disableOriginalConstructor()
            ->getMock();
        $templateElement->expects(self::exactly(3))
            ->method('getOption')
            ->withConsecutive(['form'], ['layout'], ['floating'])
            ->willReturnOnConsecutiveCalls($form, $layout, $floating);
        $templateElement->expects(self::never())
            ->method('setOption');
        $templateElement->expects(self::never())
            ->method('getAttributes');
        $templateElement->expects(self::never())
            ->method('getLabel');
        $templateElement->expects(self::never())
            ->method('getLabelOption');
        $templateElement->expects(self::never())
            ->method('hasAttribute');
        $templateElement->expects(self::once())
            ->method('getIterator')
            ->willReturn($templateList);
        $templateElement->expects(self::once())
            ->method('shouldCreateTemplate')
            ->willReturn(false);
        $templateElement->expects(self::never())
            ->method('getTemplateElement');

        $buttonElement = $this->getMockBuilder(Button::class)
            ->disableOriginalConstructor()
            ->getMock();
        $buttonElement->expects(self::exactly(2))
            ->method('getOption')
            ->withConsecutive(['form'], ['layout'])
            ->willReturnOnConsecutiveCalls($form, $layout);
        $buttonElement->expects(self::once())
            ->method('setOption')
            ->with('floating', true);

        $expectedButton   = $indent . '    <button></button>';
        $expectedText     = $indent . '    <text></text>';
        $renderedTemplate = '<template>template-content</template>';

        $expected = PHP_EOL . $expectedButton . PHP_EOL . $expectedText . PHP_EOL . $indent . '    ' . $renderedTemplate . PHP_EOL;

        $formRow = $this->getMockBuilder(FormRowInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formRow->expects(self::exactly(2))
            ->method('setIndent')
            ->with($indent . '    ');
        $formRow->expects(self::exactly(2))
            ->method('render')
            ->withConsecutive([$buttonElement], [$textElement])
            ->willReturnOnConsecutiveCalls($expectedButton, $expectedText);

        $translator = $this->getMockBuilder(Translate::class)
            ->disableOriginalConstructor()
            ->getMock();
        $translator->expects(self::never())
            ->method('__invoke');

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::never())
            ->method('__invoke');

        $htmlElement = $this->getMockBuilder(HtmlElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $htmlElement->expects(self::once())
            ->method('toHtml')
            ->with('template', ['class' => 'template-class'], PHP_EOL . $indent)
            ->willReturn($renderedTemplate);

        $helper = new FormCollection($formRow, $escapeHtml, $htmlElement, $translator);

        $innerList = new PriorityList();

        $collectionElement = $this->getMockBuilder(Collection::class)
            ->disableOriginalConstructor()
            ->getMock();
        $collectionElement->expects(self::exactly(5))
            ->method('getOption')
            ->withConsecutive(['form'], ['layout'], ['form'], ['layout'], ['floating'])
            ->willReturnOnConsecutiveCalls($form, $layout, $form, $layout, $floating);
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

        $element = $this->getMockBuilder(Collection::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::never())
            ->method('getName');
        $element->expects(self::exactly(7))
            ->method('getOption')
            ->withConsecutive(['template_attributes'], ['form'], ['layout'], ['floating'], ['show-required-mark'], ['show-required-mark'], ['show-required-mark'])
            ->willReturnOnConsecutiveCalls($templateAttributes, $form, $layout, $floating, false, false, false);
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

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws InvalidServiceException
     * @throws ServiceNotFoundException
     * @throws \Laminas\View\Exception\InvalidArgumentException
     * @throws RuntimeException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testRenderWithCollectionAndElementsAndOptionsAndTranslator7(): void
    {
        $form               = 'test-form';
        $layout             = \Mimmi20\LaminasView\BootstrapForm\Form::LAYOUT_HORIZONTAL;
        $floating           = true;
        $indent             = '<!-- -->  ';
        $textDomain         = 'test-domain';
        $templateAttributes = ['class' => 'template-class'];

        $textElement = $this->getMockBuilder(Text::class)
            ->disableOriginalConstructor()
            ->getMock();
        $textElement->expects(self::exactly(2))
            ->method('getOption')
            ->withConsecutive(['form'], ['layout'])
            ->willReturnOnConsecutiveCalls($form, $layout);
        $textElement->expects(self::once())
            ->method('setOption')
            ->with('floating', true);

        $templateList = new PriorityList();

        $templateElement = $this->getMockBuilder(Collection::class)
            ->disableOriginalConstructor()
            ->getMock();
        $templateElement->expects(self::exactly(3))
            ->method('getOption')
            ->withConsecutive(['form'], ['layout'], ['floating'])
            ->willReturnOnConsecutiveCalls($form, $layout, $floating);
        $templateElement->expects(self::never())
            ->method('setOption');
        $templateElement->expects(self::never())
            ->method('getAttributes');
        $templateElement->expects(self::never())
            ->method('getLabel');
        $templateElement->expects(self::never())
            ->method('getLabelOption');
        $templateElement->expects(self::never())
            ->method('hasAttribute');
        $templateElement->expects(self::once())
            ->method('getIterator')
            ->willReturn($templateList);
        $templateElement->expects(self::once())
            ->method('shouldCreateTemplate')
            ->willReturn(false);
        $templateElement->expects(self::never())
            ->method('getTemplateElement');

        $buttonElement = $this->getMockBuilder(Button::class)
            ->disableOriginalConstructor()
            ->getMock();
        $buttonElement->expects(self::exactly(2))
            ->method('getOption')
            ->withConsecutive(['form'], ['layout'])
            ->willReturnOnConsecutiveCalls($form, $layout);
        $buttonElement->expects(self::once())
            ->method('setOption')
            ->with('floating', true);

        $expectedButton   = $indent . '    <button></button>';
        $expectedText     = $indent . '    <text></text>';
        $renderedTemplate = '<template>template-content</template>';

        $expected = PHP_EOL . $expectedButton . PHP_EOL . $expectedText . PHP_EOL . $indent . '    ' . $renderedTemplate . PHP_EOL;

        $formRow = $this->getMockBuilder(FormRowInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formRow->expects(self::exactly(2))
            ->method('setIndent')
            ->with($indent . '    ');
        $formRow->expects(self::exactly(2))
            ->method('render')
            ->withConsecutive([$buttonElement], [$textElement])
            ->willReturnOnConsecutiveCalls($expectedButton, $expectedText);

        $translator = $this->getMockBuilder(Translate::class)
            ->disableOriginalConstructor()
            ->getMock();
        $translator->expects(self::never())
            ->method('__invoke');

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::never())
            ->method('__invoke');

        $htmlElement = $this->getMockBuilder(HtmlElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $htmlElement->expects(self::once())
            ->method('toHtml')
            ->with('template', ['class' => 'template-class'], PHP_EOL . $indent)
            ->willReturn($renderedTemplate);

        $helper = new FormCollection($formRow, $escapeHtml, $htmlElement, $translator);

        $innerList = new PriorityList();

        $collectionElement = $this->getMockBuilder(Collection::class)
            ->disableOriginalConstructor()
            ->getMock();
        $collectionElement->expects(self::exactly(5))
            ->method('getOption')
            ->withConsecutive(['form'], ['layout'], ['form'], ['layout'], ['floating'])
            ->willReturnOnConsecutiveCalls($form, $layout, $form, $layout, $floating);
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

        $element = $this->getMockBuilder(Collection::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::never())
            ->method('getName');
        $element->expects(self::exactly(7))
            ->method('getOption')
            ->withConsecutive(['template_attributes'], ['form'], ['layout'], ['floating'], ['show-required-mark'], ['show-required-mark'], ['show-required-mark'])
            ->willReturnOnConsecutiveCalls($templateAttributes, $form, $layout, $floating, false, false, false);
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

        $helperObject = $helper();

        assert($helperObject instanceof FormCollection);

        self::assertSame($expected, $helperObject->render($element));
    }

    /**
     * @throws Exception
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testRenderWithCollectionAndElementsAndOptionsAndTranslator8(): void
    {
        $form               = 'test-form';
        $layout             = \Mimmi20\LaminasView\BootstrapForm\Form::LAYOUT_HORIZONTAL;
        $floating           = true;
        $indent             = '<!-- -->  ';
        $textDomain         = 'test-domain';
        $templateAttributes = ['class' => 'template-class'];

        $textElement = $this->getMockBuilder(Text::class)
            ->disableOriginalConstructor()
            ->getMock();
        $textElement->expects(self::exactly(2))
            ->method('getOption')
            ->withConsecutive(['form'], ['layout'])
            ->willReturnOnConsecutiveCalls($form, $layout);
        $textElement->expects(self::once())
            ->method('setOption')
            ->with('floating', true);

        $templateList = new PriorityList();

        $templateElement = $this->getMockBuilder(Collection::class)
            ->disableOriginalConstructor()
            ->getMock();
        $templateElement->expects(self::exactly(3))
            ->method('getOption')
            ->withConsecutive(['form'], ['layout'], ['floating'])
            ->willReturnOnConsecutiveCalls($form, $layout, $floating);
        $templateElement->expects(self::never())
            ->method('setOption');
        $templateElement->expects(self::never())
            ->method('getAttributes');
        $templateElement->expects(self::never())
            ->method('getLabel');
        $templateElement->expects(self::never())
            ->method('getLabelOption');
        $templateElement->expects(self::never())
            ->method('hasAttribute');
        $templateElement->expects(self::once())
            ->method('getIterator')
            ->willReturn($templateList);
        $templateElement->expects(self::once())
            ->method('shouldCreateTemplate')
            ->willReturn(false);
        $templateElement->expects(self::never())
            ->method('getTemplateElement');

        $buttonElement = $this->getMockBuilder(Button::class)
            ->disableOriginalConstructor()
            ->getMock();
        $buttonElement->expects(self::exactly(2))
            ->method('getOption')
            ->withConsecutive(['form'], ['layout'])
            ->willReturnOnConsecutiveCalls($form, $layout);
        $buttonElement->expects(self::once())
            ->method('setOption')
            ->with('floating', true);

        $expectedButton   = $indent . '    <button></button>';
        $expectedText     = $indent . '    <text></text>';
        $renderedTemplate = '<template>template-content</template>';

        $expected = PHP_EOL . $expectedButton . PHP_EOL . $expectedText . PHP_EOL . $indent . '    ' . $renderedTemplate . PHP_EOL;

        $formRow = $this->getMockBuilder(FormRowInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formRow->expects(self::exactly(2))
            ->method('setIndent')
            ->with($indent . '    ');
        $formRow->expects(self::exactly(2))
            ->method('render')
            ->withConsecutive([$buttonElement], [$textElement])
            ->willReturnOnConsecutiveCalls($expectedButton, $expectedText);

        $translator = $this->getMockBuilder(Translate::class)
            ->disableOriginalConstructor()
            ->getMock();
        $translator->expects(self::never())
            ->method('__invoke');

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::never())
            ->method('__invoke');

        $htmlElement = $this->getMockBuilder(HtmlElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $htmlElement->expects(self::once())
            ->method('toHtml')
            ->with('template', ['class' => 'template-class'], PHP_EOL . $indent)
            ->willReturn($renderedTemplate);

        $helper = new FormCollection($formRow, $escapeHtml, $htmlElement, $translator);

        $innerList = new PriorityList();

        $collectionElement = $this->getMockBuilder(Collection::class)
            ->disableOriginalConstructor()
            ->getMock();
        $collectionElement->expects(self::exactly(5))
            ->method('getOption')
            ->withConsecutive(['form'], ['layout'], ['form'], ['layout'], ['floating'])
            ->willReturnOnConsecutiveCalls($form, $layout, $form, $layout, $floating);
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

        $element = $this->getMockBuilder(Collection::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::never())
            ->method('getName');
        $element->expects(self::exactly(7))
            ->method('getOption')
            ->withConsecutive(['template_attributes'], ['form'], ['layout'], ['floating'], ['show-required-mark'], ['show-required-mark'], ['show-required-mark'])
            ->willReturnOnConsecutiveCalls($templateAttributes, $form, $layout, $floating, false, false, false);
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

        self::assertSame($expected, $helper($element, false));
        self::assertFalse($helper->shouldWrap());
    }
}
