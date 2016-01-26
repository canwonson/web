<?php
namespace DataGrabber\Controller;
use Think\Controller;
use QL\QueryList;

class HtmlParserController extends Controller
{
	public function parse($url,$source){
		$rules = $this->rule($source);
		$data = QueryList::Query($url,$rules)->data;
		return $data;
	}

	public function rule($source){
		switch ($source) {
			case '1':
				$rules = array(
					'good_name'    => array('.article_title ','text'),
					'good_price'   => array('.article_title >span','text'),
					'good_img'     => array('.pic-box>img','src'),
					'shop_name'    => array('.article-meta-box>.article_meta:eq(1)>span:eq(0)>a','text'),
					'good_buy_url' => array('.buy>a','href'),
					'good_intr1'    => array('.item-box:eq(0)>.inner-block>p','text'),
					'good_intr2'    => array('.item-box:eq(1)>.inner-block>p','text'),
					);
				break;
			default:
				# code...
				break;
		}
		return $rules;
	}

}

?>