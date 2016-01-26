<?php
namespace DataGrabber\Controller;
use Think\Controller;
use QL\QueryList;

class UrlManagerController extends Controller
{
	public function getPage($url){
		$Curl = new \Lib\Net\Curl();
		$Curl->url = $url;
		$result = $Curl->exec();
		return $result;
	}

	public function getUrls1($url){
		//HTTP操作扩展
		$urls = QueryList::run('Request',[
		        'target' => $url,
		        'method' => 'GET',
		        'user_agent'=>'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.8; rv:21.0) Gecko/20100101 Firefox/21.0',
		        'timeout' =>'30'
		    ])->setQuery(['link' => ['.ulink','href','',function($content){
		    //利用回调函数补全相对链接
		    $baseUrl = 'http://www.ygdy8.net';
		    return $baseUrl.$content;
		}]])->getData(function($item){
		    return $item['link'];
		});
		return $urls;
	}

	public function getUrls2($url){
		//HTTP操作扩展
		$urls = QueryList::run('Request',[
		        'target' => $url,
		        'method' => 'GET',
		        'user_agent'=>'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.8; rv:21.0) Gecko/20100101 Firefox/21.0',
		        'timeout' =>'30'
		    ])->setQuery(['link' => ['.listTitle>h2>a','href']])->getData(function($item){
		    return $item['link'];
		});
		return $urls;
	}

	public function UrlStore($urls){
		$GoodUrl = M('good_url');
		foreach ($urls as $url) {
			$map['url'] = $url;
			$count = $GoodUrl->where($map)->count();
			if(!$count){
				$data['url'] = $url;
				$data['add_time'] = time();
				$result = $GoodUrl->add($data);
				if($result){
					$list[] = $url;
				}else{
					$this->error('写入数据库失败');
				}
 			}
		}
		return $list;
	}

}

?>