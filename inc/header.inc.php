<?php
//var_dump();
$query="select * from sfk_info where id=1";
$result_info=execute($link, $query);
$data_infp=mysqli_fetch_assoc($result_info);

?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8" />
<title><?php echo  $template['titele'];?> -<?php echo $data_infp['title']?></title>
<meta name="keywords" content="<?php echo $data_infp['keywords']?>" />
<meta name="description" content="<?php echo $data_infp['description']?>" />
<link rel='stylesheet' type='text/css' href='style/public.css' />";
<link rel='stylesheet' type='text/css' href='style/register.css' />";
<link rel='stylesheet' type='text/css' href='style/publish.css' />";
<link rel='stylesheet' type='text/css' href='style/index.css' />";
<link rel='stylesheet' type='text/css' href='style/list.css' />";
<link rel='stylesheet' type='text/css' href='style/show.css' />";
<link rel='stylesheet' type='text/css' href='style/member.css' />";
</head>
<body>
	<div class="header_wrap">
		<div id="header" class="auto">
			<div class="logo">小马论坛</div>
			<div class="nav">
				<a class="hover" href="index.php">首页</a>
			</div>
			<div class="serarch">
				<form action="serarch.php" method="get">
					<input class="keyword" type="text" name="keyword" value="<?php  if(isset($_GET['keyword'])){echo $_GET['keyword'];}?>"placeholder="搜索其实很简单" />
					<input class="submit" type="submit"  value="" />
				</form>
			</div>
			<div class="login">
				<?php 
				if($member_id) {
				    $str=<<<A
                 <a href="member.php?id={$member_id}">您好！{$_COOKIE['sfk']['name']}</a>&nbsp;&nbsp; <a href="logout.php">退出</a>
A;
				    echo $str;
				}else {
				    $str=<<<A
                <a href="login.php">登录</a>&nbsp;
				<a href="register.php">注册</a>
A;
				    echo $str;
				}
				?>
				
			</div>
		</div>
	</div>
	<div style="margin-top:55px;"></div>