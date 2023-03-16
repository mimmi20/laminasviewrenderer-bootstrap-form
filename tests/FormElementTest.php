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

namespace Mimmi20Test\LaminasView\BootstrapForm;

use Laminas\Form\Element;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\Checkbox;
use Laminas\Form\Element\Collection;
use Laminas\Form\Element\Color;
use Laminas\Form\Element\Date;
use Laminas\Form\Element\DateSelect;
use Laminas\Form\Element\DateTime;
use Laminas\Form\Element\DateTimeLocal;
use Laminas\Form\Element\DateTimeSelect;
use Laminas\Form\Element\Email;
use Laminas\Form\Element\File;
use Laminas\Form\Element\Hidden;
use Laminas\Form\Element\Image;
use Laminas\Form\Element\Month;
use Laminas\Form\Element\MonthSelect;
use Laminas\Form\Element\MultiCheckbox;
use Laminas\Form\Element\Number;
use Laminas\Form\Element\Password;
use Laminas\Form\Element\Radio;
use Laminas\Form\Element\Range;
use Laminas\Form\Element\Search;
use Laminas\Form\Element\Select;
use Laminas\Form\Element\Submit;
use Laminas\Form\Element\Tel;
use Laminas\Form\Element\Text;
use Laminas\Form\Element\Textarea;
use Laminas\Form\Element\Time;
use Laminas\Form\Element\Url;
use Laminas\Form\Element\Week;
use Laminas\Form\ElementInterface;
use Laminas\Form\Exception\InvalidArgumentException;
use Laminas\ServiceManager\Exception\InvalidServiceException;
use Laminas\ServiceManager\Exception\ServiceNotFoundException;
use Laminas\View\HelperPluginManager;
use Mimmi20\Form\Links\Element\Links;
use Mimmi20\Form\Paragraph\Element\Paragraph;
use Mimmi20\LaminasView\BootstrapForm\FormCollectionInterface;
use Mimmi20\LaminasView\BootstrapForm\FormElement;
use Mimmi20\LaminasView\BootstrapForm\FormInputInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;

use function assert;

final class FormElementTest extends TestCase
{
    /** @throws Exception */
    public function testSetGetInden1(): void
    {
        $helperPluginManager = $this->getMockBuilder(HelperPluginManager::class)
            ->disableOriginalConstructor()
            ->getMock();
        $helperPluginManager->expects(self::never())
            ->method('get');

        $helper = new FormElement($helperPluginManager);

        self::assertSame($helper, $helper->setIndent(4));
        self::assertSame('    ', $helper->getIndent());
    }

    /** @throws Exception */
    public function testSetGetInden2(): void
    {
        $helperPluginManager = $this->getMockBuilder(HelperPluginManager::class)
            ->disableOriginalConstructor()
            ->getMock();
        $helperPluginManager->expects(self::never())
            ->method('get');

        $helper = new FormElement($helperPluginManager);

        self::assertSame($helper, $helper->setIndent('  '));
        self::assertSame('  ', $helper->getIndent());
    }

    /** @throws Exception */
    public function testSetGetDefaultHelper(): void
    {
        $defaultHelper = 'xyz';

        $helperPluginManager = $this->getMockBuilder(HelperPluginManager::class)
            ->disableOriginalConstructor()
            ->getMock();
        $helperPluginManager->expects(self::never())
            ->method('get');

        $helper = new FormElement($helperPluginManager);

        self::assertSame($helper, $helper->setDefaultHelper($defaultHelper));
        self::assertSame($defaultHelper, $helper->getDefaultHelper());
    }

    /** @throws Exception */
    public function testAddType(): void
    {
        $helperPluginManager = $this->getMockBuilder(HelperPluginManager::class)
            ->disableOriginalConstructor()
            ->getMock();
        $helperPluginManager->expects(self::never())
            ->method('get');

        $helper = new FormElement($helperPluginManager);

        self::assertSame($helper, $helper->addType('xyz', 'abc'));
    }

    /** @throws Exception */
    public function testAddClass(): void
    {
        $helperPluginManager = $this->getMockBuilder(HelperPluginManager::class)
            ->disableOriginalConstructor()
            ->getMock();
        $helperPluginManager->expects(self::never())
            ->method('get');

        $helper = new FormElement($helperPluginManager);

        self::assertSame($helper, $helper->addClass('xyz', 'abc'));
    }

