<?php
global $_W;
$input = $this->__input['encrypted'];
$openid = $_W['openid'];

if(empty($openid)){
    $openid = $input['openid'];
}

load()->model('mc');
$uid = mc_openid2uid($openid);

$money = intval($input['tixian']['value']);
if(empty($money)){
	$this->code = 0;
	$this->msg = '金额有误';
	return $this;
}

if(empty($uid)){
	$this->code = 0;
	$this->msg = '会员不存在';
	return $this;
}
$return = mc_credit_update($uid,'credit2','-'.$money,array($uid,$input['goods']));
if(is_error($return)){
	$this->code = 0;
	$this->msg = "对不起,您的余额不足";
	return $this;
}

//插入提现日志
//imeepos_tixian_manage
$data = array();
$data['uniacid'] = $_W['uniacid'];
$data['create_time'] = time();
$data['openid'] = $openid;
$data['status'] = 0;
$data['credit'] = $money;
$data['message'] = $input['tixian']['title'];

pdo_insert('imeepos_tixian_manage',$data);
$this->msg = '操作成功';
$this->info = pdo_insertid();

return $this;