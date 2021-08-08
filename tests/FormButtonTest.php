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
use Laminas\Form\Exception\DomainException;
use Laminas\I18n\View\Helper\Translate;
use Laminas\View\Helper\Doctype;
use Laminas\View\Helper\EscapeHtml;
use Laminas\View\Helper\EscapeHtmlAttr;
use Mimmi20\LaminasView\BootstrapForm\FormButton;
use Mimmi20Test\LaminasView\BootstrapForm\Compare\AbstractTest;
use PHPUnit\Framework\Exception;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

use function assert;
use function gettype;
use function sprintf;

final class FormButtonTest extends AbstractTest
{
    /**
     * @throws InvalidArgumentException
     * @throws Exception
     * @throws \Laminas\Form\Exception\InvalidArgumentException
     * @throws DomainException
     */
    public function testRenderOpenTagWithNull(): void
    {
        $expected = '<button>';

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::never())
            ->method('__invoke');

        $escapeHtmlAttr = $this->getMockBuilder(EscapeHtmlAttr::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtmlAttr->expects(self::never())
            ->method('__invoke');

        $doctype = $this->getMockBuilder(Doctype::class)
            ->disableOriginalConstructor()
            ->getMock();
        $doctype->expects(self::never())
            ->method('__invoke');

        $helper = new FormButton($escapeHtml, $escapeHtmlAttr, $doctype, null);

        self::assertSame($expected, $helper->openTag());
    }

    /**
     * @throws InvalidArgumentException
     * @throws Exception
     * @throws \Laminas\Form\Exception\InvalidArgumentException
     * @throws DomainException
     */
    public function testRenderOpenTagWithArray(): void
    {
        $type       = 'test-type';
        $attributes = ['type' => $type];
        $expected   = sprintf('<button type="%s">', $type);

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::never())
            ->method('__invoke');

        $escapeHtmlAttr = $this->getMockBuilder(EscapeHtmlAttr::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtmlAttr->expects(self::never())
            ->method('__invoke');

        $doctype = $this->getMockBuilder(Doctype::class)
            ->disableOriginalConstructor()
            ->getMock();
        $doctype->expects(self::never())
            ->method('__invoke');

        $helper = new FormButton($escapeHtml, $escapeHtmlAttr, $doctype, null);

        self::assertSame($expected, $helper->openTag($attributes));
    }

    /**
     * @throws \Laminas\Form\Exception\InvalidArgumentException
     * @throws DomainException
     * @throws Exception
     */
    public function testRenderOpenTagWithInt(): void
    {
        $value = 1;

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::never())
            ->method('__invoke');

        $escapeHtmlAttr = $this->getMockBuilder(EscapeHtmlAttr::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtmlAttr->expects(self::never())
            ->method('__invoke');

        $doctype = $this->getMockBuilder(Doctype::class)
            ->disableOriginalConstructor()
            ->getMock();
        $doctype->expects(self::never())
            ->method('__invoke');

        $helper = new FormButton($escapeHtml, $escapeHtmlAttr, $doctype, null);

        $this->expectException(\Laminas\Form\Exception\InvalidArgumentException::class);
        $this->expectExceptionMessage(
            sprintf(
                '%s expects null, an array or a \Laminas\Form\ElementInterface instance; received "%s"',
                'Mimmi20\LaminasView\BootstrapForm\FormButton::openTag',
                gettype($value)
            )
        );
        $this->expectExceptionCode(0);
        $helper->openTag($value);
    }

    /**
     * @throws Exception
     * @throws \Laminas\Form\Exception\InvalidArgumentException
     * @throws DomainException
     */
    public function testRenderOpenTagWithElementWithoutName(): void
    {
        $element = $this->getMockBuilder(Button::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::once())
            ->method('getName')
            ->willReturn(null);
        $element->expects(self::never())
            ->method('getValue');
        $element->expects(self::never())
            ->method('getAttributes');
        $element->expects(self::never())
            ->method('getAttribute');
        $element->expects(self::never())
            ->method('getLabel');
        $element->expects(self::never())
            ->method('getLabelOption');

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::never())
            ->method('__invoke');

