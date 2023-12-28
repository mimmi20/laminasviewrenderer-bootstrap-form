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

namespace Mimmi20\LaminasView\BootstrapForm;

use DateTime;
use IntlDateFormatter;

trait FormDateSelectTrait
{
    /**
     * Create a key => value options for days
     *
     * @param string $pattern Pattern to use for days
     *
     * @return array<int|string, array<string, string>>
     *
     * @throws void
     *
     *
     */
    private function getDaysOptions(string $pattern): array
    {
        $keyFormatter   = new IntlDateFormatter(
            $this->getLocale(),
            IntlDateFormatter::NONE,
            IntlDateFormatter::NONE,
            null,
            null,
            'dd',
        );
        $valueFormatter = new IntlDateFormatter(
            $this->getLocale(),
            IntlDateFormatter::NONE,
            IntlDateFormatter::NONE,
            null,
            null,
            $pattern,
        );
        $date           = new DateTime('1970-01-01');

        $result = [];

        for ($day = 1; 31 >= $day; ++$day) {
            $key = $keyFormatter->format($date->getTimestamp());

            if ($key === false) {
                continue;
            }

            $value = $valueFormatter->format($date->getTimestamp());

            if ($value === false) {
                continue;
            }

            $result[$key] = ['value' => $key, 'label' => $value];

            $date->modify('+1 day');
        }

        return $result;
    }
}
