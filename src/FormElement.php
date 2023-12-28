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

namespace Mimmi20\LaminasView\BootstrapForm;

use Laminas\Form\Element;
use Laminas\Form\ElementInterface;
use Laminas\Form\Exception\InvalidArgumentException;
use Laminas\Form\View\Helper\AbstractHelper;
use Laminas\ServiceManager\Exception\InvalidServiceException;
use Laminas\ServiceManager\Exception\ServiceNotFoundException;
use Laminas\View\Helper\HelperInterface;
use Laminas\View\HelperPluginManager;
use Mimmi20\Form\Links\Element\Links;
use Mimmi20\Form\Paragraph\Element\Paragraph;

use function assert;
use function is_object;
use function method_exists;

final class FormElement extends AbstractHelper implements FormElementInterface
{
    use FormTrait;

    /**
     * Instance map to view helper
     *
     * @var array<string, string>
     */
    private array $classMap = [
        Element\Button::class => 'formButton',
        Element\Captcha::class => 'formCaptcha',
        Element\Csrf::class => 'formHidden',
        Element\Collection::class => 'formCollection',
        Element\DateTimeSelect::class => 'formDateTimeSelect',
        Element\DateSelect::class => 'formDateSelect',
        Element\MonthSelect::class => 'formMonthSelect',
        Links::class => 'formLinks',
        Paragraph::class => 'formParagraph',
    ];

    /**
     * Type map to view helper
     *
     * @var array<string, string>
     */
    private array $typeMap = [
        'checkbox' => 'formCheckbox',
        'color' => 'formColor',
        'date' => 'formDate',
        'datetime' => 'formDatetime',
        'datetime-local' => 'formDatetimeLocal',
        'email' => 'formEmail',
        'file' => 'formFile',
        'hidden' => 'formHidden',
        'image' => 'formImage',
        'month' => 'formMonth',
        'multi_checkbox' => 'formMultiCheckbox',
        'number' => 'formNumber',
        'password' => 'formPassword',
        'radio' => 'formRadio',
        'range' => 'formRange',
        'reset' => 'formReset',
        'search' => 'formSearch',
        'select' => 'formSelect',
        'submit' => 'formSubmit',
        'tel' => 'formTel',
        'text' => 'formText',
        'textarea' => 'formTextarea',
        'time' => 'formTime',
        'url' => 'formUrl',
        'week' => 'formWeek',
    ];

    /**
     * Default helper name
     */
    private string $defaultHelper = FormElementInterface::DEFAULT_HELPER;

    /**
     * @phpstan-param HelperPluginManager<HelperInterface> $helperPluginManager
     *
     * @throws void
     */
    public function __construct(
        /** @phpstan-param HelperPluginManager<HelperInterface> $helperPluginManager */
        private readonly HelperPluginManager $helperPluginManager,
    ) {
        // nothing to do
    }

    /**
     * Invoke helper as function
     *
     * Proxies to {@link render()}.
     *
     * @return self|string
     *
     * @throws InvalidServiceException
     * @throws ServiceNotFoundException
     * @throws InvalidArgumentException
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingNativeTypeHint
     */
    public function __invoke(ElementInterface | null $element = null)
    {
        if (!$element) {
            return $this;
        }

        return $this->render($element);
    }

    /**
     * Render an element
     *
     * Introspects the element type and attributes to determine which
     * helper to utilize when rendering.
     *
     * @throws InvalidServiceException
     * @throws ServiceNotFoundException
     * @throws InvalidArgumentException
     */
    public function render(ElementInterface $element): string
    {
        $renderedInstance = $this->renderInstance($element);

        if ($renderedInstance !== null) {
            return $renderedInstance;
        }

        $renderedType = $this->renderType($element);

        if ($renderedType !== null) {
            return $renderedType;
        }

        return $this->renderHelper($this->defaultHelper, $element);
    }

    /**
     * Set default helper name
     *
     * @throws void
     */
    public function setDefaultHelper(string $name): self
    {
        $this->defaultHelper = $name;

        return $this;
    }

    /**
     * Set default helper name
     *
     * @throws void
     */
    public function getDefaultHelper(): string
    {
        return $this->defaultHelper;
    }

    /**
     * Add form element type to plugin map
     *
     * @throws void
     */
    public function addType(string $type, string $plugin): self
    {
        $this->typeMap[$type] = $plugin;

        return $this;
    }

    /**
     * Add instance class to plugin map
     *
     * @throws void
     */
    public function addClass(string $class, string $plugin): self
    {
        $this->classMap[$class] = $plugin;

        return $this;
    }

    /**
     * Render element by helper name
     *
     * @throws InvalidServiceException
     * @throws ServiceNotFoundException
     * @throws InvalidArgumentException
     */
    private function renderHelper(string $name, ElementInterface $element): string
    {
        $helper = $this->helperPluginManager->get($name);

        assert(is_object($helper));

        if ($helper instanceof FormIndentInterface || method_exists($helper, 'setIndent')) {
            $helper->setIndent($this->getIndent());
        }

        if ($helper instanceof FormRenderInterface || method_exists($helper, 'render')) {
            return $helper->render($element);
        }

        throw new InvalidArgumentException('the element does not support the render function');
    }

    /**
     * Render element by instance map
     *
     * @throws InvalidServiceException
     * @throws ServiceNotFoundException
     * @throws InvalidArgumentException
     */
    private function renderInstance(ElementInterface $element): string | null
    {
        foreach ($this->classMap as $class => $pluginName) {
            if ($element instanceof $class) {
                return $this->renderHelper($pluginName, $element);
            }
        }

        return null;
    }

    /**
     * Render element by type map
     *
     * @throws InvalidServiceException
     * @throws ServiceNotFoundException
     * @throws InvalidArgumentException
     */
    private function renderType(ElementInterface $element): string | null
    {
        $type = $element->getAttribute('type');

        if (isset($this->typeMap[$type])) {
            return $this->renderHelper($this->typeMap[$type], $element);
        }

        return null;
    }
}
