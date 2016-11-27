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

 update_user_info()//更新用户SESSION,COOKIE及登录时间、登录次数
visit_stats()//统计访问信息
set_affiliate()//保存推荐uid到cookie
get_affiliate()//获取推荐uid
 *
 */

if (!defined('IN_YKE'))
{
    die('Hacking attempt');
}
/**
//用户登录公司，游客登录公司
 */
function users_sys_info()//用户登录公司，游客登录公司
{
    if ($GLOBALS['sysid'] ){
        $sql = 'SELECT `user_id`,`sysid`,  `openid`,  `unionid`, `parent_id`, `subscribe`,  `subscribetime`,  `unsubscribetime`,`lastlogin`
 FROM ' . $GLOBALS['yke']->table(1,'users_sys') . " WHERE user_id =". $GLOBALS['user_id']." and sysid = '". $GLOBALS['sysid']."' ";
        if ($row = $GLOBALS['db']->getRow($sql)){
            $sql = 'update '. $GLOBALS['yke']->table(1,'users_sys') .  ' set `lastlogin` = 1'.
                " WHERE user_id =". $GLOBALS['user_id']." and sysid = '". $GLOBALS['sysid']."' ";
        }
        else{
            $sql = 'insert into ' . $GLOBALS['yke']->table(1,'users_sys') .'(`user_id`,`sysid`,  `openid`,  `unionid`, `parent_id`, `subscribe`,  `subscribetime`,  `unsubscribetime`,`lastlogin`)
            values('.$GLOBALS['user_id'].",'".$GLOBALS['sysid']."','".$GLOBALS['openid']."','".$GLOBALS['unionid']."',".$GLOBALS['parent_userid'].",'1',".time().",null,1)";
            $GLOBALS['db']->query($sql);
        }
    }
    else{
        $sql = 'SELECT `user_id`,`sysid`,  `openid`,  `unionid`, `parent_id`, `subscribe`,  `subscribetime`,  `unsubscribetime`,`lastlogin`
 FROM ' . $GLOBALS['yke']->table(1,'users_sys') . " WHERE user_id =". $GLOBALS['user_id']." and lastlogin=1";
        if ($row = $GLOBALS['db']->getRow($sql))
        {
            $GLOBALS['sysid']=$row['sysid'];//最新一次登录公司
        }
        else
        {
            $sql = 'SELECT `user_id`,`sysid`,  `openid`,  `unionid`, `parent_id`, `subscribe`,  `subscribetime`,  `unsubscribetime`,`lastlogin`
 FROM ' . $GLOBALS['yke']->table(1,'users_sys') . " WHERE user_id =". $GLOBALS['user_id']."";
            if ($row = $GLOBALS['db']->getRow($sql))
            {
                $GLOBALS['sysid']=$row['sysid'];//第一公司
            }
            else
            {
                $GLOBALS['sysid']='cheyikai';//默认公司
            }
        }
    }
}

/**
 * 登录用户信息
 */
function users_login_info()//登录用户信息
{
    if (!isset($GLOBALS['user_id']) || empty($GLOBALS['user_id']) || !$GLOBALS['user_id']){
        $GLOBALS['user_id']=0;
    }
    if (!isset($GLOBALS['parent_userid']) || empty($GLOBALS['parent_userid']) || !$GLOBALS['parent_userid']){
        $GLOBALS['parent_userid']=0;
    }

    $sql =        " select * ".
        'FROM ' .$GLOBALS['yke']->table(1,'users').
        " WHERE user_id = '" . $_SESSION['user_id'] . "'"." and sysid ='".$GLOBALS['sysid']."' ";
    if ($row = $GLOBALS['db']->getRow($sql)){
        /*插入登录日志，用户分析数据，微信登录位置*/
        $sql = "insert into " .$GLOBALS['yke']->table(1,'users_login'). "(sysid,user_id,visit_count,sessionid,last_login,last_time,last_stime,last_phone,last_mac,
    last_ip,user_rank,is_special,is_fenxiao,country,province,city,district,Latitude,Longitude,`Precision`,url,uid,parent_id
    )".
            " select sysid,user_id,visit_count,sessionid,last_login,last_time,last_stime,last_phone,last_mac,
    last_ip,user_rank,is_special,is_fenxiao,country,province,city,district,Latitude,Longitude,`Precision`,url,uid,parent_id ".
            'FROM ' .$GLOBALS['yke']->table(1,'users').
            " WHERE user_id = '" . $_SESSION['user_id'] . "'"." and sysid ='".$GLOBALS['sysid']."' ";
        $GLOBALS['db']->query($sql);
    }
else{
    /*插入登录日志，用户分析数据，微信登录位置*/
    $sql = "insert into " . $GLOBALS['yke']->table(1,'users_login') . "(
     sysid,user_id,visit_count,sessionid,last_login,last_stime,
     last_mac,last_ip,url,uid,parent_id
    )" .
        " values( '".$GLOBALS['sysid']."',". $GLOBALS['user_id'].",1,'" .SESS_ID. "','" .gmtime(). "','".getdatestr(). "','"
        .$GLOBALS['maccode']->mac_addr. "','" .real_ip(). "','" .PHP_SELF. "',".$GLOBALS['parent_userid'].",".$GLOBALS['parent_userid'].") ";
    $GLOBALS['db']->query($sql);
//,last_phone,user_rank,is_special,is_fenxiao,country,province,city,district,Latitude,Longitude,`Precision`
}
}


