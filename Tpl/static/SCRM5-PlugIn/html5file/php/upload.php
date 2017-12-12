<?php
	session_start();
	date_default_timezone_set("Asia/Shanghai");
	$ymd = date("Ymd");
    include('class.uploader.php');
    $imgSpaceSize = getDirSize('../../../../../Uploads/'.$_SESSION['cid'].'/'.$ymd.'/');
    //$_SESSION['maximgspace']?$_SESSION['maximgspace']:'100';
	$maximgspace = '100';
    if ($imgSpaceSize >= $maximgspace*1024*1024 && $maximgspace > 0){
    	$data['error'] = "您的服务器储存空间已满，请联系您的客户经理AM购买更多存储空间";
    	echo json_encode($data);
    }else{
	    $uploader = new Uploader();
	    $data = $uploader->upload($_FILES['files'], array(
	        'limit' => 100000, //Maximum Limit of files. {null, Number}
	        'maxSize' => 50, //Maximum Size of files {null, Number(in MB's)}
	        'extensions' => null, //Whitelist for file extension. {null, Array(ex: array('jpg', 'png'))}
	        'required' => false, //Minimum one file is required for upload {Boolean}
	        'uploadDir' => '../../../../../Uploads/'.$_SESSION['cid'].'/'.$ymd.'/', //Upload directory {String}
	        'title' => array('name'), //New file name {null, String, Array} *please read documentation in README.md
	        'removeFiles' => true, //Enable file exclusion {Boolean(extra for jQuery.filer), String($_POST field name containing json data with file names)}
	        'perms' => null, //Uploaded file permisions {null, Number}
	        'onCheck' => null, //A callback function name to be called by checking a file for errors (must return an array) | ($file) | Callback
	        'onError' => null, //A callback function name to be called if an error occured (must return an array) | ($errors, $file) | Callback
	        'onSuccess' => null, //A callback function name to be called if all files were successfully uploaded | ($files, $metas) | Callback
	        'onUpload' => null, //A callback function name to be called if all files were successfully uploaded (must return an array) | ($file) | Callback
	        'onComplete' => null, //A callback function name to be called when upload is complete | ($file) | Callback
	        'onRemove' => 'onFilesRemoveCallback', //A callback function name to be called by removing files (must return an array) | ($removed_files) | Callback
	    ));
	    if($data['isComplete']){

	    	/* Asa Edit */
	    	//获取上传文件类型
	    	$file_type=$_FILES["files"]['type'][0];
	    	if($file_type == "image/pjpeg"||$file_type == "image/jpg"|$file_type == "image/jpeg"||$file_type == "image/x-png"||$file_type == "image/png"){
		    	$newFile = ResizeImage($data);
		    	//dump($data['data']);
		    	$data['data']['metas'][0]['size'] = filesize($newFile);
		    	$data['data']['metas'][0]['size2'] = formatSize(filesize($newFile));
	    	}
	    	/* Asa Edit End */
	    	
	    	$files = $data['data'];
	    	echo json_encode($files);
	    }
	    
	    if($data['hasErrors']){
	    	$errors = $data['errors'];
	    	print_r($errors);
	    }
    }
    
    
    function onFilesRemoveCallback($removed_files){
    	$ymd = date("Ymd");
        foreach($removed_files as $key=>$value){
            $file = '../../../../../Uploads/'.$_SESSION['cid'].'/' .$ymd.'/'. $value;
            if(file_exists($file)){
                unlink($file);
            }
        }
        
       return $removed_files;
    }
    function alert($msg) {
    	require_once 'JSON.php';
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
    /**
     * 实例化输出
     * @author Asa<asa@renlaifeng.cn>
     * @since  2017-3-15
     */
    function dump($vars, $label = '', $return = false) {
    	if (ini_get('html_errors')) {
    		$content = "<pre>\n";
    		if ($label != '') {
    			$content .= "<strong>{$label} :</strong>\n";
    		}
    		$content .= htmlspecialchars(print_r($vars, true));
    		$content .= "\n</pre>\n";
    	} else {
    		$content = $label . " :\n" . print_r($vars, true);
    	}
    	if ($return) { return $content; }
    	echo $content;
    	return null;
    }
    /**
     *
     * @param unknown $data 图片信息
     * @param unknown $_FILES  上传图片信息
     * @author Asa<asa@renlaifeng.cn>
     * @since  2017-3-15
     */
    function ResizeImage($data){
    	$file = '../../../../..'.$data['data']['files'][0];
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
    	//获取上传文件类型
    	$file_type=$_FILES["files"]['type'][0];
    	//缩放尺寸
    	$newwidth = $width * $percent;
    	$newheight = $height * $percent;
    	 
    	if($file_type == "image/pjpeg"||$file_type == "image/jpg"|$file_type == "image/jpeg"){
    		$src_im = imagecreatefromjpeg($file);
    		$dst_im = imagecreatetruecolor($newwidth, $newheight);
    		imagecopyresized($dst_im, $src_im, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
    		imagejpeg($dst_im,$file,$asasize); //输出压缩后的图片
    	}elseif($file_type == "image/x-png"||$file_type == "image/png"){
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
    //转换大小的
    function formatSize($bytes){
    	if ($bytes >= 1073741824){
    		$bytes = number_format($bytes / 1073741824, 2) . ' GB';
    	}elseif ($bytes >= 1048576){
    		$bytes = number_format($bytes / 1048576, 2) . ' MB';
    	}elseif ($bytes > 0){
    		$bytes = number_format($bytes / 1024, 2) . ' KB';
    	}else{
    		$bytes = '0 bytes';
    	}
    
    	return $bytes;
    }
?>
