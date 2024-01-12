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

use IntlDateFormatter;
use Laminas\Form\Element\DateSelect as DateSelectElement;
use Laminas\Form\ElementInterface;
use Laminas\Form\Exception\DomainException;
use Laminas\Form\Exception\InvalidArgumentException;
use Laminas\Form\View\Helper\AbstractHelper;
use Laminas\I18n\Exception\RuntimeException;

use function get_debug_type;
use function implode;
use function is_numeric;
use function sprintf;

use const PHP_EOL;

final class FormDateSelect extends AbstractHelper implements FormIndentInterface, FormRenderInterface
{
    use FormDateSelectTrait;
    use FormMonthSelectTrait;
    use FormTrait;
    use SelectHelperTrait;

    /**
     * Invoke helper as function
     *
     * Proxies to {@link render()}.
     *
     * @return self|string
     *
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws \Laminas\View\Exception\InvalidArgumentException
     * @throws RuntimeException
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingNativeTypeHint
     */
    public function __invoke(
        ElementInterface | null $element = null,
        int $dateType = IntlDateFormatter::LONG,
        string | null $locale = null,
    ) {
        if (!$element) {
            return $this;
        }

        $this->setDateType($dateType);

        if ($locale !== null) {
            $this->setLocale($locale);
        }

        return $this->render($element);
    }

    /**
     * Render a date element that is composed of three selects
     *
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws \Laminas\View\Exception\InvalidArgumentException
     * @throws RuntimeException
     */
    public function render(ElementInterface $element): string
    {
        if (!$element instanceof DateSelectElement) {
            throw new InvalidArgumentException(
                sprintf(
                    '%s requires that the element is of type %s, but was %s',
                    __METHOD__,
                    DateSelectElement::class,
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

        $selectHelper = $this->getSelectHelper();
        $pattern      = $this->parsePattern($element->shouldRenderDelimiters());

        $daysOptions   = $this->getDaysOptions($pattern['day']);
        $monthsOptions = $this->getMonthsOptions($pattern['month']);
        $yearOptions   = $this->getYearsOptions($element->getMinYear(), $element->getMaxYear());

        $dayElement   = $element->getDayElement()->setValueOptions($daysOptions);
        $monthElement = $element->getMonthElement()->setValueOptions($monthsOptions);
        $yearElement  = $element->getYearElement()->setValueOptions($yearOptions);

        if ($element->shouldCreateEmptyOption()) {
            $dayElement->setEmptyOption('');
            $yearElement->setEmptyOption('');
            $monthElement->setEmptyOption('');
        }

        $indent = $this->getIndent();
        $selectHelper->setIndent($indent);

        $data                    = [];
        $data[$pattern['day']]   = $selectHelper->render($dayElement);
        $data[$pattern['month']] = $selectHelper->render($monthElement);
        $data[$pattern['year']]  = $selectHelper->render($yearElement);

        $markups = [];

        foreach ($pattern as $key => $value) {
            // Delimiter
            $markups[] = is_numeric($key) ? $indent . $value : $data[$value];
        }

        return $indent . PHP_EOL . implode(PHP_EOL, $markups) . PHP_EOL . $indent;
    }
}