        $escapeHtmlAttr = $this->getMockBuilder(EscapeHtmlAttr::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtmlAttr->expects(self::never())
            ->method('__invoke');

        $doctype = $this->getMockBuilder(Doctype::class)
            ->disableOriginalConstructor()
            ->getMock();
        $doctype->expects(self::never())
            ->method('__invoke');

        $helper = new FormButton($escapeHtml, $escapeHtmlAttr, $doctype, null);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage(
            sprintf(
                '%s requires that the element has an assigned name; none discovered',
                'Mimmi20\LaminasView\BootstrapForm\FormButton::openTag'
            )
        );
        $this->expectExceptionCode(0);
        $helper->openTag($element);
    }

    /**
     * @throws InvalidArgumentException
     * @throws Exception
     * @throws \Laminas\Form\Exception\InvalidArgumentException
     * @throws DomainException
     */
    public function testRenderOpenTagWithElementWithoutValue(): void
    {
        $type = 'button';
        $name = 'test-button';

        $expected = sprintf('<button type="%s" name="%s">', $type, $name);

        $element = $this->getMockBuilder(Button::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::once())
            ->method('getName')
            ->willReturn($name);
        $element->expects(self::once())
            ->method('getValue')
            ->willReturn(null);
        $element->expects(self::once())
            ->method('getAttributes')
            ->willReturn(['type' => $type]);
        $element->expects(self::once())
            ->method('getAttribute')
            ->with('type')
            ->willReturn($type);
        $element->expects(self::never())
            ->method('getLabel');
        $element->expects(self::never())
            ->method('getLabelOption');

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::never())
            ->method('__invoke');

        $escapeHtmlAttr = $this->getMockBuilder(EscapeHtmlAttr::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtmlAttr->expects(self::never())
            ->method('__invoke');

        $doctype = $this->getMockBuilder(Doctype::class)
            ->disableOriginalConstructor()
            ->getMock();
        $doctype->expects(self::never())
            ->method('__invoke');

        $helper = new FormButton($escapeHtml, $escapeHtmlAttr, $doctype, null);

        self::assertSame($expected, $helper->openTag($element));
    }

    /**
     * @throws InvalidArgumentException
     * @throws Exception
     * @throws \Laminas\Form\Exception\InvalidArgumentException
     * @throws DomainException
     */
    public function testRenderOpenTagWithElementWithValue(): void
    {
        $type  = 'button';
        $name  = 'test-button';
        $value = 'test-value';

        $expected = sprintf('<button type="%s" name="%s" value="%s">', $type, $name, $value);

        $element = $this->getMockBuilder(Button::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::once())
            ->method('getName')
            ->willReturn($name);
        $element->expects(self::once())
            ->method('getValue')
            ->willReturn($value);
        $element->expects(self::once())
            ->method('getAttributes')
            ->willReturn(['type' => $type]);
        $element->expects(self::once())
            ->method('getAttribute')
            ->with('type')
            ->willReturn($type);
        $element->expects(self::never())
            ->method('getLabel');
        $element->expects(self::never())
            ->method('getLabelOption');

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::never())
            ->method('__invoke');

        $escapeHtmlAttr = $this->getMockBuilder(EscapeHtmlAttr::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtmlAttr->expects(self::never())
            ->method('__invoke');

        $doctype = $this->getMockBuilder(Doctype::class)
            ->disableOriginalConstructor()
            ->getMock();
        $doctype->expects(self::never())
            ->method('__invoke');

        $helper = new FormButton($escapeHtml, $escapeHtmlAttr, $doctype, null);

        self::assertSame($expected, $helper->openTag($element));
    }

