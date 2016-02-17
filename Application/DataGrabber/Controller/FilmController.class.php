<?php
namespace DataGrabber\Controller;
use Think\Controller;
use QL\QueryList;

class FilmController extends Controller
{

	public function index(){
		@set_time_limit(0);
		$store_url_table = 'film_url';
		$rule = '.ulink';
		$attr = 'href';
		$function = function($content){
		    //利用回调函数补全相对链接
		    $baseUrl = 'http://www.ygdy8.net';
		    return $baseUrl.$content;
		};

		$rules = array(
			'title'    => array('.title_all>h1','text'),
			'url'      => array('.co_content8>table>tbody>tr>td','text'),
			);

		$Obj_table = M($store_table);
		$UrlManager = new UrlManagerController();
		$HtmlParser = new HtmlParserController();
		$crawl_urls = $this->getCrawlUrls();

		foreach ($crawl_urls as $source => $url) {
			$list = $UrlManager->getUrls1($url,$rule,$attr,$function);
			//$list = $UrlManager->UrlStore($urls,$store_url_table);
			foreach ($list as $url) {
				$data = $HtmlParser->parse($url,$source,$rules);
				dump($data);
				/*$result = $this->dataStore($data,$source);
				if($result){
					$where['url'] = $url;
					$set['status'] = 1;
					$GoodUrl->where($where)->save($set);
				}*/
			}
		}
	}

	public function getCrawlUrls(){
		return array('http://www.ygdy8.net/html/gndy/dyzz/index.html');
	}
}

?>