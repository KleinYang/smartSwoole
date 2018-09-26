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
        'worker_num' => 1,
        'daemonize' => false,
        'max_request' => 10000,
        'dispatch_mode' => 2,
        'debug_mode'=> 1,
        'task_worker_num' => 1,
        'log_file' => '/var/www/html/smartHome/tmp/'.date('Ymd', time()).'.log'
    );
