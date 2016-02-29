<?php
namespace DataGrabber\Controller;
use QL\QueryList;

class HtmlParserController
{
	public $url = null;
	public $param = null;

	public function parse(){
		$result = QueryList::run('Request',[
		        'target' => $this->url,
		        'method' => 'GET',
		        'user_agent'=>'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.8; rv:21.0) Gecko/20100101 Firefox/21.0',
		        'timeout' =>'30'
		    ])->setQuery($this->param['rules'])->getData();
		return $result;
	}
}

?>