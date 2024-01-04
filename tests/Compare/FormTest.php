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
use Laminas\ServiceManager\Exception\InvalidServiceException;
use Laminas\ServiceManager\Exception\ServiceNotFoundException;
use Laminas\View\Exception\RuntimeException;
use Laminas\View\HelperPluginManager;
use Mimmi20\LaminasView\BootstrapForm\Form;
use Mimmi20\LaminasView\BootstrapForm\FormCollection;
use Mimmi20\LaminasView\BootstrapForm\FormCollectionInterface;
use Mimmi20\LaminasView\BootstrapForm\FormRow;
use Mimmi20\LaminasView\BootstrapForm\FormRowInterface;
use PHPUnit\Framework\Exception;
use Psr\Container\ContainerExceptionInterface;

use function assert;
use function trim;

final class FormTest extends AbstractTestCase
{
    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws InvalidServiceException
     * @throws ServiceNotFoundException
     * @throws \Laminas\View\Exception\InvalidArgumentException
     * @throws RuntimeException
     * @throws ContainerExceptionInterface
     */
    public function testVerticalForm(): void
    {
        $form = (new Factory())->createForm(require '_files/config/vertical.config.php');

        $expected = $this->getExpected('form/vertical.html');

        $plugin = $this->serviceManager->get(HelperPluginManager::class);

        assert($plugin instanceof HelperPluginManager);

        $row        = $plugin->get(FormRow::class);
        $collection = $plugin->get(FormCollection::class);

        assert($row instanceof FormRowInterface);
        assert($collection instanceof FormCollectionInterface);

        $helper = new Form($collection, $row);

        self::assertSame($expected, trim($helper->render($form)));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws InvalidServiceException
     * @throws ServiceNotFoundException
     * @throws \Laminas\View\Exception\InvalidArgumentException
     * @throws RuntimeException
     * @throws ContainerExceptionInterface
     */
    public function testVerticalForm2(): void
    {
        $form = (new Factory())->createForm(require '_files/config/vertical2.config.php');

        $expected = $this->getExpected('form/vertical2.html');

        $plugin = $this->serviceManager->get(HelperPluginManager::class);

        assert($plugin instanceof HelperPluginManager);

        $row        = $plugin->get(FormRow::class);
        $collection = $plugin->get(FormCollection::class);

        assert($row instanceof FormRowInterface);
        assert($collection instanceof FormCollectionInterface);

        $helper = new Form($collection, $row);

        self::assertSame($expected, trim($helper->render($form)));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws InvalidServiceException
     * @throws ServiceNotFoundException
     * @throws \Laminas\View\Exception\InvalidArgumentException
     * @throws RuntimeException
     * @throws ContainerExceptionInterface
     */
    public function testVerticalWithFloatingLabelsForm(): void
    {
        $form = (new Factory())->createForm(require '_files/config/vertical.floating.config.php');

        $expected = $this->getExpected('form/vertical.floating.html');

        $plugin = $this->serviceManager->get(HelperPluginManager::class);

        assert($plugin instanceof HelperPluginManager);

        $row        = $plugin->get(FormRowInterface::class);
        $collection = $plugin->get(FormCollectionInterface::class);

        assert($row instanceof FormRowInterface);
        assert($collection instanceof FormCollectionInterface);

        $helper = new Form($collection, $row);

        self::assertSame($expected, trim($helper->render($form)));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws InvalidServiceException
     * @throws ServiceNotFoundException
     * @throws \Laminas\View\Exception\InvalidArgumentException
     * @throws RuntimeException
     * @throws ContainerExceptionInterface
     */
    public function testVerticalWithFloatingLabelsForm2(): void
    {
        $form = (new Factory())->createForm(require '_files/config/vertical.floating.config.php');
        $form->setOption('floating-labels', false);

        $expected = $this->getExpected('form/vertical.no-floating.html');

        $plugin = $this->serviceManager->get(HelperPluginManager::class);

        assert($plugin instanceof HelperPluginManager);

        $row        = $plugin->get(FormRowInterface::class);
        $collection = $plugin->get(FormCollectionInterface::class);

        assert($row instanceof FormRowInterface);
        assert($collection instanceof FormCollectionInterface);

        $helper = new Form($collection, $row);

        self::assertSame($expected, trim($helper->render($form)));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws InvalidServiceException
     * @throws ServiceNotFoundException
     * @throws \Laminas\View\Exception\InvalidArgumentException
     * @throws RuntimeException
     * @throws ContainerExceptionInterface
     */
    public function testVerticalWithFloatingLabelsForm3(): void
    {
        $form = (new Factory())->createForm(require '_files/config/vertical.floating2.config.php');

        $expected = $this->getExpected('form/vertical.floating2.html');

        $plugin = $this->serviceManager->get(HelperPluginManager::class);

        assert($plugin instanceof HelperPluginManager);

        $row        = $plugin->get(FormRowInterface::class);
        $collection = $plugin->get(FormCollectionInterface::class);

        assert($row instanceof FormRowInterface);
        assert($collection instanceof FormCollectionInterface);

        $helper = new Form($collection, $row);

        self::assertSame($expected, trim($helper->render($form)));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws InvalidServiceException
     * @throws ServiceNotFoundException
     * @throws \Laminas\View\Exception\InvalidArgumentException
     * @throws RuntimeException
     * @throws ContainerExceptionInterface
     */
    public function testHorizontalForm(): void
    {
        $form = (new Factory())->createForm(require '_files/config/horizontal.config.php');

        $expected = $this->getExpected('form/horizonal.html');

        $plugin = $this->serviceManager->get(HelperPluginManager::class);

        assert($plugin instanceof HelperPluginManager);

        $row        = $plugin->get(FormRowInterface::class);
        $collection = $plugin->get(FormCollectionInterface::class);

        assert($row instanceof FormRowInterface);
        assert($collection instanceof FormCollectionInterface);

        $helper = new Form($collection, $row);

        self::assertSame($expected, trim($helper->render($form)));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws InvalidServiceException
     * @throws ServiceNotFoundException
     * @throws \Laminas\View\Exception\InvalidArgumentException
     * @throws RuntimeException
     * @throws ContainerExceptionInterface
     */
    public function testHorizontalWithFloatingLabelsForm(): void
    {
        $form = (new Factory())->createForm(require '_files/config/horizontal.floating.config.php');

        $expected = $this->getExpected('form/horizonal.floating.html');

        $plugin = $this->serviceManager->get(HelperPluginManager::class);

        assert($plugin instanceof HelperPluginManager);

        $row        = $plugin->get(FormRowInterface::class);
        $collection = $plugin->get(FormCollectionInterface::class);

        assert($row instanceof FormRowInterface);
        assert($collection instanceof FormCollectionInterface);

        $helper = new Form($collection, $row);

        self::assertSame($expected, trim($helper->render($form)));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws InvalidServiceException
     * @throws ServiceNotFoundException
     * @throws \Laminas\View\Exception\InvalidArgumentException
     * @throws RuntimeException
     * @throws ContainerExceptionInterface
     */
    public function testHorizontalFormWithCollection(): void
    {
        $form = (new Factory())->createForm(require '_files/config/horizontal.collection.config.php');

        $expected = $this->getExpected('form/horizontal.collection.html');

        $plugin = $this->serviceManager->get(HelperPluginManager::class);

        assert($plugin instanceof HelperPluginManager);

        $row        = $plugin->get(FormRowInterface::class);
        $collection = $plugin->get(FormCollectionInterface::class);

        assert($row instanceof FormRowInterface);
        assert($collection instanceof FormCollectionInterface);

        $helper = new Form($collection, $row);

        self::assertSame($expected, trim($helper->render($form)));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws InvalidServiceException
     * @throws ServiceNotFoundException
     * @throws \Laminas\View\Exception\InvalidArgumentException
     * @throws RuntimeException
     * @throws ContainerExceptionInterface
     */
    public function testHorizontalFormWithElementGroup(): void
    {
        $form = (new Factory())->createForm(
            require '_files/config/horizontal.element-group.config.php',
        );

        $expected = $this->getExpected('form/horizontal.element-group.html');

        $plugin = $this->serviceManager->get(HelperPluginManager::class);

        assert($plugin instanceof HelperPluginManager);

        $row        = $plugin->get(FormRowInterface::class);
        $collection = $plugin->get(FormCollectionInterface::class);

        assert($row instanceof FormRowInterface);
        assert($collection instanceof FormCollectionInterface);

        $helper = new Form($collection, $row);

        self::assertSame($expected, trim($helper->render($form)));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws InvalidServiceException
     * @throws ServiceNotFoundException
     * @throws \Laminas\View\Exception\InvalidArgumentException
     * @throws RuntimeException
     * @throws ContainerExceptionInterface
     */
    public function testInlineForm(): void
    {
        $form = (new Factory())->createForm(require '_files/config/inline.config.php');

        $expected = $this->getExpected('form/inline.html');

        $plugin = $this->serviceManager->get(HelperPluginManager::class);

        assert($plugin instanceof HelperPluginManager);

        $row        = $plugin->get(FormRowInterface::class);
        $collection = $plugin->get(FormCollectionInterface::class);

        assert($row instanceof FormRowInterface);
        assert($collection instanceof FormCollectionInterface);

        $helper = new Form($collection, $row);

        self::assertSame($expected, trim($helper->render($form)));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws InvalidServiceException
     * @throws ServiceNotFoundException
     * @throws \Laminas\View\Exception\InvalidArgumentException
     * @throws RuntimeException
     * @throws ContainerExceptionInterface
     */
    public function testInlineWithFloatingForm(): void
    {
        $form = (new Factory())->createForm(require '_files/config/inline.floating.config.php');

        $expected = $this->getExpected('form/inline.floating.html');

        $plugin = $this->serviceManager->get(HelperPluginManager::class);

        assert($plugin instanceof HelperPluginManager);

        $row        = $plugin->get(FormRowInterface::class);
        $collection = $plugin->get(FormCollectionInterface::class);

        assert($row instanceof FormRowInterface);
        assert($collection instanceof FormCollectionInterface);

        $helper = new Form($collection, $row);

        self::assertSame($expected, trim($helper->render($form)));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws InvalidServiceException
     * @throws ServiceNotFoundException
     * @throws \Laminas\View\Exception\InvalidArgumentException
     * @throws RuntimeException
     * @throws ContainerExceptionInterface
     */
    public function testHrForm(): void
    {
        $form = (new Factory())->createForm(require '_files/config/default.hr.config.php');

        $expected = $this->getExpected('form/hr.html');

        $plugin = $this->serviceManager->get(HelperPluginManager::class);

        assert($plugin instanceof HelperPluginManager);

        $row        = $plugin->get(FormRowInterface::class);
        $collection = $plugin->get(FormCollectionInterface::class);

        assert($row instanceof FormRowInterface);
        assert($collection instanceof FormCollectionInterface);

        $helper = new Form($collection, $row);

        self::assertSame($expected, trim($helper->render($form)));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws InvalidServiceException
     * @throws ServiceNotFoundException
     * @throws \Laminas\View\Exception\InvalidArgumentException
     * @throws RuntimeException
     * @throws ContainerExceptionInterface
     */
    public function testHrForm2(): void
    {
        $form = (new Factory())->createForm(require '_files/config/default.hr2.config.php');

        $expected = $this->getExpected('form/hr2.html');

        $plugin = $this->serviceManager->get(HelperPluginManager::class);

        assert($plugin instanceof HelperPluginManager);

        $row        = $plugin->get(FormRowInterface::class);
        $collection = $plugin->get(FormCollectionInterface::class);

        assert($row instanceof FormRowInterface);
        assert($collection instanceof FormCollectionInterface);

        $helper = new Form($collection, $row);

        self::assertSame($expected, trim($helper->render($form)));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws InvalidServiceException
     * @throws ServiceNotFoundException
     * @throws \Laminas\View\Exception\InvalidArgumentException
     * @throws RuntimeException
     * @throws ContainerExceptionInterface
     */
    public function testPhvForm(): void
    {
        $form = (new Factory())->createForm(require '_files/config/default.phv.config.php');

        $expected = $this->getExpected('form/phv.html');

        $plugin = $this->serviceManager->get(HelperPluginManager::class);

        assert($plugin instanceof HelperPluginManager);

        $row        = $plugin->get(FormRowInterface::class);
        $collection = $plugin->get(FormCollectionInterface::class);

        assert($row instanceof FormRowInterface);
        assert($collection instanceof FormCollectionInterface);

        $helper = new Form($collection, $row);

        self::assertSame($expected, trim($helper->render($form)));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws InvalidServiceException
     * @throws ServiceNotFoundException
     * @throws \Laminas\View\Exception\InvalidArgumentException
     * @throws RuntimeException
     * @throws ContainerExceptionInterface
     */
    public function testPhvForm2(): void
    {
        $form = (new Factory())->createForm(require '_files/config/default.phv2.config.php');

        $expected = $this->getExpected('form/phv2.html');

        $plugin = $this->serviceManager->get(HelperPluginManager::class);

        assert($plugin instanceof HelperPluginManager);

        $row        = $plugin->get(FormRowInterface::class);
        $collection = $plugin->get(FormCollectionInterface::class);

        assert($row instanceof FormRowInterface);
        assert($collection instanceof FormCollectionInterface);

        $collection->setShouldWrap(false);

        $helper = new Form($collection, $row);
        $helper->setIndent('<!-- -->');

        self::assertSame($expected, trim($helper->render($form)));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws InvalidServiceException
     * @throws ServiceNotFoundException
     * @throws \Laminas\View\Exception\InvalidArgumentException
     * @throws RuntimeException
     * @throws ContainerExceptionInterface
     */
    public function testAdminForm(): void
    {
        $form = (new Factory())->createForm(require '_files/config/default.admin.config.php');

        $expected = $this->getExpected('form/admin.html');

        $plugin = $this->serviceManager->get(HelperPluginManager::class);

        assert($plugin instanceof HelperPluginManager);

        $row        = $plugin->get(FormRowInterface::class);
        $collection = $plugin->get(FormCollectionInterface::class);

        assert($row instanceof FormRowInterface);
        assert($collection instanceof FormCollectionInterface);

        $collection->setShouldWrap(false);

        $helper = new Form($collection, $row);

        self::assertSame($expected, trim($helper->render($form)));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws InvalidServiceException
     * @throws ServiceNotFoundException
     * @throws \Laminas\View\Exception\InvalidArgumentException
     * @throws RuntimeException
     * @throws ContainerExceptionInterface
     */
    public function testAdminForm2(): void
    {
        $form = (new Factory())->createForm(require '_files/config/default.admin2.config.php');

        $expected = $this->getExpected('form/admin2.html');

        $plugin = $this->serviceManager->get(HelperPluginManager::class);

        assert($plugin instanceof HelperPluginManager);

        $row        = $plugin->get(FormRowInterface::class);
        $collection = $plugin->get(FormCollectionInterface::class);

        assert($row instanceof FormRowInterface);
        assert($collection instanceof FormCollectionInterface);

        $collection->setShouldWrap(false);

        $helper = new Form($collection, $row);

        self::assertSame($expected, trim($helper->render($form)));
    }
}
