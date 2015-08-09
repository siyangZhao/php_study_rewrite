<?php

/**
 * author: Gamelife;
 * 
 * time: 2105-08-07;
 * 
 * description: 用于发送邮件验证码;
 * 
 */
session_start();

header("Content-type:text/html;charset=utf-8");

if ($_GET) {

	$to     =   $_GET['email'];
	if (preg_match('/^[a-z0-9_\-]+(\.[_a-z0-9\-]+)*@([_a-z0-9\-]+\.)+([a-z]{2}|aero|arpa|biz|com|coop|edu|gov|info|int|jobs|mil|museum|name|nato|net|org|pro|travel)$/ ', $to)) {
		include './vendor/autoload.php';
		$config	=	include './config/email.php';

		$mailer =   new PHPMailer;
		
		$mailer->isSMTP();

		$mailer->Host 		= $config['host'];
		$mailer->SMTPAuth 	= true;
		$mailer->Username 	= $config['user'];
		$mailer->Password 	= $config['pass'];
		$mailer->Port	  	= $config['port'];

		$mailer->From	    = $config['from'];
		$mailer->FromName   = 'PHP study 论坛';

		$mailer->addAddress($to);
		$mailer->isHTML('true');

		$mailer->Subject 	= "PHP study 论坛邮箱验证码";

		$verifycode			= mt_rand(100000, pow(10, 6) - 1);
		$mailer->Body = "欢迎使用PHP study 论坛论坛，您的验证码是：{$verifycode}，十分钟之内有效";

		if ($mailer->send()) {
				$_SESSION['verifycode']['code'] = $verifycode;
				$_SESSION['verifycode']['time'] = time();
	    		echo 200; //邮件发送成功
		} else {
	    		echo 111; //邮件发送失败
		}
	} else {
		echo 333; //邮箱格式错误
	}

} else {
	echo "illegal request!";
}