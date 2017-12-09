<?php
$input = $this->__input['encrypted'];
$this->info = $input;

getList($input);

return $this;


function getList($input = array(),$fid = 0){
    foreach($input as $key=>$item){
        pdo_update('imeepos_runner4_services_group',array('displayorder'=>$key,'fid'=>$fid),array('id'=>$item['id']));         
        if(!empty($item['children'])){
            getList($item['children'],$item['id']);
        }
    }
}
