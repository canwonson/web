<?php
namespace Admin\Controller;
use Think\Controller;

class AdminController extends Controller
{
	protected function _initialize(){
		if(empty(session('userID'))){
			$this->redirect('Login/index');
		}
		$this->assign('__MENU__', $this->getMenus());
	}

	protected function getMenus(){
		$controller = CONTROLLER_NAME;
		$roleID = session('roleID');
		$AdminMenu = M('admin_menu');
		$field = 'controller,title,icon,id';
		$menus_data = $AdminMenu->field($field)->where(array('pid'=>0))->order('sort')->select();
		foreach ($menus_data as $key => &$value) {
			$value['menus_list'] = $AdminMenu->field($field)->where(array('pid'=>$value['id']))->order('sort')->select();
		}
		if ($controller) {
			foreach ($menus_data as &$value) {
				foreach ($value['menus_list'] as &$menu) {
					if ($menu['controller'] == $controller){
						$menu['active'] = 1;
						$value['active'] = 1;
					}
				}
			}
		}
		return $menus_data;
	}
}
?>