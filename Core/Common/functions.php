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
/**
 * 取得对象实例 支持调用类的静态方法
 * @param string $name 类名
 * @param string $method 方法名，如果为空则返回实例化对象
 * @param array $args 调用参数
 * @return object
 */
function get_instance_of($name, $method='', $args=array()) {
    static $_instance = array();
    $identify = empty($args) ? $name . $method : $name . $method . to_guid_string($args);
    if (!isset($_instance[$identify])) {
        if (class_exists($name)) {
            $o = new $name();
            if (method_exists($o, $method)) {
                if (!empty($args)) {
                    $_instance[$identify] = call_user_func_array(array(&$o, $method), $args);
                } else {
                    $_instance[$identify] = $o->$method();
                }
            }
            else
                $_instance[$identify] = $o;
        }
        else
            halt(L('_CLASS_NOT_EXIST_') . ':' . $name);
    }
    return $_instance[$identify];
}

/*
 * 根据PHP各种类型变量生成唯一标识号
 * @param   mixed   $mix    变量
 * @return  string
 */
function to_guid_string($mix){
    if(is_object($mix) && function_exists('spl_object_hash')){
        return spl_object_hash($mix);
    }elseif(is_resource($mix)){
        $mix = get_resource_type($mix) . strval($mix);
    }else{
        $mix = serialize($mix);
    }
    return md5($mix);
}

/*
 * 自定义异常处理
 * @param   string  $msg    异常消息
 * @param   string  $type   异常类型 默认为ThinkException
 * @param   integer $code   异常代码 默认为0
 * @return  void
 */
function throw_exception($msg, $type='ThinkException', $code=0){
    if(class_exists($type, false))
        throw new $type($msg, $code);
    else
        halt($msg);     //异常类型不存在则输出错误信息字符串
}