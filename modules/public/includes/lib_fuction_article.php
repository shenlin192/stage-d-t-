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


/**
 * 分配文章列表给smarty
 *
 * @access  public
 * @param   integer     $id     文章分类的编号
 * @param   integer     $num    文章数量
 * @return  array
 */
function assign_articles($id, $num)
{
    $sql = 'SELECT cat_name FROM ' . $GLOBALS['yke']->table(1,'ykesmart_article_cat') . " WHERE cat_id = '" . $id ."'"." and sysid ='".$GLOBALS['sysid']."' ";

    $cat['id']       = $id;
    $cat['name']     = $GLOBALS['db']->getOne($sql);
    $cat['url']      = build_uri('article_cat', array('acid' => $id), $cat['name']);

    $articles['cat'] = $cat;
    $articles['arr'] = get_cat_articles($id, 1, $num);

    return $articles;
}

/**
 * 分配帮助信息
 *
 * @access  public
 * @return  array
 */
function get_shop_help()
{
    $sql = 'SELECT c.cat_id, c.cat_name, c.sort_order, a.article_id, a.title, a.file_url, a.open_type ' .
        'FROM ' .$GLOBALS['yke']->table(1,'ykesmart_article'). ' AS a ' .
        'LEFT JOIN ' .$GLOBALS['yke']->table(1,'ykesmart_article_cat'). ' AS c ' .
        'ON a.cat_id = c.cat_id and a.sysid=c.sysid WHERE c.cat_type = 5 AND a.is_open = 1 '." and a.sysid ='".$GLOBALS['sysid']."' " .
        'ORDER BY c.sort_order ASC, a.article_id';
    $res = $GLOBALS['db']->getAll($sql);
    $arr = array();
    foreach ($res AS $key => $row)
    {
        $arr[$row['cat_id']]['cat_id']                       = build_uri('article_cat', array('acid'=> $row['cat_id']), $row['cat_name']);
        $arr[$row['cat_id']]['cat_name']                     = $row['cat_name'];
        $arr[$row['cat_id']]['article'][$key]['article_id']  = $row['article_id'];
        $arr[$row['cat_id']]['article'][$key]['title']       = $row['title'];
        $arr[$row['cat_id']]['article'][$key]['short_title'] = $GLOBALS['_CFG']['article_title_length'] > 0 ?
            sub_str($row['title'], $GLOBALS['_CFG']['article_title_length']) : $row['title'];
        $arr[$row['cat_id']]['article'][$key]['url']         = $row['open_type'] != 1 ?
            build_uri('article', array('aid' => $row['article_id']), $row['title']) : trim($row['file_url']);
    }

    return $arr;
}


/**
 * 获得指定分类同级的所有分类以及该分类下的子分类
 *
 * @access  public
 * @param   integer     $cat_id     分类编号
 * @return  array
 */
