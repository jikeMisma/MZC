<?php
include_once 'inc/config.inc.php';
include_once 'inc/mysql.inc.php';
include_once 'inc/tool.inc.php';
$template['titele']='会员登录页';
$link=connect();
//$template['css']=array('style/public.css',);
$member_id=is_login($link);
if(!$member_id){
    skip('login.php', 'error', '您没有登录不需要退出');
}
setcookie('sfk[name]','',time()-3600);
setcookie('sfk[pw]','',time()-1);
skip('index.php','ok','退出成功！');

?>
