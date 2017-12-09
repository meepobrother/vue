<?php
global $_W,$_GPC;
$input = $this->__input;

$_GPC = array_merge($_GPC,$input);

load()->func('file');
$sysms_set = M('setting')->getValue('sms_set');
//如果后台启用验证码，检查验证码是否正确

if(!empty($sysms_set['post_open'])){
	$code = $input['code'];
	$codeid = $input['codeid'];
	$code_row = M('code')->getInfo($codeid);
	if($code != $code_row['code']){
		$this->code = 0;
		$this->msg = '您输入的验证码有误！';
		return $this;
	}
}

$data = array();

$data['goodsweight'] = isset($input['goodsweight'])?floatval($input['goodsweight']):'0.00';
$data['goodscost'] = isset($input['goodscost'])?floatval($input['goodscost']):'0';
$data['goodsname'] = isset($input['goodstitle'])?trim($input['goodstitle']):'';

$data['sendaddress'] = trim($input['sendaddress']['title']);
$data['senddetail'] = trim($input['sendaddress']['detail']);
$data['sendrealname'] = isset($input['sendaddress']['realname'])?trim($input['sendaddress']['realname']):'';
$data['sendmobile'] = trim($input['sendaddress']['mobile']);
$data['sendlon'] = floatval($input['sendaddress']['lng']);
$data['sendlat'] = floatval($input['sendaddress']['lat']);

if(empty($data['sendlat']) || empty($data['sendlon']) ){
	$this->code = 0;
	$this->msg = '请选择发货位置';
	return $this;
}


$data['receiveaddress'] = trim($input['receiveaddress']['title']);
$data['receivedetail'] = trim($input['receiveaddress']['detail']);
$data['receiverealname'] = isset($input['receiveaddress']['realname'])?trim($input['receiveaddress']['realname']):'';
$data['receivemobile'] = trim($input['receiveaddress']['mobile']);
$data['receivelon'] = floatval($input['receiveaddress']['lng']);
$data['receivelat'] = floatval($input['receiveaddress']['lat']);


$data['message'] = trim($input['message']);

$images = isset($input['thumbs'])?$input['thumbs']:array();
$imgs = array();
if(!empty($images)){
	foreach ($images as $img){
		$imgs[] = M('image')->createImage($img['src']);
	}
}

$data['images'] = serialize($imgs);

if(!empty($_GPC['distance'])){
	//距离 米
    $data['distance'] = intval($_GPC['distance']);
	$data['float_distance'] = floatval($data['distance']/1000);
}else{
	//$url = "http://apis.map.qq.com/uri/v1/routeplan?type=drive&from={$data['sendaddress']}&fromcoord={$data['sendlat']},{$data['sendlon']}&to={$data['receiveaddress']}&tocoord={$data['receivelat']},{$data['receivelon']}&policy=1&referer=myapp";
	$url = "http://apis.map.qq.com/ws/distance/v1/?mode=driving&from={$data['sendlat']},{$data['sendlon']}&to={$data['receivelat']},{$data['receivelon']}&output=json&key=4MHBZ-JVL35-WLMII-Q3NME-3Z2G2-PKBJJ";
	load()->func('communication');
	$content = ihttp_get($url);
	$content = @json_decode($content['content'], true);

	if($content['status'] == '373'){
		$result = array();
		$result['result'] = 0;
		$result['message'] = $content['message']."，最大距离为10公里";
		die(json_encode($result));
	}
	$content = $content['result']['elements'][0];
	$data['distance'] = intval(intval($content['distance'])/1000);
	$data['float_distance'] = floatval(intval($content['distance'])/1000);
}

if(!empty($_GPC['duration'])){
    $data['duration'] = intval($_GPC['duration']);
}
$data['pickupdate'] = strtotime(trim($input['datatime']['value']));

if(!empty($input['datatime']['value'])){
	$data['dataTimeValue'] = strtotime(trim($input['datatime']['value']));//预约取
}else{
	$code = 'plugin_setting';
	$item = M('setting')->getByCode($code);
	$plugin = iunserializer($item['value']);
	$hour = floatval($plugin['limit_time']);
	if(empty($hour)){
		$hour = 3;
	}
	$data['dataTimeValue'] = time() + intval(60*60*$hour);
}

