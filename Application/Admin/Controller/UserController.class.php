<?php
namespace Admin\Controller;
use Think\Controller;
use User\Api\UserApi;

class UserController extends Controller{
	//登入页面
    public function index(){
		$this->display();
    }

    public function login($username = null, $password = null){
    	if(IS_POST){
    		//调用UserAPi
    		$User = new UserApi;
    		$uid = $User->login($username, $password);
    		if(0 < $uid){
    			$UserInfo = new \User\Model\UserInfoModel();
    			if($UserInfo->login($uid)){ //登录用户
                    //TODO:跳转到登录前页面
                    $data=array(
                        
                        );
                    $this->ajaxReturn();
                } else {
                    $this->error($UserInfo->getError());
                }
    		}else{//登陆失败
    			switch($uid) {
                    case -1: $error = '用户不存在或被禁用！'; break; //系统级别禁用
                    case -2: $error = '密码错误！'; break;
                    default: $error = '未知错误！'; break; // 0-接口参数错误（调试阶段使用）
                }
                $this->error($error);
    		}
    	}
    }
}
?>