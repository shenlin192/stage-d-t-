<?php
class Wechat{

  //取得微网站用户的ID，根据网页_get传递过来的参数获取
  public function get_userid()//在入口网页（可以分享的网页），设置；获取当前登录用户；如果已经是当前用户，则不需要授权
  {
	  $GLOBALS['openid']='';
	  $GLOBALS['unionid']='';
//	  $info = $this->get_user_info($wechatid);
//	  $nickname = $info['nickname'];

	  if(is_wechat_browser()){
	   if(empty($_SESSION['user_id'])){
		   //先读取session中保存的wechat_id，最安全
		   //即如果本地用户登录，则登录他人传播过来的信息，不改变关系
		   if(!empty($_SESSION['wechat_id']))
		   {
		      $wechatid = trim($_SESSION['wechat_id']);
		      //weixindebug($wechatid,'$wechatid',0,'','','','mobile/includes/cls_wechat.php',14);
		   }
		   //其次读取页面传递的wechat_id	  	
		   elseif(!empty($_REQUEST['wechat_id'])){
			  $wechatid = trim($_REQUEST['wechat_id']);
			   //weixindebug($wechatid,'$wechatid',0,'','','','mobile/includes/cls_wechat.php',19);
		   }else{
			   //最后利用网页授权获得openid
			  $code = $this->get_oauth2_code();
			  //weixindebug($code,'$code',0,'','','','mobile/includes/cls_wechat.php',19);
			  $wechatid = $this->get_oauth2_openid($code);
		   }
		   if(!empty($wechatid)){
			   //获取用户的详细信息
			   $userinfo = $GLOBALS['db']->getRow("SELECT * FROM ".$GLOBALS['yke']->table(1,'weixin_user')." WHERE `fake_id` = '".$wechatid."'");
			   if(!$userinfo)//如果用户信息不存在
			   {
				   $u = trim($_REQUEST['u']);//如果有分享用户
				   if(!empty($u)){//插入用户及分享用户信息
						$GLOBALS['user_id'] = $this->create_wechat_user($wechatid,$u);
				   }
			   }else{
				   $GLOBALS['user_id'] = $userinfo['ecuid'];
				   //存储为session
					$user_name = $GLOBALS['db']->getOne("SELECT user_name FROM ".$GLOBALS['yke']->table(1,'users')." WHERE `user_id` =".$GLOBALS['user_id']);
					
					$_SESSION['user_id']=$GLOBALS['user_id'];
					$_SESSION['user_name']=$user_name;
					$_SESSION['wechat_id'] = $wechatid;
					$this->update_userinfo($wechatid);
					update_user_info(); 
					recalculate_price();
				   if ($wechatid){
					   $GLOBALS['openid']=$wechatid;
				   }
					return $GLOBALS['user_id'];
			   }
		   }else{
			   show_message('openid is null!');
			   return;
		   }
	   }else{
	      $GLOBALS['user_id'] = $_SESSION['user_id'];
	   }
  }
	   return $GLOBALS['user_id'];
  }
  //取得网页用户授权接口中code参数
  function get_oauth2_code()
  {
	  if(empty($_GET['code'])){
        $row = $this->get_wechat_config();
		if(empty($row['appid'])){
			   show_message('appid error!'); return;   
		} 
		$redirect_uri = urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']);
		$para = array(
	         "appid"         => $row['appid'],
		     "redirect_uri"  => $redirect_uri,
		     "response_type" => 'code',
		     "scope"         => 'snsapi_base',//snsapi_base snsapi_userinfo
		     "state"         => '123#wechat_redirect'
	       );
		$url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$row['appid']."&redirect_uri=".$para['redirect_uri']."&response_type=code&scope=snsapi_base&state=123#wechat_redirect";
		url_redirect($url);
	  }
	  else
	     return $_GET['code'];
    }
	
	//取得网页用户授权接口中openid
   function get_oauth2_openid($code){
		$row = $this->get_wechat_config();
		$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$row[appid]."&secret=".$row[appsecret]."&code=".$code."&grant_type=authorization_code";
	    $rets =  $this->curl_get_contents($url);
	    //weixindebug($url,'$url',0,'','','','mobile/includes/cls_wechat.php',80);
	    weixindebug($rets,'$rets',0,'','','','mobile/includes/cls_wechat.php',95);
	    $ret_arr = json_decode($rets,true);
	    //weixindebug($ret_arr,'$ret_arr',0,'','','','mobile/includes/cls_wechat.php',82);
		if(!empty($ret_arr['openid'])){
		   return 	$ret_arr['openid'];
	    }

		else{
		   show_message('get openid failure!');	
		}
  }
		
