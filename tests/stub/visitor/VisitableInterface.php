<?php 

interface VisitableInterface
{
    public function accept(VisitorInterface $visitor);
}