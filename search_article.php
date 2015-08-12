<?php
    include './includes/page-header.php';
    include './includes/functions.php';
    $host_url = host_url();

    $database_config = require __DIR__.'/config/database.php';
    require_once __DIR__.'/lib/Medoo.class.php';

    $medoo = @new Medoo($database_config);
    $medoo->query('set names utf8');

    $search = isset($_GET['search']) ? $_GET['search'] : '';
    $request_page = isset( $_GET['page'])  ? $_GET['page']  : 1;//当前所在页数
    $articlenum = 2;//每页显示帖子数

    $count = $medoo->count('article',[
                                        "[>]users"  =>  ['article.user_id'  => 'id'],
                                     ],
                                     [
                                        'article.id',
                                     ],
                                     [
                                      'AND' =>  [
                                                    'pass'  =>  'true',
                                                    'OR' => [                   
                                                                'article.title[~]' => $search,
                                                                'article.content[~]' => $search,
                                                                'users.name[~]' => $search,
                                                            ],
                                                ],
                                     ]);

    $pagenum = ceil($count / $articlenum);//向下取整，每个分类下的页数
   
  
    $search_result = $medoo -> select('article',    [
                                                        "[>]users"  =>  ['article.user_id'  => 'id'],  //关联条件：article.user_id=users.id
                                                        "[>]class"  =>  ['article.class_id' => 'id'],  //          article.class_id=class.id
                                                    ],
                                                    [   'article.id',                //查询结果
                                                        'article.title',
                                                        'article.content',
                                                        'article.created_at',
                                                        'article.user_id',
                                                        'article.class_id',
                                                        'users.name(username)',      //重名，（）内为别名，用作区分
                                                        'class.name(classname)',
                                                    ],
                                                    [  //查询条件
                                                        'AND'   => [
                                                                    'pass'  =>  'true', 
                                                                    'OR' => [                   //查询条件
                                                                                'title[~]' => $search,
                                                                                'content[~]' => $search,
                                                                                'users.name[~]' => $search,
                                                                            ],
                                                                    ],
                                                        'ORDER' =>  ['created_at DESC'],//按时间倒叙排列                   
                                                        'LIMIT' =>  [($request_page - 1) * $articlenum, $articlenum]                                    
                                                   ]
                                        );
 //var_dump($medoo->last_query());
 //var_dump($medoo->error());
// var_dump($search_result);
// die;

?>
<body>
        <?php include './includes/nav.php'; ?>
        <div class="am-g am-container php-bg-white  php-box-shadow am-padding-bottom">
            <?php include './includes/error.php';?>
            <div class="am-u-lg-4 am-u-md-4 am-u-sm-12">
            <ul class="am-list">
                <?php 
                    foreach ($search_result as $key => $search_article) {//查询筛选后的帖子
                    ?>
                        <li><a href="./read_article.php?aid=<?php echo $search_article['id']; ?>"><?php echo $search_article['title']; ?></a></li>
                                
                            <span class="php-text-gray">
                                <?php echo $search_article['username']."发表于".$search_article['created_at'];?>
                            </span>                  
                                
                    <?php                                
                        }
                ?>                        
            </ul>
            <ul class="am-pagination">
                <li class="<?php  echo $request_page - 1 <= 0 ? 'am-disabled' : '';?>"><a href="./search_article.php?search=<?php echo $search?>&page=<?php echo $request_page - 1 > 0 ? $request_page - 1 : 1;?>">&laquo;</a></li>
                        <?php 
                            for ($i = 1; $i <= $pagenum; $i++) { 
                                ?>
                                    <li class="<?php  echo return_via_para($request_page, $i, 'am-active');?>"><a href="./search_article.php?search=<?php echo $search?>&page=<?php echo $i;?>"><?php echo $i; ?></a></li>
                                <?php
                            }
                        ?>
                <li class="<?php  echo $request_page + 1 <= $pagenum ?  $request_page + 1 : 'am-disabled';?>"><a href="./search_article.php?search=<?php echo $search?>&page=<?php echo $request_page + 1 > $pagenum ? $request_page : $request_page + 1;?>">&raquo;</a></li>
            </ul>
            </div>
            <div class="am-u-md-2 am-u-lg-2 am-u-sm-2"></div>

        </div>  

        <?php include './includes/footer.php';?>
</body>

<?php
include './includes/page-end.php';
?>