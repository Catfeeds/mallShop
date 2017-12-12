<?php
include_once ('../db.php'); // 连接数据库
include ('../biaoqing.php');
$lastperid = $_REQUEST ['lastperid'];
$action = $_GET ['action'];
if ($action == "") { // 读取数据，返回json
    $max = mysql_query ( "select max(id) from tp_wall_flag where `companyid`=" . $_SESSION ['cid'] . " and fakeid>0 order by id asc" );
    $maxid = mysql_fetch_row ( $max );
    if ($maxid [0] > $lastperid) {
        //$person = mysql_query ( "select * from tp_wall_flag where `companyid`=" . $_SESSION ['cid'] . " and id > ".$lastperid." and fakeid>0 order by id asc" );
        $person = mysql_query ( "select * from tp_wall_flag where `companyid`=" . $_SESSION ['cid'] . " and  fakeid>0 order by id asc" );
        while ( $row1 = mysql_fetch_array ( $person ) ) {
            $row1 ['nickname'] = pack ( 'H*', $row1 ['nickname'] );
            $row1 = emoji_unified_to_html ( emoji_softbank_to_unified ( $row1 ) );
            $arr [] = array (
                    'maxid' => $maxid [0],
                    'id' => $row1 ['id'],
                    'avatar' => $row1 ['avatar'],
                    'nickname' => $row1 ['nickname'] 
            );
        }
    } else {
        $arr [0] ['id'] = 0;
    }
    echo json_encode ( $arr );
} else if ($action == "reset") {
    $sqll2 = "update tp_wall_flag set othid=0 WHERE `companyid`=" . $_SESSION ['cid'];
    $queryy2 = mysql_query ( $sqll2 );
    $sqll = "update tp_wall_flag set status=2 where `companyid`=" . $_SESSION ['cid'] . " and status=3";
    $queryy = mysql_query ( $sqll );
    if ($queryy)
        echo '2';
} else if ($action == "ready") {
    $male = mysql_query ( "select count(*) from tp_wall_flag where `companyid`=" . $_SESSION ['cid'] . " and status=2 and fakeid>0 and sex=1" );
    $row1 = mysql_fetch_row ( $male );
    $female = mysql_query ( "select count(*) from tp_wall_flag where `companyid`=" . $_SESSION ['cid'] . " and status=2 and fakeid>0 and sex=2" );
    $row2 = mysql_fetch_row ( $female );
    $arr [0] = $row1 [0];
    $arr [1] = $row2 [0];
    $arr [2] = $row2 [0] + $row1 [0];
    echo json_encode ( $arr );
} else { // 标识中奖号码
    $id = $_POST ['id'];
    $toid = $_POST ['toid'];
    $sql = "update tp_wall_flag set status=3,othid=$toid where `companyid`=" . $_SESSION ['cid'] . " and id=$id";
    $query = mysql_query ( $sql );
    if ($query) { 
        $sql = "update tp_wall_flag set status=3,othid=$id where `companyid`=" . $_SESSION ['cid'] . " and id=$toid";
        $query = mysql_query ( $sql );
        if ($query)
            echo '1';
    }
}

?>