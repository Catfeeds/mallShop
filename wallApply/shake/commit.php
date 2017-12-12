<?php 
	if(!isset($_SESSION)){
		session_start();
	}
	$point=$_GET['point'];
	$wecha_id=$_GET['wecha_id']; 
	$companyid = $_GET['companyid'];
	/* $point=0;//$_GET['point'];
	$wecha_id='oY8S1jtjtmm1c7bfc2OIAkMsHt80';//$_GET['wecha_id'];
	var_dump($point);
	var_dump($wecha_id);echo '<hr/>'; */
	//实例化一个memcache对象
	if(!empty($_SERVER['HTTP_APPNAME'])){
   		@$mem = memcache_init();
	}else if(class_exists("Memcache")){
		@$mem=new Memcache;
		@$mem->connect('localhost','11211');
	}
	//var_dump($mem);echo '<pre />';
	hasmysql();
	/* if(!empty($mem)){
	    hasmemcache($mem);
	}else{
    	hasmysql();
	} */
	function hasmemcache($mem){
        global $wecha_id,$point,$companyid;
    	//从memcache服务器获取数据
    	$data = $mem->get(realpath("..").$wecha_id);
        //判断memcache是否有数据
    	if( !$data ){
       		require ('db.php');
         	include('../wall/biaoqing.php');
         	$sql="SELECT * FROM  `tp_wall_shake_toshake` WHERE `companyid`=".$companyid." and `wecha_id`='$wecha_id'";
         	//echo $sql;echo '<hr/>';
        	$query1=mysql_query($sql,$link) or die(mysql_error());
			$q=mysql_fetch_assoc($query1);
			if($q){
	            $q['phone']=pack('H*',$q['phone']);
	            $q['phone']=emoji_unified_to_html(emoji_softbank_to_unified($q['phone']));
				$mem->set(realpath("..").$q['wecha_id'],$q, MEMCACHE_COMPRESSED, 3600);
			}
    	}
        $data = $mem->get(realpath("..").$wecha_id);
   		$start=realpath("..")."UPDATE  `tp_wall_wall_config` WHERE `companyid`=".$companyid."  SET  `isopen` = ";
		$key2 = substr(md5($start), 10, 8);
		$ispen = $mem->get($key2);
		//echo json_encode($ispen).'aaa';
		if($data) {
        	$data['point'] = $point;
        	$mem->set(realpath("..").$wecha_id, $data, MEMCACHE_COMPRESSED, 3600);
		}else{
    		$ispen = 3;
		}
		if(empty($ispen)){
    		$ispen = 1;
		}
		echo $ispen;
     	$mem->close(); //关闭memcache连接
	}
	function hasmysql(){
    	global $wecha_id,$point,$companyid;
 		require ('db.php');
		$ispen=$xuanzezu[6];
		$sql = "select `wecha_id` from `tp_wall_shake_toshake` where `companyid`=".$companyid." and `wecha_id`='$wecha_id'";
		if(mysql_num_rows(mysql_query($sql))>0) {
			if($point != '0'){
				$sql_shake="UPDATE  `tp_wall_shake_toshake` SET  `point` = ".$point." WHERE `companyid`=".$companyid." and `wecha_id` = '$wecha_id'";
			}
		}else{
			$ispen = 3;
		}
		if(empty($ispen)){
			$ispen = 1;
		} 
		//echo $sql_shake;echo '<hr/>';
		mysql_query($sql_shake);
		mysql_close($link);
		echo "$ispen";
	}
?>

