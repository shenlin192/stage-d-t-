<?php
/**
 * 用户相关 前台公用函数库
 * ============================================================================
 * * 版权所有 2015-2025 杭州一开网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.yikaiqiche.cn；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: $
 * $Id: $.php  2016-02-07 06:29:08Z $
 */
//按照模块、对象定义模板，便于引用，修改，新增
if (!defined('IN_YKE'))
{
    die('Hacking attempt');
}


function assign_template($ctype = '', $catlist = array())
{
    global $smarty;

    $smarty->assign('image_width',   $GLOBALS['_CFG']['image_width']);
    $smarty->assign('image_height',  $GLOBALS['_CFG']['image_height']);
    $smarty->assign('points_name',   $GLOBALS['_CFG']['integral_name']);
    $smarty->assign('qq',            explode(',', $GLOBALS['_CFG']['qq']));
    $smarty->assign('ww',            explode(',', $GLOBALS['_CFG']['ww']));
    $smarty->assign('ym',            explode(',', $GLOBALS['_CFG']['ym']));
    $smarty->assign('msn',           explode(',', $GLOBALS['_CFG']['msn']));
    $smarty->assign('skype',         explode(',', $GLOBALS['_CFG']['skype']));
    $smarty->assign('stats_code',    $GLOBALS['_CFG']['stats_code']);
    $smarty->assign('copyright',     sprintf($GLOBALS['_LANG']['copyright'], date('Y'), $GLOBALS['_CFG']['shop_name']));
    $smarty->assign('shop_name',     $GLOBALS['_CFG']['shop_name']);
    $smarty->assign('service_email', $GLOBALS['_CFG']['service_email']);
    $smarty->assign('service_phone', $GLOBALS['_CFG']['service_phone']);
    $smarty->assign('shop_address',  $GLOBALS['_CFG']['shop_address']);
    $smarty->assign('licensed',      license_info());
    $smarty->assign('yke_version',   VERSION);
    $smarty->assign('icp_number',    $GLOBALS['_CFG']['icp_number']);
    $smarty->assign('username',      !empty($_SESSION['user_name']) ? $_SESSION['user_name'] : '');
    $smarty->assign('category_list', cat_list(0, 0, true,  2, false));
    $smarty->assign('catalog_list',  cat_list(0, 0, false, 1, false));
    $smarty->assign('navigator_list',        get_navigator($ctype, $catlist));  //自定义导航栏

    if (!empty($GLOBALS['_CFG']['search_keywords']))
    {
        $searchkeywords = explode(',', trim($GLOBALS['_CFG']['search_keywords']));
    }
    else
    {
        $searchkeywords = array();
    }
    $smarty->assign('searchkeywords', $searchkeywords);
}

/**
 * 获得指定页面的动态内容
 *
 * @access  public
 * @param   string  $tmp    模板名称
 * @return  void
 */
function assign_dynamic($tmp)
{
    //   echo  $tmp;
    $sql = 'SELECT id, number, type FROM ' . $GLOBALS['yke']->table(1,'ykesmart_template') .
        " WHERE filename = '$tmp' AND type > 0 AND remarks ='' AND theme='" . $GLOBALS['_CFG']['template'] . "'"." and sysid ='".$GLOBALS['sysid']."' ";
    $res = $GLOBALS['db']->getAll($sql);

    foreach ($res AS $row)
    {
        //    echo $row['type'];
        switch ($row['type'])
        {
            case 1:
                /* 分类下的商品 */
                $GLOBALS['smarty']->assign('goods_cat_' . $row['id'], assign_cat_goods($row['id'], $row['number']));
                break;
            case 2:
                /* 品牌的商品 */
                $brand_goods = assign_brand_goods($row['id'], $row['number']);

                $GLOBALS['smarty']->assign('brand_goods_' . $row['id'], $brand_goods['goods']);
                $GLOBALS['smarty']->assign('goods_brand_' . $row['id'], $brand_goods['brand']);
                break;
            case 3:
                /* 文章列表 */
                $cat_articles = assign_articles($row['id'], $row['number']);

                $GLOBALS['smarty']->assign('articles_cat_' . $row['id'], $cat_articles['cat']);
                $GLOBALS['smarty']->assign('articles_' . $row['id'], $cat_articles['arr']);
                break;
        }
    }
}

/**
 * 获取指定主题某个模板的主题的动态模块
 *
 * @access  public
 * @param   string       $theme    模板主题
 * @param   string       $tmp      模板名称
 *
 * @return array()
 */
function get_dyna_libs($theme, $tmp)
{
    $tmp_arr_www_cheyikai_com = explode('.', $tmp);
    $ext = end($tmp_arr_www_cheyikai_com);
    $tmp = basename($tmp,".$ext");
    $sql = 'SELECT region, library, sort_order, id, number, type' .
        ' FROM ' . $GLOBALS['yke']->table(1,'ykesmart_template') .
        " WHERE theme = '$theme' AND filename = '" . $tmp . "' AND type > 0 AND remarks=''"." and sysid ='".$GLOBALS['sysid']."' ".
        ' ORDER BY region, library, sort_order';
    $res = $GLOBALS['db']->getAll($sql);

    $dyna_libs = array();
    foreach ($res AS $row)
    {
        $dyna_libs[$row['region']][$row['library']][] = array(
            'id'     => $row['id'],
            'number' => $row['number'],
            'type'   => $row['type']
        );
    }

    return $dyna_libs;
}


/**
 * 取得某模板某库设置的数量
 * @param   string      $template   模板名，如index
 * @param   string      $library    库名，如recommend_best
 * @param   int         $def_num    默认数量：如果没有设置模板，显示的数量
 * @return  int         数量
 */
