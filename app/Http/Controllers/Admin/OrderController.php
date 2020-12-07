<?php

namespace App\Http\Controllers\Admin;

use App\Http\Logic\OrderLogic;
use App\Http\Model\OrderModel;
use App\Http\Model\OrderGoodsModel;
use App\Http\Model\UserModel;
use App\Http\Model\RegionModel;
use Illuminate\Support\Facades\DB;
use App\Common\Librarys\ReturnData;
use App\Common\Librarys\Helper;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OrderExport;

class OrderController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getLogic()
    {
        return new OrderLogic();
    }

    //订单列表
    public function index()
    {
        $res = '';
        $where = function ($query) use ($res) {
            if (isset($_REQUEST["keyword"])) {
                $query->where('name', 'like', '%' . $_REQUEST['keyword'] . '%')->orWhere('mobile', 'like', '%' . $_REQUEST['keyword'] . '%')->orWhere('order_sn', 'like', '%' . $_REQUEST['keyword'] . '%');
            }
            if (isset($_REQUEST['mobile']) && $_REQUEST['mobile'] != '') {
                $query->where('mobile', 'like', $_REQUEST['mobile']);
            }
            if (isset($_REQUEST['order_sn']) && $_REQUEST['order_sn'] != '') {
                $query->where('order_sn', 'like', $_REQUEST['order_sn']);
            }
            if (isset($_REQUEST['name']) && $_REQUEST['name'] != '') {
                $query->where('name', 'like', $_REQUEST['name']);
            }

            //0或者不传表示全部，1待付款，2待发货,3待收货,4待评价(确认收货，交易成功),5退款/售后
            if (isset($_REQUEST['status']) && $_REQUEST['status'] > 0) {
                if ($_REQUEST['status'] == 1) {
                    $query->where('order_status', '=', 0)->where('pay_status', '=', 0);
                } elseif ($_REQUEST['status'] == 2) {
                    $query->where('order_status', '=', 0)->where('shipping_status', '=', 0)->where('pay_status', '=', 1);
                } elseif ($_REQUEST['status'] == 3) {
                    $query->where('order_status', '=', 0)->where('refund_status', '=', 0)->where('shipping_status', '=', 1)->where('pay_status', '=', 1);
                } elseif ($_REQUEST['status'] == 4) {
                    $query->where('order_status', '=', 3)->where('refund_status', '=', 0)->where('shipping_status', '=', 2)->where('is_comment', '=', 0);
                } elseif ($_REQUEST['status'] == 5) {
                    $query->where('order_status', '=', 3)->where('refund_status', '=', 1);
                }
            }
        };
        $list = $this->getLogic()->getPaginate($where, ['id', 'desc']);
        $assign_data['list'] = $list;

        return view('admin.order.index', $assign_data);
    }

    //订单详情
    public function detail()
    {
        if (!check_is_number(request('id', null))) {
            error_jump('参数错误');
        }
        $id = request('id');

        $assign_data['post'] = logic('Order')->getOne(array('id' => $id));
        $assign_data['kuaidi'] = model('Kuaidi')->getAll(array('status' => 0), ['listorder', 'asc']);

        return view('admin.order.detail', $assign_data);
    }

    //添加
    public function add()
    {
        if (Helper::isPostRequest()) {
            $res = $this->getLogic()->add($_POST);
            if ($res['code'] != ReturnData::SUCCESS) {
                error_jump($res['msg']);
            }
            success_jump($res['msg'], route('admin_order'));
        }

        return view('admin.order.add');
    }

    //修改
    public function edit()
    {
        if (Helper::isPostRequest()) {
            $where['id'] = $_POST['id'];
            unset($_POST['id']);

            $res = $this->getLogic()->edit($_POST, $where);
            if ($res['code'] != ReturnData::SUCCESS) {
                error_jump($res['msg']);
            }
            success_jump($res['msg'], route('admin_order'));
        }

        if (!check_is_number(request('id', null))) {
            error_jump('参数错误');
        }
        $where['id'] = request('id');
        $assign_data['id'] = $where['id'];

        $post = $this->getLogic()->getOne($where);
        $assign_data['post'] = $post;

        return view('admin.order.edit', $assign_data);
    }

    //删除
    public function del()
    {
        if (!check_is_number(request('id', null))) {
            error_jump('删除失败！请重新提交');
        }
        $where['id'] = request('id');

        $res = $this->getLogic()->del($where);
        if ($res['code'] != ReturnData::SUCCESS) {
            error_jump($res['msg']);
        }

        success_jump('删除成功');
    }

    //发货修改物流信息
    public function change_shipping()
    {
        if (!check_is_number(request('id', null))) {
            exit(json_encode(ReturnData::create(ReturnData::PARAMS_ERROR)));
        }
        $id = request('id');
        $order = model('Order')->getOne(array('id' => $id));
        if (!$order) {
            exit(json_encode(ReturnData::create(ReturnData::PARAMS_ERROR, null, '订单不存在')));
        }
        if ($order->shipping_status != 0) {
            exit(json_encode(ReturnData::create(ReturnData::PARAMS_ERROR, null, '当前订单不可操作')));
        }

        $data['shipping_id'] = request('shipping_id', '');
        $data['shipping_sn'] = request('shipping_sn', '');

        if (!$data['shipping_id']) {
            exit(json_encode(ReturnData::create(ReturnData::PARAMS_ERROR)));
        }

        if ($data['shipping_id'] == 0) {
            $data['shipping_name'] = '无须物流';
            unset($data['shipping_sn']);
        } else {
            if ($data['shipping_sn'] == '') {
                exit(json_encode(ReturnData::create(ReturnData::PARAMS_ERROR)));
            }

            $shipping_name = model('Kuaidi')->getValue(array('id' => $data['shipping_id']), 'name');
            if (!$shipping_name) {
                exit(json_encode(ReturnData::create(ReturnData::PARAMS_ERROR, null, '快递公司不存在')));
            }
            $data['shipping_name'] = $shipping_name;
        }

        $data['shipping_status'] = 1;
        if (!model('Order')->edit($data, array('id' => $id))) {
            exit(json_encode(ReturnData::create(ReturnData::FAIL)));
        }
        exit(json_encode(ReturnData::create(ReturnData::SUCCESS)));
    }

    //修改订单状态
    public function change_status()
    {
        if (!check_is_number(request('id', null))) {
            exit(json_encode(ReturnData::create(ReturnData::PARAMS_ERROR)));
        }
        $id = request('id');
        $order = model('Order')->getOne(array('id' => $id));
        if (!$order) {
            exit(json_encode(ReturnData::create(ReturnData::PARAMS_ERROR, null, '订单不存在')));
        }
        $status = request('status', '');
        if ($status == '') {
            exit(json_encode(ReturnData::create(ReturnData::PARAMS_ERROR)));
        }

        //2设为已付款，3发货，4设为已收货，7设为无效，8同意退款
        if ($status == 2) {
            $data['pay_status'] = 1;

            //...
        } elseif ($status == 3) {
            $data['shipping_status'] = 1;
        } elseif ($status == 4) {
            $data['order_status'] = 3;
            $data['shipping_status'] = 2;

            //...
        } elseif ($status == 7) {
            $data['order_status'] = 2;

            //返库存
            if (!model('Order')->returnStock($id)) {
                exit(json_encode(ReturnData::create(ReturnData::FAIL)));
            }
        } elseif ($status == 8) {
            $data['refund_status'] = 2;

            if ($order['pay_money'] > 0) {
                //增加用户余额及余额记录
                $user_money_data['user_id'] = $order->user_id;
                $user_money_data['type'] = 0;
                $user_money_data['money'] = $order->pay_money;
                $user_money_data['desc'] = '退货-返余额';
                $user_money = logic('UserMoney')->add($user_money_data);
                if ($user_money['code'] != ReturnData::SUCCESS) {
                    exit(json_encode(ReturnData::create(ReturnData::FAIL)));
                }
            }

            //返库存
            if (!model('Order')->returnStock($id)) {
                exit(json_encode(ReturnData::create(ReturnData::FAIL)));
            }
        }

        if (!model('Order')->edit($data, array('id' => $id))) {
            exit(json_encode(ReturnData::create(ReturnData::FAIL)));
        }

        exit(json_encode(ReturnData::create(ReturnData::SUCCESS)));
    }

    //导出订单Excel
    public function output_excel()
    {
        $res = '';
        $where = function ($query) use ($res) {
            $query->where('delete_time', '=', OrderModel::ORDER_UNDELETE);
            if (isset($_REQUEST["keyword"]) && $_REQUEST['keyword'] != '') {
                $query->where('name', 'like', '%' . $_REQUEST['keyword'] . '%')->orWhere('mobile', 'like', '%' . $_REQUEST['keyword'] . '%')->orWhere('order_sn', 'like', '%' . $_REQUEST['keyword'] . '%');
            }
            if (isset($_REQUEST["mobile"]) && $_REQUEST['mobile'] != '') {
                $query->where('mobile', 'like', '%' . $_REQUEST['mobile'] . '%');
            }
            if (isset($_REQUEST["order_sn"]) && $_REQUEST['order_sn'] != '') {
                $query->where('order_sn', 'like', '%' . $_REQUEST['order_sn'] . '%');
            }
            if (isset($_REQUEST["name"]) && $_REQUEST['name'] != '') {
                $query->where('name', 'like', '%' . $_REQUEST['name'] . '%');
            }
            if (isset($_REQUEST['min_addtime']) && isset($_REQUEST['max_addtime']) && !empty($_REQUEST['min_addtime']) && !empty($_REQUEST['max_addtime'])) {
                $query->where('add_time', '>=', strtotime($_REQUEST['min_addtime']));
                $query->where('add_time', '<=', strtotime($_REQUEST['max_addtime']));
            }
            //0或者不传表示全部，1待付款，2待发货,3待收货,4待评价(确认收货，交易成功),5退款/售后
            if (isset($_REQUEST['status']) && $_REQUEST['status'] > 0) {
                if ($_REQUEST['status'] == 1) {
                    $query->where('order_status', '=', 0);
                    $query->where('pay_status', '=', 0);
                } elseif ($_REQUEST['status'] == 2) {
                    $query->where('order_status', '=', 0);
                    $query->where('shipping_status', '=', 0);
                    $query->where('pay_status', '=', 1);
                } elseif ($_REQUEST['status'] == 3) {
                    $query->where('order_status', '=', 0);
                    $query->where('refund_status', '=', 0);
                    $query->where('shipping_status', '=', 1);
                    $query->where('pay_status', '=', 1);
                } elseif ($_REQUEST['status'] == 4) {
                    $query->where('order_status', '=', 3);
                    $query->where('refund_status', '=', 0);
                    $query->where('shipping_status', '=', 2);
                    $query->where('is_comment', '=', 1);
                } elseif ($_REQUEST['status'] == 5) {
                    $query->where('order_status', '=', 3);
                    $query->where('refund_status', '=', 1);
                }
            }
        };

        //导出Excel
        /* $cellData = [
            ['学号','姓名','成绩'],
            ['10001','AAAAA','99'],
            ['10002','BBBBB','92'],
            ['10003','CCCCC','95'],
            ['10004','DDDDD','89'],
            ['10005','EEEEE','96'],
        ]; */
        $excel_title = array('ID', '订单号', '时间', '状态', '商品总价', '应付金额', '支付金额', '收货人', '地址', '电话', '订单来源');
        $cellData = array();
        array_push($cellData, $excel_title);
        $order_list = logic('Order')->getAll($where, ['id', 'desc']);
        if ($order_list) {
            foreach ($order_list as $k => $v) {
                array_push($cellData, array($v->id, (string)$v->order_sn, date('Y-m-d H:i:s', $v->add_time), $v->order_status_text, $v->goods_amount, $v->order_amount, $v->pay_money, $v->name, $v->province_name . $v->city_name . $v->district_name . ' ' . $v->address, (string)$v->mobile, $v->place_type_text));
            }
        }
        $export = new OrderExport($cellData);
        return Excel::download($export, '订单列表.xlsx');
    }

}