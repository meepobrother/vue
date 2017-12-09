<?php
global $_W;
$input = $this->__input['encrypted'];

$_item = $input['data'];

$data = array();
$data['openid'] = $_W['openid'];
$data['avatar'] = $input['avatar'];
$data['nickname'] = $input['nickname'];
$data['to_openid'] = ''.$_item['pid'];
$data['to_avatar'] = '';
$data['to_nickname'] = '';
$data['content'] = $input['content'];
$data['uniacid'] = $_W['uniacid'];
$data['create_time'] = time();

if(!pdo_fieldexists('imeepos_runner_plugin_im','type')){
    pdo_query("ALTER TABLE ".tablename('imeepos_runner_plugin_im')." ADD COLUMN `type` varchar(32) DEFAULT ''");
}
$data['type'] = $input['type'];
if(!pdo_fieldexists('imeepos_runner_plugin_im','data')){
    pdo_query("ALTER TABLE ".tablename('imeepos_runner_plugin_im')." ADD COLUMN `data` text DEFAULT ''");
}
$data['data'] = serialize($input['data']);

pdo_insert('imeepos_runner_plugin_im',$data);

$this->info = $data;
$this->msg = $input;

return $this;