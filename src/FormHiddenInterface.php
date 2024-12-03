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

use Laminas\Form\ElementInterface;
use Laminas\Form\Exception\DomainException;
use Laminas\Form\Exception\InvalidArgumentException;
use Laminas\View\Helper\HelperInterface;

interface FormHiddenInterface extends FormIndentInterface, FormRenderInterface, HelperInterface
{
    /**
     * Invoke helper as functor
     *
     * Proxies to {@link render()}.
     *
     * @return self|string
     *
     * @throws InvalidArgumentException
     * @throws DomainException
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingNativeTypeHint
     */
    public function __invoke(ElementInterface | null $element = null);
}
