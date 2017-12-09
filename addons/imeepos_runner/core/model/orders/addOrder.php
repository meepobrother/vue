<?php

global $_W;
$input = $this->__input['encrypted'];

if(empty($input['shop_id'])){
	$this->info = $input;
	$this->msg = '请选择店铺';
    return $this;
}

// carfiles
$car_id = 0;
if(!empty($input['carfiles'])){
	// 根据车牌号查找
	$carfiles = $input['carfiles'];
	$car_id = intval($carfiles['id']);
	if(!empty($car_id)){
		// 更新
	}else{
		$car = pdo_get('imeepos_repair_server_carfiles',array('car_num'=>$carfiles['car_num']));
		if(empty($car)){
			$carfiles['father'] = $input['__meepo_openid'];
			$carfiles['create_time'] = time();
			$carfiles['uniacid'] = $_W['uniacid'];
			pdo_insert('imeepos_repair_server_carfiles',$carfiles);
			$car_id = pdo_insertid();
		}else{
			$car_id = $car['id'];
			unset($carfiles['uniacid']);
			unset($carfiles['id']);
			unset($carfiles['create_time']);
			$carfiles['update_time'] = time();
			pdo_update('imeepos_repair_server_carfiles',$carfiles,array('id'=>$car_id));
		}
	}
}

$data = array();
$data['uniacid'] = $_W['uniacid'];
$data['title'] = $input['title'];
$data['desc'] = $input['desc'];
$data['money'] = $input['money'];
$data['tag'] = serialize($input['tag']);
$data['checks'] = serialize($input['checks']);
$data['services'] = serialize($input['services']);
$data['goods'] = serialize($input['goods']);
$data['emplyers'] = serialize($input['emplyers']);


$data['create_time'] = time();

$data['class_id'] = $input['class_id'];
$data['class_title'] = trim($input['class_title']);

$data['shop_id'] = intval($input['shop_id']);
$data['shop_title'] = trim($input['shop_title']);

$data['status'] = intval($input['status']);

$data['car_id'] = $car_id;

$id = intval($input['id']);
if(!empty($data['shop_id'])){
    if(!empty($id)){
        pdo_update('imeepos_runner4_order',$data,array('id'=>$input['id']));
        $data['id'] = $id;
    }else{
        pdo_insert('imeepos_runner4_order',$data);
        $data['id'] = pdo_insertid();
    }
}

$this->info = $data;
$this->msg = $input;
return $this;