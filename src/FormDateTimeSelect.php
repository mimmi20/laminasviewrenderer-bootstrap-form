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

namespace Mimmi20\LaminasView\BootstrapForm;

use DateTime;
use IntlDateFormatter;
use Laminas\Form\Element\DateTimeSelect as DateTimeSelectElement;
use Laminas\Form\ElementInterface;
use Laminas\Form\Exception\DomainException;
use Laminas\Form\Exception\InvalidArgumentException;
use Laminas\Form\View\Helper\FormDateTimeSelect as BaseFormDateTimeSelect;
use Override;

use function array_key_exists;
use function get_debug_type;
use function implode;
use function is_numeric;
use function sprintf;

use const PHP_EOL;

/** @SuppressWarnings(PHPMD.ExcessiveClassComplexity) */
final class FormDateTimeSelect extends BaseFormDateTimeSelect implements FormIndentInterface, FormRenderInterface
{
    use FormDateSelectTrait;
    use FormTrait;

    /**
     * Render a date element that is composed of six selects
     *
     * @throws InvalidArgumentException
     * @throws DomainException
     */
    #[Override]
    public function render(ElementInterface $element): string
    {
        if (!$element instanceof DateTimeSelectElement) {
            throw new InvalidArgumentException(
                sprintf(
                    '%s requires that the element is of type %s, but was %s',
                    __METHOD__,
                    DateTimeSelectElement::class,
                    get_debug_type($element),
                ),
            );
        }

        $name = $element->getName();

        if ($name === null || $name === '') {
            throw new DomainException(
                sprintf(
                    '%s requires that the element has an assigned name; none discovered',
                    __METHOD__,
                ),
            );
        }

        $shouldRenderDelimiters = $element->shouldRenderDelimiters();
        $selectHelper           = $this->getSelectElementHelper();
        $pattern                = $this->parsePattern($shouldRenderDelimiters);

        $daysOptions   = $this->getDaysOptions($pattern['day']);
        $monthsOptions = $this->getMonthsOptions($pattern['month']);
        $yearOptions   = $this->getYearsOptions($element->getMinYear(), $element->getMaxYear());
        $hourOptions   = $this->getHoursOptions($pattern['hour']);
        $minuteOptions = $this->getMinutesOptions($pattern['minute']);

        $dayElement    = $element->getDayElement()->setValueOptions($daysOptions);
        $monthElement  = $element->getMonthElement()->setValueOptions($monthsOptions);
        $yearElement   = $element->getYearElement()->setValueOptions($yearOptions);
        $hourElement   = $element->getHourElement()->setValueOptions($hourOptions);
        $minuteElement = $element->getMinuteElement()->setValueOptions($minuteOptions);

        if ($element->shouldCreateEmptyOption()) {
            $dayElement->setEmptyOption('');
            $yearElement->setEmptyOption('');
            $monthElement->setEmptyOption('');
            $hourElement->setEmptyOption('');
            $minuteElement->setEmptyOption('');
        }

        $indent = $this->getIndent();

        if ($selectHelper instanceof FormIndentInterface) {
            $selectHelper->setIndent($indent);
        }

        $data                     = [];
        $data[$pattern['day']]    = $selectHelper->render($dayElement);
        $data[$pattern['month']]  = $selectHelper->render($monthElement);
        $data[$pattern['year']]   = $selectHelper->render($yearElement);
        $data[$pattern['hour']]   = $selectHelper->render($hourElement);
        $data[$pattern['minute']] = $selectHelper->render($minuteElement);

        if ($element->shouldShowSeconds() && array_key_exists('second', $pattern)) {
            $secondOptions = $this->getSecondsOptions($pattern['second']);
            $secondElement = $element->getSecondElement()->setValueOptions($secondOptions);

            if ($element->shouldCreateEmptyOption()) {
                $secondElement->setEmptyOption('');
            }

            $data[$pattern['second']] = $selectHelper->render($secondElement);
        } else {
            unset($pattern['second']);

            if ($shouldRenderDelimiters) {
                unset($pattern[4]);
            }
        }

        $markups = [];

        foreach ($pattern as $key => $value) {
            // Delimiter
            $markups[] = is_numeric($key) ? $indent . $value : $data[$value];
        }

        return $indent . PHP_EOL . implode(PHP_EOL, $markups) . PHP_EOL . $indent;
    }

    /**
     * Create a key => value options for hours
     *
     * @param string $pattern Pattern to use for hours
     *
     * @return array<int|string, array<string, string>>
     *
     * @throws void
     */
    #[Override]
    protected function getHoursOptions(string $pattern): array
    {
        $keyFormatter   = new IntlDateFormatter(
            $this->getLocale(),
            IntlDateFormatter::NONE,
            IntlDateFormatter::NONE,
            null,
            null,
            'HH',
        );
        $valueFormatter = new IntlDateFormatter(
            $this->getLocale(),
            IntlDateFormatter::NONE,
            IntlDateFormatter::NONE,
            null,
            null,
            $pattern,
        );
        $date           = new DateTime('1970-01-01 00:00:00');

        $result = [];

        for ($hour = 1; 24 >= $hour; ++$hour) {
            $key = $keyFormatter->format($date);

            if ($key === false) {
                continue;
            }

            $value = $valueFormatter->format($date);

            if ($value === false) {
                continue;
            }

            $result[$key] = ['value' => $key, 'label' => $value];

            $date->modify('+1 hour');
        }

        return $result;
    }

    /**
     * Create a key => value options for minutes
     *
     * @param string $pattern Pattern to use for minutes
     *
     * @return array<int|string, array<string, string>>
     *
     * @throws void
     */
    #[Override]
    protected function getMinutesOptions(string $pattern): array
    {
        $keyFormatter   = new IntlDateFormatter(
            $this->getLocale(),
            IntlDateFormatter::NONE,
            IntlDateFormatter::NONE,
            null,
            null,
            'mm',
        );
        $valueFormatter = new IntlDateFormatter(
            $this->getLocale(),
            IntlDateFormatter::NONE,
            IntlDateFormatter::NONE,
            null,
            null,
            $pattern,
        );
        $date           = new DateTime('1970-01-01 00:00:00');

        $result = [];

        for ($min = 1; 60 >= $min; ++$min) {
            $key = $keyFormatter->format($date);

            if ($key === false) {
                continue;
            }

            $value = $valueFormatter->format($date);

            if ($value === false) {
                continue;
            }

            $result[$key] = ['value' => $key, 'label' => $value];

            $date->modify('+1 minute');
        }

        return $result;
    }

    /**
     * Create a key => value options for seconds
     *
     * @param string $pattern Pattern to use for seconds
     *
     * @return array<int|string, array<string, string>>
     *
     * @throws void
     */
    #[Override]
    protected function getSecondsOptions(string $pattern): array
    {
        $keyFormatter   = new IntlDateFormatter(
            $this->getLocale(),
            IntlDateFormatter::NONE,
            IntlDateFormatter::NONE,
            null,
            null,
            'ss',
        );
        $valueFormatter = new IntlDateFormatter(
            $this->getLocale(),
            IntlDateFormatter::NONE,
            IntlDateFormatter::NONE,
            null,
            null,
            $pattern,
        );
        $date           = new DateTime('1970-01-01 00:00:00');

        $result = [];

        for ($sec = 1; 60 >= $sec; ++$sec) {
            $key = $keyFormatter->format($date);

            if ($key === false) {
                continue;
            }

            $value = $valueFormatter->format($date);

            if ($value === false) {
                continue;
            }

            $result[$key] = ['value' => $key, 'label' => $value];

            $date->modify('+1 second');
        }

        return $result;
    }
}
