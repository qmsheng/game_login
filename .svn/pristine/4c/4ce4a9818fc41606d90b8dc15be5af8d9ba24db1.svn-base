<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/11/27
 * Time: 10:39
 * Desc: 婢跺嫮鎮婄?銏″煕缁旑垰褰傞弶銉ф畱鐠囬攱鐪?
 */

namespace App\Http\Controllers\Tencent;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

use App\Http\Controllers\Api\IODCommonApi;

class IndexController extends Controller
{

	private $platform_name = 'qqgame';
	private $APPLICATION_ID = 1105710488;
	private $APPLICATION_KEY = 'lSkVr5RWyBfwQXIT';
	private $TENCENT_SERVER_URL='openapi.tencentyun.com';
	private $TENCENT_SERVER_URL_DEBUG='openapi.sparta.html5.qq.com';
	private $php_login_url = 'http://s8.app1105710488.qqopenapp.com/tencent';
	private $vip_type = 0;
	private $vip_expired_time = 0;

    /**
     * 閸愬懘鍎碬EB鏉╂稑鍙嗗〒鍛婂灆閻ㄥ嫬鍙嗛崣锟?
     *
     * @return \Illuminate\Http\Response
     */
    public function proload_login(Request $request)
    {
		date_default_timezone_set('PRC');
		$access_token = $request->get('access_token', NULL, '');
		$open_id = $request->get('openid', NULL, '');
        $open_key = $request->get('openkey', NULL, '');
		$game_pf = $request->get('pf', NULL, '');
		$game_pfkey = $request->get('pfkey', NULL, '');
        $platform = $request->input('platform');
		$platform_svrid = $request->input('serverid');
		$time = time();
		//$server_id = 1;
		$create_time = Input::get('time');
		$md5sign = '';	

		$msgRet = -3001;
		if ($request->getMethod() == 'POST' || $request->getMethod() == 'GET') 
		{
			if ($open_id == '' || $open_key == '' || $game_pf == '' || $game_pfkey == '') 
			{
				$msgRet = -3002;
            }else{
                //閸氭垹顑囨稉澶嬫煙鐠囬攱鐪版宀冪槈閺勵垰鎯佸鑼病閻ц缍?
                $result = $this->is_login($open_id, $open_key, $game_pf);
                $result = json_decode($result);
                if ($result->ret == 0) {
                    $msgRet = 0;
                }
                else
                {
                    $msgRet = (int)$result->ret;
                }

                //鏉╂瑩鍣烽柅姘崇箖娑擄拷閲滅?妤侇唽閹跺﹥澧嶉張澶変繆閹垰褰傜紒娆愭箛閸斺?娅?閸忓牐娴咼SON閸愬硨RL缂傛牜鐖?
                $tmp = array(
                    'openid'=>$open_id,
                    'openkey'=>$open_key,
                    'pf'=>$game_pf,
                    'sid'=>$platform_svrid,
                    'platform'=>$platform,
                    'pfkey'=>$game_pfkey);
                $validate_key = urlencode(json_encode($tmp));
            }
		}

		if ( 0 == $msgRet )
		{
			$sdk = new IODCommonApi($this->platform_name);
		
			$plaminfo = $sdk->getPlamKey();
			if(null == $plaminfo)
			{
			return -1002;
			}
			$login_key = $plaminfo['login_key'];
			$login_url = $plaminfo['login_url'];
			
			$logininfo = $sdk->getLoginInfo($platform_svrid, $open_id);
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
			
			// 鐧诲叆璁板綍
			$sdk->OasLogin( $open_id );

			return view('Tencent.login')
            ->with('game_url', $http_api_server_address)
            ->with('loginserver_ip', $server_ip)
            ->with('loginserver_port', $login_port)
            ->with('account_name', $open_id)
            ->with('validate_key', $key)
            ->with('generate_time', $login_time)
            ->with('platform', $this->platform_name)
            ->with('server_id', $game_server_id)
            ->with('create_role', $create_role)
            ->with('login_url', $login_url)
            ->with('show_login', $show_login)
            ->with('sig', 0)
            ->with('php_login_url', $this->php_login_url)
            ->with('appid', $this->APPLICATION_ID)
            ->with('vip_type', $this->vip_type)
            ->with('vip_expired_time', $this->vip_expired_time)
            ->with('plat_svr_id', $platform_svrid)
			->with('debug', env('APP_DEBUG', false) == true ? 'true' : 'false');
		}
		return $msgRet;
    }
    
