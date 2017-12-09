<?php
global $_W;
$input = $this->__input['encrypted'];
pdo_update('imeepos_runner4_member_group',$input,array('id'=>$input['id']));
$this->info = $input;
return $this;