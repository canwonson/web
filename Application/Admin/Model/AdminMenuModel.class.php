<?php
namespace Admin\Model;
use Think\Model;

class AdminMenuModel extends Model{

	protected $_validate = array(
     	array('title','require','请填写名称！'), //名称验证
     	array('controller','require','请填写控制器！'), //名称验证
     	array('controller','','控制器名称已经存在！',0,'unique',3), // 在新增的时候验证controller字段是否唯一
   	);
}
?>