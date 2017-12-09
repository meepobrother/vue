<?php
global $_W;
$input = $this->__input;

$data = $input['data'];
$id = intval($data['id']);

$data['uniacid'] = !empty($data['uniacid']) ? $data['uniacid'] : $_W['uniacid'];
$data['setting'] = !empty($data['setting']) ? $data['setting'] : array();
$data['setting'] = serialize($data['setting']);

if(!empty($id)){
    unset($data['id']);
    pdo_update('imeepos_runner3_category',$data,array('id'=>$id));
}

return $this;