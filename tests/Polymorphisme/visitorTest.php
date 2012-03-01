<?php
namespace Attraktiv\Polymorphisme\Tests;

class VisitorTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->car = new \Car();
        $this->car->addElement(new \Wheel('left front'));
        $this->car->addElement(new \Wheel('right front'));
        $this->car->addElement(new \Wheel('left back'));
        $this->car->addElement(new \Wheel('right back'));
        $this->car->addElement(new \Engine());
        $this->car->addElement(new \Body());
        
    }
    
    public function testOutputVisitor()
    {
        $expected = array();
        $expected[] = 'visiting left front wheel car';
        $expected[] = 'visiting right front wheel car';
        $expected[] = 'visiting left back wheel car';
        $expected[] = 'visiting right back wheel car';
        $expected[] = 'visiting engine car';
        $expected[] = 'visiting body car';
        $expected[] = 'starting my car';
        
        $this->car->accept(new \CarVisitor());
        
        $this->assertEquals($expected, $this->car->getOutput());
    }
}