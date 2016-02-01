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
		$fromUsername ='me';
		$keyword = 'ss';
		$action_level = $this->actionLog($fromUsername,$keyword);
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
                $action_level = $this->actionLog($fromUsername,$keyword);
                $textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							<FuncFlag>0</FuncFlag>
							</xml>";

				if( $keyword == 'zx' || $keyword == 1)
				{
					$list = $this->getList();
					$content = array();
					foreach ($list as $key => $value) {
						$content[$key]['Title'] = $value['good_name'];
						$content[$key]['Description '] = $value['good_intr1'];
						$content[$key]['PicUrl'] = $value['good_img'];
						$content[$key]['Url'] = $value['good_buy_url'];
					}
					$title = '最新优惠';
       				$tpl = $this->transmitNews($postObj,$content,$title);
                	$resultStr = $tpl;
                	echo $resultStr;
                }

				if(!empty( $keyword ))
                {
                	$title = '欢迎关注好买助手';
              		$content = array();
					$content[] = array("Title" =>"【zx】最新优惠\n".
					    "【ss关键字】搜索优惠\n".
					    "【3】暂时没有想好\n".
					    "更多精彩，即将亮相，敬请期待！", "Description" =>"", "PicUrl" =>"", "Url" =>"");
					$content[] = array("Title" =>"回复对应代码获取信息\n发送 0 返回本菜单", "Description" =>"", "PicUrl" =>"", "Url" =>"");
                	$tpl = $this->transmitNews($postObj,$content,$title);
                	$resultStr = $tpl;
                	echo $resultStr;
                }

        }else {
        	echo "";
        	exit;
        }
    }

    public function actionLog($fromUsername,$keyword){
        $WeixinUserAction = M('weixin_user_action');
        $WeixinAction = M('weixin_action');
        $action_list = $WeixinAction->getField('action',true);
        if (in_array($keyword,$action_list)) {
        	$map['action'] = $keyword;
        	$action_level = $WeixinAction->where($map)->getField('action_level');
        	$action_info = array(
        	'userid' => $fromUsername,
        	'action' => $keyword,
        	'time'   => time(),
        	'action_level' => $action_level
        	);
        	$WeixinUserAction->add($action_info);
        }
        return $action_level;
    }

    public function getList($type='newest',$source=0){
    	$Good = M('good');
        if ($type=='newest') {
        	$source && $where['source'] = $source;
        	$list = $Good->where($where)->limit(0,9)->select();
        }
        return $list;
    }


    private function transmitNews($object, $newsArray,$title)
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
            $item_str .= sprintf($itemTpl, $item['Title'], $item['Description'], $item['PicUrl'], $item['Url']);
        }
        $xmlTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[news]]></MsgType>
<ArticleCount>%s</ArticleCount>
<Articles>
<item>
    <Title><![CDATA[$title]]></Title>
</item>
$item_str</Articles>
</xml>";

        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time(), count($newsArray)+1);
        return $result;
    }

}

?>