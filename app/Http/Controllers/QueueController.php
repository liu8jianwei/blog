<?php

namespace App\Http\Controllers;

use App\Jobs\Queue as JobsQueue;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class QueueController extends Controller
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
        Log::info('Start: ' . date('Y-m-d H:i:s', time()));
        $arr = ['time' => time(), 'id' => rand(100, 999)];
        sleep(2);
        $this->dispatch(new JobsQueue($arr));
        Log::info('End: ' . date('Y-m-d H:i:s', time()));
        return 'success';
    }


    public function insert()
    {
        sleep(2);
        $user = new User();
        $data = [
            'name' => rand(100000, 999999),
            'email' => rand(10000, 99999) . 'rockywish@xinyi.com',
            'password' => '$2y$10$7rKsXBSoccZ4c2/9I8nb1OH7X5/i.Jvt/5ZRxE7dzwskdcCLSMGBa',
        ];
        $user->insert($data);
        return 'success';
    }
}
