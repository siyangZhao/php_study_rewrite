<?php
	include './includes/page-header.php';
	include './includes/functions.php';
	$host_url = host_url();

	if ($_POST)// $_POST 变量用于收集来自 method="post" 的表单中的值
	{
		if (isset($_SESSION['verifycode']) and ($_POST['verifycode'] == $_SESSION['verifycode']['code']) and (time() - $_SESSION['verifycode']['time'] < 5 * 60))
		{
			$database_config = require __DIR__.'/config/database.php'; //require把后面的文件包含进来 DIR当前目录
		                   //include 和 require 语句是相同的，除了错误处理方面
        
			require_once __DIR__.'/lib/Medoo.class.php'; //require_once 只包含一次
			$medoo = @new Medoo($database_config);//连接数据库
			$medoo->query('set names utf8');//中文编码

			$name = handle_user_post_string($_POST['name']);           
		  	/*1. get是从服务器上获取数据，post是向服务器传送数据。
            2. get是把参数数据队列加到提交表单的ACTION属性所指的URL中，值和表单内各个字段一一对应，在URL中可以看到。post是通过HTTP post机制，将表单内各个字段与其内容放置在HTML HEADER内一起传送到ACTION属性所指的URL地址。用户看不到这个过程。
            3. 对于get方式，服务器端用Request.QueryString获取变量的值，对于post方式，服务器端用Request.Form获取提交的数据。
            4. get传送的数据量较小，不能大于2KB。post传送的数据量较大，一般被默认为不受限制。但理论上，IIS4中最大量为80KB，IIS5中为100KB。
            5. get安全性非常低，post安全性较高。但是执行效率却比Post方法好。 

            建议：
            1、get方式的安全性较Post方式要差些，包含机密信息的话，建议用Post数据提交方式；
            2、在做数据查询时，建议用Get方式；而在做数据添加、修改或删除时，建议用Post方式；*/                            
			$password = trim($_POST['password']);
			$password_again = trim($_POST['password_again']); //返回字符串去除首尾空白字符后的结果             
			$mobile = trim($_POST['mobile']);
			$email =  handle_user_post_string($_POST['email']);

			$has_error = FALSE;
			$errors = [];

			//check name
			$check_user_name_result = check_user_name($name,$medoo); 
			if ($check_user_name_result[0])
			{
				$has_error = TRUE;
				array_push($errors, $check_user_name_result[1]);
			}
			//check password
			$check_user_password_result = check_user_password($password,$password_again);
			if ($check_user_password_result[0])
			{
				$has_error = TRUE;
				array_push($errors, $check_user_password_result[1]);
			}
			//check mobile
			$check_user_mobile_result = check_user_mobile($mobile,$medoo);
			if ($check_user_mobile_result[0])
			{
				$has_error = TRUE;
				array_push($errors, $check_user_mobile_result[1]);
			}
			//check email
			$check_user_email_result = check_user_email($email,$medoo); 
			if ($check_user_email_result[0])
			{
				$has_error = TRUE;
				array_push($errors,$check_user_email_result[1]);
			}

			if (!$has_error)
			{
				$head_portrait_origin = 'origin_head_portrait.jpg';
				$insert_result = $medoo->insert('users',
						[
							'name' => $name,
							'password' => md5($password),
							'email' => $email,
							'mobile' => $mobile,
							'created_at' => date('Y-m-d H:i:s'),
							'head_portrait' => $head_portrait_origin
						]);
				
				

				if ($insert_result)
				{
					$_SESSION['errors']['state'] = 'am-alert-success';
					$_SESSION['errors']['details'] = ['恭喜您，注册成功！'];
					$_SESSION['user'] = $medoo->select('users','*',['id' => $insert_result])[0];
					header("Location:{$host_url}index.php");
					exit;
				} else {
					$_SESSION['errors']['state'] = 'am-alert-warning';
					$_SESSION['errors']['details'] = ['Sorry,@~_~@，我们的数据库出问题啦，稍后再试'];
				}

			} else {
				$_SESSION['post']['name'] = $_POST['name'];
				$_SESSION['post']['email'] = $_POST['email'];
				$_SESSION['post']['mobile'] = $_POST['mobile'];
				$_SESSION['errors']['state'] = 'am-alert-warning';
				$_SESSION['errors']['details'] = $errors;
			}

		} else {

				$_SESSION['post']['name'] = $_POST['name'];
				$_SESSION['post']['email'] = $_POST['email'];
				$_SESSION['post']['mobile'] = $_POST['mobile'];
				$_SESSION['errors']['state'] = 'am-alert-warning';
				$_SESSION['errors']['details'] = ['您提交的验证码有误或者您的验证码已经失效'];
		}	
		
	}
