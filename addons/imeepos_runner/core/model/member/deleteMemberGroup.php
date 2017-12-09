<?php
global $_W;
$input = $this->__input['encrypted'];
pdo_delete('imeepos_runner4_member_group',array('uniacid'=>$_W['uniacid'],'id'=>$input['id']));
$this->info = $input;
return $this;