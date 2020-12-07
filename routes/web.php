<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//wap路由，要放到最前面，否则解析不到
Route::domain(env('APP_SUBDOMAIN'))->namespace('Wap')->group(function () {
    Route::get('/', 'IndexController@index')->name('wap_home');
    Route::get('/page404', 'IndexController@page404')->name('wap_page404');     //404页面
    Route::get('/tags', 'IndexController@tags')->name('wap_tags');
    Route::get('/p/{id}', 'ArticleController@detail')->name('wap_detail');      //详情页
    Route::get('/cat{cat}/{page}', 'ArticleController@index');                  //分类页，分页
    Route::get('/cat{cat}', 'ArticleController@index')->name('wap_category');   //分类页
    Route::get('/tag{tag}/{page}', 'IndexController@tag');                      //标签页，分页
    Route::get('/tag{tag}', 'IndexController@tag')->name('wap_tag');            //标签页
    Route::get('/page/{id}', 'PageController@detail')->name('wap_singlepage');  //单页
    Route::get('/goods/{id}', 'GoodsController@detail')->name('wap_goods');     //商品详情页
    Route::get('/goodstype{cat}', 'IndexController@goodstype')->name('wap_goodstype'); //产品分类页
    Route::get('/sitemap.xml', 'IndexController@sitemap')->name('wap_sitemap'); //SITEMAP
});


//前台路由
Route::group(['namespace' => 'Index'], function () {
	Route::get('/', 'IndexController@index')->name('home');
	Route::get('/page404', 'IndexController@page404')->name('page404');                        //404页面
	Route::get('/page/{id}', 'PageController@detail')->name('index_page_detail');              //单页
    Route::get('/goodslist/{key}', 'GoodsController@index')->name('index_goods_index_key')->where(['key' => '[a-z0-9]+']); //商品列表
    Route::get('/goodslist', 'GoodsController@index')->name('index_goods_index');              //商品列表
    Route::get('/goods/{id}', 'GoodsController@detail')->name('index_goods_detail')->where(['id' => '[a-z0-9]+']); //商品详情页
    Route::get('/brandlist', 'GoodsController@brand_list')->name('index_goods_brandlist');     //品牌列表
	Route::get('/sitemap.xml', 'IndexController@sitemap')->name('index_index_sitemap');        //SITEMAP
	Route::get('/ad/{id}', 'AdController@detail')->name('index_ad_detail')->where(['id' => '[a-z0-9]+']); //广告详情
    Route::get('/tag/{id}', 'TagController@detail')->name('index_tag_detail')->where(['id' => '[a-z0-9]+']); //商品详情页
    Route::get('/search/detail', 'SearchController@detail')->name('index_search_detail');      //搜索页
    Route::get('/articlelist/{key}', 'ArticleController@index')->name('index_article_index_key')->where(['key' => '[a-z0-9]+']);  //文章列表
    Route::get('/articlelist', 'ArticleController@index')->name('index_article_index');        //文章列表
    Route::get('/p/{id}', 'ArticleController@detail')->name('index_article_detail')->where(['id' => '[a-z0-9]+']); //文章详情页
    //测试
	Route::get('/test/queue', 'TestController@queue')->name('index_test_queue');               //队列测试
	Route::get('/test/event', 'TestController@event')->name('index_test_event');               //事件测试
});


