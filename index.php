<?php
    include './includes/page-header.php';
    include './includes/functions.php';
    $host_url = host_url();

    $database_config = require __DIR__.'/config/database.php';
    require_once __DIR__.'/lib/Medoo.class.php';

    $medoo = @new Medoo($database_config);
    $medoo->query('set names utf8');

    $class_id = isset($_GET['class_id']) ? $_GET['class_id'] : 0;//分类的id
    $request_page = isset( $_GET['page'])  ? $_GET['page']  : 1;//当前所在页数
    $articlenum = 2;//每页显示帖子数

    $count = $medoo->count('article',[
                                      'AND' =>  [
                                                    'pass'  =>  'true',
                                                    'article.class_id' => $class_id,
                                                ],
                                     ]);//该分类下帖子总数

    $pagenum = ceil($count / $articlenum);//向下取整，每个分类下的页数


    $classes = $medoo->select('class', '*');//class所有信息

    $article_all = $medoo -> select('article', [
                                                        "[>]users"  =>  ['article.user_id'  => 'id'],  //关联条件：article.user_id=users.id
                                                        "[>]class"  =>  ['article.class_id' => 'id'],  //          article.class_id=class.id
                                                    ],
                                                    [   'article.id',                //查询结果
                                                        'article.title',
                                                        'article.created_at',
                                                        'article.user_id',
                                                        'article.class_id',
                                                        'users.name(username)',      //重名，（）内为别名，用作区分
                                                        'class.name(classname)',
                                                    ],
                                                    [
                                                        'AND'   => [
                                                                    'pass'  =>  'true',                    //查询条件
                                                                    'article.class_id' => $class_id,
                                                                    ],
                                                        'ORDER' =>  ['created_at DESC'],//按时间倒叙排列
                                                        'LIMIT' =>  [($request_page - 1) * $articlenum, $articlenum]
                                                        //LIMIT => [m,n]  m:从第几个数据开始读取 n:读取多少个数据，从第m+1条开始，取n条
                                                    ]
                                        );
    
// var_dump($medoo->last_query());
// var_dump($article_all);
// die;

?>

<body>
        <?php include './includes/nav.php'; ?>
        <div class="am-g am-container php-bg-white  php-box-shadow am-padding-bottom">
            <?php include './includes/error.php';?>
        	
            <!--  <hr data-am-widget="divider" style="" class="am-divider am-divider-default"/> -->
            <section name="top_tips" class="am-margin-top">
                <div class="am-u-lg-4 am-u-md-4 am-u-sm-12">
                    <p class="am-text-sm am-margin-top-xs">时间：<?php echo date('Y-m-d'); ?></p>
                </div>
                <div class="am-u-lg-4 am-u-md-4 am-u-sm-12 am-text-right">
                    <a class="am-btn am-btn-primary am-radius am-text-sm" href="<?php echo $host_url; ?>post_article.php">
                    <i class="am-icon-eyedropper"></i>&nbsp;快去发帖
                    </a>
                </div> 
            </section>
            <section name="read_article">
                <div class="am-u-lg-4 am-u-md-4 am-u-sm-12">                        
                    <ol class="am-breadcrumb"><!--<ol>有序html列表-->
                        <?php         
                          foreach ($classes as $key => $class) { //当前classes的值赋给class，分类栏
                              if ($class_id == $class['id']) {
                                        ?>
                                        <li class="am-active"><?php  echo $class['name'];?></li>
                                    <?php
                                } else {
                                    ?>
                                        <li><a href="./index.php?class_id=<?php echo $class['id'];?>"><?php  echo $class['name'];?></a></li>
                                        <?php                                        
                                    }
                            }
                        ?>
                    </ol>
                    <ul class="am-list">
                        <?php 
                            foreach ($article_all as $key => $article) {//当前分类下的帖子
                            ?>
                                <li><a href="./read_article.php?aid=<?php echo $article['id']; ?>"><?php echo $article['title']; ?></a></li>
                                
                                    <span class="php-text-gray">
                                        <?php echo $article['username']."发表于".$article['created_at'];?>
                                    </span>                  
                                
                            <?php                                
                            }
                        ?>                        
                    </ul>                    
                    <ul class="am-pagination">
                      <li class="<?php  echo $request_page - 1 <= 0 ? 'am-disabled' : '';?>"><a href="./index.php?class_id=<?php echo $class_id; ?>&page=<?php echo $request_page - 1 > 0 ? $request_page - 1 : 1;?>">&laquo;</a></li>
                        <?php 
                            for ($i = 1; $i <= $pagenum; $i++) { 
                                ?>
                                    <li class="<?php  echo return_via_para($request_page, $i, 'am-active');?>"><a href="./index.php?class_id=<?php echo $class_id; ?>&page=<?php echo $i;?>"><?php echo $i; ?></a></li>
                                <?php
                            }
                        ?>
                      <li class="<?php  echo $request_page + 1 <= $pagenum ?  $request_page + 1 : 'am-disabled';?>"><a href="./index.php?class_id=<?php echo $class_id; ?>&page=<?php echo $request_page + 1 > $pagenum ? $request_page : $request_page + 1;?>">&raquo;</a></li>
                    </ul>
               
            </section>
           
            <div class="am-u-md-2 am-u-lg-2 am-u-sm-2"></div>

        </div>  

        <?php include './includes/footer.php';?>
</body>

<?php
include './includes/page-end.php';
?>