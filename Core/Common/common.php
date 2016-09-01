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


