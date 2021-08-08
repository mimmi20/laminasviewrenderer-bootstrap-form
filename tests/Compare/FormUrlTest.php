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
use Mimmi20\LaminasView\BootstrapForm\FormUrl;
use PHPUnit\Framework\Exception;
use Psr\Container\ContainerExceptionInterface;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

use function trim;

final class FormUrlTest extends AbstractTest
{
    /**
     * @throws InvalidArgumentException
     * @throws Exception
     * @throws DomainException
     * @throws ContainerExceptionInterface
     */
    public function testRender(): void
    {
        $form = (new Factory())->createForm(require '_files/config/url.config.php');

        $expected = $this->getExpected('form/url.html');

        $helper = new FormUrl(
            $this->serviceManager->get(HelperPluginManager::class)->get(EscapeHtml::class),
            $this->serviceManager->get(HelperPluginManager::class)->get(EscapeHtmlAttr::class),
            $this->serviceManager->get(HelperPluginManager::class)->get(Doctype::class)
        );

        self::assertSame($expected, trim($helper->render($form->get('inputUrl'))));
    }
}
