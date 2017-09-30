<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- saved from url=(0014)about:internet -->
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<!--
Smart developers always View Source.

This application was built using Adobe Flex, an open source framework
for building rich Internet applications that get delivered via the
Flash Player or to desktops via Adobe AIR.

Learn more about Flex at http://flex.org
// -->
<head>
    <title>BioWarApp</title>
    <meta name="google" value="notranslate"/>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <!-- Include CSS to eliminate any default margins/padding and set the height of the html element and
         the body element to 100%, because Firefox, or any Gecko based browser, interprets percentage as
         the percentage of the height of its parent container, which has to be set explicitly.  Fix for
         Firefox 3.6 focus border issues.  Initially, don't display flashContent div so it won't show
         if JavaScript disabled.
    -->
    <!-- BEGIN INSERT -->
    <META HTTP-EQUIV="Expires" CONTENT="Mon, 04 Dec 1999 21:29:02 GMT">
    <!-- END INSERT -->
    <base href="{{$game_url}}" />
    <style type="text/css" media="screen">
        html, body {
            width: 100%;
            height: 100%;
        }

        body {
            margin: 0;
            padding: 0;
            overflow: auto;
            text-align: center;
            background-color: #000000;
        }

        object:focus {
            outline: none;
        }

        #flashContent {
            display: none;
        }
    </style>

    <!-- Enable Browser History by replacing useBrowserHistory tokens with two hyphens -->
    <!-- BEGIN Browser History required section ${useBrowserHistory}>
    <link rel="stylesheet" type="text/css" href="history/history.css" />
    <script type="text/javascript" src="history/history.js"></script>
    <!${useBrowserHistory} END Browser History required section -->

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
        // For version detection, set to min. required Flash Player version, or 0 (or 0.0.0), for no version detection.
        var swfVersionStr = "11.4.0";
        // To use express install, set to playerProductInstall.swf, otherwise the empty string.
        var xiSwfUrlStr = "${expressInstallSwf}";
        var flashvars = getUrlParameter();
        flashvars.test1 = "testvalue1";
        flashvars.account_name = "{{ $account_name }}";
        flashvars.account_id = "{{ $account_id }}";
        flashvars.validate_key = "{{ $validate_key }}";
        flashvars.generate_time = "{{ $generate_time }}";
        flashvars.lobby_addr = '{{ $lobby_addr }}';
        flashvars.lobby_port = '{{ $lobby_port }}';
        flashvars.rank_url = '{{ $rank_url }}';
        var params = {};
        params.quality = "high";
        params.bgcolor = "#000000";
        params.allowscriptaccess = "sameDomain";
        params.allowfullscreen = "true";
        params.allowfullscreenInteractive = "true";
        params.wmode = "direct";
        var attributes = {};
        attributes.id = "BioWarApp";
        attributes.name = "BioWarApp";
        attributes.align = "middle";
        swfobject.embedSWF(
                "BioWarApp.swf", "flashContent",
                "100%", "100%",
                swfVersionStr, xiSwfUrlStr,
                flashvars, params, attributes);
        // JavaScript enabled so display the flashContent div in case it is not replaced with a swf object.
        swfobject.createCSS("#flashContent", "display:block;text-align:left;");
    </script>
</head>
<body>
<!-- SWFObject's dynamic embed method replaces this alternative HTML content with Flash content when enough
     JavaScript and Flash plug-in support is available. The div is initially hidden so that it doesn't show
     when JavaScript is disabled.
-->
<div id="flashContent">
    <p>
        To view this page ensure that Adobe Flash Player version
        11.4.0 or greater is installed.
    </p>
    <script type="text/javascript">
        var pageHost = ((document.location.protocol == "https:") ? "https://" : "http://");
        document.write("<a href='http://www.adobe.com/go/getflashplayer'><img src='"
                + pageHost + "www.adobe.com/images/shared/download_buttons/get_flash_player.gif' alt='Get Adobe Flash player' /></a>");
    </script>
</div>

<noscript>
    <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="100%" height="100%" id="BioWarApp">
        <param name="movie" value="BioWarApp.swf"/>
        <param name="quality" value="high"/>
        <param name="bgcolor" value="#000000"/>
        <param name="allowScriptAccess" value="sameDomain"/>
        <param name="allowFullScreen" value="true"/>
        <param name="wmode" value="direct"/>
        <!--[if !IE]>-->
        <object type="application/x-shockwave-flash" data="BioWarApp.swf" width="1024" height="768">
            <param name="quality" value="high"/>
            <param name="bgcolor" value="#000000"/>
            <param name="allowScriptAccess" value="sameDomain"/>
            <param name="allowFullScreen" value="true"/>
            <param name="wmode" value="direct"/>
            <!--<![endif]-->
            <!--[if gte IE 6]>-->
            <p>
                Either scripts and active content are not permitted to run or Adobe Flash Player version
                11.4.0 or greater is not installed.
            </p>
            <!--<![endif]-->
            <a href="http://www.adobe.com/go/getflashplayer">
                <img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash Player"/>
            </a>
            <!--[if !IE]>-->
        </object>
        <!--<![endif]-->
    </object>
</noscript>
</body>
</html>

