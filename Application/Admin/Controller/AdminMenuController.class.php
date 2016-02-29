<?php
namespace Admin\Controller;
use Admin\Controller\AdminController;

class AdminMenuController extends AdminController
{
	public $data = array(
			'status'     => 1,
			'pid' 	     => 0,
			'title'      => '',
			'controller' => '',
			'icon'       => '',
			'condition'  => '',
			'sort'       => 1,
			);

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
		$AdminMenu = M('AdminMenu');
		$menus_data = $AdminMenu->where(array('pid'=>0))->order('sort')->select();
		$this->assign('data',$this->data);
		$this->assign('menus_data',$menus_data);
		$this->display();
	}

	public function store(){
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
		$menus_data = $AdminMenu->where(array('pid'=>0))->order('sort')->select();

		if (!$AdminMenu->create($data)){
		    // 如果创建失败 表示验证没有通过 输出错误提示信息
		    $errors[] = $AdminMenu -> getError();
		    $this->assign('data',$data);
		    $this->assign('errors',$errors);
		    $this->assign('menus_data',$menus_data);
		    $this->display('create');

		}else{
		    // 验证通过 可以进行其他数据操作
		    if ($res = $AdminMenu->add($data)) {
		    	$info = $data['title'].'|'.$data['controller'].' 添加成功!';
		    	$this->assign('success',$info);
		    	$this->assign('menus_data',$menus_data);
			    $this->display('create');
		    }else{
		    	$errors[] = '添加到数据库失败!';
			    $this->assign('data',$data);
			    $this->assign('errors',$errors);
			    $this->assign('menus_data',$menus_data);
			    $this->display('create');
		    }
		}
	}

	public function edit(){
		$id = I('get.id');
		$AdminMenu = M('AdminMenu');
		$data = $AdminMenu->where(array('id'=>$id))->find();
		$menus_data = $AdminMenu->where(array('pid'=>0))->order('sort')->select();
		$this->assign('data',$data);
		$this->assign('menus_data',$menus_data);
		$this->display();
	}

	public function update(){
		$id = I('post.id');
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
		$data['id'] = $id;
		$AdminMenu = D("AdminMenu");
		$menus_data = $AdminMenu->where(array('pid'=>0))->order('sort')->select();
		$count =  $AdminMenu->where(array('controller'=>$data['controller']))->getField('id');

		if ($count && $count !== $id){
		    // 如果创建失败 表示验证没有通过 输出错误提示信息
		    $errors[] = '控制器'.$data['controller'].'已经存在!';
		    $this->assign('data',$data);
		    $this->assign('errors',$errors);
		    $this->assign('menus_data',$menus_data);
		    $this->display('edit');

		}else{
		    // 验证通过 可以进行其他数据操作
		    if ($res = $AdminMenu->save($data)) {
		    	$info = $data['title'].'|'.$data['controller'].' 更新成功!';
		    	$this->assign('success',$info);
		    	$this->assign('menus_data',$menus_data);
			    $this->redirect('index');
		    }else{
		    	$errors[] = '添加到数据库失败!';
			    $this->assign('data',$data);
			    $this->assign('errors',$errors);
			    $this->assign('menus_data',$menus_data);
			    $this->display('edit');
		    }
		}
	}

	public function delete(){
		$id =  I('get.id');
		$AdminMenu = M('AdminMenu');
		$result = $AdminMenu->where(array('id'=>$id))->delete();
		if ($result){
			$this->redirect('index');
		}
	}
}

?>