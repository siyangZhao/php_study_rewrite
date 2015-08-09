<?php
    include './includes/page-header.php';
    include './includes/functions.php';
    $host_url = host_url();
    if ($_POST)
    {
        $database_config = require __DIR__.'/config/database.php';
        require_once __DIR__.'/lib/Medoo.class.php';
        $medoo = @new Medoo($database_config);//连接数据库
        $medoo->query('set names utf8');

        $password_origin = trim($_POST['password_origin']);
        $password_new = trim($_POST['password_new']);
        $password_again = trim($_POST['password_again']);
        
        $has_error = FALSE;
        $errors = [];

        //check origin password
        $password = $_SESSION['user']['password'];
        $password_origin = md5($password_origin);
        $check_user_password_result1 = check_user_password_origin($password_origin,$password);
        if ($check_user_password_result1[0])
        {
            $has_error = TRUE;
            array_push($errors, $check_user_password_result1[1]);
        }

        //check password_new
        $check_user_password_result2 = check_user_password($password_new,$password_again);
        if ($check_user_password_result2[0])
        {
            $has_error = TRUE;
            array_push($errors, $check_user_password_result2[1]);
        }

        if (!$has_error)
        {
            $insert_result = $medoo->update('users',
                [
                    'password' => md5($password_new)
                    ],
                    [
                    'name'=>$_SESSION['user']['name']
                ]);
                if ($insert_result)
                {
                    $_SESSION['errors']['state'] = 'am-alert-success';
                    $_SESSION['errors']['details'] = ['密码修改成功！'];
                    $_SESSION['user'] = $medoo->select('users','*',['id' => $insert_result])[0];
                    header("Location:{$host_url}login.php");
                    exit;
                } else {
                    $_SESSION['errors']['state'] = 'am-alert-warning';
                    $_SESSION['errors']['details'] = ['Sorry,@~_~@，我们的数据库出问题啦，稍后再试'];
                }

        } else {

                $_SESSION['errors']['state'] = 'am-alert-warning';
                $_SESSION['errors']['details'] = $errors;
            }           
        
    }
?> 
<body>
    <?php include './includes/nav.php'; ?>
        <div class="am-g am-container php-bg-white  php-box-shadow">
            <?php include './includes/error.php';?>
            <div class="am-u-lg-offset-2 am-u-md-offset-2 am-u-md-8 am-u-lg-8 am-u-sm-12  am-padding-top am-margin-top-xl ">
                 <form action="<?php echo $host_url."change_password.php"; ?>" class="am-form am-form-horizontal" method="post">
                        <div class="am-form-group am-form-icon">
                            <label for="doc-ipt-3" class="am-u-sm-2 am-form-label">原始密码：</label>
                            <div class="am-u-sm-10">
                              <i class="am-icon-key php-input-icon"></i>
                              <input type="password" name="password_origin" placeholder="请输入原始密码" class="php-input am-radius" required>
                            </div>
                        </div>
                        <div class="am-form-group am-form-icon">
                            <label for="doc-ipt-3" class="am-u-sm-2 am-form-label">新密码：</label>
                            <div class="am-u-sm-10">
                              <i class="am-icon-key php-input-icon"></i>
                              <input type="password" name="password_new" placeholder="密码 8 ~ 24 位字符" class="php-input am-radius" required>
                            </div>
                        </div>
                        <div class="am-form-group am-form-icon">
                            <label for="doc-ipt-3" class="am-u-sm-2 am-form-label">确认密码：</label>
                            <div class="am-u-sm-10">
                              <i class="am-icon-key php-input-icon"></i>
                              <input type="password" name="password_again" placeholder="确认密码" class="php-input am-radius" required>
                            </div>
                        </div>
                        <div class="am-form-group ">
                            <div class="am-u-sm-offset-2 am-u-sm-10">
                                <button type="submit" class="am-btn am-btn-primary am-radius php-button" >确认修改</button>
                            </div>
                        </div>
                    </form>
            </div>
            <div class="am-u-md-2 am-u-lg-2 am-u-sm-2"></div>
        </div>
        
    <?php include './includes/footer.php';?>
</body>
<?php
    include './includes/page-end.php';
?>




