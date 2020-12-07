<?php

namespace App\Listeners;

use App\Events\OrderShippedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;

// 事件监听器队列
class SendShipmentNotificationQueueListener implements ShouldQueue
{
    /**
     * 创建事件监听器
     *
     * @return void
     */
    public function __construct()
    {
        logger('----SendShipmentNotificationQueueListener Init----');
    }

    /**
     * 处理事件
     *
     * @param OrderShippedEvent $event
     * @return void
     */
    public function handle(OrderShippedEvent $event)
    {
        logger('----SendShipmentNotificationQueueListener handle----');
    }

    // 处理失败任务
    public function failed(OrderShippedEvent $event, $exception)
    {
        //
    }
}