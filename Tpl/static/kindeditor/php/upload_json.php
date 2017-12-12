<?php
session_start();
require_once 'JSON.php';
$php_path = dirname(__FILE__) . '/';
$php_url = dirname($_SERVER['PHP_SELF']) . '/';
//$save_path = $php_path . '../../../../Uploads/'.$_SESSION['token'].'/';//上传的磁盘地址
$save_path = $php_path . '../../../../Uploads/'.$_SESSION['cid'].'/';//上传的磁盘地址
$save_url = '/Uploads/'.$_SESSION['cid'].'/';//访问时的网络地址
$ext_arr = array(
	'image' => array('gif', 'jpg', 'jpeg', 'png', 'bmp','pdf'),
	'flash' => array('swf', 'flv'),
	'mp3' => array( 'mp3'),
	'media' => array('swf', 'flv', 'mp4', 'wav', 'wma', 'wmv', 'mid', 'avi', 'mpg', 'asf', 'rm', 'rmvb'),
	'file' => array('mp4', 'doc', 'docx','pptx','psd', 'xls', 'xlsx', 'ppt', 'html', 'txt', 'zip', 'rar', 'gz', 'bz2', 'csv','pem','png','rp','jpg','jpeg','prd','pdf'),
);
$filetype = empty($_GET['dir']) ? 'image' : trim($_GET['dir']);
$max_size = 629145600;
$save_path = realpath($save_path).'/';
if (!empty($_FILES['imgFile']['error'])) {
	switch($_FILES['imgFile']['error']){
		case '1':
			$error = '超过php.ini允许的大小。';
			break;
		case '2':
			$error = '超过表单允许的大小。';
			break;
		case '3':
			$error = '图片只有部分被上传。';
			break;
		case '4':
			$error = '请选择图片。';
			break;
		case '6':
			$error = '找不到临时目录。';
			break;
		case '7':
			$error = '写文件到硬盘出错。';
			break;
		case '8':
			$error = 'File upload stopped by extension。';
			break;
		case '999':
		default:
			$error = '未知错误。';
	}
	alert($error);
}
$imgSpaceSize = getDirSize($save_path);
if (empty($_FILES) === false) {
	$file_name = $_FILES['imgFile']['name'];
	$tmp_name = $_FILES['imgFile']['tmp_name'];
	$file_size = $_FILES['imgFile']['size'];
	if (!$file_name) alert("请选择文件。");
	if ($imgSpaceSize >= $_SESSION['maximgspace']*1024*1024 && $_SESSION['maximgspace'] > 0) alert("您的服务器储存空间已满，请联系您的客户经理AM购买更多存储空间");
	if (@is_dir($save_path) === false) alert("上传目录不存在。");
	if (@is_writable($save_path) === false) alert("上传目录没有写权限。");
	if (@is_uploaded_file($tmp_name) === false) alert("上传失败。");
	if ($file_size > $max_size) alert("抱歉，上传失败。错误原因：请上传小于等于6MB的文件");
	$dir_name = empty($_GET['dir']) ? 'image' : trim($_GET['dir']);
	if (empty($ext_arr[$dir_name])) alert("目录名不正确。");
	$temp_arr = explode(".", $file_name);
	$file_ext = array_pop($temp_arr);
	$file_ext = trim($file_ext);
	$file_ext = strtolower($file_ext);
	if (in_array($file_ext, $ext_arr[$dir_name]) === false) {
		alert("上传文件扩展名是不允许的扩展名。\n只允许" . implode(",", $ext_arr[$dir_name]) . "格式。");
	}
	if ($dir_name !== '') {
		$save_path .= $dir_name . "/";
		$save_url .= $dir_name . "/";
		if (!file_exists($save_path)) mkdir($save_path);
	}
	$ymd = date("Ymd");
	$save_path .= $ymd . "/";
	$save_url .= $ymd . "/";
	if (!file_exists($save_path)) {
		mkdir($save_path);
	}
	//新文件名
	$new_file_name = date("YmdHis") . '_' . rand(10000, 99999) . '.' . $file_ext;
	//移动文件
	$file_path = $save_path . $new_file_name;
	if (move_uploaded_file($tmp_name, $file_path) === false) {
		alert("上传文件失败。");
	}
	@chmod($file_path, 0644);
	$file_url = $save_url . $new_file_name;

	header('Content-type: text/html; charset=UTF-8');
	$json = new Services_JSON();
	echo $json->encode(array('error' => 0,'url' => $file_url));
	exit;
}
function alert($msg) {
	header('Content-type: text/html; charset=UTF-8');
	$json = new Services_JSON();
	echo $json->encode(array('error' => 1,'message' => $msg));
	exit;
}

/**
 * 获得空间大小 kb
 * Enter description here ...
 * @param unknown_type $dir
 */
function getDirSize($dir){
	if(!is_dir($dir)){
		return 0;
		exit();
	}
	$handle = opendir($dir);
	$sizeResult = 0;
	while (false!==($FolderOrFile = readdir($handle)))
	{
		if($FolderOrFile != "." && $FolderOrFile != "..")
		{
			if(is_dir("$dir/$FolderOrFile"))
			{
				$sizeResult += getDirSize("$dir/$FolderOrFile");
			}
			else
			{
				$sizeResult += filesize("$dir/$FolderOrFile");
			}
		}
	}
	closedir($handle);
	return $sizeResult;
}