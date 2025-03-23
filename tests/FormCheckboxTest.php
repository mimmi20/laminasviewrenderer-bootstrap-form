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

use Laminas\Form\Element\Checkbox;
use Laminas\Form\Element\Text;
use Laminas\Form\Exception\DomainException;
use Laminas\Form\Exception\InvalidArgumentException;
use Laminas\Form\View\Helper\FormRow as BaseFormRow;
use Mimmi20\LaminasView\BootstrapForm\FormCheckbox;
use PHPUnit\Event\NoPreviousThrowableException;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[Group('form-checkbox')]
final class FormCheckboxTest extends TestCase
{
    /** @throws InvalidArgumentException */
    public function testSetWrongLabelPosition(): void
    {
        $helper = new FormCheckbox();

        $labelPosition = 'abc';

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            sprintf(
                '%s expects either %s::LABEL_APPEND or %s::LABEL_PREPEND; received "%s"',
                'Mimmi20\LaminasView\BootstrapForm\LabelPositionTrait::setLabelPosition',
                BaseFormRow::class,
                BaseFormRow::class,
                $labelPosition,
            ),
        );
        $this->expectExceptionCode(0);

        $helper->setLabelPosition($labelPosition);
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetGetLabelPosition(): void
    {
        $helper = new FormCheckbox();

        $labelPosition = BaseFormRow::LABEL_PREPEND;

        self::assertSame(BaseFormRow::LABEL_APPEND, $helper->getLabelPosition());

        $helper->setLabelPosition($labelPosition);

        self::assertSame($labelPosition, $helper->getLabelPosition());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws \Laminas\View\Exception\InvalidArgumentException
     * @throws NoPreviousThrowableException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testRenderWithWrongElement(): void
    {
        $helper = new FormCheckbox();

        $element = $this->createMock(Text::class);
        $element->expects(self::never())
            ->method('getName');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            sprintf(
                '%s requires that the element is of type %s',
                'Mimmi20\LaminasView\BootstrapForm\FormCheckbox::render',
                Checkbox::class,
            ),
        );
        $this->expectExceptionCode(0);

        $helper->render($element);
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws \Laminas\View\Exception\InvalidArgumentException
     * @throws NoPreviousThrowableException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testRenderWithoutName(): void
    {
        $helper = new FormCheckbox();

        $element = $this->createMock(Checkbox::class);
        $element->expects(self::once())
            ->method('getName')
            ->willReturn(null);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage(
            sprintf(
                '%s requires that the element has an assigned name; none discovered',
                'Mimmi20\LaminasView\BootstrapForm\FormCheckbox::render',
            ),
        );
        $this->expectExceptionCode(0);

        $helper->render($element);
    }

    /** @throws Exception */
    public function testSetGetIndent1(): void
    {
        $helper = new FormCheckbox();

        self::assertSame($helper, $helper->setIndent(4));
        self::assertSame('    ', $helper->getIndent());
    }

    /** @throws Exception */
    public function testSetGetIndent2(): void
    {
        $helper = new FormCheckbox();

        self::assertSame($helper, $helper->setIndent('  '));
        self::assertSame('  ', $helper->getIndent());
    }
}
