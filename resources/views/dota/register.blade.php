<!DOCTYPE html>
<html lang="zh_CN">
<head>
    <title>×¢²á</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <link rel='stylesheet' href='/dota_asset//css/bio.css' type='text/css' />

</head>

<body class="bg">
<div class="content">
    <div class="panel">
        <form action="register" name="form" method="post">
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
                        <p class="input_txt"><input type="text" name="username" class="form_input_txt" value="<?php if(isset($username)){echo $username;} ?>" maxlength="32" /></p>
                        <p class="space_password"></p>
                        <p class="input_txt"><input type="password" name="password" class="form_input_txt" maxlength="32" /></p>
                        <p class="space_confirm_password"></p>
                        <p class="input_txt"><input type="password" name="confirm_password" class="form_input_txt" maxlength="32" /></p>
                        <p class="error">
                            <?php
                            if (isset($errnum) && $errnum != 0)
                            {
                                echo $errmsg;
                            }
                            ?>
                        </p>
                        <p class="reg_bar">
                            <a class="btn_reg" href="javascript:form.submit();"></a>
                            <a class="s_link" href="login"><u>返回登录</u></a>
                        </p>

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

</body>
<html>