<?php
namespace Admin\Controller;
use Admin\Controller\AdminController;

class AdminMenuController extends AdminController
{
	public function index(){
		$AdminMenu = M('AdminMenu');
		$menus_data = $AdminMenu->where(array('pid'=>0))->order('sort')->select();
		foreach ($menus_data as $key => &$value) {
			$value['menus_list'] = $AdminMenu->where(array('pid'=>$value['id']))->order('sort')->select();
		}
		$this->assign('menus_data',$menus_data);
		$this->display();
	}

	public function create(){
		$data = array(
			'controller' => I('post.controller'),
			'title' => I('post.title'),
			'status' => I('post.status'),
			'icon' => I('post.icon'),
			'condition' => I('post.conditions',''),
			'pid' => I('post.pid',0,'int'),
			'sort' => I('post.sort',1,'int'),
			'createtime' => time(),
			);
		$AdminMenu = D("AdminMenu");
		if (!$AdminMenu->create($data)){
		    // 如果创建失败 表示验证没有通过 输出错误提示信息
		    $this->error($AdminMenu->getError());
		}else{
		    // 验证通过 可以进行其他数据操作
		    if ($res = $AdminMenu->add($data)) {
		    	$this->success($data['title'].'|'.$data['controller'].' 添加成功!');
		    }else{
		    	 $this->error('添加到数据库失败!');
		    }
		}
	}
}

?>