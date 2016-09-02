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

/*
 * session管理函数
 * @param   string|array    $name       session名称，如果为数组则进行session设置
 * @param   mixed           $value      session值
 * @return  mixed
 */
function session($name, $value=''){
    $prefix = C('SESSION_PREFIX');
    if(is_array($prefix)){

    }elseif(''===$value){

    }elseif(is_null($value)){

    }else{//设置session
        if($prefix){

        }else{
            $_SESSION[$name] = $value;
        }
    }

}