    /**
     * @throws InvalidArgumentException
     * @throws Exception
     * @throws \Laminas\Form\Exception\InvalidArgumentException
     * @throws DomainException
     */
    public function testRenderOpenTagWithElementWithoutType(): void
    {
        $type  = 'submit';
        $name  = 'test-button';
        $value = 'test-value';

        $expected = sprintf('<button name="%s" type="%s" value="%s">', $name, $type, $value);

        $element = $this->getMockBuilder(Button::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::once())
            ->method('getName')
            ->willReturn($name);
        $element->expects(self::once())
            ->method('getValue')
            ->willReturn($value);
        $element->expects(self::once())
            ->method('getAttributes')
            ->willReturn([]);
        $element->expects(self::once())
            ->method('getAttribute')
            ->with('type')
            ->willReturn(null);
        $element->expects(self::never())
            ->method('getLabel');
        $element->expects(self::never())
            ->method('getLabelOption');

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::never())
            ->method('__invoke');

        $escapeHtmlAttr = $this->getMockBuilder(EscapeHtmlAttr::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtmlAttr->expects(self::never())
            ->method('__invoke');

        $doctype = $this->getMockBuilder(Doctype::class)
            ->disableOriginalConstructor()
            ->getMock();
        $doctype->expects(self::never())
            ->method('__invoke');

        $helper = new FormButton($escapeHtml, $escapeHtmlAttr, $doctype, null);

        self::assertSame($expected, $helper->openTag($element));
    }

    /**
     * @throws InvalidArgumentException
     * @throws Exception
     * @throws \Laminas\Form\Exception\InvalidArgumentException
     * @throws DomainException
     */
    public function testRenderOpenTagWithElementWithWrongType(): void
    {
        $type  = 'submit';
        $name  = 'test-button';
        $value = 'test-value';

        $expected = sprintf('<button name="%s" type="%s" value="%s">', $name, $type, $value);

        $element = $this->getMockBuilder(Button::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::once())
            ->method('getName')
            ->willReturn($name);
        $element->expects(self::once())
            ->method('getValue')
            ->willReturn($value);
        $element->expects(self::once())
            ->method('getAttributes')
            ->willReturn([]);
        $element->expects(self::once())
            ->method('getAttribute')
            ->with('type')
            ->willReturn('does-not-exist');
        $element->expects(self::never())
            ->method('getLabel');
        $element->expects(self::never())
            ->method('getLabelOption');

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::never())
            ->method('__invoke');

        $escapeHtmlAttr = $this->getMockBuilder(EscapeHtmlAttr::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtmlAttr->expects(self::never())
            ->method('__invoke');

        $doctype = $this->getMockBuilder(Doctype::class)
            ->disableOriginalConstructor()
            ->getMock();
        $doctype->expects(self::never())
            ->method('__invoke');

        $helper = new FormButton($escapeHtml, $escapeHtmlAttr, $doctype, null);

        self::assertSame($expected, $helper->openTag($element));
    }

    /**
     * @throws Exception
     * @throws \Laminas\Form\Exception\InvalidArgumentException
     * @throws DomainException
     */
    public function testRenderWithoutLabel(): void
    {
        $name  = 'test-button';
        $value = 'test-value';

        $element = $this->getMockBuilder(Button::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::once())
            ->method('getName')
            ->willReturn($name);
        $element->expects(self::once())
            ->method('getValue')
            ->willReturn($value);
        $element->expects(self::once())
            ->method('getAttributes')
            ->willReturn([]);
        $element->expects(self::once())
            ->method('getAttribute')
            ->with('type')
            ->willReturn('does-not-exist');
        $element->expects(self::once())
            ->method('getLabel')
            ->willReturn(null);
        $element->expects(self::never())
            ->method('getLabelOption');

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::never())
            ->method('__invoke');

        $escapeHtmlAttr = $this->getMockBuilder(EscapeHtmlAttr::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtmlAttr->expects(self::never())
            ->method('__invoke');

        $doctype = $this->getMockBuilder(Doctype::class)
            ->disableOriginalConstructor()
            ->getMock();
        $doctype->expects(self::never())
            ->method('__invoke');

        $helper = new FormButton($escapeHtml, $escapeHtmlAttr, $doctype, null);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage(
            sprintf(
                '%s expects either button content as the second argument, or that the element provided has a label value; neither found',
                'Mimmi20\LaminasView\BootstrapForm\FormButton::render'
            )
        );
        $this->expectExceptionCode(0);
        $helper->render($element);
    }

