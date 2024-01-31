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
use Laminas\Form\Element\Hidden;
use Laminas\Form\Element\Number;
use Laminas\Form\Element\Radio;
use Laminas\Form\Element\Select;
use Laminas\Form\Element\Text;
use Laminas\Form\Form;
use Laminas\Validator\NotEmpty;
use Mimmi20\Form\Element\Group\ElementGroup;
use Mimmi20\Form\Links\Element\Links;

return [
    'type' => Form::class,
    'options' => [
        'layout' => \Mimmi20\LaminasView\BootstrapForm\Form::LAYOUT_VERTICAL,
        'form-required-mark' => '<div class="mt-2 text-info-required">* Pflichtfeld</div>',
        'field-required-mark' => '<span class="text-info-required">*</span>',
        'col_attributes' => ['class' => 'col-sm'],
        'label_attributes' => ['class' => 'col-sm text-sm-right'],
        'help_attributes' => ['class' => 'help-content'],
    ],
    'attributes' => [
        'method' => 'post',
        'accept-charset' => 'utf-8',
        'action' => '/calculator/hr/1/input/2doqt23okbdqkgabg80guef8en?subid=A-00-000',
        'class' => 'form input-form js-help has-help has-preloader js-form-validation-base col-12 js-input-form-init',
        'data-show-arrow' => 'left',
        'id' => 'hr-form',
    ],
    'fieldsets' => [
        [
            'flags' => ['priority' => 20],
            'spec' => [
                'type' => ElementGroup::class,
                'name' => 'one',
                'options' => [
                    'label' => 'Was möchten Sie versichern?',
                    'label_options' => ['always_wrap' => true],
                    'label_attributes' => ['class' => 'headline-calculator'],
                    'as-card' => true,
                ],
                'elements' => [
                    [
                        'spec' => [
                            'type' => Select::class,
                            'name' => 'versbeginn',
                            'options' => [
                                'label' => 'Versicherungsbeginn',

                                'value_options' => [
                                    'sofort' => 'schnellstmöglich',
                                    'datum' => 'Datum angeben',
                                ],
                                'col_attributes' => ['class' => 'js-versbeginn'],
                                'row_attributes' => ['class' => 'pt-sm-3'],
                            ],
                            'attributes' => [
                                'id' => 'versbeginn',
                                'class' => 'toggle-trigger',
                                'data-toggle-modus' => 'show',
                                'data-toggle-value' => 'datum',
                            ],
                        ],
                    ],
                    [
                        'spec' => [
                            'type' => Text::class,
                            'name' => 'versbeginn_datum',
                            'options' => [
                                'label' => 'Beginn am',

                                'row_attributes' => ['class' => 'collapse toggle-box-versbeginn'],
                            ],
                            'attributes' => [
                                'id' => 'versbeginn_datum',
                                'class' => 'form-control datepicker js-datepicker js-initial-required',
                                'autocomplete' => 'off',
                            ],
                        ],
                    ],
                    [
                        'spec' => [
                            'type' => Text::class,
                            'name' => 'plz',
                            'options' => [
                                'label' => 'PLZ - Risiko-Anschrift',

                                'help_content' => '<strong>Warum fragen wir das?</strong><p>Die Postleitzahl Ihrer Wohnung wird für die Risikobeurteilung /Beitragsberechnung benötigt. Die Beitragshöhe ist nicht nur abhängig von Ihren gewünschten Leistungen, sondern wird auch anhand Art und Anzahl der Schäden, die in Ihrem Wohnort durchschnittlich gemeldet werden, bemessen.</p>',
                            ],
                            'attributes' => [
                                'id' => 'plz',
                                'class' => 'form-control form-control-short js-special-zip-message js-adress-completition',
                                'pattern' => '^\d{5}$',
                                'minlength' => '5',
                                'maxlength' => '5',
                            ],
                        ],
                    ],
                    [
                        'spec' => [
                            'type' => Select::class,
                            'name' => 'ort',
                            'options' => [
                                'label' => 'Ort',

                                'value_options' => ['' => 'Bitte zuerst PLZ eintragen'],
                                'disable_inarray_validator' => true,
                                'help_content' => '<strong>Ort</strong><p>Der Ort Ihrer zu versichernden Wohnung.</p>',
                                'col_attributes' => ['class' => 'js-ort'],
                            ],
                            'attributes' => ['id' => 'ort'],
                        ],
                    ],
                    [
                        'spec' => [
                            'type' => Select::class,
                            'name' => 'strasse',
                            'options' => [
                                'label' => 'Straße',

                                'value_options' => ['' => 'Bitte zuerst Ort wählen'],
                                'disable_inarray_validator' => true,
                                'help_content' => '<strong>Straße</strong><p>Die Straße Ihrer zu versichernden Wohnung.</p>',
                                'col_attributes' => ['class' => 'js-strasse'],
                            ],
                            'attributes' => ['id' => 'strasse'],
                        ],
                    ],
                    [
                        'spec' => [
                            'type' => Text::class,
                            'name' => 'hnr',
                            'options' => [
                                'label' => 'Hausnummer',

                                'help_content' => '<strong>Hausnummer</strong><p>Die Hausnummer Ihrer zu versichernden Wohnung.</p>',
                            ],
                            'attributes' => [
                                'id' => 'hnr',
                                'class' => 'form-control form-control-short',
                            ],
                        ],
                    ],
                    [
                        'spec' => [
                            'type' => Text::class,
                            'name' => 'gebdatum',
                            'options' => [
                                'label' => 'Geburtsdatum',
                                'col_attributes' => ['class' => 'date datepicker-group js-datepicker-group'],
                            ],
                            'attributes' => [
                                'id' => 'gebdatum',
                                'class' => 'form-control datepicker js-datepicker',
                                'placeholder' => 'TT.MM.JJJJ',

                                'data-date-format' => 'de',
                                'data-date-format-message' => 'Bitte geben Sie ein korrektes Geburtsdatum an!',
                                'data-min-age' => '18y',
                                'data-min-age-message' => 'Sie sind leider zu jung, um eine Versicherung abzuschließen.',
                                'autocomplete' => 'off',
                            ],
                        ],
                    ],
                    [
                        'spec' => [
                            'type' => Select::class,
                            'name' => 'whg',
                            'options' => [
                                'label' => 'Wo wohnen Sie?',

                                'value_options' => [
                                    'Mehrfamilienhaus' => 'Mehrfamilienhaus',
                                    'Einfamilienhaus' => 'Einfamilienhaus',
                                    'Zweifamilienhaus' => 'Zweifamilienhaus',
                                    'Doppelhaushälfte' => 'Doppelhaushälfte',
                                    'Reihenhaus' => 'Reihenhaus',
                                ],
                                'help_content' => '<strong>Wo wohnen Sie</strong><p>Wählen Sie hier, in welchem Haus sich die zu versichernde Wohnung befindet.</p>',
                            ],
                            'attributes' => [
                                'id' => 'whg',
                                'class' => 'toggle-trigger',
                                'data-toggle-modus' => 'hide',
                                'data-toggle-value' => 'Mehrfamilienhaus',
                            ],
                        ],
                    ],
                    [
                        'spec' => [
                            'type' => Select::class,
                            'name' => 'vermietet',
                            'options' => [
                                'label' => 'Ist die Wohnung möbliert vermietet?',

                                'value_options' => [
                                    'nein' => 'nein',
                                    'ja' => 'ja',
                                ],
                            ],
                            'attributes' => ['id' => 'vermietet'],
                        ],
                    ],
                    [
                        'spec' => [
                            'type' => Number::class,
                            'name' => 'wohnfl',
                            'options' => [
                                'label' => 'Ihre gesamte Wohnfläche im Haus',
                                'help_content' => '<strong>Ihre gesamte Wohnfläche im Haus</strong><p>Als Wohnfläche gilt die Grundfläche aller Räume der versicherten Wohnung. Räume, die zu Hobbyzwecken genutzt werden, gelten immer als Wohnfläche. Nicht zu berücksichtigen sind: Zubehörräume, Keller- und Speicherräume, die nicht zu Wohnzwecken genutzt werden, nicht ausgebaute Dachböden, Treppen, Balkone, Terrassen, Loggien, Garagen.</p>',
                            ],
                            'attributes' => [
                                'id' => 'wohnfl',
                                'class' => 'form-control has-legend',
                                'min' => '10',
                                'max' => '2000',
                            ],
                        ],
                    ],
                    [
                        'spec' => [
                            'type' => Number::class,
                            'name' => 'wohnfl_kg',
                            'options' => [
                                'label' => 'Davon sind im Keller',
                                'help_content' => '<strong>Davon sind im Keller</strong><p>Geben Sie hier eine evtl. Wohnfläche im Keller an. Diese muss aber im o.g. Feld schon enthalten sein. Definition siehe oben. (z.B. Hobbyraum)</p>',
                            ],
                            'attributes' => [
                                'id' => 'wohnfl_kg',
                                'class' => 'form-control has-legend',
                            ],
                        ],
                    ],
                    [
                        'spec' => [
                            'type' => Number::class,
                            'name' => 'kellerfl',
                            'options' => [
                                'label' => 'Grundfläche des Kellers',
                                'help_content' => '<strong>Grundfläche des Kellers</strong><p>Geben Sie hier die gesamte Grundfläche Ihres Kellers in qm an.</p>',
                            ],
                            'attributes' => [
                                'id' => 'kellerfl',
                                'class' => 'form-control has-legend',
                            ],
                        ],
                    ],
                    [
                        'spec' => [
                            'type' => Select::class,
                            'name' => 'beamte',
                            'options' => [
                                'label' => 'Tarif',
                                'help_content' => '<strong>Warum fragen wir das?</strong><p>Einige Versicherer bieten Beitragsnachlässe für Beamte und Angestellte im öffentlichen Dienst.</p><strong>Hinweis zur Auswahl</strong><p>Wählen Sie auch &quot;öffentlicher Dienst&quot;, wenn Sie Beamter sind oder früher im öffentlichen Dienst beschäftigt waren und sich mittlerweile im Ruhestand befinden.</p>',
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
                            'type' => Select::class,
                            'name' => 'verssummeauto',
                            'options' => [
                                'label' => 'Versicherungssumme',
                                'help_content' => '<strong>Versicherungssumme</strong><p>Die vereinbarte Versicherungssumme bildet die Höchst­entschädigungs­grenze nach einem Totalschaden. Wir empfehlen Unter­versicherungs­verzicht zu vereinbaren, damit nach einem Schaden durch den Versicherer keine Abzüge wegen möglicher Unterversicherung vorgenommen werden.</p><p>Eine Unterversicherung liegt vor, wenn im Schadenfall die vereinbarte Versicherungssumme niedriger ist als der tatsächlich vorhandene Wert Ihres Hausrates.<br/>Ein Unter­versicherungs­verzicht erfordert je nach Anbieter und Tarif eine Versicherungssumme in Höhe von 600-700 EUR/qm Wohnfläche. Beachten Sie: Ohne eigene Eingabe der Versicherungssumme wird automatisch der richtige Wert für den Unter­versicherungs­verzicht ermittelt.</p>',
                                'value_options' => [
                                    'auto' => 'automatisch ermitteln',
                                    'manuell' => 'selbst angeben',
                                ],
                            ],
                            'attributes' => [
                                'id' => 'verssummeauto',
                                'class' => 'toggle-trigger',
                                'data-toggle-modus' => 'show',
                                'data-toggle-value' => 'manuell',
                            ],
                        ],
                    ],
                    [
                        'spec' => [
                            'type' => Number::class,
                            'name' => 'verssumme',
                            'options' => [
                                'label' => 'Versicherungssumme selbst angeben',
                                'help_content' => '<strong>Versicherungssumme selbst angeben</strong><p>Die vereinbarte Versicherungssumme bildet die Höchst­entschädigungs­grenze nach einem Totalschaden. Wir empfehlen Unter­versicherungs­verzicht zu vereinbaren, damit nach einem Schaden durch den Versicherer keine Abzüge wegen möglicher Unterversicherung vorgenommen werden. Dieser Unter­versicherungs­verzicht erfordert je nach Anbieter und Tarif eine Versicherungssumme in Höhe von 600-700 EUR/qm Wohnfläche. Beachten Sie: Ohne Eingabe der Versicherungssumme wird automatisch der richtige Wert für den Unter­versicherungs­verzicht ermittelt.</p>',
                            ],
                            'attributes' => [
                                'id' => 'verssumme',
                                'class' => 'form-control has-legend',
                            ],
                        ],
                    ],
                    [
                        'spec' => [
                            'type' => Number::class,
                            'name' => 'fahrrad',
                            'options' => [
                                'label' => 'Fahrraddiebstahl bis',
                                'help_content' => '<strong>Fahrraddiebstahl bis</strong><p>Auch bei Fahrraddiebstahl gilt Neuwertersatz. Achten Sie besonders auf Anbieter, die auf die Nachtzeitklausel verzichten und auch dann Schadenersatz leisten, wenn das Fahrrad in der Zeit zwischen 22:00-06:00 Uhr entwendet wurde. Bedingung: Das Fahrrad muss vor dem Diebstahl in geeigneter Weise gesichert (angeschlossen) gewesen sein. Schaden-Beispiel: Sie fahren mit Ihrem Fahrrad einkaufen, schließen es vor dem Geschäft ordnungsgemäß an. Trotzdem wird Ihr Fahrrad gestohlen.</p>',
                            ],
                            'attributes' => [
                                'id' => 'fahrrad',
                                'class' => 'form-control has-legend',
                                'min' => '0',
                            ],
                        ],
                    ],
                    [
                        'spec' => [
                            'type' => Select::class,
                            'name' => 'glas',
                            'options' => [
                                'label' => 'Glasversicherung',
                                'help_content' => '<strong>Glasversicherung</strong><p>Bei Wohnungen im Mehrfamilienhaus ist nur die Mobiliarverglasung versichert, im selbstgenutzten Einfamilienhaus zusätzlich die Gebäudeverglasung.</p>',
                                'value_options' => [
                                    'nein' => 'nein',
                                    'ja' => 'ja',
                                ],
                            ],
                            'attributes' => ['id' => 'glas'],
                        ],
                    ],
                    [
                        'spec' => [
                            'type' => Select::class,
                            'name' => 'elem',
                            'options' => [
                                'label' => 'Elementarschäden (Überschwemmung, Erdbeben, Erdsenkung, Erdrutsch, Schneedruck- und Lawinenschäden)',
                                'help_content' => '<strong>Elementarschäden (Überschwemmung, Erdbeben, Erdsenkung, Erdrutsch, Schneedruck- und Lawinenschäden)</strong><p>Dazu gehören Überschwemmung durch Witterungsniederschläge, oder Ausuferung von oberirdischen Gewässern, Schneedruck, Lawinen, Erdrutsch, Erdfall, Erdbeben. Sturm oder Hagelschäden sind schon in der normalen Versicherung enthalten! Schaden-Beispiel: Durch starke Niederschläge wird Ihr Keller überflutet und die darin stehenden Möbel (z.B. Gefrierschrank) kommen zu Schaden.</p>',
                                'value_options' => [
                                    'nein' => 'nein',
                                    'ja' => 'ja',
                                ],
                            ],
                            'attributes' => ['id' => 'elem'],
                        ],
                    ],
                    'grob' => [
                        'spec' => [
                            'type' => Select::class,
                            'name' => 'grob',
                            'options' => [
                                'label' => 'Mitversicherung der groben Fahrlässigkeit?',
                                'help_content' => '<strong>Mitversicherung der groben Fahrlässigkeit?</strong><p>Wir empfehlen dringend, die Grobe Fahrlässigkeit mitzuversichern. Ein grob fahrlässiges Verhalten liegt beispielsweise vor, wenn Täter durch geöffnete oder gekippte Fenster bzw. Türen eindringen konnten, während der Versicherungsnehmer über einen längeren Zeitraum abwesend war. Die Auswahl &quot;ja&quot; bedeutet alle Tarife mit dieser Leistung werden angezeigt und bei der Auswahl &quot;ja, nur bestmögliche&quot; werden nur die Tarife angezeigt, die eine höchst mögliche Leistung anbieten.</p>',
                                'value_options' => [
                                    'nein' => 'nein',
                                    'ja' => 'ja',
                                ],
                            ],
                            'attributes' => ['id' => 'grob'],
                        ],
                    ],
                    [
                        'spec' => [
                            'type' => Select::class,
                            'name' => 'bauart',
                            'options' => [
                                'label' => 'Bauartklasse <a href="#modal-dialog-contruction-type" data-toggle="modal">Info</a>',
                                'help_content' => '<strong>Bauartklasse Info</strong><p>Die meisten Häuser sind massiv (aus Stein, Ziegel, Beton o. ä.) gebaut und verfügen über eine harte Dachung, z.B. aus Ziegeln, Metall, Beton, Schiefer. Sollten Sie aber z.B. in einem Fachwerkhaus wohnen oder Ihr Haus mit einer weichen Dachung z.B. aus Schilf, Reed, Holz ausgestattet sein, wird ein Zuschlag fällig.</p>',
                                'value_options' => [
                                    'massive Bauweise mit harter Dachung (BAK I)' => 'massive Bauweise mit harter Dachung (BAK I)',
                                    'Stahl/Glas Bauweise mit harter Dachung (BAK II)' => 'Stahl/Glas Bauweise mit harter Dachung (BAK II)',
                                    'Holzhaus oder Lehmfachwerk mit harter Dachung (BAK III)' => 'Holzhaus oder Lehmfachwerk mit harter Dachung (BAK III)',
                                    'weiche Dachung (BAK IV oder V)' => 'weiche Dachung (BAK IV oder V)',
                                    'Fertighaus, massiv mit harter Dachung (FHG I, FHG II)' => 'Fertighaus, massiv mit harter Dachung (FHG I, FHG II)',
                                    'Fertighaus mit harter Dachung (FHG III)' => 'Fertighaus mit harter Dachung (FHG III)',
                                ],
                                'label_options' => ['disable_html_escape' => true],
                            ],
                            'attributes' => ['id' => 'bauart'],
                        ],
                    ],
                ],
            ],
        ],
        [
            'flags' => ['priority' => 19],
            'spec' => [
                'type' => ElementGroup::class,
                'name' => 'two',
                'options' => [
                    'label' => 'Rabattrelevante Angaben',
                    'label_options' => ['always_wrap' => true],
                    'label_attributes' => ['class' => 'headline-calculator'],
                    'as-card' => true,
                ],
                'elements' => [
                    [
                        'spec' => [
                            'type' => Select::class,
                            'name' => 'selbst',
                            'options' => [
                                'label' => 'Selbstbeteiligung',
                                'help_content' => '<strong>Selbstbeteiligung</strong><p>Eine evtl. vereinbarte Selbstbeteiligung führt zu Beitragsnachlässen und muss durch Sie nach jedem Schadensfall in vereinbarter Höhe selbst tragen werden.</p>',
                                'value_options' => [
                                    'nein' => '0 €',
                                    '150' => '150 €',
                                    '200' => '200 €',
                                    '250' => '250 €',
                                    '300' => '300 €',
                                ],
                            ],
                            'attributes' => ['id' => 'selbst'],
                        ],
                    ],
                    'laufzeit' => [
                        'spec' => [
                            'type' => Select::class,
                            'name' => 'laufzeit',
                            'options' => [
                                'label' => 'Laufzeit',
                                'help_content' => '<strong>Laufzeit</strong><p>Sollten Sie eine Vertragslaufzeit von 3 Jahren wählen, können Sie bei einigen Versicherern mit einem Beitragsnachlass in Höhe von 10% rechnen.</p>',
                                'value_options' => [
                                    '1' => '1 Jahr',
                                    '3' => '3 Jahre',
                                    '5' => '5 Jahre',
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
                                'help_content' => '<strong>Bestand in den letzten 5 Jahren eine Vorversicherung?</strong><p>Haben Sie eine Vorversicherung, die bislang schadenfrei verlief, können Sie von verschiedenen Versicherern einen Rabatt bis 25% erhalten.</p>',
                                'value_options' => [
                                    '' => '-- Bitte wählen --',
                                    'nein' => 'nein',
                                    'ja' => 'ja',
                                ],
                            ],
                            'attributes' => ['id' => 'vorvers5'],
                        ],
                    ],
                    [
                        'spec' => [
                            'type' => Select::class,
                            'name' => 'schaeden5',
                            'options' => [
                                'label' => 'Schäden in den letzten 5 Jahren',
                                'help_content' => '<strong>Schäden in den letzten 5 Jahren</strong><p>Bei Schadenfreiheit gibt es bei einigen Tarifen einen Nachlass.</p>',
                                'value_options' => [
                                    '' => '-- Bitte wählen --',
                                    '0' => '0',
                                    '1' => '1',
                                    '2' => '2',
                                    '3' => '3',
                                    '4' => '4',
                                ],
                            ],
                            'attributes' => ['id' => 'schaeden5'],
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
                ],
            ],
        ],
        [
            'flags' => ['priority' => 18],
            'spec' => [
                'type' => ElementGroup::class,
                'name' => 'three',
                'options' => [
                    'label' => 'Kombirabatte für folgende Sparten-Kombinationen berücksichtigen',
                    'label_options' => ['always_wrap' => true],
                    'label_attributes' => ['class' => 'headline-calculator'],
                    'as-card' => true,
                ],
                'attributes' => ['class' => 'collapse toggle-box-kombirabatte discount'],
                'elements' => [
                    [
                        'spec' => [
                            'type' => Checkbox::class,
                            'name' => 'KrPHV',
                            'options' => [
                                'label' => 'Privathaftpflicht',
                                'help_content' => '<strong>Warum fragen wir das?</strong><p>Klicken Sie hier, wenn Sie diese Versicherung schon besitzen oder diese neu beantragen möchten.</p>',
                                'use_hidden_element' => false,
                                'checked_value' => '1',
                                'unchecked_value' => '0',
                                'as-card' => true,
                            ],
                            'attributes' => ['id' => 'KrPHV'],
                        ],
                    ],
                    [
                        'spec' => [
                            'type' => Checkbox::class,
                            'name' => 'KrTIE',
                            'options' => [
                                'label' => 'Tierhalterhaftpflicht',
                                'help_content' => '<strong>Warum fragen wir das?</strong><p>Klicken Sie hier, wenn Sie diese Versicherung schon besitzen oder diese neu beantragen möchten.</p>',
                                'use_hidden_element' => false,
                                'checked_value' => '1',
                                'unchecked_value' => '0',
                                'as-card' => true,
                            ],
                            'attributes' => ['id' => 'KrTIE'],
                        ],
                    ],
                    [
                        'spec' => [
                            'type' => Checkbox::class,
                            'name' => 'KrHUG',
                            'options' => [
                                'label' => 'Haus-Grundbesitzer Haftpflicht',
                                'help_content' => '<strong>Warum fragen wir das?</strong><p>Klicken Sie hier, wenn Sie diese Versicherung schon besitzen oder diese neu beantragen möchten.</p>',
                                'use_hidden_element' => false,
                                'checked_value' => '1',
                                'unchecked_value' => '0',
                                'as-card' => true,
                            ],
                            'attributes' => ['id' => 'KrHUG'],
                        ],
                    ],
                    [
                        'spec' => [
                            'type' => Checkbox::class,
                            'name' => 'KrOEL',
                            'options' => [
                                'label' => 'Gewässerschaden/Öltank',
                                'help_content' => '<strong>Warum fragen wir das?</strong><p>Klicken Sie hier, wenn Sie diese Versicherung schon besitzen oder diese neu beantragen möchten.</p>',
                                'use_hidden_element' => false,
                                'checked_value' => '1',
                                'unchecked_value' => '0',
                                'as-card' => true,
                            ],
                            'attributes' => ['id' => 'KrOEL'],
                        ],
                    ],
                    [
                        'spec' => [
                            'type' => Checkbox::class,
                            'name' => 'KrWG',
                            'options' => [
                                'label' => 'Wohngebäude',
                                'help_content' => '<strong>Warum fragen wir das?</strong><p>Klicken Sie hier, wenn Sie diese Versicherung schon besitzen oder diese neu beantragen möchten.</p>',
                                'use_hidden_element' => false,
                                'checked_value' => '1',
                                'unchecked_value' => '0',
                                'as-card' => true,
                            ],
                            'attributes' => ['id' => 'KrWG'],
                        ],
                    ],
                    [
                        'spec' => [
                            'type' => Checkbox::class,
                            'name' => 'KrWGGLS',
                            'options' => [
                                'label' => 'Wohngebäude-Glas',
                                'help_content' => '<strong>Warum fragen wir das?</strong><p>Klicken Sie hier, wenn Sie diese Versicherung schon besitzen oder diese neu beantragen möchten.</p>',
                                'use_hidden_element' => false,
                                'checked_value' => '1',
                                'unchecked_value' => '0',
                                'as-card' => true,
                            ],
                            'attributes' => ['id' => 'KrWGGLS'],
                        ],
                    ],
                    [
                        'spec' => [
                            'type' => Checkbox::class,
                            'name' => 'KrHR',
                            'options' => [
                                'label' => 'Hausrat',
                                'help_content' => '<strong>Warum fragen wir das?</strong><p>Klicken Sie hier, wenn Sie diese Versicherung schon besitzen oder diese neu beantragen möchten.</p>',
                                'use_hidden_element' => false,
                                'checked_value' => '1',
                                'unchecked_value' => '0',
                                'as-card' => true,
                            ],
                            'attributes' => ['id' => 'KrHR'],
                        ],
                    ],
                    [
                        'spec' => [
                            'type' => Checkbox::class,
                            'name' => 'KrHRGLS',
                            'options' => [
                                'label' => 'Hausrat-Glas',
                                'help_content' => '<strong>Warum fragen wir das?</strong><p>Klicken Sie hier, wenn Sie diese Versicherung schon besitzen oder diese neu beantragen möchten.</p>',
                                'use_hidden_element' => false,
                                'checked_value' => '1',
                                'unchecked_value' => '0',
                                'as-card' => true,
                            ],
                            'attributes' => ['id' => 'KrHRGLS'],
                        ],
                    ],
                    [
                        'spec' => [
                            'type' => Checkbox::class,
                            'name' => 'KrUNF',
                            'options' => [
                                'label' => 'Unfall',
                                'help_content' => '<strong>Warum fragen wir das?</strong><p>Klicken Sie hier, wenn Sie diese Versicherung schon besitzen oder diese neu beantragen möchten.</p>',
                                'use_hidden_element' => false,
                                'checked_value' => '1',
                                'unchecked_value' => '0',
                                'as-card' => true,
                            ],
                            'attributes' => ['id' => 'KrUNF'],
                        ],
                    ],
                    [
                        'spec' => [
                            'type' => Checkbox::class,
                            'name' => 'KrRS',
                            'options' => [
                                'label' => 'Rechtsschutz',
                                'help_content' => '<strong>Warum fragen wir das?</strong><p>Klicken Sie hier, wenn Sie diese Versicherung schon besitzen oder diese neu beantragen möchten.</p>',
                                'use_hidden_element' => false,
                                'checked_value' => '1',
                                'unchecked_value' => '0',
                                'as-card' => true,
                            ],
                            'attributes' => ['id' => 'KrRS'],
                        ],
                    ],
                ],
            ],
        ],
        [
            'flags' => ['priority' => 16],
            'spec' => [
                'type' => ElementGroup::class,
                'name' => 'four',
                'options' => [
                    'label' => 'Optionale Detailfragen',
                    'label_options' => ['always_wrap' => true],
                    'label_attributes' => ['class' => 'headline-calculator'],
                    'as-card' => true,
                ],
                'attributes' => ['class' => 'collapse collapse-questions'],
                'elements' => [
                    [
                        'spec' => [
                            'type' => Links::class,
                            'name' => 'links',
                            'options' => [
                                'label' => 'Wählen Sie eine Vorgabe für Ihre Berechnung',
                                'help_content' => '<strong>Wählen Sie eine Vorgabe für Ihre Berechnung</strong><p>Die Vorgabe des Arbeitskreis Vermittlerrichlinie (AK-Empfehlung) bietet Ihnen einen empfohlenen Mindestschutz. Heute erfüllen alle wichtigen Tarife diese Vorgaben. Zu Ihrer Sicherheit sollten Sie diese Mindestvorgaben wählen.</p>',
                                'links' => [
                                    [
                                        'href' => '#',
                                        'class' => 'js-standard js-gtm-event',
                                        'data-event-type' => 'click',
                                        'data-event-category' => 'versicherung',
                                        'data-event-label' => 'hr',
                                        'data-event-action' => 'choose standard options',
                                        'label' => 'Standard',
                                    ],
                                    [
                                        'href' => '#',
                                        'class' => 'recommendation js-recommendation js-gtm-event',
                                        'data-event-type' => 'click',
                                        'data-event-category' => 'versicherung',
                                        'data-event-label' => 'hr',
                                        'data-event-action' => 'choose AK recommentations',
                                        'label' => 'AK Empfehlung',
                                    ],
                                ],
                                'separator' => '|',
                            ],
                        ],
                    ],
                    [
                        'spec' => [
                            'type' => Select::class,
                            'name' => 'uver_v',
                            'options' => [
                                'label' => 'Wünschen Sie einen Unterversicherungsverzicht?',
                                'help_content' => '<strong>Wünschen Sie einen Unter­versicherungs­verzicht?</strong><p>Eine Unterversicherung liegt vor, wenn im Schadenfall die vereinbarte Versicherungssumme niedriger ist als der tatsächlich vorhandene Wert Ihres Hausrates.</p><p>Wenn Sie keinen Unter­versicherungs­verzicht vereinbaren (je nach Anbieter 600-700 EUR/qm) kann es im Schadenfall zur Leistungskürzung kommen.</p>',
                                'value_options' => [
                                    'nein - Eingabe einer V-Summe' => 'nein - Eingabe einer V-Summe',
                                    'ja' => 'ja - empfohlen',
                                ],
                            ],
                            'attributes' => ['id' => 'uver_v'],
                        ],
                    ],
                    [
                        'spec' => [
                            'type' => Select::class,
                            'name' => 'ues_v',
                            'options' => [
                                'label' => 'Mitversicherung von Überspannungsschäden?',
                                'help_content' => '<strong>Mitversicherung von Wertsachen? Ohne Angaben sind mind. 20% der VS mitversichert.</strong><p>Meist sind 20-25% der Versicherungssumme für Wertsachen mitversichert. Bargeld, Wertpapiere, Schmuck, Briefmarken, Münzen, Sachen aus Silber/Gold/Platin, Pelze, handgeknüpfte Teppiche, Kunstgegenstände (z.B. Gemälde, Plastiken), Sachen über 100 Jahre alt. Möbelstücke fallen nicht unter Wertsachen!</p>',
                                'value_options' => [
                                    'ja' => 'ja - empfohlen',
                                    'nein' => 'nein',
                                ],
                            ],
                            'attributes' => ['id' => 'ues_v'],
                        ],
                    ],
                    [
                        'spec' => [
                            'type' => Text::class,
                            'name' => 'werts_v',
                            'options' => [
                                'label' => 'Mitversicherung von Wertsachen? Ohne Angaben sind mind. 20% der VS mitversichert.',
                                'help_content' => '<strong>Mitversicherung von Wertsachen? Ohne Angaben sind mind. 20% der VS mitversichert.</strong><p>Meist sind 20-25% der Versicherungssumme für Wertsachen mitversichert. Bargeld, Wertpapiere, Schmuck, Briefmarken, Münzen, Sachen aus Silber/Gold/Platin, Pelze, handgeknüpfte Teppiche, Kunstgegenstände (z.B. Gemälde, Plastiken), Sachen über 100 Jahre alt. Möbelstücke fallen nicht unter Wertsachen!</p>',
                            ],
                            'attributes' => [
                                'id' => 'werts_v',
                                'class' => 'form-control has-legend',
                                'min' => '0',
                            ],
                        ],
                    ],
                    [
                        'spec' => [
                            'type' => Select::class,
                            'name' => 'dieb_v',
                            'options' => [
                                'label' => 'Diebstahl von Kinderwagen und Krankenfahrstühlen?',
                                'help_content' => '<strong>Diebstahl von Kinderwagen und Krankenfahrstühlen?</strong><p>Einige Tarife schließen diesen Diebstahl bis zu einer begrenzten Summe mit ein.</p>',
                                'value_options' => [
                                    'nein' => 'nein',
                                    'ja' => 'ja',
                                ],
                            ],
                            'attributes' => ['id' => 'dieb_v'],
                        ],
                    ],
                    [
                        'spec' => [
                            'type' => Select::class,
                            'name' => 'dkfz_v',
                            'options' => [
                                'label' => 'Diebstahl aus KFZ?',
                                'help_content' => '<strong>Diebstahl aus KFZ?</strong><p>Ausgeschlossen sind meist elektronische Geräte wie Notebook und Kameras.</p>',
                                'value_options' => [
                                    'nein' => 'nein',
                                    'ja' => 'ja',
                                ],
                            ],
                            'attributes' => ['id' => 'dkfz_v'],
                        ],
                    ],
                    [
                        'spec' => [
                            'type' => Select::class,
                            'name' => 'dkle_v',
                            'options' => [
                                'label' => 'Diebstahl von Wäsche auf der Leine?',
                                'help_content' => '<strong>Diebstahl von Wäsche auf der Leine?</strong><p>Falls Ihre Wäsche von der Leine gestohlen wird, muss eine polizeiliche Meldung vorliegen, um Ersatz vom Versicherer zu erhalten.</p>',
                                'value_options' => [
                                    'nein' => 'nein',
                                    'ja' => 'ja',
                                ],
                            ],
                            'attributes' => ['id' => 'dkle_v'],
                        ],
                    ],
                    [
                        'spec' => [
                            'type' => Select::class,
                            'name' => 'rauch_v',
                            'options' => [
                                'label' => 'Schäden durch Verpuffung, Rauch und Ruß?',
                                'help_content' => '<strong>Schäden durch Verpuffung, Rauch und Ruß?</strong><p>In einer Heizung, die befeuert wird, entsteht durch sich entwickelnde Gase eine Verpuffung.</p>',
                                'value_options' => [
                                    'nein' => 'nein',
                                    'ja' => 'ja',
                                ],
                            ],
                            'attributes' => ['id' => 'rauch_v'],
                        ],
                    ],
                    [
                        'spec' => [
                            'type' => Select::class,
                            'name' => 'kfz_v',
                            'options' => [
                                'label' => 'Schäden durch Anprall von Landfahrzeugen?',
                                'help_content' => '<strong>Schäden durch Anprall von Landfahrzeugen?</strong><p>Fährt ein KFZ gegen Ihr Haus und es entsteht ein Schaden am Hausrat, wird dieser ersetzt.</p>',
                                'value_options' => [
                                    'nein' => 'nein',
                                    'ja' => 'ja',
                                ],
                            ],
                            'attributes' => ['id' => 'kfz_v'],
                        ],
                    ],
                    [
                        'spec' => [
                            'type' => Select::class,
                            'name' => 'bank_v',
                            'options' => [
                                'label' => 'Sachen in Bankgewahrsam?',
                                'help_content' => '<strong>Sachen in Bankgewahrsam?</strong><p>Wenn z.B. Ihr Schmuck aus dem Banktresor gestohlen wird.</p>',
                                'value_options' => [
                                    'nein' => 'nein',
                                    'ja' => 'ja',
                                ],
                            ],
                            'attributes' => ['id' => 'bank_v'],
                        ],
                    ],
                    [
                        'spec' => [
                            'type' => Select::class,
                            'name' => 'dgar_v',
                            'options' => [
                                'label' => 'Diebstahl von Gartenmöbeln/Geräten?',
                                'help_content' => '<strong>Diebstahl von Gartenmöbeln/Geräten?</strong><p>Falls Ihre Sitzgruppe gestohlen wird, ist diese hier mitversichert.</p>',
                                'value_options' => [
                                    'nein' => 'nein',
                                    'ja' => 'ja',
                                ],
                            ],
                            'attributes' => ['id' => 'dgar_v'],
                        ],
                    ],
                    [
                        'spec' => [
                            'type' => Select::class,
                            'name' => 'was_v',
                            'options' => [
                                'label' => 'Haben Sie Aquarien oder Wasserbetten (Wasserschäden)?',
                                'help_content' => '<strong>Haben Sie Aquarien oder Wasserbetten (Wasserschäden)?</strong><p>In der Regel werden nur die Folgen des Wasserschadens ersetzt, nicht das Wasserbett selbst.</p>',
                                'value_options' => [
                                    'nein' => 'nein',
                                    'ja' => 'ja',
                                ],
                            ],
                            'attributes' => ['id' => 'was_v'],
                        ],
                    ],
                    [
                        'spec' => [
                            'type' => Select::class,
                            'name' => 'seng_v',
                            'options' => [
                                'label' => 'Sollen Sengschäden mitversichert werden?',
                                'help_content' => '<strong>Sollen Sengschäden mitversichert werden?</strong><p>Darunter versteht man Schäden, die ohne Feuer entstehen, sondern nur durch Hitzeeinwirkung (z.B. Bügeleisen).</p>',
                                'value_options' => [
                                    'nein' => 'nein',
                                    'ja' => 'ja',
                                ],
                            ],
                            'attributes' => ['id' => 'seng_v'],
                        ],
                    ],
                    [
                        'spec' => [
                            'type' => Select::class,
                            'name' => 'waver_v',
                            'options' => [
                                'label' => 'Soll Wasserverlust infolge Rohrbruch mitversichert werden?',
                                'help_content' => '<strong>Soll Wasserverlust infolge Rohrbruch mitversichert werden?</strong><p>Läuft längere Zeit Wasser weg, wird auch das bis zur Höchstgrenze erstattet.</p>',
                                'value_options' => [
                                    'nein' => 'nein',
                                    'ja' => 'ja',
                                ],
                            ],
                            'attributes' => ['id' => 'waver_v'],
                        ],
                    ],
                    [
                        'spec' => [
                            'type' => Select::class,
                            'name' => 'hot_v',
                            'options' => [
                                'label' => 'Hotelkosten im Schadenfall?',
                                'help_content' => '<strong>Hotelkosten im Schadenfall?</strong><p>Wird Ihre Wohnung z.B. nach einem Wasserschaden unbewohnbar, werden Hotelkosten ersetzt.</p>',
                                'value_options' => [
                                    'nein' => 'nein',
                                    'ja' => 'ja',
                                ],
                            ],
                            'attributes' => ['id' => 'hot_v'],
                        ],
                    ],
                    [
                        'spec' => [
                            'type' => Select::class,
                            'name' => 'reis_v',
                            'options' => [
                                'label' => 'Rückreisekosten aus dem Urlaub?',
                                'help_content' => '<strong>Rückreisekosten aus dem Urlaub?</strong><p>Wenn ein erheblicher Versicherungsfall eintritt, werden Fahrtmehrkosten für die Rückreise aus dem Urlaub ersetzt.</p>',
                                'value_options' => [
                                    'nein' => 'nein',
                                    'ja' => 'ja',
                                ],
                            ],
                            'attributes' => ['id' => 'reis_v'],
                        ],
                    ],
                    [
                        'spec' => [
                            'type' => Select::class,
                            'name' => 'arb_v',
                            'options' => [
                                'label' => 'Sachen im häuslichen Arbeitszimmer?',
                                'help_content' => '<strong>Sachen im häuslichen Arbeitszimmer?</strong><p>Üben Sie Ihr Gewerbe aus Ihrer Wohnung aus, sind die gewerblichen Sachen bis zu einer Höchstgrenze bei diesem Einschluss mitversichert.</p>',
                                'value_options' => [
                                    'nein' => 'nein',
                                    'ja' => 'ja',
                                ],
                            ],
                            'attributes' => ['id' => 'arb_v'],
                        ],
                    ],
                    [
                        'spec' => [
                            'type' => Select::class,
                            'name' => 'evors_v',
                            'options' => [
                                'label' => 'Erweiterte Vorsorge',
                                'help_content' => '<strong>Erweiterte Vorsorge</strong><p>Kein Deckungsnachteil gegenüber Mitbewerbern im Schadenfall. Im Versicherungsfall gelten Risiken, die im Rahmen des vereinbarten Vertrages nicht eingeschlossen sind, jedoch durch einen leistungsstärkeren, allgemein und für jedermann zugänglichen Tarif zur Hausratversicherung zum Zeitpunkt des Schadeneintritts eingeschlossen wären, automatisch entsprechend den dortigen Regelungen mitversichert.</p>',
                                'value_options' => [
                                    'nein' => 'nein',
                                    'ja' => 'ja',
                                ],
                            ],
                            'attributes' => ['id' => 'evors_v'],
                        ],
                    ],
                    [
                        'spec' => [
                            'type' => Select::class,
                            'name' => 'all_v',
                            'options' => [
                                'label' => 'Wünschen Sie für Ihren Hausrat eine Allgefahrendeckung?',
                                'help_content' => '<strong>Wünschen Sie für Ihren Hausrat eine Allgefahrendeckung?</strong><p>Bei der Allgefahrendeckung handelt es sich um einen weitreichenden, über die Leistungen einer klassischen Versicherung hinausgehenden Versicherungsschutz. Auf die gewohnte Aufzählung von versicherten Gefahren, z. B. Feuer, Leitungswasser oder Sturm, wird verzichtet: Schäden - Zerstörung, Beschädigung und Abhandenkommen von versicherten Gegenständen - gelten als Folge aller Gefahren als versichert, soweit diese nicht explizit vom Versicherungsschutz ausgenommen sind.</p>',
                                'value_options' => [
                                    'nein' => 'nein',
                                    'ja' => 'ja',
                                ],
                            ],
                            'attributes' => ['id' => 'all_v'],
                        ],
                    ],
                    [
                        'spec' => [
                            'type' => Select::class,
                            'name' => 'sich_v',
                            'options' => [
                                'label' => 'Sind an allen Haus- und sonstigen Eingangstüren Sicherheitsschlösser mit von außen nicht abschraubbaren, bündig montierten Sicherheitsbeschlägen vorhanden?',
                                'help_content' => '<strong>Sind an allen Haus- und sonstigen Eingangstüren Sicherheitsschlösser mit von außen nicht abschraubbaren, bündig montierten Sicherheitsbeschlägen vorhanden?</strong><p>Im Falle eines Einbruchdiebstahls wird überprüft, ob die Türen diesen Ansprüchen gerecht werden.</p>',
                                'value_options' => [
                                    'nein' => 'nein',
                                    'ja' => 'ja',
                                ],
                            ],
                            'attributes' => ['id' => 'sich_v'],
                        ],
                    ],
                    [
                        'spec' => [
                            'type' => Select::class,
                            'name' => 'meld_v',
                            'options' => [
                                'label' => 'Ist eine vom VdS (Verband der Sachversicherer) anerkannte Einbruchmeldeanlage vorhanden?',
                                'help_content' => '<strong>Ist eine vom VdS (Verband der Sachversicherer) anerkannte Einbruchmeldeanlage vorhanden?</strong><p>Diese Anforderung ist nützlich bei hohen Versicherungssummen oder besonderen Risiken. Im Normalfall spielt eine Meldeanlage im Schadensfall keine Rolle.</p>',
                                'value_options' => [
                                    'nein' => 'nein',
                                    'ja' => 'ja',
                                ],
                            ],
                            'attributes' => ['id' => 'meld_v'],
                        ],
                    ],
                    [
                        'spec' => [
                            'type' => Select::class,
                            'name' => 'bewo_v',
                            'options' => [
                                'label' => 'Ist die Wohnung länger als 60 Tage ununterbrochen unbewohnt?',
                                'help_content' => '<strong>Ist die Wohnung länger als 60 Tage ununterbrochen unbewohnt?</strong><p>Bei einigen Gesellschaften erlischt der Versicherungsschutz, wenn die Wohnung länger als 60 Tage leer steht. Bewohnt heißt, dass eine erwachsene Person in der Wohnung übernachtet und sich dort im Normalfall aufhält.</p>',
                                'value_options' => [
                                    'nein' => 'nein',
                                    'ja' => 'ja',
                                ],
                            ],
                            'attributes' => ['id' => 'bewo_v'],
                        ],
                    ],
                    [
                        'spec' => [
                            'type' => Select::class,
                            'name' => 'tres_v',
                            'options' => [
                                'label' => 'Ist ein mehrwandiger Stahlschrank mit einem Gewicht von > 200 kg oder ein eingemauerter Tresor mit mehrwandiger Tür vorhanden?',
                                'help_content' => '<strong>Ist ein mehrwandiger Stahlschrank mit einem Gewicht von > 200 kg oder ein eingemauerter Tresor mit mehrwandiger Tür vorhanden?</strong><p>Ist im Normalfall uninteressant. Bei besonderen Risiken muss das gesondert behandelt werden.</p>',
                                'value_options' => [
                                    'nein' => 'nein',
                                    'ja' => 'ja',
                                ],
                            ],
                            'attributes' => ['id' => 'tres_v'],
                        ],
                    ],
                    [
                        'spec' => [
                            'type' => Select::class,
                            'name' => 'feu_v',
                            'options' => [
                                'label' => 'Gibt es auf dem Versicherungsgrundstück oder in einer Entfernung von unter 10 m Betriebe / Lager, von denen eine erhöhte Feuergefahr ausgeht?',
                                'help_content' => '<strong>Gibt es auf dem Versicherungsgrundstück oder in einer Entfernung von unter 10 m Betriebe / Lager, von denen eine erhöhte Feuergefahr ausgeht?</strong><p>Es ist möglich, dass Versicherer bei erhöhtem Risiko den Vertrag ablehnen.</p>',
                                'value_options' => [
                                    'nein' => 'nein',
                                    'ja' => 'ja',
                                ],
                            ],
                            'attributes' => ['id' => 'feu_v'],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'elements' => [
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
                'type' => Checkbox::class,
                'name' => 'chkErstinfo',
                'options' => [
                    'label' => 'Ich bestätige, die Erstinformation für Versicherungsmakler gemäß § 15 VersVermV heruntergeladen und gelesen zu haben.',
                    'use_hidden_element' => true,
                    'checked_value' => '1',
                    'unchecked_value' => '0',
                    'as-card' => true,
                ],
                'attributes' => ['id' => 'mrmoErstinfo'],
            ],
        ],
        [
            'spec' => [
                'type' => Button::class,
                'name' => 'btn_berechnen',
                'options' => ['label' => 'Berechnen'],
                'attributes' => [
                    'type' => 'submit',
                    'class' => 'btn btn-default js-gtm-event',
                    'data-event-type' => 'click',
                    'data-event-category' => 'versicherung',
                    'data-event-action' => 'calculate',
                    'data-event-label' => 'hr',
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
                'type' => Hidden::class,
                'name' => 'zahlweise',
            ],
        ],
        [
            'spec' => [
                'type' => Hidden::class,
                'name' => 'chkFilterNachhaltigkeit',
            ],
        ],
        [
            'spec' => [
                'type' => Hidden::class,
                'name' => 'addressUri',
                'attributes' => ['id' => 'addressUri'],
            ],
        ],
    ],
    'input_filter' => [
        'plz' => ['required' => true],
        'ort' => [
            'required' => false,
            'validators' => [
                [
                    'name' => NotEmpty::class,
                ],
            ],
        ],
        'strasse' => [
            'required' => false,
            'validators' => [
                [
                    'name' => NotEmpty::class,
                ],
            ],
        ],
        'hnr' => ['required' => false],
        'vermietet' => ['required' => true],
        'versbeginn' => ['required' => true],
        'versbeginn_datum' => ['required' => false],
        'gebdatum' => ['required' => true],
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
        'selbst' => ['required' => false],
        'zahlweise' => ['required' => false],
        'wohnfl' => ['required' => true],
        'whg' => ['required' => true],
    ],
];
