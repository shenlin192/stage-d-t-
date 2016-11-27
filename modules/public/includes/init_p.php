<?php
//所有的页面打开共用文件,在各模块init_d.php文件中调用
if (!defined('IN_YKE')) {

   die('Hacking attempt');
}
if (!in_array($_SERVER['HTTP_HOST'], array('127.0.0.1', 'www.yikaiqiche.cn', 'yikaiqiche.cn', '114.55.24.185', 'localhost'))) {
    die();
}
$this_path = str_replace('includes/init_p.php', '', str_replace('\\', '/', __FILE__));
//echo $this_path;
define('PUBLIC_PATH',$this_path);//公共文件的位置,与其他文件中的root_path不一致是否存在问题？

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
if (!file_exists(ROOTWEB_PATH . 'data/install.lock') && !file_exists(ROOTWEB_PATH. 'includes/install.lock') && !defined('NO_CHECK_INSTALL')) {
    header("Location: ./install/index.php\n");
    exit;
}

define('MODULES_PATH',ROOTWEB_PATH.'modules/');//公共文件的位置,与其他文件中的root_path不一致是否存在问题？


define('TOKEN', "yikaieshop");

//@有没有都可以，ini_set设置php.ini的值，ini_get获取php.ini的值
// echo ini_get('memory_limit');
//seeeion初始化
@ini_set('memory_limit', '512M');
//  echo ini_get('memory_limit');
@ini_set('session.cache_expire', 180);
@ini_set('session.use_trans_sid', 0);
@ini_set('session.use_cookies', 1);
@ini_set('session.auto_start', 0);
@ini_set('display_errors', 1);
//不同的人使用是否会有问题
//缓存access_token,expire_time为上次获取access_token时计算的过期时间
$cache_access_token=array("access_token"=>'',"expire_time"=>7200,"timelast"=>7000);
//int time ( void )
//返回自从 Unix 纪元（格林威治时间 1970 年 1 月 1 日 00:00:00）到当前时间的秒数。
//缓存jsapi_ticket
$cache_jsapi_ticket=array("jsapi_ticket"=>'',"expire_time"=>7200,"timelast"=>7000);

//导入数据库配置文件//定义数据库变量
require_once(ROOTWEB_PATH . 'data/config.php');
//获取数据库表前缀
require_once(ROOTWEB_PATH.'modules/public/includes/cls_ykshop.php');
$yke = new YKE($db_name, $prefix);
define('DATA_DIR', $yke->data_dir());
define('IMAGE_DIR', $yke->image_dir());
//连接数据库
require_once(ROOTWEB_PATH.'modules/public/includes/cls_mysql.php');//内含root_path,不影响，测试同名文件是否可以相同即可

if (!isset($db))
{
    $db = new cls_mysql($db_host, $db_user, $db_pass, $db_name);
}

/*记录不可缓存的表，*通过$yke中table方法，设置表前缀*/

require_once(ROOTWEB_PATH.'modules/public/includes/cls_ykshop.php');//没有root_path,测试同名文件是否可以相同即可
require_once(ROOTWEB_PATH.'modules/public/includes/cls_error.php');//
require_once(ROOTWEB_PATH.'modules/public/includes/inc_constant.php');//
require_once(ROOTWEB_PATH.'modules/public/includes/lib_article.php');//
require_once(ROOTWEB_PATH.'modules/public/includes/lib_base.php');//内含root_path,引用很多文件，需要确定同名文件是否重复，如果重复则需要修改路径
require_once(ROOTWEB_PATH.'modules/public/includes/lib_common.php');//
require_once(ROOTWEB_PATH.'modules/public/includes/lib_main.php');//
require_once(ROOTWEB_PATH.'modules/public/includes/lib_fuction_bonus.php');//
require_once(MODULES_PATH . 'users/base/php/lib_user.php');//
require_once(ROOTWEB_PATH.'modules/public/includes/publicfuction.php');//
require_once(ROOTWEB_PATH.'modules/public/includes/lib_goods.php');
require_once(ROOTWEB_PATH.'modules/public/includes/lib_insert.php');//
require_once(ROOTWEB_PATH.'modules/public/includes/lib_time.php');//
require_once(ROOTWEB_PATH.'modules/public/includes/waf.php');//

//引入前台语言文件yikai\mobile\languages\zh_cn\common.php
$_CFG = load_config();

require(ROOT_PATH  . 'languages/' . $_CFG['lang'] . '/common.php');//需要比较mobile中的语言文件与yikai中的是否一致

require_once(ROOTWEB_PATH. 'modules/public/includes/cls_debug.php');
//需要先定义$db
////weixindebug(ROOTWEB_PATH,'ROOTWEB_PATH',0,'','','','mobile/includes/init_d.php',119);

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

