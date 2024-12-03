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

namespace Calculator;

use Laminas\Form\Element\Radio;
use Laminas\Form\Form;
use Laminas\Form\View\Helper\FormRow as BaseFormRow;

return [
    'type' => Form::class,
    'elements' => [
        [
            'spec' => [
                'type' => Radio::class,
                'name' => 'inputRadio',
                'options' => [
                    'label' => 'Radio',
                    'label_options' => ['label_position' => BaseFormRow::LABEL_PREPEND],
                    'value_options' => ['def' => 'abc'],
                    'as-button' => true,
                ],
                'attributes' => ['id' => 'inputRadio3'],
            ],
        ],
    ],
];
