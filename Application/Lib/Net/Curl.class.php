<?php
namespace Lib\Net;

class Curl
{
	public $url = null;
	public $setTimeout = 10;
	public $method = 'get';
	public $params = array();
	public $data = array();
	public $headers =array();

	private $ch = null;

	public function __construct(){
		$this->ch = curl_init();
		if(!is_resource($this->ch)) {
			return false;
		}
	}

	public function exec(){
		$params = is_array($this->params) ? http_build_query($this->params) : $this->params;
		if ($method == 'post') {
			curl_setopt($this->ch, CURLOPT_URL, $this->url);
			curl_setopt($this->ch, CURLOPT_POST, true);
			curl_setopt($this->ch, CURLOPT_POSTFIELDS, $params);
		}else {
			curl_setopt($this->ch,CURLOPT_URL,$this->url.'?'.$params);
		}
		curl_setopt($this->ch,CURLOPT_HEADER,false);
		curl_setopt($this->ch,CURLOPT_RETURNTRANSFER,true);
		curl_setopt($this->ch,CURLOPT_FOLLOWLOCATION,true);
		curl_setopt($this->ch,CURLOPT_HTTPHEADER,$headers);
		curl_setopt($this->ch,CURLOPT_TIMEOUT,$this->setTimeout);
		$result = curl_exec($this->ch);
		$this->close();
		return $result;
	}

	private function close(){
		if(is_resource($this->ch)) {
			curl_close($this->ch);
		}
	}

	public function __destruct(){
		$this->close();
	}

}
?>