<?php
global $_W;
$input = $this->__input;

$data = $input['data'];
$data['uniacid'] = $_W['uniacid'];
//$data['displayorder'] = !empty($data['displayorder']) ? $data['displayorder'] : time();
$data['time'] = time();

if(empty($data['title'])){
    $data['title'] = '测试测试';
}
pdo_insert('imeepos_runner3_adv',$data);
$data['id'] = pdo_insertid();

$this->info = $data;
return $this;