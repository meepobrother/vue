<?php

if(!pdo_tableexists('imeepos_runner4_shops')){
    $sql = "CREATE TABLE ".tablename('imeepos_runner4_shops')." (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `title` varchar(64) DEFAULT NULL,
        `uniacid` int(11) NOT NULL DEFAULT '0',
        `mobile` varchar(32) DEFAULT NULL,
        `lat` varchar(32) DEFAULT NULL,
        `lng` varchar(32) DEFAULT NULL,
        `address` varchar(320) DEFAULT NULL,
        `detail` varchar(320) DEFAULT NULL,
        `desc` varchar(320) DEFAULT NULL,
        `content` text,
        PRIMARY KEY (`id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
    pdo_query($sql);
}

if(!pdo_fieldexists('imeepos_runner4_shops','shopers')){
    $sql = "ALTER TABLE ".tablename('imeepos_runner4_shops')." ADD COLUMN `shopers` text NOT NULL;";
    pdo_query($sql);
}

if(!pdo_fieldexists('imeepos_runner4_shops','employers')){
    $sql = "ALTER TABLE ".tablename('imeepos_runner4_shops')." ADD COLUMN `employers` text NOT NULL;";
    pdo_query($sql);
}

if(!pdo_fieldexists('imeepos_runner4_shops','kefus')){
    $sql = "ALTER TABLE ".tablename('imeepos_runner4_shops')." ADD COLUMN `kefus` text NOT NULL;";
    pdo_query($sql);
}

if(!pdo_tableexists('imeepos_runner4_shops_tag')){
    $sql = "CREATE TABLE ".tablename('imeepos_runner4_shops_tag')." (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `uniacid` int(11) NOT NULL DEFAULT '0',
        `title` varchar(64) NOT NULL DEFAULT '',
        PRIMARY KEY (`id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
    pdo_query($sql);
}    

if(!pdo_tableexists('imeepos_runner4_shops_group')){
    $sql = "CREATE TABLE ".tablename('imeepos_runner4_shops_group')." (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `uniacid` int(11) NOT NULL DEFAULT '0',
        `title` varchar(64) NOT NULL DEFAULT '',
        `desc` varchar(320) NOT NULL DEFAULT '',
        `status` tinyint(2) NOT NULL DEFAULT '0',
        PRIMARY KEY (`id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
    pdo_query($sql);
}
if(!pdo_fieldexists('imeepos_runner4_shops_group','tags')){
    $sql = "ALTER TABLE ".tablename('imeepos_runner4_shops_group')." ADD COLUMN `tags` text NOT NULL AFTER `status`;";
    pdo_query($sql);
}

if(!pdo_fieldexists('imeepos_runner4_shops_group','displayorder')){
    $sql = "ALTER TABLE ".tablename('imeepos_runner4_shops_group')." ADD COLUMN `displayorder` int(11) NOT NULL DEFAULT '0'";
    pdo_query($sql);
}

if(!pdo_fieldexists('imeepos_runner4_shops_group','fid')){
    $sql = "ALTER TABLE ".tablename('imeepos_runner4_shops_group')." ADD COLUMN `fid` int(11) NOT NULL DEFAULT '0'";
    pdo_query($sql);
}

return $this;