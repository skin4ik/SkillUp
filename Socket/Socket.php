<?php

function socket()
{

    $startTime = round(microtime(true),2);
    echo "WELCOME TO SOCKET \n";

    $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

    $bind = socket_bind($socket, '127.0.0.1', 8890);

    socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1);

    socket_listen($socket, 10);

    while(true){ //Бесконечный цикл ожидания подключений
        echo "Waiting... ";
        $accept = @socket_accept($socket); //Зависаем пока не получим ответа
        if($accept === false){
            echo "Error: " . socket_strerror(socket_last_error())."<br />\r\n";
            usleep(100);
        } else {
            echo "OK <br />\r\n";
            echo "Client \"".$accept."\" has connected<br />\r\n";
        }

        $msg = "Hello, Client!";

        echo "Send to client \"".$msg."\"... ";
        socket_write($accept, $msg);
        echo "OK <br />\r\n";

        if( ( round(microtime(true),2) - $startTime) > 100) {
            echo "time = ".(round(microtime(true),2) - $startTime);
            echo "return <br />\r\n";
            return $socket;
        }


    }
}

socket();

if (isset($socket)){
    echo "Closing connection... ";
    @socket_shutdown($socket);
    socket_close($socket);
    echo "OK <br />\r\n";
}