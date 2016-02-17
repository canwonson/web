<?php
namespace DataGrabber\Controller;
use Think\Controller;
use QL\QueryList;

class ImgController extends Controller
{

	public function index(){
		$num = I('post.num',20);
		$p = I('post.p',1);
		$blog = I('post.blog');
		$size = I('post.size',5);
		$big_size = I('post.big_size',0);
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
		dump($list);
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

	public function index1(){
		$num = I('post.num',20);
		$p = I('post.p',1);
		$size = I('post.size',5);
		$big_size = I('post.big_size',0);
		$blog = I('post.blog');
		$start = ($p - 1)*$num;
		$Img = M('img');
		$blog && $where['blog']=$blog;
		$total = $Img->where($where)->count();
		$result = $Img->where($where)->limit($start,$num)->select();
		foreach ($result as $key => &$value) {
			$photos = json_decode($value['photos'],true);
			$list=array();
			foreach ($photos as $k => $photo) {
				foreach ($photo["alt_sizes"] as $kk => $img) {
					$list[$k]['img'][$kk] = $img['url'];
				}
			}
			unset($value['photos']);
			$value['photos']=$list;
		}
		$this->assign('list',$result);
		$this->assign('blog',$blog);
		$this->assign('size',$size);
		$this->assign('total_p',ceil($total / $num));
		$this->assign('total',$total);
		$this->assign('big_size',$big_size);
		$this->assign('p',$p);
		$this->assign('num',$num);
		$this->display();
	}

	public function store(){
		$blog = I('get.b','wordsnquotes');
		$ImgPage = M('img_page');
		$s = I('get.s');
		$p = $ImgPage->where(array('name'=>$blog))->max('page');
		if (!$p) {
			$page_info['name'] = $blog;
			$page_info['page'] = 1;
			$page_info['time'] = time();
			$ImgPage->add($page_info);
		}else{
			$page_info['page'] = $p+1;
			$page_info['time'] = time();
			$ImgPage->where(array('name'=>$blog))->save($page_info);
		}
		$p = $p ? $p+1 : 1;
		if ($s) {
			$p = $s+$p;
		}
		$start = ($p - 1)*20;
		$api = 'fuiKNFp9vQFvjLNvx4sUwti4Yb5yGutBN4Xh10LXZhhRKjWlV4';
		$url = 'http://api.tumblr.com/v2/blog/'.$blog.'.tumblr.com/posts/photo?offset='.$start.'&api_key='.$api.'&limit=20';
		$Curl = new \Lib\Net\Curl();
		$Curl->url = $url;
		$result = $Curl->exec();
		$result = json_decode($result,true);
		$total = $result['response']['total_posts'];
		if(!$result['response']['posts']){
			exit;
		}


		foreach ($result['response']['posts'] as $key => $value) {
			$data['slug'] = $value['slug'];
			$data['time'] = $value['timestamp'];
			$data['summary'] = $value['summary'];
			$data['blog'] = $blog;
			$data['photos'] = json_encode($value['photos']);
			$map['slug'] = $value['slug'];
			$map['time'] = $value['timestamp'];
			$map['blog'] = $blog;
			$Img = M('img');
			$count = $Img->where($map)->count();
			if (!$count){
				$Img->add($data);
			}else{
				$page_info['page'] = 1;
				$page_info['time'] = time();
				$ImgPage->where(array('name'=>$blog))->save($page_info);
			}
		}
	}

}

?>