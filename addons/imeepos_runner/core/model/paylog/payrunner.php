<?php
global $_W;
$input = $this->__input['encrypted'];


//打款
$id = intval($input['id']);
$type = $input['type'];

$paylog = pdo_get('imeepos_runner3_tasks_paylog',array('id'=>$id));

if($paylog['status'] != 4){
	$this->code = 0;
	$this->msg = '状态错误';
	return $this;
}

$task = pdo_get('imeepos_runner3_tasks',array('id'=>$paylog['tasks_id']));
$recive = pdo_get('imeepos_runner3_recive',array('taskid'=>$paylog['tasks_id']));
$openid = $recive['openid'];
$detail = pdo_get('imeepos_runner3_detail',array('taskid'=>$paylog['tasks_id']));

if($type == 'wechat'){
	//发放佣金
	$setting = M('setting')->getValue('setting.system');
	$file = ROUTERPATH."/libs/WeiXinPay2.php";
	if(file_exists($file)){
	    include_once $file;
	    $domain = new WeiXinPay2();
	    
	    //打款到这个账户

	    //如果是帮我买
	    if(floatval($detail['goodscost'] <= 0)){
	    	$detail['goodscost'] = 0;
	    }
	    $meony = ($paylog['fee'] - floatval($detail['goodscost'])) * (100 - $setting['price']) + floatval($detail['goodscost']);
	    
	    $meony = intval($meony);
	    $tid = $paylog['tid'];
	    $desc = '任务赏金发放';
	    $return = $domain->pay($openid,$meony,$tid,$desc);
	    if(is_error($return)){
	    	$this->code = 0;
	    	$this->msg = $return['message'].',金额:'.$meony.'分';
	    	return $this;
	    }

	    $content = "您受理的任务赏金已发放到您的微信余额!\n";
		$content .="到账金额: ".($meony / 100 )."元\n";
		$title = '赏金到账微信通知';
		$url = $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&do=detail&id='.$recive['taskid'].'&m=imeepos_runner';

		// $url = $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&do=index&m=imeepos_runner';
		M('common')->mc_notice_consume2($openid,$title,$content,$url);

	    pdo_update('imeepos_runner3_tasks_paylog',array('status'=>5),array('id'=>$id));
		$this->msg = '操作成功';
		return $this;
	}else{
		$this->code = 0;
		$this->msg = '文件丢失';
		return $this;
	}
}

if($type == 'credit'){
	load()->model('mc');
	$uid = mc_openid2uid($openid);
	if(empty($uid)){
		$this->code = 0;
		$this->msg = 'uid为空';
		return $this;
	}
	$setting = M('setting')->getValue('setting.system');
	$meony = ($paylog['fee'] - floatval($detail['goodscost'])) * (100 - $setting['price']) / 100 + floatval($detail['goodscost']);
	// $meony = $paylog['fee'] * (100 - $setting['price']) / 100;
	$return = mc_credit_update($uid,'credit2',$meony,'跑腿任务退款('.$id.')');
	if(is_error($return)){
		$this->code = 0;
		$this->msg = $return['message'].',金额'.$meony;
		return $this;
	}
	
	$content = "您受理的任务赏金已发放到您的账户余额!\n";
	$content .="到账金额: ".$meony."元\n";
	$title = '赏金到账余额通知';
	$url = $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&do=detail&id='.$recive['taskid'].'&m=imeepos_runner';
	M('common')->mc_notice_consume2($openid,$title,$content,$url);

	pdo_update('imeepos_runner3_tasks_paylog',array('status'=>5),array('id'=>$id));
	$this->msg = '操作成功,到账金额'.$meony.'元';
	return $this;
}


