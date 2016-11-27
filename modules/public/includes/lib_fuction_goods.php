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
 * 取得商品列表：用于把商品添加到组合、关联类、赠品类
 * @param   object  $filters    过滤条件
 */
function get_goods_list($filter)
{
    $filter->keyword = json_str_iconv($filter->keyword);
    $where = get_where_sql($filter); // 取得过滤条件

    /* 取得数据 */
    $sql = 'SELECT goods_id, goods_name, shop_price '.
        'FROM ' . $GLOBALS['yke']->table(1,'goods') . ' AS g ' . $where ." and sysid ='".$GLOBALS['sysid']."' ".
        'LIMIT 50';
    $row = $GLOBALS['db']->getAll($sql);

    return $row;
}




/**
 * 生成过滤条件：用于 get_goodslist 和 get_goods_list
 * @param   object  $filter
 * @return  string
 */
function get_where_sql($filter)
{
    $time = date('Y-m-d');

    $where  = isset($filter->is_delete) && $filter->is_delete == '1' ?
        ' WHERE supplier_id=0 AND is_delete = 1 ' : ' WHERE supplier_id=0 AND is_delete = 0 ';
    $where .= (isset($filter->real_goods) && ($filter->real_goods > -1)) ? ' AND is_real = ' . intval($filter->real_goods) : '';
    $where .= isset($filter->cat_id) && $filter->cat_id > 0 ? ' AND ' . get_children($filter->cat_id) : '';
    $where .= isset($filter->brand_id) && $filter->brand_id > 0 ? " AND brand_id = '" . $filter->brand_id . "'" : '';
    $where .= isset($filter->intro_type) && $filter->intro_type != '0' ? ' AND ' . $filter->intro_type . " = '1'" : '';
    $where .= isset($filter->intro_type) && $filter->intro_type == 'is_promote' ?
        " AND promote_start_date <= '$time' AND promote_end_date >= '$time' " : '';
    $where .= isset($filter->keyword) && trim($filter->keyword) != '' ?
        " AND (goods_name LIKE '%" . mysql_like_quote($filter->keyword) . "%' OR goods_sn LIKE '%" . mysql_like_quote($filter->keyword) . "%' OR goods_id LIKE '%" . mysql_like_quote($filter->keyword) . "%') " : '';
    $where .= isset($filter->suppliers_id) && trim($filter->suppliers_id) != '' ?
        " AND (suppliers_id = '" . $filter->suppliers_id . "') " : '';

    $where .= isset($filter->in_ids) ? ' AND goods_id ' . db_create_in($filter->in_ids) : '';
    $where .= isset($filter->exclude) ? ' AND goods_id NOT ' . db_create_in($filter->exclude) : '';
    $where .= isset($filter->stock_warning) ? ' AND goods_number <= warn_number' : '';
    $where .= isset($filter->is_on_sale) ? ' AND is_on_sale = 1 ' : '';
    //是否为虚拟商品
    $where .= isset($filter->is_virtual) ? ' AND is_virtual = '.$filter->is_virtual.' ' : '';
    return $where;
}


/**
 * 重新计算购物车中的商品价格：目的是当用户登录时享受会员价格，当用户退出登录时不享受会员价格
 * 如果商品有促销，价格不变
 *
 * @access  public
 * @return  void
 */
function recalculate_price()
{
    /* 取得有可能改变价格的商品：除配件和赠品之外的商品 */
    $sql = 'SELECT c.rec_id, c.goods_id, c.goods_attr_id, g.promote_price, g.promote_start_date, c.goods_number,'.
        "g.promote_end_date, IFNULL(mp.user_price, g.shop_price * '$_SESSION[discount]') AS member_price ".
        'FROM ' . $GLOBALS['yke']->table(1,'cart') . ' AS c '.
        'LEFT JOIN ' . $GLOBALS['yke']->table(1,'goods') . ' AS g ON g.goods_id = c.goods_id g.sysid=c.sysid '.
        "LEFT JOIN " . $GLOBALS['yke']->table(1,'member_price') . " AS mp ".
        "ON mp.goods_id = g.goods_id and mp.sysid=g.sysid AND mp.user_rank = '" . $_SESSION['user_rank'] . "' ".
        "WHERE session_id = '" .SESS_ID. "' AND c.parent_id = 0 AND c.is_gift = 0 AND c.goods_id > 0 " .
        "AND c.rec_type = '" . CART_GENERAL_GOODS . "' AND c.extension_code <> 'package_buy'"." and c.sysid ='".$GLOBALS['sysid']."' ";

    $res = $GLOBALS['db']->getAll($sql);

    foreach ($res AS $row)
    {
        $attr_id    = empty($row['goods_attr_id']) ? array() : explode(',', $row['goods_attr_id']);


        $goods_price = get_final_price($row['goods_id'], $row['goods_number'], true, $attr_id);


        $goods_sql = "UPDATE " .$GLOBALS['yke']->table(1,'cart'). " SET goods_price = '$goods_price' ".
            "WHERE goods_id = '" . $row['goods_id'] . "' AND session_id = '" . SESS_ID . "' AND rec_id = '" . $row['rec_id'] . "'"." and sysid ='".$GLOBALS['sysid']."' ";

        $GLOBALS['db']->query($goods_sql);
    }

    $time1=local_strtotime('today');
    $time2=local_strtotime('today') + 86400;
    $sql = "select rec_id,goods_id,goods_attr,goods_number ".
        " from ".$GLOBALS['yke']->table(1,'cart')  ." where user_id=0 ".
        " AND session_id = '" .SESS_ID. "' AND parent_id = 0 ".
        " AND is_gift = 0 AND goods_id > 0 " .
        "AND rec_type = '" . CART_GENERAL_GOODS . "' AND extension_code <> 'package_buy' "." and sysid ='".$GLOBALS['sysid']."' ";
    $res = $GLOBALS['db']->query($sql);
    while ($row = $GLOBALS['db']->fetchRow($res))
    {
        $sql = "select rec_id from ".$GLOBALS['yke']->table(1,'cart')." where user_id='". $_SESSION['user_id'] ."' ".
            " AND add_time >='$time1' and add_time<'$time2' ".
            " AND goods_id='$row[goods_id]' and goods_attr= '$row[goods_attr]' "." and sysid ='".$GLOBALS['sysid']."' ";
        $rec_id = $GLOBALS['db']->getOne($sql);
        if($rec_id)
        {
            $sql = "update ".$GLOBALS['yke']->table(1,'cart')." set goods_number= goods_number + ".$row['goods_number'].
                " where rec_id='$rec_id' "." and sysid ='".$GLOBALS['sysid']."' ";
            $GLOBALS['db']->query($sql);
            $sql="delete from ".$GLOBALS['yke']->table(1,'cart')." where rec_id='".$row['rec_id']."'"." and sysid ='".$GLOBALS['sysid']."' ";
            $GLOBALS['db']->query($sql);
        }
        else
        {
            $sql = "update ".$GLOBALS['yke']->table(1,'cart')." set user_id='$_SESSION[user_id]' ".
                " where rec_id='".$row['rec_id']."'"." and sysid ='".$GLOBALS['sysid']."' ";
            $GLOBALS['db']->query($sql);
        }
    }

    /* 删除赠品，重新选择 */
    $GLOBALS['db']->query('DELETE FROM ' . $GLOBALS['yke']->table(1,'cart') .
        " WHERE session_id = '" . SESS_ID . "' AND is_gift > 0"." and sysid ='".$GLOBALS['sysid']."' ");
}

/*********************/
/*                   */
/*  Version : 5.1.0  */
/*  Author  : RM     */
/*  Comment : 071223 */
/*                   */
/*********************/

function get_linked_articlesex( $goods_id )
{
    $sql = "SELECT a.article_id, a.title, a.file_url, a.open_type, a.add_time, a.content FROM ".$GLOBALS['yke']->table( "goods_article" )." AS g, ".$GLOBALS['yke']->table( "article" )." AS a ".( "WHERE g.article_id = a.article_id and g.sysid=a.sysid AND g.goods_id = '".$goods_id."' AND a.is_open = 1 " ." and g.sysid ='".$GLOBALS['sysid']."' " )."ORDER BY a.add_time DESC";
    $res = $GLOBALS['db']->query( $sql );
    $arr = array( );
    while ( $row = $GLOBALS['db']->fetchRow( $res ) )
    {
        $row['url'] = $row['open_type'] != 1 ? build_uri( "article", array(
            "aid" => $row['article_id']
        ), $row['title'] ) : trim( $row['file_url'] );
        $row['add_time'] = local_date( $GLOBALS['_CFG']['date_format'], $row['add_time'] );
        $row['short_title'] = 0 < $GLOBALS['_CFG']['article_title_length'] ? sub_str( $row['title'], $GLOBALS['_CFG']['article_title_length'] ) : $row['title'];
        $arr[] = $row;
    }
    return $arr;
}

