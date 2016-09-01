<?php
// ThinkPHP 入口文件
// 记录开始运行时间
header('Server:HC1.0');
header('X-Powered-By:HC');
$GLOBALS['_beginTime'] = microtime(TRUE);
define('MEMORY_LIMIT_ON',function_exists('memory_get_usage'));
if(MEMORY_LIMIT_ON) $GLOBALS['_startUseMems'] = memory_get_usage();

//系统目录定义
defined('THINK_PATH') 	or define('THINK_PATH', dirname(__FILE__).'/');

if(defined('ENGINE_NAME')){
    defined('ENGINE_PATH') or define('ENGINE_PATH',THINK_PATH.'Extend/Engine/');
    require ENGINE_PATH.strtolower(ENGINE_NAME).'.php';
}else{
    defined('RUNTIME_PATH') or define('RUNTIME_PATH',APP_PATH.'Runtime/');
    $runtime = defined('MODE_NAME')? '~'.strtolower(MODE_NAME).'_runtime.php':'~runtime.php';
    defined('RUNTIME_FILE') or define('RUNTIME_FILE',RUNTIME_PATH.$runtime);
    //如果调试模式(APP_DEBUG)未定义且RUNTIME_FILE是文件包含缓存文件否则包含运行时文件
    if(!APP_DEBUG && is_file(RUNTIME_FILE)){
        // 部署模式直接载入运行缓存
        require RUNTIME_FILE;
    }else{
        // 加载运行时文件
        require THINK_PATH.'Common/runtime.php';
    }
}


