<?php
    include './includes/page-header.php';
    include './includes/functions.php';
    $host_url = host_url();

    $database_config = require __DIR__.'/config/database.php';
    require_once __DIR__.'/lib/Medoo.class.php';

    $medoo = @new Medoo($database_config);
    $medoo->query('set names utf8');

    $aid = isset($_GET['aid']) ? $_GET['aid'] : '';
    $stick = $_GET['stick'];
	if (!($aid == '')){
    	$article_stick = $medoo -> update('article',[
    													'article.stick' => $stick,
    												],
    												[
    													'article.id' => $aid
    												]);
    }
    if ($article_stick)
                {
                    $_SESSION['errors']['state'] = 'am-alert-success';
                    $_SESSION['errors']['details'] = ['置顶操作已完成！'];
                    header("Location:{$host_url}read_article.php?aid=".$aid);
                    exit;
                }
?>