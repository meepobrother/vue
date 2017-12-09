<?php

// 登陆
global $_W,$_GPC;

load()->model('user');

$input = $this->__input['encrypted'];



$member = array();
$member['username'] = $input['username'];
$member['password'] = $input['password'];

$record = user_single($member);

if(!empty($record)){
    $_W['uid'] = $record['uid'];
    $_W['isfounder'] = user_is_founder($record['uid']);
    $_W['user'] = $record;

    $cookie = array();
    $cookie['uid'] = $record['uid'];
    $cookie['lastvisit'] = $record['lastvisit'];
    $cookie['lastip'] = $record['lastip'];
    $cookie['hash'] = md5($record['password'] . $record['salt']);
    $session = authcode(json_encode($cookie), 'encode');
    isetcookie('__session', $session, !empty($_GPC['rember']) ? 7 * 86400 : 0, true);
    $status = array();
    $status['uid'] = $record['uid'];
    $status['lastvisit'] = TIMESTAMP;
    $status['lastip'] = CLIENT_IP;
    user_update($status);

    if ($record['uid'] != $_GPC['__uid']) {
        isetcookie('__uniacid', '', -7 * 86400);
        isetcookie('__uid', '', -7 * 86400);
    }
    // pdo_delete('users_failed_login', array('id' => $failed['id']));
}

$this->info = $record;
$this->msg = $member;

return $this;