?>
<body>  <!--<div> 是一个块级元素。这意味着它的内容自动地开始一个新行。实际上，换行是 <div> 固有的唯一格式表现。可以通过 <div> 的 class 或 id 应用额外的样式-->
        <!--class 用于元素组（类似的元素，或者可以理解为某一类元素），而 id 用于标识单独的唯一的元素-->
	<?php include './includes/nav.php'; ?>
		<div class="am-g am-container php-bg-white  php-box-shadow">
			<?php include './includes/error.php';?>
			<div class="am-u-lg-offset-2 am-u-md-offset-2 am-u-md-8 am-u-lg-8 am-u-sm-12  am-padding-top am-margin-top-xl ">
				 <form action="<?php echo $host_url."regist.php"; ?>" class="am-form am-form-horizontal" method="post">
				 		<div class="am-form-group am-form-icon">
						    <label for="doc-ipt-3" class="am-u-sm-2 am-form-label">用户名：</label>
						    <div class="am-u-sm-10">
						      <i class="am-icon-user php-input-icon"></i>
						      <input type="text" name="name" placeholder="用户名 8 ~ 24位字符" class="php-input am-radius" required value="<?php echo session_read_post('name');?>">
						    </div>
						</div>
						<div class="am-form-group am-form-icon">
						    <label for="doc-ipt-3" class="am-u-sm-2 am-form-label">密码：</label>
						    <div class="am-u-sm-10">
						      <i class="am-icon-key php-input-icon"></i>
						      <input type="password" name="password" placeholder="密码 8 ~ 24 位字符" class="php-input am-radius" required><!--带有必填字段的表单-->
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
						    <label for="doc-ipt-3" class="am-u-sm-2 am-form-label">移动电话：</label>
						    <div class="am-u-sm-10">
						      <i class="am-icon-phone php-input-icon"></i>
						      <input type="text" name="mobile" placeholder="11位移动电话号码" class="php-input am-radius" required value="<?php echo session_read_post('mobile');?>">
						    </div>
						</div>
						<div class="am-form-group am-form-icon">
						    <label for="doc-ipt-3" class="am-u-sm-2 am-form-label">邮箱：</label>
						    <div class="am-u-sm-10">
						      <i class="am-icon-envelope php-input-icon"></i>
						      <input type="email" name="email" placeholder="您的常用邮箱" class="php-input am-radius" id="js-email" required value="<?php echo session_read_post('email');?>">
						    </div>
						</div>
						<div class="am-form-group am-form-icon">
						    <label for="doc-ipt-3" class="am-u-sm-2 am-form-label">验证码：</label>
						    <div class="am-u-sm-10">
						      <i class="am-icon-code php-input-icon"></i>
						      <input type="text" name="verifycode" placeholder="验证码" class="php-input am-radius" required style="width:76%;display:inline-block">
						      <span class="am-btn am-btn-success am-radius php-button" id="js-get-verify-code">获取验证码</span><!--span用来组合文档中的行内元素-->
						    </div>
						</div>
						<div class="am-form-group ">
						    <div class="am-u-sm-offset-2 am-u-sm-10">
						      	<button type="submit" class="am-btn am-btn-primary am-radius php-button" >注册</button>
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
					 	var url = './verify_mailer.php?email=' + email;
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