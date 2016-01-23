<?php
namespace DataGrabber\Controller;
use Think\Controller;
use QL\QueryList;

class HtmlParserController extends Controller
{
	public function parse($page){
		@set_time_limit(0);
		//HTTP操作扩展
		$urls = QueryList::run('Request',[
		        'target' => 'http://cms.querylist.cc/news/list_2.html',
		        'referrer'=>'http://cms.querylist.cc',
		        'method' => 'GET',
		        'params' => ['var1' => 'testvalue', 'var2' => 'somevalue'],
		        'user_agent'=>'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.8; rv:21.0) Gecko/20100101 Firefox/21.0',
		        'cookiePath' => './cookie.txt',
		        'timeout' =>'30'
		    ])->setQuery(['link' => ['h2>a','href','',function($content){
		    //利用回调函数补全相对链接
		    $baseUrl = 'http://cms.querylist.cc';
		    return $baseUrl.$content;
		}]],'.cate_list li')->getData(function($item){
		    return $item['link'];
		});

		//多线程扩展
		QueryList::run('Multi',[
		    'list' => $urls,
		    'curl' => [
		        'opt' => array(
		                    CURLOPT_SSL_VERIFYPEER => false,
		                    CURLOPT_SSL_VERIFYHOST => false,
		                    CURLOPT_FOLLOWLOCATION => true,
		                    CURLOPT_AUTOREFERER => true,
		                ),
		        //设置线程数
		        'maxThread' => 100,
		        //设置最大尝试数
		        'maxTry' => 3 
		    ],
		    'success' => function($a){
		        //采集规则
		        $reg = array(
		            //采集文章标题
		            'title' => array('h1','text'),
		            //采集文章发布日期,这里用到了QueryList的过滤功能，过滤掉span标签和a标签
		            'date' => array('.pt_info','text','-span -a',function($content){
		                //用回调函数进一步过滤出日期
		                $arr = explode(' ',$content);
		                return $arr[0];
		            }),
		            );
		        $rang = '.content';
		        $ql = QueryList::Query($a['content'],$reg,$rang);
		        $data = $ql->getData();
		        //打印结果，实际操作中这里应该做入数据库操作
		        print_r($data);
		    }
		]);
		die;
		$rule = array(
			'good_url'   => array('.picLeft','href'),
			'good_name'  => array('.listTitle>h2>a','text'),
			'good_price' => array('.listTitle>h2>a>span','text'),
			'good_img'   => array('.picLeft>img','src'),
			'shop_name'  => array('.botPart>.mall','text'),
			'good_buy_url'  => array('.botPart>.buy>a','href'),
			);
		$details_rule = array(
			//'good_youhuilidu'      => array('.item-box item-preferential ','html'),
			'good_shangpinjieshao' => array('.item-box>.inner-block>p','text'),
			);
		$data = QueryList::Query($page,$rule)->data;
		foreach ($data as &$good) {
			$urls[] = $good['good_url'];
			/*$good_info = QueryList::Query($good['good_url'],$details_rule)->data;
			dump($good_info);*/
			//$good['good_intr'] = '优惠力度：'.$good_info['good_youhuilidu'].' 商品介绍：'.$good_info['good_shangpinjieshao'];
		}
		die;
		QueryList::run('Multi',[
			'list' => $urls,
			'curl' => [
				'opt' => array(
					CURLOPT_SSL_VERIFYPEER => false,
                   	CURLOPT_SSL_VERIFYHOST => false,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_AUTOREFERER => true,
					),
				//设置线程数
        		'maxThread' => 100,
       			//设置最大尝试数
        		'maxTry' => 3
			],

			'success' => function($a){
				$good_info = QueryList::Query($a['content'],$details_rule)->data;
				dump($good_info);
			}
		]);
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