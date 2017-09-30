WEB采用php框架laravel5.1 开发的，需要PHP版本至少是5.4以上的，更高版本则更好，暂不要使用php7



//新 qchen
.env内
CLIENT_ROOT_URL 客户端root ui
LOGIN_SERVER_IP login服务器地址
LOGIN_SERVER_PORT login服务器端口

需要相应改变的文件
app\Http\Controllers\Login\IndexController.php
resources\views\login\login.blade.php



.env是本地配置文件
账号库，如果没有可以自建一个，注册账号会写到这里，登录时也从这里去取
ACCOUNT_DB_HOST=localhost
ACCOUNT_DB_DATABASE=biowar_account
ACCOUNT_DB_USERNAME=root
ACCOUNT_DB_PASSWORD=

游戏库，这里是考虑有可能需要先判定游戏数据是否存在，如未用到也可以不用管
GAME_DB_HOST=localhost
GAME_DB_DATABASE=biowar
GAME_DB_USERNAME=root
GAME_DB_PASSWORD=

//这两个是腾讯平台登录用的IP
SERVE_HOST=119.29.54.28
SERVE_HOST_DEBUG=119.29.54.28

//这个是第三方和服务器上一致的登录KEY，是字符串
LOGIN_KEY=1234567890
//这个地址是由第三方跳转到游戏的登录页面，用于做一些检查，然后生成客户端的页面，并把参数传给客户端
LOGIN_URL="http://www.webgame.com/api/login"
//这个是实际下载客户端的基地址，
APPLICATION_URL="http://119.29.54.28/game/bin-release/"

//这两个参数是传给客户端用的，跟登录无关，只是我们这边客户端用到了，可以不用管也可以
LOBBY_SERVER_IP=119.29.54.28
LOBBY_SERVER_PORT=2983




结构
webgame\app\Http\Controllers\Api  是处理第三方跳转过来的模块，同时也是游戏内部一些地方要用到接口的模块
webgame\app\Http\routes.php  是路由配置文件，请根据需要自行改变路由
webgame\config\app.php  是主配置文件，通常只在开发阶段设置好即可
webgame\config\database.php  是数据库配置文件，.env是本地化配置，即不同服务器上配置不同时使用，database.php会先读取.env里的配置，如找不到时才会读取自己的默认配置
webgame\resources\views  这里是视图目录，这里也是按模块划分的，需要修改界面时修改这里



apache WEB服务器需要加载rewrite module
部署时建立虚拟站点，路径指向   webgame\public\    目录下
hosts添加  127.0.0.1    www.webgame.com
访问时使用 http://www.webgame.com/dota 

布署login php,
直接checkout svn地址即可..
svn checkout http://ccaa11.wicp.net:990/svn/php_login/ ./
svn帐号密码:editor,11
注:login仓库为git仓库,可以将其主目录,设置为git和svn共有仓库,在变更后,两个管理软件同时提交即可..
更新时直接 svn up即可..

在主目录内.env文件不受svn管理,在布署时,需将其单独添加..



* * *
****布署过程中可能遇到的问题****

1.object 错误, 这种情况有可能是主目录没有访问权限..在public 目录下,加入.htaccess文件即可..
2.访问时出现白屏(在IE上访问,提示内部错误)---storage权限不足..执行 chmod -R 777 storage/ 命令,增加其权限即可..

* * *