<?php

class Socket
{
    protected $ip;
    protected $port;

    protected $connects = array();

    public function __construct($ip, $port)
    {
        $this->ip= $ip;
        $this->port= $port;
    }

    public function connect()
    {
        echo "WELCOME\n";
        $address = "$this->ip:$this->port";
        $socket = stream_socket_server($address, $errno, $errstr);
        
        while (true) {
            // обзови переменные осмысленно
            $read = $this->connects;
            $write = $except = null;
            // почему тут пробел
            $read [] = $socket;
            if (!stream_select($read, $write, $except, null)) {
                break;
            }
            if (in_array($socket, $read)) {
                // убрать коменты, лишние скобки
                if (($connect = stream_socket_accept($socket, -1))/* && $info = $this->handshake($connect)*/) {
                    $this->connects[] = $connect;
                    unset($read[array_search($socket, $read)]);
                    $this->onOpen($connect, "Welcome to my socket\n");
                    // вынести печать месседжа в метод, написать трейт для расширения функционалов класса
                    echo "New connect!\n";
                }
            }
            foreach ($read as $connect) {
                // константа
                $data = fread($connect, 100000);
                if ($data == false) {
                    // возможно стоит написать не в одну строку, а более читабельно
                    unset($this->connects[array_search($connect, $this->connects)]);
                    // поубирать echo
                    echo "$connect disconnected\n";
                    break;
                }
                $this->onMessage($connect, $data);
                // Msg - обзови нормально, старайся использовать однозначные имена
                $this->sendMsg($connect, $data);
            }
        }
    }

    protected function handshake($connect)
    {
        // опять имена переменных
        $line = fgets($connect);
        $header = explode(' ', $line);
        // убрать лишнее
        print_r($header);
        // нормальное имя дать
        $inf = array(
            'method' => $header[0],
            'uri' => $header[1]
        );

        while ($line = rtrim(fgets($connect))) {
            if (preg_match('/\A(\S+): (.*)\z/', $line, $matches)) {
                $inf[$matches[1]] = $matches[2];
            } else {
                break;
            }
        }

        $address = explode(':', stream_socket_get_name($connect, true));
        $inf['ip'] = $address[0];
        $inf['port'] = $address[1];

        if (empty($inf['Sec-WebSocket-Key'])) {
            return false;
        }

// длина строки, попробуй разнеси, чтобы было более читабельно
        $SecWebSocketAccept = base64_encode(pack('H*', sha1($inf['Sec-WebSocket-Key'] . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11')));
        $response = "HTTP/1.1 101 Web Socket Protocol Handshake\r\n" .
            "Upgrade: websocket\r\n" .
            "Connection: Upgrade\r\n" .
            "Sec-WebSocket-Accept:$SecWebSocketAccept\r\n\r\n";

        fwrite($connect, $response);

        return $inf;
    }

    protected function onMessage($connect, $data)
    {
        echo "$connect: $data\n";
    }

    protected function onOpen($connect, $msg)
    {
        // константы
        fwrite($connect, $msg, 10000);
    }

    protected function sendMsg($currentConnect, $msg)
    {
        // константы
        // имена переменных и методов
        foreach ($this->connects as $connect) {
            if ($connect != $currentConnect) {
                fwrite($connect, "$currentConnect say: $msg", 100000);
            }
        }
    }

}
