<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>UploadiFy讲解</title>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
<script src="jquery.uploadify.min.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="uploadify.css">
<style type="text/css">
body {
 font: 13px Arial, Helvetica, Sans-serif;
}
</style>
</head>
<body>
 <h1>Uploadify讲解由widuu提供</h1>
 <form>
  <div id="queue"></div>
  <input id="file_upload" name="file_upload" type="file" multiple="true">
 </form>
 <script type="text/javascript">
  <?php $timestamp = time();?>
  $(function() {
   $('#file_upload').uploadify({
    
    //上传文件时post的的数据
    'formData'     : {
     'timestamp' : '<?php echo $timestamp;?>',
     'token'     : '<?php echo md5('unique_salt' . $timestamp);?>',
     'id'  : 1
    },
    'swf'      : 'uploadify.swf',
    'uploader' : 'uploadify.php',
    /* 'onInit'   : function(index){
     alert('队列ID:'+index.settings.queueID);
    }, */
    'method'   : 'post', //设置上传的方法get 和 post
    //'auto'    : false, //是否自动上传 false关闭自动上传 true 选中文件后自动上传
    //'buttonClass' : 'myclass', //自定义按钮的样式
    //'buttonImage' : '按钮图片',
    'buttonText'  : '上传', //按钮显示的字迹
    //'fileObjName' : 'mytest'  //后台接收的时候就是$_FILES['mytest'] 
    //'checkExisting' : 'check-exists.php', //检查文件是否已经存在 返回0或者1
    'fileSizeLimit' : '100KB', //上传文件大小的限制
    'fileTypeDesc'  : '你需要一些文件',//可选择的文件的描述
    'fileTypeExts'  : '*.gif; *.jpg; *.png', //文件的允许上传的类型
    
    //上传的时候发生的事件
    'onUploadStart' : function(file){
      alert('开始上传了');       },
    'uploadLimit'   : 5, //设置最大上传文件的数量
    /*
    'onUploadComplete' : function(result){
        for (var i in result.post){
         alert(i+':::'+result[i]);
        }
       },
    */
    //文件上传成功的时候
    'onUploadSuccess' : function(file, data, response) {
     alert(data);
     },
     //
     'onUploadError' : function(file, errorCode, errorMsg, errorString) {
     alert(file.name + '上传失败原因:' + errorString); 
     },
     'itemTemplate' : '<span></span>',
     'height'  : 30, //设置高度 button
     'width'  : 30, //设置宽度
     'onDisable' : function(){
      alert('您禁止上传');
     },
     'onEnable'  : function(){
      alert('您可以继续上传了');
     },
     //当文件选中的时候
     'onSelect'  : function(file){
      alert(file.name+"已经添加到队列");
     }
   });
  });
  
 //一些常用的事件
 //$('#file_upload').uploadify('upload','*');    //用javascript 上传的方法
 //$('#file_upload').uploadify('stop','*');      //用javascript 停止上传的方法
 //$('#file_upload').uploadify('disable','*');   //用javascript 禁止上传的方法
 //$('#file_upload').uploadify('settings','buttonText',"设置上传按钮");   //设置一些属性
 //更多的请到官方网站www.uploadify.com/documentation/看讲解谢谢
 </script>
<?php
/*
 *检查文件是否存在的check-exists.php
 */
/*
$targetFolder = '/uploads'; 
if (file_exists($_SERVER['DOCUMENT_ROOT'] . $targetFolder . '/' . $_POST['filename'])) {
 echo 1;
} else {
 echo 0;
}
*/
?>
</body>
</html>