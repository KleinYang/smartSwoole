<?php
use App;

class Server
{
    private $serv;
    private $fd;
    private $pi;
    private $app;
    private $pdo;
    private $port;
    private $tcp_hash;

    public function __construct($setting) {
        self::registe();
        $this->serv = new swoole_server(HTTP_HOST, HTTP_PORT);

        $this->serv->set($setting);

        $this->serv->on('Start', array($this, 'onStart'));
        $this->serv->on('Connect', array($this, 'onConnect'));
        $this->serv->on('Receive', array($this, 'onReceive'));
        $this->serv->on('Close', array($this, 'onClose'));
        // bind callback
        $this->serv->on('WorkerStart', array($this, 'onWorkerStart'));
        $this->serv->on('Task', array($this, 'onTask'));
        $this->serv->on('Finish', array($this, 'onFinish'));
        if ($setting['tcp_enable'] === true) {
            foreach ($setting['tcp'] as $k => $v) {
                $this->tcp_hash[$v['tcp_port']] = $v['class'];
                $this->serv->addlistener($v['tcp_host'] , $v['tcp_port'] , $v['tcp_mode'] );
            }
        }
        
        $this->serv->start();
    }

    public function onStart( $serv ) {
        echo "Start"."\n";
    }

    public function onConnect( $serv, $fd, $from_id ) {
        echo "Client {$fd} connect"."\n";
        $info = $serv->connection_info($fd, $from_id);
        // print_r($info);
        $this->port = $info['server_port'];
        $this->fd = $fd;
        //来自http
        if($info['server_port'] == 80) {
            $this->app = $fd;
        }
        //来自tcp
        else {
            $this->pi = $fd;
        }
    }

    public function onReceive( swoole_server $serv, $fd, $from_id, $data ) {
        echo "Get Message From Client {$fd}"."\n";
        echo "Continue Handle Worker"."\n";
        echo $data;
        // print_r($GET);
        if($this->port == 80) {
            $GET = $this->getParam($data);
            if($GET === false){
                return false;
            }
            $param = array(
                'fd' => $fd,
                'pi' => $this->pi,
                'app' => $this->app,
                'type' => 1,
                'data' => json_encode($GET)
            );
            $serv->task( json_encode($param) );

        }
        //来自外网
        else {
            $param = array(
                'fd' => $fd,
                'pi' => $this->pi,
                'app' => $this->app,
                'type' => $this->port,
                'data' => $data
            );
            $serv->task( json_encode($param) );
        }

    }

    public function onClose( $serv, $fd, $from_id ) {
        echo "Client {$fd} close connection"."\n";
    }

    public function onWorkerStart( $serv , $worker_id) {
        echo "onWorkerStart"."\n";
        // 判定是否为Task Worker进程
        // if( $worker_id > $serv->setting['worker_num'] ) {
        //     $this->$pdo || $this->initPdo();
        // }
        
        // 在Worker进程开启时绑定定时器
        // 只有当worker_id为0时才添加定时器,避免重复添加
        // if( $worker_id == 0 ) {
        //     $check_lose = swoole_timer_tick(5*60*1000, function()use ($serv) {
        //         $serv->task( 'timer' );
        //     });

        //     $check_rule = swoole_timer_tick(24*60*60*1000, function()use ($serv) {
        //         $serv->task( 'rule' );
        //     });
        // }
    }

    /**
     * @param $serv swoole_server swoole_server对象
     * @param $task_id int 任务id
     * @param $from_id int 投递任务的worker_id
     * @param $data string 投递的数据
     */
    public function onTask($serv,$task_id,$from_id, $data) {
        //来自80的内网管理端口
        $param = json_decode( $data , true );
        $type = $param['type'];
        $fd = $param['fd'];
        $GET = $param['data'];
        if ($type == 1) {
            $class = CTRL_PATH . "Index";
            $ctrl = new $class();
            $resp = $ctrl->onReceive($GET);
            $this->sendMessage($serv, $fd, $resp);
        }else if($type != 1){
            $class = TCP_PATH . $this->tcp_hash[$type];
            echo $GET . '------\n';
            $resp = $class::onReceive($GET);
            $serv->send($fd, $resp);
        }
        
        
    }

    public function onFinish($serv,$task_id, $data) {
        echo "Task {$task_id} finish"."\n";
        $param = json_decode( $data , true );
        $serv->close( $param['fd'] );
    }

    //初始化pdo
    // public static function initPdo() {
    //     $this->$pdo = new PDO(
    //         DB_URI, 
    //         DB_USER, 
    //         DB_PASS, 
    //         array(
    //             PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8';",
    //             PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    //             PDO::ATTR_PERSISTENT => true
    //         )
    //     );
    // }

    public function getParam($str) 
    { 
        $GET = [];
        $result = array(); 
        if (strstr($str,'curl') != false) {
            # code...
        }
        preg_match_all("/(?:rest?)(.*)(?: HTTP)/i",$str, $result); 
        var_dump($result);
        if ($result[1] === []) {
            return false;
        }
        $content = substr($result[1][0], 1); 
        $param = explode("&", $content);
        foreach ($param as $k => $v) {
            $GET[explode('=', $v)[0]] = explode('=', $v)[1];
        }
        return $GET;
    } 

    public function sendMessage($serv, $fd, $data){
        $resp = "HTTP/1.1 200\r\n";
        $serv->send($fd, $resp);

        $resp = "Content-Type: text/html; charset=UTF-8\r\nContent-Length: ". strlen(json_encode($data)) ."\r\n";
        $serv->send($fd, $resp);

        $resp = "\r\n";
        $serv->send($fd, $resp);

        $resp = json_encode($data);
        $serv->send($fd, $resp);
    }

    public static function registe(){
        spl_autoload_register("Server::loadClass");
    }
    public static function loadClass($class){
        $class=str_replace('\\', '/', $class);
        $class=__DIR__ . "/../".$class.".php";
        require_once $class;    
    }


}
