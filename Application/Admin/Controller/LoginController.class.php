<?php
namespace Admin\Controller;
use Think\Controller;

class LoginController extends Controller
{
    public function index(){
    	$this->display();
    }

    public function login(){
    	$login_info = array(
    		'account'=>I('post.account'),
			'password'=>I('post.password'),
    		);

    	if (empty($login_info['account'])) {
    		$this->error('登陆账号不能为空');
    	}elseif(empty($login_info['password'])) {
    		$this->error('登陆密码不能为空');
    	}
    	$account_info=$this->verify($login_info);
    	if(isset($account_info['status']) && $account_info['status']==0){
			$this->error($account_info['info']);
		}elseif(is_array($account_info) && !empty($account_info['role_id'])){
			//登陆成功
			session('userID',$account_info['id']);
			session('roleID',$account_info['role_id']);
			session('name',$account_info['name']);
			cookie('account',$login_info['account']);
			$record=array(
			'account_id'=>$account_info['id'],
			'login_time'=>time(),
			'login_ip'=>get_client_ip(),
			'login_type'=>$action=='code'?2:1,
			);
			M('admin_login_log')->data($record)->add();
			$this->success('登陆成功');
		}else{
			$this->error('登陆失败');
		}
    }

    public function verify($login_info){
    	$password = $login_info['password'];
    	$AdminUser = M('admin_user');
    	$field="id,account,password,name,login_count,lock_time,error_count,role_id";
    	$account_info=$AdminUser->field($field)->where("status=1 and (account='{$login_info['account']}')")->find();
    	if(empty($account_info)){
			return false;
		}
		$account=$account_info['account'];
		if(empty($account_info['role_id'])){
			return array('status'=>0,'info'=>'账户未分配角色,请联系管理员');
		}elseif(($account_info['lock_time']-time())>0){
			return array('status'=>0,'info'=>'账户被锁');
		}elseif(isset($password)&&md5($password)!==$account_info['password']){
			if(($error_count=5-$account_info['error_count']%6)>0){
				$AdminUser->where("id={$account_info['id']}")->setInc('error_count');
				return array('status'=>0,'info'=>'密码错误，剩余次数'.$error_count);
			}else{
				$AdminUser->where("id={$account_info['id']}")->save(array('lock_time'=>time()+1800));
				return array('status'=>0,'info'=>'账户锁定');
			}
		}
		//登陆成功
		$data_update=array(
			'login_count'=>$account_info['login_count']+1,
			'last_login_time'=>time(),
			'last_login_ip'=>get_client_ip(),
			'error_count'=>0,
			);
		$AdminUser->where("id={$account_info['id']}")->save($data_update);
		return $account_info;
    }

    //登出
	public function logout(){
		$account=cookie('account');
		session(null);
		cookie(null);
		cookie('account',$account);
		redirect(__MODULE__.'/login');
	}
}

?>