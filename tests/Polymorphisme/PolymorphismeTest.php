<?php
namespace Attraktiv\Polymorphisme\Tests;

use Attraktiv\Polymorphisme\Polymorphisme;

class PolymorphismeTest extends \PHPUnit_Framework_TestCase
{
	
	public function testCreateExpectedObjectWithoutArgsInConstructor()
	{
		$poly = new Polymorphisme();
		$this->assertInstanceOf('\\TestB',$poly->createExpectedObject('\\TestB', new \TestA())); 
	}
	
	public function testCreateExpectedObjectWithArgsInConstructor()
	{
		$poly = new Polymorphisme();
		$this->assertInstanceOf('\\TestC',$poly->createExpectedObject('\\TestC', new \TestB())); 
	}
	
	public function testCreateExpcetedObjectWithComplicatedImplementation()
	{
		$poly = new Polymorphisme();
		$actual = new \TestD('testearg1');
		$expected = $poly->createExpectedObject('\\TestE', $actual);
		$this->assertInstanceOf('\\TestE',$expected);
	}

	public function testTranstypageTestAShouldBeTestB()
	{
		$test = Polymorphisme::transtypage('\\TestB', new \TestA());
		
		$this->assertInstanceOf('\\TestB', $test);
	}
	
	public function testTranstypageTestGShouldBeTestA()
	{
		$test = Polymorphisme::transtypage('\\TestA', new \TestG());
		
		$this->assertInstanceOf('\\TestA', $test);
	}
	

	/**
	 * @expectedException \InvalidArgumentException
	 * Throw an exception because TestF have not sharing code with TestA
	 */
	public function testTranstypageShouldBeThrowAnException()
	{
		Polymorphisme::transtypage('\\TestF', new \TestA());
	}

}