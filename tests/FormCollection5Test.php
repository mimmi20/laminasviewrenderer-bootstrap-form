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
use Laminas\I18n\View\Helper\Translate;
use Laminas\ServiceManager\Exception\InvalidServiceException;
use Laminas\ServiceManager\Exception\ServiceNotFoundException;
use Laminas\Stdlib\PriorityList;
use Laminas\View\Exception\RuntimeException;
use Laminas\View\Helper\EscapeHtml;
use Mimmi20\LaminasView\BootstrapForm\Form;
use Mimmi20\LaminasView\BootstrapForm\FormCollection;
use Mimmi20\LaminasView\BootstrapForm\FormRowInterface;
use Mimmi20\LaminasView\Helper\HtmlElement\Helper\HtmlElementInterface;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;

use function assert;

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
     */
    public function testRenderWithCollectionAndElementsAndOptionsAndTranslator4(): void
    {
        $form       = 'test-form';
        $layout     = Form::LAYOUT_HORIZONTAL;
        $floating   = true;
        $indent     = '<!-- -->  ';
        $textDomain = 'test-domain';

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
        $expected       = PHP_EOL . $expectedButton . PHP_EOL . $expectedText . PHP_EOL;

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
            ->willReturnMap(
                [
                    ['form', $form],
                    ['layout', $layout],
                    ['floating', $floating],
                ],
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

        $element = $this->getMockBuilder(Collection::class)
            ->disableOriginalConstructor()
            ->getMock();
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
     */
    public function testRenderWithCollectionAndElementsAndOptionsAndTranslator5(): void
    {
        $form               = 'test-form';
        $layout             = Form::LAYOUT_HORIZONTAL;
        $floating           = true;
        $indent             = '<!-- -->  ';
        $textDomain         = 'test-domain';
        $templateAttributes = ['class' => 'template-class'];

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
            ->willReturnMap(
                [
                    ['form', $form],
                    ['layout', $layout],
                ],
            );
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
            ->willReturnMap(
                [
                    [$templateElement, null, $expectedTemplate],
                    [$buttonElement, null, $expectedButton],
                    [$textElement, null, $expectedText],
                ],
            );

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
            ->willReturnMap(
                [
                    ['form', $form],
                    ['layout', $layout],
                    ['floating', $floating],
                ],
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

        $element = $this->getMockBuilder(Collection::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::never())
            ->method('getName');
        $element->expects(self::exactly(7))
            ->method('getOption')
            ->willReturnMap(
                [
                    ['template_attributes', $templateAttributes],
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
     */
    public function testRenderWithCollectionAndElementsAndOptionsAndTranslator6(): void
    {
        $form               = 'test-form';
        $layout             = Form::LAYOUT_HORIZONTAL;
        $floating           = true;
        $indent             = '<!-- -->  ';
        $textDomain         = 'test-domain';
        $templateAttributes = ['class' => 'template-class'];

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

        $templateList = new PriorityList();

        $templateElement = $this->getMockBuilder(Collection::class)
            ->disableOriginalConstructor()
            ->getMock();
        $templateElement->expects(self::exactly(3))
            ->method('getOption')
            ->willReturnMap(
                [
                    ['form', $form],
                    ['layout', $layout],
                    ['floating', $floating],
                ],
            );
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
            ->willReturnMap(
                [
                    ['form', $form],
                    ['layout', $layout],
                ],
            );
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
            ->willReturnMap(
                [
                    [$buttonElement, null, $expectedButton],
                    [$textElement, null, $expectedText],
                ],
            );

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
            ->willReturnMap(
                [
                    ['form', $form],
                    ['layout', $layout],
                    ['floating', $floating],
                ],
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

        $element = $this->getMockBuilder(Collection::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::never())
            ->method('getName');
        $element->expects(self::exactly(7))
            ->method('getOption')
            ->willReturnMap(
                [
                    ['template_attributes', $templateAttributes],
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
     */
    public function testRenderWithCollectionAndElementsAndOptionsAndTranslator7(): void
    {
        $form               = 'test-form';
        $layout             = Form::LAYOUT_HORIZONTAL;
        $floating           = true;
        $indent             = '<!-- -->  ';
        $textDomain         = 'test-domain';
        $templateAttributes = ['class' => 'template-class'];

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

        $templateList = new PriorityList();

        $templateElement = $this->getMockBuilder(Collection::class)
            ->disableOriginalConstructor()
            ->getMock();
        $templateElement->expects(self::exactly(3))
            ->method('getOption')
            ->willReturnMap(
                [
                    ['form', $form],
                    ['layout', $layout],
                    ['floating', $floating],
                ],
            );
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
            ->willReturnMap(
                [
                    ['form', $form],
                    ['layout', $layout],
                ],
            );
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
            ->willReturnMap(
                [
                    [$buttonElement, null, $expectedButton],
                    [$textElement, null, $expectedText],
                ],
            );

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
            ->willReturnMap(
                [
                    ['form', $form],
                    ['layout', $layout],
                    ['floating', $floating],
                ],
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

        $element = $this->getMockBuilder(Collection::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::never())
            ->method('getName');
        $element->expects(self::exactly(7))
            ->method('getOption')
            ->willReturnMap(
                [
                    ['template_attributes', $templateAttributes],
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
            ->willReturn($templateElement);

        $helper->setIndent($indent);
        $helper->setTranslatorTextDomain($textDomain);
        $helper->setShouldWrap(false);

        $helperObject = $helper();

        assert($helperObject instanceof FormCollection);

        self::assertSame($expected, $helperObject->render($element));
    }
}
