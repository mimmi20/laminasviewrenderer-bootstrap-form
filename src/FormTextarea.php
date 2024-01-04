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

namespace Mimmi20\LaminasView\BootstrapForm;

use Laminas\Form\ElementInterface;
use Laminas\Form\Exception;
use Laminas\View\Exception\InvalidArgumentException;
use Laminas\View\Helper\AbstractHelper;
use Laminas\View\Helper\EscapeHtml;
use Mimmi20\LaminasView\Helper\HtmlElement\Helper\HtmlElementInterface;

use function array_key_exists;
use function array_merge;
use function array_unique;
use function assert;
use function explode;
use function implode;
use function is_string;
use function sprintf;
use function trim;

final class FormTextarea extends AbstractHelper
{
    use FormTrait;

    /**
     * Attributes valid for the input tag
     *
     * @var array<string, bool>
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint
     */
    protected $validTagAttributes = [
        'autocomplete' => true,
        'autofocus' => true,
        'cols' => true,
        'dirname' => true,
        'disabled' => true,
        'form' => true,
        'maxlength' => true,
        'minlength' => true,
        'name' => true,
        'placeholder' => true,
        'readonly' => true,
        'required' => true,
        'rows' => true,
        'wrap' => true,
    ];

    /** @throws void */
    public function __construct(
        private readonly HtmlElementInterface $htmlElement,
        private readonly EscapeHtml $escapeHtml,
    ) {
        // nothing to do
    }

    /**
     * Invoke helper as functor
     *
     * Proxies to {@link render()}.
     *
     * @throws Exception\DomainException
     * @throws InvalidArgumentException
     */
    public function __invoke(ElementInterface | null $element = null): self | string
    {
        if (!$element) {
            return $this;
        }

        return $this->render($element);
    }

    /**
     * Render a form <textarea> element from the provided $element
     *
     * @throws Exception\DomainException
     * @throws InvalidArgumentException
     */
    public function render(ElementInterface $element): string
    {
        $name = $element->getName();

        if (empty($name)) {
            throw new Exception\DomainException(
                sprintf(
                    '%s requires that the element has an assigned name; none discovered',
                    __METHOD__,
                ),
            );
        }

        $attributes = $element->getAttributes();

        $attributes['name'] = $name;

        $classes = ['form-control'];

        if (array_key_exists('class', $attributes)) {
            $classes = array_merge($classes, explode(' ', (string) $attributes['class']));
        }

        $attributes['class'] = trim(implode(' ', array_unique($classes)));

        $value = $element->getValue();

        assert(is_string($value));

        $content = ($this->escapeHtml)($value);

        assert(is_string($content));

        $markup = $this->htmlElement->toHtml('textarea', $attributes, $content);
        $indent = $this->getIndent();

        return $indent . $markup;
    }
}