/**
 * 更新用户SESSION,COOKIE及登录时间、登录次数。
 *
 * @access  public
 * @return  void
 */

function update_user_info()//更新用户SESSION,COOKIE及登录时间、登录次数
{
    if (!$_SESSION['user_id'])
    {////把浏览的网页、个人信息保存
        users_login_info();
        return false;
    }
    if (empty($GLOBALS['parent_userid']) || !$GLOBALS['parent_userid']){
        $GLOBALS['parent_userid']=0;
    }
    /* 更新登录时间，登录次数及登录ip，记录每次分享、推荐的人 */
    $sql = "UPDATE " .$GLOBALS['yke']->table(1,'users'). " SET".
        " visit_count = visit_count + 1, ".
        " uid = ".$GLOBALS['parent_userid'].", ".
        " parent_id = ".$GLOBALS['parent_userid'].", ".
        " last_ip = '" .real_ip(). "',".
        " last_phone = '',".
        " last_mac = '" .$GLOBALS['maccode']->mac_addr. "',".
        " last_stime = '" .getdatestr(). "',".
        " last_login = '" .gmtime(). "',".
        " sessionid = '" .SESS_ID. "',".
        " url = '" .PHP_SELF. "'".
        " WHERE user_id = '" . $_SESSION['user_id'] . "'"." and sysid ='".$GLOBALS['sysid']."' ";
    $GLOBALS['db']->query($sql);
    //把浏览的网页、个人信息保存
    users_login_info();
    /* 查询会员信息 */
    $time = date('Y-m-d');
    $sql = 'SELECT u.user_money,u.email, u.pay_points, u.user_rank, u.rank_points, '.
        ' IFNULL(b.type_money, 0) AS user_bonus, u.last_login, u.last_ip,u.last_phone,u.last_mac,u.visit_count,u.last_stime'.
        ' FROM ' .$GLOBALS['yke']->table(1,'users'). ' AS u ' .
        ' LEFT JOIN ' .$GLOBALS['yke']->table(1,'user_bonus'). ' AS ub'.
        ' ON ub.user_id = u.user_id and u.sysid = ub.sysid AND ub.used_time = 0 ' .
        ' LEFT JOIN ' .$GLOBALS['yke']->table(1,'bonus_type'). ' AS b'.
        " ON b.type_id = ub.bonus_type_id and u.sysid=b.sysid AND b.use_start_date <= '$time' AND b.use_end_date >= '$time' ".
        " WHERE u.user_id = '$_SESSION[user_id]'"." and u.sysid ='".$GLOBALS['sysid']."' ";

    if ($row = $GLOBALS['db']->getRow($sql))
    {
        /* 更新SESSION */
        $_SESSION['last_time']   = $row['last_login'];
        $_SESSION['visit_count']     = $row['visit_count'];
        $_SESSION['last_ip']     = $row['last_ip'];
        $_SESSION['last_phone']     = $row['last_phone'];
        $_SESSION['last_mac']     = $row['last_mac'];
        $_SESSION['last_stime']     = $row['last_stime'];
        $_SESSION['login_fail']  = 0;
        $_SESSION['email']       = $row['email'];

        /*判断是否是特殊等级，可能后台把特殊会员组更改普通会员组*/
        if($row['user_rank'] >0)
        {
            $sql="SELECT special_rank from ".$GLOBALS['yke']->table(1,'user_rank')."where rank_id='$row[user_rank]'"." and sysid ='".$GLOBALS['sysid']."' ";
            if($GLOBALS['db']->getOne($sql)==='0' || $GLOBALS['db']->getOne($sql)===null)
            {
                $sql="update ".$GLOBALS['yke']->table(1,'users')."set user_rank='0' where user_id='$_SESSION[user_id]'"." and sysid ='".$GLOBALS['sysid']."' ";
                $GLOBALS['db']->query($sql);
                $row['user_rank']=0;
            }
        }

        /* 取得用户等级和折扣 */
        if ($row['user_rank'] == 0)
        {
            // 非特殊等级，根据等级积分计算用户等级（注意：不包括特殊等级）
            $sql = 'SELECT rank_id, discount FROM ' . $GLOBALS['yke']->table(1,'user_rank') . " WHERE special_rank = '0' AND min_points <= " . intval($row['rank_points']) . ' AND max_points > ' . intval($row['rank_points'])." and sysid ='".$GLOBALS['sysid']."' ";
            if ($row = $GLOBALS['db']->getRow($sql))
            {
                $_SESSION['user_rank'] = $row['rank_id'];
                $_SESSION['discount']  = $row['discount'] / 100.00;//可能是多种折扣，需要修改
            }
            else
            {
                $_SESSION['user_rank'] = 0;
                $_SESSION['discount']  = 1;
            }
        }
        else
        {
            // 特殊等级
            $sql = 'SELECT rank_id, discount FROM ' . $GLOBALS['yke']->table(1,'user_rank') . " WHERE rank_id = '$row[user_rank]'"." and sysid ='".$GLOBALS['sysid']."' ";
            if ($row = $GLOBALS['db']->getRow($sql))
            {
                $_SESSION['user_rank'] = $row['rank_id'];
                $_SESSION['discount']  = $row['discount'] / 100.00;
            }
            else
            {
                $_SESSION['user_rank'] = 0;
                $_SESSION['discount']  = 1;
            }
        }
    }
}

