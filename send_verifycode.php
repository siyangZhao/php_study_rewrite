<?php
session_start();
header("Content-type:text/html;charset=utf8");
if ($_GET)
{
	$to = $_GET['email'];
	include "./lib/SimpleMailer.php";
    $config = include './config/email.php';

    $smailer = new SimpleMailer($config['host'], $config['port'], $config['user'], $config['pass']);
    if ($smailer)
    {
    	$subject = "PHP study 注册验证码";
    	$_SESSION['verifycode']['code'] = mt_rand(0, pow(10, 6) - 1);
    	$content = "请使用验证码:{$_SESSION['verifycode']['code']}在十分钟之内完成注册";
    	if ($smailer->send_email($config['from'], $to, $subject, $content))
    	{
    		$_SESSION['verifycode']['time'] = time();
    		echo 200; //邮件发送成功
    	} else {
    		unset($_SESSION['verifycode']);
    		$smailer->show_debug();
    		echo 111; //邮件发送失败
    	}
    } else {
    	echo 222; //初始化错误
    }
} else {
	echo 333; //没有提交邮箱
}


