<?php
$checksum  =  crc32 ( "The quick brown fox jumped over the lazy dog." );
//printf ( "%u\n" ,  $checksum );

sprintf('%08x', $checksum);

return;

if (!defined('IN_YKE'))
{
    define('IN_YKE', true);
}
if (!defined('WEB_PATH'))
{
    define('WEB_PATH', str_replace( 'public','',str_replace('\\', '/', dirname(__FILE__)) ));
}
echo WEB_PATH;//D:/phpStudy/WWW/yikai/
//return;
include(WEB_PATH. 'modules/public/includes/cls_debug.php');

require_once (WEB_PATH . '/includes/init_d.php');
//require_once (WEB_PATH . 'public/cls_debug.php');
/* 载入语言文件 */
//require_once (ROOT_PATH . 'languages/' . $_CFG['lang'] . '/user.php');//语言文件应该全部在数据库中定义，通过一个PHP文件替换

$arg1 =array(a=>11,b=>12,5,6,1,2,3,array('a','b','c',array(1,2,'d','e',9)),5,array('r','t'));
weixindebug($arg1,'arg1',0,'','','','public/cls_debug_test.php',15);
/*
 *
//php获取以GET方式传入的全部变量名称与值事先不知道传入的参数名称与数值
//如果事先不知道的话,要遍历一下.
//针对GET方式,程序如下
foreach   ($_GET as $key=>$value)
{
    echo   "Key: $key; Value: $value <br/>\n ";
}
//如果是POST方式的话,把程序里的$_GET换成$_POST即可
$arg1 =array(a=>11,b=>12,5,6);
foreach ($arg1 AS $i => $v)
{
  echo $i."\n\r";
  echo $v."\n\r";
    //a 11 b 12 0 5 1 6
}

return;

 * */

$a = '100';
echo '$a name is:'.get_variable_name($a).' value:'.$a."\n\r"; // $a name is: a value: 100

//例子2：获取function里面定义的变量名字
function test(){
$a = '100';
echo '$a name is:'.get_variable_name($a)."\n\r";
}

test();
// $a name is: undefined
//因为在function中定义的变量globals会找不到
function test2(){
    $a = '100';
    echo '$a name is:'.get_variable_name($a, get_defined_vars())."\n\r";
}
test2();
// $a name is: a
// 将scope设定为 get_defined_vars() 可以找到

?>

