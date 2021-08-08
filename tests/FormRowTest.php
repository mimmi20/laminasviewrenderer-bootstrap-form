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

use AssertionError;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\Radio;
use Laminas\Form\Element\Text;
use Laminas\Form\ElementInterface;
use Laminas\Form\Exception\DomainException;
use Laminas\I18n\View\Helper\Translate;
use Laminas\ServiceManager\Exception\InvalidServiceException;
use Laminas\ServiceManager\Exception\ServiceNotFoundException;
use Laminas\View\Exception\InvalidArgumentException;
use Laminas\View\Exception\RuntimeException;
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
     * @throws RuntimeException
     * @throws InvalidArgumentException
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
        $this->expectExceptionMessage('$form should be an Instance of Laminas\Form\FormInterface or null, but was boolean');

        $helper->render($element);
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws RuntimeException
     * @throws InvalidArgumentException
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
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws RuntimeException
     * @throws InvalidArgumentException
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
                ]
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
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws RuntimeException
     * @throws InvalidArgumentException
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
                ]
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
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws RuntimeException
     * @throws InvalidArgumentException
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
            ->withConsecutive(['type'], ['class'])
            ->willReturnOnConsecutiveCalls($type, $class);
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
                ]
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
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws RuntimeException
     * @throws InvalidArgumentException
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
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws RuntimeException
     * @throws InvalidArgumentException
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
                ]
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
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws RuntimeException
     * @throws InvalidArgumentException
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
                ]
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
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws RuntimeException
     * @throws InvalidArgumentException
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
            ->withConsecutive(['type'], ['class'])
            ->willReturnOnConsecutiveCalls($type, $class);
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
                ]
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
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws RuntimeException
     * @throws InvalidArgumentException
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

        $helper = new FormRow($formElement, $formElementErrors, $htmlElement, $escapeHtml, $renderer, $translator);

        $helper->setIndent($indent);
        $helper->setRenderErrors($renderErrors);
        $helper->setTranslatorTextDomain($textDomain);

        self::assertSame($expected, $helper->render($element));
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws RuntimeException
     * @throws InvalidArgumentException
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
                ]
            )
            ->willReturn($expected);

        $translator = $this->getMockBuilder(Translate::class)
            ->disableOriginalConstructor()
            ->getMock();
        $translator->expects(self::never())
            ->method('__invoke');

        $helper = new FormRow($formElement, $formElementErrors, $htmlElement, $escapeHtml, $renderer, $translator);

        $helper->setIndent($indent);
        $helper->setRenderErrors($renderErrors);
        $helper->setPartial($partial);
        $helper->setTranslatorTextDomain($textDomain);

        self::assertSame($expected, $helper->render($element));
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws RuntimeException
     * @throws InvalidArgumentException
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
                ]
            )
            ->willReturn($expected);

        $translator = $this->getMockBuilder(Translate::class)
            ->disableOriginalConstructor()
            ->getMock();
        $translator->expects(self::never())
            ->method('__invoke');

        $helper = new FormRow($formElement, $formElementErrors, $htmlElement, $escapeHtml, $renderer, $translator);

        $helper->setIndent($indent);
        $helper->setRenderErrors($renderErrors);
        $helper->setPartial($partial);
        $helper->setTranslatorTextDomain($textDomain);

        self::assertSame($expected, $helper->render($element));
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws RuntimeException
     * @throws InvalidArgumentException
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
            ->withConsecutive(['type'], ['class'])
            ->willReturnOnConsecutiveCalls($type, $class);
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
                ]
            )
            ->willReturn($expected);

        $translator = $this->getMockBuilder(Translate::class)
            ->disableOriginalConstructor()
            ->getMock();
        $translator->expects(self::never())
            ->method('__invoke');

        $helper = new FormRow($formElement, $formElementErrors, $htmlElement, $escapeHtml, $renderer, $translator);

        $helper->setIndent($indent);
        $helper->setRenderErrors($renderErrors);
        $helper->setPartial($partial);
        $helper->setTranslatorTextDomain($textDomain);

        self::assertSame($expected, $helper->render($element));
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws RuntimeException
     * @throws InvalidArgumentException
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
            ->withConsecutive(['form'], ['show-required-mark'], ['layout'], ['help_content'])
            ->willReturnOnConsecutiveCalls($form, $showRequiredMark, $layout, $helpContent);
        $element->expects(self::never())
            ->method('getName');
        $element->expects(self::never())
            ->method('hasAttribute');
        $element->expects(self::never())
            ->method('setAttribute');
        $element->expects(self::exactly(2))
            ->method('getAttribute')
            ->withConsecutive(['type'], ['required'])
            ->willReturnOnConsecutiveCalls($type, $required);
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

        $helper = new FormRow($formElement, $formElementErrors, $htmlElement, $escapeHtml, $renderer, $translator);

        $helper->setIndent($indent);
        $helper->setRenderErrors($renderErrors);
        $helper->setTranslatorTextDomain($textDomain);

        self::assertSame($expected, $helper->render($element));
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws RuntimeException
     * @throws InvalidArgumentException
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
            ->withConsecutive(['form'], ['show-required-mark'], ['layout'], ['help_content'])
            ->willReturnOnConsecutiveCalls($form, $showRequiredMark, $layout, $helpContent);
        $element->expects(self::never())
            ->method('getName');
        $element->expects(self::never())
            ->method('hasAttribute');
        $element->expects(self::never())
            ->method('setAttribute');
        $element->expects(self::exactly(2))
            ->method('getAttribute')
            ->withConsecutive(['type'], ['required'])
            ->willReturnOnConsecutiveCalls($type, $required);
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

        $helper = new FormRow($formElement, $formElementErrors, $htmlElement, $escapeHtml, $renderer, $translator);

        $helper->setIndent($indent);
        $helper->setRenderErrors($renderErrors);
        $helper->setTranslatorTextDomain($textDomain);

        self::assertSame($expected, $helper->render($element));
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws RuntimeException
     * @throws InvalidArgumentException
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
            ->withConsecutive(['form'], ['show-required-mark'], ['layout'], ['help_content'])
            ->willReturnOnConsecutiveCalls($form, $showRequiredMark, $layout, $helpContent);
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
            ->withConsecutive(['type'], ['required'])
            ->willReturnOnConsecutiveCalls($type, $required);
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

        $helper = new FormRow($formElement, $formElementErrors, $htmlElement, $escapeHtml, $renderer, $translator);

        $helper->setIndent($indent);
        $helper->setRenderErrors($renderErrors);
        $helper->setTranslatorTextDomain($textDomain);

        self::assertSame($expected, $helper->render($element));
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws RuntimeException
     * @throws InvalidArgumentException
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
            ->withConsecutive(['form'], ['show-required-mark'], ['layout'], ['help_content'])
            ->willReturnOnConsecutiveCalls($form, $showRequiredMark, $layout, $helpContent);
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
            ->withConsecutive(['type'], ['class'], ['required'])
            ->willReturnOnConsecutiveCalls($type, $class, $required);
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

        $helper = new FormRow($formElement, $formElementErrors, $htmlElement, $escapeHtml, $renderer, $translator);

        $helper->setIndent($indent);
        $helper->setRenderErrors($renderErrors);
        $helper->setTranslatorTextDomain($textDomain);

        self::assertSame($expected, $helper->render($element));
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws RuntimeException
     * @throws InvalidArgumentException
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
            ->withConsecutive(['form'], ['show-required-mark'], ['layout'], ['help_content'])
            ->willReturnOnConsecutiveCalls($form, $showRequiredMark, $layout, $helpContent);
        $element->expects(self::never())
            ->method('getName');
        $element->expects(self::exactly(3))
            ->method('hasAttribute')
            ->withConsecutive(['class'], ['id'], ['aria-describedby'])
            ->willReturnOnConsecutiveCalls(true, true, false);
        $element->expects(self::exactly(2))
            ->method('setAttribute')
            ->withConsecutive(['class', $class . ' is-invalid'], ['aria-describedby', $id . 'Feedback']);
        $element->expects(self::exactly(4))
            ->method('getAttribute')
            ->withConsecutive(['type'], ['class'], ['required'], ['id'])
            ->willReturnOnConsecutiveCalls($type, $class, $required, $id);
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

        $helper = new FormRow($formElement, $formElementErrors, $htmlElement, $escapeHtml, $renderer, $translator);

        $helper->setIndent($indent);
        $helper->setRenderErrors($renderErrors);
        $helper->setTranslatorTextDomain($textDomain);

        self::assertSame($expected . $expectedErrors, $helper->render($element));
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws RuntimeException
     * @throws InvalidArgumentException
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
            ->withConsecutive(['form'], ['show-required-mark'], ['layout'], ['help_content'])
            ->willReturnOnConsecutiveCalls($form, $showRequiredMark, $layout, $helpContent);
        $element->expects(self::never())
            ->method('getName');
        $element->expects(self::exactly(2))
            ->method('hasAttribute')
            ->withConsecutive(['class'], ['id'])
            ->willReturnOnConsecutiveCalls(true, false);
        $element->expects(self::once())
            ->method('setAttribute')
            ->with('class', $class . ' is-invalid');
        $element->expects(self::exactly(3))
            ->method('getAttribute')
            ->withConsecutive(['type'], ['class'], ['required'])
            ->willReturnOnConsecutiveCalls($type, $class, $required);
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

        $helper = new FormRow($formElement, $formElementErrors, $htmlElement, $escapeHtml, $renderer, $translator);

        $helper->setIndent($indent);
        $helper->setRenderErrors($renderErrors);
        $helper->setTranslatorTextDomain($textDomain);

        self::assertSame($expected . $expectedErrors, $helper->render($element));
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws RuntimeException
     * @throws InvalidArgumentException
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
            ->withConsecutive(['form'], ['show-required-mark'], ['layout'], ['help_content'])
            ->willReturnOnConsecutiveCalls($form, $showRequiredMark, $layout, $helpContent);
        $element->expects(self::never())
            ->method('getName');
        $element->expects(self::exactly(3))
            ->method('hasAttribute')
            ->withConsecutive(['class'], ['id'], ['aria-describedby'])
            ->willReturnOnConsecutiveCalls(true, true, true);
        $element->expects(self::exactly(2))
            ->method('setAttribute')
            ->withConsecutive(['class', $class . ' is-invalid'], ['aria-describedby', $aria . ' ' . $id . 'Feedback']);
        $element->expects(self::exactly(5))
            ->method('getAttribute')
            ->withConsecutive(['type'], ['class'], ['required'], ['aria-describedby'], ['id'])
            ->willReturnOnConsecutiveCalls($type, $class, $required, $aria, $id);
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

        $helper = new FormRow($formElement, $formElementErrors, $htmlElement, $escapeHtml, $renderer, $translator);

        $helper->setIndent($indent);
        $helper->setRenderErrors($renderErrors);
        $helper->setTranslatorTextDomain($textDomain);

        self::assertSame($expected . $expectedErrors, $helper->render($element));
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws RuntimeException
     * @throws InvalidArgumentException
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
            ->withConsecutive(['form'], ['show-required-mark'], ['layout'], ['help_content'], ['help_content'], ['help_attributes'], ['form'])
            ->willReturnOnConsecutiveCalls($form, $showRequiredMark, $layout, $helpContent, $helpContent, $helpAttributes, $form);
        $element->expects(self::never())
            ->method('getName');
        $element->expects(self::exactly(3))
            ->method('hasAttribute')
            ->withConsecutive(['class'], ['id'], ['aria-describedby'])
            ->willReturnOnConsecutiveCalls(true, true, true);
        $element->expects(self::exactly(2))
            ->method('setAttribute')
            ->withConsecutive(['class', $class . ' is-invalid'], ['aria-describedby', $aria . ' ' . $id . 'Help']);
        $element->expects(self::exactly(6))
            ->method('getAttribute')
            ->withConsecutive(['type'], ['class'], ['required'], ['id'], ['aria-describedby'], ['id'])
            ->willReturnOnConsecutiveCalls($type, $class, $required, $id, $aria, $id);
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

        $helper = new FormRow($formElement, $formElementErrors, $htmlElement, $escapeHtml, $renderer, $translator);

        $helper->setIndent($indent);
        $helper->setRenderErrors($renderErrors);
        $helper->setTranslatorTextDomain($textDomain);

        self::assertSame($expected . PHP_EOL . $indent . '    ' . $expectedHelp, $helper->render($element));
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws RuntimeException
     * @throws InvalidArgumentException
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
            ->withConsecutive(['form'], ['show-required-mark'], ['layout'], ['help_content'], ['help_content'], ['help_attributes'], ['form'])
            ->willReturnOnConsecutiveCalls($form, $showRequiredMark, $layout, $helpContent, $helpContent, $helpAttributes, $form);
        $element->expects(self::never())
            ->method('getName');
        $element->expects(self::exactly(5))
            ->method('hasAttribute')
            ->withConsecutive(['class'], ['id'], ['aria-describedby'], ['id'], ['aria-describedby'])
            ->willReturnOnConsecutiveCalls(true, true, true, true, true);
        $element->expects(self::exactly(3))
            ->method('setAttribute')
            ->withConsecutive(['class', $class . ' is-invalid'], ['aria-describedby', $aria . ' ' . $id . 'Feedback'], ['aria-describedby', $aria . ' ' . $id . 'Feedback ' . $id . 'Help']);
        $element->expects(self::exactly(8))
            ->method('getAttribute')
            ->withConsecutive(['type'], ['class'], ['required'], ['aria-describedby'], ['id'], ['id'], ['aria-describedby'], ['id'])
            ->willReturnOnConsecutiveCalls($type, $class, $required, $aria, $id, $id, $aria . ' ' . $id . 'Feedback', $id);
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

        $helper = new FormRow($formElement, $formElementErrors, $htmlElement, $escapeHtml, $renderer, $translator);

        $helper->setIndent($indent);
        $helper->setRenderErrors($renderErrors);
        $helper->setTranslatorTextDomain($textDomain);

        self::assertSame($expected . $expectedErrors . PHP_EOL . $indent . '    ' . $expectedHelp, $helper->render($element));
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws RuntimeException
     * @throws InvalidArgumentException
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
            ->withConsecutive(['form'], ['show-required-mark'], ['layout'], ['row_attributes'], ['form'], ['col_attributes'], ['form'], ['label_attributes'], ['form'], ['label_col_attributes'], ['form'], ['help_content'], ['help_content'], ['help_attributes'], ['form'], ['form'])
            ->willReturnOnConsecutiveCalls($form, $showRequiredMark, $layout, $rowAttributes, $form, $colAttributes, $form, $labelAttributes, $form, $labelColAttributes, $form, $helpContent, $helpContent, $helpAttributes, $form, $form);
        $element->expects(self::never())
            ->method('getName');
        $element->expects(self::exactly(5))
            ->method('hasAttribute')
            ->withConsecutive(['class'], ['id'], ['aria-describedby'], ['id'], ['aria-describedby'])
            ->willReturnOnConsecutiveCalls(true, true, true, true, true);
        $element->expects(self::exactly(3))
            ->method('setAttribute')
            ->withConsecutive(['class', $class . ' is-invalid'], ['aria-describedby', $aria . ' ' . $id . 'Feedback'], ['aria-describedby', $aria . ' ' . $id . 'Feedback ' . $id . 'Help']);
        $element->expects(self::exactly(8))
            ->method('getAttribute')
            ->withConsecutive(['type'], ['class'], ['required'], ['aria-describedby'], ['id'], ['id'], ['aria-describedby'], ['id'])
            ->willReturnOnConsecutiveCalls($type, $class, $required, $aria, $id, $id, $aria . ' ' . $id . 'Feedback', $id);
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
            ->withConsecutive(
                ['legend', $labelColAttributes + $labelAttributes + ['class' => 'col-form-label'], $labelTranslatedEscaped],
                ['div', $helpAttributes + ['id' => $id . 'Help'], $helpContent],
                ['div', $colAttributes, PHP_EOL . $expected . $expectedErrors . PHP_EOL . $indent . '    ' . $expectedHelp . PHP_EOL . $indent . '    '],
                ['fieldset', $rowAttributes + ['class' => 'row'], PHP_EOL . $indent . '    ' . $expectedLegend . PHP_EOL . $indent . '    ' . $expectedCol . PHP_EOL . $indent]
            )
            ->willReturnOnConsecutiveCalls(
                $expectedLegend,
                $expectedHelp,
                $expectedCol,
                $expectedRow
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

        $helper = new FormRow($formElement, $formElementErrors, $htmlElement, $escapeHtml, $renderer, $translator);

        $helper->setIndent($indent);
        $helper->setRenderErrors($renderErrors);
        $helper->setTranslatorTextDomain($textDomain);

        self::assertSame($indent . $expectedRow, $helper->render($element));
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws RuntimeException
     * @throws InvalidArgumentException
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

        $element = $this->getMockBuilder(Radio::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::exactly(13))
            ->method('getOption')
            ->withConsecutive(['form'], ['show-required-mark'], ['layout'], ['col_attributes'], ['form'], ['label_attributes'], ['form'], ['legend_attributes'], ['form'], ['help_content'], ['help_content'], ['help_attributes'], ['form'], ['form'])
            ->willReturnOnConsecutiveCalls($form, $showRequiredMark, $layout, $colAttributes, $form, $labelAttributes, $form, $legendAttributes, $form, $helpContent, $helpContent, $helpAttributes, $form, $form);
        $element->expects(self::never())
            ->method('getName');
        $element->expects(self::exactly(6))
            ->method('hasAttribute')
            ->withConsecutive(['class'], ['id'], ['id'], ['aria-describedby'], ['id'], ['aria-describedby'])
            ->willReturnOnConsecutiveCalls(true, true, true, true, true, true);
        $element->expects(self::exactly(3))
            ->method('setAttribute')
            ->withConsecutive(['class', $class . ' is-invalid'], ['aria-describedby', $aria . ' ' . $id . 'Feedback'], ['aria-describedby', $aria . ' ' . $id . 'Feedback ' . $id . 'Help']);
        $element->expects(self::exactly(9))
            ->method('getAttribute')
            ->withConsecutive(['type'], ['class'], ['required'], ['id'], ['aria-describedby'], ['id'], ['id'], ['aria-describedby'], ['id'])
            ->willReturnOnConsecutiveCalls($type, $class, $required, $id, $aria, $id, $id, $aria . ' ' . $id . 'Feedback', $id);
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
            ->withConsecutive(
                ['legend', $legendAttributes + ['class' => ''], $labelTranslatedEscaped],
                ['div', $helpAttributes + ['id' => $id . 'Help'], $helpContent],
                ['fieldset', $colAttributes, PHP_EOL . $indent . '    ' . $expectedLegend . PHP_EOL . $indent . '    ' . $expected . $expectedErrors . PHP_EOL . $indent . '        ' . $expectedHelp . PHP_EOL . $indent]
            )
            ->willReturnOnConsecutiveCalls(
                $expectedLegend,
                $expectedHelp,
                $expectedCol
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

        $helper = new FormRow($formElement, $formElementErrors, $htmlElement, $escapeHtml, $renderer, $translator);

        $helper->setIndent($indent);
        $helper->setRenderErrors($renderErrors);
        $helper->setTranslatorTextDomain($textDomain);

        self::assertSame($indent . $expectedCol, $helper->render($element));
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws RuntimeException
     * @throws InvalidArgumentException
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
            ->withConsecutive(['form'], ['show-required-mark'], ['layout'], ['row_attributes'], ['form'], ['col_attributes'], ['form'], ['label_attributes'], ['form'], ['label_col_attributes'], ['form'], ['help_content'], ['help_content'], ['help_attributes'], ['form'], ['form'])
            ->willReturnOnConsecutiveCalls($form, $showRequiredMark, $layout, $rowAttributes, $form, $colAttributes, $form, $labelAttributes, $form, $labelColAttributes, $form, $helpContent, $helpContent, $helpAttributes, $form, $form);
        $element->expects(self::never())
            ->method('getName');
        $element->expects(self::exactly(5))
            ->method('hasAttribute')
            ->withConsecutive(['class'], ['id'], ['aria-describedby'], ['id'], ['aria-describedby'])
            ->willReturnOnConsecutiveCalls(true, true, true, true, true);
        $element->expects(self::exactly(3))
            ->method('setAttribute')
            ->withConsecutive(['class', $class . ' is-invalid'], ['aria-describedby', $aria . ' ' . $id . 'Feedback'], ['aria-describedby', $aria . ' ' . $id . 'Feedback ' . $id . 'Help']);
        $element->expects(self::exactly(8))
            ->method('getAttribute')
            ->withConsecutive(['type'], ['class'], ['required'], ['aria-describedby'], ['id'], ['id'], ['aria-describedby'], ['id'])
            ->willReturnOnConsecutiveCalls($type, $class, $required, $aria, $id, $id, $aria . ' ' . $id . 'Feedback', $id);
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
            ->withConsecutive(
                ['div', $helpAttributes + ['id' => $id . 'Help'], $helpContent],
                ['div', $colAttributes, PHP_EOL . $expected . $expectedErrors . PHP_EOL . $indent . '        ' . $expectedHelp . PHP_EOL . $indent . '    '],
                ['div', $rowAttributes + ['class' => 'row'], PHP_EOL . $indent . '    ' . $expectedCol . PHP_EOL . $indent]
            )
            ->willReturnOnConsecutiveCalls(
                $expectedHelp,
                $expectedCol,
                $expectedRow
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

        $helper = new FormRow($formElement, $formElementErrors, $htmlElement, $escapeHtml, $renderer, $translator);

        $helper->setIndent($indent);
        $helper->setRenderErrors($renderErrors);
        $helper->setTranslatorTextDomain($textDomain);

        self::assertSame($indent . $expectedRow, $helper->render($element));
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws RuntimeException
     * @throws InvalidArgumentException
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
            ->withConsecutive(['form'], ['show-required-mark'], ['layout'], ['col_attributes'], ['form'], ['label_attributes'], ['form'], ['help_content'], ['help_content'], ['help_attributes'], ['form'], ['form'])
            ->willReturnOnConsecutiveCalls($form, $showRequiredMark, $layout, $colAttributes, $form, $labelAttributes, $form, $helpContent, $helpContent, $helpAttributes, $form, $form);
        $element->expects(self::never())
            ->method('getName');
        $element->expects(self::exactly(6))
            ->method('hasAttribute')
            ->withConsecutive(['class'], ['id'], ['id'], ['aria-describedby'], ['id'], ['aria-describedby'])
            ->willReturnOnConsecutiveCalls(true, true, true, true, true, true);
        $element->expects(self::exactly(3))
            ->method('setAttribute')
            ->withConsecutive(['class', $class . ' is-invalid'], ['aria-describedby', $aria . ' ' . $id . 'Feedback'], ['aria-describedby', $aria . ' ' . $id . 'Feedback ' . $id . 'Help']);
        $element->expects(self::exactly(9))
            ->method('getAttribute')
            ->withConsecutive(['type'], ['class'], ['required'], ['id'], ['aria-describedby'], ['id'], ['id'], ['aria-describedby'], ['id'])
            ->willReturnOnConsecutiveCalls($type, $class, $required, $id, $aria, $id, $id, $aria . ' ' . $id . 'Feedback', $id);
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
            ->withConsecutive(
                ['div', $helpAttributes + ['id' => $id . 'Help'], $helpContent],
                ['div', $colAttributes, PHP_EOL . $expected . $expectedErrors . PHP_EOL . $indent . '    ' . $expectedHelp . PHP_EOL . $indent]
            )
            ->willReturnOnConsecutiveCalls(
                $expectedHelp,
                $expectedCol
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

        $helper = new FormRow($formElement, $formElementErrors, $htmlElement, $escapeHtml, $renderer, $translator);

        $helper->setIndent($indent);
        $helper->setRenderErrors($renderErrors);
        $helper->setTranslatorTextDomain($textDomain);

        self::assertSame($indent . $expectedCol, $helper->render($element));
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws RuntimeException
     * @throws InvalidArgumentException
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

        $element = $this->getMockBuilder(Radio::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::exactly(13))
            ->method('getOption')
            ->withConsecutive(['form'], ['show-required-mark'], ['layout'], ['col_attributes'], ['form'], ['label_attributes'], ['form'], ['legend_attributes'], ['form'], ['help_content'], ['help_content'], ['help_attributes'], ['form'], ['form'])
            ->willReturnOnConsecutiveCalls($form, $showRequiredMark, $layout, $colAttributes, $form, $labelAttributes, $form, $legendAttributes, $form, $helpContent, $helpContent, $helpAttributes, $form, $form);
        $element->expects(self::never())
            ->method('getName');
        $element->expects(self::exactly(6))
            ->method('hasAttribute')
            ->withConsecutive(['class'], ['id'], ['id'], ['aria-describedby'], ['id'], ['aria-describedby'])
            ->willReturnOnConsecutiveCalls(true, true, true, true, true, true);
        $element->expects(self::exactly(3))
            ->method('setAttribute')
            ->withConsecutive(['class', $class . ' is-invalid'], ['aria-describedby', $aria . ' ' . $id . 'Feedback'], ['aria-describedby', $aria . ' ' . $id . 'Feedback ' . $id . 'Help']);
        $element->expects(self::exactly(9))
            ->method('getAttribute')
            ->withConsecutive(['type'], ['class'], ['required'], ['id'], ['aria-describedby'], ['id'], ['id'], ['aria-describedby'], ['id'])
            ->willReturnOnConsecutiveCalls($type, $class, $required, $id, $aria, $id, $id, $aria . ' ' . $id . 'Feedback', $id);
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
            ->withConsecutive(
                ['legend', $legendAttributes + ['class' => ''], $labelTranslatedEscaped],
                ['div', $helpAttributes + ['id' => $id . 'Help'], $helpContent],
                ['fieldset', $colAttributes, PHP_EOL . $indent . '    ' . $expectedLegend . PHP_EOL . $indent . '    ' . $expected . $expectedErrors . PHP_EOL . $indent . '        ' . $expectedHelp . PHP_EOL . $indent]
            )
            ->willReturnOnConsecutiveCalls(
                $expectedLegend,
                $expectedHelp,
                $expectedCol
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

        $helper = new FormRow($formElement, $formElementErrors, $htmlElement, $escapeHtml, $renderer, $translator);

        $helper->setIndent($indent);
        $helper->setRenderErrors($renderErrors);
        $helper->setTranslatorTextDomain($textDomain);

        self::assertSame($indent . $expectedCol, $helper->render($element));
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws RuntimeException
     * @throws InvalidArgumentException
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
            ->withConsecutive(['form'], ['show-required-mark'], ['layout'], ['col_attributes'], ['form'], ['label_attributes'], ['form'], ['floating'], ['help_content'], ['help_content'], ['help_attributes'], ['form'], ['form'])
            ->willReturnOnConsecutiveCalls($form, $showRequiredMark, $layout, $colAttributes, $form, $labelAttributes, $form, $floating, $helpContent, $helpContent, $helpAttributes, $form, $form);
        $element->expects(self::never())
            ->method('getName');
        $element->expects(self::exactly(6))
            ->method('hasAttribute')
            ->withConsecutive(['class'], ['id'], ['id'], ['aria-describedby'], ['id'], ['aria-describedby'])
            ->willReturnOnConsecutiveCalls(true, true, true, true, true, true);
        $element->expects(self::exactly(3))
            ->method('setAttribute')
            ->withConsecutive(['class', $class . ' is-invalid'], ['aria-describedby', $aria . ' ' . $id . 'Feedback'], ['aria-describedby', $aria . ' ' . $id . 'Feedback ' . $id . 'Help']);
        $element->expects(self::exactly(9))
            ->method('getAttribute')
            ->withConsecutive(['type'], ['class'], ['required'], ['id'], ['aria-describedby'], ['id'], ['id'], ['aria-describedby'], ['id'])
            ->willReturnOnConsecutiveCalls($type, $class, $required, $id, $aria, $id, $id, $aria . ' ' . $id . 'Feedback', $id);
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
            ->withConsecutive(['disable_html_escape'], ['label_position'])
            ->willReturnOnConsecutiveCalls($disableEscape, $labelPosition);

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
            ->withConsecutive(
                ['label', ['class' => 'form-label'] + $labelAttributes + ['for' => $id], $labelTranslatedEscaped],
                ['div', $helpAttributes + ['id' => $id . 'Help'], $helpContent],
                ['div', $colAttributes, PHP_EOL . $expected . PHP_EOL . $indent . '    ' . $expectedLegend . $expectedErrors . PHP_EOL . $indent . '    ' . $expectedHelp . PHP_EOL . $indent]
            )
            ->willReturnOnConsecutiveCalls(
                $expectedLegend,
                $expectedHelp,
                $expectedCol
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

        $helper = new FormRow($formElement, $formElementErrors, $htmlElement, $escapeHtml, $renderer, $translator);

        $helper->setIndent($indent);
        $helper->setRenderErrors($renderErrors);
        $helper->setTranslatorTextDomain($textDomain);

        self::assertSame($indent . $expectedCol, $helper->render($element));
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws RuntimeException
     * @throws InvalidArgumentException
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
            ->withConsecutive(['form'], ['show-required-mark'], ['layout'], ['row_attributes'], ['form'], ['col_attributes'], ['form'], ['label_attributes'], ['form'], ['label_col_attributes'], ['form'], ['help_content'], ['help_content'], ['help_attributes'], ['form'], ['form'])
            ->willReturnOnConsecutiveCalls($form, $showRequiredMark, $layout, $rowAttributes, $form, $colAttributes, $form, $labelAttributes, $form, $labelColAttributes, $form, $helpContent, $helpContent, $helpAttributes, $form, $form);
        $element->expects(self::never())
            ->method('getName');
        $element->expects(self::exactly(6))
            ->method('hasAttribute')
            ->withConsecutive(['class'], ['id'], ['id'], ['aria-describedby'], ['id'], ['aria-describedby'])
            ->willReturnOnConsecutiveCalls(true, true, true, true, true, true);
        $element->expects(self::exactly(3))
            ->method('setAttribute')
            ->withConsecutive(['class', $class . ' is-invalid'], ['aria-describedby', $aria . ' ' . $id . 'Feedback'], ['aria-describedby', $aria . ' ' . $id . 'Feedback ' . $id . 'Help']);
        $element->expects(self::exactly(9))
            ->method('getAttribute')
            ->withConsecutive(['type'], ['class'], ['required'], ['id'], ['aria-describedby'], ['id'], ['id'], ['aria-describedby'], ['id'])
            ->willReturnOnConsecutiveCalls($type, $class, $required, $id, $aria, $id, $id, $aria . ' ' . $id . 'Feedback', $id);
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
            ->withConsecutive(
                ['label', $labelColAttributes + $labelAttributes + ['class' => 'col-form-label', 'for' => $id], $labelTranslatedEscaped],
                ['div', $helpAttributes + ['id' => $id . 'Help'], $helpContent],
                ['div', $colAttributes, PHP_EOL . $expected . $expectedErrors . PHP_EOL . $indent . '        ' . $expectedHelp . PHP_EOL . $indent . '    '],
                ['div', $rowAttributes + ['class' => 'row'], PHP_EOL . $indent . '    ' . $expectedLegend . PHP_EOL . $indent . '    ' . $expectedCol . PHP_EOL . $indent]
            )
            ->willReturnOnConsecutiveCalls(
                $expectedLegend,
                $expectedHelp,
                $expectedCol,
                $expectedRow
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

        $helper = new FormRow($formElement, $formElementErrors, $htmlElement, $escapeHtml, $renderer, $translator);

        $helper->setIndent($indent);
        $helper->setRenderErrors($renderErrors);
        $helper->setTranslatorTextDomain($textDomain);

        self::assertSame($indent . $expectedRow, $helper->render($element));
    }
}
