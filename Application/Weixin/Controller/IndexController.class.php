<?php
namespace Weixin\Controller;
use Think\Controller;

define("TOKEN", "jian1");

class IndexController extends Controller
{
	public function index(){
		$echoStr   = I('get.echostr');
		$signature = I('get.signature');
		$timestamp = I('get.timestamp');
		$nonce      = I('get.nonce');
		$token     = TOKEN;
		$tmpArr    = array( $token , $timestamp , $nonce );
		sort( $tmpArr , SORT_STRING);
		$tmpStr    = implode( $tmpArr );
		$tmpStr    = sha1( $tmpStr );
		if ($tmpStr == $signature && $echoStr) {
			echo $echoStr;
        	exit;
		}else{
			$this -> responseMsg();
		}
	}

	public function responseMsg()
    {
		//get post data, May be due to the different environments
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

      	//extract post data
		if (!empty($postStr)){
                /* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
                   the best way is to check the validity of xml by yourself */
                libxml_disable_entity_loader(true);
              	$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
                $fromUsername = $postObj->FromUserName;
                $toUsername = $postObj->ToUserName;
                $keyword = trim($postObj->Content);
                $time = time();
               

				$articleTpl = "<xml>
							<ToUserName><![CDATA[toUser]]></ToUserName>
							<FromUserName><![CDATA[fromUser]]></FromUserName>
							<CreateTime>12345678</CreateTime>
							<MsgType><![CDATA[news]]></MsgType>
							<ArticleCount>2</ArticleCount>
							<Articles>
							<item>
							<Title><![CDATA[title1]]></Title>
							<Description><![CDATA[description1]]></Description>
							<PicUrl><![CDATA[picurl]]></PicUrl>
							<Url><![CDATA[url]]></Url>
							</item>
							<item>
							<Title><![CDATA[title]]></Title>
							<Description><![CDATA[description]]></Description>
							<PicUrl><![CDATA[picurl]]></PicUrl>
							<Url><![CDATA[url]]></Url>
							</item>
							</Articles>
							</xml> ";
				if( $keyword == '最新')
				{
                	$list = $this->getList();
       				$tpl = $this->getNewTpl($list,$fromUsername,$toUsername);
                	$resultStr = $tpl;
                	echo $resultStr;
                }



				if(!empty( $keyword ))
                {
              		$msgType = "text";
                	$contentStr = "Welcome to wechat world!";
                	$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                	echo $resultStr;
                }

        }else {
        	echo "";
        	exit;
        }
    }

    public function getList($type='newest',$source=0){
    	$Good = M('good');
        if ($type=='newest') {
        	$source && $where['source'] = $source;
        	$list = $Good->where($where)->limit(0,10)->select();
        }
        return $list;
    }

    public function getNewTpl($list,$fromUsername,$toUsername){
    	$tpl_head = '<xml>
					<ToUserName><![CDATA['.$toUsername.']]></ToUserName>
					<FromUserName><![CDATA['.$fromUsername.']]></FromUserName>
					<CreateTime><![CDATA['.time().']]></CreateTime>
					<MsgType><![CDATA[news]]></MsgType>
					<ArticleCount><![CDATA['.count($list).']]></ArticleCount>
					<Articles>';
		foreach ($list as $key => $value) {
			$tpl_content .='<item>
							<Title><![CDATA['.$value['good_name'].']]></Title>
							<Description><![CDATA['.$value['good_intr1'].']]></Description>
							<PicUrl><![CDATA['.$value['good_img'].']]></PicUrl>
							<Url><![CDATA['.$value['good_buy_url'].']]></Url>
							</item>';
		}
		$tpl_foot = '</Articles></xml>';
		$tpl = $tpl_head.$tpl_content.$tpl_foot;
		return $tpl;
    }

}

?>