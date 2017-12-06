<?php
if(!pdo_tableexists('ims_imeepos_runner3_detail')){
    pdo_query("
    CREATE TABLE `ims_imeepos_runner3_detail` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `uniacid` int(11) DEFAULT '0',
        `taskid` int(11) DEFAULT '0',
        `goodsweight` float(10,2) DEFAULT '0.00',
        `goodscost` float(10,2) DEFAULT '0.00',
        `goodsname` varchar(64) DEFAULT '',
        `sendprovince` varchar(32) DEFAULT '',
        `sendcity` varchar(32) DEFAULT '',
        `sendaddress` varchar(132) DEFAULT '',
        `receiveprovince` varchar(32) DEFAULT '',
        `receivecity` varchar(32) DEFAULT '',
        `receiveaddress` varchar(132) DEFAULT '',
        `pickupdate` int(11) DEFAULT '0',
        `sendlon` varchar(64) DEFAULT '',
        `sendlat` varchar(64) DEFAULT '',
        `receivelon` varchar(64) DEFAULT '',
        `receivelat` varchar(64) DEFAULT '',
        `distance` int(11) DEFAULT '0',
        `dataTimeValue` int(11) DEFAULT '0',
        `time` tinyint(2) DEFAULT '0',
        `base_fee` float(10,2) DEFAULT '0.00',
        `fee` float(10,2) DEFAULT '0.00',
        `total` float(10,2) DEFAULT '0.00',
        `small_money` float(10,2) DEFAULT '0.00',
        `senddetail` varchar(64) DEFAULT '',
        `receivedetail` varchar(320) DEFAULT '',
        `receivemobile` varchar(32) DEFAULT '',
        `receiverealname` varchar(32) DEFAULT '',
        `message` varchar(640) DEFAULT '',
        `images` varchar(1000) DEFAULT '',
        `float_distance` float(10,2) DEFAULT '0.00',
        `duration` varchar(120) DEFAULT '',
        `sendrealname` varchar(32) DEFAULT '',
        `sendmobile` varchar(64) DEFAULT '',
        `total_num` int(11) NOT NULL DEFAULT '1',
        `duration_value` int(11) NOT NULL DEFAULT '0',
        `small_fee` decimal(10,2) DEFAULT '0.00',
        `tiji` int(11) DEFAULT '0',
        `steps` text,
        PRIMARY KEY (`id`),
        KEY `IDX_TASKID` (`taskid`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
    ");
}    

if(!pdo_tableexists('ims_imeepos_runner3_member')){
    pdo_query("
    CREATE TABLE `ims_imeepos_runner3_member` (
        `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `uid` int(11) unsigned NOT NULL,
        `uniacid` int(11) unsigned NOT NULL,
        `status` tinyint(2) unsigned NOT NULL,
        `groupid` int(11) unsigned NOT NULL,
        `time` int(11) DEFAULT NULL,
        `openid` varchar(64) DEFAULT NULL,
        `online` tinyint(2) DEFAULT '0',
        `nickname` varchar(32) DEFAULT '',
        `avatar` varchar(320) DEFAULT NULL,
        `gender` tinyint(2) DEFAULT '0',
        `city` varchar(32) DEFAULT '',
        `provice` varchar(32) DEFAULT '',
        `realname` varchar(32) DEFAULT '',
        `mobile` varchar(32) DEFAULT '',
        `xinyu` int(11) DEFAULT '0',
        `isrunner` tinyint(2) DEFAULT '0',
        `card_image1` varchar(320) DEFAULT '',
        `card_image2` varchar(320) DEFAULT '',
        `cardnum` varchar(64) DEFAULT '',
        `lat` varchar(64) DEFAULT '',
        `lng` varchar(64) DEFAULT '',
        `forbid` int(4) DEFAULT '0',
        `oauth_code` varchar(64) DEFAULT '',
        `level_id` int(11) DEFAULT '0',
        `description` varchar(320) DEFAULT '',
        `hash` varchar(32) DEFAULT '',
        `card_image3` varchar(320) DEFAULT '',
        `isadmin` tinyint(2) DEFAULT '0',
        `ismanager` tinyint(2) DEFAULT '0',
        `forbid_time` int(10) NOT NULL DEFAULT '0',
        `shop_id` int(11) NOT NULL DEFAULT '0',
        PRIMARY KEY (`id`),
        KEY `INDEX_OPENID` (`openid`),
        KEY `INDEX_UNIACID` (`uniacid`),
        KEY `INDEX_ISRUNNER` (`isrunner`),
        KEY `INDEX_HASH` (`hash`),
        KEY `IDX_OPENID` (`openid`),
        KEY `IDX_UNIACID` (`uniacid`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    ");
}    

if(!pdo_tableexists('ims_imeepos_runner3_detail')){
    pdo_query("
    CREATE TABLE `ims_imeepos_runner3_paylog` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `uniacid` int(11) DEFAULT '0',
        `tid` varchar(64) DEFAULT '',
        `time` int(11) DEFAULT '0',
        `setting` text,
        `status` tinyint(2) DEFAULT '0',
        `openid` varchar(64) DEFAULT '',
        `fee` float(10,2) DEFAULT '0.00',
        `type` varchar(32) DEFAULT '',
        `taskid` int(10) DEFAULT '0',
        PRIMARY KEY (`id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
    ");
}    

if(!pdo_tableexists('ims_imeepos_runner3_detail')){
    pdo_query("
    CREATE TABLE `ims_imeepos_runner3_setting` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `uniacid` int(11) DEFAULT '0',
        `code` varchar(640) DEFAULT '',
        `value` text,
        PRIMARY KEY (`id`),
        KEY `IDX_CODE` (`code`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
    ");
}    
