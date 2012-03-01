<?php

use Attraktiv\Polymorphisme\Overloading\Method;

class Overloaded extends Method
{
    
    protected function anotherMethod()
    {
        
    }
    
    /**
     * @Overload(method="apply")
     * @param TestA $a An instance of TestA
     */
    protected function applyOne(\TestA $a)
    {
        return 1;
    }

    /**
     * @Overload(method="apply")
     * @param TestB $a An instance of TestA
     */
    protected function applyTwo(\TestB $a)
    {
        return 2;
    }

    /**
     * @Overload(method="apply")
     * @param TestB   $a    An instance of TestA
     * @param integer $test An integer
     */
    protected function applyThree(\TestB $a, $test)
    {
        return $test;
    }

    /**
     * @Overload(method="apply")
     * @param TestB   $a    An instance of TestA
     * @param string  $test An integer
     */
    protected function applyFour(\TestB $a, $test)
    {
        return sprintf("applyFour: %s", $test);
    }
    
    /**
     * @Overload(method="apply")
     * @param TestB   $a    An instance of TestA
     * @param array   $test An array
     */
    protected function applyFive(\TestB $a, $test)
    {
        return 'array';
    }
    
    /**
     * @Overload(method="apply")
     * @param TestB   $a    An instance of TestA
     * @param double  $test A float
     */
    protected function applySix(\TestB $a, $test)
    {
        return 'float';
    }
    
    
    /**
     * @Overload(method="apply")
     * @param TestB   $a    An instance of TestA
     * @param boolean $test A boolean
     */
    protected function applySeven(\TestB $a, $test)
    {
        return 'boolean';
    }
    
    /**
     * @Overload(method="apply")
     * @param TestInterface $a    An instance of TestA
     */
    protected function applyEight(\TestInterface $a)
    {
        return 'subclass';
    }    
    
	/**
     * @Overload(method="apply")
     */
    protected function applyNine()
    {
        return 'noarg';
    }    
    
    

}
