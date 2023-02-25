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

use Laminas\Form\Element\Text;
use Laminas\I18n\View\Helper\Translate;
use Laminas\View\Helper\EscapeHtml;
use Mimmi20\LaminasView\BootstrapForm\FormElementErrors;
use Mimmi20\LaminasView\Helper\HtmlElement\Helper\HtmlElementInterface;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;

use function assert;
use function sprintf;

use const PHP_EOL;

final class FormElementErrorsTest extends TestCase
{
    /** @throws Exception */
    public function testRenderWithoutMessages(): void
    {
        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::never())
            ->method('__invoke');

        $htmlElement = $this->getMockBuilder(HtmlElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $htmlElement->expects(self::never())
            ->method('toHtml');

        $helper = new FormElementErrors($htmlElement, $escapeHtml, null);

        $element = $this->getMockBuilder(Text::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::once())
            ->method('getMessages')
            ->willReturn([]);
        $element->expects(self::never())
            ->method('getAttribute');
        $element->expects(self::never())
            ->method('hasAttribute');
        $element->expects(self::never())
            ->method('getLabelOption');

        self::assertSame('', $helper->render($element));
    }

    /** @throws Exception */
    public function testRenderWithMessages(): void
    {
        $message          = 'too long';
        $messageEscaped   = 'too long, but escaped';
        $listEntryMessage = sprintf('<li>%s</li>', $messageEscaped);
        $listMessage      = sprintf('<ul>%s</ul>', $listEntryMessage);
        $divMessage       = sprintf('<div>%s</div>', $listMessage);

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::once())
            ->method('__invoke')
            ->with($message)
            ->willReturn($messageEscaped);

        $htmlElement = $this->getMockBuilder(HtmlElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $htmlElement->expects(self::exactly(3))
            ->method('toHtml')
            ->willReturnMap(
                [
                    ['li', [], $messageEscaped, $listEntryMessage],
                    ['ul', [], '        ' . $listEntryMessage . PHP_EOL . '    ', $listMessage],
                    ['div', ['class' => 'invalid-feedback'], '    ' . $listMessage, $divMessage],
                ],
            );

        $helper = new FormElementErrors($htmlElement, $escapeHtml, null);

        $element = $this->getMockBuilder(Text::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::once())
            ->method('getMessages')
            ->willReturn(['x1' => $message, 'x2' => '']);
        $element->expects(self::never())
            ->method('getAttribute');
        $element->expects(self::once())
            ->method('hasAttribute')
            ->with('id')
            ->willReturn(false);
        $element->expects(self::once())
            ->method('getLabelOption')
            ->with('disable_html_escape')
            ->willReturn(false);

        self::assertSame($divMessage, $helper->render($element));
    }

    /** @throws Exception */
    public function testRenderWithEmptyMessages(): void
    {
        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::never())
            ->method('__invoke');

        $htmlElement = $this->getMockBuilder(HtmlElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $htmlElement->expects(self::never())
            ->method('toHtml');

        $helper = new FormElementErrors($htmlElement, $escapeHtml, null);

        $element = $this->getMockBuilder(Text::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::once())
            ->method('getMessages')
            ->willReturn(['', '']);
        $element->expects(self::never())
            ->method('getAttribute');
        $element->expects(self::never())
            ->method('hasAttribute');
        $element->expects(self::never())
            ->method('getLabelOption');

        self::assertSame('', $helper->render($element));
    }

    /** @throws Exception */
    public function testRenderWithMessagesAndTranslator(): void
    {
        $message1                  = 'too long';
        $message1Translated        = 'too long, but translated';
        $message1TranslatedEscaped = 'too long, but translated and escaped';
        $message2                  = 'too short';
        $message2Translated        = 'too short, but translated';
        $message2TranslatedEscaped = 'too short, but translated and escaped';
        $listEntryMessage1         = sprintf('<li>%s</li>', $message1TranslatedEscaped);
        $listEntryMessage2         = sprintf('<li>%s</li>', $message2TranslatedEscaped);
        $listMessage               = sprintf('<ul>%s%s</ul>', $listEntryMessage1, $listEntryMessage2);
        $divMessage                = sprintf('<div>%s</div>', $listMessage);
        $textDomain                = 'test-domain';

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::exactly(2))
            ->method('__invoke')
            ->willReturnMap(
                [
                    [$message1Translated, 0, $message1TranslatedEscaped],
                    [$message2Translated, 0, $message2TranslatedEscaped],
                ],
            );

