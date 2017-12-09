<?php
global $_W,$_GPC;
$input = $this->__input['encrypted'];
$id = intval($input['id']);
$item = pdo_get('imeepos_runner3_member',array('id'=>$id));
$this->info = $item;
return $this;