<?php
namespace DataGrabber\Controller;
use Think\Controller;

class IndexController extends Controller
{
	public function index(){
		@set_time_limit(0);
		$store_table = 'good_url';

		$rules = array(
			'good_name'    => array('.article_title ','text'),
			'good_price'   => array('.article_title >span','text'),
			'good_img'     => array('.pic-box>img','src'),
			'shop_name'    => array('.article-meta-box>.article_meta:eq(1)>span:eq(0)>a','text'),
			'good_buy_url' => array('.buy>a','href'),
			'good_intr1'    => array('.item-box:eq(0)>.inner-block>p','text'),
			'good_intr2'    => array('.item-box:eq(1)>.inner-block>p','text'),
			);

		$Obj_table = M($store_table);
		$UrlManager = new UrlManagerController();
		$HtmlParser = new HtmlParserController();
		$crawl_urls = $this->getCrawlUrls();
		foreach ($crawl_urls as $source => $url) {
			$urls = $UrlManager->getUrls2($url);
			$list = $UrlManager->UrlStore($urls,$store_table);
			foreach ($list as $url) {
				$data = $HtmlParser->parse($url,$source,$rules);
				$result = $this->dataStore($data,$source);
				if($result){
					$where['url'] = $url;
					$set['status'] = 1;
					$Obj_table->where($where)->save($set);
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