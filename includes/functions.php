<?php
//Date:2015-06-18
//Author:gamelife
//Description:定义项目需要的函数


if (! function_exists('host_url'))
{
	/**
	 * [host_url 返回项目根目录]
	 * @return [type] [description]
	 */
	function host_url()
	{
		if (isset($_SERVER['REQUEST_SCHEME']))
		{
			//return $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/php-study-rewrite/';
			return './';
		} else {
			return 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/';
		}
	}
}

if (!function_exists('handle_user_post_string'))
	{
		/**
		 * 简单处理来自用户提交的数据
		 * @param  [type] $string [description]
		 * @return [type]         [description]
		 */
		function handle_user_post_string($string)
		{
			$string = str_ireplace('--', "\'--\'", $string);
			$string = str_ireplace('<script>', "\'<script>\'", $string);
			return htmlentities(trim($string),ENT_NOQUOTES,'UTF-8');
		} 
	}

if (!function_exists('check_user_name'))
{
	/**
	 * 检查用户注册时提交的用户名是否否合规格
	 * @param  [type] $name      [description]
	 * @param  [type] $db_handle Medoo 实例化后的对象
	 * @return [type]            [description]
	 */
	function check_user_name($name,$db_handle)
	{	
		$length = strlen($name);
		if ($length < 8 || $length > 24)
		{
			return [TRUE,'用户名长度应该在8~24之间，且不要出现html实体字符'];
		} else {
			if ($db_handle->has('users',['name' => $name]))
			{
				return [TRUE,'该用户名已经存在'];
			} else {
				return [FALSE,''];
			}
		}	 
	}
}

if (!function_exists('check_user_password'))
{
	/**
	 *  检查用户注册时提交的密码是否否合规格
	 * @param  [type] $password       [description]
	 * @param  [type] $password_again [description]
	 * @return [type]                 [description]
	 */
	function check_user_password($password,$password_again)
	{	
		if ($password_again == $password)
		{	
			$length = strlen($password);
			if ($length < 8 || $length > 24) 
			{
				return [TRUE,'密码长度应该介于8 ~ 24之间'];
			} else {
				return [FALSE,''];
			}
		} else {
		  return [TRUE,'两次密码输入不一致'];					
		}
	}
}

if (!function_exists('check_user_password_origin'))
{
	/**
	 *  检查用户注册时提交的密码是否否合规格
	 * @param  [type] $password       [description]
	 * @param  [type] $password_again [description]
	 * @return [type]                 [description]
	 */
	function check_user_password_origin($password,$password_again)
	{	
		if ($password_again == $password)
		{	
			return [FALSE,''];		
		} else {
		  return [TRUE,'密码与原密码不符'];					
		}
	}
}

if (!function_exists('check_user_mobile'))
{
	/**
	 *  检查用户注册时提交的电话号码是否否合规格
	 * @param  [type] $mobile    [description]
	 * @param  [type] $db_handle Medoo 实例化后的对象
	 * @return [type]            [description]
	 */
	function check_user_mobile($mobile,$db_handle)
	{
		if ( strlen($mobile) != 11)
		{
			 return [TRUE,'移动电话号码长度应为11位字符'];	
		} else {
			if (!preg_match('/^1\d{10}/', $mobile))
			{
				return [TRUE,'移动电话号码长度应为11位数字'];
			} else {
				if ($db_handle->has('users',['mobile' => $mobile]))
				{
					return [TRUE,'该电话号码已经被注册'];
				} else {
					return [FALSE,''];
				}
			}	
		}
	}
}

if (!function_exists('check_user_email'))
{
	/**
	 * 检查用户注册时提交的邮箱是否否合规格
	 * @param  [type] $email     [description]
	 * @param  [type] $db_handle Medoo 实例化后的对象
	 * @return [type]            [description]
	 */
	function check_user_email($email,$db_handle)
	{
		if (!preg_match('/^[a-z0-9_\-]+(\.[_a-z0-9\-]+)*@([_a-z0-9\-]+\.)+([a-z]{2}|aero|arpa|biz|com|coop|edu|gov|info|int|jobs|mil|museum|name|nato|net|org|pro|travel)$/ ', $email))
		{
			return [TRUE,'请填写正确的邮箱'];
		} else {
			if ($db_handle->has('users',['email' => $email]))
			{
				return [TRUE,'该邮箱已经被注册'];
			} else {
				return [FALSE,''];
			}
		}
	}
}

if (!function_exists('check_user_email_exist'))
{
	/**
	 * 检查用户注册时提交的邮箱是否否合规格
	 * @param  [type] $email     [description]
	 * @param  [type] $db_handle Medoo 实例化后的对象
	 * @return [type]            [description]
	 */
	function check_user_email_exist($email,$db_handle)
	{
		if (!preg_match('/^[a-z0-9_\-]+(\.[_a-z0-9\-]+)*@([_a-z0-9\-]+\.)+([a-z]{2}|aero|arpa|biz|com|coop|edu|gov|info|int|jobs|mil|museum|name|nato|net|org|pro|travel)$/ ', $email))
		{
			return [TRUE,'请填写正确的邮箱'];
		} else {
			if ($db_handle->has('users',['email' => $email]))
			{
				return [FALSE,''];
			} else {
				return [TRUE,'没有用户绑定该邮箱'];
			}
		}
	}
}

if (!function_exists('handle_illegal_string'))
{
	/**
	 * 处理不合法字符串
	 * @param  [type] $string [description]
	 * @return [type]         [description]
	 */
	function handle_illegal_string($string)
	{
		$string = str_ireplace('--', "\'--\'", $string);
		$string = str_ireplace('<script>', "\'<script>\'", $string);
		return trim($string);
	}
}

if (!function_exists('session_read_post'))
{
	function session_read_post($key)
	{
		if (isset($_SESSION['post']["$key"]))
		{
			return $_SESSION['post']["$key"];
		} else {
			return '';
		}
	}
}
if (! function_exists('return_via_para')) {
		/**
		 * 根据传入的参数是否相等返回值
		 */
		function return_via_para($com, $via, $return) {
			if ($com == $via) {
				return $return;
			} else {
				return '';
			}
		}
}