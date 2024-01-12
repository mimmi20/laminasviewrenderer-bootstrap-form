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

use Laminas\Form\Element\Text;
use Laminas\Form\ElementInterface;
use Laminas\Form\Exception\InvalidArgumentException;
use Laminas\View\Renderer\PhpRenderer;
use Mimmi20\Form\Paragraph\Element\Paragraph;
use Mimmi20\LaminasView\BootstrapForm\FormElement;
use Mimmi20\LaminasView\BootstrapForm\FormInputInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;

use function assert;

#[Group('form-element')]
final class FormElementTest extends TestCase
{
    /** @throws Exception */
    public function testSetGetInden1(): void
    {
        $helper = new FormElement();

        self::assertSame($helper, $helper->setIndent(4));
        self::assertSame('    ', $helper->getIndent());
    }

    /** @throws Exception */
    public function testSetGetInden2(): void
    {
        $helper = new FormElement();

        self::assertSame($helper, $helper->setIndent('  '));
        self::assertSame('  ', $helper->getIndent());
    }

    /** @throws Exception */
    public function testSetGetDefaultHelper(): void
    {
        $defaultHelper = 'xyz';

        $helper = new FormElement();

        self::assertSame($helper, $helper->setDefaultHelper($defaultHelper));
        self::assertSame($defaultHelper, $helper->getDefaultHelper());
    }

    /**
     * @return array<int, array<int, ElementInterface|string>>
     *
     * @throws InvalidArgumentException
     */
    public static function providerRender(): array
    {
        return [
            [
                new Paragraph(),
                'formParagraph',
                FormInputInterface::class,
                '<paragraph>',
            ],
        ];
    }

    /**
     * @param class-string<mixed> $class
     *
     * @throws Exception
     */
    #[DataProvider('providerRender')]
    public function testRender(ElementInterface $element, string $helperType, string $class, string $rendered): void
    {
        $subHelper = $this->createMock($class);
        $subHelper->expects(self::once())
            ->method('setIndent')
            ->with('');
        $subHelper->expects(self::once())
            ->method('render')
            ->with($element)
            ->willReturn($rendered);

        $renderer = $this->createMock(PhpRenderer::class);
        $renderer->expects(self::never())
            ->method('getHelperPluginManager');
        $renderer->expects(self::once())
            ->method('plugin')
            ->with($helperType)
            ->willReturn($subHelper);
        $renderer->expects(self::never())
            ->method('render');

        $helper = new FormElement();
        $helper->setView($renderer);

        self::assertSame($rendered, $helper->render($element));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testInvoke1(): void
    {
        $element    = new Text();
        $helperType = 'formtext';
        $rendered   = '<text>';

        $subHelper = $this->createMock(FormInputInterface::class);
        $subHelper->expects(self::once())
            ->method('setIndent')
            ->with('');
        $subHelper->expects(self::once())
            ->method('render')
            ->with($element)
            ->willReturn($rendered);

        $renderer = $this->createMock(PhpRenderer::class);
        $renderer->expects(self::never())
            ->method('getHelperPluginManager');
        $renderer->expects(self::once())
            ->method('plugin')
            ->with($helperType)
            ->willReturn($subHelper);
        $renderer->expects(self::never())
            ->method('render');

        $helper = new FormElement();
        $helper->setView($renderer);

        $helperObject = $helper();

        assert($helperObject instanceof FormElement);

        self::assertSame($rendered, $helperObject->render($element));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testInvoke2(): void
    {
        $element    = new Text();
        $helperType = 'formtext';
        $rendered   = '<text>';

        $subHelper = $this->createMock(FormInputInterface::class);
        $subHelper->expects(self::once())
            ->method('setIndent')
            ->with('');
        $subHelper->expects(self::once())
            ->method('render')
            ->with($element)
            ->willReturn($rendered);

        $renderer = $this->createMock(PhpRenderer::class);
        $renderer->expects(self::never())
            ->method('getHelperPluginManager');
        $renderer->expects(self::never())
            ->method('getHelperPluginManager');
        $renderer->expects(self::once())
            ->method('plugin')
            ->with($helperType)
            ->willReturn($subHelper);
        $renderer->expects(self::never())
            ->method('render');

        $helper = new FormElement();
        $helper->setView($renderer);

        self::assertSame($rendered, $helper($element));
    }
}
