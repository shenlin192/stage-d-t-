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
    $smarty->assign('simplified_page_info', json_encode(get_simplified_page_info()));
    $smarty->display('yikaiweb.html');
}
else if($dwt_id==2){
    $smarty->assign('simplified_page_info', json_encode(get_simplified_page_info()));
    $smarty->display('yikaiwebpreview.html');
}
else if($dwt_id==3){
    $smarty->assign('elements_detail', json_encode(get_elements_detail()));
    $smarty->assign('elements_type', json_encode(get_elements_type()));
    $smarty->display('yikaiwebtest.html');
}

function get_simplified_page_info(){
    $db = $GLOBALS['db'];
    $sql = 'select * from ' .$GLOBALS['yke']->table(1,"simplified_version");
    return $db->getAll($sql);
}

function get_elements_detail(){
    $db = $GLOBALS['db'];
    $sql = 'select * from ' .$GLOBALS['yke']->table(1,"wqd_elements_detail");
    return $db->getAll($sql);
}

function get_elements_type(){
    $db = $GLOBALS['db'];
    $sql = 'select * from ' .$GLOBALS['yke']->table(1,"wqd_elements_type");
    return $db->getAll($sql);
}

function get_template(){
    $db = $GLOBALS['db'];
    $sql = 'select * from ' .$GLOBALS['yke']->table(1,"admin_lib");
    return $db->getAll($sql);
}



