<?php

namespace Fastbolt\SonataAdminProtectedFields\Tests\Unit\Protection;

use Fastbolt\SonataAdminProtectedFields\Exception\CheckerNotFoundException;
use Fastbolt\SonataAdminProtectedFields\Form\FormFieldProvider;
use Fastbolt\SonataAdminProtectedFields\Mapping\Attributes\WriteProtected;
use Fastbolt\SonataAdminProtectedFields\Protection\Checker\Checker;
use Fastbolt\SonataAdminProtectedFields\Protection\DefaultProtector;
use Fastbolt\TestHelpers\BaseTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Builder\FormContractorInterface;
use Sonata\AdminBundle\Form\FormMapper;
use stdClass;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @covers \Fastbolt\SonataAdminProtectedFields\Protection\DefaultProtector
 */
class DefaultProtectorTest extends BaseTestCase
{
    /**
     * @var FormContractorInterface&MockObject
     */
    private $formContractor;

    /**
     * @var FormBuilderInterface&MockObject
     */
    private $builder;

    /**
     * @var AdminInterface&MockObject
     */
    private $admin;

    /**
     * @var Checker&MockObject
     */
    private $checker;

    /**
     * @var FormFieldProvider&MockObject
     */
    private $formFieldProvider;

    /**
     * @var FormMapper
     */
    private FormMapper $mapper;

    public function testNoFormField()
    {
        $this->checker->expects(self::never())
                      ->method('shouldBeProtected');
        $this->formFieldProvider->expects(self::exactly(2))
                                ->method('getFormField')
                                ->willReturn(null);
        $fields    = [
            'field1' => new WriteProtected('fooChecker'),
            'field2' => new WriteProtected('fooChecker'),
        ];
        $protector = new DefaultProtector(
            $this->formFieldProvider,
            [
                $this->checker,
            ]
        );

        $protector->protectForm($this->mapper, $fields, $this->object);
    }

    public function testCheckerNotFound()
    {
        $this->checker->expects(self::never())
                      ->method('shouldBeProtected');
        $this->formFieldProvider->expects(self::once())
                                ->method('getFormField')
                                ->willReturn($this->getMock(FormBuilderInterface::class));
        $fields    = [
            'field1' => new WriteProtected('asdChecker'),
        ];
        $protector = new DefaultProtector(
            $this->formFieldProvider,
            [
                $this->checker,
            ]
        );

        $this->expectException(CheckerNotFoundException::class);
        $this->expectExceptionMessage('Checker not found: asdChecker');

        $protector->protectForm($this->mapper, $fields, $this->object);
    }

    public function testOneFieldProtected()
    {
        $this->checker->expects(self::exactly(2))
                      ->method('shouldBeProtected')
                      ->withConsecutive(
                          [$object1 = $this->getMock(stdClass::class)],
                          [$object2 = $this->getMock(stdClass::class)],
                      )
                      ->willReturnOnConsecutiveCalls(true, false);
        $configs = [
            'field1' => $config1 = new WriteProtected('fooChecker'),
            'field2' => $config2 = new WriteProtected('fooChecker'),
        ];
        $this->formFieldProvider->expects(self::exactly(2))
                                ->method('getFormField')
                                ->withConsecutive(
                                    [$this->mapper, 'field1', $config1],
                                    [$this->mapper, 'field2', $config2],
                                )
                                ->willReturnOnConsecutiveCalls(
                                    $formField1 = $this->getMock(FormBuilderInterface::class),
                                    $formField2 = $this->getMock(FormBuilderInterface::class),
                                );
        $formField1->expects(self::once())
                   ->method('setDisabled')
                   ->with(true);
        $formField2->expects(self::never())
                   ->method('setDisabled');
        $protector = new DefaultProtector(
            $this->formFieldProvider,
            [
                $this->checker,
            ]
        );

        $protector->protectForm($this->mapper, $configs, $this->object);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->checker = $this->getMock(Checker::class);
        $this->checker->method('getName')
                      ->willReturn('fooChecker');
        $this->formFieldProvider = $this->getMock(FormFieldProvider::class);
        $this->formContractor    = $this->getMock(FormContractorInterface::class);
        $this->builder           = $this->getMock(FormBuilder::class);
        $this->admin             = $this->getMock(AdminInterface::class);
        $this->mapper            = new FormMapper($this->formContractor, $this->builder, $this->admin);
        $this->object            = $this->getMock(stdClass::class);
    }

}
