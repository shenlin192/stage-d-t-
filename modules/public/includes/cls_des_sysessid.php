<?php
if (!defined('IN_YKE')) {
    die('Hacking attempt');
}
if (!in_array($_SERVER['HTTP_HOST'], array('127.0.0.1', 'www.yikaiqiche.cn', 'yikaiqiche.cn', '114.55.24.185', 'localhost'))) {
    die();
}

if (!defined('PUBLIC_PATH')) {
    $this_path = str_replace('includes/cls_des_sysessid.php', '', str_replace('\\', '/', __FILE__));
    //echo $this_path;
    define('PUBLIC_PATH', $this_path);//公共文件的位置,与其他文件中的root_path不一致是否存在问题？
}

require_once(ROOTWEB_PATH.'modules/public/includes/cls_des_2.php');//没有root_path,测试同名文件是否可以相同即可
$des = new CryptDes("hyki", "daodos");//（秘钥向量，混淆向量）
if (!defined('HZYIKAI')) {
    define('HZYIKAI', '-yk-');
}
include_once(ROOTWEB_PATH.'modules/public/includes/cls_getmac.php');//获取mac地址
$maccode = new cls_getmac();

//$custmaccode=$maccode->getcustMacAddr();//获取服务器mac
//weixindebug($custmaccode,'$custmaccode',0,'','','','modules/public/includes/cls_des_sysessid.php:24',24);
weixindebug($maccode->mac_addr,'$maccode->mac_addr',0,'','','','modules/public/includes/cls_des_sysessid.php:25',25);
//echo $maccode->mac_addr;

function syssessidsplit($ssid){
    //参数传递为公司，sessonid，mac，用户，
//        echo '$ssid='.$ssid;
        $ret = $ssid;//$GLOBALS['des']->decrypt($ssid);//解密字符串$ssid;//分解为$sysid和sessionid，所以网址中带参数需要修改
        if (!$ret){//避免未加密串为空
            $ret = $ssid;
        }
        $ss=array();
        if ($ssid) {
            $split = explode(HZYIKAI, $ret);
            foreach ($split as $key => $value) {
  //              echo $key."\n";//必须是双引号
  //              echo $value."\n";//必须是双引号
                if ($key == 0) {
                    $ss['sysid'] = $value;//来源为：_get参数，cookie参数；根据用户id，获取公司
                } elseif ($key == 1) {
                    $ss['sysuserid'] =  $value;//来源为：_get参数，cookie参数，微信登录后获取的wxid，否则为未登录状态
                }
                elseif ($key == 2) {
                    $ss['sysparentid'] =  $value;//来源为：_get参数，cookie参数，微信登录后获取的wxid，否则为未登录状态
                }
                elseif ($key == 3) {
                    $ss['sessionid'] =  $value;//来源为：_get参数，cookie参数；根据用户id，获取公司
                }
                elseif ($key == 4) {
                    $ss['sysmac'] =  $value;$value;;//来源为：判断用户登录的mac是否一致，与sessionid是否一致，如果不一致，则可能为黑客登录
                }
            }
        }

        //weixindebug($ss,'$ss',0,'','','','public/includes/cls_des_sysessid.php:52',52);

        if (!isset($ss['sysid']) || !$ss['sysid']) {
           $ss['sysid'] =  'cheyikai';//defaultsys;//用于测试用户和浏览用户，未注册用户
       }
        if (!isset($ss['sysuserid']) || !$ss['sysuserid']) {
            $ss['sysuserid'] = 0;//用户测试用户和浏览用户，未注册用户
        }
        if (!isset($ss['sysparentid']) || !$ss['sysparentid']) {
            $ss['sysparentid'] = 0;//
        }

        if (!isset($ss['sessionid']) || !$ss['sessionid']) {
            $ss['sessionid'] = '';//用于重新生成
        }
        if ($ss['sessionid'] =='SESS_ID' || strlen($ss['sessionid']) < 5 )
        {
            $ss['sessionid']='';
        }

        if (!isset($ss['sysmac']) || !$ss['sysmac']) {
            $ss['sysmac']='';
           // $ss['sysmac'] =$GLOBALS['maccode']->mac_addr;
    //echo $maccode->mac_addr;
        }
/*
        else
            if ($ss['sysmac'] !== $GLOBALS['maccode']->mac_addr) {
            //参数mac地址与当前mac地址不相同，可能为黑客，带处理
            $ss['sysmac']='';
        }
*/
//echo '121';
       $GLOBALS['sysid'] = $ss['sysid'];
       return $ss;
    }

    function syssessidcombination($ss){
       // weixindebug($ss,'$ss',0,'','','','public/includes/cls_des_sysessid.php:87',87);
        //形成新的加密字符串
        $ensysessid = $ss['sysid']. HZYIKAI .$ss['sysuserid']. HZYIKAI .$ss['sysparentid']. HZYIKAI .$ss['sessionid']. HZYIKAI . $ss['sysmac'];
//echo '5$ensysessid = '.$ensysessid."\r\n";
        $ssid =$ensysessid;//$GLOBALS['des']->encrypt($ensysessid);//加密字符串 $ensysessid;//
        //weixindebug($ssid,'$ssid',0,'','','','public/includes/cls_des_sysessid.php:93',93);
        return $ssid;
    }

/*
$dpos=strpos($ret,HZYIKAI);
//echo '0  $dpos = '. $dpos."\r\n";
if ($dpos > 0 )//是加密字符串，可以解密
{
    $sysid=substr ($ret,0,$dpos);
    $sessionid=substr ($ret,$dpos +strlen(HZYIKAI));
}
else
{
    $sysid=$ssid;
    $sessionid='';
}
*/

?>