    /**
     * @return array<int, array<int, ElementInterface|string>>
     *
     * @throws InvalidArgumentException
     */
    public static function providerRender(): array
    {
        return [
            [
                new Button(),
                'formButton',
                FormInputInterface::class,
                '<button>',
            ],
            [
                new Collection(),
                'formCollection',
                FormCollectionInterface::class,
                '<collection>',
            ],
            [
                new DateTimeSelect(),
                'formDateTimeSelect',
                FormInputInterface::class,
                '<date-time-select>',
            ],
            [
                new DateSelect(),
                'formDateSelect',
                FormInputInterface::class,
                '<date-select>',
            ],
            [
                new MonthSelect(),
                'formMonthSelect',
                FormInputInterface::class,
                '<month-select>',
            ],
            [
                new Links(),
                'formLinks',
                FormInputInterface::class,
                '<links>',
            ],
            [
                new Checkbox(),
                'formCheckbox',
                FormInputInterface::class,
                '<checkbox>',
            ],
            [
                new Color(),
                'formColor',
                FormInputInterface::class,
                '<color>',
            ],
            [
                new Date(),
                'formDate',
                FormInputInterface::class,
                '<date>',
            ],
            [
                new DateTimeLocal(),
                'formDatetimeLocal',
                FormInputInterface::class,
                '<datetimelocal>',
            ],
            [
                new Email(),
                'formEmail',
                FormInputInterface::class,
                '<email>',
            ],
            [
                new File(),
                'formFile',
                FormInputInterface::class,
                '<file>',
            ],
            [
                new Hidden(),
                'formHidden',
                FormInputInterface::class,
                '<hidden>',
            ],
            [
                new Image(),
                'formImage',
                FormInputInterface::class,
                '<image>',
            ],
            [
                new Month(),
                'formMonth',
                FormInputInterface::class,
                '<month>',
            ],
            [
                new MultiCheckbox(),
                'formMultiCheckbox',
                FormInputInterface::class,
                '<multicheckbox>',
            ],
            [
                new Number(),
                'formNumber',
                FormInputInterface::class,
                '<number>',
            ],
            [
                new Password(),
                'formPassword',
                FormInputInterface::class,
                '<password>',
            ],
            [
                new Radio(),
                'formRadio',
                FormInputInterface::class,
                '<radio>',
            ],
            [
                new Range(),
                'formRange',
                FormInputInterface::class,
                '<range>',
            ],
            [
                new Search(),
                'formSearch',
                FormInputInterface::class,
                '<search>',
            ],
            [
                new Select(),
                'formSelect',
                FormInputInterface::class,
                '<select>',
            ],
            [
                new Submit(),
                'formSubmit',
                FormInputInterface::class,
                '<submit>',
            ],
            [
                new Tel(),
                'formTel',
                FormInputInterface::class,
                '<tel>',
            ],
            [
                new Text(),
                'formText',
                FormInputInterface::class,
                '<text>',
            ],
            [
                new Textarea(),
                'formTextarea',
                FormInputInterface::class,
                '<textarea>',
            ],
            [
                new Time(),
                'formTime',
                FormInputInterface::class,
                '<time>',
            ],
            [
                new Url(),
                'formUrl',
                FormInputInterface::class,
                '<url>',
            ],
            [
                new Week(),
                'formWeek',
                FormInputInterface::class,
                '<week>',
            ],
            [
                new DateTime(),
                'formDatetime',
                FormInputInterface::class,
                '<datetime>',
            ],
            [
                new class () extends Element {
                },
                'formInput',
                FormInputInterface::class,
                '<custom>',
            ],
            [
                new Paragraph(),
                'formParagraph',
                FormInputInterface::class,
                '<paragraph>',
            ],
        ];
    }

    /**
     * @throws Exception
     * @throws ServiceNotFoundException
     * @throws InvalidServiceException
     * @throws InvalidArgumentException
     */
    #[DataProvider('providerRender')]
    public function testRender(
        ElementInterface $element,
        string $helperType,
        string $class,
        string $rendered,
    ): void {
        $subHelper = $this->getMockBuilder($class)
            ->disableOriginalConstructor()
            ->getMock();
        $subHelper->expects(self::once())
            ->method('setIndent')
            ->with('');
        $subHelper->expects(self::once())
            ->method('render')
            ->with($element)
            ->willReturn($rendered);

        $helperPluginManager = $this->getMockBuilder(HelperPluginManager::class)
            ->disableOriginalConstructor()
            ->getMock();
        $helperPluginManager->expects(self::once())
            ->method('get')
            ->with($helperType)
            ->willReturn($subHelper);

        $helper = new FormElement($helperPluginManager);

        self::assertSame($rendered, $helper->render($element));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws ServiceNotFoundException
     * @throws InvalidServiceException
     */
    public function testInvoke1(): void
    {
        $element    = new Text();
        $helperType = 'formText';
        $rendered   = '<text>';

        $subHelper = $this->getMockBuilder(FormInputInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $subHelper->expects(self::once())
            ->method('setIndent')
            ->with('');
        $subHelper->expects(self::once())
            ->method('render')
            ->with($element)
            ->willReturn($rendered);

        $helperPluginManager = $this->getMockBuilder(HelperPluginManager::class)
            ->disableOriginalConstructor()
            ->getMock();
        $helperPluginManager->expects(self::once())
            ->method('get')
            ->with($helperType)
            ->willReturn($subHelper);

        $helper = new FormElement($helperPluginManager);

        $helperObject = $helper();

        assert($helperObject instanceof FormElement);

        self::assertSame($rendered, $helperObject->render($element));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testInvoke2(): void
    {
        $element    = new Text();
        $helperType = 'formText';
        $rendered   = '<text>';

        $subHelper = $this->getMockBuilder(FormInputInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $subHelper->expects(self::once())
            ->method('setIndent')
            ->with('');
        $subHelper->expects(self::once())
            ->method('render')
            ->with($element)
            ->willReturn($rendered);

        $helperPluginManager = $this->getMockBuilder(HelperPluginManager::class)
            ->disableOriginalConstructor()
            ->getMock();
        $helperPluginManager->expects(self::once())
            ->method('get')
            ->with($helperType)
            ->willReturn($subHelper);

        $helper = new FormElement($helperPluginManager);

        self::assertSame($rendered, $helper($element));
    }
}