function get_goods_min_max_price( $goods_id )
{
    $goods = get_goods_info_ex( $goods_id );
    if ( $goods['is_promote'] && $goods['gmt_end_time'] )
    {
        $price = $goods['promote_price'];
    }
    else
    {
        $price = $goods['shop_price'];
    }
    $price_arr = array(
        "min" => $price - 200,
        "max" => $price + 200
    );
    return $price_arr;
}

function get_art_cat_name( $cat_id )
{
    $row = $GLOBALS['db']->getRow( "SELECT cat_name FROM ".$GLOBALS['yke']->table( "article_cat" ).( " WHERE cat_id = '".$cat_id."'"  ." and sysid ='".$GLOBALS['sysid']."' ") );
    return $row['cat_name'];
}

function get_goods_info_ex( $goods_id )
{
    $sql = "SELECT g.market_price, g.shop_price, g.promote_price, g.is_promote, g.promote_start_date, g.promote_end_date  FROM ".$GLOBALS['yke']->table( "goods" )." AS g ".( "WHERE g.goods_id = '".$goods_id."' AND g.is_delete = 0 "  ." and sysid ='".$GLOBALS['sysid']."' ");
    $row = $GLOBALS['db']->getRow( $sql );
    if ( $row !== FALSE )
    {
        if ( 0 < $row['promote_price'] )
        {
            $promote_price = bargain_price( $row['promote_price'], $row['promote_start_date'], $row['promote_end_date'] );
        }
        else
        {
            $promote_price = 0;
        }
        $row['promote_price'] = $promote_price;
        $time = gmtime( );
        if ( $row['promote_start_date'] <= $time && $time <= $row['promote_end_date'] )
        {
            $row['gmt_end_time'] = $row['promote_end_date'];
            return $row;
        }
        $row['gmt_end_time'] = 0;
    }
    return $row;
}

function get_goods_saving( $price, $market_price )
{
    $total = array( "saving" => 0, "save_rate" => 0 );
    $total['saving'] = $market_price - $price;
    $total['save_rate'] = $market_price != 0 ? round( $price / $market_price, 2 ) * 10 : 0;
    return $total;
}

function index_get_group_buyex( )
{
    $time = gmtime( );
    $limit = get_library_number( "group_buy", "index" );
    $group_buy_list = array( );
    if ( 0 < $limit )
    {
        $sql = "SELECT gb.act_id AS group_buy_id, gb.goods_id, gb.ext_info, gb.goods_name,gb.end_time, g.goods_thumb,g.market_price, g.goods_img, gb.act_desc FROM ".$GLOBALS['yke']->table( "goods_activity" )." AS gb, ".$GLOBALS['yke']->table( "goods" )." AS g WHERE gb.act_type = '".GAT_GROUP_BUY."' AND g.goods_id = gb.goods_id and g.sysid=gb.sysid AND gb.start_time <= '".$time."' AND gb.end_time >= '".$time."' AND g.is_delete = 0" ." and gb.sysid ='".$GLOBALS['sysid']."' "." ORDER BY gb.act_id DESC ".( "LIMIT ".$limit );
        $res = $GLOBALS['db']->query( $sql );
        while ( $row = $GLOBALS['db']->fetchRow( $res ) )
        {
            $row['market_price_no_format'] = $row['market_price'];
            $row['goods_img'] = get_image_path( $row['goods_id'], $row['goods_img'] );
            $row['thumb'] = get_image_path( $row['goods_id'], $row['goods_thumb'], TRUE );
            $ext_info = unserialize( $row['ext_info'] );
            $row = array_merge( $row, $ext_info );
            $price_ladder = $ext_info['price_ladder'];
            if ( !is_array( $price_ladder ) && empty( $price_ladder ) )
            {
                $row['last_price'] = price_format( 0 );
            }
            else
            {
                foreach ( $price_ladder as $amount_price )
                {
                    $price_ladder[$amount_price['amount']] = $amount_price['price'];
                }
            }
            ksort( $price_ladder );
            $row['last_price_no_format'] = end( $price_ladder );
            $row['last_price'] = price_format( end( $price_ladder ) );
            $stat = group_buy_stat( $row['group_buy_id'], $row['deposit'] );
            $row['valid_order'] = $stat['valid_order'];
            $row['url'] = build_uri( "group_buy", array(
                "gbid" => $row['group_buy_id']
            ) );
            $row['short_name'] = 0 < $GLOBALS['_CFG']['goods_name_length'] ? sub_str( $row['goods_name'], $GLOBALS['_CFG']['goods_name_length'] ) : $row['goods_name'];
            $row['short_style_name'] = add_style( $row['short_name'], "" );
            $row['market_price'] = price_format( $row['market_price'] );
            $row['end_time'] = local_date('M d, Y H:i:s',$row['end_time']);
            $group_buy_list[] = $row;
        }
    }
    return $group_buy_list;
}

function get_cat_recommend_type( $cid )
{
    $sql = "select rc.recommend_type from ".$GLOBALS['yke']->table( "category" )." as c left join ".$GLOBALS['yke']->table( "cat_recommend" ).( " as rc on c.cat_id = rc.cat_id and c.sysid=rc.sysid where c.cat_id=".$cid  ." and c.sysid ='".$GLOBALS['sysid']."' ");
    return $GLOBALS['db']->getOne( $sql );
}

function get_cart_goodsex( )
{
    $goods_list = array( );
    $total = array( "goods_price" => 0, "market_price" => 0, "saving" => 0, "save_rate" => 0, "goods_amount" => 0 );
    $sql = "SELECT *, IF(parent_id, parent_id, goods_id) AS pid  FROM ".$GLOBALS['yke']->table( "cart" )."  WHERE session_id = '".SESS_ID."' AND rec_type = '".CART_GENERAL_GOODS."'" ." and sysid ='".$GLOBALS['sysid']."' "." ORDER BY pid, parent_id";
    $res = $GLOBALS['db']->query( $sql );
    $virtual_goods_count = 0;
    $real_goods_count = 0;
    while ( $row = $GLOBALS['db']->fetchRow( $res ) )
    {
        $total['goods_price'] += $row['goods_price'] * $row['goods_number'];
        $total['market_price'] += $row['market_price'] * $row['goods_number'];
        $row['subtotal'] = price_format( $row['goods_price'] * $row['goods_number'], FALSE );
        $row['goods_price'] = price_format( $row['goods_price'], FALSE );
        $row['market_price'] = price_format( $row['market_price'], FALSE );
        if ( $row['is_real'] )
        {
            ++$real_goods_count;
        }
        else
        {
            ++$virtual_goods_count;
        }
        if ( trim( $row['goods_attr'] ) != "" )
        {
            $sql = "SELECT attr_value FROM ".$GLOBALS['yke']->table( "goods_attr" )." WHERE goods_attr_id ".db_create_in( $row['goods_attr'] ) ." and sysid ='".$GLOBALS['sysid']."' ";
            $attr_list = $GLOBALS['db']->getCol( $sql );
            foreach ( $attr_list as $attr )
            {
                $row['goods_name'] .= " [".$attr."] ";
            }
        }
        if ( ( $GLOBALS['_CFG']['show_goods_in_cart'] == "2" || $GLOBALS['_CFG']['show_goods_in_cart'] == "3" ) && $row['extension_code'] != "package_buy" )
        {
            $goods_thumb = $GLOBALS['db']->getOne( "SELECT `goods_thumb` FROM ".$GLOBALS['yke']->table( "goods" ).( " WHERE `goods_id`='".$row['goods_id']."'"  ." and sysid ='".$GLOBALS['sysid']."' ") );
            $row['goods_thumb'] = get_image_path( $row['goods_id'], $goods_thumb, TRUE );
        }
        if ( $row['extension_code'] == "package_buy" )
        {
            $row['package_goods_list'] = get_package_goods( $row['goods_id'] );
        }
        $goods_list[] = $row;
    }
    $total['goods_amount'] = $total['goods_price'];
    $total['saving'] = price_format( $total['market_price'] - $total['goods_price'], FALSE );
    if ( 0 < $total['market_price'] )
    {
        $total['save_rate'] = $total['market_price'] ? round( ( $total['market_price'] - $total['goods_price'] ) * 100 / $total['market_price'] )."%" : 0;
    }
    $total['goods_price'] = price_format( $total['goods_price'], FALSE );
    $total['market_price'] = price_format( $total['market_price'], FALSE );
    $total['real_goods_count'] = $real_goods_count;
    $total['virtual_goods_count'] = $virtual_goods_count;
    return array(
        "goods_list" => $goods_list,
        "total" => $total
    );
}

