<?php
global $_W;
$input = $this->__input['encrypted'];
$table = "imeepos_runner4_goods";

$data = array();
$data['title'] = $input['title'];
$data['tag'] = $input['tag'];

$data['desc'] = $input['desc'];
$data['thumbs'] = serialize($input['thumbs']);
$data['setting'] = serialize($input['setting']);
$data['content'] = htmlspecialchars($input['content']);
$data['create_time'] = time();
$data['count'] = intval($input['count']);
$data['price'] = floatval($input['price']);
// 店铺id
$data['shop_id'] = intval($input['shop_id']);
$data['group_id'] = intval($input['group_id']);


$data['uniacid'] = $_W['uniacid'];
if(!empty($input['title'])){
    if(empty($input['id'])){
        pdo_insert($table,$data);
        $data['id'] = pdo_insertid();
        $data['thumbs'] = unserialize($data['thumbs']);
        $data['setting'] = unserialize($data['setting']);
    }else{
        pdo_update($table,$data,array('id'=>$input['id']));
        $data['id'] = $input['id'];
        $data['thumbs'] = unserialize($data['thumbs']);
        $data['setting'] = unserialize($data['setting']);
    }
}

$shop = pdo_get('imeepos_runner4_shops',array('id'=>$data['shop_id']));
$data['shop_title'] = $shop['title'];
$group = pdo_get('imeepos_runner4_goods_group',array('id'=>$data['group_id']));
$data['group_title'] = $group['title'];

$this->info = $data;
$this->msg = $input;
return $this;