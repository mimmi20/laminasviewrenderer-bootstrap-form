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

use AssertionError;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\Radio;
use Laminas\Form\Element\Text;
use Laminas\Form\ElementInterface;
use Laminas\Form\Exception\DomainException;
use Laminas\I18n\View\Helper\Translate;
use Laminas\ServiceManager\Exception\InvalidServiceException;
use Laminas\ServiceManager\Exception\ServiceNotFoundException;
use Laminas\View\Helper\EscapeHtml;
use Laminas\View\Renderer\RendererInterface;
use Mimmi20\LaminasView\BootstrapForm\Form;
use Mimmi20\LaminasView\BootstrapForm\FormElementErrorsInterface;
use Mimmi20\LaminasView\BootstrapForm\FormElementInterface;
use Mimmi20\LaminasView\BootstrapForm\FormRow;
use Mimmi20\LaminasView\Helper\HtmlElement\Helper\HtmlElementInterface;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;

use const PHP_EOL;

final class FormRowTest extends TestCase
{
    /**
     * @throws Exception
     * @throws DomainException
     * @throws ServiceNotFoundException
     * @throws InvalidServiceException
     */
    public function testRenderWithWrongFormOption(): void
    {
        $formElement = $this->getMockBuilder(FormElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formElement->expects(self::never())
            ->method('setIndent');
        $formElement->expects(self::never())
            ->method('render');

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

        $helper = new FormRow($formElement, $formElementErrors, $htmlElement, $escapeHtml, $renderer);

        $element = $this->getMockBuilder(ElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::once())
            ->method('getOption')
            ->with('form')
            ->willReturn(true);
        $element->expects(self::never())
            ->method('getName');
        $element->expects(self::never())
            ->method('hasAttribute');
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

        $helper->render($element);
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws ServiceNotFoundException
     * @throws InvalidServiceException
     */
    public function testRenderHiddenWithoutFormOptionAndLabel(): void
    {
        $label        = '';
        $messages     = [];
        $type         = 'hidden';
        $indent       = '<!-- -->  ';
        $expected     = '<hidden></hidden>';
        $renderErrors = false;

        $element = $this->getMockBuilder(ElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::once())
            ->method('getOption')
            ->with('form')
            ->willReturn(null);
        $element->expects(self::never())
            ->method('getName');
        $element->expects(self::never())
            ->method('hasAttribute');
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

        $formElement = $this->getMockBuilder(FormElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formElement->expects(self::once())
            ->method('setIndent')
            ->with($indent);
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

        $helper = new FormRow($formElement, $formElementErrors, $htmlElement, $escapeHtml, $renderer);

        $helper->setIndent($indent);
        $helper->setRenderErrors($renderErrors);

        self::assertSame($expected, $helper->render($element));
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws ServiceNotFoundException
     * @throws InvalidServiceException
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

        $element = $this->getMockBuilder(ElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::once())
            ->method('getOption')
            ->with('form')
            ->willReturn(null);
        $element->expects(self::never())
            ->method('getName');
        $element->expects(self::never())
            ->method('hasAttribute');
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

        $formElement = $this->getMockBuilder(FormElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formElement->expects(self::never())
            ->method('setIndent');
        $formElement->expects(self::never())
            ->method('render');

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

        $helper = new FormRow($formElement, $formElementErrors, $htmlElement, $escapeHtml, $renderer);

        $helper->setIndent($indent);
        $helper->setRenderErrors($renderErrors);
        $helper->setPartial($partial);

        self::assertSame($expected, $helper->render($element));
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws ServiceNotFoundException
     * @throws InvalidServiceException
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

        $element = $this->getMockBuilder(ElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::once())
            ->method('getOption')
            ->with('form')
            ->willReturn(null);
        $element->expects(self::never())
            ->method('getName');
        $element->expects(self::once())
            ->method('hasAttribute')
            ->with('class')
            ->willReturn(false);
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

        $formElement = $this->getMockBuilder(FormElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formElement->expects(self::never())
            ->method('setIndent');
        $formElement->expects(self::never())
            ->method('render');

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

        $helper = new FormRow($formElement, $formElementErrors, $htmlElement, $escapeHtml, $renderer);

        $helper->setIndent($indent);
        $helper->setRenderErrors($renderErrors);
        $helper->setPartial($partial);

        self::assertSame($expected, $helper->render($element));
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws ServiceNotFoundException
     * @throws InvalidServiceException
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

        $element = $this->getMockBuilder(ElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::once())
            ->method('getOption')
            ->with('form')
            ->willReturn(null);
        $element->expects(self::never())
            ->method('getName');
        $element->expects(self::once())
            ->method('hasAttribute')
            ->with('class')
            ->willReturn(true);
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

        $formElement = $this->getMockBuilder(FormElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formElement->expects(self::never())
            ->method('setIndent');
        $formElement->expects(self::never())
            ->method('render');

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

        $helper = new FormRow($formElement, $formElementErrors, $htmlElement, $escapeHtml, $renderer);

        $helper->setIndent($indent);
        $helper->setRenderErrors($renderErrors);
        $helper->setPartial($partial);

        self::assertSame($expected, $helper->render($element));
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws ServiceNotFoundException
     * @throws InvalidServiceException
     */
    public function testRenderHiddenWithLabelWithoutFormOption(): void
    {
        $label        = 'test-label';
        $messages     = [];
        $type         = 'hidden';
        $indent       = '<!-- -->  ';
        $expected     = '<hidden></hidden>';
        $renderErrors = false;

        $element = $this->getMockBuilder(ElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::once())
            ->method('getOption')
            ->with('form')
            ->willReturn(null);
        $element->expects(self::never())
            ->method('getName');
        $element->expects(self::never())
            ->method('hasAttribute');
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

        $formElement = $this->getMockBuilder(FormElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formElement->expects(self::once())
            ->method('setIndent')
            ->with($indent);
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

        $helper = new FormRow($formElement, $formElementErrors, $htmlElement, $escapeHtml, $renderer);

        $helper->setIndent($indent);
        $helper->setRenderErrors($renderErrors);

        self::assertSame($expected, $helper->render($element));
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws ServiceNotFoundException
     * @throws InvalidServiceException
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

        $element = $this->getMockBuilder(ElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::once())
            ->method('getOption')
            ->with('form')
            ->willReturn(null);
        $element->expects(self::never())
            ->method('getName');
        $element->expects(self::never())
            ->method('hasAttribute');
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

        $formElement = $this->getMockBuilder(FormElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formElement->expects(self::never())
            ->method('setIndent');
        $formElement->expects(self::never())
            ->method('render');

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

        $helper = new FormRow($formElement, $formElementErrors, $htmlElement, $escapeHtml, $renderer);

        $helper->setIndent($indent);
        $helper->setRenderErrors($renderErrors);
        $helper->setPartial($partial);

        self::assertSame($expected, $helper->render($element));
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws ServiceNotFoundException
     * @throws InvalidServiceException
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

        $element = $this->getMockBuilder(ElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::once())
            ->method('getOption')
            ->with('form')
            ->willReturn(null);
        $element->expects(self::never())
            ->method('getName');
        $element->expects(self::once())
            ->method('hasAttribute')
            ->with('class')
            ->willReturn(false);
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

        $formElement = $this->getMockBuilder(FormElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formElement->expects(self::never())
            ->method('setIndent');
        $formElement->expects(self::never())
            ->method('render');

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

        $helper = new FormRow($formElement, $formElementErrors, $htmlElement, $escapeHtml, $renderer);

        $helper->setIndent($indent);
        $helper->setRenderErrors($renderErrors);
        $helper->setPartial($partial);

        self::assertSame($expected, $helper->render($element));
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws ServiceNotFoundException
     * @throws InvalidServiceException
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

        $element = $this->getMockBuilder(ElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::once())
            ->method('getOption')
            ->with('form')
            ->willReturn(null);
        $element->expects(self::never())
            ->method('getName');
        $element->expects(self::once())
            ->method('hasAttribute')
            ->with('class')
            ->willReturn(true);
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

        $formElement = $this->getMockBuilder(FormElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formElement->expects(self::never())
            ->method('setIndent');
        $formElement->expects(self::never())
            ->method('render');

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

        $helper = new FormRow($formElement, $formElementErrors, $htmlElement, $escapeHtml, $renderer);

        $helper->setIndent($indent);
        $helper->setRenderErrors($renderErrors);
        $helper->setPartial($partial);

        self::assertSame($expected, $helper->render($element));
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws ServiceNotFoundException
     * @throws InvalidServiceException
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

        $element = $this->getMockBuilder(ElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::once())
            ->method('getOption')
            ->with('form')
            ->willReturn(null);
        $element->expects(self::never())
            ->method('getName');
        $element->expects(self::never())
            ->method('hasAttribute');
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

        $formElement = $this->getMockBuilder(FormElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formElement->expects(self::once())
            ->method('setIndent')
            ->with($indent);
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
    public function testRenderPartialWithLabelAndTranslatorWithoutFormOption(): void
    {
        $label        = 'test-label';
        $messages     = [];
        $type         = 'hidden';
        $indent       = '<!-- -->  ';
        $expected     = '<hidden></hidden>';
        $partial      = 'test-partial';
        $renderErrors = false;
        $textDomain   = 'text-domain';

        $element = $this->getMockBuilder(ElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::once())
            ->method('getOption')
            ->with('form')
            ->willReturn(null);
        $element->expects(self::never())
            ->method('getName');
        $element->expects(self::never())
            ->method('hasAttribute');
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

        $formElement = $this->getMockBuilder(FormElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formElement->expects(self::never())
            ->method('setIndent');
        $formElement->expects(self::never())
            ->method('render');

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
        $helper->setPartial($partial);
        $helper->setTranslatorTextDomain($textDomain);

        self::assertSame($expected, $helper->render($element));
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws ServiceNotFoundException
     * @throws InvalidServiceException
     */
    public function testRenderPartialWithLabelAndTranslatorWithoutFormOption2(): void
    {
        $label        = 'test-label';
        $messages     = ['x' => 'y'];
        $type         = 'hidden';
        $indent       = '<!-- -->  ';
        $expected     = '<hidden></hidden>';
        $partial      = 'test-partial';
        $renderErrors = false;
        $textDomain   = 'text-domain';

        $element = $this->getMockBuilder(ElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::once())
            ->method('getOption')
            ->with('form')
            ->willReturn(null);
        $element->expects(self::never())
            ->method('getName');
        $element->expects(self::once())
            ->method('hasAttribute')
            ->with('class')
            ->willReturn(false);
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

        $formElement = $this->getMockBuilder(FormElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formElement->expects(self::never())
            ->method('setIndent');
        $formElement->expects(self::never())
            ->method('render');

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
        $helper->setPartial($partial);
        $helper->setTranslatorTextDomain($textDomain);

        self::assertSame($expected, $helper->render($element));
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws ServiceNotFoundException
     * @throws InvalidServiceException
     */
    public function testRenderPartialWithLabelAndTranslatorWithoutFormOption3(): void
    {
        $label        = 'test-label';
        $messages     = ['x' => 'y'];
        $type         = 'hidden';
        $class        = 'test-class';
        $indent       = '<!-- -->  ';
        $expected     = '<hidden></hidden>';
        $partial      = 'test-partial';
        $renderErrors = false;
        $textDomain   = 'text-domain';

        $element = $this->getMockBuilder(ElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::once())
            ->method('getOption')
            ->with('form')
            ->willReturn(null);
        $element->expects(self::never())
            ->method('getName');
        $element->expects(self::once())
            ->method('hasAttribute')
            ->with('class')
            ->willReturn(true);
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

        $formElement = $this->getMockBuilder(FormElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formElement->expects(self::never())
            ->method('setIndent');
        $formElement->expects(self::never())
            ->method('render');

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
        $helper->setPartial($partial);
        $helper->setTranslatorTextDomain($textDomain);

        self::assertSame($expected, $helper->render($element));
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws ServiceNotFoundException
     * @throws InvalidServiceException
     */
    public function testRenderTextWithoutFormOptionAndLabel(): void
    {
        $label            = '';
        $messages         = [];
        $type             = 'text';
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
        $element->expects(self::exactly(4))
            ->method('getOption')
            ->willReturnMap(
                [
                    ['form', $form],
                    ['show-required-mark', $showRequiredMark],
                    ['layout', $layout],
                    ['help_content', $helpContent],
                ],
            );
        $element->expects(self::never())
            ->method('getName');
        $element->expects(self::never())
            ->method('hasAttribute');
        $element->expects(self::never())
            ->method('setAttribute');
        $element->expects(self::exactly(2))
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
    public function testRenderTextWithoutFormOptionAndLabel4(): void
    {
        $label            = '';
        $messages         = [];
        $type             = 'text';
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
        $element->expects(self::exactly(4))
            ->method('getOption')
            ->willReturnMap(
                [
                    ['form', $form],
                    ['show-required-mark', $showRequiredMark],
                    ['layout', $layout],
                    ['help_content', $helpContent],
                ],
            );
        $element->expects(self::never())
            ->method('getName');
        $element->expects(self::never())
            ->method('hasAttribute');
        $element->expects(self::never())
            ->method('setAttribute');
        $element->expects(self::exactly(2))
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
    public function testRenderTextWithoutFormOptionAndLabel5(): void
    {
        $label            = '';
        $messages         = ['x' => 'y'];
        $type             = 'text';
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
        $element->expects(self::exactly(4))
            ->method('getOption')
            ->willReturnMap(
                [
                    ['form', $form],
                    ['show-required-mark', $showRequiredMark],
                    ['layout', $layout],
                    ['help_content', $helpContent],
                ],
            );
        $element->expects(self::never())
            ->method('getName');
        $element->expects(self::once())
            ->method('hasAttribute')
            ->with('class')
            ->willReturn(false);
        $element->expects(self::once())
            ->method('setAttribute')
            ->with('class', 'is-invalid');
        $element->expects(self::exactly(2))
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
        $element->expects(self::exactly(4))
            ->method('getOption')
            ->willReturnMap(
                [
                    ['form', $form],
                    ['show-required-mark', $showRequiredMark],
                    ['layout', $layout],
                    ['help_content', $helpContent],
                ],
            );
        $element->expects(self::never())
            ->method('getName');
        $element->expects(self::once())
            ->method('hasAttribute')
            ->with('class')
            ->willReturn(true);
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
        $element->expects(self::exactly(4))
            ->method('getOption')
            ->willReturnMap(
                [
                    ['form', $form],
                    ['show-required-mark', $showRequiredMark],
                    ['layout', $layout],
                    ['help_content', $helpContent],
                ],
            );
        $element->expects(self::never())
            ->method('getName');
        $element->expects(self::exactly(3))
            ->method('hasAttribute')
            ->willReturnMap(
                [
                    ['class', true],
                    ['id', true],
                    ['aria-describedby', false],
                ],
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
        $element->expects(self::exactly(4))
            ->method('getOption')
            ->willReturnMap(
                [
                    ['form', $form],
                    ['show-required-mark', $showRequiredMark],
                    ['layout', $layout],
                    ['help_content', $helpContent],
                ],
            );
        $element->expects(self::never())
            ->method('getName');
        $element->expects(self::exactly(2))
            ->method('hasAttribute')
            ->willReturnMap(
                [
                    ['class', true],
                    ['id', false],
                ],
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
        $element->expects(self::exactly(4))
            ->method('getOption')
            ->willReturnMap(
                [
                    ['form', $form],
                    ['show-required-mark', $showRequiredMark],
                    ['layout', $layout],
                    ['help_content', $helpContent],
                ],
            );
        $element->expects(self::never())
            ->method('getName');
        $element->expects(self::exactly(3))
            ->method('hasAttribute')
            ->willReturnMap(
                [
                    ['class', true],
                    ['id', true],
                    ['aria-describedby', true],
                ],
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
        $element->expects(self::exactly(7))
            ->method('getOption')
            ->willReturnMap(
                [
                    ['form', $form],
                    ['show-required-mark', $showRequiredMark],
                    ['layout', $layout],
                    ['help_content', $helpContent],
                    ['help_attributes', $helpAttributes],
                ],
            );
        $element->expects(self::never())
            ->method('getName');
        $element->expects(self::exactly(3))
            ->method('hasAttribute')
            ->willReturnMap(
                [
                    ['class', true],
                    ['id', true],
                    ['aria-describedby', true],
                ],
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

    /**
     * @throws Exception
     * @throws DomainException
     * @throws ServiceNotFoundException
     * @throws InvalidServiceException
     */
    public function testRenderTextWithoutFormOptionAndLabel11(): void
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
        $element->expects(self::exactly(7))
            ->method('getOption')
            ->willReturnMap(
                [
                    ['form', $form],
                    ['show-required-mark', $showRequiredMark],
                    ['layout', $layout],
                    ['help_content', $helpContent],
                    ['help_attributes', $helpAttributes],
                ],
            );
        $element->expects(self::never())
            ->method('getName');
        $element->expects(self::exactly(5))
            ->method('hasAttribute')
            ->willReturnMap(
                [
                    ['class', true],
                    ['id', true],
                    ['aria-describedby', true],
                ],
            );
        $element->expects(self::exactly(3))
            ->method('setAttribute')
            ->willReturnMap(
                [
                    ['class', $class . ' is-invalid', null],
                    ['aria-describedby', $aria . ' ' . $id . 'Feedback', null],
                    ['aria-describedby', $aria . ' ' . $id . 'Feedback ' . $id . 'Help', null],
                ],
            );
        $element->expects(self::exactly(8))
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
            $expected . $expectedErrors . PHP_EOL . $indent . '    ' . $expectedHelp,
            $helper->render($element),
        );
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws ServiceNotFoundException
     * @throws InvalidServiceException
     */
    public function testRenderTextWithoutFormOptionAndLabel12(): void
    {
        $label                  = 'test-label';
        $labelTranslated        = 'test-label-translated';
        $labelTranslatedEscaped = 'test-label-translated-escaped';
        $messages               = ['x' => 'y'];
        $type                   = 'radio';
        $class                  = 'test-class';
        $indent                 = '<!-- -->  ';
        $expected               = '<hidden></hidden>';
        $expectedErrors         = '<errors></errors>';
        $renderErrors           = true;
        $required               = true;
        $showRequiredMark       = false;
        $layout                 = Form::LAYOUT_HORIZONTAL;
        $helpContent            = 'help';
        $helpAttributes         = ['a' => 'b'];
        $expectedHelp           = '<help></help>';
        $id                     = 'test-id';
        $aria                   = 'aria-described';
        $form                   = null;
        $rowAttributes          = ['c' => 'd'];
        $colAttributes          = ['e' => 'f'];
        $labelAttributes        = ['g' => 'h'];
        $labelColAttributes     = ['i' => 'j'];
        $expectedLegend         = '<legend></legend>';
        $expectedCol            = '<col1></col1>';
        $expectedRow            = '<row></row>';
        $textDomain             = 'text-domain';

        $element = $this->getMockBuilder(Radio::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::exactly(15))
            ->method('getOption')
            ->willReturnMap(
                [
                    ['form', $form],
                    ['show-required-mark', $showRequiredMark],
                    ['layout', $layout],
                    ['row_attributes', $rowAttributes],
                    ['col_attributes', $colAttributes],
                    ['label_attributes', $labelAttributes],
                    ['label_col_attributes', $labelColAttributes],
                    ['help_content', $helpContent],
                    ['help_attributes', $helpAttributes],
                ],
            );
        $element->expects(self::never())
            ->method('getName');
        $element->expects(self::exactly(5))
            ->method('hasAttribute')
            ->willReturnMap(
                [
                    ['class', true],
                    ['id', true],
                    ['aria-describedby', true],
                ],
            );
        $element->expects(self::exactly(3))
            ->method('setAttribute')
            ->willReturnMap(
                [
                    ['class', $class . ' is-invalid', null],
                    ['aria-describedby', $aria . ' ' . $id . 'Feedback', null],
                    ['aria-describedby', $aria . ' ' . $id . 'Feedback ' . $id . 'Help', null],
                ],
            );
        $element->expects(self::exactly(8))
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
        $htmlElement->expects(self::exactly(4))
            ->method('toHtml')
            ->willReturnMap(
                [
                    ['legend', $labelColAttributes + $labelAttributes + ['class' => 'col-form-label'], $labelTranslatedEscaped, $expectedLegend],
                    ['div', $helpAttributes + ['id' => $id . 'Help'], $helpContent, $expectedHelp],
                    ['div', $colAttributes, PHP_EOL . $expected . $expectedErrors . PHP_EOL . $indent . '    ' . $expectedHelp . PHP_EOL . $indent . '    ', $expectedCol],
                    ['fieldset', $rowAttributes + ['class' => 'row'], PHP_EOL . $indent . '    ' . $expectedLegend . PHP_EOL . $indent . '    ' . $expectedCol . PHP_EOL . $indent, $expectedRow],
                ],
            );

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::once())
            ->method('__invoke')
            ->with($labelTranslated, 0)
            ->willReturn($labelTranslatedEscaped);

        $renderer = $this->getMockBuilder(RendererInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $renderer->expects(self::never())
            ->method('render');

        $translator = $this->getMockBuilder(Translate::class)
            ->disableOriginalConstructor()
            ->getMock();
        $translator->expects(self::once())
            ->method('__invoke')
            ->with($label, $textDomain, null)
            ->willReturn($labelTranslated);

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

        self::assertSame($indent . $expectedRow, $helper->render($element));
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws ServiceNotFoundException
     * @throws InvalidServiceException
     */
    public function testRenderTextWithoutFormOptionAndLabel13(): void
    {
        $label                  = 'test-label';
        $labelTranslated        = 'test-label-translated';
        $labelTranslatedEscaped = 'test-label-translated-escaped';
        $messages               = ['x' => 'y'];
        $type                   = 'radio';
        $class                  = 'test-class';
        $indent                 = '<!-- -->  ';
        $expected               = '<hidden></hidden>';
        $expectedErrors         = '<errors></errors>';
        $renderErrors           = true;
        $required               = true;
        $showRequiredMark       = false;
        $layout                 = Form::LAYOUT_VERTICAL;
        $helpContent            = 'help';
        $helpAttributes         = ['a' => 'b'];
        $expectedHelp           = '<help></help>';
        $id                     = 'test-id';
        $aria                   = 'aria-described';
        $form                   = null;
        $colAttributes          = ['e' => 'f'];
        $labelAttributes        = ['g' => 'h'];
        $legendAttributes       = ['i' => 'j'];
        $expectedLegend         = '<legend></legend>';
        $expectedCol            = '<col1></col1>';
        $textDomain             = 'text-domain';
        $disableEscape          = false;
        $floating               = false;

        $element = $this->getMockBuilder(Radio::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::exactly(14))
            ->method('getOption')
            ->willReturnMap(
                [
                    ['form', $form],
                    ['show-required-mark', $showRequiredMark],
                    ['layout', $layout],
                    ['col_attributes', $colAttributes],
                    ['label_attributes', $labelAttributes],
                    ['legend_attributes', $legendAttributes],
                    ['help_content', $helpContent],
                    ['help_attributes', $helpAttributes],
                    ['floating', $floating],
                ],
            );
        $element->expects(self::never())
            ->method('getName');
        $element->expects(self::exactly(6))
            ->method('hasAttribute')
            ->willReturnMap(
                [
                    ['class', true],
                    ['id', true],
                    ['aria-describedby', true],
                ],
            );
        $element->expects(self::exactly(3))
            ->method('setAttribute')
            ->willReturnMap(
                [
                    ['class', $class . ' is-invalid', null],
                    ['aria-describedby', $aria . ' ' . $id . 'Feedback', null],
                    ['aria-describedby', $aria . ' ' . $id . 'Feedback ' . $id . 'Help', null],
                ],
            );
        $element->expects(self::exactly(9))
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
        $element->expects(self::never())
            ->method('hasLabelOption');
        $element->expects(self::once())
            ->method('getLabelOption')
            ->with('disable_html_escape')
            ->willReturn($disableEscape);

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
            ->with($indent . '        ');
        $formElementErrors->expects(self::once())
            ->method('render')
            ->with($element)
            ->willReturn($expectedErrors);

        $htmlElement = $this->getMockBuilder(HtmlElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $htmlElement->expects(self::exactly(3))
            ->method('toHtml')
            ->willReturnMap(
                [
                    ['legend', $legendAttributes + ['class' => ''], $labelTranslatedEscaped, $expectedLegend],
                    ['div', $helpAttributes + ['id' => $id . 'Help'], $helpContent, $expectedHelp],
                    ['fieldset', $colAttributes, PHP_EOL . $indent . '    ' . $expectedLegend . PHP_EOL . $indent . '    ' . $expected . $expectedErrors . PHP_EOL . $indent . '        ' . $expectedHelp . PHP_EOL . $indent, $expectedCol],
                ],
            );

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::once())
            ->method('__invoke')
            ->with($labelTranslated, 0)
            ->willReturn($labelTranslatedEscaped);

        $renderer = $this->getMockBuilder(RendererInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $renderer->expects(self::never())
            ->method('render');

        $translator = $this->getMockBuilder(Translate::class)
            ->disableOriginalConstructor()
            ->getMock();
        $translator->expects(self::once())
            ->method('__invoke')
            ->with($label, $textDomain, null)
            ->willReturn($labelTranslated);

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

        self::assertSame($indent . $expectedCol, $helper->render($element));
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws ServiceNotFoundException
     * @throws InvalidServiceException
     */
    public function testRenderTextWithoutFormOptionAndLabel14(): void
    {
        $label                  = 'test-label';
        $labelTranslated        = 'test-label-translated';
        $labelTranslatedEscaped = 'test-label-translated-escaped';
        $messages               = ['x' => 'y'];
        $type                   = 'radio';
        $class                  = 'test-class';
        $indent                 = '<!-- -->  ';
        $expected               = '<hidden></hidden>';
        $expectedErrors         = '<errors></errors>';
        $renderErrors           = true;
        $required               = true;
        $showRequiredMark       = false;
        $layout                 = Form::LAYOUT_HORIZONTAL;
        $helpContent            = 'help';
        $helpAttributes         = ['a' => 'b'];
        $expectedHelp           = '<help></help>';
        $id                     = 'test-id';
        $aria                   = 'aria-described';
        $form                   = null;
        $rowAttributes          = ['c' => 'd'];
        $colAttributes          = ['e' => 'f'];
        $labelAttributes        = ['g' => 'h'];
        $labelColAttributes     = ['i' => 'j'];
        $expectedCol            = '<col1></col1>';
        $expectedRow            = '<row></row>';
        $textDomain             = 'text-domain';
        $disableEscape          = false;

        $element = $this->getMockBuilder(Button::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::exactly(15))
            ->method('getOption')
            ->willReturnMap(
                [
                    ['form', $form],
                    ['show-required-mark', $showRequiredMark],
                    ['layout', $layout],
                    ['row_attributes', $rowAttributes],
                    ['col_attributes', $colAttributes],
                    ['label_attributes', $labelAttributes],
                    ['label_col_attributes', $labelColAttributes],
                    ['help_content', $helpContent],
                    ['help_attributes', $helpAttributes],
                ],
            );
        $element->expects(self::never())
            ->method('getName');
        $element->expects(self::exactly(5))
            ->method('hasAttribute')
            ->willReturnMap(
                [
                    ['class', true],
                    ['id', true],
                    ['aria-describedby', true],
                ],
            );
        $element->expects(self::exactly(3))
            ->method('setAttribute')
            ->willReturnMap(
                [
                    ['class', $class . ' is-invalid', null],
                    ['aria-describedby', $aria . ' ' . $id . 'Feedback', null],
                    ['aria-describedby', $aria . ' ' . $id . 'Feedback ' . $id . 'Help', null],
                ],
            );
        $element->expects(self::exactly(8))
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
        $element->expects(self::never())
            ->method('hasLabelOption');
        $element->expects(self::once())
            ->method('getLabelOption')
            ->with('disable_html_escape')
            ->willReturn($disableEscape);

        $formElement = $this->getMockBuilder(FormElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formElement->expects(self::once())
            ->method('setIndent')
            ->with($indent . '        ');
        $formElement->expects(self::once())
            ->method('render')
            ->with($element)
            ->willReturn($expected);

        $formElementErrors = $this->getMockBuilder(FormElementErrorsInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formElementErrors->expects(self::once())
            ->method('setIndent')
            ->with($indent . '        ');
        $formElementErrors->expects(self::once())
            ->method('render')
            ->with($element)
            ->willReturn($expectedErrors);

        $htmlElement = $this->getMockBuilder(HtmlElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $htmlElement->expects(self::exactly(3))
            ->method('toHtml')
            ->willReturnMap(
                [
                    ['div', $helpAttributes + ['id' => $id . 'Help'], $helpContent, $expectedHelp],
                    ['div', $colAttributes, PHP_EOL . $expected . $expectedErrors . PHP_EOL . $indent . '        ' . $expectedHelp . PHP_EOL . $indent . '    ', $expectedCol],
                    ['div', $rowAttributes + ['class' => 'row'], PHP_EOL . $indent . '    ' . $expectedCol . PHP_EOL . $indent, $expectedRow],
                ],
            );

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::once())
            ->method('__invoke')
            ->with($labelTranslated, 0)
            ->willReturn($labelTranslatedEscaped);

        $renderer = $this->getMockBuilder(RendererInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $renderer->expects(self::never())
            ->method('render');

        $translator = $this->getMockBuilder(Translate::class)
            ->disableOriginalConstructor()
            ->getMock();
        $translator->expects(self::once())
            ->method('__invoke')
            ->with($label, $textDomain, null)
            ->willReturn($labelTranslated);

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

        self::assertSame($indent . $expectedRow, $helper->render($element));
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws ServiceNotFoundException
     * @throws InvalidServiceException
     */
    public function testRenderTextWithoutFormOptionAndLabel15(): void
    {
        $label                  = 'test-label';
        $labelTranslated        = 'test-label-translated';
        $labelTranslatedEscaped = 'test-label-translated-escaped';
        $messages               = ['x' => 'y'];
        $type                   = 'radio';
        $class                  = 'test-class';
        $indent                 = '<!-- -->  ';
        $expected               = '<hidden></hidden>';
        $expectedErrors         = '<errors></errors>';
        $renderErrors           = true;
        $required               = true;
        $showRequiredMark       = false;
        $layout                 = Form::LAYOUT_VERTICAL;
        $helpContent            = 'help';
        $helpAttributes         = ['a' => 'b'];
        $expectedHelp           = '<help></help>';
        $id                     = 'test-id';
        $aria                   = 'aria-described';
        $form                   = null;
        $colAttributes          = ['e' => 'f'];
        $labelAttributes        = ['g' => 'h'];
        $expectedCol            = '<col1></col1>';
        $textDomain             = 'text-domain';
        $disableEscape          = false;

        $element = $this->getMockBuilder(Button::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::exactly(11))
            ->method('getOption')
            ->willReturnMap(
                [
                    ['form', $form],
                    ['show-required-mark', $showRequiredMark],
                    ['layout', $layout],
                    ['col_attributes', $colAttributes],
                    ['label_attributes', $labelAttributes],
                    ['help_content', $helpContent],
                    ['help_attributes', $helpAttributes],
                ],
            );
        $element->expects(self::never())
            ->method('getName');
        $element->expects(self::exactly(6))
            ->method('hasAttribute')
            ->willReturnMap(
                [
                    ['class', true],
                    ['id', true],
                    ['aria-describedby', true],
                ],
            );
        $element->expects(self::exactly(3))
            ->method('setAttribute')
            ->willReturnMap(
                [
                    ['class', $class . ' is-invalid', null],
                    ['aria-describedby', $aria . ' ' . $id . 'Feedback', null],
                    ['aria-describedby', $aria . ' ' . $id . 'Feedback ' . $id . 'Help', null],
                ],
            );
        $element->expects(self::exactly(9))
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
        $element->expects(self::never())
            ->method('hasLabelOption');
        $element->expects(self::once())
            ->method('getLabelOption')
            ->with('disable_html_escape')
            ->willReturn($disableEscape);

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
        $htmlElement->expects(self::exactly(2))
            ->method('toHtml')
            ->willReturnMap(
                [
                    ['div', $helpAttributes + ['id' => $id . 'Help'], $helpContent, $expectedHelp],
                    ['div', $colAttributes, PHP_EOL . $expected . $expectedErrors . PHP_EOL . $indent . '    ' . $expectedHelp . PHP_EOL . $indent, $expectedCol],
                ],
            );

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::once())
            ->method('__invoke')
            ->with($labelTranslated, 0)
            ->willReturn($labelTranslatedEscaped);

        $renderer = $this->getMockBuilder(RendererInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $renderer->expects(self::never())
            ->method('render');

        $translator = $this->getMockBuilder(Translate::class)
            ->disableOriginalConstructor()
            ->getMock();
        $translator->expects(self::once())
            ->method('__invoke')
            ->with($label, $textDomain, null)
            ->willReturn($labelTranslated);

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

        self::assertSame($indent . $expectedCol, $helper->render($element));
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws ServiceNotFoundException
     * @throws InvalidServiceException
     */
    public function testRenderTextWithoutFormOptionAndLabel16(): void
    {
        $label                  = 'test-label';
        $labelTranslated        = 'test-label-translated';
        $labelTranslatedEscaped = 'test-label-translated-escaped';
        $messages               = ['x' => 'y'];
        $type                   = 'radio';
        $class                  = 'test-class';
        $indent                 = '<!-- -->  ';
        $expected               = '<hidden></hidden>';
        $expectedErrors         = '<errors></errors>';
        $renderErrors           = true;
        $required               = true;
        $showRequiredMark       = false;
        $layout                 = Form::LAYOUT_VERTICAL;
        $helpContent            = 'help';
        $helpAttributes         = ['a' => 'b'];
        $expectedHelp           = '<help></help>';
        $id                     = 'test-id';
        $aria                   = 'aria-described';
        $form                   = null;
        $colAttributes          = ['e' => 'f'];
        $labelAttributes        = ['g' => 'h'];
        $legendAttributes       = ['i' => 'j', 'class' => 'legend-class'];
        $expectedLegend         = '<legend></legend>';
        $expectedCol            = '<col1></col1>';
        $textDomain             = 'text-domain';
        $disableEscape          = false;
        $floating               = false;

        $element = $this->getMockBuilder(Radio::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::exactly(14))
            ->method('getOption')
            ->willReturnMap(
                [
                    ['form', $form],
                    ['show-required-mark', $showRequiredMark],
                    ['layout', $layout],
                    ['col_attributes', $colAttributes],
                    ['label_attributes', $labelAttributes],
                    ['legend_attributes', $legendAttributes],
                    ['help_content', $helpContent],
                    ['help_attributes', $helpAttributes],
                    ['floating', $floating],
                ],
            );
        $element->expects(self::never())
            ->method('getName');
        $element->expects(self::exactly(6))
            ->method('hasAttribute')
            ->willReturnMap(
                [
                    ['class', true],
                    ['id', true],
                    ['aria-describedby', true],
                ],
            );
        $element->expects(self::exactly(3))
            ->method('setAttribute')
            ->willReturnMap(
                [
                    ['class', $class . ' is-invalid', null],
                    ['aria-describedby', $aria . ' ' . $id . 'Feedback', null],
                    ['aria-describedby', $aria . ' ' . $id . 'Feedback ' . $id . 'Help', null],
                ],
            );
        $element->expects(self::exactly(9))
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
        $element->expects(self::never())
            ->method('hasLabelOption');
        $element->expects(self::once())
            ->method('getLabelOption')
            ->with('disable_html_escape')
            ->willReturn($disableEscape);

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
            ->with($indent . '        ');
        $formElementErrors->expects(self::once())
            ->method('render')
            ->with($element)
            ->willReturn($expectedErrors);

        $htmlElement = $this->getMockBuilder(HtmlElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $htmlElement->expects(self::exactly(3))
            ->method('toHtml')
            ->willReturnMap(
                [
                    ['legend', $legendAttributes + ['class' => ''], $labelTranslatedEscaped, $expectedLegend],
                    ['div', $helpAttributes + ['id' => $id . 'Help'], $helpContent, $expectedHelp],
                    ['fieldset', $colAttributes, PHP_EOL . $indent . '    ' . $expectedLegend . PHP_EOL . $indent . '    ' . $expected . $expectedErrors . PHP_EOL . $indent . '        ' . $expectedHelp . PHP_EOL . $indent, $expectedCol],
                ],
            );

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::once())
            ->method('__invoke')
            ->with($labelTranslated, 0)
            ->willReturn($labelTranslatedEscaped);

        $renderer = $this->getMockBuilder(RendererInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $renderer->expects(self::never())
            ->method('render');

        $translator = $this->getMockBuilder(Translate::class)
            ->disableOriginalConstructor()
            ->getMock();
        $translator->expects(self::once())
            ->method('__invoke')
            ->with($label, $textDomain, null)
            ->willReturn($labelTranslated);

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

        self::assertSame($indent . $expectedCol, $helper->render($element));
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws ServiceNotFoundException
     * @throws InvalidServiceException
     */
    public function testRenderTextWithoutFormOptionAndLabel17(): void
    {
        $label                  = 'test-label';
        $labelTranslated        = 'test-label-translated';
        $labelTranslatedEscaped = 'test-label-translated-escaped';
        $messages               = ['x' => 'y'];
        $type                   = 'radio';
        $class                  = 'test-class';
        $indent                 = '<!-- -->  ';
        $expected               = '<hidden></hidden>';
        $expectedErrors         = '<errors></errors>';
        $renderErrors           = true;
        $required               = true;
        $showRequiredMark       = false;
        $layout                 = Form::LAYOUT_VERTICAL;
        $helpContent            = 'help';
        $helpAttributes         = ['a' => 'b'];
        $expectedHelp           = '<help></help>';
        $id                     = 'test-id';
        $aria                   = 'aria-described';
        $form                   = null;
        $colAttributes          = ['e' => 'f'];
        $labelAttributes        = ['g' => 'h'];
        $expectedLegend         = '<legend></legend>';
        $expectedCol            = '<col1></col1>';
        $textDomain             = 'text-domain';
        $floating               = false;
        $labelPosition          = \Laminas\Form\View\Helper\FormRow::LABEL_APPEND;
        $disableEscape          = false;

        $element = $this->getMockBuilder(Text::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::exactly(12))
            ->method('getOption')
            ->willReturnMap(
                [
                    ['form', $form],
                    ['show-required-mark', $showRequiredMark],
                    ['layout', $layout],
                    ['col_attributes', $colAttributes],
                    ['label_attributes', $labelAttributes],
                    ['floating', $floating],
                    ['help_content', $helpContent],
                    ['help_attributes', $helpAttributes],
                ],
            );
        $element->expects(self::never())
            ->method('getName');
        $element->expects(self::exactly(6))
            ->method('hasAttribute')
            ->willReturnMap(
                [
                    ['class', true],
                    ['id', true],
                    ['aria-describedby', true],
                ],
            );
        $element->expects(self::exactly(3))
            ->method('setAttribute')
            ->willReturnMap(
                [
                    ['class', $class . ' is-invalid', null],
                    ['aria-describedby', $aria . ' ' . $id . 'Feedback', null],
                    ['aria-describedby', $aria . ' ' . $id . 'Feedback ' . $id . 'Help', null],
                ],
            );
        $element->expects(self::exactly(9))
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
        $element->expects(self::once())
            ->method('hasLabelOption')
            ->with('label_position')
            ->willReturn(true);
        $element->expects(self::exactly(2))
            ->method('getLabelOption')
            ->willReturnMap(
                [
                    ['disable_html_escape', $disableEscape],
                    ['label_position', $labelPosition],
                ],
            );

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
        $htmlElement->expects(self::exactly(3))
            ->method('toHtml')
            ->willReturnMap(
                [
                    ['label', $labelAttributes + ['class' => 'form-label', 'for' => $id], $labelTranslatedEscaped, $expectedLegend],
                    ['div', $helpAttributes + ['id' => $id . 'Help'], $helpContent, $expectedHelp],
                    ['div', $colAttributes, PHP_EOL . $expected . PHP_EOL . $indent . '    ' . $expectedLegend . $expectedErrors . PHP_EOL . $indent . '    ' . $expectedHelp . PHP_EOL . $indent, $expectedCol],
                ],
            );

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::once())
            ->method('__invoke')
            ->with($labelTranslated, 0)
            ->willReturn($labelTranslatedEscaped);

        $renderer = $this->getMockBuilder(RendererInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $renderer->expects(self::never())
            ->method('render');

        $translator = $this->getMockBuilder(Translate::class)
            ->disableOriginalConstructor()
            ->getMock();
        $translator->expects(self::once())
            ->method('__invoke')
            ->with($label, $textDomain, null)
            ->willReturn($labelTranslated);

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

        self::assertSame($indent . $expectedCol, $helper->render($element));
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws ServiceNotFoundException
     * @throws InvalidServiceException
     */
    public function testRenderTextWithoutFormOptionAndLabel18(): void
    {
        $label                  = 'test-label';
        $labelTranslated        = 'test-label-translated';
        $labelTranslatedEscaped = 'test-label-translated-escaped';
        $messages               = ['x' => 'y'];
        $type                   = 'radio';
        $class                  = 'test-class';
        $indent                 = '<!-- -->  ';
        $expected               = '<hidden></hidden>';
        $expectedErrors         = '<errors></errors>';
        $renderErrors           = true;
        $required               = true;
        $showRequiredMark       = false;
        $layout                 = Form::LAYOUT_HORIZONTAL;
        $helpContent            = 'help';
        $helpAttributes         = ['a' => 'b'];
        $expectedHelp           = '<help></help>';
        $id                     = 'test-id';
        $aria                   = 'aria-described';
        $form                   = null;
        $rowAttributes          = ['c' => 'd'];
        $colAttributes          = ['e' => 'f'];
        $labelAttributes        = ['g' => 'h'];
        $labelColAttributes     = ['i' => 'j'];
        $expectedLegend         = '<legend></legend>';
        $expectedCol            = '<col1></col1>';
        $expectedRow            = '<row></row>';
        $textDomain             = 'text-domain';
        $disableEscape          = false;

        $element = $this->getMockBuilder(Text::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::exactly(15))
            ->method('getOption')
            ->willReturnMap(
                [
                    ['form', $form],
                    ['show-required-mark', $showRequiredMark],
                    ['layout', $layout],
                    ['row_attributes', $rowAttributes],
                    ['col_attributes', $colAttributes],
                    ['label_attributes', $labelAttributes],
                    ['label_col_attributes', $labelColAttributes],
                    ['help_content', $helpContent],
                    ['help_attributes', $helpAttributes],
                ],
            );
        $element->expects(self::never())
            ->method('getName');
        $element->expects(self::exactly(6))
            ->method('hasAttribute')
            ->willReturnMap(
                [
                    ['class', true],
                    ['id', true],
                    ['aria-describedby', true],
                ],
            );
        $element->expects(self::exactly(3))
            ->method('setAttribute')
            ->willReturnMap(
                [
                    ['class', $class . ' is-invalid', null],
                    ['aria-describedby', $aria . ' ' . $id . 'Feedback', null],
                    ['aria-describedby', $aria . ' ' . $id . 'Feedback ' . $id . 'Help', null],
                ],
            );
        $element->expects(self::exactly(9))
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
        $element->expects(self::never())
            ->method('hasLabelOption');
        $element->expects(self::once())
            ->method('getLabelOption')
            ->with('disable_html_escape')
            ->willReturn($disableEscape);

        $formElement = $this->getMockBuilder(FormElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formElement->expects(self::once())
            ->method('setIndent')
            ->with($indent . '        ');
        $formElement->expects(self::once())
            ->method('render')
            ->with($element)
            ->willReturn($expected);

        $formElementErrors = $this->getMockBuilder(FormElementErrorsInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formElementErrors->expects(self::once())
            ->method('setIndent')
            ->with($indent . '        ');
        $formElementErrors->expects(self::once())
            ->method('render')
            ->with($element)
            ->willReturn($expectedErrors);

        $htmlElement = $this->getMockBuilder(HtmlElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $htmlElement->expects(self::exactly(4))
            ->method('toHtml')
            ->willReturnMap(
                [
                    ['label', $labelColAttributes + $labelAttributes + ['class' => 'col-form-label', 'for' => $id], $labelTranslatedEscaped, $expectedLegend],
                    ['div', $helpAttributes + ['id' => $id . 'Help'], $helpContent, $expectedHelp],
                    ['div', $colAttributes, PHP_EOL . $expected . $expectedErrors . PHP_EOL . $indent . '        ' . $expectedHelp . PHP_EOL . $indent . '    ', $expectedCol],
                    ['div', $rowAttributes + ['class' => 'row'], PHP_EOL . $indent . '    ' . $expectedLegend . PHP_EOL . $indent . '    ' . $expectedCol . PHP_EOL . $indent, $expectedRow],
                ],
            );

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::once())
            ->method('__invoke')
            ->with($labelTranslated, 0)
            ->willReturn($labelTranslatedEscaped);

        $renderer = $this->getMockBuilder(RendererInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $renderer->expects(self::never())
            ->method('render');

        $translator = $this->getMockBuilder(Translate::class)
            ->disableOriginalConstructor()
            ->getMock();
        $translator->expects(self::once())
            ->method('__invoke')
            ->with($label, $textDomain, null)
            ->willReturn($labelTranslated);

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

        self::assertSame($indent . $expectedRow, $helper->render($element));
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws ServiceNotFoundException
     * @throws InvalidServiceException
     */
    public function testRenderTextWithoutFormOptionAndLabel19(): void
    {
        $label                  = 'test-label';
        $labelTranslated        = 'test-label-translated';
        $labelTranslatedEscaped = 'test-label-translated-escaped';
        $messages               = ['x' => 'y'];
        $type                   = 'radio';
        $class                  = 'test-class';
        $indent                 = '<!-- -->  ';
        $expected               = '<hidden></hidden>';
        $expectedErrors         = '<errors></errors>';
        $renderErrors           = true;
        $required               = true;
        $showRequiredMark       = false;
        $layout                 = Form::LAYOUT_VERTICAL;
        $helpContent            = 'help';
        $helpAttributes         = ['a' => 'b'];
        $expectedHelp           = '<help></help>';
        $id                     = 'test-id';
        $aria                   = 'aria-described';
        $form                   = null;
        $colAttributes          = ['e' => 'f'];
        $labelAttributes        = ['g' => 'h'];
        $legendAttributes       = ['i' => 'j'];
        $expectedLegend         = '<legend></legend>';
        $expectedCol            = '<col1></col1>';
        $expectedControl        = '<control1></control1>';
        $expectedFloating       = '<floating1></floating1>';
        $textDomain             = 'text-domain';
        $disableEscape          = false;
        $floating               = true;

        $element = $this->getMockBuilder(Radio::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::exactly(14))
            ->method('getOption')
            ->willReturnMap(
                [
                    ['form', $form],
                    ['show-required-mark', $showRequiredMark],
                    ['layout', $layout],
                    ['col_attributes', $colAttributes],
                    ['label_attributes', $labelAttributes],
                    ['legend_attributes', $legendAttributes],
                    ['help_content', $helpContent],
                    ['help_attributes', $helpAttributes],
                    ['floating', $floating],
                ],
            );
        $element->expects(self::never())
            ->method('getName');
        $element->expects(self::exactly(6))
            ->method('hasAttribute')
            ->willReturnMap(
                [
                    ['class', true],
                    ['id', true],
                    ['aria-describedby', true],
                ],
            );
        $element->expects(self::exactly(3))
            ->method('setAttribute')
            ->willReturnMap(
                [
                    ['class', $class . ' is-invalid', null],
                    ['aria-describedby', $aria . ' ' . $id . 'Feedback', null],
                    ['aria-describedby', $aria . ' ' . $id . 'Feedback ' . $id . 'Help', null],
                ],
            );
        $element->expects(self::exactly(9))
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
        $element->expects(self::never())
            ->method('hasLabelOption');
        $element->expects(self::once())
            ->method('getLabelOption')
            ->with('disable_html_escape')
            ->willReturn($disableEscape);

        $formElement = $this->getMockBuilder(FormElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formElement->expects(self::once())
            ->method('setIndent')
            ->with($indent . '        ');
        $formElement->expects(self::once())
            ->method('render')
            ->with($element)
            ->willReturn($expected);

        $formElementErrors = $this->getMockBuilder(FormElementErrorsInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formElementErrors->expects(self::once())
            ->method('setIndent')
            ->with($indent . '        ');
        $formElementErrors->expects(self::once())
            ->method('render')
            ->with($element)
            ->willReturn($expectedErrors);

        $htmlElement = $this->getMockBuilder(HtmlElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $htmlElement->expects(self::exactly(5))
            ->method('toHtml')
            ->willReturnMap(
                [
                    ['legend', $legendAttributes + ['class' => ''], $labelTranslatedEscaped, $expectedLegend],
                    ['div', $helpAttributes + ['id' => $id . 'Help'], $helpContent, $expectedHelp],
                    ['div', ['class' => 'form-control'], PHP_EOL . $indent . '        ' . $expected . $expectedErrors . PHP_EOL . $indent . '        ' . $expectedHelp . PHP_EOL . $indent . '    ', $expectedControl],
                    ['div', ['class' => 'form-floating'], PHP_EOL . $expectedControl . PHP_EOL . $indent . '    ' . $expectedLegend . PHP_EOL . $indent . '    ', $expectedFloating],
                    ['fieldset', $colAttributes, PHP_EOL . $indent . '    ' . $expectedFloating . PHP_EOL . $indent, $expectedCol],
                ],
            );

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::once())
            ->method('__invoke')
            ->with($labelTranslated, 0)
            ->willReturn($labelTranslatedEscaped);

        $renderer = $this->getMockBuilder(RendererInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $renderer->expects(self::never())
            ->method('render');

        $translator = $this->getMockBuilder(Translate::class)
            ->disableOriginalConstructor()
            ->getMock();
        $translator->expects(self::once())
            ->method('__invoke')
            ->with($label, $textDomain, null)
            ->willReturn($labelTranslated);

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

        self::assertSame($indent . $expectedCol, $helper->render($element));
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws ServiceNotFoundException
     * @throws InvalidServiceException
     */
    public function testRenderTextWithoutFormOptionAndLabel20(): void
    {
        $label                  = 'test-label';
        $labelTranslated        = 'test-label-translated';
        $labelTranslatedEscaped = 'test-label-translated-escaped';
        $messages               = ['x' => 'y'];
        $type                   = 'radio';
        $class                  = 'test-class';
        $indent                 = '<!-- -->  ';
        $expected               = '<hidden></hidden>';
        $expectedErrors         = '<errors></errors>';
        $renderErrors           = true;
        $required               = true;
        $showRequiredMark       = false;
        $layout                 = Form::LAYOUT_VERTICAL;
        $helpContent            = 'help';
        $helpAttributes         = ['a' => 'b'];
        $expectedHelp           = '<help></help>';
        $id                     = 'test-id';
        $aria                   = 'aria-described';
        $form                   = null;
        $colAttributes          = ['e' => 'f'];
        $labelAttributes        = ['g' => 'h'];
        $legendAttributes       = ['i' => 'j', 'class' => 'legend-class'];
        $expectedLegend         = '<legend></legend>';
        $expectedCol            = '<col1></col1>';
        $expectedControl        = '<control1></control1>';
        $expectedFloating       = '<floating1></floating1>';
        $textDomain             = 'text-domain';
        $disableEscape          = false;
        $floating               = true;

        $element = $this->getMockBuilder(Radio::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::exactly(14))
            ->method('getOption')
            ->willReturnMap(
                [
                    ['form', $form],
                    ['show-required-mark', $showRequiredMark],
                    ['layout', $layout],
                    ['col_attributes', $colAttributes],
                    ['label_attributes', $labelAttributes],
                    ['legend_attributes', $legendAttributes],
                    ['help_content', $helpContent],
                    ['help_attributes', $helpAttributes],
                    ['floating', $floating],
                ],
            );
        $element->expects(self::never())
            ->method('getName');
        $element->expects(self::exactly(6))
            ->method('hasAttribute')
            ->willReturnMap(
                [
                    ['class', true],
                    ['id', true],
                    ['aria-describedby', true],
                ],
            );
        $element->expects(self::exactly(3))
            ->method('setAttribute')
            ->willReturnMap(
                [
                    ['class', $class . ' is-invalid', null],
                    ['aria-describedby', $aria . ' ' . $id . 'Feedback', null],
                    ['aria-describedby', $aria . ' ' . $id . 'Feedback ' . $id . 'Help', null],
                ],
            );
        $element->expects(self::exactly(9))
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
        $element->expects(self::never())
            ->method('hasLabelOption');
        $element->expects(self::once())
            ->method('getLabelOption')
            ->with('disable_html_escape')
            ->willReturn($disableEscape);

        $formElement = $this->getMockBuilder(FormElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formElement->expects(self::once())
            ->method('setIndent')
            ->with($indent . '        ');
        $formElement->expects(self::once())
            ->method('render')
            ->with($element)
            ->willReturn($expected);

        $formElementErrors = $this->getMockBuilder(FormElementErrorsInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $formElementErrors->expects(self::once())
            ->method('setIndent')
            ->with($indent . '        ');
        $formElementErrors->expects(self::once())
            ->method('render')
            ->with($element)
            ->willReturn($expectedErrors);

        $htmlElement = $this->getMockBuilder(HtmlElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $htmlElement->expects(self::exactly(5))
            ->method('toHtml')
            ->willReturnMap(
                [
                    ['legend', $legendAttributes + ['class' => ''], $labelTranslatedEscaped, $expectedLegend],
                    ['div', $helpAttributes + ['id' => $id . 'Help'], $helpContent, $expectedHelp],
                    ['div', ['class' => 'form-control'], PHP_EOL . $indent . '        ' . $expected . $expectedErrors . PHP_EOL . $indent . '        ' . $expectedHelp . PHP_EOL . $indent . '    ', $expectedControl],
                    ['div', ['class' => 'form-floating'], PHP_EOL . $expectedControl . PHP_EOL . $indent . '    ' . $expectedLegend . PHP_EOL . $indent . '    ', $expectedFloating],
                    ['fieldset', $colAttributes, PHP_EOL . $indent . '    ' . $expectedFloating . PHP_EOL . $indent, $expectedCol],
                ],
            );

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::once())
            ->method('__invoke')
            ->with($labelTranslated, 0)
            ->willReturn($labelTranslatedEscaped);

        $renderer = $this->getMockBuilder(RendererInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $renderer->expects(self::never())
            ->method('render');

        $translator = $this->getMockBuilder(Translate::class)
            ->disableOriginalConstructor()
            ->getMock();
        $translator->expects(self::once())
            ->method('__invoke')
            ->with($label, $textDomain, null)
            ->willReturn($labelTranslated);

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

        self::assertSame($indent . $expectedCol, $helper->render($element));
    }
}