        /**
     *  閸掋倖鏌囬弰顖氭儊閻ц缍?
     */
    public function is_login($open_id, $open_key, $pf, $format = 'json')
    {
        $sdk = new \OpenApiV3($this->APPLICATION_ID, $this->APPLICATION_KEY);
        if (env('APP_DEBUG', false) == true)
        {
            $sdk->setServerName($this->TENCENT_SERVER_URL_DEBUG);
        }
        else
        {
            $sdk->setServerName($this->TENCENT_SERVER_URL);
        }

        $params = array(
            'openid' => $open_id,
            'openkey' => $open_key,
            'pf' => $pf,
        );
        $result = $sdk->api("/v3/user/is_login", $params,'post', 'https');
        return json_encode($result);
    }

    /**
     *  閼惧嘲褰囬悳鈺侇啀娣団剝浼?
     */
    public function get_info($open_id, $open_key, $pf, $ip='')
    {

        $sdk = new \OpenApiV3($this->APPLICATION_ID, $this->APPLICATION_KEY);
        if (env('APP_DEBUG', false) == true)
        {
            $sdk->setServerName($this->TENCENT_SERVER_URL_DEBUG);
        }
        else
        {
            $sdk->setServerName($this->TENCENT_SERVER_URL);
        }

        $params = array(
            'openid' => $open_id,
            'openkey' => $open_key,
            'pf' => $pf,
        );
        $result = $sdk->api("/v3/user/get_info", $params,'post', 'https');
        return json_encode($result);
    }
    
    //鐠嬪啰鏁x閹恒儱褰?閼惧嘲褰囬拑婵嬫崌娣団剝浼?
    public function tx_get_blueinfo($open_id, $open_key, $pf)
    {
        $sdk = new \OpenApiV3($this->APPLICATION_ID, $this->APPLICATION_KEY);
        if (env('APP_DEBUG', false) == true)
        {
            $sdk->setServerName($this->TENCENT_SERVER_URL_DEBUG);
        }
        else
        {
            $sdk->setServerName($this->TENCENT_SERVER_URL);
        }

        $params = array(
            'appid' => $this->APPLICATION_ID,
            'openid' => $open_id,
            'openkey' => $open_key,
            'pf' => $pf,
        );
        $result = $sdk->api("/v3/user/blue_vip_info", $params,'post', 'https');
        return json_encode($result);
    }
    
    //鐠嬪啰鏁x閹恒儱褰?閹笛嗩攽鐠愵厺鎷遍崯鍡楁惂閹垮秳缍?
    public function tx_buy_good($open_id, $open_key, $pfkey, $pf, $goodid, $qcoinnum, $generate_time, $zoneid)
    {
        $sdk = new \OpenApiV3($this->APPLICATION_ID, $this->APPLICATION_KEY);
        if (env('APP_DEBUG', false) == true)
        {
            $sdk->setServerName($this->TENCENT_SERVER_URL_DEBUG);
        }
        else
        {
            $sdk->setServerName($this->TENCENT_SERVER_URL);
        }

        $goodsurl = 'https://liemozhixue-1252697245.cossh.myqcloud.com/tx_photo/zs01.png';//'http://minigame.qq.com/plat/social_hall/app_frame/demo/img/pic_02.jpg';
        
        //1.閺?垯绮幍锟芥付鏉堟挸鍙嗛崣鍌涙殶閿涘牆顕畍alue閸婂吋瀵滈悡褍绱戦獮瀹狀洣濮瑰倽绻樼悰宀?椽閻緤绱?
        $payParam = array();
        $payParam["appid"] = $this->APPLICATION_ID;
        $payParam["goodsmeta"] = '钻石*测试';
        $payParam["goodsurl"] = $goodsurl;
        $payParam["openid"] = $open_id;
        $payParam["openkey"] = $open_key;
        $payParam["payitem"] = $goodid . '*' . $qcoinnum . '*1';
        $payParam["pf"] = $pf;
        $payParam["pfkey"] = $pfkey;
        $payParam["ts"] = $generate_time;
        $payParam["zoneid"] = $zoneid;
                
        $result = $sdk->api("/v3/pay/buy_goods", $payParam,'post', 'https');
        return json_encode($result);
    }
	
