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

namespace Mimmi20\LaminasView\BootstrapForm;

use Laminas\Form\Element;
use Laminas\Form\ElementInterface;
use Laminas\Form\Exception\InvalidArgumentException;
use Laminas\Form\View\Helper\AbstractHelper;
use Laminas\ServiceManager\Exception\InvalidServiceException;
use Laminas\ServiceManager\Exception\ServiceNotFoundException;
use Laminas\View\Helper\HelperInterface;
use Laminas\View\HelperPluginManager;
use Laminas\View\Renderer\PhpRenderer;
use Mimmi20\Form\Links\Element\Links;
use Mimmi20\Form\Paragraph\Element\Paragraph;
use Laminas\Form\View\Helper\FormElement as BaseFormElement;

use function assert;
use function is_object;
use function method_exists;

final class FormElement extends BaseFormElement implements FormIndentInterface, FormRenderInterface
{
    use FormTrait;

    /**
     * Instance map to view helper
     *
     * @var array<string, string>
     */
    protected $classMap = [
        Element\Button::class => 'formButton',
        Element\Captcha::class => 'formCaptcha',
        Element\Csrf::class => 'formHidden',
        Element\Collection::class => 'formCollection',
        Element\DateTimeSelect::class => 'formDateTimeSelect',
        Element\DateSelect::class => 'formDateSelect',
        Element\MonthSelect::class => 'formMonthSelect',
        Element\Submit::class => 'formSubmit',
        Links::class => 'formLinks',
        Paragraph::class => 'formParagraph',
    ];

    /**
     * Type map to view helper
     *
     * @var array<string, string>
     */
    protected $typeMap = [
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
     * Render element by helper name
     *
     * @throws InvalidServiceException
     * @throws ServiceNotFoundException
     * @throws InvalidArgumentException
     */
    protected function renderHelper(string $name, ElementInterface $element): string
    {
        $renderer = $this->view;
        assert(
            $renderer instanceof PhpRenderer,
            sprintf(
                '$renderer should be an Instance of %s or null, but was %s',
                PhpRenderer::class,
                get_debug_type($renderer),
            ),
        );

        $helper = $renderer->getHelperPluginManager()->get($name);
        assert($helper instanceof HelperInterface);

        if ($helper instanceof FormIndentInterface || method_exists($helper, 'setIndent')) {
            $helper->setIndent($this->getIndent());
        }

        if ($helper instanceof FormRenderInterface || method_exists($helper, 'render')) {
            return $helper->render($element);
        }

        if (is_callable($helper)) {
            return $helper($element);
        }

        throw new InvalidArgumentException('the element does not support the render function');
    }
}
