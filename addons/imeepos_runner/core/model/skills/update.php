<?php
if(!pdo_tableexists('imeepos_runner4_skills')){
    $sql = "CREATE TABLE ".tablename('imeepos_runner4_skills')." (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `uniacid` int(11) NOT NULL DEFAULT '0',
        `title` varchar(64) NOT NULL DEFAULT '',
        `desc` varchar(320) NOT NULL DEFAULT '',
        `status` tinyint(2) NOT NULL DEFAULT '0',
        PRIMARY KEY (`id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
    pdo_query($sql);
}

if(!pdo_fieldexists('imeepos_runner4_skills','group_id')){
    $sql = "ALTER TABLE ".tablename('imeepos_runner4_skills')." ADD COLUMN `group_id` int(11) NOT NULL DEFAULT '0'";
    pdo_query($sql);
}

if(!pdo_fieldexists('imeepos_runner4_skills','create_time')){
    $sql = "ALTER TABLE ".tablename('imeepos_runner4_skills')." ADD COLUMN `create_time` int(11) NOT NULL DEFAULT '0'";
    pdo_query($sql);
}

if(!pdo_tableexists('imeepos_runner4_skills_group')){
    $sql = "CREATE TABLE ".tablename('imeepos_runner4_skills_group')." (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `uniacid` int(11) NOT NULL DEFAULT '0',
        `title` varchar(64) NOT NULL DEFAULT '',
        `desc` varchar(320) NOT NULL DEFAULT '',
        `status` tinyint(2) NOT NULL DEFAULT '0',
        PRIMARY KEY (`id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
    pdo_query($sql);
}
if(!pdo_fieldexists('imeepos_runner4_skills_group','tags')){
    $sql = "ALTER TABLE ".tablename('imeepos_runner4_skills_group')." ADD COLUMN `tags` text NOT NULL AFTER `status`;";
    pdo_query($sql);
}

if(!pdo_fieldexists('imeepos_runner4_skills_group','displayorder')){
    $sql = "ALTER TABLE ".tablename('imeepos_runner4_skills_group')." ADD COLUMN `displayorder` int(11) NOT NULL DEFAULT '0'";
    pdo_query($sql);
}

if(!pdo_fieldexists('imeepos_runner4_skills_group','fid')){
    $sql = "ALTER TABLE ".tablename('imeepos_runner4_skills_group')." ADD COLUMN `fid` int(11) NOT NULL DEFAULT '0'";
    pdo_query($sql);
}

return $this;