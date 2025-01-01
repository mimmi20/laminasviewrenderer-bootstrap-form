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

use Laminas\Form\Element\Radio;
use Laminas\Form\Form;
use Laminas\Form\View\Helper\FormRow as BaseFormRow;

return [
    'type' => Form::class,
    'elements' => [
        [
            'flags' => ['priority' => 17],
            'spec' => [
                'type' => Radio::class,
                'name' => 'inputRadio',
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
                            'disabled' => true,
                            'label_attributes' => [
                                'class' => 'form-check-label form-text',
                                'data-a' => 'a',
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
                            'selected' => true,
                            'label_attributes' => [
                                'class' => 'form-check-label form-text',
                                'data-b' => 'b',
                            ],
                        ],
                    ],
                    'as-card' => true,
                    'label_options' => [
                        'always_wrap' => true,
                        'label_position' => BaseFormRow::LABEL_APPEND,
                    ],
                    'label_attributes' => [
                        'class' => 'form-check-label form-text',
                        'data-y' => 'y',
                    ],
                    'switch' => true,
                    'group_attributes' => [
                        'class' => 'form-check-inline form-switch',
                        'data-x' => 'x',
                    ],
                    'layout' => \Mimmi20\LaminasView\BootstrapForm\Form::LAYOUT_INLINE,
                ],
                'attributes' => ['id' => 'inputRadio'],
            ],
        ],
    ],
];
