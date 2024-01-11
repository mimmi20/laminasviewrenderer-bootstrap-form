<?php

declare(strict_types=1);

namespace Mimmi20\LaminasView\BootstrapForm;

use Laminas\Form\ElementInterface;
use Laminas\Form\Exception;

use Laminas\Form\View\Helper\AbstractHelper;
use Laminas\View\Exception\InvalidArgumentException;
use stdClass;
use function is_string;
use function sprintf;
use function strtolower;

final class FormHtml extends AbstractHelper implements FormHtmlInterface
{
    use FormTrait;

    /**
     * Invoke helper as functor
     *
     * Proxies to {@link render()}.
     *
     * @throws void
     * @return string|self
     */
    public function __invoke(string|null $element = null, array $attribs = [], string $content = ''): string|self
    {
        if (! $element) {
            return $this;
        }

        return $this->render($element, $attribs, $content);
    }

    /**
     * Render a html element
     *
     * @phpstan-param array<int|string, (array<int, string>|bool|float|int|iterable<int, string>|stdClass|string|null)> $attribs
     *
     * @throws void
     */
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
