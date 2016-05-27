<?php

class Property implements IteratorAggregate
{
    public $property1 = 'property 1';
    public $property2 = 'property 2';
    public $property3 = 'property 3';

    public function __construct(array $property)
    {
        $this->property = $property;
    }

    public function getIterator()
    {
        return new ArrayIterator($this);
    }

}

$checkPrime = new Property(['property4' => 'property 4']);

foreach ($checkPrime as $key => $check) {
    var_dump($check);
}