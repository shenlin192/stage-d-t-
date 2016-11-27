<?php
//rand_number ($length = 6)// rand_number随机生成指定长度的数字
//is_telephone ($phone)//检查是否电话号码
function rand_number ($length = 6)// rand_number随机生成指定长度的数字
{
    if($length < 1)
    {
        $length = 6;
    }

    $min = 1;
    for($i = 0; $i < $length - 1; $i ++)
    {
        $min = $min * 10;
    }
    $max = $min * 10 - 1;

    return rand($min, $max);
}

function is_telephone ($phone)//检查是否电话号码
{
    $chars = "/^13[0-9]{1}[0-9]{8}$|15[0-9]{1}[0-9]{8}$|18[0-9]{1}[0-9]{8}$/";
    if(preg_match($chars, $phone))
    {
        return true;
    }
}


?>