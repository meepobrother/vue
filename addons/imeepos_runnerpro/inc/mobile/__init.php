<?php

// 手机端需要引入的东西
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header('P3P: CP="CAO PSA OUR"');
header("Content-Type: application/json; charset=utf-8");

function ToJson($data = array())
{
    die(json_encode($data));
}