function get_library_number($library, $template = null)
{
    global $page_libs;

    if (empty($template))
    {
        $template = basename(PHP_SELF);
        $template = substr($template, 0, strrpos($template, '.'));
    }
    $template = addslashes($template);

    static $lib_list = array();

    /* 如果没有该模板的信息，取得该模板的信息 */
    if (!isset($lib_list[$template]))
    {
        $lib_list[$template] = array();
        $sql = "SELECT library, number FROM " . $GLOBALS['yke']->table(1,'ykesmart_template') .
            " WHERE theme = '" . $GLOBALS['_CFG']['template'] . "'" .
            " AND filename = '$template' AND remarks='' "." and sysid ='".$GLOBALS['sysid']."' ";
        $res = $GLOBALS['db']->query($sql);
        while ($row = $GLOBALS['db']->fetchRow($res))
        {
            $lib = basename(strtolower(substr($row['library'], 0, strpos($row['library'], '.'))));
            $lib_list[$template][$lib] = $row['number'];
        }
    }

    $num = 0;
    if (isset($lib_list[$template][$library]))
    {
        $num = intval($lib_list[$template][$library]);
    }
    else
    {
        /* 模板设置文件查找默认值 */
        include_once(ROOT_PATH . ADMIN_PATH . '/includes/lib_template.php');
        static $static_page_libs = null;
        if ($static_page_libs == null)
        {
            $static_page_libs = $page_libs;
        }
        $lib = '/library/' . $library . '.lbi';

        $num = isset($static_page_libs[$template][$lib]) ? $static_page_libs[$template][$lib] :  3;
    }

    return $num;
}


/**
 * 取得自定义导航栏列表
 * @param   string      $type    位置，如top、bottom、middle
 * @return  array         列表
 */
function get_navigator($ctype = '', $catlist = array())
{
    $sql = 'SELECT * FROM '. $GLOBALS['yke']->table(1,'nav') . '
            WHERE ifshow = \'1\' ORDER BY type, vieworder'." and sysid ='".$GLOBALS['sysid']."' ";
    $res = $GLOBALS['db']->query($sql);

    $cur_url = substr(strrchr($_SERVER['REQUEST_URI'],'/'),1);

    if (intval($GLOBALS['_CFG']['rewrite']))
    {
        if(strpos($cur_url, '-'))
        {
            preg_match('/([a-z]*)-([0-9]*)/',$cur_url,$matches);
            $cur_url = $matches[1].'.php?sysessid='.$GLOBALS['sysessid'].'&id='.$matches[2];
        }
    }
    else
    {
        $cur_url = substr(strrchr($_SERVER['REQUEST_URI'],'/'),1);
    }

    $noindex = false;
    $active = 0;
    $navlist = array(
        'top' => array(),
        'middle' => array(),
        'bottom' => array()
    );
    while ($row = $GLOBALS['db']->fetchRow($res))
    {
        $navlist[$row['type']][] = array(
            'name'      =>  $row['name'],
            'opennew'   =>  $row['opennew'],
            'url'       =>  $row['url'],
            'ctype'     =>  $row['ctype'],
            'cid'       =>  $row['cid'],
        );
    }

    /*遍历自定义是否存在currentPage*/
    foreach($navlist['middle'] as $k=>$v)
    {
        $condition = empty($ctype) ? (strpos($cur_url, $v['url']) === 0) : (strpos($cur_url, $v['url']) === 0 && strlen($cur_url) == strlen($v['url']));
        if ($condition)
        {
            $navlist['middle'][$k]['active'] = 1;
            $noindex = true;
            $active += 1;
        }
    }

    if(!empty($ctype) && $active < 1)
    {
        foreach($catlist as $key => $val)
        {
            foreach($navlist['middle'] as $k=>$v)
            {
                if(!empty($v['ctype']) && $v['ctype'] == $ctype && $v['cid'] == $val && $active < 1)
                {
                    $navlist['middle'][$k]['active'] = 1;
                    $noindex = true;
                    $active += 1;
                }
            }
        }
    }

    if ($noindex == false) {
        $navlist['config']['index'] = 1;
    }

    return $navlist;
}



/**
 * 授权信息内容
 *
 * @return  str
 */
function license_info()
{
    if($GLOBALS['_CFG']['licensed'] > 0)
    {
        /* 获取HOST */
        if (isset($_SERVER['HTTP_X_FORWARDED_HOST']))
        {
            $host = $_SERVER['HTTP_X_FORWARDED_HOST'];
        }
        elseif (isset($_SERVER['HTTP_HOST']))
        {
            $host = $_SERVER['HTTP_HOST'];
        }
        $url_domain=url_domain();
        $host = 'http://' . $host .$url_domain ;
        $license = '<a href="http://www.hzyikai.com/license.php?sysessid='.$GLOBALS['sysessid'].'&product=ykshop_b2c&url=' . urlencode($host) . '" target="_blank"
>&nbsp;&nbsp;Licensed</a>';
        return $license;
    }
    else
    {
        return '';
    }
}



function url_domain()
{
    $curr = strpos(PHP_SELF, ADMIN_PATH . '/') !== false ?
        preg_replace('/(.*)(' . ADMIN_PATH . ')(\/?)(.)*/i', '\1', dirname(PHP_SELF)) :
        dirname(PHP_SELF);

    $root = str_replace('\\', '/', $curr);

    if (substr($root, -1) != '/')
    {
        $root .= '/';
    }

    return $root;
}
?>