<?php

if(!pdo_tableexists('imeepos_repair_server_carfiles')){
    $sql = "CREATE TABLE ".tablename('imeepos_repair_server_carfiles')." (
        `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `uniacid` int(11) unsigned NOT NULL DEFAULT '0',
        `create_time` int(11) unsigned NOT NULL DEFAULT '0',
        `realname` varchar(32) NOT NULL DEFAULT '',
        `mobile` varchar(11) NOT NULL DEFAULT '',
        `car_num` varchar(64) NOT NULL DEFAULT '',
        `nickname` varchar(64) NOT NULL DEFAULT '',
        `image` varchar(320) NOT NULL DEFAULT '',
        `openid` varchar(64) NOT NULL DEFAULT '',
        `father` varchar(64) NOT NULL DEFAULT '',
        `update_time` int(11) unsigned NOT NULL DEFAULT '0',
        `licheng` varchar(11) NOT NULL DEFAULT '',
        `pinpai` varchar(64) NOT NULL DEFAULT '',
        `jar_num` varchar(64) NOT NULL DEFAULT '',
        PRIMARY KEY (`id`),
        UNIQUE KEY `IDX_CAR_NUM` (`car_num`)
      ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC";
    pdo_query($sql);
}

if(!pdo_tableexists('imeepos_repair_server_back')){
    $sql = "CREATE TABLE ".tablename('imeepos_repair_server_back')." (
        `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `uniacid` int(11) unsigned NOT NULL DEFAULT '0',
        `mid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '店铺',
        `title` varchar(320) NOT NULL DEFAULT '',
        `desc` varchar(320) NOT NULL DEFAULT '',
        `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
        `one_title` varchar(32) NOT NULL DEFAULT '',
        `one_price` decimal(10,2) NOT NULL DEFAULT '0.00',
        `one_sale` decimal(10,2) NOT NULL DEFAULT '0.00',
        `mobile` varchar(32) NOT NULL DEFAULT '',
        `carfile_id` int(11) unsigned NOT NULL DEFAULT '0',
        PRIMARY KEY (`id`)
      ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC";
      pdo_query($sql);
}


if(!pdo_tableexists('imeepos_repair_server_employer')){
    $sql = "CREATE TABLE ".tablename('imeepos_repair_server_employer')." (
        `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `uniacid` int(11) unsigned NOT NULL DEFAULT '0',
        `displayorder` int(11) unsigned NOT NULL DEFAULT '0',
        `title` varchar(32) NOT NULL DEFAULT '',
        `code` varchar(32) NOT NULL DEFAULT '',
        `openid` varchar(64) NOT NULL DEFAULT '',
        `shop_id` int(11) unsigned NOT NULL DEFAULT '0',
        `shop_openid` varchar(64) NOT NULL,
        PRIMARY KEY (`id`),
        KEY `IDX_OPENID` (`openid`)
      ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8";
      pdo_query($sql);
}

if(!pdo_tableexists('imeepos_repair_server_menus')){
    $sql = "CREATE TABLE ".tablename('imeepos_repair_server_menus')." (
        `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `fid` int(11) unsigned DEFAULT '0',
        `title` varchar(32) DEFAULT NULL,
        `link` varchar(320) DEFAULT NULL,
        `active` tinyint(2) DEFAULT '0' COMMENT '激活状态',
        `modulename` varchar(32) DEFAULT NULL,
        `displayorder` int(11) unsigned DEFAULT '0',
        PRIMARY KEY (`id`)
      ) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8";
      pdo_query($sql);
}


if(!pdo_tableexists('imeepos_repair_server_order')){
    $sql = "CREATE TABLE ".tablename('imeepos_repair_server_order')." (
        `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `uniacid` int(11) unsigned NOT NULL DEFAULT '0',
        `create_time` int(11) unsigned NOT NULL DEFAULT '0',
        `title` varchar(32) NOT NULL DEFAULT '',
        `tid` varchar(32) NOT NULL DEFAULT '',
        `openid` varchar(64) NOT NULL DEFAULT '',
        `to_openid` varchar(64) NOT NULL DEFAULT '',
        `carfiles_id` int(11) unsigned NOT NULL DEFAULT '0',
        `fee` decimal(10,2) NOT NULL DEFAULT '0.00',
        `status` tinyint(2) NOT NULL DEFAULT '0',
        `paidan_time` int(11) NOT NULL DEFAULT '0',
        `services` text NOT NULL COMMENT '服务项目',
        `back` text NOT NULL COMMENT '配件',
        `employers` text NOT NULL,
        `shop_id` int(11) unsigned NOT NULL DEFAULT '0',
        `other_message` varchar(320) NOT NULL DEFAULT '',
        `other_fee` decimal(10,2) NOT NULL DEFAULT '0.00',
        `shop_openid` varchar(64) NOT NULL,
        `cars` varchar(1000) NOT NULL COMMENT '车辆信息',
        `check` text NOT NULL COMMENT '体检信息',
        `type` varchar(32) NOT NULL,
        PRIMARY KEY (`id`)
      ) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8";
      pdo_query($sql);
}

if(!pdo_tableexists('imeepos_repair_server_parts_shop')){
    $sql = "CREATE TABLE ".tablename('imeepos_repair_server_parts_shop')." (
        `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `lat` varchar(32) NOT NULL DEFAULT '',
        `lng` varchar(32) NOT NULL DEFAULT '',
        `hash` varchar(32) NOT NULL DEFAULT '',
        `create_time` int(11) unsigned NOT NULL DEFAULT '0',
        `address` varchar(64) NOT NULL DEFAULT '',
        `mobile` varchar(32) NOT NULL DEFAULT '',
        `realname` varchar(32) NOT NULL DEFAULT '',
        `title` varchar(64) NOT NULL DEFAULT '',
        `setting` text NOT NULL,
        `uniacid` int(11) unsigned NOT NULL DEFAULT '0',
        `openid` varchar(64) DEFAULT NULL,
        PRIMARY KEY (`id`)
      ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8";
      pdo_query($sql);
}

if(!pdo_tableexists('imeepos_repair_server_services')){
    $sql = "CREATE TABLE ".tablename('imeepos_repair_server_services')." (
        `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `uniacid` int(11) unsigned NOT NULL DEFAULT '0',
        `create_time` int(11) unsigned NOT NULL DEFAULT '0',
        `title` varchar(32) NOT NULL DEFAULT '',
        `desc` varchar(320) NOT NULL DEFAULT '',
        `price` decimal(10,2) NOT NULL DEFAULT '0.00',
        `shop_id` int(11) unsigned NOT NULL DEFAULT '0',
        `openid` varchar(64) NOT NULL DEFAULT '0',
        PRIMARY KEY (`id`),
        KEY `IDX_OPENID` (`openid`)
      ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8";
      pdo_query($sql);
}

if(!pdo_tableexists('imeepos_repair_server_shops')){
    $sql = "CREATE TABLE ".tablename('imeepos_repair_server_shops')." (
        `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `openid` varchar(64) NOT NULL,
        `uniacid` int(11) unsigned NOT NULL DEFAULT '0',
        `domain` varchar(320) NOT NULL,
        `create_time` int(11) unsigned NOT NULL DEFAULT '0',
        `end_time` int(11) unsigned NOT NULL DEFAULT '0',
        `title` varchar(320) NOT NULL,
        `status` tinyint(2) unsigned NOT NULL DEFAULT '0',
        `father` varchar(64) NOT NULL,
        PRIMARY KEY (`id`)
      ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8";
      pdo_query($sql);
}

return $this;