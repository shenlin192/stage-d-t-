<?php
//echo 'a111';
//admin 是否需要登录，定义每一个页面是否需要登录
/* 初始化 action */
if (!isset($_REQUEST['act'])) {
    $_REQUEST['act'] = '';
} elseif (($_REQUEST['act'] == 'login' || $_REQUEST['act'] == 'logout' || $_REQUEST['act'] == 'signin') &&
    strpos(PHP_SELF, '/privilege.php') === false
) {
    $_REQUEST['act'] = '';
} elseif (($_REQUEST['act'] == 'forget_pwd' || $_REQUEST['act'] == 'reset_pwd' || $_REQUEST['act'] == 'get_pwd') &&
    strpos(PHP_SELF, '/get_password.php') === false
) {
    $_REQUEST['act'] = '';
}

if ($_REQUEST['act'] == 'captcha') {
    include_once(ROOTWEB_PATH.'modules/public/includes/cls_captcha.php');
    $img = new captcha(ROOT_PATH . '/data/captcha/');
    @ob_end_clean(); //清除之前出现的多余输入
    $img->generate_image();
    exit;
}

if ($_REQUEST['act'] != 'login' && $_REQUEST['act'] != 'signin' &&
    $_REQUEST['act'] != 'forget_pwd' && $_REQUEST['act'] != 'reset_pwd' && $_REQUEST['act'] != 'check_order'
) {
    $ADMIN_PATH = preg_replace('/:\d+/', '', $yke->url()) . ADMIN_PATH;
    if (!empty($_SERVER['HTTP_REFERER']) &&
        strpos(preg_replace('/:\d+/', '', $_SERVER['HTTP_REFERER']), $ADMIN_PATH) === false
    ) {
        if (!empty($_REQUEST['is_ajax'])) {
            make_json_error($_LANG['priv_error']);
        } else {
            yk_header('Location: ' . ROOT_PATH . ADMIN_PATH . '/includes/privilege.php?sysessid=' . $GLOBALS['sysessid'] . '&act=login');//.'\n'
        }

        exit;
    }
}

echo $_REQUEST['act'];
/* 验证管理员身份 */
if ((!isset($_SESSION['admin_id']) || intval($_SESSION['admin_id']) <= 0) &&
    $_REQUEST['act'] != 'login' && $_REQUEST['act'] != 'signin' &&
    $_REQUEST['act'] != 'forget_pwd' && $_REQUEST['act'] != 'reset_pwd' && $_REQUEST['act'] != 'check_order'
) {
    /* 检查cookie */    /* session 不存在，检查cookie */
    if (!empty($_COOKIE['YKECP']['sysid']) && (!empty($_COOKIE['YKECP']['admin_id']) && !empty($_COOKIE['YKECP']['admin_pass']))) {
        //      echo 'a1';
        // 找到了cookie, 验证cookie信息
        $sql = 'SELECT user_id, user_name, password, action_list, last_login ' .
            ' FROM ' . $yke->table(1,'admin_user') .
            " WHERE user_id = '" . intval($_COOKIE['YKECP']['admin_id']) . "'  and sysid ='" . $GLOBALS['sysid'] . "' ";
        $row = $db->GetRow($sql);
        if (!$row) {
            // 没有找到这个记录
            setcookie($_COOKIE['YKECP']['sysid'], '', 1);
            setcookie($_COOKIE['YKECP']['admin_id'], '', 1);
            setcookie($_COOKIE['YKECP']['admin_pass'], '', 1);
            if (!empty($_REQUEST['is_ajax'])) {
                make_json_error($_LANG['priv_error']);
            } else {
                yk_header('Location:' . ROOT_PATH . ADMIN_PATH . '/includes/privilege.php?sysessid=' . $GLOBALS['sysessid'] . '&act=login');//.'\n'
            }
            exit;
        } else {
            // 检查密码是否正确
            if (md5($row['password'] . $_CFG['hash_code']) == $_COOKIE['YKECP']['admin_pass']) {
                !isset($row['last_time']) && $row['last_time'] = '';
                set_admin_session($row['user_id'], $row['user_name'], $row['action_list'], $row['last_time']);
                // 更新最后登录时间和IP
                $db->query('UPDATE ' . $yke->table(1,'admin_user') .
                    " SET last_login = '" . gmtime() . "', last_ip = '" . real_ip() . "'" .
                    " WHERE user_id = '" . $_SESSION['admin_id'] . "'  and sysid ='" . $GLOBALS['sysid'] . "' ");
            } else {
                setcookie($_COOKIE['YKECP']['sysid'], '', 1);
                setcookie($_COOKIE['YKECP']['admin_id'], '', 1);
                setcookie($_COOKIE['YKECP']['admin_pass'], '', 1);
                if (!empty($_REQUEST['is_ajax'])) {
                    make_json_error($_LANG['priv_error']);
                } else {
                    yk_header('Location: ' . ROOT_PATH . ADMIN_PATH . '/includes/privilege.php?sysessid=' . $GLOBALS['sysessid'] . '&act=login');//.'\n'
                }
                exit;
            }
        }
    } else {
//        echo 'a2';
        //echo ROOT_PATH;
        if (!empty($_REQUEST['is_ajax'])) {
            make_json_error($_LANG['priv_error']);
        } else {
            $href = '/yikai/admin/privilege.php?sysessid=' . $GLOBALS['sysessid'] . '&act=login';
            //echo $href;
            //yk_header("Location: $href");//."\n"
            //          echo '1 admin/includes/init $GLOBALS[\'sysessid\'] = '.$GLOBALS['sysessid']."\r\n";
            ///yikai/mobile/admin
            yk_header('Location: ' . ROOT_PATH . ADMIN_PATH . '/includes/privilege.php?sysessid=' . $GLOBALS['sysessid'] . '&act=login');//."\n"
        }

        exit;//退出，不执行后面所有的代码
    }
}



// safety_20150626 del_start
/* 管理员登录后可在任何页面使用 act=phpinfo 显示 phpinfo() 信息 */
/*if ($_REQUEST['act'] == 'phpinfo' && function_exists('phpinfo'))
{
    phpinfo();

    exit;
}*/
// safety_20150626 del_end
?>