<?php

class Queue extends SplQueue implements ArrayAccess
{
    protected $books;

    public function __construct()
    {
        $this->books = array();
    }

    /**
     * add o queue
     * @param mixed $book
     * @param $value
     */
    public function enqueue ($book, $value) {
        if ($this->offsetExists($book)) {
            array_push($this->books[$book], $value);
        } else {
            $this->setBook($book);
            $this->enqueue($book, $value);
        }
    }

    /**
     * 
     * @param $book
     */
    public function dequeue ($book) {
        array_shift($this->books[$book]);
    }

    /**
     * @param mixed $index
     * @return bool
     */
    public function offsetExists($index)
    {
        return isset($this->books[$index]);
    }

    /**
     * @param mixed $index
     * @return bool|mixed
     */
    public function offsetGet($index)
    {
        if ($this->offsetExists($index)) {
            return $this->books[$index];
        }
        return false;
    }

    /**
     * @param $book
     */
    public function setBook($book) {
        $this->books[$book] = array();
    }

    /**
     * @return array
     */
    public function getAllBook() {
        return $this->books;
    }

}

$queueBook = new Queue();
$queueBook->enqueue('Game of Thrones', 'fuser');
$queueBook->enqueue('Game of Thrones', 'Petya');
$queueBook->enqueue('Easy come easy go', 'Vasya');
$queueBook->enqueue('Game of Thrones', 'Vasya');
$queueBook->enqueue('Easy come easy go', 'Katya');
$queueBook->dequeue('Game of Thrones');

var_dump('Game of Thrones');
var_dump($queueBook->offsetGet('Game of Thrones'));
var_dump('Easy come easy go');
var_dump($queueBook->offsetGet('Easy come easy go'));

$queueBook->enqueue('Game of Thrones', 'Liza');
$queueBook->enqueue('Gamer', 'Pasha');
var_dump('Game of Thrones');
var_dump($queueBook->offsetGet('Game of Thrones'));
var_dump('Gamer');
var_dump($queueBook->offsetGet('Gamer'));
var_dump($queueBook->getAllBook());