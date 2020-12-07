<?php

namespace App\Listeners;

use App\Events\OrderShippedEvent;

class SendShipmentNotificationListener
{
    /**
     * 创建事件监听器
     *
     * @return void
     */
    public function __construct()
    {
        logger('----SendShipmentNotificationListener Init----');
    }

    /**
     * 处理事件
     *
     * @param OrderShippedEvent $event
     * @return void
     */
    public function handle(OrderShippedEvent $event)
    {
        // 使用 $event->order 发访问订单
        logger('----SendShipmentNotificationListener handle----');
        logger('order_id:' . $event->order_id);
    }
}