    /**
     * @throws InvalidArgumentException
     * @throws Exception
     * @throws \Laminas\Form\Exception\InvalidArgumentException
     * @throws DomainException
     */
    public function testRenderWithTranslator(): void
    {
        $type                  = 'button';
        $name                  = 'test-button';
        $value                 = 'test-value';
        $textDomain            = 'text-domain';
        $label                 = 'test-label';
        $tranlatedLabel        = 'test-label-translated';
        $escapedTranlatedLabel = 'test-label-translated-escaped';

        $expected = sprintf('<button type="%s" name="%s" value="%s">%s</button>', $type, $name, $value, $escapedTranlatedLabel);

        $element = $this->getMockBuilder(Button::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::once())
            ->method('getName')
            ->willReturn($name);
        $element->expects(self::once())
            ->method('getValue')
            ->willReturn($value);
        $element->expects(self::once())
            ->method('getAttributes')
            ->willReturn(['type' => $type]);
        $element->expects(self::once())
            ->method('getAttribute')
            ->with('type')
            ->willReturn($type);
        $element->expects(self::once())
            ->method('getLabel')
            ->willReturn($label);
        $element->expects(self::once())
            ->method('getLabelOption')
            ->with('disable_html_escape')
            ->willReturn(false);

        $translator = $this->getMockBuilder(Translate::class)
            ->disableOriginalConstructor()
            ->getMock();
        $translator->expects(self::once())
            ->method('__invoke')
            ->with($label, $textDomain)
            ->willReturn($tranlatedLabel);

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::once())
            ->method('__invoke')
            ->with($tranlatedLabel)
            ->willReturn($escapedTranlatedLabel);

        $escapeHtmlAttr = $this->getMockBuilder(EscapeHtmlAttr::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtmlAttr->expects(self::never())
            ->method('__invoke');

        $doctype = $this->getMockBuilder(Doctype::class)
            ->disableOriginalConstructor()
            ->getMock();
        $doctype->expects(self::never())
            ->method('__invoke');

        $helper = new FormButton($escapeHtml, $escapeHtmlAttr, $doctype, $translator);

        $helper->setTranslatorTextDomain($textDomain);

        self::assertSame($expected, $helper->render($element));
    }

    /**
     * @throws InvalidArgumentException
     * @throws Exception
     */
    public function testInvokeWithTranslator1(): void
    {
        $type                  = 'button';
        $name                  = 'test-button';
        $value                 = 'test-value';
        $textDomain            = 'text-domain';
        $label                 = 'test-label';
        $tranlatedLabel        = 'test-label-translated';
        $escapedTranlatedLabel = 'test-label-translated-escaped';

        $expected = sprintf('<button type="%s" name="%s" value="%s">%s</button>', $type, $name, $value, $escapedTranlatedLabel);

        $element = $this->getMockBuilder(Button::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::once())
            ->method('getName')
            ->willReturn($name);
        $element->expects(self::once())
            ->method('getValue')
            ->willReturn($value);
        $element->expects(self::once())
            ->method('getAttributes')
            ->willReturn(['type' => $type]);
        $element->expects(self::once())
            ->method('getAttribute')
            ->with('type')
            ->willReturn($type);
        $element->expects(self::once())
            ->method('getLabel')
            ->willReturn($label);
        $element->expects(self::once())
            ->method('getLabelOption')
            ->with('disable_html_escape')
            ->willReturn(false);

        $translator = $this->getMockBuilder(Translate::class)
            ->disableOriginalConstructor()
            ->getMock();
        $translator->expects(self::once())
            ->method('__invoke')
            ->with($label, $textDomain)
            ->willReturn($tranlatedLabel);

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::once())
            ->method('__invoke')
            ->with($tranlatedLabel)
            ->willReturn($escapedTranlatedLabel);

