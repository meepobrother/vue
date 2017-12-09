<?php


if(!pdo_tableexists('imeepos_runner4_topics')){
    $sql = "CREATE TABLE ".tablename('imeepos_runner4_topics')." (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `uniacid` int(11) DEFAULT '0',
        `title` varchar(128) DEFAULT '',
        `desc` varchar(320) DEFAULT '',
        `content` text,
        `create_time` int(11) DEFAULT '0',
        `class_id` int(11) DEFAULT '0',
        `tags` varchar(640) DEFAULT '',
        PRIMARY KEY (`id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
      pdo_query($sql);
}

if(!pdo_tableexists('imeepos_runner4_topics_group')){
    $sql = "CREATE TABLE ".tablename('imeepos_runner4_topics_group')." (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `uniacid` int(11) NOT NULL DEFAULT '0',
        `title` varchar(64) NOT NULL DEFAULT '',
        `desc` varchar(320) NOT NULL DEFAULT '',
        `status` tinyint(2) NOT NULL DEFAULT '0',
        PRIMARY KEY (`id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
    pdo_query($sql);
}
if(!pdo_fieldexists('imeepos_runner4_topics_group','tags')){
    $sql = "ALTER TABLE ".tablename('imeepos_runner4_topics_group')." ADD COLUMN `tags` text NOT NULL AFTER `status`;";
    pdo_query($sql);
}

if(!pdo_fieldexists('imeepos_runner4_topics_group','displayorder')){
    $sql = "ALTER TABLE ".tablename('imeepos_runner4_topics_group')." ADD COLUMN `displayorder` int(11) NOT NULL DEFAULT '0'";
    pdo_query($sql);
}

if(!pdo_fieldexists('imeepos_runner4_topics_group','fid')){
    $sql = "ALTER TABLE ".tablename('imeepos_runner4_topics_group')." ADD COLUMN `fid` int(11) NOT NULL DEFAULT '0'";
    pdo_query($sql);
}

return $this;