if (isset($_GET['sysessid'])) {
    $sysessid = $_GET['sysessid'];
} else {
    $sysessid = '';
}
if (!isset($sysessid) || !$sysessid)
{
    $sysessid='';
    // echo '输入公司参数不正确！';
    // die;
}

//$sysid='cheyikai';
weixindebug($sysessid,'$sysessid',0,'','','','modules/public/includes/init_p.php:120',120);

require_once(ROOTWEB_PATH. 'modules/public/includes/cls_des_sysessid.php');

$syssess = syssessidsplit($sysessid);

weixindebug($syssess,'$syssess',0,'','','','modules/public/includes/init_p.php:127',127);

$db->set_disable_cache_tables(array($yke->table(1,'sessions'),$yke->table(1,'sessions_data'),$yke->table(1,'cart')));
//将数据库登录参数置空
$db_host = $db_user = $db_pass = $db_name = NULL;
//错误信息处理对象$err//设置 错误信息模板文件
$err = new yk_error('message.dwt');
//11.载入配置信息,引自yikai/mobile/includes/lib_common.php

//echo ini_get('include_path');

if (PHP_VERSION >= '5.1' && !empty($timezone)) {
    date_default_timezone_set($timezone);
}
//获取当前网址，如果后缀为/，则加上'index.php'
$php_self = isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
if ('/' == substr($php_self, -1)) {
    $php_self .= 'index.php';
}

//weixindebug($_SESSION,'$_SESSION',0,'','','','modules/public/includes/init_p.php:141',141);

define('PHP_SELF', $php_self);

//echo  $php_self;



// * 判断是否为搜索引擎蜘蛛
if (is_spider()) {
    if (!defined('INIT_NO_USERS')) {
        define('INIT_NO_USERS', true);
        if ($_CFG['integrate_code'] == 'ucenter') {
            $user = &init_users();
        }
    }
    $_SESSION = array();
    $_SESSION['sysid'] = '';
    $_SESSION['user_id'] = 0;
    $_SESSION['user_name'] = '';
    $_SESSION['email'] = '';
    $_SESSION['user_rank'] = 0;
    $_SESSION['discount'] = 1.00;
}
$syssess['sessionid'] = '';

if (!defined('INIT_NO_USERS')) {
    include(ROOTWEB_PATH.'modules/public/includes/cls_session.php');//内含root_path,不影响，测试同名文件是否可以相同即可
    //产生一个session_id，并保存到数据库中
    //echo $syssess['sessionid'];
//    $syssess['sessionid']='6feb36463928bc4c36e793993cdd910877894f7f';
    $sess = new cls_session($db, $yke->table(1,'sessions'), $yke->table(1,'sessions_data'), 'SESS_ID',$syssess['sessionid']);
    //获取session_id
    define('SESS_ID', $sess->get_session_id());
//    weixindebug(SESS_ID,'SESS_ID',0,'','','','modules/public/includes/init_p.php:174',174);
//    $syssess['sessionid'] = SESS_ID;
}
else
{
//    weixindebug(INIT_NO_USERS,'INIT_NO_USERS',0,'','','','modules/public/includes/init_p.php:174',174);
}

if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id']))
{
    $_SESSION['user_id']=0;
}
//weixindebug($_SESSION['user_id'],'$_SESSION[user_id]',0,'','','','modules/public/includes/init_p.php:194',194);
weixindebug($_GET,'$_GET',0,'','','','modules/public/includes/init_p.php:206',206);
weixindebug($_COOKIE,'$_COOKIE',0,'','','','modules/public/includes/init_p.php:207',207);
weixindebug($_SESSION,'$_SESSION',0,'','','','modules/public/includes/init_p.php:208',208);

$user_id = $_SESSION['user_id'];
//weixindebug($user_id ,'$user_id',0,'','','','modules/public/includes/init_p.php:194',194);
weixindebug($user_id ,'$user_id',0,'','','','modules/public/includes/init_p.php:212',212);




//获取微信登录用户
require(ROOT_PATH . 'includes/cls_wechat.php');
$wechat = new Wechat();
//对微信userid相关信息赋值
$user_id = $wechat->get_userid();//微信userid与微信公众号有关，与公司无关；openid;用户id与公司为一对一关系，建立公共用户关系
//根据用户id获取关注的公司，在表yke_users_sys中，关注或者传播中记录
weixindebug($user_id ,'$user_id',0,'','','','modules/public/includes/init_p.php:223',223);
weixindebug($syssess,'$syssess',0,'','','','modules/public/includes/init_p.php:224',224);
//!is_int($syssess['sysuserid']) || !isset($syssess['sysuserid']) ||
if (empty($syssess['sysuserid']) )
{
    $syssess['sysuserid']=0;
}
weixindebug($syssess,'$syssess',0,'','','','modules/public/includes/init_p.php:230',230);

