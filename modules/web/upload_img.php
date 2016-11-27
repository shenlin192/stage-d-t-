<?php

$dir_base = "/images/";     //文件上传根目录

//    weixindebug($isMoved,'$isMoved',0,'','','','yikaiweb/upload_img.php:38',38);
//没有成功上传文件，报错并退出。
if(empty($_FILES)) {
    echo false;
    exit(0);
}

$upload_file_name = 'upload_img' ;        //对应index.html FomData中的文件命名

$filename = $_FILES[$upload_file_name]['name'];
$gb_filename = iconv('utf-8','gb2312',$filename);    //名字转换成gb2312处理
//文件不存在才上传(not successful)  dwt2/yikaiweb/images/
if(!file_exists(ROOTYKE_PATH. $GLOBALS['project_path'].$dir_base.$gb_filename)) {//F:/phpStudy/WWW/

    $MAXIMUM_FILESIZE = 1 * 1024 * 1024;     //文件大小限制    1M = 1 * 1024 * 1024 B;
    $rEFileTypes = "/^\.(jpg|jpeg|gif|png){1}$/i";
    if ($_FILES[$upload_file_name]['size'] <= $MAXIMUM_FILESIZE && preg_match($rEFileTypes, strrchr($gb_filename, '.'))) {

        $isMoved = @move_uploaded_file ( $_FILES[$upload_file_name]['tmp_name'], ROOTYKE_PATH. $GLOBALS['project_path'].$dir_base.$gb_filename);        //上传文件

    }
}
//weixindebug($dir_base.$gb_filename,'$dir_base.$gb_filename',0,'','','','modules/web/upload_img.php:27',27);
echo $dir_base.$gb_filename;

?>