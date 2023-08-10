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

namespace Application;

use Laminas\Filter\StringTrim;
use Laminas\Filter\StripTags;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\Hidden;
use Laminas\Form\Element\Number;
use Laminas\Form\Element\Select;
use Laminas\Form\Element\Text;
use Laminas\Form\Element\Url;
use Laminas\Form\Form;
use Laminas\Form\InputFilterProviderFieldset;
use Laminas\Validator\Digits;
use Laminas\Validator\InArray;
use Laminas\Validator\NotEmpty;
use Laminas\Validator\Uri;

return [
    'type' => Form::class,
    'options' => [
        'layout' => \Mimmi20\LaminasView\BootstrapForm\Form::LAYOUT_VERTICAL,
        'use_input_filter_defaults' => true,
    ],
    'attributes' => [
        'class' => 'form p-3 g-0 needs-validation',
        'novalidate' => 'novalidate',
    ],
    'fieldsets' => [
        [
            'spec' => [
                'type' => InputFilterProviderFieldset::class,
                'name' => 'general',
                'options' => [
                    'label' => 'allgemeine Informationen',
                    'allow_remove' => false,
                    'label_attributes' => ['class' => 'w-auto px-2'],
                    'input_filter_spec' => [
                        'name' => [
                            'required' => true,
                            'filters' => [
                                ['name' => StripTags::class],
                                ['name' => StringTrim::class],
                            ],
                            'validators' => [
                                [
                                    'name' => NotEmpty::class,
                                    'options' => ['message' => 'Bitte den Namen des Mandanten eingeben'],
                                ],
                            ],
                        ],
                        'mandantor' => [
                            'required' => true,
                            'filters' => [
                                ['name' => StripTags::class],
                                ['name' => StringTrim::class],
                            ],
                            'validators' => [
                                [
                                    'name' => NotEmpty::class,
                                    'options' => ['message' => 'Bitte den Mandantennamen eingeben'],
                                ],
                            ],
                        ],
                        'theme' => [
                            'required' => false,
                            'filters' => [
                                ['name' => StripTags::class],
                                ['name' => StringTrim::class],
                            ],
                            'validators' => [
                                [
                                    'name' => NotEmpty::class,
                                    'options' => ['message' => 'Bitte ein Theme angeben'],
                                ],
                            ],
                        ],
                        'locale' => [
                            'required' => true,
                            'filters' => [
                                ['name' => StripTags::class],
                                ['name' => StringTrim::class],
                            ],
                            'validators' => [
                                [
                                    'name' => NotEmpty::class,
                                    'options' => ['message' => 'Bitte wählen, welche Sprache genutzt werden soll'],
                                ],
                            ],
                        ],
                        'translationDomain' => [
                            'required' => true,
                            'filters' => [
                                ['name' => StripTags::class],
                                ['name' => StringTrim::class],
                            ],
                            'validators' => [
                                [
                                    'name' => NotEmpty::class,
                                    'options' => ['message' => 'Bitte wählen, welche Sprach-Variante genutzt werden soll'],
                                ],
                            ],
                        ],
                        'role' => [
                            'required' => true,
                            'filters' => [
                                ['name' => StripTags::class],
                                ['name' => StringTrim::class],
                            ],
                            'validators' => [
                                [
                                    'name' => NotEmpty::class,
                                    'options' => ['message' => 'Bitte die Rolle wählen'],
                                ],
                                [
                                    'name' => InArray::class,
                                    'options' => [
                                        'haystack' => ['Mandant', 'Guest'],
                                        'message' => 'Bitte eine korrekte Rolle wählen',
                                    ],
                                ],
                            ],
                        ],
                        'active' => [
                            'required' => true,
                            'filters' => [
                                ['name' => StripTags::class],
                                ['name' => StringTrim::class],
                            ],
                            'validators' => [
                                [
                                    'name' => NotEmpty::class,
                                    'options' => ['message' => 'Bitte wählen, ob der Mandant aktiviert werden soll oder nicht'],
                                ],
                            ],
                        ],
                    ],
                ],
                'attributes' => [
                    'aria-label' => 'allgemeine Informationen',
                    'class' => 'form border p-3 g-0',
                ],
                'elements' => [
                    [
                        'spec' => [
                            'type' => Text::class,
                            'name' => 'name',
                            'options' => [
                                'label' => 'Mandant',
                                'floating' => true,
                            ],
                            'attributes' => [
                                'id' => 'name',
                                'class' => 'form-control form-control-input form-control-short',
                                'autocomplete' => 'off',
                                'aria-label' => 'Mandant',
                                'placeholder' => ' ',
                            ],
                        ],
                    ],
                    [
                        'spec' => [
                            'type' => Text::class,
                            'name' => 'mandantor',
                            'options' => [
                                'label' => 'Mandantenname',
                                'floating' => true,
                            ],
                            'attributes' => [
                                'id' => 'mandantor',
                                'class' => 'form-control form-control-input form-control-short',
                                'autocomplete' => 'off',
                                'aria-label' => 'Mandantenname',
                                'placeholder' => ' ',
                            ],
                        ],
                    ],
                    [
                        'spec' => [
                            'type' => Text::class,
                            'name' => 'theme',
                            'options' => [
                                'label' => 'Theme',
                                'floating' => true,
                            ],
                            'attributes' => [
                                'id' => 'theme',
                                'class' => 'form-control form-control-input form-control-short',
                                'autocomplete' => 'off',
                                'aria-label' => 'Theme',
                                'placeholder' => ' ',
                            ],
                        ],
                    ],
                    [
                        'spec' => [
                            'type' => Select::class,
                            'name' => 'locale',
                            'options' => [
                                'label' => 'Sprach-Code',
                                'value_options' => ['de_DE' => 'deutsch'],
                                'floating' => true,
                            ],
                            'attributes' => [
                                'id' => 'locale',
                                'class' => 'form-control form-control-select',
                                'autocomplete' => 'off',
                                'aria-label' => 'Sprach-Code',
                            ],
                        ],
                    ],
                    [
                        'spec' => [
                            'type' => Select::class,
                            'name' => 'translationDomain',
                            'options' => [
                                'label' => 'Sprach-Version',
                                'value_options' => ['default' => 'Sie-Form', 'casually' => 'Du-Form'],
                                'floating' => true,
                            ],
                            'attributes' => [
                                'id' => 'translationDomain',
                                'class' => 'form-control form-control-select',
                                'autocomplete' => 'off',
                                'aria-label' => 'Sprach-Version',
                            ],
                        ],
                    ],
                    [
                        'spec' => [
                            'type' => Select::class,
                            'name' => 'role',
                            'options' => [
                                'label' => 'Rolle des Mandanten?',
                                'value_options' => ['Mandant' => 'Mandant', 'Guest' => 'Guest'],
                                'floating' => true,
                            ],
                            'attributes' => [
                                'id' => 'role',
                                'class' => 'form-control form-control-select',
                                'autocomplete' => 'off',
                                'aria-label' => 'Rolle des Mandanten?',
                            ],
                        ],
                    ],
                    [
                        'spec' => [
                            'type' => Select::class,
                            'name' => 'active',
                            'options' => [
                                'label' => 'Mandant ist aktiv?',
                                'value_options' => ['Y' => 'Ja', 'N' => 'Nein'],
                                'floating' => true,
                            ],
                            'attributes' => [
                                'id' => 'active',
                                'class' => 'form-control form-control-select',
                                'autocomplete' => 'off',
                                'aria-label' => 'Mandant ist aktiv?',
                            ],
                        ],
                    ],
                ],
            ],
            'flags' => ['priority' => 5],
        ],
        [
            'spec' => [
                'type' => InputFilterProviderFieldset::class,
                'name' => 'vt',
                'options' => [
                    'label' => 'Optionen für VTools',
                    // 'allow_remove' => false,
                    'label_attributes' => ['class' => 'w-auto px-2'],
                    'input_filter_spec' => [
                        'rente' => [
                            'required' => true,
                            'filters' => [
                                ['name' => StripTags::class],
                                ['name' => StringTrim::class],
                            ],
                            'validators' => [
                                [
                                    'name' => NotEmpty::class,
                                    'options' => ['message' => 'Bitte wählen, ob die Rentenlücke für den Mandanten aktiviert werden soll oder nicht'],
                                ],
                            ],
                        ],
                        'pflege' => [
                            'required' => true,
                            'filters' => [
                                ['name' => StripTags::class],
                                ['name' => StringTrim::class],
                            ],
                            'validators' => [
                                [
                                    'name' => NotEmpty::class,
                                    'options' => ['message' => 'Bitte wählen, ob die Pflegelücke für den Mandanten aktiviert werden soll oder nicht'],
                                ],
                            ],
                        ],
                        'bu' => [
                            'required' => true,
                            'filters' => [
                                ['name' => StripTags::class],
                                ['name' => StringTrim::class],
                            ],
                            'validators' => [
                                [
                                    'name' => NotEmpty::class,
                                    'options' => ['message' => 'Bitte wählen, ob die BU-Lücke für den Mandanten aktiviert werden soll oder nicht'],
                                ],
                            ],
                        ],
                        'gkv' => [
                            'required' => true,
                            'filters' => [
                                ['name' => StripTags::class],
                                ['name' => StringTrim::class],
                            ],
                            'validators' => [
                                [
                                    'name' => NotEmpty::class,
                                    'options' => ['message' => 'Bitte wählen, ob der GKV für den Mandanten aktiviert werden soll oder nicht'],
                                ],
                            ],
                        ],
                    ],
                ],
                'attributes' => [
                    'aria-label' => 'Optionen für VTools',
                    'class' => 'form border p-3 g-0',
                ],
                'elements' => [
                    [
                        'spec' => [
                            'type' => Select::class,
                            'name' => 'rente',
                            'options' => [
                                'label' => 'Ist die Rentenlücke für den Mandanten aktiviert?',
                                'value_options' => ['Y' => 'Ja', 'N' => 'Nein'],
                                'floating' => true,
                            ],
                            'attributes' => [
                                'id' => 'rente',
                                'class' => 'form-control form-control-select',
                                'autocomplete' => 'off',
                                'aria-label' => 'Ist die Rentenlücke für den Mandanten aktiviert?',
                            ],
                        ],
                    ],
                    [
                        'spec' => [
                            'type' => Select::class,
                            'name' => 'pflege',
                            'options' => [
                                'label' => 'Ist die Pflegelücke für den Mandanten aktiviert?',
                                'value_options' => ['Y' => 'Ja', 'N' => 'Nein'],
                                'floating' => true,
                            ],
                            'attributes' => [
                                'id' => 'pflege',
                                'class' => 'form-control form-control-select',
                                'autocomplete' => 'off',
                                'aria-label' => 'Ist die Pflegelücke für den Mandanten aktiviert?',
                            ],
                        ],
                    ],
                    [
                        'spec' => [
                            'type' => Select::class,
                            'name' => 'bu',
                            'options' => [
                                'label' => 'Ist die BU-Lücke für den Mandanten aktiviert?',
                                'value_options' => ['Y' => 'Ja', 'N' => 'Nein'],
                                'floating' => true,
                            ],
                            'attributes' => [
                                'id' => 'bu',
                                'class' => 'form-control form-control-select',
                                'autocomplete' => 'off',
                                'aria-label' => 'Ist die BU-Lücke für den Mandanten aktiviert?',
                            ],
                        ],
                    ],
                    [
                        'spec' => [
                            'type' => Select::class,
                            'name' => 'gkv',
                            'options' => [
                                'label' => 'Ist der GKV für den Mandanten aktiviert?',
                                'value_options' => ['Y' => 'Ja', 'N' => 'Nein'],
                                'floating' => true,
                            ],
                            'attributes' => [
                                'id' => 'gkv',
                                'class' => 'form-control form-control-select',
                                'autocomplete' => 'off',
                                'aria-label' => 'Ist der GKV für den Mandanten aktiviert?',
                            ],
                        ],
                    ],
                ],
            ],
            'flags' => ['priority' => 4],
        ],
        [
            'spec' => [
                'type' => InputFilterProviderFieldset::class,
                'name' => 'atb',
                'options' => [
                    'label' => 'Optionen für Alttarifbewertung',
                    // 'allow_remove' => false,
                    'label_attributes' => ['class' => 'w-auto px-2'],
                    'input_filter_spec' => [
                        'atb' => [
                            'required' => true,
                            'filters' => [
                                ['name' => StripTags::class],
                                ['name' => StringTrim::class],
                            ],
                            'validators' => [
                                [
                                    'name' => NotEmpty::class,
                                    'options' => ['message' => 'Bitte wählen, ob die Alttarifbewertung für den Mandanten aktiviert werden soll oder nicht'],
                                ],
                            ],
                        ],
                        'showInfo' => [
                            'required' => true,
                            'filters' => [
                                ['name' => StripTags::class],
                                ['name' => StringTrim::class],
                            ],
                            'validators' => [
                                [
                                    'name' => NotEmpty::class,
                                    'options' => ['message' => 'Bitte wählen, ob die Info für den Mandanten angezeigt werden soll oder nicht'],
                                ],
                            ],
                        ],
                        'showComparison' => [
                            'required' => true,
                            'filters' => [
                                ['name' => StripTags::class],
                                ['name' => StringTrim::class],
                            ],
                            'validators' => [
                                [
                                    'name' => NotEmpty::class,
                                    'options' => ['message' => 'Bitte wählen, ob der Vergleich für den Mandanten angezeigt werden soll oder nicht'],
                                ],
                            ],
                        ],
                        'useCheckitProfile' => [
                            'required' => true,
                            'filters' => [
                                ['name' => StripTags::class],
                                ['name' => StringTrim::class],
                            ],
                            'validators' => [
                                [
                                    'name' => NotEmpty::class,
                                    'options' => ['message' => 'Bitte wählen, ob das "checkit" Profil für den Mandanten genutzt werden soll oder nicht'],
                                ],
                            ],
                        ],
                    ],
                ],
                'attributes' => [
                    'aria-label' => 'Optionen für Alttarifbewertung',
                    'class' => 'form border p-3 g-0',
                ],
                'elements' => [
                    [
                        'spec' => [
                            'type' => Select::class,
                            'name' => 'atb',
                            'options' => [
                                'label' => 'Ist die Alttarifbewertung für den Mandanten aktiviert?',
                                'value_options' => ['Y' => 'Ja', 'N' => 'Nein'],
                                'floating' => true,
                            ],
                            'attributes' => [
                                'id' => 'atb',
                                'class' => 'form-control form-control-select',
                                'autocomplete' => 'off',
                                'aria-label' => 'Ist die Alttarifbewertung für den Mandanten aktiviert?',
                            ],
                        ],
                    ],
                    [
                        'spec' => [
                            'type' => Select::class,
                            'name' => 'showInfo',
                            'options' => [
                                'label' => 'Info anzeigen?',
                                'value_options' => ['Y' => 'Ja', 'N' => 'Nein'],
                                'floating' => true,
                            ],
                            'attributes' => [
                                'id' => 'showInfo',
                                'class' => 'form-control form-control-select',
                                'autocomplete' => 'off',
                                'aria-label' => 'Info anzeigen?',
                            ],
                        ],
                    ],
                    [
                        'spec' => [
                            'type' => Select::class,
                            'name' => 'showComparison',
                            'options' => [
                                'label' => 'Vergleich anzeigen?',
                                'value_options' => ['Y' => 'Ja', 'N' => 'Nein'],
                                'floating' => true,
                            ],
                            'attributes' => [
                                'id' => 'showComparison',
                                'class' => 'form-control form-control-select',
                                'autocomplete' => 'off',
                                'aria-label' => 'Vergleich anzeigen?',
                            ],
                        ],
                    ],
                    [
                        'spec' => [
                            'type' => Select::class,
                            'name' => 'useCheckitProfile',
                            'options' => [
                                'label' => 'Soll das "checkit" Profil genutzt werden?',
                                'value_options' => ['Y' => 'Ja', 'N' => 'Nein'],
                                'floating' => true,
                            ],
                            'attributes' => [
                                'id' => 'useCheckitProfile',
                                'class' => 'form-control form-control-select',
                                'autocomplete' => 'off',
                                'aria-label' => 'Soll das "checkit" Profil genutzt werden?',
                            ],
                        ],
                    ],
                ],
            ],
            'flags' => ['priority' => 3],
        ],
        [
            'spec' => [
                'type' => InputFilterProviderFieldset::class,
                'name' => 'ks',
                'options' => [
                    'label' => 'Optionen für Kontoservice',
                    // 'allow_remove' => false,
                    'label_attributes' => ['class' => 'w-auto px-2'],
                    'input_filter_spec' => [
                        'ks' => [
                            'required' => true,
                            'filters' => [
                                ['name' => StripTags::class],
                                ['name' => StringTrim::class],
                            ],
                            'validators' => [
                                [
                                    'name' => NotEmpty::class,
                                    'options' => ['message' => 'Bitte wählen, ob der Kontoservice für den Mandanten aktiviert werden soll oder nicht'],
                                ],
                            ],
                        ],
                        'limit' => [
                            'required' => true,
                            'filters' => [
                                ['name' => StripTags::class],
                                ['name' => StringTrim::class],
                            ],
                            'validators' => [
                                [
                                    'name' => Digits::class,
                                    'options' => ['message' => 'Bitte geben Sie nur Zahlen ein'],
                                ],
                            ],
                        ],
                    ],
                ],
                'attributes' => [
                    'aria-label' => 'Optionen für Kontoservice',
                    'class' => 'form border p-3 g-0',
                ],
                'elements' => [
                    [
                        'spec' => [
                            'type' => Select::class,
                            'name' => 'ks',
                            'options' => [
                                'label' => 'Ist der Kontoservice für den Mandanten aktiviert?',
                                'value_options' => ['Y' => 'Ja', 'N' => 'Nein'],
                                'floating' => true,
                            ],
                            'attributes' => [
                                'id' => 'ks',
                                'class' => 'form-control form-control-select',
                                'autocomplete' => 'off',
                                'aria-label' => 'Ist der Kontoservice für den Mandanten aktiviert?',
                            ],
                        ],
                    ],
                    [
                        'spec' => [
                            'type' => Number::class,
                            'name' => 'limit',
                            'options' => [
                                'label' => 'Wie oft kann an einem Tag ein Nutzer Daten abfragen?',
                                'floating' => true,
                            ],
                            'attributes' => [
                                'id' => 'limit',
                                'class' => 'form-control',
                                'autocomplete' => 'off',
                                'aria-label' => 'Wie oft kann an einem Tag ein Nutzer Daten abfragen?',
                                'min' => 0,
                                'max' => 10,
                                'placeholder' => ' ',
                            ],
                        ],
                    ],
                    [
                        'spec' => [
                            'type' => InputFilterProviderFieldset::class,
                            'name' => 'tw',
                            'options' => [
                                'label' => 'Optionen für Tarifwechsel',
                                // 'allow_remove' => false,
                                'label_attributes' => ['class' => 'w-auto px-2'],
                                'input_filter_spec' => [
                                    'tw' => [
                                        'required' => true,
                                        'filters' => [
                                            ['name' => StripTags::class],
                                            ['name' => StringTrim::class],
                                        ],
                                        'validators' => [
                                            [
                                                'name' => NotEmpty::class,
                                                'options' => ['message' => 'Bitte wählen, ob der Tarifwechsel für den Mandanten aktiviert werden soll oder nicht'],
                                            ],
                                        ],
                                    ],
                                    'agbLink' => [
                                        'required' => false,
                                        'filters' => [
                                            ['name' => StripTags::class],
                                            ['name' => StringTrim::class],
                                        ],
                                        'validators' => [
                                            [
                                                'name' => Uri::class,
                                                'options' => ['message' => 'Bitte geben Sie eine gültige Url an'],
                                            ],
                                        ],
                                    ],
                                    'dsLink' => [
                                        'required' => false,
                                        'filters' => [
                                            ['name' => StripTags::class],
                                            ['name' => StringTrim::class],
                                        ],
                                        'validators' => [
                                            [
                                                'name' => Uri::class,
                                                'options' => ['message' => 'Bitte geben Sie eine gültige Url an'],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                            'attributes' => [
                                'aria-label' => 'Optionen für Tarifwechsel',
                                'class' => 'form border p-3 g-0',
                            ],
                            'elements' => [
                                [
                                    'spec' => [
                                        'type' => Select::class,
                                        'name' => 'tw',
                                        'options' => [
                                            'label' => 'Ist der Tarifwechsel für den Mandanten aktiviert?',
                                            'value_options' => ['Y' => 'Ja', 'N' => 'Nein'],
                                            'floating' => true,
                                        ],
                                        'attributes' => [
                                            'id' => 'tw',
                                            'class' => 'form-control form-control-select',
                                            'autocomplete' => 'off',
                                            'aria-label' => 'Ist der Tarifwechsel für den Mandanten aktiviert?',
                                        ],
                                    ],
                                ],
                                [
                                    'spec' => [
                                        'type' => Url::class,
                                        'name' => 'agbLink',
                                        'options' => [
                                            'label' => 'Link zu den AGB des Mandanten',
                                            'floating' => true,
                                        ],
                                        'attributes' => [
                                            'id' => 'agbLink',
                                            'class' => 'form-control',
                                            'autocomplete' => 'off',
                                            'aria-label' => 'Link zu den AGB des Mandanten',
                                            'placeholder' => ' ',
                                        ],
                                    ],
                                ],
                                [
                                    'spec' => [
                                        'type' => Url::class,
                                        'name' => 'dsLink',
                                        'options' => [
                                            'label' => 'Link zu den Datenschutzbestimmungen des Mandanten',
                                            'floating' => true,
                                        ],
                                        'attributes' => [
                                            'id' => 'dsLink',
                                            'class' => 'form-control',
                                            'autocomplete' => 'off',
                                            'aria-label' => 'Link zu den Datenschutzbestimmungen des Mandanten',
                                            'placeholder' => ' ',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        'flags' => ['priority' => 1],
                    ],
                ],
            ],
            'flags' => ['priority' => 2],
        ],
    ],
    'elements' => [
        [
            'spec' => [
                'type' => Hidden::class,
                'name' => 'id',
                'attributes' => ['id' => 'id'],
            ],
        ],
        [
            'spec' => [
                'type' => Button::class,
                'name' => 'submit',
                'options' => [
                    'label' => 'speichern',
                    'col_attributes' => ['class' => 'row border p-3 g-0'],
                ],
                'attributes' => [
                    'id' => 'submit',
                    'type' => 'submit',
                    'class' => 'btn btn-primary',
                ],
            ],
            'flags' => ['priority' => 0],
        ],
    ],
];
