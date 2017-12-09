<?php
//添加字段
if(!pdo_fieldexists('imeepos_runner3_tasks','payType')){
    pdo_query("ALTER TABLE ".tablename('imeepos_runner3_tasks')." ADD COLUMN `payType` varchar(32) DEFAULT ''");
}
//小费
if(!pdo_fieldexists('imeepos_runner3_tasks','small_fee')){
    pdo_query("ALTER TABLE ".tablename('imeepos_runner3_tasks')." ADD COLUMN `small_fee` decimal(10,2) DEFAULT '0.00'");
}

if(!pdo_fieldexists('imeepos_runner3_tasks','media_src')){
    pdo_query("ALTER TABLE ".tablename('imeepos_runner3_tasks')." ADD COLUMN `media_src` varchar(320) DEFAULT ''");
}

if(!pdo_fieldexists('imeepos_runner3_tasks','voice_time')){
    pdo_query("ALTER TABLE ".tablename('imeepos_runner3_tasks')." ADD COLUMN `voice_time` int(11) DEFAULT '0'");
}

//小费
if(!pdo_fieldexists('imeepos_runner3_detail','small_fee')){
    pdo_query("ALTER TABLE ".tablename('imeepos_runner3_detail')." ADD COLUMN `small_fee` decimal(10,2) DEFAULT '0.00'");
}

//体积
if(!pdo_fieldexists('imeepos_runner3_detail','tiji')){
    pdo_query("ALTER TABLE ".tablename('imeepos_runner3_detail')." ADD COLUMN `tiji` int(11) DEFAULT '0'");
}

if(!pdo_fieldexists('imeepos_runner3_detail','total_num')){
	pdo_query("ALTER TABLE ".tablename('imeepos_runner3_detail')." ADD COLUMN `total_num` int(11) DEFAULT '1'");
}

if(!pdo_fieldexists('imeepos_runner3_detail','steps')){
    pdo_query("ALTER TABLE ".tablename('imeepos_runner3_detail')." ADD COLUMN `steps` text DEFAULT ''");
}

if(!pdo_fieldexists('imeepos_runner3_detail','duration_value')){
    pdo_query("ALTER TABLE ".tablename('imeepos_runner3_detail')." ADD COLUMN `duration_value` int(11) DEFAULT '1'");
	pdo_query('alter table '.tablename('imeepos_runner3_detail').' modify column duration varchar(120);');
}