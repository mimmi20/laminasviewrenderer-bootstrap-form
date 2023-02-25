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

use IntlDateFormatter;
use Laminas\Form\Element\MonthSelect as MonthSelectElement;
use Laminas\Form\ElementInterface;
use Laminas\Form\Exception;
use Laminas\Form\Exception\DomainException;
use Laminas\Form\Exception\InvalidArgumentException;
use Laminas\Form\View\Helper\AbstractHelper;

use function implode;
use function is_numeric;
use function sprintf;

use const PHP_EOL;

final class FormMonthSelect extends AbstractHelper implements FormIndentInterface, FormRenderInterface
{
    use FormMonthSelectTrait;
    use FormTrait;

    /**
     * Invoke helper as function
     *
     * Proxies to {@link render()}.
     *
     * @return self|string
     *
     * @throws DomainException
     * @throws InvalidArgumentException
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

        if (null !== $locale) {
            $this->setLocale($locale);
        }

        return $this->render($element);
    }

    /**
     * Render a month element that is composed of two selects
     *
     * @throws Exception\InvalidArgumentException
     * @throws Exception\DomainException
     */
    public function render(ElementInterface $element): string
    {
        if (!$element instanceof MonthSelectElement) {
            throw new Exception\InvalidArgumentException(
                sprintf(
                    '%s requires that the element is of type %s',
                    __METHOD__,
                    MonthSelectElement::class,
                ),
            );
        }

        $name = $element->getName();

        if (null === $name || '' === $name) {
            throw new Exception\DomainException(
                sprintf(
                    '%s requires that the element has an assigned name; none discovered',
                    __METHOD__,
                ),
            );
        }

        $pattern = $this->parsePattern($element->shouldRenderDelimiters());

        // The pattern always contains "day" part and the first separator, so we have to remove it
        unset($pattern['day'], $pattern[0]);

        $monthsOptions = $this->getMonthsOptions($pattern['month']);
        $yearOptions   = $this->getYearsOptions($element->getMinYear(), $element->getMaxYear());

        $monthElement = $element->getMonthElement()->setValueOptions($monthsOptions);
        $yearElement  = $element->getYearElement()->setValueOptions($yearOptions);

        if ($element->shouldCreateEmptyOption()) {
            $monthElement->setEmptyOption('');
            $yearElement->setEmptyOption('');
        }

        $indent = $this->getIndent();
        $this->selectHelper->setIndent($indent);

        $data                    = [];
        $data[$pattern['month']] = $this->selectHelper->render($monthElement);
        $data[$pattern['year']]  = $this->selectHelper->render($yearElement);

        $markups = [];

        foreach ($pattern as $key => $value) {
            // Delimiter
            if (is_numeric($key)) {
                $markups[] = $indent . $value;
            } else {
                $markups[] = $data[$value];
            }
        }

        return $indent . PHP_EOL . implode(PHP_EOL, $markups) . PHP_EOL . $indent;
    }
}
