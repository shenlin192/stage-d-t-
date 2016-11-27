<?php
if(!defined('IN_YKE')) {
    define('IN_YKE', true);
}
/**
 * ykshop 管理中心公用文件
 * ============================================================================
 * * 版权所有 2015-2025 杭州一开网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.yikaiqiche.cn；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: $
 * $Id: $.php  2015-02-07 06:29:08Z $
*/
define('yke_ADMIN', true);
/* 取得当前ykshop所在的根目录 */
$this_path = str_replace('init_d.php', '', str_replace('\\', '/', __FILE__));
$replaceweb_path = 'mobile/admin/includes/';
$addroot_path = 'mobile/';
//echo $this_path;//D:/phpStudy/WWW/yikai/mobile/admin/includes/
//获取根目录
if (!defined('ROOTWEB_PATH')) {
    define('ROOTWEB_PATH',str_replace($replaceweb_path, '', $this_path));
}
//echo ROOTWEB_PATH;//D:/phpStudy/WWW/yikai/
//修改与init_d.php中的ROOT_PATH一致，避免后续引用时出错，因为大量的文件中使用了ROOT_PATH，而且PC网站和mobile中的ROOT_PATH不一致，所以导致大量复制文件
//ROOT_PATH为根据模块跟目录变化而变化的文件和目录，例如缓存及个性化文件等
define('ROOT_PATH',ROOTWEB_PATH.$addroot_path);
$thislang_path =  '/admin/';

if(!defined('ADMIN_PATH'))
{
    define('ADMIN_PATH','admin');
}
//定义模块PC,PCADMIN,MOBILE,MOBILEADMIN
define("MODULE",'MOBILEADMIN');//根据模块，定义cookie和缓存文件

define("NEEDLOGIN",1);//是否需要用户登录，在每个php网页文件定义

//echo ROOT_PATH;//D:/phpStudy/WWW/yikai/mobile/  D:/phpStudy/WWW/yikai/mobile/

//define('ROOT_PATH', str_replace(ADMIN_PATH . '/includes/init_d.php', '', str_replace('\\', '/', __FILE__)));

//echo ROOT_PATH;//D:/phpStudy/WWW/yikai/mobile/ D:/phpStudy/WWW/yikai/mobile/
//调用公共的init_p.php文件

require_once(ROOTWEB_PATH. 'modules/public/includes/init_p.php');
require(ROOT_PATH . ADMIN_PATH . '/includes/cls_exchange.php');


?>
