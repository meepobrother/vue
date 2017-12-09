<?php

global $_W;
$input = $this->__input['encrypted'];
$id = intval($input['id']);
$shop = pdo_get('imeepos_runner4_shops',array('id'=>$id));
// 解放会员
if(!empty($shop)){
	pdo_update('imeepos_runner3_member',array('shop_id'=>0),array('shop_id'=>$id));
	pdo_delete('imeepos_runner4_shops',array('id'=>$input['id']));
}

$this->info = $input;
$this->msg = 'success';
return $this;
