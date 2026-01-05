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

namespace Mimmi20\LaminasView\BootstrapForm;

use Laminas\Form\Element;
use Laminas\Form\ElementInterface;
use Laminas\Form\Exception\InvalidArgumentException;
use Laminas\Form\View\Helper\FormElement as BaseFormElement;
use Laminas\View\Helper\HelperInterface;
use Laminas\View\Renderer\PhpRenderer;
use Mimmi20\Form\Links\Element\Links;
use Mimmi20\Form\Paragraph\Element\Paragraph;
use Override;

use function assert;
use function get_debug_type;
use function is_callable;
use function method_exists;
use function sprintf;

final class FormElement extends BaseFormElement implements FormIndentInterface, FormRenderInterface
{
    use FormTrait;

    /**
     * Instance map to view helper
     *
     * @var array<class-string<ElementInterface>, string>
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint
     */
    protected $classMap = [
        Element\Button::class => 'formButton',
        Element\Captcha::class => 'formCaptcha',
        Element\Csrf::class => 'formHidden',
        Element\Collection::class => 'formCollection',
        Element\DateTimeSelect::class => 'formDateTimeSelect',
        Element\DateSelect::class => 'formDateSelect',
        Element\MonthSelect::class => 'formMonthSelect',
        Element\Submit::class => 'formSubmit',
        Links::class => 'formLinks',
        Paragraph::class => 'formParagraph',
    ];

    /**
     * Get default helper name
     *
     * @throws void
     *
     * @api
     */
    public function getDefaultHelper(): string
    {
        return $this->defaultHelper;
    }

    /**
     * Render element by helper name
     *
     * @throws InvalidArgumentException
     */
    #[Override]
    protected function renderHelper(string $name, ElementInterface $element): string
    {
        $renderer = $this->getView();
        assert(
            $renderer instanceof PhpRenderer,
            sprintf(
                '$renderer should be an Instance of %s, but was %s',
                PhpRenderer::class,
                get_debug_type($renderer),
            ),
        );

        $helper = $renderer->plugin($name);
        assert(
            $helper instanceof HelperInterface || is_callable($helper),
            sprintf(
                '$helper should be an Instance of %s or a Callable, but was %s',
                HelperInterface::class,
                get_debug_type($helper),
            ),
        );

        if ($helper instanceof HelperInterface) {
            if ($helper instanceof FormIndentInterface || method_exists($helper, 'setIndent')) {
                $helper->setIndent($this->getIndent());
            }

            if ($helper instanceof FormRenderInterface || method_exists($helper, 'render')) {
                return $helper->render($element);
            }
        }

        if (is_callable($helper)) {
            return $helper($element);
        }

        throw new InvalidArgumentException('the element does not support the render function');
    }
}
