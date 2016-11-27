<?php

class cls_getmac
{
    public $mac_addr;//mac地址
    public $custmac_addr;//客户端mac地址
    public $return_array = array(); // 返回带有MAC地址的字串数组

    public function __construct()
    {
        $this->cls_getmac();
    }

    public function cls_getmac()
    {
        //获取物理地址
        $this->mac_addr = $this->getmac(PHP_OS);
    }
    /**
     * 取得服务器的MAC地址
     */
    /**
    获取网卡的MAC地址原码；目前支持WIN/LINUX系统
    获取机器网卡的物理（MAC）地址
     **/
    public function getmac($os_type)
    {
        switch (strtolower($os_type)) {
            case "linux":
                $this->forLinux();
                break;
            case "solaris":
                break;
            case "unix":
                break;
            case "aix":
                break;
            default:
                $this->forWindows();
                break;
        }
        $temp_array = array();
        foreach ($this->return_array as $value) {
            if (preg_match("/[0-9a-f][0-9a-f][:-]" . "[0-9a-f][0-9a-f][:-]" . "[0-9a-f][0-9a-f][:-]" . "[0-9a-f][0-9a-f][:-]" . "[0-9a-f][0-9a-f][:-]" . "[0-9a-f][0-9a-f]/i", $value, $temp_array)) {
                $this->mac_addr = $temp_array[0];
                break;
            }
        }
        unset($temp_array);
        return $this->mac_addr;
    }

    /**
     * windows服务器下执行ipconfig命令
     */
    public function forWindows()
    {
        @exec("ipconfig /all", $this->return_array);
        if ($this->return_array)
            return $this->return_array;
        else {
            $ipconfig = $_SERVER["WINDIR"] . "\system32\ipconfig.exe";
            if (is_file($ipconfig))
                @exec($ipconfig . " /all", $this->return_array);
            else
                @exec($_SERVER["WINDIR"] . "\system\ipconfig.exe /all", $this->return_array);
            return $this->return_array;
        }
    }
    /**
     * Linux服务器下执行ifconfig命令
     */
    public function forLinux()
    {
        @exec("ifconfig -a", $this->return_array);
        return $this->return_array;
    }

    function get_real_ip()
    {
        $ip = false;
        if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        }
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ips = explode(", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
            if ($ip) {
                array_unshift($ips, $ip);
                $ip = FALSE;
            }
            for ($i = 0; $i < count($ips); $i++) {
                if (!eregi("^(10|172\.16|192\.168)\.", $ips[$i])) {
                    $ip = $ips[$i];
                    break;
                }
            }
        }
        return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
    }
    /**
    获取网卡的MAC地址原码；目前支持WIN/LINUX系统
    获取机器网卡的物理（MAC）地址
     **/
    function getMacAddr()//获取服务器mac
    {
        $arrayMac = array(); // 返回带有MAC地址的字串数组
        @exec("ipconfig /all", $arrayMac);
        if (!empty($arrayMac)) {
            for ($TempMac = 0; $TempMac < count($arrayMac); $TempMac++) {
                //eregi 不区分大小写的正则表达式匹配 这是因为php5.3中不再支持eregi()函数，而使用preg_match()函数替代
                if (preg_match("Physical", $arrayMac[$TempMac]) || preg_match("物理地址", $arrayMac[$TempMac])) {
                    $macAddr = explode(":", $arrayMac[$TempMac]);
                    return $macAddr[1];
                }
            }
            return 'null';
        } else {
            return 'null';
        }
    }
    function getcustMacAddr()//获取服务器mac
    {
        //获取客户端mac地址：
        @exec("arp -a", $array); //执行arp -a命令，结果放到数组$array中
        weixindebug($array,'$array',0,'','','','modules/public/includes/cls_getmac.php:125',125);
        weixindebug($_SERVER["REMOTE_ADDR"],'REMOTE_ADDR',0,'','','','modules/public/includes/cls_getmac.php:125',125);

        foreach ($array as $value) {
            //匹配结果放到数组$mac_array
            if (strpos($value, $_SERVER["REMOTE_ADDR"]) && preg_match("/(:?[0-9A-F]{2}[:-]){5}[0-9A-F]{2}/i", $value, $mac_array)) {
                $mac = $mac_array[0];
                break;
            }
        }
        $this->custmac_addr=$mac;
        return $mac;
    }


