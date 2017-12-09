<?php
global $_W;

$input = $this->__input['encrypted'];
$id = intval($input['id']);

$id = $input['id'];
//检查订购单是否为空
$order = pdo_get('imeepos_runner3_tasks',array('id'=>$id));
if(empty($order)){
	$this->code = 0;
	$this->msg = '订单不存在或已删除';
	return $this;
}

$detail = pdo_get('imeepos_runner3_detail',array('taskid'=>$id));
if(empty($detail)){
	$this->code = 0;
	$this->msg = '订单不存在或已删除';
}

//检查是否自己订单
$openid = $_W['openid'];

$recive = pdo_get('imeepos_runner3_recive',array('taskid'=>$id));

if(!empty($recive)){
	$this->code = 0;
	$this->msg = '已在配送中,不能取消';
	return $this;
}

if($openid != $order['openid']){
	$this->code = 0;
	$this->msg = '权限错误';
	return $this;
}

//请求撤销订单
$file = ROUTERPATH."/libs/WeiXinPay2.php";
if(file_exists($file)){
    include_once $file;
    $domain = new WeiXinPay2();
    $sql = "SELECT * FROM ".tablename('imeepos_runner3_tasks_paylog')." WHERE tasks_id =:tasks_id AND status = 1";
    $pa = array(':tasks_id'=>$id);
    $item = pdo_fetch($sql,$pa);
    
    if(empty($item)){
    	$this->msg = '恭喜您,撤销成功';
    	pdo_update('imeepos_runner3_tasks',array('status'=>6),array('id'=>$id));
		pdo_update('imeepos_runner3_tasks_paylog',array('status'=>6),array('tasks_id'=>$item['id']));
		return $this;
    }
    if($item['status'] > 1){
    	$this->code = 0;
    	$this->msg = '状态有误';
    	return $this;
    }
    $params = array(
    	'out_trade_no'=>$item['tid'],
    	'out_refund_no'=>time(),
    	'total_fee'=>$item['fee'] * 100,
    	'refund_fee'=>$item['fee'] * 100,
    	'refund_desc'=>'订单申请退款:'.$input['desc']
    );
	$return = $domain -> refund($params);
	if(is_error($return)){
		load()->model('mc');
		load()->func('logging');
		logging_run($return,'trace','imeepos_runner');
		$uid = mc_openid2uid($openid);
		if(empty($uid)){
			$this->code = 0;
			$this->msg = 'uid为空,申请失败';
			return $this;
		}
		$return = mc_credit_update($uid,'credit2',$item['fee'],'跑腿任务退款('.$id.')');
		if(is_error($return)){
			$this->code = 0;
			$this->msg = $return['message'];
			return $this;
		}else{
			//退单成功 
			$this->msg = '恭喜您,撤销成功,退还'.$item['fee'].'元,已到账余额!请查收';
			$this->info = $item;
			// 6退款
			pdo_update('imeepos_runner3_tasks',array('status'=>6),array('id'=>$id));
			pdo_update('imeepos_runner3_tasks_paylog',array('status'=>6),array('id'=>$item['id'],'status'=>1));
			return $this;
		}
	}else{
		$this->msg = '恭喜您,成功申请退款';
		pdo_update('imeepos_runner3_tasks',array('status'=>6),array('id'=>$id));
		pdo_update('imeepos_runner3_tasks_paylog',array('status'=>6),array('id'=>$item['id'],'status'=>1));
		return $this;
	}
}else{
	$this->code = 0;
	$this->msg = '缺少文件';
}



return $this;