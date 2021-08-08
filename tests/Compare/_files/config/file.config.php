<?php
/**
 * This file is part of the mimmi20/laminasviewrenderer-bootstrap-form package.
 *
 * Copyright (c) 2021, Thomas Mueller <mimmi20@live.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Calculator;

use Laminas\Form\Element\File;
use Laminas\Form\Form;

return [
    'type' => Form::class,
    'elements' => [
        [
            'spec' => [
                'type' => File::class,
                'name' => 'inputFile',
                'options' => ['label' => 'File'],
                'attributes' => ['id' => 'inputFile3'],
            ],
        ],
    ],
];
