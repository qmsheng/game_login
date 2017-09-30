<!DOCTYPE html>
<html lang="zh_CN">
<head>
    <meta charset="utf-8" />
    <title>登录</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <link rel='stylesheet' href='/dota_asset/css/bio.css' type='text/css' />
</head>

<body class="bg">
<div class="content">
    <div class="panel">
        <form action="dota" name="form" method="post" onkeydown="submitByEnter();">
            <table class="box" >
                <tr>
                    <td class="t_l"></td>
                    <td class="t_m"></td>
                    <td class="t_r"></td>
                </tr>
                <tr>
                    <td class="m_l"></td>
                    <td class="m_m">
                        <p class="space_username"></p>

                        <p class="input_txt"><input type="text" name="username" class="form_input_txt" value="{{ old('username') }}" maxlength="32" /></p>
                        <p class="space_password"></p>
                        <p class="input_txt"><input type="password" name="password" class="form_input_txt" maxlength="32" /></p>
                        <p class="contain">

						<span class="aleft">
							<input type="checkbox" class="css-checkbox" id="remember" value="1" name="remember" <?php if(isset($remember) && $remember == 1){echo 'checked';} ?>>
							<label for="remember" class="css-label">记住账号</label>
						</span>
                            <span class="aright"><a class="s_link" href="dota/register">注册账号</a></span>
                        </p>
                        <p class="error">

                            <?php
                            if (isset($errnum) && $errnum != 0)
                            {
                                echo $errmsg;
                            }
                            ?>
                        </p>
                        <p><a class="btn_login" href="javascript:form.submit();"></a></p>

                    </td>
                    <td class="m_r"></td>
                </tr>
                <tr>
                    <td class="b_l"></td>
                    <td class="b_m"></td>
                    <td class="b_r"></td>
                </tr>
            </table>
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
        </form>
    </div>
</div>
<script type="text/javascript" src="/dota_asset/js/jquery.min.js"></script>
<script type="text/javascript">
    $(function(){
        if($('input[type="checkbox"]').attr("checked") == "checked"){
            $('input[type="checkbox"]')[$('input[type="checkbox"]').prop('checked') ? 'addClass' : 'removeClass']('checked');
        }
        return true;
    });
    $('input[type="checkbox"]').on('change', function() {
        $(this)[$(this).prop('checked') ? 'addClass' : 'removeClass']('checked');
        return true;
    });
    function submitByEnter()
    {
        if(event.keyCode == 13)
        {
            form.submit();
        }
    }
</script>
</body>
<html>