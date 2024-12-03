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

use Laminas\Form\Element\DateTime;
use Laminas\Form\Form;

return [
    'type' => Form::class,
    'elements' => [
        [
            'spec' => [
                'type' => DateTime::class,
                'name' => 'inputDate4',
                'options' => [
                    'label' => 'Date',
                    'col_attributes' => ['class' => 'col-md-6'],
                ],
                'attributes' => ['id' => 'inputDate4'],
            ],
        ],
    ],
];
