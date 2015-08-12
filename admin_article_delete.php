<?php
    include './includes/page-header.php';
    include './includes/functions.php';
    $host_url = host_url();

    $database_config = require __DIR__.'/config/database.php';
    require_once __DIR__.'/lib/Medoo.class.php';

    $medoo = @new Medoo($database_config);
    $medoo->query('set names utf8');

    $aid = isset($_GET['aid']) ? $_GET['aid'] : '';
    
	if (!($aid == '')){
    	$article_delete = $medoo -> delete('article',[
    													'article.id' => $aid
    												 ]);
    }
    if ($article_delete)
                {
                    $_SESSION['errors']['state'] = 'am-alert-success';
                    $_SESSION['errors']['details'] = ['帖子已删除！'];
                    header("Location:{$host_url}index.php");
                    exit;
                }
?>