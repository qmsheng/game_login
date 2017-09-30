<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- saved from url=(0014)about:internet -->
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">	
    <head>
        <title></title>
        <meta name="renderer" content="ie-comp"/>
        <meta name="google" value="notranslate" />		
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <base href="{{$game_url}}"/>
        <style type="text/css" media="screen"> 
			html, body	{ height:100%; width:100%;}
            body { margin:0; padding:0; overflow:auto; text-align:center; 
                   background-color: #000000; }   
		
			#gameContent {width:100;height:100%;background:#000000} 
			
        </style>
        <script type="text/javascript" src="bin-debug/swfobject.js"></script>
        <script type="text/javascript" src="bin-debug/jquery.js"></script>
		<script type="text/javascript" src="bin-debug/js_launcher.js"></script>
		<script type="text/javascript">
		
        function initGameData()
        {
            clientUrl = "{{ $game_url }}";
            loginserver_ip = "{{ $loginserver_ip }}";
            loginserver_port = "{{ $loginserver_port }}";
            account_name = "{{ $account_name }}";
            key = "{{ $validate_key }}";
            login_time = "{{ $generate_time }}";
            platform = "{{ $platform }}";
            server_id = "{{ $server_id }}";
            login_url = "{{ $login_url }}";
            show_login = "{{ $show_login }}";
			platf_svr_id = "{{ $plat_svr_id }}";
        }
		
		window.onload = function() {
			prelaunch();

			initGameData();
            
            //IE6 IE7
            //if(!+"\v1" && !document.querySelector)
            //{
            //    document.body.onresize = onResize;
            //}else
            //{   
            //    window.onresize = onResize;  
            //}

			swfobject.removeSWF("biogame");
			
			//tencentBuyGood(1, 21, 100, null);
            
            //检察是否安装flash player,如果没安装,则提交错误log
            var fls = flashChecker();
            if(!fls.f)
            {
                //如果没安装,则提交错误log
                //postData("http://game.iodsoft.cn/api/addlog?uid=" + "{{ $account_name }}" + "&type=15&logid=10025" + "&serverid=" + "{{ $server_id }}");
                report_log(15, 10025, "");
            }
            else
            {
                //postData("http://game.iodsoft.cn/api/addlog?uid=" + "{{ $account_name }}" + "&type=15&logid=10002" + "&serverid=" + "{{ $server_id }}");               
                report_log(15, 10026, "");
            }
            
			vip_type = {{ $vip_type }};
			var expird_tm = "{{ $vip_expired_time }}";

			if({{ $vip_expired_time }} != 0)
			{
				var date = new Date();
				date.setFullYear(expird_tm.substring(0,4));
				date.setMonth(expird_tm.substring(4,6)-1);
				date.setDate(expird_tm.substring(6,8));
				date.setHours(23);
				date.setMinutes(59);
				date.setSeconds(59);
				
				vip_expired_time = Date.parse(date)/1000;
			}
			else
			{
				vip_expired_time = 0;			
			}
			//console.log(vip_expired_time);

			
			var versionstr = "{{ $generate_time }}";
			var app_url = "{{ $game_url }}";

			//var flashvars = {ip:"192.168.1.8", port:"6190", userid:"{{ $account_name }}", prv_key:"{{ $validate_key }}", timestamp:"{{ $generate_time }}"};
			var flashvars = {ip:"{{ $loginserver_ip }}", port:"{{ $loginserver_port }}", userid:"{{ $account_name }}", prv_key:"{{ $validate_key }}", timestamp:"{{ $generate_time }}", platform:"{{ $platform }}", create_role:"{{ $create_role }}", server_id:"{{ $server_id }}", path:app_url};
			var params = {allowFullScreen:"true",allowScriptAccess:"always",wmode:"direct", allowfullscreenInteractive:"true"};
			var swf = app_url + 'bin-debug/proload.swf?' + versionstr;
            var attributes = {
                name:"biogame",
                id:"biogame"
            };
        
			var onEmbedError = function(msg) {
                if (!msg.success) {
                    //postData("http://game.iodsoft.cn/api/addlog?uid=" + "{{ $account_name }}" + "&type=15&logid=10026" + "&serverid=" + "{{ $server_id }}");
                    report_log(15, 10026, "");
                }
                else{
                    //postData("http://game.iodsoft.cn/api/addlog?uid=" + "{{ $account_name }}" + "&type=15&logid=10027" + "&serverid=" + "{{ $server_id }}");
                    report_log(15, 10027, "");
                }
			}

			swfobject.embedSWF(
				swf, "gameContent",
				"100%", "100%",
				"11.8.0",
				"bin-debug/expressInstall.swf",
				flashvars,params, attributes, onEmbedError
			);
            swfobject.createCSS("#gameContent", "display:block;text-align:center;");
            //onResize();
			}
		</script><style type="text/css" media="screen">#gameContent {visibility:hidden}#flashContent {display:block;text-align:center;}</style>
    </head>

<body>
<script language="JavaScript">collectData("pageBody");</script>
<!--
    <div id="gameContent" style="width:100%;height:100%;background:#ffffff;min-width:1280px;min-height:720px;">
        <h1>Flash player install</h1>
        <p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" /></a></p>
	</div>
    -->
    <div id = "rechargeContent">
		<div id = "rechargeBox">		
		</div>
	</div>
    <!--<div id = "resizeTarget" style="width:1280px;height:720px">        
    </div> -->
    <div id="resizeTarget" style="width:1280px;height:720px;background:#000000">	
		<div id="gameContent" style="width:100%;height:100%;background:#ffffff;min-width:1280px;min-height:720px;">
			<p  style="color:red;font-size:16px;">
				<a href = "http://www.baidu.com/s?tn=98010089_dg&ch=1&ie=utf-8&wd=flashplayer" > 你的FlashPlayer版本过低，点击这里安装FLASHPLAYER。 </a>
			</p>
			<script type="text/javascript"> 
				var pageHost = ((document.location.protocol == "https:") ? "https://" : "http://"); 
			</script> 
		</div> 
	</div> 

</body>

</html>
