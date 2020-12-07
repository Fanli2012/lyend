<!DOCTYPE html>
<html>

<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<title>后台登录</title>
	<link rel="stylesheet" href="/plugin/layui/css/layui.css">
	<link rel="stylesheet" href="/css/admin/style.css">
	<script src="/js/jquery.min.js"></script>
	<script type="text/javascript" src="/plugin/layui/layui.js"></script>
	<script src="/js/public.js"></script>
	<style>
		body,
		html {
			height: 100%
		}

		html {
			background-color: #f2f2f2;
			color: #666;
		}

		.layui-layout-body {
			overflow: auto
		}

		#LAY-user-login,
		.layadmin-user-display-show {
			display: block !important
		}

		.layadmin-user-login {
			position: relative;
			left: 0;
			top: 0;
			padding: 110px 0;
			min-height: 100%;
			box-sizing: border-box
		}

		.layadmin-user-login-main {
			width: 375px;
			margin: 0 auto;
			box-sizing: border-box
		}

		.layadmin-user-login-box {
			padding: 20px
		}

		.layadmin-user-login-header {
			text-align: center
		}

		.layadmin-user-login-header h2 {
			margin-bottom: 10px;
			font-weight: 300;
			font-size: 30px;
			color: #000
		}

		.layadmin-user-login-header p {
			font-weight: 300;
			color: #999
		}

		.layadmin-user-login-body .layui-form-item {
			position: relative
		}

		.layadmin-user-login-icon {
			position: absolute;
			left: 1px;
			top: 1px;
			width: 38px;
			line-height: 36px;
			text-align: center;
			color: #d2d2d2
		}

		.layadmin-user-login-body .layui-form-item .layui-input {
			padding-left: 38px
		}

		.layadmin-user-login-codeimg {
			max-height: 38px;
			width: 100%;
			cursor: pointer;
			box-sizing: border-box
		}

		.layadmin-user-login-other {
			position: relative;
			font-size: 0;
			line-height: 38px;
			padding-top: 20px
		}

		.layadmin-user-login-other>* {
			display: inline-block;
			vertical-align: middle;
			margin-right: 10px;
			font-size: 14px
		}

		.layadmin-user-login-other .layui-icon {
			position: relative;
			top: 2px;
			font-size: 26px
		}

		.layadmin-user-login-other a:hover {
			opacity: .8
		}

		.layadmin-user-jump-change {
			float: right
		}

		.layadmin-user-login-footer {
			position: absolute;
			left: 0;
			bottom: 0;
			width: 100%;
			line-height: 30px;
			padding: 20px;
			text-align: center;
			box-sizing: border-box;
			color: rgba(0, 0, 0, .5)
		}

		.layadmin-user-login-footer span {
			padding: 0 5px
		}

		.layadmin-user-login-footer a {
			padding: 0 5px;
			color: rgba(0, 0, 0, .5)
		}

		.layadmin-user-login-footer a:hover {
			color: rgba(0, 0, 0, 1)
		}

		.layadmin-user-login-main[bgimg] {
			background-color: #fff;
			box-shadow: 0 0 5px rgba(0, 0, 0, .05)
		}

		.ladmin-user-login-theme {
			position: fixed;
			bottom: 0;
			left: 0;
			width: 100%;
			text-align: center
		}

		.ladmin-user-login-theme ul {
			display: inline-block;
			padding: 5px;
			background-color: #fff
		}

		.ladmin-user-login-theme ul li {
			display: inline-block;
			vertical-align: top;
			width: 64px;
			height: 43px;
			cursor: pointer;
			transition: all .3s;
			-webkit-transition: all .3s;
			background-color: #f2f2f2
		}

		.ladmin-user-login-theme ul li:hover {
			opacity: .9
		}

		@media screen and (max-width:768px) {
			.layadmin-user-login {
				padding-top: 60px
			}

			.layadmin-user-login-main {
				width: 300px
			}

			.layadmin-user-login-box {
				padding: 10px
			}
		}

		.layui-icon-login-qq {
			color: #3492ED;
		}

		.layui-icon-login-wechat {
			color: #4DAF29;
		}

		.layui-icon-login-weibo {
			color: #CF1900;
		}

		.layadmin-link {
			color: #029789 !important;
		}
	</style>
</head>

