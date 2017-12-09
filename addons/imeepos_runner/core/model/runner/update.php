<?php

if(!pdo_fieldexists('imeepos_runner3_member','forbid_time')){
    $sql = "ALTER TABLE ".tablename('imeepos_runner3_member')." ADD COLUMN `forbid_time` int(10) NOT NULL DEFAULT 0";
    pdo_query($sql);
}

return $this;