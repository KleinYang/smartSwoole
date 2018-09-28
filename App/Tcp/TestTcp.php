<?php
//test
namespace App\Tcp;
class TestTcp
{
    public function onReceive( $data ) {
        $resp = json_encode("i am test");
        return $resp;
    }
}