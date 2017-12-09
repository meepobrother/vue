<?php

global $_W;
$input = $this->__input['encrypted'];

$type = trim($input['type']);
$data = $input['data'];
$shop_id = $input['shop_id'];

$shop = array();
$shop[$type] = serialize($data);

if(pdo_update('imeepos_runner4_shops',$shop,array('id'=>$shop_id))){
    $this->info = '成功';
}else{
    $this->info = '失败';
}

udpateMemberShopId($shop_id, $data);

$this->msg = $input;
return $this;

function udpateMemberShopId($shop_id = 0, $members = array()){
	foreach($members as $member){
		pdo_update('imeepos_runner3_member',array('shop_id'=>$shop_id),array('openid'=>$member['openid']));
	}
}