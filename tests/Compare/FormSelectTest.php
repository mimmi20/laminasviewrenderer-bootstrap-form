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
use Laminas\View\Helper\EscapeHtml;
use Laminas\View\HelperPluginManager;
use Mimmi20\LaminasView\BootstrapForm\FormHiddenInterface;
use Mimmi20\LaminasView\BootstrapForm\FormSelect;
use PHPUnit\Framework\Exception;
use Psr\Container\ContainerExceptionInterface;

use function assert;
use function gettype;
use function is_object;
use function sprintf;
use function trim;

final class FormSelectTest extends AbstractTestCase
{
    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws ContainerExceptionInterface
     */
    public function testRender(): void
    {
        $form = (new Factory())->createForm(require '_files/config/select.config.php');

        $expected = $this->getExpected('form/select.html');

        $plugin = $this->serviceManager->get(HelperPluginManager::class);

        assert($plugin instanceof HelperPluginManager);

        $escapeHtml = $plugin->get(EscapeHtml::class);
        $hidden     = $plugin->get(FormHiddenInterface::class);

        assert(
            $escapeHtml instanceof EscapeHtml,
            sprintf(
                '$escapeHtml should be an Instance of %s, but was %s',
                EscapeHtml::class,
                is_object($escapeHtml) ? $escapeHtml::class : gettype($escapeHtml),
            ),
        );
        assert($hidden instanceof FormHiddenInterface);

        $helper = new FormSelect($escapeHtml, $hidden, null);

        self::assertSame($expected, trim($helper->render($form->get('inputState'))));
    }
}
