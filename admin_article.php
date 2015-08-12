<?php session_start(); ?>
<!doctype html>
<html class="no-js">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>PHP study 后台管理页面</title>
  <meta name="description" content="这是一个 index 页面">
  <meta name="keywords" content="index">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <meta name="renderer" content="webkit">
  <meta http-equiv="Cache-Control" content="no-siteapp" />
  <link rel="icon" type="image/x-icon" href="./public/images/app.ico">
  <link rel="apple-touch-icon-precomposed" href="public/amaze/i/app-icon72x72@2x.png">
  <meta name="apple-mobile-web-app-title" content="Amaze UI" />
  <link rel="stylesheet" href="public/amaze/css/amazeui.min.css"/>
  <link rel="stylesheet" href="public/amaze/css/admin.css">
</head>
<?php
  include './includes/functions.php';
    $host_url = host_url();

    $database_config = require __DIR__.'/config/database.php';
    require_once __DIR__.'/lib/Medoo.class.php';

    $medoo = @new Medoo($database_config);
    $medoo->query('set names utf8');

    $article_all = $medoo -> select('article', [
                                                        "[>]users"  =>  ['article.user_id'  => 'id'],  //关联条件：article.user_id=users.id
                                                        "[>]class"  =>  ['article.class_id' => 'id'],  //          article.class_id=class.id
                                                    ],
                                                    [   'article.id',                //查询结果
                                                        'article.title',
                                                        'article.created_at',
                                                        'article.user_id',
                                                        'article.class_id',
                                                        'article.stick',
                                                        'article.excellent',
                                                        'article.pass',
                                                        'users.name(username)',      //重名，（）内为别名，用作区分
                                                        'class.name(classname)',
                                                    ],
                                                    [
                                                        
                                                        'ORDER' =>  ['id RESC'],//按时间倒叙排列
                                                        
                                                    ]
                                        );
?>
<body>
<!--[if lte IE 9]>
<p class="browsehappy">你正在使用<strong>过时</strong>的浏览器，Amaze UI 暂不支持。 请 <a href="http://browsehappy.com/" target="_blank">升级浏览器</a>
  以获得更好的体验！</p>
<![endif]-->

<header class="am-topbar admin-header">
  <div class="am-topbar-brand">
    <strong>PHP study</strong> <small>后台管理页面</small>
  </div>

  <button class="am-topbar-btn am-topbar-toggle am-btn am-btn-sm am-btn-success am-show-sm-only" data-am-collapse="{target: '#topbar-collapse'}"><span class="am-sr-only">导航切换</span> <span class="am-icon-bars"></span></button>

  <div class="am-collapse am-topbar-collapse" id="topbar-collapse">

    <ul class="am-nav am-nav-pills am-topbar-nav am-topbar-right admin-header-list">
      <li><a href="javascript:;"><span class="am-icon-envelope-o"></span> 收件箱 <span class="am-badge am-badge-warning">5</span></a></li>
      <li class="am-dropdown" data-am-dropdown>
        <a class="am-dropdown-toggle" data-am-dropdown-toggle href="javascript:;">
          <span class="am-icon-users"></span> 管理员 <span class="am-icon-caret-down"></span>
        </a>
        <ul class="am-dropdown-content">
          <li><a href="#"><span class="am-icon-user"></span> 资料</a></li>
          <li><a href="#"><span class="am-icon-cog"></span> 设置</a></li>
          <li><a href="#"><span class="am-icon-power-off"></span> 退出</a></li>
        </ul>
      </li>
      <li class="am-hide-sm-only"><a href="javascript:;" id="admin-fullscreen"><span class="am-icon-arrows-alt"></span> <span class="admin-fullText">开启全屏</span></a></li>
    </ul>
  </div>
</header>