<body>

	<div class="layadmin-user-login layadmin-user-display-show" id="LAY-user-login">

		<div class="layadmin-user-login-main">
			<div class="layadmin-user-login-box layadmin-user-login-header">
				<h2>后台登录</h2>
				<p>请输入您的用户名、密码</p>
			</div>
			<form id="myform" method="post" action="" class="login-form" role="form">{{ csrf_field() }}
				<div class="layadmin-user-login-box layadmin-user-login-body layui-form">
					<div class="layui-form-item">
						<label class="layadmin-user-login-icon layui-icon layui-icon-username"
							for="LAY-user-login-username"></label>
						<input type="text" id="name" name="name" lay-verify="required" placeholder="用户名/邮箱/手机号..."
							class="layui-input">
					</div>
					<div class="layui-form-item">
						<label class="layadmin-user-login-icon layui-icon layui-icon-password"
							for="LAY-user-login-password"></label>
						<input type="password" id="pwd" name="pwd" lay-verify="required" placeholder="输入密码..."
							class="layui-input">
					</div>
					<div class="layui-form-item">
						<div class="layui-row">
							<div class="layui-col-xs7">
								<label class="layadmin-user-login-icon layui-icon layui-icon-vercode"
									for="LAY-user-login-vercode"></label>
								<input type="text" id="captcha" name="captcha" lay-verify="required" placeholder="图形验证码"
									class="layui-input">
							</div>
							<div class="layui-col-xs5">
								<div style="margin-left: 10px;">
									<img style="height:38px;" class="layadmin-user-login-codeimg" id="captcha-img"
										src="{{ captcha_src('number') }}" onclick="javascript:document.getElementById('captcha-img').src='{{ captcha_src('number') }}?random=' + Math.random();" />
								</div>
							</div>
						</div>
					</div>
					<!-- <div class="layui-form-item" style="margin-bottom: 20px;">
	  <input type="checkbox" name="remember" lay-skin="primary" title="记住密码">
	  <a href="javascript:;" class="layadmin-user-jump-change layadmin-link" style="margin-top: 7px;">忘记密码？</a>
	</div> -->
					<div class="layui-form-item">
						<button type="submit" class="layui-btn layui-btn-fluid" lay-submit lay-filter="form_submit">立即登录</button>
					</div>
					<!-- <div class="layui-trans layui-form-item layadmin-user-login-other">
	  <label>社交账号登录</label>
	  <a href="javascript:;"><i class="layui-icon layui-icon-login-qq"></i></a>
	  <a href="javascript:;"><i class="layui-icon layui-icon-login-wechat"></i></a>
	  <a href="javascript:;"><i class="layui-icon layui-icon-login-weibo"></i></a>

	  <a href="reg.html" class="layadmin-user-jump-change layadmin-link">注册帐号</a>
	</div> -->
				</div>
			</form>
		</div>

		<div class="layui-trans layadmin-user-login-footer">
			<p>© <?php echo date('Y'); ?></p>
		</div>

	</div>
	<script type="text/javascript">
		layui.use(['form', 'layer'], function () {
			// 操作对象
			var form = layui.form
				, layer = layui.layer
				, $ = layui.jquery;

			//监听提交
			form.on('submit(form_submit)', function(data) {
				//layer.msg(JSON.stringify(data.field));
				//return false;
				setCookie('admin_name', $("#name").val(), 24 * 60 * 60 * 1000);
			});
		});
		
if (getCookie('admin_name')) {
	document.getElementById('name').value = getCookie('admin_name');
}

function setCookie(name, value, expire)
{
	expire = expire || 24 * 60 * 60 * 1000; //此 cookie 将被保存 30 天
	var exp  = new Date(); //new Date("December 31, 9998");
	exp.setTime(exp.getTime() + expire);
	document.cookie = name + "="+ escape(value) +";expires="+ exp.toGMTString();
}
function getCookie(name)
{
	var arr = document.cookie.match(new RegExp("(^| )"+name+"=([^;]*)(;|$)"));
	if(arr != null) return unescape(arr[2]); return null;
}
function delCookie(name)
{
	var exp = new Date();
	exp.setTime(exp.getTime() - 1);
	var cval=getCookie(name);
	if(cval!=null) document.cookie=name +"="+cval+";expires="+exp.toGMTString();
}
	</script>
	
</body>

</html>