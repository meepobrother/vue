<?php

global $_W,$_GPC;
$input = $this->__input['encrypted'];
$id = intval($input['id']);
if(pdo_update('imeepos_runner3_member',array('forbid'=>0,'forbid_time'=>time()),array('id'=>$input['id']))){
    $content = "恭喜您,您的账号已解封！";
    $title = '账号解封通知';
    
    $member = pdo_get('imeepos_runner3_member',array('id'=>$id));
    $openid = $member['openid'];
    $url = $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&do=index&m=imeepos_runner';
    M('common')->mc_notice_consume2($openid,$title,$content,$url);
    $this->code = 1;
    $this->msg = '账号解封通知';
}else{
    $this->code = 0;
    $this->msgg = '操作失败';
}
$this->info = $input;
return $this;
