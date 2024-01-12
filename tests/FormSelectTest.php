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
use Laminas\Form\ElementInterface;
use Laminas\Form\Exception\DomainException;
use Laminas\Form\Exception\InvalidArgumentException;
use Laminas\I18n\Exception\RuntimeException;
use Laminas\I18n\Translator\TranslatorInterface as Translator;
use Laminas\View\Helper\Doctype;
use Laminas\View\Helper\EscapeHtml;
use Laminas\View\Helper\EscapeHtmlAttr;
use Laminas\View\Helper\Escaper\AbstractHelper;
use Laminas\View\Helper\HelperInterface;
use Laminas\View\Renderer\PhpRenderer;
use Mimmi20\LaminasView\BootstrapForm\FormHiddenInterface;
use Mimmi20\LaminasView\BootstrapForm\FormSelect;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;

use function assert;
use function sprintf;

use const PHP_EOL;

#[Group('form-select')]
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
        $nameEscaped        = 'test-name-escaped';
        $id                 = 'test-id';
        $idEscaped          = 'test-id-escaped';
        $value2             = 'def';
        $value2Escaped      = 'def-escaped';
        $value3             = 'abc';
        $value3Escaped      = 'abc-escaped';
        $class              = 'test-class';
        $classEscaped       = 'test-class-escaped';
        $ariaLabel          = 'test';
        $ariaLabelEscaped   = 'test-escaped';
        $valueOptions       = [$value3 => $value2];
        $attributes         = ['class' => $class, 'aria-label' => $ariaLabel, 'id' => $id];
        $emptyOption        = '0';
        $emptyOptionEscaped = '0e';
        $expected           = sprintf(
            '<select classEscaped="%s" aria-labelEscaped="%s" idEscaped="%s" nameEscaped="%s">',
            $classEscaped,
            $ariaLabelEscaped,
            $idEscaped,
            $nameEscaped,
        ) . PHP_EOL
            . sprintf('    <option valueEscaped="">%s</option>', $emptyOptionEscaped) . PHP_EOL
            . sprintf(
                '    <option valueEscaped="%s">%s</option>',
                $value3Escaped,
                $value2Escaped,
            ) . PHP_EOL
            . '</select>';

        $escapeHtml = $this->createMock(EscapeHtml::class);
        $matcher    = self::exactly(8);
        $escapeHtml->expects($matcher)
            ->method('__invoke')
            ->willReturnCallback(
                static function (string $value, int $recurse = AbstractHelper::RECURSE_NONE) use ($matcher, $emptyOption, $emptyOptionEscaped, $value2, $value2Escaped): string {
                    $invocation = $matcher->numberOfInvocations();

                    match ($invocation) {
                        1 => self::assertSame(
                            $emptyOption,
                            $value,
                            (string) $invocation,
                        ),
                        2, 4 => self::assertSame(
                            'value',
                            $value,
                            (string) $invocation,
                        ),
                        3 => self::assertSame(
                            $value2,
                            $value,
                            (string) $invocation,
                        ),
                        5 => self::assertSame(
                            'class',
                            $value,
                            (string) $invocation,
                        ),
                        6 => self::assertSame(
                            'aria-label',
                            $value,
                            (string) $invocation,
                        ),
                        7 => self::assertSame(
                            'id',
                            $value,
                            (string) $invocation,
                        ),
                        8 => self::assertSame(
                            'name',
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
                        1 => $emptyOptionEscaped,
                        2, 4 => 'valueEscaped',
                        3 => $value2Escaped,
                        5 => 'classEscaped',
                        6 => 'aria-labelEscaped',
                        7 => 'idEscaped',
                        8 => 'nameEscaped',
                        default => '',
                    };
                },
            );

        $escapeHtmlAttr = $this->createMock(EscapeHtmlAttr::class);
        $matcher        = self::exactly(6);
        $escapeHtmlAttr->expects($matcher)
            ->method('__invoke')
            ->willReturnCallback(
                static function (string $valueParam, int $recurse = AbstractHelper::RECURSE_NONE) use ($matcher, $class, $classEscaped, $value3, $value3Escaped, $ariaLabel, $ariaLabelEscaped, $id, $idEscaped, $name, $nameEscaped): string {
                    $invocation = $matcher->numberOfInvocations();

                    match ($invocation) {
                        1 => self::assertSame(
                            '',
                            $valueParam,
                            (string) $invocation,
                        ),
                        2 => self::assertSame(
                            $value3,
                            $valueParam,
                            (string) $invocation,
                        ),
                        3 => self::assertSame(
                            'form-select ' . $class,
                            $valueParam,
                            (string) $invocation,
                        ),
                        4 => self::assertSame(
                            $ariaLabel,
                            $valueParam,
                            (string) $invocation,
                        ),
                        5 => self::assertSame(
                            $id,
                            $valueParam,
                            (string) $invocation,
                        ),
                        6 => self::assertSame(
                            $name,
                            $valueParam,
                            (string) $invocation,
                        ),
                        default => self::assertSame(
                            'x',
                            $valueParam,
                            (string) $invocation,
                        ),
                    };

                    self::assertSame(AbstractHelper::RECURSE_NONE, $recurse, (string) $invocation);

                    return match ($invocation) {
                        2 => $value3Escaped,
                        3 => $classEscaped,
                        4 => $ariaLabelEscaped,
                        5 => $idEscaped,
                        6 => $nameEscaped,
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
        $nameEscaped        = 'test-name-escaped';
        $id                 = 'test-id';
        $idEscaped          = 'test-id-escaped';
        $value1             = 'xyz';
        $value2             = 'def';
        $value2Escaped      = 'def-escaped';
        $value3             = 'abc';
        $value3Escaped      = 'abc-escaped';
        $class              = 'test-class';
        $classEscaped       = 'test-class-escaped';
        $ariaLabel          = 'test';
        $ariaLabelEscaped   = 'test-escaped';
        $valueOptions       = [$value3 => $value2];
        $attributes         = ['class' => $class, 'aria-label' => $ariaLabel, 'id' => $id];
        $emptyOption        = '0';
        $emptyOptionEscaped = '0e';
        $expected           = sprintf(
            '<select classEscaped="%s" aria-labelEscaped="%s" idEscaped="%s" nameEscaped="%s">',
            $classEscaped,
            $ariaLabelEscaped,
            $idEscaped,
            $nameEscaped,
        ) . PHP_EOL
            . sprintf('    <option valueEscaped="">%s</option>', $emptyOptionEscaped) . PHP_EOL
            . sprintf(
                '    <option valueEscaped="%s">%s</option>',
                $value3Escaped,
                $value2Escaped,
            ) . PHP_EOL
            . '</select>';

        $escapeHtml = $this->createMock(EscapeHtml::class);
        $matcher    = self::exactly(8);
        $escapeHtml->expects($matcher)
            ->method('__invoke')
            ->willReturnCallback(
                static function (string $value, int $recurse = AbstractHelper::RECURSE_NONE) use ($matcher, $emptyOption, $emptyOptionEscaped, $value2, $value2Escaped): string {
                    $invocation = $matcher->numberOfInvocations();

                    match ($invocation) {
                        1 => self::assertSame(
                            $emptyOption,
                            $value,
                            (string) $invocation,
                        ),
                        2, 4 => self::assertSame(
                            'value',
                            $value,
                            (string) $invocation,
                        ),
                        3 => self::assertSame(
                            $value2,
                            $value,
                            (string) $invocation,
                        ),
                        5 => self::assertSame(
                            'class',
                            $value,
                            (string) $invocation,
                        ),
                        6 => self::assertSame(
                            'aria-label',
                            $value,
                            (string) $invocation,
                        ),
                        7 => self::assertSame(
                            'id',
                            $value,
                            (string) $invocation,
                        ),
                        8 => self::assertSame(
                            'name',
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
                        1 => $emptyOptionEscaped,
                        2, 4 => 'valueEscaped',
                        3 => $value2Escaped,
                        5 => 'classEscaped',
                        6 => 'aria-labelEscaped',
                        7 => 'idEscaped',
                        8 => 'nameEscaped',
                        default => '',
                    };
                },
            );

        $escapeHtmlAttr = $this->createMock(EscapeHtmlAttr::class);
        $matcher        = self::exactly(6);
        $escapeHtmlAttr->expects($matcher)
            ->method('__invoke')
            ->willReturnCallback(
                static function (string $valueParam, int $recurse = AbstractHelper::RECURSE_NONE) use ($matcher, $class, $classEscaped, $value3, $value3Escaped, $ariaLabel, $ariaLabelEscaped, $id, $idEscaped, $name, $nameEscaped): string {
                    $invocation = $matcher->numberOfInvocations();

                    match ($invocation) {
                        1 => self::assertSame(
                            '',
                            $valueParam,
                            (string) $invocation,
                        ),
                        2 => self::assertSame(
                            $value3,
                            $valueParam,
                            (string) $invocation,
                        ),
                        3 => self::assertSame(
                            'form-select ' . $class,
                            $valueParam,
                            (string) $invocation,
                        ),
                        4 => self::assertSame(
                            $ariaLabel,
                            $valueParam,
                            (string) $invocation,
                        ),
                        5 => self::assertSame(
                            $id,
                            $valueParam,
                            (string) $invocation,
                        ),
                        6 => self::assertSame(
                            $name,
                            $valueParam,
                            (string) $invocation,
                        ),
                        default => self::assertSame(
                            'x',
                            $valueParam,
                            (string) $invocation,
                        ),
                    };

                    self::assertSame(AbstractHelper::RECURSE_NONE, $recurse, (string) $invocation);

                    return match ($invocation) {
                        2 => $value3Escaped,
                        3 => $classEscaped,
                        4 => $ariaLabelEscaped,
                        5 => $idEscaped,
                        6 => $nameEscaped,
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
        $nameEscaped        = 'test-name-escaped';
        $id                 = 'test-id';
        $idEscaped          = 'test-id-escaped';
        $value1             = 'xyz';
        $value2             = 'def';
        $value2Escaped      = 'def-escaped';
        $value3             = 'abc';
        $value3Escaped      = 'abc-escaped';
        $class              = 'test-class';
        $classEscaped       = 'test-class-escaped';
        $ariaLabel          = 'test';
        $ariaLabelEscaped   = 'test-escaped';
        $valueOptions       = [$value3 => $value2];
        $attributes         = ['class' => $class, 'aria-label' => $ariaLabel, 'id' => $id, 'multiple' => true];
        $emptyOption        = '0';
        $emptyOptionEscaped = '0e';
        $expected           = sprintf(
            '<select classEscaped="%s" aria-labelEscaped="%s" idEscaped="%s" multipleEscaped="multiple-escaped" nameEscaped="%s">',
            $classEscaped,
            $ariaLabelEscaped,
            $idEscaped,
            $nameEscaped,
        ) . PHP_EOL
            . sprintf('    <option valueEscaped="">%s</option>', $emptyOptionEscaped) . PHP_EOL
            . sprintf(
                '    <option valueEscaped="%s" selectedEscaped="selected-escaped">%s</option>',
                $value3Escaped,
                $value2Escaped,
            ) . PHP_EOL
            . '</select>';

        $escapeHtml = $this->createMock(EscapeHtml::class);
        $matcher    = self::exactly(10);
        $escapeHtml->expects($matcher)
            ->method('__invoke')
            ->willReturnCallback(
                static function (string $value, int $recurse = AbstractHelper::RECURSE_NONE) use ($matcher, $emptyOption, $emptyOptionEscaped, $value2, $value2Escaped): string {
                    $invocation = $matcher->numberOfInvocations();

                    match ($invocation) {
                        1 => self::assertSame(
                            $emptyOption,
                            $value,
                            (string) $invocation,
                        ),
                        2, 4 => self::assertSame(
                            'value',
                            $value,
                            (string) $invocation,
                        ),
                        3 => self::assertSame(
                            $value2,
                            $value,
                            (string) $invocation,
                        ),
                        5 => self::assertSame(
                            'selected',
                            $value,
                            (string) $invocation,
                        ),
                        6 => self::assertSame(
                            'class',
                            $value,
                            (string) $invocation,
                        ),
                        7 => self::assertSame(
                            'aria-label',
                            $value,
                            (string) $invocation,
                        ),
                        8 => self::assertSame(
                            'id',
                            $value,
                            (string) $invocation,
                        ),
                        9 => self::assertSame(
                            'multiple',
                            $value,
                            (string) $invocation,
                        ),
                        10 => self::assertSame(
                            'name',
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
                        1 => $emptyOptionEscaped,
                        2, 4 => 'valueEscaped',
                        3 => $value2Escaped,
                        5 => 'selectedEscaped',
                        6 => 'classEscaped',
                        7 => 'aria-labelEscaped',
                        8 => 'idEscaped',
                        9 => 'multipleEscaped',
                        10 => 'nameEscaped',
                        default => '',
                    };
                },
            );

        $escapeHtmlAttr = $this->createMock(EscapeHtmlAttr::class);
        $matcher        = self::exactly(8);
        $escapeHtmlAttr->expects($matcher)
            ->method('__invoke')
            ->willReturnCallback(
                static function (string $valueParam, int $recurse = AbstractHelper::RECURSE_NONE) use ($matcher, $class, $classEscaped, $value3, $value3Escaped, $ariaLabel, $ariaLabelEscaped, $id, $idEscaped, $name, $nameEscaped): string {
                    $invocation = $matcher->numberOfInvocations();

                    match ($invocation) {
                        1 => self::assertSame(
                            '',
                            $valueParam,
                            (string) $invocation,
                        ),
                        2 => self::assertSame(
                            $value3,
                            $valueParam,
                            (string) $invocation,
                        ),
                        3 => self::assertSame(
                            'selected',
                            $valueParam,
                            (string) $invocation,
                        ),
                        4 => self::assertSame(
                            'form-select ' . $class,
                            $valueParam,
                            (string) $invocation,
                        ),
                        5 => self::assertSame(
                            $ariaLabel,
                            $valueParam,
                            (string) $invocation,
                        ),
                        6 => self::assertSame(
                            $id,
                            $valueParam,
                            (string) $invocation,
                        ),
                        7 => self::assertSame(
                            'multiple',
                            $valueParam,
                            (string) $invocation,
                        ),
                        8 => self::assertSame(
                            $name . '[]',
                            $valueParam,
                            (string) $invocation,
                        ),
                        default => self::assertSame(
                            'x',
                            $valueParam,
                            (string) $invocation,
                        ),
                    };

                    self::assertSame(AbstractHelper::RECURSE_NONE, $recurse, (string) $invocation);

                    return match ($invocation) {
                        2 => $value3Escaped,
                        3 => 'selected-escaped',
                        4 => $classEscaped,
                        5 => $ariaLabelEscaped,
                        6 => $idEscaped,
                        7 => 'multiple-escaped',
                        8 => $nameEscaped,
                        default => '',
                    };
                },
            );

        $doctype = $this->createMock(Doctype::class);
        $doctype->expects(self::never())
            ->method('__invoke');
       $doctype->expects(self::never())
           ->method('isXhtml');
        $doctype->expects(self::exactly(2))
            ->method('isHtml5')
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
        $name             = 'test-name';
        $nameEscaped      = 'test-name-escaped';
        $id               = 'test-id';
        $idEscaped        = 'test-id-escaped';
        $value1           = 'xyz';
        $value1Escaped    = 'xyz-escaped';
        $label1           = 'def1';
        $label1Escaped    = 'def1-escaped';
        $value2           = '1';
        $value2Escaped    = '1-escaped';
        $label2           = '...2';
        $label2Escaped    = '...2-escaped';
        $label3           = 'group3';
        $label3Escaped    = 'group3-escaped';
        $value4           = '2';
        $value4Escaped    = '2-escaped';
        $label4           = 'Choose...4';
        $label4Escaped    = 'Choose...4-escaped';
        $value5           = '3';
        $value5Escaped    = '3-escaped';
        $label5           = '...5';
        $label5Escaped    = '...5-escaped';
        $label6           = 'group6';
        $label6Escaped    = 'group6-escaped';
        $value7           = '4';
        $value7Escaped    = '4-escaped';
        $label7           = 'Choose...7';
        $label7Escaped    = 'Choose...7-escaped';
        $value8           = '5';
        $value8Escaped    = '5-escaped';
        $label8           = '...8';
        $label8Escaped    = '...8-escaped';
        $class            = 'test-class';
        $classEscaped     = 'test-class-escaped';
        $ariaLabel        = 'test';
        $ariaLabelEscaped = 'test-escaped';

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
            '<select classEscaped="%s" aria-labelEscaped="%s" idEscaped="%s" multipleEscaped="multiple-escaped" nameEscaped="%s">',
            $classEscaped,
            $ariaLabelEscaped,
            $idEscaped,
            $nameEscaped,
        ) . PHP_EOL
            . sprintf('    <option valueEscaped="">%s</option>', $emptyOptionEscaped) . PHP_EOL
            . sprintf(
                '    <option valueEscaped="%s" selectedEscaped="selected-escaped">%s</option>',
                $value1Escaped,
                $label1Escaped,
            ) . PHP_EOL
            . sprintf(
                '    <option valueEscaped="%s">%s</option>',
                $value2Escaped,
                $label2Escaped,
            ) . PHP_EOL
            . sprintf('    <optgroup labelEscaped="%s">', $label3Escaped) . PHP_EOL
            . sprintf(
                '        <option valueEscaped="%s">%s</option>',
                $value4Escaped,
                $label4Escaped,
            ) . PHP_EOL
            . sprintf(
                '        <option valueEscaped="%s">%s</option>',
                $value5Escaped,
                $label5Escaped,
            ) . PHP_EOL
            . sprintf('        <optgroup labelEscaped="%s">', $label6Escaped) . PHP_EOL
            . sprintf(
                '            <option valueEscaped="%s" selectedEscaped="selected-escaped">%s</option>',
                $value7Escaped,
                $label7Escaped,
            ) . PHP_EOL
            . sprintf(
                '            <option valueEscaped="%s" selectedEscaped="selected-escaped" disabledEscaped="disabled-escaped">%s</option>',
                $value8Escaped,
                $label8Escaped,
            ) . PHP_EOL
            . '        </optgroup>' . PHP_EOL
            . '    </optgroup>' . PHP_EOL
            . '</select>';

        $escapeHtml = $this->createMock(EscapeHtml::class);
        $matcher    = self::exactly(25);
        $escapeHtml->expects($matcher)
            ->method('__invoke')
            ->willReturnCallback(
                static function (string $value, int $recurse = AbstractHelper::RECURSE_NONE) use ($matcher, $emptyOption, $emptyOptionEscaped, $label1, $label1Escaped, $label2, $label2Escaped, $label4, $label4Escaped, $label5, $label5Escaped, $label7, $label7Escaped, $label8, $label8Escaped): string {
                    $invocation = $matcher->numberOfInvocations();

                    match ($invocation) {
                        1 => self::assertSame(
                            $emptyOption,
                            $value,
                            (string) $invocation,
                        ),
                        2, 4, 7, 10, 12, 15, 18 => self::assertSame(
                            'value',
                            $value,
                            (string) $invocation,
                        ),
                        3 => self::assertSame(
                            $label1,
                            $value,
                            (string) $invocation,
                        ),
                        5, 16, 19 => self::assertSame(
                            'selected',
                            $value,
                            (string) $invocation,
                        ),
                        6 => self::assertSame(
                            $label2,
                            $value,
                            (string) $invocation,
                        ),
                        8, 13 => self::assertSame(
                            'label',
                            $value,
                            (string) $invocation,
                        ),
                        9 => self::assertSame(
                            $label4,
                            $value,
                            (string) $invocation,
                        ),
                        11 => self::assertSame(
                            $label5,
                            $value,
                            (string) $invocation,
                        ),
                        14 => self::assertSame(
                            $label7,
                            $value,
                            (string) $invocation,
                        ),
                        17 => self::assertSame(
                            $label8,
                            $value,
                            (string) $invocation,
                        ),
                        20 => self::assertSame(
                            'disabled',
                            $value,
                            (string) $invocation,
                        ),
                        21 => self::assertSame(
                            'class',
                            $value,
                            (string) $invocation,
                        ),
                        22 => self::assertSame(
                            'aria-label',
                            $value,
                            (string) $invocation,
                        ),
                        23 => self::assertSame(
                            'id',
                            $value,
                            (string) $invocation,
                        ),
                        24 => self::assertSame(
                            'multiple',
                            $value,
                            (string) $invocation,
                        ),
                        25 => self::assertSame(
                            'name',
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
                        1 => $emptyOptionEscaped,
                        2, 4, 7, 10, 12, 15, 18 => 'valueEscaped',
                        3 => $label1Escaped,
                        5, 16, 19 => 'selectedEscaped',
                        6 => $label2Escaped,
                        8, 13 => 'labelEscaped',
                        9 => $label4Escaped,
                        11 => $label5Escaped,
                        14 => $label7Escaped,
                        17 => $label8Escaped,
                        20 => 'disabledEscaped',
                        21 => 'classEscaped',
                        22 => 'aria-labelEscaped',
                        23 => 'idEscaped',
                        24 => 'multipleEscaped',
                        25 => 'nameEscaped',
                        default => '',
                    };
                },
            );

        $escapeHtmlAttr = $this->createMock(EscapeHtmlAttr::class);
        $matcher        = self::exactly(18);
        $escapeHtmlAttr->expects($matcher)
            ->method('__invoke')
            ->willReturnCallback(
                static function (string $valueParam, int $recurse = AbstractHelper::RECURSE_NONE) use ($matcher, $class, $classEscaped, $value1, $value1Escaped, $value2, $value2Escaped, $label3, $label3Escaped, $value4, $value4Escaped, $value5, $value5Escaped, $label6, $label6Escaped, $value7, $value7Escaped, $value8, $value8Escaped, $ariaLabel, $ariaLabelEscaped, $id, $idEscaped, $name, $nameEscaped): string {
                    $invocation = $matcher->numberOfInvocations();

                    match ($invocation) {
                        1 => self::assertSame(
                            '',
                            $valueParam,
                            (string) $invocation,
                        ),
                        2 => self::assertSame(
                            $value1,
                            $valueParam,
                            (string) $invocation,
                        ),
                        3, 10, 12 => self::assertSame(
                            'selected',
                            $valueParam,
                            (string) $invocation,
                        ),
                        4 => self::assertSame(
                            $value2,
                            $valueParam,
                            (string) $invocation,
                        ),
                        5 => self::assertSame(
                            $label3,
                            $valueParam,
                            (string) $invocation,
                        ),
                        6 => self::assertSame(
                            $value4,
                            $valueParam,
                            (string) $invocation,
                        ),
                        7 => self::assertSame(
                            $value5,
                            $valueParam,
                            (string) $invocation,
                        ),
                        8 => self::assertSame(
                            $label6,
                            $valueParam,
                            (string) $invocation,
                        ),
                        9 => self::assertSame(
                            $value7,
                            $valueParam,
                            (string) $invocation,
                        ),
                        11 => self::assertSame(
                            $value8,
                            $valueParam,
                            (string) $invocation,
                        ),
                        13 => self::assertSame(
                            'disabled',
                            $valueParam,
                            (string) $invocation,
                        ),
                        14 => self::assertSame(
                            'form-select ' . $class,
                            $valueParam,
                            (string) $invocation,
                        ),
                        15 => self::assertSame(
                            $ariaLabel,
                            $valueParam,
                            (string) $invocation,
                        ),
                        16 => self::assertSame(
                            $id,
                            $valueParam,
                            (string) $invocation,
                        ),
                        17 => self::assertSame(
                            'multiple',
                            $valueParam,
                            (string) $invocation,
                        ),
                        18 => self::assertSame(
                            $name . '[]',
                            $valueParam,
                            (string) $invocation,
                        ),
                        default => self::assertSame(
                            'x',
                            $valueParam,
                            (string) $invocation,
                        ),
                    };

                    self::assertSame(AbstractHelper::RECURSE_NONE, $recurse, (string) $invocation);

                    return match ($invocation) {
                        2 => $value1Escaped,
                        3, 10, 12 => 'selected-escaped',
                        4 => $value2Escaped,
                        5 => $label3Escaped,
                        6 => $value4Escaped,
                        7 => $value5Escaped,
                        8 => $label6Escaped,
                        9 => $value7Escaped,
                        11 => $value8Escaped,
                        13 => 'disabled-escaped',
                        14 => $classEscaped,
                        15 => $ariaLabelEscaped,
                        16 => $idEscaped,
                        17 => 'multiple-escaped',
                        18 => $nameEscaped,
                        default => '',
                    };
                },
            );

        $doctype = $this->createMock(Doctype::class);
        $doctype->expects(self::never())
            ->method('__invoke');
       $doctype->expects(self::never())
           ->method('isXhtml');
        $doctype->expects(self::exactly(5))
            ->method('isHtml5')
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
        $name                         = 'test-name';
        $nameEscaped                  = 'test-name-escaped';
        $id                           = 'test-id';
        $idEscaped                    = 'test-id-escaped';
        $value1                       = 'xyz';
        $value1Escaped                = 'xyz-escaped';
        $label1                       = 'def1';
        $label1Translated             = 'def1-translated';
        $label1TranslatedEscaped      = 'def1-translated-escaped';
        $value2                       = 'def';
        $value2Escaped                = 'def-escaped';
        $label2                       = '...2';
        $label2Translated             = '...2-translated';
        $label2TranslatedEscaped      = '...2-translated-escaped';
        $label3                       = 'group3';
        $label3Translated             = 'group3-translated';
        $label3TranslatedEscaped      = 'group3-translated-escaped';
        $value4                       = '2';
        $value4Escaped                = '2-escaped';
        $label4                       = 'Choose...4';
        $label4Translated             = 'Choose...4-translated';
        $label4TranslatedEscaped      = 'Choose...4-translated-escaped';
        $value5                       = '3';
        $value5Escaped                = '3-escaped';
        $label5                       = '...5';
        $label5Translated             = '...5-translated';
        $label5TranslatedEscaped      = '...5-translated-escaped';
        $label6                       = 'group6';
        $label6Translated             = 'group6-translated';
        $label6TranslatedEscaped      = 'group6-translated-escaped';
        $value7                       = '4';
        $value7Escaped                = '4-escaped';
        $label7                       = 'Choose...7';
        $label7Translated             = 'Choose...7-translated';
        $label7TranslatedEscaped      = 'Choose...7-translated-escaped';
        $value8                       = '5';
        $value8Escaped                = '5-escaped';
        $label8                       = '...8';
        $label8Translated             = '...8-translated';
        $class                        = 'test-class';
        $classEscaped                 = 'test-class-escaped';
        $ariaLabel                    = 'test';
        $ariaLabelTranslated          = 'test-translated';
        $ariaLabelTranslatedEscaped   = 'test-translated-escaped';
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
            '<select classEscaped="%s" aria-labelEscaped="%s" idEscaped="%s" multipleEscaped nameEscaped="%s">',
            $classEscaped,
            $ariaLabelTranslatedEscaped,
            $idEscaped,
            $nameEscaped,
        ) . PHP_EOL
            . sprintf(
                '    <option valueEscaped="">%s</option>',
                $emptyOptionTranslatedEscaped,
            ) . PHP_EOL
            . sprintf(
                '    <option valueEscaped="%s" selectedEscaped>%s</option>',
                $value1Escaped,
                $label1TranslatedEscaped,
            ) . PHP_EOL
            . sprintf(
                '    <option valueEscaped="%s">%s</option>',
                $value2Escaped,
                $label2TranslatedEscaped,
            ) . PHP_EOL
            . sprintf('    <optgroup labelEscaped="%s">', $label3TranslatedEscaped) . PHP_EOL
            . sprintf(
                '        <option valueEscaped="%s">%s</option>',
                $value4Escaped,
                $label4TranslatedEscaped,
            ) . PHP_EOL
            . sprintf(
                '        <option valueEscaped="%s" selectedEscaped>%s</option>',
                $value5Escaped,
                $label5TranslatedEscaped,
            ) . PHP_EOL
            . sprintf('        <optgroup labelEscaped="%s">', $label6TranslatedEscaped) . PHP_EOL
            . sprintf(
                '            <option valueEscaped="%s">%s</option>',
                $value7Escaped,
                $label7TranslatedEscaped,
            ) . PHP_EOL
            . sprintf(
                '            <option valueEscaped="%s" disabledEscaped>%s</option>',
                $value8Escaped,
                $label8Translated,
            ) . PHP_EOL
            . '        </optgroup>' . PHP_EOL
            . '    </optgroup>' . PHP_EOL
            . '</select>';
        $textDomain = 'test-domain';

        $escapeHtml = $this->createMock(EscapeHtml::class);
        $matcher    = self::exactly(23);
        $escapeHtml->expects($matcher)
            ->method('__invoke')
            ->willReturnCallback(
                static function (string $value, int $recurse = AbstractHelper::RECURSE_NONE) use ($matcher, $emptyOptionTranslated, $emptyOptionTranslatedEscaped, $label1Translated, $label1TranslatedEscaped, $label2Translated, $label2TranslatedEscaped, $label4Translated, $label4TranslatedEscaped, $label5Translated, $label5TranslatedEscaped, $label7Translated, $label7TranslatedEscaped): string {
                    $invocation = $matcher->numberOfInvocations();

                    match ($invocation) {
                        1 => self::assertSame(
                            $emptyOptionTranslated,
                            $value,
                            (string) $invocation,
                        ),
                        2, 4, 7, 10, 12, 16, 17 => self::assertSame(
                            'value',
                            $value,
                            (string) $invocation,
                        ),
                        3 => self::assertSame(
                            $label1Translated,
                            $value,
                            (string) $invocation,
                        ),
                        5, 13 => self::assertSame(
                            'selected',
                            $value,
                            (string) $invocation,
                        ),
                        6 => self::assertSame(
                            $label2Translated,
                            $value,
                            (string) $invocation,
                        ),
                        8, 14 => self::assertSame(
                            'label',
                            $value,
                            (string) $invocation,
                        ),
                        9 => self::assertSame(
                            $label4Translated,
                            $value,
                            (string) $invocation,
                        ),
                        11 => self::assertSame(
                            $label5Translated,
                            $value,
                            (string) $invocation,
                        ),
                        15 => self::assertSame(
                            $label7Translated,
                            $value,
                            (string) $invocation,
                        ),
                        18 => self::assertSame(
                            'disabled',
                            $value,
                            (string) $invocation,
                        ),
                        19 => self::assertSame(
                            'class',
                            $value,
                            (string) $invocation,
                        ),
                        20 => self::assertSame(
                            'aria-label',
                            $value,
                            (string) $invocation,
                        ),
                        21 => self::assertSame(
                            'id',
                            $value,
                            (string) $invocation,
                        ),
                        22 => self::assertSame(
                            'multiple',
                            $value,
                            (string) $invocation,
                        ),
                        23 => self::assertSame(
                            'name',
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
                        1 => $emptyOptionTranslatedEscaped,
                        2, 4, 7, 10, 12, 16, 17 => 'valueEscaped',
                        3 => $label1TranslatedEscaped,
                        5, 13 => 'selectedEscaped',
                        6 => $label2TranslatedEscaped,
                        8, 14 => 'labelEscaped',
                        9 => $label4TranslatedEscaped,
                        11 => $label5TranslatedEscaped,
                        15 => $label7TranslatedEscaped,
                        18 => 'disabledEscaped',
                        19 => 'classEscaped',
                        20 => 'aria-labelEscaped',
                        21 => 'idEscaped',
                        22 => 'multipleEscaped',
                        23 => 'nameEscaped',
                        default => '',
                    };
                },
            );

        $escapeHtmlAttr = $this->createMock(EscapeHtmlAttr::class);
        $matcher        = self::exactly(13);
        $escapeHtmlAttr->expects($matcher)
            ->method('__invoke')
            ->willReturnCallback(
                static function (string $valueParam, int $recurse = AbstractHelper::RECURSE_NONE) use ($matcher, $class, $classEscaped, $value1, $value1Escaped, $value2, $value2Escaped, $label3Translated, $label3TranslatedEscaped, $value4, $value4Escaped, $value5, $value5Escaped, $label6Translated, $label6TranslatedEscaped, $value7, $value7Escaped, $value8, $value8Escaped, $ariaLabelTranslated, $ariaLabelTranslatedEscaped, $id, $idEscaped, $name, $nameEscaped): string {
                    $invocation = $matcher->numberOfInvocations();

                    match ($invocation) {
                        1 => self::assertSame(
                            '',
                            $valueParam,
                            (string) $invocation,
                        ),
                        2 => self::assertSame(
                            $value1,
                            $valueParam,
                            (string) $invocation,
                        ),
                        3 => self::assertSame(
                            $value2,
                            $valueParam,
                            (string) $invocation,
                        ),
                        4 => self::assertSame(
                            $label3Translated,
                            $valueParam,
                            (string) $invocation,
                        ),
                        5 => self::assertSame(
                            $value4,
                            $valueParam,
                            (string) $invocation,
                        ),
                        6 => self::assertSame(
                            $value5,
                            $valueParam,
                            (string) $invocation,
                        ),
                        7 => self::assertSame(
                            $label6Translated,
                            $valueParam,
                            (string) $invocation,
                        ),
                        8 => self::assertSame(
                            $value7,
                            $valueParam,
                            (string) $invocation,
                        ),
                        9 => self::assertSame(
                            $value8,
                            $valueParam,
                            (string) $invocation,
                        ),
                        10 => self::assertSame(
                            'form-select ' . $class,
                            $valueParam,
                            (string) $invocation,
                        ),
                        11 => self::assertSame(
                            $ariaLabelTranslated,
                            $valueParam,
                            (string) $invocation,
                        ),
                        12 => self::assertSame(
                            $id,
                            $valueParam,
                            (string) $invocation,
                        ),
                        13 => self::assertSame(
                            $name . '[]',
                            $valueParam,
                            (string) $invocation,
                        ),
                        default => self::assertSame(
                            'x',
                            $valueParam,
                            (string) $invocation,
                        ),
                    };

                    self::assertSame(AbstractHelper::RECURSE_NONE, $recurse, (string) $invocation);

                    return match ($invocation) {
                        2 => $value1Escaped,
                        3 => $value2Escaped,
                        4 => $label3TranslatedEscaped,
                        5 => $value4Escaped,
                        6 => $value5Escaped,
                        7 => $label6TranslatedEscaped,
                        8 => $value7Escaped,
                        9 => $value8Escaped,
                        10 => $classEscaped,
                        11 => $ariaLabelTranslatedEscaped,
                        12 => $idEscaped,
                        13 => $nameEscaped,
                        default => '',
                    };
                },
            );

        $doctype = $this->createMock(Doctype::class);
        $doctype->expects(self::never())
            ->method('__invoke');
        $doctype->expects(self::exactly(4))
            ->method('isXhtml')
            ->willReturn(false);
        $doctype->expects(self::exactly(4))
            ->method('isHtml5')
            ->willReturn(true);

        $formHidden = $this->createMock(FormHiddenInterface::class);
        $formHidden->expects(self::never())
            ->method('render');

        $translator = $this->createMock(Translator::class);
        $matcher    = self::exactly(10);
        $translator->expects($matcher)
            ->method('translate')
            ->willReturnCallback(
                static function (string $message, string $textDomainParam = 'default', string | null $locale = null) use ($matcher, $textDomain, $emptyOption, $emptyOptionTranslated, $label1, $label1Translated, $label2, $label2Translated, $label3, $label3Translated, $label4, $label4Translated, $label5, $label5Translated, $label6, $label6Translated, $label7, $label7Translated, $label8, $label8Translated, $ariaLabel, $ariaLabelTranslated): string {
                    $invocation = $matcher->numberOfInvocations();

                    match ($invocation) {
                        1 => self::assertSame($emptyOption, $message, (string) $invocation),
                        2 => self::assertSame($label1, $message, (string) $invocation),
                        3 => self::assertSame($label2, $message, (string) $invocation),
                        4 => self::assertSame($label3, $message, (string) $invocation),
                        5 => self::assertSame($label4, $message, (string) $invocation),
                        6 => self::assertSame($label5, $message, (string) $invocation),
                        7 => self::assertSame($label6, $message, (string) $invocation),
                        8 => self::assertSame($label7, $message, (string) $invocation),
                        9 => self::assertSame($label8, $message, (string) $invocation),
                        10 => self::assertSame($ariaLabel, $message, (string) $invocation),
                        default => self::assertSame('', $message, (string) $invocation),
                    };

                    self::assertSame($textDomain, $textDomainParam, (string) $invocation);
                    self::assertNull($locale, (string) $invocation);

                    return match ($invocation) {
                        1 => $emptyOptionTranslated,
                        2 => $label1Translated,
                        3 => $label2Translated,
                        4 => $label3Translated,
                        5 => $label4Translated,
                        6 => $label5Translated,
                        7 => $label6Translated,
                        8 => $label7Translated,
                        9 => $label8Translated,
                        10 => $ariaLabelTranslated,
                        default => '',
                    };
                },
            );

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
        $this->helper->setTranslator($translator);

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
        $nameEscaped        = 'test-name-escaped';
        $id                 = 'test-id';
        $idEscaped          = 'test-id-escaped';
        $value1             = 'xyz';
        $value2             = 'def';
        $value2Escaped      = 'def-escaped';
        $value3             = 'abc';
        $value3Escaped      = 'abc-escaped';
        $class              = 'test-class';
        $classEscaped       = 'test-class-escaped';
        $ariaLabel          = 'test';
        $ariaLabelEscaped   = 'test-escaped';
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
                '<select classEscaped="%s" aria-labelEscaped="%s" idEscaped="%s" multipleEscaped nameEscaped="%s">',
                $classEscaped,
                $ariaLabelEscaped,
                $idEscaped,
                $nameEscaped,
            ) . PHP_EOL
            . sprintf('    <option valueEscaped="">%s</option>', $emptyOptionEscaped) . PHP_EOL
            . sprintf(
                '    <option valueEscaped="%s" selectedEscaped>%s</option>',
                $value3Escaped,
                $value2Escaped,
            ) . PHP_EOL
            . '</select>';

        $escapeHtml = $this->createMock(EscapeHtml::class);
        $matcher    = self::exactly(10);
        $escapeHtml->expects($matcher)
            ->method('__invoke')
            ->willReturnCallback(
                static function (string $value, int $recurse = AbstractHelper::RECURSE_NONE) use ($matcher, $emptyOption, $emptyOptionEscaped, $value2, $value2Escaped): string {
                    $invocation = $matcher->numberOfInvocations();

                    match ($invocation) {
                        1 => self::assertSame(
                            $emptyOption,
                            $value,
                            (string) $invocation,
                        ),
                        2, 4 => self::assertSame(
                            'value',
                            $value,
                            (string) $invocation,
                        ),
                        3 => self::assertSame(
                            $value2,
                            $value,
                            (string) $invocation,
                        ),
                        5 => self::assertSame(
                            'selected',
                            $value,
                            (string) $invocation,
                        ),
                        6 => self::assertSame(
                            'class',
                            $value,
                            (string) $invocation,
                        ),
                        7 => self::assertSame(
                            'aria-label',
                            $value,
                            (string) $invocation,
                        ),
                        8 => self::assertSame(
                            'id',
                            $value,
                            (string) $invocation,
                        ),
                        9 => self::assertSame(
                            'multiple',
                            $value,
                            (string) $invocation,
                        ),
                        10 => self::assertSame(
                            'name',
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
                        1 => $emptyOptionEscaped,
                        2, 4 => 'valueEscaped',
                        3 => $value2Escaped,
                        5 => 'selectedEscaped',
                        6 => 'classEscaped',
                        7 => 'aria-labelEscaped',
                        8 => 'idEscaped',
                        9 => 'multipleEscaped',
                        10 => 'nameEscaped',
                        default => '',
                    };
                },
            );

        $escapeHtmlAttr = $this->createMock(EscapeHtmlAttr::class);
        $matcher        = self::exactly(6);
        $escapeHtmlAttr->expects($matcher)
            ->method('__invoke')
            ->willReturnCallback(
                static function (string $valueParam, int $recurse = AbstractHelper::RECURSE_NONE) use ($matcher, $class, $classEscaped, $value3, $value3Escaped, $ariaLabel, $ariaLabelEscaped, $id, $idEscaped, $name, $nameEscaped): string {
                    $invocation = $matcher->numberOfInvocations();

                    match ($invocation) {
                        1 => self::assertSame(
                            '',
                            $valueParam,
                            (string) $invocation,
                        ),
                        2 => self::assertSame(
                            $value3,
                            $valueParam,
                            (string) $invocation,
                        ),
                        3 => self::assertSame(
                            'form-select ' . $class,
                            $valueParam,
                            (string) $invocation,
                        ),
                        4 => self::assertSame(
                            $ariaLabel,
                            $valueParam,
                            (string) $invocation,
                        ),
                        5 => self::assertSame(
                            $id,
                            $valueParam,
                            (string) $invocation,
                        ),
                        6 => self::assertSame(
                            $name . '[]',
                            $valueParam,
                            (string) $invocation,
                        ),
                        default => self::assertSame(
                            'x',
                            $valueParam,
                            (string) $invocation,
                        ),
                    };

                    self::assertSame(AbstractHelper::RECURSE_NONE, $recurse, (string) $invocation);

                    return match ($invocation) {
                        2 => $value3Escaped,
                        3 => $classEscaped,
                        4 => $ariaLabelEscaped,
                        5 => $idEscaped,
                        6 => $nameEscaped,
                        default => '',
                    };
                },
            );

        $doctype = $this->createMock(Doctype::class);
        $doctype->expects(self::never())
            ->method('__invoke');
        $doctype->expects(self::exactly(2))
            ->method('isXhtml')
            ->willReturn(false);
        $doctype->expects(self::exactly(2))
            ->method('isHtml5')
            ->willReturn(true);

        $formHidden = $this->createMock(FormHiddenInterface::class);
        $formHidden->expects(self::once())
            ->method('render')
            ->willReturnCallback(
                static function (ElementInterface $element) use ($name, $unselectedValue): string {
                    self::assertInstanceOf(Hidden::class, $element);

                    assert($element instanceof Hidden);

                    self::assertSame($name, $element->getName());
                    self::assertSame($unselectedValue, $element->getValue());

                    return sprintf(
                        '<input type="hidden" name="%s" value="%s"/>',
                        $name,
                        $unselectedValue,
                    );
                },
            );

        $renderer = $this->createMock(PhpRenderer::class);
        $renderer->expects(self::never())
            ->method('getHelperPluginManager');
        $matcher = self::exactly(4);
        $renderer->expects($matcher)
            ->method('plugin')
            ->willReturnCallback(
                static function (string $name, array | null $options = null) use ($matcher, $escapeHtml, $escapeHtmlAttr, $doctype, $formHidden): HelperInterface | null {
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
                        4 => self::assertSame(
                            'form_hidden',
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
                        4 => $formHidden,
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
