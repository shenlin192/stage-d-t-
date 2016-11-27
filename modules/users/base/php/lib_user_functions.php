<?php

/*
 get_user_info_by_user_id($user_id)//获取用户信息：微信昵称、头像
get_boss_by_user_id($user_id)//获取上司信息
get_dianpu_by_user_id($user_id)//获取用户关注店铺信息
get_dianpu_test_by_user_id($user_id)//获取测试店铺信息
is_erweima($user_id)//是否生成二维码
get_erweima_by_user_id($user_id)//获取用户二维码
get_user_money_by_user_id($user_id)//获取用户余额，需要结转，检查；
get_split_money_by_user_id($user_id)//获取用户分成金额，可以结转，分布
get_distrib_user_info($user_id,$level)//获取分销商下级会员信息,$level代表哪一级，1代表是一级会员
get_user_count($user_id,$level)//获取分销商下级会员个数,$level代表哪一级，1代表是一级会员

get_split_order_by_user_id($user_id)//获取用户分成订单数量
get_count_distrib_order_by_user_id($user_id,$is_separate)//获取分销商下所有下线会员分成订单数量
get_all_distrib_order_by_user_id($user_id,$is_separate,$page,$size)//获取分销商下所有下线会员分成订单信息
 get_level_user($user_id,$uid)//查看某一个会员是当前分销商的几级会员
get_total_money_by_user_id($user_id,$is_separate)//获取用户分成、未分成、撤销分成总金额
get_split_money_by_orderid($order_id)//获取某一个订单的分成金额
is_distribor($user_id)//判断会员是否是分销商
*/

if (!defined('IN_YKE'))
{
    die('Hacking attempt');
}

function get_user_info_by_user_id($user_id)//获取用户信息：微信昵称、头像
{
	$sql = "SELECT * FROM " . $GLOBALS['yke']->table(1,'weixin_user') . " WHERE ecuid = '$user_id'"." and sysid ='".$GLOBALS['sysid']."' ";
	$rows = $GLOBALS['db']->getRow($sql);
	if(!empty($rows))
	{
		return $rows; 
	} 
}

function get_boss_by_user_id($user_id)//获取上司信息
{
	$sql = "SELECT parent_id from " . $GLOBALS['yke']->table(1,'users') . " WHERE user_id = '$user_id'"." and sysid ='".$GLOBALS['sysid']."' ";
	$parent_id = $GLOBALS['db']->getOne($sql);
	if($parent_id > 0)
	{
		 $sql = "SELECT user_id,user_name FROM " . $GLOBALS['yke']->table(1,'users') . " WHERE user_id = '$parent_id'"." and sysid ='".$GLOBALS['sysid']."' ";
		 $user = $GLOBALS['db']->getRow($sql);
		 $info = get_user_info_by_user_id($user['user_id']);
		 $user['headimgurl'] = $info['headimgurl'];
		 return $user;
	}
}


function get_dianpu_by_user_id($user_id)//获取用户关注店铺信息
{
	$sql = "SELECT * from " . $GLOBALS['yke']->table(1,'dianpu') . " WHERE user_id = '$user_id'"." and sysid ='".$GLOBALS['sysid']."' ";
	return $GLOBALS['db']->getRow($sql);
}


function get_dianpu_test_by_user_id($user_id)//获取测试店铺信息
{
	$sql = "SELECT * from " . $GLOBALS['yke']->table(1,'dianpu_test') . " WHERE user_id = '$user_id'"." and sysid ='".$GLOBALS['sysid']."' ";
	return $GLOBALS['db']->getRow($sql);
}

function is_erweima($user_id)//是否生成二维码
{
	$sql = "SELECT count(*) FROM " . $GLOBALS['yke']->table(1,'weixin_qcode') . " where `type`='4' and content='$user_id'"." and sysid ='".$GLOBALS['sysid']."' ";
	return $GLOBALS['db']->getOne($sql);
}

function get_erweima_by_user_id($user_id)//获取用户二维码
{
	$sql = "SELECT * from " . $GLOBALS['yke']->table(1,'weixin_qcode') . " WHERE `type` = 4 AND content = '$user_id'"." and sysid ='".$GLOBALS['sysid']."' ";
	return $GLOBALS['db']->getRow($sql); 
}

