<?php
global $_W,$_GPC;

$input = $this->__input;
$_GPC = array_merge($_GPC,$input);
load()->func('file');

$sysms_set = M('setting')->getValue('sms_set');
//如果后台启用验证码，检查验证码是否正确

if(!empty($sysms_set['post_open'])){
    $code = $input['code'];
    $codeid = $input['codeid'];

    $code_row = M('code')->getInfo($codeid);
    if($code != $code_row['code']){
        $return = array();
        $return['status'] = 0;
        $return['code'] = $sms['code'];
        $return['message'] = '您输入的验证码有误！';
        die(json_encode($return));
    }
}

$data = array();
$rec = $input['receiveaddress'];
//送货地址
$data['receiveaddress'] = $rec['title'];
$data['receivelon'] = $rec['lng'];
$data['receivelat'] = $rec['lat'];

if(empty($rec['lng']) && empty($rec['lat']) ){
	$this->code= 0;
	$this->msg = '请选择收货地址';
	return $this;
}
$data['receivedetail'] = $rec['detail'];//详细地址
$data['receivemobile'] = $rec['mobile'];//新增
$data['receiverealname'] = $rec['realname'];//新增
$data['message'] = trim($input['message']);

$data['dianfu'] = intval($input['dianfu']);

$data['goodscost'] = floatval($input['goodscost']);
$data['goodstitle'] = !empty($input['goodstitle'])?trim($input['goodstitle']):trim($input['goodsname']);

$data['uniacid'] = $_W['uniacid'];
$data['openid'] = $_W['openid'];

$data['freight'] = floatval($input['small_money']);

$data['other'] = trim($input['other']);
$data['distance'] = intval($input['distance']);
$data['expectedtime'] = intval($input['expectedtime']);

if(!empty($data['expectedtime'])){
    $data['limit_time'] = time()+60*$data['expectedtime'];
}else{
    $settingitem = M('setting')->getValue('plugin_setting');
    $hour = intval($settingitem['limit_time']);
    $data['limit_time'] = time() + intval(60*60*$hour);
}
$distance = floatval($data['distance']/1000);
$buy = $data;
$tasks = array();

if(!empty($input['type'])){
    $tasks['type'] = intval($input['type']);
}

if(true){
    $text = "";
    if(empty($tasks['type'])){
        if(!empty($data['expectedtime'])){
            $text .= "帮我买: ";
            if(!empty($data['goodstitle'])){
                $text .= "\n".$data['goodstitle']."\n";
            }
            $text .= "，\n送到:\n".$data['receiveaddress']."\n";
            if(!empty($data['distance'])){
                $text .= "总路程:\n".($data['distance']/1000)."公里\n";
            }
            $text .= "送达时间:\n".date('h点i分',$data['limit_time']);
            if(!empty($data['other'])){
                $text .= "备注:\n".$data['other']."\n";
            }
            if(!empty($data['freight'])){
                $text .= "赏金:\n".$data['freight']."元。";
            }
        }else{
            $text .= "帮我买: ";
            if(!empty($data['goodstitle'])){
                $text .= "\n".$data['goodstitle']."\n";
            }
            $text .= "，\n送到:\n".$data['receiveaddress']."\n";
            if(!empty($data['distance'])){
                $text .= "总路程:\n".($data['distance']/1000)."公里\n";
            }
            $text .= "送达时间: \n不限  \n";
            if(!empty($data['other'])){
                $text .= "备注:\n".$data['other']."\n";
            }
            if(!empty($data['freight'])){
                $text .= "赏金:\n".$data['freight']."元。";
            }
        }
    }else{
        if(!empty($data['expectedtime'])){
            $text .= "帮我: ";
            if(!empty($data['message'])){
                $text .= "\n".$data['message']."\n";
            }
            $text .= "，\n地点:\n".$data['receiveaddress']."\n";
            $text .= "截止时间:\n".date('h点i分',$data['limit_time']);
            if(!empty($data['freight'])){
                $text .= "赏金:\n".$data['freight']."元。";
            }
        }else{
            $text .= "帮我: ";
            if(!empty($data['message'])){
                $text .= "\n".$data['message']."\n";
            }
            $text .= "，\n地点:\n".$data['receiveaddress']."\n";
            $text .= "截止时间: \n不限  \n";
            if(!empty($data['freight'])){
                $text .= "赏金:\n".$data['freight']."元。";
            }
        }
    }

    $acc = WeAccount::create();
    if(!empty($text)){
        $url = "http://tts.baidu.com/text2audio?lan=zh&ie=UTF-8&spd=5&text=".urlencode($text);
        $img = array();
        $data = file_get_contents($url);
        $type = 'mp3';
        $filename = "audios/imeepos_runner/".time()."_".random(6).".".$type;
        if(file_write($filename,$data)){
            $result = $acc->uploadMedia($filename,'voice');
            $img['media_id'] = $result['media_id'];
        }
        if(empty($tasks['type'])){
            $tasks['type'] = 3;
        }
    }
    if(!empty($input['voiceid'])){
        $img = array();
        $img['media_id'] = trim($input['voiceid']);
        if(empty($tasks['type'])){
            $tasks['type'] = 2;
        }
    }else{
        if(empty($tasks['type'])){
            $tasks['type'] = 3;
        }
    }
}
//插入任务表
$tasks['uniacid'] = $_W['uniacid'];
$tasks['openid'] = $_W['openid'];
$tasks['create_time'] = time();
$tasks['desc'] = $text;
$tasks['media_id'] = $img['media_id'];
$tasks['status'] = 0;
$tasks['total'] = floatval($buy['freight']);