  function get_user_info($wechatid){
	 if(!empty($wechatid)) {
	     $this->access_token($GLOBALS['db']);
	     $ret = $GLOBALS['db']->getRow("SELECT * FROM " . $GLOBALS['yke']->table(1,'weixin_config') ." "."WHERE `sysid` = '".$GLOBALS['sysid']."'");
	     $access_token = $ret['access_token'];
	     $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$access_token&openid=$wechatid";
	     $res_json = $this->curl_get_contents($url);
	     $w_user = json_decode($res_json,TRUE);
	     if($w_user['errcode'] == '40001') 
	     {
		     $access_token = $this->new_access_token($GLOBALS['db']);
		     $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$access_token&openid=$wechatid";
		     $res_json = $this->curl_get_contents($url);
		     $w_user = json_decode($res_json,TRUE);
	     }
	   weixindebug($w_user,'$w_user',0,'','','','mobile/includes/cls_wechat.php',122);
	   return $w_user;
	  }
  }
  
  function get_wechat_config(){
	    $row = $GLOBALS['db']->getRow("SELECT * FROM " . $GLOBALS['yke']->table(1,'weixin_config') ." "."WHERE `sysid` = '".$GLOBALS['sysid']."'");
		if(!$row || empty($row['appid']) || empty($row['appsecret'])){
		   show_message('微信没有设置相关appid和appsecret参数');	
		}
		return $row;
  }
  
  function get_wechat_code(){
		if(empty($_GET['code'])){  
		   $redirect_uri = urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']);
		   $para = array(
	         "appid"         => $row['appid'],
		     "redirect_uri"  => $redirect_uri,
		     "response_type" => 'code',
		     "scope"         => 'snsapi_base',//userinfo
		     "state"         => '123#wechat_redirect'
	       );
		   $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$row['appid']."&redirect_uri=".$para['redirect_uri']."&response_type=code&scope=snsapi_base&state=123#wechat_redirect";
		   url_redirect($url);
	   }
}

//取得openid
  function get_openid($code){
	  $row = $GLOBALS['db']->getRow("SELECT * FROM " . $GLOBALS['yke']->table(1,'weixin_config') ." "."WHERE `sysid` = '".$GLOBALS['sysid']."'");
	  if(empty($row['appid']) || empty($row['appsecret'])){
	      return 0;   
	  }
	  $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$row['appid']."&secret=".$row['appsecret']."&code=".$code."&grant_type=authorization_code";
	  $ret =  $this->curl_get_contents($url);
	  $ret_arr = json_decode($ret,true);	
	  return $ret_arr;
  }


  //判断用户是否关注
  public function is_subscribe($wechatid){
      $res = 1;
	  if(empty($wechatid))
	    $res = 0;
	  else{ 
 	    $is_subscribe =  $GLOBALS['db']->getOne("select is_subscribe from ".$GLOBALS['yke']->table(1,'weixin_user')." where fake_id='".$wechatid."'");
	    if(!$is_subscribe){
		   $res = 0;  
	    }
	    if($is_subscribe == 0)
	       $res = 0; 
	    }
	return $res;
   }
  //判断用户是否关注,byid
  public function is_subscribe_byid($userid){
      $res = 1;
	  if(empty($userid))
	    $res = 0;
	  else{ 
 	    $is_subscribe =  $GLOBALS['db']->getOne("select is_subscribe from ".$GLOBALS['yke']->table(1,'weixin_user')." where user_id=".$userid);
	    if(!$is_subscribe){
		   $res = 0;  
	    }
	    if($is_subscribe == 0)
	       $res = 0; 
	    }
	return $res;
   }
  //验证身份
   public function validate_user(){
	    if(empty($_SESSION['user_id']) || empty($_SESSION['wechat_id'])){
			show_message('用户验证失败');return;  
		}else
		    return $_SESSION['user_id'];
   } 


  function create_wechat_user($wechatid,$u) 	  //如果微信用户openid不在数据库中，记录推荐人
  {
		if(empty($u)){
			$u=0;
		}
		$ret = $GLOBALS['db'] -> getRow("SELECT `fake_id` FROM ".$GLOBALS['yke']->table(1,'weixin_user')." WHERE `fake_id` = '$wechatid'");
		if (empty($ret)) {
			if (!empty($wechatid)) {
				$createtime = time();
				$createymd = date('Y-m-d');
				$sql = "insert into ".$GLOBALS['yke']->table(1,'weixin_user')."(`sysid`,`ecuid`,`fake_id`,`createtime`,`createymd`,`isfollow`,`nickname`,`sex`,`country`,`province`,`city`,`access_token`,`expire_in`,`headimgurl`,`from_id`,`affiliate`) value ("."'".$GLOBALS['sysid']."',"."0,'{$wechatid}','{$createtime}','{$createymd}',0,'','','','','','','','','',$u)";
				$GLOBALS['db']->query($sql);
			} 
		} 
	}
  
