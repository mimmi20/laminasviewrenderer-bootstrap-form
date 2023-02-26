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

namespace Calculator;

use Laminas\Form\Element\Select;
use Laminas\Form\Form;

return [
    'type' => Form::class,
    'elements' => [
        [
            'spec' => [
                'type' => Select::class,
                'name' => 'inputState',
                'options' => [
                    'label' => 'State',
                    'col_attributes' => ['class' => 'col-md-4'],
                    'value_options' => [
                        [
                            'value' => '0',
                            'label' => 'Choose...',
                            'attributes' => ['selected' => true],
                        ],
                        [
                            'value' => '1',
                            'label' => '...',
                        ],
                        [
                            'label' => 'group',
                            'options' => [
                                [
                                    'value' => '2',
                                    'label' => 'Choose...',
                                ],
                                [
                                    'value' => '3',
                                    'label' => '...',
                                ],
                                [
                                    'label' => 'group2',
                                    'options' => [
                                        [
                                            'value' => '4',
                                            'label' => 'Choose...',
                                        ],
                                        [
                                            'value' => '5',
                                            'label' => '...',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                'attributes' => ['id' => 'inputState'],
            ],
        ],
    ],
];
