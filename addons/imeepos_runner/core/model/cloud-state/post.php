<?php
global $_W,$_GPC;
$input = $this->__input['encrypted'];

$data = array();
$data['url'] = $input['url'];
$data['uniacid'] = $input['uniacid'];
$data['page_id'] = $input['page_id'];
$data['page_title'] = $input['page_title'];

$data['avatar'] = $input['avatar'];
$data['qrcode'] = $input['qrcode'];
$data['title'] = $input['title'];
$data['desc'] = $input['desc'];
$data['scan_num'] = 1;
$data['script_url'] = $input['script_url'];


$site = pdo_get('imeepos_runner4_cloud_site',array('url'=>$data['url'],'uniacid'=>$data['uniacid'],'page_id'=>$data['page_id']));
if(empty($site)){
    pdo_insert('imeepos_runner4_cloud_site',$data);
}else{
    $data['scan_num'] = $site['scan_num'] + 1;
    pdo_update('imeepos_runner4_cloud_site',$data,array('id'=>$site['id']));
}
$this->info = $data;
return $this;
