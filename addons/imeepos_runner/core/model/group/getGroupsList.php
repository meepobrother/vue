<?php
global $_W,$_GPC;

$activesGroup = getListActivesGroup(0,'actives','group');
$topicsGroup = getListActivesGroup(0,'topics','group');
$tasksGroup = getListActivesGroup(0,'tasks','group');
$ordersGroup = getListActivesGroup(0,'order','class');
$goodsGroup = getListActivesGroup(0,'goods','group');
$skillsGroup = getListActivesGroup(0,'skills','group');
$shopsGroup = getListActivesGroup(0,'shops','group');
$memberGroup = getListActivesGroup(0,'member','group');

$data = array();
$data[] = array('items'=>$activesGroup,'title'=>'活动分类','code'=>'actives');
$data[] = array('items'=>$topicsGroup,'title'=>'主题分类','code'=>'topics');
$data[] = array('items'=>$tasksGroup,'title'=>'任务分类','code'=>'tasks');
$data[] = array('items'=>$ordersGroup,'title'=>'工单分类','code'=>'order');
$data[] = array('items'=>$goodsGroup,'title'=>'商品分类','code'=>'goods');
$data[] = array('items'=>$skillsGroup,'title'=>'技能分类','code'=>'skills');
$data[] = array('items'=>$shopsGroup,'title'=>'店铺分类','code'=>'shops');
$data[] = array('items'=>$memberGroup,'title'=>'会员分类','code'=>'member');

$this->info = $data;
return $this;

function getListActivesGroup($fid = 0,$table = 'actives',$pre="group"){
    global $_W;
    $sql = "SELECT * FROM ".tablename('imeepos_runner4_'.$table.'_'.$pre)." WHERE uniacid=:uniacid AND fid=:fid ORDER BY displayorder ASC";
    $params = array(':uniacid'=>$_W['uniacid'],':fid'=>$fid);
    $list = pdo_fetchall($sql,$params);
    foreach($list as &$li){
        $li['tags'] = unserialize($li['tags']);
        $children = getListActivesGroup($li['id']);
        if(!empty($children)){
            $li['children'] = $children;
        }
    }
    unset($li);
    $list = $list ? $list : array();
    return $list;
}
