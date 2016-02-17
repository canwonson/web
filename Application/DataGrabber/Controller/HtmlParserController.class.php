<?php
namespace DataGrabber\Controller;
use Think\Controller;
use QL\QueryList;

class HtmlParserController extends Controller
{
	public function parse($url,$source,$rules){
		$data = QueryList::Query($url,$rules)->data;
		return $data;
	}
}

?>