<?php

class Client
{
    protected $host;
    protected $port;

    public function __construct($host, $post)
    {
        $this->host = $host;
        $this->port = $post;
    }

    public function start()
    {
        $address = "$this->host:$this->port";
        // use camelCase
        // используй имена, которые характеризуют то что лежит внутри, stream_socket_client вернет ресурс потока, а не конект
        $connect = stream_socket_client($address, $errno, $errstr);
        $stdin = fopen('php://stdin', 'r');
        
        if (!$connect) {
            // http://i.imgur.com/YhsVGbk.png
            echo "$errstr ($errno)<br />\n";
        } else {
            // почему не заюзать $address
            echo "Welcome to $this->host:$this->port\n";
            
            while (!feof($connect)) {
                $streams = array($connect, $stdin);
                $write = $except = null;
                
                if (!stream_select($streams, $write, $except, null)) {
                    break;
                }

                foreach ($streams as $stream) {
                    if ($stream == $stdin) {
                        $msg = trim(fgets($stdin));
                        // используй полные имена
                        $this->send($connect, $msg);
                    } else {
                        // вынести в константу
                        $msg = fread($stream, 10000);
                        $this->onMessage($msg);
                    }
                }
            }
            fclose($connect);
        }
    }

    protected function send($resource, $msg)
    {
        fwrite($resource, $msg);
    }

    protected function onMessage($msg)
    {
        echo "$msg\n";
    }
}

$client = new Client('10.10.24.161', 8890);
$client->start();
