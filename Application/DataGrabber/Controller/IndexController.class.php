<?php
namespace DataGrabber\Controller;
use Think\Controller;
use QL\QueryList;

class IndexController extends Controller
{
	public $crawl_url = '';
	public $crawl_param = '';

	public function crawl(){
		@set_time_limit(0);
		/*$store_url_table = 'film_url';
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

		$Obj_table = M($store_table);*/
		$url = $this->$crawl_url;
		$rule = $this->$crawl_param;
		dump($url);
		$UrlManager = new UrlManagerController();
		$HtmlParser = new HtmlParserController();
		$data = $HtmlParser->parse($url,$rules);
		return $data;
		//$list = $UrlManager->getUrls1($url,$rule,$attr,$function);

	}

	public function getCrawlUrls(){
		return array('http://www.ygdy8.net/html/gndy/dyzz/index.html');
	}
}

?>