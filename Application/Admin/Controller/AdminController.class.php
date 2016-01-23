<?php
namespace Admin\Controller;
use Think\Controller;

class AdminController extends Controller
{
	protected function _initialize(){
		if(empty(session('user_auth'))){
			$this->redirect('User/index');
		}
		$this->assign('__MENU__', $this->getMenus());
	}

	final public function getMenus($controller=CONTROLLER_NAME){
		$AdminMenu=M('admin_menu');
		$menus = $AdminMenu->where(array('pid'=>0))->order('sort')->select();
		foreach ($menus as &$module) {
			$module['menus_list'] = $AdminMenu->where(array('pid'=>$module['id']))->order('sort')->select();
			$count = $AdminMenu->where(array('pid'=>$module['id'],'controller'=>$controller))->count();
			if ($count) {
				$module['active']=1;
			}
		}
		return $menus;
	}

	/**
     * 权限检测
     * @param string  $rule    检测的规则
     * @param string  $mode    check模式
     * @return boolean
     */
	final public function checkRule($rule, $type=AuthRuleModel::RULE_URL, $mode='url'){
		static $Auth = null;
        if (!$Auth) {
            $Auth = new \Think\Auth();
        }
        if(!$Auth->check($rule,UID,$type,$mode)){
            return false;
        }
        return true;
	}

	final public function checkLogin(){
		$user = session('userID');
		if(!$user){
			return 0;
		}else{
			return session('userID');
		}

		$user = session('user_auth');
        if (empty($user)) {
            return 0;
        } else {
            return session('user_auth_sign') == data_auth_sign($user) ? $user['uid'] : 0;
        }
	}
}
?>