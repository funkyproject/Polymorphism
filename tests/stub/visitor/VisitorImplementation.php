<?php

class Wheel implements VisitableInterface
{
    private $position;

    public function __construct($position)
    {
        $this->position = $position;
    }

    public function getPosition()
    {
        return $this->position;
    }

    public function accept(VisitorInterface $visitor)
    {
        return $visitor->visit($this);
    }
}

class Engine implements VisitableInterface
{
    public function accept(VisitorInterface $visitor)
    {
        return $visitor->visit($this);
    }
}

class Body implements VisitableInterface
{
    public function accept(VisitorInterface $visitor)
    {
        return $visitor->visit($this);
    }
}

class Car implements VisitableInterface
{
    private $elements = array();
    private $output = array();

    public function addElement(VisitableInterface $element)
    {
        $this->elements[] = $element;
    }

    public function accept(VisitorInterface $visitor)
    {
        foreach ($this->elements as $element) {
            $this->output[] = $element->accept($visitor);
        }

        $this->output[] = $visitor->visit($this);
    }

    public function getOutput()
    {
        return $this->output;
    }
}

class CarVisitor extends Attraktiv\Polymorphism\Overloading\Method implements VisitorInterface
{
    /**
     * @Overload(method="visit")
     * @param Wheel $wheel
     */
    public function visitWheel(Wheel $wheel)
    {
        return sprintf("visiting %s wheel car", $wheel->getPosition());
    }

    /**
     * @Overload(method="visit")
     * @param Body $body
     */
    public function visitBody(Body $body)
    {
        return sprintf("visiting body car");
    }

    /**
     * @Overload(method="visit")
     * @param Engine $engine
     */
    public function visitEngine(Engine $engine)
    {
        return sprintf("visiting engine car");
    }

    /**
     * @Overload(method="visit")
     * @param Car $car
     */
    public function visitCar(Car $engine)
    {
        return sprintf("starting my car");
    }
}
