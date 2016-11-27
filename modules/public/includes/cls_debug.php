<?php

/**
 * cls_debug.php
 * YKESHOP debug 类
 * ===========================================================
 * * 版权所有 2015-2025 杭州一开网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.hzyikai.cn ；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ==========================================================
 */
/*
6.“ object ”
7.“ resource ”（从 PHP 4 起）
8.“ NULL ”（从 PHP 4 起）
9.“user function”（只用于 PHP 3，现已停用）
10.“unknown type”

*/

function weixindebug($arg,$argname,$level=0,$colid='',$colidfa='',$col='',$filename,$line)
    {
        global $db;
        if ((gettype($arg) == 'boolean') or (gettype($arg) == 'integer') or (gettype($arg) == 'double') or (gettype($arg) == 'string')  or (gettype($arg) == 'float'))
        {
            if (gettype($arg) == 'string')
            { $arg = addslashes_deep($arg); }

            if ($arg === false){
                $arg='false';
            }
            if ($arg === true){
                $arg='true';
            }
            $sql = "INSERT INTO `dos_debug` (`sysid`, `param`,`level`, `colid`, `colidfa`, `col`, `colvalue`, `filename`, `line`) VALUES ('".getdatestr()."','".strval($argname)."',".strval($level).",'".strval($colid)."','".strval($colidfa)."','".strval($col)."','".strval($arg)."','".strval($filename)."','".strval($line)."')";
            $query = $db->query($sql);
        }
        elseif ((gettype($arg) == 'array'||gettype($arg) == 'object'))
        {
            if(gettype($arg) == 'array'){
                $type='array';
            }
            else{
                $type='object';//获取对象属性
            }

            $sql = "INSERT INTO `dos_debug` (`sysid`, `param`,`level`, `colid`, `colidfa`, `col`, `colvalue`, `filename`, `line`) VALUES ('".getdatestr()."','".strval($argname)."',".strval($level).",'".strval($colid)."','".strval($colidfa)."','','".$type."','".strval($filename)."','".strval($line)."')";
            $query = $db->query($sql);
            //需要迭代，逐层插入$i => $v   key=>value,为便于处理json，把键名写入
            $newcolidfa = $colid;
            $ii=0;
            foreach ($arg AS $i => $v)
            {
                $ii=$ii+1;
                $s='00'.strval($ii);
                $s=substr($s,strlen($s) - 3,3);
                $colid   = $newcolidfa.$s;
//                echo $arg[$i];
                weixindebug( $arg[$i],$argname,$level+1,$colid,$newcolidfa,$i,$filename,$line);//不是对象，不能用$this->
            }
        }
    }


/*
foreach ($arg1 AS $i => $v)
{
//    echo $arg1[$i];
    encode($arg1[$i],'arg1','');
}
*/



/**
 * @param String $var   要查找的变量
 * @param Array  $scope 要搜寻的范围
 * @param String        变量名称
if (NULL == $scope)
{
$scope = $GLOBALS;
}
 *
PHP中，所有的变量都存储在"符号表"的HastTable结构中，符号的作用域是与活动符号表相关联的。因此，同一时间，只有一个活动符号表。
我们要获取到当前活动符号表可以通过 get_defined_vars 方法来获取。
[php] view plain copy
get_defined_vars // 返回所有已定义的变量所组成的数组
根据变量的值查找变量名字，但要注意，有可能有相同值的变量存在。
因此先将当前变量的值保存到一个临时变量中，然后再对原变量赋唯一值，以便查找出变量的名字，找到名字后，将临时变量的值重新赋值到原变量。
 */

function get_variable_name(&$var, $scope=null)

{
    $scope = $scope==null? $GLOBALS : $scope; // 如果没有范围则在globals中找寻
    // 因有可能有相同值的变量,因此先将当前变量的值保存到一个临时变量中,然后再对原变量赋唯一值,以便查找出变量的名称,找到名字后,将临时变量的值重新赋值到原变量
    $tmp = $var;

    $var = 'tmp_value_'.mt_rand();
    //array_search — 在数组中搜索给定的值，如果成功则返回相应的键名
    $name = array_search($var, $scope, true); // 根据值查找变量名称
    $var = $tmp;
    return $name;
}



/*

$getrow=array();
$sql = "select id, bill_type, module_id,module_name, phpfile, path, action, appar, fuctionname, memo from dosmodules where isnull(fuctionname)<>1  and fuctionname<> '' ";
$query = $db->query($sql);
$getrow =  $db->fetchRow($query);//$db->getOne($sql);

echo $getrow['phpfile'];
echo $getrow['fuctionname'];

*/

?>