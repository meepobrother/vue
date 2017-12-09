<?php
global $_W;
$input = $this->__input;

$data = $input['data'];
$id = intval($data['id']);

$data['uniacid'] = !empty($data['uniacid']) ? $data['uniacid'] : $_W['uniacid'];

if(!empty($id)){
    unset($data['id']);
    pdo_update('imeepos_runner3_adv',$data,array('id'=>$id));
}

return $this;