<?php
namespace Admin\Model;
use Think\Model;

class CrawlDeployModel extends Model{

	protected $_validate = array(
     	array('title','require','请填写名称！'), //名称验证
     	array('url','require','请填写抓取地址！'),
     	array('table','require','数据表已经存在！',0,'unique',3),
   	);

}
?>