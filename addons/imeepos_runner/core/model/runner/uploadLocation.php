<?php
global $_W;
$input = $this->__input['encrypted'];
$this->info = $input;

$data = array();
$data['lat'] = $input['lat'];
$data['lng'] = $input['lng'];
$data['desc'] = $input['desc'];

if(empty($input['lat']) || empty($input['lng'])){
	$this->code = 0;
	return $this;
}
$hash = '';
if(!empty($data['lat']) && !empty($data['lng'])){
    $file = ROUTERPATH."/libs/Geohash.php";
	if(file_exists($file)){
	    include_once $file;
	    $domain = new Domain_Geohash();
    	$domain -> setLatitude($data['lat']);
	    $domain -> setLongitude($data['lng']);
	    $domain -> setPrecision(0.1);
	    $hash = $domain -> __toString();
	    //检查hash字段
	    if(!pdo_fieldexists('imeepos_runner3_member','hash')){
	        pdo_query("ALTER TABLE ".tablename('imeepos_runner3_member')." ADD COLUMN `hash` varchar(32) DEFAULT ''");
	    }
	}
}

$member = pdo_get('imeepos_runner3_member',array('openid'=>$_W['openid'],'uniacid'=>$_W['uniacid']));
load()->model('mc');
$fans = mc_fansinfo($_W['openid']);
if(empty($member)){
	$d = array();
	$d['avatar'] = $fans['avatar'];
	$d['nickname'] = $fans['nickname'];
	$d['lat'] = $input['lat'];
	$d['lng'] = $input['lng'];
	$d['uid'] = $fans['uid'];
	pdo_insert('imeepos_runner3_member',$d);
}else{
	$d = array();
	$d['lat'] = $input['lat'];
	$d['lng'] = $input['lng'];
	$d['uid'] = $fans['uid'];
	if(!empty($hash)){
		$d['hash'] = $hash;
	}
	pdo_update('imeepos_runner3_member',$d,array('id'=>$member['id']));
}

//检查接单状态
$sql = "SELECT * FROM ".tablename('imeepos_runner3_recive')." WHERE status = 0 AND openid=:openid";
$params = array(':openid'=>$_W['openid']);
$recives = pdo_fetchall($sql,$params);
if(!empty($recives)){
	foreach ($recives as $recive) {
		// 去除重复数据
		$log = array();
		$log['uniacid'] = $_W['uniacid'];
		$log['create_time'] = time();
		$log['lat'] = $input['lat'];
		$log['lng'] = $input['lng'];
		$log['openid']= $_W['openid'];
		$log['taskid'] = $recive['taskid'];
		$log['content'] = $input['desc'];
		pdo_insert('imeepos_runner3_tasks_log',$log);
	}
}

$this->info = $input;

return $this;