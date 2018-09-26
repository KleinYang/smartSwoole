<?php
namespace App\Tcp;
class Demo
{
    public function onReceive( \swoole_server $serv, $fd, $data ) {
        $resp = json_encode($data);
        $serv->send($fd, $resp);
    }
}