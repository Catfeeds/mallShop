<?php
/*
Uploadify
Copyright (c) 2012 Reactive Apps, Ronnie Garcia
Released under the MIT License <http://www.opensource.org/licenses/mit-license.php> 
*/

// Define a destination
$targetFolder = '/Uploads/photos/article'; // Relative to the root
//$targetFolder = '/data/uploads/images'; // Relative to the root
//check_dir($targetFolder);
$verifyToken = md5('unique_salt' . $_POST['timestamp']);
if (!empty($_FILES) && $_POST['token'] == $verifyToken) {
	$tempFile = $_FILES['file_upload']['tmp_name'];
	$targetPath = $_SERVER['DOCUMENT_ROOT'] . $targetFolder;
	$file_ext_arr=explode('.', $_FILES['file_upload']['name']);
	$new_file_name = md5(time().rand(0, 10000)).'.'.$file_ext_arr[count($file_ext_arr)-1];
	$targetFile = rtrim($targetPath,'/') . '/' .$new_file_name;
	// Validate the file type
	$fileTypes = array('jpg','jpeg','gif','png'); // File extensions
	$fileParts = pathinfo($_FILES['file_upload']['name']);
	
	if (in_array($fileParts['extension'],$fileTypes)) {
		move_uploaded_file($tempFile,$targetFile);
		echo $new_file_name.'|'.$_FILES['file_upload']['name'].'|'.$_FILES['file_upload']['size'];
	} else {
		echo 'Invalid file type.';
	}
}
/**
 * 循环创建目录
 *
 * @param 
 * @return string  
 */
function check_dir($dir, $mode = 0755) { 
	if (is_dir($dir) || @mkdir($dir,$mode)){ 
		return true; 
	}
	if (!$this->check_dir(dirname($dir),$mode)){
		return false; 
	}
	return @mkdir($dir,$mode); 
}
?>