//如果
//if (empty($GLOBALS['parent_userid']) || !$GLOBALS['parent_userid']){
//    $GLOBALS['parent_userid']=0;
//}

if ($syssess['sysmac'] == $GLOBALS['maccode']->mac_addr){
    //自己传播sysparentid保持不变
//    $syssess['sysparentid']=$syssess['sysuserid'];
}
else{
//他人传播
    $syssess['sysparentid']=$syssess['sysuserid'];
}
weixindebug($syssess,'$syssess',0,'','','','modules/public/includes/init_p.php:233',233);
//$_GET['u']
$parent_userid=$syssess['sysparentid'];
$syssess['sysuserid']=$_SESSION['user_id'];
$syssess['sessionid']='';
$syssess['sysmac']=$GLOBALS['maccode']->mac_addr;

//根据获取的用户id，获取//设置默认公司
if ($GLOBALS['user_id']){
    //没有当前公司
    users_sys_info();
}

//$sysessid='';
weixindebug($syssess,'$syssess',0,'','','','modules/public/includes/init_p.php:255',255);

$sysessid = syssessidcombination($syssess);
weixindebug($sysessid,'$sysessid',0,'','','','modules/public/includes/init_p.php:256',256);

//if (!defined(SESS_ID)){ define('SESS_ID','0');}

if (isset($_SERVER['PHP_SELF']))
{
    $_SERVER['PHP_SELF'] = htmlspecialchars($_SERVER['PHP_SELF']);
}

if (!defined('INIT_NO_SMARTY')) {
    header('Cache-control: private');
    header('Content-type: text/html; charset=' . EC_CHARSET);

    require(ROOTWEB_PATH.'modules/public/includes/cls_template.php');

    $smarty = new cls_template;
    $smarty->cache_lifetime = $_CFG['cache_time'];
    $ua = strtolower($_SERVER['HTTP_USER_AGENT']);
    $uachar = "/(nokia|sony|ericsson|mot|samsung|sgh|lg|philips|panasonic|alcatel|lenovo|cldc|midp|mobile)/i";
    if (($ua == '' || preg_match($uachar, $ua)) && !strpos(strtolower($_SERVER['REQUEST_URI']), 'wap')) {
        $smarty->template_dir = ROOT_PATH . 'themesmobile/' . $_CFG['template'];
        $smarty->cache_dir = ROOT_PATH . 'temp/caches';
        $smarty->compile_dir = ROOT_PATH . 'temp/compiled';
    } else {
        $smarty->template_dir = ROOT_PATH . 'themesmobile/' . $_CFG['template'];
        $smarty->cache_dir = ROOT_PATH . 'temp/caches';
        $smarty->compile_dir = ROOT_PATH . 'temp/compiled';
    }
    if ((DEBUG_MODE & 2) == 2) {
        $smarty->direct_output = true;
        $smarty->force_compile = true;
    } else {
        $smarty->direct_output = false;
        $smarty->force_compile = false;
    }
    $smarty->assign('lang', $_LANG);
    $smarty->assign('yke_charset', EC_CHARSET);
    if (($ua == '' || preg_match($uachar, $ua)) && !strpos(strtolower($_SERVER['REQUEST_URI']), 'wap')) {
        if (!empty($_CFG['stylename'])) {
            $smarty->assign('yke_css_path', 'themesmobile/' . $_CFG['template'] . '/style_' . $_CFG['stylename'] . '.css');
        } else {
            $smarty->assign('yke_css_path', 'themesmobile/' . $_CFG['template'] . '/style.css');
        }
    } else {
        if (!empty($_CFG['stylename'])) {
            $smarty->assign('yke_css_path', 'themesmobile/' . $_CFG['template'] . '/style_' . $_CFG['stylename'] . '.css');
        } else {
            $smarty->assign('yke_css_path', 'themesmobile/' . $_CFG['template'] . '/style.css');
        }
    }
}

if (isset($GLOBALS['parent_userid'])) {
    set_affiliate();//设置记录cookie
    $u = $GLOBALS['parent_userid'];
} else {
    $parent_userid = 0;
    $u = 0;
}

