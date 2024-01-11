<?php

declare(strict_types=1);

namespace Mimmi20\LaminasView\BootstrapForm;

use Laminas\Form\ElementInterface;
use Laminas\Form\Exception;

use Laminas\Form\View\Helper\AbstractHelper;
use Laminas\View\Exception\InvalidArgumentException;
use Laminas\View\Helper\HelperInterface;
use Laminas\View\Renderer\RendererInterface;
use stdClass;
use function is_string;
use function sprintf;
use function strtolower;

interface FormHtmlInterface extends HelperInterface, FormIndentInterface
{
    /**
     * Invoke helper as functor
     *
     * Proxies to {@link render()}.
     *
     * @throws void
     * @return string|self
     */
    public function __invoke(string|null $element = null, array $attribs = [], string $content = ''): string|self;

    /**
     * Render a html element
     *
     * @phpstan-param array<int|string, (array<int, string>|bool|float|int|iterable<int, string>|stdClass|string|null)> $attribs
     *
     * @throws void
     */
    public function render(string $element, array $attribs, string $content): string;
}
