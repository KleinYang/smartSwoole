<?php
namespace App\Tcp;
class Test
{
    public function onReceive( $data ) {
        $resp = json_encode("i am test");
        return $resp;
    }
}