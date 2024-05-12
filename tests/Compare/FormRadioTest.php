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

namespace Mimmi20Test\LaminasView\BootstrapForm\Compare;

use Laminas\Form\Exception\DomainException;
use Laminas\Form\Exception\InvalidArgumentException;
use Laminas\Form\Factory;
use Laminas\I18n\Exception\RuntimeException;
use Laminas\View\HelperPluginManager;
use Laminas\View\Renderer\PhpRenderer;
use Mimmi20\LaminasView\BootstrapForm\FormRadio;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Exception;
use Psr\Container\ContainerExceptionInterface;

use function assert;
use function trim;

#[Group('form-radio')]
final class FormRadioTest extends AbstractTestCase
{
    /**
     * @return array<string, array{config: string, template: string, indent: string, messages: array<string, array<int, string>>}>
     *
     * @throws void
     */
    public static function providerTests(): array
    {
        return [
            'radio' => [
                'config' => 'radio.config.php',
                'template' => 'radio.html',
                'indent' => '<!-- -->',
                'messages' => [],
            ],
            'radio2' => [
                'config' => 'radio2.config.php',
                'template' => 'radio2.html',
                'indent' => '<!-- -->',
                'messages' => [],
            ],
            'radio3' => [
                'config' => 'radio3.config.php',
                'template' => 'radio3.html',
                'indent' => '<!-- -->',
                'messages' => [],
            ],
            'radio4' => [
                'config' => 'radio4.config.php',
                'template' => 'radio4.html',
                'indent' => '<!-- -->',
                'messages' => [],
            ],
            'radio5' => [
                'config' => 'radio5.config.php',
                'template' => 'radio5.html',
                'indent' => '<!-- -->',
                'messages' => [],
            ],
            'radio6' => [
                'config' => 'radio6.config.php',
                'template' => 'radio6.html',
                'indent' => '<!-- -->',
                'messages' => [],
            ],
            'radio7' => [
                'config' => 'radio7.config.php',
                'template' => 'radio7.html',
                'indent' => '<!-- -->',
                'messages' => [],
            ],
            'radio8' => [
                'config' => 'radio8.config.php',
                'template' => 'radio8.html',
                'indent' => '<!-- -->',
                'messages' => [],
            ],
            'radio9' => [
                'config' => 'radio9.config.php',
                'template' => 'radio9.html',
                'indent' => '<!-- -->',
                'messages' => [],
            ],
            'radio10' => [
                'config' => 'radio10.config.php',
                'template' => 'radio10.html',
                'indent' => '<!-- -->',
                'messages' => [],
            ],
            'radio11' => [
                'config' => 'radio11.config.php',
                'template' => 'radio11.html',
                'indent' => '<!-- -->',
                'messages' => [],
            ],
            'radio12' => [
                'config' => 'radio12.config.php',
                'template' => 'radio12.html',
                'indent' => '<!-- -->',
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

        $helper = new FormRadio();
        $helper->setView($renderer);

        if ($indent !== '') {
            $helper->setIndent($indent);
        }

        if ($messages !== []) {
            $form->setMessages($messages);
        }

        // file_put_contents($this->files . '/expected/' . $file, trim($helper->render($form->get('inputRadio'))));

        self::assertSame($expected, trim($helper->render($form->get('inputRadio'))));
    }
}
