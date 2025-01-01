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

use Laminas\Form\Element\Button;
use Laminas\Form\Element\Checkbox;
use Laminas\Form\Element\Email;
use Laminas\Form\Element\Password;
use Laminas\Form\Element\Radio;
use Laminas\Form\Element\Select;
use Laminas\Form\Element\Text;
use Laminas\Form\Form;

return [
    'type' => Form::class,
    'options' => ['floating-labels' => true],
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
                'type' => Radio::class,
                'name' => 'gridRadios',
                'options' => [
                    'label' => 'Address',
                    'label_col_attributes' => ['class' => 'pt-0'],
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
                    'as-card' => true,
                    'as-button' => true,
                ],
                'attributes' => ['id' => 'gridRadios'],
            ],
        ],
        [
            'spec' => [
                'type' => Radio::class,
                'name' => 'gridRadios2',
                'options' => [
                    'label' => 'Address',
                    'label_options' => ['always_wrap' => true],
                    'label_col_attributes' => ['class' => 'pt-0'],
                    'value_options' => [
                        [
                            'value' => 'option1',
                            'label' => 'First radio',
                            'attributes' => ['id' => 'gridRadios4'],
                        ],
                        [
                            'value' => 'option2',
                            'label' => 'Second radio',
                            'attributes' => ['id' => 'gridRadios5'],
                        ],
                        [
                            'value' => 'option3',
                            'label' => 'Third disabled radio',
                            'attributes' => [
                                'id' => 'gridRadios6',
                                'disabled' => true,
                            ],
                        ],
                    ],
                    'as-card' => true,
                    'as-button' => true,
                ],
                'attributes' => ['id' => 'gridRadios2'],
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
                    'as-card' => true,
                    'as-button' => true,
                ],
                'attributes' => ['id' => 'gridCheck'],
            ],
        ],
        [
            'spec' => [
                'type' => Checkbox::class,
                'name' => 'gridCheck2',
                'options' => [
                    'label' => 'Check me out',
                    'label_options' => ['always_wrap' => true],
                    'col_attributes' => ['class' => 'col-12'],
                    'use_hidden_element' => false,
                    'as-card' => true,
                    'as-button' => true,
                ],
                'attributes' => ['id' => 'gridCheck2'],
            ],
        ],
        [
            'spec' => [
                'type' => Radio::class,
                'name' => 'zusatzfragen',
                'options' => [
                    'label' => 'weitere Fragen',
                    'value_options' => [
                        'nein' => [
                            'value' => 'nein',
                            'label' => 'Ich verzichte auf die Beantwortung weiterer Fragen und wähle aus dem Vergleich einen Tarif, der meinen Bedarf erfüllt.',
                            'attributes' => [
                                'id' => 'zusatzfragen_nein',
                                'class' => 'form-check-input form-radio-input js-gtm-event',
                                'data-event-type' => 'click',
                                'data-event-category' => 'versicherung',
                                'data-event-action' => 'no additional questions',
                                'data-event-label' => 'hr',
                            ],
                        ],
                        'ja' => [
                            'value' => 'ja',
                            'label' => 'Ich möchte weitere Angaben zum gewünschten Versicherungsschutz machen. Es werden dann nur Tarife angezeigt, welche die Vorgaben erfüllen.',
                            'attributes' => [
                                'id' => 'zusatzfragen_ja',
                                'class' => 'form-check-input form-radio-input js-gtm-event',
                                'data-event-type' => 'click',
                                'data-event-category' => 'versicherung',
                                'data-event-action' => 'additional questions requested',
                                'data-event-label' => 'hr',
                            ],
                        ],
                    ],
                    'as-card' => true,
                ],
                'attributes' => ['id' => 'zusatzfragen'],
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