function article_categories_tree($cat_id = 0)
{
    if ($cat_id > 0)
    {
        $sql = 'SELECT parent_id FROM ' . $GLOBALS['yke']->table(1,'ykesmart_article_cat') . " WHERE cat_id = '$cat_id'"." and sysid ='".$GLOBALS['sysid']."' ";
        $parent_id = $GLOBALS['db']->getOne($sql);
    }
    else
    {
        $parent_id = 0;
    }

    /*
     判断当前分类中全是是否是底级分类，
     如果是取出底级分类上级分类，
     如果不是取当前分类及其下的子分类
    */
    $sql = 'SELECT count(*) FROM ' . $GLOBALS['yke']->table(1,'ykesmart_article_cat') . " WHERE parent_id = '$parent_id'"." and sysid ='".$GLOBALS['sysid']."' ";
    if ($GLOBALS['db']->getOne($sql))
    {
        /* 获取当前分类及其子分类 */
        $sql = 'SELECT a.cat_id, a.cat_name, a.sort_order AS parent_order, a.cat_id, ' .
            'b.cat_id AS child_id, b.cat_name AS child_name, b.sort_order AS child_order ' .
            'FROM ' . $GLOBALS['yke']->table(1,'ykesmart_article_cat') . ' AS a ' .
            'LEFT JOIN ' . $GLOBALS['yke']->table(1,'ykesmart_article_cat') . ' AS b ON b.parent_id = a.cat_id and b.sysid=a.sysid ' .
            "WHERE a.parent_id = '$parent_id' AND a.cat_type=1 And a.keywords<>'-1'"." and a.sysid ='".$GLOBALS['sysid']."' "." ORDER BY parent_order ASC, a.cat_id ASC, child_order ASC";
    }
    else
    {
        /* 获取当前分类及其父分类 */
        $sql = 'SELECT a.cat_id, a.cat_name, b.cat_id AS child_id, b.cat_name AS child_name, b.sort_order ' .
            'FROM ' . $GLOBALS['yke']->table(1,'ykesmart_article_cat') . ' AS a ' .
            'LEFT JOIN ' . $GLOBALS['yke']->table(1,'ykesmart_article_cat') . ' AS b ON b.parent_id = a.cat_id and a.sysid=b.sysid ' .
            "WHERE b.parent_id = '$parent_id' AND b.cat_type = 1 "." and a.sysid ='".$GLOBALS['sysid']."' "."ORDER BY sort_order ASC";
    }
    $res = $GLOBALS['db']->getAll($sql);
    $cat_arr = array();
    foreach ($res AS $row)
    {
        $cat_arr[$row['cat_id']]['id']   = $row['cat_id'];
        $cat_arr[$row['cat_id']]['name'] = $row['cat_name'];
        $cat_arr[$row['cat_id']]['url']  = build_uri('article_cat', array('acid' => $row['cat_id']), $row['cat_name']);

        if ($row['child_id'] != NULL)
        {
            $cat_arr[$row['cat_id']]['children'][$row['child_id']]['id']   = $row['child_id'];
            $cat_arr[$row['cat_id']]['children'][$row['child_id']]['name'] = $row['child_name'];
            $cat_arr[$row['cat_id']]['children'][$row['child_id']]['url']  = build_uri('article_cat', array('acid' => $row['child_id']), $row['child_name']);
        }
    }

    return $cat_arr;
}

/**
 * 获得指定文章分类的所有上级分类
 *
 * @access  public
 * @param   integer $cat    分类编号
 * @return  array
 */
function get_article_parent_cats($cat)
{
    if ($cat == 0)
    {
        return array();
    }

    $arr = $GLOBALS['db']->GetAll('SELECT cat_id, cat_name, parent_id FROM ' . $GLOBALS['yke']->table(1,'ykesmart_article_cat')." where sysid ='".$GLOBALS['sysid']."' ");

    if (empty($arr))
    {
        return array();
    }

    $index = 0;
    $cats  = array();

    while (1)
    {
        foreach ($arr AS $row)
        {
            if ($cat == $row['cat_id'])
            {
                $cat = $row['parent_id'];

                $cats[$index]['cat_id']   = $row['cat_id'];
                $cats[$index]['cat_name'] = $row['cat_name'];

                $index++;
                break;
            }
        }

        if ($index == 0 || $cat == 0)
        {
            break;
        }
    }

    return $cats;
}

/**
 * 取得文章列表：用于商品关联文章
 * @param   object  $filters    过滤条件
 */
function get_article_list($filter)
{
    /* 创建数据容器对象 */
    $ol = new OptionList();

    /* 取得过滤条件 */
    $where = ' WHERE a.cat_id = c.cat_id and a.sysid = c.sysid AND c.cat_type = 1 '." and a.sysid ='".$GLOBALS['sysid']."' ";
    $where .= isset($filter->title) ? " AND a.title LIKE '%" . mysql_like_quote($filter->title) . "%'" : '';

    /* 取得数据 */
    $sql = 'SELECT a.article_id, a.title '.
        'FROM ' .$GLOBALS['yke']->table(1,'article'). ' AS a, ' .$GLOBALS['yke']->table(1,'article_cat'). ' AS c ' . $where." and a.sysid=c.sysid and a.sysid ='".$GLOBALS['sysid']."' ";
    $res = $GLOBALS['db']->query($sql);

    while ($row = $GLOBALS['db']->fetchRow($res))
    {
        $ol->add_option($row['article_id'], $row['title']);
    }

    /* 生成列表 */
    $ol->build_select();
}



?>