if(empty($data['dataTimeValue'])){
	$code = 'plugin_setting';
	$item = M('setting')->getByCode($code);
	$plugin = iunserializer($item['value']);
	$hour = floatval($plugin['limit_time']);
	if(empty($hour)){
		$hour = 3;
	}
	$data['dataTimeValue'] = time() + intval(60*60*$hour);
}
$data['time'] = intval($input['time']);

$data['small_money'] = floatval($input['small_money']);

if($data['small_money'] < 0){
	$data['small_money'] = abs($data['small_money']);
}

//计算费用
$set = M('setting')->getValue('divider_set');

//判断是否在起步价内
$distance = floatval($data['distance']);
$max_distance = floatval($set['start_km']);

$distance = $distance/1000;
$price = 0;
$start_price = floatval($set['start_fee']);

$v_set = M('setting')->getValue('v_set');

if($distance > $max_distance){
	$chao_distance = $distance - $max_distance;
	$limit_km_km = floatval($set['limit_km_km']);
	if($limit_km_km >0){
		if($v_set['open_45'] == 1){
			$chao = round($chao_distance / $limit_km_km);
		}else{
			$chao = ceil($chao_distance / $limit_km_km);
		}
	}else{
		$chao = 0;
	}
	$limit_km_fee = floatval($set['limit_km_fee']);
	$price += $chao * $limit_km_fee;
}

$max_goodsweight = floatval($set['start_kg']);
$goodsweight = floatval($data['goodsweight']);

if($goodsweight > $max_goodsweight){
	$chao_goodsweight = $goodsweight - $max_goodsweight;
	$limit_kg_kg = floatval($set['limit_kg_kg']);
	if($limit_kg_kg >0){
		if($v_set['open_45'] == 1){
			$chao = round($chao_goodsweight / $limit_kg_kg);
		}else{
			$chao = ceil($chao_goodsweight / $limit_kg_kg);
		}
	}else{
		$chao = 0;
	}
	$limit_kg_fee = floatval($set['limit_kg_fee']);
	$price += $chao * $limit_kg_fee;
}



$data['base_fee'] = $start_price;
$data['fee'] = $price;
$data['total'] = $start_price + $price + $data['small_money'];

$now = time();

$month=intval(date('m'));
$day=intval(date('d'));
$year=intval(date('Y'));

$start_time = intval($set['start_time']);
$start_time = mktime($start_time,0,0,$month,$day,$year);
$end_time = intval($set['end_time']);
$end_time = mktime($end_time,0,0,$month,$day,$year);

$time_fee = (1+floatval($set['time_fee'])/100);

if($now >= $start_time && $now <= $end_time){

}else{
	$data['base_fee'] = $data['base_fee'] * $time_fee;
	$data['fee'] = $data['fee'] * $time_fee;
	$data['total'] = $data['base_fee'] +$data['fee'] + $data['small_money'];
}

$detail = $data;

if(empty($input['media_id'])){
	$text = "";
	if(empty($data['time'])){
		//及时取

		$text .="及时取";
		if(!empty($data['small_money'])){
			$text .= "(加急):";
		}
		if(!empty($data['goodsname'])){
			$text .= "商品名称:".$data['goodsname'];
		}
		if(!empty($data['goodscost'])){
			$text .= ",价值：".$data['goodscost']."元";
		}
		if(!empty($data['goodsweight'])){
			$text .= ",".$data['goodsweight']."公斤";
		}
		if(!empty($data['distance'])){
			$text .= ",总路程".($data['float_distance'])."公里";
		}
		$text .= '从'.$data['sendaddress'].'送到'.$data['receiveaddress'].",赏金：".$data['total']."元";
	}else{
		$text .="预约取";
		if(!empty($data['small_money'])){
			$text .= "(加急):";
		}
		if(!empty($data['goodsname'])){
			$text .= "商品名称:".$data['goodsname'];
		}
		if(!empty($data['goodscost'])){
			$text .= ",价值：".$data['goodscost']."元";
		}
		if(!empty($data['goodsweight'])){
			$text .= ",".$data['goodsweight']."公斤";
		}
		if(!empty($data['distance'])){
			$text .= ",总路程".($data['float_distance'])."公里";
		}
		$text .= '从'.$data['sendaddress'].'送到'.$data['receiveaddress'].",赏金：".$data['total']."元";
	}

	$acc = WeAccount::create();
	if(!empty($text)){
		$url = "http://tts.baidu.com/text2audio?lan=zh&ie=UTF-8&spd=5&text=".urlencode($text);
		$img = array();
		$data = file_get_contents($url);
		$type = 'mp3';
		$filename = "audios/imeepos_runner/".time()."_".random(6).".".$type;
		if(file_write($filename,$data)){
			$result = $acc->uploadMedia($filename,'voice');
			$img['media_id'] = $result['media_id'];
		}
	}
}else{
	$img = array();
	$img['media_id'] = $result['media_id'];
}


