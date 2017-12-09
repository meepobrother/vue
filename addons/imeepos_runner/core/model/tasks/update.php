<?php

if(!pdo_tableexists('imeepos_runner4_tasks_group')){
  $sql = "CREATE TABLE ".tablename('imeepos_runner4_tasks_group')." (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `uniacid` int(11) DEFAULT '0',
    `title` varchar(64) DEFAULT '',
    `setting` text,
    `displayorder` int(11) DEFAULT '0',
    PRIMARY KEY (`id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8";

  pdo_query($sql);

}

if(!pdo_fieldexists('imeepos_runner4_tasks_group','desc')){
  $sql = "ALTER TABLE ".tablename('imeepos_runner4_tasks_group')." ADD COLUMN `desc` text NOT NULL;";
  pdo_query($sql);
}

if(!pdo_fieldexists('imeepos_runner4_tasks_group','tags')){
  $sql = "ALTER TABLE ".tablename('imeepos_runner4_tasks_group')." ADD COLUMN `tags` text NOT NULL AFTER `status`;";
  pdo_query($sql);
}

if(!pdo_fieldexists('imeepos_runner4_tasks_group','displayorder')){
  $sql = "ALTER TABLE ".tablename('imeepos_runner4_tasks_group')." ADD COLUMN `displayorder` int(11) NOT NULL DEFAULT '0'";
  pdo_query($sql);
}

if(!pdo_fieldexists('imeepos_runner4_tasks_group','fid')){
  $sql = "ALTER TABLE ".tablename('imeepos_runner4_tasks_group')." ADD COLUMN `fid` int(11) NOT NULL DEFAULT '0'";
  pdo_query($sql);
}

return $this;