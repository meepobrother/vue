<?php
$input = $this->__input['encrypted'];

$file = ROUTERPATH."/libs/Qiniu.php";
if(file_exists($file)){
    include_once $file;
    $qiniu = new qiniu();

	$token = $qiniu->getUploadTocken();
	$this->info = $token;
	return $this;
}

// $this->info = $file;

return $this;