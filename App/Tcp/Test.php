<?php
namespace App\Tcp;
class Test
{
    public function onReceive( \swoole_server $serv, $fd, $data ) {
        $resp = json_encode("i am test");
        $serv->send($fd, $resp);
    }
}