<?php
/**
 * This file is part of the mimmi20/laminasviewrenderer-bootstrap-form package.
 *
 * Copyright (c) 2021-2024, Thomas Mueller <mimmi20@live.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Mimmi20Test\LaminasView\BootstrapForm;

use Laminas\Form\Element\Hidden;
use Laminas\Form\Element\Select as SelectElement;
use Laminas\Form\Element\Text;
use Laminas\Form\Exception\DomainException;
use Laminas\Form\Exception\InvalidArgumentException;
use Laminas\I18n\Exception\RuntimeException;
use Laminas\I18n\View\Helper\Translate;
use Laminas\View\Helper\Doctype;
use Laminas\View\Helper\EscapeHtml;
use Laminas\View\Helper\EscapeHtmlAttr;
use Laminas\View\Helper\Escaper\AbstractHelper;
use Laminas\View\Helper\HelperInterface;
use Laminas\View\Renderer\PhpRenderer;
use Mimmi20\LaminasView\BootstrapForm\FormHiddenInterface;
use Mimmi20\LaminasView\BootstrapForm\FormSelect;
use PHPUnit\Framework\Constraint\IsInstanceOf;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;

use function sprintf;

use const PHP_EOL;

use Psr\Container\ContainerExceptionInterface;

use function assert;

/**
 * @group form-select
 */
final class FormSelectTest extends TestCase
{
    private FormSelect $helper;

