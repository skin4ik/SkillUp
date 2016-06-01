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
        $connect = stream_socket_client($address, $errno, $errstr);
        $stdin = fopen('php://stdin', 'r');
        if (!$connect) {
            echo "$errstr ($errno)<br />\n";
        } else {
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
                        $this->send($connect, $msg);
                    } else {
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
