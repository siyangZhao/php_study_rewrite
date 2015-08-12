<?php
include './includes/page-header.php';
include './includes/functions.php';
$host_url = host_url();

$database_config = require __DIR__.'/config/database.php';
require_once __DIR__.'/lib/Medoo.class.php';

$medoo = @new Medoo($database_config);
$medoo->query('set names utf8');
$aid = isset($_GET['aid']) ? $_GET['aid'] : '';
if (!($aid == ''))
{	
   $article = $medoo->select('article',[
                                            '[>]users' => ['user_id' => 'id']
                                       ],
                                       [                            //要关联查询的数据表与字段名称
                                            'article.id',           //left join是以A表的记录为基础的
                                            'article.title',        //左表(A)的记录将会全部表示出来
                                            'article.created_at',   //而右表(B)只会显示符合搜索条件的记录,B表记录不足的地方均为NULL
                                            'article.user_id',      //FROM `article`  LEFT JOIN `users` ON `article`.`user_id` = `users`.`id` 
                                            'article.content',
                                            'article.stick',
                                            'article.excellent',      //A表:article B表:users
                                            'users.level',
                                            'users.name',           //*符合的条件是：查找帖子：article.id' = $_GET['aid']
                                                                    //               关联条件：article.user_id=users.id
                                                                    // 查询的结果是：article:id,title,created_at,user_id,content
                                                                                //   users:name
                                        ],
                                        [
                                          'article.id' => $_GET['aid']
                                        ])[0];
    $_SESSION['article'] = $article;
  
} else {
	$_SESSION['errors']['state'] = 'am-alert-warning';
    $_SESSION['errors']['details'] = ['请您通过正确的方式进入读帖页面！'];
	header('Location:'.$host_url.'index.php');
	exit;
}

$comment_previous = $medoo -> select('comment', [
                                                    '[>]article' => ['comment.article_id' => 'id'],
                                                    '[>]users' => ['comment.user_id' => 'id']
                                                ],
                                                [
                                                    'comment.id',
                                                    'comment.user_id',
                                                    'comment.article_id',
                                                    'comment.content',
                                                    'comment.created_at',
                                                    'users.name',
                                                ],
                                                [
                                                    'article.id' => $article['id']
                                                ]);
    
if($_POST)    
{
    if (!isset($_SESSION['user']))
    {
        $_SESSION['errors']['state'] = 'am-alert-warning';
        $_SESSION['errors']['details'] = ['请您先登录！'];
        header('Location:'.$host_url.'login.php');
        exit;
    }
        
        $comment =  isset($_POST['comment']) ? handle_illegal_string($_POST['comment']) : '';

        if ($medoo->insert('comment',['content' => $comment, 'user_id' => $_SESSION['user']['id'], 'article_id' => $article['id'],  'created_at' => date('Y-m-d H:i:s')]))
        {
            $_SESSION['errors']['state'] = 'am-alert-success';
            $_SESSION['errors']['details'] = ['评论成功啦！'];
            header('Location:'.$host_url.'read_article.php?aid='.$aid);
            exit;
        } else {

            $_SESSION['post']['comment'] = $_POST['comment'];
            $_SESSION['errors']['state'] = 'am-alert-warning';
            $_SESSION['errors']['details'] = ['Sorry,@~_~@，我们的数据库出问题啦，稍后再试'];
        }
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
					  		<h3 class="am-panel-title"><?php echo $article['title']; ?>
                                
                            </h3>
    				    </header>
					    <div class="am-panel-bd">
					   		<pre class="php-text-indent"><?php echo $article['content']; ?></pre><!--按排版原样输出-->                            
					   		<p class="php-text-gray am-margin-xs">
					   			<a href="<?php echo $host_url; ?>user.php?uid=<?php echo $article['user_id']; ?>"><?php echo $article['name']; ?></a>
                                &nbsp;于&nbsp;<?php echo $article['created_at']; ?>&nbsp;时发布                           
                                <?php
                                    if(isset($_SESSION['user'])&&($_SESSION['user']['name'] == $article['name'])){
                                    ?>
                                <div class="am-u-lg-12 am-u-md-4 am-u-sm-12 am-text-right" required style="display:inline-block">
                                    <a class="am-btn am-btn-primary am-radius am-text-sm" href="<?php echo $host_url; ?>update_article.php">
                                    <i class="am-icon-eyedropper"></i>&nbsp;修改帖子
                                    </a>
                                </div>
                                    <?php
                                    }
                                ?>
                            </p>
                            <div class="am-panel-bd am-text-left topic-body" id="topic" >                                       
                            <?php
                                if($article['stick'] == 'true'){
                                ?>                        
                                <p class="am-text-warning am-text-sm am-kai"><span class="am-icon-thumb-tack"></span>&nbsp;本帖已经被置顶！</p>
                                <?php
                                }
                            ?>
                            <?php
                                if($article['excellent'] == 'true'){
                                ?>                        
                                <p class="am-text-warning am-text-sm am-kai"><span class="am-icon-trophy"></span>&nbsp;本帖已经被置为精华帖！</p>
                                <?php
                                }
                            ?>
                            <?php
                                if(isset($_SESSION['user'])){
                                    if($_SESSION['user']['level'] == 'admin'){
                                ?>                          
                                    <a href="./admin_article_excellent.php?aid=<?php echo $article['id']; ?>&excellent=true" class="am-text-muted operation"><span class="am-icon-trophy inline-block" title="精华"></span>&nbsp;&nbsp;</a>•
                                    &nbsp;&nbsp;<a href="./admin_article_stick.php?aid=<?php echo $article['id']; ?>&stick=true" class="am-text-muted operation" ><span class="am-icon-thumb-tack inline-block" title="置顶"></span>&nbsp;&nbsp;</a>•
                                    &nbsp;&nbsp;<a href="./admin_article_delete.php?aid=<?php echo $article['id']; ?>" onclick="return confirm('您确定要删除？')"><span class="am-icon-trash inline-block" title="删除"></span>&nbsp;&nbsp;</a>
                                    
                                <?php
                                    }
                                }
                            ?>
                            </div>
                            <hr>
                            <div class="am-form-group">
                                <label for="doc-ta-1">评论：</label>
                                <?php
                                    if($comment_previous){
                                        foreach ($comment_previous as $key => $comment_article) {
                                ?>
                                <pre class="php-text-indent"><?php echo $comment_article['content']; ?></pre>
                                <p class="php-text-gray am-margin-xs">
                                <a href="<?php echo $host_url; ?>user.php?uid=<?php echo $comment_article['user_id']; ?>"><?php echo $comment_article['name']; ?></a>
                                &nbsp;于&nbsp;<?php echo $comment_article['created_at']; ?>&nbsp;时评论
                            </p>
                                <?php
                                        }
                                    }else{
                                ?>
                                <pre class="php-text-indent"><?php echo "该帖子暂无评论，快去评论吧~"; ?></pre>
                                <?php
                                    }
                                ?>
                            </div>
                            <hr>
                            <form class="am-form" method="post" action="<?php echo $host_url;?>read_article.php?aid=<?php echo $aid;?>">
                                <div class="am-form-group">
                                    <label for="doc-ta-1">回复：</label>
                                    <textarea class=" am-radius php-textarea" rows="3" name="comment"><?php echo session_read_post('comment'); ?></textarea>
                                </div>
                                <p><button type="submit" class="am-btn am-btn-primary am-radius am-text-sm">发表评论</button></p>
                            </form>                            
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