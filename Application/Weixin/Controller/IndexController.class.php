<?php
namespace Weixin\Controller;
use Think\Controller;

define("TOKEN", "jian1");

class IndexController extends Controller
{
	public function index(){
		$echoStr = I('get.echostr');
		if ( $this->checkSignature()) {
			echo $echoStr;
        	exit;
		}
	}

	public function checkSignature(){
		$signature = I('get.signature');
		$timestamp = I('get.timestamp');
		$noce      = I('get.noce');
		$token     = TOKEN;
		$tmpArr    = array( $token , $timestamp , $noce );
		sort( $tmpArr , SORT_STRING);
		$tmpStr    = implode( $tmpArr );
		$tmpStr    = sha1( $tmpStr );
		print $signature;
		print $noce;
		print $timestamp;
		print $token;
		if ($tmpStr == $signature) {
			return true;
		}else{
			return false;
		}
	}

}

?>