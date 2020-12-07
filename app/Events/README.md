
## 事件监听说明

- 一个事件可以被一个或多个监听器监听，也就是观察者模式，我们可以定义多个监听器，当这个事件发生，执行一系列逻辑。
- 注册事件以及监听器，在 app/Providers/ 目录下的 EventServiceProvider.php 中注册事件监听器映射关系，然后执行php artisan event:generate
- 定义事件
- 定义监听器
- 触发事件
