<?php

ob_start(); //打开输出控制缓冲
$config = require_once '../Conf/config.php';
require_once 'cas/CAS.php';
require_once 'cas/des.php';

$crypt = new DES1($config['desKey']);

if(isset($_SESSION['uname']) && $_SESSION['uname']){

}else{
    phpCAS::client(CAS_VERSION_2_0, $config['cas_host'], $config['cas_port'], $config['cas_context']);
    phpCAS::setNoCasServerValidation();
    phpCAS::forceAuthentication(); // force CAS authentication
    if(isset($_REQUEST['logout'])){
        $log = $_SESSION['uname'];
        unset($_SESSION['phpCAS']);
        session_destory();
        phpCAS::logout();
    }else{
        if(phpCAS::checkAuthentication()){
            if(phpCAS::checkAuthentication()){
                $mname = phpCAS::getUser();
                var_dump($mname);
            }
        }else{
            echo 2;
        }
    }
}