<?php
//所有的页面打开共用文件,在各模块init_d.php文件中调用
if (!defined('IN_YKE')) {
    die('Hacking attempt');
}

if (__FILE__ == '') {
    die('Fatal error code: 0');
}
$this_path = str_replace('includes/init_p.php', '', str_replace('\\', '/', __FILE__));
define('PUBLIC_PATH', $this_path);//公共文件的位置,与其他文件中的root_path不一致是否存在问题？
//ROOT_PATH为根据模块跟目录变化而变化的文件和目录，例如缓存及个性化文件等
//下面的应用文件中用到ROOT_path,需要区分
//用于确定是否是测试阶段，便于在PC端测试ADMIN_TEST == '0' 时为正式使用，ADMIN_TEST == '1'为测试阶段
if (!defined('ADMIN_TEST')) {
    define('ADMIN_TEST', '1');
}
if (defined('DEBUG_MODE') == false) {
    define('DEBUG_MODE', 0);
}

//只需要有一个存在
if (!file_exists(ROOTWEB_PATH . 'data/install.lock') && !file_exists(ROOTWEB_PATH . 'includes/install.lock') && !defined('NO_CHECK_INSTALL')) {
    header('Location: ./install/index.php?sysessid=' . $GLOBALS['sysessid']);//.'\n'
    exit;
}
define('TOKEN', "yikaieshop");

//@有没有都可以，ini_set设置php.ini的值，ini_get获取php.ini的值
// echo ini_get('memory_limit');
//seeeion初始化
/* 初始化设置 */
@ini_set('memory_limit', '512M');
//  echo ini_get('memory_limit');
@ini_set('session.cache_expire', 180);
@ini_set('session.use_trans_sid', 0);
@ini_set('session.use_cookies', 1);
@ini_set('session.auto_start', 0);
@ini_set('display_errors', 1);

//获取一个配置选项的值php.ini 包含了以下的设置：echo ini_get('include_path');//11.载入配置信息,引自yikai/mobile/includes/lib_common.php
if (DIRECTORY_SEPARATOR == '\\') {
    @ini_set('include_path', '.;' . ROOT_PATH);
} else {
    @ini_set('include_path', '.:' . ROOT_PATH);
}

//缓存access_token,expire_time为上次获取access_token时计算的过期时间
$cache_access_token = array("access_token" => '', "expire_time" => 7200, "timelast" => 7000);
//int time ( void )
//返回自从 Unix 纪元（格林威治时间 1970 年 1 月 1 日 00:00:00）到当前时间的秒数。
//缓存jsapi_ticket
$cache_jsapi_ticket = array("jsapi_ticket" => '', "expire_time" => 7200, "timelast" => 7000);

//导入数据库配置文件//定义数据库变量
if (file_exists(ROOTWEB_PATH . 'data/config.php')) {
    require_once(ROOTWEB_PATH . 'data/config.php');
} else {
    include(ROOTWEB_PATH . 'includes/config.php');
}
//获取数据库表前缀
require_once(ROOTWEB_PATH.'modules/public/includes/cls_ykshop.php');
/* 创建 ykshop 对象 */
$yke = new YKE($db_name, $prefix);
define('DATA_DIR', $yke->data_dir());
define('IMAGE_DIR', $yke->image_dir());
/* 初始化数据库类 */
//连接数据库
require_once(ROOTWEB_PATH.'modules/public/includes/cls_mysql.php');//内含root_path,不影响，测试同名文件是否可以相同即可
if (!isset($db)) {
    $db = new cls_mysql($db_host, $db_user, $db_pass, $db_name);
}



/*记录不可缓存的表，*通过$yke中table方法，设置表前缀*/
require_once(ROOTWEB_PATH.'modules/public/includes/publicfuction.php');//新增共用函数
//require_once(ROOTWEB_PATH.'modules/public/includes/cls_ykshop.php');//没有root_path,测试同名文件是否可以相同即可
require_once(ROOTWEB_PATH.'modules/public/includes/cls_error.php');//没有root_path,测试同名文件是否可以相同即可
require_once(ROOTWEB_PATH.'modules/public/includes/inc_constant.php');//没有root_path,测试同名文件是否可以相同即可
require_once(ROOTWEB_PATH.'modules/public/includes/lib_article.php');//没有root_path,测试同名文件是否可以相同即可
require_once(ROOTWEB_PATH.'modules/public/includes/lib_base.php');//内含root_path,引用很多文件，需要确定同名文件是否重复，如果重复则需要修改路径
require_once(ROOTWEB_PATH.'modules/public/includes/lib_common.php');//内含root_path,引用很多文件，需要确定同名文件是否重复，如果重复则需要修改路径

