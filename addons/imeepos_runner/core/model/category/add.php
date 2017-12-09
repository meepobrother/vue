<?php
global $_W;
$input = $this->__input;

$data = $input['data'];
$data['uniacid'] = $_W['uniacid'];
$data['displayorder'] = !empty($data['displayorder']) ? $data['displayorder'] : time();
$data['create_time'] = time();
$data['setting'] = !empty($data['setting']) ? $data['setting'] : array();
$data['setting'] = serialize($data['setting']);
if(empty($data['title'])){
    $data['title'] = '分类标题';
}
pdo_insert('imeepos_runner3_category',$data);
$data['id'] = pdo_insertid();

$this->info = $data;
return $this;