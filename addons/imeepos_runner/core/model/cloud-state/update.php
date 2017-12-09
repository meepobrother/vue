<?php



if(!pdo_tableexists('imeepos_runner4_cloud_site')){
    $sql = "CREATE TABLE ".tablename('imeepos_runner4_cloud_site')." (
        `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `url` varchar(320) DEFAULT NULL,
        `uniacid` int(11) DEFAULT '0',
        `avatar` varchar(320) DEFAULT '',
        `qrcode` varchar(320) DEFAULT '',
        `title` varchar(320) DEFAULT '',
        `desc` varchar(640) DEFAULT '',
        `scan_num` int(11) DEFAULT '0',
        PRIMARY KEY (`id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
      pdo_query($sql);
}

if(!pdo_fieldexists('imeepos_runner4_cloud_site','script_url')){
    $sql = "ALTER TABLE ".tablename('imeepos_runner4_cloud_site')." ADD COLUMN `script_url` varchar(320) DEFAULT ''";
    pdo_query($sql);
}

if(!pdo_fieldexists('imeepos_runner4_cloud_site','page_title')){
    $sql = "ALTER TABLE ".tablename('imeepos_runner4_cloud_site')." ADD COLUMN `page_title` varchar(32) DEFAULT ''";
    pdo_query($sql);
}

if(!pdo_fieldexists('imeepos_runner4_cloud_site','page_id')){
    $sql = "ALTER TABLE ".tablename('imeepos_runner4_cloud_site')." ADD COLUMN `page_id` int(11) DEFAULT '0'";
    pdo_query($sql);
}

if(!pdo_fieldexists('imeepos_runner4_cloud_site','good_num')){
    $sql = "ALTER TABLE ".tablename('imeepos_runner4_cloud_site')." ADD COLUMN `good_num` int(11) DEFAULT '0'";
    pdo_query($sql);
}

return $this;