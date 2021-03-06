<?php
namespace App\Http\Controllers\Api;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Common\Librarys\ReturnData;
use App\Common\Librarys\Helper;
use App\Http\Model\UserAddressModel;
use App\Http\Logic\UserAddressLogic;

class UserAddressController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }
	
    public function getLogic()
    {
        return logic('UserAddress');
    }
    
    public function userAddressList(Request $request)
	{
        //参数
        $limit = $request->input('limit', 10);
        $offset = $request->input('offset', 0);
        
        $where['user_id'] = Token::$uid;
        
        $res = $this->getLogic()->getList($where, array('id', 'desc'), '*', $offset, $limit);
		
		return ReturnData::create(ReturnData::SUCCESS,$res);
    }
    
    public function userAddressDetail(Request $request)
	{
        //参数
        if($request->input('id',null) != null){$where['id'] = $request->input('id');}
        $where['user_id'] = Token::$uid;
        
        $res = $this->getLogic()->getOne($where);
		if(!$res)
		{
			return ReturnData::create(ReturnData::RECORD_NOT_EXIST);
		}
        
		return ReturnData::create(ReturnData::SUCCESS,$res);
    }
    
    //添加
    public function userAddressAdd(Request $request)
    {
        if(Helper::isPostRequest())
        {
            $_POST['user_id'] = Token::$uid;
            
            return $this->getLogic()->add($_POST);
        }
    }
    
    //修改
    public function userAddressUpdate(Request $request)
    {
        if(!checkIsNumber($request->input('id',null))){return ReturnData::create(ReturnData::PARAMS_ERROR);}
        $id = $request->input('id');
        
        if(Helper::isPostRequest())
        {
            unset($_POST['id']);
            $where['id'] = $id;
            $where['user_id'] = Token::$uid;
            
            return $this->getLogic()->edit($_POST,$where);
        }
    }
    
    //删除
    public function userAddressDelete(Request $request)
    {
        if(!checkIsNumber($request->input('id',null))){return ReturnData::create(ReturnData::PARAMS_ERROR);}
        $id = $request->input('id');
        
        if(Helper::isPostRequest())
        {
            $where['id'] = $id;
            $where['user_id'] = Token::$uid;
            
            return $this->getLogic()->del($where);
        }
    }
    
    //设为默认地址
    public function userAddressSetDefault(Request $request)
	{
        //参数
        if(!checkIsNumber($request->input('id',null))){return ReturnData::create(ReturnData::PARAMS_ERROR);}
        $id = $request->input('id');
        
        $where['id'] = $id;
        $where['user_id'] = Token::$uid;
        $res = $this->getLogic()->setDefault($where);
		if($res)
		{
			return ReturnData::create(ReturnData::SUCCESS,$res);
		}
        
		return ReturnData::create(ReturnData::FAIL);
    }
    
    //获取用户默认地址
    public function userDefaultAddress(Request $request)
	{
        $where['user_id'] = Token::$uid;
        $res = $this->getLogic()->userDefaultAddress($where);
		if($res)
		{
			return ReturnData::create(ReturnData::SUCCESS,$res);
		}
        
		return ReturnData::create(ReturnData::FAIL);
    }
}