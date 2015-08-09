<?php
session_start();
// 建立一幅 120X40 的图像

$height = 120;
$width = 40;
$im = imagecreate($height, $width);

// 白色背景和蓝色文本
$bg = imagecolorallocate($im, 255, 255, 255);
$textcolor = imagecolorallocate($im, 0, 0, 255);
$vcodes=0;
for ($i = 0; $i < 4; $i ++) { 

	$rand =  mt_rand(0, 9);
	$vcodes = $vcodes*10+$rand;
                                                                             //随机数  //数字颜色
	imagestring($im, mt_rand(15,20), 30 * $i + mt_rand(0,9), mt_rand(10,15), $rand, imagecolorallocate($im, mt_rand(0,128), mt_rand(128,255), mt_rand(127,240)));
    //bool imagestring  ( resource $image  , int $font  , int $x  , int $y  , string $s  , int $col  )
    //用 col 颜色将字符串 s 画到 image 所代表的图像的 x，y 坐标处（这是字符串左上角坐标，整幅图像的左上角为 0，0）。如果 font 是 1，2，3，4 或 5，则使用内置字体。 
}

// 输出图像
header("Content-type: image/png");
header("Cache-Control: no-cache");//不缓存
imagepng($im);

$_SESSION['verifycode']['code2'] = $vcodes;
$_SESSION['verifycode']['time'] = time();
?>