function get_categories_tree_ex( $cat_id = 0 )
{
    if ( 0 < $cat_id )
    {
        $sql = "SELECT parent_id FROM ".$GLOBALS['yke']->table( "category" ).( " WHERE cat_id = '".$cat_id."'"  ." and sysid ='".$GLOBALS['sysid']."' ");
        $parent_id = $GLOBALS['db']->getOne( $sql );
    }
    else
    {
        $parent_id = 0;
    }
    $sql = "SELECT count(*) FROM ".$GLOBALS['yke']->table( "category" ).( " WHERE parent_id = '".$parent_id."' AND is_show = 1 " ." and sysid ='".$GLOBALS['sysid']."' " );
    if ( $GLOBALS['db']->getOne( $sql ) || $parent_id == 0 )
    {
        $sql = "SELECT cat_id,cat_name ,parent_id,is_show FROM ".$GLOBALS['yke']->table( "category" ).( "WHERE parent_id = '".$parent_id."' AND is_show = 1" ." and sysid ='".$GLOBALS['sysid']."' "." ORDER BY sort_order ASC, cat_id ASC" );
        $res = $GLOBALS['db']->getAll( $sql );
        foreach ( $res as $row )
        {
            if ( $row['is_show'] )
            {
                $children = get_children( $row['cat_id'] );
                $count = get_cagtegory_goods_count_ex( $children );
                $cat_arr[$row['cat_id']]['id'] = $row['cat_id'];
                $cat_arr[$row['cat_id']]['name'] = $row['cat_name'];
                $cat_arr[$row['cat_id']]['url'] = build_uri( "category", array(
                    "cid" => $row['cat_id']
                ), $row['cat_name'] );
                $cat_arr[$row['cat_id']]['count'] = $count;
                if ( isset( $row['cat_id'] ) != NULL )
                {
                    $cat_arr[$row['cat_id']]['cat_id'] = get_child_tree_ex( $row['cat_id'] );
                }
            }
        }
    }
    if ( isset( $cat_arr ) )
    {
        return $cat_arr;
    }
}

function get_child_tree_ex( $tree_id = 0 )
{
    $three_arr = array( );
    $sql = "SELECT count(*) FROM ".$GLOBALS['yke']->table( "category" ).( " WHERE parent_id = '".$tree_id."' AND is_show = 1 " ." and sysid ='".$GLOBALS['sysid']."' " );
    if ( $GLOBALS['db']->getOne( $sql ) || $tree_id == 0 )
    {
        $child_sql = "SELECT cat_id, cat_name, parent_id, is_show FROM ".$GLOBALS['yke']->table( "category" ).( "WHERE parent_id = '".$tree_id."' AND is_show = 1" ." and sysid ='".$GLOBALS['sysid']."' "." ORDER BY sort_order ASC, cat_id ASC" );
        $res = $GLOBALS['db']->getAll( $child_sql );
        foreach ( $res as $row )
        {
            if ( $row['is_show'] )
            {
                $children = get_children( $row['cat_id'] );
                $count = get_cagtegory_goods_count_ex( $children );
                $three_arr[$row['cat_id']]['id'] = $row['cat_id'];
                $three_arr[$row['cat_id']]['name'] = $row['cat_name'];
                $three_arr[$row['cat_id']]['url'] = build_uri( "category", array(
                    "cid" => $row['cat_id']
                ), $row['cat_name'] );
                $three_arr[$row['cat_id']]['count'] = $count;
                if ( isset( $row['cat_id'] ) != NULL )
                {
                    $three_arr[$row['cat_id']]['cat_id'] = get_child_tree_ex( $row['cat_id'] );
                }
            }
        }
    }
    return $three_arr;
}

function get_cagtegory_goods_count_ex( $children, $brand = 0, $min = 0, $max = 0, $ext = "" )
{
    $where = "g.is_on_sale = 1 AND g.is_alone_sale = 1 AND g.is_delete = 0 AND (".$children." OR ".get_extension_goods( $children ).")";
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
    return $GLOBALS['db']->getOne( "SELECT COUNT(*) FROM ".$GLOBALS['yke']->table( "goods" ).( " AS g WHERE ".$where." {$ext}" ." and sysid ='".$GLOBALS['sysid']."' " ) );
}

function get_first_char( $str )
{
    $fchar = $str[0];
    if ( ord( "A" ) <= ord( $fchar ) && ord( $fchar ) <= ord( "z" ) )
    {
        return strtoupper( $fchar );
    }
    $str = iconv( "UTF-8", "gb2312", $str );
    $asc = ord( $str[0] ) * 256 + ord( $str[1] ) - 65536;
    if ( -20319 <= $asc && $asc <= -20284 )
    {
        return "A";
    }
    if ( -20283 <= $asc && $asc <= -19776 )
    {
        return "B";
    }
    if ( -19775 <= $asc && $asc <= -19219 )
    {
        return "C";
    }
    if ( -19218 <= $asc && $asc <= -18711 )
    {
        return "D";
    }
    if ( -18710 <= $asc && $asc <= -18527 )
    {
        return "E";
    }
    if ( -18526 <= $asc && $asc <= -18240 )
    {
        return "F";
    }
    if ( -18239 <= $asc && $asc <= -17923 )
    {
        return "G";
    }
    if ( -17922 <= $asc && $asc <= -17418 )
    {
        return "H";
    }
    if ( -17417 <= $asc && $asc <= -16475 )
    {
        return "I";
    }
    if ( -16474 <= $asc && $asc <= -16213 )
    {
        return "J";
    }
    if ( -16212 <= $asc && $asc <= -15641 )
    {
        return "K";
    }
    if ( -15640 <= $asc && $asc <= -15166 )
    {
        return "L";
    }
    if ( -15165 <= $asc && $asc <= -14923 )
    {
        return "M";
    }
    if ( -14922 <= $asc && $asc <= -14915 )
    {
        return "N";
    }
    if ( -14914 <= $asc && $asc <= -14631 )
    {
        return "P";
    }
    if ( -14630 <= $asc && $asc <= -14150 )
    {
        return "Q";
    }
    if ( -14149 <= $asc && $asc <= -14091 )
    {
        return "R";
    }
    if ( -14090 <= $asc && $asc <= -13319 )
    {
        return "S";
    }
    if ( -13318 <= $asc && $asc <= -12839 )
    {
        return "T";
    }
    if ( -12838 <= $asc && $asc <= -12557 )
    {
        return "W";
    }
    if ( -12556 <= $asc && $asc <= -11848 )
    {
        return "X";
    }
    if ( -11847 <= $asc && $asc <= -11056 )
    {
        return "Y";
    }
    if ( -11055 <= $asc && $asc <= -10247 )
    {
        return "Z";
    }
}

function get_all_brand( )
{
    $sql = "SELECT b.brand_id, b.brand_name, b.brand_logo, b.brand_desc, COUNT(*) AS goods_num, IF(b.brand_logo > '', '1', '0') AS tag FROM ".$GLOBALS['yke']->table( "brand" )."AS b, ".$GLOBALS['yke']->table( "goods" )." AS g ".( "WHERE g.brand_id = b.brand_id ".$children." AND is_show = 1 " )." AND g.is_on_sale = 1 AND g.is_alone_sale = 1 AND g.is_delete = 0 GROUP BY b.brand_id HAVING goods_num > 0" ." and sysid ='".$GLOBALS['sysid']."' "." ORDER BY  CONVERT( brand_name USING gbk ) COLLATE gbk_chinese_ci ASC";
    $brand_array = $GLOBALS['db']->getall( $sql );
    $brand_list = array( );
    $i = 0;
    for ( ;	$i < count( $brand_array );	++$i	)
    {
        $l = get_first_char( $brand_array[$i]['brand_name'] );
        $brand_list[$l][$brand_array[$i]['brand_id']] = $brand_array[$i]['brand_name']."-".$brand_array[$i]['brand_id'];
    }
    $show = "";
    foreach ( $brand_list as $row => $idx )
    {
        $show .= "<dl>\r\n\t\t\t\t\t<dt>".$row."</dt>\r\n\t\t\t\t <dd>\r\n\t\t\t\t  <ul>";
        foreach ( $idx as $row2 => $idx2 )
        {
            $idx2 = explode( "-", $idx2 );
            $show .= "<li> <a href='brand.php?sysessid=".$GLOBALS['sysid']."&id=".$idx2['1']."' target='_blank'>{$idx2['0']}</a> </li>";
        }
        $show .= "</ul>\r\n\t\t\t\t </dd>\r\n\t\t\t\t</dl>";
    }
    return $show;
}

