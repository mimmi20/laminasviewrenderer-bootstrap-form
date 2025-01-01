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
use Laminas\View\Exception\InvalidArgumentException;
use Laminas\View\Helper\Doctype;
use Laminas\View\Helper\EscapeHtml;
use Laminas\View\Helper\EscapeHtmlAttr;
use Laminas\View\Helper\Escaper\AbstractHelper;
use Laminas\View\Helper\HelperInterface;
use Laminas\View\Renderer\PhpRenderer;
use Mimmi20\LaminasView\BootstrapForm\FormTextarea;
use Override;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;

use function assert;
use function sprintf;

#[Group('form-textarea')]
final class FormTextareaTest extends TestCase
{
    private FormTextarea $helper;

    /** @throws void */
    #[Override]
    protected function setUp(): void
    {
        $this->helper = new FormTextarea();
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws InvalidArgumentException
     */
    public function testRenderWithoutName(): void
    {
        $element = $this->createMock(File::class);
        $element->expects(self::once())
            ->method('getName')
            ->willReturn(null);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage(
            sprintf(
                '%s requires that the element has an assigned name; none discovered',
                'Mimmi20\LaminasView\BootstrapForm\FormTextarea::render',
            ),
        );
        $this->expectExceptionCode(0);

        $this->helper->render($element);
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws InvalidArgumentException
     */
    public function testRenderWithName(): void
    {
        $name         = 'name';
        $value        = 'xyz';
        $escapedValue = 'uvwxyz';
        $expected     = '<textarea class-escaped="form-control-escaped abc-escaped" nameEscaped="name-escaped">uvwxyz</textarea>';

        $escapeHtml = $this->createMock(EscapeHtml::class);
        $matcher    = self::exactly(3);
        $escapeHtml->expects($matcher)
            ->method('__invoke')
            ->willReturnCallback(
                static function (string $valueParam, int $recurse = AbstractHelper::RECURSE_NONE) use ($matcher, $value, $escapedValue): string {
                    $invocation = $matcher->numberOfInvocations();

                    match ($invocation) {
                        1 => self::assertSame(
                            $value,
                            $valueParam,
                            (string) $invocation,
                        ),
                        2 => self::assertSame(
                            'class',
                            $valueParam,
                            (string) $invocation,
                        ),
                        3 => self::assertSame(
                            'name',
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
                        1 => $escapedValue,
                        2 => 'class-escaped',
                        3 => 'nameEscaped',
                        default => '',
                    };
                },
            );

        $escapeHtmlAttr = $this->createMock(EscapeHtmlAttr::class);
        $matcher        = self::exactly(2);
        $escapeHtmlAttr->expects($matcher)
            ->method('__invoke')
            ->willReturnCallback(
                static function (string $valueParam, int $recurse = AbstractHelper::RECURSE_NONE) use ($matcher, $name): string {
                    $invocation = $matcher->numberOfInvocations();

                    match ($invocation) {
                        1 => self::assertSame(
                            'form-control abc',
                            $valueParam,
                            (string) $invocation,
                        ),
                        2 => self::assertSame(
                            $name,
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
                        1 => 'form-control-escaped abc-escaped',
                        2 => 'name-escaped',
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

        $this->helper->setView($renderer);

        $element = $this->createMock(File::class);
        $element->expects(self::once())
            ->method('getName')
            ->willReturn($name);
        $element->expects(self::once())
            ->method('getAttributes')
            ->willReturn(['class' => 'abc']);
        $element->expects(self::once())
            ->method('getValue')
            ->willReturn($value);

        self::assertSame($expected, $this->helper->render($element));
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws InvalidArgumentException
     */
    public function testInvoke1(): void
    {
        $name         = 'name';
        $value        = 'xyz';
        $escapedValue = 'uvwxyz';
        $expected     = '<textarea class-escaped="form-control-escaped abc-escaped" nameEscaped="name-escaped">uvwxyz</textarea>';

        $escapeHtml = $this->createMock(EscapeHtml::class);
        $matcher    = self::exactly(3);
        $escapeHtml->expects($matcher)
            ->method('__invoke')
            ->willReturnCallback(
                static function (string $valueParam, int $recurse = AbstractHelper::RECURSE_NONE) use ($matcher, $value, $escapedValue): string {
                    $invocation = $matcher->numberOfInvocations();

                    match ($invocation) {
                        1 => self::assertSame(
                            $value,
                            $valueParam,
                            (string) $invocation,
                        ),
                        2 => self::assertSame(
                            'class',
                            $valueParam,
                            (string) $invocation,
                        ),
                        3 => self::assertSame(
                            'name',
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
                        1 => $escapedValue,
                        2 => 'class-escaped',
                        3 => 'nameEscaped',
                        default => '',
                    };
                },
            );

        $escapeHtmlAttr = $this->createMock(EscapeHtmlAttr::class);
        $matcher        = self::exactly(2);
        $escapeHtmlAttr->expects($matcher)
            ->method('__invoke')
            ->willReturnCallback(
                static function (string $valueParam, int $recurse = AbstractHelper::RECURSE_NONE) use ($matcher, $name): string {
                    $invocation = $matcher->numberOfInvocations();

                    match ($invocation) {
                        1 => self::assertSame(
                            'form-control abc',
                            $valueParam,
                            (string) $invocation,
                        ),
                        2 => self::assertSame(
                            $name,
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
                        1 => 'form-control-escaped abc-escaped',
                        2 => 'name-escaped',
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

        $this->helper->setView($renderer);

        $element = $this->createMock(File::class);
        $element->expects(self::once())
            ->method('getName')
            ->willReturn($name);
        $element->expects(self::once())
            ->method('getAttributes')
            ->willReturn(['class' => 'abc']);
        $element->expects(self::once())
            ->method('getValue')
            ->willReturn($value);

        $helperObject = ($this->helper)();

        assert($helperObject instanceof FormTextarea);

        self::assertSame($expected, $helperObject->render($element));
    }

    /**
     * @throws Exception
     * @throws DomainException
     * @throws ContainerExceptionInterface
     * @throws InvalidArgumentException
     */
    public function testInvoke2(): void
    {
        $name         = 'name';
        $value        = 'xyz';
        $escapedValue = 'uvwxyz';
        $expected     = '<textarea class-escaped="form-control-escaped abc-escaped" nameEscaped="name-escaped">uvwxyz</textarea>';

        $escapeHtml = $this->createMock(EscapeHtml::class);
        $matcher    = self::exactly(3);
        $escapeHtml->expects($matcher)
            ->method('__invoke')
            ->willReturnCallback(
                static function (string $valueParam, int $recurse = AbstractHelper::RECURSE_NONE) use ($matcher, $value, $escapedValue): string {
                    $invocation = $matcher->numberOfInvocations();

                    match ($invocation) {
                        1 => self::assertSame(
                            $value,
                            $valueParam,
                            (string) $invocation,
                        ),
                        2 => self::assertSame(
                            'class',
                            $valueParam,
                            (string) $invocation,
                        ),
                        3 => self::assertSame(
                            'name',
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
                        1 => $escapedValue,
                        2 => 'class-escaped',
                        3 => 'nameEscaped',
                        default => '',
                    };
                },
            );

        $escapeHtmlAttr = $this->createMock(EscapeHtmlAttr::class);
        $matcher        = self::exactly(2);
        $escapeHtmlAttr->expects($matcher)
            ->method('__invoke')
            ->willReturnCallback(
                static function (string $valueParam, int $recurse = AbstractHelper::RECURSE_NONE) use ($matcher, $name): string {
                    $invocation = $matcher->numberOfInvocations();

                    match ($invocation) {
                        1 => self::assertSame(
                            'form-control abc',
                            $valueParam,
                            (string) $invocation,
                        ),
                        2 => self::assertSame(
                            $name,
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
                        1 => 'form-control-escaped abc-escaped',
                        2 => 'name-escaped',
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

        $this->helper->setView($renderer);

        $element = $this->createMock(File::class);
        $element->expects(self::once())
            ->method('getName')
            ->willReturn($name);
        $element->expects(self::once())
            ->method('getAttributes')
            ->willReturn(['class' => 'abc']);
        $element->expects(self::once())
            ->method('getValue')
            ->willReturn($value);

        self::assertSame($expected, ($this->helper)($element));
    }

    /** @throws Exception */
    public function testSetGetIndent1(): void
    {
        self::assertSame($this->helper, $this->helper->setIndent(4));
        self::assertSame('    ', $this->helper->getIndent());
    }

    /** @throws Exception */
    public function testSetGetIndent2(): void
    {
        self::assertSame($this->helper, $this->helper->setIndent('  '));
        self::assertSame('  ', $this->helper->getIndent());
    }
}
