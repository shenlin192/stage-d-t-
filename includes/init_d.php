<?php
/**
 * yikaieshop mobile首页共用文件
 * ============================================================================
 * * 版权所有 2015-2025 杭州一开网络网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.hzyikai.com;
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: $
 * $Id: index.php 17217 2016-01-19 06:29:08Z derek $
 */

if(!defined('IN_YKE')) {
    // define('IN_YKE', true);
}
if (!defined('IN_YKE'))
{
    die('Hacking attempt');
}

$this_path = str_replace('init_d.php', '', str_replace('\\', '/', __FILE__));
$replaceweb_path = 'mobile/includes/';
$addroot_path = 'mobile/';
//echo $this_path;
//获取根目录
if (!defined('ROOTWEB_PATH')) {
    define('ROOTWEB_PATH',str_replace($replaceweb_path, '', $this_path));
}
//修改与init_d.php中的ROOT_PATH一致，避免后续引用时出错，因为大量的文件中使用了ROOT_PATH，而且PC网站和mobile中的ROOT_PATH不一致，所以导致大量复制文件
//ROOT_PATH为根据模块跟目录变化而变化的文件和目录，例如缓存及个性化文件等
define('ROOT_PATH',ROOTWEB_PATH.$addroot_path);
$thislang_path =  '/';
if(!defined('ADMIN_PATH'))
{
    define('ADMIN_PATH','');
}
//定义模块PC,PCADMIN,MOBILE,MOBILEADMIN
define("MODULE",'MOBILE');//根据模块，定义cookie和缓存文件

define("NEEDLOGIN",1);//是否需要用户登录，在每个php网页文件定义
//调用公共的init_p.php文件

//调用公共的init_p.php文件
require_once(ROOTWEB_PATH. 'modules/public/includes/init_p.php');

//    xdebug_break();
?>