function get_shop_help_ex( )
{
    $sql = "SELECT c.cat_id, c.cat_name, c.sort_order, a.article_id, a.title, a.file_url, a.open_type FROM ".$GLOBALS['yke']->table( "article" )." AS a LEFT JOIN ".$GLOBALS['yke']->table( "article_cat" )." AS c ON a.cat_id = c.cat_id and a.sysid=c.sysid WHERE c.cat_type = 5 AND a.is_open = 1 " ." and sysid ='".$GLOBALS['sysid']."' "." ORDER BY c.sort_order ASC, a.article_id";
    $res = $GLOBALS['db']->getAll( $sql );
    $arr = array( );
    foreach ( $res as $key => $row )
    {
        $arr[$row['cat_id']]['id'] = $row['cat_id'];
        $arr[$row['cat_id']]['cat_id'] = build_uri( "article_cat", array(
            "acid" => $row['cat_id']
        ), $row['cat_name'] );
        $arr[$row['cat_id']]['cat_name'] = $row['cat_name'];
        $arr[$row['cat_id']]['article'][$key]['article_id'] = $row['article_id'];
        $arr[$row['cat_id']]['article'][$key]['title'] = $row['title'];
        $arr[$row['cat_id']]['article'][$key]['short_title'] = 0 < $GLOBALS['_CFG']['article_title_length'] ? sub_str( $row['title'], $GLOBALS['_CFG']['article_title_length'] ) : $row['title'];
        $arr[$row['cat_id']]['article'][$key]['url'] = $row['open_type'] != 1 ? build_uri( "article", array(
            "aid" => $row['article_id']
        ), $row['title'] ) : trim( $row['file_url'] );
    }
    return $arr;
}

function index_get_links_ex( )
{
    $sql = "SELECT link_logo, link_name, link_url FROM ".$GLOBALS['yke']->table( "friend_link" ) ." where sysid ='".$GLOBALS['sysid']."' "." ORDER BY show_order";
    $res = $GLOBALS['db']->getAll( $sql );
    $links['img'] = $links['txt'] = array( );
    foreach ( $res as $row )
    {
        if ( !empty( $row['link_logo'] ) )
        {
            $links['img'][] = array(
                "name" => $row['link_name'],
                "url" => $row['link_url'],
                "logo" => $row['link_logo']
            );
        }
        else
        {
            $links['txt'][] = array(
                "name" => $row['link_name'],
                "url" => $row['link_url']
            );
        }
    }
    return $links;
}

function get_cat_new_arts( $cat_id, $num )
{
    if ( $cat_id == "-1" )
    {
        $cat_str = "cat_id > 0";
    }
    else
    {
        $cat_str = get_article_children( $cat_id );
    }
    $sql = "SELECT a.article_id, a.title, ac.cat_name, a.add_time, a.file_url, a.open_type, ac.cat_id, ac.cat_name  FROM ".$GLOBALS['yke']->table( "article" )." AS a, ".$GLOBALS['yke']->table( "article_cat" )." AS ac WHERE a.is_open = 1 and a.article_type = 0 AND a.cat_id = ac.cat_id and a.sysid=ac.sysid AND ac.cat_type = 1 AND a.".$cat_str ." and a.sysid ='".$GLOBALS['sysid']."' "." ORDER BY a.add_time DESC LIMIT ".$num;
    $res = $GLOBALS['db']->getAll( $sql );
    $arr = array( );
    foreach ( $res as $idx => $row )
    {
        $arr[$idx]['id'] = $row['article_id'];
        $arr[$idx]['title'] = $row['title'];
        $arr[$idx]['short_title'] = 0 < $GLOBALS['_CFG']['article_title_length'] ? sub_str( $row['title'], $GLOBALS['_CFG']['article_title_length'] ) : $row['title'];
        $arr[$idx]['cat_name'] = $row['cat_name'];
        $arr[$idx]['add_time'] = local_date( $GLOBALS['_CFG']['date_format'], $row['add_time'] );
        $arr[$idx]['url'] = $row['open_type'] != 1 ? build_uri( "article", array(
            "aid" => $row['article_id']
        ), $row['title'] ) : trim( $row['file_url'] );
        $arr[$idx]['cat_url'] = build_uri( "article_cat", array(
            "acid" => $row['cat_id']
        ), $row['cat_name'] );
    }
    return $arr;
}

function get_catinfo_byurl( $url )
{
    $rs = is_int( strpos( $url, "channel" ) );
    if ( !$rs )
    {
        $rs = is_int( strpos( $url, "category" ) );
    }
    if ( $rs )
    {
        preg_match( "/\\d+/i", $url, $matches );
        $cid = $matches[0];
        return $cid;
    }
    return FALSE;
}

function get_art_info( $art_id )
{
    $sql = "SELECT * FROM ".$GLOBALS['yke']->table( "article" ).( " WHERE is_open = 1 AND article_id = '".$art_id."'"  ." and sysid ='".$GLOBALS['sysid']."' ");
    $row = $GLOBALS['db']->getRow( $sql );
    return $row;
}

