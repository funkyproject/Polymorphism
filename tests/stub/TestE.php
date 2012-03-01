<?php

class TestE implements TestInterface
{
	private $p1 = 'OtherDefaultValue';
	private $p2 = 'setterInConstructor';
	protected $p3;
	
	public function __construct($p2, $p3)
	{
		$this->p2 = $p2;
		$this->p3 = $p3;
	}	
	
	public function getP($indice)
	{		
		$attribut = sprintf("p%s", $indice);
		return $this->$attibut;
	}
}

