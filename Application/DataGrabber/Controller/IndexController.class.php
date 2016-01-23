<?php
namespace DataGrabber\Controller;
use Think\Controller;

class IndexController extends Controller
{
	public function index(){
		$HtmlDownloader = new HtmlDownloaderController();
		$HtmlParser = new HtmlParserController();
		$url = 'http://www.smzdm.com/youhui/';
		$page = $HtmlDownloader->getPage($url);
		$data = $HtmlParser->parse($page);
	}

}

?>