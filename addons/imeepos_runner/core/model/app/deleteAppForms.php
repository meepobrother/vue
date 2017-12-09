<?php
global $_W;
$input = $this->__input['encrypted'];
pdo_delete('imeepos_runner4_app_forms',array('id'=>$input['id']));
$this->info = $input;
$this->msg = 'success';
return $this;