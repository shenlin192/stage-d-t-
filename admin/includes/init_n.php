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

if (file_exists('../../data/config.php'))
{
    include('../../data/config.php');
}
else
{
    include('../../includes/config.php');
}

/* 取得当前ykshop所在的根目录 */
if(!defined('ADMIN_PATH_M'))
{
    define('ADMIN_PATH_M','admin');
}
define('ROOT_PATH', str_replace(ADMIN_PATH_M . '/includes/init_d.php', '', str_replace('\\', '/', __FILE__)));

if (defined('DEBUG_MODE') == false)
{
    define('DEBUG_MODE', 0);
}

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
if (!defined('WEB_PATH'))
{
    define('WEB_PATH', str_replace( 'mobile/admin/includes','',str_replace('\\', '/', dirname(__FILE__)) ));
}
//echo WEB_PATH;//D:/phpStudy/WWW/yikai//includes
//return;

require(WEB_PATH. 'modules/public/includes/cls_debug.php');

require(WEB_PATH. 'modules/public/includes/inc_constant.php');

require(WEB_PATH. 'modules/public/includes/cls_ykshop.php');



require(WEB_PATH. 'modules/public/includes/cls_error.php');
require(WEB_PATH. 'modules/public/includes/lib_time.php');
require(WEB_PATH. 'modules/public/includes/lib_base.php');

require(ROOT_PATH . ADMIN_PATH_M . '/includes/lib_common.php');

require(ROOT_PATH . ADMIN_PATH_M . '/includes/lib_main.php');


require(ROOT_PATH . ADMIN_PATH_M . '/includes/cls_exchange.php');


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
    yk_header('Location:' . substr(PHP_SELF, 0, strpos(PHP_SELF, '.php/') + 4) . '\n');
    exit();
}

/* 创建 ykshop 对象 */
$yke = new YKE($db_name, $prefix);
define('DATA_DIR', $yke->data_dir());
define('IMAGE_DIR', $yke->image_dir());

/* 初始化数据库类 */
require(WEB_PATH. 'modules/public/includes/cls_mysql.php');
$db = new cls_mysql($db_host, $db_user, $db_pass, $db_name);
$db_host = $db_user = $db_pass = $db_name = NULL;

echo '11qq';
return;
//weixindebug($_GET,'$_GET',0,'','','','mobile/admin/includes/init_d.php',129);
if (isset($_GET['sysid']))
{
    $sysid=$_GET['sysid'];
}
//weixindebug($sysid,'$sysid',0,'','','','mobile/admin/includes/init_d.php',131);

if (!isset($sysid) || !$sysid)
{
     $sysid='cheyikai';
    // echo '输入公司参数不正确！';
    // die;
}

/* 创建错误处理对象 */
$err = new yk_error('message.htm');

/* 初始化session */
require(WEB_PATH. 'modules/public/includes/cls_session.php');
$sess = new cls_session($db, $yke->table(1,'sessions'), $yke->table(1,'sessions_data'), 'YKECP_ID');
/* 初始化 action */
if (!isset($_REQUEST['act']))
{
    $_REQUEST['act'] = '';
}
elseif (($_REQUEST['act'] == 'login' || $_REQUEST['act'] == 'logout' || $_REQUEST['act'] == 'signin') &&
    strpos(PHP_SELF, '/privilege.php') === false)
{
    $_REQUEST['act'] = '';
}
elseif (($_REQUEST['act'] == 'forget_pwd' || $_REQUEST['act'] == 'reset_pwd' || $_REQUEST['act'] == 'get_pwd') &&
    strpos(PHP_SELF, '/get_password.php') === false)
{
    $_REQUEST['act'] = '';
}

/* 载入系统参数 */
$_CFG = load_config();

// TODO : 登录部分准备拿出去做，到时候把以下操作一起挪过去
if ($_REQUEST['act'] == 'captcha')
{
    include_once(ROOTWEB_PATH.'modules/public/includes/cls_captcha.php');

    $img = new captcha('../data/captcha/');
    @ob_end_clean(); //清除之前出现的多余输入
    $img->generate_image();

    exit;
}

