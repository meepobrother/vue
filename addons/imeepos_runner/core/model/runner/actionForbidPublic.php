<?php
global $_W,$_GPC;
$input = $this->__input['encrypted'];
$id = intval($input['id']);

pdo_update('imeepos_runner3_member',array('forbid'=>1,'forbid_time'=>time() + 3*24*60*60),array('id'=>$input['id']));
$content = "您的账号涉嫌违规,已被管理员禁封!";
$title = '违规禁封通知';

$member = pdo_get('imeepos_runner3_member',array('id'=>$id));
$openid = $member['openid'];
$url = $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&do=index&m=imeepos_runner';
M('common')->mc_notice_consume2($openid,$title,$content,$url);

$this->info = $input;
return $this;