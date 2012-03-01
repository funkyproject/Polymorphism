<?php
namespace Attraktiv\Polymorphism\Tests;

use Attraktiv\Polymorphism\Transtypage;

class PolymorphismTest extends \PHPUnit_Framework_TestCase
{
	
	public function testCreateExpectedObjectWithoutArgsInConstructor()
	{
		$poly = new Transtypage();
		$this->assertInstanceOf('\\TestB',$poly->createExpectedObject('\\TestB', new \TestA())); 
	}
	
	public function testCreateExpectedObjectWithArgsInConstructor()
	{
		$poly = new Transtypage();
		$this->assertInstanceOf('\\TestC',$poly->createExpectedObject('\\TestC', new \TestB())); 
	}
	
	public function testCreateExpcetedObjectWithComplicatedImplementation()
	{
		$poly = new Transtypage();
		$actual = new \TestD('testearg1');
		$expected = $poly->createExpectedObject('\\TestE', $actual);
		$this->assertInstanceOf('\\TestE',$expected);
	}

	public function testTranstypageTestAShouldBeTestB()
	{
		$test = Transtypage::transform('\\TestB', new \TestA());
		
		$this->assertInstanceOf('\\TestB', $test);
	}
	
	public function testTranstypageTestGShouldBeTestA()
	{
		$test = Transtypage::transform('\\TestA', new \TestG());
		
		$this->assertInstanceOf('\\TestA', $test);
	}
	

	/**
	 * @expectedException \InvalidArgumentException
	 * Throw an exception because TestF have not sharing code with TestA
	 */
	public function testTranstypageShouldBeThrowAnException()
	{
		Transtypage::transform('\\TestF', new \TestA());
	}

}