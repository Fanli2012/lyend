
## 队列说明

- 在.env文件中配置使用的存储方式，QUEUE_CONNECTION=database
- 启动队列php artisan queue:work，php artisan queue:restart
- 消息队列优点：提高系统响应速度，异步化、解耦、消除峰值
- 消息队列缺点：暂时的不一致性问题、系统复杂度提高

使用了消息队列，生产者一方，把消息往队列里一扔，就可以立马返回，响应用户了，无需等待处理结果。
