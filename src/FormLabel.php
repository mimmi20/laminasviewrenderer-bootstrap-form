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

namespace Mimmi20\LaminasView\BootstrapForm;

use Laminas\Form\ElementInterface;
use Laminas\Form\Exception;
use Laminas\Form\Exception\InvalidArgumentException;
use Laminas\Form\LabelAwareInterface;
use Laminas\Form\View\Helper\AbstractHelper;
use Laminas\I18n\View\Helper\Translate;
use Laminas\View\Helper\EscapeHtml;

use function array_merge;
use function get_class;
use function gettype;
use function is_array;
use function is_object;
use function sprintf;

final class FormLabel extends AbstractHelper implements FormLabelInterface
{
    /**
     * Attributes valid for the label tag
     *
     * @var array<string, bool>
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint
     */
    protected $validTagAttributes = [
        'for' => true,
        'form' => true,
    ];

    private ?Translate $translate;
    private EscapeHtml $escaper;

    public function __construct(EscapeHtml $escaper, ?Translate $translator = null)
    {
        $this->escaper   = $escaper;
        $this->translate = $translator;
    }

    /**
     * Generate a form label, optionally with content
     *
     * Always generates a "for" statement, as we cannot assume the form input
     * will be provided in the $labelContent.
     *
     * @return FormLabel|string
     *
     * @throws Exception\DomainException
     * @throws InvalidArgumentException
     */
    public function __invoke(?ElementInterface $element = null, ?string $labelContent = null, ?string $position = null)
    {
        if (!$element) {
            return $this;
        }

        $label = '';

        if (null === $labelContent || null !== $position) {
            $label = $element->getLabel();

            if (null === $labelContent && empty($label)) {
                throw new Exception\DomainException(
                    sprintf(
                        '%s expects either label content as the second argument, or that the element provided has a label attribute; neither found',
                        __METHOD__
                    )
                );
            }

            if (!empty($label)) {
                if (null !== $this->translate) {
                    $label = ($this->translate)($label, $this->getTranslatorTextDomain());
                }

                if (!$element instanceof LabelAwareInterface || !$element->getLabelOption('disable_html_escape')) {
                    $label = ($this->escaper)($label);
                }

                if (
                    '' !== $label && !$element->hasAttribute('id')
                    || ($element instanceof LabelAwareInterface && $element->getLabelOption('always_wrap'))
                ) {
                    $label = '<span>' . $label . '</span>';
                }
            }
        }

        if ($label && $labelContent) {
            switch ($position) {
                case FormLabelInterface::APPEND:
                    $labelContent .= $label;
                    break;
                case FormLabelInterface::PREPEND:
                default:
                    $labelContent = $label . $labelContent;
                    break;
            }
        }

        if ($label && null === $labelContent) {
            $labelContent = $label;
        }

        return $this->openTag($element) . $labelContent . $this->closeTag();
    }

    /**
     * Generate an opening label tag
     *
     * @param array<string, bool|string>|ElementInterface|null $attributesOrElement
     *
     * @throws Exception\InvalidArgumentException
     * @throws Exception\DomainException
     */
    public function openTag($attributesOrElement = null): string
    {
        if (null === $attributesOrElement) {
            return '<label>';
        }

        if (is_array($attributesOrElement)) {
            $attributes = $this->createAttributesString($attributesOrElement);

            return sprintf('<label %s>', $attributes);
        }

        if (!$attributesOrElement instanceof ElementInterface) {
            throw new Exception\InvalidArgumentException(
                sprintf(
                    '%s expects an array or Laminas\Form\ElementInterface instance; received "%s"',
                    __METHOD__,
                    is_object($attributesOrElement) ? get_class($attributesOrElement) : gettype($attributesOrElement)
                )
            );
        }

        $id = $this->getId($attributesOrElement);
        if (null === $id) {
            throw new Exception\DomainException(
                sprintf(
                    '%s expects the Element provided to have either a name or an id present; neither found',
                    __METHOD__
                )
            );
        }

        $labelAttributes = [];
        if ($attributesOrElement instanceof LabelAwareInterface) {
            $labelAttributes = $attributesOrElement->getLabelAttributes();
        }

        $attributes = ['for' => $id];

        if (!empty($labelAttributes)) {
            $attributes = array_merge($attributes, $labelAttributes);
        }

        $attributes = $this->createAttributesString($attributes);

        return sprintf('<label %s>', $attributes);
    }

    /**
     * Return a closing label tag
     */
    public function closeTag(): string
    {
        return '</label>';
    }
}
