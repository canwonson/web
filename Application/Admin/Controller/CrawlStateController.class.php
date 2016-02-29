<?php
namespace Admin\Controller;
use QL\QueryList;
use Admin\Controller\AdminController;

class CrawlStateController extends AdminController
{

	public function index()
	{
		$output = system("ping 127.0.0.1",$output);
		dump($output);die;
    	$this->assign('output',$output);
		$this->display();
	}

	public function create(){
		$path = I('post.path');
		$CrawlMission = M('crawl_mission');
		$mission_list = $CrawlMission->field('deploy_id,interval')->where(array('status'=>1))->order('createtime desc')->select();
		foreach ($mission_list as $mission) {
			$str .= $mission['interval'].' index.php /DataGrabber?id='.$mission['deploy_id'];
			$str .= "\n";
		}
		$data['status']  = 1;
		$data['content'] = $str;
		$this->ajaxReturn($data);
	}

}

?>