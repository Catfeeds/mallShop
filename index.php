<?php
header("Content-type: text/html; charset=utf-8");
//require_once('360_safe3.php');
//if (get_magic_quotes_gpc()) {
//	function stripslashes_deep($value){
//		$value = is_array($value) ? array_map('stripslashes_deep', $value) : stripslashes($value);
//		return $value;
//	}
//	$_POST = array_map('stripslashes_deep', $_POST);
//	$_GET = array_map('stripslashes_deep', $_GET);
//	$_COOKIE = array_map('stripslashes_deep', $_COOKIE);
//}
date_default_timezone_set('PRC'); 
define('APP_DEBUG',1);
define('Project_Dir',dirname(__FILE__));
define('APP_NAME', 'LightpenCms');
define('CONF_PATH','./LightpenData/conf/');
define('RUNTIME_PATH','./LightpenData/logs/');
define('TMPL_PATH','./Tpl/');
define('HTML_PATH','./LightpenData/html/');
define('APP_PATH','./LightpenCms/');
define('CORE','./LightpenCms/_Core');
require(CORE.'/LightpenCms.php');
?>