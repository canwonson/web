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
                $textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							<FuncFlag>0</FuncFlag>
							</xml>";
				$newTpl = "<xml>
<ToUserName><![CDATA[%s]]]></ToUserName>
<FromUserName><![CDATA[%s]]]></FromUserName>
<CreateTime><![CDATA[%s]]]></CreateTime>
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

				if( $keyword == 'zx')
				{
					$list = $this->getList();
       				$tpl = $this->transmitNews($object, $list);
                	$resultStr = $tpl;
                	echo $resultStr;
                }
                if( $keyword == 'new')
				{
					$resultStr = sprintf($newTpl, $fromUsername, $toUsername, $time);
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
        	$list = $Good->where($where)->limit(0,5)->select();
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

    private function transmitNews($object, $newsArray)
    {
        if(!is_array($newsArray)){
            return;
        }
        $itemTpl = "    <item>
        <Title><![CDATA[%s]]></Title>
        <Description><![CDATA[%s]]></Description>
        <PicUrl><![CDATA[%s]]></PicUrl>
        <Url><![CDATA[%s]]></Url>
    </item>
";
        $item_str = "";
        foreach ($newsArray as $item){
        	if (!$item['good_img']) {
        		$item['good_img']='http://y.zdmimg.com/201512/03/566055fe7d0ee5899.png_a100.jpg';
        	}
            $item_str .= sprintf($itemTpl, $item['good_name'], $item['good_intr1'], $item['good_img'], $item['good_buy_url']);
        }
        $xmlTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[news]]></MsgType>
<ArticleCount>%s</ArticleCount>
<Articles>
$item_str</Articles>
</xml>";

        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time(), count($newsArray));
        return $result;
    }

}

?>