function get_ip()
    {//php获取ip的算法
        if ($_SERVER["HTTP_X_FORWARDED_FOR"])//$HTTP_SERVER_VARS
        {
            $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } elseif ($_SERVER["HTTP_CLIENT_IP"]) {
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        } elseif ($_SERVER["REMOTE_ADDR"]) {
            $ip = $_SERVER["REMOTE_ADDR"];
        } elseif (getenv("HTTP_X_FORWARDED_FOR")) {
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        } elseif (getenv("HTTP_CLIENT_IP")) {
            $ip = getenv("HTTP_CLIENT_IP");
        } elseif (getenv("REMOTE_ADDR")) {
            $ip = getenv("REMOTE_ADDR");
        } else {
            $ip = "Unknown";
        }
        return $ip;
    }

    function get_onlineip()
    {//php获取ip的算法
        if (getenv('HTTP_CLIENT_IP')) {
            $onlineip = getenv('HTTP_CLIENT_IP');
        } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
            $onlineip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('REMOTE_ADDR')) {
            $onlineip = getenv('REMOTE_ADDR');
        } else {
            $onlineip = $_SERVER['REMOTE_ADDR'];//$HTTP_SERVER_VARS
        }
        return $onlineip;
    }


    /*
     * 腾讯通过IP地址获取当前地理位置（省份）的接口
     * 腾讯的接口是,返回数组 http://fw.qq.com/ipaddress
     * 返回值 var IPData = new Array("71.131.122.114","","辽宁省","沈阳市");
     */
    function getIpAddressqq()
    {
        $ipContent = file_get_contents("http://fw.qq.com/ipaddress");
        $replaceIp = str_replace('"', ' ', $ipContent);
        $ipArray = explode("(", $replaceIp);
        $content = substr($ipArray[1], 0, -2);
        $ipAddress = explode(",", $content);
        return $ipAddress;
    }

    /*
     * 新浪通过IP地址获取当前地理位置（省份）的接口
     * 新浪的接口是,返回json
     * http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js
     * http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js&ip=218.192.3.42
     */
    function getIpAddresssina()
    {
        $ipContent = file_get_contents("http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js");
        $jsonData = explode("=", $ipContent);
        $jsonAddress = substr($jsonData[1], 0, -1);
        return $jsonAddress;
    }

}
/*
 *

 // 作用取得客户端的ip、地理信息、浏览器、本地真实IP
 class get_gust_info {

  ////获得访客浏览器类型
  function GetBrowser(){
   if(!empty($_SERVER['HTTP_USER_AGENT'])){
    $br = $_SERVER['HTTP_USER_AGENT'];
    if (preg_match('/MSIE/i',$br)) {
               $br = 'MSIE';
             }elseif (preg_match('/Firefox/i',$br)) {
     $br = 'Firefox';
    }elseif (preg_match('/Chrome/i',$br)) {
     $br = 'Chrome';
       }elseif (preg_match('/Safari/i',$br)) {
     $br = 'Safari';
    }elseif (preg_match('/Opera/i',$br)) {
        $br = 'Opera';
    }else {
        $br = 'Other';
    }
    return $br;
   }else{return "获取浏览器信息失败！";}
  }

  ////获得访客浏览器语言
  function GetLang(){
   if(!empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])){
    $lang = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
    $lang = substr($lang,0,5);
    if(preg_match("/zh-cn/i",$lang)){
     $lang = "简体中文";
    }elseif(preg_match("/zh/i",$lang)){
     $lang = "繁体中文";
    }else{
        $lang = "English";
    }
    return $lang;

   }else{return "获取浏览器语言失败！";}
  }

   ////获取访客操作系统
  function GetOs(){
   if(!empty($_SERVER['HTTP_USER_AGENT'])){
    $OS = $_SERVER['HTTP_USER_AGENT'];
      if (preg_match('/win/i',$OS)) {
     $OS = 'Windows';
    }elseif (preg_match('/mac/i',$OS)) {
     $OS = 'MAC';
    }elseif (preg_match('/linux/i',$OS)) {
     $OS = 'Linux';
    }elseif (preg_match('/unix/i',$OS)) {
     $OS = 'Unix';
    }elseif (preg_match('/bsd/i',$OS)) {
     $OS = 'BSD';
    }else {
     $OS = 'Other';
    }
          return $OS;
   }else{return "获取访客操作系统信息失败！";}
  }

  ////获得访客真实ip
  function Getip(){
   if(!empty($_SERVER["HTTP_CLIENT_IP"])){
      $ip = $_SERVER["HTTP_CLIENT_IP"];
   }
   if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){ //获取代理ip
    $ips = explode(',',$_SERVER['HTTP_X_FORWARDED_FOR']);
   }
   if($ip){
      $ips = array_unshift($ips,$ip);
   }

   $count = count($ips);
   for($i=0;$i<$count;$i++){
     if(!preg_match("/^(10|172\.16|192\.168)\./i",$ips[$i])){//排除局域网ip
      $ip = $ips[$i];
      break;
      }
   }
   $tip = empty($_SERVER['REMOTE_ADDR']) ? $ip : $_SERVER['REMOTE_ADDR'];
   if($tip=="127.0.0.1"){ //获得本地真实IP
      return $this->get_onlineip();
   }else{
      return $tip;
   }
  }

  ////获得本地真实IP
  function get_onlineip() {
      $mip = file_get_contents("http://city.ip138.com/city0.asp");
       if($mip){
           preg_match("/\[.*\]/",$mip,$sip);
           $p = array("/\[/","/\]/");
           return preg_replace($p,"",$sip[0]);
       }else{return "获取本地IP失败！";}
   }

  ////根据ip获得访客所在地地名
  function Getaddress($ip=''){
   if(empty($ip)){
       $ip = $this->Getip();
   }
   $ipadd = file_get_contents("http://int.dpool.sina.com.cn/iplookup/iplookup.php?ip=".$ip);//根据新浪api接口获取
   if($ipadd){
    $charset = iconv("gbk","utf-8",$ipadd);
    preg_match_all("/[\x{4e00}-\x{9fa5}]+/u",$charset,$ipadds);

    return $ipadds;   //返回一个二维数组
   }else{return "addree is none";}
  }
 }
 $gifo = new get_gust_info();
 echo "你的ip:".$gifo->Getip();
 echo "<br/>所在地：";
 $ipadds = $gifo->Getaddress();
 foreach($ipadds[0] as $value){
     echo "\r\n    ".iconv("utf-8","gbk",$value);
 }

 echo "<br/>浏览器类型：".$gifo->GetBrowser();
 echo "<br/>浏览器语言：".$gifo->GetLang();
 echo "<br/>操作系统：".$gifo->GetOs();



if (!defined('IN_YKE'))
{
    define('IN_YKE',true);
}
//include_once (dirname(__FILE__).'/init_P.php');
$code=new cls_getmac();
echo $code->mac_addr;
//echo $code->getMacAddr(); //报错

echo 'get_real_IP:'.$code->get_real_ip();
//echo 'get_IP:'.$code->get_ip();

//echo 'get_onIP:'.$code->get_onlineip();
//echo $code->getIpAddressqq();
echo $code->getIpAddresssina();

//echo 'get_real_IP:', $getIp;

//string gethostbyaddr ( string $ip_address )//通过IP地址获取主机名
$getname = $_SERVER["REMOTE_HOST"];
echo $getname;
$getIp = $_SERVER["REMOTE_ADDR"];
echo 'IP:', $getIp;
echo '<br/>';
$content = file_get_contents("http://api.map.baidu.com/location/ip?ak=ifPfufIZ3q3luHwun7XG4xDG3jZ4mxa4&ip={$getIp}&coor=bd09ll");
$json = json_decode($content);
echo 'log:', $json->{'content'}->{'point'}->{'x'};//按层级关系提取经度数据
echo '<br/>';
echo 'lat:', $json->{'content'}->{'point'}->{'y'};//按层级关系提取纬度数据
echo '<br/>';
print $json->{'content'}->{'address'};//按层级关系提取address数据

*/
//echo  $_SERVER['REMOTE_ADDR'];


