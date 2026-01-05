<?php

/**
 * This file is part of the mimmi20/laminasviewrenderer-bootstrap-form package.
 *
 * Copyright (c) 2021-2026, Thomas Mueller <mimmi20@live.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Mimmi20Test\LaminasView\BootstrapForm;

use Laminas\Form\Element\Button;
use Laminas\Form\Exception\DomainException;
use Laminas\Form\Exception\ExceptionInterface;
use Laminas\Form\Exception\InvalidArgumentException;
use Laminas\Form\Fieldset;
use Laminas\Form\FormInterface;
use Laminas\ServiceManager\Exception\InvalidServiceException;
use Laminas\ServiceManager\Exception\ServiceNotFoundException;
use Laminas\View\Exception\RuntimeException;
use Laminas\View\Helper\Doctype;
use Laminas\View\Helper\EscapeHtml;
use Laminas\View\Helper\EscapeHtmlAttr;
use Laminas\View\Helper\Escaper\AbstractHelper;
use Laminas\View\Helper\HelperInterface;
use Laminas\View\Renderer\PhpRenderer;
use Mimmi20\LaminasView\BootstrapForm\Form;
use Mimmi20\LaminasView\BootstrapForm\FormCollectionInterface;
use Mimmi20\LaminasView\BootstrapForm\FormRowInterface;
use PHPUnit\Event\NoPreviousThrowableException;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;

use const PHP_EOL;

#[Group('form-tel')]
final class FormTest extends TestCase
{
    /**
     * @throws Exception
     * @throws DomainException
     * @throws RuntimeException
     * @throws InvalidArgumentException
     * @throws \Laminas\View\Exception\InvalidArgumentException
     * @throws InvalidServiceException
     * @throws ServiceNotFoundException
     * @throws NoPreviousThrowableException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testRender1(): void
    {
        $helper = new Form();

        $expected = PHP_EOL . '<form action="" method="get">' . PHP_EOL . '</form>' . PHP_EOL;

        $form = $this->createMock(\Laminas\Form\Form::class);
        $form->expects(self::never())
            ->method('getName');
        $form->expects(self::never())
            ->method('getValue');
        $form->expects(self::once())
            ->method('getAttributes')
            ->willReturn([]);
        $form->expects(self::once())
            ->method('getAttribute')
            ->willReturn(null);
        $form->expects(self::never())
            ->method('getLabel');
        $form->expects(self::exactly(5))
            ->method('getOption')
            ->willReturn(null);

        self::assertSame($expected, $helper->render($form));
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws RuntimeException
     * @throws InvalidArgumentException
     * @throws \Laminas\View\Exception\InvalidArgumentException
     * @throws InvalidServiceException
     * @throws ServiceNotFoundException
     * @throws NoPreviousThrowableException
     * @throws \PHPUnit\Framework\MockObject\Exception
     * @throws ExceptionInterface
     */
    public function testRender2(): void
    {
        $helper = new Form();

        $name     = 'test-name';
        $expected = PHP_EOL . '<form action="" method="POST" role="form" class="">' . PHP_EOL . '    <div>'
            . PHP_EOL . '' . PHP_EOL . '    </div>' . PHP_EOL . '</form>' . PHP_EOL;

        /** @var FormInterface<object> $form */
        $form = new \Laminas\Form\Form();

        $element = $this->createMock(Button::class);
        $element->expects(self::exactly(3))
            ->method('setOption');
        $element->expects(self::exactly(2))
            ->method('getName')
            ->willReturn($name);
        $element->expects(self::never())
            ->method('setName');

        $form->add($element);

        self::assertSame($expected, $helper->render($form));
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws RuntimeException
     * @throws InvalidArgumentException
     * @throws \Laminas\View\Exception\InvalidArgumentException
     * @throws InvalidServiceException
     * @throws ServiceNotFoundException
     * @throws NoPreviousThrowableException
     * @throws \PHPUnit\Framework\MockObject\Exception
     * @throws ExceptionInterface
     */
    public function testRender3(): void
    {
        $helper = new Form();

        $name1            = 'test-name-1';
        $name2            = 'test-name-2';
        $indent           = '<!-- -->';
        $expectedFieldset = '<fieldset>';
        $expectedButton   = '<button>';
        $expected         = PHP_EOL . $indent . '<form action="" method="POST" role="form" class="">' . PHP_EOL . '<button>'
            . PHP_EOL . '<fieldset>' . PHP_EOL . $indent . '</form>' . PHP_EOL;

        /** @var FormInterface<object> $form */
        $form = new \Laminas\Form\Form();

        $element1 = $this->createMock(Button::class);
        $element1->expects(self::exactly(3))
            ->method('setOption');
        $element1->expects(self::once())
            ->method('getName')
            ->willReturn($name1);
        $element1->expects(self::never())
            ->method('setName');

        $form->add($element1);

        $element2 = $this->createMock(Fieldset::class);
        $element2->expects(self::exactly(3))
            ->method('setOption');
        $element2->expects(self::once())
            ->method('getName')
            ->willReturn($name2);
        $element2->expects(self::never())
            ->method('setName');

        $form->add($element2);

        $elementHelper = $this->createMock(FormRowInterface::class);
        $elementHelper->expects(self::once())
            ->method('setIndent')
            ->with($indent . '    ');
        $elementHelper->expects(self::once())
            ->method('render')
            ->with($element1)
            ->willReturn($expectedButton);

        $fieldsetHelper = $this->createMock(FormCollectionInterface::class);
        $fieldsetHelper->expects(self::once())
            ->method('setIndent')
            ->with($indent . '    ');
        $fieldsetHelper->expects(self::once())
            ->method('setShouldWrap')
            ->with(true);
        $fieldsetHelper->expects(self::once())
            ->method('render')
            ->with($element2)
            ->willReturn($expectedFieldset);

        $helper->setElementHelper($elementHelper);
        $helper->setFieldsetHelper($fieldsetHelper);
        $helper->setIndent($indent);

        self::assertSame($expected, $helper->render($form));
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws RuntimeException
     * @throws InvalidArgumentException
     * @throws \Laminas\View\Exception\InvalidArgumentException
     * @throws InvalidServiceException
     * @throws ServiceNotFoundException
     * @throws NoPreviousThrowableException
     * @throws \PHPUnit\Framework\MockObject\Exception
     * @throws ExceptionInterface
     */
    public function testRender4(): void
    {
        $helper = new Form();

        $name1            = 'test-name-1';
        $name2            = 'test-name-2';
        $indent           = '<!-- -->';
        $expectedFieldset = '<fieldset>';
        $expectedButton   = '<button>';
        $expected         = PHP_EOL . $indent . '<form class-escaped="" nameEscaped="" typeEscaped="url-escaped" valueEscaped="">' . PHP_EOL . '<button>'
            . PHP_EOL . '<fieldset>' . PHP_EOL . $indent . '</form>' . PHP_EOL;

        /** @var FormInterface<object> $form */
        $form = new \Laminas\Form\Form();

        $element1 = $this->createMock(Button::class);
        $element1->expects(self::exactly(3))
            ->method('setOption');
        $element1->expects(self::once())
            ->method('getName')
            ->willReturn($name1);
        $element1->expects(self::never())
            ->method('setName');

        $form->add($element1);

        $element2 = $this->createMock(Fieldset::class);
        $element2->expects(self::exactly(3))
            ->method('setOption');
        $element2->expects(self::once())
            ->method('getName')
            ->willReturn($name2);
        $element2->expects(self::never())
            ->method('setName');

        $form->add($element2);

        $elementHelper = $this->createMock(FormRowInterface::class);
        $elementHelper->expects(self::once())
            ->method('setIndent')
            ->with($indent . '    ');
        $elementHelper->expects(self::once())
            ->method('render')
            ->with($element1)
            ->willReturn($expectedButton);

        $fieldsetHelper = $this->createMock(FormCollectionInterface::class);
        $fieldsetHelper->expects(self::once())
            ->method('setIndent')
            ->with($indent . '    ');
        $fieldsetHelper->expects(self::once())
            ->method('setShouldWrap')
            ->with(true);
        $fieldsetHelper->expects(self::once())
            ->method('render')
            ->with($element2)
            ->willReturn($expectedFieldset);

        $helper->setIndent($indent);

        $escapeHtml = $this->createMock(EscapeHtml::class);
        $matcher    = self::exactly(4);
        $escapeHtml->expects($matcher)
            ->method('__invoke')
            ->willReturnCallback(
                static function (string $value, int $recurse = AbstractHelper::RECURSE_NONE) use ($matcher): string {
                    $invocation = $matcher->numberOfInvocations();

                    match ($invocation) {
                        1 => self::assertSame(
                            'action',
                            $value,
                            (string) $invocation,
                        ),
                        2 => self::assertSame(
                            'method',
                            $value,
                            (string) $invocation,
                        ),
                        3 => self::assertSame(
                            'role',
                            $value,
                            (string) $invocation,
                        ),
                        4 => self::assertSame(
                            'class',
                            $value,
                            (string) $invocation,
                        ),
                        default => self::assertSame(
                            '',
                            $value,
                            (string) $invocation,
                        ),
                    };

                    self::assertSame(AbstractHelper::RECURSE_NONE, $recurse, (string) $invocation);

                    return match ($invocation) {
                        1 => 'class-escaped',
                        2 => 'nameEscaped',
                        3 => 'typeEscaped',
                        4 => 'valueEscaped',
                        default => '',
                    };
                },
            );

        $escapeHtmlAttr = $this->createMock(EscapeHtmlAttr::class);
        $matcher        = self::exactly(4);
        $escapeHtmlAttr->expects($matcher)
            ->method('__invoke')
            ->willReturnCallback(
                static function (string $valueParam, int $recurse = AbstractHelper::RECURSE_NONE) use ($matcher): string {
                    $invocation = $matcher->numberOfInvocations();

                    match ($invocation) {
                        1 => self::assertSame(
                            '',
                            $valueParam,
                            (string) $invocation,
                        ),
                        2 => self::assertSame(
                            'POST',
                            $valueParam,
                            (string) $invocation,
                        ),
                        3 => self::assertSame(
                            'form',
                            $valueParam,
                            (string) $invocation,
                        ),
                        default => self::assertSame(
                            '',
                            $valueParam,
                            (string) $invocation,
                        ),
                    };

                    self::assertSame(AbstractHelper::RECURSE_NONE, $recurse, (string) $invocation);

                    return match ($invocation) {
                        3 => 'url-escaped',
                        default => '',
                    };
                },
            );

        $doctype = $this->createMock(Doctype::class);
        $doctype->expects(self::never())
            ->method('__invoke');
        $doctype->expects(self::never())
            ->method('isXhtml');
        $doctype->expects(self::never())
            ->method('isHtml5');
        $doctype->expects(self::once())
            ->method('getDoctype')
            ->willReturn(Doctype::HTML4_STRICT);

        $renderer = $this->createMock(PhpRenderer::class);
        $renderer->expects(self::never())
            ->method('getHelperPluginManager');
        $matcher = self::exactly(5);
        $renderer->expects($matcher)
            ->method('plugin')
            ->willReturnCallback(
                static function (string $name, array | null $options = null) use ($matcher, $escapeHtml, $escapeHtmlAttr, $doctype, $elementHelper, $fieldsetHelper): HelperInterface | null {
                    $invocation = $matcher->numberOfInvocations();

                    match ($invocation) {
                        1 => self::assertSame(
                            'doctype',
                            $name,
                            (string) $invocation,
                        ),
                        2 => self::assertSame(
                            'escapehtml',
                            $name,
                            (string) $invocation,
                        ),
                        3 => self::assertSame(
                            'escapehtmlattr',
                            $name,
                            (string) $invocation,
                        ),
                        4 => self::assertSame(
                            'form_row',
                            $name,
                            (string) $invocation,
                        ),
                        5 => self::assertSame(
                            'form_collection',
                            $name,
                            (string) $invocation,
                        ),
                        default => self::assertSame(
                            'class',
                            $name,
                            (string) $invocation,
                        ),
                    };

                    self::assertNull($options);

                    return match ($invocation) {
                        1 => $doctype,
                        2 => $escapeHtml,
                        3 => $escapeHtmlAttr,
                        4 => $elementHelper,
                        5 => $fieldsetHelper,
                        default => null,
                    };
                },
            );
        $renderer->expects(self::never())
            ->method('render');

        $helper->setView($renderer);

        self::assertSame($expected, $helper->render($form));
    }

    /** @throws Exception */
    public function testSetGetIndent1(): void
    {
        $helper = new Form();

        self::assertSame($helper, $helper->setIndent(4));
        self::assertSame('    ', $helper->getIndent());
    }

    /** @throws Exception */
    public function testSetGetIndent2(): void
    {
        $helper = new Form();

        self::assertSame($helper, $helper->setIndent('  '));
        self::assertSame('  ', $helper->getIndent());
    }
}
