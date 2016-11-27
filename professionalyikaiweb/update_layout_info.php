<?php
/**
 * Created by PhpStorm.
 * User: shenlin
 * Date: 2016/9/9
 * Time: 14:24
 */
define('IN_YKE', true);
require(dirname(__FILE__) . '/includes/init_d.php');


//获取新的test_info
$layout_info = $_POST['layout_info'];

update_database($layout_info);

function update_database($layout_info){

    $db = $GLOBALS['db'];
    $sql= "delete from " . $GLOBALS['yke']->table(1,'web_professional_layout_items') ." where 1 ";
    $db->query($sql);

    foreach ($layout_info as $key => $item) {

        $sql = "insert into   " . $GLOBALS['yke']->table(1,'web_professional_layout_items') ."
            (`projectName`,`dwtId`,`pageId`,`sectionId`,`sectionContain`,`positionX`,`positionY`)
            values('" . $item['projectName'] . "','" . $item['dwtId'] . "','" . $item['pageId'] . "','" . $item['sectionId'] . "','" . $item['sectionContain'] . "','" . $item['positionX'] . "','" . $item['positionY'] ."')";
        $db->query($sql);

    }

}
echo success;