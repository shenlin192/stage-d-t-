<?php
// * 判断是否为搜索引擎蜘蛛
if (is_spider()) {
    if (!defined('INIT_NO_USERS')) {
        define('INIT_NO_USERS', true);
        if ($_CFG['integrate_code'] == 'ucenter') {
            $user = &init_users();
        }
    }
    $_SESSION = array();
    $_SESSION['user_id'] = 0;
    $_SESSION['user_name'] = '';
    $_SESSION['email'] = '';
    $_SESSION['user_rank'] = 0;
    $_SESSION['discount'] = 1.00;
}

//定义模块PC,PCADMIN,MOBILE,MOBILEADMIN
if ((MODULE == 'PC') || (MODULE == 'MOBILE')) {
    $sessid = 'SESS_ID';
} elseif ((MODULE == 'PCADMIN') || (MODULE == 'MOBILEADMIN')) {

    $sessid = 'YKECP_ID';
} elseif ((MODULE == 'PCADMIN') || (MODULE == 'MOBILEADMIN')) {
    $sessid = 'yke_ID';
}

$sess_name = defined("SESS_NAME") ? SESS_NAME : 'yke_ID';


if (!defined('INIT_NO_USERS')) {
    /* 初始化session */
    include_once(ROOTWEB_PATH.'modules/public/includes/cls_session.php');//内含root_path,不影响，测试同名文件是否可以相同即可
//产生一个session_id，并保存到数据库中
    $sess = new cls_session($db, $yke->table(1,'sessions'), $yke->table(1,'sessions_data'), $sessid, $sessionid);
//获取session_id
    define('SESS_ID', $sess->get_session_id());

    require_once(ROOTWEB_PATH.'modules/public/includes/init_sysessid_en.php');//用户环境相关函数
//记录每次mac地址登录情况，不论是否注册用户，是否有cookie，此时sysid和userid都可以是空，
if (!defined('INIT_NO_USERS')) {
    /* 初始化用户插件 */
    $user =& init_users();

    if (!isset($_SESSION['user_id'])) {
        //$site_name = isset($_GET['from']) ? $_GET['from'] : addslashes($_LANG['self_site']);
        $site_name = isset($_GET['from']) ? htmlspecialchars($_GET['from']) : addslashes($_LANG['self_site']);
        $from_ad = !empty($_GET['ad_id']) ? intval($_GET['ad_id']) : 0;
        $_SESSION['from_ad'] = $from_ad;
        $_SESSION['referer'] = stripslashes($site_name);
        unset($site_name);
        if (!defined('INGORE_VISIT_STATS')) {
            visit_stats();
        }
    }
}
    if (empty($_SESSION['user_id'])) {
        if ($user->get_cookie())  //PC端用户只能根据cookie确认是否是同一个用户，判断用户，或者后面登录用户，如果cookie没有记录，则只有每次登录或者根据mac地址登录
        {
            if ($_SESSION['user_id'] > 0) {
                //weixindebug($_SESSION['user_id'],$_SESSION['user_id'],0,'','','','mobile/includes/init_d.php:201',201);
                //更新用户登录日志、经纬度、和用户信息
                update_user_info();
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


    if ((isset($sysuserid)) && (isset($_SESSION['user_id']))) {
        if ($sysuserid !== $_SESSION['user_id'] && ($sysuserid) && ($_SESSION['user_id'])) {
            //可能是黑客
            die('user Hacking attempt');
        }
    }


    if (!empty($_COOKIE['YKE']['sysid']) && !empty($_COOKIE['YKE']['user_id']) && !empty($_COOKIE['YKE']['password'])) {
        $sql = 'SELECT user_id, user_name, password ' . ' FROM ' . $yke->table(1,'users') . " WHERE user_id = '" . intval($_COOKIE['YKE']['user_id']) . "' AND password = '" . $_COOKIE['YKE']['password'] . "'" . " and sysid ='" . $GLOBALS['sysid'] . "' ";
        $row = $db->GetRow($sql);
        if (!$row) {
            $time = time() - 3600;
            setcookie("YKE[sysid]", '', $time, '/');
            setcookie("YKE[user_id]", '', $time, '/');
            setcookie("YKE[password]", '', $time, '/');
        } else {
            $_SESSION['sysid'] = $GLOBALS['sysid'];
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['user_name'] = $row['user_name'];
//        update_user_info();
        }
    }
}


//PC端获取

update_user_info();
$share_info = array();

//获取来自网页的分享用户信息，注意分享用户编码为ecuid

if (isset($GLOBALS['parent_userid'])) {
    set_affiliate();//设置记录cookie
    $u = $GLOBALS['parent_userid'];
} else {
    $parent_userid = 0;
    $u = 0;
}

if (!empty($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT parent_id FROM " . $yke->table(1,'users') . "where user_id ='$user_id'" . " and sysid ='" . $GLOBALS['sysid'] . "' ";
    $parent_id = $GLOBALS['db']->getOne($sql);
    if (empty($parent_id)) {
        if (isset($GLOBALS['parent_userid'])) {
            if ($u == $user_id) {
                $share_info = array();
            } else {
                $sql = "SELECT * FROM " . $GLOBALS['yke']->table(1,'weixin_user') . " where ecuid ='$u'" . " and sysid ='" . $GLOBALS['sysid'] . "' ";
                $share_info = $GLOBALS['db']->getRow($sql);
            }
        }
    } else {
        $sql = "SELECT * FROM " . $GLOBALS['yke']->table(1,'weixin_user') . " where ecuid ='$parent_id'" . " and sysid ='" . $GLOBALS['sysid'] . "' ";
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
/* 验证通行证信息 */
if (isset($_GET['ent_id']) && isset($_GET['ent_ac']) && isset($_GET['ent_sign']) && isset($_GET['ent_email'])) {
    $ent_id = trim($_GET['ent_id']);
    $ent_ac = trim($_GET['ent_ac']);
    $ent_sign = trim($_GET['ent_sign']);
    $ent_email = trim($_GET['ent_email']);
    $certificate_id = trim($_CFG['certificate_id']);
    $domain_url = $yke->url();
    $token = $_GET['token'];
    if (($token == md5(md5($_CFG['token']) . $domain_url . ADMIN_PATH)) || MODULE == 'PCADMIN') {
        require_once(ROOTWEB_PATH.'modules/public/includes/cls_transport.php');
        $t = new transport('-1', 5);
        if (MODULE == 'MOBILEADMIN') {
            $apiget = "act=ent_sign&ent_id= $ent_id & certificate_id=$certificate_id";
            $t->request('http://cloud.ykshop.com/api.php', $apiget);
            $db->query('UPDATE ' . $yke->table(1,'ykesmart_shop_config', 1) . ' SET value = "' . $ent_id . '" WHERE code = "ent_id"' . " and sysid ='" . $GLOBALS['sysid'] . "' ");
            $db->query('UPDATE ' . $yke->table(1,'ykesmart_shop_config', 1) . ' SET value = "' . $ent_ac . '" WHERE code = "ent_ac"' . " and sysid ='" . $GLOBALS['sysid'] . "' ");
            $db->query('UPDATE ' . $yke->table(1,'ykesmart_shop_config', 1) . ' SET value = "' . $ent_sign . '" WHERE code = "ent_sign"' . "  and sysid ='" . $GLOBALS['sysid'] . "' ");
            $db->query('UPDATE ' . $yke->table(1,'ykesmart_shop_config', 1) . ' SET value = "' . $ent_email . '" WHERE code = "ent_email"' . " and sysid ='" . $GLOBALS['sysid'] . "' ");
            clear_cache_files();
            yk_header('Location: ' . ROOT_PATH . '/index.php?sysessid=' . $GLOBALS['sysessid']);//.'\n'
        } elseif (MODULE == 'PCADMIN') {
            $apiget = "act=ent_sign&ent_id= $ent_id &ent_ac= $ent_ac &ent_sign= $ent_sign &ent_email= $ent_email &domain_url= $domain_url";
            //$api_comment = $t->request('http://cloud.ykshop.com/api.php', $apiget);
            $api_str = $api_comment["body"];
            if ($api_str == $ent_sign) {
                $db->query('UPDATE ' . $yke->table(1,'shop_config') . ' SET value = "' . $ent_id . '" WHERE code = "ent_id"' . " and sysid ='" . $GLOBALS['sysid'] . "' ");
                $db->query('UPDATE ' . $yke->table(1,'shop_config') . ' SET value = "' . $ent_ac . '" WHERE code = "ent_ac"' . " and sysid ='" . $GLOBALS['sysid'] . "' ");
                $db->query('UPDATE ' . $yke->table(1,'shop_config') . ' SET value = "' . $ent_sign . '" WHERE code = "ent_sign"' . " and sysid ='" . $GLOBALS['sysid'] . "' ");
                $db->query('UPDATE ' . $yke->table(1,'shop_config') . ' SET value = "' . $ent_email . '" WHERE code = "ent_email"' . " and sysid ='" . $GLOBALS['sysid'] . "' ");
                clear_cache_files();
                yk_header('Location: ' . ROOT_PATH . '/index.php?sysessid=' . $GLOBALS['sysessid']);//.'\n'
            }
        }

    }
}
if (isset($_CFG['token'])) {
    $smarty->assign('token', $_CFG['token']);
}

/* 验证管理员身份 */
if (isset($_REQUEST['act'])) {
//    echo $_REQUEST['act'];
    include_once(ROOTWEB_PATH.'modules/public/includes/init_p_loginact.php');
}
if (NEEDLOGIN == 1) {
}

?>

