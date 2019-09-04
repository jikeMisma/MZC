<?php
include_once 'inc/config.inc.php';
include_once 'inc/mysql.inc.php';
include_once 'inc/tool.inc.php';
include_once 'inc/pege.inc.php';
$link=connect();
$is_manage_login=is_manage_login($link);
$member_id=is_login($link);

if(!isset($_GET['keyword'])){
    $_GET['keyword']='';
}
$_GET['keyword']=trim($_GET['keyword']);
$_GET['keyword']=escape($link, $_GET['keyword']);
$query="select count(*) from sfk_content where title like '%{$_GET['keyword']}%'";
$count_all=num($link, $query);



$template['titele']='搜索页';

?>
<?php include 'inc/header.inc.php';?>
<div id="position" class="auto">
	<?php 
    
    ?>
	 <a href="index.php">首页</a> &gt;搜索页
</div>
<div id="main" class="auto">
	<div id="left">
		<div class="box_wrap">
			<h3>共有<?php echo $count_all?>条匹配的记录</h3>
			<div class="pages_wrap">
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
		$query="select
		 sfk_content.title,sfk_content.id,sfk_content.time,sfk_content.times,sfk_content.member_id,sfk_member.name,sfk_member.photo
		 from sfk_content,sfk_member where
		 sfk_content.title like '%{$_GET['keyword']}%' and
		 sfk_content.member_id=sfk_member.id 
		  {$page['limit']}";
		$result_content=execute($link, $query);
		while($data_content=mysqli_fetch_assoc($result_content)){
		    $data_content['title']=htmlspecialchars($data_content['title']);
		    $data_content['title_color']= str_replace($_GET['keyword'], "<span style='color:red'>{$_GET['keyword']}</span>", $data_content['title']);
		    $query="select time from sfk_reply where content_id={$data_content['id']} order by id  desc limit 1";
		    $result_last_reply=execute($link, $query);
		    if(mysqli_num_rows($result_last_reply) == 0){
		        $last_time='暂无';
		    }else{
		        $data_last_reply=(mysqli_fetch_assoc($result_last_reply));
		        $last_time=$data_last_reply['time'];
		    }
		    
		    $query="select  count(*) from sfk_reply where content_id={$data_content['id']}";
		    $count_huifu=num($link, $query);
		   
		?>
			<li>
				<div class="smallPic">
					<a href="member.php?id=<?php echo $data_content['member_id']?>">
						<img width="45" height="45"src="<?php if($data_content['photo']!=''){echo SUB_URL.$data_content['photo'];}else{echo 'style/mzc.jpg';}?>">
					</a> 
				</div>
				<div class="subject">
					<div class="titleWrap">&nbsp;&nbsp;<h2><a target="_blank"href="show.php?id=<?php echo $data_content['id']?>"><?php  echo $data_content['title_color']?></a></h2></div>
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
						回复<br /><span><?php echo $count_huifu;?></span>
					</p>
					<p>
						浏览<br /><span><?php  echo $data_content['times']?></span>
					</p>
				</div>
				<div style="clear:both;"></div>
			</li>
			<?php }?>
		</ul>
		<div class="pages_wrap">
			<div class="pages">
				<?php 
				echo $page['html'];
				?>
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