function get_user_money_by_user_id($user_id)//获取用户余额
{
	$sql = "SELECT user_money FROM " . $GLOBALS['yke']->table(1,'users') . " WHERE user_id = '$user_id'"." and sysid ='".$GLOBALS['sysid']."' ";
	$user_money = $GLOBALS['db']->getOne($sql);
	if($user_money > 0)
	{
		return $user_money;
	}
	else
	{
		return 0;
	}
}

function get_split_money_by_user_id($user_id)//获取用户分成金额，可以结转，分布
{
	$sql = "SELECT sum(money) FROM " . $GLOBALS['yke']->table(1,'distrib_sort') . " WHERE user_id = '$user_id'"." and sysid ='".$GLOBALS['sysid']."' ";
	$split_money = $GLOBALS['db']->getOne($sql);
	if($split_money > 0)
	{
		return $split_money;
	}
	else
	{
		return 0;
	}
}


function get_distrib_user_info($user_id,$level)//获取分销商下级会员信息,$level代表哪一级，1代表是一级会员
{
	$call_username = $GLOBALS['_CFG']['call_username'];
	$up_uid = "'$user_id'";
    for ($i = 1; $i<=$level; $i++)
    {
		$count = 0;
        if ($up_uid)
        {
            $sql = "SELECT user_id FROM " . $GLOBALS['yke']->table(1,'users') . " WHERE parent_id IN($up_uid)"." and sysid ='".$GLOBALS['sysid']."' ";
            $query = $GLOBALS['db']->query($sql);
            $up_uid = '';
            while ($rt = $GLOBALS['db']->fetch_array($query))
            {
                $up_uid .= $up_uid ? ",'$rt[user_id]'" : "'$rt[user_id]'";
				$count++;
            }
        }
	}
	if($count)
	{
		 $sql = "SELECT user_id, user_name, email, is_validated, user_money, frozen_money, rank_points, pay_points, reg_time ".
                    " FROM " . $GLOBALS['yke']->table(1,'users') . " WHERE user_id IN($up_uid)"." and sysid ='".$GLOBALS['sysid']."' ";
		 $list = $GLOBALS['db']->getAll($sql);
		 $arr = array();
		 foreach($list as $key => $val)
		 {
			  if($call_username == 1)
			  {
				  $arr[$key]['call_username'] = '会员ID：'.$val['user_id'];
			  }
			  else
			  {
				  $arr[$key]['call_username'] = '会员名称：'.$val['user_name'];;
			  }
			  $arr[$key]['user_id'] = $val['user_id'];
			  $arr[$key]['user_name'] = $val['user_name'];
			  $arr[$key]['order_count'] = get_split_order_by_user_id($val['user_id']); //分成订单数量
			  $arr[$key]['split_money'] = get_split_money_by_user_id($val['user_id']); //分成金额
			  $info = get_user_info_by_user_id($val['user_id']);
			  $arr[$key]['headimgurl'] = $info['headimgurl'];
		 }
		 if(!empty($arr))
		 {
			 return $arr; 
		 }
	} 
}


function get_user_count($user_id,$level)//获取分销商下级会员个数,$level代表哪一级，1代表是一级会员
{
    $up_uid = "'$user_id'";
    for ($i = 1; $i<=$level; $i++)
    {
		$count = 0;
        if ($up_uid)
        {
            $sql = "SELECT user_id FROM " . $GLOBALS['yke']->table(1,'users') . " WHERE parent_id IN($up_uid)"." and sysid ='".$GLOBALS['sysid']."' ";
            $query = $GLOBALS['db']->query($sql);
            $up_uid = '';
            while ($rt = $GLOBALS['db']->fetch_array($query))
            {
                $up_uid .= $up_uid ? ",'$rt[user_id]'" : "'$rt[user_id]'";
				$count++;
            }
        }
	}
	if($count)
	{
		$sql = "SELECT count(*) FROM " . $GLOBALS['yke']->table(1,'users') . " WHERE user_id IN($up_uid)"." and sysid ='".$GLOBALS['sysid']."' ";
		return $GLOBALS['db']->getOne($sql);
	}
	else
	{
		return 0;
	}
}


function get_split_order_by_user_id($user_id)//获取用户分成订单数量
{
	$sql = "select count(*) from (select a.order_id,sum(split_money) as total_money from " . $GLOBALS['yke']->table(1,'order_info') . " as a ," . $GLOBALS['yke']->table(1,'order_goods') . " as b 
where a.order_id = b.order_id and a.sysid=b.sysid  and a.user_id = '$user_id' "." and a.sysid ='".$GLOBALS['sysid']."' "." group by a.order_id ) as ab where total_money > 0";
	return $GLOBALS['db']->getOne($sql);
}


