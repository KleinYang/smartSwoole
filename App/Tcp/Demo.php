<?php
namespace App\Tcp;
class Demo
{
    public function onReceive( $data ) {
        $resp = json_encode($data);
        return $resp;
    }
}