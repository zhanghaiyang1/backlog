<?php
/**
 * ThinkPHP 运行时文件 编译后不再加载
 */
defined('THINK_PATH') or exit();
if(version_compare(PHP_VERSION,'5.2.0','<')) die('requier PHP > 5.2.0 !');

//系统信息
if(version_compare(PHP_VERSION,'5.4.0','<')){
    ini_set('magic_quotes_runtime',0);
    define('MAGIC_QUOTES_GPC',get_magic_quotes_gpc()?true:false);
}else{
    define('MAGIC_QUOTES_GPC',false);
}

define('IS_CGI',substr(PHP_SAPI,-3) == 'cgi' ? 1 : 0);
define('IS_CLI',PHP_SAPI == 'cli' ? 1 : 0);
define('IS_WIN',strstr(PHP_OS,'WIN') ? 1 : 0);

if(!IS_CLI){
    //当前文件名
    if(!defined('_PHP_FILE_')){
        if(IS_CGI){
            $_temp = explode('.php',$_SERVER['PHP_SELF']);
            define('_PHP_FILE_', rtrim(str_replace($_SERVER['HTTP_HOST'],'',$_temp[0].'.php'),'/'));
        }else{
            define('_PHP_FILE_',$_SERVER['SCRIPT_NAME']);
        }
    }
    //网站URL根目录
    if(!defined('__ROOT__')){
        $_root = dirname(_PHP_FILE_);
        define('__ROOT__',($_root == '/' || $_root == '\\') ? '' : $_root);
    }
}

//路径设置 可在入口文件中重新定义 所有路径常量都必须以 / 结尾
defined('CORE_PATH')    or define('CORE_PATH',THINK_PATH.'lib/');
defined('COMMON_PATH')  or define('COMMON_PATH',APP_PATH.'Common/');
defined('CONF_PATH')    or define('CONF_PATH',APP_PATH.'Conf/');

//加载运行时所需要的文件并负责自动目录生成
function load_runtime_file(){
    //系统基础函数库
    require THINK_PATH.'Common/common.php';

    //读取核心文件列表
    $list = array(
        CORE_PATH.'Core/Think.class.php',
        CORE_PATH.'Core/ThinkException.class.php',
        CORE_PATH.'Core/Behavior.class.php'
    );
    // 加载模式文件列表
    foreach($list as $file){
        if(is_file($file)) require_cache($file);
    }

    // 加载系统类库别名定义
    alias_import(include THINK_PATH.'Conf/alias.php');

}

load_runtime_file();
Think::start();