        $htmlElement = $this->getMockBuilder(HtmlElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $htmlElement->expects(self::exactly(4))
            ->method('toHtml')
            ->willReturnMap(
                [
                    ['li', [], $message1TranslatedEscaped, $listEntryMessage1],
                    ['li', [], $message2TranslatedEscaped, $listEntryMessage2],
                    ['ul', [], '        ' . $listEntryMessage1 . PHP_EOL . '        ' . $listEntryMessage2 . PHP_EOL . '    ', $listMessage],
                    ['div', ['class' => 'invalid-feedback'], '    ' . $listMessage, $divMessage],
                ],
            );

        $translator = $this->getMockBuilder(Translate::class)
            ->disableOriginalConstructor()
            ->getMock();
        $translator->expects(self::exactly(2))
            ->method('__invoke')
            ->willReturnMap(
                [
                    [$message1, $textDomain, null, $message1Translated],
                    [$message2, $textDomain, null, $message2Translated],
                ],
            );

        $helper = new FormElementErrors($htmlElement, $escapeHtml, $translator);

        $element = $this->getMockBuilder(Text::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::once())
            ->method('getMessages')
            ->willReturn(['x1' => $message1, 'x2' => '', 'x3' => [$message2, '']]);
        $element->expects(self::never())
            ->method('getAttribute');
        $element->expects(self::once())
            ->method('hasAttribute')
            ->with('id')
            ->willReturn(false);
        $element->expects(self::exactly(2))
            ->method('getLabelOption')
            ->with('disable_html_escape')
            ->willReturn(false);

        $helper->setTranslatorTextDomain($textDomain);

        self::assertSame($divMessage, $helper->render($element));
    }

    /** @throws Exception */
    public function testRenderWithMessagesAndTranslatorWithoutEscape(): void
    {
        $message1                  = 'too long';
        $message1Translated        = 'too long, but translated';
        $message1TranslatedEscaped = 'too long, but translated and escaped';
        $message2                  = 'too short';
        $message2Translated        = 'too short, but translated';
        $listEntryMessage1         = sprintf('<li>%s</li>', $message1TranslatedEscaped);
        $listEntryMessage2         = sprintf('<li>%s</li>', $message2Translated);
        $listMessage               = sprintf('<ul>%s%s</ul>', $listEntryMessage1, $listEntryMessage2);
        $divMessage                = sprintf('<div>%s</div>', $listMessage);
        $textDomain                = 'test-domain';

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::once())
            ->method('__invoke')
            ->with($message1Translated)
            ->willReturn($message1TranslatedEscaped);

        $htmlElement = $this->getMockBuilder(HtmlElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $htmlElement->expects(self::exactly(4))
            ->method('toHtml')
            ->willReturnMap(
                [
                    ['li', [], $message1TranslatedEscaped, $listEntryMessage1],
                    ['li', [], $message2Translated, $listEntryMessage2],
                    ['ul', [], '        ' . $listEntryMessage1 . PHP_EOL . '        ' . $listEntryMessage2 . PHP_EOL . '    ', $listMessage],
                    ['div', ['class' => 'invalid-feedback'], '    ' . $listMessage, $divMessage],
                ],
            );

        $translator = $this->getMockBuilder(Translate::class)
            ->disableOriginalConstructor()
            ->getMock();
        $translator->expects(self::exactly(2))
            ->method('__invoke')
            ->willReturnMap(
                [
                    [$message1, $textDomain, null, $message1Translated],
                    [$message2, $textDomain, null, $message2Translated],
                ],
            );

        $helper = new FormElementErrors($htmlElement, $escapeHtml, $translator);

        $element = $this->getMockBuilder(Text::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::once())
            ->method('getMessages')
            ->willReturn(['x1' => $message1, 'x2' => '', 'x3' => [$message2, '']]);
        $element->expects(self::never())
            ->method('getAttribute');
        $element->expects(self::once())
            ->method('hasAttribute')
            ->with('id')
            ->willReturn(false);
        $element->expects(self::exactly(2))
            ->method('getLabelOption')
            ->with('disable_html_escape')
            ->willReturnOnConsecutiveCalls(false, true);

        $helper->setTranslatorTextDomain($textDomain);

        self::assertSame($divMessage, $helper->render($element));
    }

    /** @throws Exception */
    public function testInvokeWithMessagesAndTranslatorWithoutEscape1(): void
    {
        $message1                  = 'too long';
        $message1Translated        = 'too long, but translated';
        $message1TranslatedEscaped = 'too long, but translated and escaped';
        $message2                  = 'too short';
        $message2Translated        = 'too short, but translated';
        $listEntryMessage1         = sprintf('<li>%s</li>', $message1TranslatedEscaped);
        $listEntryMessage2         = sprintf('<li>%s</li>', $message2Translated);
        $listMessage               = sprintf('<ul>%s%s</ul>', $listEntryMessage1, $listEntryMessage2);
        $divMessage                = sprintf('<div>%s</div>', $listMessage);
        $textDomain                = 'test-domain';

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::once())
            ->method('__invoke')
            ->with($message1Translated)
            ->willReturn($message1TranslatedEscaped);

