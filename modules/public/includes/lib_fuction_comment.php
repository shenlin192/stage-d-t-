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
 * 查询评论内容
 *
 * @access  public
 * @params  integer     $id
 * @params  integer     $type
 * @params  integer     $page
 * @return  array
 */
function assign_comment($id, $type, $page = 1,$comment_level=0)
{
    /* 取得评论列表 */
    if ($comment_level == '1')
    {
        $sql_comment=" AND comment_rank in ('4', '5') ";
    }
    elseif ($comment_level == '2')
    {
        $sql_comment=" AND comment_rank in ('3', '2') ";
    }
    elseif ($comment_level == '3')
    {
        $sql_comment=" AND comment_rank in ('1') ";
    }else{
        $sql_comment=" ";
    }

    $count = $GLOBALS['db']->getOne('SELECT COUNT(*) FROM ' .$GLOBALS['yke']->table(1,'comment').
        " WHERE id_value = '$id' AND comment_type = '$type' AND status = 1 AND parent_id = 0 $sql_comment "." and sysid ='".$GLOBALS['sysid']."' ");
    $size  = !empty($GLOBALS['_CFG']['comments_number']) ? $GLOBALS['_CFG']['comments_number'] : 5;

    $page_count = ($count > 0) ? intval(ceil($count / $size)) : 1;

    $sql = 'SELECT * FROM ' . $GLOBALS['yke']->table(1,'comment') .
        " WHERE id_value = '$id' AND comment_type = '$type' AND status = 1 AND parent_id = 0 $sql_comment "." and sysid ='".$GLOBALS['sysid']."' ".
        ' ORDER BY comment_id DESC';
    $res = $GLOBALS['db']->selectLimit($sql, $size, ($page-1) * $size);

    $arr = array();
    $ids = '';
    while ($row = $GLOBALS['db']->fetchRow($res))
    {
        $ids .= $ids ? ",$row[comment_id]" : $row['comment_id'];
        $arr[$row['comment_id']]['id']       = $row['comment_id'];
        $arr[$row['comment_id']]['email']    = $row['email'];
        $arr[$row['comment_id']]['username'] = $row['user_name'];
        $arr[$row['comment_id']]['title'] = $row['title'];
        $arr[$row['comment_id']]['content']  = str_replace('\r\n', '<br />', htmlspecialchars($row['content']));
        $arr[$row['comment_id']]['content']  = nl2br(str_replace('\n', '<br />', $arr[$row['comment_id']]['content']));
        $arr[$row['comment_id']]['rank']     = $row['comment_rank'];
        $arr[$row['comment_id']]['add_time'] = local_date($GLOBALS['_CFG']['time_format'], $row['add_time']);
        if($row['user_id'])
        {
            $arr[$row['comment_id']]['headimg'] = $GLOBALS['db']->getOne('select headimg from '. $GLOBALS['yke']->table(1,'users') .' where user_id=\''. $row['user_id'] .'\''." and sysid ='".$GLOBALS['sysid']."' ");
        }

        /* 会员级别 */
        $sql_ur = "select user_rank  from " . $GLOBALS['yke']->table(1,'users') . " where user_id = '$row[user_id]' "." and sysid ='".$GLOBALS['sysid']."' ";
        $userrankid = $GLOBALS['db']->getOne($sql_ur);
        $arr[$row['comment_id']]['userrankid'] = $userrankid ? $userrankid : 0;
    }
    /* 取得已有回复的评论 */
    if ($ids)
    {
        $sql = 'SELECT * FROM ' . $GLOBALS['yke']->table(1,'comment') .
            " WHERE parent_id IN( $ids )"." and sysid ='".$GLOBALS['sysid']."' ";
        $res = $GLOBALS['db']->query($sql);
        while ($row = $GLOBALS['db']->fetch_array($res))
        {
            $arr[$row['parent_id']]['re_content']  = nl2br(str_replace('\n', '<br />', htmlspecialchars($row['content'])));
            $arr[$row['parent_id']]['re_add_time'] = local_date($GLOBALS['_CFG']['time_format'], $row['add_time']);
            $arr[$row['parent_id']]['re_email']    = $row['email'];
            $arr[$row['parent_id']]['re_username'] = $row['user_name'];
        }
    }
    /* 分页样式 */
    //$pager['styleid'] = isset($GLOBALS['_CFG']['page_style'])? intval($GLOBALS['_CFG']['page_style']) : 0;
    $pager['page']         = $page;
    $pager['size']         = $size;
    $pager['record_count'] = $count;
    $pager['page_count']   = $page_count;
    $pager['page_first']   = "javascript:gotoPage(1,$id,$type,$comment_level)";
    $pager['page_prev']    = $page > 1 ? "javascript:gotoPage(" .($page-1). ",$id,$type,$comment_level)" : 'javascript:;';
    $pager['page_next']    = $page < $page_count ? 'javascript:gotoPage(' .($page + 1) . ",$id,$type,$comment_level)" : 'javascript:;';
    $pager['page_last']    = $page < $page_count ? 'javascript:gotoPage(' .$page_count. ",$id,$type,$comment_level)"  : 'javascript:;';

    $cmt = array('comments' => $arr, 'pager' => $pager);

    return $cmt;
}


?>