<?php

/**
 * This file is part of the mimmi20/laminasviewrenderer-bootstrap-form package.
 *
 * Copyright (c) 2021-2026, Thomas Mueller <mimmi20@live.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Mimmi20Test\LaminasView\BootstrapForm\Compare;

use Laminas\Form\Exception\DomainException;
use Laminas\Form\Exception\ExceptionInterface;
use Laminas\Form\Exception\InvalidArgumentException;
use Laminas\Form\Factory;
use Laminas\ServiceManager\Exception\InvalidServiceException;
use Laminas\ServiceManager\Exception\ServiceNotFoundException;
use Laminas\View\Exception\RuntimeException;
use Laminas\View\HelperPluginManager;
use Laminas\View\Renderer\PhpRenderer;
use Mimmi20\LaminasView\BootstrapForm\Form;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Exception;
use Psr\Container\ContainerExceptionInterface;

use function assert;
use function trim;

final class FormTest extends AbstractTestCase
{
    /**
     * @return array<string, array{config: string, template: string, indent: string, messages: array<string, array<int, string>>}>
     *
     * @throws void
     */
    public static function providerTests(): array
    {
        return [
            'vertical' => [
                'config' => 'vertical.config.php',
                'template' => 'vertical.html',
                'indent' => '',
                'messages' => [],
                'values' => [],
                'validate' => false,
            ],
            'vertical2' => [
                'config' => 'vertical2.config.php',
                'template' => 'vertical2.html',
                'indent' => '',
                'messages' => [],
                'values' => [],
                'validate' => false,
            ],
            'vertical.floating2' => [
                'config' => 'vertical.floating2.config.php',
                'template' => 'vertical.floating2.html',
                'indent' => '',
                'messages' => [],
                'values' => [],
                'validate' => false,
            ],
            'vertical.card' => [
                'config' => 'vertical.card.config.php',
                'template' => 'vertical.card.html',
                'indent' => '',
                'messages' => [],
                'values' => [],
                'validate' => false,
            ],
            'vertical.floating' => [
                'config' => 'vertical.floating.config.php',
                'template' => 'vertical.floating.html',
                'indent' => '',
                'messages' => [],
                'values' => [],
                'validate' => false,
            ],
            'vertical.floating3' => [
                'config' => 'vertical.floating3.config.php',
                'template' => 'vertical.floating3.html',
                'indent' => '',
                'messages' => [],
                'values' => [],
                'validate' => false,
            ],
            'vertical.floating.card' => [
                'config' => 'vertical.floating.card.config.php',
                'template' => 'vertical.floating.card.html',
                'indent' => '',
                'messages' => [],
                'values' => [],
                'validate' => false,
            ],
            'horizontal' => [
                'config' => 'horizontal.config.php',
                'template' => 'horizontal.html',
                'indent' => '',
                'messages' => [],
                'values' => [],
                'validate' => false,
            ],
            'horizontal2' => [
                'config' => 'horizontal2.config.php',
                'template' => 'horizontal2.html',
                'indent' => '',
                'messages' => [],
                'values' => [],
                'validate' => false,
            ],
            'horizontal.collection' => [
                'config' => 'horizontal.collection.config.php',
                'template' => 'horizontal.collection.html',
                'indent' => '',
                'messages' => [],
                'values' => [],
                'validate' => false,
            ],
            'horizontal.element-group' => [
                'config' => 'horizontal.element-group.config.php',
                'template' => 'horizontal.element-group.html',
                'indent' => '',
                'messages' => [],
                'values' => [],
                'validate' => false,
            ],
            'horizontal.floating' => [
                'config' => 'horizontal.floating.config.php',
                'template' => 'horizontal.floating.html',
                'indent' => '',
                'messages' => [],
                'values' => [],
                'validate' => false,
            ],
            'horizontal.floating2' => [
                'config' => 'horizontal.floating2.config.php',
                'template' => 'horizontal.floating2.html',
                'indent' => '',
                'messages' => [],
                'values' => [],
                'validate' => false,
            ],
            'inline' => [
                'config' => 'inline.config.php',
                'template' => 'inline.html',
                'indent' => '',
                'messages' => [],
                'values' => [],
                'validate' => false,
            ],
            'inline.floating' => [
                'config' => 'inline.floating.config.php',
                'template' => 'inline.floating.html',
                'indent' => '',
                'messages' => [],
                'values' => [],
                'validate' => false,
            ],
            'horizontal.hr' => [
                'config' => 'horizontal.hr.config.php',
                'template' => 'horizontal.hr.html',
                'indent' => '',
                'messages' => [],
                'values' => [],
                'validate' => false,
            ],
            'horizontal.hr2' => [
                'config' => 'horizontal.hr2.config.php',
                'template' => 'horizontal.hr2.html',
                'indent' => '',
                'messages' => [],
                'values' => [],
                'validate' => false,
            ],
            'vertical.hr' => [
                'config' => 'vertical.hr.config.php',
                'template' => 'vertical.hr.html',
                'indent' => '',
                'messages' => [],
                'values' => [],
                'validate' => false,
            ],
            'vertical.hr.in-group' => [
                'config' => 'vertical.hr.in-group.config.php',
                'template' => 'vertical.hr.in-group.html',
                'indent' => '',
                'messages' => [
                    'zusatzfragen' => ['is required'],
                    'KrPHV' => ['add some more'],
                ],
                'values' => [],
                'validate' => false,
            ],
            'vertical.hr.floating.in-group' => [
                'config' => 'vertical.hr.floating.in-group.config.php',
                'template' => 'vertical.hr.floating.in-group.html',
                'indent' => '',
                'messages' => [
                    'zusatzfragen' => ['is required'],
                    'KrPHV' => ['add some more'],
                ],
                'values' => [],
                'validate' => false,
            ],
            'horizontal.hr.card' => [
                'config' => 'horizontal.hr.card.config.php',
                'template' => 'horizontal.hr.card.html',
                'indent' => '',
                'messages' => [],
                'values' => [],
                'validate' => false,
            ],
            'horizontal.hr.card.values' => [
                'config' => 'horizontal.hr.card.config.php',
                'template' => 'horizontal.hr.card.values.html',
                'indent' => '',
                'messages' => [],
                'values' => ['chkErstinfo' => '1'],
                'validate' => false,
            ],
            'horizontal.hr.card2' => [
                'config' => 'horizontal.hr.card2.config.php',
                'template' => 'horizontal.hr.card2.html',
                'indent' => '',
                'messages' => [],
                'values' => [],
                'validate' => false,
            ],
            'horizontal.hr.card2.values' => [
                'config' => 'horizontal.hr.card2.config.php',
                'template' => 'horizontal.hr.card2.values.html',
                'indent' => '',
                'messages' => [],
                'values' => [
                    'zusatzfragen' => 'ja',
                    'feu_v' => 'ja',
                ],
                'validate' => false,
            ],
            'horizontal.hr.card2.values2' => [
                'config' => 'horizontal.hr.card2.config.php',
                'template' => 'horizontal.hr.card2.values2.html',
                'indent' => '',
                'messages' => [],
                'values' => [
                    'zusatzfragen' => 'ja',
                    'feu_v' => 'ja',
                ],
                'validate' => true,
            ],
            'horizontal.hr.in-group' => [
                'config' => 'horizontal.hr.in-group.config.php',
                'template' => 'horizontal.hr.in-group.html',
                'indent' => '',
                'messages' => [
                    'zusatzfragen' => ['is required'],
                    'KrPHV' => ['add some more'],
                ],
                'values' => [],
                'validate' => false,
            ],
            'vertical.hr.card' => [
                'config' => 'vertical.hr.card.config.php',
                'template' => 'vertical.hr.card.html',
                'indent' => '',
                'messages' => [],
                'values' => [],
                'validate' => false,
            ],
            'vertical.hr.card2' => [
                'config' => 'vertical.hr.card2.config.php',
                'template' => 'vertical.hr.card2.html',
                'indent' => '',
                'messages' => [],
                'values' => [],
                'validate' => false,
            ],
            'horizontal.phv' => [
                'config' => 'horizontal.phv.config.php',
                'template' => 'horizontal.phv.html',
                'indent' => '',
                'messages' => [],
                'values' => [],
                'validate' => false,
            ],
            'horizontal.phv2' => [
                'config' => 'horizontal.phv2.config.php',
                'template' => 'horizontal.phv2.html',
                'indent' => '<!-- -->',
                'messages' => [],
                'values' => [],
                'validate' => false,
            ],
            'horizontal.rs' => [
                'config' => 'horizontal.rs.config.php',
                'template' => 'horizontal.rs.html',
                'indent' => '',
                'messages' => [],
                'values' => [],
                'validate' => false,
            ],
            'horizontal.rs.messages' => [
                'config' => 'horizontal.rs.config.php',
                'template' => 'horizontal.rs.messages.html',
                'indent' => '',
                'messages' => [
                    'gebdatum' => ['too young'],
                    'anag' => ['value not in group'],
                    'zusatzfragen' => ['is required'],
                    'tarif_privat' => ['not checked yet'],
                    'KrPHV' => ['add some more'],
                ],
                'values' => [],
                'validate' => false,
            ],
            'vertical.floating.rs' => [
                'config' => 'vertical.floating.rs.config.php',
                'template' => 'vertical.floating.rs.html',
                'indent' => '',
                'messages' => [],
                'values' => [],
                'validate' => false,
            ],
            'vertical.floating.rs.messages' => [
                'config' => 'vertical.floating.rs.config.php',
                'template' => 'vertical.floating.rs.messages.html',
                'indent' => '',
                'messages' => [
                    'gebdatum' => ['too young'],
                    'anag' => ['value not in group'],
                    'zusatzfragen' => ['is required'],
                    'tarif_privat' => ['not checked yet'],
                    'KrPHV' => ['add some more'],
                ],
                'values' => [],
                'validate' => false,
            ],
            'vertical.rs' => [
                'config' => 'vertical.rs.config.php',
                'template' => 'vertical.rs.html',
                'indent' => '',
                'messages' => [],
                'values' => [],
                'validate' => false,
            ],
            'vertical.rs.messages' => [
                'config' => 'vertical.rs.config.php',
                'template' => 'vertical.rs.messages.html',
                'indent' => '',
                'messages' => [
                    'gebdatum' => ['too young'],
                    'anag' => ['value not in group'],
                    'zusatzfragen' => ['is required'],
                    'tarif_privat' => ['not checked yet'],
                    'KrPHV' => ['add some more'],
                ],
                'values' => [],
                'validate' => false,
            ],
            'vertical.admin' => [
                'config' => 'vertical.admin.config.php',
                'template' => 'vertical.admin.html',
                'indent' => '',
                'messages' => [],
                'values' => [],
                'validate' => false,
            ],
            'vertical.admin2' => [
                'config' => 'vertical.admin2.config.php',
                'template' => 'vertical.admin2.html',
                'indent' => '',
                'messages' => [],
                'values' => [],
                'validate' => false,
            ],
            'horizontal.hr.as-form-control' => [
                'config' => 'horizontal.hr.as-form-control.config.php',
                'template' => 'horizontal.hr.as-form-control.html',
                'indent' => '',
                'messages' => [],
                'values' => [],
                'validate' => false,
            ],
            'vertical.hr.as-form-control' => [
                'config' => 'vertical.hr.as-form-control.config.php',
                'template' => 'vertical.hr.as-form-control.html',
                'indent' => '',
                'messages' => [],
                'values' => [],
                'validate' => false,
            ],
            'vertical.floating.rs.as-form-control' => [
                'config' => 'vertical.floating.rs.as-form-control.config.php',
                'template' => 'vertical.floating.rs.as-form-control.html',
                'indent' => '',
                'messages' => [],
                'values' => [],
                'validate' => false,
            ],
        ];
    }

    /**
     * @param array<string, array<int, string>> $messages
     * @param array<string, mixed>              $values
     *
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws InvalidServiceException
     * @throws ServiceNotFoundException
     * @throws \Laminas\View\Exception\InvalidArgumentException
     * @throws RuntimeException
     * @throws ContainerExceptionInterface
     * @throws \Laminas\I18n\Exception\RuntimeException
     * @throws ExceptionInterface
     */
    #[DataProvider('providerTests')]
    public function testRender(
        string $config,
        string $template,
        string $indent,
        array $messages,
        array $values,
        bool $validate,
    ): void {
        $file = 'form/' . $template;

        $form = (new Factory())->createForm(require '_files/config/' . $config);

        $expected = $this->getExpected($file);

        $plugin = $this->serviceManager->get(HelperPluginManager::class);

        assert($plugin instanceof HelperPluginManager);

        $renderer = new PhpRenderer();
        $renderer->setHelperPluginManager($plugin);

        $helper = new Form();
        $helper->setView($renderer);

        if ($indent !== '') {
            $helper->setIndent($indent);
        }

        if ($messages !== []) {
            $form->setMessages($messages);
        }

        if ($values !== []) {
            $form->setData($values);
        }

        if ($validate) {
            $form->isValid();
        }

        // file_put_contents($this->files . '/expected/' . $file, trim($helper->render($form)));

        self::assertSame($expected, trim($helper->render($form)));
    }
}
