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
function brand_exists($brand_name)
{
    $sql = "SELECT COUNT(*) FROM " .$GLOBALS['yke']->table(1,'brand').
        " WHERE brand_name = '" . $brand_name . "'"." and sysid ='".$GLOBALS['sysid']."' ";
    return ($GLOBALS['db']->getOne($sql) > 0) ? true : false;
}

/*********************/
/*                   */
/*  Version : 5.1.0  */
/*  Author  : RM     */
/*  Comment : 071223 */
/*                   */
/*********************/

function brand_get_goodsex( $brand_id, $cate, $size, $page, $sort, $order )
{
    $cate_where = 0 < $cate ? "AND ".get_children( $cate ) : "";
    $sql = "SELECT g.goods_id, g.goods_name, g.goods_sn, g.market_price, g.shop_price AS org_price,g.is_promote, g.is_new, g.is_best, g.is_hot,".( "IFNULL(mp.user_price, g.shop_price * '".$_SESSION['discount']."') AS shop_price, g.promote_price, " ).
        "(select AVG(r.comment_rank) from ".$GLOBALS['yke']->table( "comment" )." as r where r.id_value = g.goods_id and r.sysid=g.sysid AND r.comment_type = 0 AND r.parent_id = 0 AND r.status = 1"." and r.sysid ='".$GLOBALS['sysid']."' ".") AS comment_rank,
        (select IFNULL(sum(r.id_value), 0) from ".$GLOBALS['yke']->table( "comment" )." as r where r.id_value = g.goods_id and r.sysid=g.sysid AND r.comment_type = 0 AND r.parent_id = 0 AND r.status = 1"." and sysid ='".$GLOBALS['sysid']."' ".") AS comment_count,
        (select IFNULL(sum(og.goods_number), 0) from ".$GLOBALS['yke']->table( "order_goods" )." as og where og.goods_id = g.goods_id and og.sysid=g.sysid "." and og.sysid ='".$GLOBALS['sysid']."' ".") AS sell_number,
         g.promote_start_date, g.promote_end_date, g.goods_brief, g.goods_thumb , g.goods_img FROM ".$GLOBALS['yke']->table( "goods" )." AS g LEFT JOIN ".$GLOBALS['yke']->table( "member_price" )." AS mp ".( "ON mp.goods_id = g.goods_id and mp.sysid=g.sysid AND mp.user_rank = '".$_SESSION['user_rank']."' " ).( "WHERE g.is_on_sale = 1 AND g.is_alone_sale = 1 AND g.is_delete = 0 AND g.brand_id = '".$brand_id."' {$cate_where}"." and sysid ='".$GLOBALS['sysid']."' ").( "ORDER BY ".$sort." {$order}" );
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
        $arr[$row['goods_id']]['goods_id'] = $row['goods_id'];
        if ( $GLOBALS['display'] == "grid" )
        {
            $arr[$row['goods_id']]['short_name'] = 0 < $GLOBALS['_CFG']['goods_name_length'] ? sub_str( $row['goods_name'], $GLOBALS['_CFG']['goods_name_length'] ) : $row['goods_name'];
        }
        $arr[$row['goods_id']]['goods_name'] = $row['goods_name'];
        $arr[$row['goods_id']]['goods_sn'] = $row['goods_sn'];
        $arr[$row['goods_id']]['comment_count'] = $row['comment_count'];
        $arr[$row['goods_id']]['comment_rank'] = $row['comment_rank'];
        $arr[$row['goods_id']]['sell_number'] = $row['sell_number'];
        $arr[$row['goods_id']]['is_promote'] = $row['is_promote'];
        $arr[$row['goods_id']]['is_new'] = $row['is_new'];
        $arr[$row['goods_id']]['is_best'] = $row['is_best'];
        $arr[$row['goods_id']]['is_hot'] = $row['is_hot'];
        $arr[$row['goods_id']]['market_price'] = price_format( $row['market_price'] );
        $arr[$row['goods_id']]['shop_price'] = price_format( $row['shop_price'] );
        $arr[$row['goods_id']]['promote_price'] = 0 < $promote_price ? price_format( $promote_price ) : "";
        $arr[$row['goods_id']]['goods_brief'] = $row['goods_brief'];
        $arr[$row['goods_id']]['goods_thumb'] = get_image_path( $row['goods_id'], $row['goods_thumb'], TRUE );
        $arr[$row['goods_id']]['goods_img'] = get_image_path( $row['goods_id'], $row['goods_img'] );
        $arr[$row['goods_id']]['url'] = build_uri( "goods", array(
            "gid" => $row['goods_id']
        ), $row['goods_name'] );
    }
    return $arr;
}

$sort = isset( $_REQUEST['sort'] ) && in_array( trim( strtolower( $_REQUEST['sort'] ) ), array( "goods_id", "shop_price", "sell_number", "comment_count" ) ) ? trim( $_REQUEST['sort'] ) : $default_sort_order_type;
$goodslist = brand_get_goodsex( $brand_id, $cate, $size, $page, $sort, $order );
if ( $display == "grid" && count( $goodslist ) % 2 != 0 )
{
    $goodslist[] = array( );
}
$smarty->assign( "goods_list", $goodslist );

?>