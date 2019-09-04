<?php
include_once '../inc/config.inc.php';
include_once '../inc/mysql.inc.php';
include_once '../inc/tool.inc.php';
$link=connect();
include_once 'inc/is_manage_login.inc.php';
$template['title']='管理员列表';
$template['css']=array('style/public.css');
?>
<?php include 'inc/header.inc.php';?> 
<div id="main" ">
	<div class="title">管理员列表</div>
	<table class="list">
		<tr>
			<th>姓名</th>
			<td>管理员id</td>	
			<th>操作等级</th> 	 	
			<th>创建日期</th>
			
		</tr>
		<?php 
		$query="select *  from sfk_manage";
		$result=execute($link, $query);
		while($data=mysqli_fetch_assoc($result)){
		    if($data['level'] ==0){
		        $data['level']='超级管理员';
		    }else{
		        $data['level']='普通管理员';
		    }
		  //father_module_delete.php?id={$data['id']}
		  // var_dump($data); 
		    $url=urlencode("manage_delete.php?id={$data['id']}");
		    $return_url=urlencode($_SERVER['REQUEST_URI']);
		    $message="你做的要删除管理员   {$data['name']}    吗？";
		  $delete_url="fonfirm.php?url={$url}&return_url={$return_url}&message={$message}";
		  
$html=<<<A
        <tr>
			<td>{$data['name']}</td>
			<td>{$data['id']}</td>
            <td>{$data['level']}</td>
            <td>{$data['create_time']}</td>
			<td>&nbsp;&nbsp;<a href="$delete_url">[删除]</a></td>
		</tr>
A;
        echo $html;

		}
		?>

	</table>
	
</div>
<?php include 'inc/fooder.inc.php';?> 