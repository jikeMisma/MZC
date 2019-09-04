<?php

if(mb_strlen($_POST['content'])<3){
    skip($_SERVER['REQUEST_URI'], 'error', '对不起，回复内容不得浩宇三个字！');
}

?>