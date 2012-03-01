<?php
namespace Attraktiv\Polymorphism\Tests\Overloading;

use Attraktiv\Polymorphism\Overloading\Method;

class MethodTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->object = new \Overloaded();
    }

    public function testApplyShouldBeOne()
    {
        $actual = $this->object
            ->apply(new \TestA());
        $this->assertEquals(1, $actual);
    }

    public function testApplyShouldBeTwo()
    {
        $actual = $this->object
            ->apply(new \TestB());
        $this->assertEquals(2, $actual);
    }

    public function testApplyShouldBeIntegerArg()
    {
        $actual = $this->object
            ->apply(new \TestB(), 3);
        $this->assertEquals(3, $actual);
    }

    public function testApplyShouldBeStringArg()
    {
        $actual = $this->object
            ->apply(new \TestB(), "autre");
        $this->assertEquals("applyFour: autre", $actual);
    }

    public function testApplyShouldBeArrayArg()
    {
        $actual = $this->object
            ->apply(new \TestB(), array(1, 2, 3));
        $this->assertEquals("array", $actual);
    }

    public function testApplyShouldBeFloatArg()
    {
        $actual = $this->object
            ->apply(new \TestB(), 11.2);
        $this->assertEquals("float", $actual);
    }

    public function testApplyShouldBeBooleanArg()
    {
        $actual = $this->object
            ->apply(new \TestB(), true);
        $this->assertEquals("boolean", $actual);
    }

    public function testApplyShouldBeSubClassArg()
    {
        $actual = $this->object
            ->apply(new \TestC(1));
        $this->assertEquals("subclass", $actual);
    }

    public function testApplySWhitoutArg()
    {
        $actual = $this->object
            ->apply();
        $this->assertEquals("noarg", $actual);
    }

    public function testApplyWithExtendedClassShouldBeOne()
    {
        $actual = $this->object
            ->apply(new \TestG());
        $this->assertEquals(1, $actual);
    }

    /**
     * @expectedException \Attraktiv\Polymorphism\Overloading\BadMethodOverloadedException
     */
    public function testExistShouldBeExpception()
    {
        $this->object->exist();
    }
}
