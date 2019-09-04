<?php
if(empty($_POST['module_id']) || !is_numeric($_POST['module_id'])){
    skip('publish.php', 'error', '所属版块id不合法！');
}
$query="select *  from sfk_son_module where id={$_POST['module_id']}";
$result=execute($link, $query);
if(mysqli_num_rows($result) != 1){
    skip('publish.php', 'error', '请选择一个版块！');
}
if(empty($_POST['title'])){
    skip('publish.php', 'error', '标题不能为空！');
}
if(mb_strlen($_POST['title'])>255){
    skip('register.php', 'error', '标题用度不要超过255个字符！');
}

?>