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
return $this;