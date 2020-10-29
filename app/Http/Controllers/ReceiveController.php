<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use PhpAmqpLib\Connection\AMQPStreamConnection;


class ReceiveController extends Controller
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
        // set_time_limit(0);
        $connection = new AMQPStreamConnection('182.92.218.34', 5672, 'admin', 'admin');
        $channel = $connection->channel();

        $channel->exchange_declare('logs', 'fanout', false, false, false);

        list($queue_name,,) = $channel->queue_declare("", false, false, true, false);

        $channel->queue_bind($queue_name, 'logs');

        echo ' [*] Waiting for logs. To exit press CTRL+C', "\n";

        $callback = function ($msg) {
            $m =  ' [x] ' . $msg->body . "\n";
            error_log($m, 3, '/tmp/queue.log');
            echo $m;
        };

        $channel->basic_consume($queue_name, '', false, true, false, false, $callback);

        while (count($channel->callbacks)) {
            $channel->wait();
        }

        $channel->close();
        $connection->close();
    }
}
