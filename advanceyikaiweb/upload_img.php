<?php
define('IN_YKE', true);
require(dirname(__FILE__) . '/includes/init_d.php');
$smarty->assign('project_path', $GLOBALS['project_path']);
$smarty->assign('project_path_js', $GLOBALS['project_path_js']);
include_once(MODULES_PATH.'web/upload_img.php');
?>
