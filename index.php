<?php
include 'Socket/Socket.php';
$socket = new Socket('10.10.24.161', 8890);

$socket->connect();