function get_count_distrib_order_by_user_id($user_id,$is_separate)//获取分销商下所有下线会员分成订单数量
{
	$up_uid = "'$user_id'";
	$all_uid = '';
    for ($i = 1; $i<=3; $i++)
    {
        if ($up_uid)
        {
            $sql = "SELECT user_id FROM " . $GLOBALS['yke']->table(1,'users') . " WHERE parent_id IN($up_uid)"." and sysid ='".$GLOBALS['sysid']."' ";
            $query = $GLOBALS['db']->query($sql);
            $up_uid = '';
            while ($rt = $GLOBALS['db']->fetch_array($query))
            {
                $up_uid .= $up_uid ? ",'$rt[user_id]'" : "'$rt[user_id]'";
            }
			if($up_uid)
			{
				$all_uid .= $up_uid.',';
			}
        }
	}
	$uids = rtrim($all_uid,',');
	if(!empty($all_uid))
	{
		$sql = "SELECT order_id FROM " . $GLOBALS['yke']->table(1,'order_info') . " WHERE user_id in($uids)"." and sysid ='".$GLOBALS['sysid']."' ";
		$order_list = $GLOBALS['db']->getAll($sql);
		$oids = ''; //分销商下所有下级会员的订单id
		for($i = 0; $i < count($order_list); $i++)
		{
			if($i == 0)
			{
				$oids .= $order_list[$i]['order_id'];
			}
			else
			{
				$oids .= ','.$order_list[$i]['order_id'];
			}
		}
	}
	if(!empty($oids))
	{
		$sql = "SELECT count(*) FROM " . 
		$GLOBALS['yke']->table(1,'order_goods') . " as og , " .
		$GLOBALS['yke']->table(1,'order_info') . " as o , " . 
		$GLOBALS['yke']->table(1,'goods') . " as g, " . 
		$GLOBALS['yke']->table(1,'users') . " as u " .
		"WHERE og.order_id = o.order_id and og.sysid=o.sysid AND og.goods_id = g.goods_id and og.sysid=g.sysid AND o.user_id = u.user_id and o.sysid=u.sysid AND og.split_money > 0 AND og.order_id in($oids) AND is_separate = '$is_separate'"." and og.sysid ='".$GLOBALS['sysid']."' ";
		return $GLOBALS['db']->getOne($sql);
	}
}

