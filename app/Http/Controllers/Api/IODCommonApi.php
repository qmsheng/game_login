<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/11/27
 * Time: 10:39
 * Desc: 处理客户端发来的请求
 */

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class IODCommonApi
{
	private $platform_id  = 0;
    private $platform_name  = '';
    private $special_svr  = null;
	private $svr_id = 0;
	//private $server_name = '';
    private $gs_login_key = '';
    private $gs_recharge_key = '';
    
	function __construct($plam_name)
	{
		$this->platform_name = $plam_name;
    }
    
    public function setSpecialSvr($special_svr)
    {
        $this->special_svr = $special_svr;
    }

    public function getPlamKey()
    {
        $authinfo = \DB::connection('game')->selectOne("SELECT * FROM tglobalconfig where id = 1");
        if(null == $authinfo)
        {
        return null;
        }
        //获取loginkey
        $this->gs_login_key = $authinfo->value;
    
        $plaminfo = \DB::connection('game')->table('tplatforms')->where('platform_name', '=', $this->platform_name)->first();
        if(null == $plaminfo)
        {
        return null;
        }
        $this->platform_id = $plaminfo->id;
        return array(
        'platform_id' => $plaminfo->id,
        'login_key' => $plaminfo->login_key,
        'recharge_key' => $plaminfo->recharge_key,
        'login_url' =>$plaminfo->login_url);
    }
    
    //获取服务器信息,svrid为平台server_id,非游戏svr id
    public function getGsSvrInfo($platform_svr_id)
    {
        $svrinfo = null;
        //如果指定名称的特殊服务器
        if(null != $this->special_svr)
        {
            $svrinfo = \DB::connection('game')->table('tservers')->where(['server_name'=>$this->special_svr])->first();
        }
        else
        {
            $svrinfo = \DB::connection('game')->table('tservers')->where(['platform_id'=>$this->platform_id,'platform_server_id'=>$platform_svr_id])->first();
        }
        if(null == $svrinfo)
        {
        return null;
        }
        $this->svr_id = $svrinfo->server_id;
        return array(
        'server_ip' => $svrinfo->server_ip,
        'login_port' => $svrinfo->login_port,
        'server_id' => $svrinfo->server_id,
        'http_api_server_address' => $svrinfo->http_api_server_address);
    }
    
    //获取创建角色信息,$server_id为游戏服id
    private function getCreateRole($server_id, $account_name)
    {
        $create_role = 1;
		$account_info = \DB::connection('game')->table('taccount')->where(['account_name'=>$account_name, 'platform' => $this->platform_name])->first();
		if ( $account_info )
		{
			$role_info = \DB::connection('game')->table('tactroleinfo')->whereRaw('account_id = ? AND server_id = ?', [(int)$account_info->account_Id, (int)$server_id])->first();
			if ( $role_info )
			{
				$create_role = 0;
			}
		}
        return $create_role;
    }
    
    //获取登陆信息,server_id为平台server_id,非游戏svr id
    public function getLoginInfo($platform_svr_id, $account_name)
    {
        $svrinfo = $this->getGsSvrInfo($platform_svr_id);
        if(null == $svrinfo)
        {
            return null;
        }
		$server_id = $this->svr_id;
        
        $create_role = $this->getCreateRole($server_id, $account_name);
        
        $svrinfo['create_role'] = $create_role;
        $svrinfo['login_time'] = $logintime = time();
        $svrinfo['keyflag'] = $this->makeValidateKey($server_id, $account_name, $logintime);
        
        return $svrinfo;
    }

    //获取创建角色信息,$server_id为游戏服id
	private function makeValidateKey($server_id, $account_name, $logintime)
	{
        //Log::info($account_name . $logintime . $server_id . $this->platform_name . $this->gs_login_key);
		return md5($account_name . $logintime . $server_id . $this->platform_name . $this->gs_login_key);
	}
    
    //获取登陆信息,server_id为平台server_id,非游戏svr id
    public function getRoleInfo($platform_svr_id, $account_name)
    {
        //获取游戏服信息
        $svrinfo = $this->getGsSvrInfo($platform_svr_id);
        if(null == $svrinfo)
        {
            return null;
        }
		$account_info = \DB::connection('game')->table('taccount')->where(['account_name'=>$account_name, 'platform' => $this->platform_name])->first();
		if ( $account_info )
		{
			$role_info = \DB::connection('game')->table('tactroleinfo')->whereRaw('account_id = ? AND server_id = ?', [(int)$account_info->account_Id, (int)$this->svr_id])->first();
            return $role_info;
		}
        return null;
    }
    
    public function recharge($pay_id, $account, $roleName, $money, $game_gold, $platform_svr_id, $ext_data)
    {
        //获取游戏服信息
        $svrinfo = $this->getGsSvrInfo($platform_svr_id);
        if(null == $svrinfo)
        {
            return -1;
        }
        //$this->svr_id
        //定单已经存在
        $order_info = \DB::connection('recharge')->table('trechargedata')->where('pay_id', '=', $pay_id , 'AND' , 'platform_name', '=', $this->platform_name)->first();
        if ( $order_info )
        {
            return -2;
        }
        
        $roleInfo = $this->getRoleInfo($platform_svr_id, $account);
        if(null == $roleInfo)
        {
            return -3;
        }
        //如果角色名为空,则从数据库查询
        $roleName = $roleInfo->name;
        //向数据库中插入数据
        \DB::connection('recharge')->insert('insert into trechargedata(pay_id, account, role_id, role_name, server_id, money, game_gold, platform_name, platform_svr_id,ext_data) values(?,?,?,?,?,?,?,?,?,?)'
        , [$pay_id, $account, $roleInfo->role_id, $roleName, $this->svr_id, $money, $game_gold, $this->platform_name, $platform_svr_id, $ext_data]);
        
        //生成验证key
        $generate_time = time();
        $key = $this->makeValidateKey($this->svr_id, $account, $generate_time);
        
		$roleName_base64 = base64_encode($roleName);
        //向gmsvr发送充值数据
        $gmsvr_http = $this->queryGMSvr();
        $rechargeUrl = $gmsvr_http . '/recharge?instid=' . $pay_id . $this->platform_name . '&account=' . $account . '&time=' . $generate_time . '&server_id=' . $this->svr_id . '&platform_name=' . $this->platform_name . '&key=' . $key . '&gamecoins=' . $game_gold . "&roleid=" . $roleInfo->role_id . '&role_name=' . $roleName_base64;
        //Log::info($rechargeUrl);
		
        $ret = $this->postHttpUrl($rechargeUrl);
        if(0 === $ret)
        {
        //成功
        return 0;
        }
        //验证失败
        else if(-2 == $ret)
        {
        return -3;
        }
        //其它错误
        else
        {
        return $ret;
        }
        return 0;
    }
    
    /**
     * 获取gmsvr的配置路径
     */
    private function queryGMSvr()
    {
        $sql = " SELECT t2.server_ip,t2.run_param
                    FROM tglobalconfig t1
                    LEFT JOIN tservers t2
                    ON t1.`value`=t2.id
                    WHERE t1.id=3 ";

        $authinfo = \DB::connection('game')->selectOne( $sql );
        if(null == $authinfo)
        {
            return null;
        }

        $arr = json_decode($authinfo->run_param);

        if ($arr) {
            //获取gmsvr的配置路径
            return 'http://'.$authinfo->server_ip.':'.$arr->http_port;
        }else{
            return null;
        } 
    }
    
    private function postHttpUrl($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5000);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);  
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    /**
     * 获取客户端ip
     */
    public function getClientIP()
    {
        global $ip;
        if (getenv("HTTP_CLIENT_IP"))
            $ip = getenv("HTTP_CLIENT_IP");
        else if(getenv("HTTP_X_FORWARDED_FOR"))
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        else if(getenv("REMOTE_ADDR"))
            $ip = getenv("REMOTE_ADDR");
        else
            $ip = "Unknow";

        return $ip;
    }

    /**
     * 鉴权
     */
    public function authentication()
    {
        $clientip = $this->getClientIP();

        $sql_str = " SELECT * FROM `twhitelist` WHERE platform='$this->platform_name' ";
        $arr_query = \DB::connection('game')->select( $sql_str );

        $sql_str2 = " INSERT INTO twhitelist(platform,ip) VALUES ('test','$clientip') ";
        $arr_query2 = \DB::connection('game')->select( $sql_str2 );

        foreach ($arr_query as $arr_query_key => $arr_query_value) {
            if ( $clientip == $arr_query_value->ip ) {
                return;
            }
        }

		Log::error("authentication ip faild... error ip is:" . $clientip);
        header('HTTP/1.1 401 Unauthorized');
        exit;
    }

    /**
     * 登入记录
     */
    public function OasLogin( $uid )
    {
        $platform = $this->platform_name;            
        $empty_data = '';
        $server_name = 'loginserver';
        $log_type = 13;

        \DB::connection('logdb')->insert('insert into TLog(LogType, RoleId, AccountId, uid, Data1, Message, strData1, strData2, strData3, numberData1, numberData2, numberData3,platform,serverid) values(?,?,?,?,?,?,?,?,?,?,?,?,?,?)', 
            [(int)$log_type, 0, 0, $uid, $server_name, $empty_data, $empty_data, $empty_data, $empty_data, 0, 0, 0,$platform, $this->svr_id]);

        $log_type = 15;
        \DB::connection('logdb')->insert('insert into TLog(LogType, RoleId, AccountId, uid, Data1, Message, strData1, strData2, strData3, numberData1, numberData2, numberData3,platform,serverid) values(?,?,?,?,?,?,?,?,?,?,?,?,?,?)', 
            [(int)$log_type, 0, 0, $uid, $server_name, $empty_data, $empty_data, $empty_data, $empty_data, 10000, 0, 0,$platform, $this->svr_id]);
    }
}

