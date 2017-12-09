<?php
global $_W;
$input = $this->__input['encrypted'];
$table = "imeepos_runner4_goods";

pdo_delete($table,array('id'=>$input['id']));
return $this;