$tasks['small_money'] = abs(floatval($buy['small_money']));
$tasks['address'] = $buy['receiveaddress']."【".$buy['receivedetail']."】";
$tasks['limit_time'] = $buy['limit_time'];


$tasks['message'] = $buy['message'];

$tasks['dianfu'] = $buy['dianfu'];

$input = array();
$input['lat'] = $rec['lat'];
$input['lng'] = $rec['lng'];

$tasks['lat'] = $rec['lat'] * 1000000;
$tasks['lng'] = $rec['lng'] * 1000000;
$res = $this->exec('hash.getHash',$input)->getData();

$tasks['hash'] = $res['info'];

pdo_insert('imeepos_runner3_tasks',$tasks);
$buy['taskid'] = pdo_insertid();

$code = random(4,true);
$codetask = array();
$codetask['code'] = $code;
$qrcode = 'imeepos_runner'.md5($code.$tasks['create_time']);
$codetask['qrcode'] = $qrcode;

pdo_update('imeepos_runner3_tasks',$codetask,array('id'=>$buy['taskid']));
pdo_insert('imeepos_runner3_buy',$buy);

//插入订单记录
$paylog = array();
$paylog['fee'] = floatval($buy['freight']);
$paylog['tid'] = "U".time().random(6,true);
$paylog['uniacid'] = $_W['uniacid'];
$paylog['setting'] = iserializer(array('taskid'=>$buy['taskid']));
$paylog['status'] = 0;
$paylog['openid'] = $_W['openid'];
$paylog['time'] = time();
$paylog['type'] = 'post_buy';

pdo_insert('imeepos_runner3_paylog',$paylog);
$tid = pdo_insertid();

//imeepos_runner3_detail
$result = array();
$result['result'] = 0;
$result['paylog'] = $paylog;
$result['media_id'] = $img['media_id'];
if(empty($distance)){
    $result['message'] = '总费用：'.$paylog['fee']."元";
}else{
    $result['message'] = '总路程:'.$distance.'总费用：'.$paylog['fee']."元";
}

$member = M('member')->getInfo($_W['openid']);
$content = $member['nickname']."成功发布此任务！";
$data = array();
$data['uniacid'] = $_W['uniacid'];
$data['openid'] = $_W['openid'];
$data['create_time'] = time();
$data['taskid'] = $buy['taskid'];
$data['content'] = $content;
$data['lat'] = $buy['receivelat'];
$data['lng'] = $buy['receivelon'];

M('tasks_log')->update($data);

//新订单后台提醒
$data = array();
$data['uniacid'] = $_W['uniacid'];
$data['create_time'] = time();
$data['status'] = 0;
$data['title'] = "【".$member['nickname']."】成功提交任务";
$data['link'] = '';
$data['task_id'] = $buy['taskid'];
M('message')->update($data);

$result['result'] = 0;
$result['status'] = 0;
$result['tid'] = $tid;
$result['result'] = $buy;
$result['paylog'] = $paylog;
$result['tasks'] = $tasks;

$this->info =$result;
return $this;