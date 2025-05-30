<?php

/**
 * This file is part of the mimmi20/laminasviewrenderer-bootstrap-form package.
 *
 * Copyright (c) 2021-2025, Thomas Mueller <mimmi20@live.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Mimmi20Test\LaminasView\BootstrapForm;

use Laminas\Form\Element\File;
use Laminas\Form\Exception\DomainException;
use Laminas\View\Helper\Doctype;
use Laminas\View\Helper\EscapeHtml;
use Laminas\View\Helper\EscapeHtmlAttr;
use Laminas\View\Helper\Escaper\AbstractHelper;
use Laminas\View\Helper\HelperInterface;
use Laminas\View\Renderer\PhpRenderer;
use Mimmi20\LaminasView\BootstrapForm\FormHidden;
use PHPUnit\Event\NoPreviousThrowableException;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[Group('form-hidden')]
final class FormHiddenTest extends TestCase
{
    /**
     * @throws Exception
     * @throws DomainException
     * @throws NoPreviousThrowableException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testRenderWithoutName(): void
    {
        $helper = new FormHidden();

        $element = $this->createMock(File::class);
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
        $element->expects(self::never())
            ->method('getOption');

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage(
            sprintf(
                '%s requires that the element has an assigned name; none discovered',
                'Mimmi20\LaminasView\BootstrapForm\FormHidden::render',
            ),
        );
        $this->expectExceptionCode(0);
        $helper->render($element);
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws NoPreviousThrowableException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testRenderHtml(): void
    {
        $helper = new FormHidden();

        $name         = 'test-name';
        $class        = 'test-class';
        $value        = 'test-value';
        $classEscaped = sprintf('%s-escaped', $class);
        $nameEscaped  = 'test-name-escaped';
        $valueEscaped = 'test-value-escaped';

        $expected = sprintf(
            '<input class-escaped="%s" nameEscaped="%s" typeEscaped="hidden-escaped" valueEscaped="%s">',
            $classEscaped,
            $nameEscaped,
            $valueEscaped,
        );

        $element = $this->createMock(File::class);
        $element->expects(self::once())
            ->method('getName')
            ->willReturn($name);
        $element->expects(self::once())
            ->method('getValue')
            ->willReturn($value);
        $element->expects(self::once())
            ->method('getAttributes')
            ->willReturn(['class' => $class]);
        $element->expects(self::never())
            ->method('getAttribute');
        $element->expects(self::never())
            ->method('getLabel');
        $element->expects(self::never())
            ->method('getLabelOption');
        $element->expects(self::never())
            ->method('getOption');

        $escapeHtml = $this->createMock(EscapeHtml::class);
        $matcher    = self::exactly(4);
        $escapeHtml->expects($matcher)
            ->method('__invoke')
            ->willReturnCallback(
                static function (string $value, int $recurse = AbstractHelper::RECURSE_NONE) use ($matcher): string {
                    $invocation = $matcher->numberOfInvocations();

                    match ($invocation) {
                        1 => self::assertSame(
                            'class',
                            $value,
                            (string) $invocation,
                        ),
                        2 => self::assertSame(
                            'name',
                            $value,
                            (string) $invocation,
                        ),
                        3 => self::assertSame(
                            'type',
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
                static function (string $valueParam, int $recurse = AbstractHelper::RECURSE_NONE) use ($matcher, $class, $classEscaped, $name, $nameEscaped, $value, $valueEscaped): string {
                    $invocation = $matcher->numberOfInvocations();

                    match ($invocation) {
                        1 => self::assertSame(
                            $class,
                            $valueParam,
                            (string) $invocation,
                        ),
                        2 => self::assertSame(
                            $name,
                            $valueParam,
                            (string) $invocation,
                        ),
                        3 => self::assertSame(
                            'hidden',
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
                        1 => $classEscaped,
                        2 => $nameEscaped,
                        3 => 'hidden-escaped',
                        4 => $valueEscaped,
                        default => '',
                    };
                },
            );

        $doctype = $this->createMock(Doctype::class);
        $doctype->expects(self::never())
            ->method('__invoke');
        $doctype->expects(self::once())
            ->method('isXhtml')
            ->willReturn(false);
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

        self::assertSame($expected, $helper->render($element));
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws NoPreviousThrowableException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testRenderXHtml(): void
    {
        $helper = new FormHidden();

        $name         = 'test-name';
        $class        = 'test-class';
        $value        = 'test-value';
        $classEscaped = sprintf('%s-escaped', $class);
        $nameEscaped  = 'test-name-escaped';
        $valueEscaped = 'test-value-escaped';

        $expected = sprintf(
            '<input class-escaped="%s" nameEscaped="%s" typeEscaped="hidden-escaped" valueEscaped="%s"/>',
            $classEscaped,
            $nameEscaped,
            $valueEscaped,
        );

        $element = $this->createMock(File::class);
        $element->expects(self::once())
            ->method('getName')
            ->willReturn($name);
        $element->expects(self::once())
            ->method('getValue')
            ->willReturn($value);
        $element->expects(self::once())
            ->method('getAttributes')
            ->willReturn(['class' => $class]);
        $element->expects(self::never())
            ->method('getAttribute');
        $element->expects(self::never())
            ->method('getLabel');
        $element->expects(self::never())
            ->method('getLabelOption');
        $element->expects(self::never())
            ->method('getOption');

        $escapeHtml = $this->createMock(EscapeHtml::class);
        $matcher    = self::exactly(4);
        $escapeHtml->expects($matcher)
            ->method('__invoke')
            ->willReturnCallback(
                static function (string $value, int $recurse = AbstractHelper::RECURSE_NONE) use ($matcher): string {
                    $invocation = $matcher->numberOfInvocations();

                    match ($invocation) {
                        1 => self::assertSame(
                            'class',
                            $value,
                            (string) $invocation,
                        ),
                        2 => self::assertSame(
                            'name',
                            $value,
                            (string) $invocation,
                        ),
                        3 => self::assertSame(
                            'type',
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
                static function (string $valueParam, int $recurse = AbstractHelper::RECURSE_NONE) use ($matcher, $class, $classEscaped, $name, $nameEscaped, $value, $valueEscaped): string {
                    $invocation = $matcher->numberOfInvocations();

                    match ($invocation) {
                        1 => self::assertSame(
                            $class,
                            $valueParam,
                            (string) $invocation,
                        ),
                        2 => self::assertSame(
                            $name,
                            $valueParam,
                            (string) $invocation,
                        ),
                        3 => self::assertSame(
                            'hidden',
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
                        1 => $classEscaped,
                        2 => $nameEscaped,
                        3 => 'hidden-escaped',
                        4 => $valueEscaped,
                        default => '',
                    };
                },
            );

        $doctype = $this->createMock(Doctype::class);
        $doctype->expects(self::never())
            ->method('__invoke');
        $doctype->expects(self::once())
            ->method('isXhtml')
            ->willReturn(true);
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

        self::assertSame($expected, $helper->render($element));
    }

    /** @throws Exception */
    public function testSetGetIndent1(): void
    {
        $helper = new FormHidden();

        self::assertSame($helper, $helper->setIndent(4));
        self::assertSame('    ', $helper->getIndent());
    }

    /** @throws Exception */
    public function testSetGetIndent2(): void
    {
        $helper = new FormHidden();

        self::assertSame($helper, $helper->setIndent('  '));
        self::assertSame('  ', $helper->getIndent());
    }
}
