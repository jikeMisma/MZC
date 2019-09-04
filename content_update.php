<?php
include_once 'inc/config.inc.php';
include_once 'inc/mysql.inc.php';
include_once 'inc/tool.inc.php';
$link=connect();
$member_id=is_login($link);
$is_manage_login=is_manage_login($link);
if(!$member_id && !$is_manage_login){
    skip('login.php', 'error', '请登录后进行操作!') ;
}
if(!isset($_GET['id']) || !is_numeric($_GET['id'])){
    skip('index.php', 'error', '帖子id参数不合法！');
}

$query="select * from sfk_content where id={$_GET['id']}";
$result_content=execute($link, $query);
if(mysqli_num_rows($result_content) ==1){
    $data_content=mysqli_fetch_assoc($result_content);
    $data_content['title']=htmlspecialchars($data_content['title']);
    $data_content['member_id'];
    if(check_user($member_id, $data_content['member_id'],$is_manage_login)){
        if(isset($_POST['submit'])){
            include 'inc/check_publish.inc.php';
            $_POST=escape($link, $_POST);
            $query="update sfk_content set module_id={$_POST['module_id']},title='{$_POST['title']}',content='{$_POST['content']}' where id={$_GET['id']}";
            $result=execute($link, $query);
            if(isset($_GET['return_url'])){
                $return_url=$_GET['return_url'];
            }else{
                $return_url='member.php?id={$member_id}';
            }
            if(mysqli_affected_rows($link) == 1){
                skip($return_url, 'ok', '恭喜你，修改成功！');
            }else{
                skip($return_url, '发布失败，请重试！');
            }
        
        }
    }else{
        skip('index.php', 'error', '帖子不属于你，您无权限') ;
    }
}else{
    skip('inxex.php', 'error', '帖子不存在!') ;
}

$template['titele']='帖子修改页面';
?>
<?php include 'inc/header.inc.php';?>
<div id="position" class="auto">
	 <a>首页</a> &gt;发布帖子
</div>
<div id="publish">
	<form method="post">
		<select name="module_id">
		<option value='-1'>请选择一个子版块</option>
		<?php 
			$query="select * from sfk_father_module {$where}order by sort desc";
			$result_father=execute($link, $query);
			while ($data_father=mysqli_fetch_assoc($result_father)){
				echo "<optgroup label='{$data_father['module_name']}'>";
				$query="select * from sfk_son_module where father_module_id={$data_father['id']} order by sort desc";
				$result_son=execute($link, $query);
				while ($data_son=mysqli_fetch_assoc($result_son)){
				    if($data_son['id']==$data_content['module_id']){
				        echo "<option selected='selected' value='{$data_son['id']}'>{$data_son['module_name']}</option>";
				    }else{
				        echo "<option value='{$data_son['id']}'>{$data_son['module_name']}</option>";
				    }
				}
				echo "</optgroup>";
			}
			?>
		</select>
		<input class="title" placeholder="请输入标题" value="<?php echo $data_content['title']?>"name="title" type="text" />
		<textarea name="content" class="content"><?php echo $data_content['content']?></textarea>
		<input class="publish" type="submit" name="submit" value="" />
		<div style="clear:both;"></div>
	</form>
</div>
<?php include 'inc/footer.inc.php';?>