function build_uri_ex( $app, $params, $append = "", $page = 0, $keywords = "", $size = 0 )
{
    static $rewrite = NULL;
    if ( $rewrite === NULL )
    {
        $rewrite = intval( $GLOBALS['_CFG']['rewrite'] );
    }
    $args = array( "cid" => 0, "gid" => 0, "bid" => 0, "acid" => 0, "aid" => 0, "sid" => 0, "gbid" => 0, "auid" => 0, "sort" => "", "intro" => "", "order" => "" );
    extract( array_merge( $args, $params ) );
    $uri = "";
    switch ( $app )
    {
        case "category" :
            if ( empty( $cid ) )
            {
                return FALSE;
            }
            if ( $rewrite )
            {
                $uri = "category-".$cid;
                if ( isset( $bid ) )
                {
                    $uri .= "-b".$bid;
                }
                if ( isset( $price_min ) )
                {
                    $uri .= "-min".$price_min;
                }
                if ( isset( $price_max ) )
                {
                    $uri .= "-max".$price_max;
                }
                if ( isset( $filter_attr ) )
                {
                    $uri .= "-attr".$filter_attr;
                }
                if ( !empty( $page ) )
                {
                    $uri .= "-".$page;
                }
                if ( !empty( $sort ) )
                {
                    $uri .= "-".$sort;
                }
                if ( empty( $order ) )
                {
                    break;
                }
                $uri .= "-".$order;
            }
            else
            {
                $uri = "category.php?sysessid=".$GLOBALS['sysid']."&id=".$cid;
                if ( !empty( $bid ) )
                {
                    $uri .= "&amp;brand=".$bid;
                }
                if ( isset( $price_min ) )
                {
                    $uri .= "&amp;price_min=".$price_min;
                }
                if ( isset( $price_max ) )
                {
                    $uri .= "&amp;price_max=".$price_max;
                }
                if ( !empty( $filter_attr ) )
                {
                    $uri .= "&amp;filter_attr=".$filter_attr;
                }
                if ( !empty( $page ) )
                {
                    $uri .= "&amp;page=".$page;
                }
                if ( !empty( $sort ) )
                {
                    $uri .= "&amp;sort=".$sort;
                }
                if ( empty( $order ) )
                {
                    break;
                }
                $uri .= "&amp;order=".$order;
            }
            break;
        case "search" :
            if ( empty( $cid ) )
            {
                return FALSE;
            }
            if ( $rewrite )
            {
                $uri = "search-".$cid;
                if ( isset( $bid ) )
                {
                    $uri .= "-b".$bid;
                }
                if ( !empty( $intro ) )
                {
                    $uri .= "-i".$intro;
                }
                if ( isset( $price_min ) )
                {
                    $uri .= "-min".$price_min;
                }
                if ( isset( $price_max ) )
                {
                    $uri .= "-max".$price_max;
                }
                if ( isset( $filter_attr ) )
                {
                    $uri .= "-attr".$filter_attr;
                }
                if ( !empty( $page ) )
                {
                    $uri .= "-".$page;
                }
                if ( !empty( $sort ) )
                {
                    $uri .= "-".$sort;
                }
                if ( empty( $order ) )
                {
                    break;
                }
                $uri .= "-".$order;
            }
            else
            {
                $uri = "search.php?sysessid=".$GLOBALS['sysid']."&category=".$cid;
                if ( !empty( $intro ) )
                {
                    $uri .= "&amp;intro=".$intro;
                }
                if ( !empty( $bid ) )
                {
                    $uri .= "&amp;brand=".$bid;
                }
                if ( isset( $price_min ) )
                {
                    $uri .= "&amp;min_price=".$price_min;
                }
                if ( isset( $price_max ) )
                {
                    $uri .= "&amp;max_price=".$price_max;
                }
                if ( !empty( $filter_attr ) )
                {
                    $uri .= "&amp;filter_attr=".$filter_attr;
                }
                if ( !empty( $page ) )
                {
                    $uri .= "&amp;page=".$page;
                }
                if ( !empty( $sort ) )
                {
                    $uri .= "&amp;sort=".$sort;
                }
                if ( empty( $order ) )
                {
                    break;
                }
                $uri .= "&amp;order=".$order;
            }
            break;
        case "goods" :
            if ( empty( $gid ) )
            {
                return FALSE;
            }
            $uri = $rewrite ? "goods-".$gid : "goods.php?sysessid=".$GLOBALS['sysid']."&id=".$gid;
            break;
        case "brand" :
            if ( empty( $bid ) )
            {
                return FALSE;
            }
            if ( $rewrite )
            {
                $uri = "brand-".$bid;
                if ( isset( $cid ) )
                {
                    $uri .= "-c".$cid;
                }
                if ( !empty( $page ) )
                {
                    $uri .= "-".$page;
                }
                if ( !empty( $sort ) )
                {
                    $uri .= "-".$sort;
                }
                if ( empty( $order ) )
                {
                    break;
                }
                $uri .= "-".$order;
            }
            else
            {
                $uri = "brand.php?sysessid=".$GLOBALS['sysid']."&id=".$bid;
                if ( !empty( $cid ) )
                {
                    $uri .= "&amp;cat=".$cid;
                }
                if ( !empty( $page ) )
                {
                    $uri .= "&amp;page=".$page;
                }
                if ( !empty( $sort ) )
                {
                    $uri .= "&amp;sort=".$sort;
                }
                if ( empty( $order ) )
                {
                    break;
                }
                $uri .= "&amp;order=".$order;
            }
            break;
        case "article_cat" :
            if ( empty( $acid ) )
            {
                return FALSE;
            }
            if ( $rewrite )
            {
                $uri = "article_cat-".$acid;
                if ( !empty( $page ) )
                {
                    $uri .= "-".$page;
                }
                if ( !empty( $sort ) )
                {
                    $uri .= "-".$sort;
                }
                if ( !empty( $order ) )
                {
                    $uri .= "-".$order;
                }
                if ( empty( $keywords ) )
                {
                    break;
                }
                $uri .= "-".$keywords;
            }
            else
            {
                $uri = "article_cat.php?sysessid=".$GLOBALS['sysid']."&id=".$acid;
                if ( !empty( $page ) )
                {
                    $uri .= "&amp;page=".$page;
                }
                if ( !empty( $sort ) )
                {
                    $uri .= "&amp;sort=".$sort;
                }
                if ( !empty( $order ) )
                {
                    $uri .= "&amp;order=".$order;
                }
                if ( empty( $keywords ) )
                {
                    break;
                }
                $uri .= "&amp;keywords=".$keywords;
            }
            break;
        case "article" :
            if ( empty( $aid ) )
            {
                return FALSE;
            }
            $uri = $rewrite ? "article-".$aid : "article.php?sysessid=".$GLOBALS['sysid']."&id=".$aid;
            break;
        case "group_buy" :
            if ( empty( $gbid ) )
            {
                return FALSE;
            }
            $uri = $rewrite ? "group_buy-".$gbid : "group_buy.php?sysessid=".$GLOBALS['sysid']."&act=view&amp;id=".$gbid;
            break;
        case "auction" :
            if ( empty( $auid ) )
            {
                return FALSE;
            }
            $uri = $rewrite ? "auction-".$auid : "auction.php?sysessid=".$GLOBALS['sysid']."&act=view&amp;id=".$auid;
            break;
        case "snatch" :
            if ( empty( $sid ) )
            {
                return FALSE;
            }
            $uri = $rewrite ? "snatch-".$sid : "snatch.php?sysessid=".$GLOBALS['sysid']."&id=".$sid;
            break;
        case "search" :
        case "exchange" :
            if ( $rewrite )
            {
                $uri = "exchange-".$cid;
                if ( isset( $price_min ) )
                {
                    $uri .= "-min".$price_min;
                }
                if ( isset( $price_max ) )
                {
                    $uri .= "-max".$price_max;
                }
                if ( !empty( $page ) )
                {
                    $uri .= "-".$page;
                }
                if ( !empty( $sort ) )
                {
                    $uri .= "-".$sort;
                }
                if ( empty( $order ) )
                {
                    break;
                }
                $uri .= "-".$order;
            }
            else
            {
                $uri = "exchange.php?sysessid=".$GLOBALS['sysid']."&cat_id=".$cid;
                if ( isset( $price_min ) )
                {
                    $uri .= "&amp;integral_min=".$price_min;
                }
                if ( isset( $price_max ) )
                {
                    $uri .= "&amp;integral_max=".$price_max;
                }
                if ( !empty( $page ) )
                {
                    $uri .= "&amp;page=".$page;
                }
                if ( !empty( $sort ) )
                {
                    $uri .= "&amp;sort=".$sort;
                }
                if ( empty( $order ) )
                {
                    break;
                }
                $uri .= "&amp;order=".$order;
            }
            break;
        case "exchange_goods" :
            if ( empty( $gid ) )
            {
                return FALSE;
            }
            $uri = $rewrite ? "exchange-id".$gid : "exchange.php?sysessid=".$GLOBALS['sysid']."&id=".$gid."&amp;act=view";
            break;
            return FALSE;
    }
    if ( $rewrite == 2 && !empty( $append ) )
    {
        $uri .= "-".urlencode( preg_replace( "/[\\.|\\/|\\?|&|\\+|\\\\|'|\"|,]+/", "", $append ) );
    }
    $uri .= ".html";
    if ( $rewrite == 2 && strpos( strtolower( EC_CHARSET ), "utf" ) !== 0 )
    {
        $uri = urlencode( $uri );
    }
    return $uri;
}

function get_top_parent_cat( $nid )
{
    $sql = "select parent_id from ".$GLOBALS['yke']->table( "category" )." where cat_id = ".$nid."" ." and sysid ='".$GLOBALS['sysid']."' ";
    $pid = $GLOBALS['db']->getOne( $sql );
    return $pid;
}

function get_comment_count( $id, $type, $flag = 0 )
{
    $where = "";
    if ( $flag == 1 )
    {
        $where = "comment_rank = 5";
    }
    if ( $flag == 2 )
    {
        $where = "comment_rank = 3 or comment_rank = 4";
    }
    if ( $flag == 3 )
    {
        $where = "comment_rank = 1 or comment_rank = 2";
    }
    if ( 0 < $flag )
    {
        $where = " AND (".$where.")";
    }
    $sql = "SELECT COUNT(*) FROM ".$GLOBALS['yke']->table( "comment" ).( " WHERE id_value = '".$id."' AND comment_type = '{$type}' AND status = 1 AND parent_id = 0 " ).$where ." and sysid ='".$GLOBALS['sysid']."' ";
    $count = $GLOBALS['db']->getOne( $sql );
    return $count;
}

function assign_commentex( $id, $type, $page = 1, $flag = 0 )
{
    $ip_address = "";
    if ( 0 < $flag )
    {
        $ip_address = " AND ip_address = '".$flag."' ";
    }
    $count = $GLOBALS['db']->getOne( "SELECT COUNT(*) FROM ".$GLOBALS['yke']->table( "comment" ).( " WHERE id_value = '".$id."' AND comment_type = '{$type}' {$ip_address} AND status = 1 AND parent_id = 0"  ." and sysid ='".$GLOBALS['sysid']."' ") );
    $size = !empty( $GLOBALS['_CFG']['comments_number'] ) ? $GLOBALS['_CFG']['comments_number'] : 5;
    $page_count = 0 < $count ? intval( ceil( $count / $size ) ) : 1;
    $sql = "SELECT * FROM ".$GLOBALS['yke']->table( "comment" ).( " WHERE id_value = '".$id."' AND comment_type = '{$type}' {$ip_address} AND status = 1 AND parent_id = 0" ) ." and sysid ='".$GLOBALS['sysid']."' "." ORDER BY comment_id DESC";
    $res = $GLOBALS['db']->selectLimit( $sql, $size, ( $page - 1 ) * $size );
    $arr = array( );
    $ids = "";
    while ( ( $row = $GLOBALS['db']->fetchRow( $res ) ) ) // 修改 By  yikaiche.com
    {
        $ids .= $row['comment_id'];
        $arr[$row['comment_id']]['id'] = $row['comment_id'];
        $arr[$row['comment_id']]['email'] = $row['email'];
        $arr[$row['comment_id']]['username'] = $row['user_name'];
        $arr[$row['comment_id']]['content'] = str_replace( "\\r\\n", "<br />", htmlspecialchars( $row['content'] ) );
        $arr[$row['comment_id']]['content'] = nl2br( str_replace( "\\n", "<br />", $arr[$row['comment_id']]['content'] ) );
        $arr[$row['comment_id']]['rank'] = $row['comment_rank'];
        $arr[$row['comment_id']]['add_time'] = local_date( $GLOBALS['_CFG']['time_format'], $row['add_time'] );
    }
    if ( $ids )
    {
        $sql = "SELECT * FROM ".$GLOBALS['yke']->table( "comment" ).( " WHERE parent_id IN( ".$ids." )"  ." and sysid ='".$GLOBALS['sysid']."' ");
        $res = $GLOBALS['db']->query( $sql );
        while ( $row = $GLOBALS['db']->fetch_array( $res ) )
        {
            $arr[$row['parent_id']]['re_content'] = nl2br( str_replace( "\\n", "<br />", htmlspecialchars( $row['content'] ) ) );
            $arr[$row['parent_id']]['re_add_time'] = local_date( $GLOBALS['_CFG']['time_format'], $row['add_time'] );
            $arr[$row['parent_id']]['re_email'] = $row['email'];
            $arr[$row['parent_id']]['re_username'] = $row['user_name'];
        }
    }
    $gotopage = "gotoPage";
    if ( $type == 2 )
    {
        $gotopage = "gotoPage2";
    }
    $pager['page'] = $page;
    $pager['size'] = $size;
    $pager['record_count'] = $count;
    $pager['page_count'] = $page_count;
    $pager['page_first'] = "javascript:".$gotopage.( "(1,".$id.",{$type})" );
    $pager['page_prev'] = 1 < $page ? "javascript:".$gotopage."(".( $page - 1 ).( ",".$id.",{$type})" ) : "javascript:;";
    $pager['page_next'] = $page < $page_count ? "javascript:".$gotopage."(".( $page + 1 ).( ",".$id.",{$type})" ) : "javascript:;";
    $pager['page_last'] = $page < $page_count ? "javascript:".$gotopage."(".$page_count.( ",".$id.",{$type})" ) : "javascript:;";
    $cmt = array(
        "comments" => $arr,
        "pager" => $pager
    );
    return $cmt;
}

