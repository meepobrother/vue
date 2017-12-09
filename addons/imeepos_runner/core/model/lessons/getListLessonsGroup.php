<?php

global $_W;
$input = $this->__input['encrypted'];
$list = getListTopicGroup(0);

$this->info = $list;
return $this;

function getListTopicGroup($fid = 0){
    global $_W;
    $sql = "SELECT * FROM ".tablename('imeepos_runner4_lessons_group')." WHERE uniacid=:uniacid AND fid=:fid ORDER BY displayorder ASC";
    $params = array(':uniacid'=>$_W['uniacid'],':fid'=>$fid);
    $list = pdo_fetchall($sql,$params);
    foreach($list as &$li){
        $li['tags'] = unserialize($li['tags']);
        $children = getListTopicGroup($li['id']);
        if(!empty($children)){
            $li['children'] = $children;
        }
    }
    unset($li);
    $list = $list ? $list : array();
    return $list;
}