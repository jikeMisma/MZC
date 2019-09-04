<?php
include_once '../inc/config.inc.php';
include_once '../inc/mysql.inc.php';
include_once '../inc/tool.inc.php';
$link=connect();
include_once 'inc/is_manage_login.inc.php';
if(isset($_POST['submit'])){
    include 'inc/check_manage.inc.php';
    //var_dump($_POST);
    $query="insert into sfk_manage(name,pw,create_time,level) values('{$_POST['name']}',md5({$_POST['pw']}),now(),{$_POST['level']})";
    execute($link,$query);
    if(mysqli_affected_rows($link)==1){
        skip('manage.php','ok','恭喜你，添加成功！');
    }else{
        skip('manage_add.php','error','对不起，添加失败，请重试！');
    }
    
}
$template['title']='管理员添加页';
$template['css']=array('style/public.css');
?>
<?php include 'inc/header.inc.php';?>
<div id="main">
	<div class="title" style="margin-bottom:20px;">添加管理员</div>
	<form method="post">
		<table class="au">
			<tr>
				<td>管理员姓名</td>
				<td><input name="name" type="text" /></td>
				<td>
					名称不得为空，不得超过32个字符
				</td>
			</tr>
			<tr>
				<td>密码</td>
				<td><input name="pw"  type="text" /></td>
				<td>
					密码不能少于六位
				</td>
			</tr>
			<tr>
				<td>等级</td>
				<td>
					<select name="level">
						<option value="1">普通管理员</option>
						<option value="0">超级管理员</option>
					</select>
				</td>
				<td>
					默认为普通管理员，(不具备后台管理员操作权限)
				</td>
			</tr>
		</table>
		<input style="margin-top:20px;cursor:pointer;" class="btn" type="submit" name="submit" value="添加" />
	</form>
</div>

<?php include 'inc/fooder.inc.php'?>