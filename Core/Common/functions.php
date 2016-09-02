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

/*
 * 加载动态扩展文件
 * @return void
 */
function load_ext_file(){
    //加载自定义外部文件
    if(C('LOAD_EXT_FILE')){

    }
    //加载自定义的动态配置文件
    if(C('LOAD_EXT_CONFIG')){
        $configs = C('LOAD_EXT_CONFIG');
        if(is_string($configs)) $configs = explode(',', $configs);
        foreach($configs as $key=>$config){
            $file = CONF_PATH.$config.'.php';
            if(is_file($file)){
                is_numeric($key) ? C(include $file) : C($key, include $file);
            }
        }
    }
}