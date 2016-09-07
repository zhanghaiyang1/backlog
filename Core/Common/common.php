<?php
/**
 * Think 基础函数库
 * @category   Think
 * @package  Common
 * @author   liu21st <liu21st@gmail.com>
 */


/*
 * 优化的require_once
 * @param string $filename  文件地址
 * @return boolen
 */
function require_cache($filename){
    static $_improtFiles = array();
    if(!isset($_improtFiles[$filename])){
        if(file_exists_case($filename)){
            require $filename;
            $_improtFiles[$filename] = true;
        }else{
            $_improtFiles[$filename] = false;
        }
    }
    return $_improtFiles;
}

/*
 * 区分大小写的文件存在判断
 * @param string $filename  文件地址
 * @return boolen
 */
function file_exists_case($filename){
    if(is_file($filename)){
           if(IS_WIN && C('APP_FILE_CASE')){
            if(basename(realpath($filename)) != basename($filename)){
                return false;
            }
        }
        return true;
    }
    return false;
}

/*
 * 获取和设置配置参数 支持批量定义
 * @param string|array $name 配置变量
 * @param mixed $value       配置值
 * @return mixed
 */
function C($name=null, $value=null){
    static $_config = array();
    
    if(empty($name)){
        if(!empty($value) && $array = S('c_'.$value)){

        }
    }

    if(is_string($name)){
        if(!strpos($name, '.')){
            $name = strtolower($name);
            if(is_null($value)){
                return isset($_config[$name]) ? $_config[$name] : null;
                $_config[$name] = $value;
                return;
            }
        }
        //二维数组设置和获取支持
        $name = explode('.', $name);
        $name[0] = strtolower($name[0]);
        if(is_null($value))
            return isset($_config[$name[0]][$name[1]]) ? $_config[$name[0]][$name[1]] : null;
        $_config[$name[0]][$name[1]] = $value;
        return;
    }

    if(is_array($name)){
        $_config = array_merge($_config, array_change_key_case($name));
        if(!empty($value)){
            S('c_'.$value,$_config);
        }
        return;
    }
    return null;
}

/*
 * 快速定义和导入别名 支持批量定义
 * @param string|array $alias 类库别名
 * @param string $classfile   对应类库
 * @return boolean
 */

function alias_import($alias, $classfile=''){
    static $_alias = array();
    if(is_string($alias)){
        if(isset($_alias[$alias])){
            return require_cache($_alias[$alias]);
        }elseif('' !== $classfile){
            //定义别名导入
            $_alias[$alias] = $classfile;
            return;
        }
    }elseif(is_array($alias)){
        $_alias = array_merge($_alias, $alias);
        return;
    }
    return false;
}


/*
 * 获取和设置语言定义（不区分大小写）
 * @param string | array  $name 语言变量
 * @param string          $value语言值
 * @return mixed
 */
function L($name=null, $value=null){
    static $_lang = array();
    if(empty($name))
        return $_lang;

    if(is_string($name)){
        $name = strtoupper($name);
        if(is_null($value))
            return isset($_lang[$name]) ? $_lang[$name] : $name;
        $_lang[$name] = $value;
        return;
    }

    if(is_array($name))
        $_lang = array_merge($_lang, array_change_key_case($name, CASE_UPPER));
    return;
}

//编译文件
function compile($filename){
    $content = file_get_contents($filename);
    //替换预编译指令
    $content =preg_replace('/\/\/\[RUNTIME\](.*?)\/\/\[\/RUNTIME\]/s', '', $content);
    $content = substr(trim($content), 5);
    if('?>'==substr($content ,-2))
        $content = substr($content, 0, -2);
    return $content;
}

/*
 * 处理标签扩展
 * @param   string $tag     标签名称
 * @param   mixed  $params  传入参数
 * @return  mixed
 */
function tag($tag, &$param=null){
    //系统标签扩展
    $extends = C('extends.' . $tag);
    //应用标签扩展
    $tags = C('tags.' . $tag);
    if($tags){

    }else{
        return false;
    }
}

/**
 * 记录和统计时间（微秒）和内存使用情况
 * 使用方法:
 * <code>
 * G('begin'); // 记录开始标记位
 * // ... 区间运行代码
 * G('end'); // 记录结束标签位
 * echo G('begin','end',6); // 统计区间运行时间 精确到小数后6位
 * echo G('begin','end','m'); // 统计区间内存使用情况
 * 如果end标记位没有定义，则会自动以当前作为标记位
 * 其中统计内存使用需要 MEMORY_LIMIT_ON 常量为true才有效
 * </code>
 * @param string $start 开始标签
 * @param string $end 结束标签
 * @param integer|string $dec 小数位或者m
 * @return mixed
 */
function G($start, $end='', $dec=4){
    static $_info = array();
    static $_mem = array();
    if(is_float($end)){

    }elseif(!empty($end)){

    }else{//记录时间和内存使用
        $_info[$start] = microtime(true);
        if(MEMORY_LIMIT_ON) $_mem[$start] = memory_get_usage();
    }
}

/*
 * 字符串命名风格转换
 * type 0 将Java风格转换为C风格 1  将C风格转换为Java风格
 * @param   string  $name   字符串
 * @param   integer $type   转换类型
 * @return  string
 */
function parse_name($name, $type=0){
    if($type){
        return ucfirst(preg_replace("/_(a-zA-Z)/e", "strtoupper('\\1')", $name));
    }else{
        return($name);exit;
        return strtolower(trim(preg_replace("/[A-Z]/", "_\\0", $name), "_"));
    }
}

/**
 * 批量导入文件 成功则返回
 * @param array $array 文件数组
 * @param boolean $return 加载成功后是否返回
 * @return boolean
 */
function require_array($array, $return=false){
    foreach($array as $file){
        if(require_cache($file) && $return) return true;
    }
    if($return) return false;
}

/*
 * 加解密函数，注加密串中不允许包括/-=+等字符
 * @param   boolean $is_crypt   true加密  $str需传明文串；  false为解密,$str需传加密后的串
 */
function str_ed_crypt($str, $is_crypt=true, $keyStr='hc'){
    if($str){
        import('ORG.Crypt.CryptAES');
    }else{
        return '';
    }
}

/*
 * 导入类库，同Java的import，本函数有缓存功能
 * @param   string  $class      类库命名空间字符串
 * @param   string  $baseUrl    起始路径
 * @param   string  $ext        导入的文件扩展名
 * @return  boolean
 */
function import($class, $baseUrl = '', $ext = '.class.php'){
    static $file = array();
    $class = str_replace(array('.', '#'), array('/', '.'), $class);
    if($baseUrl===''){
        die(1);
    }else{
        die(2);
    }
}

/*
 * 设置和获取统计数据
 * 使用方法：
 * <code>
 * N('db',1);       记录数据库操作次数
 * N('read',1);     记录读取次数
 * echo N('db');    获取当前页面数据库的所有操作次数
 * echo N('read');  获取当前页面读取次数
 * </code>
 * @param   string  $key    标识位置
 * @param   integer $step   步进值
 * @return  mixed
 */
function N($key, $step=0, $save=false){
    static $_num = array();
    if(!isset($_num[$key])){
        $_num[$key] = (false !== $save) ? S('N_'.$key) : 0;
    }
    if(empty($step))
        return $_num[$key];
    else
        $_num[$key] = $_num[$key] + (int) $step;
    if(false!==$save){
        S('N_'.$key, $_num[$key], $save);
    }
}