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
			#gameContent {width:100;height:100%;background:#ff0000} 
			
        </style>
    </head>
    <body>
	<div style="width:100%;height:100%;background:#ff0000">
   	    <div id="gameContent"> </div>
		<!--
   	    <div style="color:#cccccc" id="versionDiv"> 111 </div>
		-->
	</div>
	</div>
	<script type="text/javascript" src="swfobject.js"></script>
		<script type="text/javascript">
		    function getUrlParameter() {
                var parameters ={};
                var url = window.location.href;
    
                var index = url.indexOf("?");
                if (index >= 0 && index != url.length-1) {
                    var data = url.substr(index + 1);
                    var items = data.split("&");
                    for (var i = 0; i < items.length; i++) {
                        if(items[i].indexOf("=") >= 0){
                            var param = items[i].split("=");
                            parameters[param[0]] = unescape(param[1]);
                        }
                    }
                }
                return parameters;
           }
				
			var versionstr = "dev-2013-03-30-17-20";
		    
			var flashvars = {ip:"192.168.1.8", port:"6190", userid:"{{ $account_name }}", prv_key:"{{ $validate_key }}", timestamp:"{{ $generate_time }}"};
			var params = {allowFullScreen:"true",allowScriptAccess:"always",wmode:"direct", allowfullscreenInteractive:"true"};
		var swf = 'flash/bin-debug/ProLoad.swf?' + versionstr;
		swfobject.embedSWF(
	    	swf, "gameContent",
	    	"100%", "100%",
	    	"11.0.0",
	    	"expressInstall.swf",
	    	flashvars,params
    	);
		</script>
   </body>
</html>