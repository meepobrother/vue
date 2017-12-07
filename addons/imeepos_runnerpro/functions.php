<?php
if (!function_exists('M')) {
    function M($name)
    {
        static $model = array();
        if (empty($model[$name])) {
            include IA_ROOT.'/addons/imeepos_runnerpro/inc/model/'.$name.'.mod.php';
        }
        $class_name = ucfirst($name) . 'MeepoModel';
        $model[$name] = new $class_name();
        return $model[$name];
    }
}
