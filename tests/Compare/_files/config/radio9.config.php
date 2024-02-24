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

return [
    'type' => Form::class,
    'elements' => [
        [
            'spec' => [
                'type' => Radio::class,
                'name' => 'inputRadio',
                'options' => [
                    'label' => 'Address',
                    'label_options' => ['always_wrap' => true],
                    'value_options' => [
                        [
                            'value' => 'option1',
                            'label' => 'First radio',
                            'attributes' => ['id' => 'gridRadios1'],
                        ],
                        [
                            'value' => 'option2',
                            'label' => 'Second radio',
                            'attributes' => ['id' => 'gridRadios2'],
                        ],
                        [
                            'value' => 'option3',
                            'label' => 'Third disabled radio',
                            'attributes' => [
                                'id' => 'gridRadios3',
                                'disabled' => true,
                            ],
                        ],
                    ],
                    'as-button' => true,
                ],
                'attributes' => ['id' => 'inputRadio'],
            ],
        ],
    ],
];