//weixindebug($_SESSION['user_id'],'$_SESSION[user_id]',0,'','','','mobile/includes/init_d.php:185',185);
if (!defined('INIT_NO_USERS')) {
    $user =& init_users();
    if (!isset($_SESSION['user_id']))
    {
        $site_name = isset($_GET['from']) ? htmlspecialchars($_GET['from']) : addslashes($_LANG['self_site']);
        $from_ad = !empty($_GET['ad_id']) ? intval($_GET['ad_id']) : 0;
        $_SESSION['from_ad'] = $from_ad;
        $_SESSION['referer'] = stripslashes($site_name);
        unset($site_name);
        if (!defined('INGORE_VISIT_STATS')) {
            visit_stats();
        }
    }
    if (empty($_SESSION['user_id'])) {
        if ($user->get_cookie()) {
            if ($_SESSION['user_id'] > 0) {
                //weixindebug($_SESSION['user_id'],$_SESSION['user_id'],0,'','','','mobile/includes/init_d.php:201',201);
            }
        } else {
            $_SESSION['user_id'] = 0;
            $_SESSION['user_name'] = '';
            $_SESSION['email'] = '';
            $_SESSION['user_rank'] = 0;
            $_SESSION['discount'] = 1.00;
            if (!isset($_SESSION['login_fail'])) {
                $_SESSION['login_fail'] = 0;
            }
        }
    }
    if (isset($GLOBALS['parent_userid'])) {
        set_affiliate();
    }
    if (!empty($_COOKIE['YKE']['user_id']) && !empty($_COOKIE['YKE']['password'])) {
        $sql = 'SELECT user_id, user_name, password ' . ' FROM ' . $yke->table(1,'users') . " WHERE user_id = '" . intval($_COOKIE['YKE']['user_id']) . "' AND password = '" . $_COOKIE['YKE']['password'] . "'";
        $row = $db->GetRow($sql);
        if (!$row) {
            $time = time() - 3600;
            setcookie("YKE[user_id]", '', $time, '/');
            setcookie("YKE[password]", '', $time, '/');
        } else {
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['user_name'] = $row['user_name'];

        }
    }
    if (isset($smarty)) {
        $smarty->assign('yke_session', $_SESSION);
    }
}
update_user_info();
//echo $GLOBALS['user_id'];
//echo $GLOBALS['_SESSION']['user_id'];
//echo $GLOBALS['_SESSION']['user_name'];
//echo 'ab';

if ((DEBUG_MODE & 1) == 1) {
    // 显示所有错误
    error_reporting(E_ALL);
} else {
    error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
}
if ((DEBUG_MODE & 4) == 4) {
    include(ROOTWEB_PATH. 'modules/publicincludes/lib.debug.php');
}


$share_info = array();
//获取来自网页的分享用户信息，注意分享用户编码为ecuid

$u = $GLOBALS['parent_userid'];
if (!empty($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT parent_id FROM " . $yke->table(1,'users') . "where user_id ='$user_id'"." and sysid ='".$GLOBALS['sysid']."' ";
    $parent_id = $GLOBALS['db']->getOne($sql);
    if (empty($parent_id)) {
        if (isset($GLOBALS['parent_userid'])) {
            if ($u == $user_id) {
                $share_info = array();
            } else {
                $sql = "SELECT * FROM " . $GLOBALS['yke']->table(1,'weixin_user') . " where ecuid ='$u'"." and sysid ='".$GLOBALS['sysid']."' ";
                $share_info = $GLOBALS['db']->getRow($sql);
            }
        }
    } else {
        $sql = "SELECT * FROM " . $GLOBALS['yke']->table(1,'weixin_user') . " where ecuid ='$parent_id'"." and sysid ='".$GLOBALS['sysid']."' ";
        $share_info = $GLOBALS['db']->getRow($sql);
    }
} else {
    if (isset($GLOBALS['parent_userid'])) {
        if ($u == $user_id) {
            $share_info = array();
        } else {
            $sql = "SELECT * FROM " . $GLOBALS['yke']->table(1,'weixin_user') . " where ecuid ='$u'" . " and sysid ='" . $GLOBALS['sysid'] . "'";
            $share_info = $GLOBALS['db']->getRow($sql);
        }
    }
}

//获取一个配置选项的值php.ini 包含了以下的设置：echo ini_get('include_path');
if (DIRECTORY_SEPARATOR == '\\') {
    @ini_set('include_path', '.;' . ROOT_PATH);
} else {
    @ini_set('include_path', '.:' . ROOT_PATH);
}

if ($_CFG['shop_closed'] == 1) {
    header('Content-type: text/html;
  charset=' . EC_CHARSET);
    die('<div style="margin: 150px; text-align: center;
  font-size: 14px"><p>' . $_LANG['shop_closed'] . '</p><p>' . $_CFG['close_comment'] . '</p></div>');
}

if (!defined('INIT_NO_SMARTY') && gzip_enabled()) {
    ob_start('ob_gzhandler');
} else {
    ob_start();
}


?>