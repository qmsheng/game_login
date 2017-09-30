<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- saved from url=(0014)about:internet -->
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">	
    <head>
        <title></title>         
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <base href="{{$game_url}}"/>
        <style type="text/css" media="screen"> 
			html, body	{ height:100%; width:100%;}
			body { margin:0; padding:0;
			       background-color: #000000;}
		
			#gameContent {width:100;height:100%;background:#000000} 
			
        </style>
		<link rel="stylesheet" type="text/css" href="bin-debug/history/history.css" />
        <script type="text/javascript" src="bin-debug/history/history.js"></script>
        <script type="text/javascript" src="bin-debug/swfobject.js"></script>
        <script type="text/javascript" src="bin-debug/jquery.js"></script>
		<script type="text/javascript" src="bin-debug/js_launcher.js"></script>
		<script type="text/javascript">
		window.onload = function() {
			prelaunch();
            
            //检察是否安装flash player,如果没安装,则提交错误log
            var fls = flashChecker();
            if(!fls.f)
            {
				postData("http://bianyi-pc/oas/addlog?uid=" + "{{ $account_name }}" + "&type=15&logid=10025");
                //postData("http://192.168.1.90:7080/addLog?uid=" + "{{ $account_name }}" + "&type=15&numberdata1=10025");
            }
			
			var versionstr = "{{ $generate_time }}";
			var app_url = "{{ $game_url }}";

			//var flashvars = {ip:"192.168.1.8", port:"6190", userid:"{{ $account_name }}", prv_key:"{{ $validate_key }}", timestamp:"{{ $generate_time }}"};
			var flashvars = {ip:"{{ $loginserver_ip }}", port:"{{ $loginserver_port }}", userid:"{{ $account_name }}", prv_key:"{{ $validate_key }}", timestamp:"{{ $generate_time }}", platform:"{{ $platform }}", server_id:"{{ $server_id }}", path:app_url, log_url:"http://192.168.1.90:7080"};
			var params = {allowFullScreen:"true",allowScriptAccess:"always",wmode:"direct", allowfullscreenInteractive:"true"};
			var swf = app_url + 'bin-debug/proload.swf?' + versionstr;
			
			var attributes = {};
			var onEmbedError = function(msg) {
				if (!msg.success) {
					collectData("embedError");
				}
			}

			swfobject.embedSWF(
				swf, "gameContent",
				"100%", "100%",
				"11.8.0",
				"bin-debug/playerProductInstall.swf",
				flashvars,params, attributes, onEmbedError
			);
			swfobject.createCSS("#gameContent", "display:block;text-align:left;");
			}
		</script>
    </head>

<body>
<script language="JavaScript">collectData("pageBody");</script>
<div id="gameContent">
  <div id="gameContainer" style="text-align:center; padding:10px; font-size: 24px; line-height: 600px; style='color:#000000';">
  game loading...
  <noscript>对不起您的浏览器不支持JavaScript，请启用JavaScript！</noscript>
  </div>
</div>
</body>

</html>