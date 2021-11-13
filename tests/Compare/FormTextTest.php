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

namespace Mimmi20Test\LaminasView\BootstrapForm\Compare;

use Laminas\Form\Exception\DomainException;
use Laminas\Form\Factory;
use Laminas\View\Helper\Doctype;
use Laminas\View\Helper\EscapeHtml;
use Laminas\View\Helper\EscapeHtmlAttr;
use Laminas\View\HelperPluginManager;
use Mimmi20\LaminasView\BootstrapForm\FormText;
use PHPUnit\Framework\Exception;
use Psr\Container\ContainerExceptionInterface;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

use function assert;
use function trim;

final class FormTextTest extends AbstractTest
{
    /**
     * @throws InvalidArgumentException
     * @throws Exception
     * @throws DomainException
     * @throws ContainerExceptionInterface
     */
    public function testRender(): void
    {
        $form = (new Factory())->createForm(require '_files/config/text.config.php');

        $expected = $this->getExpected('form/text.html');

        $plugin = $this->serviceManager->get(HelperPluginManager::class);

        assert($plugin instanceof HelperPluginManager);

        $escapeHtml     = $plugin->get(EscapeHtml::class);
        $escapeHtmlAttr = $plugin->get(EscapeHtmlAttr::class);
        $docType        = $plugin->get(Doctype::class);

        assert($escapeHtml instanceof EscapeHtml);
        assert($escapeHtmlAttr instanceof EscapeHtmlAttr);
        assert($docType instanceof Doctype);

        $helper = new FormText($escapeHtml, $escapeHtmlAttr, $docType);

        self::assertSame($expected, trim($helper->render($form->get('inputText'))));
    }
}