<div class="am-cf admin-main">  
  <!-- sidebar start -->
  <div class="admin-sidebar am-offcanvas" id="admin-offcanvas">
    <div class="am-offcanvas-bar admin-offcanvas-bar">
      <ul class="am-list admin-sidebar-list">
        <li><a href="admin.php"><span class="am-icon-home"></span> 首页</a></li>
        <li class="admin-parent">
          <a class="am-cf" data-am-collapse="{target: '#collapse-nav'}"><span class="am-icon-file"></span> 页面模块 <span class="am-icon-angle-right am-fr am-margin-right"></span></a>
          <ul class="am-list am-collapse admin-sidebar-sub am-in" id="collapse-nav">
            <li><a href="admin_article.php" class="am-cf"><span class="am-icon-check"></span> 帖子管理<span class="am-icon-star am-fr am-margin-right admin-icon-yellow"></span></a></li>
            <li><a href="admin-help.html"><span class="am-icon-puzzle-piece"></span> 帮助页</a></li>
            <li><a href="admin-gallery.html"><span class="am-icon-th"></span> 相册页面<span class="am-badge am-badge-secondary am-margin-right am-fr">24</span></a></li>
            <li><a href="admin-log.html"><span class="am-icon-calendar"></span> 系统日志</a></li>
            <li><a href="admin-404.html"><span class="am-icon-bug"></span> 404</a></li>
          </ul>
        </li>
        <li><a href="admin-table.html"><span class="am-icon-table"></span> 表格</a></li>
        <li><a href="admin-form.html"><span class="am-icon-pencil-square-o"></span> 表单</a></li>
        <li><a href="#"><span class="am-icon-sign-out"></span> 注销</a></li>
      </ul>

      <div class="am-panel am-panel-default admin-sidebar-panel">
        <div class="am-panel-bd">
          <p><span class="am-icon-bookmark"></span> 公告</p>
          <p>时光静好，与君语；细水流年，与君同。—— Amaze UI</p>
        </div>
      </div>

      <div class="am-panel am-panel-default admin-sidebar-panel">
        <div class="am-panel-bd">
          <p><span class="am-icon-tag"></span> wiki</p>
          <p>Welcome to the Amaze UI wiki!</p>
        </div>
      </div>
    </div>
  </div>
  <!-- sidebar end -->

  <!-- content start -->
  <div class="admin-content">
    <?php include './includes/error.php';?>
    

    <div class="am-g">
      <div class="am-u-sm-12">
        <table class="am-table am-table-bd am-table-striped admin-content-table">
          <thead>
          <tr>
            <th>ID</th><th>发帖人</th><th>帖子</th><th>审核通过</th><th>置顶</th><th>精华</th><th>管理</th>
          </tr>
          </thead>
          <tbody>
          <?php
            foreach ($article_all as $key => $article){
            ?>
          <tr><td><?php echo $article['id']; ?></td><td><?php echo $article['username']; ?></td><td><a href="./read_article.php?aid=<?php echo $article['id']; ?>"><?php echo $article['title']; ?></a></td> <td><?php echo $article['pass']; ?></td><td><?php echo $article['stick']; ?></td><td><?php echo $article['excellent']; ?></td>
            <td>
              <div class="am-dropdown" data-am-dropdown>
                <button class="am-btn am-btn-default am-btn-xs am-dropdown-toggle" data-am-dropdown-toggle><span class="am-icon-cog"></span> <span class="am-icon-caret-down"></span></button>
                <ul class="am-dropdown-content">
                <?php 
                  if($article['excellent'] == 'false'){
                    ?>                  
                      <li><a href="./admin_article_excellent.php?aid=<?php echo $article['id']; ?>&excellent=true">1. 精华</a></li>
                    <?php
                  }else{
                    ?>
                      <li><a href="./admin_article_excellent.php?aid=<?php echo $article['id']; ?>&excellent=false">1. 取消精华</a></li>
                    <?php
                    }
                ?>
                <?php 
                  if($article['stick'] == 'false'){
                    ?>                  
                      <li><a href="./admin_article_stick.php?aid=<?php echo $article['id']; ?>&stick=true">2. 置顶</a></li>
                    <?php
                  }else{
                    ?>
                      <li><a href="./admin_article_stick.php?aid=<?php echo $article['id']; ?>&stick=false">2. 取消置顶</a></li>
                    <?php
                    }
                ?>
                <?php 
                  if($article['pass'] == 'true'){
                    ?>                  
                      <li><a href="./admin_article_pass.php?aid=<?php echo $article['id']; ?>&pass=false">3. 屏蔽</a></li>
                    <?php
                  }else{
                    ?>
                      <li><a href="./admin_article_pass.php?aid=<?php echo $article['id']; ?>&pass=true">3. 取消屏蔽</a></li>
                    <?php
                    }
                ?>
                  <li><a href="./admin_article_delete.php?aid=<?php echo $article['id']; ?>">4. 删除</a></li>
                </ul>
              </div>
            </td>
          </tr>
          <?php
          }
          ?>
          
          </tbody>
        </table>
      </div>
    </div>
  

  
      <div class="am-u-sm-12">
        <div class="am-panel am-panel-default">
          <div class="am-panel-hd am-cf" data-am-collapse="{target: '#collapse-panel-1'}">文件上传<span class="am-icon-chevron-down am-fr" ></span></div>
          <div class="am-panel-bd am-collapse am-in" id="collapse-panel-1">
            <ul class="am-list admin-content-file">
              <li>
                <strong><span class="am-icon-upload"></span> Kong-cetian.Mp3</strong>
                <p>3.3 of 5MB - 5 mins - 1MB/Sec</p>
                <div class="am-progress am-progress-striped am-progress-sm am-active">
                  <div class="am-progress-bar am-progress-bar-success" style="width: 82%">82%</div>
                </div>
              </li>
              <li>
                <strong><span class="am-icon-check"></span> 好人-cetian.Mp3</strong>
                <p>3.3 of 5MB - 5 mins - 3MB/Sec</p>
              </li>
              <li>
                <strong><span class="am-icon-check"></span> 其实都没有.Mp3</strong>
                <p>3.3 of 5MB - 5 mins - 3MB/Sec</p>
              </li>
            </ul>
          </div>
        </div>
        <div class="am-panel am-panel-default">
          <div class="am-panel-hd am-cf" data-am-collapse="{target: '#collapse-panel-2'}">浏览器统计<span class="am-icon-chevron-down am-fr" ></span></div>
          <div id="collapse-panel-2" class="am-in">
            <table class="am-table am-table-bd am-table-bdrs am-table-striped am-table-hover">
              <tbody>
              <tr>
                <th class="am-text-center">#</th>
                <th>浏览器</th>
                <th>访问量</th>
              </tr>
              <tr>
                <td class="am-text-center"><img src="public/amaze/i/examples/admin-chrome.png" alt=""></td>
                <td>Google Chrome</td>
                <td>3,005</td>
              </tr>
              <tr>
                <td class="am-text-center"><img src="public/amaze/i/examples/admin-firefox.png" alt=""></td>
                <td>Mozilla Firefox</td>
                <td>2,505</td>
              </tr>
              <tr>
                <td class="am-text-center"><img src="public/amaze/i/examples/admin-ie.png" alt=""></td>
                <td>Internet Explorer</td>
                <td>1,405</td>
              </tr>
              <tr>
                <td class="am-text-center"><img src="public/amaze/i/examples/admin-opera.png" alt=""></td>
                <td>Opera</td>
                <td>4,005</td>
              </tr>
              <tr>
                <td class="am-text-center"><img src="public/amaze/i/examples/admin-safari.png" alt=""></td>
                <td>Safari</td>
                <td>505</td>
              </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      

</div>

<a href="#" class="am-show-sm-only admin-menu" data-am-offcanvas="{target: '#admin-offcanvas'}">
  <span class="am-icon-btn am-icon-th-list"></span>
</a>

<footer class="php-footer">
  <p>© 2015 <a href="index.php" target="_blank">NEU PHP学习.</a> Licensed under <a
      href="http://opensource.org/licenses/MIT" target="_blank">MIT license</a>. by  xiaoxi.</p>
</footer>
<!--[if lt IE 9]>
<script src="http://libs.baidu.com/jquery/1.11.1/jquery.min.js"></script>
<script src="http://cdn.staticfile.org/modernizr/2.8.3/modernizr.js"></script>
<script src="public/amaze/js/amazeui.ie8polyfill.min.js"></script>
<![endif]-->

<!--[if (gte IE 9)|!(IE)]><!-->
<script src="public/amaze/js/jquery.min.js"></script>
<!--<![endif]-->
<script src="public/amaze/js/amazeui.min.js"></script>
<script src="public/amaze/js/app.js"></script>
</body>
</html>
