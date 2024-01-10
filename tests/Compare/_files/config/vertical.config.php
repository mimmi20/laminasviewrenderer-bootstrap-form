<?php
/**
 * This file is part of the mimmi20/mezzio-form-laminasviewrenderer-bootstrap package.
 *
 * Copyright (c) 2021-2024, Thomas Mueller <mimmi20@live.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Calculator;

use Laminas\Form\Element\Button;
use Laminas\Form\Element\Checkbox;
use Laminas\Form\Element\Email;
use Laminas\Form\Element\Password;
use Laminas\Form\Element\Select;
use Laminas\Form\Element\Text;
use Laminas\Form\Form;

return [
    'type' => Form::class,
    'options' => [
        'layout' => \Mimmi20\LaminasView\BootstrapForm\Form::LAYOUT_VERTICAL,
    ],
    'attributes' => ['class' => 'g-3'],
    'elements' => [
        [
            'spec' => [
                'type' => Email::class,
                'name' => 'inputEmail4',
                'options' => [
                    'label' => 'Email',
                    'col_attributes' => ['class' => 'col-md-6'],
                ],
                'attributes' => ['id' => 'inputEmail4'],
            ],
        ],
        [
            'spec' => [
                'type' => Password::class,
                'name' => 'inputPassword4',
                'options' => [
                    'label' => 'Password',
                    'col_attributes' => ['class' => 'col-md-6'],
                ],
                'attributes' => ['id' => 'inputPassword4'],
            ],
        ],
        [
            'spec' => [
                'type' => Text::class,
                'name' => 'inputAddress',
                'options' => [
                    'label' => 'Address',
                    'col_attributes' => ['class' => 'col-12'],
                ],
                'attributes' => [
                    'id' => 'inputAddress',
                    'placeholder' => '1234 Main St',
                ],
            ],
        ],
        [
            'spec' => [
                'type' => Text::class,
                'name' => 'inputAddress2',
                'options' => [
                    'label' => 'Address 2',
                    'col_attributes' => ['class' => 'col-12'],
                ],
                'attributes' => [
                    'id' => 'inputAddress2',
                    'placeholder' => 'Apartment, studio, or floor',
                ],
            ],
        ],
        [
            'spec' => [
                'type' => Text::class,
                'name' => 'inputCity',
                'options' => [
                    'label' => 'City',
                    'col_attributes' => ['class' => 'col-md-6'],
                ],
                'attributes' => ['id' => 'inputCity'],
            ],
        ],
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
                    ],
                ],
                'attributes' => ['id' => 'inputState'],
            ],
        ],
        [
            'spec' => [
                'type' => Text::class,
                'name' => 'inputZip',
                'options' => [
                    'label' => 'Zip',
                    'col_attributes' => ['class' => 'col-md-2'],
                ],
                'attributes' => ['id' => 'inputZip'],
            ],
        ],
        [
            'spec' => [
                'type' => Checkbox::class,
                'name' => 'gridCheck',
                'options' => [
                    'label' => 'Check me out',
                    'col_attributes' => ['class' => 'col-12'],
                    'use_hidden_element' => false,
                ],
                'attributes' => ['id' => 'gridCheck'],
            ],
        ],
        [
            'spec' => [
                'type' => Button::class,
                'name' => 'button',
                'options' => [
                    'label' => 'Sign in',
                    'col_attributes' => ['class' => 'col-12'],
                ],
                'attributes' => [
                    'type' => 'submit',
                    'class' => 'btn btn-primary',
                ],
            ],
        ],
    ],
];
