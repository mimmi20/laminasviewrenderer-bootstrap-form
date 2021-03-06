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

use Laminas\Form\Element\Text;
use Laminas\Form\Exception\InvalidArgumentException;
use Laminas\I18n\View\Helper\Translate;
use Laminas\View\Helper\EscapeHtml;
use Mimmi20\Form\Element\Links\LinksInterface as LinksElement;
use Mimmi20\LaminasView\BootstrapForm\FormLinks;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;

use function assert;
use function sprintf;

use const PHP_EOL;

final class FormLinksTest extends TestCase
{
    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testRenderWithWrongElement(): void
    {
        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::never())
            ->method('__invoke');

        $helper = new FormLinks($escapeHtml, null);

        $element = $this->getMockBuilder(Text::class)
            ->disableOriginalConstructor()
            ->getMock();
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
                'Mimmi20\LaminasView\BootstrapForm\FormLinks::render',
                LinksElement::class
            )
        );
        $this->expectExceptionCode(0);

        $helper->render($element);
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testRenderEmptyLinkList(): void
    {
        $expected = '';

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::never())
            ->method('__invoke');

        $helper = new FormLinks($escapeHtml, null);

        $element = $this->getMockBuilder(LinksElement::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::never())
            ->method('getAttributes');
        $element->expects(self::never())
            ->method('getValue');
        $element->expects(self::never())
            ->method('getOption');
        $element->expects(self::once())
            ->method('getLinks')
            ->willReturn([]);
        $element->expects(self::once())
            ->method('getSeparator')
            ->willReturn('');

        self::assertSame($expected, $helper->render($element));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testRenderSingleLink(): void
    {
        $class        = 'test-class';
        $ariaLabel    = 'test';
        $attributes   = ['class' => $class, 'aria-label' => $ariaLabel];
        $label        = 'test-label';
        $labelEscaped = 'test-label-escaped';
        $linkClass    = 'abc';
        $seperator    = '';

        $expected = sprintf('<a aria-label="%s" href="&#x23;" class="%s&#x20;%s">%s</a>', $ariaLabel, $class, $linkClass, $labelEscaped);

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::once())
            ->method('__invoke')
            ->with($label)
            ->willReturn($labelEscaped);

        $helper = new FormLinks($escapeHtml, null);

        $element = $this->getMockBuilder(LinksElement::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::once())
            ->method('getAttributes')
            ->willReturn($attributes);
        $element->expects(self::never())
            ->method('getValue');
        $element->expects(self::never())
            ->method('getOption');
        $element->expects(self::once())
            ->method('getLinks')
            ->willReturn(
                [
                    [
                        'label' => $label,
                        'class' => $linkClass,
                        'href' => '#',
                    ],
                ]
            );
        $element->expects(self::once())
            ->method('getSeparator')
            ->willReturn($seperator);

        self::assertSame($expected, $helper->render($element));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testRenderDoubleLink(): void
    {
        $class         = 'test-class';
        $ariaLabel     = 'test';
        $attributes    = ['class' => $class, 'aria-label' => $ariaLabel];
        $label1        = 'test-label1';
        $label1Escaped = 'test-label1-escaped';
        $linkClass1    = 'abc';
        $label2        = 'test-label2';
        $label2Escaped = 'test-label2-escaped';
        $linkClass2    = 'xyz';
        $seperator     = '||';

        $expected = sprintf('<a aria-label="%s" href="&#x23;1" class="%s&#x20;%s">%s</a>', $ariaLabel, $class, $linkClass1, $label1Escaped) . PHP_EOL .
            $seperator . PHP_EOL .
            sprintf('<a aria-label="%s" href="&#x23;2" class="%s&#x20;%s">%s</a>', $ariaLabel, $class, $linkClass2, $label2Escaped);

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::exactly(2))
            ->method('__invoke')
            ->withConsecutive([$label1], [$label2])
            ->willReturnOnConsecutiveCalls($label1Escaped, $label2Escaped);

        $helper = new FormLinks($escapeHtml, null);

        $element = $this->getMockBuilder(LinksElement::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::exactly(2))
            ->method('getAttributes')
            ->willReturn($attributes);
        $element->expects(self::never())
            ->method('getValue');
        $element->expects(self::never())
            ->method('getOption');
        $element->expects(self::once())
            ->method('getLinks')
            ->willReturn(
                [
                    [
                        'label' => $label1,
                        'class' => $linkClass1,
                        'href' => '#1',
                    ],
                    [
                        'label' => $label2,
                        'class' => $linkClass2,
                        'href' => '#2',
                    ],
                ]
            );
        $element->expects(self::once())
            ->method('getSeparator')
            ->willReturn($seperator);

        self::assertSame($expected, $helper->render($element));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testRenderDoubleLinkWithIndent(): void
    {
        $class         = 'test-class';
        $ariaLabel     = 'test';
        $attributes    = ['class' => $class, 'aria-label' => $ariaLabel];
        $label1        = 'test-label1';
        $label1Escaped = 'test-label1-escaped';
        $linkClass1    = 'abc';
        $label2        = 'test-label2';
        $label2Escaped = 'test-label2-escaped';
        $linkClass2    = 'xyz';
        $seperator     = '||';
        $indent        = '    ';

        $expected = $indent . sprintf('<a aria-label="%s" href="&#x23;1" class="%s&#x20;%s">%s</a>', $ariaLabel, $class, $linkClass1, $label1Escaped) . PHP_EOL .
            $indent . $seperator . PHP_EOL .
            $indent . sprintf('<a aria-label="%s" href="&#x23;2" class="%s&#x20;%s">%s</a>', $ariaLabel, $class, $linkClass2, $label2Escaped);

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::exactly(2))
            ->method('__invoke')
            ->withConsecutive([$label1], [$label2])
            ->willReturnOnConsecutiveCalls($label1Escaped, $label2Escaped);

        $helper = new FormLinks($escapeHtml, null);

        $element = $this->getMockBuilder(LinksElement::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::exactly(2))
            ->method('getAttributes')
            ->willReturn($attributes);
        $element->expects(self::never())
            ->method('getValue');
        $element->expects(self::never())
            ->method('getOption');
        $element->expects(self::once())
            ->method('getLinks')
            ->willReturn(
                [
                    [
                        'label' => $label1,
                        'class' => $linkClass1,
                        'href' => '#1',
                    ],
                    [
                        'label' => $label2,
                        'class' => $linkClass2,
                        'href' => '#2',
                    ],
                ]
            );
        $element->expects(self::once())
            ->method('getSeparator')
            ->willReturn($seperator);

        $helper->setIndent($indent);

        self::assertSame($expected, $helper->render($element));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testRenderDoubleLinkWithoutLabel(): void
    {
        $class         = 'test-class';
        $ariaLabel     = 'test';
        $attributes    = ['class' => $class, 'aria-label' => $ariaLabel];
        $label1        = '';
        $linkClass1    = 'abc';
        $label2        = 'test-label2';
        $label2Escaped = 'test-label2-escaped';
        $linkClass2    = 'xyz';
        $seperator     = '||';

        $expected = sprintf('<a aria-label="%s" href="&#x23;1" class="%s&#x20;%s">%s</a>', $ariaLabel, $class, $linkClass1, $label1) . PHP_EOL .
            $seperator . PHP_EOL .
            sprintf('<a aria-label="%s" href="&#x23;2" class="%s&#x20;%s">%s</a>', $ariaLabel, $class, $linkClass2, $label2Escaped);

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::once())
            ->method('__invoke')
            ->with($label2)
            ->willReturn($label2Escaped);

        $helper = new FormLinks($escapeHtml, null);

        $element = $this->getMockBuilder(LinksElement::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::exactly(2))
            ->method('getAttributes')
            ->willReturn($attributes);
        $element->expects(self::never())
            ->method('getValue');
        $element->expects(self::never())
            ->method('getOption');
        $element->expects(self::once())
            ->method('getLinks')
            ->willReturn(
                [
                    [
                        'label' => $label1,
                        'class' => $linkClass1,
                        'href' => '#1',
                    ],
                    [
                        'label' => $label2,
                        'class' => $linkClass2,
                        'href' => '#2',
                    ],
                ]
            );
        $element->expects(self::once())
            ->method('getSeparator')
            ->willReturn($seperator);

        self::assertSame($expected, $helper->render($element));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testRenderDoubleLinkWithTranslator(): void
    {
        $class                  = 'test-class';
        $ariaLabel              = 'test';
        $attributes             = ['class' => $class, 'aria-label' => $ariaLabel];
        $label1                 = 'test-label1';
        $label1Tranlated        = 'test-label1-translated';
        $label1TranlatedEscaped = 'test-label1-translated-escaped';
        $linkClass1             = 'abc';
        $label2                 = 'test-label2';
        $label2Tranlated        = 'test-label2-translated';
        $label2TranlatedEscaped = 'test-label2-translated-escaped';
        $linkClass2             = 'xyz';
        $seperator              = '||';
        $textDomain             = 'test-domain';

        $expected = sprintf('<a aria-label="%s" href="&#x23;1" class="%s&#x20;%s">%s</a>', $ariaLabel, $class, $linkClass1, $label1TranlatedEscaped) . PHP_EOL .
            $seperator . PHP_EOL .
            sprintf('<a aria-label="%s" href="&#x23;2" class="%s&#x20;%s">%s</a>', $ariaLabel, $class, $linkClass2, $label2TranlatedEscaped);

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::exactly(2))
            ->method('__invoke')
            ->withConsecutive([$label1Tranlated], [$label2Tranlated])
            ->willReturnOnConsecutiveCalls($label1TranlatedEscaped, $label2TranlatedEscaped);

        $translator = $this->getMockBuilder(Translate::class)
            ->disableOriginalConstructor()
            ->getMock();
        $translator->expects(self::exactly(2))
            ->method('__invoke')
            ->withConsecutive([$label1, $textDomain], [$label2, $textDomain])
            ->willReturnOnConsecutiveCalls($label1Tranlated, $label2Tranlated);

        $helper = new FormLinks($escapeHtml, $translator);

        $element = $this->getMockBuilder(LinksElement::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::exactly(2))
            ->method('getAttributes')
            ->willReturn($attributes);
        $element->expects(self::never())
            ->method('getValue');
        $element->expects(self::never())
            ->method('getOption');
        $element->expects(self::once())
            ->method('getLinks')
            ->willReturn(
                [
                    [
                        'label' => $label1,
                        'class' => $linkClass1,
                        'href' => '#1',
                    ],
                    [
                        'label' => $label2,
                        'class' => $linkClass2,
                        'href' => '#2',
                    ],
                ]
            );
        $element->expects(self::once())
            ->method('getSeparator')
            ->willReturn($seperator);

        $helper->setTranslatorTextDomain($textDomain);

        self::assertSame($expected, $helper->render($element));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testRenderDoubleLinkWithTranslatorButWithoutLabel(): void
    {
        $class                  = 'test-class';
        $ariaLabel              = 'test';
        $attributes             = ['class' => $class, 'aria-label' => $ariaLabel];
        $label1                 = '';
        $linkClass1             = 'abc';
        $label2                 = 'test-label2';
        $label2Tranlated        = 'test-label2-translated';
        $label2TranlatedEscaped = 'test-label2-translated-escaped';
        $linkClass2             = 'xyz';
        $seperator              = '||';
        $textDomain             = 'test-domain';

        $expected = sprintf('<a aria-label="%s" href="&#x23;1" class="%s&#x20;%s">%s</a>', $ariaLabel, $class, $linkClass1, $label1) . PHP_EOL .
            $seperator . PHP_EOL .
            sprintf('<a aria-label="%s" href="&#x23;2" class="%s&#x20;%s">%s</a>', $ariaLabel, $class, $linkClass2, $label2TranlatedEscaped);

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::once())
            ->method('__invoke')
            ->with($label2Tranlated)
            ->willReturn($label2TranlatedEscaped);

        $translator = $this->getMockBuilder(Translate::class)
            ->disableOriginalConstructor()
            ->getMock();
        $translator->expects(self::once())
            ->method('__invoke')
            ->with($label2, $textDomain)
            ->willReturn($label2Tranlated);

        $helper = new FormLinks($escapeHtml, $translator);

        $element = $this->getMockBuilder(LinksElement::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::exactly(2))
            ->method('getAttributes')
            ->willReturn($attributes);
        $element->expects(self::never())
            ->method('getValue');
        $element->expects(self::never())
            ->method('getOption');
        $element->expects(self::once())
            ->method('getLinks')
            ->willReturn(
                [
                    [
                        'label' => $label1,
                        'class' => $linkClass1,
                        'href' => '#1',
                    ],
                    [
                        'label' => $label2,
                        'class' => $linkClass2,
                        'href' => '#2',
                    ],
                ]
            );
        $element->expects(self::once())
            ->method('getSeparator')
            ->willReturn($seperator);

        $helper->setTranslatorTextDomain($textDomain);

        self::assertSame($expected, $helper->render($element));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testInvokeDoubleLink1(): void
    {
        $class         = 'test-class';
        $ariaLabel     = 'test';
        $attributes    = ['class' => $class, 'aria-label' => $ariaLabel];
        $label1        = 'test-label1';
        $label1Escaped = 'test-label1-escaped';
        $linkClass1    = 'abc';
        $label2        = 'test-label2';
        $label2Escaped = 'test-label2-escaped';
        $linkClass2    = 'xyz';
        $seperator     = '||';

        $expected = sprintf('<a aria-label="%s" href="&#x23;1" class="%s&#x20;%s">%s</a>', $ariaLabel, $class, $linkClass1, $label1Escaped) . PHP_EOL .
            $seperator . PHP_EOL .
            sprintf('<a aria-label="%s" href="&#x23;2" class="%s&#x20;%s">%s</a>', $ariaLabel, $class, $linkClass2, $label2Escaped);

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::exactly(2))
            ->method('__invoke')
            ->withConsecutive([$label1], [$label2])
            ->willReturnOnConsecutiveCalls($label1Escaped, $label2Escaped);

        $helper = new FormLinks($escapeHtml, null);

        $element = $this->getMockBuilder(LinksElement::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::exactly(2))
            ->method('getAttributes')
            ->willReturn($attributes);
        $element->expects(self::never())
            ->method('getValue');
        $element->expects(self::never())
            ->method('getOption');
        $element->expects(self::once())
            ->method('getLinks')
            ->willReturn(
                [
                    [
                        'label' => $label1,
                        'class' => $linkClass1,
                        'href' => '#1',
                    ],
                    [
                        'label' => $label2,
                        'class' => $linkClass2,
                        'href' => '#2',
                    ],
                ]
            );
        $element->expects(self::once())
            ->method('getSeparator')
            ->willReturn($seperator);

        $helperObject = $helper();

        assert($helperObject instanceof FormLinks);

        self::assertSame($expected, $helperObject->render($element));
    }

    /**
     * @throws Exception
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testInvokeDoubleLink2(): void
    {
        $class         = 'test-class';
        $ariaLabel     = 'test';
        $attributes    = ['class' => $class, 'aria-label' => $ariaLabel];
        $label1        = 'test-label1';
        $label1Escaped = 'test-label1-escaped';
        $linkClass1    = 'abc';
        $label2        = 'test-label2';
        $label2Escaped = 'test-label2-escaped';
        $linkClass2    = 'xyz';
        $seperator     = '||';

        $expected = sprintf('<a aria-label="%s" href="&#x23;1" class="%s&#x20;%s">%s</a>', $ariaLabel, $class, $linkClass1, $label1Escaped) . PHP_EOL .
            $seperator . PHP_EOL .
            sprintf('<a aria-label="%s" href="&#x23;2" class="%s&#x20;%s">%s</a>', $ariaLabel, $class, $linkClass2, $label2Escaped);

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::exactly(2))
            ->method('__invoke')
            ->withConsecutive([$label1], [$label2])
            ->willReturnOnConsecutiveCalls($label1Escaped, $label2Escaped);

        $helper = new FormLinks($escapeHtml, null);

        $element = $this->getMockBuilder(LinksElement::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::exactly(2))
            ->method('getAttributes')
            ->willReturn($attributes);
        $element->expects(self::never())
            ->method('getValue');
        $element->expects(self::never())
            ->method('getOption');
        $element->expects(self::once())
            ->method('getLinks')
            ->willReturn(
                [
                    [
                        'label' => $label1,
                        'class' => $linkClass1,
                        'href' => '#1',
                    ],
                    [
                        'label' => $label2,
                        'class' => $linkClass2,
                        'href' => '#2',
                    ],
                ]
            );
        $element->expects(self::once())
            ->method('getSeparator')
            ->willReturn($seperator);

        self::assertSame($expected, $helper($element));
    }

    /**
     * @throws Exception
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testSetGetIndent1(): void
    {
        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::never())
            ->method('__invoke');

        $helper = new FormLinks($escapeHtml, null);

        self::assertSame($helper, $helper->setIndent(4));
        self::assertSame('    ', $helper->getIndent());
    }

    /**
     * @throws Exception
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testSetGetIndent2(): void
    {
        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::never())
            ->method('__invoke');

        $helper = new FormLinks($escapeHtml, null);

        self::assertSame($helper, $helper->setIndent('  '));
        self::assertSame('  ', $helper->getIndent());
    }
}
