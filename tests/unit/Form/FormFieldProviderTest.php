<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\SonataAdminProtectedFields\Tests\Unit\Form;

use Fastbolt\SonataAdminProtectedFields\Exception\FieldNotFoundException;
use Fastbolt\SonataAdminProtectedFields\Form\FormFieldProvider;
use Fastbolt\SonataAdminProtectedFields\Mapping\Attributes\WriteProtected;
use Fastbolt\TestHelpers\BaseTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Builder\FormContractorInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\PropertyAccess\PropertyPath;

/**
 * @covers \Fastbolt\SonataAdminProtectedFields\Form\FormFieldProvider
 */
class FormFieldProviderTest extends BaseTestCase
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
     * @var FormMapper
     */
    private FormMapper $mapper;

    public function testGetFormFieldHasField(): void
    {
        $writeProtected = $this->getMock(WriteProtected::class);
        $field          = $this->getMock(FormBuilder::class);
        $this->builder->method('has')
                      ->with('fooName')
                      ->willReturn(true);
        $this->builder->method('get')
                      ->with('fooName')
                      ->willReturn($field);

        $provider = new FormFieldProvider();
        $result   = $provider->getFormField($this->mapper, 'fooName', $writeProtected);

        self::assertSame($field, $result);
    }

    public function testGetFormFieldUnknownDontThrow(): void
    {
        $writeProtected = $this->getMock(WriteProtected::class);
        $writeProtected->method('getThrowOnMissing')
                       ->willReturn(false);

        $this->builder->method('has')
                      ->with('fooChild')
                      ->willReturn(false);
        $this->admin->method('getFormBuilder')
                    ->willReturn($this->builder);
        $this->builder->method('all')
                      ->willReturn([]);

        $provider = new FormFieldProvider();
        $result   = $provider->getFormField($this->mapper, 'fooChild', $writeProtected);

        self::assertNull($result);
    }

    public function testGetFormFieldUnknownThrow(): void
    {
        $writeProtected = $this->getMock(WriteProtected::class);
        $writeProtected->method('getThrowOnMissing')
                       ->willReturn(true);

        $this->builder->method('has')
                      ->with('fooChild')
                      ->willReturn(false);
        $this->admin->method('getFormBuilder')
                    ->willReturn($this->builder);
        $this->builder->method('all')
                      ->willReturn([]);
        $this->admin->method('getClass')
                    ->willReturn('fooClass');

        $this->expectException(FieldNotFoundException::class);
        $this->expectExceptionMessage('Field fooChild not found in class fooClass');

        $provider = new FormFieldProvider();
        $provider->getFormField($this->mapper, 'fooChild', $writeProtected);
    }

    public function testGetFormFieldHasChildField(): void
    {
        $writeProtected = $this->getMock(WriteProtected::class);
        $fields         = [
            $field1 = $this->getMock(FormBuilderInterface::class),
            $field2 = $this->getMock(FormBuilderInterface::class),
            $field3 = $this->getMock(FormBuilderInterface::class),
        ];

        $field1->method('getName')
               ->willReturn('asd');
        $field2->method('getName')
               ->willReturn('fooChild.fooBarName');
        $field2->method('getPropertyPath')
               ->willReturn(new PropertyPath('fooChild.fooBarName'));
        $field3->method('getName')
               ->willReturn('fooChild.bazName');
        $field3->method('getPropertyPath')
               ->willReturn(new PropertyPath('fooChild.bazName'));

        $this->builder->method('has')
                      ->with('fooChild')
                      ->willReturn(false);
        $this->admin->method('getFormBuilder')
                    ->willReturn($this->builder);
        $this->builder->method('all')
                      ->willReturn($fields);

        $provider = new FormFieldProvider();
        $result   = $provider->getFormField($this->mapper, 'fooChild', $writeProtected);

        self::assertSame($field2, $result);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->formContractor = $this->getMock(FormContractorInterface::class);
        $this->builder        = $this->getMock(FormBuilder::class);
        $this->admin          = $this->getMock(AdminInterface::class);
        $this->mapper         = new FormMapper($this->formContractor, $this->builder, $this->admin);
    }
}
