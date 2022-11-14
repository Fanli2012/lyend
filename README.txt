
git clone https://github.com/Fanli2012/lyend.git

cmd下创建.env文件
echo hello > .env


说明

1、基于Laravel6
2、PHP+Mysql
3、后台登录：/fladmin/login
账号：admin888
密码：123456


安装

1、 导入数据库
1) 打开根目录下的lyend.sql文件，将 http://www.lyend.com 改成自己的站点根网址，格式：http://+域名
2) 导入数据库

2、复制.env.example重命名成.env，修改相应配置APP_SUBDOMAIN及数据库配置

3、
php composer.phar install
php artisan key:generate


注意

只能放在根目录
如果要开启调试模式，请修改 .env 文件， APP_ENV=local 和 APP_DEBUG=true 。



Simple QrCode文档：https://www.simplesoftware.io/docs/simple-qrcode/zh
$qrcode = new \SimpleSoftwareIO\QrCode\BaconQrCodeGenerator;
return $qrcode->size(500)->generate('Make a qrcode without Laravel!');

二进制数据直接显示成二维码图片return '<img src="data:image/png;base64,'.base64_encode(\QrCode::format('png')->encoding('UTF-8')->size(200)->generate('http://www.baidu.com/')).'">';


composer.phar install安装出现proc_open错误，解决办法
修改composer.json中scripts下的"php artisan optimize"为"php artisan clear-compiled"


composer中国全量镜像修改
进入项目的根目录（也就是 composer.json 文件所在目录），执行如下命令：
composer config repo.packagist composer https://mirrors.aliyun.com/composer/


微信开发，支付
https://easywechat.org/


storage软链映射
# php artisan storage:link //创建storage/app/public目录的软连接
队列启动
# nohup php artisan queue:listen >/dev/null 2>&1 &
# nohup php artisan queue:work --daemon >/dev/null 2>&1 & //不重新加载整个框架，而是直接 fire 动作
# php artisan queue:restart



配置 路由 html页面有做修改，需要清除缓存

php artisan cache:clear	清除应用程序缓存
php artisan route:cache	清除路由缓存
php artisan config:cache	清除配置缓存
php artisan view:clear	清除已编译的视图文件


git强制更新主分支代码
git fetch --all
git reset --hard origin/dev
git reset --hard origin/master


php artisan vendor:publish


Laravel伪静态
try_files $uri $uri/ /index.php?$query_string;