/**
 *  获取用户信息数组
 *
 * @access  public
 * @param
 *
 * @return array        $user       用户信息数组
 */
function get_user_info($id=0)//获取用户信息数组
{
    if ($id == 0)
    {
        $id = $_SESSION['user_id'];
    }
    $time = date('Y-m-d');
    $sql  = 'SELECT u.user_id, u.email, u.user_name, u.user_money, u.pay_points, u.reg_time,u.last_mac,u.last_phone,u.visit_count'.
        ' FROM ' .$GLOBALS['yke']->table(1,'users'). ' AS u ' .
        " WHERE u.user_id = '$id'"." and sysid ='".$GLOBALS['sysid']."' ";
    $user = $GLOBALS['db']->getRow($sql);
    $bonus = get_user_bonus($id);
    $user['username']    = $user['user_name'];
    $user['last_mac']    = $user['last_mac'];
    $user['last_phone']    = $user['last_phone'];
    $user['visit_count']    = $user['visit_count'];
    $user['user_points'] = $user['pay_points'] . $GLOBALS['_CFG']['integral_name'];
    $user['user_money']  = price_format($user['user_money'], false);
    $user['user_bonus']  = price_format($bonus['bonus_value'], false);
    $user['bonus_count'] = $bonus['bonus_count'];
    $user['reg_time']	 = local_date('Y-m-d',$user['reg_time']);
    return $user;
}

/**
 * 统计访问信息
 *
 * @access  public
 * @return  void
 */