    /** @throws void */
    protected function setUp(): void
    {
        $this->helper = new FormSelect();
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws \Laminas\View\Exception\InvalidArgumentException
     * @throws RuntimeException
     */
    public function testRenderWithWrongElement(): void
    {
        $escapeHtml = $this->createMock(EscapeHtml::class);
        $escapeHtml->expects(self::never())
            ->method('__invoke');

        $formHidden = $this->createMock(FormHiddenInterface::class);
        $formHidden->expects(self::never())
            ->method('render');

        $renderer = $this->createMock(PhpRenderer::class);
        $renderer->expects(self::never())
            ->method('getHelperPluginManager');
        $renderer->expects(self::never())
            ->method('plugin');
        $renderer->expects(self::never())
            ->method('render');

        $this->helper->setView($renderer);

        $element = $this->createMock(Text::class);
        $element->expects(self::never())
            ->method('getName');
        $element->expects(self::never())
            ->method('getAttributes');
        $element->expects(self::never())
            ->method('getValue');
        $element->expects(self::never())
            ->method('getLabelAttributes');
        $element->expects(self::never())
            ->method('getOption');
        $element->expects(self::never())
            ->method('getLabelOption');
        $element->expects(self::never())
            ->method('hasLabelOption');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            sprintf(
                '%s requires that the element is of type %s',
                'Mimmi20\LaminasView\BootstrapForm\FormSelect::render',
                SelectElement::class,
            ),
        );
        $this->expectExceptionCode(0);

        $this->helper->render($element);
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws \Laminas\View\Exception\InvalidArgumentException
     * @throws RuntimeException
     */
    public function testRenderWithoutName(): void
    {
        $escapeHtml = $this->createMock(EscapeHtml::class);
        $escapeHtml->expects(self::never())
            ->method('__invoke');

        $formHidden = $this->createMock(FormHiddenInterface::class);
        $formHidden->expects(self::never())
            ->method('render');

        $renderer = $this->createMock(PhpRenderer::class);
        $renderer->expects(self::never())
            ->method('getHelperPluginManager');
        $renderer->expects(self::never())
            ->method('plugin');
        $renderer->expects(self::never())
            ->method('render');

        $this->helper->setView($renderer);

        $element = $this->createMock(SelectElement::class);
        $element->expects(self::once())
            ->method('getName')
            ->willReturn(null);
        $element->expects(self::never())
            ->method('getValueOptions');
        $element->expects(self::never())
            ->method('getAttributes');
        $element->expects(self::never())
            ->method('getValue');
        $element->expects(self::never())
            ->method('useHiddenElement');
        $element->expects(self::never())
            ->method('getLabelAttributes');
        $element->expects(self::never())
            ->method('getOption');
        $element->expects(self::never())
            ->method('getLabelOption');
        $element->expects(self::never())
            ->method('hasLabelOption');
        $element->expects(self::never())
            ->method('getEmptyOption');
        $element->expects(self::never())
            ->method('getUnselectedValue');

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage(
            sprintf(
                '%s requires that the element has an assigned name; none discovered',
                'Mimmi20\LaminasView\BootstrapForm\FormSelect::render',
            ),
        );
        $this->expectExceptionCode(0);

        $this->helper->render($element);
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws \Laminas\View\Exception\InvalidArgumentException
     * @throws RuntimeException
     */
    public function testRenderWithNameWithoutValue(): void
    {
        $name               = 'test-name';
        $id                 = 'test-id';
        $value2             = 'def';
        $value2Escaped      = 'def-escaped';
        $value3             = 'abc';
        $class              = 'test-class';
        $ariaLabel          = 'test';
        $valueOptions       = [$value3 => $value2];
        $attributes         = ['class' => $class, 'aria-label' => $ariaLabel, 'id' => $id];
        $emptyOption        = '0';
        $emptyOptionEscaped = '0e';
        $expected           = sprintf(
            '<select class="form-select&#x20;%s" aria-label="%s" id="%s" name="%s">',
            $class,
            $ariaLabel,
            $id,
            $name,
        ) . PHP_EOL
            . sprintf('    <option value="">%s</option>', $emptyOptionEscaped) . PHP_EOL
            . sprintf('    <option value="%s">%s</option>', $value3, $value2Escaped) . PHP_EOL
            . '</select>';

        $escapeHtml = $this->createMock(EscapeHtml::class);
        $escapeHtml->expects(self::exactly(2))
            ->method('__invoke')
            ->willReturnMap(
                [
                    [$emptyOption, 0, $emptyOptionEscaped],
                    [$value2, 0, $value2Escaped],
                ],
            );

        $escapeHtmlAttr = $this->createMock(EscapeHtmlAttr::class);
        $matcher = self::exactly(4);
        $escapeHtmlAttr->expects($matcher)
            ->method('__invoke')
            ->willReturnCallback(
                static function (string $valueParam, int $recurse = AbstractHelper::RECURSE_NONE) use ($matcher, $class, $name): string {
                    $invocation = $matcher->numberOfInvocations();

                    match ($invocation) {
                        1 => self::assertSame(
                            'form-control ' . $class,
                            $valueParam,
                            (string) $invocation,
                        ),
                        2 => self::assertSame(
                            $name,
                            $valueParam,
                            (string) $invocation,
                        ),
                        3 => self::assertSame(
                            'week',
                            $valueParam,
                            (string) $invocation,
                        ),
                        default => self::assertSame(
                            '',
                            $valueParam,
                            (string) $invocation,
                        ),
                    };

                    self::assertSame(AbstractHelper::RECURSE_NONE, $recurse);

                    return match ($invocation) {
                        3 => 'week-escaped',
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

        $formHidden = $this->createMock(FormHiddenInterface::class);
        $formHidden->expects(self::never())
            ->method('render');

        $renderer = $this->createMock(PhpRenderer::class);
        $renderer->expects(self::never())
            ->method('getHelperPluginManager');
        $matcher = self::exactly(3);
        $renderer->expects($matcher)
            ->method('plugin')
            ->willReturnCallback(
                static function (string $name, ?array $options = null) use ($matcher, $escapeHtml, $escapeHtmlAttr, $doctype): HelperInterface|null {
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

        $element = $this->createMock(SelectElement::class);
        $element->expects(self::once())
            ->method('getName')
            ->willReturn($name);
        $element->expects(self::once())
            ->method('getValueOptions')
            ->willReturn($valueOptions);
        $element->expects(self::once())
            ->method('getAttributes')
            ->willReturn($attributes);
        $element->expects(self::once())
            ->method('getValue')
            ->willReturn(null);
        $element->expects(self::once())
            ->method('useHiddenElement')
            ->willReturn(false);
        $element->expects(self::never())
            ->method('getLabelAttributes');
        $element->expects(self::never())
            ->method('getOption');
        $element->expects(self::never())
            ->method('getLabelOption');
        $element->expects(self::never())
            ->method('hasLabelOption');
        $element->expects(self::once())
            ->method('getEmptyOption')
            ->willReturn($emptyOption);
        $element->expects(self::never())
            ->method('getUnselectedValue');

        self::assertSame($expected, $this->helper->render($element));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws \Laminas\View\Exception\InvalidArgumentException
     * @throws RuntimeException
     */
    public function testRenderWithNameWithStringValue(): void
    {
        $name               = 'test-name';
        $id                 = 'test-id';
        $value1             = 'xyz';
        $value2             = 'def';
        $value2Escaped      = 'def-escaped';
        $value3             = 'abc';
        $class              = 'test-class';
        $ariaLabel          = 'test';
        $valueOptions       = [$value3 => $value2];
        $attributes         = ['class' => $class, 'aria-label' => $ariaLabel, 'id' => $id];
        $emptyOption        = '0';
        $emptyOptionEscaped = '0e';
        $expected           = sprintf(
            '<select class="form-select&#x20;%s" aria-label="%s" id="%s" name="%s">',
            $class,
            $ariaLabel,
            $id,
            $name,
        ) . PHP_EOL
            . sprintf('    <option value="">%s</option>', $emptyOptionEscaped) . PHP_EOL
            . sprintf('    <option value="%s">%s</option>', $value3, $value2Escaped) . PHP_EOL
            . '</select>';

        $escapeHtml = $this->createMock(EscapeHtml::class);
        $escapeHtml->expects(self::exactly(2))
            ->method('__invoke')
            ->willReturnMap(
                [
                    [$emptyOption, 0, $emptyOptionEscaped],
                    [$value2, 0, $value2Escaped],
                ],
            );

        $escapeHtmlAttr = $this->createMock(EscapeHtmlAttr::class);
        $matcher = self::exactly(4);
        $escapeHtmlAttr->expects($matcher)
            ->method('__invoke')
            ->willReturnCallback(
                static function (string $valueParam, int $recurse = AbstractHelper::RECURSE_NONE) use ($matcher, $class, $name): string {
                    $invocation = $matcher->numberOfInvocations();

                    match ($invocation) {
                        1 => self::assertSame(
                            'form-control ' . $class,
                            $valueParam,
                            (string) $invocation,
                        ),
                        2 => self::assertSame(
                            $name,
                            $valueParam,
                            (string) $invocation,
                        ),
                        3 => self::assertSame(
                            'week',
                            $valueParam,
                            (string) $invocation,
                        ),
                        default => self::assertSame(
                            '',
                            $valueParam,
                            (string) $invocation,
                        ),
                    };

                    self::assertSame(AbstractHelper::RECURSE_NONE, $recurse);

                    return match ($invocation) {
                        3 => 'week-escaped',
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

        $formHidden = $this->createMock(FormHiddenInterface::class);
        $formHidden->expects(self::never())
            ->method('render');

        $renderer = $this->createMock(PhpRenderer::class);
        $renderer->expects(self::never())
            ->method('getHelperPluginManager');
        $matcher = self::exactly(3);
        $renderer->expects($matcher)
            ->method('plugin')
            ->willReturnCallback(
                static function (string $name, ?array $options = null) use ($matcher, $escapeHtml, $escapeHtmlAttr, $doctype): HelperInterface|null {
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

        $element = $this->createMock(SelectElement::class);
        $element->expects(self::once())
            ->method('getName')
            ->willReturn($name);
        $element->expects(self::once())
            ->method('getValueOptions')
            ->willReturn($valueOptions);
        $element->expects(self::once())
            ->method('getAttributes')
            ->willReturn($attributes);
        $element->expects(self::once())
            ->method('getValue')
            ->willReturn($value1);
        $element->expects(self::once())
            ->method('useHiddenElement')
            ->willReturn(false);
        $element->expects(self::never())
            ->method('getLabelAttributes');
        $element->expects(self::never())
            ->method('getOption');
        $element->expects(self::never())
            ->method('getLabelOption');
        $element->expects(self::never())
            ->method('hasLabelOption');
        $element->expects(self::once())
            ->method('getEmptyOption')
            ->willReturn($emptyOption);
        $element->expects(self::never())
            ->method('getUnselectedValue');

        self::assertSame($expected, $this->helper->render($element));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws \Laminas\View\Exception\InvalidArgumentException
     * @throws RuntimeException
     */
    public function testRenderWithNameWithArrayValue(): void
    {
        $name         = 'test-name';
        $id           = 'test-id';
        $value2       = 'def';
        $value3       = 'abc';
        $class        = 'test-class';
        $ariaLabel    = 'test';
        $valueOptions = [$value3 => $value2];
        $attributes   = ['class' => $class, 'aria-label' => $ariaLabel, 'id' => $id];
        $emptyOption  = '0';

        $escapeHtml = $this->createMock(EscapeHtml::class);
        $escapeHtml->expects(self::never())
            ->method('__invoke');

        $formHidden = $this->createMock(FormHiddenInterface::class);
        $formHidden->expects(self::never())
            ->method('render');

        $renderer = $this->createMock(PhpRenderer::class);
        $renderer->expects(self::never())
            ->method('getHelperPluginManager');
        $renderer->expects(self::never())
            ->method('plugin');
        $renderer->expects(self::never())
            ->method('render');

        $this->helper->setView($renderer);

        $element = $this->createMock(SelectElement::class);
        $element->expects(self::once())
            ->method('getName')
            ->willReturn($name);
        $element->expects(self::once())
            ->method('getValueOptions')
            ->willReturn($valueOptions);
        $element->expects(self::once())
            ->method('getAttributes')
            ->willReturn($attributes);
        $element->expects(self::once())
            ->method('getValue')
            ->willReturn([$value3]);
        $element->expects(self::never())
            ->method('useHiddenElement');
        $element->expects(self::never())
            ->method('getLabelAttributes');
        $element->expects(self::never())
            ->method('getOption');
        $element->expects(self::never())
            ->method('getLabelOption');
        $element->expects(self::never())
            ->method('hasLabelOption');
        $element->expects(self::once())
            ->method('getEmptyOption')
            ->willReturn($emptyOption);
        $element->expects(self::never())
            ->method('getUnselectedValue');

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage(
            sprintf(
                '%s does not allow specifying multiple selected values when the element does not have a multiple attribute set to a boolean true',
                FormSelect::class,
            ),
        );
        $this->expectExceptionCode(0);

        $this->helper->render($element);
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws \Laminas\View\Exception\InvalidArgumentException
     * @throws RuntimeException
     */
    public function testRenderWithNameWithArrayMultipleValue(): void
    {
        $name               = 'test-name';
        $id                 = 'test-id';
        $value1             = 'xyz';
        $value2             = 'def';
        $value2Escaped      = 'def-escaped';
        $value3             = 'abc';
        $class              = 'test-class';
        $ariaLabel          = 'test';
        $valueOptions       = [$value3 => $value2];
        $attributes         = ['class' => $class, 'aria-label' => $ariaLabel, 'id' => $id, 'multiple' => true];
        $emptyOption        = '0';
        $emptyOptionEscaped = '0e';
        $expected           = sprintf(
            '<select class="form-select&#x20;%s" aria-label="%s" id="%s" multiple="multiple" name="%s&#x5B;&#x5D;">',
            $class,
            $ariaLabel,
            $id,
            $name,
        ) . PHP_EOL
            . sprintf('    <option value="">%s</option>', $emptyOptionEscaped) . PHP_EOL
            . sprintf(
                '    <option value="%s" selected="selected">%s</option>',
                $value3,
                $value2Escaped,
            ) . PHP_EOL
            . '</select>';

        $escapeHtml = $this->createMock(EscapeHtml::class);
        $escapeHtml->expects(self::exactly(2))
            ->method('__invoke')
            ->willReturnMap(
                [
                    [$emptyOption, 0, $emptyOptionEscaped],
                    [$value2, 0, $value2Escaped],
                ],
            );

        $escapeHtmlAttr = $this->createMock(EscapeHtmlAttr::class);
        $matcher = self::exactly(4);
        $escapeHtmlAttr->expects($matcher)
            ->method('__invoke')
            ->willReturnCallback(
                static function (string $valueParam, int $recurse = AbstractHelper::RECURSE_NONE) use ($matcher, $class, $name): string {
                    $invocation = $matcher->numberOfInvocations();

                    match ($invocation) {
                        1 => self::assertSame(
                            'form-control ' . $class,
                            $valueParam,
                            (string) $invocation,
                        ),
                        2 => self::assertSame(
                            $name,
                            $valueParam,
                            (string) $invocation,
                        ),
                        3 => self::assertSame(
                            'week',
                            $valueParam,
                            (string) $invocation,
                        ),
                        default => self::assertSame(
                            '',
                            $valueParam,
                            (string) $invocation,
                        ),
                    };

                    self::assertSame(AbstractHelper::RECURSE_NONE, $recurse);

                    return match ($invocation) {
                        3 => 'week-escaped',
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

        $formHidden = $this->createMock(FormHiddenInterface::class);
        $formHidden->expects(self::never())
            ->method('render');

        $renderer = $this->createMock(PhpRenderer::class);
        $renderer->expects(self::never())
            ->method('getHelperPluginManager');
        $matcher = self::exactly(3);
        $renderer->expects($matcher)
            ->method('plugin')
            ->willReturnCallback(
                static function (string $name, ?array $options = null) use ($matcher, $escapeHtml, $escapeHtmlAttr, $doctype): HelperInterface|null {
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

        $element = $this->createMock(SelectElement::class);
        $element->expects(self::once())
            ->method('getName')
            ->willReturn($name);
        $element->expects(self::once())
            ->method('getValueOptions')
            ->willReturn($valueOptions);
        $element->expects(self::once())
            ->method('getAttributes')
            ->willReturn($attributes);
        $element->expects(self::once())
            ->method('getValue')
            ->willReturn([$value1, $value3]);
        $element->expects(self::once())
            ->method('useHiddenElement')
            ->willReturn(false);
        $element->expects(self::never())
            ->method('getLabelAttributes');
        $element->expects(self::never())
            ->method('getOption');
        $element->expects(self::never())
            ->method('getLabelOption');
        $element->expects(self::never())
            ->method('hasLabelOption');
        $element->expects(self::once())
            ->method('getEmptyOption')
            ->willReturn($emptyOption);
        $element->expects(self::never())
            ->method('getUnselectedValue');

        self::assertSame($expected, $this->helper->render($element));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws \Laminas\View\Exception\InvalidArgumentException
     * @throws RuntimeException
     */
    public function testRenderMultipleOptions1(): void
    {
        $name          = 'test-name';
        $id            = 'test-id';
        $value1        = 'xyz';
        $label1        = 'def1';
        $label1Escaped = 'def-escaped';
        $value2        = '1';
        $label2        = '...2';
        $label2Escaped = '...2-escaped';
        $label3        = 'group3';
        $value4        = '2';
        $label4        = 'Choose...4';
        $label4Escaped = 'Choose...4-escaped';
        $value5        = '3';
        $label5        = '...5';
        $label5Escaped = '...5-escaped';
        $label6        = 'group6';
        $value7        = '4';
        $label7        = 'Choose...7';
        $label7Escaped = 'Choose...7-escaped';
        $value8        = '5';
        $label8        = '...8';
        $label8Escaped = '...8-escaped';

        $class              = 'test-class';
        $ariaLabel          = 'test';
        $valueOptions       = [
            [
                'value' => $value1,
                'label' => $label1,
                'attributes' => ['selected' => true],
            ],
            [
                'value' => $value2,
                'label' => $label2,
            ],
            [
                'label' => $label3,
                'options' => [
                    [
                        'value' => $value4,
                        'label' => $label4,
                    ],
                    [
                        'value' => $value5,
                        'label' => $label5,
                    ],
                    [
                        'label' => $label6,
                        'options' => [
                            [
                                'value' => $value7,
                                'label' => $label7,
                            ],
                            [
                                'value' => $value8,
                                'label' => $label8,
                                'disabled' => true,
                            ],
                        ],
                    ],
                ],
            ],
        ];
        $attributes         = ['class' => $class, 'aria-label' => $ariaLabel, 'id' => $id, 'multiple' => true];
        $emptyOption        = '0';
        $emptyOptionEscaped = '0e';
        $expected           = sprintf(
            '<select class="form-select&#x20;%s" aria-label="%s" id="%s" multiple="multiple" name="%s&#x5B;&#x5D;">',
            $class,
            $ariaLabel,
            $id,
            $name,
        ) . PHP_EOL
            . sprintf('    <option value="">%s</option>', $emptyOptionEscaped) . PHP_EOL
            . sprintf(
                '    <option value="%s" selected="selected">%s</option>',
                $value1,
                $label1Escaped,
            ) . PHP_EOL
            . sprintf('    <option value="%s">%s</option>', $value2, $label2Escaped) . PHP_EOL
            . sprintf('    <optgroup label="%s">', $label3) . PHP_EOL
            . sprintf('        <option value="%s">%s</option>', $value4, $label4Escaped) . PHP_EOL
            . sprintf('        <option value="%s">%s</option>', $value5, $label5Escaped) . PHP_EOL
            . sprintf('        <optgroup label="%s">', $label6) . PHP_EOL
            . sprintf(
                '            <option value="%s" selected="selected">%s</option>',
                $value7,
                $label7Escaped,
            ) . PHP_EOL
            . sprintf(
                '            <option value="%s" selected="selected" disabled="disabled">%s</option>',
                $value8,
                $label8Escaped,
            ) . PHP_EOL
            . '        </optgroup>' . PHP_EOL
            . '    </optgroup>' . PHP_EOL
            . '</select>';

        $escapeHtml = $this->createMock(EscapeHtml::class);
        $escapeHtml->expects(self::exactly(7))
            ->method('__invoke')
            ->willReturnMap(
                [
                    [$emptyOption, 0, $emptyOptionEscaped],
                    [$label1, 0, $label1Escaped],
                    [$label2, 0, $label2Escaped],
                    [$label4, 0, $label4Escaped],
                    [$label5, 0, $label5Escaped],
                    [$label7, 0, $label7Escaped],
                    [$label8, 0, $label8Escaped],
                ],
            );

        $escapeHtmlAttr = $this->createMock(EscapeHtmlAttr::class);
        $matcher = self::exactly(4);
        $escapeHtmlAttr->expects($matcher)
            ->method('__invoke')
            ->willReturnCallback(
                static function (string $valueParam, int $recurse = AbstractHelper::RECURSE_NONE) use ($matcher, $class, $name): string {
                    $invocation = $matcher->numberOfInvocations();

                    match ($invocation) {
                        1 => self::assertSame(
                            'form-control ' . $class,
                            $valueParam,
                            (string) $invocation,
                        ),
                        2 => self::assertSame(
                            $name,
                            $valueParam,
                            (string) $invocation,
                        ),
                        3 => self::assertSame(
                            'week',
                            $valueParam,
                            (string) $invocation,
                        ),
                        default => self::assertSame(
                            '',
                            $valueParam,
                            (string) $invocation,
                        ),
                    };

                    self::assertSame(AbstractHelper::RECURSE_NONE, $recurse);

                    return match ($invocation) {
                        3 => 'week-escaped',
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

        $formHidden = $this->createMock(FormHiddenInterface::class);
        $formHidden->expects(self::never())
            ->method('render');

        $renderer = $this->createMock(PhpRenderer::class);
        $renderer->expects(self::never())
            ->method('getHelperPluginManager');
        $matcher = self::exactly(3);
        $renderer->expects($matcher)
            ->method('plugin')
            ->willReturnCallback(
                static function (string $name, ?array $options = null) use ($matcher, $escapeHtml, $escapeHtmlAttr, $doctype): HelperInterface|null {
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

        $element = $this->createMock(SelectElement::class);
        $element->expects(self::once())
            ->method('getName')
            ->willReturn($name);
        $element->expects(self::once())
            ->method('getValueOptions')
            ->willReturn($valueOptions);
        $element->expects(self::once())
            ->method('getAttributes')
            ->willReturn($attributes);
        $element->expects(self::once())
            ->method('getValue')
            ->willReturn([$value7, $value8]);
        $element->expects(self::once())
            ->method('useHiddenElement')
            ->willReturn(false);
        $element->expects(self::never())
            ->method('getLabelAttributes');
        $element->expects(self::never())
            ->method('getOption');
        $element->expects(self::never())
            ->method('getLabelOption');
        $element->expects(self::never())
            ->method('hasLabelOption');
        $element->expects(self::once())
            ->method('getEmptyOption')
            ->willReturn($emptyOption);
        $element->expects(self::never())
            ->method('getUnselectedValue');

        self::assertSame($expected, $this->helper->render($element));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws \Laminas\View\Exception\InvalidArgumentException
     * @throws RuntimeException
     */
    public function testRenderMultipleOptions2(): void
    {
        $name                    = 'test-name';
        $id                      = 'test-id';
        $value1                  = 'xyz';
        $label1                  = 'def1';
        $label1Translated        = 'def1-translated';
        $label1TranslatedEscaped = 'def1-translated-escaped';
        $value2                  = '1';
        $label2                  = '...2';
        $label2Translated        = '...2-translated';
        $label2TranslatedEscaped = '...2-translated-escaped';
        $label3                  = 'group3';
        $value4                  = '2';
        $label4                  = 'Choose...4';
        $label4Translated        = 'Choose...4-translated';
        $label4TranslatedEscaped = 'Choose...4-translated-escaped';
        $value5                  = '3';
        $label5                  = '...5';
        $label5Translated        = '...5-translated';
        $label5TranslatedEscaped = '...5-translated-escaped';
        $label6                  = 'group6';
        $value7                  = '4';
        $label7                  = 'Choose...7';
        $label7Translated        = 'Choose...7-translated';
        $label7TranslatedEscaped = 'Choose...7-translated-escaped';
        $value8                  = '5';
        $label8                  = '...8';
        $label8Translated        = '...8-translated';

        $class                        = 'test-class';
        $ariaLabel                    = 'test';
        $valueOptions                 = [
            [
                'value' => $value1,
                'label' => $label1,
                'attributes' => ['selected' => true],
            ],
            [
                'value' => $value2,
                'label' => $label2,
            ],
            [
                'label' => $label3,
                'options' => [
                    [
                        'value' => $value4,
                        'label' => $label4,
                    ],
                    [
                        'value' => $value5,
                        'label' => $label5,
                        'selected' => true,
                    ],
                    [
                        'label' => $label6,
                        'options' => [
                            [
                                'value' => $value7,
                                'label' => $label7,
                            ],
                            [
                                'value' => $value8,
                                'label' => $label8,
                                'disabled' => true,
                                'disable_html_escape' => true,
                            ],
                        ],
                    ],
                ],
            ],
        ];
        $attributes                   = ['class' => $class, 'aria-label' => $ariaLabel, 'id' => $id, 'multiple' => true];
        $emptyOption                  = '0';
        $emptyOptionTranslated        = '0t';
        $emptyOptionTranslatedEscaped = '0te';

        $expected   = sprintf(
            '<select class="form-select&#x20;%s" aria-label="%s" id="%s" multiple="multiple" name="%s&#x5B;&#x5D;">',
            $class,
            $ariaLabel,
            $id,
            $name,
        ) . PHP_EOL
            . sprintf('    <option value="">%s</option>', $emptyOptionTranslatedEscaped) . PHP_EOL
            . sprintf(
                '    <option value="%s" selected="selected">%s</option>',
                $value1,
                $label1TranslatedEscaped,
            ) . PHP_EOL
            . sprintf('    <option value="%s">%s</option>', $value2, $label2TranslatedEscaped) . PHP_EOL
            . sprintf('    <optgroup label="%s">', $label3) . PHP_EOL
            . sprintf(
                '        <option value="%s">%s</option>',
                $value4,
                $label4TranslatedEscaped,
            ) . PHP_EOL
            . sprintf(
                '        <option value="%s" selected="selected">%s</option>',
                $value5,
                $label5TranslatedEscaped,
            ) . PHP_EOL
            . sprintf('        <optgroup label="%s">', $label6) . PHP_EOL
            . sprintf(
                '            <option value="%s">%s</option>',
                $value7,
                $label7TranslatedEscaped,
            ) . PHP_EOL
            . sprintf(
                '            <option value="%s" disabled="disabled">%s</option>',
                $value8,
                $label8Translated,
            ) . PHP_EOL
            . '        </optgroup>' . PHP_EOL
            . '    </optgroup>' . PHP_EOL
            . '</select>';
        $textDomain = 'test-domain';

        $escapeHtml = $this->createMock(EscapeHtml::class);
        $escapeHtml->expects(self::exactly(6))
            ->method('__invoke')
            ->willReturnMap(
                [
                    [$emptyOptionTranslated, 0, $emptyOptionTranslatedEscaped],
                    [$label1Translated, 0, $label1TranslatedEscaped],
                    [$label2Translated, 0, $label2TranslatedEscaped],
                    [$label4Translated, 0, $label4TranslatedEscaped],
                    [$label5Translated, 0, $label5TranslatedEscaped],
                    [$label7Translated, 0, $label7TranslatedEscaped],
                ],
            );

        $escapeHtmlAttr = $this->createMock(EscapeHtmlAttr::class);
        $matcher = self::exactly(4);
        $escapeHtmlAttr->expects($matcher)
            ->method('__invoke')
            ->willReturnCallback(
                static function (string $valueParam, int $recurse = AbstractHelper::RECURSE_NONE) use ($matcher, $class, $name): string {
                    $invocation = $matcher->numberOfInvocations();

                    match ($invocation) {
                        1 => self::assertSame(
                            'form-control ' . $class,
                            $valueParam,
                            (string) $invocation,
                        ),
                        2 => self::assertSame(
                            $name,
                            $valueParam,
                            (string) $invocation,
                        ),
                        3 => self::assertSame(
                            'week',
                            $valueParam,
                            (string) $invocation,
                        ),
                        default => self::assertSame(
                            '',
                            $valueParam,
                            (string) $invocation,
                        ),
                    };

                    self::assertSame(AbstractHelper::RECURSE_NONE, $recurse);

                    return match ($invocation) {
                        3 => 'week-escaped',
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

        $formHidden = $this->createMock(FormHiddenInterface::class);
        $formHidden->expects(self::never())
            ->method('render');

        $translator = $this->createMock(Translate::class);
        $translator->expects(self::exactly(7))
            ->method('__invoke')
            ->willReturnMap(
                [
                    [$emptyOption, $textDomain, null, $emptyOptionTranslated],
                    [$label1, $textDomain, null, $label1Translated],
                    [$label2, $textDomain, null, $label2Translated],
                    [$label4, $textDomain, null, $label4Translated],
                    [$label5, $textDomain, null, $label5Translated],
                    [$label7, $textDomain, null, $label7Translated],
                    [$label8, $textDomain, null, $label8Translated],
                ],
            );

        $renderer = $this->createMock(PhpRenderer::class);
        $renderer->expects(self::never())
            ->method('getHelperPluginManager');
        $matcher = self::exactly(3);
        $renderer->expects($matcher)
            ->method('plugin')
            ->willReturnCallback(
                static function (string $name, ?array $options = null) use ($matcher, $escapeHtml, $escapeHtmlAttr, $doctype): HelperInterface|null {
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

        $element = $this->createMock(SelectElement::class);
        $element->expects(self::once())
            ->method('getName')
            ->willReturn($name);
        $element->expects(self::once())
            ->method('getValueOptions')
            ->willReturn($valueOptions);
        $element->expects(self::once())
            ->method('getAttributes')
            ->willReturn($attributes);
        $element->expects(self::once())
            ->method('getValue')
            ->willReturn([]);
        $element->expects(self::once())
            ->method('useHiddenElement')
            ->willReturn(false);
        $element->expects(self::never())
            ->method('getLabelAttributes');
        $element->expects(self::never())
            ->method('getOption');
        $element->expects(self::never())
            ->method('getLabelOption');
        $element->expects(self::never())
            ->method('hasLabelOption');
        $element->expects(self::once())
            ->method('getEmptyOption')
            ->willReturn($emptyOption);
        $element->expects(self::never())
            ->method('getUnselectedValue');

        $this->helper->setTranslatorTextDomain($textDomain);

        self::assertSame($expected, $this->helper->render($element));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws \Laminas\View\Exception\InvalidArgumentException
     * @throws RuntimeException
     */
    public function testRenderWithHiddenElement(): void
    {
        $name               = 'test-name';
        $id                 = 'test-id';
        $value1             = 'xyz';
        $value2             = 'def';
        $value2Escaped      = 'def-escaped';
        $value3             = 'abc';
        $class              = 'test-class';
        $ariaLabel          = 'test';
        $valueOptions       = [$value3 => $value2];
        $attributes         = ['class' => $class, 'aria-label' => $ariaLabel, 'id' => $id, 'multiple' => true];
        $emptyOption        = '0';
        $emptyOptionEscaped = '0e';
        $unselectedValue    = 'u';
        $expected           = sprintf(
            '<input type="hidden" name="%s" value="%s"/>',
            $name,
            $unselectedValue,
        ) . PHP_EOL
            . sprintf(
                '<select class="form-select&#x20;%s" aria-label="%s" id="%s" multiple="multiple" name="%s&#x5B;&#x5D;">',
                $class,
                $ariaLabel,
                $id,
                $name,
            ) . PHP_EOL
            . sprintf('    <option value="">%s</option>', $emptyOptionEscaped) . PHP_EOL
            . sprintf(
                '    <option value="%s" selected="selected">%s</option>',
                $value3,
                $value2Escaped,
            ) . PHP_EOL
            . '</select>';

        $escapeHtml = $this->createMock(EscapeHtml::class);
        $escapeHtml->expects(self::exactly(2))
            ->method('__invoke')
            ->willReturnMap(
                [
                    [$emptyOption, 0, $emptyOptionEscaped],
                    [$value2, 0, $value2Escaped],
                ],
            );

        $escapeHtmlAttr = $this->createMock(EscapeHtmlAttr::class);
        $matcher = self::exactly(4);
        $escapeHtmlAttr->expects($matcher)
            ->method('__invoke')
            ->willReturnCallback(
                static function (string $valueParam, int $recurse = AbstractHelper::RECURSE_NONE) use ($matcher, $class, $name): string {
                    $invocation = $matcher->numberOfInvocations();

                    match ($invocation) {
                        1 => self::assertSame(
                            'form-control ' . $class,
                            $valueParam,
                            (string) $invocation,
                        ),
                        2 => self::assertSame(
                            $name,
                            $valueParam,
                            (string) $invocation,
                        ),
                        3 => self::assertSame(
                            'week',
                            $valueParam,
                            (string) $invocation,
                        ),
                        default => self::assertSame(
                            '',
                            $valueParam,
                            (string) $invocation,
                        ),
                    };

                    self::assertSame(AbstractHelper::RECURSE_NONE, $recurse);

                    return match ($invocation) {
                        3 => 'week-escaped',
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

        $formHidden = $this->createMock(FormHiddenInterface::class);
        $formHidden->expects(self::once())
            ->method('render')
            ->with(new IsInstanceOf(Hidden::class))
            ->willReturn(
                sprintf('<input type="hidden" name="%s" value="%s"/>', $name, $unselectedValue),
            );

        $renderer = $this->createMock(PhpRenderer::class);
        $renderer->expects(self::never())
            ->method('getHelperPluginManager');
        $matcher = self::exactly(3);
        $renderer->expects($matcher)
            ->method('plugin')
            ->willReturnCallback(
                static function (string $name, ?array $options = null) use ($matcher, $escapeHtml, $escapeHtmlAttr, $doctype): HelperInterface|null {
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

        $element = $this->createMock(SelectElement::class);
        $element->expects(self::exactly(2))
            ->method('getName')
            ->willReturn($name);
        $element->expects(self::once())
            ->method('getValueOptions')
            ->willReturn($valueOptions);
        $element->expects(self::once())
            ->method('getAttributes')
            ->willReturn($attributes);
        $element->expects(self::once())
            ->method('getValue')
            ->willReturn([$value1, $value3]);
        $element->expects(self::once())
            ->method('useHiddenElement')
            ->willReturn(true);
        $element->expects(self::never())
            ->method('getLabelAttributes');
        $element->expects(self::never())
            ->method('getOption');
        $element->expects(self::never())
            ->method('getLabelOption');
        $element->expects(self::never())
            ->method('hasLabelOption');
        $element->expects(self::once())
            ->method('getEmptyOption')
            ->willReturn($emptyOption);
        $element->expects(self::once())
            ->method('getUnselectedValue')
            ->willReturn($unselectedValue);

        self::assertSame($expected, $this->helper->render($element));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws \Laminas\View\Exception\InvalidArgumentException
     * @throws RuntimeException
     */
    public function testInvokeWithHiddenElement1(): void
    {
        $name               = 'test-name';
        $id                 = 'test-id';
        $value1             = 'xyz';
        $value2             = 'def';
        $value2Escaped      = 'def-escaped';
        $value3             = 'abc';
        $class              = 'test-class';
        $ariaLabel          = 'test';
        $valueOptions       = [$value3 => $value2];
        $attributes         = ['class' => $class, 'aria-label' => $ariaLabel, 'id' => $id, 'multiple' => true];
        $emptyOption        = '0';
        $emptyOptionEscaped = '0e';
        $unselectedValue    = 'u';
        $expected           = sprintf(
                '<input type="hidden" name="%s" value="%s"/>',
                $name,
                $unselectedValue,
            ) . PHP_EOL
            . sprintf(
                '<select class="form-select&#x20;%s" aria-label="%s" id="%s" multiple="multiple" name="%s&#x5B;&#x5D;">',
                $class,
                $ariaLabel,
                $id,
                $name,
            ) . PHP_EOL
            . sprintf('    <option value="">%s</option>', $emptyOptionEscaped) . PHP_EOL
            . sprintf(
                '    <option value="%s" selected="selected">%s</option>',
                $value3,
                $value2Escaped,
            ) . PHP_EOL
            . '</select>';

        $escapeHtml = $this->createMock(EscapeHtml::class);
        $escapeHtml->expects(self::exactly(2))
            ->method('__invoke')
            ->willReturnMap(
                [
                    [$emptyOption, 0, $emptyOptionEscaped],
                    [$value2, 0, $value2Escaped],
                ],
            );

        $escapeHtmlAttr = $this->createMock(EscapeHtmlAttr::class);
        $matcher = self::exactly(4);
        $escapeHtmlAttr->expects($matcher)
            ->method('__invoke')
            ->willReturnCallback(
                static function (string $valueParam, int $recurse = AbstractHelper::RECURSE_NONE) use ($matcher, $class, $name): string {
                    $invocation = $matcher->numberOfInvocations();

                    match ($invocation) {
                        1 => self::assertSame(
                            'form-control ' . $class,
                            $valueParam,
                            (string) $invocation,
                        ),
                        2 => self::assertSame(
                            $name,
                            $valueParam,
                            (string) $invocation,
                        ),
                        3 => self::assertSame(
                            'week',
                            $valueParam,
                            (string) $invocation,
                        ),
                        default => self::assertSame(
                            '',
                            $valueParam,
                            (string) $invocation,
                        ),
                    };

                    self::assertSame(AbstractHelper::RECURSE_NONE, $recurse);

                    return match ($invocation) {
                        3 => 'week-escaped',
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

        $formHidden = $this->createMock(FormHiddenInterface::class);
        $formHidden->expects(self::once())
            ->method('render')
            ->with(new IsInstanceOf(Hidden::class))
            ->willReturn(
                sprintf('<input type="hidden" name="%s" value="%s"/>', $name, $unselectedValue),
            );

        $renderer = $this->createMock(PhpRenderer::class);
        $renderer->expects(self::never())
            ->method('getHelperPluginManager');
        $matcher = self::exactly(3);
        $renderer->expects($matcher)
            ->method('plugin')
            ->willReturnCallback(
                static function (string $name, ?array $options = null) use ($matcher, $escapeHtml, $escapeHtmlAttr, $doctype): HelperInterface|null {
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

        $element = $this->createMock(SelectElement::class);
        $element->expects(self::exactly(2))
            ->method('getName')
            ->willReturn($name);
        $element->expects(self::once())
            ->method('getValueOptions')
            ->willReturn($valueOptions);
        $element->expects(self::once())
            ->method('getAttributes')
            ->willReturn($attributes);
        $element->expects(self::once())
            ->method('getValue')
            ->willReturn([$value1, $value3]);
        $element->expects(self::once())
            ->method('useHiddenElement')
            ->willReturn(true);
        $element->expects(self::never())
            ->method('getLabelAttributes');
        $element->expects(self::never())
            ->method('getOption');
        $element->expects(self::never())
            ->method('getLabelOption');
        $element->expects(self::never())
            ->method('hasLabelOption');
        $element->expects(self::once())
            ->method('getEmptyOption')
            ->willReturn($emptyOption);
        $element->expects(self::once())
            ->method('getUnselectedValue')
            ->willReturn($unselectedValue);

        $helperObject = ($this->helper)();

        assert($helperObject instanceof FormSelect);

        self::assertSame($expected, $helperObject->render($element));
    }

    /**
     * @throws Exception
     * @throws ContainerExceptionInterface
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws \Laminas\View\Exception\InvalidArgumentException
     * @throws RuntimeException
     */
    public function testInvokeWithHiddenElement2(): void
    {
        $name               = 'test-name';
        $id                 = 'test-id';
        $value1             = 'xyz';
        $value2             = 'def';
        $value2Escaped      = 'def-escaped';
        $value3             = 'abc';
        $class              = 'test-class';
        $ariaLabel          = 'test';
        $valueOptions       = [$value3 => $value2];
        $attributes         = ['class' => $class, 'aria-label' => $ariaLabel, 'id' => $id, 'multiple' => true];
        $emptyOption        = '0';
        $emptyOptionEscaped = '0e';
        $unselectedValue    = 'u';
        $expected           = sprintf(
                '<input type="hidden" name="%s" value="%s"/>',
                $name,
                $unselectedValue,
            ) . PHP_EOL
            . sprintf(
                '<select class="form-select&#x20;%s" aria-label="%s" id="%s" multiple="multiple" name="%s&#x5B;&#x5D;">',
                $class,
                $ariaLabel,
                $id,
                $name,
            ) . PHP_EOL
            . sprintf('    <option value="">%s</option>', $emptyOptionEscaped) . PHP_EOL
            . sprintf(
                '    <option value="%s" selected="selected">%s</option>',
                $value3,
                $value2Escaped,
            ) . PHP_EOL
            . '</select>';

        $escapeHtml = $this->createMock(EscapeHtml::class);
        $escapeHtml->expects(self::exactly(2))
            ->method('__invoke')
            ->willReturnMap(
                [
                    [$emptyOption, 0, $emptyOptionEscaped],
                    [$value2, 0, $value2Escaped],
                ],
            );

        $escapeHtmlAttr = $this->createMock(EscapeHtmlAttr::class);
        $matcher = self::exactly(4);
        $escapeHtmlAttr->expects($matcher)
            ->method('__invoke')
            ->willReturnCallback(
                static function (string $valueParam, int $recurse = AbstractHelper::RECURSE_NONE) use ($matcher, $class, $name): string {
                    $invocation = $matcher->numberOfInvocations();

                    match ($invocation) {
                        1 => self::assertSame(
                            'form-control ' . $class,
                            $valueParam,
                            (string) $invocation,
                        ),
                        2 => self::assertSame(
                            $name,
                            $valueParam,
                            (string) $invocation,
                        ),
                        3 => self::assertSame(
                            'week',
                            $valueParam,
                            (string) $invocation,
                        ),
                        default => self::assertSame(
                            '',
                            $valueParam,
                            (string) $invocation,
                        ),
                    };

                    self::assertSame(AbstractHelper::RECURSE_NONE, $recurse);

                    return match ($invocation) {
                        3 => 'week-escaped',
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

        $formHidden = $this->createMock(FormHiddenInterface::class);
        $formHidden->expects(self::once())
            ->method('render')
            ->with(new IsInstanceOf(Hidden::class))
            ->willReturn(
                sprintf('<input type="hidden" name="%s" value="%s"/>', $name, $unselectedValue),
            );

        $renderer = $this->createMock(PhpRenderer::class);
        $renderer->expects(self::never())
            ->method('getHelperPluginManager');
        $matcher = self::exactly(3);
        $renderer->expects($matcher)
            ->method('plugin')
            ->willReturnCallback(
                static function (string $name, ?array $options = null) use ($matcher, $escapeHtml, $escapeHtmlAttr, $doctype): HelperInterface|null {
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

        $element = $this->createMock(SelectElement::class);
        $element->expects(self::exactly(2))
            ->method('getName')
            ->willReturn($name);
        $element->expects(self::once())
            ->method('getValueOptions')
            ->willReturn($valueOptions);
        $element->expects(self::once())
            ->method('getAttributes')
            ->willReturn($attributes);
        $element->expects(self::once())
            ->method('getValue')
            ->willReturn([$value1, $value3]);
        $element->expects(self::once())
            ->method('useHiddenElement')
            ->willReturn(true);
        $element->expects(self::never())
            ->method('getLabelAttributes');
        $element->expects(self::never())
            ->method('getOption');
        $element->expects(self::never())
            ->method('getLabelOption');
        $element->expects(self::never())
            ->method('hasLabelOption');
        $element->expects(self::once())
            ->method('getEmptyOption')
            ->willReturn($emptyOption);
        $element->expects(self::once())
            ->method('getUnselectedValue')
            ->willReturn($unselectedValue);

        self::assertSame($expected, ($this->helper)($element));
    }

    /**
     * @throws Exception
     * @throws ContainerExceptionInterface
     */
    public function testSetGetIndent1(): void
    {
        $escapeHtml = $this->createMock(EscapeHtml::class);
        $escapeHtml->expects(self::never())
            ->method('__invoke');

        $formHidden = $this->createMock(FormHiddenInterface::class);
        $formHidden->expects(self::never())
            ->method('render');

        $renderer = $this->createMock(PhpRenderer::class);
        $renderer->expects(self::never())
            ->method('getHelperPluginManager');
        $renderer->expects(self::never())
            ->method('plugin');
        $renderer->expects(self::never())
            ->method('render');

        $this->helper->setView($renderer);

        self::assertSame($this->helper, $this->helper->setIndent(4));
        self::assertSame('    ', $this->helper->getIndent());
    }

    /**
     * @throws Exception
     * @throws ContainerExceptionInterface
     */
    public function testSetGetIndent2(): void
    {
        $escapeHtml = $this->createMock(EscapeHtml::class);
        $escapeHtml->expects(self::never())
            ->method('__invoke');

        $formHidden = $this->createMock(FormHiddenInterface::class);
        $formHidden->expects(self::never())
            ->method('render');

        $renderer = $this->createMock(PhpRenderer::class);
        $renderer->expects(self::never())
            ->method('getHelperPluginManager');
        $renderer->expects(self::never())
            ->method('plugin');
        $renderer->expects(self::never())
            ->method('render');

        $this->helper->setView($renderer);

        self::assertSame($this->helper, $this->helper->setIndent('  '));
        self::assertSame('  ', $this->helper->getIndent());
    }
}
