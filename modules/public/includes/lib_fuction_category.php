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
 * 检查分类是否已经存在
 *
 * @param   string      $cat_name       分类名称
 * @param   integer     $parent_cat     上级分类
 * @param   integer     $exclude        排除的分类ID
 *
 * @return  boolean
 */
function cat_exists($cat_name, $parent_cat, $exclude = 0)
{
    $sql = "SELECT COUNT(*) FROM " .$GLOBALS['yke']->table(1,'category').
        " WHERE parent_id = '$parent_cat' AND cat_name = '$cat_name' AND cat_id<>'$exclude'"." and sysid ='".$GLOBALS['sysid']."' ";
    return ($GLOBALS['db']->getOne($sql) > 0) ? true : false;
}



/**
 * 获得指定的商品类型下所有的属性分组
 *
 * @param   integer     $cat_id     商品类型ID
 *
 * @return  array
 */
function get_attr_groups($cat_id)
{
    $sql = "SELECT attr_group FROM " . $GLOBALS['yke']->table(1,'goods_type') . " WHERE cat_id='$cat_id'"." and sysid ='".$GLOBALS['sysid']."' ";
    $grp = str_replace("\r", '', $GLOBALS['db']->getOne($sql));

    if ($grp)
    {
        return explode("\n", $grp);
    }
    else
    {
        return array();
    }
}
/**
 * 获得指定分类的所有上级分类
 *
 * @access  public
 * @param   integer $cat    分类编号
 * @return  array
 */
