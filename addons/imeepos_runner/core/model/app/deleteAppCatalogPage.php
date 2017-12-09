<?php

$input = $this->__input['encrypted'];
pdo_delete('imeepos_runner4_app_catalog_pages',array('id'=>$input['id']));

$this->info = $input;
return $this;