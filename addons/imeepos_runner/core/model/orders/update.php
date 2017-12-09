<?php

if(!pdo_tableexists('imeepos_runner4_state_shop')){
    $sql = "CREATE TABLE ".tablename('imeepos_runner4_state_shop')." (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `uniacid` int(11) NOT NULL DEFAULT '0',
        `shop_id` int(11) NOT NULL DEFAULT '0',
        `fee` decimal(10,2) NOT NULL DEFAULT '0.00',
        `create_time` int(11) NOT NULL DEFAULT '0',
        PRIMARY KEY (`id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
    pdo_query($sql);
}


if(!pdo_tableexists('imeepos_runner4_state_group')){
    $sql = "CREATE TABLE ".tablename('imeepos_runner4_state_group')." (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `uniacid` int(11) NOT NULL DEFAULT '0',
        `group_id` int(11) NOT NULL DEFAULT '0',
        `fee` decimal(10,2) NOT NULL DEFAULT '0.00',
        `create_time` int(11) NOT NULL DEFAULT '0',
        PRIMARY KEY (`id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
    pdo_query($sql);
}

if(!pdo_tableexists('imeepos_runner4_state_emplyer')){
    $sql = "CREATE TABLE ".tablename('imeepos_runner4_state_emplyer')." (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `uniacid` int(11) NOT NULL DEFAULT '0',
        `openid` varchar(64) NOT NULL DEFAULT '',
        `fee` decimal(10,2) NOT NULL DEFAULT '0.00',
        `create_time` int(11) NOT NULL DEFAULT '0',
        PRIMARY KEY (`id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
    pdo_query($sql);
}


if(!pdo_tableexists('imeepos_runner4_state_good')){
    $sql = "CREATE TABLE ".tablename('imeepos_runner4_state_good')." (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `uniacid` int(11) NOT NULL DEFAULT '0',
        `good_id` int(11) NOT NULL DEFAULT '0',
        `num` int(11) NOT NULL DEFAULT '0',
        `create_time` int(11) NOT NULL DEFAULT '0',
        PRIMARY KEY (`id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
    pdo_query($sql);
}

if(!pdo_tableexists('imeepos_runner4_state_service')){
    $sql = "CREATE TABLE ".tablename('imeepos_runner4_state_service')." (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `uniacid` int(11) NOT NULL DEFAULT '0',
        `service_id` int(11) NOT NULL DEFAULT '0',
        `num` int(11) NOT NULL DEFAULT '0',
        `create_time` int(11) NOT NULL DEFAULT '0',
        PRIMARY KEY (`id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
    pdo_query($sql);
}

if(!pdo_tableexists('imeepos_runner4_order_tag')){
    $sql = "CREATE TABLE ".tablename('imeepos_runner4_order_tag')." (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `uniacid` int(11) NOT NULL DEFAULT '0',
        `title` varchar(64) NOT NULL DEFAULT '',
        PRIMARY KEY (`id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
    pdo_query($sql);
}    

if(!pdo_tableexists('imeepos_runner4_order_class')){
    $sql = "CREATE TABLE ".tablename('imeepos_runner4_order_class')." (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `uniacid` int(11) NOT NULL DEFAULT '0',
        `title` varchar(64) NOT NULL DEFAULT '',
        `desc` varchar(320) NOT NULL DEFAULT '',
        `status` tinyint(2) NOT NULL DEFAULT '0',
        PRIMARY KEY (`id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
    pdo_query($sql);
}    
if(!pdo_fieldexists('imeepos_runner4_order_class','tags')){
    $sql = "ALTER TABLE ".tablename('imeepos_runner4_order_class')." ADD COLUMN `tags` text NOT NULL AFTER `status`;";
    pdo_query($sql);
}
if(!pdo_fieldexists('imeepos_runner4_order_class','displayorder')){
    $sql = "ALTER TABLE ".tablename('imeepos_runner4_order_class')." ADD COLUMN `displayorder` int(11) NOT NULL DEFAULT '0'";
    pdo_query($sql);
}

if(!pdo_fieldexists('imeepos_runner4_order_class','fid')){
    $sql = "ALTER TABLE ".tablename('imeepos_runner4_order_class')." ADD COLUMN `fid` int(11) NOT NULL DEFAULT '0'";
    pdo_query($sql);
}

if(!pdo_tableexists('imeepos_runner4_order')){
    $sql = "CREATE TABLE ".tablename('imeepos_runner4_order')." (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `uniacid` int(11) DEFAULT '0',
        `title` varchar(64) DEFAULT '',
        `desc` varchar(320) DEFAULT '',
        `money` decimal(10,2) DEFAULT '0.00',
        `tag` varchar(320) DEFAULT '',
        `class_id` int(11) DEFAULT '0',
        `create_time` int(11) DEFAULT '0',
        PRIMARY KEY (`id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
      pdo_query($sql);
}

if(!pdo_fieldexists('imeepos_runner4_order','class_title')){
    $sql = "ALTER TABLE ".tablename('imeepos_runner4_order')." ADD COLUMN `class_title` varchar(64) NOT NULL DEFAULT ''";
    pdo_query($sql);
}

if(!pdo_fieldexists('imeepos_runner4_order','shop_title')){
    $sql = "ALTER TABLE ".tablename('imeepos_runner4_order')." ADD COLUMN `shop_title` varchar(64) NOT NULL DEFAULT ''";
    pdo_query($sql);
}

if(!pdo_fieldexists('imeepos_runner4_order','shop_id')){
    $sql = "ALTER TABLE ".tablename('imeepos_runner4_order')." ADD COLUMN `shop_id` int(11) NOT NULL DEFAULT '0'";
    pdo_query($sql);
}
 
if(!pdo_fieldexists('imeepos_runner4_order','status')){
    $sql = "ALTER TABLE ".tablename('imeepos_runner4_order')." ADD COLUMN `status` tinyint(4) NOT NULL DEFAULT '0'";
    pdo_query($sql);
}

if(!pdo_fieldexists('imeepos_runner4_order','is_finish')){
    $sql = "ALTER TABLE ".tablename('imeepos_runner4_order')." ADD COLUMN `is_finish` tinyint(4) NOT NULL DEFAULT '0'";
    pdo_query($sql);
}

if(!pdo_fieldexists('imeepos_runner4_order','create_time')){
    $sql = "ALTER TABLE ".tablename('imeepos_runner4_order')." ADD COLUMN `create_time` int(11) NOT NULL DEFAULT '0'";
    pdo_query($sql);
}

if(!pdo_fieldexists('imeepos_runner4_order','car_id')){
    $sql = "ALTER TABLE ".tablename('imeepos_runner4_order')." ADD COLUMN `car_id` int(11) NOT NULL DEFAULT '0'";
    pdo_query($sql);
}

if(!pdo_fieldexists('imeepos_runner4_order','checks')){
    $sql = "ALTER TABLE ".tablename('imeepos_runner4_order')." ADD COLUMN `checks` text NOT NULL";
    pdo_query($sql);
}

if(!pdo_fieldexists('imeepos_runner4_order','services')){
    $sql = "ALTER TABLE ".tablename('imeepos_runner4_order')." ADD COLUMN `services` text NOT NULL";
    pdo_query($sql);
}

if(!pdo_fieldexists('imeepos_runner4_order','fee')){
    $sql = "ALTER TABLE ".tablename('imeepos_runner4_order')." ADD COLUMN `fee` decimal(10,2) NOT NULL DEFAULT '0.00'";
    pdo_query($sql);
}

if(!pdo_fieldexists('imeepos_runner4_order','goods')){
    $sql = "ALTER TABLE ".tablename('imeepos_runner4_order')." ADD COLUMN `goods` text NOT NULL";
    pdo_query($sql);
}


if(!pdo_fieldexists('imeepos_runner4_order','emplyers')){
    $sql = "ALTER TABLE ".tablename('imeepos_runner4_order')." ADD COLUMN `emplyers` text NOT NULL";
    pdo_query($sql);
}


return $this;