//微信路由，无需登录
Route::group(['prefix' => 'weixin', 'namespace' => 'Weixin'], function () {
	Route::get('/', 'IndexController@index')->name('weixin');
	Route::get('/category', 'IndexController@category')->name('weixin_category');
    Route::get('/category_goods_list', 'GoodsController@categoryGoodsList')->name('weixin_category_goods_list'); //产品分类页
    Route::get('/page404', 'IndexController@page404')->name('weixin_page404');  //404页面
	Route::get('/search', 'IndexController@search')->name('weixin_search');     //搜索页面
	Route::get('/p/{id}', 'ArticleController@detail')->name('weixin_article_detail'); //文章详情页
	Route::get('/cat{cat}', 'ArticleController@category')->name('weixin_article_category'); //分类页
	Route::get('/tag{tag}', 'IndexController@tag')->name('weixin_tag');         //标签页
	Route::get('/page/{id}', 'IndexController@page')->name('weixin_singlepage');//单页
	Route::get('/goods/{id}', 'GoodsController@goodsDetail')->name('weixin_goods_detail'); //商品详情页
	Route::get('/goodslist', 'GoodsController@goodsList')->name('weixin_goods_list'); //商品筛选列表
    Route::get('/brandlist', 'GoodsBrandController@brandList')->name('weixin_brand_list'); //品牌列表
    Route::get('/brand_detail/{id}', 'GoodsBrandController@brandDetail')->name('weixin_brand_detail'); //品牌详情

    Route::get('/bonus_list', 'BonusController@bonusList')->name('weixin_bonus_list');
    Route::any('/wxoauth', 'UserController@oauth')->name('weixin_wxoauth');     //微信网页授权
    Route::any('/login', 'UserController@login')->name('weixin_login');
    Route::any('/register', 'UserController@register')->name('weixin_register');
    Route::get('/logout', 'UserController@logout')->name('weixin_user_logout'); //退出
    //页面跳转
	Route::get('/jump', 'IndexController@jump')->name('weixin_jump');

	Route::get('/test', 'IndexController@test')->name('weixin_test');           //测试
});


//微信路由，需登录，全局
Route::group(['prefix' => 'weixin', 'namespace' => 'Weixin', 'middleware' => ['web','wxlogin']], function () {
    //个人中心
	Route::get('/user', 'UserController@index')->name('weixin_user');
    Route::get('/userinfo', 'UserController@userinfo')->name('weixin_userinfo');
    Route::get('/user_account', 'UserController@userAccount')->name('weixin_user_account');
    Route::get('/user_money_list', 'UserController@userMoneyList')->name('weixin_user_money_list');
    Route::get('/user_point_list', 'UserController@userPointList')->name('weixin_user_point_list');
    Route::get('/user_message_list', 'UserController@userMessageList')->name('weixin_user_message_list');
    Route::get('/user_distribution', 'UserController@userDistribution')->name('weixin_user_distribution');
    Route::any('/user_withdraw', 'UserController@userWithdraw')->name('weixin_user_withdraw');
    Route::get('/user_withdraw_list', 'UserController@userWithdrawList')->name('weixin_user_withdraw_list');
    //用户充值
    Route::get('/user_recharge', 'UserController@userRecharge')->name('weixin_user_recharge');
    Route::get('/user_recharge_order', 'UserController@userRechargeOrder')->name('weixin_user_recharge_order');
    //优惠券、红包
    Route::get('/user_bonus_list', 'UserController@userBonusList')->name('weixin_user_bonus_list');
    //浏览记录
    Route::get('/user_goods_history', 'UserController@userGoodsHistory')->name('weixin_user_goods_history');
    Route::get('/user_goods_history_delete', 'UserController@userGoodsHistoryDelete')->name('weixin_user_goods_history_delete');
    Route::get('/user_goods_history_clear', 'UserController@userGoodsHistoryClear')->name('weixin_user_goods_history_clear');
    //商品收藏
    Route::get('/collect_goods', 'CollectGoodsController@index')->name('weixin_user_collect_goods');
    //购物车
    Route::get('/cart', 'CartController@index')->name('weixin_cart');
    Route::get('/cart_checkout/{ids}', 'CartController@cartCheckout')->name('weixin_cart_checkout');
    Route::post('/cart_done', 'CartController@cartDone')->name('weixin_cart_done');
    //订单
    Route::get('/order_pay/{id}', 'OrderController@pay')->name('weixin_order_pay'); //订单支付
    Route::post('/order_dopay', 'OrderController@dopay')->name('weixin_order_dopay'); //订单支付
    Route::get('/order_list', 'OrderController@orderList')->name('weixin_order_list'); //全部订单列表
    Route::get('/order_detail', 'OrderController@orderDetail')->name('weixin_order_detail'); //订单详情
    Route::get('/order_yuepay', 'OrderController@orderYuepay')->name('weixin_order_yuepay'); //订单余额支付
    Route::get('/order_wxpay', 'OrderController@orderWxpay')->name('weixin_order_wxpay'); //订单微信支付
    Route::any('/order_comment', 'OrderController@orderComment')->name('weixin_order_comment'); //订单评价
    //收货地址
    Route::get('/user_address', 'AddressController@index')->name('weixin_user_address_list');
    Route::get('/user_address_add', 'AddressController@userAddressAdd')->name('weixin_user_address_add');
    Route::get('/user_address_update', 'AddressController@userAddressUpdate')->name('weixin_user_address_update');
    //意见反馈
    Route::get('/user_feedback_add', 'FeedbackController@userFeedbackAdd')->name('weixin_user_feedback_add');
});


