<?php
return array(
'TMPL_L_DELIM'=>'<{',
'TMPL_R_DELIM'=>'}>',
'DB_TYPE'   => 'mysql', // 数据库类型
'DB_HOST'   => 'localhost', // 数据库连接地址
'DB_NAME'   => 'web', // 数据库名
'DB_USER'   => 'root', // 数据库用户名
'DB_PWD'    => 'root', // 数据库密码
'DB_PORT'   => 3306, // 数据库端口
'DB_PREFIX' => 'web_', // 数据库前缀
'DB_CHARSET'=> 'utf8', // 数据库编码
'DB_DEBUG'  =>  TRUE, // 是否开启调试模式
'DB_LIKE_FIELDS'=>'news_title|news_content|news_flag|news_open',//自动模糊查询字段
		'URL_ROUTER_ON'   => true,
		'URL_ROUTE_RULES'=>array(
				'con/:n_id'=> 'Home/Index/news_content',//路由文章页
				'list/:c_id'=> 'Home/Index/news_list',//路由列表页
		),
);