<?php
/**
 * 用户模块
 * 1，检查登录
 * 2，新用户注册
 */
class UsersModel extends AdminModel{
    //检查登陆
    public function loginCheck(){
        $session = S(str_ed_crypt($_REQUEST['sid'], false, 'upload'));
        if(empty($session)){
            if(empty($_SESSION['uname'])){
                $server = getallheaders();
                var_dump($server);exit;
                if('ajax-request'==$server['Request-Type']){
                    die(json_encode(array('status'=>'301', 'mess'=>'请重新登陆')));
                }
                header("Location: /casIndex.php");
            }else{
                header("Location:index.html");
                die;
            }
        }
    }
}