//无需token验证，全局
Route::group(['middleware' => ['web']], function () {
    Route::get('/weixin_user_recharge_order_detail', 'Weixin\UserController@userRechargeOrderDetail')->name('weixin_user_recharge_order_detail'); //微信充值支付，为了配合公众号支付授权目录
    Route::post('/api/listarc', 'Api\IndexController@listarc')->name('api_listarc');
    Route::post('/api/customer_login', 'Api\WechatAuthController@customerLogin');
	Route::post('/api/', 'Api\UserController@signin'); //签到
});


//API接口路由，无需token验证
Route::prefix('api')->namespace('Api')->middleware('web')->group(function () {
    //各种回调
    Route::any('/notify_wxpay_jsapi', 'NotifyController@wxpayJsapi')->name('notify_wxpay_jsapi'); //微信支付回调
    //轮播图
	Route::get('/slide/index', 'SlideController@index')->name('api_slide_index');
    //文章
	Route::get('/article/index', 'ArticleController@index')->name('api_article_index');
    Route::get('/article/detail', 'ArticleController@detail')->name('api_article_detail');
    Route::get('/article_type/index', 'ArticleTypeController@index')->name('api_article_type_index');
    Route::get('/article_type/detail', 'ArticleTypeController@detail')->name('api_article_type_detail');
    //单页
	Route::get('/page/index', 'PageController@index')->name('api_page_index');
    Route::get('/page/detail', 'PageController@detail')->name('api_page_detail');
    //商品
    Route::get('/goods/detail', 'GoodsController@detail'); //商品详情
    Route::get('/goods/index', 'GoodsController@index'); //商品列表
    Route::get('/goods_type/index', 'GoodsTypeController@index'); //商品分类列表
    Route::get('/goods_type/detail', 'GoodsTypeController@detail'); //商品分类详情
    Route::get('/goods_searchword/index', 'GoodsSearchwordController@index'); //商品搜索词列表
    Route::get('/goods_brand/detail', 'GoodsBrandController@detail'); //商品品牌详情
    Route::get('/goods_brand/index', 'GoodsBrandController@index'); //商品品牌列表
    //地区，省市区
	Route::get('/region/index', 'RegionController@index')->name('api_region_index');
    Route::get('/region/detail', 'RegionController@detail')->name('api_region_detail');
    //用户
	Route::post('/wx_register', 'UserController@wxRegister'); //注册
    Route::post('/wx_login', 'UserController@wxLogin'); //登录
    Route::post('/wx_oauth_register', 'UserController@wxOauthRegister'); //微信授权注册登录
    //可用的优惠券列表
    Route::get('/bonus/index', 'BonusController@index'); //可用获取的优惠券列表

    //--------------------API接口路由，需token验证--------------------
    Route::post('/article/add', 'ArticleController@add'); //添加文章
    Route::post('/article/edit', 'ArticleController@edit'); //修改文章
    Route::post('/article/del', 'ArticleController@del'); //删除文章
    //用户中心
    Route::post('/user_signin', 'UserController@signin'); //签到
    Route::get('/user_info', 'UserController@userInfo'); //用户详细信息
    Route::post('/user_info_update', 'UserController@userUpdate'); //修改用户信息
    Route::post('/user_password_update', 'UserController@userPasswordUpdate'); //修改用户密码、支付密码
    Route::get('/user_list', 'UserController@userList'); //用户列表
    //用户充值
    Route::post('/user_recharge_add', 'UserRechargeController@userRechargeAdd');
    Route::get('/user_recharge_detail', 'UserRechargeController@userRechargeDetail');
    Route::get('/user_recharge_list', 'UserRechargeController@userRechargeList');
    //用户余额(钱包)
    Route::get('/user_money_list', 'UserMoneyController@userMoneyList');
    Route::post('/user_money_add', 'UserMoneyController@userMoneyAdd');
    //用户消息
    Route::get('/user_message_list', 'UserMessageController@userMessageList');
    Route::post('/user_message_add', 'UserMessageController@userMessageAdd');
    Route::post('/user_message_update', 'UserMessageController@userMessageUpdate');
    //用户提现
    Route::get('/user_withdraw_list', 'UserWithdrawController@userWithdrawList');
    Route::post('/user_withdraw_add', 'UserWithdrawController@userWithdrawAdd');
    Route::post('/user_withdraw_update', 'UserWithdrawController@userWithdrawUpdate');
    //浏览记录
    Route::get('/user_goods_history_list', 'UserGoodsHistoryController@userGoodsHistoryList'); //我的足迹列表
    Route::post('/user_goods_history_delete', 'UserGoodsHistoryController@userGoodsHistoryDelete'); //我的足迹删除一条
    Route::post('/user_goods_history_clear', 'UserGoodsHistoryController@userGoodsHistoryClear'); //我的足迹清空
    Route::post('/user_goods_history_add', 'UserGoodsHistoryController@userGoodsHistoryAdd'); //我的足迹添加
    //评价
    Route::get('/comment_list', 'CommentController@commentList'); //商品评价列表
    Route::post('/comment_add', 'CommentController@commentAdd'); //商品评价添加
    Route::post('/comment_batch_add', 'CommentController@commentBatchAdd'); //商品评价批量添加
    Route::post('/comment_update', 'CommentController@commentUpdate'); //商品评价修改
    Route::post('/comment_delete', 'CommentController@commentDelete'); //商品评价删除
    //商品收藏
    Route::get('/collect_goods_list', 'CollectGoodsController@collectGoodsList'); //收藏商品列表
    Route::post('/collect_goods_add', 'CollectGoodsController@collectGoodsAdd'); //收藏商品
    Route::post('/collect_goods_delete', 'CollectGoodsController@collectGoodsDelete'); //取消收藏商品
    //订单
    Route::post('/order_add', 'OrderController@orderAdd'); //生成订单
    Route::post('/order_update', 'OrderController@orderUpdate'); //订单修改
    Route::get('/order_list', 'OrderController@orderList'); //订单列表
    Route::get('/order_detail', 'OrderController@orderDetail'); //订单详情
    Route::post('/order_yue_pay', 'OrderController@orderYuepay'); //订单余额支付
    Route::post('/order_user_cancel', 'OrderController@userCancelOrder'); //用户取消订单
    Route::post('/order_user_receipt_confirm', 'OrderController@userReceiptConfirm'); //用户确认收货
    Route::post('/order_user_refund', 'OrderController@userOrderRefund'); //用户退款退货
    Route::post('/order_user_delete', 'OrderController@userOrderDelete'); //用户删除订单
    //购物车
    Route::get('/cart_list', 'CartController@cartList'); //购物车列表
    Route::post('/cart_clear', 'CartController@cartClear'); //清空购物车
    Route::post('/cart_add', 'CartController@cartAdd'); //添加购物车
    Route::post('/cart_delete', 'CartController@cartDelete'); //删除购物
    Route::get('/cart_checkout_goods_list', 'CartController@cartCheckoutGoodsList'); //购物车结算商品列表

    //分销

    //积分
    Route::get('/user_point_list', 'UserPointController@userPointList'); //用户积分列表
    Route::post('/user_point_add', 'UserPointController@userPointAdd');
    //优惠券
    Route::get('/user_available_bonus_list', 'UserBonusController@userAvailableBonusList'); //用户结算时获取可用优惠券列表
    Route::get('/user_bonus_list', 'UserBonusController@userBonusList'); //用户优惠券列表
    Route::post('/user_bonus_add', 'UserBonusController@userBonusAdd'); //用户获取优惠券
    Route::post('/bonus_add', 'BonusController@bonusAdd'); //添加优惠券
    Route::post('/bonus_update', 'BonusController@bonusUpdate'); //修改优惠券
    Route::post('/bonus_delete', 'BonusController@bonusDelete'); //删除优惠券
    //微信

    //意见反馈
    Route::get('/feedback_list', 'FeedBackController@feedbackList');
    Route::post('/feedback_add', 'FeedBackController@feedbackAdd');

    //其它
    Route::get('/verifycode_check', 'VerifyCodeController@verifyCodeCheck'); //验证码校验
    Route::get('/andriod_upgrade', 'IndexController@andriodUpgrade'); //安卓升级
    Route::get('/payment_list', 'PaymentController@paymentList'); //支付方式列表
    //图片上传
    Route::post('/image_upload', 'ImageController@imageUpload'); //普通文件/图片上传
    Route::post('/multiple_file_upload', 'ImageController@multipleFileUpload'); //多文件上传
    //二维码
    Route::get('/create_simple_qrcode', 'QrcodeController@createSimpleQrcode');
    //收货地址
    Route::get('/user_address_list', 'UserAddressController@userAddressList');
    Route::get('/user_address_detail', 'UserAddressController@userAddressDetail');
    Route::get('/user_default_address', 'UserAddressController@userDefaultAddress'); //获取用户默认地址
    Route::post('/user_address_setdefault', 'UserAddressController@userAddressSetDefault');
    Route::post('/user_address_add', 'UserAddressController@userAddressAdd');
    Route::post('/user_address_update', 'UserAddressController@userAddressUpdate');
    Route::post('/user_address_delete', 'UserAddressController@userAddressDelete');
});


