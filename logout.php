<?php

	include './includes/functions.php';
	$host_url = host_url(); 
	session_start();
	
	unset($_SESSION['user']);
	if(!empty($_COOKIE['username'])){  
		setcookie("username", null, time()-1);  
	}
	
	$_SESSION['errors']['state'] = 'am-alert-success';
	$_SESSION['errors']['details'] = ['退出成功！'];
	header("Location:{$host_url}index.php");
	exit;