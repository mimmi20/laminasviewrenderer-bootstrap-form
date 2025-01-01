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

use Laminas\Form\Element\Checkbox;
use Laminas\Form\Element\Hidden;
use Laminas\Form\Element\Radio;
use Laminas\Form\Element\Select;
use Laminas\Form\Element\Submit;
use Laminas\Form\Element\Text;
use Laminas\Form\Form;

use function date;

return [
    'type' => Form::class,
    'options' => [
        'layout' => \Mimmi20\LaminasView\BootstrapForm\Form::LAYOUT_HORIZONTAL,
        'form-required-mark' => '<div class="mt-2 text-info-required">* Pflichtfeld</div>',
        'field-required-mark' => '<span class="text-info-required">*</span>',
        'col_attributes' => ['class' => 'col-sm'],
        'label_attributes' => ['class' => 'col-sm text-sm-right'],
        'help_attributes' => ['class' => 'help-content'],
    ],
    'attributes' => [
        'method' => 'post',
        'accept-charset' => 'utf-8',
        'action' => '/calculator/tier/1/input/u0u4gdbkooufei2q7gh23kr46o?subid=A-00-000',
        'class' => 'form input-form js-help has-help has-preloader js-form-validation-base col-12 js-input-form-init',
        'data-show-arrow' => 'left',
        'id' => 'tier-form',
    ],
    'elements' => [
        [
            'spec' => [
                'type' => Select::class,
                'name' => 'beamte',
                'options' => [
                    'label' => 'Tarifgruppe',
                    'label_attributes' => ['class' => 'col-sm text-sm-right'],
                    'value_options' => [
                        'Normal' => 'Normal',
                        'öffentl. Dienst' => 'öffentlicher Dienst',
                    ],
                ],
                'attributes' => ['id' => 'beamte'],
            ],
        ],
        [
            'spec' => [
                'type' => Text::class,
                'name' => 'gebdatum',
                'options' => [
                    'label' => 'Geburtsdatum',

                    'label_attributes' => ['class' => 'col-sm text-sm-right'],
                ],
                'attributes' => [
                    'id' => 'gebdatum',
                    'class' => 'form-control form-control-input datepicker js-datepicker',
                    'placeholder' => 'TT.MM.JJJJ',
                    'required' => 'required',
                    'data-date-format' => 'de',
                    'data-date-format-message' => 'Bitte geben Sie ein korrektes Geburtsdatum an!',
                    'data-min-age' => '18y',
                    'data-min-age-message' => 'Sie sind leider zu jung, um eine Versicherung abzuschließen.',
                ],
            ],
        ],
        'artdestieres' => [
            'spec' => [
                'type' => Select::class,
                'name' => 'artdestieres',
                'options' => [
                    'label' => 'Art des Tieres',
                    'label_attributes' => ['class' => 'col-sm text-sm-right'],
                    'value_options' => [
                        'oHund' => [
                            'value' => 'Hund',
                            'label' => 'Hund',
                        ],
                        'oPferd' => [
                            'value' => 'Pferd',
                            'label' => 'Pferd',
                        ],
                        'oEsel' => [
                            'value' => 'Esel',
                            'label' => 'Esel',
                        ],
                    ],
                ],
                'attributes' => ['id' => 'artdestieres'],
            ],
        ],
        [
            'spec' => [
                'type' => Select::class,
                'name' => 'anzahldertiere',
                'options' => [
                    'label' => 'Anzahl der Tiere',
                    'label_attributes' => ['class' => 'col-sm text-sm-right'],
                    'value_options' => [
                        '1' => '1',
                        '2' => '2',
                        '3' => '3',
                        '4' => '4',
                        '5' => '5',
                        '6' => '6',
                    ],
                ],
                'attributes' => ['id' => 'anzahldertiere'],
            ],
        ],
        [
            'spec' => [
                'type' => Text::class,
                'name' => 'Rasse_Tier1',
                'options' => [
                    'label' => 'Rasse Hund 1 eingeben',

                    'label_attributes' => ['class' => 'col-sm text-sm-right'],
                ],
                'attributes' => [
                    'id' => 'Rasse_Tier1',
                    'class' => 'form-control form-control-input js-pet-autocomplete',
                    'required' => 'required',
                    'pattern' => '[A-Za-z_äâàÄÂÀöÖüÜßÉÊÈéèêç()\'\s.-]{2,}',
                ],
            ],
        ],
        [
            'spec' => [
                'type' => Text::class,
                'name' => 'Rasse_Tier2',
                'options' => [
                    'label' => 'Rasse Hund 2 eingeben',

                    'label_attributes' => ['class' => 'col-sm text-sm-right'],
                ],
                'attributes' => [
                    'id' => 'Rasse_Tier2',
                    'class' => 'form-control form-control-input js-pet-autocomplete',
                    'required' => 'required',
                    'pattern' => '[A-Za-z_äâàÄÂÀöÖüÜßÉÊÈéèêç()\'\s.-]{2,}',
                ],
            ],
        ],
        [
            'spec' => [
                'type' => Text::class,
                'name' => 'Rasse_Tier3',
                'options' => [
                    'label' => 'Rasse Hund 3 eingeben',

                    'label_attributes' => ['class' => 'col-sm text-sm-right'],
                ],
                'attributes' => [
                    'id' => 'Rasse_Tier3',
                    'class' => 'form-control form-control-input js-pet-autocomplete',
                    'required' => 'required',
                    'pattern' => '[A-Za-z_äâàÄÂÀöÖüÜßÉÊÈéèêç()\'\s.-]{2,}',
                ],
            ],
        ],
        [
            'spec' => [
                'type' => Text::class,
                'name' => 'Rasse_Tier4',
                'options' => [
                    'label' => 'Rasse Hund 4 eingeben',

                    'label_attributes' => ['class' => 'col-sm text-sm-right'],
                ],
                'attributes' => [
                    'id' => 'Rasse_Tier4',
                    'class' => 'form-control form-control-input js-pet-autocomplete',
                    'required' => 'required',
                    'pattern' => '[A-Za-z_äâàÄÂÀöÖüÜßÉÊÈéèêç()\'\s.-]{2,}',
                ],
            ],
        ],
        [
            'spec' => [
                'type' => Text::class,
                'name' => 'Rasse_Tier5',
                'options' => [
                    'label' => 'Rasse Hund 5 eingeben',

                    'label_attributes' => ['class' => 'col-sm text-sm-right'],
                ],
                'attributes' => [
                    'id' => 'Rasse_Tier5',
                    'class' => 'form-control form-control-input js-pet-autocomplete',
                    'required' => 'required',
                    'pattern' => '[A-Za-z_äâàÄÂÀöÖüÜßÉÊÈéèêç()\'\s.-]{2,}',
                ],
            ],
        ],
        [
            'spec' => [
                'type' => Text::class,
                'name' => 'Rasse_Tier6',
                'options' => [
                    'label' => 'Rasse Hund 6 eingeben',

                    'label_attributes' => ['class' => 'col-sm text-sm-right'],
                ],
                'attributes' => [
                    'id' => 'Rasse_Tier6',
                    'class' => 'form-control form-control-input js-pet-autocomplete',
                    'required' => 'required',
                    'pattern' => '[A-Za-z_äâàÄÂÀöÖüÜßÉÊÈéèêç()\'\s.-]{2,}',
                ],
            ],
        ],
        [
            'spec' => [
                'type' => Select::class,
                'name' => 'mischling_hund1',
                'options' => [
                    'label' => 'Ist Hund 1 ein Mischling?',
                    'label_attributes' => ['class' => 'col-sm text-sm-right'],
                    'value_options' => [
                        'nein' => 'nein',
                        'ja' => 'ja',
                    ],
                ],
                'attributes' => ['id' => 'mischling_hund1'],
            ],
        ],
        [
            'spec' => [
                'type' => Select::class,
                'name' => 'mischling_hund2',
                'options' => [
                    'label' => 'Ist Hund 2 ein Mischling?',
                    'label_attributes' => ['class' => 'col-sm text-sm-right'],
                    'value_options' => [
                        'nein' => 'nein',
                        'ja' => 'ja',
                    ],
                ],
                'attributes' => ['id' => 'mischling_hund2'],
            ],
        ],
        [
            'spec' => [
                'type' => Select::class,
                'name' => 'mischling_hund3',
                'options' => [
                    'label' => 'Ist Hund 3 ein Mischling?',
                    'label_attributes' => ['class' => 'col-sm text-sm-right'],
                    'value_options' => [
                        'nein' => 'nein',
                        'ja' => 'ja',
                    ],
                ],
                'attributes' => ['id' => 'mischling_hund3'],
            ],
        ],
        [
            'spec' => [
                'type' => Select::class,
                'name' => 'mischling_hund4',
                'options' => [
                    'label' => 'Ist Hund 4 ein Mischling?',
                    'label_attributes' => ['class' => 'col-sm text-sm-right'],
                    'value_options' => [
                        'nein' => 'nein',
                        'ja' => 'ja',
                    ],
                ],
                'attributes' => ['id' => 'mischling_hund4'],
            ],
        ],
        [
            'spec' => [
                'type' => Select::class,
                'name' => 'mischling_hund5',
                'options' => [
                    'label' => 'Ist Hund 5 ein Mischling?',
                    'label_attributes' => ['class' => 'col-sm text-sm-right'],
                    'value_options' => [
                        'nein' => 'nein',
                        'ja' => 'ja',
                    ],
                ],
                'attributes' => ['id' => 'mischling_hund5'],
            ],
        ],
        [
            'spec' => [
                'type' => Select::class,
                'name' => 'mischling_hund6',
                'options' => [
                    'label' => 'Ist Hund 6 ein Mischling?',
                    'label_attributes' => ['class' => 'col-sm text-sm-right'],
                    'value_options' => [
                        'nein' => 'nein',
                        'ja' => 'ja',
                    ],
                ],
                'attributes' => ['id' => 'mischling_hund6'],
            ],
        ],
        [
            'spec' => [
                'type' => Text::class,
                'name' => 'Rasse_Tier1a',
                'options' => [
                    'label' => 'Mischlingsrasse Hund 1',

                    'label_attributes' => ['class' => 'col-sm text-sm-right'],
                ],
                'attributes' => [
                    'id' => 'Rasse_Tier1a',
                    'class' => 'form-control form-control-input js-pet-autocomplete',
                    'required' => 'required',
                    'pattern' => '[A-Za-z_äâàÄÂÀöÖüÜßÉÊÈéèêç()\'\s.-]{2,}',
                ],
            ],
        ],
        [
            'spec' => [
                'type' => Text::class,
                'name' => 'Rasse_Tier2a',
                'options' => [
                    'label' => 'Mischlingsrasse Hund 2',

                    'label_attributes' => ['class' => 'col-sm text-sm-right'],
                ],
                'attributes' => [
                    'id' => 'Rasse_Tier2a',
                    'class' => 'form-control form-control-input js-pet-autocomplete',
                    'required' => 'required',
                    'pattern' => '[A-Za-z_äâàÄÂÀöÖüÜßÉÊÈéèêç()\'\s.-]{2,}',
                ],
            ],
        ],
        [
            'spec' => [
                'type' => Text::class,
                'name' => 'Rasse_Tier3a',
                'options' => [
                    'label' => 'Mischlingsrasse Hund 3',

                    'label_attributes' => ['class' => 'col-sm text-sm-right'],
                ],
                'attributes' => [
                    'id' => 'Rasse_Tier3a',
                    'class' => 'form-control form-control-input js-pet-autocomplete',
                    'required' => 'required',
                    'pattern' => '[A-Za-z_äâàÄÂÀöÖüÜßÉÊÈéèêç()\'\s.-]{2,}',
                ],
            ],
        ],
        [
            'spec' => [
                'type' => Text::class,
                'name' => 'Rasse_Tier4a',
                'options' => [
                    'label' => 'Mischlingsrasse Hund 4',

                    'label_attributes' => ['class' => 'col-sm text-sm-right'],
                ],
                'attributes' => [
                    'id' => 'Rasse_Tier4a',
                    'class' => 'form-control form-control-input js-pet-autocomplete',
                    'required' => 'required',
                    'pattern' => '[A-Za-z_äâàÄÂÀöÖüÜßÉÊÈéèêç()\'\s.-]{2,}',
                ],
            ],
        ],
        [
            'spec' => [
                'type' => Text::class,
                'name' => 'Rasse_Tier5a',
                'options' => [
                    'label' => 'Mischlingsrasse Hund 5',

                    'label_attributes' => ['class' => 'col-sm text-sm-right'],
                ],
                'attributes' => [
                    'id' => 'Rasse_Tier5a',
                    'class' => 'form-control form-control-input js-pet-autocomplete',
                    'required' => 'required',
                    'pattern' => '[A-Za-z_äâàÄÂÀöÖüÜßÉÊÈéèêç()\'\s.-]{2,}',
                ],
            ],
        ],
        [
            'spec' => [
                'type' => Text::class,
                'name' => 'Rasse_Tier6a',
                'options' => [
                    'label' => 'Mischlingsrasse Hund 6',

                    'label_attributes' => ['class' => 'col-sm text-sm-right'],
                ],
                'attributes' => [
                    'id' => 'Rasse_Tier6a',
                    'class' => 'form-control form-control-input js-pet-autocomplete',
                    'required' => 'required',
                    'pattern' => '[A-Za-z_äâàÄÂÀöÖüÜßÉÊÈéèêç()\'\s.-]{2,}',
                ],
            ],
        ],
        [
            'spec' => [
                'type' => Select::class,
                'name' => 'rasse_pferd_v',
                'options' => [
                    'label' => 'Rasse des Pferdes',
                    'label_attributes' => ['class' => 'col-sm text-sm-right'],
                    'value_options' => [
                        'Sonstige' => 'Sonstige',
                        'Abaco-Wildpferd' => 'Abaco-Wildpferd',
                        'Abessinier' => 'Abessinier',
                        'Achetta' => 'Achetta',
                        'Aegidienberger' => 'Aegidienberger',
                        'Ainos-Pony' => 'Ainos-Pony',
                        'Albaner' => 'Albaner',
                        'American Classik Shetlandpony' => 'American Classik Shetlandpony',
                        'American Miniature Horse' => 'American Miniature Horse',
                        'American Paint Pony' => 'American Paint Pony',
                        'American Walking Pony' => 'American Walking Pony',
                        'American Welara Pony' => 'American Welara Pony',
                        'Andino' => 'Andino',
                        'Arenberg-Nordkirchner Pony' => 'Arenberg-Nordkirchner Pony',
                        'Arravani' => 'Arravani',
                        'Assateague-Pony' => 'Assateague-Pony',
                        'Asturcon-Australian Pony' => 'Asturcon-Australian Pony',
                        'Aveligneser (Italienischer Hafinger)' => 'Aveligneser (Italienischer Hafinger)',
                        'Baise' => 'Baise',
                        'Balearen Pony' => 'Balearen Pony',
                        'Bali Pony' => 'Bali Pony',
                        'Banker-Pony' => 'Banker-Pony',
                        'Bardigiano' => 'Bardigiano',
                        'Baschkire Basuto-Pony' => 'Baschkire Basuto-Pony',
                        'Batak Pony (Deli Pony)' => 'Batak Pony (Deli Pony)',
                        'Belgisches Reitpony' => 'Belgisches Reitpony',
                        'Bergmann Pony' => 'Bergmann Pony',
                        'Bhutia' => 'Bhutia',
                        'Bosniake' => 'Bosniake',
                        'Bosnisches Gebirgspferd' => 'Bosnisches Gebirgspferd',
                        'British Riding Pony' => 'British Riding Pony',
                        'British Spotted Pony' => 'British Spotted Pony',
                        'Burenpferd' => 'Burenpferd',
                        'Carmague' => 'Carmague',
                        'Cavallino di Monterufoli' => 'Cavallino di Monterufoli',
                        'Cayuse Pony' => 'Cayuse Pony',
                        'Cheju Pony' => 'Cheju Pony',
                        'Chickasaw Pony' => 'Chickasaw Pony',
                        'China Pony' => 'China Pony',
                        'Chincoteague' => 'Chincoteague',
                        'Connemara-Pony' => 'Connemara-Pony',
                        'Criollo' => 'Criollo',
                        'Dales-Pony' => 'Dales-Pony',
                        'Dartmoor-Pony' => 'Dartmoor-Pony',
                        'Deutsches Classic-Pony' => 'Deutsches Classic-Pony',
                        'Deutsches Part-Bred Shetland Pony' => 'Deutsches Part-Bred Shetland Pony',
                        'Deutsches Reitpony' => 'Deutsches Reitpony',
                        'Dülmener (Wildpferd/Grubenpony)' => 'Dülmener (Wildpferd/Grubenpony)',
                        'Edelblut (Arabo-Haflinger)' => 'Edelblut (Arabo-Haflinger)',
                        'Exmoor-Pony' => 'Exmoor-Pony',
                        'Falabella' => 'Falabella',
                        'Färöerpony' => 'Färöerpony',
                        'Fell Pony' => 'Fell Pony',
                        'Fjord' => 'Fjord',
                        'Flores Pony' => 'Flores Pony',
                        'Galiceno Pony' => 'Galiceno Pony',
                        'Garrano Gotland-Pony' => 'Garrano Gotland-Pony',
                        'Haflinger' => 'Haflinger',
                        'Highland-Pony' => 'Highland-Pony',
                        'Hokkaido' => 'Hokkaido',
                        'Holländisches Reitpony' => 'Holländisches Reitpony',
                        'Huzule' => 'Huzule',
                        'Isländer' => 'Isländer',
                        'Java' => 'Java',
                        'Kasak' => 'Kasak',
                        'Kaspisches Kleinpferd' => 'Kaspisches Kleinpferd',
                        'Knabstrupper' => 'Knabstrupper',
                        'Konik' => 'Konik',
                        'Kurdisches Halbblut' => 'Kurdisches Halbblut',
                        'Landais Pony' => 'Landais Pony',
                        'Lehmkuhlener Pony' => 'Lehmkuhlener Pony',
                        'Lewitzer' => 'Lewitzer',
                        'Liebenthaler Pferd' => 'Liebenthaler Pferd',
                        'Mérens' => 'Mérens',
                        'Minishetlandpony' => 'Minishetlandpony',
                        'Misaki' => 'Misaki',
                        'Mongolen Pony' => 'Mongolen Pony',
                        'Mongolisches Wildpferd' => 'Mongolisches Wildpferd',
                        'Mpar' => 'Mpar',
                        'Mustang' => 'Mustang',
                        'Nanfan' => 'Nanfan',
                        'Neufundland Pony' => 'Neufundland Pony',
                        'New-Forest-Pony' => 'New-Forest-Pony',
                        'Niederländisches Reitpony' => 'Niederländisches Reitpony',
                        'Nigerianisches Pony' => 'Nigerianisches Pony',
                        'Norwegisches Fjordpferd' => 'Norwegisches Fjordpferd',
                        'Panjepferd' => 'Panjepferd',
                        'Paso Fino' => 'Paso Fino',
                        'Paso Peruano' => 'Paso Peruano',
                        'Pindos Pony' => 'Pindos Pony',
                        'Polo-Pony' => 'Polo-Pony',
                        'Pony of the Americas' => 'Pony of the Americas',
                        'Pottok-Pony' => 'Pottok-Pony',
                        'Przewalski-Pony' => 'Przewalski-Pony',
                        'Quba' => 'Quba',
                        'Riwoque' => 'Riwoque',
                        'Sable Island Pony' => 'Sable Island Pony',
                        'Sandelholz-Pony' => 'Sandelholz-Pony',
                        'Sardisches Pony' => 'Sardisches Pony',
                        'Schweike' => 'Schweike',
                        'Shetlandpony' => 'Shetlandpony',
                        'Skyros Pony' => 'Skyros Pony',
                        'Sorraia' => 'Sorraia',
                        'Spiti' => 'Spiti',
                        'Sumba' => 'Sumba',
                        'Taishu' => 'Taishu',
                        'Tarpan' => 'Tarpan',
                        'Tibet' => 'Tibet',
                        'Tigerscheckpony' => 'Tigerscheckpony',
                        'Timor' => 'Timor',
                        'Tinker' => 'Tinker',
                        'Tokara Pony' => 'Tokara Pony',
                        'Wjatka-Pony' => 'Wjatka-Pony',
                        'Welsh Cob' => 'Welsh Cob',
                        'Welsh Mountain' => 'Welsh Mountain',
                        'Welsh Partbred' => 'Welsh Partbred',
                        'Welsh Riding Pony' => 'Welsh Riding Pony',
                        'Welsh-Pony' => 'Welsh-Pony',
                        'Yonaguni Pony' => 'Yonaguni Pony',
                        'Zemaitukas Pony' => 'Zemaitukas Pony',
                    ],
                ],
                'attributes' => ['id' => 'rasse_pferd_v'],
            ],
        ],
        'selbst' => [
            'spec' => [
                'type' => Select::class,
                'name' => 'selbst',
                'options' => [
                    'label' => 'Selbstbeteiligung',
                    'label_attributes' => ['class' => 'col-sm text-sm-right'],
                    'value_options' => [
                        'onein' => [
                            'value' => 'nein',
                            'label' => '0 €',
                        ],
                        'o150' => [
                            'value' => '150',
                            'label' => '150 €',
                        ],
                        'o200' => [
                            'value' => '200',
                            'label' => '200 €',
                        ],
                        'o250' => [
                            'value' => '250',
                            'label' => '250 €',
                        ],
                        'o300' => [
                            'value' => '300',
                            'label' => '300 €',
                        ],
                    ],
                ],
                'attributes' => ['id' => 'selbst'],
            ],
        ],
        [
            'spec' => [
                'type' => Select::class,
                'name' => 'zahlweise',
                'options' => [
                    'label' => 'Zahlweise',
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
        'laufzeit' => [
            'spec' => [
                'type' => Select::class,
                'name' => 'laufzeit',
                'options' => [
                    'label' => 'Laufzeit',
                    'label_attributes' => ['class' => 'col-sm text-sm-right'],
                    'value_options' => [
                        '1' => '1 Jahr',
                        '3' => 'bis zu 3 Jahre',
                        '5' => 'bis zu 5 Jahre',
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
                    'label' => 'Bestand in den letzten 5 Jahren eine Vorversicherung?',

                    'label_attributes' => ['class' => 'col-sm text-sm-right'],
                    'value_options' => [
                        '' => '-- Bitte wählen --',
                        'nein' => 'nein',
                        'ja' => 'ja',
                    ],
                ],
                'attributes' => [
                    'id' => 'vorvers5',

                    'required' => 'required',
                ],
            ],
        ],
        [
            'spec' => [
                'type' => Select::class,
                'name' => 'schaeden5',
                'options' => [
                    'label' => 'Schäden in den letzten 5 Jahren',

                    'label_attributes' => ['class' => 'col-sm text-sm-right'],
                    'value_options' => [
                        '' => '-- Bitte wählen --',
                        '0' => '0',
                        '1' => '1',
                        '2' => '2',
                        '3' => '3',
                        '4' => '4',
                    ],
                ],
                'attributes' => [
                    'id' => 'schaeden5',

                    'required' => 'required',
                ],
            ],
        ],
        [
            'spec' => [
                'type' => Select::class,
                'name' => 'schaden1jahr',
                'options' => [
                    'label' => 'In welchem Jahr war der 1. Schaden?',

                    'label_attributes' => ['class' => 'col-sm text-sm-right'],
                    'value_options' => [
                        date('Y') => date('Y'),
                        date('Y') - 1 => date('Y') - 1,
                        date('Y') - 2 => date('Y') - 2,
                        date('Y') - 3 => date('Y') - 3,
                        date('Y') - 4 => date('Y') - 4,
                        date('Y') - 5 => date('Y') - 5,
                    ],
                ],
                'attributes' => ['id' => 'schaden1jahr'],
            ],
        ],
        [
            'spec' => [
                'type' => Select::class,
                'name' => 'schaden2jahr',
                'options' => [
                    'label' => 'In welchem Jahr war der 2. Schaden?',

                    'label_attributes' => ['class' => 'col-sm text-sm-right'],
                    'value_options' => [
                        date('Y') => date('Y'),
                        date('Y') - 1 => date('Y') - 1,
                        date('Y') - 2 => date('Y') - 2,
                        date('Y') - 3 => date('Y') - 3,
                        date('Y') - 4 => date('Y') - 4,
                        date('Y') - 5 => date('Y') - 5,
                    ],
                ],
                'attributes' => ['id' => 'schaden2jahr'],
            ],
        ],
        [
            'spec' => [
                'type' => Select::class,
                'name' => 'schaden3jahr',
                'options' => [
                    'label' => 'In welchem Jahr war der 3. Schaden?',

                    'label_attributes' => ['class' => 'col-sm text-sm-right'],
                    'value_options' => [
                        date('Y') => date('Y'),
                        date('Y') - 1 => date('Y') - 1,
                        date('Y') - 2 => date('Y') - 2,
                        date('Y') - 3 => date('Y') - 3,
                        date('Y') - 4 => date('Y') - 4,
                        date('Y') - 5 => date('Y') - 5,
                    ],
                ],
                'attributes' => ['id' => 'schaden3jahr'],
            ],
        ],
        [
            'spec' => [
                'type' => Select::class,
                'name' => 'schaden4jahr',
                'options' => [
                    'label' => 'In welchem Jahr war der 4. Schaden?',

                    'label_attributes' => ['class' => 'col-sm text-sm-right'],
                    'value_options' => [
                        date('Y') => date('Y'),
                        date('Y') - 1 => date('Y') - 1,
                        date('Y') - 2 => date('Y') - 2,
                        date('Y') - 3 => date('Y') - 3,
                        date('Y') - 4 => date('Y') - 4,
                        date('Y') - 5 => date('Y') - 5,
                    ],
                ],
                'attributes' => ['id' => 'schaden4jahr'],
            ],
        ],
        [
            'spec' => [
                'type' => Select::class,
                'name' => 'chipnr',
                'options' => [
                    'label' => 'Chip-Nr. vorhanden?',
                    'label_attributes' => ['class' => 'col-sm text-sm-right'],
                    'value_options' => [
                        'nein' => 'nein',
                        'ja' => 'ja',
                    ],
                ],
                'attributes' => ['id' => 'chipnr'],
            ],
        ],
        [
            'spec' => [
                'type' => Select::class,
                'name' => 'lebensnr',
                'options' => [
                    'label' => 'Lebens-Nr. vorhanden?',
                    'label_attributes' => ['class' => 'col-sm text-sm-right'],
                    'value_options' => [
                        'nein' => 'nein',
                        'ja' => 'ja',
                    ],
                ],
                'attributes' => ['id' => 'lebensnr'],
            ],
        ],
        [
            'spec' => [
                'type' => Select::class,
                'name' => 'kombirabatte',
                'options' => [
                    'label' => 'Kombirabatte mit berechnen?',
                    'label_attributes' => ['class' => 'col-sm text-sm-right'],
                    'value_options' => [
                        'nein' => 'nein',
                        'ja' => 'ja',
                    ],
                ],
                'attributes' => [
                    'id' => 'kombirabatte',
                    'class' => 'toggle-trigger',
                    'data-toggle-modus' => 'show',
                    'data-toggle-value' => 'ja',
                ],
            ],
        ],
        [
            'spec' => [
                'type' => Checkbox::class,
                'name' => 'KrPHV',
                'options' => [
                    'label' => 'Privathaftpflicht',
                    'label_attributes' => ['class' => 'col-sm text-sm-right check-label'],
                    'use_hidden_element' => false,
                    'checked_value' => '1',
                    'unchecked_value' => '0',
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
                    'label_attributes' => ['class' => 'col-sm text-sm-right check-label'],
                    'use_hidden_element' => false,
                    'checked_value' => '1',
                    'unchecked_value' => '0',
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
                    'label_attributes' => ['class' => 'col-sm text-sm-right check-label'],
                    'use_hidden_element' => false,
                    'checked_value' => '1',
                    'unchecked_value' => '0',
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
                    'label_attributes' => ['class' => 'col-sm text-sm-right check-label'],
                    'use_hidden_element' => false,
                    'checked_value' => '1',
                    'unchecked_value' => '0',
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
                    'label_attributes' => ['class' => 'col-sm text-sm-right check-label'],
                    'use_hidden_element' => false,
                    'checked_value' => '1',
                    'unchecked_value' => '0',
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
                    'label_attributes' => ['class' => 'col-sm text-sm-right check-label'],
                    'use_hidden_element' => false,
                    'checked_value' => '1',
                    'unchecked_value' => '0',
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
                    'label_attributes' => ['class' => 'col-sm text-sm-right check-label'],
                    'use_hidden_element' => false,
                    'checked_value' => '1',
                    'unchecked_value' => '0',
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
                    'label_attributes' => ['class' => 'col-sm text-sm-right check-label'],
                    'use_hidden_element' => false,
                    'checked_value' => '1',
                    'unchecked_value' => '0',
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
                    'label_attributes' => ['class' => 'col-sm text-sm-right check-label'],
                    'use_hidden_element' => false,
                    'checked_value' => '1',
                    'unchecked_value' => '0',
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
                    'label_attributes' => ['class' => 'col-sm text-sm-right check-label'],
                    'use_hidden_element' => false,
                    'checked_value' => '1',
                    'unchecked_value' => '0',
                ],
                'attributes' => [
                    'id' => 'KrRS',
                    'class' => 'form-check-input',
                ],
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
                                'class' => 'form-check-input form-radio-input',
                                'required' => 'required',
                            ],
                            'label_attributes' => [
                                'class' => 'form-radio js-gtm-event',
                                'for' => 'zusatzfragen_nein',
                                'data-event-type' => 'click',
                                'data-event-category' => 'versicherung',
                                'data-event-action' => 'no additional questions',
                                'data-event-label' => 'tier',
                            ],
                        ],
                        'ja' => [
                            'value' => 'ja',
                            'label' => 'Ich möchte weitere Angaben zum gewünschten Versicherungsschutz machen. Es werden dann nur Tarife angezeigt, welche die Vorgaben erfüllen.',
                            'attributes' => [
                                'id' => 'zusatzfragen_ja',
                                'class' => 'form-check-input form-radio-input',
                                'required' => 'required',
                            ],
                            'label_attributes' => [
                                'class' => 'form-radio js-gtm-event',
                                'for' => 'zusatzfragen_ja',
                                'data-event-type' => 'click',
                                'data-event-category' => 'versicherung',
                                'data-event-action' => 'additional questions requested',
                                'data-event-label' => 'tier',
                            ],
                        ],
                    ],
                ],
                'attributes' => ['id' => 'zusatzfragen'],
            ],
        ],
        [
            'spec' => [
                'type' => Select::class,
                'name' => 'pferd_stockmass_v',
                'options' => [
                    'label' => 'Stockmaß des Pferdes bis 148 cm?',
                    'label_attributes' => ['class' => 'col-sm text-sm-right'],
                    'value_options' => [
                        'nein' => 'nein',
                        'ja' => 'ja',
                    ],
                ],
                'attributes' => ['id' => 'pferd_stockmass_v'],
            ],
        ],
        [
            'spec' => [
                'type' => Select::class,
                'name' => 'pferd_reiten_v',
                'options' => [
                    'label' => 'Wird das Pferd geritten? (Reitrisiko mitversichern)',
                    'label_attributes' => ['class' => 'col-sm text-sm-right'],
                    'value_options' => [
                        'ja' => 'ja',
                        'nein' => 'nein',
                    ],
                ],
                'attributes' => ['id' => 'pferd_reiten_v'],
            ],
        ],
        [
            'spec' => [
                'type' => Select::class,
                'name' => 'reitpass_v',
                'options' => [
                    'label' => 'Hat der Halter einen Reitpass?',
                    'label_attributes' => ['class' => 'col-sm text-sm-right'],
                    'value_options' => [
                        'nein' => 'nein',
                        'ja' => 'ja',
                    ],
                ],
                'attributes' => ['id' => 'reitpass_v'],
            ],
        ],
        [
            'spec' => [
                'type' => Select::class,
                'name' => 'pferd_reitbeteiligung_v',
                'options' => [
                    'label' => 'Gibt es eine Reitbeteiligung?',
                    'label_attributes' => ['class' => 'col-sm text-sm-right'],
                    'value_options' => [
                        'nein' => 'nein',
                        'ja' => 'ja',
                    ],
                ],
                'attributes' => ['id' => 'pferd_reitbeteiligung_v'],
            ],
        ],
        [
            'spec' => [
                'type' => Select::class,
                'name' => 'fremd_v',
                'options' => [
                    'label' => 'Fremdreiterrisiko?',
                    'label_attributes' => ['class' => 'col-sm text-sm-right'],
                    'value_options' => [
                        'ja' => 'ja (AK Empfehlung)',
                        'nein' => 'nein',
                    ],
                ],
                'attributes' => ['id' => 'fremd_v'],
            ],
        ],
        [
            'spec' => [
                'type' => Select::class,
                'name' => 'sport_v',
                'options' => [
                    'label' => 'Nehmen die Tiere an Wettkämpfen/Turnieren teil?',
                    'label_attributes' => ['class' => 'col-sm text-sm-right'],
                    'value_options' => [
                        'nein' => 'nein',
                        'ja' => 'ja',
                    ],
                ],
                'attributes' => ['id' => 'sport_v'],
            ],
        ],
        [
            'spec' => [
                'type' => Select::class,
                'name' => 'pferderennen_v',
                'options' => [
                    'label' => 'Nimmt das Pferd an Pferderennen teil?',
                    'label_attributes' => ['class' => 'col-sm text-sm-right'],
                    'value_options' => [
                        'nein' => 'nein',
                        'ja' => 'ja',
                    ],
                ],
                'attributes' => ['id' => 'pferderennen_v'],
            ],
        ],
        [
            'spec' => [
                'type' => Select::class,
                'name' => 'verein_v',
                'options' => [
                    'label' => 'Sind Sie Mitglied im Reitverein?',
                    'label_attributes' => ['class' => 'col-sm text-sm-right'],
                    'value_options' => [
                        'nein' => 'nein',
                        'ja' => 'ja',
                    ],
                ],
                'attributes' => ['id' => 'verein_v'],
            ],
        ],
        [
            'spec' => [
                'type' => Select::class,
                'name' => 'kutsch_v',
                'options' => [
                    'label' => 'Verwendung für Kutschfahrten?',
                    'label_attributes' => ['class' => 'col-sm text-sm-right'],
                    'value_options' => [
                        'nein' => 'nein',
                        'ja' => 'ja',
                    ],
                ],
                'attributes' => ['id' => 'kutsch_v'],
            ],
        ],
        [
            'spec' => [
                'type' => Select::class,
                'name' => 'tier_nebenberuf_v',
                'options' => [
                    'label' => 'Nebenberufliche Tätigkeit bis 17.500 EUR?',
                    'label_attributes' => ['class' => 'col-sm text-sm-right'],
                    'value_options' => [
                        'nein' => 'nein',
                        'ja' => 'ja',
                    ],
                ],
                'attributes' => ['id' => 'tier_nebenberuf_v'],
            ],
        ],
        [
            'spec' => [
                'type' => Select::class,
                'name' => 'hund_schule_v',
                'options' => [
                    'label' => 'Nachweis für den Besuch einer Hundeschule?',
                    'label_attributes' => ['class' => 'col-sm text-sm-right'],
                    'value_options' => [
                        'nein' => 'nein',
                        'ja' => 'ja',
                    ],
                ],
                'attributes' => ['id' => 'hund_schule_v'],
            ],
        ],
        'vs_v' => [
            'spec' => [
                'type' => Select::class,
                'name' => 'vs_v',
                'options' => [
                    'label' => 'Mindest-Versicherungssumme Personen/Sachschäden',
                    'label_attributes' => ['class' => 'col-sm text-sm-right'],
                    'value_options' => [
                        'o1000000' => [
                            'value' => '1000000',
                            'label' => 'mind. 1 Mill EUR',
                        ],
                        'o3000000' => [
                            'value' => '3000000',
                            'label' => 'mind. 3 Mill EUR (AK Empfehlung)',
                        ],
                        'o5000000' => [
                            'value' => '5000000',
                            'label' => 'mind. 5 Mill EUR',
                        ],
                    ],
                ],
                'attributes' => ['id' => 'vs_v'],
            ],
        ],
        [
            'spec' => [
                'type' => Select::class,
                'name' => 'vsmp_v',
                'options' => [
                    'label' => 'Mindest-Versicherungssumme Mietsachschäden für Pferd',
                    'label_attributes' => ['class' => 'col-sm text-sm-right'],
                    'value_options' => [
                        '0' => 'keine (EU-Pferd)',
                        '1 - ja' => 'mitversichert laut Tarif',
                    ],
                ],
                'attributes' => ['id' => 'vsmp_v'],
            ],
        ],
        [
            'spec' => [
                'type' => Select::class,
                'name' => 'vsm_v',
                'options' => [
                    'label' => 'Mindest-Versicherungssumme Mietsachschäden für Hund',
                    'label_attributes' => ['class' => 'col-sm text-sm-right'],
                    'value_options' => [
                        '0' => 'keine',
                        '50000' => 'mind. 50.000 EUR',
                        '300000' => 'mind. 300.000 EUR (AK Empfehlung)',
                        '500000' => 'mind. 500.000 EUR',
                        '1000000' => 'mind. 1 Mill. EUR',
                    ],
                ],
                'attributes' => ['id' => 'vsm_v'],
            ],
        ],
        [
            'spec' => [
                'type' => Select::class,
                'name' => 'vsmbep_v',
                'options' => [
                    'label' => 'Mindest-Versicherungssumme Mietsachschäden für Pferd bei beweglichen Objekten',
                    'label_attributes' => ['class' => 'col-sm text-sm-right'],
                    'value_options' => [
                        '0' => 'keine (EU-Pferd)',
                        '1 - ja' => 'mitversichert laut Tarif',
                    ],
                ],
                'attributes' => ['id' => 'vsmbep_v'],
            ],
        ],
        [
            'spec' => [
                'type' => Select::class,
                'name' => 'vsmbeh_v',
                'options' => [
                    'label' => 'Mindest-Versicherungssumme Mietsachschäden für Hund bei beweglichen Objekten',
                    'label_attributes' => ['class' => 'col-sm text-sm-right'],
                    'value_options' => [
                        '0' => 'keine (EU-Hund)',
                        '1 - ja' => 'mitversichert laut Tarif',
                    ],
                ],
                'attributes' => ['id' => 'vsmbeh_v'],
            ],
        ],
        [
            'spec' => [
                'type' => Select::class,
                'name' => 'vsv_v',
                'options' => [
                    'label' => 'Mindest-Versicherungssumme für Vermögensschäden',
                    'label_attributes' => ['class' => 'col-sm text-sm-right'],
                    'value_options' => [
                        '0' => 'keine',
                        '50000' => 'mind. 50.000 EUR (AK Empfehlung)',
                        '100000' => 'mind. 100.000 EUR',
                        '1000000' => 'mind. 1 Mill EUR',
                    ],
                ],
                'attributes' => ['id' => 'vsv_v'],
            ],
        ],
        [
            'spec' => [
                'type' => Select::class,
                'name' => 'vors_v',
                'options' => [
                    'label' => 'Vorsorgeversicherung für neugeborene Jungtiere?',
                    'label_attributes' => ['class' => 'col-sm text-sm-right'],
                    'value_options' => [
                        'nein' => 'nein',
                        'ja' => 'ja (AK Empfehlung)',
                    ],
                ],
                'attributes' => ['id' => 'vors_v'],
            ],
        ],
        [
            'spec' => [
                'type' => Select::class,
                'name' => 'schul_v',
                'options' => [
                    'label' => 'Stellen Sie Ihr Pferd Schulungszwecken zur Verfügung?',
                    'label_attributes' => ['class' => 'col-sm text-sm-right'],
                    'value_options' => [
                        'nein' => 'nein',
                        'ja, unentgeltlich' => 'ja, unentgeltlich',
                        'ja, auch gegen Entgelt' => 'ja, auch gegen Entgelt',
                    ],
                ],
                'attributes' => ['id' => 'schul_v'],
            ],
        ],
        [
            'spec' => [
                'type' => Select::class,
                'name' => 'flur_v',
                'options' => [
                    'label' => 'Flurschäden?',
                    'label_attributes' => ['class' => 'col-sm text-sm-right'],
                    'value_options' => [
                        'nein' => 'nein',
                        'ja' => 'ja (AK Empfehlung)',
                    ],
                ],
                'attributes' => ['id' => 'flur_v'],
            ],
        ],
        [
            'spec' => [
                'type' => Checkbox::class,
                'name' => 'chkErstinfo',
                'options' => [
                    'use_hidden_element' => true,
                    'checked_value' => '1',
                    'unchecked_value' => '0',
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
                'name' => 'sToken',
            ],
        ],
        [
            'spec' => [
                'type' => Submit::class,
                'name' => 'btn_berechnen',
                'options' => ['label' => 'Berechnen'],
                'attributes' => [
                    'value' => 'Berechnen',
                    'class' => 'btn btn-default js-gtm-event',
                    'data-event-type' => 'click',
                    'data-event-category' => 'versicherung',
                    'data-event-action' => 'calculate',
                    'data-event-label' => 'tier',
                ],
            ],
        ],
        [
            'spec' => [
                'type' => Submit::class,
                'name' => 'recalc',
                'options' => ['label' => 'neu berechnen'],
                'attributes' => ['value' => 'neu berechnen'],
            ],
        ],
    ],
    'input_filter' => [
        'selbst' => ['required' => false],
        'zahlweise' => ['required' => false],
        'vorvers5' => ['required' => true],
        'schaeden5' => ['required' => true],
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
    ],
];
