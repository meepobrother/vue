<?php

if(!pdo_tableexists('imeepos_runner4_services_group')){
    $sql = "
    CREATE TABLE ".tablename('imeepos_runner4_services_group')." (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `title` varchar(32) DEFAULT NULL,
        `desc` varchar(120) DEFAULT NULL,
        `uniacid` int(11) DEFAULT '0',
        PRIMARY KEY (`id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8
    ";
    pdo_query($sql);
}

if(!pdo_fieldexists('imeepos_runner4_services_group','tags')){
    $sql = "ALTER TABLE ".tablename('imeepos_runner4_services_group')." ADD COLUMN `tags` text NOT NULL;";
    pdo_query($sql);
}

if(!pdo_fieldexists('imeepos_runner4_services_group','displayorder')){
    $sql = "ALTER TABLE ".tablename('imeepos_runner4_services_group')." ADD COLUMN `displayorder` int(11) NOT NULL DEFAULT '0'";
    pdo_query($sql);
}

if(!pdo_fieldexists('imeepos_runner4_services_group','fid')){
    $sql = "ALTER TABLE ".tablename('imeepos_runner4_services_group')." ADD COLUMN `fid` int(11) NOT NULL DEFAULT '0'";
    pdo_query($sql);
}

if(!pdo_fieldexists('imeepos_runner4_services_group','fids')){
    $sql = "ALTER TABLE ".tablename('imeepos_runner4_services_group')." ADD COLUMN `fids` text NOT NULL";
    pdo_query($sql);
}

if(!pdo_fieldexists('imeepos_runner4_services_group','desc')){
    $sql = "ALTER TABLE ".tablename('imeepos_runner4_services_group')." ADD COLUMN `desc` varchar(320) NOT NULL DEFAULT ''";
    pdo_query($sql);
}

if(!pdo_fieldexists('imeepos_runner4_services_group','fee')){
    $sql = "ALTER TABLE ".tablename('imeepos_runner4_services_group')." ADD COLUMN `fee` decimal(10,2) NOT NULL DEFAULT '0,00'";
    pdo_query($sql);
}
return $this;