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
function tag($tag, &$param){
    //系统标签扩展
    $extends = C('extends.' . $tag);
    //应用标签扩展
    $tags = C('tags' . $tag);
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

