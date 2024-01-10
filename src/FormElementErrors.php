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
use Laminas\Form\View\Helper\FormElementErrors as BaseFormElementErrors;

use function array_walk_recursive;
use function assert;
use function implode;
use function is_string;

use const PHP_EOL;

final class FormElementErrors extends BaseFormElementErrors implements FormRenderInterface, FormIndentInterface
{
    use FormTrait;
    use HtmlHelperTrait;

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

        $htmlHelper       = $this->getHtmlHelper();

        foreach ($messages as $message) {
            if (!is_string($message)) {
                continue;
            }

            if (
                !$element instanceof LabelAwareInterface
                || !$element->getLabelOption('disable_html_escape')
            ) {
                $escapeHtmlHelper = $this->getEscapeHtmlHelper();
                $message = $escapeHtmlHelper($message);
            }

            assert(is_string($message));

            $markups[] = $indent . $this->getWhitespace(8) . $htmlHelper->render(
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

        $ul = $htmlHelper->render(
            'ul',
            $attributes,
            PHP_EOL . implode(PHP_EOL, $markups) . PHP_EOL . $indent . $this->getWhitespace(4),
        );

        return PHP_EOL . $indent . $htmlHelper->render(
            'div',
            $errorAttributes,
            PHP_EOL . $indent . $this->getWhitespace(4) . $ul . PHP_EOL . $indent,
        );
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
        $translator      = $this->getTranslator();

        if (!$translator instanceof Translate) {
            $messageCallback = static function ($message) use (&$messagesToPrint): void {
                if ($message === '') {
                    return;
                }

                $messagesToPrint[] = $message;
            };
        } else {
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
