<?php

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

if (!defined('IN_YKE'))
{
    die('Hacking attempt');
}

define('yke_ADMIN', true);

error_reporting(E_ALL);

if (__FILE__ == '')
{
    die('Fatal error code: 0');
}

/* 初始化设置 */
@ini_set('memory_limit',          '64M');
@ini_set('session.cache_expire',  180);
@ini_set('session.use_trans_sid', 0);
@ini_set('session.use_cookies',   1);
@ini_set('session.auto_start',    0);
@ini_set('display_errors',        0);

if (DIRECTORY_SEPARATOR == '\\')
{
    @ini_set('include_path',      '.;' . ROOT_PATH);
}
else
{
    @ini_set('include_path',      '.:' . ROOT_PATH);
}


/* 取得当前ykshop所在的根目录 */
if(!defined('ADMIN_PATH_M'))
{
    define('ADMIN_PATH_M','admin');
}


//$project='yikaiweb';

define('PROJECT','professionalyikaiweb');

define('ROOT_PATH', str_replace(PROJECT . '/includes/init_d.php', '', str_replace('\\', '/', __FILE__)));
//echo ROOT_PATH."</BR>";
$this_path = str_replace('init_d.php', '', str_replace('\\', '/', __FILE__));

$replaceweb_path = PROJECT.'/includes/';
$addroot_path = PROJECT.'/';

if (!defined('ROOTWEB_PATH')) {
    define('ROOTWEB_PATH',str_replace($replaceweb_path, '', $this_path));

}

//echo  ROOTWEB_PATH."</BR>";

define('ROOTYKE_PATH',str_replace('\\','/',$_SERVER['DOCUMENT_ROOT']).'/');
//echo  ROOTYKE_PATH."</BR>";

define('ROOTYKE_WEB',str_replace(ROOTYKE_PATH,'',ROOTWEB_PATH));
//$project='dwt2';
//echo  ROOTYKE_WEB."</BR>"; //     dwt2/web/
define('ROOTYKE_WEB',str_replace(ROOTYKE_PATH,'',ROOTWEB_PATH));

$project_path=ROOTYKE_WEB.''.PROJECT."";
$project_path_js=json_encode($project_path);

//echo   $project_path."</BR>"; //     dwt2/web/

//echo ROOTWEB_PATH;
define('MODULES_PATH',ROOTWEB_PATH.'modules/');

define('PUBLIC_PATH', str_replace('/mobile', '/modules/public', ROOT_PATH));

if (defined('DEBUG_MODE') == false)
{
    define('DEBUG_MODE', 0);
}

   include(ROOTWEB_PATH.'data/config.php');


if (PHP_VERSION >= '5.1' && !empty($timezone))
{
    date_default_timezone_set($timezone);
}

if (isset($_SERVER['PHP_SELF']))
{
    define('PHP_SELF', $_SERVER['PHP_SELF']);
}
else
{
    define('PHP_SELF', $_SERVER['SCRIPT_NAME']);
}
//echo ROOTWEB_PATH;
//echo '11';
require(ROOTWEB_PATH. 'modules/public/includes/inc_constant.php');
//echo '12';
require(ROOTWEB_PATH. 'modules/public/includes/cls_ykshop.php');
//echo '13';
require(ROOTWEB_PATH. 'modules/public/includes/cls_error.php');
//echo '14';
require(ROOTWEB_PATH. 'modules/public/includes/lib_time.php');
//echo '15';
require(ROOTWEB_PATH. 'modules/public/includes/lib_base.php');
//echo '16';
require(ROOTWEB_PATH. 'modules/public/includes/lib_common.php');
//echo '17';

require(ROOT_PATH . 'ADMIN/includes/lib_main.php');
//echo '18';

require(ROOT_PATH . 'ADMIN/includes/cls_exchange.php');
//echo '19';

////echo PUBLIC_PATH."\r\n";
////echo ROOTWEB_PATH."\r\n";

