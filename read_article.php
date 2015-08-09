<?php
include './includes/page-header.php';
include './includes/functions.php';
$host_url = host_url();

$database_config = require __DIR__.'/config/database.php';
require_once __DIR__.'/lib/Medoo.class.php';

$medoo = @new Medoo($database_config);
$medoo->query('set names utf8');

if (!empty($_GET['aid']))
{	
   $article = $medoo->select('article',[
                                            '[>]users' => ['user_id' => 'id']
                                       ],
                                       [                            //要关联查询的数据表与字段名称
                                            'article.id',           //left join是以A表的记录为基础的
                                            'article.title',        //左表(A)的记录将会全部表示出来
                                            'article.created_at',   //而右表(B)只会显示符合搜索条件的记录,B表记录不足的地方均为NULL
                                            'article.user_id',      //FROM `article`  LEFT JOIN `users` ON `article`.`user_id` = `users`.`id` 
                                            'article.content',      //A表:article B表:users
                                            'users.name',           //*符合的条件是：查找帖子：article.id' = $_GET['aid']
                                                                    //               关联条件：article.user_id=users.id
                                                                    // 查询的结果是：article:id,title,created_at,user_id,content
                                                                                //   users:name
                                        ],
                                        [
                                          'article.id' => $_GET['aid']
                                        ])[0];
  
} else {
	$_SESSION['errors']['state'] = 'am-alert-warning';
    $_SESSION['errors']['details'] = ['请您通过正确的方式进入读帖页面！'];
	header('Location:'.$host_url.'index.php');
	exit;
}
?>
<body>
	<?php include './includes/nav.php'; ?>
    <div class="am-g am-container php-bg-white  php-box-shadow am-padding-bottom">
        <?php include './includes/error.php';?>
    	<section name="topic">
    		<div class="am-u-lg-12 am-u-md-12 am-u-sm-12 am-margin-top-xl">
    			<section class="am-panel am-panel-primary">
					  <header class="am-panel-hd">
					  		<h3 class="am-panel-title"><?php echo $article['title']; ?></h3>
					  </header>
					  <div class="am-panel-bd">
					   		<pre class="php-text-indent"><?php echo $article['content']; ?></pre><!--按排版原样输出-->
					   		<p class="php-text-gray am-margin-xs">
					   			<a href="<?php echo $host_url; ?>user.php?uid=<?php echo $article['user_id']; ?>"><?php echo $article['name']; ?></a>
                                &nbsp;于&nbsp;<?php echo $article['created_at']; ?>&nbsp;时发布
					   		</p>
					  </div>
				</section>
    		</div>
    	</section>
    </div>
	<?php include './includes/footer.php';?>
</body>
<?php
include './includes/page-end.php';
?>