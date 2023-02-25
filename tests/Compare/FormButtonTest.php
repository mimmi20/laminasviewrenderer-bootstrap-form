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

namespace Mimmi20Test\LaminasView\BootstrapForm\Compare;

use Laminas\Form\Exception\DomainException;
use Laminas\Form\Exception\InvalidArgumentException;
use Laminas\Form\Factory;
use Laminas\View\Helper\Doctype;
use Laminas\View\Helper\EscapeHtml;
use Laminas\View\Helper\EscapeHtmlAttr;
use Laminas\View\HelperPluginManager;
use Mimmi20\LaminasView\BootstrapForm\FormButton;
use PHPUnit\Framework\Exception;
use Psr\Container\ContainerExceptionInterface;

use function assert;
use function gettype;
use function is_object;
use function sprintf;
use function trim;

final class FormButtonTest extends AbstractTestCase
{
    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws ContainerExceptionInterface
     */
    public function testRender(): void
    {
        $form = (new Factory())->createForm(require '_files/config/button.config.php');

        $expected = $this->getExpected('form/button.html');

        $plugin = $this->serviceManager->get(HelperPluginManager::class);

        assert($plugin instanceof HelperPluginManager);

        $escapeHtml     = $plugin->get(EscapeHtml::class);
        $escapeHtmlAttr = $plugin->get(EscapeHtmlAttr::class);
        $docType        = $plugin->get(Doctype::class);

        assert(
            $escapeHtml instanceof EscapeHtml,
            sprintf(
                '$escapeHtml should be an Instance of %s, but was %s',
                EscapeHtml::class,
                is_object($escapeHtml) ? $escapeHtml::class : gettype($escapeHtml),
            ),
        );
        assert(
            $escapeHtmlAttr instanceof EscapeHtmlAttr,
            sprintf(
                '$escapeHtmlAttr should be an Instance of %s, but was %s',
                EscapeHtmlAttr::class,
                is_object($escapeHtmlAttr) ? $escapeHtmlAttr::class : gettype($escapeHtmlAttr),
            ),
        );
        assert(
            $docType instanceof Doctype,
            sprintf(
                '$docType should be an Instance of %s, but was %s',
                Doctype::class,
                is_object($docType) ? $docType::class : gettype($docType),
            ),
        );

        $helper = new FormButton($escapeHtml, $escapeHtmlAttr, $docType, null);

        self::assertSame($expected, trim($helper->render($form->get('button'))));
    }
}