function get_parent_cats($cat)
{
    if ($cat == 0)
    {
        return array();
    }

    $arr = $GLOBALS['db']->GetAll('SELECT cat_id, cat_name, parent_id FROM ' . $GLOBALS['yke']->table(1,'category')." where sysid ='".$GLOBALS['sysid']."' ");

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
 * 获得商品类型的列表
 *
 * @access  public
 * @param   integer     $selected   选定的类型编号
 * @return  string
 */
function goods_type_list($selected)
{
    $sql = 'SELECT cat_id, cat_name FROM ' . $GLOBALS['yke']->table(1,'goods_type') . ' WHERE enabled = 1'." and sysid ='".$GLOBALS['sysid']."' ";
    $res = $GLOBALS['db']->query($sql);

    $lst = '';
    while ($row = $GLOBALS['db']->fetchRow($res))
    {
        $lst .= "<option value='$row[cat_id]'";
        $lst .= ($selected == $row['cat_id']) ? ' selected="true"' : '';
        $lst .= '>' . htmlspecialchars($row['cat_name']). '</option>';
    }

    return $lst;
}


/*********************/
/*                   */
/*  Version : 5.1.0  */
/*  Author  : RM     */
/*  Comment : 071223 */
/*                   */
/*********************/

function get_cat_goods_count( $keywords, $children, $brand = 0, $min = 0, $max = 0, $ext = "" )
{
    $where = "g.is_on_sale = 1 AND g.is_alone_sale = 1 AND g.is_delete = 0 AND (".$children." OR ".get_extension_goods( $children ).")";
    if ( $keywords != "" )
    {
        $where .= " AND g.goods_name LIKE '%".$keywords."%' ";
    }
    if ( 0 < $brand )
    {
        $where .= " AND g.brand_id = ".$brand." ";
    }
    if ( 0 < $min )
    {
        $where .= " AND g.shop_price >= ".$min." ";
    }
    if ( 0 < $max )
    {
        $where .= " AND g.shop_price <= ".$max." ";
    }
    return $GLOBALS['db']->getOne( "SELECT COUNT(*) FROM ".$GLOBALS['yke']->table( "goods" ).( " AS g WHERE ".$where." {$ext}" ." and sysid ='".$GLOBALS['sysid']."' ") );
}

function cat_get_goods( $keywords, $children, $brand, $min, $max, $ext, $size, $page, $sort, $order )
{
    $display = $GLOBALS['display'];
    $where = "g.is_on_sale = 1 AND g.is_alone_sale = 1 AND ".( "g.is_delete = 0 AND (".$children." OR " ).get_extension_goods( $children ).")";
    if ( $keywords != "" )
    {
        $where .= " AND g.goods_name LIKE '%".$keywords."%' ";
    }
    if ( 0 < $brand )
    {
        $where .= "AND g.brand_id=".$brand." ";
    }
    if ( 0 < $min )
    {
        $where .= " AND g.shop_price >= ".$min." ";
    }
    if ( 0 < $max )
    {
        $where .= " AND g.shop_price <= ".$max." ";
    }
    $sql = "SELECT g.goods_id, g.goods_name, g.goods_sn, g.goods_name_style, g.market_price,g.is_promote, g.is_new, g.is_best, g.is_hot, g.shop_price AS org_price, ".( "IFNULL(mp.user_price, g.shop_price * '".$_SESSION['discount']."') AS shop_price, g.promote_price, g.goods_type, " )."
    (select AVG(r.comment_rank) from ".$GLOBALS['yke']->table( "comment" )." as r where r.id_value = g.goods_id and r.sysid=g.sysid AND r.comment_type = 0 AND r.parent_id = 0 AND r.status = 1"." and r.sysid ='".$GLOBALS['sysid']."' ".") AS comment_rank,
    (select IFNULL(sum(r.id_value), 0) from ".$GLOBALS['yke']->table( "comment" )." as r where r.id_value = g.goods_id and r.sysid=g.sysid AND r.comment_type = 0 AND r.parent_id = 0 AND r.status = 1"." and sysid ='".$GLOBALS['sysid']."' ".") AS comment_count,
    (select IFNULL(sum(og.goods_number), 0) from ".$GLOBALS['yke']->table( "order_goods" )." as og where og.goods_id = g.goods_id) AS sell_number, g.promote_start_date, g.promote_end_date, g.goods_brief, g.goods_thumb , g.goods_img FROM ".$GLOBALS['yke']->table( "goods" )." AS g LEFT JOIN ".$GLOBALS['yke']->table( "member_price" )." AS mp ".( "ON mp.goods_id = g.goods_id and mp.sysid=g.sysid AND mp.user_rank = '".$_SESSION['user_rank']."' " ).( "WHERE ".$where." {$ext} "." and g.sysid ='".$GLOBALS['sysid']."' "." ORDER BY {$sort} {$order}" );
    $res = $GLOBALS['db']->selectLimit( $sql, $size, ( $page - 1 ) * $size );
    $arr = array( );
    while ( $row = $GLOBALS['db']->fetchRow( $res ) )
    {
        if ( 0 < $row['promote_price'] )
        {
            $promote_price = bargain_price( $row['promote_price'], $row['promote_start_date'], $row['promote_end_date'] );
        }
        else
        {
            $promote_price = 0;
        }
        $watermark_img = "";
        if ( $promote_price != 0 )
        {
            $watermark_img = "watermark_promote_small";
        }
        else if ( $row['is_new'] != 0 )
        {
            $watermark_img = "watermark_new_small";
        }
        else if ( $row['is_best'] != 0 )
        {
            $watermark_img = "watermark_best_small";
        }
        else if ( $row['is_hot'] != 0 )
        {
            $watermark_img = "watermark_hot_small";
        }
        if ( $watermark_img != "" )
        {
            $arr[$row['goods_id']]['watermark_img'] = $watermark_img;
        }
        $arr[$row['goods_id']]['goods_id'] = $row['goods_id'];
        $arr[$row['goods_id']]['goods_name'] = $row['goods_name'];
        $arr[$row['goods_id']]['name'] = $row['goods_name'];
        $arr[$row['goods_id']]['goods_sn'] = $row['goods_sn'];
        $arr[$row['goods_id']]['comment_rank'] = $row['comment_rank'];
        $arr[$row['goods_id']]['comment_count'] = $row['comment_count'];
        $arr[$row['goods_id']]['sell_number'] = $row['sell_number'];
        $arr[$row['goods_id']]['is_promote'] = $row['is_promote'];
        $arr[$row['goods_id']]['is_new'] = $row['is_new'];
        $arr[$row['goods_id']]['is_best'] = $row['is_best'];
        $arr[$row['goods_id']]['is_hot'] = $row['is_hot'];
        $arr[$row['goods_id']]['goods_brief'] = $row['goods_brief'];
        $arr[$row['goods_id']]['goods_style_name'] = add_style( $row['goods_name'], $row['goods_name_style'] );
        $arr[$row['goods_id']]['market_price'] = price_format( $row['market_price'] );
        $arr[$row['goods_id']]['shop_price'] = price_format( $row['shop_price'] );
        $arr[$row['goods_id']]['type'] = $row['goods_type'];
        $arr[$row['goods_id']]['promote_price'] = 0 < $promote_price ? price_format( $promote_price ) : "";
        $arr[$row['goods_id']]['goods_thumb'] = get_image_path( $row['goods_id'], $row['goods_thumb'], TRUE );
        $arr[$row['goods_id']]['goods_img'] = get_image_path( $row['goods_id'], $row['goods_img'] );
        $arr[$row['goods_id']]['url'] = build_uri( "goods", array(
            "gid" => $row['goods_id']
        ), $row['goods_name'] );
    }
    return $arr;
}
$default_sort_order_type = $_CFG['sort_order_type'] == '0' ? 'goods_id' : ($_CFG['sort_order_type'] == '1' ? 'shop_price' : 'last_update');
$keywords = !empty( $_REQUEST['keywords'] ) ? htmlspecialchars( trim( $_REQUEST['keywords'] ) ) : "";
$sort = isset( $_REQUEST['sort'] ) && in_array( trim( strtolower( $_REQUEST['sort'] ) ), array( "goods_id", "shop_price", "sell_number", "comment_count" ) ) ? trim( $_REQUEST['sort'] ) : $default_sort_order_type;
$count = get_cat_goods_count( $keywords, $children, $brand, $price_min, $price_max, $ext );
$max_page = 0 < $count ? ceil( $count / $size ) : 1;
if ( $max_page < $page )
{
    $page = $max_page;
}
$goodslist = cat_get_goods( $keywords, $children, $brand, $price_min, $price_max, $ext, $size, $page, $sort, $order );
if ( $display == "grid" && count( $goodslist ) % 2 != 0 )
{
    $goodslist[] = array( );
}
$smarty->assign( "goods_list", $goodslist );
$smarty->assign( "keys", $keywords );
assign_pager( "category", $cat_id, $count, $size, $sort, $order, $page, "", $brand, $price_min, $price_max, $display, $filter_attr_str );

?>