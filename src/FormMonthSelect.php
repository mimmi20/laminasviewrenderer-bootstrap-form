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

namespace Mimmi20\LaminasView\BootstrapForm;

use Laminas\Form\Element\MonthSelect as MonthSelectElement;
use Laminas\Form\ElementInterface;
use Laminas\Form\Exception\DomainException;
use Laminas\Form\Exception\InvalidArgumentException;
use Laminas\Form\View\Helper\FormMonthSelect as BaseFormMonthSelect;
use Override;

use function get_debug_type;
use function implode;
use function is_numeric;
use function sprintf;

use const PHP_EOL;

final class FormMonthSelect extends BaseFormMonthSelect implements FormIndentInterface, FormRenderInterface
{
    use FormTrait;

    /**
     * Render a month element that is composed of two selects
     *
     * @throws InvalidArgumentException
     * @throws DomainException
     */
    #[Override]
    public function render(ElementInterface $element): string
    {
        if (!$element instanceof MonthSelectElement) {
            throw new InvalidArgumentException(
                sprintf(
                    '%s requires that the element is of type %s, but was %s',
                    __METHOD__,
                    MonthSelectElement::class,
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

        $selectHelper = $this->getSelectElementHelper();
        $pattern      = $this->parsePattern($element->shouldRenderDelimiters());

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

        if ($selectHelper instanceof FormIndentInterface) {
            $selectHelper->setIndent($indent);
        }

        $data                    = [];
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
