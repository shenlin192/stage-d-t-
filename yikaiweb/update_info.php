<?php

define('IN_YKE', true);
require(dirname(__FILE__) . '/includes/init_d.php');

//获取新的test_info
$page_info = $_POST['page_info'];
//weixindebug($page_info,'$page_info',0,'','','','admin/update_info.php:13',13);

update_database($page_info);


function update_database($page_info){

//    weixindebug($page_info['elementId'],'elementId',0,'','','','admin/save_element.php:26',26);
    $db = $GLOBALS['db'];
    $sql= "delete from " . $GLOBALS['yke']->table(1,'simplified_version') ." where 1 ";
    $db->query($sql);

    foreach ($page_info as $key => $item) {
        //weixindebug($item,'$item'.$key,0,'','','','admin/save_element.php:26',26);
            $sql = "insert into   " . $GLOBALS['yke']->table(1,'simplified_version') ."
            (`elementId`,`typeId`,`typeName`,`text`,`imgUrl1`,`itemUrl1`,`textUrl`)
            values('" . $item['elementId'] . "','" . $item['typeId'] . "','" . $item['typeName'] . "','" . $item['text'] . "','" . $item['imgUrl1'] . "','" . $item['itemUrl1'] . "','" . $item['textUrl']. "')";
            $db->query($sql);
//        weixindebug($sql,'$sql',0,'','','','admin/save_element.php:26',26);

    }
}


