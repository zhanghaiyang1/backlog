<?php
return array(
    //'配置项'=>'配置值'
    'LOAD_EXT_CONFIG'=>'other_config',
    //页面属性设置
    'TITLE' => 'Chedai',
    'KEYWORDS' => 'Chedai',
    'DESCRIPTION' => 'Chedai',
    'PROJECT_NAME' => '车贷管理',

    //数据库设置
    'DB_TYPE' => 'pdo', // 数据库类型
    'DB_DSN' => 'mysql:host=10.150.20.41;dbname=jaguar;charset=utf8', // DSN连接。           #DB_DSN
    'DB_USER' => 'jaguar', // 数据库用户名                                                   #DB_USER
    'DB_PWD' => 'jaguar', // 数据库密码                                                  #DB_PWD
    'DB_PORT' => '3306', // 数据库端口
    'DB_PREFIX' => 'hcd_', // 数据表前缀
    'DB_CHARSET' => 'utf8', // 数据库编码采用utf8
    'DB_FIELDTYPE_CHECK' => false, // 是否进行字段类型检查
    'DB_FIELDS_CACHE' => false, // 启用字段缓存
    'DB_DEPLOY_TYPE' => 0, // 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)        #DB_DEPLOY_TYPE
    'DB_RW_SEPARATE' => false, // 数据库读写是否分离 主从式有效                               #DB_RW_SEPARATE

    //redis配置
    'RS_DB' => array(
        'host'   => '10.150.20.162',                                                            #redishost
        'port' => '6379',                                                                   #redisport
        'expire'=>'30',                                                                     #redisexpire
        'database'=> '15'                                                                   #redisdatabase
    ),

    //缓存设置
    'DATA_CACHE_TYPE' => 'Memcache',
    'MEMCACHE_HOST' => '127.0.0.1:12321',                                                   #MEMCACHE_HOST
    'DATA_CACHE_TIME' => 30, // 数据缓存有效期
    'DATA_CACHE_COMPRESS' => true, // 数据缓存是否压缩缓存
    'DATA_CACHE_CHECK' => true, // 数据缓存是否校验缓存
    'DATA_CACHE_PATH' => TEMP_PATH, // 缓存路径设置 (仅对File方式缓存有效)
    'DATA_CACHE_SUBDIR' => true, // 使用子目录缓存 (自动根据缓存标识的哈希创建子目录)
    'DATA_PATH_LEVEL' => 1, // 子目录缓存级别

    /* 运行时间设置  调试时用 */
    'APP_DEBUG' => true,
    'SHOW_RUN_TIME' => false, // 运行时间显示
    'SHOW_ADV_TIME' => false, // 显示详细的运行时间
    'SHOW_DB_TIMES' => false, // 显示数据库查询和写入次数
    'SHOW_CACHE_TIMES' => false, // 显示缓存操作次数
    'SHOW_USE_MEM' => false, // 显示内存开销
    'URL_PAGE_DEPR' => false, //url_model为1，2时有效
    'VAR_FILTERS' => false,
    //分页设置
    'VAR_PAGE' => 'p',
    'PAGE_ROW_NUMS' => 15,

    //是否开启webservice服务
    'WEB_SER_LOAD' => true,
    //配置webservice服务地址
    'WEB_SER_ARR' => array(
        'esb' => 'http://10.150.20.143:8080/ESB/webservice/esbWS?wsdl',                    #esb
    ),

    //静态地址配置
    'WEB_URL_ARR'=>array(
        'upload_path' =>'http://localhost/file/'                                            #upload_path
    ),

    //登录验证
    'CHECK_LOGIN' => true,
    //css，js路径设置
    'CSS_DIR' => APP_URL . 'Public/css/',
    'JS_DIR' => APP_URL . 'Public/js/',
    'IMG_DIR' => APP_URL . 'Public/images/',

    /* Cookie设置 */
    'COOKIE_EXPIRE' => 3600, // Coodie有效期
    'COOKIE_DOMAIN' => '', // Cookie有效域名
    'COOKIE_PATH' => '/', // Cookie路径
    'COOKIE_PREFIX' => '', // Cookie前缀 避免冲突
    'SESSION_AUTO_START' => true, //是否开启session

    /* 默认设定 */
    'DEFAULT_MODULE'        => 'admin',
    'DEFAULT_TIMEZONE' => 'PRC', // 默认时区
    'DEFAULT_AJAX_RETURN' => 'JSON', // 默认AJAX 数据返回格式,可选JSON XML ...
    'DEFAULT_LANG' => 'zh-cn', // 默认语言
    'VAR_FILTERS'=>'filter_default',

    /* 错误设置 */
    'ERROR_MESSAGE' => '您浏览的页面暂时发生了错误！请稍后再试～', //错误显示信息,非调试模式有效
    'ERROR_PAGE' => '', // 错误定向页面
    'ERROR_ON' => false,//开发时用
    'ERROR_SHOW' => true,

    //缩略图设置
    'TMPL_PARSE_STRING' => array('__PUBLIC__' => APP_URL . 'Public'),
    'DEFAULT_CHARSET' => 'utf-8',
    //广告图片上传路径
    'UPLOAD_TEMP' =>  '../Runtime/Temp/',

    'URL_ROUTER_ON' => true, //开启路由
    'URL_MODEL' => '2', //URL模式
    'URL_CASE_INSENSITIVE'  =>  true,
    'URL_PATHINFO_DEPR'=>'/', // 更改PATHINFO参数分隔符
    'URL_HTML_SUFFIX' => '', //仅当url模式为1，2时有效

    //定义路由规则
    'URL_ROUTE_RULES' => array(
        '/^webservice.html$/' => 'Soap/esbRemind',  //后台首页
        '/^index.html$/' => 'admin/index',  //后台首页
        '/^login.html$/' => 'login/index',  //登录页
        '/^upload-(\d+).html$/' => 'file/upload_img?aid=:1', //上传页面
        '/^upload_(\d+)-(\d+).html$/' => 'file/upload_img?flag=:1&aid=:2', //上传页面
        '/^rupload_(\w+)-(\d+).html$/' => 'file/upload_img?rid=:1&aid=:2', //上传页面
        '/^upload.action$/' => 'file/upload', //上传
    ),

    //展期次数
    'ZQ_NUMS' => 5,
    //抓取数量
    'GRAB_NUMS'=>1,

    /*****************cas登录配置******************************/
    'phpcas_path' => '.',
    // Full Hostname of your CAS Server
    'cas_host'		=> '10.150.20.142',//请求登录地址       #cas_host
    'webserviceUrl' => 'http://10.150.20.142:8080/SMP/webservice/smpWS?wsdl',//请求登录地址    #webserviceUrl
    // Context of the CAS Server
    'cas_context'	=> '/cas',
    // Port of your CAS server. Normally for a https server it's 443
    'cas_port'		=> 8080,
    // Path to the ca chain that issued the cas server certificate
    'cas_server_ca_cert_path' => '/path/to/cachain.pem',
    //项目配置
    'desKey' => "NASDAQ恒昌－互联网金融－核心系统",
    'desMark' => "NASDAQ",
    /*****************cas登录配置******************************/
);
