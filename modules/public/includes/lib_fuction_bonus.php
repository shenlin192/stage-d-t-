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
 * 查询会员的红包金额
 *
 * @access  public
 * @param   integer     $user_id
 * @return  void
 */
function get_user_bonus($user_id = 0)
{
    if ($user_id == 0)
    {
        $user_id = $_SESSION['user_id'];
    }

    $sql = "SELECT SUM(bt.type_money) AS bonus_value, COUNT(*) AS bonus_count ".
        "FROM " .$GLOBALS['yke']->table(1,'user_bonus'). " AS ub, ".
        $GLOBALS['yke']->table(1,'bonus_type') . " AS bt ".
        "WHERE ub.user_id = '$user_id' AND ub.bonus_type_id = bt.type_id and ub.sysid=bt.sysid AND ub.order_id = 0"." and ub.sysid ='".$GLOBALS['sysid']."' ";
    $row = $GLOBALS['db']->getRow($sql);

    return $row;
}

/**
 * 取得红包类型数组（用于生成下拉列表）
 *
 * @return  array       分类数组 bonus_typeid => bonus_type_name
 */
function get_bonus_type()
{
    $bonus = array();
    $sql = 'SELECT type_id, type_name, type_money FROM ' . $GLOBALS['yke']->table(1,'bonus_type') .
        ' WHERE send_type = 3'." and sysid ='".$GLOBALS['sysid']."' ";
    $res = $GLOBALS['db']->query($sql);

    while ($row = $GLOBALS['db']->fetchRow($res))
    {
        $bonus[$row['type_id']] = $row['type_name'].' [' .sprintf($GLOBALS['_CFG']['currency_format'], $row['type_money']).']';
    }

    return $bonus;
}
?>