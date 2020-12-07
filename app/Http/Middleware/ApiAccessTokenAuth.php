<?php

namespace App\Http\Middleware;

use Closure;
use App\Common\Librarys\ReturnData;
use App\Common\Librarys\Helper;

class ApiAccessTokenAuth
{
    /**
     * Token验证
     * token可以在header里面传递【AccessToken】，也可以在参数里面传【access_token】，注意区分大小写
     */
    public function handle($request, Closure $next)
    {
		//前中间件代码
		$access_token = $request->header('AccessToken', '') ?: $request->input('access_token', '');
        
        if (!$access_token) {
            return ReturnData::create(ReturnData::TOKEN_ERROR);
        }
		$res = logic('Token')->checkToken($access_token);
        if ($res['code'] != ReturnData::SUCCESS) {
            return $res;
        }
		

        $request->merge(['token_info' => $res['data']]); // 合并参数，将token合并
		
		$response = $next($request);
		//后中间件代码
		
        return $response;
    }
}