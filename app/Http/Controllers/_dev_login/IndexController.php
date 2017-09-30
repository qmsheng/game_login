<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/11/27
 * Time: 10:39
 * Desc: 处理客户端发来的请求
 */

namespace App\Http\Controllers\_dev_login;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Api\IODCommonApi;

class IndexController extends Controller
{
    private $platform_name = 'dev';
    public function login_debug(Request $request)
    {
        $userid = '';
        $server_id = $request->get('server_id', NULL, '');
        $gs_name = $request->get('gs', NULL, '');
        $server_id = 1;
        
        $sdk = new IODCommonApi('dev');
        $sdk->setSpecialSvr($gs_name);
        $plaminfo = $sdk->getPlamKey();
        if(null == $plaminfo)
        {
        return -1002;
        }
        $login_key = $plaminfo['login_key'];
        $login_url = $plaminfo['login_url'];
                
        $logininfo = $sdk->getLoginInfo($server_id, $userid);
        if(null == $logininfo)
        {
            return -1003;
        }
        
        $server_ip = $logininfo['server_ip'];
        $login_port  = $logininfo['login_port'];
        $game_server_id  = $logininfo['server_id'];
        $http_api_server_address  = $logininfo['http_api_server_address'];
        $create_role = $logininfo['create_role'];
        $key = $logininfo['keyflag'];
        $login_time = $logininfo['login_time'];
        $create_role = 0;
        $show_login = 1;
        
        return view('dev_login.login')
            ->with('game_url', $http_api_server_address)
            ->with('loginserver_ip', $server_ip)
            ->with('loginserver_port', $login_port)
            ->with('account_name', $userid)
            ->with('validate_key', $key)
            ->with('generate_time', $login_time)
            ->with('platform', $this->platform_name)
            ->with('server_id', $game_server_id)
            ->with('create_role', $create_role)
            ->with('login_url', $login_url)
            ->with('show_login', $show_login)
            ->with('spe_gs', $gs_name);
    }
    
    public function req_login(Request $request)
    {
        $userid = $request->get('userid', NULL, '');
        $gs_name = $request->get('gs', NULL, '');
        
        $server_id = 1;
        
        $sdk = new IODCommonApi('dev');
        $sdk->setSpecialSvr($gs_name);
        $plaminfo = $sdk->getPlamKey();
        
        if(null == $plaminfo)
        {
            $params = array('res' => -1002);
            return json_encode($params);
        }
        $logininfo = $sdk->getLoginInfo($server_id, $userid);
        if(null == $logininfo)
        {
            $params = array('res' => -1003);
            return json_encode($params);
        }
        $login_port  = $logininfo['login_port'];
        $game_server_id  = $logininfo['server_id'];
        $http_api_server_address  = $logininfo['http_api_server_address'];
        $create_role = $logininfo['create_role'];
        $key = $logininfo['keyflag'];
        $login_time = $logininfo['login_time'];
        $create_role = 0;
        $show_login = 1;
        
        $params = array(
            'res' => 0,
            'key' => $key,
            'time' => $login_time,
            'svr_id' => $game_server_id
        );
        
        return json_encode($params);
    }
}