function get_cat_recommend_goods( $type = "", $cats = "", $cat_num = 0, $brand = 0, $min = 0, $max = 0, $ext = "" )
{
    $brand_where = 0 < $brand ? " AND g.brand_id = '".$brand."'" : "";
    $price_where = 0 < $min ? " AND g.shop_price >= ".$min." " : "";
    $price_where .= 0 < $max ? " AND g.shop_price <= ".$max." " : "";
    $sql = "SELECT g.goods_id, g.goods_name, g.goods_name_style,g.goods_sn,  g.market_price, g.shop_price AS org_price, g.promote_price, "
        .( "IFNULL(mp.user_price, g.shop_price * '".$_SESSION['discount']."') AS shop_price, " ).
        "(select AVG(r.comment_rank) from ".$GLOBALS['yke']->table( "comment" )." as r
          where r.id_value = g.goods_id AND r.comment_type = 0 AND r.parent_id = 0 AND r.status = 1" ." and r.sysid ='".$GLOBALS['sysid']."' ".") AS comment_rank,
        (select IFNULL(sum(og.goods_number), 0) from ".$GLOBALS['yke']->table( "order_goods" )." as og where og.goods_id = g.goods_id" ." and og.sysid ='".$GLOBALS['sysid']."' ".") AS sell_number, promote_start_date, promote_end_date, g.goods_brief, g.goods_thumb, goods_img, b.brand_name, b.brand_id, g.is_best, g.is_new, g.is_hot, g.is_promote FROM ".$GLOBALS['yke']->table( "goods" )." AS g LEFT JOIN ".$GLOBALS['yke']->table( "brand" )." AS b ON b.brand_id = g.brand_id and g.sysid=b.sysid LEFT JOIN ".$GLOBALS['yke']->table( "member_price" )." AS mp ".( "ON mp.goods_id = g.goods_id and mp.sysid=g.sysid AND mp.user_rank = '".$_SESSION['user_rank']."' " )."WHERE g.is_on_sale = 1 AND g.is_alone_sale = 1 AND g.is_delete = 0 ".$brand_where.$price_where.$ext ." and g.sysid ='".$GLOBALS['sysid']."' ";
    $num = 0;
    $type2lib = array( "best" => "recommend_best", "new" => "recommend_new", "hot" => "recommend_hot", "promote" => "recommend_promotion" );
    if ( $cat_num == 0 )
    {
        $num = get_library_number( $type2lib[$type] );
    }
    else
    {
        $num = $cat_num;
    }
    switch ( $type )
    {
        case "best" :
            $sql .= " AND is_best = 1";
            break;
        case "new" :
            $sql .= " AND is_new = 1";
            break;
        case "hot" :
            $sql .= " AND is_hot = 1";
            break;
        case "promote" :
            $time = gmtime( );
            $sql .= " AND is_promote = 1 AND promote_start_date <= '".$time."' AND promote_end_date >= '{$time}'";
    }
    if ( !empty( $cats ) )
    {
        $sql .= " AND (".$cats." OR ".get_extension_goods( $cats ).")";
    }
    $order_type = $GLOBALS['_CFG']['recommend_order'];
    $sql .= $order_type == 0 ? " ORDER BY g.sort_order, g.last_update DESC" : " ORDER BY RAND()";
    $res = $GLOBALS['db']->selectLimit( $sql, $num );
    $idx = 0;
    $goods = array( );
    while ( $row = $GLOBALS['db']->fetchRow( $res ) )
    {
        if ( 0 < $row['promote_price'] )
        {
            $promote_price = bargain_price( $row['promote_price'], $row['promote_start_date'], $row['promote_end_date'] );
            $goods[$idx]['promote_price'] = 0 < $promote_price ? price_format( $promote_price ) : "";
        }
        else
        {
            $goods[$idx]['promote_price'] = "";
        }
        $row['comment_rank'] = ceil( $row['comment_rank'] ) == 0 ? 5 : ceil( $row['comment_rank'] );
        $goods[$idx]['id'] = $row['goods_id'];
        $goods[$idx]['name'] = $row['goods_name'];
        $goods[$idx]['goods_sn'] = $row['goods_sn'];
        $goods[$idx]['comment_rank'] = $row['comment_rank'];
        $goods[$idx]['sell_number'] = $row['sell_number'];
        $goods[$idx]['is_new'] = $row['is_new'];
        $goods[$idx]['is_best'] = $row['is_best'];
        $goods[$idx]['is_hot'] = $row['is_hot'];
        $goods[$idx]['is_promote'] = $row['is_promote'];
        $goods[$idx]['brief'] = $row['goods_brief'];
        $goods[$idx]['brand_id'] = $row['brand_id'];
        $goods[$idx]['brand_name'] = $row['brand_name'];
        $goods[$idx]['short_name'] = 0 < $GLOBALS['_CFG']['goods_name_length'] ? sub_str( $row['goods_name'], $GLOBALS['_CFG']['goods_name_length'] ) : $row['goods_name'];
        $goods[$idx]['market_price'] = price_format( $row['market_price'] );
        $goods[$idx]['shop_price'] = price_format( $row['shop_price'] );
        $goods[$idx]['thumb'] = get_image_path( $row['goods_id'], $row['goods_thumb'], TRUE );
        $goods[$idx]['goods_img'] = get_image_path( $row['goods_id'], $row['goods_img'] );
        $goods[$idx]['url'] = build_uri( "goods", array(
            "gid" => $row['goods_id']
        ), $row['goods_name'] );
        $goods[$idx]['short_style_name'] = add_style( $goods[$idx]['short_name'], $row['goods_name_style'] );
        ++$idx;
    }
    return $goods;
}

function get_child_cat( $tree_id = 0, $num = 0 )
{
    $three_arr = array( );
    $sql = "SELECT count(*) FROM ".$GLOBALS['yke']->table( "category" ).( " WHERE parent_id = '".$tree_id."' AND is_show = 1 "  ." and sysid ='".$GLOBALS['sysid']."' ");
    if ( 0 < $num )
    {
        $where = " limit ".$num;
    }
    if ( $GLOBALS['db']->getOne( $sql ) || $tree_id == 0 )
    {
        $child_sql = "SELECT cat_id, cat_name, parent_id, is_show FROM ".$GLOBALS['yke']->table( "category" ).( "WHERE parent_id = '".$tree_id."' AND is_show = 1 " ." and sysid ='".$GLOBALS['sysid']."' "." ORDER BY sort_order ASC, cat_id ASC {$where}" );
        $res = $GLOBALS['db']->getAll( $child_sql );
        foreach ( $res as $row )
        {
            if ( $row['is_show'] )
            {
                $three_arr[$row['cat_id']]['id'] = $row['cat_id'];
                $three_arr[$row['cat_id']]['name'] = $row['cat_name'];
                $three_arr[$row['cat_id']]['url'] = build_uri( "category", array(
                    "cid" => $row['cat_id']
                ), $row['cat_name'] );
            }
        }
    }
    return $three_arr;
}

function get_cat_info_ex( $cid )
{
    $sql = "select * from ".$GLOBALS['yke']->table( "category" )." where cat_id = ".$cid."" ." and sysid ='".$GLOBALS['sysid']."' ";
    return $GLOBALS['db']->getRow( $sql );
}

function get_cat_name_ex( $cid )
{
    $sql = "select cat_name from ".$GLOBALS['yke']->table( "category" )." where cat_id = ".$cid."" ." and sysid ='".$GLOBALS['sysid']."' ";
    return $GLOBALS['db']->getOne( $sql );
}

