<?php
if(empty($_POST['name'])){
    skip('login.php', 'error', '用户名不能为空');
}
if(empty($_POST['pw'])){
    skip('login.php', 'error', '密码不能为空');
}
if(mb_strlen($_POST['name'])>32){
    skip('register.php', 'error', '用户名长度不要超过32个字符！');
}
if(strtolower($_POST['vcode'] )!=strtolower($_SESSION['vcode'])){
    skip('register.php', 'error','验证码输入错误！');
}
if(empty($_POST['time']) ||  is_numeric($_POST['time']) || $_POST['time']>2592000){
    $_POST['time']=2592000;
}



?>