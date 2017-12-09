<?php

$input = $this->__input['encrypted'];

$id = intval($input['id']);

$table = "imeepos_runner4_app_catalog_pages";


$page = pdo_get($table, array('id'=>$id));

$page['header'] = _unserialize($page['header'],'layout-header','头部',array());
$page['footer'] = _unserialize($page['footer'],'layout-footer','底部',array());
$page['body'] = _unserialize($page['body'],'layout-body','主体',array());
$page['menu'] = _unserialize($page['menu'],'layout-menu','快捷菜单',array());
$page['kefu'] = _unserialize($page['kefu'],'layout-kefu','客服',array());

$this->info = $page;
return $this;

function _unserialize($str = '',$type = '',$name = '',$children = array()){
    $data = unserialize($str);
    if(empty($data)){
        $data = array(
            'type'=>$type,
            'name'=>$name,
            'children'=>$children,
            'styleObj'=>array(
                'color'=> '#fff'
            ),
            'classObj'=>array(
                'author'=>false
            ),
            'containerClass'=>array(
                'author'=>false
            ),
            'containerStyle'=>array(
                'color'=> '#fff'
            ),            
        );
    }
    return $data;
}