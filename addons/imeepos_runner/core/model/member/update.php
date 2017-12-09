<?php
// pdo_query("DROP table ".tablename('imeepos_runner4_member_group'));
if(!pdo_tableexists('imeepos_runner4_member_group')){
    $sql = "CREATE TABLE ".tablename('imeepos_runner4_member_group')." (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `title` varchar(64) NOT NULL,
        `uniacid` int(11) NOT NULL DEFAULT '0',
        `desc` varchar(320) NOT NULL DEFAULT '',
        `status` tinyint(2) NOT NULL DEFAULT '0',
        PRIMARY KEY (`id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
    pdo_query($sql);
}

if(!pdo_fieldexists('imeepos_runner4_member_group','tags')){
    $sql = "ALTER TABLE ".tablename('imeepos_runner4_member_group')." ADD COLUMN `tags` text NOT NULL AFTER `status`;";
    pdo_query($sql);
}

if(!pdo_fieldexists('imeepos_runner4_member_group','displayorder')){
    $sql = "ALTER TABLE ".tablename('imeepos_runner4_member_group')." ADD COLUMN `displayorder` int(11) NOT NULL DEFAULT '0'";
    pdo_query($sql);
}

if(!pdo_fieldexists('imeepos_runner4_member_group','fid')){
    $sql = "ALTER TABLE ".tablename('imeepos_runner4_member_group')." ADD COLUMN `fid` int(11) NOT NULL DEFAULT '0'";
    pdo_query($sql);
}

if(!pdo_fieldexists('imeepos_runner3_member','shop_id')){
    $sql = "ALTER TABLE ".tablename('imeepos_runner3_member')." ADD COLUMN `shop_id` int(11) NOT NULL DEFAULT '0'";
    pdo_query($sql);
}

return $this;