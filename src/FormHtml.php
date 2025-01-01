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

namespace Mimmi20\LaminasView\BootstrapForm;

use Laminas\Form\View\Helper\AbstractHelper;
use Override;
use stdClass;

use function sprintf;

final class FormHtml extends AbstractHelper implements FormHtmlInterface
{
    use FormTrait;

    /**
     * Render a html element
     *
     * @phpstan-param array<int|string, (array<int, string>|bool|float|int|iterable<int, string>|stdClass|string|null)> $attribs
     *
     * @throws void
     */
    #[Override]
    public function render(string $element, array $attribs, string $content): string
    {
        return $this->open($element, $attribs) . $content . $this->close($element);
    }

    /**
     * Generate an opening tag
     *
     * @phpstan-param array<int|string, (array<int, string>|bool|float|int|iterable<int, string>|stdClass|string|null)> $attribs
     *
     * @throws void
     */
    private function open(string $element, array $attribs): string
    {
        $attribsString = $this->createAttributesString($attribs);

        if ($attribsString !== '') {
            $attribsString = ' ' . $attribsString;
        }

        return sprintf('<%s%s>', $element, $attribsString);
    }

    /**
     * Return a closing tag
     *
     * @throws void
     */
    private function close(string $element): string
    {
        return sprintf('</%s>', $element);
    }
}
