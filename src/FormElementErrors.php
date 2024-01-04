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
use Laminas\Form\LabelAwareInterface;
use Laminas\Form\View\Helper\AbstractHelper;
use Laminas\I18n\View\Helper\Translate;
use Laminas\View\Exception\InvalidArgumentException;
use Laminas\View\Helper\EscapeHtml;
use Mimmi20\LaminasView\Helper\HtmlElement\Helper\HtmlElementInterface;

use function array_walk_recursive;
use function assert;
use function implode;
use function is_string;

use const PHP_EOL;

final class FormElementErrors extends AbstractHelper implements FormElementErrorsInterface
{
    use FormTrait;

    /** @var array<string, string> Default attributes for the open format tag */
    private array $attributes = [];

    /** @throws void */
    public function __construct(
        private readonly HtmlElementInterface $htmlElement,
        private readonly EscapeHtml $escapeHtml,
        private readonly Translate | null $translate = null,
    ) {
        // nothing to do
    }

    /**
     * Invoke helper as functor
     *
     * Proxies to {@link render()} if an element is passed.
     *
     * @param array<string, string> $attributes
     *
     * @throws InvalidArgumentException
     */
    public function __invoke(ElementInterface | null $element = null, array $attributes = []): self | string
    {
        if (!$element) {
            return $this;
        }

        return $this->render($element, $attributes);
    }

    /**
     * Render validation errors for the provided $element
     *
     * If a translator is
     * composed, messages retrieved from the element will be translated; if
     * either is not the case, they will not.
     *
     * @param array<string, string> $attributes
     *
     * @throws InvalidArgumentException
     */
    public function render(ElementInterface $element, array $attributes = []): string
    {
        $messages = $element->getMessages();

        if ($messages === []) {
            return '';
        }

        // Flatten message array
        $messages = $this->flattenMessages($messages);

        if ($messages === []) {
            return '';
        }

        $indent  = $this->getIndent();
        $markups = [];

        foreach ($messages as $message) {
            if (
                !$element instanceof LabelAwareInterface
                || !$element->getLabelOption('disable_html_escape')
            ) {
                $message = ($this->escapeHtml)($message);
            }

            assert(is_string($message));

            $markups[] = $indent . $this->getWhitespace(8) . $this->htmlElement->toHtml(
                'li',
                [],
                $message,
            );
        }

        // Prepare attributes for opening tag
        $attributes      = [...$this->attributes, ...$attributes];
        $errorAttributes = ['class' => 'invalid-feedback'];

        if ($element->hasAttribute('id')) {
            $errorAttributes['id'] = $element->getAttribute('id') . 'Feedback';
        }

        $ul = $indent . $this->getWhitespace(4) . $this->htmlElement->toHtml(
            'ul',
            $attributes,
            implode(PHP_EOL, $markups) . PHP_EOL . $indent . $this->getWhitespace(4),
        );

        return $indent . $this->htmlElement->toHtml('div', $errorAttributes, $ul);
    }

    /**
     * Set the attributes that will go on the message open format
     *
     * @param array<string, string> $attributes key value pairs of attributes
     *
     * @throws void
     */
    public function setAttributes(array $attributes): self
    {
        $this->attributes = $attributes;

        return $this;
    }

    /**
     * Get the attributes that will go on the message open format
     *
     * @return array<string, string>
     *
     * @throws void
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @param array<int|string, string> $messages
     *
     * @return array<int, string>
     *
     * @throws void
     */
    private function flattenMessages(array $messages): array
    {
        $messagesToPrint = [];

        if (!$this->translate instanceof Translate) {
            $messageCallback = static function ($message) use (&$messagesToPrint): void {
                if ($message === '') {
                    return;
                }

                $messagesToPrint[] = $message;
            };
        } else {
            $translator      = $this->translate;
            $textDomain      = $this->getTranslatorTextDomain();
            $messageCallback = static function ($message) use (&$messagesToPrint, $translator, $textDomain): void {
                if ($message === '') {
                    return;
                }

                $messagesToPrint[] = ($translator)($message, $textDomain);
            };
        }

        array_walk_recursive($messages, $messageCallback);

        return $messagesToPrint;
    }
}
