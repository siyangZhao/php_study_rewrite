<?php
    include './includes/page-header.php';
    include './includes/functions.php';
    $host_url = host_url();
    if ($_POST) 
    {
        if (isset($_SESSION['verifycode']) and ($_POST['verifycode'] == $_SESSION['verifycode']['code2']) and (time() - $_SESSION['verifycode']['time'] < 5 * 60))
        {
            $database_config = require __DIR__.'/config/database.php';
            require_once __DIR__.'/lib/Medoo.class.php';
            $medoo = @new Medoo($database_config);
            $medoo->query('set names utf8');
            $name = trim($_POST['name']);
            $password = md5(trim($_POST['password']));
            $user = $medoo->select('users','*',
                                           [
                                              'AND' => 
                                                     [
                                                        'OR' =>
                                                        [
                                                            'name' => $name,
                                                            'email' => $name,
                                                            'mobile' => $name,
                                                        ],
                                                        'password' => $password,
                                                        'level' => 'admin',
                                                     ]
                                           ]);
        
            $medoo = null;
            if (count($user)) {
                $_SESSION['user'] = $user[0];
                $_SESSION['errors']['state'] = 'am-alert-success';
                $_SESSION['errors']['details'] = ['欢迎登录后台管理页面！'];
                header("Location:{$host_url}admin.php");
                exit;
            } else {
                $_SESSION['errors']['state'] = 'am-alert-warning';
                $_SESSION['errors']['details'] = ['用户名和密码不匹配或没有权限进入管理页面'];
            }
        } else {
                $_SESSION['post']['name'] = $_POST['name'];                
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
                 <form action="<?php echo $host_url."admin_login.php"; ?>" class="am-form am-form-horizontal" method="post">
                    <div class="am-form-group am-form-icon">
                            <label for="doc-ipt-3" class="am-u-sm-2 am-form-label">用户名：</label>
                            <div class="am-u-sm-10">
                              <i class="am-icon-user php-input-icon"></i>
                              <input type="text" name="name" placeholder="用户名 / 邮箱 / 手机号" class="php-input am-radius" required>
                            </div>
                        </div>
                        <div class="am-form-group am-form-icon">
                            <label for="doc-ipt-3" class="am-u-sm-2 am-form-label">密码：</label>
                            <div class="am-u-sm-10">
                              <i class="am-icon-key php-input-icon"></i>
                              <input type="password" name="password" placeholder="密码" class="php-input am-radius" required>
                            </div>
                        </div>
                        <div class="am-form-group am-form-icon">
                            <label for="doc-ipt-3" class="am-u-sm-2 am-form-label">验证码：</label>
                            <div class="am-u-sm-10">
                                <i class="am-icon-code php-input-icon"></i>
                                <input type="text" name="verifycode" placeholder="验证码" class="php-input am-radius" required style="width:70%;display:inline-block">
                                <img id="js-get-verify-code"  src="verifycode.php" style="display:inline-block;cursor:pointer">                         
                            </div>
                        </div>              
                        <div class="am-form-group ">
                            <div class="am-u-sm-offset-2 am-u-sm-10">
                                <button type="submit" class="am-btn am-btn-primary am-radius php-button">登录</button>                                                           
                            </div>
                        </div>
                 </form>
            </div>
            <div class="am-u-md-2 am-u-lg-2 am-u-sm-2"></div>

        </div>    

        <?php include './includes/footer.php';?>
        <script type="text/javascript">
           jQuery("#js-get-verify-code").click(function(event){
                jQuery(this).attr('src', 'verifycode.php?c=' + Math.random());
           });
        </script>
</body>

<?php
include './includes/page-end.php';
?>