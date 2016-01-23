<?php
$config = array(

	'AUTH_CONFIG' => array(
	'AUTH_ON' => true, //是否开启权限
	'AUTH_TYPE' => 1, //
	'AUTH_GROUP' => 'mr_auth_group', //用户组
	'AUTH_GROUP_ACCESS' => 'mr_auth_group_access', //用户组规则
	'AUTH_RULE' => 'mr_auth_rule', //规则中间表
	'AUTH_USER' => 'mr_admin'// 管理员表
	),

);

return array_merge(include './Conf/config.php', $config);
?>