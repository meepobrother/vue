<?php
global $_W;
$input = $this->__input['encrypted'];

$id = $input['id'];
$item = pdo_get('imeepos_runner4_order_goods',array('id'=>$id));
$this->info = $item;
return $this;