<?php

class TestD implements TestInterface
{
	private $p1 = 'defaultValue';
	private $p2 = 'setterInConstructor';
	protected $p3;
	
	public function __construct($p2)
	{
		$this->p2 = $p2;
	}	
	
	public function getP($indice)
	{		
		$attribut = sprintf("p%s", $indice);
		return $this->$attibut;
	}
}

