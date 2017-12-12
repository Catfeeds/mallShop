<?php
session_start();
    include('class.uploader.php');
    //$ymd = date("Ymd");
    //$imgSpaceSize = getDirSize('../../../../../Uploads/'.$_SESSION['cid'].'/'.$ymd.'/');
    //$_SESSION['maximgspace']?$_SESSION['maximgspace']:'100';
    //if ($imgSpaceSize >= $_SESSION['maximgspace']*1024*1024 && $_SESSION['maximgspace'] > 0){
    	//$data['error'] = "您的服务器储存空间已满，请联系您的客户经理AM购买更多存储空间";
    	//echo json_encode($data);
    //}else{
	    $uploader = new Uploader();
	    $data = $uploader->upload($_FILES['files'], array(
	        'limit' => 100000, //Maximum Limit of files. {null, Number}
	        'maxSize' => 10, //Maximum Size of files {null, Number(in MB's)}
	        'extensions' => null, //Whitelist for file extension. {null, Array(ex: array('jpg', 'png'))}
	        'required' => false, //Minimum one file is required for upload {Boolean}
	        'uploadDir' => '../../../../../', //Upload directory {String}
	        'title' => array('name'), //New file name {null, String, Array} *please read documentation in README.md
	        'removeFiles' => true, //Enable file exclusion {Boolean(extra for jQuery.filer), String($_POST field name containing json data with file names)}
	        'perms' => null, //Uploaded file permisions {null, Number}
	        'onCheck' => null, //A callback function name to be called by checking a file for errors (must return an array) | ($file) | Callback
	        'onError' => null, //A callback function name to be called if an error occured (must return an array) | ($errors, $file) | Callback
	        'onSuccess' => null, //A callback function name to be called if all files were successfully uploaded | ($files, $metas) | Callback
	        'onUpload' => null, //A callback function name to be called if all files were successfully uploaded (must return an array) | ($file) | Callback
	        'onComplete' => null, //A callback function name to be called when upload is complete | ($file) | Callback
	        'onRemove' => 'onFilesRemoveCallback', //A callback function name to be called by removing files (must return an array) | ($removed_files) | Callback
	    ),2);
	    if($data['isComplete']){
	    	$files = $data['data'];
	    	echo json_encode($files);
	    }
	    
	    if($data['hasErrors']){
	    	$errors = $data['errors'];
	    	print_r($errors);
	    }
    //}
    
    
    function onFilesRemoveCallback($removed_files){
    	$ymd = date("Ymd");
    	//dump($removed_files);
        foreach($removed_files as $key=>$value){
            $file = '../../../../../../'. $value;
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
?>
