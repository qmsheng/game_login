<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/11/27
 * Time: 11:48
 */

namespace App\Http\Controllers\Oas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

class IndexController extends Controller
{
    public function oas_login(Request $request)
    {
		//date_default_timezone_set('PRC');
		date_default_timezone_set("Etc/GMT-3");
        $uid = Input::get('uid');
        $nickname = Input::get('nickname');
        $invite_uid = Input::get('invite_uid');
		$face_url = Input::get('face_url');
		$create_time = Input::get('time');
		$server_id = Input::get('server_id');
		$sign = Input::get('sign');
		$md5sign = '';

		$msgRet = 1;
        if ($request->getMethod() == 'POST' || $request->getMethod() == 'GET') 
		{
            if ($uid == '' || $create_time == '' || $server_id == '' || $sign == '') 
			{
				$msgRet = -4;
            } 
			else 
			{
                $time = time();
				$localsign = $uid . $nickname . $invite_uid. $face_url . $create_time . $server_id . env('OAS_KEY');
				$md5sign = md5($localsign);
				
                if ( $sign == $md5sign )
				{
					$msgRet = 1;
				}
				else
				{
					$msgRet = -1;
				} 
            }
        }
		
		if ( $msgRet == 1 )
		{
			$platform = 'Oas';
			$login_uid = \DB::connection('login_sign')->table('TSignInfo')->where('uid', '=', $uid)->first();
			if ( $login_uid )
			{
				$create_time = (int)$create_time;
				$userdate = date("Y-m-d H:i:s",$create_time);
				\DB::connection('login_sign')->update('update TSignInfo set create_time = ?, sign = ?, server_id = ? where uid = ?', [$userdate, $md5sign, $server_id, $uid]);
			}
			else
			{
				$timeout = 120;
				$create_time = (int)$create_time;
				$userdate = date("Y-m-d H:i:s",$create_time);
				\DB::connection('login_sign')->insert('insert into TSignInfo(uid, create_time, sign, validity_time, platform, server_id) values(?,?,?,?,?,?)', [$uid, $userdate, $md5sign, (int)$timeout, $platform, (int)$server_id]);
			}
				
			$empty_data = '';
			$server_name = 'loginserver';
			$log_type = 13;
			\DB::connection('logdb')->insert('insert into TLog(LogType, RoleId, AccountId, uid, Data1, Message, strData1, strData2, strData3, numberData1, numberData2, numberData3) values(?,?,?,?,?,?,?,?,?,?,?,?)', [(int)$log_type, 0, 0, $uid, $server_name, $empty_data, $platform, $empty_data, $empty_data, 0, 0, 0]);
			
			$log_type = 15;
			\DB::connection('logdb')->insert('insert into TLog(LogType, RoleId, AccountId, uid, Data1, Message, strData1, strData2, strData3, numberData1, numberData2, numberData3) values(?,?,?,?,?,?,?,?,?,?,?,?)', [(int)$log_type, 0, 0, $uid, $server_name, $empty_data, $platform, $empty_data, $empty_data, 10000, 0, 0]);
		}

        return $msgRet;
    }

	public function oas_join(Request $request)
	{
		//date_default_timezone_set('PRC');
		date_default_timezone_set("Etc/GMT-3");
		if ($request->getMethod() == 'POST' || $request->getMethod() == 'GET') 
		{
			$sign = Input::get('sign');
			if ( $sign )
			{
				$login_sign = \DB::connection('login_sign')->table('TSignInfo')->where('sign', '=', $sign)->first();
				if ( $login_sign )
				{
					$server_info = \DB::connection('laraveldb')->table('servers')->where('server_id', '=', $login_sign->server_id)->first();
					if ( $server_info )
					{
						$generate_time = time();
						if ( (int)$generate_time > (int)strtotime($login_sign->create_time) + $login_sign->validity_time )
						{
						//	return -2;
						}
						$token = $sign;
						$uid = $login_sign->uid;
						$platform = $login_sign->platform;
						$server_id = $login_sign->server_id;
						$create_role = 1;
						$account_info = \DB::connection('game')->table('TAccount')->where('account_name', '=', $uid)->first();
						if ( $account_info )
						{
							$role_info = \DB::connection('game')->table('TActRoleInfo')->whereRaw('account_id = ? AND server_id = ?', [(int)$account_info->account_Id, (int)$server_id])->first();
							if ( $role_info )
							{
								$create_role = 0;
							}
						}
						return view('Oas.oas')
							->with('game_url', env('CLIENT_ROOT_URL'))
							->with('gs_ip', $server_info->server_ip)
							->with('gs_port', $server_info->login_port)
							->with('uid', $uid)
							->with('platform', $platform)
							->with('validate_key', $token)
							->with('generate_time', $generate_time)
							->with('server_id', $server_id)
							->with('create_role', $create_role);
					}
					else
					{
						return -4;
					}
				}
				else
				{
					return -1;
				}
			}
		}
		
		return -3;
	}
	
