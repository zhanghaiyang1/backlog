<?php
class Think{
    /*应用程序初始化
     * @access public
     * @return void
     */
    static public function start(){
        //设定错误和异常处理
        register_shutdown_function(array('Think','fatalError'));
        set_error_handler(array('Think','appError'));

        Think::buildApp();      //预编译项目
    }


    //致命错误捕获
    static function fatalError(){
        //保存日志记录
        if(C('LOG_RECORD')) Log::save();
        if($e = error_get_last()){
            switch($e['type']){
                case E_ERROR:
                case E_PARSE:
                case E_CORE_ERROR:
                case E_COMPILE_ERROR:
                case E_USER_ERROR:

                ob_end_clean();
                function_exists('halt') ? halt($e) : exit('ERROR:'.$e['message']);
                break;
            }
        }
        echo '<br>',"I'm gone";
    }

    /*
     * 自定义错误处理
     * @access public
     * @param int $errno        错误类型
     * @param string $errstr    错误信息
     * @param string $errfile   错误文件
     * @param int $errline      错误行号
     * @return void
     */
    static public function appError($errno, $errstr, $errfile, $errline){
        echo '<br>',"appError，i am comming";
        switch($errno){
            case E_ERROR:
            case E_PARSE:
            case E_CORE_ERROR:
            case E_COMPILE_ERROR:
            case E_USER_ERROR:

            ob_end_clean();     //清除缓冲区并关闭输出缓冲
            if(C('OUTPUT_ENCODE')){
                $zlib = ini_get('zlib.output_compression');
                if(empty($zlib)) ob_start('ob_gzhandler');
            }
            $errStr = "$errstr ".$errfile." 第 $errline 行。";
        }
    }

    /*
     * 读取配置信息编译项目
     * @access private
     * @return string
     */

    static private function buildApp(){
        echo 'buildApp';
        //读取运行模式
        if(defined('MODE_NAME')){
            $mode = include MODE_PATH.strtolower(MODE_NAME).'php';
        }else{
            $mode = array();
        }
        //加载核心惯例配置文件
        C(include THINK_PATH.'Conf/convention.php');

        //加载项目配置文件
        if(is_file(CONF_PATH.'config.php'))
            C(include CONF_PATH.'config.php');

        //加载框架底层语言包

        L(include THINK_PATH.'/Lang/'.strtolower(C('DEFAULT_LANG')).'.php');

    }


}