<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/11/27
 * Time: 10:39
 * Desc: 处理客户端发来的请求
 */

namespace App\Http\Controllers\Login4;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{

    /**
     * 内部WEB进入游戏的入口
     *
     * @return \Illuminate\Http\Response
     */
    public function proload_login4(Request $request)
    {
        $account_name = $request->get('account_name', NULL, '');
        $validate_key = $request->get('validate_key', NULL, '');
        //$generate_time = $request->get('generate_time', NULL, '');
        $generate_time = time();
		$platform = 'oas';
		$server_id = 1;
        $create_role = 0;
		$account_info = \DB::connection('game')->table('TAccount')->where('account_name', '=', $uid)->first();
		if ( $account_info )
		{
			$role_info = \DB::connection('game')->table('TActRoleInfo')->whereRaw('account_id = ? AND server_id = ?', [(int)$server_id, (int)$account_info->account_Id])->first();
			if ( $create_role )
			{
				$create_role = 0;
			}
			else
			{
				$create_role = 1;
			}
		}
		
		//使用login的view来显示
        return view('login.login')
            ->with('game_url', env('CLIENT_ROOT_URL4'))
            ->with('loginserver_ip', env('LOGIN_SERVER_IP4'))
            ->with('loginserver_port', env('LOGIN_SERVER_PORT4'))
            ->with('account_name', $account_name)
            ->with('validate_key', $validate_key)
            ->with('generate_time', $generate_time)
			->with('platform', $platform)
			->with('server_id', $server_id)
			->with('create_role', $create_role);
    }
}