<?php

namespace App\Jobs;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ExampleJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * 队列任务最大失败次数
     *
     * @var int
     */
    public $tries = 5;

    /**
     * 队列任务最大运行时长（秒）
     *
     * @var int
     */
    public $timeout = 120;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        logger('----ExampleJob Queue----');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        logger('队列执行成功');
        // 业务逻辑处理
    }

    /**
     * 任务失败
     *
     * @param Exception $exception
     * @return void
     */
    public function failed(Exception $e)
    {
        // 发送失败通知
        logger('队列执行失败');
    }
}
