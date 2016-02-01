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

				if( $keyword == 'zx')
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
              		$content = array();
					$content[] = array("Title" =>"欢迎关注好买助手","Description" =>"", "PicUrl" =>"", "Url" =>"");
					$content[] = array("Title" =>"【1】新闻 天气 空气 股票 彩票 星座\n".
					    "【2】快递 人品 算命 解梦 附近 苹果\n".
					    "【3】公交 火车 汽车 航班 路况 违章\n".
					    "【4】翻译 百科 双语 听力 成语 历史\n".
					    "【5】团购 充值 菜谱 贺卡 景点 冬吴\n".
					    "【6】情侣相 夫妻相 亲子相 女人味\n".
					    "【7】相册 游戏 笑话 答题 点歌 树洞\n".
					    "【8】微社区 四六级 华强北 世界杯\n\n".
					    "更多精彩，即将亮相，敬请期待！";, "Description" =>"", "PicUrl" =>"", "Url" =>"");
					$content[] = array("Title" =>"回复对应数字查看使用方法\n发送 0 返回本菜单", "Description" =>"", "PicUrl" =>"", "Url" =>"");
                	$tpl = $this->transmitNews($postObj,$content,$title);
                	$resultStr = $tpl;
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

        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time(), count($newsArray));
        return $result;
    }

}

?>