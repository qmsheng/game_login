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
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class IndexController extends Controller
{

    /*
     * 给外网登录使用的入口
     */
    public function network_login(Request $request)
    {
        $account_name = $request->get('account_name', NULL, '');
        $validate_key = $request->get('validate_key', NULL, '');
        $generate_time = $request->get('generate_time', NULL, '');
        
        return view('api.game2')
            ->with('game_url', env('APPLICATION_URL'))
            ->with('account_name', $account_name)
            ->with('validate_key', $validate_key)
            ->with('generate_time', $generate_time);
    }
    
    /**
     * 内部WEB进入游戏的入�?
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        //$this->view->setRenderLevel(\Phalcon\Mvc\View::LEVEL_NO_RENDER);
        $account_name = $request->get('account_name', NULL, '');
        $account_id = $request->get('account_id', NULL, '');
        $validate_key = $request->get('validate_key', NULL, '');
        $generate_time = $request->get('generate_time', NULL, '');

        $priv_key = $account_name . "-"
            . $generate_time . "-"
            . $account_id . "-"
            . env('LOGIN_KEY');
        if (md5($priv_key) != $validate_key) {
            //$this->view->setRenderLevel(\Phalcon\Mvc\View::LEVEL_NO_RENDER);
//			echo"<script>alert('授权已经过期，请重新登录.');history.go(-1);</script>";
        }

        $generate_time = time();
        $priv_key = $account_name . "-"
            . $generate_time . "-"
            . $account_id . "-"
            . env('LOGIN_KEY');
        $validate_key = md5($priv_key);

        return view('api.game')
            ->with('game_url', env('APPLICATION_URL'))
            ->with('account_name', $account_name)
            ->with('account_id', $account_id)
            ->with('validate_key', $validate_key)
            ->with('generate_time', $generate_time)
            ->with('lobby_addr', env('LOBBY_SERVER_IP'))
            ->with('lobby_port', env('LOBBY_SERVER_PORT'))
            ->with('rank_url', env('RANKING_URL'));
    }

    /**
     * 腾讯平台进入游戏的入�?
     *
     * @return \Illuminate\Http\Response
     */
    public function tencent_login(Request $request)
    {
        $open_id = $request->input('openid');
        $open_key = $request->input('openkey');
        $pf = $request->input('pf');
        $server_id = $request->input('serverid');
        $pfkey = $request->input('pfkey');
        $platform = $request->input('platform');
        $server_name = $request->input('sName');

        $generate_time = time();

        $application_base_url = env('APPLICATION_URL');
        $lobby_server_ip = env('LOBBY_SERVER_IP');
        $lobby_server_port = env('LOBBY_SERVER_PORT');
        $rank_url = env('RANKING_URL');

        //向第三方请求验证是否已经登录
        $result = $this->is_login($open_id, $open_key, $pf);
        $result = json_decode($result);
        if ($result->ret == 0) {
            if (\DB::connection('account')->table('bio_locale_account')->where('account_name', '=', $open_id)->count() == 0)
            {
                //为登录的玩家创建账号�?
                $sql = "insert into bio_locale_account(account_name, encrypt_passwd) values(?,?)";
                \DB::connection('account')->insert($sql, [$open_id, $open_id]);
            }
        }

        //这里通过�?��字段把所有信息发给服务器(先转JSON再URL编码)
        $tmp = array(
            'openid'=>$open_id,
            'openkey'=>$open_key,
            'pf'=>$pf,
            'sid'=>$server_id,
            'platform'=>$platform,
            'pfkey'=>$pfkey);
        //$validate_key = urlencode($open_id."/".$open_key."/".$pf);
        $validate_key = urlencode(json_encode($tmp));

        return view('api.game')
            ->with('game_url', $application_base_url)
            ->with('account_name', $open_id)
            ->with('account_id', 0)
            ->with('validate_key', $validate_key)
            ->with('generate_time', 0)
            ->with('lobby_addr', $lobby_server_ip)
            ->with('lobby_port', $lobby_server_port)
            ->with('rank_url', $rank_url);
    }

    /**
     * 排行
     * @return \Illuminate\Http\Response
     */
    public function rank(Request $request)
    {
        $type = $request->input('type', 1);
        $local = $request->input('local', 1);
        $role_id = $request->input('roleid', 1);
        $sql = "";
        $order_key = "";
        $order_type = "asc";
        $params = array();
        if ($type != 0)
        {
            switch($type)
            {
                case 1:
                    $order_key = "level";
                    $order_type = "desc";
                    break;
                case 2:
                    $order_key = "effectiveness";
                    $order_type = "desc";
                    break;
                default:
                    $order_key = "level";
                    $order_type = "desc";
            }
            $sql = "(select role_name,role_id,".$order_key.",role_class,account_id from role_info where role_id = :role_id) union all (select role_name,role_id,".$order_key.",role_class,account_id from role_info order by ".$order_key." ".$order_type." limit 20)";
            $params['role_id'] = $role_id;
        }
        else
        {
            //总榜
            $order_key = "order_key";
            $sql = "(select 1 as rank, role_name,role_id,level as order_key,role_class,account_id from role_info order by level desc limit 1) union all (select 2 as rank, role_name,role_id,effectiveness as order_key,role_class,account_id from role_info order by effectiveness desc limit 1)";
        }

        $result = array();
        $result['type'] = $type;
        $result['local'] = $local;
        $result['role_id'] = $role_id;

        $ranks =  \DB::connection('game')->select($sql, $params);
        if (count($ranks) >= 2)
        {
            if ($type != 0)
            {
                $self = array_shift($ranks);
                if ($self->role_id != $role_id)
                {
                    return json_encode($result);
                }
                $result['self']['role_name'] = $self->role_name;
                $result['self']['role_id'] = $self->role_id;
                $result['self']['job'] = $self->role_class;
                $result['self']['key'] = $self->$order_key;
                $result['self']['rank'] = 0;
                $index = 0;
                foreach($ranks as $rank)
                {
                    $result['ranks'][$index]['role_name'] = $rank->role_name;
                    $result['ranks'][$index]['role_id'] = $rank->role_id;
                    $result['ranks'][$index]['key'] = $rank->$order_key;
                    $result['ranks'][$index]['job'] = $rank->role_class;
                    $result['ranks'][$index]['rank'] = $index+1;
                    if ($rank->role_id == $role_id)
                    {
                        $result['self']['rank'] = $index+1;
                    }
                    $index++;
                }
                $result['count'] = $index;
                $result['time'] = time();
            }
            else
            {
                $result['self'] = array();
                $index = 0;
                foreach($ranks as $rank)
                {
                    $result['ranks'][$index]['role_name'] = $rank->role_name;
                    $result['ranks'][$index]['role_id'] = $rank->role_id;
                    $result['ranks'][$index]['key'] = $rank->$order_key;
                    $result['ranks'][$index]['job'] = $rank->role_class;
                    $result['ranks'][$index]['rank'] = $rank->rank;
                    $index++;
                }
            }
        }
        return json_encode($result);
    }

    /**
     *  判断是否登录
     */
    public function is_login($open_id, $open_key, $pf, $format = 'json')
    {
        $sdk = new \OpenApiV3(env('APPLICATION_ID', ''), env('APPLICATION_KEY', ''));
        if (env('APP_DEBUG', false) == true)
        {
            $sdk->setServerName(env('SERVE_HOST_DEBUG', ''));
        }
        else
        {
            $sdk->setServerName(env('SERVE_HOST', ''));
        }

        $params = array(
            'openid' => $open_id,
            'openkey' => $open_key,
            'pf' => $pf,
        );
        $result = $sdk->api("/v3/user/is_login", $params,'post');
        return json_encode($result);
    }

    /**
     *  获取玩家信息
     */
    public function get_info($open_id, $open_key, $pf, $ip='')
    {

        $sdk = new \OpenApiV3(env('APPLICATION_ID', ''), env('APPLICATION_KEY', ''));
        if (env('APP_DEBUG', false) == true)
        {
            $sdk->setServerName(env('SERVE_HOST_DEBUG', ''));
        }
        else
        {
            $sdk->setServerName(env('SERVE_HOST', ''));
        }

        $params = array(
            'openid' => $open_id,
            'openkey' => $open_key,
            'pf' => $pf,
        );
        $result = $sdk->api("/v3/user/get_info", $params,'post');
        return json_encode($result);
    }

    /**
     *  过虑文字
     */
    public function word_filter($open_id, $open_key, $pf, $ip, $content, $msgid)
    {

        $sdk = new \OpenApiV3(env('APPLICATION_ID', ''), env('APPLICATION_KEY', ''));
        if (env('APP_DEBUG', false) == true)
        {
            $sdk->setServerName(env('SERVE_HOST_DEBUG', ''));
        }
        else
        {
            $sdk->setServerName(env('SERVE_HOST', ''));
        }

        $params = array(
            'openid' => $open_id,
            'openkey' => $open_key,
            'pf' => $pf,
            'ip' => $ip,
            'content' => $content,
            'msgid'=> $msgid
        );
        $result = $sdk->api("/v3/csec/word_filter", $params,'post');
        return json_encode($result);
    }
    
    //添加日志打印接口
    public function add_log(Request $request)
    {
        $error_code = 1;
       
		$uid = $request->get('uid', NULL, ''); 
		//$logid = $request->get('logid', NULL, '');
		$log_type = $request->get('type', 0, '');
		$logdata = $request->get('logdata', '', '');
		$serverid = $request->get('serverid', 0, '');
		$platform = $request->get('platform', '', '');
		
		$num1 = $request->get('num1', 0, 0);
		$num2 = $request->get('num2', 0, 0);
		$num3 = $request->get('num3', 0, 0);
		
		$str1 = $request->get('str1', '', '');
		$str2 = $request->get('str2', '', '');
		$str3 = $request->get('str3', '', '');
		
        
        //$platform = env('PLATFORM');
        
        $empty_data = '';
		$server_name = 'loginserver';
        
		\DB::connection('logdb')->insert('insert into TLog(LogType, RoleId, AccountId, uid, Data1, Message, strData1, strData2, strData3, numberData1, numberData2, numberData3, platform, serverid) values(?,?,?,?,?,?,?,?,?,?,?,?,?,?)'
		, [(int)$log_type, 0, 0, $uid, $server_name, $logdata, $str1, $str2, $str3, $num1, $num2, $num3, $platform, $serverid]);
        
        return $error_code;
    }
}
