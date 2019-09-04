<?php
    session_start();
    include_once  'inc/vocode.inc.php';
    $_SESSION['vcode']=vcode();
?>