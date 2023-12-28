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
use Laminas\Form\Element\DateTimeSelect as DateTimeSelectElement;
use Laminas\Form\ElementInterface;
use Laminas\Form\Exception;
use Laminas\Form\View\Helper\AbstractHelper;

use function extension_loaded;
use function implode;
use function is_numeric;
use function mb_stripos;
use function mb_strpos;
use function preg_split;
use function rtrim;
use function sprintf;
use function str_replace;

use const PHP_EOL;
use const PREG_SPLIT_DELIM_CAPTURE;
use const PREG_SPLIT_NO_EMPTY;

/**
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 *
 */
final class FormDateTimeSelect extends AbstractHelper implements FormIndentInterface, FormRenderInterface
{
    use FormDateSelectTrait;
    use FormMonthSelectTrait;
    use FormTrait;

    /**
     * Time formatter to use
     */
    private int $timeType;

    /** @throws Exception\ExtensionNotLoadedException if ext/intl is not present */
    public function __construct(FormSelectInterface $selectHelper)
    {
        if (!extension_loaded('intl')) {
            throw new Exception\ExtensionNotLoadedException(
                sprintf(
                    '%s component requires the intl PHP extension',
                    __NAMESPACE__,
                ),
            );
        }

        $this->selectHelper = $selectHelper;

        // Delaying initialization until we know ext/intl is available
        $this->dateType = IntlDateFormatter::LONG;

        // Delaying initialization until we know ext/intl is available
        $this->timeType = IntlDateFormatter::LONG;
    }

    /**
     * Invoke helper as function
     *
     * Proxies to {@link render()}.
     *
     * @return self|string
     *
     * @throws Exception\InvalidArgumentException
     * @throws Exception\DomainException
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingNativeTypeHint
     */
    public function __invoke(
        ElementInterface | null $element = null,
        int $dateType = IntlDateFormatter::LONG,
        int $timeType = IntlDateFormatter::LONG,
        string | null $locale = null,
    ) {
        if (!$element) {
            return $this;
        }

        $this->setDateType($dateType);
        $this->setTimeType($timeType);

        if ($locale !== null) {
            $this->setLocale($locale);
        }

        return $this->render($element);
    }

    /**
     * Render a date element that is composed of six selects
     *
     * @throws Exception\InvalidArgumentException
     * @throws Exception\DomainException
     */
    public function render(ElementInterface $element): string
    {
        if (!$element instanceof DateTimeSelectElement) {
            throw new Exception\InvalidArgumentException(
                sprintf(
                    '%s requires that the element is of type %s',
                    __METHOD__,
                    DateTimeSelectElement::class,
                ),
            );
        }

        $name = $element->getName();

        if ($name === null || $name === '') {
            throw new Exception\DomainException(
                sprintf(
                    '%s requires that the element has an assigned name; none discovered',
                    __METHOD__,
                ),
            );
        }

        $shouldRenderDelimiters = $element->shouldRenderDelimiters();
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
        $this->selectHelper->setIndent($indent);

        $data                     = [];
        $data[$pattern['day']]    = $this->selectHelper->render($dayElement);
        $data[$pattern['month']]  = $this->selectHelper->render($monthElement);
        $data[$pattern['year']]   = $this->selectHelper->render($yearElement);
        $data[$pattern['hour']]   = $this->selectHelper->render($hourElement);
        $data[$pattern['minute']] = $this->selectHelper->render($minuteElement);

        if ($element->shouldShowSeconds()) {
            $secondOptions = $this->getSecondsOptions($pattern['second']);
            $secondElement = $element->getSecondElement()->setValueOptions($secondOptions);

            if ($element->shouldCreateEmptyOption()) {
                $secondElement->setEmptyOption('');
            }

            $data[$pattern['second']] = $this->selectHelper->render($secondElement);
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
     * @throws void
     *
     *
     */
    public function setTimeType(int $timeType): self
    {
        // The FULL format uses values that are not used
        if ($timeType === IntlDateFormatter::FULL) {
            $timeType = IntlDateFormatter::LONG;
        }

        $this->timeType = $timeType;

        return $this;
    }

    /**
     * @throws void
     *
     *
     */
    public function getTimeType(): int
    {
        return $this->timeType;
    }

    /**
     * Override to also get time part
     *
     * @throws void
     */
    public function getPattern(): string
    {
        if ($this->pattern === null) {
            $intl = new IntlDateFormatter($this->getLocale(), $this->dateType, $this->timeType);
            // remove time zone format character
            $this->pattern = rtrim($intl->getPattern(), ' z');
        }

        return $this->pattern;
    }

    /**
     * Parse the pattern
     *
     * @return array<int|string, string>
     *
     * @throws void
     */
    private function parsePattern(bool $renderDelimiters = true): array
    {
        $pattern    = $this->getPattern();
        $pregResult = preg_split(
            '/([ -,.:\\/]*\'.*?\'[ -,.:\\/]*)|([ -,.:\\/]+)/',
            $pattern,
            -1,
            PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY,
        );

        if ($pregResult === false) {
            return [];
        }

        $result = [];

        foreach ($pregResult as $value) {
            if (mb_stripos($value, '\'') === false && mb_stripos($value, 'd') !== false) {
                $result['day'] = $value;
            } elseif (mb_stripos($value, '\'') === false && mb_strpos($value, 'M') !== false) {
                $result['month'] = $value;
            } elseif (mb_stripos($value, '\'') === false && mb_stripos($value, 'y') !== false) {
                $result['year'] = $value;
            } elseif (mb_stripos($value, '\'') === false && mb_stripos($value, 'h') !== false) {
                $result['hour'] = $value;
            } elseif (mb_stripos($value, '\'') === false && mb_stripos($value, 'm') !== false) {
                $result['minute'] = $value;
            } elseif (mb_stripos($value, '\'') === false && mb_strpos($value, 's') !== false) {
                $result['second'] = $value;
            } elseif (mb_stripos($value, '\'') === false && mb_stripos($value, 'a') !== false) {
                // ignore ante/post meridiem marker
                continue;
            } elseif ($renderDelimiters) {
                $result[] = str_replace('\'', '', $value);
            }
        }

        return $result;
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
    private function getHoursOptions(string $pattern): array
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
    private function getMinutesOptions(string $pattern): array
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
    private function getSecondsOptions(string $pattern): array
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
