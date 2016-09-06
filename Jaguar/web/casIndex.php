<?php

    ob_start(); //打开输出控制缓冲
    $config = require_once '../Conf/config.php';
    require_once 'cas/CAS.php';
    require_once 'cas/des.php';

    $crypt = new DES1($config['desKey']);