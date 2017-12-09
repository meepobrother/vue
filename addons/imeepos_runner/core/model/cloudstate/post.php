<?php
global $_W,$_GPC;
$input = $this->__input['encrypted'];

$data = array();
$data['url'] = $input['url'];
$data['uniacid'] = $input['uniacid'];
$data['avatar'] = $input['avatar'];
$data['qrcode'] = $input['qrcode'];
$data['title'] = $input['title'];
$data['desc'] = $input['desc'];
$data['scan_num'] = 1;

$site = pdo_get('imeepos_runner4_cloud_site',array('url'=>$data['url'],'uniacid'=>$data['uniacid']));
if(empty($site)){
    pdo_insert('imeepos_runner4_cloud_site',$data);
}else{
    $data['scan_num'] = $site['scan_num'] + 1;
    pdo_update('imeepos_runner4_cloud_site',$update,array('id'=>$site['id']));
}

return $this;