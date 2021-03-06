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
            $read = $this->connects;
            $write = $except = null;
            $read []['connect'] = $socket;
            if (!stream_select($read, $write, $except, null)) {
                break;
            }

            if (in_array($socket, array_column($read, 'connect'))) {
                if (($connect = stream_socket_accept($socket, -1))/* && $info = $this->handshake($connect)*/) {
                    $this->connects[] = ['connect' => $connect];
                    $this->onOpen($connect, "Welcome to my socket\n");
                    echo "New connect!\n";
                }
            }


            foreach ($this->connects as $connect) {
                if (empty($connect['data']) && $connect['data'] != $connect) {
                    $data = fread($connect, 1000);
                    $connect['data'] = $data;
                    $isExit = (boolean)((string)$data == "exit");
                    echo $isExit;
                    if ($isExit) {
                        fclose($connect);
                        unset($this->connects[array_search($connect, $this->connects)]);
                        echo $connect . " removed";
                    } else if ($data != '') {
                        $this->onMessage($connect['connect'], $data);
                        $this->sendMsg($connect['connect'], $data);
                    }
                }
            }
        }
    }

    protected function handshake($connect)
    {

        $line = fgets($connect);
        $header = explode(' ', $line);
        print_r($header);
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
        echo "$data\n";
    }

    protected function onOpen($connect, $msg)
    {
        fwrite($connect, $msg);
    }

    protected function sendMsg($currentConnect, $msg)
    {
        foreach ($this->connects as $connect) {
            if ($connect != $currentConnect) {
                fwrite($connect, $currentConnect . " say: " . $msg);
            }
        }
    }

}