//echo get_ip();
//echo get_onlineip();
//$user_IP = ($_SERVER["HTTP_VIA"]) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : $_SERVER["REMOTE_ADDR"];
//$user_IP = ($user_IP) ? $user_IP : $_SERVER["REMOTE_ADDR"];
//echo $user_IP;
/*
 *
 * jquery ajax异步请求百度IP定位API怎么获得json数据
 分享| 2014-08-23 14:41 t_358630529
 百度
不沾边的回答就免了，题目很清楚。
具体请求改设置哪些，补充哪些求大神解答。简单概括的更该省略，你怎不说百度去。
2014-08-23 15:05 提问者采纳
$.getJSON("http://api.map.baidu.com/location/ip?ak=E4805d16520de693a3fe707cdc962045&ip=202.198.16.3&coor=bd09ll",function(data)
{
alert(data.address);......

});
追问：
没出来
追答：
这个只是例子，具体的你要去申请AK，绑定域名才能用
参照
http://developer.baidu.com/map/ip-location-api.htm
提问者评价
新人，分也不多，算了，东西我已经弄好了，不过你这个我写了N种方式了。

$a=file('./url.txt');//读取文件
echo $a[1];
 $url_wav=$a[1];//获取第一行的链接地址
 $con2=file_get_contents($url_wav);
 $preg2="#<REF HREF=\"(.*)\?#iUs";
 preg_match_all($preg2,$con2,$arr);
  foreach($arr[1] as $id=>$m2){
   echo $arr[1][$id];
  }

所有的链接地址都在 txt文件里
我想一行一行的读取文件 然后在进行 读取网页源码 获取自己需要的东西，
不过  $url_wav直接写链接 地址 是可以获取的
但是 $url_wav=$a[1];//获取第一行的链接地址 就不能获取了！！
出错：

Warning: file_get_contents(这里是URL地址，百度不让发，艹 ) [function.file-get-contents]: failed to open stream: HTTP request failed! HTTP/1.1 500 Internal Server Error in XXXX on line 57直接写地址 就能获取的，写这个变量 就不能获取了！！是我写法不对吗？
2014-12-12 12:39 提问者采纳
file函数 与 file_get_contents() 类似，不同的是 file() 将文件作为一个数组返回。数组中的每个单元都是文件中相应的一行，包括换行符在内。
所以亲,要注意有换行符,用trim()把换行符去掉试试
提问者评价
哈哈哈，可以了！！太感谢你了！！！！

 * */

?>

