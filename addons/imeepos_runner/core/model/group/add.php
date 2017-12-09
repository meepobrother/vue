<?php
global $_W;
$input = $this->__input['encrypted'];

$data = array();
$data['title'] = $input['title'];
$data['modules'] = serialize($input['modules']);
$data['create_time'] = time();
$data['uniacid'] = $_W['uniacid'];
$data['displayorder'] = time();
$data['uniacid'] = $_W['uniacid'];



return $this;