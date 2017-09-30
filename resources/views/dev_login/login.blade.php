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
		
			#gameContent {width:100%;height:100%;background:#000000} 
			
        </style>
        <script type="text/javascript" src="bin-debug/swfobject.js"></script>
        <script type="text/javascript" src="bin-debug/jquery.js"></script>
	<script type="text/javascript" src="bin-debug/js_launcher.js?rand=" + new Date().getTime()></script>
<!--	<script type="text/javascript">
		var webpath = "bin-debug/js_launcher.js?rand=" + new Date().getTime();
		document.write('<scr'+'ipt src="' + webpath +'"></scr'+'ipt>');
	</script>
-->
	
	<script type="text/javascript">
        var spe_gs;
        function req_login(res_func, usrId)
        {
            var url = login_url + "dev_login/req_login?userid="+usrId + "&gs=" + spe_gs;
            //console.log("req_login:" + url + res_func);
            GetJson(url
            , function(data)
            {
				try{
					thisMovie()[res_func](data);
				}
				catch(e){
					alert(e);
				}
				//alert("2aaa:" +  data + " " + thisMovie() + " " + res_func);
            }
            , function(data)
            {
                thisMovie()[res_func](data);
            }
            , null
            , false);
        
        }
        
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
        }
       
		window.onload = function() {
			prelaunch();
            initGameData();
            spe_gs = "{{ $spe_gs }}";
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
                //report_log(15, 10025, "");
            }
            else
            {
                //report_log(15, 100026, "");
            }
            
			var versionstr = "{{ $generate_time }}";
			var app_url = "{{ $game_url }}";

			//var flashvars = {ip:"192.168.1.8", port:"6190", userid:"{{ $account_name }}", prv_key:"{{ $validate_key }}", timestamp:"{{ $generate_time }}"};
			var flashvars = {ip:"{{ $loginserver_ip }}", port:"{{ $loginserver_port }}", userid:"{{ $account_name }}", prv_key:"{{ $validate_key }}", timestamp:"{{ $generate_time }}", platform:"{{ $platform }}", create_role:"{{ $create_role }}", server_id:"{{ $server_id }}", path:app_url, show_login:"{{ $show_login }}"};
			var params = {bgcolor:"#000000", allowFullScreen:"true",allowScriptAccess:"always",wmode:"direct", allowfullscreenInteractive:"true"};
			var swf = app_url + 'bin-debug/proload.swf?' + versionstr + '?timestamp='+new Date();
            var attributes = {
                name:"biogame",
                id:"biogame"
            };
        
			var onEmbedError = function(msg) {
                if (!msg.success) {
                    //report_log(15, 10026, "");
                }
                else{
                    //report_log(15, 10027, "");
                }
			}

			swfobject.embedSWF(
				swf, "gameContent",
				"100%", "100%",
				"11.6.0",
				"bin-debug/expressInstall.swf",
				flashvars,params, attributes, onEmbedError
			);
            //swfobject.createCSS("#gameContent", "display:block;text-align:center;");
            //onResize();
			}
		</script><style type="text/css" media="screen">#gameContent {visibility:hidden}#flashContent {display:block;text-align:center;}</style>
    </head>

<body>
<script language="JavaScript"></script>

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
	<div id="resizeTarget" style="width:1200px;height:675px;background:#000000">	
		<div id="gameContent" style="width:%100;height:%100;background:#000000;min-width:1200px;min-height:675px;">
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
