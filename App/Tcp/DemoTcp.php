<?php
//demo
namespace App\Tcp;
class DemoTcp
{
    public function onReceive( $data ) {

        $resp = json_encode($data);
        return $resp;
    }
}