        $htmlElement = $this->getMockBuilder(HtmlElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $htmlElement->expects(self::exactly(4))
            ->method('toHtml')
            ->willReturnMap(
                [
                    ['li', [], $message1TranslatedEscaped, $listEntryMessage1],
                    ['li', [], $message2Translated, $listEntryMessage2],
                    ['ul', [], '        ' . $listEntryMessage1 . PHP_EOL . '        ' . $listEntryMessage2 . PHP_EOL . '    ', $listMessage],
                    ['div', ['class' => 'invalid-feedback'], '    ' . $listMessage, $divMessage],
                ],
            );

        $translator = $this->getMockBuilder(Translate::class)
            ->disableOriginalConstructor()
            ->getMock();
        $translator->expects(self::exactly(2))
            ->method('__invoke')
            ->willReturnMap(
                [
                    [$message1, $textDomain, null, $message1Translated],
                    [$message2, $textDomain, null, $message2Translated],
                ],
            );

        $helper = new FormElementErrors($htmlElement, $escapeHtml, $translator);

        $element = $this->getMockBuilder(Text::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::once())
            ->method('getMessages')
            ->willReturn(['x1' => $message1, 'x2' => '', 'x3' => [$message2, '']]);
        $element->expects(self::never())
            ->method('getAttribute');
        $element->expects(self::once())
            ->method('hasAttribute')
            ->with('id')
            ->willReturn(false);
        $element->expects(self::exactly(2))
            ->method('getLabelOption')
            ->with('disable_html_escape')
            ->willReturnOnConsecutiveCalls(false, true);

        $helper->setTranslatorTextDomain($textDomain);

        $helperObject = $helper();

        assert($helperObject instanceof FormElementErrors);

        self::assertSame($divMessage, $helperObject->render($element));
    }

    /** @throws Exception */
    public function testInvokeWithMessagesAndTranslatorWithoutEscape2(): void
    {
        $message1                  = 'too long';
        $message1Translated        = 'too long, but translated';
        $message1TranslatedEscaped = 'too long, but translated and escaped';
        $message2                  = 'too short';
        $message2Translated        = 'too short, but translated';
        $listEntryMessage1         = sprintf('<li>%s</li>', $message1TranslatedEscaped);
        $listEntryMessage2         = sprintf('<li>%s</li>', $message2Translated);
        $listMessage               = sprintf('<ul>%s%s</ul>', $listEntryMessage1, $listEntryMessage2);
        $divMessage                = sprintf('<div>%s</div>', $listMessage);
        $textDomain                = 'test-domain';

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::once())
            ->method('__invoke')
            ->with($message1Translated)
            ->willReturn($message1TranslatedEscaped);

        $htmlElement = $this->getMockBuilder(HtmlElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $htmlElement->expects(self::exactly(4))
            ->method('toHtml')
            ->willReturnMap(
                [
                    ['li', [], $message1TranslatedEscaped, $listEntryMessage1],
                    ['li', [], $message2Translated, $listEntryMessage2],
                    ['ul', [], '        ' . $listEntryMessage1 . PHP_EOL . '        ' . $listEntryMessage2 . PHP_EOL . '    ', $listMessage],
                    ['div', ['class' => 'invalid-feedback'], '    ' . $listMessage, $divMessage],
                ],
            );

        $translator = $this->getMockBuilder(Translate::class)
            ->disableOriginalConstructor()
            ->getMock();
        $translator->expects(self::exactly(2))
            ->method('__invoke')
            ->willReturnMap(
                [
                    [$message1, $textDomain, null, $message1Translated],
                    [$message2, $textDomain, null, $message2Translated],
                ],
            );

        $helper = new FormElementErrors($htmlElement, $escapeHtml, $translator);

        $element = $this->getMockBuilder(Text::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::once())
            ->method('getMessages')
            ->willReturn(['x1' => $message1, 'x2' => '', 'x3' => [$message2, '']]);
        $element->expects(self::never())
            ->method('getAttribute');
        $element->expects(self::once())
            ->method('hasAttribute')
            ->with('id')
            ->willReturn(false);
        $element->expects(self::exactly(2))
            ->method('getLabelOption')
            ->with('disable_html_escape')
            ->willReturnOnConsecutiveCalls(false, true);

        $helper->setTranslatorTextDomain($textDomain);

        self::assertSame($divMessage, $helper($element));
    }

