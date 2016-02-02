<?php
namespace DataGrabber\Controller;
use Think\Controller;
use QL\QueryList;

class ImgController extends Controller
{
	public function index(){
		$num = I('get.num',10);
		$p = I('get.p',1);
		$blog = I('get.blog');
		$size = I('get.size');
		$start = ($p - 1)*$num;
		$url = 'http://'.$blog.'.tumblr.com/api/read?type=photo&num='.$num.'&start='.$start;
		dump($url);
		$rules = array(
			'img_1280'  => array('post>photoset>photo>photo-url[max-width="1280"]','text'),
			'img_500'  => array('post>photoset>photo>photo-url[max-width="500"]','text'),
			'img_250'  => array('post>photoset>photo>photo-url[max-width="250"]','text'),
			'img_100'  => array('post>photoset>photo>photo-url[max-width="100"]','text')
			);
		$data = QueryList::Query($url,$rules)->data;
		foreach ($data as $key => $value) {
			$img_size = 'img_'.$size;
			$list[] = $value[$img_size];
		}
		$img_size = 'img_'.$size;
		$this->assign('list',$list);
		$this->assign('blog',$blog);
		$this->assign('size',$size);
		$p = $p+1;
		$this->assign('p',$p);
		$this->assign('num',$num);
		$this->display();
	}
}

?>