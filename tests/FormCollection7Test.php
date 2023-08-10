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
use Laminas\Form\ElementInterface;
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

final class FormCollection7Test extends TestCase
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
    public function testRenderWithCollectionAndElementsAndOptionsAndTranslator6(): void
    {
        $form               = 'test-form';
        $layout             = Form::LAYOUT_HORIZONTAL;
        $floating           = true;
        $indent             = '<!-- -->  ';
        $textDomain         = 'test-domain';
        $templateAttributes = ['class' => 'template-class'];

        $element = $this->getMockBuilder(Collection::class)
            ->disableOriginalConstructor()
            ->getMock();

        $textElement = $this->getMockBuilder(Text::class)
            ->disableOriginalConstructor()
            ->getMock();
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

        $templateList = new PriorityList();

        $templateElement = $this->getMockBuilder(Collection::class)
            ->disableOriginalConstructor()
            ->getMock();
        $matcher         = self::exactly(3);
        $templateElement->expects($matcher)
            ->method('getOption')
            ->willReturnCallback(
                static function (string $option) use ($matcher, $form, $layout, $floating): mixed {
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
                        default => self::assertSame(
                            'layout',
                            $option,
                            (string) $matcher->numberOfInvocations(),
                        ),
                    };

                    return match ($matcher->numberOfInvocations()) {
                        1 => $form,
                        3 => $floating,
                        default => $layout,
                    };
                },
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
        $renderedTemplate = '<template>template-content</template>';

        $expected = PHP_EOL . $expectedButton . PHP_EOL . $expectedText . PHP_EOL . $indent . '    ' . $renderedTemplate . PHP_EOL;

        $formRow = $this->getMockBuilder(FormRowInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
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

        $element = $this->getMockBuilder(Collection::class)
            ->disableOriginalConstructor()
            ->getMock();

        $textElement = $this->getMockBuilder(Text::class)
            ->disableOriginalConstructor()
            ->getMock();
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

        $templateList = new PriorityList();

        $templateElement = $this->getMockBuilder(Collection::class)
            ->disableOriginalConstructor()
            ->getMock();
        $matcher         = self::exactly(3);
        $templateElement->expects($matcher)
            ->method('getOption')
            ->willReturnCallback(
                static function (string $option) use ($matcher, $form, $layout, $floating): mixed {
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
                        default => self::assertSame(
                            'layout',
                            $option,
                            (string) $matcher->numberOfInvocations(),
                        ),
                    };

                    return match ($matcher->numberOfInvocations()) {
                        1 => $form,
                        3 => $floating,
                        default => $layout,
                    };
                },
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
        $renderedTemplate = '<template>template-content</template>';

        $expected = PHP_EOL . $expectedButton . PHP_EOL . $expectedText . PHP_EOL . $indent . '    ' . $renderedTemplate . PHP_EOL;

        $formRow = $this->getMockBuilder(FormRowInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
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

        $helperObject = $helper();

        assert($helperObject instanceof FormCollection);

        self::assertSame($expected, $helperObject->render($element));
    }
}
