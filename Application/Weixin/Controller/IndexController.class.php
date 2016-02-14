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
              	$fromUsername = trim($postObj->FromUserName);
                $toUsername = $postObj->ToUserName;
                $keyword = trim($postObj->Content);
                $time = time();
                $action_level = $this->actionLog($fromUsername,$keyword);
                $session_id = $this->getUserSeesion($fromUsername);
                if ($action_level == 1) {
	                if (!$session_id) {
						$this->createUserSession($fromUsername,$keyword);
	                }else{
	                	$this->updateUserSession($session_id,$keyword,$action_level);
	                }
                }

                if ($action_level == 2 && $session_id) {
                	$this->updateUserSession($session_id,$keyword,$action_level);
                }

                if ($action_level || $action_level ==1) {
                	if( $keyword == 'zx')
					{
						$list = $this->getList($session_id);
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

	                if ($keyword == 'ss') {
	                	$title = '搜索优惠';
	                	$content = array();
	                	$content[] = array("Title" =>"搜索优惠\n".
					    "现在直接输入关键字搜索优惠\n".
					    "【p】上一页\n".
					    "【n】下一页\n".
					    "【e】退出搜索\n".
					    "请回复操作！", "Description" =>"", "PicUrl" =>"", "Url" =>"");
	       				$tpl = $this->transmitNews($postObj,$content,$title);
	                	$resultStr = $tpl;
	                	echo $resultStr;
	                }
                }


                $textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							<FuncFlag>0</FuncFlag>
							</xml>";

                if ($keyword == 'cs') {
		        	$msgType = "text";
                	$contentStr = gettype($postObj->FromUserName);
                	$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                	echo $resultStr;
                }

				if(!empty( $keyword ) || $keyword=='m')
                {
                	$title = '欢迎关注好买助手';
              		$content = array();
					$content[] = array("Title" =>"【zx】最新优惠\n".
					    "【ss】搜索优惠\n".
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

    public function getUserSeesion($fromUsername){
    	//获取用户回话id
    	$WeixinUserSession = M('weixin_user_session');
    	$map['userid'] = $fromUsername;
    	$session_id = $WeixinUserSession->where($map)->field('max(time),id')->find();
    	return $session_id['id'];
    }

    public function createUserSession($fromUsername,$keyword){
    	//创建用户会话
    	$WeixinUserSession = M('weixin_user_session');
    	$parame = array(
    		'p' => 1
    		);
    	$parame = json_encode($parame);
    	$session_info = array(
    		'userid' => $fromUsername,
    		'action' => $keyword,
    		'parame' => $parame,
    		'time'	 => time()
    		);
    	$session_id = $WeixinUserSession->add($session_info);
    }

    public function updateUserSession($session_id,$keyword,$action_level){
    	$WeixinUserSession = M('weixin_user_session');
    	$map = array('id'=>$session_id);
    	$session_info = $WeixinUserSession->where($map)->find();
    	if ($action_level == 1) {
    		$parame = array(
	    		'p' => 1
	    		);
    		$parame = json_encode($parame);
    		$session_info['time'] = time();
    		$session_info['action'] = $keyword;
    		$session_info['parame'] = $parame;
   			$session_id = $WeixinUserSession->save($session_info);
    	}

    	if ($action_level == 2) {
    		$parame = json_decode($session_info['parame'],true);
    		$p = $parame['p'];
    		switch ($keyword) {
    			case 'p':
    				$p = $p==1 ? 1 : $p-1;
    				break;
    			case 'n':
    				$p = $p+1;
    				break;
    			case 'e':
    				$WeixinUserSession->where(array('id'=>$session_info['id']))->delete();
    				break;
    			default:
    				# code...
    				break;
    		}
    		if ($p) {
    			$parame = array(
		    		'p' => $p
		    		);
    			$parame = json_encode($parame);
    			$session_info['parame'] = $parame;
    			$session_info['time'] = time();
    			$session_id = $WeixinUserSession->save($session_info);
    		}
    	}
    }

    public function actionLog($fromUsername,$keyword){
        $WeixinUserAction = M('weixin_user_action');
        $WeixinAction = M('weixin_action');
        $action_list = $WeixinAction->getField('action',true);
        $keyword = $keyword;
        $userid = $fromUsername;
        if (in_array($keyword,$action_list)) {
        	$map['action'] = $keyword;
        	$action_level = $WeixinAction->where($map)->getField('action_level');
        	$action_info = array(
        	'userid' => $userid,
        	'action' => $keyword,
        	'time'   => time(),
        	'action_level' => $action_level
        	);
        	$WeixinUserAction->add($action_info);
        }
        return $action_level;
    }

    public function getList($session_id){
    	$Good = M('good');
    	$WeixinUserSession = M('weixin_user_session');
    	$map = array('id'=>$session_id);
    	$session_info = $WeixinUserSession->where($map)->find();
    	$parame = json_decode($session_info['parame'],true);
    	$p = $parame['p'];
    	$start = ($p - 1)*8;
    	$source && $where['source'] = $source;
    	$list = $Good->where($where)->limit($start,8)->order('add_time desc')->select();
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