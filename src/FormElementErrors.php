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
use Laminas\Form\Exception\DomainException;
use Laminas\Form\View\Helper\FormElementErrors as BaseFormElementErrors;

use const PHP_EOL;

final class FormElementErrors extends BaseFormElementErrors implements FormIndentInterface, FormRenderInterface
{
    use FormTrait;
    use HtmlHelperTrait;

    /**
     * Render validation errors for the provided $element
     *
     * If a translator is
     * composed, messages retrieved from the element will be translated; if
     * either is not the case, they will not.
     *
     * @param array<string, string> $attributes
     *
     * @throws DomainException
     */
    public function render(ElementInterface $element, array $attributes = []): string
    {
        $indent = $this->getIndent();

        $this->setMessageOpenFormat('<ul%s>' . PHP_EOL . $indent . $this->getWhitespace(8) . '<li>');
        $this->setMessageSeparatorString(
            '</li>' . PHP_EOL . $indent . $this->getWhitespace(8) . '<li>',
        );
        $this->setMessageCloseString('</li>' . PHP_EOL . $indent . $this->getWhitespace(4) . '</ul>');

        $markup = parent::render($element, $attributes);

        if ($markup === '') {
            return '';
        }

        $htmlHelper      = $this->getHtmlHelper();
        $errorAttributes = ['class' => 'invalid-feedback'];

        if ($element->hasAttribute('id')) {
            $errorAttributes['id'] = $element->getAttribute('id') . 'Feedback';
        }

        return PHP_EOL . $indent . $htmlHelper->render(
            'div',
            $errorAttributes,
            PHP_EOL . $indent . $this->getWhitespace(4) . $markup . PHP_EOL . $indent,
        );
    }
}
