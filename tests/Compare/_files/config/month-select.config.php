<?php

/**
 * This file is part of the mimmi20/laminasviewrenderer-bootstrap-form package.
 *
 * Copyright (c) 2021-2025, Thomas Mueller <mimmi20@live.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Calculator;

use Laminas\Form\Element\MonthSelect;
use Laminas\Form\Form;

use function date;

return [
    'type' => Form::class,
    'elements' => [
        [
            'spec' => [
                'type' => MonthSelect::class,
                'name' => 'inputDate4',
                'options' => [
                    'render_delimiters' => true,
                    'create_empty_option' => true,
                    'min_year' => (int) date('Y') - 1,
                    'max_year' => (int) date('Y'),
                    'label' => 'Date',
                    'col_attributes' => ['class' => 'col-md-6'],
                ],
                'attributes' => ['id' => 'inputDate4'],
            ],
        ],
    ],
];
