<?php
global $_W;
$input = $this->__input['encrypted'];

$id = $input['id'];
$item = pdo_get('imeepos_runner4_services_group',array('id'=>$id));
$this->info = $item;
return $this;