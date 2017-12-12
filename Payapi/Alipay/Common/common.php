<?php
    /**
     * 
     */
    /**
     * 	作用：以post方式提交xml到对应的接口url
     */
    function postXmlCurl($xml_data,$url,$second=30)
    {
        //初始化curl
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
		$header[] = "Content-type: text/xml";//定义content-type为xml
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_data);
		$response = curl_exec($ch);
		if(curl_errno($ch))
		{
		    print curl_error($ch);
		}
		curl_close($ch);
		
	}
    /**
     * 
     * 记录日志
     * 
     * @param unknown $text  记录内容
     * @param unknown $filename  文件名（请确保文件存在并且有可写权限）
     * @param unknown $type 文件内容写入类型  FILE_USE_INCLUDE_PATH（对文件覆盖原内容）,FILE_APPEND（对文件追加内容）,LOCK_EX（对文件上锁 ）
     * @author Lando<806728685@qq.com>
     * @since  2015-8-13
     */
    function writeLog( $data, $filename, $type = FILE_APPEND) {
        // $text=iconv("GBK", "UTF-8//IGNORE", $text);
        $text = characet ( $data );
        if(is_file($filename)==false){
            $tempFile = fopen($filename,'a');
            fclose($tempFile);
        }
        file_put_contents ($filename, $data, $type );
    }
    /**
     * 
     * 转换文本编码 为UTF-8
     * 
     * @param unknown $data  带转换数据
     * @return string
     * @author Lando<806728685@qq.com>
     * @since  2015-8-13
     */
    function characet($data) {
        if (! empty ( $data )) {
            $fileType = mb_detect_encoding ( $data, array (
                    'UTF-8',
                    'GBK',
                    'GB2312',
                    'LATIN1',
                    'BIG5'
            ) );
            if ($fileType != 'UTF-8') {
                $data = mb_convert_encoding ( $data, 'UTF-8', $fileType );
            }
        }
        return $data;
    }
    /**
     * GET 请求
     * @param string $url
     */
    function http_get($url){
        $oCurl = curl_init();
        if(stripos($url,"https://")!==FALSE){
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($oCurl, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1
        }
        curl_setopt($oCurl, CURLOPT_URL, $url);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1 );
        $sContent = curl_exec($oCurl);
        $aStatus = curl_getinfo($oCurl);
        curl_close($oCurl);
        if(intval($aStatus["http_code"])==200){
            return $sContent;
        }else{
            return false;
        }
    }
    /**
     * POST 请求
     * @param string $url
     * @param array $param
     * @param boolean $post_file 是否文件上传
     * @return string content
     */
    function http_post($url,$param,$post_file=false){
        $oCurl = curl_init();
        if(stripos($url,"https://")!==FALSE){
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($oCurl, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1
        }
        if (is_string($param) || $post_file) {
            $strPOST = $param;
        } else {
            $aPOST = array();
            foreach($param as $key=>$val){
                $aPOST[] = $key."=".urlencode($val);
            }
            $strPOST =  join("&", $aPOST);
        }
        curl_setopt($oCurl, CURLOPT_URL, $url);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt($oCurl, CURLOPT_POST,true);
        curl_setopt($oCurl, CURLOPT_POSTFIELDS,$strPOST);
        $sContent = curl_exec($oCurl);
        $aStatus = curl_getinfo($oCurl);
        curl_close($oCurl);
        if(intval($aStatus["http_code"])==200){
            return $sContent;
        }else{
            return false;
        }
    }
    /**
     * 
     *  函数名称:encrypt
     *  函数作用:加密解密字符串
     *  使用方法:
     *  加密     :encrypt('str','E','nowamagic');
     *  解密     :encrypt('被加密过的字符串','D','nowamagic');
     * 
     * @param unknown $string  :需要加密解密的字符串
     * @param unknown $operation  :判断是加密还是解密:E:加密   D:解密
     * @param string $key  :加密的钥匙(密匙);
     * @return string|mixed
     * @author Lando<806728685@qq.com>
     * @since  2015-8-13
     */
    function encrypt($string, $operation, $key = '') {
        $key = md5 ( $key );
        $key_length = strlen ( $key );
        $string = $operation == 'D' ? base64_decode ( $string ) : substr ( md5 ( $string . $key ), 0, 8 ) . $string;
        $string_length = strlen ( $string );
        $rndkey = $box = array ();
        $result = '';
        for($i = 0; $i <= 255; $i ++) {
            $rndkey [$i] = ord ( $key [$i % $key_length] );
            $box [$i] = $i;
        }
        for($j = $i = 0; $i < 256; $i ++) {
            $j = ($j + $box [$i] + $rndkey [$i]) % 256;
            $tmp = $box [$i];
            $box [$i] = $box [$j];
            $box [$j] = $tmp;
        }
        for($a = $j = $i = 0; $i < $string_length; $i ++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box [$a]) % 256;
            $tmp = $box [$a];
            $box [$a] = $box [$j];
            $box [$j] = $tmp;
            $result .= chr ( ord ( $string [$i] ) ^ ($box [($box [$a] + $box [$j]) % 256]) );
        }
        if ($operation == 'D') {
            if (substr ( $result, 0, 8 ) == substr ( md5 ( substr ( $result, 8 ) . $key ), 0, 8 )) {
                return substr ( $result, 8 );
            } else {
                return '';
            }
        } else {
            return str_replace ( '=', '', base64_encode ( $result ) );
        }
    }
	//src 图片完整路径
	//$direction 1 顺时针90   2 逆时针90
	function imgturn($src,$direction=1){
		$imgInfo = pathinfo($src);
		$ext = $imgInfo['extension'];
		switch ($ext) {
			case 'gif':
				$img = imagecreatefromgif($src);
				break;
			case 'jpg':
			case 'jpeg':
				$img = imagecreatefromjpeg($src);
				break;
			case 'png':
				$img = imagecreatefrompng($src);
				break;
			default:
				die('图片格式错误!');
				break;
		}
		$width = imagesx($img);
		$height = imagesy($img);
		$img2 = imagecreatetruecolor($height,$width);
		//顺时针旋转90度
		if($direction==1)
		{
			for ($x = 0; $x < $width; $x++) {
				for($y=0;$y<$height;$y++) {
					imagecopy($img2, $img, $height-1-$y,$x, $x, $y, 1, 1);
				}
			}
		}else if($direction==2) {
			//逆时针旋转90度
			for ($x = 0; $x < $height; $x++) {
				for($y=0;$y<$width;$y++) {
					imagecopy($img2, $img, $x, $y, $width-1-$y, $x, 1, 1);
				}
			}
		}
		switch ($ext) {
			case 'jpg':
			case "jpeg":
				imagejpeg($img2, $src, 100);
				break;
	
			case "gif":
				imagegif($img2, $src, 100);
				break;
	
			case "png":
				imagepng($img2, $src, 100);
				break;
	
			default:
				die('图片格式错误!');
				break;
		}
		imagedestroy($img);
		imagedestroy($img2);
	}
	/**
	 * 获得IP地址（服务器IP）
	 * @return Ambigous <unknown, boolean>
	 */
	function get_real_ip()
	{
		$ip=false;
		if(!empty($_SERVER["HTTP_CLIENT_IP"])){
			$ip = $_SERVER["HTTP_CLIENT_IP"];
		}
		if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
			$ips = explode (", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
			if($ip){
				array_unshift($ips, $ip); $ip = FALSE;
			}
			for($i = 0; $i < count($ips); $i++){
				if (!eregi ("^(10|172\.16|192\.168)\.", $ips[$i])){
					$ip = $ips[$i];
					break;
				}
			}
		}
		return($ip ? $ip : $_SERVER['REMOTE_ADDR']);
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
	 * 转换字节  大小
	 * Enter description here ...
	 * @param unknown_type $size
	 */
	function getRealSize($size)
	{
		$kb = 1024;          //Kilobyte
		$mb = 1024 * $kb;    //Megabyte
		$gb = 1024 * $mb;    //Gigabyte
		$tb = 1024 * $gb;    //Terabyte
		if($size < $kb){
			return $size." B";
		}else if($size < $mb){
			return round($size/$kb,2)." KB";
		}else if($size < $gb){
			return round($size/$mb,2)." MB";
		}else if($size < $tb){
			return round($size/$gb,2)." GB";
		}else{
			return round($size/$tb,2)." TB";
		}
	}

	/**
	 * 获得字符编码值
	 * Enter description here ...
	 * @param unknown_type $word
	 */
	function getUnicodeFromUTF8($word) {  
	  //获取其字符的内部数组表示，所以本文件应用utf-8编码！  
	  if (is_array( $word))  
	     $arr = $word;  
	  else    
	     $arr = str_split($word);  
	  //此时，$arr应类似array(228, 189, 160)  
	  //定义一个空字符串存储  
	   $bin_str = '';  
	  //转成数字再转成二进制字符串，最后联合起来。  
	   foreach ($arr as $value)  
	     $bin_str .= decbin(ord($value));  
	  //此时，$bin_str应类似111001001011110110100000,如果是汉字"你"  
	  //正则截取  
	   $bin_str = preg_replace('/^.{4}(.{4}).{2}(.{6}).{2}(.{6})$/','$1$2$3', $bin_str);  
	  //此时， $bin_str应类似0100111101100000,如果是汉字"你"  
	  return bindec($bin_str); //返回类似20320， 汉字"你"  
	  //return dechex(bindec($bin_str)); //如想返回十六进制4f60，用这句  
	}

	/**
	 * 删除目录 及其 下面的文件
	 */
	function del_dir($dir) {
	  //先删除目录下的文件：
	  $dh=opendir($dir);
	  while ($file=readdir($dh)) {
	    if($file!="." && $file!="..") {
	      $fullpath=$dir."/".$file;
	      if(!is_dir($fullpath)) {
	          unlink($fullpath);
	      } else {
	          del_dir($fullpath);
	      }
	    }
	  }
	  closedir($dh);
	  //删除当前文件夹：
	  if(rmdir($dir)) {
	    return true;
	  }else{
	    return false;
	  }
	}
	/**
	 * 返回json 数据
	 * @access   public
     * @param    array 
     * @return   json
	 */
	function print_json($var){
		return json_encode($var);
	}
	/**
	 * 格式化数据
	 * @param $code 0 成功；-1 失败；1 警告 2 系统错误
	 * @param $msg 返回消息
	 * @param $data 返回数据
	 */
	function build_return($code = 0, $msg = '', $data = array()){
		$data = array(
			'code' => $code,
			'msg' => $msg,
			'data' => $data,
		);
		return $data;
	}
	
	/**
	 * 格式化数据2
	 * @param $info
	 * @param $status y 成功 ; n 失败
	 */
	function valide_return($info = '', $status = 'y'){
		$data = array(
			'status' => $status,
			'info' => $info,
		);
		return $data;
	}
	/**
	 * 获得唯一的 纯数字编码
	 * Enter description here ...
	 * $type  积分类型 ： 1：代表积分 sn 码； 2:代表 消费SN 码；3:储值消费交易流水号;4:充值交易流水号;5:餐饮订座交易流水号;6:电子券交易流水号;7:特权交易流水号;8:万能预约交易流水号;9:积点交易流水号;10:经验值流水号;
	 */
	function getUniqueSN($type){
		if ($type == 1){
			$top = 'vp';
		}elseif ($type == 2){
			$top = 's';
		}elseif ($type == 3){
			$top = 'ps';
		}elseif ($type == 4){
			$top = 'r';
		}elseif ($type == 5){
			$top = 'd';
		}elseif ($type == 6){
			$top = 'ev';
		}elseif ($type == 7){
			$top = 'pr';
		}elseif ($type == 8){
			$top = 'cb';
		}elseif ($type == 9){
			$top = 'bp';
		}elseif ($type == 10){
			$top = 'exp';
		}
		$top .= substr(time(),-5).substr(microtime(),2,6);
		return  $top;
	}
	/**
	 * 获得唯一的 纯数字编码
	 * Enter description here ...
	 */
	function getUniqueNumber(){
		return  date('ymd').substr(time(),-3).substr(microtime(),2,6);
	}
	/**
	 * fast_uuid 为模型生成 64 位整数或混淆字符串的不重复 ID
	 *
	 * 通常我们习惯使用自增字段来做主键，简单易用。
	 *
	 * 但在于大规模应用中，使用自增字段将难以实现分布式数据库架构。
	 * 并且对数据进行纵向和横向分割（分表分库）造成障碍。
	 * 此时最好的解决方案是使用 UUID。
	 *
	 * 但 UUID 不是每一种数据库都支持，用字符串来模拟效率太低。
	 * 并且如果通过 URL 传递，UUID 也显得太长。
	 *
	 * fast_uuid 方法提供了另一种解决方案：
	 * 使用 64bit 整数存储主键，主键由 fast_uuid 方法在创建记录时调用生成。
	 *
	 * 参数 suffix_len指定 生成的 ID 值附加多少位随机数，默认值为 3。
	 *    即便不附加随机数也不会生成重复 ID，但附加的随机数可以让 ID 更难被猜测。
	 *
	 * @param int suffix_len
	 *
	 * @return string
	 */
	function get_order_id($suffix_len=3){
		//! 计算种子数的开始时间
		static $being_timestamp = 1336681180;// 2012-5-10
	
		$time = explode(' ', microtime());
		$id = ($time[1] - $being_timestamp) . sprintf('%06u', substr($time[0], 2, 6));
		if ($suffix_len > 0)
		{
			$id .= substr(sprintf('%010u', mt_rand()), 0, $suffix_len);
			if($suffix_len > 24){
				$max = $suffix_len-24;
				for ($i = 1;$i<=$max;$i++){
					$id .= rand(0, 9);
				}
			}
		}
		return $id;
	}
	/**
	 * 获得唯一的 7位数字编码
	 * Enter description here ...
	 */
	function get_seven_number(){
		return  substr(microtime(),2,6).rand(0, 9);
	}
	/**
	 * 获得唯一的 6位数字编码
	 * Enter description here ...
	 */
	function get_six_number(){
		return  substr(microtime(),2,6);
	}
	/**
	 * 生成唯一标示符
	 */
	function create_guid() { 
		$charid = strtoupper(md5(uniqid(mt_rand(), true))); 
		$hyphen = chr(45);
		$uuid = chr(123).substr($charid, 0, 8).$hyphen.substr($charid, 8, 4).$hyphen.substr($charid,12, 4).$hyphen.substr($charid,16, 4).$hyphen.substr($charid,20,12).chr(125);
		return $uuid; 
	}
	/**
	 * 过滤得到安全的html
	 * @param string $text 待过滤的字符串
	 * @param string $type 默认为string,可选项:INT,FLOAT,BOOL,WORD,ALNUM,CMD,BASE64,ARRAY,PATH,USERNAME
	 * @param bool $tagsMethod true为开启黑名单，白名单失效。false为开启白名单，黑名单失效.
	 * @param bool $attrMethod 同上
	 * @param array $tags 标签的过滤白名单
	 * @param array $attr 属性名的过滤白名单
	 * @param array $tagsBlack 标签的过滤黑名单
	 * @param array $attrBlack 标签中属性的过滤黑名单
	 */
	
	//输出安全的html
	 function h($text, $tags = null){
		$text	=	trim($text);
		$text	=	preg_replace('/<!--?.*-->/','',$text);
		//完全过滤注释
		$text	=	preg_replace('/<!--?.*-->/','',$text);
		//完全过滤动态代码
		$text	=	preg_replace('/<\?|\?'.'>/','',$text);
		//完全过滤js
		$text	=	preg_replace('/<script?.*\/script>/','',$text);
	
		$text	=	str_replace('[','&#091;',$text);
		$text	=	str_replace(']','&#093;',$text);
		$text	=	str_replace('|','&#124;',$text);
		//过滤换行符
		$text	=	preg_replace('/\r?\n/','',$text);
		//br
		$text	=	preg_replace('/<br(\s\/)?'.'>/i','[br]',$text);
		$text	=	preg_replace('/(\[br\]\s*){10,}/i','[br]',$text);
		//过滤危险的属性，如：过滤on事件lang js
		while(preg_match('/(<[^><]+) (lang|on|action|background|codebase|dynsrc|lowsrc)[^><]+/i',$text,$mat)){
			$text=str_replace($mat[0],$mat[1],$text);
		}
		while(preg_match('/(<[^><]+)(window\.|javascript:|js:|about:|file:|document\.|vbs:|cookie)([^><]*)/i',$text,$mat)){
			$text=str_replace($mat[0],$mat[1].$mat[3],$text);
		}
		if(empty($tags)) {
			$tags = 'table|tbody|td|th|tr|i|b|u|strong|img|p|br|div|span|em|ul|ol|li|dl|dd|dt|a|alt|h[1-9]?';
			$tags.= '|object|param|embed';	// 音乐和视频
		}
		//允许的HTML标签
		$text	=	preg_replace('/<(\/?(?:'.$tags.'))( [^><\[\]]*)?>/i','[\1\2]',$text);
		//过滤多余html
		$text	=	preg_replace('/<\/?(html|head|meta|link|base|basefont|body|bgsound|title|style|script|form|iframe|frame|frameset|applet|id|ilayer|layer|name|style|xml)[^><]*>/i','',$text);
		//过滤合法的html标签
		while(preg_match('/<([a-z]+)[^><\[\]]*>[^><]*<\/\1>/i',$text,$mat)){
			$text=str_replace($mat[0],str_replace('>',']',str_replace('<','[',$mat[0])),$text);
		}
		//转换引号
		while(preg_match('/(\[[^\[\]]*=\s*)(\"|\')([^\2\[\]]+)\2([^\[\]]*\])/i',$text,$mat)){
			$text = str_replace($mat[0], $mat[1] . '|' . $mat[3] . '|' . $mat[4],$text);
		}
		
		//转换其它所有不合法的 < >
		$text	=	str_replace('<','&lt;',$text);
		$text	=	str_replace('>','&gt;',$text);
	    $text   =   str_replace('"','&quot;',$text);
	    //$text   =   str_replace('\'','&#039;',$text);
		 //反转换
		$text	=	str_replace('[','<',$text);
		$text	=	str_replace(']','>',$text);
		$text	=	str_replace('|','"',$text);
		//过滤多余空格
		$text	=	str_replace('  ',' ',$text);

		return $text;
	}
	/**
	 * 过滤为安全的文本
	 *
	 * @param string  $text
	 * @param boolean $parse_br    是否转换换行符
	 * @param int     $quote_style ENT_NOQUOTES:(默认)不过滤单引号和双引号 ENT_QUOTES:过滤单引号和双引号 ENT_COMPAT:过滤双引号,而不过滤单引号
	 * @return string|null string:被转换的字符串 null:参数错误
	 */
	function get_safe_text($text,$type='html',$tagsMethod=true,$attrMethod=true,$xssAuto = 1,$tags=array(),$attr=array(),$tagsBlack=array(),$attrBlack=array()){
	
	    //无标签格式
	    $text_tags	=	'';
	
	    //只存在字体样式
	    $font_tags	=	'<i><b><u><s><em><strong><font><big><small><sup><sub><bdo><h1><h2><h3><h4><h5><h6>';
	
	    //标题摘要基本格式
	    $base_tags	=	$font_tags.'<p><br><hr><a><img><map><area><pre><code><q><blockquote><acronym><cite><ins><del><center><strike>';
	
	    //兼容Form格式
	    $form_tags	=	$base_tags.'<form><input><textarea><button><select><optgroup><option><label><fieldset><legend>';
	
	    //内容等允许HTML的格式
	    $html_tags	=	$base_tags.'<ul><ol><li><dl><dd><dt><table><caption><td><th><tr><thead><tbody><tfoot><col><colgroup><div><object><embed>';
	
	    //专题等全HTML格式
	    $all_tags	=	$form_tags.$html_tags.'<!DOCTYPE><html><head><title><body><base><basefont><script><noscript><applet><object><param><style><frame><frameset><noframes><iframe>';
	
	    //过滤标签
	    $text	=	strip_tags($text, ${$type.'_tags'} );
	
	        //过滤攻击代码
	        if($type!='all'){
	            //过滤危险的属性，如：过滤on事件lang js
	            while(preg_match('/(<[^><]+) (onclick|onload|unload|onmouseover|onmouseup|onmouseout|onmousedown|onkeydown|onkeypress|onkeyup|onblur|onchange|onfocus|action|background|codebase|dynsrc|lowsrc)([^><]*)/i',$text,$mat)){
	                $text	=	str_ireplace($mat[0],$mat[1].$mat[3],$text);
	            }
	            while(preg_match('/(<[^><]+)(window\.|javascript:|js:|about:|file:|document\.|vbs:|cookie)([^><]*)/i',$text,$mat)){
	                $text	=	str_ireplace($mat[0],$mat[1].$mat[3],$text);
	            }
	        }
	        return $text;
	}
	/**
	 * 转换纯文本
	 *
	 * @param string  $text
	 * @param boolean $parse_br    是否转换换行符
	 * @param int     $quote_style ENT_NOQUOTES:(默认)不过滤单引号和双引号 ENT_QUOTES:过滤单引号和双引号 ENT_COMPAT:过滤双引号,而不过滤单引号
	 * @return string|null string:被转换的字符串 null:参数错误
	 */
	function get_text($text,$parseBr=false){
	    $text = htmlspecialchars_decode($text);
	    $text	=	get_safe_text($text,'text');
	    if(!$parseBr){
	        $text	=	str_ireplace(array("\r","\n","\t","&nbsp;"),'',$text);
	        $text	=	htmlspecialchars($text,ENT_QUOTES);
	    }else{
	        $text	=	htmlspecialchars($text,ENT_QUOTES);
	        $text	=	nl2br($text);
	    }
	    $text	=	trim($text);
	    return $text;
	}
	/**
	 *   这里提供一个函数可较好地解决substr遇到中文字符的问题。
	 *   中文字符按2个长度单位来计算，使得中英文混用环境下字符串截取结果最后的显示长度接近；
	 *   舍弃最后一个不完整字符，保证不会出现显示上的乱码；且兼容了中文字符常用的utf-8编码和GB2312编码，
	 *   有很好的通用性。
	 *   $string:被截取的字符串
	 *   $length:需要截取的字节数
	 */
	function get_substr($string, $length, $encoding  = 'utf-8',$end_str = '') {     
	  $string = trim($string);       
	  if($length && strlen($string) > $length) {
		//截断字符         $wordscut = '';         
		if(strtolower($encoding) == 'utf-8') {             
			//utf8编码            
			 $n = 0;         
			 $tn = 0;             
			 $noc = 0;             
			 while ($n < strlen($string)) {                 
			 	$t = ord($string[$n]);                 
			 	if($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {                     
			 		$tn = 1;                     
			 		$n++;                     
			 		$noc++;                 
			 	} elseif(194 <= $t && $t <= 223) {                     
			 		$tn = 2;                     
			 		$n += 2;                     
			 		$noc += 2;                 
			 	} elseif(224 <= $t && $t < 239) {                     
			 		$tn = 3;                     
			 		$n += 3;                     
			 		$noc += 2;                 
			 	} elseif(240 <= $t && $t <= 247) {                     
			 		$tn = 4;                     
			 		$n += 4;                     
			 		$noc += 2;                 
			 	} elseif(248 <= $t && $t <= 251) {                     
			 		$tn = 5;                     
			 		$n += 5;                     
			 		$noc += 2;                 
			 	} elseif($t == 252 || $t == 253) {                     
			 		$tn = 6;                     
			 		$n += 6;                     
			 		$noc += 2;                 
			 	} else {                     
			 		$n++;                 
			 	}                 
			 	if ($noc >= $length) {                     
			 		break;                 
			 	}             
			 }             
			 if ($noc > $length) {                 
			 	$n -= $tn;             
			 }             
			 $wordscut = substr($string, 0, $n);         
		 } else {             
			for($i = 0; $i < $length - 1; $i++) {                 
				if(ord($string[$i]) > 127) {                     
					$wordscut .= $string[$i].$string[$i + 1];                     
					$i++;                
				 } else {                     
				 	$wordscut .= $string[$i];                 
				 }            
			 }
			 $wordscut .= $wordscut;        
		 }
		 $string = $wordscut;  
		 if ($end_str){
		 	if(strlen($string) > $length){
			 	$string .= $end_str;
		 	}
		 } 
	   }    
	  return trim($string); 
	}
	/**
	 * 中文截取
	 * @param unknown $str
	 * @param string $encodeType
	 * @param number $start
	 * @param number $length
	 * @param string $hasSuffix
	 * @param string $suffix
	 * @return boolean|string|unknown
	 */
	function zh_substr($str, $encodeType='utf-8',$start = 0, $length = 20, $hasSuffix = false, $suffix = '。。。') {
		$res['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
		$res['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
		$res['gbk']= "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
		$res['big5'] = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
		if(!array_key_exists($encodeType, $res)){
			return false;
		}
		$re=$res[$encodeType];
		preg_match_all ( $re, $str, $match );
		$slice = join ( "", array_slice ( $match [0], $start, $length ) );
		if ($hasSuffix) {
			return $slice . $suffix;
		} else {
			return $slice;
		}
	}
	/**
	 * 转换为安全的纯文本
	 *
	 * @param string  $text
	 * @param boolean $parse_br    是否转换换行符
	 * @param int     $quote_style ENT_NOQUOTES:(默认)不过滤单引号和双引号 ENT_QUOTES:过滤单引号和双引号 ENT_COMPAT:过滤双引号,而不过滤单引号
	 * @return string|null string:被转换的字符串 null:参数错误
	 */
	function get_html_text($text, $parse_br = false, $quote_style = 0)
	{
		if (is_numeric($text))
			$text = (string)$text;
	
		if (!is_string($text))
			return null;
	
		if (!$parse_br) {
			$text = str_replace(array("\r","\n","\t"), ' ', $text);
		} else {
			$text = nl2br($text);
		}
	
		//$text = stripslashes($text);
		$text = htmlspecialchars($text, $quote_style, 'UTF-8');
	
		return $text;
	}
	/**
	 * 格式化时间来友好的显示时间
	 * @access   public
     * @param    number $sTime 待显示的时间
     * @param    string $type  类型 | mohu | zhymdhis | zhymdhi | zhymd | zhmd | ymdhis | payymdhis | ymdhi | ymdh | ymd | ym | md | .ymdhi | .ymd | .mdhi | .md | /ymd | /ym | /mdhi | /md | y | m | d | h | i 
	 * @param	 boole  $alt   已失效
     * @return   string
	 */
	function format_time($sTime,$type = 'normal') {
		$cTime		=	time();
		$dTime		=	$cTime - $sTime;
		$dDay		=	intval(date("z",$cTime)) - intval(date("z",$sTime));
		$dYear		=	intval(date("Y",$cTime)) - intval(date("Y",$sTime));
		if(empty($sTime)){
			return '';
			exit();
		}
		if($type=='mohu'){
			if( $dTime < 60 ){
				return $dTime."秒前";
			}elseif( $dTime < 3600 ){
				return intval($dTime/60)."分钟前";
			}elseif( $dTime >= 3600 && $dDay == 0  ){
				return intval($dTime/3600)."小时前";
			}elseif( $dDay > 0 && $dDay<=7 ){
				return intval($dDay)."天前";
			}elseif( $dDay > 7 &&  $dDay <= 30 ){
				return intval($dDay/7) . '周前';
			}elseif( $dDay > 30 ){
				return intval($dDay/30) . '个月前';
			}
		}elseif($type=='zhymdhis'){
			return date("Y年m月d日 H:i:s",$sTime);
		}elseif($type=='zhymdhi'){
			return date("Y年m月d日 H:i",$sTime);
		}elseif($type=='zhymd'){
			return date("Y年m月d日",$sTime);
		}elseif($type=='zhmd'){
			return date("m月d日",$sTime);
		}elseif($type=='ymdhis'){
			return date("Y-m-d H:i:s",$sTime);
		}elseif($type=='payymdhis'){
			return date("YmdHis",$sTime);
		}elseif($type=='ymdhi'){
			return date("Y-m-d H:i",$sTime);
		}elseif($type=='ymdh'){
			return date("Y-m-d H",$sTime);
		}elseif($type=='ymd'){
			return date("Y-m-d",$sTime);
		}elseif($type=='ym'){
			return date("Y-m",$sTime);
		}elseif($type=='md'){
			return date("m-d",$sTime);
		}elseif($type=='.ymdhi'){
			return date('Y.m.d H:i',$sTime);
		}elseif($type=='.ymd'){
			return date('Y.m.d',$sTime);
		}elseif($type=='.mdhi'){
			return date('m.d H:i',$sTime);
		}elseif($type=='.md'){
			return date('m.d',$sTime);
		}elseif($type=='/ymd'){
			return date("Y/m/d",$sTime);
		}elseif($type=='/ym'){
			return date("Y/m",$sTime);
		}elseif($type=='/mdhi'){
			return date("m/d H:i",$sTime);
		}elseif($type=='/md'){
			return date("m/d",$sTime);
		}elseif($type=='y'){
			return date("Y",$sTime);
		}elseif($type=='m'){
			return date("m",$sTime);
		}elseif($type=='d'){
			return date("d",$sTime);
		}elseif($type=='h'){
			return date("H",$sTime);
		}elseif($type=='i'){
			return date("i",$sTime);
		}elseif($type=='ymd'){
			return date("Y-m-d",$sTime);
		}elseif($type=='md'){
			return date("m-d",$sTime);
		}elseif($type=='.ymd'){
			return date('Y.m.d',$sTime);
		}elseif($type=='ym'){
			return date("Y-m",$sTime);
		}elseif($type=='ymdh'){
			return date("Y-m-d H",$sTime);
		}elseif($type=='ymdhi'){
			return date("Y-m-d H:i",$sTime);
		}elseif($type=='ymdhis'){
			return date("Y-m-d H:i:s",$sTime);
		}elseif($type=='/ymd'){
			return date("Y/m/d",$sTime);
		}elseif($type=='.ymd'){
			return date("Y.m.d",$sTime);
		}elseif($type=='/ym'){
			return date("Y/m",$sTime);
		}elseif($type=='/mdhi'){
			return date("m/d H:i",$sTime);
		}elseif($type=='/md'){
			return date("m/d",$sTime);
		}elseif($type=='N'){
			$str = '';
			$day = date('N',$sTime);
			switch($day){
				case 1:
					$str .=" 周一 ";
					break;
				case 2:
					$str .= " 周二 ";
					break;
				case 3:
					$str .= " 周三 ";
					break;
				case 4:
					$str .= " 周四 ";
					break;
				case 5:
					$str .= " 周五 ";
					break;
				case 6:
					$str .= " 周六 ";
					break;
				case 7:
					$str .= " 周日 ";
					break;
			}
			return $str;
		}else{
			if( $dTime < 60 ){
				return $dTime."秒前";
			}elseif( $dTime < 3600 ){
				return intval($dTime/60)."分钟前";
			}elseif( $dTime >= 3600 && $dDay == 0  ){
				return intval($dTime/3600)."小时前";
			}elseif($dYear==0){
				return date("Y-m-d H:i:s",$sTime);
			}else{
				return date("Y-m-d H:i:s",$sTime);
			}
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
		if (!check_dir(dirname($dir),$mode)){
			return false; 
		}
		return @mkdir($dir,$mode); 
	}
function isAndroid(){
	if(strstr($_SERVER['HTTP_USER_AGENT'],'Android')) {
		return 1;
	}
	return 0;
}

function p($arr){
	echo '<pre>'.print_r($arr,true).'</pre>';
}

//无线打印!!!
function wifi_print($str){


		/**  
		 * 发送post请求  
		 * @param string $url 请求地址  
		 * @param array $post_data post键值对数据  
		 * @return string  	 
		 */  

	function send_post($url, $post_data) {   

  
	//生成encode之后的post字符串
	  $postdata = http_build_query($post_data);   

	  $options = array(   

	    'http' => array(   

	      'method' => 'POST',   

	      'header' => 'Content-type:application/x-www-form-urlencoded',   

	      'content' => $postdata,   

	      'timeout' => 15 * 60 // 超时时间（单位:s）   

	    )   

	  );

	//设置服务器头文件与内容
	  $context = stream_context_create($options);   

	  $result = file_get_contents($url, false, $context);   

	  return $result;   

	}   




	//使用方法   

	// $post_data = array(   

	  // 'username' => 'stclair2201',   

	  // 'password' => 'handan'  

	// );   

	// send_post('http://www.qianyunlai.com', $post_data);   
	

	//生成唯一标识符
	function EncyptUrl($data , $key) {
	                 ksort($data);
	                 $__av = array_values($data);
	                 $src_string = implode(",", $__av) . $key;
	                 $md5 = md5($src_string);
	                 return $md5;
	}



	//要打印的内容
	// $content = "CC!-CC!". "---- x ---- xxx ---- x----\n";  //   居中打印
 //    $content = $content. "CC!-CC!". "1请拍照保存 2向服务员索取\n"; //   居中打印
 //    $content = $content.  "CC!-CC!用微信，360卫士，搜狗拼音扫描\n"; //   居中打印
 //    $content = $content.  "CC!-CC!". "每桌一张\n"; //   居中打印
 //    $content = $content.  "BC!-BC!". "菜谱二维码\n";  //   居中打印并加粗
 //    $content = $content.  "BC!-BC!". "菜谱二维码\n";  //  加粗打印
	$content = urlencode($str);
		

	
	
	$key= "AK47";
	$debug = 1;

	$array['type'] = "text";  				// qrcode
	$array['content'] =$content;
	$array['createtime'] = time();
	$si= EncyptUrl($array , $key);			//唯一标识符
	$array['key'] = $si;

	//第三方接口
	$site = "http://gd002.unifw.com";

	//GET 
	//$url = "$site/service/sendPrintData.php?content={$array['content']}&type=text&key=$si&createtime=$now";

	//POST
	$url = "$site/service/sendPrintData.php";


	

	//POST
	$post_data = $array;  
	$content = send_post($url, $post_data);   

	//GET
	//$content = file_get_contents($url);


	//echo $content;

	// if ($debug) echo "$url  \n";
		
	// if ($debug) echo "STEP 2 \n";

	// if ($debug) echo "STEP 3 \n";



}
/**
 * 下载远程图片
 * @param string $url 图片的绝对url
 * @param string $filepath 文件的完整路径（包括目录，不包括后缀名,例如/www/images/test） ，此函数会自动根据图片url和http头信息确定图片的后缀名
 * @return mixed 下载成功返回一个描述图片信息的数组，下载失败则返回false
 */
function download_image($url, $filepath) {
	//服务器返回的头信息
	$responseHeaders = array();
	//原始图片名
	$originalfilename = '';
	//图片的后缀名
	$ext = '';
	$ch = curl_init($url);
	//设置curl_exec返回的值包含Http头
	curl_setopt($ch, CURLOPT_HEADER, 1);
	//设置curl_exec返回的值包含Http内容
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	//设置抓取跳转（http 301，302）后的页面
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	//设置最多的HTTP重定向的数量
	curl_setopt($ch, CURLOPT_MAXREDIRS, 2);

	//服务器返回的数据（包括http头信息和内容）
	$html = curl_exec($ch);
	//获取此次抓取的相关信息
	$httpinfo = curl_getinfo($ch);
	curl_close($ch);
	if ($html !== false) {
		//分离response的header和body，由于服务器可能使用了302跳转，所以此处需要将字符串分离为 2+跳转次数 个子串
		$httpArr = explode("\r\n\r\n", $html, 2 + $httpinfo['redirect_count']);
		//倒数第二段是服务器最后一次response的http头
		$header = $httpArr[count($httpArr) - 2];
		//倒数第一段是服务器最后一次response的内容
		$body = $httpArr[count($httpArr) - 1];
		$header.="\r\n";

		//获取最后一次response的header信息
		preg_match_all('/([a-z0-9-_]+):\s*([^\r\n]+)\r\n/i', $header, $matches);
		if (!empty($matches) && count($matches) == 3 && !empty($matches[1]) && !empty($matches[1])) {
			for ($i = 0; $i < count($matches[1]); $i++) {
				if (array_key_exists($i, $matches[2])) {
					$responseHeaders[$matches[1][$i]] = $matches[2][$i];
				}
			}
		}
		//获取图片后缀名
		if (0 < preg_match('{(?:[^\/\\\\]+)\.(jpg|jpeg|gif|png|bmp)$}i', $url, $matches)) {
			$originalfilename = $matches[0];
			$ext = $matches[1];
		} else {
			if (array_key_exists('Content-Type', $responseHeaders)) {
				if (0 < preg_match('{image/(\w+)}i', $responseHeaders['Content-Type'], $extmatches)) {
					$ext = $extmatches[1];
				}
			}
		}
		//保存文件
		if (!empty($ext)) {
			$filepath .= ".$ext";
			//如果目录不存在，则先要创建目录
			//CFiles::createDirectory(dirname($filepath));
			$local_file = fopen($filepath, 'w');
			if (false !== $local_file) {
				if (false !== fwrite($local_file, $body)) {
					fclose($local_file);
					$sizeinfo = getimagesize($filepath);
					return array('filepath' => realpath($filepath), 'width' => $sizeinfo[0], 'height' => $sizeinfo[1], 'orginalfilename' => $originalfilename, 'filename' => pathinfo($filepath, PATHINFO_BASENAME));
				}
			}
		}
	}
	return false;
}
/**
 * 下载 二维码图片
 * @return multitype:
 */
function download_qrcode_img($url,$savePath = '',$filename = ''){
	if ($url){
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_NOBODY, 0);    //只取body头
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$package = curl_exec($ch);
		$httpinfo = curl_getinfo($ch);
		curl_close($ch);
		$imageInfo =  array_merge(array('body' => $package), array('header' => $httpinfo));
		if (!$filename){
			$filename = getUniqueNumber().'.jpg';
		}
		if (!$savePath){
			$savePath = './Uploads/QRCodeImg/';
		}
		$filepath = $savePath.$filename;
		$local_file = fopen($filepath, 'w');
		if (false !== $local_file) {
			if (false !== fwrite($local_file,  $imageInfo["body"])) {
				fclose($local_file);
				$sizeinfo = getimagesize($filepath);
				return array('filepath' => realpath($filepath), 'width' => $sizeinfo[0], 'height' => $sizeinfo[1],'filename' => pathinfo($filepath, PATHINFO_BASENAME));
			}
		}
	}else{
		return false;
	}
}
/**
 * 下载 google api 二维码图片 缓存到本地
 * @param unknown $url
 * @param unknown $path
 * @param unknown $qrpic
 */
function get_qrCode_img($url, $savePath = '', $newName = '')
{
	if ($url){
		set_time_limit(10);
		if (!$savePath){
			$savePath = './Uploads/QRCodeImg/';
		}
		if (!$newName){
			$newName = getUniqueNumber().'.jpg';
		}
		check_dir($savePath);
		$localname = $savePath . $newName;
		$file = fopen ($url, "rb");
		if ($file)
		{
			$newf = fopen ($localname, "wb");
			if ($newf)
			while(!feof($file))
			{
				fwrite( $newf, fread($file, 1024 * 2 ), 1024 * 2 );
			}
		}
		if ($file)
		{
			fclose($file);
		}
		if ($newf)
		{
			fclose($newf);
		}
	}else{
		return false;
	}
}
/**
 * google api 二维码生成【QRcode可以存储最多4296个字母数字类型的任意文本，具体可以查看二维码数据格式】
 * @param string $chl 二维码包含的信息，可以是数字、字符、二进制信息、汉字。不能混合数据类型，数据必须经过UTF-8 URL-encoded.如果需要传递的信息超过2K个字节，请使用POST方式
 * @param int $widhtHeight 生成二维码的尺寸设置
 * @param string $EC_level 可选纠错级别，QR码支持四个等级纠错，用来恢复丢失的、读错的、模糊的、数据。
 *                 L-默认：可以识别已损失的7%的数据
 *                 M-可以识别已损失15%的数据
 *                 Q-可以识别已损失25%的数据
 *                 H-可以识别已损失30%的数据
 * @param int $margin 生成的二维码离图片边框的距离
 */
function get_google_api_qrCode($savePath,$newName,$chl,$widhtHeight='300'){
	$localFile = $savePath.$newName;
	if ( ! file_exists($localFile) ){
		//get_qrCode_img("http://chart.googleapis.com/chart?cht=qr&chs=".$widhtHeight."x".$widhtHeight."&choe=UTF-8&chld=L|2&chl=".$chl ,$savePath, $newName);
		//get_qrCode_img("http://qr.liantu.com/api.php?el=l&w=".$widhtHeight."&m=10&text=".$chl ,$savePath, $newName);
		download_remote_file("http://qr.liantu.com/api.php?el=l&w=".$widhtHeight."&m=10&text=".$chl,$savePath.$newName);
	}
	return $localFile;
}
/**
 * 下载文件
 * @param unknown $url
 * @param unknown $filename
 */
function download_remote_file($file_url, $save_to)
{
	$content = file_get_contents($file_url);
	file_put_contents($save_to, $content);
}
/**
 * 下载文件
 * @param unknown $url
 * @param unknown $filename
 */
function download_file($file_path, $filename) {
	// 获得文件大小, 防止超过2G的文件, 用sprintf来读
	//首先要判断给定的文件存在与否
		if(!file_exists($file_path)){
			echo "没有该文件文件";
			return ;
		}
		$fp=fopen($file_path,"r");
		$file_size=filesize($file_path);
		//下载文件需要用到的头
		Header("Content-type: application/octet-stream");
		Header("Accept-Ranges: bytes");
		Header("Accept-Length:".$file_size);
		Header("Content-Disposition: attachment; filename=".$filename);
		$buffer=1024;
		$file_count=0;
		//向浏览器返回数据
		while(!feof($fp) && $file_count<$file_size){
			$file_con=fread($fp,$buffer);
			$file_count+=$buffer;
			echo $file_con;
		}
		fclose($fp);
}
/**
 * 读取加载文件
 * @param unknown $filename
 * @param string $retbytes
 * @return boolean|number
 */
function load_file($filename, $retbytes = true) {
	$buffer = '';
	$cnt = 0;
	$handle = fopen ( $filename, 'rb' );
	if ($handle === false) {           return false;        }
	while ( ! feof ( $handle ) ) {
		$buffer = fread ( $handle, 1024 * 1024 );
		echo $buffer;
		ob_flush ();
		flush ();
		if ($retbytes) {
			$cnt += strlen ( $buffer );
		}
	}
	$status = fclose ( $handle );
	if ($retbytes && $status) {
		return $cnt; // return num. bytes delivered like readfile() does.
	}
	return $status;
}
/** 
 *  计算年龄
 * @param int $birthday    生日 时间戳
 */ 
function get_age($birthday){
	if($birthday == '0000-00-00'){
		return FALSE;
	}elseif ($birthday && $birthday != '0000-00-00'){
		list($year,$month,$day)=explode('-',$birthday);
		$nowMonth=date('n');
		$nowDay=date('j');
		$age=date('Y')-$year-1;
		if ($nowMonth>$month || $nowMonth=$month && $nowDay>$day) $age++;
		$age = $age > 0 ? $age : 1;
		return $age;
	}else{
		return FALSE;
	}
}
/**
 *  计算时间戳距离现在的天数
 * @param int $birthday    生日 时间戳
 */
function get_days($time){
	//$age = strtotime($birthday);
	if($time === false){
		return false;
	}else{
	    $days = ceil(abs(time() - $time)/86400); 
	    return $days; 
	}
}
/**
 * 获得md5加密字符串
 */
function get_md5_password($str){
	return md5(trim($str));
}
/**
 * 获得两个时间内的月份 $time1 > $time2
 */
function get_time_months($time1,$time2){
	$months = 1;
	$monthDays = array('01'=>'31','02'=>'30','03'=>'31','04'=>'28','05'=>'31','06'=>'30','07'=>'31','08'=>'31','09'=>'30','10'=>'31','11'=>'30','12'=>'31');
	if ($time1 >= $time2){
		$year1 = date('Y',$time1);
		$month1 = date('m',$time1);
		$day1 = date('d',$time1);
		$year2 = date('Y',$time2);
		$month2 = date('m',$time2);
		$day2 = date('d',$time2);
		if ($month1 > $month2){
			$months = ($year1-$year2)*12+($month1-$month2);
		}elseif ($month1 < $month2){
			$months =($year1-$year2)*12-$month2+$month1;
		}elseif ($month1 == $month2){
			$months =($year1-$year2)*12;
		}
		$monthDay1 =  $day1/$monthDays[$month1];
		$monthDay2 =  $day2/$monthDays[$month2];
		if($monthDay1 >$monthDay2){
			$months += 1;
		}
	}
	return $months;
}
/**
 * 格式化数字 
 * $number 数字 
 * 保留两位小数
 */
function format_number($number){
	return number_format($number,2,'.','');
}
/**
 * 格式化地图echarts 地图数据
 * @param unknown $mapdata  上海:0;云南:0;内蒙古:0;北京:0;台湾:0;吉林:0;四川:0;天津:0;宁夏:0;安徽:0;山东:1;山西:0;广东:0;广西:0;新疆:0;江苏:0;江西:0;河北:0;河南:0;浙江:0;海南:0;湖北:0;湖南:0;澳门:0;甘肃:0;福建:0;西藏:0;贵州:0;辽宁:0;重庆:0;陕西:0;青海:0;香港:0;黑龙江:0;
 * @return string 
 */
function format_map_data($mapdata){
	$returnMapData = '';
	if($mapdata){
		$chinaWechatArr = explode(';',$mapdata);
		foreach($chinaWechatArr as $cwaKey=>$cwaVal){
			$chinaWechatValArr = explode(':',$cwaVal);
			if($chinaWechatValArr[0]){
				$returnMapData .= '{name: "'.$chinaWechatValArr[0].'",value:'.$chinaWechatValArr[1].'},';
			}
		}
	}
	return $returnMapData;
}
/**
 *  @desc 根据两点间的经纬度计算距离
 *  @param float $lat1 位置1纬度值
 *  @param float $lng1 位置1经度值
 *  @param float $lat2 位置2纬度值
 *  @param float $lng2 位置2经度值
 */
function get_distance($lat1, $lng1, $lat2, $lng2)
{
	$earthRadius = 6367000; //approximate radius of earth in meters

	/*
	 Convert these degrees to radians
	to work with the formula
	*/

	$lat1 = ($lat1 * pi() ) / 180;
	$lng1 = ($lng1 * pi() ) / 180;

	$lat2 = ($lat2 * pi() ) / 180;
	$lng2 = ($lng2 * pi() ) / 180;

	/*
	 Using the
	Haversine formula

	http://en.wikipedia.org/wiki/Haversine_formula

	calculate the distance
	*/

	$calcLongitude = $lng2 - $lng1;
	$calcLatitude = $lat2 - $lat1;
	$stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);  $stepTwo = 2 * asin(min(1, sqrt($stepOne)));
	$calculatedDistance = $earthRadius * $stepTwo;
	
	return round($calculatedDistance);
}

/**
 * 微信关注位置与本店的距离
 * @param unknown $metre 以“米”为单位
 * @return string
 */
function distance($metre){
	if($metre > 5000000){
		return ">5000Km";
	}elseif($metre <= 5000000 && $metre >= 1000){
		return intval($metre/1000)."Km";
	}elseif(0 <= $metre && $metre < 1000){
		return intval($metre)."m";
	}
}
/**
 * 中国正常GCJ02坐标---->百度地图BD09坐标
 * 腾讯地图用的也是GCJ02坐标
 * @param double $lat 纬度
 * @param double $lng 经度
 */
function Convert_GCJ02_To_BD09($lat,$lng){
	$x_pi = 3.14159265358979324 * 3000.0 / 180.0;
	$x = $lng;
	$y = $lat;
	$z =sqrt($x * $x + $y * $y) + 0.00002 * sin($y * $x_pi);
	$theta = atan2($y, $x) + 0.000003 * cos($x * $x_pi);
	$lng = $z * cos($theta) + 0.0065;
	$lat = $z * sin($theta) + 0.006;
	return array('lng'=>$lng,'lat'=>$lat);
}
/**
 * 百度地图BD09坐标---->中国正常GCJ02坐标
 * 腾讯地图用的也是GCJ02坐标
 * @param double $lat 纬度
 * @param double $lng 经度
 * @return array();
 */
function Convert_BD09_To_GCJ02($lat,$lng){
	$x_pi = 3.14159265358979324 * 3000.0 / 180.0;
	$x = $lng - 0.0065;
	$y = $lat - 0.006;
	$z = sqrt($x * $x + $y * $y) - 0.00002 * sin($y * $x_pi);
	$theta = atan2($y, $x) - 0.000003 * cos($x * $x_pi);
	$lng = $z * cos($theta);
	$lat = $z * sin($theta);
	return array('lng'=>$lng,'lat'=>$lat);
}
/**
 * 二维数组排序
 * @param unknown $arrUsers  数组
 * @param unknown $field  键
 * @param string $sort  排序类型
 * @return unknown
 */
function arraySort($arrUsers, $field, $sort = 'SORT_DESC'){
	$sort = array(
			'direction' => $sort, //排序顺序标志 SORT_DESC 降序；SORT_ASC 升序
			'field' => $field, //排序字段
	);
	$arrSort = array();
	foreach($arrUsers AS $uniqid => $row){
		foreach($row AS $key=>$value){
			$arrSort[$key][$uniqid] = $value;
		}
	}
	if($sort['direction']){
		array_multisort($arrSort[$sort['field']], constant($sort['direction']), $arrUsers);
	}
	return $arrUsers;
}
/**
 * 获得奖项概率
 * @param unknown $proArr('奖项'=>'整数概率,相加为100');
 * @return Ambigous <string, unknown>
 */
function getRand($proArr) {
	$result = '';

	//概率数组的总概率精度
	$proSum = array_sum($proArr);

	//概率数组循环
	foreach ($proArr as $key => $proCur) {
		$randNum = mt_rand(1, $proSum);
		if ($randNum <= $proCur) {
			$result = $key;
			break;
		} else {
			$proSum -= $proCur;
		}
	}
	unset ($proArr);
	return $result;
}
/**
 * 将手机号中间四位转为*号
 * @param unknown $string 手机号
 * @return mixed
 */
function hideLittleMobile($string){
	$pattern = '/(1\d{2})\d{4}(\d{4})/';
	$replacement = "\$1****\$2";
	return preg_replace($pattern,$replacement,$string);
}
?>