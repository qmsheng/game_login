<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/11/27
 * Time: 11:48
 */

namespace App\Http\Controllers\Dota;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

class IndexController extends Controller
{

    public function login(Request $request)
    {
        $username = Input::get('username');
        $password = Input::get('password');
        $remember = Input::get('remember');
        $errnum = 0;
        $errmsg = ""; 
        if ($request->getMethod() == 'POST') {
            if ($remember != 0) {
                Cookie::forever('remember', $username);
            } else {
                Cookie::forever('remember', "");
            }

            if ($username == "" || $password == "") {
                $errnum = 10000;
                $errmsg = "账号或密码不能为空。";
            } else {
                $time = time();
                $url = "account_name=" . $username
                        . "&validate_key=" . md5($password)
                        . "&generate_time=" . $time;
                //$gotourl = env('LOGIN_URL', 'http://localhost/api/network_login') .'?' . $url;
                $gotourl = env('APPLICATION_URL').'api/network_login'.'?' . $url;
                return Redirect::to($gotourl)
                        ->with('account_name', $username)
                        ->with('validate_key', md5($password))
                        ->with('generate_time', $time);
            }

        } else {
            $username = Cookie::get('remember');
        }

        return view('dota.login')
            ->with('username', $username)
            ->with('remember', $remember)
            ->with('errnum', $errnum)
            ->with('errmsg', $errmsg);
    }

    public function register(Request $request)
    {
       
        if ($request->getMethod() == 'POST') 
        {
            $username = Input::get('username');
            $password = Input::get('password');
            $errnum = 0;
            $errmsg = "";
            $account = \DB::connection('account')->table('bio_locale_account')->where('account_name', '=', $username)->first();
            if ($account) {
                $errnum = 20001;
                $errmsg = "账号已经存在。";
            }
            else
            {
                $sql = "insert into bio_locale_account(account_name, encrypt_passwd) values(?,?)";
                \DB::connection('account')->insert($sql, [$username, md5($password)]);
            }
            return view('dota.login');
        }
        else
        {
            //return view('dota.register');
			return view('game');
        }
    }
}