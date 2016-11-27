<?php
/**
 * 用户相关 前台公用函数库
 * ============================================================================
 * * 版权所有 2015-2025 杭州一开网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.yikaiqiche.cn；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: $
 * $Id: $.php  2016-02-07 06:29:08Z $
 */
//按照模块、对象定义模板，便于引用，修改，新增
if (!defined('IN_YKE'))
{
    die('Hacking attempt');
}
/**
 * 生成编辑器
 * @param   string  input_name  输入框名称
 * @param   string  input_value 输入框值
 */

/**
 * 生成编辑器
 * @param   string  input_name  输入框名称
 * @param   string  input_value 输入框值
 */

function create_html_editor($input_name, $input_value = '')
{
    global $smarty;
    $HTML='
    <script type="text/javascript" charset="utf-8"

src="../includes/ueditor/ueditor.config.js"></script>
    <script type="text/javascript" charset="utf-8"

src="../includes/ueditor/ueditor.all.js"></script>
    <textarea name="'.$input_name.'" id="'.$input_name.'" style="width:100%;">'.

        $input_value.'</textarea>
    <script type="text/javascript">
    UE.getEditor("'.$input_name.'",{
    theme:"default", //皮肤
    lang:"zh-cn",    //语言
    initialFrameWidth:1000,  //初始化编辑器宽度,默认650
    initialFrameHeight:350  //初始化编辑器高度,默认180
    });
    </script>';
    $smarty->assign('FCKeditor', $HTML);
}

/*
 function create_html_editor($input_name, $input_value = '')
{
    global $smarty;
    $kindeditor="<script charset='utf-8' src='../includes/kindeditor/kindeditor-min.js'></script>
    <script>
        var editor;
            KindEditor.ready(function(K) {
                editor = K.create('textarea[name=\"$input_name\"]', {
                    allowFileManager : true,
                    width : '100%',
                    height: '300px',
                    resizeType: 0    //固定宽高
                });
            });
    </script>
    <textarea id=\"$input_name\" name=\"$input_name\" style='width:100%;height:300px;'>$input_value</textarea>
	<input type=\"submit\" value=\"提交\" />
    ";
    $smarty->assign('FCKeditor', $kindeditor);  //这里前面的 FCKEditor 不要变
}


 * */

/* 修改 By  www.hzyikai.cn  百度编辑器 end */
/**
 * 取得商品列表：用于把商品添加到组合、关联类、赠品类
 * @param   object  $filters    过滤条件
 */

/**
 * 返回是否
 * @param   int     $var    变量 1, 0
 */
function get_yes_no($var)
{
    return empty($var) ? '<img src="images/no.gif" border="0" />' : '<img src="images/yes.gif" border="0" />';
}


/**
 * 取得图表颜色
 *
 * @access  public
 * @param   integer $n  颜色顺序
 * @return  void
 */
function chart_color($n)
{
    /* 随机显示颜色代码 */
    $arr = array('33FF66', 'FF6600', '3399FF', '009966', 'CC3399', 'FFCC33', '6699CC', 'CC3366', '33FF66', 'FF6600', '3399FF');

    if ($n > 8)
    {
        $n = $n % 8;
    }

    return $arr[$n];
}


/**
 *  返回字符集列表数组
 *
 * @access  public
 * @param
 *
 * @return void
 */
function get_charset_list()
{
    return array(
        'UTF8'   => 'UTF-8',
        'GB2312' => 'GB2312/GBK',
        'BIG5'   => 'BIG5',
    );
}

/**
 * 根据过滤条件获得排序的标记
 *
 * @access  public
 * @param   array   $filter
 * @return  array
 */
function sort_flag($filter)
{
    $flag['tag']    = 'sort_' . preg_replace('/^.*\./', '', $filter['sort_by']);
    $flag['img']    = '<img src="images/' . ($filter['sort_order'] == "DESC" ? 'sort_desc.gif' : 'sort_asc.gif') . '"/>';

    return $flag;
}

/**
 *  将含有单位的数字转成字节
 *
 * @access  public
 * @param   string      $val        带单位的数字
 *
 * @return  int         $val
 */
function return_bytes($val)
{
    $val = trim($val);
    $last = strtolower($val{strlen($val)-1});
    switch($last)
    {
        case 'g':
            $val *= 1024;
        case 'm':
            $val *= 1024;
        case 'k':
            $val *= 1024;
    }

    return $val;
}


/**
 * 生成链接后缀
 */
function list_link_postfix()
{
    return 'uselastfilter=1';
}

/**
 * 保存过滤条件
 * @param   array   $filter     过滤条件
 * @param   string  $sql        查询语句
 * @param   string  $param_str  参数字符串，由list函数的参数组成
 */
function set_filter($filter, $sql, $param_str = '')
{
    $filterfile = basename(PHP_SELF, '.php');
    if ($param_str)
    {
        $filterfile .= $param_str;
    }
    setcookie('YKECP[lastfilterfile]', sprintf('%X', crc32($filterfile)), time() + 600);
    setcookie('YKECP[lastfilter]',     urlencode(serialize($filter)), time() + 600);
    setcookie('YKECP[lastfiltersql]',  base64_encode($sql), time() + 600);
}


/**
 * 取得上次的过滤条件
 * @param   string  $param_str  参数字符串，由list函数的参数组成
 * @return  如果有，返回array('filter' => $filter, 'sql' => $sql)；否则返回false
 */

/**
 * 取得上次的过滤条件
 * @param   string  $param_str  参数字符串，由list函数的参数组成
 * @return  如果有，返回array('filter' => $filter, 'sql' => $sql)；否则返回false
 */
function get_filter($param_str = '')
{
    $filterfile = basename(PHP_SELF, '.php');
    if ($param_str)
    {
        $filterfile .= $param_str;
    }
    if (isset($_GET['uselastfilter']) && isset($_COOKIE['YKECP']['lastfilterfile'])
        && $_COOKIE['YKECP']['lastfilterfile'] == sprintf('%X', crc32($filterfile)))
    {
        return array(
            'filter' => unserialize(urldecode($_COOKIE['YKECP']['lastfilter'])),
            'sql'    => base64_decode($_COOKIE['YKECP']['lastfiltersql'])
        );
    }
    else
    {
        return false;
    }
}

/**
 * URL过滤
 * @param   string  $url  参数字符串，一个urld地址,对url地址进行校正
 * @return  返回校正过的url;
 */
function sanitize_url($url , $check = 'http://')
{
    if (strpos( $url, $check ) === false)
    {
        $url = $check . $url;
    }
    return $url;
}





?>