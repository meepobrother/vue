<?php

if(!pdo_tableexists('imeepos_runner4_goods_group')){
    $sql = "
    CREATE TABLE ".tablename('imeepos_runner4_goods_group')." (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `title` varchar(32) DEFAULT NULL,
        `desc` varchar(120) DEFAULT NULL,
        `uniacid` int(11) DEFAULT '0',
        PRIMARY KEY (`id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8
    ";
    pdo_query($sql);
}

if(!pdo_fieldexists('imeepos_runner4_goods_group','tags')){
    $sql = "ALTER TABLE ".tablename('imeepos_runner4_goods_group')." ADD COLUMN `tags` text NOT NULL;";
    pdo_query($sql);
}

if(!pdo_fieldexists('imeepos_runner4_goods_group','displayorder')){
    $sql = "ALTER TABLE ".tablename('imeepos_runner4_goods_group')." ADD COLUMN `displayorder` int(11) NOT NULL DEFAULT '0'";
    pdo_query($sql);
}

if(!pdo_fieldexists('imeepos_runner4_goods_group','fid')){
    $sql = "ALTER TABLE ".tablename('imeepos_runner4_goods_group')." ADD COLUMN `fid` int(11) NOT NULL DEFAULT '0'";
    pdo_query($sql);
}

if(!pdo_tableexists('imeepos_runner4_goods_tags')){
    $sql = "CREATE TABLE ".tablename('imeepos_runner4_goods_tags')." (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `title` varchar(32) DEFAULT NULL,
        `uniacid` int(11) DEFAULT NULL,
        PRIMARY KEY (`id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
    pdo_query($sql);
}

if(!pdo_tableexists('imeepos_runner4_goods')){
    $sql = "CREATE TABLE ".tablename('imeepos_runner4_goods')." (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `uniacid` int(11) unsigned NOT NULL DEFAULT '0',
        `title` varchar(64) DEFAULT NULL,
        `desc` varchar(320) DEFAULT NULL,
        `thumbs` text,
        `content` text,
        `create_time` int(11) DEFAULT NULL,
        `count` int(11) DEFAULT NULL,
        `price` decimal(10,0) DEFAULT NULL,
        `setting` text,
        PRIMARY KEY (`id`)
      ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8";
      pdo_query($sql);
}

if(!pdo_fieldexists('imeepos_runner4_goods','shop_id')){
    $sql = "ALTER TABLE ".tablename('imeepos_runner4_goods')." ADD COLUMN `shop_id` int(11) unsigned DEFAULT '0'";
    pdo_query($sql);
}

if(!pdo_fieldexists('imeepos_runner4_goods','tag')){
    $sql = "ALTER TABLE ".tablename('imeepos_runner4_goods')." ADD COLUMN `tag` varchar(64) NOT NULL DEFAULT ''";
    pdo_query($sql);
}

if(!pdo_fieldexists('imeepos_runner4_goods','group_id')){
    $sql = "ALTER TABLE ".tablename('imeepos_runner4_goods')." ADD COLUMN `group_id` int(11) unsigned DEFAULT '0'";
    pdo_query($sql);
}

return $this;