$tasks = array();
$tasks['uniacid'] = $_W['uniacid'];
$tasks['openid'] = $_W['openid'];
$tasks['create_time'] = time();
$tasks['desc'] = $text;

$tasks['media_id'] = $img['media_id'];
$tasks['status'] = 0;
$tasks['type'] = intval($detail['time']);

$tasks['total'] = $detail['total'];
$tasks['small_money'] = $detail['small_money'];
$tasks['address'] = $detail['receiveaddress'];


$tasks['message'] = $detail['message'];
$tasks['limit_time'] = $detail['dataTimeValue'];

$input = array();
$input['lat'] = $detail['sendlat'];
$input['lng'] = $detail['sendlon'];
$res = $this->exec('hash.getHash',$input)->getData();

$tasks['hash'] = $res['info'];
$tasks['lat'] = $input['lat'] * 1000000;
$tasks['lng'] = $input['lng'] * 1000000;


pdo_insert('imeepos_runner3_tasks',$tasks);

$detail['taskid'] = pdo_insertid();
$code = random(4,true);
$codetask = array();
$codetask['code'] = $code;
$qrcode = 'imeepos_runner'.md5($code.$tasks['create_time']);
$codetask['qrcode'] = $qrcode;

pdo_update('imeepos_runner3_tasks',$codetask,array('id'=>$detail['taskid']));

pdo_insert('imeepos_runner3_detail',$detail);

//插入订单记录
$paylog = array();
$paylog['fee'] = $detail['total'];
$paylog['tid'] = "U".time().random(6,true);
$paylog['uniacid'] = $_W['uniacid'];
$paylog['setting'] = iserializer(array('taskid'=>$detail['taskid']));
$paylog['status'] = 0;
$paylog['openid'] = $_W['openid'];
$paylog['time'] = time();
$paylog['type'] = 'post_task';

pdo_insert('imeepos_runner3_paylog',$paylog);
$tid = pdo_insertid();

//imeepos_runner3_detail
$result = array();
$result['result'] = 0;
$result['paylog'] = $paylog;
$result['media_id'] = $img['media_id'];
if(empty($distance)){
	$result['message'] = '总费用：'.$detail['total']."元";
}else{
	$result['message'] = '总路程：'.$distance.'公里，总费用：'.$detail['total']."元";
}

$member = M('member')->getInfo($_W['openid']);
$content = "【".$member['nickname']."】,成功发布此任务！";
$data = array();
$data['uniacid'] = $_W['uniacid'];
$data['openid'] = $_W['openid'];
$data['create_time'] = time();
$data['taskid'] = $detail['taskid'];
$data['content'] = $content;
$data['lat'] = $detail['receivelat'];
$data['lng'] = $detail['receivelon'];
M('tasks_log')->update($data);

//新订单后台提醒
$data = array();
$data['uniacid'] = $_W['uniacid'];
$data['create_time'] = time();
$data['status'] = 0;
$data['title'] = "【".$member['nickname']."】成功提交任务";
$data['link'] = '';
$data['task_id'] = $detail['taskid'];
M('message')->update($data);


$result['distance'] = $distance;
$result['tid'] = $tid;
$result['detail'] = $detail;
$result['content'] = $content;
$tasks['limit_time'] = date('Y-m-d H:i',$tasks['limit_time']);
$result['tasks'] = $tasks;

$this->info = $result;

return $this;