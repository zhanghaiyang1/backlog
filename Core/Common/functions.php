<?php
/*
 * 缓存管理
 * @param mixed $name     缓存名称,如果为数组表示进行缓存设置
 * @param mixed $value    缓存值
 * @param mixed $options  缓存参数
 * @return mixed
 */
function S($name, $value='', $options=null){
    static $cache = '';
    if(empty($cache)){
        $cache = Cache::getInstance();
    }

}