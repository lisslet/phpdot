<?php
namespace Dot\Server;

class WebSocket
{
    protected $host;
    protected $port;

    protected $socket;
    protected $connections;

    function __construct(string $host = 'localhost', int $port = 8000)
    {
        $this->host = $host;
        $this->port = $port;
    }

    function listen($callback)
    {
        print_r('Server Start');
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1);
        socket_bind($socket, 0, $this->port);
        socket_listen($socket);

        $this->socket =& $socket;

        $this->connections = [$socket];
        $connections =& $this->connections;
        $null = null;

        if (is_callable($callback)) {
            $callback = Closure::bind($callback, $this, get_class($this));
        } else {
            $callback = null;
        }

        while (true) {
            $changed = $connections;
            socket_select($changed, $null, $null, 0, 10);

            if (in_array($socket, $changed)) {
                $newConnection = socket_accept($socket);
                $connections[] = $newConnection;

                $this->handshake($newConnection);
                socket_getpeername($newConnection, $ip);
                print_r($ip . ' connected');
                $index = array_search($socket, $changed);
                unset($changed[$index]);
            }

            foreach ($changed as $changedConnection) {
                $buffer = @socket_read($changedConnection, 1024, PHP_NORMAL_READ);

                if ($buffer === false) {
                    $index = array_search($changedConnection, $connections);
                    socket_getpeername($changedConnection, $ip);
                    print_r($ip . ' disconnected');
                    unset($connections[$index]);
                }
            }

            if ($callback) {
                $callback();
            }
            sleep(1);
        }

        socket_close($socket);
    }

    protected function handshake($connection)
    {
        $header = socket_read($connection, 1024);
        $headers = Header::parse($header);
        $key = $headers['Sec-WebSocket-Key'];
        $accept = base64_encode(pack('H*', sha1($key . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11')));

        $upgradeHeader = implode("\r\n", [
                'HTTP/1.1 101 Web Socket Protocol Handshake',
                'Upgrade: websocket',
                'Connection: Upgrade',
                'WebSocket-Origin: ' . $this->host,
                'WebSocket-Location: ws://' . $this->host . ':' . $this->port,
                'Sec-WebSocket-Accept: ' . $accept
            ]) . '\r\n\r\n';

        socket_write($connection, $upgradeHeader, strlen($upgradeHeader));
    }

    protected function send($type, array $dataset)
    {
        $message = json_encode([
            'type' => $type,
            'dataset' => $dataset
        ]);

        $message = mask($message);
        print_r(count($this->connections));
        foreach ($this->connections as $connection) {
            @socket_write($connection, $message, strlen($message));
        }
    }

}

//Encode message for transfer to client.
function mask($text)
{
    $b1 = 0x80 | (0x1 & 0x0f);
    $length = strlen($text);

    if ($length <= 125)
        $header = pack('CC', $b1, $length);
    elseif ($length > 125 && $length < 65536)
        $header = pack('CCn', $b1, 126, $length);
    elseif ($length >= 65536)
        $header = pack('CCNN', $b1, 127, $length);
    return $header . $text;
}