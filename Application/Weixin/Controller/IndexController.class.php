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
                $tpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							<FuncFlag>0</FuncFlag>
							</xml>";

				if( $keyword == 'hello')
				{
                	$msgType = "text";
                	$contentStr = "Welcome to wechat world!";
                	$resultStr = sprintf($tpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                	echo $resultStr;
                }

				if( $keyword == 'pic')
				{
                	$msgType = "image";
                	$contentStr = "http://www.hostloc.com/uc_server/data/avatar/000/00/96/23_avatar_middle.jpg";
                	$resultStr = sprintf($tpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                	echo $resultStr;
                }

				if(!empty( $keyword ))
                {
              		$msgType = "text";
                	$contentStr = "Welcome to wechat world!";
                	$resultStr = sprintf($tpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                	echo $resultStr;
                }

        }else {
        	echo "";
        	exit;
        }
    }

}

?>