	public function buy_good(Request $request)
    {
		date_default_timezone_set('PRC');
		$role_id = $request->get('roleid', NULL, '');
        $open_id = $request->get('openid', NULL, '');
        $open_key = $request->get('openkey', NULL, '');
        $pfkey = $request->get('pfkey', NULL, '');
		$game_pf = $request->get('pf', NULL, '');
		$goodid = $request->get('goodid', NULL, '');
		//$goodsNum = $request->get('goodnum', NULL, '');
		$qcoinnum = $request->get('qcoinnum', NULL, '');
		$plat_svr_id = $request->get('plat_svr_id', 0, 0);
		$goodsNum = $qcoinnum;
		$generate_time = time();
		
		$appid = '1105710488';
		$appkey = 'lSkVr5RWyBfwQXIT&';
		$msgRet = -3001;
		if ($request->getMethod() == 'POST' || $request->getMethod() == 'GET') 
		{
			$msgRet = -3002;
			if ( $open_id == '' ) 
			{
				$msgRet = -3003;
				return $msgRet;
            } 
			else if ( $open_key == '' ) 
			{
				$msgRet = -3004;
				return $msgRet;
            } 
			else if ( $game_pf == '' ) 
			{
				$msgRet = -3005;
				return $msgRet;
            } 
			else 
			{
 				//$sign1 = 'GET';
				//$sign2 = '/v3/pay/buy_goods';
				//$sign2 = rawurlencode($sign2);
				//$sign3 = 'appid=' . $appid . '&openid=' . $open_id . '&openkey=' . $open_key . '&pf=' . $game_pf;
				//$sign3 = rawurlencode($sign3);
				//$localsign = $sign1 . '&' . $sign2 . '&' . $sign3;
				//$hashsign = hash_hmac('sha1', $localsign, $localkey, TRUE);
				//$base64sign = base64_encode($hashsign);	
				//$payitem = '1*10*' . $goodsNum;
				//$goodsmeta = '閽荤煶*娴嬭瘯';
				//$goodsurl = 'http://minigame.qq.com/plat/social_hall/app_frame/demo/img/pic_02.jpg';
				//$zoneid = '0';
				
				//1.鏀粯锟?锟斤拷杈撳叆鍙傛暟锛堝value鍊兼寜鐓у紑骞宠姹傝繘琛岀紪鐮侊級
				//$payParam["appid"] = $appid;
				//$payParam["goodsmeta"] = '閽荤煶*娴嬭瘯';
				//$payParam["goodsurl"] = $goodsurl;
				//$payParam["openid"] = $open_id;
				//$payParam["openkey"] = $open_key;
				//$payParam["payitem"] = $goodid . '*10*' . '1';//$goodsNum;
				//$payParam["pf"] = $game_pf;
				//$payParam["pfkey"] = $pfkey;
				//$payParam["ts"] = $generate_time;
				//$payParam["zoneid"] = $zoneid;
				
				#2.瀛楀吀鍗囧簭鎺掑垪
				//ksort($payParam);
				
				//$payParamStr = '';
				//$paramArr = array();
				//foreach ($payParam as $key => $value) {
				//	$paramArr[] = $key.'='.$value;
				//}
				//$payParamStr = join('&', $paramArr);
				
				//3.鎸塐penAPI V3.0鐨勭鍚嶇敓鎴愯鏄庯紝鏋勶拷?婧愪覆(锟?锟斤拷鍙傛暟缁冩垚锟?锟斤拷瀛楃涓诧紝瀵瑰瓧绗︿覆杩涜鏁翠綋缂栫爜)
				//$payapi = '/v3/pay/buy_goods';
				//$encodeSigStr = 'GET&'.rawurlencode($payapi).'&'.rawurlencode($payParamStr);

				//4.璁＄畻绛惧悕
				//$sig = base64_encode(hash_hmac("sha1", $encodeSigStr, $appkey, true));
				
				//$data = 'http://openapi.sparta.html5.qq.com/v3/pay/buy_goods?openid=' . $open_id . '&openkey=' . $open_key . '&appid=' . $appid . '&sig=' . $base64sign . '&pf=' . $game_pf . '&pfkey=' . $pfkey . '&ts=' . $generate_time . '&payitem=' . $payitem . '&goodsmeta=' . $goodsmeta . '&goodsurl=' . $goodsurl . '&zoneid=' . $zoneid;
				
				//5.鍙戯拷?璇锋眰鏃舵墍鏈夊弬鏁伴兘瑕佽繘琛孶RL缂栫爜锛堟瘡涓弬鏁扮殑value鍒嗗埆杩涜缂栫爜锟?
				//$urlencodeParamStr = "";
				//$urlencodeParamArr = array();
				//foreach ($payParam as $key => $value) {
				//	$urlencodeParamArr[] = $key."=".rawurlencode($value);
				//}
				//$urlencodeParamStr = join('&', $urlencodeParamArr);
				//$urlencodeParamStr .= "&sig=".rawurlencode($sig);
				
				//姝ｅ紡鐜鐨刪ost
				// $host = "https://openapi.tencentyun.com";
				//娴嬭瘯鐜鐨刪ost
				//$host = "http://openapi.sparta.html5.qq.com";
				
				//$data = $host.$payapi."?".$urlencodeParamStr;
	
				//$ch = curl_init();
				//curl_setopt($ch, CURLOPT_URL, $data);
				//curl_setopt($ch, CURLOPT_TIMEOUT, 10);
				//curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				//curl_setopt($ch, CURLOPT_HEADER, 0); 
				//$response = curl_exec($ch);
				//curl_close($ch); 
                
                $response = $this->tx_buy_good($open_id, $open_key, $pfkey, $game_pf, $goodid, $qcoinnum, $generate_time, $plat_svr_id);
				$json_data=json_decode($response);
				if ( 0 == $json_data->ret )
				{
					$order_info = \DB::connection('recharge')->table('ttencentrechargeinfo')->where('openid', '=', $open_id , 'AND' , 'token', '=', $json_data->token)->first();
					if ( $order_info )
					{
						\DB::connection('recharge')->table('ttencentrechargeinfo')->where('openid', '=', $open_id , 'AND' , 'token', '=', $json_data->token)->delete();
					}
					
					$role_info = \DB::connection('game')->table('tactroleinfo')->where('role_id', '=', (int)$role_id)->first();
					if ( $role_info )
					{
						$userdate = date("Y-m-d H:i:s",(int)$generate_time);
						\DB::connection('recharge')->insert('insert into ttencentrechargeinfo(openid, token, recharge_time, url, pf, goodid, goodnum, server_id, qcoinnum ) values(?,?,?,?,?,?,?,?,?)', [$open_id, $json_data->token, $userdate, $json_data->url_params, $game_pf, $goodid, $goodsNum, $role_info->server_id, $qcoinnum]);
					}
					else
					{
						$msgRet = -3006;
						return $msgRet;
					}
				}
				return $response;
            }
		}
        return $msgRet;
    }
	
