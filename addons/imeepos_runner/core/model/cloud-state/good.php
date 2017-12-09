<?php
global $_W,$_GPC;
$input = $this->__input['encrypted'];

$site = $input['data'];
$data = array();
$data['good_num'] = $site['good_num'] + 1;
pdo_update('imeepos_runner4_cloud_site',$data,array('id'=>$site['id']));
return $this;