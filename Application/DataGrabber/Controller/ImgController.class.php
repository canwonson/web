<?php
namespace DataGrabber\Controller;
use Think\Controller;
use QL\QueryList;

class ImgController extends Controller
{
	/*public function index(){
		$num = I('post.num',10);
		$p = I('post.p',1);
		$blog = I('post.blog');
		$size = I('post.size');
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
	}*/

	public function index(){
		$num = I('post.num',20);
		$p = I('post.p',1);
		$blog = I('post.blog');
		$size = I('post.size',5);
		$big_size = I('post.big_size',3);
		$start = ($p - 1)*$num;
		$api = 'fuiKNFp9vQFvjLNvx4sUwti4Yb5yGutBN4Xh10LXZhhRKjWlV4';
		$url = 'http://api.tumblr.com/v2/blog/'.$blog.'.tumblr.com/posts/photo?offset='.$start.'&api_key='.$api.'&limit='.$num;
		$Curl = new \Lib\Net\Curl();
		$Curl->url = $url;
		$result = $Curl->exec();
		$result = json_decode($result,true);

		$total = $result['response']['total_posts'];
		foreach ($result['response']['posts'] as $key => $value) {
			$list[$key]['slug'] = $value['slug'];
			$list[$key]['time'] = $value['timestamp'];
			$list[$key]['summary'] = $value['summary'];
			foreach ($value['photos'] as $k => $photo) {
				foreach ($photo["alt_sizes"] as $kk => $img) {
					$list[$key]['photos'][$k]['img'][$kk] = $img['url'];
				}
			}
		}
		$this->assign('list',$list);
		$this->assign('blog',$blog);
		$this->assign('size',$size);
		$this->assign('total_p',ceil($total / $num));
		$this->assign('total',$total);
		$this->assign('big_size',$big_size);
		$this->assign('p',$p);
		$this->assign('num',$num);
		$this->display();
	}
}

?>