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
        $stream = stream_socket_client($address, $errno, $errstr);
        if (!$stream) {
            echo "$errstr ($errno)<br />\n";
        } else {
            echo "Welcome to $this->host:$this->port\n";
            //fwrite($stream, "GET / HTTP/1.0\r\nHost: $this->host\r\nAccept: */*\r\n\r\n");
            while (!feof($stream)) {
                $streams[] = $stream;
                $write = $except = null;
                if (!stream_select($streams, $write, $except, null)) {
                    break;
                }
                $msg = trim(fgets(STDIN));
                $data = fread($stream, 1000);
                echo "1 " . $data . "\n";
                //$this->onMessage($stream, $data);
                $this->send($stream, $msg);
            }
            fclose($stream);
        }
    }

    protected function send($resource, $msg)
    {
        fwrite($resource, $msg);
    }

    protected function onMessage($resource, $msg)
    {
        print_r($msg);
    }
}

$client = new Client('10.10.24.161', 8890);
$client->start();