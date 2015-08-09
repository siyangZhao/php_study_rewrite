-- phpMyAdmin SQL Dump
-- version phpStudy 2014
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2015 �?06 �?20 �?22:48
-- 服务器版本: 5.5.40
-- PHP 版本: 5.5.17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `phpstudy`
--

-- --------------------------------------------------------

--
-- 表的结构 `articles`
--

CREATE TABLE IF NOT EXISTS `articles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `title` (`title`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- 转存表中的数据 `articles`
--

INSERT INTO `articles` (`id`, `title`, `content`, `user_id`, `created_at`) VALUES
(1, 'Smarty模板', 'Smarty是一个php模板引擎。更准确的说,它分开了逻辑程序和外在的内容,提供了一种易于管理的方法。可以描述为应用程序员和美工扮演了不同的角色,因为在大多数情况下 ,他们不可能是同一个人。例如,你正在创建一个用于浏览新闻的网页,新闻标题,标签栏,作者和内容等都是内容要素,他们并不包含应该怎样去呈现。在Smarty的程序里,这些被忽略了。模板设计者们编辑模板,组合使用html标签和模板标签去格式化这些要素的输出(html表格,背景色,字体大小,样式表,等等)。有一天程序员想要改变文章检索的方式(也就是程序逻辑的改变)。这个改变不影响模板设计者,内容仍将准确的输出到模板。同样的,哪天美工吃多了想要完全重做界面,也不会影响到程序逻辑。因此,程序员可以改变逻辑而不需要重新构建模板,模板设计者可以改变模板而不影响到逻辑。 现在简短的说一下什么是smarty不做的。smarty不尝试将逻辑完全和模板分开。如果逻辑程序严格的用于页面表现,那么它在模板里不会出现问题。有个建议:让应用程序逻辑远离模板, 页面表现逻辑远离应用程序逻辑。这将在以后使内容更容易管理,程序更容易升级。<br>&nbsp;&nbsp;&nbsp;&nbsp;Smarty的特点之一是"模板编译"。意思是Smarty读取模板文件然后用他们创建php脚本。这些脚本创建以后将被执行。<br>&nbsp;&nbsp;&nbsp;&nbsp;因此并没有花费模板文件的语法解析,同时每个模板可以享受到诸如Zend加速器(http://www.zend.com) 或者PHP加速器(http://www.php-accelerator.co.uk)。这样的php编译器高速缓存解决方案。', 3, '2015-06-20 07:19:23'),
(2, 'Laravel php开发框架', 'Laravel是一套简洁、优雅的PHP Web开发框架(PHP Web Framework)。它可以让你从面条一样杂乱的代码中解脱出来；它可以帮你构建一个完美的网络APP，而且每行代码都可以简洁、富于表达力。', 3, '2015-06-20 21:56:40'),
(3, 'Composer  php包管理器', 'Composer 是 PHP 的一个依赖管理工具。它允许你申明项目所依赖的代码库，它会在你的项目中为你安装他们。\r\nComposer 不是一个包管理器。是的，它涉及 "packages" 和 "libraries"，但它在每个项目的基础上进行管理，在你项目的某个目录中（例如 vendor）进行安装。默认情况下它不会在全局安装任何东西。因此，这仅仅是一个依赖管理。\r\n\r\n这种想法并不新鲜，Composer 受到了 node''s npm 和 ruby''s bundler 的强烈启发。而当时 PHP 下并没有类似的工具。\r\n\r\nComposer 将这样为你解决问题：\r\n\r\na) 你有一个项目依赖于若干个库。\r\n\r\nb) 其中一些库依赖于其他库。\r\n\r\nc) 你声明你所依赖的东西。\r\n\r\nd) Composer 会找出哪个版本的包需要安装，并安装它们（将它们下载到你的项目中）。\r\n\r\ncomposer中文网中文网：<a href="http://docs.phpcomposer.com/">composer中文网</a>', 3, '2015-06-20 22:32:01'),
(4, 'Git 分布式版本控制系统', 'Git是一款免费、开源的分布式版本控制系统，用于敏捷高效地处理任何或小或大的项目。[1] Git的读音为/gɪt/。\r\nGit是一个开源的分布式版本控制系统，用以有效、高速的处理从很小到非常大的项目版本管理。[2] Git 是 Linus Torvalds 为了帮助管理 Linux 内核开发而开发的一个开放源码的版本控制软件。\r\nTorvalds 开始着手开发 Git 是为了作为一种过渡方案来替代 BitKeeper，后者之前一直是 Linux 内核开发人员在全球使用的主要源代码工具。开放源码社区中的有些人觉得 BitKeeper 的许可证并不适合开放源码社区的工作，因此 Torvalds 决定着手研究许可证更为灵活的版本控制系统。尽管最初 Git 的开发是为了辅助 Linux 内核开发的过程，但是我们已经发现在很多其他自由软件项目中也使用了 Git。例如 最近就迁移到 Git 上来了，很多 Freedesktop 的项目也迁移到了 Git 上。', 3, '2015-06-20 22:33:10'),
(5, 'Bootstrap，Amaze UI，ZUI 前端开发框架', 'Bootstrap，来自 Twitter，是目前最受欢迎的前端框架。Bootstrap 是基于 HTML、CSS、JAVASCRIPT 的，它简洁灵活，使得 Web 开发更加快捷。[1] 它由Twitter的设计师Mark Otto和Jacob Thornton合作开发，是一个CSS/HTML框架。Bootstrap提供了优雅的HTML和CSS规范，它即是由动态CSS语言Less写成。Bootstrap一经推出后颇受欢迎，一直是GitHub上的热门开源项目，包括NASA的MSNBC（微软全国广播公司）的Breaking News都使用了该项目。[2] 国内一些移动开发者较为熟悉的框架，如WeX5前端开源框架等，也是基于Bootsrap源码进行性能优化而来', 3, '2015-06-20 22:34:43');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
