<?php
	include './includes/page-header.php';
	include './includes/functions.php';
	$host_url = host_url();

	if ($_POST)
	{
		if (isset($_SESSION['verifycode']) and ($_POST['verifycode'] == $_SESSION['verifycode']['code']) and (time() - $_SESSION['verifycode']['time'] < 5 * 60))
		{
			$database_config = require __DIR__.'/config/database.php';
			require_once __DIR__.'/lib/Medoo.class.php';
			$medoo = @new Medoo($database_config);//连接数据库
			$medoo->query('set names utf8');

			$email =  handle_user_post_string($_POST['email']);
			$password = trim($_POST['password']);
			$password_again = trim($_POST['password_again']);
			
			$has_error = FALSE;
			$errors = [];

			//check email
			$check_user_email_result = check_user_email_exist($email,$medoo); 
			if ($check_user_email_result[0])
			{
				$has_error = TRUE;
				array_push($errors,$check_user_email_result[1]);
			}

			//check password
			$check_user_password_result = check_user_password($password,$password_again);
			if ($check_user_password_result[0])
			{
				$has_error = TRUE;
				array_push($errors, $check_user_password_result[1]);
			}
			
			if (!$has_error)
        	{
              	$insert_result = $medoo->update('users',
                    [
                        'password' => md5($password)
                        ],
                        [
                        'email'=>$email
                    ]);
				if ($insert_result)
				{
					$_SESSION['errors']['state'] = 'am-alert-success';
					$_SESSION['errors']['details'] = ['密码重置成功！'];
					$_SESSION['user'] = $medoo->select('users','*',['id' => $insert_result])[0];
					header("Location:{$host_url}login.php");
					exit;
				} else {
					$_SESSION['errors']['state'] = 'am-alert-warning';
					$_SESSION['errors']['details'] = ['Sorry,@~_~@，我们的数据库出问题啦，稍后再试'];
				}

			} else {

				$_SESSION['post']['email'] = $_POST['email'];
				$_SESSION['errors']['state'] = 'am-alert-warning';
				$_SESSION['errors']['details'] = $errors;
			}

		} else {

				$_SESSION['post']['email'] = $_POST['email'];
				$_SESSION['errors']['state'] = 'am-alert-warning';
				$_SESSION['errors']['details'] = ['您提交的验证码有误或者您的验证码已经失效'];
		}	
		
	}
?> 
<body>
	<?php include './includes/nav.php'; ?>
		<div class="am-g am-container php-bg-white  php-box-shadow">
			<?php include './includes/error.php';?>
			<div class="am-u-lg-offset-2 am-u-md-offset-2 am-u-md-8 am-u-lg-8 am-u-sm-12  am-padding-top am-margin-top-xl ">
				 <form action="<?php echo $host_url."reset_password.php"; ?>" class="am-form am-form-horizontal"
				       method="post">
							<div class="am-form-group am-form-icon">
						    <label for="doc-ipt-3" class="am-u-sm-2 am-form-label">邮箱：</label>
						    <div class="am-u-sm-10">
						      <i class="am-icon-envelope php-input-icon"></i>
						      <input type="email" name="email" placeholder="您的常用邮箱" class="php-input am-radius" id="js-email" required value="<?php echo session_read_post('email');?>">
						    </div>
						</div>
						<div class="am-form-group am-form-icon">
						    <label for="doc-ipt-3" class="am-u-sm-2 am-form-label">密码：</label>
						    <div class="am-u-sm-10">
						      <i class="am-icon-key php-input-icon"></i>
						      <input type="password" name="password" placeholder="请输入新密码" class="php-input am-radius" required>
						    </div>
						</div>
						<div class="am-form-group am-form-icon">
						    <label for="doc-ipt-3" class="am-u-sm-2 am-form-label">确认密码：</label>
						    <div class="am-u-sm-10">
						      <i class="am-icon-key php-input-icon"></i>
						      <input type="password" name="password_again" placeholder="确认密码" class="php-input am-radius" required>
						    </div>
						</div>
						<div class="am-form-group am-form-icon">
						    <label for="doc-ipt-3" class="am-u-sm-2 am-form-label">验证码：</label>
						    <div class="am-u-sm-10">
						      <i class="am-icon-code php-input-icon"></i>
						      <input type="text" name="verifycode" placeholder="验证码" class="php-input am-radius" required style="width:76%;display:inline-block">
						      <span class="am-btn am-btn-success am-radius php-button" id="js-get-verify-code">获取验证码
						      </span><!--span用来组合文档中的行内元素-->
						    </div>
						</div>
						<div class="am-form-group ">
						    <div class="am-u-sm-offset-2 am-u-sm-10">
						      	<button type="submit" class="am-btn am-btn-primary am-radius php-button" >提交</button>
						    </div>
						</div>

				 </form>
			</div>
			<div class="am-u-md-2 am-u-lg-2 am-u-sm-2"></div>
		</div>

		<div class="am-modal am-modal-no-btn am-radius" tabindex="-1" id="my-modal">
			  <div class="am-modal-dialog am-radius">
				    <div class="am-modal-bd">
				       <span class="am-text-default">验证码发送成功！</span>
				    </div>
			  </div>
		</div>		
		<!--script在 HTML 页面中插入一段 JavaScript：-->
		<script type="text/javascript">
			jQuery(document).ready(function(){	
				jQuery("#js-get-verify-code").click(function(event){
					event.preventDefault();
					var email = jQuery("#js-email").val();
					 if (!jQuery.trim(email)) {
					 	jQuery("#my-modal").find('span').text('请您输入邮箱').end().modal('open');
					 } else {
					 	var url = './send_verifycode.php?email=' + email;
					 	jQuery.get(url,function(response,status){
					 		if (response == 200 && status == 'success') {
					 			jQuery("#my-modal").find('span').text('验证码发送成功,请去您的邮箱查看').end().modal('open');
					 		} else {
					 			jQuery("#my-modal").find('span').text('验证码发送失败').end().modal('open');
					 		}
					 	});
					 }
				});
			});
		</script>
		
	<?php include './includes/footer.php';?>
</body>
<?php
	include './includes/page-end.php';
?>