require(ROOT_PATH . 'languages/' .$_CFG['lang']. '/admin/common.php');
require(ROOT_PATH . 'languages/' .$_CFG['lang']. '/admin/log_action.php');

if (file_exists(ROOT_PATH . 'languages/' . $_CFG['lang'] . '/admin/' . basename(PHP_SELF)))
{
    include(ROOT_PATH . 'languages/' . $_CFG['lang'] . '/admin/' . basename(PHP_SELF));
}

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
    yk_header('Location: ../upgrade/index.php?sysessid='.$GLOBALS['sysessid'].'\n');

    exit;
}

/* 创建 Smarty 对象。*/
require(WEB_PATH. 'modules/public/includes/cls_template.php');
$smarty = new cls_template;

echo '112';

$smarty->template_dir  = ROOT_PATH . ADMIN_PATH_M . '/templates';

echo $smarty->template_dir;

$smarty->compile_dir   = ROOT_PATH . 'temp/compiled/admin';
if ((DEBUG_MODE & 2) == 2)
{
    $smarty->force_compile = true;
}


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

/* 验证通行证信息 */
if(isset($_GET['ent_id']) && isset($_GET['ent_ac']) &&  isset($_GET['ent_sign']) && isset($_GET['ent_email']))
{
    $ent_id = trim($_GET['ent_id']);
    $ent_ac = trim($_GET['ent_ac']);
    $ent_sign = trim($_GET['ent_sign']);
    $ent_email = trim($_GET['ent_email']);
    $certificate_id = trim($_CFG['certificate_id']);
    $domain_url = $yke->url();
    $token=$_GET['token'];
    if($token==md5(md5($_CFG['token']).$domain_url.ADMIN_PATH_M))
    {
        require(ROOT_PATH . 'includes/cls_transport.php');
        $t = new transport('-1',5);
        $apiget = "act=ent_sign&ent_id= $ent_id & certificate_id=$certificate_id";

        $t->request('http://cloud.ykshop.com/api.php', $apiget);
        $db->query('UPDATE '.$yke->table(1,'ykesmart_shop_config',1) . ' SET value = "'. $ent_id .'" WHERE code = "ent_id"' . " and sysid ='".$GLOBALS['sysid']."'"  );
        $db->query('UPDATE '.$yke->table(1,'ykesmart_shop_config',1) . ' SET value = "'. $ent_ac .'" WHERE code = "ent_ac"' ." and sysid ='".$GLOBALS['sysid']."'"  );
        $db->query('UPDATE '.$yke->table(1,'ykesmart_shop_config',1) . ' SET value = "'. $ent_sign .'" WHERE code = "ent_sign"' ."  and sysid ='".$GLOBALS['sysid']."'"  );
        $db->query('UPDATE '.$yke->table(1,'ykesmart_shop_config',1) . ' SET value = "'. $ent_email .'" WHERE code = "ent_email"' ." and sysid ='".$GLOBALS['sysid']."'"  );
        clear_cache_files();
        yk_header('Location: ./index.php?sysessid='.$GLOBALS['sysessid'].'\n');
    }
}

