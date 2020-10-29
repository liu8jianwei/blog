<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

require_once __DIR__ . '/vendor/autoload.php';

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
        $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
        $channel = $connection->channel();

        $channel->queue_declare('hello', false, false, false, false);

        echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";
        $callback = function ($msg) {
            echo " [x] Received ", $msg->body, "\n";
        };

        $channel->basic_consume('hello', '', false, true, false, false, $callback);

        while (count($channel->callbacks)) {
            $channel->wait();
        }
    }
}
