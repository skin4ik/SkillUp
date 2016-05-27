<?php

class CheckGuest implements Iterator
{
    private $listGuest = array();
    private $blackList = array();
    private $vipList = array();
    private $position;

    public function __construct($listGuest, $blackList, $vipList)
    {
        $this->listGuest = $listGuest;
        $this->blackList = $blackList;
        $this->vipList = $vipList;
    }

    function rewind()
    {
        $this->position = 0;
    }

    function valid()
    {
        return isset($this->listGuest[$this->position]);
    }

    function current()
    {
        $guest = $this->listGuest[$this->position];
        if (in_array($guest, $this->vipList)) {
            return "$guest is VIP person!";
        } elseif (in_array($guest, $this->blackList)) {
            return "$guest in black list!";
        } else {
            return "$guest invited!";
        }
    }

    public function key()
    {
        return $this->position;
    }

    function next()
    {
        ++$this->position;
    }

}

$guestList = ['Vasya', 'John', 'Petya', 'Terry', 'Eden', 'Messi', 'Katya', 'Valeria'];
$blackList = ['Messi'];
$vipList = ['John', 'Eden'];
$checkGuest = new CheckGuest($guestList, $blackList, $vipList);

foreach ($checkGuest as $check) {
    var_dump($check);
}
