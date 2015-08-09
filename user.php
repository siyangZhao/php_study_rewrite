<?php
    include './includes/page-header.php';
    include './includes/functions.php';
    $host_url = host_url();
    $database_config = require __DIR__.'/config/database.php';
    require_once __DIR__.'/lib/Medoo.class.php';



            $medoo = @new Medoo($database_config);
            $medoo->query('set names utf8');

            if(!empty($_FILES)){

                $tmpname = $_FILES['upfile']['tmp_name'];
                $filetype = strrchr($_FILES['upfile']['name'], '.');  
                $newname = time() . '_' . $_SESSION['user']['name'] . $filetype;
                $name = "./public/head_portrait/". $newname;
                move_uploaded_file($tmpname, $name) or die('文件上传失败，程序退出');
               
                $update_result = $medoo->update('users',
                    ['head_portrait' => $newname ],
                    ['name' => $_SESSION['user']['name']]
                    );
                $_SESSION['user']['head_portrait'] = $newname;
            }

?>

       <body>
        <?php include './includes/nav.php'; ?>
        <div class="am-g am-container php-bg-white  php-box-shadow am-padding-bottom">
            <?php include './includes/error.php';?>
            

            <section name="top_tips" class="am-margin-top">
                <div class="am-u-lg-4 am-u-md-4 am-u-sm-12">
                    <h1><?php   echo "用户信息 我很丑但我狠温油=。=<br>"?></h1>
                    <h2><?php   echo "用户名：" . $_SESSION['user']['name'].'<br/>';?></h2>
                    <h2><?php   echo "移动电话：" . $_SESSION['user']['mobile'].'<br/>';?></h2>
                    <h2><?php   echo "邮箱：" . $_SESSION['user']['email'].'<br/>';?></h2>
                    <h2><?php   echo "注册日期：" . $_SESSION['user']['created_at'];?></h2>
                    <h2><a class="am-btn am-btn-primary am-radius am-text-sm" href="<?php echo $host_url; ?>change_password.php">
                    修改密码
                    </a></h2>            
                </div>
                <div class="am-u-lg-4 am-u-md-4 am-u-sm-12 am-text-right">
                    
                    <figure data-am-widget="figure" class="am am-figure am-figure-default "data-am-figure="{  pureview: 'true' }">
                        <img src="<?php echo $host_url . 'public/head_portrait/' . $_SESSION['user']['head_portrait']; ?>" class="am-img-responsive" alt="" align="center"/>
                        <figcaption class="am-figure-capition-btm .am-figure-middle">用户头像</figcaption>
                    </figure>
                        
                    <div>

                        <form action="<?php echo $host_url."user.php"; ?>" method='post' enctype='multipart/form-data'>
                            <input type='file' name='upfile' />
                            <input type='submit' value='上传' style='inline' />                    
                        </form>
                    
                    </div>

                </div>            
                                 
        </div>    

        <?php include './includes/footer.php';?>
</body>

<?php
include './includes/page-end.php';
?>