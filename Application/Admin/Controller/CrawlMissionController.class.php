<?php
namespace Admin\Controller;
use QL\QueryList;
use Admin\Controller\AdminController;

class CrawlMissionController extends AdminController
{
	public $data = array(
			'mission_name'=> '',
			'interval'=> '* * * * *',
			'sort' => 1,
			);

	public function index()
	{
		$CrawlMission = M('crawl_mission');
		$data = $CrawlMission->order('sort')->select();
		$CrawlDeploy = M('crawl_deploy');
		foreach ($data as $key => &$value) {
			$value['deploy'] = $CrawlDeploy->where(array('id'=>$value['deploy_id']))->getField('title');
		}
		$this->assign('data',$data);
		$this->display();
	}

	public function create(){
		$CrawlDeploy = M('crawl_deploy');
		$deploy_list = $CrawlDeploy->order('createtime desc')->select();
		$this->assign('deploy_list',$deploy_list);
		$this->assign('data',$this->data);
		$this->display();
	}

	public function store(){
		$data = array(
			'mission_name' => I('post.mission_name'),
			'interval' => I('post.interval'),
			'deploy_id'=> I('post.deploy_id'),
			'status' => 0,
			'sort' => I('post.sort'),
			'createtime' => time(),
			);
		$data['admin_id']=session('userID');
		$CrawlMission = M('crawl_mission');
		$CrawlDeploy = M('crawl_deploy');
		$deploy_list = $CrawlDeploy->order('createtime desc')->select();
		if (!$data['mission_name'] || !$data['interval']){
		    // 如果创建失败 表示验证没有通过 输出错误提示信息
		    $errors[] = '任务名称或间隔配置不能为空!';
		    $this->assign('data',$data);
		    $this->assign('errors',$errors);
		    $this->assign('deploy_list',$deploy_list);
		    $this->display('create');

		}else{
		    // 验证通过 可以进行其他数据操作
	    	if ($res = $CrawlMission->add($data)) {
		    	$info = $data['mission_name'].'|'.$data['interval'].' 添加成功!';
		    	$this->assign('success',$info);
			    $this->redirect('index');
		    }else{
		    	$errors[] = '添加到数据库失败!';
			    $this->assign('data',$data);
			    $this->assign('errors',$errors);
			    $this->assign('deploy_list',$deploy_list);
			    $this->display('create');
		    }
		}
	}

	public function edit(){
		$id = I('get.id');
		$CrawlMission = M('crawl_mission');
		$CrawlDeploy = M('crawl_deploy');
		$deploy_list = $CrawlDeploy->order('createtime desc')->select();
		$data = $CrawlMission->where(array('id'=>$id))->find();
		$this->assign('deploy_list',$deploy_list);
		$this->assign('data',$data);
		$this->display();
	}

	public function update(){
		$id = I('post.id');
		$data = array(
			'mission_name' => I('post.mission_name'),
			'interval' => I('post.interval'),
			'deploy_id'=> I('post.deploy_id'),
			'sort' => I('post.sort'),
			'createtime' => time(),
			);
		$data['admin_id']=session('userID');
		$data['id'] = $id;
		$CrawlDeploy = D('crawl_deploy');
		$CrawlMission = M('crawl_mission');
		if (!$data['mission_name'] || !$data['interval']){
		    // 如果创建失败 表示验证没有通过 输出错误提示信息
		    $errors[] = $CrawlMission -> getError();
		    $this->assign('data',$data);
		    $this->assign('errors',$errors);
		    $this->display('create');

		}else{
		    // 验证通过 可以进行其他数据操作
		    if ($res = $CrawlMission->save($data)) {
		    	$info = $data['mission_name'].'|'.$data['interval'].' 更改成功!';
		    	$this->assign('success',$info);
			    $this->redirect('index');
		    }else{
		    	$errors[] = '添加到数据库失败!';
			    $this->assign('data',$data);
			    $this->assign('errors',$errors);
			    $this->display('create');
		    }
		}
	}

	public function delete(){
		$id =  I('get.id');
		$CrawlMission = M('crawl_mission');
		$result = $CrawlMission->where(array('id'=>$id))->delete();
		if ($result){
			$this->redirect('index');
		}
	}

	public function start(){
		$id =  I('get.id');
		$CrawlMission = M('crawl_mission');
		$data['status'] = 1;
		$data['id'] = $id;
		$result = $CrawlMission->save($data);
		if ($result){
			$this->redirect('index');
		}
	}

	public function stop(){
		$id =  I('get.id');
		$CrawlMission = M('crawl_mission');
		$data['status'] = 0;
		$data['id'] = $id;
		$result = $CrawlMission->save($data);
		if ($result){
			$this->redirect('index');
		}
	}


}

?>