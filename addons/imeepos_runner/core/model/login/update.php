<?php

if(!pdo_tableexists('imeepos_runner4_member_site')){
    $sql = "CREATE TABLE ".tablename('imeepos_runner4_member_site')." (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `uniacid` int(11) DEFAULT '0',
        `acid` int(11) DEFAULT '0',
        `siteroot` varchar(320) DEFAULT '',
        `openid` varchar(64) DEFAULT '',
        PRIMARY KEY (`id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
      pdo_query($sql);
}    

return $this;