if (MODULE == 'MOBILEADMIN' || MODULE=="PCADMIN" || MODULE=="APIADMIN")
{
    require_once(ROOTWEB_PATH.'modules/public/includes/lib_fuction_adminuser.php');//新增共用函数
}
require_once(ROOTWEB_PATH.'modules/public/includes/lib_fuction_article.php');//用户环境相关函数
require_once(ROOTWEB_PATH.'modules/public/includes/lib_fuction_bonus.php');//用户环境相关函数
//require_once(ROOTWEB_PATH.'modules/public/includes/lib_fuction_brand.php');//用户环境相关函数
//require_once(ROOTWEB_PATH.'modules/public/includes/lib_fuction_category.php');//用户环境相关函数
require_once(ROOTWEB_PATH.'modules/public/includes/lib_fuction_comment.php');//用户环境相关函数
require_once(ROOTWEB_PATH.'modules/public/includes/lib_fuction_evn.php');//用户环境相关函数
require_once(ROOTWEB_PATH.'modules/public/includes/lib_fuction_file.php');//用户环境相关函数
require_once(ROOTWEB_PATH.'modules/public/includes/lib_fuction_goods.php');//用户环境相关函数
require_once(ROOTWEB_PATH.'modules/public/includes/lib_fuction_html.php');//用户环境相关函数
require_once(ROOTWEB_PATH.'modules/public/includes/lib_fuction_json.php');//用户环境相关函数
require_once(ROOTWEB_PATH.'modules/public/includes/lib_fuction_message.php');//用户环境相关函数
require_once(ROOTWEB_PATH.'modules/public/includes/lib_fuction_modules.php');//用户环境相关函数
require_once(ROOTWEB_PATH.'modules/public/includes/lib_fuction_pager.php');//用户环境相关函数
require_once(ROOTWEB_PATH.'modules/public/includes/lib_fuction_pay.php');//用户环境相关函数
require_once(ROOTWEB_PATH.'modules/public/includes/lib_fuction_position.php');//咨询问题相关
require_once(ROOTWEB_PATH.'modules/public/includes/lib_fuction_question.php');//咨询问题相关
require_once(ROOTWEB_PATH.'modules/public/includes/lib_fuction_shop.php');//咨询问题相关
require_once(ROOTWEB_PATH.'modules/public/includes/lib_fuction_sql.php');//咨询问题相关
require_once(ROOTWEB_PATH.'modules/public/includes/lib_fuction_suppliers.php');//咨询问题相关
require_once(ROOTWEB_PATH.'modules/public/includes/lib_fuction_tags.php');//用户环境相关函数
require_once(ROOTWEB_PATH.'modules/public/includes/lib_fuction_template.php');//用户环境相关函数
require_once(ROOTWEB_PATH.'modules/public/includes/lib_fuction_user.php');//用户环境相关函数
require_once(ROOTWEB_PATH.'modules/public/includes/lib_fuction_vote.php');//用户环境相关函数

//require_once(ROOTWEB_PATH.'modules/public/includes/lib_main.php');//内含root_path,引用很多文件，需要确定同名文件是否重复，如果重复则需要修改路径
require_once(ROOTWEB_PATH.'modules/public/includes/lib_insert.php');//没有root_path,测试同名文件是否可以相同即可
require_once(ROOTWEB_PATH.'modules/public/includes/lib_time.php');//没有root_path,测试同名文件是否可以相同即可
require_once(ROOTWEB_PATH.'modules/public/includes/waf.php');//没有root_path,测试同名文件是否可以相同即可
require_once(ROOTWEB_PATH.'modules/public/includes/lib_goods.php');//内含root_path,引用很多文件，需要确定同名文件是否重复，如果重复则需要修改路径

//weixindebug($_GET,'$_GET',0,'','','','mobile/includes/init_d.php',156);
//获取get、post/HTTP Cookies传递的参数
if (!get_magic_quotes_gpc()) {
//注意逻辑的严密性，需要先检查是否为空，即是否传递了参数
    if (!empty($_GET)) {
        $_GET = addslashes_deep($_GET);
    }
    if (!empty($_POST)) {
        $_POST = addslashes_deep($_POST);
    }
    $_COOKIE = addslashes_deep($_COOKIE);//通过 HTTP Cookies 方式传递给当前脚本的变量的 数组
    $_REQUEST = addslashes_deep($_REQUEST);//$_REQUEST默认情况下包含了 $_GET ， $_POST 和 $_COOKIE 的 数组 。
}

require_once(ROOTWEB_PATH.'modules/public/includes/init_sysessid.php');//用户环境相关函数

/* 载入系统参数 *///必须在load_config()之前获取sysid，否则刷新参数为空，如果参数不合法，则会出错
$_CFG = load_config();