function get_brand_info_ex( $bid )
{
    $sql = "select * from ".$GLOBALS['yke']->table( "brand" )." where brand_id = ".$bid."" ." and sysid ='".$GLOBALS['sysid']."' ";
    return $GLOBALS['db']->getRow( $sql );
}

function get_cat_brands( $cat, $num = 0, $app = "category" )
{
    $where = "";
    if ( $num != 0 )
    {
        $where = " limit ".$num;
    }
    $children = 0 < $cat ? " AND ".get_children( $cat ) : "";
    $sql = "SELECT b.brand_id, b.brand_name, b.brand_logo, COUNT(g.goods_id) AS goods_num, IF(b.brand_logo > '', '1', '0') AS tag FROM ".$GLOBALS['yke']->table( "brand" )."AS b, ".$GLOBALS['yke']->table( "goods" )." AS g ".( "WHERE g.brand_id = b.brand_id and b.sysid=g.sysid ".$children." " ) ." and b.sysid ='".$GLOBALS['sysid']."' "."GROUP BY b.brand_id HAVING goods_num > 0 ORDER BY tag DESC, b.sort_order ASC ".$where;
    $row = $GLOBALS['db']->getAll( $sql );
    foreach ( $row as $key => $val )
    {
        $row[$key]['id'] = $val['brand_id'];
        $row[$key]['name'] = $val['brand_name'];
        $row[$key]['logo'] = $val['brand_logo'];
        $row[$key]['url'] = build_uri( $app, array(
            "cid" => $cat,
            "bid" => $val['brand_id']
        ), $val['brand_name'] );
    }
    return $row;
}

function get_goods_gallerys( $goods_id )
{
    $sql = "SELECT img_id, img_url, thumb_url,img_original, img_desc FROM ".$GLOBALS['yke']->table( "goods_gallery" ).( " WHERE goods_id = '".$goods_id."'" ." and sysid ='".$GLOBALS['sysid']."' "." LIMIT " ).$GLOBALS['_CFG']['goods_gallery_number'];
    $row = $GLOBALS['db']->getAll( $sql );
    foreach ( $row as $key => $gallery_img )
    {
        $row[$key]['img_url'] = get_image_path( $goods_id, $gallery_img['img_url'], FALSE, "gallery" );
        $row[$key]['thumb_url'] = get_image_path( $goods_id, $gallery_img['thumb_url'], TRUE, "gallery" );
        $row[$key]['original_url'] = get_image_path( $goods_id, $gallery_img['img_original'], TRUE, "gallery" );
    }
    return $row;
}

function get_history( )
{
    $str = "";
    if ( !empty( $_COOKIE['YKE']['history'] ) )
    {
        $where = db_create_in( $_COOKIE['YKE']['history'], "goods_id" );
        $sql = "SELECT goods_id, goods_name, goods_thumb, shop_price, market_price FROM ".$GLOBALS['yke']->table( "goods" ).( " WHERE ".$where." AND is_on_sale = 1 AND is_alone_sale = 1 AND is_delete = 0"  ." and sysid ='".$GLOBALS['sysid']."' ");
        $query = $GLOBALS['db']->query( $sql );
        $res = array( );
        while ( $row = $GLOBALS['db']->fetch_array( $query ) )
        {
            $goods['goods_id'] = $row['goods_id'];
            $goods['goods_name'] = $row['goods_name'];
            $goods['short_name'] = 0 < $GLOBALS['_CFG']['goods_name_length'] ? sub_str( $row['goods_name'], $GLOBALS['_CFG']['goods_name_length'] ) : $row['goods_name'];
            $goods['goods_thumb'] = get_image_path( $row['goods_id'], $row['goods_thumb'], TRUE );
            $goods['shop_price'] = price_format( $row['shop_price'] );
            $goods['market_price'] = price_format( $row['market_price'] );
            $goods['url'] = build_uri( "goods", array(
                "gid" => $row['goods_id']
            ), $row['goods_name'] );
            $res[] = $goods;
        }
    }
    return $res;
}

function get_hotcate_tree( $rtype = "hot" )
{
    $parent_id = 0;
    $sql = "SELECT count(*) FROM ".$GLOBALS['yke']->table( "category" ).( " WHERE parent_id = '".$parent_id."' AND is_show = 1 "  ." and sysid ='".$GLOBALS['sysid']."' ");
    if ( $GLOBALS['db']->getOne( $sql ) || $parent_id == 0 )
    {
        $sql = "select c.* from ".$GLOBALS['yke']->table( "category" )." as c left join ".$GLOBALS['yke']->table( "cat_recommend" ).( " as rc on c.cat_id = rc.cat_id and c.sysid=rc.sysid where rc.recommend_type=3 and c.parent_id=".$parent_id ." and c.sysid ='".$GLOBALS['sysid']."' "." order by c.sort_order asc, c.cat_id asc" );
        $res = $GLOBALS['db']->getAll( $sql );
        foreach ( $res as $row )
        {
            if ( $row['is_show'] )
            {
                $cat_arr[$row['cat_id']]['id'] = $row['cat_id'];
                $cat_arr[$row['cat_id']]['name'] = $row['cat_name'];
                $cat_arr[$row['cat_id']]['url'] = build_uri( "category", array(
                    "cid" => $row['cat_id']
                ), $row['cat_name'] );
                if ( isset( $row['cat_id'] ) != NULL )
                {
                    $hotcat = get_hotcate( 0, $rtype );
                    if ( $hotcat )
                    {
                        foreach ( $hotcat as $key => $val )
                        {
                            $pid = get_top_parentid( $val['id'], "index" );
                            $hpid = $pid['cate_parentid'];
                            if ( !( $hpid == $cat_arr[$row['cat_id']]['id'] ) && !( $val['id'] != $cat_arr[$row['cat_id']]['id'] ) )
                            {
                                $cat_arr[$row['cat_id']]['cat_id'][$key] = $val;
                            }
                        }
                    }
                }
            }
        }
    }
    if ( isset( $cat_arr ) )
    {
        return $cat_arr;
    }
}

function get_top_parentid( $id = 0, $type = "" )
{
    if ( 0 < $id && $type != "" )
    {
        if ( $type == "goods" )
        {
            $sql = "SELECT cat_id FROM ".$GLOBALS['yke']->table( "goods" )." WHERE goods_id=".$id ." and sysid ='".$GLOBALS['sysid']."' ";
            $id = $GLOBALS['db']->getOne( $sql );
            $res['goods_parentid'] = $id;
        }
        while ( $id )
        {
            $sql = "SELECT\tcat_id,parent_id FROM ".$GLOBALS['yke']->table( "category" )." WHERE cat_id=".$id ." and sysid ='".$GLOBALS['sysid']."' ";
            $cat = $GLOBALS['db']->getRow( $sql );
            $id = $cat['parent_id'];
        }
        $res['cate_parentid'] = $cat['cat_id'];
        return $res;
    }
    return FALSE;
}

function get_hotcate( $num = 0, $rtype = "" )
{
    $sql = "SELECT cat_id,cat_name,parent_id FROM ".$GLOBALS['yke']->table( "category" )." WHERE " ." sysid ='".$GLOBALS['sysid']."' " and "cat_id in (SELECT DISTINCT cat_id FROM ".$GLOBALS['yke']->table( "cat_recommend" )." WHERE is_show=1 " ." and sysid ='".$GLOBALS['sysid']."' ";
    if ( $rtype == "best" )
    {
        $sql .= "AND recommend_type=1)";
    }
    else if ( $rtype == "new" )
    {
        $sql .= "AND recommend_type=2)";
    }
    else if ( $type == "hot" )
    {
        $sql .= "AND recommend_type=3)";
    }
    else
    {
        $sql .= "AND recommend_type in (1,2,3))";
    }
    $sql .= " ORDER BY sort_order ASC";
    if ( 0 < $num )
    {
        $sql .= " LIMIT ".$num;
    }
    $res = $GLOBALS['db']->getAll( $sql );
    foreach ( $res as $key => $val )
    {
        $arr[$key]['id'] = $val['cat_id'];
        $arr[$key]['name'] = $val['cat_name'];
        $arr[$key]['url'] = build_uri( "category", array(
            "cid" => $val['cat_id']
        ), $val['cat_name'] );
    }
    return $arr;
}

function get_cart_info( )
{
    $sql = "SELECT SUM(goods_number) AS number, SUM(goods_price * goods_number) AS amount FROM ".$GLOBALS['yke']->table( "cart" )." WHERE session_id = '".SESS_ID."' AND rec_type = '".CART_GENERAL_GOODS."'" ." and sysid ='".$GLOBALS['sysid']."' ";
    $row = $GLOBALS['db']->GetRow( $sql );
    if ( $row )
    {
        $number = intval( $row['number'] );
        $amount = floatval( $row['amount'] );
    }
    else
    {
        $number = 0;
        $amount = 0;
    }
    $str = sprintf( $GLOBALS['_LANG']['cart_info'], $number, price_format( $amount, FALSE ) );
    return $number.",".$amount;
}

