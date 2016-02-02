<?php
namespace DataGrabber\Controller;
use Think\Controller;
use QL\QueryList;

class ImgController extends Controller
{
	public function index(){
		$num = I('get.n',10);
		$p = I('get.p',1);
		$blog = I('get.b');
		$size = I('get.s');
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
			echo '<img src="'.$value[$img_size].'">';
			echo '<br/>';
		}
	}
}

?>