function get_all_distrib_order_by_user_id($user_id,$is_separate,$page,$size)//获取分销商下所有下线会员分成订单信息
{
	$call_username = $GLOBALS['_CFG']['call_username'];
	$up_uid = $user_id;
	$all_uid = '';
	//$ret[0] = array($user_id);
    for ($i = 1; $i<=3; $i++)
    {
		//$j = $i-1;
        //if (count($ret[$j])>0)
		if($up_uid)
        {
            //$sql = "SELECT user_id FROM " . $GLOBALS['yke']->table(1,'users') . " WHERE parent_id IN(".implode(',',$ret[$j]).")";
            $sql = "SELECT user_id FROM " . $GLOBALS['yke']->table(1,'users') . " WHERE parent_id IN($up_uid)"." and sysid ='".$GLOBALS['sysid']."' ";
			//$ret[$i] = $GLOBALS['db']->getCol($sql);
			$query = $GLOBALS['db']->query($sql);
            $up_uid = '';
            while ($rt = $GLOBALS['db']->fetch_array($query))
            {
                $up_uid .= $up_uid ? ",$rt[user_id]" : "$rt[user_id]";
            }
			if($up_uid)
			{
				$all_uid .= $up_uid.',';
			}
        }
	}
	$uids = rtrim($all_uid,',');
	if(!empty($uids))
	{
		$sql = "SELECT order_id FROM " . $GLOBALS['yke']->table(1,'order_info') . " WHERE user_id in($uids)"." and sysid ='".$GLOBALS['sysid']."' ";
		$order_list = $GLOBALS['db']->getAll($sql);
		$oids = ''; //分销商下所有下级会员的订单id
		for($i = 0; $i < count($order_list); $i++)
		{
			if($i == 0)
			{
				$oids .= $order_list[$i]['order_id'];
			}
			else
			{
				$oids .= ','.$order_list[$i]['order_id'];
			}
		}
		if(!empty($oids))
		{
			$sql = "SELECT og.order_id,og.goods_id,og.goods_name,o.user_id,g.goods_thumb,u.user_name FROM " . 
			$GLOBALS['yke']->table(1,'order_goods') . " as og , " .
			$GLOBALS['yke']->table(1,'order_info') . " as o , " . 
			$GLOBALS['yke']->table(1,'goods') . " as g, " . 
			$GLOBALS['yke']->table(1,'users') . " as u " .
			"WHERE og.order_id = o.order_id and og.sysid=o.sysid AND og.goods_id = g.goods_id and og.sysid=g.sysid  AND o.user_id = u.user_id and o.sysid=u.sysid AND og.split_money > 0 AND og.order_id in($oids) AND is_separate = '$is_separate'"." and og.sysid ='".$GLOBALS['sysid']."' ";
			if(isset($size) && isset($page))
			{
				$res = $GLOBALS['db']->selectLimit($sql, $size, ($page - 1) * $size);
			}
			else
			{
				$res = $GLOBALS['db']->query($sql); 
			}
			$arr = array();
			while ($row = $GLOBALS['db']->fetchRow($res))
			{
				$arr[$row['order_id']]['goods_name'] = $row['goods_name'];
				$arr[$row['order_id']]['goods_thumb'] = $row['goods_thumb'];
				$info = get_user_info_by_user_id($row['user_id']);
				$arr[$row['order_id']]['nickname'] = $info['nickname'];
				if($call_username == 1)
				{
					$arr[$row['order_id']]['call_username'] = '会员ID：'.$row['user_id'];;
				}
				else
				{
					$arr[$row['order_id']]['call_username'] = '会员名称：'.$row['user_name'];
				}
				$arr[$row['order_id']]['user_name'] = $row['user_name'];
				$arr[$row['order_id']]['split_money'] = price_format(get_split_money_by_user_id($row['user_id']));
				$arr[$row['order_id']]['level'] = get_level_user($user_id,$row['user_id']);
			}
			if(!empty($arr))
			{
				return $arr; 
			}
		}
	}
	return array();
}

function get_level_user($user_id,$uid)//查看某一个会员是当前分销商的几级会员
{
	$up_uid = "'$user_id'";
	$all_uid = '';
	$level = 0;
    for ($i = 1; $i<=3; $i++)
    {
        if ($up_uid)
        {
            $sql = "SELECT user_id FROM " . $GLOBALS['yke']->table(1,'users') . " WHERE parent_id IN($up_uid)"." and sysid ='".$GLOBALS['sysid']."' ";
            $query = $GLOBALS['db']->query($sql);
            $up_uid = '';
            while ($rt = $GLOBALS['db']->fetch_array($query))
            {
                $up_uid .= $up_uid ? ",'$rt[user_id]'" : "'$rt[user_id]'";
				if($rt['user_id'] == $uid)
				{
					$level = $i;
					break;
				}
            }
        }
	}
	return $level;
}

