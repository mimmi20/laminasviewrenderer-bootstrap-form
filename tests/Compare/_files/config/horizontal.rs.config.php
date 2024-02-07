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

use Laminas\Form\Element\Button;
use Laminas\Form\Element\Checkbox;
use Laminas\Form\Element\Date;
use Laminas\Form\Element\Hidden;
use Laminas\Form\Element\Radio;
use Laminas\Form\Element\Select;
use Laminas\Form\Element\Text;
use Laminas\Form\Form;

return [
    'type' => Form::class,
    'options' => [
        'layout' => \Mimmi20\LaminasView\BootstrapForm\Form::LAYOUT_HORIZONTAL,
        'form-required-mark' => '<div class="mt-2 text-info-required"><sup>*</sup> Pflichtfeld</div>',
        'field-required-mark' => '<span class="text-info-required"><sup>*</sup></span>',
        'row_attributes' => ['class' => 'my-2'],
        'label_col_attributes' => ['class' => 'col-2'],
        'col_attributes' => ['class' => 'col-8'],
        'help_attributes' => ['class' => 'col-2 help-content'],
    ],
    'attributes' => [
        'method' => 'post',
        'class' => 'g-0 needs-validation was-validated',
        'accept-charset' => 'utf-8',
        'novalidate' => 'novalidate',
        'data-needs-validation' => true,
        'id' => 'rs-form',
    ],
    'elements' => [
        [
            'spec' => [
                'type' => Checkbox::class,
                'name' => 'tarif_privat',
                'options' => [
                    'label' => 'Privater Bereich',
                    'use_hidden_element' => true,
                    'checked_value' => '1',
                    'unchecked_value' => '0',
                    'help_content' => ['Help-Content'],
                    'as-card' => true,
                ],
                'attributes' => [
                    'id' => 'tarif_privat',
                    'class' => ' area-selector js-tarif-privat',
                    'data-toggle' => 'collapse',
                    'data-target' => '.collapse-more-questions',
                    'aria-expanded' => 'true',
                    'aria-controls' => 'collapse-unterhalt collapse-spezialstraf',
                    'role' => 'button',
                ],
            ],
        ],
        [
            'spec' => [
                'type' => Checkbox::class,
                'name' => 'tarif_beruf',
                'options' => [
                    'label' => 'Arbeit & Beruf',
                    'use_hidden_element' => true,
                    'checked_value' => '1',
                    'unchecked_value' => '0',
                    'help_content' => ['content' => ''],
                    'as-card' => true,
                ],
                'attributes' => [
                    'id' => 'tarif_beruf',
                    'class' => ' area-selector js-tarif-beruf',
                ],
            ],
        ],
        [
            'spec' => [
                'type' => Checkbox::class,
                'name' => 'tarif_verkehr_familie',
                'options' => [
                    'label' => 'Verkehr Familie (für alle KFZ)',
                    'use_hidden_element' => true,
                    'checked_value' => '1',
                    'unchecked_value' => '0',
                    'help_content' => ['content' => 'Help-Content'],
                    'as-card' => true,
                ],
                'attributes' => [
                    'id' => 'tarif_verkehr_familie',
                    'class' => ' area-selector js-tarif-verkehr-familie',
                ],
            ],
        ],
        [
            'spec' => [
                'type' => Checkbox::class,
                'name' => 'tarif_miete',
                'options' => [
                    'label' => 'Eigentum & Mieter',
                    'use_hidden_element' => true,
                    'checked_value' => '1',
                    'unchecked_value' => '0',
                    'help_content' => ['content' => 'Help-Content', 'header' => 'Help Title'],
                    'as-card' => true,
                ],
                'attributes' => [
                    'id' => 'tarif_miete',
                    'class' => ' area-selector js-tarif-miete',
                    'data-toggle' => 'collapse',
                    'aria-expanded' => 'false',
                    'role' => 'button',
                ],
            ],
        ],
        [
            'spec' => [
                'type' => Checkbox::class,
                'name' => 'tarif_verkehr',
                'options' => [
                    'label' => 'Verkehr nur für den VN',
                    'use_hidden_element' => true,
                    'checked_value' => '1',
                    'unchecked_value' => '0',
                    'help_content' => 'Help-Content',
                    'as-card' => true,
                ],
                'attributes' => [
                    'id' => 'tarif_verkehr',
                    'class' => ' area-selector js-tarif-verkehr',
                ],
            ],
        ],
        [
            'spec' => [
                'type' => Select::class,
                'name' => 'vermiet',
                'options' => [
                    'label' => 'Vermietete Wohneinheiten',
                    'help_content' => 'Help-Content',

                    'value_options' => [
                        'nein' => 'nein',
                        '1' => '1 vermietete WE',
                        '2' => '2 vermietete WEs',
                        '3' => '3 vermietete WEs',
                        '4' => '4 vermietete WEs',
                        '5' => '5 vermietete WEs',
                        '6' => '6 vermietete WEs',
                    ],
                ],
                'attributes' => ['id' => 'vermiet'],
            ],
        ],
        [
            'spec' => [
                'type' => Text::class,
                'name' => 'OB1',
                'options' => [
                    'label' => 'WE 1 Jahresbruttomiete EUR',
                    'help_content' => 'Help-Content',
                ],
                'attributes' => [
                    'id' => 'OB1',

                    'required' => 'required',
                    'title' => 'Bitte geben Sie die Jahresmiete an!',
                    'pattern' => '^\d{1,}$',
                ],
            ],
        ],
        [
            'spec' => [
                'type' => Text::class,
                'name' => 'OB2',
                'options' => [
                    'label' => 'WE 2 Jahresbruttomiete EUR',
                    'help_content' => 'Help-Content',
                ],
                'attributes' => [
                    'id' => 'OB2',

                    'required' => 'required',
                    'title' => 'Bitte geben Sie die Jahresmiete an!',
                    'pattern' => '^\d{1,}$',
                ],
            ],
        ],
        [
            'spec' => [
                'type' => Text::class,
                'name' => 'OB3',
                'options' => [
                    'label' => 'WE 3 Jahresbruttomiete EUR',
                    'help_content' => 'Help-Content',
                ],
                'attributes' => [
                    'id' => 'OB3',

                    'required' => 'required',
                    'title' => 'Bitte geben Sie die Jahresmiete an!',
                    'pattern' => '^\d{1,}$',
                ],
            ],
        ],
        [
            'spec' => [
                'type' => Text::class,
                'name' => 'OB4',
                'options' => [
                    'label' => 'WE 4 Jahresbruttomiete EUR',
                    'help_content' => 'Help-Content',
                ],
                'attributes' => [
                    'id' => 'OB4',

                    'required' => 'required',
                    'title' => 'Bitte geben Sie die Jahresmiete an!',
                    'pattern' => '^\d{1,}$',
                ],
            ],
        ],
        [
            'spec' => [
                'type' => Text::class,
                'name' => 'OB5',
                'options' => [
                    'label' => 'WE 5 Jahresbruttomiete EUR',
                    'help_content' => 'Help-Content',
                ],
                'attributes' => [
                    'id' => 'OB5',

                    'required' => 'required',
                    'title' => 'Bitte geben Sie die Jahresmiete an!',
                    'pattern' => '^\d{1,}$',
                ],
            ],
        ],
        [
            'spec' => [
                'type' => Text::class,
                'name' => 'OB6',
                'options' => [
                    'label' => 'WE 6 Jahresbruttomiete EUR',
                    'help_content' => 'Help-Content',
                ],
                'attributes' => [
                    'id' => 'OB6',

                    'required' => 'required',
                    'title' => 'Bitte geben Sie die Jahresmiete an!',
                    'pattern' => '^\d{1,}$',
                ],
            ],
        ],
        [
            'spec' => [
                'type' => Select::class,
                'name' => 'cyber',
                'options' => [
                    'label' => 'Erweiterter Internet-Schutz',
                    'help_content' => 'Help-Content',

                    'value_options' => [
                        'nein' => 'nein',
                        'ja' => 'ja',
                    ],
                ],
                'attributes' => ['id' => 'cyber'],
            ],
        ],
        [
            'spec' => [
                'type' => Text::class,
                'name' => 'plz',
                'options' => [
                    'label' => 'PLZ des Antragsstellers',
                    'help_content' => 'Help-Content',
                ],
                'attributes' => [
                    'id' => 'plz',
                    'class' => ' -short',
                    'placeholder' => ' ',
                ],
            ],
        ],
        [
            'spec' => [
                'type' => Select::class,
                'name' => 'famstand',
                'options' => [
                    'label' => 'Familiäre Verhältnisse',
                    'help_content' => 'Help-Content',

                    'value_options' => [
                        'Familie' => 'Familie',
                        'Paar' => 'Paar',
                        'Single' => 'Single',
                        'Alleinerziehend' => 'Alleinerziehend',
                    ],
                ],
                'attributes' => ['id' => 'famstand'],
            ],
        ],
        [
            'spec' => [
                'type' => Date::class,
                'name' => 'gebdatum',
                'options' => [
                    'label' => 'Geburtsdatum des Versicherungsnehmers',
                    'help_content' => 'Help-Content',
                    'messages' => [
                        [
                            'attributes' => ['class' => 'invalid-feedback'],
                            'content' => 'nicht in Ordnung',
                        ],
                        [
                            'attributes' => ['class' => 'invalid-max-feedback'],
                            'content' => 'zu jung',
                        ],
                        [
                            'attributes' => ['class' => 'valid-feedback'],
                            'content' => 'in Ordnung',
                        ],
                    ],
                    'error-class' => 'form-error',
                ],
                'attributes' => [
                    'id' => 'gebdatum',
                    'placeholder' => 'TT.MM.JJJJ',

                    'autocomplete' => 'off',
                    'max' => '2006-01-02',
                ],
            ],
        ],
        [
            'spec' => [
                'type' => Text::class,
                'name' => 'geburtsdatumPartner',
                'options' => [
                    'label' => 'Geburtsdatum des Ehe- oder Lebenspartners',
                    'help_content' => 'Help-Content',
                    'was-validated' => true,
                    'valid-class' => 'form-success',
                ],
                'attributes' => [
                    'id' => 'geburtsdatumPartner',
                    'class' => ' datepicker js-datepicker',
                    'placeholder' => 'TT.MM.JJJJ',
                    'required' => 'required',
                    'data-date-format' => 'de',
                    'data-date-format-message' => 'Bitte geben Sie ein korrektes Geburtsdatum an!',
                    'data-min-age' => '18y',
                    'data-min-age-message' => 'Sie sind leider zu jung, um eine Versicherung abzuschließen.',
                ],
            ],
        ],
        [
            'spec' => [
                'type' => Select::class,
                'name' => 'anag',
                'options' => [
                    'label' => 'Aktuelle Tätigkeit',
                    'help_content' => 'Help-Content',

                    'value_options' => [
                        'Arbeitnehmer' => 'Arbeitnehmer',
                        'ohne berufliche Tätigkeit' => 'ohne berufliche Tätigkeit',
                        'öffentl. Dienst' => 'öffentlicher Dienst',
                        'Selbständig' => 'Selbständig',
                        'auf Dauer nicht mehr erwerbstätig' => 'auf Dauer nicht mehr erwerbstätig',
                        'Azubi/Student' => 'Azubi/Student',
                    ],
                    'was-validated' => true,
                ],
                'attributes' => [
                    'id' => 'anag',
                    'class' => 'toggle-trigger',
                    'data-toggle-modus' => 'show',
                    'data-toggle-value' => 'Selbständig',
                ],
            ],
        ],
        [
            'spec' => [
                'type' => Select::class,
                'name' => 'umsatzselbst',
                'options' => [
                    'label' => 'Jahresumsatz',
                    'help_content' => 'Help-Content',

                    'value_options' => [
                        '10000' => '1 - 10.000 EUR',
                        '15000' => '10.001 - 15.000 EUR',
                        '20000' => '15.001 - 20.000 EUR',
                        '50000' => '20.001 - 50.000 EUR',
                        '9999999' => 'ab 50.001 EUR',
                    ],
                    'was-validated' => true,
                ],
                'attributes' => ['id' => 'umsatzselbst'],
            ],
        ],
        'laufzeit' => [
            'spec' => [
                'type' => Select::class,
                'name' => 'laufzeit',
                'options' => [
                    'label' => 'Laufzeit',
                    'help_content' => 'Help-Content',

                    'value_options' => [
                        '1' => '1 Jahr',
                        '3' => '3 Jahre',
                    ],
                ],
                'attributes' => ['id' => 'laufzeit'],
            ],
        ],
        [
            'spec' => [
                'type' => Select::class,
                'name' => 'vorvers5',
                'options' => [
                    'label' => 'Wie lange bestehen oder bestanden für den Antragsteller und/oder den mitversicherten Lebenspartner Vorversicherungen?',
                    'help_content' => 'Help-Content',

                    'value_options' => [
                        '' => '-- Bitte wählen --',
                        'keine Vorversicherung' => 'keine Vorversicherung',
                        'weniger als 2 Jahre' => 'weniger als 2 Jahre',
                        'mind. 2 Jahre' => 'mind. 2 Jahre',
                        'mind. 3 Jahre' => 'mind. 3 Jahre',
                        'mind. 4 Jahre' => 'mind. 4 Jahre',
                        'mind. 5 Jahre' => 'mind. 5 Jahre',
                    ],
                ],
                'attributes' => [
                    'id' => 'vorvers5',

                    'required' => 'required',
                    'title' => 'Bitte geben Sie die Information zur Vorversicherung an!',
                ],
            ],
        ],
        [
            'spec' => [
                'type' => Select::class,
                'name' => 'wannschaden',
                'options' => [
                    'label' => 'Wann wurde der letzte Schaden gemeldet?',
                    'help_content' => 'Help-Content',

                    'value_options' => [
                        '' => '-- Bitte wählen --',
                        'vor mehr als 5 Jahren oder schadenfrei' => 'vor mehr als 5 Jahren oder schadenfrei',
                        'innerhalb der letzten 2 Jahre' => 'innerhalb der letzten 2 Jahre',
                        'vor mehr als 2 Jahren' => 'vor mehr als 2 Jahren',
                        'vor mehr als 3 Jahren' => 'vor mehr als 3 Jahren',
                        'vor mehr als 4 Jahren' => 'vor mehr als 4 Jahren',
                    ],
                ],
                'attributes' => [
                    'id' => 'wannschaden',

                    'required' => 'required',
                    'title' => 'Bitte geben Sie an, wann der letzte Schaden gemeldet wurde!',
                ],
            ],
        ],
        [
            'spec' => [
                'type' => Select::class,
                'name' => 'kombirabatte',
                'options' => [
                    'label' => 'Kombirabatte mit berechnen?',
                    'help_content' => '<strong>Kombirabatte mit berechnen?</strong><p>Welche Verträge haben Sie schon oder haben vor, sie zu versichern? Je mehr Verträge Sie bei einer Gesellschaft haben, umso günstiger wird der Preis.</p>',
                    'value_options' => [
                        [
                            'label' => 'nein',
                            'value' => 'nein',
                            'attributes' => ['id' => 'kr_n'],
                        ],
                        [
                            'label' => 'ja',
                            'value' => 'ja',
                            'attributes' => ['id' => 'kr_j'],
                        ],
                    ],

                    'col_attributes' => ['data-toogle' => '1'],
                ],
                'attributes' => ['id' => 'kombirabatte'],
            ],
        ],
        [
            'spec' => [
                'type' => Checkbox::class,
                'name' => 'KrPHV',
                'options' => [
                    'label' => 'Privathaftpflicht',
                    'help_content' => 'Help-Content',

                    'use_hidden_element' => false,
                    'checked_value' => '1',
                    'unchecked_value' => '0',
                    'messages' => [
                        [
                            'attributes' => ['class' => 'invalid-feedback'],
                            'content' => 'nicht in Ordnung',
                        ],
                        [
                            'attributes' => ['class' => 'invalid-max-feedback'],
                            'content' => 'zu jung',
                        ],
                        [
                            'attributes' => ['class' => 'valid-feedback'],
                            'content' => 'in Ordnung',
                        ],
                    ],
                    'as-card' => true,
                ],
                'attributes' => [
                    'id' => 'KrPHV',
                    'class' => 'form-check-input',
                ],
            ],
        ],
        [
            'spec' => [
                'type' => Checkbox::class,
                'name' => 'KrTIE',
                'options' => [
                    'label' => 'Tierhalterhaftpflicht',
                    'help_content' => 'Help-Content',

                    'use_hidden_element' => false,
                    'checked_value' => '1',
                    'unchecked_value' => '0',
                    'as-card' => true,
                ],
                'attributes' => [
                    'id' => 'KrTIE',
                    'class' => 'form-check-input',
                ],
            ],
        ],
        [
            'spec' => [
                'type' => Checkbox::class,
                'name' => 'KrHUG',
                'options' => [
                    'label' => 'Haus-Grundbesitzer Haftpflicht',
                    'help_content' => 'Help-Content',

                    'use_hidden_element' => false,
                    'checked_value' => '1',
                    'unchecked_value' => '0',
                    'as-card' => true,
                ],
                'attributes' => [
                    'id' => 'KrHUG',
                    'class' => 'form-check-input',
                ],
            ],
        ],
        [
            'spec' => [
                'type' => Checkbox::class,
                'name' => 'KrOEL',
                'options' => [
                    'label' => 'Gewässerschaden/Öltank',
                    'help_content' => 'Help-Content',

                    'use_hidden_element' => false,
                    'checked_value' => '1',
                    'unchecked_value' => '0',
                    'as-card' => true,
                ],
                'attributes' => [
                    'id' => 'KrOEL',
                    'class' => 'form-check-input',
                ],
            ],
        ],
        [
            'spec' => [
                'type' => Checkbox::class,
                'name' => 'KrWG',
                'options' => [
                    'label' => 'Wohngebäude',
                    'help_content' => 'Help-Content',

                    'use_hidden_element' => false,
                    'checked_value' => '1',
                    'unchecked_value' => '0',
                    'as-card' => true,
                ],
                'attributes' => [
                    'id' => 'KrWG',
                    'class' => 'form-check-input',
                ],
            ],
        ],
        [
            'spec' => [
                'type' => Checkbox::class,
                'name' => 'KrWGGLS',
                'options' => [
                    'label' => 'Wohngebäude-Glas',
                    'help_content' => 'Help-Content',

                    'use_hidden_element' => false,
                    'checked_value' => '1',
                    'unchecked_value' => '0',
                    'as-card' => true,
                ],
                'attributes' => [
                    'id' => 'KrWGGLS',
                    'class' => 'form-check-input',
                ],
            ],
        ],
        [
            'spec' => [
                'type' => Checkbox::class,
                'name' => 'KrHR',
                'options' => [
                    'label' => 'Hausrat',
                    'help_content' => 'Help-Content',

                    'use_hidden_element' => false,
                    'checked_value' => '1',
                    'unchecked_value' => '0',
                    'as-card' => true,
                ],
                'attributes' => [
                    'id' => 'KrHR',
                    'class' => 'form-check-input',
                ],
            ],
        ],
        [
            'spec' => [
                'type' => Checkbox::class,
                'name' => 'KrHRGLS',
                'options' => [
                    'label' => 'Hausrat-Glas',
                    'help_content' => 'Help-Content',

                    'use_hidden_element' => false,
                    'checked_value' => '1',
                    'unchecked_value' => '0',
                    'as-card' => true,
                ],
                'attributes' => [
                    'id' => 'KrHRGLS',
                    'class' => 'form-check-input',
                ],
            ],
        ],
        [
            'spec' => [
                'type' => Checkbox::class,
                'name' => 'KrUNF',
                'options' => [
                    'label' => 'Unfall',
                    'help_content' => 'Help-Content',

                    'use_hidden_element' => false,
                    'checked_value' => '1',
                    'unchecked_value' => '0',
                    'as-card' => true,
                ],
                'attributes' => [
                    'id' => 'KrUNF',
                    'class' => 'form-check-input',
                ],
            ],
        ],
        [
            'spec' => [
                'type' => Checkbox::class,
                'name' => 'KrRS',
                'options' => [
                    'label' => 'Rechtsschutz',
                    'help_content' => 'Help-Content',

                    'use_hidden_element' => false,
                    'checked_value' => '1',
                    'unchecked_value' => '0',
                    'messages' => 42,
                    'as-card' => true,
                ],
                'attributes' => [
                    'id' => 'KrRS',
                    'class' => 'form-check-input',
                ],
            ],
        ],
        [
            'flags' => ['priority' => 17],
            'spec' => [
                'type' => Radio::class,
                'name' => 'zusatzfragen',
                'options' => [
                    'label' => 'weitere Fragen',
                    'value_options' => [
                        'nein' => [
                            'value' => 'nein',
                            'label' => 'Ich verzichte auf die Beantwortung weiterer Fragen und wähle aus dem Vergleich einen Tarif, der meinen Bedarf erfüllt.',
                            'attributes' => ['id' => 'zusatzfragen_nein'],
                        ],
                        'ja' => [
                            'value' => 'ja',
                            'label' => 'Ich möchte weitere Angaben zum gewünschten Versicherungsschutz machen. Es werden dann nur Tarife angezeigt, welche die Vorgaben erfüllen.',
                            'attributes' => ['id' => 'zusatzfragen_ja'],
                        ],
                    ],
                    'col_attributes' => ['data-toogle' => '1'],
                    'help_content' => 'Help-Content',
                    'messages' => [
                        ['content' => ''],
                        [],
                        [
                            'attributes' => ['class' => 'invalid-feedback', 'id' => 'messageInvalidId'],
                            'content' => 'nicht in Ordnung',
                        ],
                        [
                            'attributes' => ['class' => 'invalid-max-feedback'],
                            'content' => 'zu jung',
                        ],
                        [
                            'attributes' => ['class' => 'valid-feedback'],
                            'content' => 'in Ordnung',
                        ],
                    ],
                    'as-card' => true,
                    'use_hidden_element' => true,
                    'unchecked_value' => '0',
                ],
                'attributes' => [
                    'id' => 'zusatzfragen',
                    'aria-describedby' => 'xxx',
                ],
            ],
        ],
        'vs_v' => [
            'spec' => [
                'type' => Select::class,
                'name' => 'vs_v',
                'options' => [
                    'label' => 'Deckungssumme?',
                    'help_content' => 'Help-Content',

                    'value_options' => [
                        'o100000' => [
                            'value' => '100000',
                            'label' => 'weniger als 300.000 EUR',
                        ],

                        'o300000' => [
                            'value' => '300000',
                            'label' => '300.000 EUR (AK Empfehlung)',
                        ],
                        'o500000' => [
                            'value' => '500000',
                            'label' => '500.000 EUR',
                        ],
                        'ounbegrenzt' => [
                            'value' => 'unbegrenzt',
                            'label' => 'unbegrenzt',
                        ],
                    ],
                ],
                'attributes' => ['id' => 'vs_v'],
            ],
        ],
        'kaution_v' => [
            'spec' => [
                'type' => Select::class,
                'name' => 'kaution_v',
                'options' => [
                    'label' => 'Strafkautionen?',
                    'help_content' => 'Help-Content',

                    'value_options' => [
                        'o50000' => [
                            'value' => '50000',
                            'label' => '50.000 EUR',
                        ],
                        'o100000' => [
                            'value' => '100000',
                            'label' => '100.000 EUR (AK Empfehlung)',
                        ],
                        'o100001' => [
                            'value' => '100001',
                            'label' => 'mehr als 100.000 EUR',
                        ],
                    ],
                ],
                'attributes' => ['id' => 'kaution_v'],
            ],
        ],
        [
            'spec' => [
                'type' => Select::class,
                'name' => 'folge_v',
                'options' => [
                    'label' => 'Schadensregulierung nach Folgeereignistheorie',
                    'help_content' => 'Help-Content',

                    'value_options' => [
                        'nein' => 'nein',
                        'ja' => 'ja (AK Empfehlung)',
                    ],
                ],
                'attributes' => ['id' => 'folge_v'],
            ],
        ],
        [
            'spec' => [
                'type' => Select::class,
                'name' => 'ehestreit_v',
                'options' => [
                    'label' => 'Rechtsschutz in Ehesachen (nur wenn verheiratet)',
                    'help_content' => 'Help-Content',

                    'value_options' => [
                        'nein' => 'nein',
                        'beratung' => 'mindestens Beratungsleistung',
                        'aussergerichtlich' => 'mindestens außergerichtlich',
                        'gerichtlich' => 'Übernahme gerichtliche Kosten',
                    ],
                ],
                'attributes' => ['id' => 'ehestreit_v'],
            ],
        ],
        [
            'spec' => [
                'type' => Select::class,
                'name' => 'unterhalt_v',
                'options' => [
                    'label' => 'Rechtsschutz für Unterhalt',
                    'help_content' => 'Help-Content',

                    'value_options' => [
                        'nein' => 'nein',
                        'beratung' => 'mindestens Beratungsleistung',
                        'aussergerichtlich' => 'mindestens außergerichtlich',
                        'gerichtlich' => 'Übernahme gerichtliche Kosten',
                    ],
                ],
                'attributes' => ['id' => 'unterhalt_v'],
            ],
        ],
        [
            'spec' => [
                'type' => Select::class,
                'name' => 'spezialstraf_v',
                'options' => [
                    'label' => 'Einschluss Spezial-Straf-Rechtschutz',
                    'help_content' => 'Help-Content',

                    'value_options' => [
                        'nein' => 'nein',
                        'ja' => 'ja',
                    ],
                ],
                'attributes' => ['id' => 'spezialstraf_v'],
            ],
        ],
        [
            'spec' => [
                'type' => Select::class,
                'name' => 'zahlweise',
                'options' => [
                    'label' => 'Zahlweise',
                    'help_content' => 'Help-Content',
                    'value_options' => [
                        '1' => 'jährlich',
                        '2' => 'halbjährlich',
                        '4' => 'vierteljährlich',
                        '12' => 'monatlich',
                    ],
                ],
                'attributes' => ['id' => 'zahlweise'],
            ],
        ],
        [
            'spec' => [
                'type' => Checkbox::class,
                'name' => 'chkErstinfo',
                'options' => [
                    'label' => 'Ich bestätige, die Erstinformation für Versicherungsmakler gemäß § 15 VersVermV heruntergeladen und gelesen zu haben.',
                    'use_hidden_element' => true,
                    'checked_value' => '1',
                    'unchecked_value' => '0',
                    'help_content' => 'Help-Content',
                    'as-card' => true,
                ],
                'attributes' => [
                    'id' => 'mrmoErstinfo',
                    'class' => 'form-check-input',
                    'required' => 'required',
                ],
            ],
        ],
        [
            'spec' => [
                'type' => Hidden::class,
                'name' => 'selbstMin',
                'options' => ['label' => 'Selbstbeteiligung'],
                'attributes' => ['id' => 'selbstMin'],
            ],
        ],
        [
            'spec' => [
                'type' => Hidden::class,
                'name' => 'selbstMax',
                'options' => ['label' => 'Selbstbeteiligung'],
                'attributes' => ['id' => 'selbstMax'],
            ],
        ],
        [
            'spec' => [
                'type' => Hidden::class,
                'name' => 'sToken',
            ],
        ],
        [
            'spec' => [
                'type' => Button::class,
                'name' => 'btn_berechnen',
                'options' => ['label' => 'Berechnen'],
                'attributes' => [
                    'type' => 'submit',
                    'class' => 'btn btn-primary',
                ],
            ],
        ],
    ],
    'input_filter' => [
        'tarif_privat' => ['required' => false],
        'tarif_beruf' => ['required' => false],
        'tarif_verkehr_familie' => ['required' => false],
        'tarif_miete' => ['required' => false],
        'tarif_verkehr' => ['required' => false],
        'KrPHV' => ['required' => false],
        'KrTIE' => ['required' => false],
        'KrHUG' => ['required' => false],
        'KrOEL' => ['required' => false],
        'KrWG' => ['required' => false],
        'KrWGGLS' => ['required' => false],
        'KrHR' => ['required' => false],
        'KrHRGLS' => ['required' => false],
        'KrUNF' => ['required' => false],
        'KrRS' => ['required' => false],
        'selbst' => ['required' => false],
        'zahlweise' => ['required' => false],
    ],
];
