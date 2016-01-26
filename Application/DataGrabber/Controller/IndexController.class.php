<?php
namespace DataGrabber\Controller;
use Think\Controller;

class IndexController extends Controller
{
	public function index(){
		@set_time_limit(0);
		$GoodUrl = M('good_url');
		$UrlManager = new UrlManagerController();
		$HtmlParser = new HtmlParserController();
		$crawl_urls = $this->getCrawlUrls();
		foreach ($crawl_urls as $source => $url) {
			$urls = $UrlManager->getUrls2($url);
			$list = $UrlManager->UrlStore($urls);
			foreach ($list as $url) {
				$data = $HtmlParser->parse($url,$source);
				$result = $this->dataStore($data,$source);
				if($result){
					$where['url'] = $url;
					$set['status'] = 1;
					$GoodUrl->where($where)->save($set);
				}
			}
		}
	}

	public function dataStore($data,$source){
		$Good = M('good');
		$map['good_name'] = $data['good_name'];
		$count = $Good->where($map)->count();
		if(!$count){
			$data[0]['add_time'] = time();
			$data[0]['source'] = $source;
			$result = $Good->add($data[0]);
			if(!$result){
				$this->error('写入数据库失败'.$data['good_name']);
			}else{
				return true;
			}
		}else{
			$this->error('数据库中已存在此商品');
		}
	}

	public function getCrawlUrls(){
		$CrawlUrl = M('crawl_url');
		$urls = $CrawlUrl->getField('id,url',true);
		return $urls;
	}
}

?>