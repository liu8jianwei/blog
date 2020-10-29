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
}
