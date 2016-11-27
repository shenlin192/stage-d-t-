<?php
/**
 * Created by PhpStorm.
 * User: shenlin
 * Date: 2016/9/6
 * Time: 9:32
 */
define('IN_YKE', true);
require(dirname(__FILE__) . '/includes/init_d.php');


//获取新的test_info
$page_info = $_POST['page_info'];

update_database($page_info);

function update_database($page_info){
    
    $db = $GLOBALS['db'];
    $sql= "delete from " . $GLOBALS['yke']->table(1,'web_professional_section_items') ." where 1 ";
    $db->query($sql);

    foreach ($page_info as $key => $item) {
     
            $sql = "insert into   " . $GLOBALS['yke']->table(1,'web_professional_section_items') ."
            (`sectionId`,`elementId`,`elementTypeId`,`elementTypeName`,`draggable`,`positionX`,`positionY`,`resizable`,`width`,`height`,`rotatable`,`degree`,`zIndex`,`content`,`imgUrl`,`itemUrl`)
            values('" . $item['sectionId'] . "','" . $item['elementId'] . "','" . $item['elementTypeId'] . "','" . $item['elementTypeName'] . "','" . $item['draggable'] . "','" . $item['positionX'] . "','" . $item['positionY'] . "','" . $item['resizable'] . "','" . $item['width'] . "','" . $item['height'] . "','" . $item['rotatable'] . "','" . $item['degree'] . "','" . $item['zIndex'] . "','" . $item['content'] . "','" . $item['imgUrl'] . "','" . $item['itemUrl'] . "')";
            $db->query($sql);

    }
}
echo success;


