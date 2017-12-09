<?php

if(!pdo_tableexists('imeepos_tixian_manage')){
    $sql = "CREATE TABLE ".tablename('imeepos_tixian_manage')." (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `uniacid` int(11) DEFAULT '0',
        `create_time` int(11) DEFAULT '0',
        `openid` varchar(64) DEFAULT '',
        `status` tinyint(2) DEFAULT '0',
        `credit` decimal(10,2) DEFAULT '0.00',
        `message` varchar(320) DEFAULT '',
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC";
    pdo_query($sql);
}

return $this;