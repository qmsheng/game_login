<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/11/27
 * Time: 10:39
 * Desc: 处理客户端发来的请求
 */
namespace App\Http\Controllers\_7k7k;

//require_once '/../Api/IODCommonApi.php';

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Controllers\Api\IODCommonApi;
//require_once 'IODCommonApi.php';


class IndexController extends Controller
{
    private $platform_name = '7k7k';

    //请求登陆
    public function login(Request $request)
    {
        $sdk = new IODCommonApi($this->platform_name);
        // $sdk->authentication();

        $userid = $request->get('userid', NULL, '');
        $username = $request->get('username', NULL, '');
        $server_id = $request->get('server_id', NULL, '');
        $isAdult = $request->get('isAdult', NULL, '');
        $time = $request->get('time', NULL, '');
        $flag = $request->get('flag', NULL, '');
        $source = $request->get('source', NULL, '');
        $type = $request->get('type', NULL, '');
        $expired = $request->get('expired', NULL, '');

        $plaminfo = $sdk->getPlamKey();
        if(null == $plaminfo)
        {
        return -1002;
        }     
        $login_key = $plaminfo['login_key'];
        $login_url = $plaminfo['login_url'];
                
        $keytmp = md5($userid . $username . $time . $server_id . $isAdult . $type . $expired . $login_key);
        //如果验证失败则直接返回
        if($keytmp != $flag)
        {
            return -1001;
        }

        // 鉴权key过期机制
        $nowtime = time();
        $pastduetime = 15;   //过期时间15s
        if($time + $pastduetime < $nowtime)
        {
            //return -1004;
        }

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
        $show_login = 0;
        //var_dump($plaminfo);
        //var_dump($logininfo);
        //return 0;

        // 登入记录
        $sdk->OasLogin( $userid );
        
        return view('_7k7k.login')
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
            ->with('vip_type', $type)
            ->with('vip_expired_time', $expired)
            ->with('plat_svr_id', $server_id)
			->with('debug', env('APP_DEBUG', false) == true ? 'true' : 'false');

        return 0;
    }
    
    //验证用户是否存在
    public function check_user(Request $request)
    {
        $userid = $request->get('userid', NULL, '');
        $server_id = $request->get('server_id', NULL, '');
        
        $sdk = new IODCommonApi($this->platform_name);
        $plaminfo = $sdk->getPlamKey();
        if(null == $plaminfo)
        {
        return array('status' => -1002);
        }
        //获取相应的角色信息
        $roleInfo = $sdk->getRoleInfo($server_id, $userid);
        $info = [];
        if(null != $roleInfo)
        {
            return array(
            'status' => 1,
            'rolename' => $roleInfo->name,
            'lever' => $roleInfo->level
            );
        }
        else
        {
            $info = array(
            'status' => 1,
            'info'=> '角色未创建'
            );
        }
        return $info;
    }
    
    //请求充值
    public function req_charge(Request $request)
    {
        //鉴权
        $sdk = new IODCommonApi($this->platform_name);
        $sdk->authentication();

        $PayNum = $request->get('PayNum', NULL, '');
        $PayToUserId = $request->get('PayToUserId', NULL, '');
        $PayToUser = $request->get('PayToUser', NULL, '');
        $Server_id = $request->get('Server_id', NULL, '');
        $PayMoney = $request->get('PayMoney', NULL, '');
        $PayGold = $request->get('PayGold', NULL, '');
        $PayTime = $request->get('PayTime', NULL, '');
        $flag = $request->get('flag', NULL, '');

        $plaminfo = $sdk->getPlamKey();
        if(null== $plaminfo)
        {
            //平台不存在
            return -1001;
        }
        $recharge_key = $plaminfo['recharge_key'];
        
        $keytmp = md5($PayNum . '|' . $PayToUserId . '|'. $Server_id. '|'  . $PayToUser . '|' . $PayMoney . '|' . $PayGold . '|' . $PayTime . '|' . $recharge_key );
        //如果验证失败则直接返回
        if($keytmp != $flag)
        {
            //验证失败
            return -3;
        }
        $ret = $sdk->recharge($PayNum, $PayToUserId, $PayToUser, $PayMoney, $PayGold, $Server_id, '');
        //var_dump($ret.'  ');
        //充值成功
        if(0 == $ret)
        {
        return 1;
        }
        //定单已经存在/重覆
        if(-2 == $ret)
        {
        return -2;
        }
        //帐号不存在
        else if(-3 == $ret || -1 == $ret)
        {
        return -1;
        }
        //其它错误
        else
        {
        return 0;
        }
    }
}