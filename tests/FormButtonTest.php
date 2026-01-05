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
use Laminas\Form\Element\Submit;
use Laminas\Form\Element\Text;
use Laminas\Form\Exception\DomainException;
use Laminas\Form\Exception\InvalidArgumentException;
use Laminas\I18n\Translator\TranslatorInterface as Translator;
use Laminas\View\Helper\Doctype;
use Laminas\View\Helper\EscapeHtml;
use Laminas\View\Helper\EscapeHtmlAttr;
use Laminas\View\Helper\Escaper\AbstractHelper;
use Laminas\View\Helper\HelperInterface;
use Laminas\View\Renderer\PhpRenderer;
use Mimmi20\LaminasView\BootstrapForm\FormButton;
use PHPUnit\Event\NoPreviousThrowableException;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;

use function assert;
use function get_debug_type;
use function sprintf;

#[Group('form-button')]
final class FormButtonTest extends TestCase
{
    /**
     * @throws Exception
     * @throws DomainException
     */
    public function testRenderOpenTagWithNull(): void
    {
        $helper = new FormButton();

        $expected = '<button>';

        self::assertSame($expected, $helper->openTag());
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws NoPreviousThrowableException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testRenderOpenTagWithArray(): void
    {
        $helper = new FormButton();

        $type        = 'test-type';
        $attributes  = ['type' => $type];
        $typeEscaped = 'test-type-escaped';
        $expected    = sprintf('<button typeEscaped="%s" class-escaped="btn-escaped">', $typeEscaped);

        $escapeHtml = $this->createMock(EscapeHtml::class);
        $matcher    = self::exactly(2);
        $escapeHtml->expects($matcher)
            ->method('__invoke')
            ->willReturnCallback(
                static function (string $value, int $recurse = AbstractHelper::RECURSE_NONE) use ($matcher): string {
                    $invocation = $matcher->numberOfInvocations();

                    match ($invocation) {
                        1 => self::assertSame(
                            'type',
                            $value,
                            (string) $invocation,
                        ),
                        2 => self::assertSame(
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
                        1 => 'typeEscaped',
                        2 => 'class-escaped',
                        default => '',
                    };
                },
            );

        $escapeHtmlAttr = $this->createMock(EscapeHtmlAttr::class);
        $matcher        = self::exactly(2);
        $escapeHtmlAttr->expects($matcher)
            ->method('__invoke')
            ->willReturnCallback(
                static function (string $valueParam, int $recurse = AbstractHelper::RECURSE_NONE) use ($matcher, $type, $typeEscaped): string {
                    $invocation = $matcher->numberOfInvocations();

                    match ($invocation) {
                        1 => self::assertSame(
                            $type,
                            $valueParam,
                            (string) $invocation,
                        ),
                        2 => self::assertSame(
                            'btn',
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
                        1 => $typeEscaped,
                        2 => 'btn-escaped',
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
        $doctype->expects(self::never())
            ->method('getDoctype');

        $renderer = $this->createMock(PhpRenderer::class);
        $renderer->expects(self::never())
            ->method('getHelperPluginManager');
        $matcher = self::exactly(3);
        $renderer->expects($matcher)
            ->method('plugin')
            ->willReturnCallback(
                static function (string $name, array | null $options = null) use ($matcher, $escapeHtml, $escapeHtmlAttr, $doctype): HelperInterface | null {
                    $invocation = $matcher->numberOfInvocations();

                    match ($invocation) {
                        1 => self::assertSame(
                            'escapehtml',
                            $name,
                            (string) $invocation,
                        ),
                        2 => self::assertSame(
                            'escapehtmlattr',
                            $name,
                            (string) $invocation,
                        ),
                        3 => self::assertSame(
                            'doctype',
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
                        1 => $escapeHtml,
                        2 => $escapeHtmlAttr,
                        3 => $doctype,
                        default => null,
                    };
                },
            );
        $renderer->expects(self::never())
            ->method('render');

        $helper->setView($renderer);

        self::assertSame($expected, $helper->openTag($attributes));
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws NoPreviousThrowableException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testRenderOpenTagWithArray2(): void
    {
        $helper = new FormButton();

        $type        = 'test-type';
        $attributes  = ['type' => $type, 'class' => 'btn btn-success'];
        $typeEscaped = 'test-type-escaped';
        $expected    = sprintf(
            '<button typeEscaped="%s" class-escaped="btn-escaped btn-success-escaped">',
            $typeEscaped,
        );

        $escapeHtml = $this->createMock(EscapeHtml::class);
        $matcher    = self::exactly(2);
        $escapeHtml->expects($matcher)
            ->method('__invoke')
            ->willReturnCallback(
                static function (string $value, int $recurse = AbstractHelper::RECURSE_NONE) use ($matcher): string {
                    $invocation = $matcher->numberOfInvocations();

                    match ($invocation) {
                        1 => self::assertSame(
                            'type',
                            $value,
                            (string) $invocation,
                        ),
                        2 => self::assertSame(
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
                        1 => 'typeEscaped',
                        2 => 'class-escaped',
                        default => '',
                    };
                },
            );

        $escapeHtmlAttr = $this->createMock(EscapeHtmlAttr::class);
        $matcher        = self::exactly(2);
        $escapeHtmlAttr->expects($matcher)
            ->method('__invoke')
            ->willReturnCallback(
                static function (string $valueParam, int $recurse = AbstractHelper::RECURSE_NONE) use ($matcher, $type, $typeEscaped): string {
                    $invocation = $matcher->numberOfInvocations();

                    match ($invocation) {
                        1 => self::assertSame(
                            $type,
                            $valueParam,
                            (string) $invocation,
                        ),
                        2 => self::assertSame(
                            'btn btn-success',
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
                        1 => $typeEscaped,
                        2 => 'btn-escaped btn-success-escaped',
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
        $doctype->expects(self::never())
            ->method('getDoctype');

        $renderer = $this->createMock(PhpRenderer::class);
        $renderer->expects(self::never())
            ->method('getHelperPluginManager');
        $matcher = self::exactly(3);
        $renderer->expects($matcher)
            ->method('plugin')
            ->willReturnCallback(
                static function (string $name, array | null $options = null) use ($matcher, $escapeHtml, $escapeHtmlAttr, $doctype): HelperInterface | null {
                    $invocation = $matcher->numberOfInvocations();

                    match ($invocation) {
                        1 => self::assertSame(
                            'escapehtml',
                            $name,
                            (string) $invocation,
                        ),
                        2 => self::assertSame(
                            'escapehtmlattr',
                            $name,
                            (string) $invocation,
                        ),
                        3 => self::assertSame(
                            'doctype',
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
                        1 => $escapeHtml,
                        2 => $escapeHtmlAttr,
                        3 => $doctype,
                        default => null,
                    };
                },
            );
        $renderer->expects(self::never())
            ->method('render');

        $helper->setView($renderer);

        self::assertSame($expected, $helper->openTag($attributes));
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws NoPreviousThrowableException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testRenderOpenTagWithElementWithoutName(): void
    {
        $helper = new FormButton();

        $element = $this->createMock(Button::class);
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

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage(
            sprintf(
                '%s requires that the element has an assigned name; none discovered',
                'Mimmi20\LaminasView\BootstrapForm\FormButton::openTag',
            ),
        );
        $this->expectExceptionCode(0);
        $helper->openTag($element);
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws NoPreviousThrowableException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testRenderOpenTagWithElementWithoutValue(): void
    {
        $helper = new FormButton();

        $type        = 'button';
        $name        = 'test-button';
        $typeEscaped = 'button-escaped';
        $nameEscaped = 'test-button-escaped';

        $expected = sprintf(
            '<button typeEscaped="%s" nameEscaped="%s" class-escaped="btn-escaped">',
            $typeEscaped,
            $nameEscaped,
        );

        $element = $this->createMock(Button::class);
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

        $escapeHtml = $this->createMock(EscapeHtml::class);
        $matcher    = self::exactly(3);
        $escapeHtml->expects($matcher)
            ->method('__invoke')
            ->willReturnCallback(
                static function (string $value, int $recurse = AbstractHelper::RECURSE_NONE) use ($matcher): string {
                    $invocation = $matcher->numberOfInvocations();

                    match ($invocation) {
                        1 => self::assertSame(
                            'type',
                            $value,
                            (string) $invocation,
                        ),
                        2 => self::assertSame(
                            'name',
                            $value,
                            (string) $invocation,
                        ),
                        3 => self::assertSame(
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
                        1 => 'typeEscaped',
                        2 => 'nameEscaped',
                        3 => 'class-escaped',
                        default => '',
                    };
                },
            );

        $escapeHtmlAttr = $this->createMock(EscapeHtmlAttr::class);
        $matcher        = self::exactly(3);
        $escapeHtmlAttr->expects($matcher)
            ->method('__invoke')
            ->willReturnCallback(
                static function (string $valueParam, int $recurse = AbstractHelper::RECURSE_NONE) use ($matcher, $type, $typeEscaped, $name, $nameEscaped): string {
                    $invocation = $matcher->numberOfInvocations();

                    match ($invocation) {
                        1 => self::assertSame(
                            $type,
                            $valueParam,
                            (string) $invocation,
                        ),
                        2 => self::assertSame(
                            $name,
                            $valueParam,
                            (string) $invocation,
                        ),
                        3 => self::assertSame(
                            'btn',
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
                        1 => $typeEscaped,
                        2 => $nameEscaped,
                        3 => 'btn-escaped',
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
        $doctype->expects(self::never())
            ->method('getDoctype');

        $renderer = $this->createMock(PhpRenderer::class);
        $renderer->expects(self::never())
            ->method('getHelperPluginManager');
        $matcher = self::exactly(3);
        $renderer->expects($matcher)
            ->method('plugin')
            ->willReturnCallback(
                static function (string $name, array | null $options = null) use ($matcher, $escapeHtml, $escapeHtmlAttr, $doctype): HelperInterface | null {
                    $invocation = $matcher->numberOfInvocations();

                    match ($invocation) {
                        1 => self::assertSame(
                            'escapehtml',
                            $name,
                            (string) $invocation,
                        ),
                        2 => self::assertSame(
                            'escapehtmlattr',
                            $name,
                            (string) $invocation,
                        ),
                        3 => self::assertSame(
                            'doctype',
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
                        1 => $escapeHtml,
                        2 => $escapeHtmlAttr,
                        3 => $doctype,
                        default => null,
                    };
                },
            );
        $renderer->expects(self::never())
            ->method('render');

        $helper->setView($renderer);

        self::assertSame($expected, $helper->openTag($element));
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws NoPreviousThrowableException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testRenderOpenTagWithElementWithValue(): void
    {
        $helper = new FormButton();

        $type         = 'button';
        $name         = 'test-button';
        $value        = 'test-value';
        $typeEscaped  = 'button-escaped';
        $nameEscaped  = 'test-button-escaped';
        $valueEscaped = 'test-value-escaped';

        $expected = sprintf(
            '<button typeEscaped="%s" nameEscaped="%s" class-escaped="btn-escaped" valueEscaped="%s">',
            $typeEscaped,
            $nameEscaped,
            $valueEscaped,
        );

        $element = $this->createMock(Button::class);
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

        $escapeHtml = $this->createMock(EscapeHtml::class);
        $matcher    = self::exactly(4);
        $escapeHtml->expects($matcher)
            ->method('__invoke')
            ->willReturnCallback(
                static function (string $value, int $recurse = AbstractHelper::RECURSE_NONE) use ($matcher): string {
                    $invocation = $matcher->numberOfInvocations();

                    match ($invocation) {
                        1 => self::assertSame(
                            'type',
                            $value,
                            (string) $invocation,
                        ),
                        2 => self::assertSame(
                            'name',
                            $value,
                            (string) $invocation,
                        ),
                        3 => self::assertSame(
                            'class',
                            $value,
                            (string) $invocation,
                        ),
                        4 => self::assertSame(
                            'value',
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
                        1 => 'typeEscaped',
                        2 => 'nameEscaped',
                        3 => 'class-escaped',
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
                static function (string $valueParam, int $recurse = AbstractHelper::RECURSE_NONE) use ($matcher, $type, $typeEscaped, $name, $nameEscaped, $value, $valueEscaped): string {
                    $invocation = $matcher->numberOfInvocations();

                    match ($invocation) {
                        1 => self::assertSame(
                            $type,
                            $valueParam,
                            (string) $invocation,
                        ),
                        2 => self::assertSame(
                            $name,
                            $valueParam,
                            (string) $invocation,
                        ),
                        3 => self::assertSame(
                            'btn',
                            $valueParam,
                            (string) $invocation,
                        ),
                        4 => self::assertSame(
                            $value,
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
                        1 => $typeEscaped,
                        2 => $nameEscaped,
                        3 => 'btn-escaped',
                        4 => $valueEscaped,
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
        $doctype->expects(self::never())
            ->method('getDoctype');

        $renderer = $this->createMock(PhpRenderer::class);
        $renderer->expects(self::never())
            ->method('getHelperPluginManager');
        $matcher = self::exactly(3);
        $renderer->expects($matcher)
            ->method('plugin')
            ->willReturnCallback(
                static function (string $name, array | null $options = null) use ($matcher, $escapeHtml, $escapeHtmlAttr, $doctype): HelperInterface | null {
                    $invocation = $matcher->numberOfInvocations();

                    match ($invocation) {
                        1 => self::assertSame(
                            'escapehtml',
                            $name,
                            (string) $invocation,
                        ),
                        2 => self::assertSame(
                            'escapehtmlattr',
                            $name,
                            (string) $invocation,
                        ),
                        3 => self::assertSame(
                            'doctype',
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
                        1 => $escapeHtml,
                        2 => $escapeHtmlAttr,
                        3 => $doctype,
                        default => null,
                    };
                },
            );
        $renderer->expects(self::never())
            ->method('render');

        $helper->setView($renderer);

        self::assertSame($expected, $helper->openTag($element));
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws NoPreviousThrowableException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testRenderOpenTagWithElementWithoutType(): void
    {
        $helper = new FormButton();

        $type         = 'submit';
        $name         = 'test-button';
        $value        = 'test-value';
        $typeEscaped  = 'button-escaped';
        $nameEscaped  = 'test-button-escaped';
        $valueEscaped = 'test-value-escaped';

        $expected = sprintf(
            '<button nameEscaped="%s" typeEscaped="%s" class-escaped="btn-escaped" valueEscaped="%s">',
            $nameEscaped,
            $typeEscaped,
            $valueEscaped,
        );

        $element = $this->createMock(Button::class);
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

        $escapeHtml = $this->createMock(EscapeHtml::class);
        $matcher    = self::exactly(4);
        $escapeHtml->expects($matcher)
            ->method('__invoke')
            ->willReturnCallback(
                static function (string $value, int $recurse = AbstractHelper::RECURSE_NONE) use ($matcher): string {
                    $invocation = $matcher->numberOfInvocations();

                    match ($invocation) {
                        1 => self::assertSame(
                            'name',
                            $value,
                            (string) $invocation,
                        ),
                        2 => self::assertSame(
                            'type',
                            $value,
                            (string) $invocation,
                        ),
                        3 => self::assertSame(
                            'class',
                            $value,
                            (string) $invocation,
                        ),
                        4 => self::assertSame(
                            'value',
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
                        1 => 'nameEscaped',
                        2 => 'typeEscaped',
                        3 => 'class-escaped',
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
                static function (string $valueParam, int $recurse = AbstractHelper::RECURSE_NONE) use ($matcher, $type, $typeEscaped, $name, $nameEscaped, $value, $valueEscaped): string {
                    $invocation = $matcher->numberOfInvocations();

                    match ($invocation) {
                        1 => self::assertSame(
                            $name,
                            $valueParam,
                            (string) $invocation,
                        ),
                        2 => self::assertSame(
                            $type,
                            $valueParam,
                            (string) $invocation,
                        ),
                        3 => self::assertSame(
                            'btn',
                            $valueParam,
                            (string) $invocation,
                        ),
                        4 => self::assertSame(
                            $value,
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
                        1 => $nameEscaped,
                        2 => $typeEscaped,
                        3 => 'btn-escaped',
                        4 => $valueEscaped,
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
        $doctype->expects(self::never())
            ->method('getDoctype');

        $renderer = $this->createMock(PhpRenderer::class);
        $renderer->expects(self::never())
            ->method('getHelperPluginManager');
        $matcher = self::exactly(3);
        $renderer->expects($matcher)
            ->method('plugin')
            ->willReturnCallback(
                static function (string $name, array | null $options = null) use ($matcher, $escapeHtml, $escapeHtmlAttr, $doctype): HelperInterface | null {
                    $invocation = $matcher->numberOfInvocations();

                    match ($invocation) {
                        1 => self::assertSame(
                            'escapehtml',
                            $name,
                            (string) $invocation,
                        ),
                        2 => self::assertSame(
                            'escapehtmlattr',
                            $name,
                            (string) $invocation,
                        ),
                        3 => self::assertSame(
                            'doctype',
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
                        1 => $escapeHtml,
                        2 => $escapeHtmlAttr,
                        3 => $doctype,
                        default => null,
                    };
                },
            );
        $renderer->expects(self::never())
            ->method('render');

        $helper->setView($renderer);

        self::assertSame($expected, $helper->openTag($element));
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws NoPreviousThrowableException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testRenderOpenTagWithElementWithWrongType(): void
    {
        $helper = new FormButton();

        $type         = 'submit';
        $name         = 'test-button';
        $value        = 'test-value';
        $typeEscaped  = 'button-escaped';
        $nameEscaped  = 'test-button-escaped';
        $valueEscaped = 'test-value-escaped';

        $expected = sprintf(
            '<button nameEscaped="%s" typeEscaped="%s" class-escaped="btn-escaped" valueEscaped="%s">',
            $nameEscaped,
            $typeEscaped,
            $valueEscaped,
        );

        $element = $this->createMock(Button::class);
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

        $escapeHtml = $this->createMock(EscapeHtml::class);
        $matcher    = self::exactly(4);
        $escapeHtml->expects($matcher)
            ->method('__invoke')
            ->willReturnCallback(
                static function (string $value, int $recurse = AbstractHelper::RECURSE_NONE) use ($matcher): string {
                    $invocation = $matcher->numberOfInvocations();

                    match ($invocation) {
                        1 => self::assertSame(
                            'name',
                            $value,
                            (string) $invocation,
                        ),
                        2 => self::assertSame(
                            'type',
                            $value,
                            (string) $invocation,
                        ),
                        3 => self::assertSame(
                            'class',
                            $value,
                            (string) $invocation,
                        ),
                        4 => self::assertSame(
                            'value',
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
                        1 => 'nameEscaped',
                        2 => 'typeEscaped',
                        3 => 'class-escaped',
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
                static function (string $valueParam, int $recurse = AbstractHelper::RECURSE_NONE) use ($matcher, $type, $typeEscaped, $name, $nameEscaped, $value, $valueEscaped): string {
                    $invocation = $matcher->numberOfInvocations();

                    match ($invocation) {
                        1 => self::assertSame(
                            $name,
                            $valueParam,
                            (string) $invocation,
                        ),
                        2 => self::assertSame(
                            $type,
                            $valueParam,
                            (string) $invocation,
                        ),
                        3 => self::assertSame(
                            'btn',
                            $valueParam,
                            (string) $invocation,
                        ),
                        4 => self::assertSame(
                            $value,
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
                        1 => $nameEscaped,
                        2 => $typeEscaped,
                        3 => 'btn-escaped',
                        4 => $valueEscaped,
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
        $doctype->expects(self::never())
            ->method('getDoctype');

        $renderer = $this->createMock(PhpRenderer::class);
        $renderer->expects(self::never())
            ->method('getHelperPluginManager');
        $matcher = self::exactly(3);
        $renderer->expects($matcher)
            ->method('plugin')
            ->willReturnCallback(
                static function (string $name, array | null $options = null) use ($matcher, $escapeHtml, $escapeHtmlAttr, $doctype): HelperInterface | null {
                    $invocation = $matcher->numberOfInvocations();

                    match ($invocation) {
                        1 => self::assertSame(
                            'escapehtml',
                            $name,
                            (string) $invocation,
                        ),
                        2 => self::assertSame(
                            'escapehtmlattr',
                            $name,
                            (string) $invocation,
                        ),
                        3 => self::assertSame(
                            'doctype',
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
                        1 => $escapeHtml,
                        2 => $escapeHtmlAttr,
                        3 => $doctype,
                        default => null,
                    };
                },
            );
        $renderer->expects(self::never())
            ->method('render');

        $helper->setView($renderer);

        self::assertSame($expected, $helper->openTag($element));
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws InvalidArgumentException
     * @throws NoPreviousThrowableException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testRenderWithoutLabel(): void
    {
        $helper = new FormButton();

        $element = $this->createMock(Button::class);
        $element->expects(self::never())
            ->method('getName');
        $element->expects(self::never())
            ->method('getValue');
        $element->expects(self::never())
            ->method('getAttributes');
        $element->expects(self::never())
            ->method('getAttribute');
        $element->expects(self::once())
            ->method('getLabel')
            ->willReturn(null);
        $element->expects(self::never())
            ->method('getLabelOption');

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage(
            sprintf(
                '%s expects either button content as the second argument, or that the element provided has a label value; neither found',
                'Mimmi20\LaminasView\BootstrapForm\FormButton::render',
            ),
        );
        $this->expectExceptionCode(0);
        $helper->render($element);
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws InvalidArgumentException
     * @throws NoPreviousThrowableException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testRenderWithTranslator(): void
    {
        $helper = new FormButton();

        $type                  = 'button';
        $name                  = 'test-button';
        $value                 = 'test-value';
        $textDomain            = 'text-domain';
        $label                 = 'test-label';
        $tranlatedLabel        = 'test-label-translated';
        $escapedTranlatedLabel = 'test-label-translated-escaped';
        $typeEscaped           = 'button-escaped';
        $nameEscaped           = 'test-button-escaped';
        $valueEscaped          = 'test-value-escaped';

        $expected = sprintf(
            '<button typeEscaped="%s" nameEscaped="%s" class-escaped="btn-escaped" valueEscaped="%s">%s</button>',
            $typeEscaped,
            $nameEscaped,
            $valueEscaped,
            $escapedTranlatedLabel,
        );

        $element = $this->createMock(Button::class);
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

        $translator = $this->createMock(Translator::class);
        $translator->expects(self::once())
            ->method('translate')
            ->with($label, $textDomain)
            ->willReturn($tranlatedLabel);

        $escapeHtml = $this->createMock(EscapeHtml::class);
        $matcher    = self::exactly(5);
        $escapeHtml->expects($matcher)
            ->method('__invoke')
            ->willReturnCallback(
                static function (string $value, int $recurse = AbstractHelper::RECURSE_NONE) use ($matcher, $tranlatedLabel, $escapedTranlatedLabel): string {
                    $invocation = $matcher->numberOfInvocations();

                    match ($invocation) {
                        1 => self::assertSame(
                            $tranlatedLabel,
                            $value,
                            (string) $invocation,
                        ),
                        2 => self::assertSame(
                            'type',
                            $value,
                            (string) $invocation,
                        ),
                        3 => self::assertSame(
                            'name',
                            $value,
                            (string) $invocation,
                        ),
                        4 => self::assertSame(
                            'class',
                            $value,
                            (string) $invocation,
                        ),
                        5 => self::assertSame(
                            'value',
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
                        1 => $escapedTranlatedLabel,
                        2 => 'typeEscaped',
                        3 => 'nameEscaped',
                        4 => 'class-escaped',
                        5 => 'valueEscaped',
                        default => '',
                    };
                },
            );

        $escapeHtmlAttr = $this->createMock(EscapeHtmlAttr::class);
        $matcher        = self::exactly(4);
        $escapeHtmlAttr->expects($matcher)
            ->method('__invoke')
            ->willReturnCallback(
                static function (string $valueParam, int $recurse = AbstractHelper::RECURSE_NONE) use ($matcher, $type, $typeEscaped, $name, $nameEscaped, $value, $valueEscaped): string {
                    $invocation = $matcher->numberOfInvocations();

                    match ($invocation) {
                        1 => self::assertSame(
                            $type,
                            $valueParam,
                            (string) $invocation,
                        ),
                        2 => self::assertSame(
                            $name,
                            $valueParam,
                            (string) $invocation,
                        ),
                        3 => self::assertSame(
                            'btn',
                            $valueParam,
                            (string) $invocation,
                        ),
                        4 => self::assertSame(
                            $value,
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
                        1 => $typeEscaped,
                        2 => $nameEscaped,
                        3 => 'btn-escaped',
                        4 => $valueEscaped,
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
        $doctype->expects(self::never())
            ->method('getDoctype');

        $renderer = $this->createMock(PhpRenderer::class);
        $renderer->expects(self::never())
            ->method('getHelperPluginManager');
        $matcher = self::exactly(3);
        $renderer->expects($matcher)
            ->method('plugin')
            ->willReturnCallback(
                static function (string $name, array | null $options = null) use ($matcher, $escapeHtml, $escapeHtmlAttr, $doctype): HelperInterface | null {
                    $invocation = $matcher->numberOfInvocations();

                    match ($invocation) {
                        1 => self::assertSame(
                            'escapehtml',
                            $name,
                            (string) $invocation,
                        ),
                        2 => self::assertSame(
                            'escapehtmlattr',
                            $name,
                            (string) $invocation,
                        ),
                        3 => self::assertSame(
                            'doctype',
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
                        1 => $escapeHtml,
                        2 => $escapeHtmlAttr,
                        3 => $doctype,
                        default => null,
                    };
                },
            );
        $renderer->expects(self::never())
            ->method('render');

        $helper->setView($renderer);
        $helper->setTranslator($translator);

        $helper->setTranslatorTextDomain($textDomain);

        self::assertSame($expected, $helper->render($element));
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws ContainerExceptionInterface
     * @throws InvalidArgumentException
     * @throws NoPreviousThrowableException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testInvokeWithTranslator1(): void
    {
        $helper = new FormButton();

        $type                  = 'button';
        $name                  = 'test-button';
        $value                 = 'test-value';
        $textDomain            = 'text-domain';
        $label                 = 'test-label';
        $tranlatedLabel        = 'test-label-translated';
        $escapedTranlatedLabel = 'test-label-translated-escaped';
        $typeEscaped           = 'button-escaped';
        $nameEscaped           = 'test-button-escaped';
        $valueEscaped          = 'test-value-escaped';

        $expected = sprintf(
            '<button typeEscaped="%s" nameEscaped="%s" class-escaped="btn-escaped" valueEscaped="%s">%s</button>',
            $typeEscaped,
            $nameEscaped,
            $valueEscaped,
            $escapedTranlatedLabel,
        );

        $element = $this->createMock(Button::class);
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

        $translator = $this->createMock(Translator::class);
        $translator->expects(self::once())
            ->method('translate')
            ->with($label, $textDomain)
            ->willReturn($tranlatedLabel);

        $escapeHtml = $this->createMock(EscapeHtml::class);
        $matcher    = self::exactly(5);
        $escapeHtml->expects($matcher)
            ->method('__invoke')
            ->willReturnCallback(
                static function (string $value, int $recurse = AbstractHelper::RECURSE_NONE) use ($matcher, $tranlatedLabel, $escapedTranlatedLabel): string {
                    $invocation = $matcher->numberOfInvocations();

                    match ($invocation) {
                        1 => self::assertSame(
                            $tranlatedLabel,
                            $value,
                            (string) $invocation,
                        ),
                        2 => self::assertSame(
                            'type',
                            $value,
                            (string) $invocation,
                        ),
                        3 => self::assertSame(
                            'name',
                            $value,
                            (string) $invocation,
                        ),
                        4 => self::assertSame(
                            'class',
                            $value,
                            (string) $invocation,
                        ),
                        5 => self::assertSame(
                            'value',
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
                        1 => $escapedTranlatedLabel,
                        2 => 'typeEscaped',
                        3 => 'nameEscaped',
                        4 => 'class-escaped',
                        5 => 'valueEscaped',
                        default => '',
                    };
                },
            );

        $escapeHtmlAttr = $this->createMock(EscapeHtmlAttr::class);
        $matcher        = self::exactly(4);
        $escapeHtmlAttr->expects($matcher)
            ->method('__invoke')
            ->willReturnCallback(
                static function (string $valueParam, int $recurse = AbstractHelper::RECURSE_NONE) use ($matcher, $type, $typeEscaped, $name, $nameEscaped, $value, $valueEscaped): string {
                    $invocation = $matcher->numberOfInvocations();

                    match ($invocation) {
                        1 => self::assertSame(
                            $type,
                            $valueParam,
                            (string) $invocation,
                        ),
                        2 => self::assertSame(
                            $name,
                            $valueParam,
                            (string) $invocation,
                        ),
                        3 => self::assertSame(
                            'btn',
                            $valueParam,
                            (string) $invocation,
                        ),
                        4 => self::assertSame(
                            $value,
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
                        1 => $typeEscaped,
                        2 => $nameEscaped,
                        3 => 'btn-escaped',
                        4 => $valueEscaped,
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
        $doctype->expects(self::never())
            ->method('getDoctype');

        $renderer = $this->createMock(PhpRenderer::class);
        $renderer->expects(self::never())
            ->method('getHelperPluginManager');
        $matcher = self::exactly(3);
        $renderer->expects($matcher)
            ->method('plugin')
            ->willReturnCallback(
                static function (string $name, array | null $options = null) use ($matcher, $escapeHtml, $escapeHtmlAttr, $doctype): HelperInterface | null {
                    $invocation = $matcher->numberOfInvocations();

                    match ($invocation) {
                        1 => self::assertSame(
                            'escapehtml',
                            $name,
                            (string) $invocation,
                        ),
                        2 => self::assertSame(
                            'escapehtmlattr',
                            $name,
                            (string) $invocation,
                        ),
                        3 => self::assertSame(
                            'doctype',
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
                        1 => $escapeHtml,
                        2 => $escapeHtmlAttr,
                        3 => $doctype,
                        default => null,
                    };
                },
            );
        $renderer->expects(self::never())
            ->method('render');

        $helper->setView($renderer);
        $helper->setTranslator($translator);
        $helper->setTranslatorTextDomain($textDomain);

        self::assertSame($expected, ($helper)($element));
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws InvalidArgumentException
     * @throws NoPreviousThrowableException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testRenderWithTranslator2(): void
    {
        $helper = new FormButton();

        $type                  = 'button';
        $name                  = 'test-button';
        $value                 = 'test-value';
        $textDomain            = 'text-domain';
        $label                 = 'test-label';
        $tranlatedLabel        = 'test-label-translated';
        $escapedTranlatedLabel = 'test-label-translated-escaped';
        $typeEscaped           = 'button-escaped';
        $nameEscaped           = 'test-button-escaped';
        $valueEscaped          = 'test-value-escaped';

        $expected = sprintf(
            '<button typeEscaped="%s" nameEscaped="%s" class-escaped="btn-escaped" valueEscaped="%s">%s</button>',
            $typeEscaped,
            $nameEscaped,
            $valueEscaped,
            $escapedTranlatedLabel,
        );

        $element = $this->createMock(Button::class);
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

        $translator = $this->createMock(Translator::class);
        $translator->expects(self::once())
            ->method('translate')
            ->with($label, $textDomain)
            ->willReturn($tranlatedLabel);

        $escapeHtml = $this->createMock(EscapeHtml::class);
        $matcher    = self::exactly(5);
        $escapeHtml->expects($matcher)
            ->method('__invoke')
            ->willReturnCallback(
                static function (string $value, int $recurse = AbstractHelper::RECURSE_NONE) use ($matcher, $tranlatedLabel, $escapedTranlatedLabel): string {
                    $invocation = $matcher->numberOfInvocations();

                    match ($invocation) {
                        1 => self::assertSame(
                            $tranlatedLabel,
                            $value,
                            (string) $invocation,
                        ),
                        2 => self::assertSame(
                            'type',
                            $value,
                            (string) $invocation,
                        ),
                        3 => self::assertSame(
                            'name',
                            $value,
                            (string) $invocation,
                        ),
                        4 => self::assertSame(
                            'class',
                            $value,
                            (string) $invocation,
                        ),
                        5 => self::assertSame(
                            'value',
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
                        1 => $escapedTranlatedLabel,
                        2 => 'typeEscaped',
                        3 => 'nameEscaped',
                        4 => 'class-escaped',
                        5 => 'valueEscaped',
                        default => '',
                    };
                },
            );

        $escapeHtmlAttr = $this->createMock(EscapeHtmlAttr::class);
        $matcher        = self::exactly(4);
        $escapeHtmlAttr->expects($matcher)
            ->method('__invoke')
            ->willReturnCallback(
                static function (string $valueParam, int $recurse = AbstractHelper::RECURSE_NONE) use ($matcher, $type, $typeEscaped, $name, $nameEscaped, $value, $valueEscaped): string {
                    $invocation = $matcher->numberOfInvocations();

                    match ($invocation) {
                        1 => self::assertSame(
                            $type,
                            $valueParam,
                            (string) $invocation,
                        ),
                        2 => self::assertSame(
                            $name,
                            $valueParam,
                            (string) $invocation,
                        ),
                        3 => self::assertSame(
                            'btn',
                            $valueParam,
                            (string) $invocation,
                        ),
                        4 => self::assertSame(
                            $value,
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
                        1 => $typeEscaped,
                        2 => $nameEscaped,
                        3 => 'btn-escaped',
                        4 => $valueEscaped,
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
        $doctype->expects(self::never())
            ->method('getDoctype');

        $renderer = $this->createMock(PhpRenderer::class);
        $renderer->expects(self::never())
            ->method('getHelperPluginManager');
        $matcher = self::exactly(3);
        $renderer->expects($matcher)
            ->method('plugin')
            ->willReturnCallback(
                static function (string $name, array | null $options = null) use ($matcher, $escapeHtml, $escapeHtmlAttr, $doctype): HelperInterface | null {
                    $invocation = $matcher->numberOfInvocations();

                    match ($invocation) {
                        1 => self::assertSame(
                            'escapehtml',
                            $name,
                            (string) $invocation,
                        ),
                        2 => self::assertSame(
                            'escapehtmlattr',
                            $name,
                            (string) $invocation,
                        ),
                        3 => self::assertSame(
                            'doctype',
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
                        1 => $escapeHtml,
                        2 => $escapeHtmlAttr,
                        3 => $doctype,
                        default => null,
                    };
                },
            );
        $renderer->expects(self::never())
            ->method('render');

        $helper->setView($renderer);
        $helper->setTranslator($translator);
        $helper->setTranslatorTextDomain($textDomain);

        $helperObject = ($helper)();

        assert($helperObject instanceof FormButton);

        self::assertSame($expected, $helperObject->render($element));
    }

    /** @throws Exception */
    public function testSetGetIndent1(): void
    {
        $helper = new FormButton();

        self::assertSame($helper, $helper->setIndent(4));
        self::assertSame('    ', $helper->getIndent());
    }

    /** @throws Exception */
    public function testSetGetIndent2(): void
    {
        $helper = new FormButton();

        self::assertSame($helper, $helper->setIndent('  '));
        self::assertSame('  ', $helper->getIndent());
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws InvalidArgumentException
     * @throws NoPreviousThrowableException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testRenderWithWrongElement(): void
    {
        $helper = new FormButton();

        $element = $this->createMock(Text::class);
        $element->expects(self::never())
            ->method('getName');
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

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            sprintf(
                '%s requires that the element is of type %s or of type %s, but was %s',
                'Mimmi20\LaminasView\BootstrapForm\FormButton::render',
                Button::class,
                Submit::class,
                get_debug_type($element),
            ),
        );
        $this->expectExceptionCode(0);
        $helper->render($element);
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws InvalidArgumentException
     * @throws NoPreviousThrowableException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testRenderWithSubmit(): void
    {
        $helper = new FormButton();

        $element = $this->createMock(Submit::class);
        $element->expects(self::never())
            ->method('getName');
        $element->expects(self::never())
            ->method('getValue');
        $element->expects(self::never())
            ->method('getAttributes');
        $element->expects(self::never())
            ->method('getAttribute');
        $element->expects(self::once())
            ->method('getLabel')
            ->willReturn(null);
        $element->expects(self::never())
            ->method('getLabelOption');

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage(
            sprintf(
                '%s expects either button content as the second argument, or that the element provided has a label value; neither found',
                'Mimmi20\LaminasView\BootstrapForm\FormButton::render',
            ),
        );
        $this->expectExceptionCode(0);
        $helper->render($element);
    }
}