	public function recharge(Request $request)
	{
		//杩斿洖缁欒吘璁殑ret锟?
		//0: 鎴愬姛
		//1: 绯荤粺绻佸繖
		//2: token宸茶繃锟?
		//3: token涓嶅瓨锟?
		//4: 璇锋眰鍙傛暟閿欒锛氾紙杩欓噷濉啓閿欒鐨勫叿浣撳弬鏁帮級
		date_default_timezone_set('PRC');
		$open_id = Input::get('openid');
		$appid = Input::get('appid');
		$ts = Input::get('ts');
		$payitem = Input::get('payitem');
		$token = Input::get('token');
		$order_id = Input::get('billno');
		$version = Input::get('version');
		$zoneid = Input::get('zoneid');
		$providetype = Input::get('providetype');
		$amt = Input::get('amt');
		$payamt_coins = Input::get('payamt_coins');
		$pubacct_payamt_coins = Input::get('pubacct_payamt_coins');
		$sig = Input::get('sig');
		$generate_time = time();

		$userdate = date("Y-m-d H:i:s",$generate_time);
		$retId = 1;
		if ($request->getMethod() == 'POST' || $request->getMethod() == 'GET') 
		{
			$order_info = \DB::connection('recharge')->table('ttencentrechargeinfo')->where('openid', '=', $open_id , 'AND' , 'token', '=', $token)->first();
			if ( $order_info )
			{
				if ( (int)$generate_time > (int)strtotime($order_info->recharge_time) + 15*60 )
				{
					$retId = 2;
				}
				else
				{
					$retId = 0;
					$userdate = date("Y-m-d H:i:s",(int)$generate_time);
					\DB::connection('recharge')->insert('insert into ttencentorderinfo(order_id, open_id, appid, ts, payitem, token, zoneid, providetype, amt, payamt_coins, pubacct_payamt_coins, sig, version, server_id ) values(?,?,?,?,?,?,?,?,?,?,?,?,?,?)', [$order_id, $open_id, $appid, $userdate, $payitem, $token, $zoneid, $providetype, $amt, (int)$payamt_coins, (int)$pubacct_payamt_coins, $sig, $version, (int)$order_info->server_id]);
				}
			}
			else
			{
				$retId = 3;
			}
		}
		else
		{
			$retId = 1;
		}
		
		$msgRet = array();
		$msgRet["ret"] = $retId;
		if ( 0 == $retId )
		{
			$msgRet["msg"] = 'OK';
			//$data = env('GAME_SERVER_HTTP_URL') . 'recharge?uid=' . $open_id . '&gamecoins=' . $order_info->goodnum;
			//$ch = curl_init();
			//curl_setopt($ch, CURLOPT_URL, $data);
			//curl_setopt($ch, CURLOPT_TIMEOUT, 10);
			//curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			//curl_setopt($ch, CURLOPT_HEADER, 0);
			//curl_setopt($ch, CURLOPT_POST, 1);  
			//$response = curl_exec($ch);
			//curl_close($ch);
			
			$sdk = new IODCommonApi($this->platform_name);
			$plaminfo = $sdk->getPlamKey();
			if(null== $plaminfo)
			{
				//骞冲彴涓嶅瓨锟?
				return -1001;
			}
			
			$arr_val = explode('*',$payitem,3);
			$goodCount = 1;
			if(count($arr_val) >= 3)
			{
				$goodCount = (int)$arr_val[2];
			}
			//$amt 乘0.1换算为1Q点, 再乘0.1换算成人民币1元
			$ret = $sdk->recharge($order_id, $open_id, "", $amt * 0.1 * 0.1, $order_info->goodnum * $goodCount, $zoneid, '');
			
			//涓婃姤缃楃洏
			$data = 'http://tencentlog.com/stat/report_recharge.php?version=1&appid=' . $appid . '&time=' . $generate_time . '&domain=10' . '&opuid=' . $open_id . '&opopenid=' . $open_id . '&modifyfee=' . $order_info->qcoinnum*10;
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $data);
			curl_setopt($ch, CURLOPT_TIMEOUT, 10);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_POST, 1);  
			curl_exec($ch);
			curl_close($ch);
			
