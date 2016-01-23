<?php
namespace DataGrabber\Controller;
use Think\Controller;

class HtmlDownloaderController extends Controller
{
	public function getPage($url){
		$Curl = new \Lib\Net\Curl();
		$Curl->url = $url;
		$result = $Curl->exec();
		return $result;
	}

}

?>