    public function player_query(Request $request)
    {
		$error_code = -999;
		$ret_info = '';
		if ($request->getMethod() == 'POST' || $request->getMethod() == 'GET') 
		{
			$uid = Input::get('uid');
			$time = Input::get('time');
			$server_id = Input::get('server_id');
			$sign = Input::get('sign');

			$localsign = $uid . $server_id . $time . env('OAS_KEY');
			$md5sign = md5($localsign);
			if ( $sign == $md5sign )
			{
				if ( 2 == (int)$server_id )
				{
					$account_info = \DB::connection('game2')->table('TAccount')->where('account_name', '=', $uid)->first();
					if ( $account_info )
					{
						$role_info = \DB::connection('game2')->table('TActRoleInfo')->where('account_id', '=', (int)$account_info->account_Id)->first();
						if ( $role_info )
						{
							$ret_info = '{"status":"ok","uid":"' . $uid . '","roles":[{"name":"' . $role_info->name . '","level":' . $role_info->level . ', "roleid":' . $role_info->role_id . '}]}';
						}
						else
						{
							$error_code = -2;
							$ret_info = '{"status":"fail","error":"' . $error_code . '"}]}';
						}
					}
					else
					{
						$error_code = -2;
						$ret_info = '{"status":"fail","error":"' . $error_code . '"}]}';
					}
				}
				else
				{
					$account_info = \DB::connection('game')->table('TAccount')->where('account_name', '=', $uid)->first();
					if ( $account_info )
					{
						$role_info = \DB::connection('game')->table('TActRoleInfo')->where('account_id', '=', (int)$account_info->account_Id)->first();
						if ( $role_info )
						{
							$ret_info = '{"status":"ok","uid":"' . $uid . '","roles":[{"name":"' . $role_info->name . '","level":' . $role_info->level . ', "roleid":' . $role_info->role_id . '}]}';
						}
						else
						{
							$error_code = -2;
							$ret_info = '{"status":"fail","error":"' . $error_code . '"}]}';
						}
					}
					else
					{
						$error_code = -2;
						$ret_info = '{"status":"fail","error":"' . $error_code . '"}]}';
					}
				}
				
				return $ret_info;
			}
			else
			{
				$error_code = -1;
				$ret_info = '{"status":"fail","error":"' . $error_code . '"}]}';
				return $ret_info;
			}
		}
		
		$ret_info = '{"status":"fail","error":"' . $error_code . '"}]}';
		return $ret_info;
    }
	
	public function online_query(Request $request)
    {
		date_default_timezone_set("Etc/GMT-3");
		$error_code = -1;
		if ($request->getMethod() == 'POST' || $request->getMethod() == 'GET') 
		{
			$log_info = \DB::connection('logdb')->table('TLog')->select('numberData1')->whereRaw(' LogType=5 AND Data1="gs1" ORDER BY LogTime DESC ')->first();
			if ( $log_info )
			{			
				return $log_info->numberData1;
			}
			else
			{
				return 0;
			}
			$role_info = 0;
			$time = time();
			$gs_count = \DB::connection('game')->table('TGsData')->whereRaw('gs_name = "gs1" AND data_type = 6')->count();
			if ( 1 == (int)$gs_count )
			{
				$gs_login_info = \DB::connection('game')->table('TGsData')->whereRaw('gs_name = "gs1" AND data_type = 6')->first();
				$gs_login_time = (int)($gs_login_info->content);
				$user_time_date = date("Y-m-d H:i:s",$gs_login_time);
				$role_info = \DB::connection('game')->table('TActRoleInfo')->whereRaw('last_logout_time < last_login_time AND last_login_time > ? ', [$user_time_date])->count();
			}
			else
			{
				$role_info = \DB::connection('game')->table('TActRoleInfo')->whereRaw('last_logout_time < last_login_time AND last_login_time > "2016-12-22 05:08:0" ')->count();
			}
			
			if ( $role_info )
			{
				return $role_info;
			}
			else
			{
				return 0;
			}
		}
		
		return $error_code;
    }
	