//后台路由
Route::group(['prefix' => 'fladmin', 'namespace' => 'Admin', 'middleware' => ['web']], function () {
	Route::get('/', 'IndexController@index')->name('admin');
    Route::get('/page404', 'LoginController@page404')->name('admin_page404');                //404页面
	Route::get('/welcome', 'IndexController@welcome')->name('admin_welcome');                //后台欢迎页
	Route::get('/index/upconfig', 'IndexController@upconfig')->name('admin_index_upconfig'); //更新系统参数配置
	Route::get('/index/upcache', 'IndexController@upcache')->name('admin_index_upcache');    //更新缓存
	//文章
	Route::any('/article', 'ArticleController@index')->name('admin_article');
	Route::any('/article/add', 'ArticleController@add')->name('admin_article_add');
	Route::any('/article/edit', 'ArticleController@edit')->name('admin_article_edit');
	Route::any('/article/del', 'ArticleController@del')->name('admin_article_del');
	Route::get('/article/repetarc', 'ArticleController@repetarc')->name('admin_article_repetarc');
	Route::get('/article/recommendarc', 'ArticleController@recommendarc')->name('admin_article_recommendarc');
	Route::get('/article/articleexists', 'ArticleController@articleexists')->name('admin_article_articleexists');
	//文章分类
	Route::get('/article_type', 'ArticleTypeController@index')->name('admin_article_type');
	Route::any('/article_type/add', 'ArticleTypeController@add')->name('admin_article_type_add');
	Route::any('/article_type/edit', 'ArticleTypeController@edit')->name('admin_article_type_edit');
	Route::any('/article_type/del', 'ArticleTypeController@del')->name('admin_article_type_del');
	//标签
	Route::get('/tag', 'TagController@index')->name('admin_tag');
	Route::any('/tag/add', 'TagController@add')->name('admin_tag_add');
	Route::any('/tag/edit', 'TagController@edit')->name('admin_tag_edit');
	Route::any('/tag/del', 'TagController@del')->name('admin_tag_del');
	//单页
	Route::get('/page', 'PageController@index')->name('admin_page');
	Route::any('/page/add', 'PageController@add')->name('admin_page_add');
	Route::any('/page/edit', 'PageController@edit')->name('admin_page_edit');
	Route::any('/page/del', 'PageController@del')->name('admin_page_del');
	//产品
	Route::get('/goods', 'GoodsController@index')->name('admin_goods');
	Route::any('/goods/add', 'GoodsController@add')->name('admin_goods_add');
	Route::any('/goods/edit', 'GoodsController@edit')->name('admin_goods_edit');
	Route::any('/goods/del', 'GoodsController@del')->name('admin_goods_del');
	Route::get('/goods/recommendarc', 'GoodsController@recommendarc')->name('admin_goods_recommendarc');
	Route::get('/goods/goodsexists', 'GoodsController@goodsexists')->name('admin_goods_goodsexists');
	//产品分类
	Route::get('/goods_type', 'GoodsTypeController@index')->name('admin_goods_type');
	Route::any('/goods_type/add', 'GoodsTypeController@add')->name('admin_goods_type_add');
	Route::any('/goods_type/edit', 'GoodsTypeController@edit')->name('admin_goods_type_edit');
	Route::any('/goods_type/del', 'GoodsTypeController@del')->name('admin_goods_type_del');
    //订单
	Route::get('/order', 'OrderController@index')->name('admin_order');
    Route::get('/order/detail', 'OrderController@detail')->name('admin_order_detail');
	Route::get('/order/edit', 'OrderController@edit')->name('admin_order_edit');
	Route::any('/order/del', 'OrderController@del')->name('admin_order_del');
    Route::any('/order/output_excel', 'OrderController@output_excel')->name('admin_order_output_excel');
    Route::post('/order/change_shipping', 'OrderController@change_shipping')->name('admin_order_change_shipping');
    Route::post('/order/change_status', 'OrderController@change_status')->name('admin_order_change_status');
    //快递管理
	Route::get('/kuaidi', 'KuaidiController@index')->name('admin_kuaidi');
	Route::any('/kuaidi/add', 'KuaidiController@add')->name('admin_kuaidi_add');
	Route::any('/kuaidi/edit', 'KuaidiController@edit')->name('admin_kuaidi_edit');
	Route::get('/kuaidi/del', 'KuaidiController@del')->name('admin_kuaidi_del');
    //优惠券管理
	Route::get('/bonus', 'BonusController@index')->name('admin_bonus');
	Route::any('/bonus/add', 'BonusController@add')->name('admin_bonus_add');
	Route::any('/bonus/edit', 'BonusController@edit')->name('admin_bonus_edit');
	Route::any('/bonus/del', 'BonusController@del')->name('admin_bonus_del');
    //商品品牌
	Route::get('/goods_brand', 'GoodsBrandController@index')->name('admin_goods_brand');
	Route::any('/goods_brand/add', 'GoodsBrandController@add')->name('admin_goods_brand_add');
	Route::any('/goods_brand/edit', 'GoodsBrandController@edit')->name('admin_goods_brand_edit');
	Route::any('/goods_brand/del', 'GoodsBrandController@del')->name('admin_goods_brand_del');
	//友情链接
	Route::get('/friendlink', 'FriendlinkController@index')->name('admin_friendlink');
	Route::any('/friendlink/add', 'FriendlinkController@add')->name('admin_friendlink_add');
	Route::any('/friendlink/edit', 'FriendlinkController@edit')->name('admin_friendlink_edit');
	Route::any('/friendlink/del', 'FriendlinkController@del')->name('admin_friendlink_del');
	//关键词管理
	Route::get('/keyword', 'KeywordController@index')->name('admin_keyword');
	Route::any('/keyword/add', 'KeywordController@add')->name('admin_keyword_add');
	Route::any('/keyword/edit', 'KeywordController@edit')->name('admin_keyword_edit');
	Route::any('/keyword/del', 'KeywordController@del')->name('admin_keyword_del');
	//搜索关键词
	Route::get('/searchword', 'SearchwordController@index')->name('admin_searchword');
	Route::any('/searchword/add', 'SearchwordController@add')->name('admin_searchword_add');
	Route::any('/searchword/edit', 'SearchwordController@edit')->name('admin_searchword_edit');
	Route::any('/searchword/del', 'SearchwordController@del')->name('admin_searchword_del');
	//幻灯片
	Route::get('/slide', 'SlideController@index')->name('admin_slide');
	Route::any('/slide/add', 'SlideController@add')->name('admin_slide_add');
	Route::any('/slide/edit', 'SlideController@edit')->name('admin_slide_edit');
	Route::any('/slide/del', 'SlideController@del')->name('admin_slide_del');
	//在线留言管理
	Route::get('/guestbook', 'GuestbookController@index')->name('admin_guestbook');
	Route::any('/guestbook/del', 'GuestbookController@del')->name('admin_guestbook_del');
	//系统参数配置
    Route::get('/sysconfig', 'SysconfigController@index')->name('admin_sysconfig');
    Route::any('/sysconfig/other', 'SysconfigController@other')->name('admin_sysconfig_other');
	Route::any('/sysconfig/add', 'SysconfigController@add')->name('admin_sysconfig_add');
	Route::any('/sysconfig/edit', 'SysconfigController@edit')->name('admin_sysconfig_edit');
	Route::any('/sysconfig/del', 'SysconfigController@del')->name('admin_sysconfig_del');
    //意见反馈
	Route::get('/feedback', 'FeedbackController@index')->name('admin_feedback');
	Route::any('/feedback/add', 'FeedbackController@add')->name('admin_feedback_add');
	Route::any('/feedback/edit', 'FeedbackController@edit')->name('admin_feedback_edit');
	Route::any('/feedback/del', 'FeedbackController@del')->name('admin_feedback_del');
    //会员管理
	Route::get('/user', 'UserController@index')->name('admin_user');
	Route::any('/user/add', 'UserController@add')->name('admin_user_add');
	Route::any('/user/edit', 'UserController@edit')->name('admin_user_edit');
	Route::any('/user/del', 'UserController@del')->name('admin_user_del');
    //用户余额管理
    Route::get('/user_money', 'UserMoneyController@index')->name('admin_user_money'); // 会员账户记录
    Route::any('/user_money/add', 'UserMoneyController@add')->name('admin_user_money_add'); //人工充值
    //充值管理
    Route::get('/user_recharge', 'UserRechargeController@index')->name('admin_user_recharge');
    Route::any('/user_recharge/del', 'UserRechargeController@del')->name('admin_user_recharge_del');
    //会员等级管理
	Route::get('/user_rank', 'UserRankController@index')->name('admin_user_rank');
	Route::any('/user_rank/add', 'UserRankController@add')->name('admin_user_rank_add');
	Route::any('/user_rank/edit', 'UserRankController@edit')->name('admin_user_rank_edit');
	Route::any('/user_rank/del', 'UserRankController@del')->name('admin_user_rank_del');
    //提现申请
	Route::get('/user_withdraw', 'UserWithdrawController@index')->name('admin_user_withdraw');
	Route::any('/user_withdraw/edit', 'UserWithdrawController@edit')->name('admin_user_withdraw_edit');
    Route::post('/user_withdraw/change_status', 'UserWithdrawController@change_status')->name('admin_user_withdraw_change_status');
	//管理员管理
	Route::get('/admin', 'AdminController@index')->name('admin_admin');
	Route::any('/admin/add', 'AdminController@add')->name('admin_admin_add');
	Route::any('/admin/edit', 'AdminController@edit')->name('admin_admin_edit');
	Route::any('/admin/del', 'AdminController@del')->name('admin_admin_del');
	//角色管理
	Route::get('/admin_role', 'AdminRoleController@index')->name('admin_admin_role');
	Route::any('/admin_role/add', 'AdminRoleController@add')->name('admin_admin_role_add');
	Route::any('/admin_role/edit', 'AdminRoleController@edit')->name('admin_admin_role_edit');
	Route::any('/admin_role/del', 'AdminRoleController@del')->name('admin_admin_role_del');
	Route::any('/admin_role/permissions', 'AdminRoleController@permissions')->name('admin_admin_role_permissions'); //权限设置
	//菜单管理
	Route::get('/menu', 'MenuController@index')->name('admin_menu');
	Route::any('/menu/add', 'MenuController@add')->name('admin_menu_add');
	Route::any('/menu/edit', 'MenuController@edit')->name('admin_menu_edit');
	Route::any('/menu/del', 'MenuController@del')->name('admin_menu_del');
    //微信自定义菜单管理
	Route::get('/weixinmenu', 'WeixinMenuController@index')->name('admin_weixinmenu');
	Route::any('/weixinmenu/add', 'WeixinMenuController@add')->name('admin_weixinmenu_add');
	Route::any('/weixinmenu/edit', 'WeixinMenuController@edit')->name('admin_weixinmenu_edit');
	Route::any('/weixinmenu/del', 'WeixinMenuController@del')->name('admin_weixinmenu_del');
    Route::get('/weixinmenu/createmenu', 'WeixinMenuController@createmenu')->name('admin_weixinmenu_createmenu'); //生成自定义菜单
	//后台登录注销
	Route::any('/login', 'LoginController@index')->name('admin_login');
	Route::get('/logout', 'LoginController@logout')->name('admin_logout');
	Route::get('/recoverpwd', 'LoginController@recoverpwd')->name('admin_recoverpwd');
	//操作日志
    Route::any('/log', 'LogController@index')->name('admin_log');
    Route::any('/log/del', 'LogController@del')->name('admin_log_del');
    Route::any('/log/clear', 'LogController@clear')->name('admin_log_clear');
	//数据库备份
	Route::any('/database', 'DatabaseController@index')->name('admin_database');
	Route::any('/database/optimize', 'DatabaseController@optimize')->name('admin_database_optimize'); //优化表
	Route::any('/database/repair', 'DatabaseController@repair')->name('admin_database_repair'); //修复表
	Route::any('/database/tables_backup', 'DatabaseController@tables_backup')->name('admin_database_tables_backup'); //备份数据库
	//广告管理
	Route::get('/ad', 'AdController@index')->name('admin_ad');
	Route::any('/ad/add', 'AdController@add')->name('admin_ad_add');
	Route::any('/ad/edit', 'AdController@edit')->name('admin_ad_edit');
	Route::any('/ad/del', 'AdController@del')->name('admin_ad_del');
    //页面跳转
    Route::get('/jump', 'LoginController@jump')->name('admin_jump');
	//测试
	Route::any('/test', 'LoginController@test')->name('admin_test');
});
