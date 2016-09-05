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
        set_exception_handler(array('Think', 'appException'));
        //注册AUTOLOAD方法
        spl_autoload_register(array('Think', 'autoload'));

        Think::buildApp();      //预编译项目
        App::run();             //运行应用
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
     * 自定义异常处理
     * @access  public
     * @param   mixed   $e  异常对象
     */
    static public function appException($e){
        $error = array();
        $error['message'] = $e->getMessage();
        $trace = $e->getTrace();
        if('throw_exception'==$trace[0]['function']){
            $error['file'] = $trace[0]['file'];
            $error['line'] = $trace[0]['line'];
        }else{
            $error['file'] = $e->getFile();
            $error['line'] = $e->getLine();
        }
        Log::record($error['message'],Log::ERR);
        halt($error);
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
//        echo '<br>',"appError，i am comming";
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

        //加载模式系统行为定义
        if(C('APP_TAGS_ON')){
            if(isset($mode['extends'])){
                C('extends',is_array($mode['extends']) ? $mode['extends'] : include $mode['extends']);
            }else{ //默认加载系统行为定义
                C('extends', include THINK_PATH.'Conf/tags.php');
            }
        }

        //加载应用行为定义
        if(isset($mode['tags'])){
            C('tats', is_array($mode['tags']) ? $mode['tags'] : include $mode['tags']);
        }elseif(is_file(CONF_PATH.'tags.php')){
            //默认加载项目配置目录的tags文件定义
            C('tags', include CONF_PAHT.'tags.php');
        }

        $compile = '';
        //读取核心编译文件列表
        if(isset($mode['core'])){
            $list = $mode['core'];
        }else{
            $list = array(
                THINK_PATH.'Common/functions.php',      //标准模式函数库
                CORE_PATH.'Core/Log.class.php',         //日志处理类
                CORE_PATH.'Core/Dispatcher.class.php',  //URL调度类
                CORE_PATH.'Core/App.class.php',         //应用程序类
                CORE_PATH.'Core/Action.class.php',      //控制器类
                CORE_PATH.'Core/View.class.php'         //视图类
            );
        }

        //项目追加核心编译列表文件
        if(is_file(CONF_PATH.'core.php')){
            $list = array_merge($list, include CONF_PATH.'core.php');
        }
        foreach($list as $file){
            if(is_file($file)){
                require_cache($file);
                if(!APP_DEBUG) $compile .= compile($file);
            }
        }

        //加载项目公共文件
        if(is_file(COMMON_PATH.'common.php')){
            include COMMON_PATH.'common.php';
            //编译文件
            if(!APP_DEBUG) $compile .= compile(COMMON_PATH.'common.php');
        }

        //加载模式别名定义
        if(isset($mode['alias'])){}

        //加载项目别名定义
        if(is_file(CONF_PATH.'alias.php')){
            $alias = include CONF_PATH.'alias.php';
        }

        if(APP_DEBUG){
            //调试模式加载系统默认的配置文件
            C(include THINK_PATH.'Conf/debug.php');
            //读取调试模式的应用状态
            $status = C('APP_STATUS');
            //加载对应的项目配置文件
            if(is_file(CONF_PATH.$status.'.php')){
                C(include CONF_PATH.$status.'.php');
            }
        }else{
            build_runtime_cache($compile);
        }
        return;
    }

    /*
     *系统自动加载Thinkphp类库
     *并且支持配置自动加载路径
     * @param   string  $class  类名
     * @return  void
     */
    public static function autoload($class){
        //检查是否存在别名定义
        if(alias_import($class)) return;

        $libPath = defined('BASE_LIB_PATH') ? BASE_LIB_PATH : LIB_PATH;
        $group = defined('GROUP_NAME') && C('APP_GROUP_MODE') == 0 ? GROUP_NAME.'/':'';
        $file  = $class.'.class.php';
        if(substr($class, -8)=='Behavior'){ //加载行为
            if(require_array(array(
                CORE_PATH.'Behavior'.$file,
                EXTEND_PATH.'Behavior'.$file,
                LIB_PATH.'Behavior'.$file,
                $libPath.'Behavior'.$file), true)
                || (defined('MODE_NAME') && require_cache(MODE_PATH.ucwords(MODE_NAME).'/Behavior/'.$file))){
                return;
            }
        }elseif(substr($class, -5)=='Model'){
            if(require_array(array(
                LIB_PATH.'Model/'.$group.$file,
                $libPath.'Mode/'.$file,
                EXTEND_PATH.'Model/'.$file), true)){
                return;
            }
        }
    }

}