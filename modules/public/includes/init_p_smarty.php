<?php

//设置$smarty模板及缓存文件路径
//定义缓存文件路径//每个模块路径不同，手机与PC也不同
//设置$smarty模板及缓存文件路径

/* 创建 Smarty 对象。*/
if (!defined('INIT_NO_SMARTY')) {

    require_once(ROOTWEB_PATH.'modules/public/includes/cls_template.php');

    //定义模块PC,PCADMIN,MOBILE,MOBILEADMIN
    if ((MODULE == 'PC') || (MODULE == 'PCAPI')) {
        define("templatepath", 'themes/');
        define("cachespath", 'temp/caches');
        define("compiledpath", 'temp/compiled');
    } //elseif ((MODULE == 'PCADMIN') || (MODULE == 'MOBILEADMIN'))
    {
        if (!file_exists(ROOT_PATH . 'temp/caches'))//../temp/caches
        {
            @mkdir('../temp/caches', 0777);
            @chmod('../temp/caches', 0777);
        }

        if (!file_exists(ROOT_PATH . 'temp/compiled/admin'))//../temp/compiled/admin
        {
            @mkdir('../temp/compiled/admin', 0777);
            @chmod('../temp/compiled/admin', 0777);
        }

        define("templatepath", PROJECT . '/');//  $smarty->template_dir  = ROOT_PATH . ADMIN_PATH . '/templates';
        define("cachespath", 'temp/caches');
        define("compiledpath", 'temp/compiled/admin');//
    }
//
//    else {
//        $ua = strtolower($_SERVER['HTTP_USER_AGENT']);
//        $uachar = "/(nokia|sony|ericsson|mot|samsung|sgh|lg|philips|panasonic|alcatel|lenovo|cldc|midp|mobile)/i";
//        if (($ua == '' || preg_match($uachar, $ua)) && !strpos(strtolower($_SERVER['REQUEST_URI']), 'wap')) {
//            define("templatepath", 'themesmobile/');
//            define("cachespath", 'temp/caches');
//            define("compiledpath", 'temp/compiled');
//        } else {
//            define("templatepath", 'themesmobile/');
//            define("cachespath", 'temp/caches');
//            define("compiledpath", 'temp/compiled');
//        }
//    }


    header('Cache-control: private');
    header('Content-type: text/html; charset=' . EC_CHARSET);
    /* 创建 Smarty 对象。*/
    $smarty = new cls_template;
    $smarty->cache_lifetime = $_CFG['cache_time'];

    clearstatcache();

    if ((DEBUG_MODE & 2) == 2) {
        $smarty->direct_output = true;
        $smarty->force_compile = true;
    } else {
        $smarty->direct_output = false;
        $smarty->force_compile = false;
    }

    $smarty->assign('lang', $_LANG);
    $smarty->assign('yke_charset', EC_CHARSET);
    $smarty->assign('help_open', $_CFG['help_open']);

    if ((MODULE == 'MOBILEADMIN')||(MODULE == 'PCADMIN')) {
        $smarty->template_dir = ROOT_PATH . ADMIN_PATH . '/templates';
    } else {
        $smarty->template_dir = ROOT_PATH . templatepath . $_CFG['template'];
    }
    $smarty->cache_dir = ROOT_PATH . cachespath;
    $smarty->compile_dir = ROOT_PATH . compiledpath;

//    echo $smarty->template_dir;
//    echo $smarty->cache_dir;
//    echo $smarty->compile_dir;

    if (isset($_CFG['enable_order_check']))  // 为了从旧版本顺利升级到2.5.0
    {
        $smarty->assign('enable_order_check', $_CFG['enable_order_check']);
    } else {
        $smarty->assign('enable_order_check', 0);
    }

    if (!empty($_CFG['stylename'])) {
        $smarty->assign('yke_css_path', templatepath . $_CFG['template'] . '/style_' . $_CFG['stylename'] . '.css');
    } else {
        $smarty->assign('yke_css_path', templatepath . $_CFG['template'] . '/style.css');

    }
}

?>

