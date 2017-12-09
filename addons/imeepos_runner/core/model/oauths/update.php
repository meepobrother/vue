<?php


if(!pdo_tableexists('imeepos_oauth2_manage_group')){
    $sql = "CREATE TABLE ".tablename('imeepos_oauth2_manage_group')." (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `uniacid` int(11) NOT NULL DEFAULT '0',
        `title` varchar(64) NOT NULL DEFAULT '',
        `desc` varchar(320) NOT NULL DEFAULT '',
        `status` tinyint(2) NOT NULL DEFAULT '0',
        PRIMARY KEY (`id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
    pdo_query($sql);
}
if(!pdo_fieldexists('imeepos_oauth2_manage_group','tags')){
    $sql = "ALTER TABLE ".tablename('imeepos_oauth2_manage_group')." ADD COLUMN `tags` text NOT NULL AFTER `status`;";
    pdo_query($sql);
}

if(!pdo_fieldexists('imeepos_oauth2_manage_group','displayorder')){
    $sql = "ALTER TABLE ".tablename('imeepos_oauth2_manage_group')." ADD COLUMN `displayorder` int(11) NOT NULL DEFAULT '0'";
    pdo_query($sql);
}

if(!pdo_fieldexists('imeepos_oauth2_manage_group','fid')){
    $sql = "ALTER TABLE ".tablename('imeepos_oauth2_manage_group')." ADD COLUMN `fid` int(11) NOT NULL DEFAULT '0'";
    pdo_query($sql);
}

if(!pdo_fieldexists('imeepos_oauth2_manage_group','fids')){
    $sql = "ALTER TABLE ".tablename('imeepos_oauth2_manage_group')." ADD COLUMN `fids` varchar(320) NOT NULL DEFAULT ''";
    pdo_query($sql);
}
return $this;