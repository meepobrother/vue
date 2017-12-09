<?php
$input = $this->__input['encrypted'];
$url = $input['url'];
load()->func('communication');
$post = array('__input'=>$input['data']);
$res = ihttp_post($url,$post);

$content = $res['content'];
$content = json_decode($content,true);
$this->info = $content['info'];
$post['__input']['encrypted'] = json_decode(base64_decode($post['__input']['encrypted']),true);
$this->msg = $post;
$this->code = $content['code'];
return $this;