function get_cat_by_type( $num, $rec = 3 )
{
    $arr = array( );
    $sql = "select c.* from ".$GLOBALS['yke']->table( "category" )." as c left join ".$GLOBALS['yke']->table( "cat_recommend" ).( " as rc on c.cat_id = rc.cat_id where rc.recommend_type=".$rec ." and sysid ='".$GLOBALS['sysid']."' "." order by c.sort_order asc, c.cat_id asc limit {$num}" );
    $res = $GLOBALS['db']->getAll( $sql );
    foreach ( $res as $row )
    {
        $arr[$row['cat_id']]['id'] = $row['cat_id'];
        $arr[$row['cat_id']]['name'] = $row['cat_name'];
        $arr[$row['cat_id']]['url'] = build_uri( "category", array(
            "cid" => $row['cat_id']
        ), $row['cat_name'] );
    }
    return $arr;
}



function get_top_class_cat( $nid )
{
    $sql = "select parent_id from ".$GLOBALS['yke']->table( "category" )." where cat_id = ".$nid."" ." and sysid ='".$GLOBALS['sysid']."' ";
    $temp_id = 0;
    $pid = $GLOBALS['db']->getOne( $sql );
    if ( 0 < $pid )
    {
        $temp_id = get_top_class_cat( $pid );
        return $temp_id;
    }
    $temp_id = $nid;
    return $temp_id;
}

function get_parent_cat_id( $nid )
{
    $sql = "select parent_id from ".$GLOBALS['yke']->table( "category" )." where cat_id = ".$nid."" ." and sysid ='".$GLOBALS['sysid']."' ";
    return $GLOBALS['db']->getOne( $sql );
}

function get_hot_cat_tree( $pid = 0, $rec = 3 )
{
    $arr = array( );
    $sql = "select c.*, rc.recommend_type from ".$GLOBALS['yke']->table( "category" )." as c left join ".$GLOBALS['yke']->table( "cat_recommend" ).( " as rc on c.cat_id = rc.cat_id and c.sysid=rc.sysid where c.parent_id=".$pid ." and c.sysid ='".$GLOBALS['sysid']."' "." order by c.sort_order asc, c.cat_id asc" );
    $res = $GLOBALS['db']->getAll( $sql );
    foreach ( $res as $row )
    {
        if ( $row['recommend_type'] == $rec )
        {
            $arr[$row['cat_id']]['id'] = $row['cat_id'];
            $arr[$row['cat_id']]['name'] = $row['cat_name'];
            $arr[$row['cat_id']]['url'] = build_uri( "category", array(
                "cid" => $row['cat_id']
            ), $row['cat_name'] );
        }
    }
    foreach ( $res as $row )
    {
        $arr2 = get_hot_cat_tree2( $row['cat_id'], $rec );
        if ( $arr2 )
        {
            $arr[$row['cat_id']]['child'] = $arr2;
        }
    }
    return $arr;
}

function get_hot_cat_tree2( $pid = 0, $rec = 3 )
{
    $arr = array( );
    $sql = "select c.*, rc.recommend_type from ".$GLOBALS['yke']->table( "category" )." as c left join ".$GLOBALS['yke']->table( "cat_recommend" ).( " as rc on c.cat_id = rc.cat_id and c.sysid=rc.sysid where rc.recommend_type=".$rec." and c.parent_id={$pid} " ." and c.sysid ='".$GLOBALS['sysid']."' "." order by c.sort_order asc, c.cat_id asc" );
    $res = $GLOBALS['db']->getAll( $sql );
    foreach ( $res as $row )
    {
        $arr[$row['cat_id']]['id'] = $row['cat_id'];
        $arr[$row['cat_id']]['name'] = $row['cat_name'];
        $arr[$row['cat_id']]['url'] = build_uri( "category", array(
            "cid" => $row['cat_id']
        ), $row['cat_name'] );
    }
    return $arr;
}

function get_adv( $position )
{
    $sql = "select ap.ad_width,ap.ad_height,ad.ad_id,ad.ad_name,ad.ad_code,ad.ad_link from ".$GLOBALS['yke']->table( "ad_position" )." as ap left join ".$GLOBALS['yke']->table( "ad" )." as ad on ad.position_id = ap.position_id and ad.sysid=ap.sysid where ap.position_name='".$position."' and ad.media_type=0 and UNIX_TIMESTAMP()>ad.start_time and UNIX_TIMESTAMP()<ad.end_time and ad.enabled=1 " ." and ap.sysid ='".$GLOBALS['sysid']."' "." order by ad.ad_id desc limit 1";
    $res = $GLOBALS['db']->getRow( $sql );
    if ( $res )
    {
        return "<a href='affiche.php?sysessid=".$GLOBALS['sysid']."&ad_id=".$res['ad_id']."&uri=".$res['ad_link']."' target='_blank'><img src='data/afficheimg/".$res['ad_code']."' width='".$res['ad_width']."' height='".$res['ad_height']."' /></a>";
    }
    return "";
}

function get_advlist( $position, $num )
{
    $arr = array( );
    $sql = "select ap.ad_width,ap.ad_height,ad.ad_id,ad.ad_name,ad.ad_code,ad.ad_link,ad.ad_id from ".$GLOBALS['yke']->table( "ad_position" )." as ap left join ".$GLOBALS['yke']->table( "ad" )." as ad on ad.position_id = ap.position_id and ad.sysid=ap.sysid where ap.position_name='".$position.( "' and UNIX_TIMESTAMP()>ad.start_time and UNIX_TIMESTAMP()<ad.end_time and ad.enabled=1" ." and ap.sysid ='".$GLOBALS['sysid']."' "." limit ".$num );
    $res = $GLOBALS['db']->getAll( $sql );
    foreach ( $res as $idx => $row )
    {
        $arr[$row['ad_id']]['name'] = $row['ad_name'];
        $arr[$row['ad_id']]['url'] = "affiche.php?sysessid=".$GLOBALS['sysid']."&ad_id=".$row['ad_id']."&uri=".$row['ad_link'];
        $arr[$row['ad_id']]['image'] = "data/afficheimg/".$row['ad_code'];
        $arr[$row['ad_id']]['content'] = "<a href='".$arr[$row['ad_id']]['url']."' target='_blank'><img src='data/afficheimg/".$row['ad_code']."' width='".$row['ad_width']."' height='".$row['ad_height']."' /></a>";
        $arr[$row['ad_id']]['ad_code'] = $row['ad_code'];
    }
    return $arr;
}

function get_new_comment( $type, $count )
{
    $arr = array( );
    $sql = "select c.*, g.goods_id, g.goods_thumb, g.goods_name from ".$GLOBALS['yke']->table( "comment" )." AS c  LEFT JOIN ".$GLOBALS['yke']->table( "goods" )." AS g ON c.id_value = g.goods_id and c.sysid=g.sysid where c.comment_type = ".$type." and c.status=1" ." and c.sysid ='".$GLOBALS['sysid']."' "." order by c.add_time desc limit ".$count;
    $res = $GLOBALS['db']->getAll( $sql );
    foreach ( $res as $idx => $row )
    {
        $arr[$idx]['id_value'] = $row['id_value'];
        $arr[$idx]['content'] = $row['content'];
        $arr[$idx]['comment_rank'] = $row['comment_rank'];
        $arr[$idx]['time'] = local_date( "m-d", $row['add_time'] );
        $arr[$idx]['goods_id'] = $row['goods_id'];
        $arr[$idx]['goods_name'] = $row['goods_name'];
        $arr[$idx]['goods_thumb'] = get_image_path( $row['goods_id'], $row['goods_thumb'], TRUE );
        $arr[$idx]['url'] = build_uri( "goods", array(
            "gid" => $row['goods_id']
        ), $row['goods_name'] );
    }
    return $arr;
}



/**
 * 取得商品列表：用于把商品添加到分销商品
 * @param   object  $filters    过滤条件
 */
function get_distrib_goods_list($filter)
{
    $filter->keyword = json_str_iconv($filter->keyword);
    $where = get_where_sql($filter); // 取得过滤条件
    /* 	if($where)
        {
            $where .= " AND g.supplier_id = 0 ";
        }
        else
        {
            $where .= " g.supplier_id = 0 ";
        } */

    /* 取得数据 */
    $sql = 'SELECT goods_id, goods_name, shop_price '.
        'FROM ' . $GLOBALS['yke']->table(1,'goods') . ' AS g ' . $where ." and sysid ='".$GLOBALS['sysid']."' ".
        'LIMIT 50';
    $row = $GLOBALS['db']->getAll($sql);

    return $row;
}

?>