function visit_stats()//统计访问信息
{
    if (isset($GLOBALS['_CFG']['visit_stats']) && $GLOBALS['_CFG']['visit_stats'] == 'off')
    {
        return;
    }
    $time = gmtime();
    /* 检查客户端是否存在访问统计的cookie */
    $visit_times = (!empty($_COOKIE['YKE']['visit_times'])) ? intval($_COOKIE['YKE']['visit_times']) + 1 : 1;
    setcookie('YKE[visit_times]', $visit_times, $time + 86400 * 365, '/');

    $browser  = get_user_browser();
    $os       = get_os();
    $ip       = real_ip();
    $area     = yke_geoip($ip);

    /* 语言 */
    if (!empty($_SERVER['HTTP_ACCEPT_LANGUAGE']))
    {
        $pos  = strpos($_SERVER['HTTP_ACCEPT_LANGUAGE'], ';');
        $lang = addslashes(($pos !== false) ? substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, $pos) : $_SERVER['HTTP_ACCEPT_LANGUAGE']);
    }
    else
    {
        $lang = '';
    }

    /* 来源 */
    if (!empty($_SERVER['HTTP_REFERER']) && strlen($_SERVER['HTTP_REFERER']) > 9)
    {
        $pos = strpos($_SERVER['HTTP_REFERER'], '/', 9);
        if ($pos !== false)
        {
            $domain = substr($_SERVER['HTTP_REFERER'], 0, $pos);
            $path   = substr($_SERVER['HTTP_REFERER'], $pos);

            /* 来源关键字 */
            if (!empty($domain) && !empty($path))
            {
                save_searchengine_keyword($domain, $path);
            }
        }
        else
        {
            $domain = $path = '';
        }
    }
    else
    {
        $domain = $path = '';
    }

    $sql = 'INSERT INTO ' . $GLOBALS['yke']->table(1,'stats') . ' ( ' .
        'sysid,ip_address, visit_times, browser, system, language, area, ' .
        'referer_domain, referer_path, access_url, access_time' .
        ') VALUES (' ."'".$GLOBALS['sysid']."',".
        "'$ip', '$visit_times', '$browser', '$os', '$lang', '$area', ".
        "'" . htmlspecialchars(addslashes($domain)) ."', '" . htmlspecialchars(addslashes($path)) ."', '" . htmlspecialchars(addslashes(PHP_SELF)) ."', '" . $time . "')";
    $GLOBALS['db']->query($sql);
}



/**
 * 保存推荐uid到cookie
 *
 * @access  public
 * @param   void
 *
 * @return void
 * @author xuanyan


 **/
function set_affiliate()//保存推荐uid到cookie
{
    $config = unserialize($GLOBALS['_CFG']['affiliate']);
    if (!empty($GLOBALS['parent_userid']) && $config['on'] == 1)
    {
        if(!empty($config['config']['expire']))
        {
            if($config['config']['expire_unit'] == 'hour')
            {
                $c = 1;
            }
            elseif($config['config']['expire_unit'] == 'day')
            {
                $c = 24;
            }
            elseif($config['config']['expire_unit'] == 'week')
            {
                $c = 24 * 7;
            }
            else
            {
                $c = 1;
            }
            setcookie('ykshop_affiliate_uid', intval($GLOBALS['parent_userid']), gmtime() + 3600 * $config['config']['expire'] * $c);
        }
        else
        {
            setcookie('ykshop_affiliate_uid', intval($GLOBALS['parent_userid']), gmtime() + 3600 * 24); // 过期时间为 1 天
        }
    }
}

/**
 * 获取推荐uid
 *
 * @access  public
 * @param   void
 *
 * @return int
 * @author xuanyan
 **/
function get_affiliate()//获取推荐uid
{
    if (!empty($_COOKIE['ykshop_affiliate_uid']))
    {
        $uid = intval($_COOKIE['ykshop_affiliate_uid']);
        if ($GLOBALS['db']->getOne('SELECT user_id FROM ' . $GLOBALS['yke']->table(1,'users') . "WHERE user_id = '$uid'"." and sysid ='".$GLOBALS['sysid']."' "))
        {
            return $uid;
        }
        else
        {
            setcookie('ykshop_affiliate_uid', '', 1);
        }
    }

    return 0;
}


?>