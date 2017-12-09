<?php
global $_W;
include MODULE_ROOT.'/core/libs/ry/rongcloud.php';

$config = getRyConfig();
if(!empty($config)){
    $appKey = 'k51hidwq1qcyb';
    $appSecret = 'vEomKdzDe36IWC';
}else{
    $appKey = $config['appKey'];
    $appSecret = $config['appSecret'];
}

$jsonPath = "jsonsource/";
$RongCloud = new RongCloud($appKey,$appSecret);

$input = $this->__input['encrypted'];

// 用户信息
$openid = empty($input['openid']) ? $_W['openid'] : $input['openid'];
$openid = $openid ? $openid : 'fromUser';
$user = pdo_get('imeepos_runner3_member',array('openid'=>$openid));
$user['nickname'] = $user['nickname'] ? $user['nickname'] : '昵称';
$user['avatar'] = $user['avatar'] ? $user['avatar'] : 'https://mepeo.com.cn/meepo/images/avatar.png';

$input['act'] = $input['act'] ? $input['act'] : 'group.create';
$result = $RongCloud->user()->getToken($openid, $user['nickname'], $user['avatar']);

if($input['act'] == 'getToken'){
    $result = $RongCloud->user()->getToken($openid, $user['nickname'], $user['avatar']);
    $this->info = json_decode($result,true);
    return $this;
}

if($input['act'] == 'publishPrivate'){
    $from_openid = $input['from_openid'] ? $input['from_openid'] : 'fromUser';
    $to_openids = $input['to_openids'] ? $input['to_openids'] : array();

    $result = $RongCloud->message()->publishPrivate($from_openid, $to_openids, 'RC:VcMsg',"{\"content\":\"hello\",\"extra\":\"helloExtra\",\"duration\":20}", 'thisisapush', '{\"pushData\":\"hello\"}', '4', '0', '0', '0', '0');
    $this->info = json_decode($result,true);
    return $this;
}


if($input['act'] == 'publishTemplate'){
    $result = $RongCloud->message()->publishTemplate(file_get_contents($jsonPath.'TemplateMessage.json'));
    $this->info = json_decode($result,true);
    return $this;
}

if($input['act'] == 'PublishSystem'){
    $result = $RongCloud->message()->PublishSystem('userId1', ["userId2","userid3","userId4"], 'RC:TxtMsg',"{\"content\":\"hello\",\"extra\":\"helloExtra\"}", 'thisisapush', '{\"pushData\":\"hello\"}', '0', '0');
    $this->info = json_decode($result,true);
    return $this;
}

if($input['act'] == 'publishSystemTemplate'){
    $result = $RongCloud->message()->publishSystemTemplate(file_get_contents($jsonPath.'TemplateMessage.json'));
    $this->info = json_decode($result,true);
    return $this;
}

if($input['act'] == 'publishDiscussion'){
    $result = $RongCloud->message()->publishDiscussion('userId1', 'discussionId1', 'RC:TxtMsg',"{\"content\":\"hello\",\"extra\":\"helloExtra\"}", 'thisisapush', '{\"pushData\":\"hello\"}', '1', '1', '0');
    $this->info = json_decode($result,true);
    return $this;
}

if($input['act'] == 'broadcast'){
    $result = $RongCloud->message()->broadcast('userId1', 'RC:TxtMsg',"{\"content\":\"哈哈\",\"extra\":\"hello ex\"}", 'thisisapush', '{\"pushData\":\"hello\"}', 'iOS');
    $this->info = json_decode($result,true);
    return $this;
}

if($input['act'] == 'group.create'){
    $pid = $_GPC['pid'];
    $groupName = $input['groupName']? $input['groupName']: 'groupId'.$pid;
    $result = $RongCloud->group()->create(["fromUser"], 'groupId'.$pid, $groupName);
    $this->info = json_decode($result,true);
    $this->msg = $groupName;
    return $this;
}

function getRyConfig(){
    global $_W;
    $code = '__meepo.app.ry.'.$_W['uniacid'];		
    $setting = pdo_get('imeepos_runner3_setting',array('code'=>$code));
    $value = unserialize($setting['value']);
    return value;
}