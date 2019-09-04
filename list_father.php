<?php
include_once 'inc/config.inc.php';
include_once 'inc/mysql.inc.php';
include_once 'inc/tool.inc.php';
include_once 'inc/pege.inc.php';
$link=connect();
$is_manage_login=is_manage_login($link);
$member_id=is_login($link);
if(!isset($_GET['id']) || !is_numeric($_GET['id'])){
    skip('index.php', 'error', '父版块id参数不合法！');
}

$query="select * from sfk_father_module where id={$_GET['id']}";
$resqult_father=execute($link, $query);
if(mysqli_num_rows($resqult_father) == 0){
    skip('index.php', 'error', '父版块不存在！');
}
$data_father=mysqli_fetch_assoc($resqult_father);

$query="select * from sfk_son_module where father_module_id={$_GET['id']}";
$result_son=execute($link, $query);
$id_son='';
$son_list='';
while($data_son=mysqli_fetch_assoc($result_son)){
    $id_son.=$data_son['id'].',';
    $son_list.="<a href='list_son.php?id={$data_son['id']}'>{$data_son['module_name']}&nbsp;&nbsp;</a>'  ";
}
if($id_son == ''){
    $id_son='-1';
}
$id_son=trim($id_son,',');
$query="select count(*) from sfk_content where module_id in({$id_son})";
$count_all=num($link, $query);


$query="select count(*) from sfk_content where module_id in({$id_son}) and time>CURDATE()";
$count_today=num($link, $query);



$template['titele']=$data_father['module_name'];


?>
<?php include 'inc/header.inc.php';?>
<div id="position" class="auto">
<a href="index.php">首页</a> &gt; <a href="list_father.php?id=<?php echo $data_father['id']?>"><?php echo $data_father['module_name']?></a>

</div>
<div id="main" class="auto">
	<div id="left">
		<div class="box_wrap">
			<h3><?php echo $data_father['module_name']?></h3>
			<div class="num">
			    今日：<span><?php echo $count_today?></span>&nbsp;&nbsp;&nbsp;
			    总帖：<span><?php echo $count_all?></span>
			  <div class="moderator"> 子版块：<?php echo $son_list?></div>
			</div>
			<div class="pages_wrap">
				<a class="btn publish" target="_black"href="publish.php?father_module_id=<?php echo $_GET['id']?>"></a>
				<div class="pages">
				<?php 
				
				$page=page($count_all, 10,2);
				echo $page['html'];
				?>
				</div>
				<div style="clear:both;"></div>
			</div>
		</div>
		<div style="clear:both;"></div>
		<ul class="postsList">
		<?php 
		/* 
		 select 
		 sfk_content.title,sfk_content.id,sfk_content.time,sfk_member.name,sfk_member.photo,sfk_son_module.module_name 
		 from sfk_content,sfk_member,sfk_son_module where 
		 sfk_content.module_id in({$id_son}) and 
		 sfk_content.member_id=sfk_member.id and 
		 sfk_content.module_id=sfk_son_module.id
		 *  */
		$query="select 
		 sfk_content.title,sfk_content.id,sfk_content.time,sfk_content.times,sfk_content.member_id,sfk_member.name,sfk_member.photo,sfk_son_module.module_name,sfk_son_module.id ssm_id  
		 from sfk_content,sfk_member,sfk_son_module where 
		 sfk_content.module_id in({$id_son}) and 
		 sfk_content.member_id=sfk_member.id and 
		 sfk_content.module_id=sfk_son_module.id {$page['limit']}";
        $result_content=execute($link, $query);
        //var_dump(mysqli_fetch_all($result_content,MYSQLI_ASSOC));
        while($data_content=mysqli_fetch_assoc($result_content)){
            $data_content['title']=htmlspecialchars($data_content['title']);
            $query="select time from sfk_reply where content_id={$data_content['id']} order by id  desc limit 1";
            $result_last_reply=execute($link, $query);
            if(mysqli_num_rows($result_last_reply) == 0){
                $last_time='暂无';
            }else{
                $data_last_reply=(mysqli_fetch_assoc($result_last_reply));
                $last_time=$data_last_reply['time'];
            }
            
            $query="select  count(*) from sfk_reply where content_id={$data_content['id']}";
            $count_huifu=num($link, $query)
            
		?>
			<li>
				<div class="smallPic">
					<a href="member.php?id=<?php echo $data_content['member_id']?>">
						<img width="45" height="45"src="<?php if($data_content['photo']!=''){echo SUB_URL.$data_content['photo'];}else{echo 'style/mzc.jpg';}?>">
					</a> 
				</div>
				<div class="subject">
					<div class="titleWrap"><a href="list_son.php?id=<?php echo $data_content['ssm_id']?>">[<?php  echo $data_content['module_name']?>]</a>&nbsp;&nbsp;<h2><a  target="_blank"href="show.php?id=<?php echo $data_content['id']?>"><?php  echo $data_content['title']?></a></h2></div>
					<p>
						<?php 
						if(check_user($member_id, $data_content['member_id'],$is_manage_login)){
					    // var_dump($data);
					    $return_url=urlencode($_SERVER['REQUEST_URI']);
					    $url=urlencode("content_delete.php?id={$data_content['id']}&return_url={$return_url}");
		
					    $message="你做的要删除帖子   {$data_content['title']}    吗？";
					    $delete_url="fonfirm.php?url={$url}&return_url={$return_url}&message={$message}";
					   echo "<a href='content_update.php?id={$data_content['id']}&return_url={$return_url}'>编辑</a>&nbsp;&nbsp;&nbsp;<a href='{$delete_url}'>删除</a><br /><br />"; 
					}

					?>
						楼主：<?php  echo $data_content['name']?>&nbsp;<?php  echo $data_content['time']?>&nbsp;&nbsp;&nbsp;&nbsp;最后回复：<?php echo $last_time?>
					</p>
				</div>
				<div class="count">
					<p>
						回复<br /><span><?php echo $count_huifu?></span>
					</p>
					<p>
						浏览<br /><span><?php  echo $data_content['times']?></span>
					</p>
				</div>
				<div style="clear:both;"></div>
			</li>
			<?php 
                }
			?>
		</ul>
		<div class="pages_wrap">
			<a class="btn publish" href="publish.php?father_module_id=<?php echo $_GET['id']?>"></a>
			<div class="pages">
				<?php echo $page['html']?>
			</div>
			<div style="clear:both;"></div>
		</div>
	</div>
	<div id="right">
		<div class="classList">
			<div class="title">版块列表</div>
			<ul class="listWrap">
			<?php 
			 $query="select * from sfk_father_module";
			 $result_father=execute($link, $query);
			 while($data_father1=mysqli_fetch_assoc($result_father)){
			?>
				<li>
					<h2><a href="list_father.php?id=<?php echo $data_father1['id']?>"><?php echo $data_father1['module_name']?></a></h2>
					<ul>
					<?php 
					$query="select * from sfk_son_module where father_module_id={$data_father1['id']}";
					$result_son=execute($link, $query);
					while($data_son=mysqli_fetch_assoc($result_son)){
					    
					?>
						<li><h3><a href="list_son.php?id=<?php echo $data_son['id']?>"><?php echo $data_son['module_name']?></a></h3></li>
						<?php }?>
					</ul>
				</li>
				<?php }?>
			</ul>
		</div>
	</div>
	<div style="clear:both;"></div>
</div>

<?php include 'inc/footer.inc.php';?>