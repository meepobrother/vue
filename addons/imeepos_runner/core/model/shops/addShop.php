<?php

global $_W;
$input = $this->__input['encrypted'];

$data = array();
$data['uniacid'] = $_W['uniacid'];
$data['title'] = $input['title'];
$data['mobile'] = $input['mobile'];

$location = $input['location'];

$data['lat'] = $location['lat'];
$data['lng'] = $location['lng'];
$data['address'] = $location['address'];
$data['detail'] = $location['detail'];

$data['desc'] = $input['desc'];
$data['content'] = $input['content'];

$data['shopers'] = serialize($input['shopers']);
$data['employers'] = serialize($input['employers']);
$data['kefus'] = serialize($input['kefus']);

if(!empty($data['title'])){
    if(!empty($input['id'])){
        pdo_update('imeepos_runner4_shops',$data,array('id'=>$input['id']));
        $data['id'] = $input['id'];
    }else{
        pdo_insert('imeepos_runner4_shops',$data);
        $data['id'] = pdo_insertid();
    }
}

$data['shopers'] = unserialize($data['shopers']);
$data['employers'] = unserialize($data['employers']);
$data['kefus'] = unserialize($data['kefus']);

// 更新员工所在店铺
udpateMemberShopId($data['id'],$data['shopers']);
udpateMemberShopId($data['id'],$data['employers']);
udpateMemberShopId($data['id'],$data['kefus']);

$this->info = $data;
$this->msg = $input;
return $this;


function udpateMemberShopId($shop_id = 0, $members = array()){
	foreach($members as $member){
		pdo_update('imeepos_runner3_member',array('shop_id'=>$shop_id),array('openid'=>$member['openid']));
	}
}