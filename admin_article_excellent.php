<?php
    include './includes/page-header.php';
    include './includes/functions.php';
    $host_url = host_url();

    $database_config = require __DIR__.'/config/database.php';
    require_once __DIR__.'/lib/Medoo.class.php';

    $medoo = @new Medoo($database_config);
    $medoo->query('set names utf8');

    $aid = isset($_GET['aid']) ? $_GET['aid'] : '';
    $excellent = $_GET['excellent'];
	if (!($aid == '')){
    	$article_excellent = $medoo -> update('article',[
    													'article.excellent' => $excellent,
    												],
    												[
    													'article.id' => $aid
    												]);
    }
    if ($article_excellent)
                {
                    $_SESSION['errors']['state'] = 'am-alert-success';
                    $_SESSION['errors']['details'] = ['精华操作已完成！'];
                    header("Location:{$host_url}read_article.php?aid=".$aid);
                    exit;
                }
?>