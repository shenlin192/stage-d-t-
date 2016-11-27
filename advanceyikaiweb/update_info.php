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
    $sql= "delete from " . $GLOBALS['yke']->table(1,'web_advance') ." where 1 ";
    $db->query($sql);

    foreach ($page_info as $key => $item) {
     
            $sql = "insert into   " . $GLOBALS['yke']->table(1,'web_advance') ."
            (`elementId`,`typeId`,`typeName`,`draggable`,`positionX`,`positionY`,`resizable`,`width`,`height`,`rotatable`,`degree`,`text`,`imgUrl1`,`itemUrl1`,`textUrl`)
            values('" . $item['elementId'] . "','" . $item['typeId'] . "','" . $item['typeName'] . "','" . $item['draggable'] . "','" . $item['positionX'] . "','" . $item['positionY'] . "','" . $item['resizable'] . "','" . $item['width'] . "','" . $item['height'] . "','" . $item['rotatable'] . "','" . $item['degree'] . "','" . $item['text'] . "','" . $item['imgUrl1'] . "','" . $item['itemUrl1'] . "','" . $item['textUrl']. "')";
            $db->query($sql);

    }
}
echo success;