  //更新微信用户数据
  function update_userinfo($wechatid){
	  $user = $GLOBALS['db']->getRow("select * from ".$GLOBALS['yke']->table(1,'weixin_user')." where fake_id='".$wechatid."'");
      if($user){
		  
			$info = $this->get_user_info($wechatid);
			$nickname = $info['nickname'];
			$sex = intval($info['sex']);
			$country = $info['country'];
			$province = $info['province'];
			$city = $info['city'];
			$access_token = $info['access_token'];
			$headimgurl = $info['headimgurl'];
			$expire_in = time()+48*3600;
			if($info){
				$set = ",`nickname`='{$nickname}',`sex`='$sex',`country`='$country',`province`='$province',
					`city`='$city',`access_token`='$access_token',`expire_in`='$expire_in',`headimgurl`='$headimgurl'";
			}
			$sql = "update ".$GLOBALS['yke']->table(1,'weixin_user')." set from_id='{$from_id}'{$set} where fake_id='{$wechatid}'";
		    $GLOBALS['db']->query($sql);
	     
	  }
  }

  function access_token() 
  {
	  $ret = $GLOBALS['db']->getRow("SELECT * FROM " . $GLOBALS['yke']->table(1,'weixin_config') ." "."WHERE `sysid` = '".$GLOBALS['sysid']."'");
	  $appid = $ret['appid'];
	  $appsecret = $ret['appsecret'];
	  $access_token = $ret['access_token'];
	  $dateline = $ret['dateline'];
	  $time = time();
	  if(($time - $dateline) >= 7200-20) 
	  {
		  $access_token = $this->new_access_token();
	  }
	  elseif(empty($access_token)) 
	  {
		  $access_token = $this->new_access_token();
	  }
	  return $access_token;
  }
  
  function new_access_token() 
  {
	  $ret = $GLOBALS['db']->getRow("SELECT * FROM " . $GLOBALS['yke']->table(1,'weixin_config') ." "."WHERE `sysid` = '".$GLOBALS['sysid']."'");
	  $appid = $ret['appid'];
	  $appsecret = $ret['appsecret'];
	  $dateline = $ret['dateline'];
	  $access_token = $ret['access_token'];
	  $time = time();

	  $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";
	  $ret_json = $this->curl_get_contents($url);
	  $ret = json_decode($ret_json);
	  if($ret->access_token)
	  {
		$GLOBALS['db']->query("UPDATE `yke_weixin_config` SET `access_token` = '$ret->access_token',`dateline` = '$time'"."WHERE `sysid` = '".$GLOBALS['sysid']."'");
	  }
	  return $ret->access_token;

  }
  
  function curl_get_contents($url){
	   $ch = curl_init();
	   curl_setopt($ch, CURLOPT_URL, $url);
	   curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Macintosh; Intel Mac OS X 10.9; rv:26.0) Gecko/20100101 Firefox/26.0");
	   curl_setopt($ch, CURLOPT_REFERER,$url);
	   curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
	   curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	   curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	   $r = curl_exec($ch);
	   curl_close($ch);
	   return $r;  
  }
  function curl_get_contents1($url){
		$ch = curl_init();
		$header = "Accept-Charset: utf-8";
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$temp = curl_exec($ch);
		return $temp;
  }

  function curl_grab_page($url,$data,$proxy='',$proxystatus='',$ref_url='') 
  {
	  $ch = curl_init();
	  curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
	  curl_setopt($ch, CURLOPT_TIMEOUT, 3);
	  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	  if ($proxystatus == 'true') 
	  {
		curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, TRUE);
		curl_setopt($ch, CURLOPT_PROXY, $proxy);
	  }
	  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	  curl_setopt($ch, CURLOPT_URL, $url);
	  if(!empty($ref_url))
	  {
		curl_setopt($ch, CURLOPT_HEADER, TRUE);
		curl_setopt($ch, CURLOPT_REFERER, $ref_url);
	  }
	  curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
	  curl_setopt($ch, CURLOPT_MAXREDIRS, 200);
	  @curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
	  curl_setopt($ch, CURLOPT_POST, TRUE);
	  curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	  ob_start();
	  return curl_exec ($ch);
	  ob_end_clean();
	  curl_close ($ch);
	  unset($ch);
  }

}
?>