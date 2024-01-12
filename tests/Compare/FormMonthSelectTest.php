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

namespace Compare;

use Laminas\Form\Exception\DomainException;
use Laminas\Form\Exception\InvalidArgumentException;
use Laminas\Form\Factory;
use Laminas\I18n\Exception\RuntimeException;
use Laminas\View\HelperPluginManager;
use Laminas\View\Renderer\PhpRenderer;
use Mimmi20\LaminasView\BootstrapForm\FormMonthSelect;
use Mimmi20Test\LaminasView\BootstrapForm\Compare\AbstractTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Exception;
use Psr\Container\ContainerExceptionInterface;

use function assert;
use function trim;

#[Group('form-month-select')]
final class FormMonthSelectTest extends AbstractTestCase
{
    /**
     * @return array<string, array{config: string, template: string, indent: string, messages: array<string, array<int, string>>}>
     *
     * @throws void
     */
    public static function providerTests(): array
    {
        return [
            'month-select' => [
                'config' => 'month-select.config.php',
                'template' => 'month-select.html',
                'indent' => '',
                'messages' => [],
            ],
        ];
    }

    /**
     * @param array<string, array<int, string>> $messages
     *
     * @throws Exception
     * @throws DomainException
     * @throws ContainerExceptionInterface
     * @throws InvalidArgumentException
     * @throws \Laminas\View\Exception\InvalidArgumentException
     * @throws RuntimeException
     */
    #[DataProvider('providerTests')]
    public function testRender(string $config, string $template, string $indent, array $messages): void
    {
        $file = 'form/' . $template;

        $form = (new Factory())->createForm(require '_files/config/' . $config);

        $expected = $this->getExpected($file);

        $plugin = $this->serviceManager->get(HelperPluginManager::class);

        assert($plugin instanceof HelperPluginManager);

        $renderer = new PhpRenderer();
        $renderer->setHelperPluginManager($plugin);

        $helper = new FormMonthSelect();
        $helper->setView($renderer);

        if ($indent !== '') {
            $helper->setIndent($indent);
        }

        if ($messages !== []) {
            $form->setMessages($messages);
        }

        // file_put_contents($this->files . '/expected/' . $file, trim($helper->render($form->get('inputDate4'))));

        self::assertSame($expected, trim($helper->render($form->get('inputDate4'))));
    }
}
