
git clone https://github.com/Fanli2012/lyend.git

cmd�´���.env�ļ�
echo hello > .env


˵��

1������Laravel6
2��PHP+Mysql
3����̨��¼��/fladmin/login
�˺ţ�admin888
���룺123456


��װ

1�� �������ݿ�
1) �򿪸�Ŀ¼�µ�lyend.sql�ļ����� http://www.lyend.com �ĳ��Լ���վ�����ַ����ʽ��http://+����
2) �������ݿ�

2������.env.example��������.env���޸���Ӧ����APP_SUBDOMAIN�����ݿ�����

3��
php composer.phar install
php artisan key:generate


ע��

ֻ�ܷ��ڸ�Ŀ¼
���Ҫ��������ģʽ�����޸� .env �ļ��� APP_ENV=local �� APP_DEBUG=true ��



Simple QrCode�ĵ���https://www.simplesoftware.io/docs/simple-qrcode/zh
$qrcode = new \SimpleSoftwareIO\QrCode\BaconQrCodeGenerator;
return $qrcode->size(500)->generate('Make a qrcode without Laravel!');

����������ֱ����ʾ�ɶ�ά��ͼƬreturn '<img src="data:image/png;base64,'.base64_encode(\QrCode::format('png')->encoding('UTF-8')->size(200)->generate('http://www.baidu.com/')).'">';


composer.phar install��װ����proc_open���󣬽���취
�޸�composer.json��scripts�µ�"php artisan optimize"Ϊ"php artisan clear-compiled"


composer�й�ȫ�������޸�
������Ŀ�ĸ�Ŀ¼��Ҳ���� composer.json �ļ�����Ŀ¼����ִ���������
composer config repo.packagist composer https://mirrors.aliyun.com/composer/


΢�ſ�����֧��
https://easywechat.org/


storage����ӳ��
# php artisan storage:link //����storage/app/publicĿ¼��������
��������
# nohup php artisan queue:listen >/dev/null 2>&1 &
# nohup php artisan queue:work --daemon >/dev/null 2>&1 & //�����¼���������ܣ�����ֱ�� fire ����
# php artisan queue:restart



���� ·�� htmlҳ�������޸ģ���Ҫ�������

php artisan cache:clear	���Ӧ�ó��򻺴�
php artisan route:cache	���·�ɻ���
php artisan config:cache	������û���
php artisan view:clear	����ѱ������ͼ�ļ�


gitǿ�Ƹ�������֧����
git fetch --all
git reset --hard origin/dev
git reset --hard origin/master


php artisan vendor:publish


Laravelα��̬
try_files $uri $uri/ /index.php?$query_string;




