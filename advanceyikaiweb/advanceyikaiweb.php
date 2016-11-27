<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/4/27
 * Time: 15:09
 */
define('IN_YKE', true);

require(dirname(__FILE__) . '/includes/init_d.php');
$smarty->assign('project_path', $GLOBALS['project_path']);
$smarty->assign('project_path_js', $GLOBALS['project_path_js']);

$dwt_type = $_REQUEST['dwt_type'];
$dwt_id = $_REQUEST['dwt_id'];

if($dwt_id==1){
    $smarty->assign('page_info', json_encode(get_page_info()));
    $smarty->display('advance_yikai_web_edit.html');
}
else if($dwt_id==2){
    $smarty->assign('page_info', json_encode(get_page_info()));
    $smarty->display('advance_yikai_web_preview.html');
}


function get_page_info(){
    $db = $GLOBALS['db'];
    $sql = 'select * from ' .$GLOBALS['yke']->table(1,"web_advance");
    return $db->getAll($sql);
}




