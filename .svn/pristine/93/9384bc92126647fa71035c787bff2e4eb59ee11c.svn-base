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
			       background-color: #ffffff;}
			#gameContent {width:100;height:100%;background:#000000} 
			
        </style>
        
        	<script type="text/javascript">document.domain = "oasgames.com";</script>
            <script type="text/javascript" src="bin-debug/swfobject.js"></script>
            <script type="text/javascript" src="bin-debug/jquery.js"></script>
            <script type="text/javascript" src="bin-debug/js_launcher.js"></script>
            <script type="text/javascript">
                    window.onload = function() {
                    //prelaunch();
                    
                    //检察是否安装flash player
                    var fls = flashChecker();
                    if(!fls.f)
                    {
                        //如果没安装,则提交错误log
                        postData("https://s1tr.dh.oasgames.com/oas/addlog?uid=" + "{{ $uid }}" + "&type=15&logid=10025" + "&serverid=" + "{{ $server_id }}");
                    }
                    else
                    {
                        postData("https://s1tr.dh.oasgames.com/oas/addlog?uid=" + "{{ $uid }}" + "&type=15&logid=10002" + "&serverid=" + "{{ $server_id }}");               
                    }
                    
                    var versionstr = "{{ $generate_time }}";
                    var app_url = "{{ $game_url }}";

                    var flashvars = {ip:"{{ $gs_ip }}", port:"{{ $gs_port }}", userid:"{{ $uid }}", prv_key:"{{ $validate_key }}", platform:"{{ $platform }}", timestamp:"{{ $generate_time }}",server_id:"{{ $server_id }}", create_role:"{{ $create_role }}", path:app_url, log_url:"http://148.153.34.19:7080"};
                    var attributes = {};
                    var params = {allowFullScreen:"true",allowScriptAccess:"always",wmode:"direct", allowfullscreenInteractive:"true"};
                var swf = app_url + 'bin-debug/proload.swf?' + versionstr;
                    
                    var onEmbedError = function(msg) {
                        if (!msg.success) {
                            //collectData("embedError");
                            postData("https://s1tr.dh.oasgames.com/oas/addlog?uid=" + "{{ $uid }}" + "&type=15&logid=10026" + "&serverid=" + "{{ $server_id }}");
                            parent.OasisSdk.show_active_flash_guide();
                        }
                        else
                        {
                            postData("https://s1tr.dh.oasgames.com/oas/addlog?uid=" + "{{ $uid }}" + "&type=15&logid=10027" + "&serverid=" + "{{ $server_id }}");
                        }
                    }

                    swfobject.embedSWF(
                        swf, "gameContent",
                        "100%", "100%",
                        "11.6.0",
                        "bin-debug/expressInstall.swf",
                        flashvars,params, attributes, onEmbedError
                    );
                    }
                    
                    function report_log(_uid, _type, _logid) {
                        postData("https://s1tr.dh.oasgames.com/oas/addlog?uid=" + _uid + "&type=" + _type + "&logid=" + _logid + "&serverid=" + "{{ $server_id }}");
                    }
                </script>
    </head>
   
   
   <body onresize="resize();">
    
    <div id="gameContent" style="width:100%;height:100%;background:#ffffff;min-width:1280px;min-height:720px;">
        <h1>Flash player install</h1>
        <p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" /></a></p>
	</div>
</body>
</html>