function get_total_money_by_user_id($user_id,$is_separate)//获取用户分成、未分成、撤销分成总金额
{
	$up_uid = "'$user_id'";
	$all_uid = '';
    for ($i = 1; $i<=3; $i++)
    {
        if ($up_uid)
        {
            $sql = "SELECT user_id FROM " . $GLOBALS['yke']->table(1,'users') . " WHERE parent_id IN($up_uid)"." and sysid ='".$GLOBALS['sysid']."' ";
            $query = $GLOBALS['db']->query($sql);
            $up_uid = '';
            while ($rt = $GLOBALS['db']->fetch_array($query))
            {
                $up_uid .= $up_uid ? ",$rt[user_id]" : "$rt[user_id]";
            }
			if($up_uid)
			{
				$all_uid .= $up_uid.",";
			}
        }
	}
	$uids = rtrim($all_uid,',');
	if(!empty($uids))
	{
		//$sql = "select order_id,user_id,total_money from (select a.order_id,a.user_id,sum(split_money*goods_number) as total_money from " . $GLOBALS['yke']->table(1,'order_info') . " as a ," . $GLOBALS['yke']->table(1,'order_goods') . " as b where a.order_id = b.order_id and a.user_id in($uids) and is_separate = '$is_separate' group by a.order_id ) as ab where total_money > 0";
		$sql = "select a.order_id,a.user_id,sum(split_money*goods_number) as total_money from " . $GLOBALS['yke']->table(1,'order_info') . " as a ," . $GLOBALS['yke']->table(1,'order_goods') . " as b where a.order_id = b.order_id and a.sysid=b.sysid and a.user_id in($uids) and is_separate = '$is_separate'"." and a.sysid ='".$GLOBALS['sysid']."' "." group by a.order_id";
		$order_ids = $GLOBALS['db']->getAll($sql);
		if(!empty($order_ids))
		{
			  $total_money = 0;
			  $affiliate = unserialize($GLOBALS['_CFG']['affiliate']);  
			  for($j = 0;$j < count($order_ids); $j++)
			  {
				  $split_money = $order_ids[$j]['total_money'];
				  if($split_money > 0)
				  {
				  $level = get_level_user($user_id,$order_ids[$j]['user_id']);
				  $num = count($affiliate['item']);
				  for ($k=0; $k < $num; $k++)
				  {
					  if($level == ($k+1))
					  {
						$a = (float)$affiliate['item'][$k]['level_money'];
						if($affiliate['config']['level_money_all']==100 )
						{
							$total_money += $split_money;
						}
						else 
						{
							if ($a)
							{
								$a /= 100;
							}
							$total_money += round($split_money * $a, 2);
						} 
					  }
				  }
				  }
			  }
		}
	}
	 if($total_money > 0)
	 {
	 	return $total_money; 
	 }
	 else
	 {
		return 0; 
	 }
}


function get_split_money_by_orderid($order_id)//获取某一个订单的分成金额
{
	 $sql = "SELECT sum(split_money*goods_number) FROM " . $GLOBALS['yke']->table(1,'order_goods') . " WHERE order_id = '$order_id'"." and sysid ='".$GLOBALS['sysid']."' ";
	 $split_money = $GLOBALS['db']->getOne($sql);
	 if($split_money > 0)
	 {
		 return $split_money; 
	 }
	 else
	 {
		 return 0; 
	 }
}


function is_distribor($user_id)//判断会员是否是分销商
{
	 //判断是否是分销商
	$distrib_rank = $GLOBALS['_CFG']['distrib_rank'];
	if($distrib_rank == -1)
	{
		 //所有注册会员都是分销商
		$GLOBALS['db']->query("UPDATE " . $GLOBALS['yke']->table(1,'users') . " SET is_fenxiao = 1 WHERE is_fenxiao <> 0"." and sysid ='".$GLOBALS['sysid']."' ");
	}
	else
	{
		 $rank = explode(',',$distrib_rank);
		 $ex_where = '';
		 $fx_where = '';
		 for($i = 0; $i < count($rank); $i++)
		 {
			 $sql = "SELECT min_points, max_points FROM ".$GLOBALS['yke']->table(1,'user_rank')." WHERE rank_id = '" . $rank[$i] . "'"." and sysid ='".$GLOBALS['sysid']."' ";
             $row = $GLOBALS['db']->getRow($sql);
			 if($i != 0)
			 {
				 $ex_where .= " or ";
				 $fx_where .= " or ";
			 }
             $ex_where .= " (rank_points >= " . intval($row['min_points']) . " AND rank_points < " . intval($row['max_points']) . ")";
			 $fx_where .= " (rank_points < " . intval($row['min_points']) . " OR rank_points >= " . intval($row['max_points']) . ")";
         }
		 //没达到条件的所有会员变为普通会员
		 //$GLOBALS['db']->query("UPDATE " . $GLOBALS['yke']->table(1,'users') . " SET is_fenxiao = 2 WHERE is_fenxiao <> 0 AND " . "(".$fx_where.")"." and sysid ='".$GLOBALS['sysid']."' ");
		 //达到条件的所有会员晋级为分销商
		 $GLOBALS['db']->query("UPDATE " . $GLOBALS['yke']->table(1,'users') . " SET is_fenxiao = 1 WHERE is_fenxiao <> 0 AND " . "(".$ex_where.")"." and sysid ='".$GLOBALS['sysid']."' ");
	}
	$sql = "SELECT is_fenxiao FROM " . $GLOBALS['yke']->table(1,'users') . " WHERE user_id = '$user_id'"." and sysid ='".$GLOBALS['sysid']."' ";
	return $GLOBALS['db']->getOne($sql);
}

?>