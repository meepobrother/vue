<?php

global $_W,$_GPC;
$input = $this->__input['encrypted'];
$id = intval($input['id']);

if(pdo_update('imeepos_runner3_member',array('status'=>1),array('id'=>$input['id']))){
    $content = "恭喜您,您的资料审核通过!点击去任务大厅逛逛吧!";
    $title = '资料审核通知';
    
    $member = pdo_get('imeepos_runner3_member',array('id'=>$id));
    $openid = $member['openid'];
    $url = $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&do=index&m=imeepos_runner';
    M('common')->mc_notice_consume2($openid,$title,$content,$url);

    $this->msg = '资料审核成功';
    $this->code = 1;
}else{
    $this->msg = '资料审核失败';
    $this->code = 0;
}

$this->info = $input;
return $this;