<?php
    include './includes/page-header.php';
    include './includes/functions.php';
    $host_url = host_url();

    $database_config = require __DIR__.'/config/database.php';
    require_once __DIR__.'/lib/Medoo.class.php';

    $medoo = @new Medoo($database_config);
    $medoo->query('set names utf8');

    $aid = isset($_GET['aid']) ? $_GET['aid'] : '';
    $pass = $_GET['pass'];
	if (!($aid == '')){
    	$article_pass = $medoo -> update('article',[
    													'article.pass' => $pass,
    												],
    												[
    													'article.id' => $aid
    												]);
    }
    if ($article_pass)
                {
                    $_SESSION['errors']['state'] = 'am-alert-success';
                    $_SESSION['errors']['details'] = ['屏蔽操作已完成！'];
                    header("Location:{$host_url}admin_article.php");
                    exit;
                }
?>