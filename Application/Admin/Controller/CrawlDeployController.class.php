<?php
namespace Admin\Controller;
use QL\QueryList;
use Admin\Controller\AdminController;

class CrawlDeployController extends AdminController
{
	public $data = array(
			'status'     => 1,
			'title'      => '',
			'url'  		 => 'http://',
			'crawl_param'=> '{"rules":{"":["",""]}}',
			'table'      => 'crawl_',
			);

	public function index()
	{
		$CrawlDeploy = M('crawl_deploy');
		$data = $CrawlDeploy->order('createtime desc')->select();
		$this->assign('data',$data);
		$this->display();
	}

	public function create(){
		$this->assign('data',$this->data);
		$this->display();
	}

	public function store(){
		$data = array(
			'status' => I('post.status'),
			'title' => I('post.title'),
			'url' => I('post.url'),
			'crawl_param' => I('post.crawl_param'),
			'table' => I('post.table'),
			'createtime' => time(),
			);
		$data['crawl_param']=str_replace('&gt;', '>',$data['crawl_param']);
		$data['crawl_param']=str_replace('&quot;', '"',$data['crawl_param']);
		$data['admin_id']=session('userID');
		$CrawlDeploy = D('crawl_deploy');
		if (!$CrawlDeploy->create($data)){
		    // 如果创建失败 表示验证没有通过 输出错误提示信息
		    $errors[] = $CrawlDeploy->getError();
		    $this->assign('data',$data);
		    $this->assign('errors',$errors);
		    $this->display('create');

		}else{
		    // 验证通过 可以进行其他数据操作
	    	if ($res = $CrawlDeploy->add($data)) {
		    	$info = $data['title'].'|'.$data['url'].' 添加成功!';
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

	public function edit(){
		$id = I('get.id');
		$CrawlDeploy = M('crawl_deploy');
		$data =$CrawlDeploy->where(array('id'=>$id))->find();
		$this->assign('data',$data);
		$this->display();
	}

	public function update(){
		$id = I('post.id');
		$data = array(
			'status' => I('post.status'),
			'title' => I('post.title'),
			'url' => I('post.url'),
			'crawl_param' => I('post.crawl_param'),
			'createtime' => time(),
			);
		$data['crawl_param']=str_replace('&gt;', '>',$data['crawl_param']);
		$data['crawl_param']=str_replace('&quot;', '"',$data['crawl_param']);
		$data['admin_id']=session('userID');
		$data['id'] = $id;
		$CrawlDeploy = D('crawl_deploy');

		if (!$CrawlDeploy->create($data)){
		    // 如果创建失败 表示验证没有通过 输出错误提示信息
		    $errors[] = $CrawlDeploy -> getError();
		    $this->assign('data',$data);
		    $this->assign('errors',$errors);
		    $this->display('create');

		}else{
		    // 验证通过 可以进行其他数据操作
		    if ($res = $CrawlDeploy->save($data)) {
		    	$info = $data['title'].'|'.$data['url'].' 更改成功!';
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
		$CrawlDeploy = M('crawl_deploy');
		$result = $CrawlDeploy->where(array('id'=>$id))->delete();
		if ($result){
			$this->redirect('index');
		}
	}

	public function test(){
		@set_time_limit(0);
		$id = I('get.id');
		$CrawlDeploy = M('crawl_deploy');
		$data =$CrawlDeploy->where(array('id'=>$id))->find();
		$crawl_param = json_decode($data['crawl_param'],true);
		$DataGrabber = new \DataGrabber\Controller\HtmlParserController();
		$DataGrabber->url = $data['url'];
		$DataGrabber->param = $crawl_param;
		$result = $DataGrabber->parse();
		dump($result);
	}

}

?>