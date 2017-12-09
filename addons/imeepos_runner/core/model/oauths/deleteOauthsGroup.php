<?php

global $_W;
$input = $this->__input['encrypted'];
pdo_delete('imeepos_oauth2_manage_group',array('id'=>$input['id']));
$this->info = $input;
$this->msg = 'success';
return $this;