<?php
namespace Lib\Pthreads;
class Threads extends \Thread {
    public $url;
    public $result;
    public function __construct($url) {
        $this->url = $url;
    }

    public function run() {

        if ($this->url) {
            $this->result = getPage($url);
        }
    }

    public function getPage($url){
        $Curl = new \Lib\Net\Curl();
        $Curl->url = $url;
        $result = $Curl->exec();
        return $result;
    }

}

?>