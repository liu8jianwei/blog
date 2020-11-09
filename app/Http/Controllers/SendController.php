<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;


class SendController extends Controller
{
    /**
     * @Notes: 使用队列插入数据
     * @Interface index
     * @param Request $request
     * @author: zzh
     * @Time: 2020/6/11   11:20
     */
    public function index(Request $request)
    {
        $connection = new AMQPStreamConnection('182.92.218.34', 5672, 'admin', 'admin');
        $channel = $connection->channel();

        $channel->exchange_declare('logs', 'fanout', false, false, false);

        $data = "info: Hello World!";
        $msg = new AMQPMessage($data);

        $channel->basic_publish($msg, 'logs');

        echo " [x] Sent ", $data, "\n";

        $channel->close();
        $connection->close();
    }


    public function indexExchange(Request $request)
    {
        $connection = new AMQPStreamConnection('182.92.218.34', 5672, 'admin', 'admin');
        $channel = $connection->channel();

        $e_name = 'e_linvo'; //交换机名 
        $k_route = array(0 => 'key_1', 1 => 'key_2'); //路由key 
        //创建交换机    
        $ex = new AMQPExchange($channel);
        $ex->setName($e_name);
        $ex->setType(AMQP_EX_TYPE_DIRECT); //direct类型  
        $ex->setFlags(AMQP_DURABLE); //持久化 
        echo "Exchange Status:" . $ex->declare() . "\n";
        for ($i = 0; $i < 5; ++$i) {
            echo "Send Message:" . $ex->publish($message . date('H:i:s'), $k_route[i % 2]) . "\n";
        }

        $channel->close();
        $connection->close();
    }
}
