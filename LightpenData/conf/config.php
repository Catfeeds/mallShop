<?php

/**

 *项目公共配置

 *@package PiGCms

 *@author PiGCms

 **/

return array(
    'LOAD_EXT_CONFIG' 		=> 'db,info,email,safe,upfile,cache,route,app,alipay,memcached',		
	'APP_AUTOLOAD_PATH'     =>'@.ORG',
	'OUTPUT_ENCODE'         =>  true, 			//页面压缩输出
	'PAGE_NUM'				=> 15,
	/*Cookie配置*/
	'COOKIE_PATH'           => '/',     		// Cookie路径
    'COOKIE_PREFIX'         => '',      		// Cookie前缀 避免冲突
	/*定义模版标签*/
	'TMPL_L_DELIM'   		=>'{lanrain:',			//模板引擎普通标签开始标记
	'TMPL_R_DELIM'			=>'}',				//模板引擎普通标签结束标记
	'SHOW_PAGE_TRACE'		=>FALSE,
	 /* SESSION设置 */
    'SESSION_AUTO_START'    =>  true,    // 是否自动开启Session
    'SESSION_OPTIONS'       =>  array('expire'=>'5184000'), // session 配置数组 支持type name id path expire domain 等参数
    'SESSION_TYPE'          =>  '', // session hander类型 默认无需设置 除非扩展了session hander驱动
    'SESSION_PREFIX'        =>  '', // session 前缀
    //'VAR_SESSION_ID'      =>  'session_id',     //sessionID的提交变量
		
	/* 日志设置 */
    'LOG_RECORD'            =>  false,   // 默认不记录日志
    'LOG_TYPE'              =>  'File', // 日志记录类型 默认为文件方式
    'LOG_LEVEL'             =>  'EMERG,ALERT,CRIT,ERR',// 允许记录的日志级别
    'LOG_FILE_SIZE'         =>  2097152,    // 日志文件大小限制
    'LOG_EXCEPTION_RECORD'  =>  false,    // 是否记录异常信息日志
	/*多语言配置*/
	'LANG_SWITCH_ON'    => true,        //开启多语言支持开关
	'LANG_AUTO_DETECT' => FALSE, // 自动侦测语言 开启多语言功能后有效
	//'LANG_AUTO_DETECT' => true, // 自动侦测语言 开启多语言功能后有效
	'LANG_LIST'        => 'zh-cn', // 允许切换的语言列表 用逗号分隔
	//'LANG_LIST'        => 'zh-cn,en-us', // 允许切换的语言列表 用逗号分隔
	'VAR_LANGUAGE'     => 'l', // 默认语言切换变量
	/* 模板引擎设置 */
    'TMPL_CONTENT_TYPE'     => 'text/html', // 默认模板输出类型
    'TMPL_ACTION_ERROR'     => './Tpl/dispatch_jump.tpl', // 默认错误跳转对应的模板文件
    'TMPL_ACTION_SUCCESS'   => './Tpl/dispatch_jump.tpl', // 默认成功跳转对应的模板文件
    /*Wap 提示模板*/
	'WAP_TMPL_ACTION_ERROR'     => './Tpl/wap_dispatch_jump.tpl', // 默认错误跳转对应的模板文件
	'WAP_TMPL_ACTION_SUCCESS'   => './Tpl/wap_dispatch_jump.tpl', // 默认成功跳转对应的模板文件
    //'TMPL_EXCEPTION_FILE'   => './Tpl/think_exception.tpl',// 异常页面的模板文件
    'TMPL_DETECT_THEME'     => false,       // 自动侦测模板主题
    'TMPL_TEMPLATE_SUFFIX'  => '.html',     // 默认模板文件后缀
    'TMPL_FILE_DEPR'        =>  '/', //模板文件MODULE_NAME与ACTION_NAME之间的分割符，只对项目分组部署有效
);

?>