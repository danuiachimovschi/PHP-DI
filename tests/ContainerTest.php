<?php

declare(strict_types=1);

namespace Tests;

use Danu\PhpDi\Container;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
    private Container $instance;

    protected function setUp(): void
    {
        parent::setUp();
        $this->instance = Container::instance();
    }

    /**
     * @return void
     * @throws \Danu\PhpDi\Exception\ContainerException
     */
    public function testIfThisClassAssertInstanceOfMakeClass()
    {
        $classExample = $this->instance->make(Examples\UserExample::class, ['name' => 'Danu']);

        $this->assertInstanceOf(Examples\UserExample::class, $classExample);
    }

    /**
     * @return void
     * @throws \Danu\PhpDi\Exception\ContainerException
     */
    public function testIfClassDoesNotExist()
    {
        $this->expectExceptionObject(\Danu\PhpDi\Exception\ContainerException::classDoesNotExist('UserExampleClassThatDoesNotExist'));

        $this->instance->make('UserExampleClassThatDoesNotExist', ['name' => 'Danu']);
    }
}