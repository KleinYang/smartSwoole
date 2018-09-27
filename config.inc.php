<?php
// 传感器数据类型定义
const SENSOR_TEMP       =   1;  //温度
const SENSOR_HYMI       =   2;  //湿度

// 传感器状态定义
const SENSOR_NORMAL 	 =   1;  //正常
const SENSOR_ERROR 		 =   2;  //报警
const SENSOR_LOSE 		 =   3;  //掉线
const SENSOR_WARN 		 =   4;  //预警

$setting = array(
    'worker_num' => 4,
    'daemonize' => false,
    'max_request' => 10000,
    'dispatch_mode' => 2,
    'debug_mode'=> 1,
    'task_worker_num' => 4,
    'log_file' => '/var/www/html/smartHome/tmp/'.date('Ymd', time()).'.log',
    'tcp_enable' => true,
    'tcp' => [

        ["tcp_host"=>"0.0.0.0","tcp_port"=>9505,"tcp_mode"=>SWOOLE_TCP,"class"=>"Demo"],
        ["tcp_host"=>"0.0.0.0","tcp_port"=>3389,"tcp_mode"=>SWOOLE_TCP,"class"=>"Test"]
    ]
);

//MySQL
const DB_URI          =   "mysql:host=127.0.0.1;port=3306;dbname=smartHome";
const DB_PASS         =   "123456";
const DB_USER         =   "root";

//HTTP
const HTTP_HOST     =    '0.0.0.0';
const HTTP_PORT     =    80;

//PATH
const DIR_PATH      =   __DIR__ . '/';
const ROOT_PATH     =   DIR_PATH . '/../';
const APP_PATH      =   ROOT_PATH . '/App/';
const TCP_PATH      =   'App\\Tcp\\';
const CTRL_PATH      =   'App\\Controllers\\';
const MOD_PATH      =   'App\\Models\\';
const VIEW_PATH      =   'App\\Views\\';