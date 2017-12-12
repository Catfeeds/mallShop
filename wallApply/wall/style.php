<?php
@header("Content-type: text/html; charset=utf-8");
include_once('db.php');
$sty_name=array();//name数组，
$sty_name[1]="新年模板";
$sty_name[2]="简约模板";
$sty_name[3]="星空模板";
$sty_name[4]="小清新模板";
$sty_name[5]="年会模板二"; 
$sty_name[6]="年会模板一"; 
//$sty_name[7]="喜来登";
$sty_name[7]="自定义模板";

$sty_img=array();//图片数组
$sty_img[1]="../img/xinnian.png";
$sty_img[2]="../img/jianyue.png";
$sty_img[3]="../img/xingkong.png";
$sty_img[4]="../img/xiaoqingxin.png";
$sty_img[5]="../img/nianhui2.png";
$sty_img[6]="../img/nianhui1.jpg"; 
//$sty_img[7]="../img/diandeng.jpg";
if($xuanzezu[41]==''){$sty_img[7]="../img/zidingyi.jpg";}else{$sty_img[7]=$xuanzezu[41];}

$sty_lnk=array();//链接数组
$sty_lnk[1]="index.php?style=xinnian";
$sty_lnk[2]="index.php?style=jianyue";
$sty_lnk[3]="index.php?style=xingkong";
$sty_lnk[4]="index.php?style=xiaoqingxin";
$sty_lnk[5]="index.php?style=nianhui2";
$sty_lnk[6]="index.php?style=nianhui1";
//$sty_lnk[7]="index.php?style=diandeng";
$sty_lnk[7]="index.php?style=zidingyi"; 
?>