			//var_dump($ret.'  ');
			//鍏咃拷?鎴愬姛
			if(0 == $ret)
			{
			$msgRet["msg"] = 'OK';
			}
			//瀹氬崟宸茬粡瀛樺湪/閲嶈
			if(-2 == $ret)
			{
			$msgRet["msg"] = 'order has exits, error code'.$ret;
			}
			//甯愬彿涓嶅瓨锟?
			else if(-3 == $ret || -1 == $ret)
			{
			$msgRet["msg"] = 'has no role exits, error code'.$ret;
			}
			//鍏跺畠閿欒
			else
			{
			$msgRet["msg"] = 'other error';
			}
		}
		else
		{
			$msgRet["msg"] = 'Error';
		}
		
		$jsonRetMsg = json_encode($msgRet); 
		return $jsonRetMsg;
	}
	
	public function blue_info(Request $request)
    {
		date_default_timezone_set('PRC');
        $open_id = $request->get('openid', NULL, '');
        $open_key = $request->get('openkey', NULL, '');
		$game_pf = $request->get('pf', NULL, '');
		$generate_time = time();

		$msgRet = -3001;
		if ($request->getMethod() == 'POST' || $request->getMethod() == 'GET') 
		{
			$msgRet = -3002;
			if ( $open_id == '' ) 
			{
				$msgRet = -3003;
            } 
			else if ( $open_key == '' ) 
			{
				$msgRet = -3004;
            } 
			else if ( $game_pf == '' ) 
			{
				$msgRet = -3005;
            } 
			else 
			{
 				// $sign1 = 'GET';
				// $sign2 = '/v3/user/blue_vip_info';
				// $sign2 = rawurlencode($sign2);
				// $sign3 = 'appid=' . $appid . '&openid=' . $open_id . '&openkey=' . $open_key . '&pf=' . $game_pf;
				// $sign3 = rawurlencode($sign3);
				// $localsign = $sign1 . '&' . $sign2 . '&' . $sign3;
				// $hashsign = hash_hmac('sha1', $localsign, $appkey, TRUE);
				// $base64sign = base64_encode($hashsign);	
				
				// $data = 'http://openapi.sparta.html5.qq.com/v3/user/blue_vip_info?openid=' . $open_id . '&openkey=' . $open_key . '&appid=' . $appid . '&sig=' . $base64sign . '&pf=' . $game_pf;
	
				// $ch = curl_init();
				// curl_setopt($ch, CURLOPT_URL, $data);
				// curl_setopt($ch, CURLOPT_TIMEOUT, 10);
				// curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				// curl_setopt($ch, CURLOPT_HEADER, 0); 
				// $response = curl_exec($ch);
				// curl_close($ch);
				// return $response;
                
                $result = $this->tx_get_blueinfo($open_id, $open_key, $game_pf);
                return $result;
            }
		}
        return $msgRet;
    }
	
	public function proload_compass(Request $request)
	{
		date_default_timezone_set('PRC');
		$msgtype = $request->get('msgtype', NULL, '');
		
		$generate_time = time();
		$appid = '1105710488';
		if ($request->getMethod() == 'POST' || $request->getMethod() == 'GET')
		{
			//1鐜╁閽荤煶娑堣垂 2鐜╁锟?锟斤拷 3鏈嶅姟鍣ㄥ湪绾夸汉锟?
			$data = '';
			if ( 1 == (int)$msgtype )
			{
				$open_id = $request->get('openid', NULL, '');
				$domain = $request->get('domain', NULL, '');
				$modifyfee = $request->get('modifyfee', NULL, '');
				$modifycoin = $request->get('modifycoin', NULL, '');
				$totalcoin = $request->get('totalcoin', NULL, '');
				$data = 'http://tencentlog.com/stat/report_consume.php?version=1&appid=' . $appid . '&time=' . $generate_time . '&domain=10' . '&opuid=' . $open_id . '&opopenid=' . $open_id . '&modifyfee=' . $modifyfee . '&modifycoin=' . $modifycoin . '&totalcoin=' . $totalcoin;
			}
			else if ( 2 == (int)$msgtype )
			{
				$open_id = $request->get('openid', NULL, '');
				$domain = $request->get('domain', NULL, '');
				$onlinetime = $request->get('onlinetime', NULL, '');
				$data = 'http://tencentlog.com/stat/report_quit.php?version=1&appid=' . $appid . '&time=' . $generate_time . '&domain=10' . '&opuid=' . $open_id . '&opopenid=' . $open_id . '&onlinetime=' . $onlinetime;
			}
			else if ( 3 == (int)$msgtype )
			{
				$domain = $request->get('domain', NULL, '');
				$user_num = $request->get('user_num', NULL, '');
				$data = 'http://tencentlog.com/stat/report_online.php?version=1&appid=' . $appid . '&time=' . $generate_time . '&domain=10' . '&user_num=' . $user_num;
			}
			else
			{
				return -1;
			}
			//涓婃姤缃楃洏
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $data);
			curl_setopt($ch, CURLOPT_TIMEOUT, 10);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_POST, 1);  
			curl_exec($ch);
			curl_close($ch);
		}
		
		return 0;
	}
}