	public function register_query(Request $request)
    {
		$error_code = -1;
		if ($request->getMethod() == 'POST' || $request->getMethod() == 'GET') 
		{
			$role_info = \DB::connection('game')->table('TActRoleInfo')->count();
			if ( $role_info )
			{		
				return $role_info;
			}
			else
			{
				return 0;
			}
		}
		
		return $error_code;
    }
    
    //添加日志打印接口
    public function add_log(Request $request)
    {
        $error_code = 1;
        
        $uid = Input::get('uid');
        $logid= Input::get('logid');
        $log_type= Input::get('type');
        
        $platform = 'Oas';
        
        $empty_data = '';
		$server_name = 'loginserver';
        
		\DB::connection('logdb')->insert('insert into TLog(LogType, RoleId, AccountId, uid, Data1, Message, strData1, strData2, strData3, numberData1, numberData2, numberData3) values(?,?,?,?,?,?,?,?,?,?,?,?)', [(int)$log_type, 0, 0, $uid, $server_name, $empty_data, $platform, $empty_data, $empty_data, $logid, 0, 0]);
        
        return $error_code;
    }
	
	public function recharge(Request $request)
	{
		//date_default_timezone_set('PRC');
		date_default_timezone_set("Etc/GMT-3");
		$order_id = Input::get('order_id');
		$uid = Input::get('uid');
		$server_id = Input::get('server_id');
		$gamecoins = Input::get('gamecoins');
		$payway = Input::get('payway');
		$money = Input::get('money');
		$moneytype = Input::get('moneytype');
		$sign = Input::get('sign');
		$time = time();

		$userdate = date("Y-m-d H:i:s",$time);
		$platform = 'Oas';
		$md5sign = '';
		$msgRet = 1;
		if ($request->getMethod() == 'POST' || $request->getMethod() == 'GET') 
		{
			$localsign = $order_id . $uid . $server_id. $gamecoins . $payway . $money . $moneytype . env('OAS_RECHARGE_KEY');
			$md5sign = md5($localsign);
			if ( $md5sign == $sign )
			{
				if ( 2 == $server_id )
				{
					$order_info = \DB::connection('recharge2')->table('TRechargeInfo')->where('order_id', '=', $order_id , 'AND' , 'platform', '=', $platform)->first();
					if ( $order_info )
					{
						return -4;
						//\DB::connection('recharge')->update('update TRechargeInfo set uid = ?, server_id = ?, gamecoins = ?, payway = ?, money = ?, moneytype = ?, create_time = ?, sign = ? where order_id = ? AND platform = ?', [$uid, $server_id, $gamecoins, $payway, $money, $moneytype, $userdate, $md5sign, $order_id, $platform]);
					}
					else
					{
						\DB::connection('recharge2')->insert('insert into TRechargeInfo(order_id, platform, uid, server_id, gamecoins, payway, money, moneytype, sign, create_time) values(?,?,?,?,?,?,?,?,?,?)', [$order_id, $platform, $uid, $server_id, $gamecoins, $payway, $money, $moneytype, $md5sign, $userdate]);
						$empty_data = '';
						$log_type = 18;
						\DB::connection('logdb')->insert('insert into TLog(LogType, RoleId, AccountId, uid, Data1, Message, strData1, strData2, strData3, numberData1, numberData2, numberData3, server_id) values(?,?,?,?,?,?,?,?,?,?,?,?,?)', [(int)$log_type, 0, 0, $uid, $empty_data, $empty_data, $order_id, $moneytype, $payway, (int)$money, (int)$gamecoins, 0, (int)$server_id]);
					}
				}
				else
				{
					$order_info = \DB::connection('recharge')->table('TRechargeInfo')->where('order_id', '=', $order_id , 'AND' , 'platform', '=', $platform)->first();
					if ( $order_info )
					{
						return -4;
						//\DB::connection('recharge')->update('update TRechargeInfo set uid = ?, server_id = ?, gamecoins = ?, payway = ?, money = ?, moneytype = ?, create_time = ?, sign = ? where order_id = ? AND platform = ?', [$uid, $server_id, $gamecoins, $payway, $money, $moneytype, $userdate, $md5sign, $order_id, $platform]);
					}
					else
					{
						\DB::connection('recharge')->insert('insert into TRechargeInfo(order_id, platform, uid, server_id, gamecoins, payway, money, moneytype, sign, create_time) values(?,?,?,?,?,?,?,?,?,?)', [$order_id, $platform, $uid, $server_id, $gamecoins, $payway, $money, $moneytype, $md5sign, $userdate]);
                        $empty_data = '';
						$log_type = 18;
						\DB::connection('logdb')->insert('insert into TLog(LogType, RoleId, AccountId, uid, Data1, Message, strData1, strData2, strData3, numberData1, numberData2, numberData3, server_id) values(?,?,?,?,?,?,?,?,?,?,?,?,?)', [(int)$log_type, 0, 0, $uid, $empty_data, $empty_data, $order_id, $moneytype, $payway, (int)$money, (int)$gamecoins, 0, (int)$server_id]);
					}
				}
			}
			else
			{
				$msgRet = -7;
			}
		}
		else
		{
			$msgRet = -2;
		}
		
		if ( 1 == $msgRet )
		{
			$data = '';
			if ( 2 == $server_id )
			{
				$data = env('GAME_SERVER_HTTP_URL2') . 'recharge?uid=' . $uid . '&gamecoins=' . $gamecoins;
			}
			else
			{
				$data = env('GAME_SERVER_HTTP_URL') . 'recharge?uid=' . $uid . '&gamecoins=' . $gamecoins;
			}
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $data);
			curl_setopt($ch, CURLOPT_TIMEOUT, 10);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_POST, 1);  
			$response = curl_exec($ch);
			curl_close($ch);
			return $msgRet;
		}
		
		return $msgRet;
	}

	public function recharge_bak(Request $request)
	{
		$order_id = Input::get('order_id');
		$uid = Input::get('uid');
		$server_id = Input::get('server_id');
		$gamecoins = Input::get('gamecoins');
		$payway = Input::get('payway');
		$money = Input::get('money');
		$moneytype = Input::get('moneytype');
		$sign = Input::get('sign');
		$time = time();

		$platform = 'Oas';
		$md5sign = '';
		$msgRet = 1;
		if ($request->getMethod() == 'POST' || $request->getMethod() == 'GET') 
		{
			$localsign = $order_id . $uid . $server_id. $gamecoins . $payway . $money . $moneytype . env('OAS_RECHARGE_KEY');
			$md5sign = md5($localsign);
			if ( $md5sign == $sign )
			{
				$order_info = \DB::connection('recharge')->table('TRechargeInfo')->where('order_id', '=', $order_id , 'AND' , 'platform', '=', $platform)->first();
				if ( $order_info )
				{
					\DB::connection('recharge')->update('update TRechargeInfo set uid = ?, server_id = ?, gamecoins = ?, payway = ?, money = ?, moneytype = ?, create_time = ?, sign = ? where order_id = ? AND platform = ?', [$uid, $server_id, $gamecoins, $payway, $money, $moneytype, $time, $md5sign, $order_id, $platform]);
				}
				else
				{
					\DB::connection('recharge')->insert('insert into TRechargeInfo(order_id, platform, uid, server_id, gamecoins, payway, money, moneytype, sign, create_time) values(?,?,?,?,?,?,?,?,?,?)', [$order_id, $platform, $uid, $server_id, $gamecoins, $payway, $money, $moneytype, $md5sign, $time]);
				}
			}
			else
			{
				$msgRet = -7;
			}
		}
		else
		{
			$msgRet = -2;
		}
		
		if ( 1 == $msgRet )
		{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, env('GAME_SERVER_HTTP_URL'));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			$response = curl_exec($ch);
			curl_close($ch);
			return $msgRet;
		}
		
		return $msgRet;
	}
}
