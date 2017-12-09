<?php

$input = $this->__input['encrypted'];

// pdo_query("DROP table".tablename('imeepos_runner4_app'));
if(!pdo_tableexists('imeepos_runner4_app')){
  // 安装 app imeepos_runner4_app
  $sql = "CREATE TABLE ".tablename('imeepos_runner4_app')." (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `token` varchar(64) DEFAULT NULL COMMENT 'token',
    `title` varchar(64) DEFAULT NULL COMMENT '标题',
    `author` varchar(64) DEFAULT NULL COMMENT '作者',
    `uniacid` int(11) DEFAULT '0',
    `price` decimal(10,2) DEFAULT '0.00' COMMENT '价格',
    PRIMARY KEY (`id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
  pdo_query($sql);
}

if(!pdo_fieldexists('imeepos_runner4_app','rights')){
  $sql = "ALTER TABLE ".tablename('imeepos_runner4_app')." ADD COLUMN `rights` text NOT NULL ";
  pdo_query($sql);
}
  

if(!pdo_tableexists('imeepos_runner4_app_catalog')){
  // 安装 页面分组 imeepos_runner4_app_catalog
  $sql = "CREATE TABLE ".tablename('imeepos_runner4_app_catalog')." (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `title` varchar(32) DEFAULT NULL,
    `app_id` int(11) DEFAULT NULL,
    PRIMARY KEY (`id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
  pdo_query($sql);
}  

if(!pdo_tableexists('imeepos_runner4_app_catalog_pages')){
  // 安装 页面 imeepos_runner4_app_catalog_pages
  $sql = "CREATE TABLE ".tablename('imeepos_runner4_app_catalog_pages')." (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `cata_id` int(11) DEFAULT NULL,
    `app_id` int(11) DEFAULT NULL,
    `title` varchar(64) DEFAULT NULL,
    PRIMARY KEY (`id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
  pdo_query($sql);
}

if(!pdo_fieldexists('imeepos_runner4_app_catalog_pages','header')){
  $sql = "ALTER TABLE ".tablename('imeepos_runner4_app_catalog_pages')." 
  ADD COLUMN `header` text NULL AFTER `title`,
  ADD COLUMN `body` text NULL AFTER `header`,
  ADD COLUMN `menu` text NULL AFTER `body`,
  ADD COLUMN `footer` text NULL AFTER `menu`,
  ADD COLUMN `kefu` text NULL AFTER `footer`,
  ADD COLUMN `desc` text NULL AFTER `kefu`,
  ADD COLUMN `url` varchar(320) NULL AFTER `desc`,
  ADD COLUMN `cover` varchar(320) NULL AFTER `url`;
  ";
  pdo_query($sql);
}  


if(!pdo_fieldexists('imeepos_runner4_app_catalog_pages','html_content')){
  $sql = "ALTER TABLE ".tablename('imeepos_runner4_app_catalog_pages')." ADD COLUMN `html_content` text NULL AFTER `title` ";
  pdo_query($sql);
}
if(!pdo_fieldexists('imeepos_runner4_app_catalog_pages','pageType')){
  $sql = "ALTER TABLE ".tablename('imeepos_runner4_app_catalog_pages')." ADD COLUMN `pageType` varchar(32) NOT NULL DEFAULT ''";
  pdo_query($sql);
}

if(!pdo_fieldexists('imeepos_runner4_app_catalog_pages','isdefault')){
  $sql = "ALTER TABLE ".tablename('imeepos_runner4_app_catalog_pages')." ADD COLUMN `isdefault` tinyint(2) NOT NULL DEFAULT '0'";
  pdo_query($sql);
}


if(!pdo_tableexists('imeepos_runner4_app_catalog_pages_widget')){
  // 安装 插件 imeepos_runner4_app_catalog_pages_widget

  $sql = "CREATE TABLE ".tablename('imeepos_runner4_app_catalog_pages_widget')." (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `type` varchar(32) DEFAULT NULL COMMENT '类型',
    `name` varchar(32) DEFAULT NULL COMMENT '名称',
    `content` text COMMENT '内容',
    `page_id` int(11) DEFAULT NULL,
    `fid` int(11) unsigned NOT NULL DEFAULT '0',
    `styleObj` text,
    `classObj` text,
    `containerStyle` text,
    `containerClass` text,
    PRIMARY KEY (`id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
  pdo_query($sql);
}  

if(!pdo_tableexists('imeepos_runner4_app_widgets')){
  // 插件
  $sql = "CREATE TABLE ".tablename('imeepos_runner4_app_widgets')." (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `type` varchar(32) DEFAULT NULL,
    `name` varchar(32) DEFAULT NULL,
    PRIMARY KEY (`id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
  pdo_query($sql);

  $sql = "insert into ".tablename('imeepos_runner4_app_widgets')."(`id`,`type`,`name`) values
  ('3','meepo-tasks','任务列表'),
  ('2','meepo-filter','过滤'),
  ('1','meepo-advs','广告');";

  pdo_query($sql);
}  



if(!pdo_tableexists('imeepos_runner4_app_widgets_group')){
  // 插件
  $sql = "CREATE TABLE ".tablename('imeepos_runner4_app_widgets_group')." (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `title` varchar(32) DEFAULT NULL,
    `code` varchar(32) DEFAULT NULL,
    PRIMARY KEY (`id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
  pdo_query($sql);
}  

if(!pdo_fieldexists('imeepos_runner4_app_widgets_group','group_id')){
  $sql = "ALTER TABLE ".tablename('imeepos_runner4_app_widgets_group')." ADD COLUMN `group_id` int(11) NOT NULL DEFAULT '0' ";
  pdo_query($sql);
}


if(!pdo_fieldexists('imeepos_runner4_app_widgets','group_id')){
  $sql = "ALTER TABLE ".tablename('imeepos_runner4_app_widgets')." ADD COLUMN `group_id` int(11) NOT NULL DEFAULT '0' ";
  pdo_query($sql);
}

if(!pdo_tableexists('imeepos_runner4_app_forms')){
  // 插件
  $sql = "CREATE TABLE ".tablename('imeepos_runner4_app_forms')." (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `type` varchar(32) DEFAULT NULL,
    `name` varchar(32) DEFAULT NULL,
    PRIMARY KEY (`id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
  pdo_query($sql);
}  


if(!pdo_fieldexists('imeepos_runner4_app_forms','tpl')){
  $sql = "ALTER TABLE ".tablename('imeepos_runner4_app_forms')." ADD COLUMN `tpl` text NULL ";
  pdo_query($sql);
}

return $this;