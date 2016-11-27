<?php
//print gmdate("Y-m-d\TH:i:s\Z");

//print gmdate("Y-m-d\TH:i:s\G");

//2016-03-31T02:05:18Z

//$timezone  = -5; //(GMT -5:00) EST (U.S. & Canada)
//$timezone  = 8; //china
//echo gmdate("Y/m/j H:i:s", time() + 3600*($timezone+date("I")));

//echo str_replace( '/','-',strval(gmdate("Y/m/j H:i:s", time() + 3600*($timezone+date("I")))));

echo getdatestr();

$str='abcd-yikai-eefghi-yikai--yikai-lgjk';
$split=array();
/*
$split=strtok ($str,';yikai;');
while ( $split  !==  false ) {
 echo  "Word= $split <br />" ;
 $split  =  strtok ( ";yikai;" );
}
$split=preg_split ('yikai',$str);
//0 abcd 1 eefghi 2 3 lgjk
*/
$split=explode ('-yikai-' , $str);
foreach ( $split  as $key => $value ) {
 echo $key."\n";//必须是双引号
 echo $value."\n";//必须是双引号
}

//echo $split;
//array_expression as $key => $value
//2016-04-11 22:05:060
function getdatestr(){
 $timezone  = 8; //china
 return str_replace( '/','-',strval(gmdate("Y/m/j H:i:s", time() + 3600*($timezone+date("I")))));
}


?>

