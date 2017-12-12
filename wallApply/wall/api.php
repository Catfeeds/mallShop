<?php
@header ( "Content-type: text/html; charset=utf-8" );
include ('biaoqing.php');
include ("db.php");

$lastid = $_REQUEST ['lastid'];
$num = $lastid + 1;
$sql1 = "SELECT * FROM  `tp_wall_wall` where num>0 and `companyid`=" . $_SESSION ['cid'] . " and `num` = '$num' order by id asc limit 1 ";
$query1 = mysql_query ( $sql1, $link ) or die ( mysql_error () );
$q = mysql_fetch_row ( $query1 );
$wallnumsql = mysql_query("SELECT count(*) as wnum FROM  `tp_wall_wall` where num>0 and `companyid`=" . $_SESSION ['cid']);
$wallnum=mysql_fetch_assoc($wallnumsql);

if ($q == '' && $wallnum['wnum'] > 1) {
    for($num = $num +1; $q == ''; $num ++) {
        $sql1 = "SELECT * FROM  `tp_wall_wall` where num>0 and `companyid`=" . $_SESSION ['cid'] . " and `num` = '$num' limit 1 ";
        $query1 = mysql_query ( $sql1, $link );
        $q = mysql_fetch_row ( $query1 );
       
        // 当最大值是停止循环
        $result = mysql_query ( 'select num from `tp_wall_wall_num` where `companyid`=' . $_SESSION ['cid'] );
        $row = mysql_fetch_array ( $result, MYSQL_ASSOC );
        $maxid = $row ["num"];
        if ($maxid <= $num) {
            if ($xuanzezu [22]) {
                $num = 1;
            }else{
                break;
            }
        }
    }
    if($q){
        $q [5] = pack ( 'H*', $q [5] );
        @$q [4] = pack ( 'H*', $q [4] );
        $q = emoji_unified_to_html ( emoji_softbank_to_unified ( $q ) );
        $id = $q [0];
        $fakeid = $q [2];
        $num = $q [3];
        $content = $q [4];
        $content = biaoqing ( $content );
        $nickname = $q [5];
        $avatar = $q [6];
        $ret = $q [7];
        $fromtype = $q [8];
        $image = $q [9];
        if ($q [3]) {
            @$msg = array (
                    data => array (
                            array (
                                    "id" => $id,
                                    "fakeid" => $fakeid,
                                    "num" => $num,
                                    "content" => $content,
                                    "nickname" => $nickname,
                                    "avatar" => $avatar,
                                    "image" => $image,
                                    "fromtype" => $fromtype
                            )
                    ),
                    ret => 1
            );
            echo $msg = json_encode ( $msg );
        }
        if (! $q [3]) {
            @$msg = array (
                    data => array (),
                    ret => 0
            );
            echo $msg = json_encode ( $msg );
        }
    }
}else{
    
    $q [5] = pack ( 'H*', $q [5] );
    @$q [4] = pack ( 'H*', $q [4] );
    $q = emoji_unified_to_html ( emoji_softbank_to_unified ( $q ) );
    $id = $q [0];
    $fakeid = $q [2];
    $num = $q [3];
    $content = $q [4];
    $content = biaoqing ( $content );
    $nickname = $q [5];
    $avatar = $q [6];
    $ret = $q [7];
    $fromtype = $q [8];
    $image = $q [9];
    if ($q [3]) {
        @$msg = array (
                data => array (
                        array (
                                "id" => $id,
                                "fakeid" => $fakeid,
                                "num" => $num,
                                "content" => $content,
                                "nickname" => $nickname,
                                "avatar" => $avatar,
                                "image" => $image,
                                "fromtype" => $fromtype
                        )
                ),
                ret => 1
        );
        echo $msg = json_encode ( $msg );
    }
    if (! $q [3]) {
        @$msg = array (
                data => array (),
                ret => 0
        );
        echo $msg = json_encode ( $msg );
    }
}


?>