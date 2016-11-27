<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/4/13
 * Time: 13:34
 */
class recommend
{
  static function get_recommend_cars($number, $type)
  {

    $db = $GLOBALS['db'];
    $sql = 'select t1.goods_id,t1.goods_name,t1.goods_img,t1.shop_price,t1.market_price,t2.recommend,t2.sales,t2.collect
            from yke_goods t1,yke_goods_car t2
            WHERE t1.goods_id=t2.goods_id and t1.sysid=t2.sysid '." and t1.sysid ='".$GLOBALS['sysid']."'  ".'
            group by '.$type.' limit '.$number;
    $recommend_cars = array_map('price_serialize', $db->getAll($sql));
    for ($i = 0; $i < count($recommend_cars); $i++) {
      $recommend_cars[$i][$type]++;
    }
    return $recommend_cars;
  }

}

//封装静态方法的方法类
class catalog
{
  /*获取品牌列表*/
  static function get_brand_list()
  {
    $db = $GLOBALS['db'];
    $sql = "select brand_id,brand_name,brand_logo from " .$GLOBALS['yke']->table(1,"brand")." where sysid ='".$GLOBALS['sysid']."'  ";
    $brand_list = $db->getAll($sql);
    //根据门店名称第一个汉字的首字母正序排序
    $result = array();
    foreach ($brand_list as $key => $value) {
      $brand_name = $value['brand_name'];
      $firstCharter = getFirstCharter($brand_name); //取出门店的第一个汉字的首字母
      $result[$firstCharter][0] = $firstCharter;
      $result[$firstCharter][1][] = $value;
    }
    return $result;
  }

  /*获取车型列表*/
  static function get_type_list($brand_id)
  {
    $db = $GLOBALS['db'];
    $sql = "SELECT t1.cat_id,t1.cat_name,t2.shop_price,t2.market_price,t2.parent_name
            from yke_category t1,yke_category_car t2
            where t1.cat_id=t2.cat_id and t1.sysid=t2.sysid and t2.brand_id= ".$brand_id." and t1.sysid ='".$GLOBALS['sysid']."'  "."
            order by t1.cat_id";

    $result = $db->getAll($sql);
    $types = array();
    foreach ($result as $key => $value) {
      /*将价格如1230000转化为123万*/
      $value = price_serialize($value);
      $types[$value['parent_name']][1][] = $value;
      $types[$value['parent_name']][0] = $value['parent_name'];
    }

    return $types;
  }

   static function get_brand($brand_id)
  {
    $db = $GLOBALS['db'];
    $sql='select brand_name from yke_brand WHERE brand_id='.$brand_id." and sysid ='".$GLOBALS['sysid']."'  ";
    return $db->getOne($sql);
  }
  static function get_type($cat_id)
  {
    $db = $GLOBALS['db'];
    $sql='select t1.cat_name,t1.type_img,t2.shop_price,t2.market_price
          FROM yke_category t1,yke_category_car t2
          WHERE t1.cat_id=t2.cat_id and t1.sysid=t2.sysid and t1.cat_id ='.$cat_id." and t1.sysid ='".$GLOBALS['sysid']."'  ";
    $query = $db->query($sql);
    $type = $db->fetchRow($query);
    $type = price_serialize($type);
    return $type;
  }

  /*获取车款列表*/

  static function get_car_list($cat_id)
  {
    $db = $GLOBALS['db'];
    $sql = "select t1.goods_id,t1.goods_name,t1.shop_price,t1.market_price,t1.goods_img
            from yke_goods t1,yke_goods_car t2
            where t1.goods_id=t2.goods_id and t1.sysid=t2.sysid and t1.cat_id= ".$cat_id." and t1.sysid ='".$GLOBALS['sysid']."'  ";
    $result = $db->getAll($sql);
    $cars = array();
    foreach ($result as $key => $value) {
      $value = price_serialize($value);
      $year = intval(sub_str($value['goods_name'], 4)) . "";
      $value = slice_style_name($value);
      $cars[$year][1][] = $value;
      $cars[$year][0] = $year . '款';
    }
    return $cars;
  }

  /*获取车款详情*/
  static function get_car_info($goods_id)
  {
    $db = $GLOBALS['db'];
    $sql = "select t1.cat_id,t1.goods_name,t1.market_price,t1.shop_price,t1.goods_img,t2.attr_cat_id
            from yke_goods t1,yke_goods_car t2
            WHERE t1.goods_id=t2.goods_id and t1.sysid=t2.sysid and t1.goods_id=".$goods_id." and t1.sysid ='".$GLOBALS['sysid']."'  ";
    $query = $db->query($sql);
    $result = price_serialize($db->fetchRow($query));
    if ($result['attr_cat_id']) {
      $result['color'] = self::get_car_color($result['attr_cat_id']);
    } else {
      $result['color'] = "";
    }
    $result['config'] = self::get_car_config($goods_id);
    return $result;
  }

  /*获取颜色*/
  static function get_car_color($attr_cat_id)
  {
    $db = $GLOBALS['db'];
    $sql = "select color_code,img from" .$GLOBALS['yke']->table(1,"category_attr") . " where attr_cat_id='$attr_cat_id'"." and sysid ='".$GLOBALS['sysid']."'  ";
    $colors = $db->getAll($sql);
    return $colors;
  }

  /*获取车款配置*/
  static function get_car_config($goods_id)
  {
    $db = $GLOBALS['db'];
    $sql = 'select attr_key,attr_value
            from yke_goods_attr_car
            WHERE goods_id='.$goods_id." and sysid ='".$GLOBALS['sysid']."'  ";
    return $db->getAll($sql);
  }

  /*获取车款参数*/
/*  static function get_car_parameter($goods_id)
  {
    $db = $GLOBALS['db'];
    $sql = "select data_key,data_value from" . $GLOBALS['yke']->table(1,'car_parameter') . 'where goods_id=' . $goods_id." and sysid ='".$GLOBALS['sysid']."'  ";
    return $db->getAll($sql);
  }*/
}