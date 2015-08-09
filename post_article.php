<?php
include './includes/page-header.php';
include './includes/functions.php';
$host_url = host_url();
if (!isset($_SESSION['user']))
{
	$_SESSION['errors']['state'] = 'am-alert-warning';
    $_SESSION['errors']['details'] = ['请您先登录！'];
	header('Location:'.$host_url.'login.php');
	exit;
}
if ($_POST)
{
	$database_config = require __DIR__.'/config/database.php';
	require_once __DIR__.'/lib/Medoo.class.php';
	$medoo = @new Medoo($database_config);
	$medoo->query('set names utf8');

	$title = handle_illegal_string($_POST['title']);
	$type = $_POST['type'];
	$content =  handle_illegal_string($_POST['content']);
    $class = $medoo->select('class','*',['name'=>$type]);
    

    if(count($class))
    {
    	$_SESSION['class'] = $class[0];
    }else{
    	echo "没有这种类型";
    }

	if (strlen($content) > 10)
	{
		if ($medoo->insert('article',['title' => $title, 'content' => $content, 'user_id' => $_SESSION['user']['id'], 'class_id' => $_SESSION['class']['id'],  'created_at' => date('Y-m-d H:i:s')]))
		{
			$_SESSION['errors']['state'] = 'am-alert-success';
		    $_SESSION['errors']['details'] = ['发帖成功啦！'];
			header('Location:'.$host_url.'index.php');
			exit;
		} else {
			$_SESSION['post']['title'] = $_POST['title'];
			$_SESSION['post']['content'] = $_POST['content'];
			$_SESSION['errors']['state'] = 'am-alert-warning';
			$_SESSION['errors']['details'] = ['Sorry,@~_~@，我们的数据库出问题啦，稍后再试'];
		}

	} else {
		$_SESSION['errors']['state'] = 'am-alert-warning';
		$_SESSION['errors']['details'] = ['您的帖子内容太少'];
		$_SESSION['post']['title'] = $_POST['title'];
		$_SESSION['post']['content'] = $_POST['content'];
	}
}
?>

<body>
	<?php include './includes/nav.php'; ?>
		<div class="am-g am-container php-bg-white  php-box-shadow am-padding-bottom">
			<?php include './includes/error.php';?>
			<div class="am-u-lg-offset-1 am-u-lg-10 am-u-md-offset-1 am-u-md-10 am-u-sm-12 am-margin-top-xl">
				<form class="am-form" method="post" action="<?php echo $host_url;?>post_article.php">
					<div class="am-form-group am-form-icon">
					    <label for="title">主题：</label><!--<label>当用户选择该标签时，浏览器就会自动将焦点转到和标签相关的表单控件上
					                                           <label> 标签的 for 属性应当与相关元素的 id 属性相同-->
					    <input type="text" class="am-radius php-input" name="title" placeholder="输入帖子主题" value="<?php echo session_read_post('title');?>" required>
				    </div>
				    <div class="am-form-group am-form-icon">
				    	<label for="type">类型：</label>
				    	<select size='1' class="am-radius" name='type'>
				    		<option>游记</option>
				    		<option>微小说</option>
                            <option>影评</option>
                        </select>			   
                    </div>
	
				    <div class="am-form-group">
				        <label for="doc-ta-1">内容：</label>
				        <textarea class="am-text-sm am-radius php-textarea" rows="5" name="content"><?php echo session_read_post('content'); ?></textarea>
				    </div>
				    <p><button type="submit" class="am-btn am-btn-primary am-radius am-text-sm">发布</button></p>
				</form> 
			</div> 
			<div class="am-u-lg-1 am-u-md-1"></div>
		</div>
	<?php include './includes/footer.php';?>
	<?php 
	 if (isset($_SESSION['post']))
	 {
	 	unset($_SESSION['post']);
	 }
	?>
</body>

<?php
include './includes/page-end.php';
?>