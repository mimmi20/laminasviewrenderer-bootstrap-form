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
use Laminas\View\HelperPluginManager;
use Mimmi20\LaminasView\BootstrapForm\FormDateSelect;
use Mimmi20\LaminasView\BootstrapForm\FormSelectInterface;
use PHPUnit\Framework\Exception;
use Psr\Container\ContainerExceptionInterface;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

use function trim;

final class FormDateSelectTest extends AbstractTest
{
    /**
     * @throws InvalidArgumentException
     * @throws Exception
     * @throws \Laminas\Form\Exception\InvalidArgumentException
     * @throws DomainException
     * @throws ContainerExceptionInterface
     */
    public function testRender(): void
    {
        $form = (new Factory())->createForm(require '_files/config/date-select.config.php');

        $expected = $this->getExpected('form/date-select.html');

        $helper = new FormDateSelect(
            $this->serviceManager->get(HelperPluginManager::class)->get(FormSelectInterface::class)
        );

        self::assertSame($expected, trim($helper->render($form->get('inputDate4'))));
    }
}
