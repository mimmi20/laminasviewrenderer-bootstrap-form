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

namespace Mimmi20\LaminasView\BootstrapForm;

use Laminas\Form\ElementInterface;
use Laminas\Form\Exception;
use Laminas\Form\View\Helper\FormInput as BaseFormInput;
use Laminas\View\Helper\Doctype;
use Laminas\View\Helper\EscapeHtml;
use Laminas\View\Helper\EscapeHtmlAttr;

use function array_key_exists;
use function array_merge;
use function array_unique;
use function explode;
use function implode;
use function sprintf;
use function trim;

/**
 * @SuppressWarnings(PHPMD.NumberOfChildren)
 * @psalm-suppress ReservedWord
 */
abstract class FormInput extends BaseFormInput implements FormInputInterface
{
    use FormTrait;

    /**
     * @throws void
     *
     * @psalm-suppress ReservedWord
     */
    public function __construct(
        protected EscapeHtml $escapeHtml,
        protected EscapeHtmlAttr $escapeHtmlAttr,
        protected Doctype $doctype,
    ) {
        // nothing to do
    }

    /**
     * Render a form <input> element from the provided $element
     *
     * @throws Exception\DomainException
     */
    public function render(ElementInterface $element): string
    {
        $name = $element->getName();

        if ($name === null || $name === '') {
            throw new Exception\DomainException(
                sprintf(
                    '%s requires that the element has an assigned name; none discovered',
                    __METHOD__,
                ),
            );
        }

        $attributes = $element->getAttributes();

        $attributes['name'] = $name;
        $type               = $this->getType($element);
        $attributes['type'] = $type;

        $attributes['value'] = $type === 'password' ? '' : $element->getValue();

        if (isset($attributes['readonly']) && $element->getOption('plain')) {
            $classes = ['form-control-plaintext'];
        } else {
            $classes = ['form-control'];

            if (array_key_exists('class', $attributes)) {
                $classes = array_merge($classes, explode(' ', (string) $attributes['class']));
            }
        }

        $attributes['class'] = trim(implode(' ', array_unique($classes)));

        $markup = sprintf(
            '<input %s%s',
            $this->createAttributesString($attributes),
            $this->getInlineClosingBracket(),
        );

        $indent = $this->getIndent();

        return $indent . $markup;
    }

    /**
     * Get the closing bracket for an inline tag
     *
     * Closes as either "/>" for XHTML doctypes or ">" otherwise.
     *
     * @throws void
     */
    public function getInlineClosingBracket(): string
    {
        if ($this->doctype->isXhtml()) {
            return '/>';
        }

        return '>';
    }
}
