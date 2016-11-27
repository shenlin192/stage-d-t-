<?php
/**
 * Created by PhpStorm.
 * User: shenlin
 * Date: 2016/6/20
 * Time: 15:19
 */

//以下两行好像是默认路径的设置
define('IN_YKE', true);
require(dirname(__FILE__) . '/includes/init_d.php');

//引入老李自定义的调试调试文件
include_once (PUBLIC_PATH.'includes/cls_debug.php');

//获取新的test_info
$page_info = $_POST['page_info'];
//weixindebug($page_info,'$page_info',0,'','','','admin/update_info.php:13',13);

update_database($page_info);


function update_database($page_info){

//    weixindebug($page_info['elementId'],'elementId',0,'','','','admin/save_element.php:26',26);
    $db = $GLOBALS['db'];
    $sql= "delete from " . $GLOBALS['yke']->table(1,'wqd_elements_detail') ." where 1 ";
    $db->query($sql);

    foreach ($page_info as $key => $item) {
        weixindebug($item,'$item'.$key,0,'','','','admin/save_element.php:26',26);
            $sql = "insert into  " . $GLOBALS['yke']->table(1,'wqd_elements_detail') ."
            (`elementId`,`libId`,`libName`,`positionX`,`positionY`,`height`,`width`,`rotation`,`draggable`,`rotatable`,`copyable`,`resizable`,`elementTitle`)
            values('" . $item['elementId'] . "','" . $item['libId'] . "','" . $item['libName'] . "','" . $item['positionX'] . "','" . $item['positionY'] . "','" . $item['height'] . "','" . $item['width'] . "'," .
                "'" . $item['rotation'] . "','" . $item['draggable'] . "','" . $item['rotatable'] . "','" . $item['copyable'] . "','" . $item['resizable'] . "','" . $item['elementTitle'] . "')";
            $db->query($sql);
//        weixindebug($sql,'$sql',0,'','','','admin/save_element.php:26',26);

    }
}


