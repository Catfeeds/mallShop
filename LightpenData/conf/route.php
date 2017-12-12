<?php
/**
 *网站路由配置
 *@package 
 *@author 
 **/
return array(
	/* URL设置 */
	'URL_CASE_INSENSITIVE'  => false,   // 默认false 表示URL区分大小写 true则表示不区分大小写
	'URL_MODEL'             => 0,       // URL访问模式,可选参数0、1、2、3,代表以下四种模式：
	// 0 (普通模式); 1 (PATHINFO 模式); 2 (REWRITE  模式); 3 (兼容模式)  默认为PATHINFO 模式，提供最好的用户体验和SEO支持
	'URL_PATHINFO_DEPR'     => '/',	// PATHINFO模式下，各参数之间的分割符号
	'URL_PATHINFO_FETCH'    =>   'ORIG_PATH_INFO,REDIRECT_PATH_INFO,REDIRECT_URL', // 用于兼容判断PATH_INFO 参数的SERVER替代变量列表
	'URL_HTML_SUFFIX'       => '',  // URL伪静态后缀设置
	'URL_PARAMS_BIND'       =>  true, // URL变量绑定到Action方法参数
	/*路由设置*/
	'URL_ROUTER_ON'   		=> true, 			//开启路由
	'URL_ROUTE_RULES' 		=> array( 			//定义路由规则
		'api/:token'        => 'Home/Weixin/index',
		'wapi/:token'        => 'Home/Wechat/index',
		'show/:token'       => 'Home/Adma/index',
	),
);