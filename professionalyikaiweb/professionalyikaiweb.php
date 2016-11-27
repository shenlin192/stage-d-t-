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
    $smarty->assign('section_info', json_encode(get_section_info()));
    $smarty->assign('component_info', json_encode(get_component_info()));
    $smarty->display('professional_yikai_web_edit.html');
}
else if($dwt_id==2){
    $smarty->assign('section_info', json_encode(get_section_info()));
    $smarty->display('professional_yikai_web_preview.html');
}else if($dwt_id==3){
    $smarty->assign('layout_info', json_encode(get_layout_info()));
    $smarty->display('professional_yikai_web_layout.html');
}

function get_component_info(){
    $db = $GLOBALS['db'];
    $sql = 'select * from ' .$GLOBALS['yke']->table(1,"web_professional_section_components")." order by componentid ASC";
    $row=$db->getAll($sql);

    weixindebug($row,'$row',0,'','','','professionalyikaiweb/professionalyikaiweb.php:46',46);

    return $row;
}


function get_section_info(){
    $db = $GLOBALS['db'];
    $sql = 'select * from ' .$GLOBALS['yke']->table(1,"web_professional_section_items");

    $row=$db->getAll($sql);
    return $row;
}


function get_layout_info(){
    $db = $GLOBALS['db'];
    $sql = 'select * from ' .$GLOBALS['yke']->table(1,"web_professional_layout_items");
    return $db->getAll($sql);
}