    /** @throws Exception */
    public function testSetGetAttributes(): void
    {
        $attributes = ['class' => 'xyz', 'data-message' => 'void'];

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::never())
            ->method('__invoke');

        $htmlElement = $this->getMockBuilder(HtmlElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $htmlElement->expects(self::never())
            ->method('toHtml');

        $helper = new FormElementErrors($htmlElement, $escapeHtml, null);

        self::assertSame($helper, $helper->setAttributes($attributes));
        self::assertSame($attributes, $helper->getAttributes());
    }

    /** @throws Exception */
    public function testInvokeWithMessagesAndTranslatorWithoutEscape3(): void
    {
        $id                        = 'test-id';
        $message1                  = 'too long';
        $message1Translated        = 'too long, but translated';
        $message1TranslatedEscaped = 'too long, but translated and escaped';
        $message2                  = 'too short';
        $message2Translated        = 'too short, but translated';
        $listEntryMessage1         = sprintf('<li>%s</li>', $message1TranslatedEscaped);
        $listEntryMessage2         = sprintf('<li>%s</li>', $message2Translated);
        $listMessage               = sprintf('<ul>%s%s</ul>', $listEntryMessage1, $listEntryMessage2);
        $divMessage                = sprintf('<div>%s</div>', $listMessage);
        $textDomain                = 'test-domain';
        $attributes                = ['class' => 'xyz', 'data-message' => 'void'];

        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::once())
            ->method('__invoke')
            ->with($message1Translated)
            ->willReturn($message1TranslatedEscaped);

        $htmlElement = $this->getMockBuilder(HtmlElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $htmlElement->expects(self::exactly(4))
            ->method('toHtml')
            ->willReturnMap(
                [
                    ['li', [], $message1TranslatedEscaped, $listEntryMessage1],
                    ['li', [], $message2Translated, $listEntryMessage2],
                    ['ul', $attributes, '        ' . $listEntryMessage1 . PHP_EOL . '        ' . $listEntryMessage2 . PHP_EOL . '    ', $listMessage],
                    ['div', ['class' => 'invalid-feedback', 'id' => 'test-idFeedback'], '    ' . $listMessage, $divMessage],
                ],
            );

        $translator = $this->getMockBuilder(Translate::class)
            ->disableOriginalConstructor()
            ->getMock();
        $translator->expects(self::exactly(2))
            ->method('__invoke')
            ->willReturnMap(
                [
                    [$message1, $textDomain, null, $message1Translated],
                    [$message2, $textDomain, null, $message2Translated],
                ],
            );

        $helper = new FormElementErrors($htmlElement, $escapeHtml, $translator);

        $element = $this->getMockBuilder(Text::class)
            ->disableOriginalConstructor()
            ->getMock();
        $element->expects(self::once())
            ->method('getMessages')
            ->willReturn(['x1' => $message1, 'x2' => '', 'x3' => [$message2, '']]);
        $element->expects(self::once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn($id);
        $element->expects(self::once())
            ->method('hasAttribute')
            ->with('id')
            ->willReturn(true);
        $element->expects(self::exactly(2))
            ->method('getLabelOption')
            ->with('disable_html_escape')
            ->willReturnOnConsecutiveCalls(false, true);

        $helper->setTranslatorTextDomain($textDomain);
        $helper->setAttributes($attributes);

        self::assertSame($divMessage, $helper($element));
    }

    /** @throws Exception */
    public function testSetGetInden1(): void
    {
        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::never())
            ->method('__invoke');

        $htmlElement = $this->getMockBuilder(HtmlElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $htmlElement->expects(self::never())
            ->method('toHtml');

        $helper = new FormElementErrors($htmlElement, $escapeHtml, null);

        self::assertSame($helper, $helper->setIndent(4));
        self::assertSame('    ', $helper->getIndent());
    }

    /** @throws Exception */
    public function testSetGetInden2(): void
    {
        $escapeHtml = $this->getMockBuilder(EscapeHtml::class)
            ->disableOriginalConstructor()
            ->getMock();
        $escapeHtml->expects(self::never())
            ->method('__invoke');

        $htmlElement = $this->getMockBuilder(HtmlElementInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $htmlElement->expects(self::never())
            ->method('toHtml');

        $helper = new FormElementErrors($htmlElement, $escapeHtml, null);

        self::assertSame($helper, $helper->setIndent('  '));
        self::assertSame('  ', $helper->getIndent());
    }
}