/* 验证管理员身份 */
if ((!isset($_SESSION['admin_id']) || intval($_SESSION['admin_id']) <= 0) &&
    $_REQUEST['act'] != 'login' && $_REQUEST['act'] != 'signin' &&
    $_REQUEST['act'] != 'forget_pwd' && $_REQUEST['act'] != 'reset_pwd' && $_REQUEST['act'] != 'check_order')
{
    /* session 不存在，检查cookie */
    if (!empty($_COOKIE['YKECP']['sysid']) && (!empty($_COOKIE['YKECP']['admin_id']) && !empty($_COOKIE['YKECP']['admin_pass'])))
    {
        // 找到了cookie, 验证cookie信息
        $sql = 'SELECT user_id, user_name, password, action_list, last_login ' .
                ' FROM ' .$yke->table(1,'admin_user') .
                " WHERE user_id = '" . intval($_COOKIE['YKECP']['admin_id']) . "'  and sysid ='".$GLOBALS['sysid']."'"  ;
        $row = $db->GetRow($sql);

        if (!$row)
        {
            // 没有找到这个记录
            setcookie($_COOKIE['YKECP']['sysid'],   '', 1);
            setcookie($_COOKIE['YKECP']['admin_id'],   '', 1);
            setcookie($_COOKIE['YKECP']['admin_pass'], '', 1);

            if (!empty($_REQUEST['is_ajax']))
            {
                make_json_error($_LANG['priv_error']);
            }
            else
            {
                yk_header('Location: privilege.php?sysessid='.$GLOBALS['sysessid'].'&act=login\n');
            }

            exit;
        }
        else
        {
            // 检查密码是否正确
            if (md5($row['password'] . $_CFG['hash_code']) == $_COOKIE['YKECP']['admin_pass'])
            {
                !isset($row['last_time']) && $row['last_time'] = '';
                set_admin_session($row['user_id'], $row['user_name'], $row['action_list'], $row['last_time']);

                // 更新最后登录时间和IP
                $db->query('UPDATE ' . $yke->table(1,'admin_user') .
                            " SET last_login = '" . gmtime() . "', last_ip = '" . real_ip() . "'" .
                            " WHERE user_id = '" . $_SESSION['admin_id'] . "'  and sysid ='".$GLOBALS['sysid']."'" );
            }
            else
            {
                setcookie($_COOKIE['YKECP']['sysid'],   '', 1);
                setcookie($_COOKIE['YKECP']['admin_id'],   '', 1);
                setcookie($_COOKIE['YKECP']['admin_pass'], '', 1);

                if (!empty($_REQUEST['is_ajax']))
                {
                    make_json_error($_LANG['priv_error']);
                }
                else
                {
                    yk_header('Location: privilege.php?sysessid='.$GLOBALS['sysessid'].'&act=login\n');
                }

                exit;
            }
        }
    }
    else
    {
        if (!empty($_REQUEST['is_ajax']))
        {
            make_json_error($_LANG['priv_error']);
        }
        else
        {
            yk_header('Location: privilege.php?sysessid='.$GLOBALS['sysessid'].'&act=login\n');
        }

        exit;
    }
}

$smarty->assign('token', $_CFG['token']);

if ($_REQUEST['act'] != 'login' && $_REQUEST['act'] != 'signin' &&
    $_REQUEST['act'] != 'forget_pwd' && $_REQUEST['act'] != 'reset_pwd' && $_REQUEST['act'] != 'check_order')
{
    $ADMIN_PATH = preg_replace('/:\d+/', '', $yke->url()) . ADMIN_PATH_M;
    if (!empty($_SERVER['HTTP_REFERER']) &&
        strpos(preg_replace('/:\d+/', '', $_SERVER['HTTP_REFERER']), $ADMIN_PATH) === false)
    {
        if (!empty($_REQUEST['is_ajax']))
        {
            make_json_error($_LANG['priv_error']);
        }
        else
        {
            yk_header('Location: privilege.php?sysessid='.$GLOBALS['sysessid'].'&act=login\n');
        }

        exit;
    }
}

/* 管理员登录后可在任何页面使用 act=phpinfo 显示 phpinfo() 信息 */
/*if ($_REQUEST['act'] == 'phpinfo' && function_exists('phpinfo'))
{
    phpinfo();

    exit;
}*/
// safety_20150626 del_end
//header('Cache-control: private');
header('content-type: text/html; charset=' . EC_CHARSET);
header('Expires: Fri, 14 Mar 1980 20:53:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: no-cache, must-revalidate');
header('Pragma: no-cache');

if ((DEBUG_MODE & 1) == 1)
{
    error_reporting(E_ALL);
}
else
{
    error_reporting(E_ALL ^ E_NOTICE);
}
if ((DEBUG_MODE & 4) == 4)
{
    include(ROOT_PATH . 'includes/lib.debug.php');
}

/* 判断是否支持gzip模式 */
if (gzip_enabled())
{
    ob_start('ob_gzhandler');
}
else
{
    ob_start();
}

?>
