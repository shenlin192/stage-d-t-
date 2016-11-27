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

$supplier_id=0;
$page_id=1;


if($dwt_id==1) {
    $smarty->assign('page_template', json_encode(get_template()));
    $smarty->assign('page_info', json_encode(get_info($supplier_id, $page_id)));
    $smarty->display('index2.html');
}else if($dwt_id==1.1){
    $smarty->assign('page_template', json_encode(get_template()));
    $smarty->assign('page_info', json_encode(get_info($supplier_id, $page_id)));
    $smarty->display('index3.html');
} else if($dwt_id==1.2){
//    $smarty->assign('page_template', json_encode(get_template()));
//    $smarty->assign('page_info', json_encode(get_info($supplier_id, $page_id)));
    $smarty->display('test.html');
}else if($dwt_id==2){
    $smarty->assign('page_info', json_encode(get_info($supplier_id, $page_id)));
    $smarty->display('preview.html');
}


function get_template(){
    $db = $GLOBALS['db'];
    $sql = 'select * from ' .$GLOBALS['yke']->table(1,"admin_lib");
    return $db->getAll($sql);
}

function get_info($supplier_id,$page_id){
    //根据supplier_id和page_id查表yke_supplier_page判断此用户是否已经保存过模板
    $db = $GLOBALS['db'];
    $sql = 'select * from' .$GLOBALS['yke']->table(1,"supplier_page") .
        'WHERE supplier_id=' . $supplier_id . ' and page_id=' . $page_id . "  and sysid='" . $GLOBALS["sysid"] . "'";
    $result = $db->getRow($sql);

    if($result) {
        //若已存在则查找对应模板的内容
        $sql_1 = 'select * from' .$GLOBALS['yke']->table(1,"supplier_page_lib_items") .
            'WHERE supplier_id=' . $supplier_id . ' and page_id=' . $page_id . "  and sysid='" . $GLOBALS["sysid"] . "'";
        $result_1 = $db->getAll($sql_1);
        return $result_1;
    }else{
        //若不存在则根据page_id查询表yke_admin_page_dwt判断模板市场是否存在模板
        $sql_2 = 'select * from' .$GLOBALS['yke']->table(1,"admin_page_dwt") .
            'WHERE page_id=' . $page_id . "  and sysid='" . $GLOBALS["sysid"] . "'";
        $result_2 = $db->getAll($sql_2);
            
        if($result_2){
                //若模板市场存在模板，则让用户在yke_admin_page_dwt选择想要的已经存在的页面模板
                $sql_2_1 = 'select * from' .$GLOBALS['yke']->table(1,"admin_page_dwt_lib_items") .
                    'WHERE page_id=' . $page_id . "  and sysid='" . $GLOBALS["sysid"] . "'";
                $result_2_1 = $db->getCol($sql);
                return $result_2_1;
        }else{
                //若模板市场不存在模板则根据page_id查询表yke_admin_page_ini确定这个page可以使用的不同组件的最大数量
                $sql_2_2 = 'select * from' .$GLOBALS['yke']->table(1,"admin_page_ini") .
                    'WHERE page_id=' . $page_id . "  and sysid='" . $GLOBALS["sysid"] . "'";
                $result_2_2 = $db->getAll($sql_2_2);
                return  $result_2_2;
            }
       }
}