/* 对用户传入的变量进行转义操作。*/
if (!get_magic_quotes_gpc())
{
    if (!empty($_GET))
    {
        $_GET  = addslashes_deep($_GET);
    }
    if (!empty($_POST))
    {
        $_POST = addslashes_deep($_POST);
    }

    $_COOKIE   = addslashes_deep($_COOKIE);
    $_REQUEST  = addslashes_deep($_REQUEST);
}


/* 对路径进行安全处理 */
if (strpos(PHP_SELF, '.php/') !== false)
{
    yk_header("Location:" . substr(PHP_SELF, 0, strpos(PHP_SELF, '.php/') + 4) . "");//\n"
    exit();
}
//echo '20';
/* 创建 ykshop 对象 */
//echo $db_host;
//echo $db_name;
//echo $prefix;
//echo $db_user;
//echo $db_pass;
$yke = new YKE($db_name, $prefix);
define('DATA_DIR', $yke->data_dir());
define('IMAGE_DIR', $yke->image_dir());

/* 初始化数据库类 */
require(ROOTWEB_PATH. 'modules/public/includes/cls_mysql.php');
//echo '21';
$db = new cls_mysql($db_host, $db_user, $db_pass, $db_name);
$db_host = $db_user = $db_pass = $db_name = NULL;
//echo '22';
include_once(ROOTWEB_PATH. 'modules/public/includes/cls_debug.php');
//weixindebug($_GET,'$_GET',0,'','','','mobile/admin/includes/init_d.php',129);
//echo '23';
if (isset($_GET['sysessid'])) {
    $sysessid = $_GET['sysessid'];
} else {
    $sysessid = '';
}
if (!isset($sysessid) || !$sysessid)
{
    $sysessid='';
    // //echo '输入公司参数不正确！';
    // die;
}
////echo 'aa3';
//require_once(ROOTWEB_PATH. 'modules/public/includes/cls_des_sysessid.php');
//echo '24';
////echo 'aa3';
//$syssess = syssessidsplit($sysessid);

////echo $syssess;

/* 创建错误处理对象 */
$err = new yk_error('message.htm');


/* 载入系统参数 */
$_CFG = load_config();
//echo '27';


if (!file_exists('../temp/caches'))
{
    @mkdir('../temp/caches', 0777);
    @chmod('../temp/caches', 0777);
}

if (!file_exists('../temp/compiled/admin'))
{
    @mkdir('../temp/compiled/admin', 0777);
    @chmod('../temp/compiled/admin', 0777);
}

clearstatcache();

/* 如果有新版本，升级 */
if (!isset($_CFG['yke_version']))
{
    $_CFG['yke_version'] = 'v2.0.5';
}

if (preg_replace('/(?:\.|\s+)[a-z]*$/i', '', $_CFG['yke_version']) != preg_replace('/(?:\.|\s+)[a-z]*$/i', '', VERSION)
        && file_exists('../upgrade/index.php'))
{
    // 转到升级文件
    yk_header("Location: ../upgrade/index.php"."?sysessid=".$GLOBALS['sysessid']."");//\n"

    exit;
}
$sysid='cheyikai';
//echo ROOTWEB_PATH;
/* 创建 Smarty 对象。*/
require(ROOTWEB_PATH.'modules/public/includes/cls_template.php');
//echo '281';
$smarty = new cls_template;
//echo '29';
$smarty->template_dir  = ROOT_PATH .PROJECT.'/templates';
$smarty->compile_dir   = ROOT_PATH . 'temp/compiled/admin';
if ((DEBUG_MODE & 2) == 2)
{
    $smarty->force_compile = true;
}

//echo '30';
$smarty->assign('lang', $_LANG);
$smarty->assign('help_open', $_CFG['help_open']);

if(isset($_CFG['enable_order_check']))  // 为了从旧版本顺利升级到2.5.0
{
    $smarty->assign('enable_order_check', $_CFG['enable_order_check']);
}
else
{
    $smarty->assign('enable_order_check', 0);
}
//echo '31';

?>
