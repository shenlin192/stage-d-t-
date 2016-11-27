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
 * 插入一个配置信息
 *
 * @access  public
 * @param   string      $parent     分组的code
 * @param   string      $code       该配置信息的唯一标识
 * @param   string      $value      该配置信息值
 * @return  void
 */
function insert_config($parent, $code, $value)
{
    global $yke, $db, $_LANG;

    $sql = 'SELECT id FROM ' . $yke->table(1,'shop_config') . " WHERE code = '$parent' AND type = 1"." and sysid ='".$GLOBALS['sysid']."' ";
    $parent_id = $db->getOne($sql);

    $sql = 'INSERT INTO ' . $yke->table(1,'shop_config') . ' (sysid,parent_id, code, value) ' .
        "VALUES("."'".$GLOBALS['sysid']."',"."'$parent_id', '$code', '$value')";
    $db->query($sql);
}
/**
 * 插入一个配置信息
 *
 * @access  public
 * @param   string      $parent     分组的code
 * @param   string      $code       该配置信息的唯一标识
 * @param   string      $value      该配置信息值
 * @return  void
 */
function insert_smartconfig($parent, $code, $value)
{
    global $yke, $db, $_LANG;

    $sql = 'SELECT id FROM ' . $yke->table(1,'ykesmart_shop_config') . " WHERE code = '$parent' AND type = 1"." and sysid ='".$GLOBALS['sysid']."' ";
    $parent_id = $db->getOne($sql);

    $sql = 'INSERT INTO ' . $yke->table(1,'ykesmart_shop_config',1) . ' (sysid,parent_id, code, value) ' .
        "VALUES("."'".$GLOBALS['sysid']."',"."'$parent_id', '$code', '$value')";
    $db->query($sql);
}


?>