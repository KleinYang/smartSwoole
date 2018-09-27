<?php
namespace App\Controllers;
class Index
{
    public function onReceive( $data ) {
        $content = json_decode($data, true);
        var_dump($content['a']);
        $resp = json_encode($content['a']);
        return $resp;
        //exit
    }
}