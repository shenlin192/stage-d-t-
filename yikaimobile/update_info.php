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

//获取新的test_info
$page_info = $_POST['page_info'];
//weixindebug($page_info,'$page_info',0,'','','','admin/update_info.php:13',13);

update_database($page_info);


function update_database($page_info){
    $db = $GLOBALS['db'];
    $sql= "delete from " . $GLOBALS['yke']->table(1,'supplier_page_lib_items') ." where 1 ";
    $db->query($sql);

    foreach ($page_info as $item) {
        $sql = "insert into  " . $GLOBALS['yke']->table(1,'supplier_page_lib_items') ."
        (`sysid`,`supplier_id`,`page_id`,`row`,`lib_id`,`lib_title`,`lib_detail`,`type`,`type_of_lib31a`,`type_of_lib31b`,`type_of_lib32a`,`type_of_lib32b`,`lib_text1`,`lib_text2`,`lib_text3`,`lib_text4`,`img_url1`,`img_url2`,`img_url3`,`img_url4`,`item_url1`,`item_url2`,`item_url3`,`item_url4`,`price1`,`price2`,`lib_num`,`lib_num_max`,`visibility`)
        values('".$item['sysid']."',".$item['supplier_id'].",".$item['page_id'].",".$item['row'].",".$item['lib_id'].",'".$item['lib_title']."','".$item['lib_detail']."',".
            "'".$item['type']."','".$item['type_of_lib31a']."','".$item['type_of_lib31b']."','".$item['type_of_lib32a']."','".$item['type_of_lib32b']."','".$item['lib_text1']."','". $item['lib_text2']."','".$item['lib_text3']."','".$item['lib_text4']."','".$item['img_url1']."','". $item['img_url2']."','".$item['img_url3']."','".$item['img_url4']."','". $item['item_url1']."','".$item['item_url2']."','".$item['item_url3']."','".$item['item_url4']."','".$item['price1']."','".$item['price2']."',". $item['lib_num'].",".$item['lib_num_max'].",".$item['visibility'].")";
        $db->query($sql);
echo $sql;
    }
}


