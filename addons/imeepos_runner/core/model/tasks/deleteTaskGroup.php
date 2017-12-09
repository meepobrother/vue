<?php
global $_W,$_GPC;
$input = $this->__input['encrypted'];

pdo_delete('imeepos_runner4_tasks_group',array('id'=>$input['id']));
$this->info = $input;
return $this;