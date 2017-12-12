<?php

@header("Content-type: text/html; charset=utf-8");
if(!isset($_SESSION)){
    session_start();
}
if(isset($_SESSION['openid']) && $_SESSION['openid'] == true){
	$openid = $_SESSION['openid'];
}else{
	echo "<script>window.location='error.php';</script>";
}
$companyid = $_GET['companyid'];
if(isset($_SESSION['companyid']) && $_SESSION['companyid'] == true){
	$companyid = $_SESSION['companyid'];
}else{
	if($openid != ''){
		$_SESSION['companyid']= $companyid ;
	}
}
include ('isweixin.php');

include("db.php");

$sql = "SELECT * FROM `tp_wall_wall_config` where `companyid`=".$companyid;
$query_vote = mysql_query($sql);
$voteInfo = mysql_fetch_assoc($query_vote);

if(isset($_GET['do'])){

	$do = $_GET['do'];

}else{

	die("invild action");

}



switch($do){

	    case "vote":
		vote();	
 		break;

		

		}

function vote(){
    global $companyid,$voteInfo;
$id = $_POST['voteid'];
$openid = $_SESSION['openid'];
if($voteInfo['votecannum'] != count($id)){
	echo "<script>alert('对不起，请投".$voteInfo['votecannum']."票！');location.href='index.php';</script>";
	die;
}
$sql_vote_check = "SELECT * FROM `tp_wall_flag` where `companyid`=".$companyid." and `openid` = '{$openid}'";
$query_vote_check = mysql_query($sql_vote_check);
$vote_check = mysql_fetch_row($query_vote_check);
if($vote_check[3]!=0 || $vote_check <= 0){
	echo "<script>alert('您已经投过票了！');location.href='index.php';</script>";
	die;
}


$idvalues=implode(",",$id); 
    $sql_flag="UPDATE  `tp_wall_flag` SET  `vote` =  '{$idvalues}' WHERE `companyid`=".$companyid." and `openid` = '{$openid}'";
	$succed=mysql_query($sql_flag) or die(mysql_error());
foreach ($id as $value){
	$sql_vote="UPDATE  `tp_wall_vote` SET  `res` =  `res`+1 WHERE `companyid`=".$companyid." and `id` = '{$value}'";
	$succed=mysql_query($sql_vote) or die(mysql_error());
}
echo "<script>alert('恭喜，投票成功！');location.href='index.php';</script>";

}
	
	

?>