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
 * 查询咨询内容
 *
 * @access  public
 * @params  integer     $id
 * @params  integer     $page
 * @return  array
 */
function assign_question($id, $page = 1, $question_type = 0)
{
    /* 取得咨询列表 */
    $count = $GLOBALS['db']->getOne('SELECT COUNT(*) FROM ' .$GLOBALS['yke']->table(1,'question').
        " WHERE id_value = '$id' AND status = 1 AND parent_id = 0 and question_type = '$question_type' "." and sysid ='".$GLOBALS['sysid']."' ");
    $size  = !empty($GLOBALS['_CFG']['comments_number']) ? $GLOBALS['_CFG']['comments_number'] : 5;

    $page_count = ($count > 0) ? intval(ceil($count / $size)) : 1;

    $sql = 'SELECT * FROM ' . $GLOBALS['yke']->table(1,'question') .
        " WHERE id_value = '$id'  AND status = 1 AND parent_id = 0 AND question_type= '$question_type' "." and sysid ='".$GLOBALS['sysid']."' ".
        ' ORDER BY question_id DESC';
    $res = $GLOBALS['db']->selectLimit($sql, $size, ($page-1) * $size);

    $arr = array();
    $ids = '';
    while ($row = $GLOBALS['db']->fetchRow($res))
    {
        $ids .= $ids ? ",$row[question_id]" : $row['question_id'];
        $arr[$row['question_id']]['id']       = $row['question_id'];
        $arr[$row['question_id']]['email']    = $row['email'];
        $arr[$row['question_id']]['username'] = $row['user_name'];
        $arr[$row['question_id']]['content']  = str_replace('\r\n', '<br />', htmlspecialchars($row['content']));
        $arr[$row['question_id']]['content']  = nl2br(str_replace('\n', '<br />', $arr[$row['question_id']]['content']));
        $arr[$row['question_id']]['add_time'] = local_date($GLOBALS['_CFG']['time_format'], $row['add_time']);
    }
    /* 取得已有回复的咨询 */
    if ($ids)
    {
        $sql = 'SELECT * FROM ' . $GLOBALS['yke']->table(1,'question') .
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
    $pager['page_first']   = "javascript:gotoPage_question(1,$id, $question_type)";
    $pager['page_prev']    = $page > 1 ? "javascript:gotoPage_question(" .($page-1). ",$id, $question_type)" : 'javascript:;';
    $pager['page_next']    = $page < $page_count ? 'javascript:gotoPage_question(' .($page + 1) . ",$id, $question_type)" : 'javascript:;';
    $pager['page_last']    = $page < $page_count ? 'javascript:gotoPage_question(' .$page_count. ",$id, $question_type)"  : 'javascript:;';

    $cmt = array('comments' => $arr, 'pager' => $pager);

    return $cmt;
}

?>