//require_once(ROOTWEB_PATH.'modules/public/includes/lib_fuction_category.php');//用户环境相关函数
//定义语言文件路径
define('LANG_PATH', ROOT_PATH . 'languages/' . $_CFG['lang'] . $thislang_path);



//D:/phpStudy/WWW/yikai/mobile/languages/zh_cn/admin/

//echo LANG_PATH;
require_once(LANG_PATH . 'common.php');//需要比较mobile中的语言文件与yikai中的是否一致//引入前台语言文件yikai\mobile\languages\zh_cn\common.php
if (file_exists(LANG_PATH . 'log_action.php')) {
    require_once(LANG_PATH . 'log_action.php');
}


require_once(ROOTWEB_PATH. 'modules/public/includes/cls_debug.php');

clearstatcache();


//需要先定义$db
////weixindebug(ROOTWEB_PATH,'ROOTWEB_PATH',0,'','','','mobile/includes/init_d.php',119);

//将数据库登录参数置空
$db_host = $db_user = $db_pass = $db_name = NULL;
//错误信息处理对象$err//设置 错误信息模板文件
$err = new yk_error('message.dwt');
if (PHP_VERSION >= '5.1' && !empty($timezone)) {
    date_default_timezone_set($timezone);
}
//获取当前网址，如果后缀为/，则加上'index.php'
$php_self = isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];


/*
if (isset($_SERVER['PHP_SELF']))
{
    define('PHP_SELF', $_SERVER['PHP_SELF']);
}
else
{
    define('PHP_SELF', $_SERVER['SCRIPT_NAME']);
}

*/

if ('/' == substr($php_self, -1)) {
    $php_self .= 'index.php?sysessid=' . $GLOBALS['sysessid'];
}
define('PHP_SELF', $php_self);
/* 对路径进行安全处理 */
if (strpos(PHP_SELF, '.php/') !== false) {
//    yk_header('Location:' . substr(PHP_SELF, 0, strpos(PHP_SELF, '.php/') + 4) . '');//.'\n'
//    exit();
}
if (isset($_SERVER['PHP_SELF'])) {
    $_SERVER['PHP_SELF'] = htmlspecialchars($_SERVER['PHP_SELF']);
}
if (file_exists(LANG_PATH . '' . basename(PHP_SELF))) {
    include_once(LANG_PATH . '' . basename(PHP_SELF));
}



//echo  $php_self;


/* 创建错误处理对象 */
$err = new yk_error('message.htm');

$db->set_disable_cache_tables(array($yke->table(1,'sessions'), $yke->table(1,'sessions_data'), $yke->table(1,'cart')));



include_once(ROOTWEB_PATH.'modules/public/includes/init_p_smarty.php');


//是都需要用户登录
include_once(ROOTWEB_PATH.'modules/public/includes/init_p_user.php');


if (isset($smarty)) {
    $smarty->assign('yke_session', $_SESSION);
}

/* 如果有新版本，升级 每个模块升级不一样 */
if (!isset($_CFG['yke_version'])) {
    $_CFG['yke_version'] = 'v2.0.5';
}

if (preg_replace('/(?:\.|\s+)[a-z]*$/i', '', $_CFG['yke_version']) != preg_replace('/(?:\.|\s+)[a-z]*$/i', '', VERSION)
    && file_exists(ROOTWEB_PATH . '/upgrade/index.php')
) {
    // 转到升级文件
    yk_header('Location:' . ROOTWEB_PATH . '/upgrade/index.php?sysessid=' . $GLOBALS['sysessid']);//.'\n'

    exit;
}

if ((DEBUG_MODE & 1) == 1) {
    // 显示所有错误
    error_reporting(E_ALL);
} else {
    error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
}

if ((DEBUG_MODE & 4) == 4) {
    include_once(ROOTWEB_PATH.'modules/public/includes/lib.debug.php');
}

//header('Cache-control: private');
header('Content-type: text/html; charset=' . EC_CHARSET);
header('Expires: Fri, 14 Mar 1980 20:53:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: no-cache, must-revalidate');
header('Pragma: no-cache');

if ($_CFG['shop_closed'] == 1) {
    //   header('Content-type: text/html;charset=' . EC_CHARSET);
    die('<div style="margin: 150px; text-align: center;font-size: 14px"><p>' . $_LANG['shop_closed'] . '</p><p>' . $_CFG['close_comment'] . '</p></div>');
}

/* 判断是否支持 Gzip 模式 */
if (!defined('INIT_NO_SMARTY') && gzip_enabled()) {
    ob_start('ob_gzhandler');
} else {
    ob_start();
}

?>