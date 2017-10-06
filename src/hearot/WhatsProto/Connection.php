<?php

namespace hearot\WhatsProto;

class Connection
{
    public function connect()
    {
        if ($this->is_connected()) {
            \hearot\WhatsProto\Log('Connection: You\'re already connected...', 'warning');
        }
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if ($socket !== false) {
            $result = socket_connect($socket, 'e' . rand(1, 16) . '.whatsapp.net', \hearot\WhatsProto\Constants::WHATSAPP_PORT);
            if ($result === false) {
                $socket = false;
            }
        }
        if ($socket !== false) {
            socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, ['sec' => \hearot\WhatsProto\Constants::TIMEOUT_SEC, 'usec' => \hearot\WhatsProto\Constants::TIMEOUT_USEC]);
            socket_set_option($socket, SOL_SOCKET, SO_SNDTIMEO, ['sec' => \hearot\WhatsProto\Constants::TIMEOUT_SEC, 'usec' => \hearot\WhatsProto\Constants::TIMEOUT_USEC]);
            $this->socket = $socket;
            \hearot\WhatsProto\Log('Connection: Connected to the server...', 'connection');
            return true;
        } else {
            \hearot\WhatsProto\Log('Connection: Unable to connect to the server!', 'error');
            throw new \hearot\WhatsProto\ConnectionException('Unable to connect to the server!');
            return false;
        }
    }
    public function is_connected()
    {
        return $this->socket !== null;
    }
}
