<?php
namespace DataGrabber\Controller;
use Think\Controller;
use QL\QueryList;

class OddController extends Controller
{
	public function index(){
		$url = 'http://sb.uedwin.com/zh-cn/OddsService/GetOdds?_=1455613746215&sportId=1&programmeId=0&pageType=1&uiBetType=am&displayView=2&oddsType=2&sortBy=1&isFirstLoad=true&MoreBetEvent=null';
		/*$UrlManager = new UrlManagerController();
		$html = $UrlManager->getPage($url);
		echo($html);*/
		$data = QueryList::run('Request',[
	        'target' => $url,
	        'method' => 'GET',
	        //'params' => ['var1' => 'testvalue', 'var2' => 'somevalue'],
	        'user_agent'=>'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.8; rv:21.0) Gecko/20100101 Firefox/21.0',
	        //'cookiePath' => './cookie.txt',
	        'timeout' =>'30'
	    ])->getHtml($rel = false);
		$data = json_decode($data,true);
		dump($data);
	}
}

?>