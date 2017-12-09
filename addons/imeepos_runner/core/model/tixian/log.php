<?php
global $_W;

$input = $this->__input['encrypted'];
$openid = $_W['openid'];
$member =  pdo_get('imeepos_runner3_member',array('openid'=>$openid,'uniacid'=>$_W['uniacid']));
$action = $input['action'];


$start = intval($input['start']);
$len = intval($input['len']);

$start = $start > 0 ? $start: 0;
$len = $len > 0? $len: 20;


if($action == 'mylog'){
    $where = "";
    if(empty($openid)){
        $openid = $input['openid'];
    }
	$params = array(':openid'=>$openid,':uniacid'=>$_W['uniacid']);
	if(isset($input['status'])){
		$where .=" AND status=:status";
		$params[':status'] = intval($input['status']);
	}
	$sql = "SELECT * FROM ".tablename('imeepos_tixian_manage')." WHERE openid=:openid AND uniacid=:uniacid {$where} ORDER BY create_time DESC limit {$start},{$len}";
	$list = pdo_fetchall($sql,$params);
	if(empty($list)){
		$list = array();
	}
	foreach($list as &$li){
		$member = pdo_get('imeepos_runner3_member',array('openid'=>$li['openid'],'uniacid'=>$_W['uniacid']));

		$li['nickname'] = $member['nickname'];
		$li['mobile'] = $member['mobile'];
		$li['avatar'] = $member['avatar'];

		$li['create_time'] = date('m-d H:i',$li['create_time']);
	}
	unset($li);

	$this->info = $list;
	$this->msg = $input;
	return $this;
}

if($action == 'delete'){
	if($member['isadmin']== 1 || $member['ismanager']){
		$id = intval($input['id']);
		if(empty($id)){
			$this->code = 0;
			$this->msg ='参数错误';
			return $this;
		}
		pdo_delete('imeepos_tixian_manage',array('id'=>$id));
		$this->msg = '操作成功';
		return $this;
	}else{
		$this->msg = '权限错误';
		$this->code = 0;
		return $this;
	}
}

if($action == 'redback'){
	if($member['isadmin']== 1 || $member['ismanager']){
		$id = intval($input['id']);

		if(empty($id)){
			$this->code = 0;
			$this->msg ='参数错误';
			return $this;
		}
		$log = pdo_get('imeepos_tixian_manage',array('id'=>$id));
		$openid = $log['openid'];
		load()->model('mc');
		$uid = mc_openid2uid($log['openid']);
		if(empty($uid)){
			$this->code = 0;
			$this->msg = 'uid为空';
			return $this;
		}
		$meony = floatval($log['credit']);
		$return = mc_credit_update($uid,'credit2',$meony,array($uid,'提现退回','imeepos_runner','0','0'));
		if(is_error($return)){
			$this->code = 0;
			$this->msg = $return['message'];
			return $this;
		}
		
		$content = "您的提现申请已被退还到用户余额!\n";
		$content .="到账金额: ".$meony."元\n";
		$title = '提现申请处理通知';
		$url = $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&do=index&m=imeepos_runner';
		M('common')->mc_notice_consume2($openid,$title,$content,$url);

		pdo_update('imeepos_tixian_manage',array('status'=>2,'message'=>'退还余额'),array('id'=>$id));
		$this->msg = '操作成功';
		return $this;
	}else{
		$this->msg = '权限错误';
		$this->code = 0;
		return $this;
	}
}

if($action == 'fafang'){
	if($member['isadmin']== 1 || $member['ismanager']){
		$id = intval($input['id']);
		if(empty($id)){
			$this->code = 0;
			$this->msg ='参数错误';
			return $this;
		}
		$log = pdo_get('imeepos_tixian_manage',array('id'=>$id));
		$openid = $log['openid'];
		$meony = floatval($log['credit']) * 100;
		$file = IA_ROOT."/addons/imeepos_runner/core/libs/WeiXinPay2.php";
		if(file_exists($file)){
		    include_once $file;
		    $domain = new WeiXinPay2();
		    $tid = time();
		    $desc = '余额提现';
		    $return = $domain->pay($openid,$meony,$tid,$desc);
		    if(is_error($return)){
		    	$this->code = 0;
		    	$this->msg = $return['message'];
		    	return $this;
		    }

		    $content = "您提交的提现已到账微信红包!\n";
			$content .="到账金额: ".($meony / 100 )."元\n";
			$title = '提现到账通知';
			$url = $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&do=index&m=imeepos_runner';
			M('common')->mc_notice_consume2($openid,$title,$content,$url);

		    pdo_update('imeepos_tixian_manage',array('status'=>2,'message'=>'到账微信红包'),array('id'=>$id));
			$this->msg = '操作成功';
			return $this;
		}else{
			$this->code = 0;
			$this->msg = '文件丢失';
			$this->info = $file;
			return $this;
		}
	}else{
		$this->msg = '权限错误';
		$this->code = 0;
		return $this;
	}
}

if($action == 'admin'){
	if($member['isadmin']== 1 || $member['ismanager']){
		$where = "";
		$params = array(':uniacid'=>$_W['uniacid']);

		if(isset($input['status'])){
			$where .=" AND status=:status";
			$params[':status'] = intval($input['status']);
		}
		$sql = "SELECT * FROM ".tablename('imeepos_tixian_manage')." WHERE uniacid=:uniacid {$where} ORDER BY create_time DESC limit {$start},{$len}";
		$list = pdo_fetchall($sql,$params);
		if(empty($list)){
			$list = array();
		}
		foreach($list as &$li){
			$member = pdo_get('imeepos_runner3_member',array('openid'=>$li['openid'],'uniacid'=>$_W['uniacid']));

			$li['nickname'] = $member['nickname'];
			$li['mobile'] = $member['mobile'];
			$li['avatar'] = $member['avatar'];
		}
		unset($li);

		$this->info = $list;
		$this->msg = $input;
		return $this;
	}else{
		$this->msg = '权限错误';
		$this->code = 0;
		return $this;
	}
}


return $this;