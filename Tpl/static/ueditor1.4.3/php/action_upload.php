<?php
session_start();
/**
 * 上传附件和上传视频
 * User: Jinqn
 * Date: 14-04-09
 * Time: 上午10:17
 */
include "Uploader.class.php";

/* 上传配置 */
$base64 = "upload";
switch (htmlspecialchars($_GET['action'])) {
    case 'uploadimage':
        $config = array(
            "pathFormat" => "/Uploads/".$_SESSION['cid']."/image/{yyyy}{mm}{dd}/{time}{rand:6}",
            /* "pathFormat" => $CONFIG['imagePathFormat'],"/ueditor/php/upload/image/{yyyy}{mm}{dd}/{time}{rand:6}" */
            "maxSize" => $CONFIG['imageMaxSize'],
            "allowFiles" => $CONFIG['imageAllowFiles']
        );
        $fieldName = $CONFIG['imageFieldName'];
        break;
    case 'uploadscrawl':
        $config = array(
            "pathFormat" => "/Uploads/".$_SESSION['cid']."/image/{yyyy}{mm}{dd}/{time}{rand:6}",
            "maxSize" => $CONFIG['scrawlMaxSize'],
            "allowFiles" => $CONFIG['scrawlAllowFiles'],
            "oriName" => "scrawl.png"
        );
        $fieldName = $CONFIG['scrawlFieldName'];
        $base64 = "base64";
        break;
    case 'uploadvideo':
        $config = array(
            "pathFormat" => "/Uploads/".$_SESSION['cid']."/video/{yyyy}{mm}{dd}/{time}{rand:6}",
            "maxSize" => $CONFIG['videoMaxSize'],
            "allowFiles" => $CONFIG['videoAllowFiles']
        );
        $fieldName = $CONFIG['videoFieldName'];
        break;
    case 'uploadfile':
    default:
        $config = array(
            "pathFormat" => "/Uploads/".$_SESSION['cid']."/file/{yyyy}{mm}{dd}/{time}{rand:6}",
            "maxSize" => $CONFIG['fileMaxSize'],
            "allowFiles" => $CONFIG['fileAllowFiles']
        );
        $fieldName = $CONFIG['fileFieldName'];
        break;
}

/* 生成上传实例对象并完成上传 */
$up = new Uploader($fieldName, $config, $base64);
/* Asa Edit */
$pathInfo = $up->getFileInfo();
//dump($pathInfo);
if($pathInfo['type']=='.jpg'||$pathInfo['type']=='.jpeg'||$pathInfo['type']=='.png')
	ResizeImage($pathInfo['url'],$pathInfo['type']);
/* Asa Edit End */
/**
 * 得到上传文件所对应的各个参数,数组结构
 * array(
 *     "state" => "",          //上传状态，上传成功时必须返回"SUCCESS"
 *     "url" => "",            //返回的地址
 *     "title" => "",          //新文件名
 *     "original" => "",       //原始文件名
 *     "type" => ""            //文件类型
 *     "size" => "",           //文件大小
 * )
 */

/* 返回数据 */
return json_encode($up->getFileInfo());
/**
 *
 * @param unknown $data 图片信息
 * @param unknown $_FILES  上传图片信息
 * @author Asa<asa@renlaifeng.cn>
 * @since  2017-3-15
 */
function ResizeImage($file,$file_type){
	$file = '../../../../'.$file;
	list($width, $height) = getimagesize($file); //获取原图尺寸
	if($width>960){
		$percent = 960/$width;
	}elseif ($width<960 && $width>640){
	    $percent = 640/$width;
	}else{
	    $percent = 1;  //图片压缩比
	}
	if(filesize($file)>200*1024){
		$asasize = 80; //压缩比1-100
	}else{
		$asasize = 80; //压缩比1-100
	}
	//缩放尺寸
	$newwidth = $width * $percent;
	$newheight = $height * $percent;

	if($file_type == ".pjpeg"||$file_type == ".jpg"|$file_type == ".jpeg"){
		$src_im = imagecreatefromjpeg($file);
		$dst_im = imagecreatetruecolor($newwidth, $newheight);
		imagecopyresized($dst_im, $src_im, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
		imagejpeg($dst_im,$file,$asasize); //输出压缩后的图片
	}elseif($file_type == ".x-png"||$file_type == ".png"){
		$src_im = imagecreatefrompng($file);
		$dst_im = imagecreatetruecolor($newwidth, $newheight);
		imagecopyresized($dst_im, $src_im, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
		imagejpeg($dst_im,$file,$asasize); //输出压缩后的图片
	}else{
		$src_im = imagecreatefromjpeg($file);
		$dst_im = imagecreatetruecolor($newwidth, $newheight);
		imagecopyresized($dst_im, $src_im, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
		imagejpeg($dst_im,$file,$asasize); //输出压缩后的图片
	}
	imagedestroy($dst_im);
	imagedestroy($src_im);
	return $file;
}