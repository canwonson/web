<?php
namespace DataGrabber\Controller;
use Think\Controller;
use QL\QueryList;

class HtmlParserController extends Controller
{
	public function parse($page){
		$rule = array(
			'good_url'   => array('.picLeft','href'),
			'good_name'  => array('.listTitle>h2>a','text'),
			'good_price' => array('.listTitle>h2>a>span','text'),
			'good_img'   => array('.picLeft>img','src'),
			'shop_name'  => array('.botPart>.mall','text'),
			'good_buy_url'  => array('.botPart>.buy>a','href'),
			);
		$details_rule = array(
			'good_youhuilidu'      => array('.item-box item-preferential >.inner-block','html'),
			//'good_shangpinjieshao' => array('.item-box>.inner-block>p','text'),
			);
		$data = QueryList::Query($page,$rule)->data;
		foreach ($data as &$good) {
			$good_info = QueryList::Query($good['good_url'],$details_rule)->data;
			dump($good_info);
			//$good['good_intr'] = '优惠力度：'.$good_info['good_youhuilidu'].' 商品介绍：'.$good_info['good_shangpinjieshao'];
		}
        //打印结果
        //dump($data);
	}

	public function DataRule(){
		$rule = array(
			'good_name' => '/<div.*?class="list list_(.*?)l.*?>/i',
			);

		return $rule;
	}
}

?>