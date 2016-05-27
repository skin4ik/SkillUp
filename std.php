<?php

class Stack
{
    protected $stack;
    protected $limit;

    public function __construct($limit = 10)
    {
        $this->stack = array();
        $this->limit = $limit;
    }

    /**
     * @param $item
     */
    public function push($item)
    {
        if (count($this->stack) < $this->limit) {
            array_unshift($this->stack, $item);
        } else {
            throw new RuntimeException('stack overflow!');
        }
    }

    /**
     * remove first element from stack
     */
    public function pop()
    {
        if ($this->isEmpty()) {
            throw new RuntimeException('Stack is empty');
        } else {
            array_shift($this->stack);
        }
    }

    /**
     * check empty stack
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->stack);
    }

    /**
     * get first element of stack
     * @return mixed
     */
    public function top()
    {
        return current($this->stack);
    }

}

echo "/*****Hello*******/\n";
$stack = new Stack();
$line = '';
while ($line != 'exit') {
    echo "Type anything to add to stack \n";
    echo "Remove first element of stack, type: kill \n";
    echo "Get first element of stack, type: current \n";
    echo "Type \"exit\" to exit!\n";
    $line = trim(fgets(STDIN));
    if ($line == 'kill') {
        $stack->pop();
    } elseif ($line == 'current') {
        echo $stack->top() . "\n";
    } elseif ($line != 'exit') {
        $stack->push($line);
        echo "$line added to stack\n";
    }

}

