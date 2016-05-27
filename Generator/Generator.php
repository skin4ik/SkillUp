<?php
error_reporting(E_ALL);
class PrimeNumber implements IteratorAggregate
{
    protected $min;
    protected $max;

    public function __construct($min, $max)
    {
        $this->min = $min;
        $this->max = $max;
    }

    public function getIterator()
    {
        for ($i = $this->min; $i < $this->max; $i++) {
            if($this->prime($i)) {
                yield $i;
            }
        }
    }

    public function prime($number)
    {
        for ($i = 2; $i <= sqrt($number); $i++) {
            if ($number % $i == 0) {
                return false;
            }
        }
        return true;
    }
}

$primeNumbers = new PrimeNumber(1, 10000);
$start = microtime(true);
foreach ($primeNumbers as $number) {
    var_dump($number);
}
echo "<br/>";
echo microtime(true) - $start;