        $escapeHtmlAttr = $this->getMockBuilder(EscapeHtmlAttr::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtmlAttr->expects(self::never())
            ->method('__invoke');

        $doctype = $this->getMockBuilder(Doctype::class)
            ->disableOriginalConstructor()
            ->getMock();
        $doctype->expects(self::never())
            ->method('__invoke');

        $helper = new FormButton($escapeHtml, $escapeHtmlAttr, $doctype, $translator);

        $helper->setTranslatorTextDomain($textDomain);

        self::assertSame($expected, $helper($element));
    }

    /**
     * @throws InvalidArgumentException
     * @throws Exception
     * @throws \Laminas\Form\Exception\InvalidArgumentException
     * @throws DomainException
     */
    public function testInvokeWithTranslator2(): void
    {
        $type                  = 'button';
        $name                  = 'test-button';
        $value                 = 'test-value';
        $textDomain            = 'text-domain';
        $label                 = 'test-label';
        $tranlatedLabel        = 'test-label-translated';
        $escapedTranlatedLabel = 'test-label-translated-escaped';

        $expected = sprintf('<button type="%s" name="%s" value="%s">%s</button>', $type, $name, $value, $escapedTranlatedLabel);

        $element = $this->getMockBuilder(Button::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::once())
            ->method('getName')
            ->willReturn($name);
        $element->expects(self::once())
            ->method('getValue')
            ->willReturn($value);
        $element->expects(self::once())
            ->method('getAttributes')
            ->willReturn(['type' => $type]);
        $element->expects(self::once())
            ->method('getAttribute')
            ->with('type')
            ->willReturn($type);
        $element->expects(self::once())
            ->method('getLabel')
            ->willReturn($label);
        $element->expects(self::once())
            ->method('getLabelOption')
            ->with('disable_html_escape')
            ->willReturn(false);

        $translator = $this->getMockBuilder(Translate::class)
            ->disableOriginalConstructor()
            ->getMock();
        $translator->expects(self::once())
            ->method('__invoke')
            ->with($label, $textDomain)
            ->willReturn($tranlatedLabel);

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::once())
            ->method('__invoke')
            ->with($tranlatedLabel)
            ->willReturn($escapedTranlatedLabel);

        $escapeHtmlAttr = $this->getMockBuilder(EscapeHtmlAttr::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtmlAttr->expects(self::never())
            ->method('__invoke');

        $doctype = $this->getMockBuilder(Doctype::class)
            ->disableOriginalConstructor()
            ->getMock();
        $doctype->expects(self::never())
            ->method('__invoke');

        $helper = new FormButton($escapeHtml, $escapeHtmlAttr, $doctype, $translator);

        $helper->setTranslatorTextDomain($textDomain);

        $helperObject = $helper();

        assert($helperObject instanceof FormButton);

        self::assertSame($expected, $helperObject->render($element));
    }

    /**
     * @throws InvalidArgumentException
     * @throws Exception
     */
    public function testSetGetIndent1(): void
    {
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

        $escapeHtmlAttr = $this->getMockBuilder(EscapeHtmlAttr::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtmlAttr->expects(self::never())
            ->method('__invoke');

        $doctype = $this->getMockBuilder(Doctype::class)
            ->disableOriginalConstructor()
            ->getMock();
        $doctype->expects(self::never())
            ->method('__invoke');

        $helper = new FormButton($escapeHtml, $escapeHtmlAttr, $doctype, $translator);

        self::assertSame($helper, $helper->setIndent(4));
        self::assertSame('    ', $helper->getIndent());
    }

    /**
     * @throws InvalidArgumentException
     * @throws Exception
     */
    public function testSetGetIndent2(): void
    {
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

        $escapeHtmlAttr = $this->getMockBuilder(EscapeHtmlAttr::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtmlAttr->expects(self::never())
            ->method('__invoke');

        $doctype = $this->getMockBuilder(Doctype::class)
            ->disableOriginalConstructor()
            ->getMock();
        $doctype->expects(self::never())
            ->method('__invoke');

        $helper = new FormButton($escapeHtml, $escapeHtmlAttr, $doctype, $translator);

        self::assertSame($helper, $helper->setIndent('  '));
        self::assertSame('  ', $helper->getIndent());
    }
}
