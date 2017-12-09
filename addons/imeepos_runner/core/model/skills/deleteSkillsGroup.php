<?php

global $_W;
$input = $this->__input['encrypted'];
pdo_delete('imeepos_runner4_skills_group',array('id'=>$input['id']));
$this->info = $input;
$this->msg = 'success';
return $this;