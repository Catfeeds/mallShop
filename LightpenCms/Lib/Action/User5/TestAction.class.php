<?php
/**
 * 
 * 
 * @author    Mark<1311013341@qq.com>
 * @since     2016-9-13
 * @version   1.0
 */
class TestAction extends BaseAction{
    
public function wechatnum(){
		$time = time();
		$list = M('quick_response_code')->where(array('type'=>'1','subscribe'=>array('gt','0')))->field('id,companyid,content,subscribe')->select();
		if($list){
			foreach ($list as $lkey=>$lval){
				$unsubscribe = M()->table('tp_member_register_info as mri')->join('left join tp_member_wechat_info as mwi on mwi.mid=mri.id')
				->where(array('mwi.companyid'=>$lval['companyid'],'mwi.scene_id'=>$lval['content'],'mri.subscribetype'=>'2'))->count();
				if($unsubscribe>0 && $lval['subscribe']>0){
					if($unsubscribe<$lval['subscribe']){
						$scannum = $lval['subscribe']-$unsubscribe;
					}else{
						$scannum = 0;
					}
					M('quick_response_code')->where(array('id'=>$lval['id']))->save(array('updatetime'=>$time,'scannum'=>$scannum,'unsubscribe'=>$unsubscribe));
				}
				unset($lval,$unsubscribe,$scannum);
			}
		}
	}
	
    
    public function testre(){
        $time = time();
        $list = M('history_wechat_request')->where(array('Event'=>array('in',array('subscribe','unsubscribe')),'CreateTime'=>array('between',array('1489766400','1490020352'))))->field('FromUserName,token')->select();
        if($list){
            foreach ($list as $lkey=>$lval){
                if($lval['Event'] == 'subscribe'){
                    $subscribetype = '1';
                }else{
                    $subscribetype = '2';
                }
                $companyid = M('wechats')->where(array('token'=>$lval['token']))->getField('companyid');
                M()->table('tp_member_wechat_info as mwi')->join('left join tp_member_register_info as mri on mri.id=mwi.mid')
                ->where(array('mwi.companyid'=>$companyid,'mwi.openid'=>$lval['FromUserName']))
                ->save(array('mri.subscribetype'=>$subscribetype,'mri.updatetime'=>$time));
                unset($companyid,$lval,$subscribetype);
            }
        }
    }
    
	public function bookclass(){
		$bookclass = M('spa_mobile_book_project_class')->field('id,companyid')->select();
		foreach ($bookclass as $key=>$val){
			$count = M('spa_mobile_book_project_set')->where(array('companyid'=>$val['companyid'],'bookclass'=>$val['id']))->count();
			echo $count;
			M('spa_mobile_book_project_class')->where(array('companyid'=>$val['companyid'],'id'=>$val['id']))->save(array('updatetime'=>time(),'projectnum'=>$count));
			unset($val,$count);
		}
	}
    
	
	public function testAsa(){
	    set_time_limit(0);
		/**
		 *
		 * @param unknown $file 图片路径
		 * @param unknown $file_type  上传图片信息
		 * @author Asa<asa@renlaifeng.cn>
		 * @since  2017-3-15
		 */
		function ResizeImage($file,$file_type){
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
			//缩放尺寸
			$newwidth = $width * $percent;
			$newheight = $height * $percent;
				
			if($file_type == "pjpeg"||$file_type == "jpg"|$file_type == "jpeg"){
				$src_im = imagecreatefromjpeg($file);
				$dst_im = imagecreatetruecolor($newwidth, $newheight);
				imagecopyresized($dst_im, $src_im, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
				imagejpeg($dst_im,$file,$asasize); //输出压缩后的图片
			}elseif($file_type == "x-png"||$file_type == "png"){
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
		
		
		function tree($directory){
			$mydir = dir($directory);
			echo "<ul>\n";
			if($mydir !==false){
    			while($file = $mydir->read()){
    				$fileUrl = $directory."/".$file;
    				if((is_dir("$directory/$file")) AND ($file!=".") AND ($file!="..")){
    					echo "<li><font color=\"#ff00cc\"><b>$file</b></font></li>\n";
    					if($fileUrl != './Uploads/1' ||$fileUrl != './Uploads/2' || $fileUrl != './Uploads/messageboard'|| $fileUrl != './Uploads/service'){
        					tree("$directory/$file");
    					}
    				}elseif(($file!=".") AND ($file!="..")){
    					$file_type = substr($fileUrl, strrpos($fileUrl, '.')+ 1);
    					echo "<li>$file\t ". $file_type;
    					if($file_type == "pjpeg"||$file_type == "jpg"|$file_type == "jpeg"||$file_type == "x-png"||$file_type == "png"){
    						//echo $fileUrl.'/'.$file_type.'<br/>';
    					    echo " \t ". ResizeImage($fileUrl,$file_type) ."</li>\n";
    					}
    				}
    			}
			    $mydir->close();
			}
			echo "</ul>\n";
		}
		echo "<h2>目录为粉红色</h2><br>\n";
		//$dir ="./Uploads/6";
		//echo ResizeImage('./Uploads/6/image/20140924/20140924191015_58187.png','png');
		//tree($dir);
		/* for ($i=20001;$i<20101;$i++){
		    echo $dir ="./Uploads/".$i; 
    		tree($dir);
		}
		 */
	}
	
    /**
     * 
     * 闪惠错误 支付方式 数据修复
     * 
     * @author Lando<806728685@qq.com>
     * @since  2017-2-28
     */
    public function checkShanhui(){
        $time = time();
        $actualamount = $succActualamount = $wxSuccActualamount = $czSuccActualamount = '0.00';   // 错误订单金额  // 成功处理金额
        $orders = M('shanhui_order')->where(array('paystate'=>'2'))->field('id,companyid,orderid,paytype,actualamount')->order('createtime DESC')->select();
        foreach ($orders as $key=>$val) {
            $spendInfo = M('member_spending')->where(array('linkorderid'=>$val['orderid'],'companyid'=>$val['companyid']))->field('id,paytype')->find();    // 订单记录表中的订单类型
            if($spendInfo){
                if($val['paytype']==1 && $spendInfo['paytype']!=1){        // 微信支付错误
                    // 加微信
                    // 减储值
                    $companyData['totalwechatpayincome'] = array('exp', 'totalwechatpayincome+'.$val['actualamount']);
                    $companyData['totalrechargepayincome'] = array('exp', 'totalrechargepayincome-'.$val['actualamount']);
                    // 修改订单类型
                    $spendData['paytype'] = 1;
                    
                    $isHandle = 1;    // 是否需要处理数据    
                }elseif($val['paytype']==2 && $spendInfo['paytype']!=4){   // 储值支付错误
                    // 减微信
                    // 加储值
                    $companyData['totalwechatpayincome'] = array('exp', 'totalwechatpayincome-'.$val['actualamount']);
                    $companyData['totalrechargepayincome'] = array('exp', 'totalrechargepayincome+'.$val['actualamount']);
                    // 修改订单类型
                    $spendData['paytype'] = 4;
                    
                    $isHandle = 1;    // 是否需要处理数据
                }
                // 修改微信支付累计收入/储值支付累计收入
                // 修改交易记录中的订单类型
                // 会员积分等先不考虑
                if($isHandle){
                    $actualamount += $val['actualamount'];
                    $companyData['updatetime'] = $time;
                    M()->startTrans();
                    $companyReturn = M('company')->where(array('id'=>$val['companyid']))->save($companyData);
                    if($companyReturn){
                        $spendData['updatetime'] = $time;
                        $spendReturn = M('member_spending')->where(array('companyid'=>$val['companyid'], 'id'=>$spendInfo['id']))->save($spendData);
                    }
                    if($spendReturn){
                        M()->commit();
                        $succActualamount += $val['actualamount'];
                        if($val['paytype']==1){
                            $wxSuccActualamount += $val['actualamount'];
                        }else{
                            $czSuccActualamount += $val['actualamount'];
                        }
                    }else{
                        M()->rollback();
                        echo '订单：'.$val['orderid'].' 信息处理失败!!<br/>';
                    }
                }
            }
            unset($spendInfo, $companyData, $spendData, $isHandle, $companyReturn, $spendReturn);
        }
        echo '处理完成,应该处理：'.$actualamount.'元，成功处理：'.$succActualamount.'元，其中微信：'.$wxSuccActualamount.'元，储值：'.$czSuccActualamount.'元.';
    }
    public function  checkShanhui1(){
        $orders = M('shanhui_order')->where(array('paystate'=>'2'))->field('paytype,companyid,orderid')->order('createtime DESC')->select();
        foreach ($orders as $oKey=>$OVal){
            $spendingInfo = M('member_spending')->where(array('linkorderid'=>$OVal['orderid'],'companyid'=>$OVal['companyid']))->field('paytype')->find();
            if($spendingInfo['paytype']&&$OVal['paytype'] == '1' && $spendingInfo['paytype'] != $OVal['paytype']){
                echo $OVal['companyid'].'/'.$OVal['paytype'].'/'.$spendingInfo['paytype'].'///////';
                echo $OVal['orderid']."<br/>";
            }elseif ($spendingInfo['paytype']&&$OVal['paytype'] =='2' && $spendingInfo['paytype'] != '4' ){
                echo $OVal['companyid'].'/'.$OVal['paytype'].'/'.$spendingInfo['paytype'].'///////';
                echo $OVal['orderid']."<br/>";
            }
        }
    }
    
    /**
     * 
     * 剔除重复wechat mid 数据
     * 
     * @author Lando<806728685@qq.com>
     * @since  2017-2-21
     */
    public function checkRegisterMember(){
        $wechatList = M()->query('select id,companyid,mid,openid,count(mid) from tp_member_wechat_info group by mid having(count(mid)>1)');
        foreach ($wechatList as $wKey=>$wVal){
            $subscribetype = M('wechat_member_subscribe_link')->where(array('companyid'=>$wVal['companyid'],'openid'=>$wVal['openid']))->getField('type');
            $newRegister['companyid'] = $wVal['companyid'];
            $newRegister['subscribetype'] = $subscribetype?$subscribetype:'2';
            $newRegister['createtime'] = $newRegister['updatetime'] = time();
            $newRegisterAddReturnId = M('member_register_info')->add($newRegister);
            if($newRegisterAddReturnId){
                $newWechatInfo['mid'] = $newRegisterAddReturnId;
                $newWechatInfo['updatetime'] = time();
                $oldWechatSaveReturn = M('member_wechat_info')->where(array('companyid'=>$wVal['companyid'],'id'=>$wVal['id']))->save($newWechatInfo);
            }
        }
        print_r($wechatList);
    }
    
    
    /**
     * 
     * 检查累计收入
     * 
     * @author Lando<806728685@qq.com>
     * @since  2017-2-20
     */
    public function checkCompanyZong(){
        
        $companyList = M('company')->where(array('_string'=>"isclose ='0' and  ((viptime > ".time().") or( gid=5))"))->field('id,name,totalincome')->select();
        foreach ($companyList as $cKey=>$cVal){
            $xong  = M('member_spending')->where(array('companyid'=>$cVal['id'],'_string'=>" status='1' and paytype!='4' and ( type = '106' OR type = '107' OR type = '110' OR type = '111' OR (type = '112' AND rechargetype = '2') OR (type = '112' AND rechargetype = '5') OR type = '113' OR type = '118' OR type = '119' OR type = '120' OR type = '114' OR type = '115' OR type = '121') "))->sum('spendingamount');
            if($xong > 0 && $xong != $cVal['totalincome']){
                echo $xong;
                print_r($cVal);
                echo "<br/>";
            }
            unset($cVal);
            
        }
        
        
    }
    
    
    /**
     * 
     * 核对星座
     * 
     * @author Lando<806728685@qq.com>
     * @since  2017-2-18
     */
    public function checkDMxingzuo(){
        $memberList = M('member_register_info')->where(array('companyid'=>'20223'))->field('id,birthday')->select();
        
        foreach ($memberList as $mkey=>$mVal){
            $return = $this->constellation($mVal['birthday']);
            $age = $this->agetag($mVal['birthday']);
            //var_dump($return);
            $registerData['constellationtag'] = $return ? $return : '0';
            $registerData['agetag'] = $age ? $age : '0';
            //$save = M('member_register_info')->where(array('id'=>$mVal['id'],'companyid'=>'20223'))->save($registerData);
            echo $registerData['agetag'].'/'.$mVal['birthday'];
            echo "<br/>";
            unset($mVal);
        }
        exit();
        // 星座  1:水瓶座; 2:双鱼座; 3:白羊座; 4:金牛座; 5:双子座; 6:巨蟹座; 7:狮子座; 8:处女座; 9:天秤座; 10:天蝎座； 11:射手座; 12:摩羯座; 0:未填写星座;
        $companyid = '20223';
        $constellation1 = M('member_register_info')->where(array('companyid'=>$companyid, 'isregister'=>'1', 'constellationtag'=>'1'))->count();
        $constellation2 = M('member_register_info')->where(array('companyid'=>$companyid, 'isregister'=>'1', 'constellationtag'=>'2'))->count();
        $constellation3 = M('member_register_info')->where(array('companyid'=>$companyid, 'isregister'=>'1', 'constellationtag'=>'3'))->count();
        $constellation4 = M('member_register_info')->where(array('companyid'=>$companyid, 'isregister'=>'1', 'constellationtag'=>'4'))->count();
        $constellation5 = M('member_register_info')->where(array('companyid'=>$companyid, 'isregister'=>'1', 'constellationtag'=>'5'))->count();
        $constellation6 = M('member_register_info')->where(array('companyid'=>$companyid, 'isregister'=>'1', 'constellationtag'=>'6'))->count();
        $constellation7 = M('member_register_info')->where(array('companyid'=>$companyid, 'isregister'=>'1', 'constellationtag'=>'7'))->count();
        $constellation8 = M('member_register_info')->where(array('companyid'=>$companyid, 'isregister'=>'1', 'constellationtag'=>'8'))->count();
        $constellation9 = M('member_register_info')->where(array('companyid'=>$companyid, 'isregister'=>'1', 'constellationtag'=>'9'))->count();
        $constellation10 = M('member_register_info')->where(array('companyid'=>$companyid, 'isregister'=>'1', 'constellationtag'=>'10'))->count();
        $constellation11 = M('member_register_info')->where(array('companyid'=>$companyid, 'isregister'=>'1', 'constellationtag'=>'11'))->count();
        $constellation12 = M('member_register_info')->where(array('companyid'=>$companyid, 'isregister'=>'1', 'constellationtag'=>'12'))->count();
        $constellation0 = M('member_register_info')->where(array('companyid'=>$companyid, 'isregister'=>'1', 'constellationtag'=>'0'))->count();
        $data['constellation'] = '{"1":'.$constellation1.', "2":'.$constellation2.', "3":'.$constellation3.', "4":'.$constellation4.', "5":'.$constellation5.', "6":'.$constellation6.', "7":'.$constellation7.', "8":'.$constellation8.', "9":'.$constellation9.', "10":'.$constellation10.', "11":'.$constellation11.', "12":'.$constellation12.', "0":'.$constellation0.'}';
        // 年龄   1:10后; 2:00后; 3:90后; 4:80后; 5:70后; 6:60后; 7:50后及以上; 0:未填写生日
        $age1 = M('member_register_info')->where(array('companyid'=>$companyid, 'isregister'=>'1', 'agetag'=>'1'))->count();
        $age2 = M('member_register_info')->where(array('companyid'=>$companyid, 'isregister'=>'1', 'agetag'=>'2'))->count();
        $age3 = M('member_register_info')->where(array('companyid'=>$companyid, 'isregister'=>'1', 'agetag'=>'3'))->count();
        $age4 = M('member_register_info')->where(array('companyid'=>$companyid, 'isregister'=>'1', 'agetag'=>'4'))->count();
        $age5 = M('member_register_info')->where(array('companyid'=>$companyid, 'isregister'=>'1', 'agetag'=>'5'))->count();
        $age6 = M('member_register_info')->where(array('companyid'=>$companyid, 'isregister'=>'1', 'agetag'=>'6'))->count();
        $age7 = M('member_register_info')->where(array('companyid'=>$companyid, 'isregister'=>'1', 'agetag'=>'7'))->count();
        $age0 = M('member_register_info')->where(array('companyid'=>$companyid, 'isregister'=>'1', 'agetag'=>'0'))->count();
        $data['age'] = '{"1":'.$age1.', "2":'.$age2.', "3":'.$age3.', "4":'.$age4.', "5":'.$age5.', "6":'.$age6.', "7":'.$age7.', "0":'.$age0.'}';
        
        $save = M('report_member_system_group')->where(array('companyid'=>$companyid))->save($data);
        var_dump($save);
        echo "<br/>";
        
        
    }
    
    
	/**
	 * 
	 * DM 会员卡发放
	 * 
	 * @author Lando<806728685@qq.com>
	 * @since  2017-2-18
	 */
	public function checkMemberCardddd(){
	    $memberList = M('member_register_info')->where(array('companyid'=>'20223'))->field('id')->select();
	    foreach ($memberList as $mkey=>$mVal){
	        $return = $this->NewchangMemberCardRank('20223',$mVal['id']);
	        var_dump($return);
	        echo "<br/>";
	    }
	}
	
	
	/**
	 * 
	 * 闪惠丢失交易记录添加
	 * 
	 * @author Lando<806728685@qq.com>
	 * @since  2017-2-18
	 */
	public function checkShanhuiOrder(){
	    $OrderList = M('shanhui_order')->where(array('paystate'=>'2','createtime'=>array('gt','1473837360')))->order('createtime Desc')->select();
	    //$OrderList = M('mall_order_info')->where(array('orderstatus'=>array('in','2,3,4,6')))->select();
	    $i = 0;
	    // W17022010163320272001   W17021819420120272001
	    foreach ($OrderList as $OKey=>$OVal){
	        
	        $sCount = M('member_spending')->where(array('linkorderid'=>$OVal['orderid'],'mid'=>array('gt','0')))->find();
	        if($OVal['paytype'] == '2'){
	            $option['paytype']  = '4';
	        }elseif ($OVal['paytype'] == '1'){
	            $option['paytype']  = '1';
	        }
	        if($sCount['paytype']!=$option['paytype']){
	            print_r($sCount);
	            echo "<br/>";
	        }
	        
	        
	        /* $sCount = M('member_spending')->where(array('linkorderid'=>$OVal['orderid']))->count();
	        if($sCount<1){
	            $i++;
	            echo format_time($OVal['createtime']);
	            print_r($OVal);
	            // 加积分/经验值
                $option['cid']  = $OVal['companyid'];
                $option['shopid']  = $OVal['shopid'];
                $option['type']  = '106'; // 交易类型
                $option['mid']  = $OVal['mid'];// 会员id
                $option['num']  = $OVal['actualamount'];// 数值
                $option['linkorderid']  = $OVal['orderid'];// 订单号
                $option['linkoutorderid']  = $OVal['out_trade_no'];// 商户订单号
                if($OVal['paytype'] == '2'){
                    $option['paytype']  = '4';
                }elseif ($OVal['paytype'] == '1'){
                    $option['paytype']  = '1';
                }
                $option['createtime']  = $OVal['createtime'];
                if($option['paytype']){
    	            $return = $this->changeMemberBusinessSCRM5($option);
    	            var_dump($return);exit(); 
    	            echo "<br/>";
                }
	        } */
	    }
	    echo $i;
	    
	}
	
	
	public function checkvou(){
	    
	    $Vouchers = M('member_vouchers')->where(array('companyid'=>'20223'))->select();
	    foreach ($Vouchers as $key=>$Val){
	        $registerInfo = M('member_register_info')->where(array('id'=>$Val['mid']))->find();
	        echo $key.'//'.print_r($registerInfo)."<br/>";
	        if(!$registerInfo){
	            echo M()->getLastSql()."<br/>";
	        }
	        
	    }
	    
	}
	/**
	 * 
	 * 核对会员等级对应会员数
	 * 
	 * @author Lando<806728685@qq.com>
	 * @since  2017-2-10
	 */
	public function checkCardRankMemberNum(){
	    $cardRandS = M('member_card_rank')->field('id,companyid,reportnumber,number')->order('companyid asc')->select();
	    foreach ($cardRandS as $cVal){
	        $mNum = M()->table('tp_member_register_info as register')->join(array('tp_member_card_info as card on card.mid=register.id'))->where(array('register.isregister'=>'1','register.moblie'=>array('neq',''),'card.rankid'=>$cVal['id'],'card.companyid'=>$cVal['companyid']))->count();
	        //echo M()->getLastSql();exit();
	        //M('member_card_info')->where(array('companyid'=>$cVal['companyid'],'rankid'=>$cVal['id']))->count();
	        if($mNum != $cVal['reportnumber'] && $cVal['reportnumber']){
	            //M('member_card_rank')->where(array('companyid'=>$cVal['companyid'],'id'=>$cVal['id']))->save(array('reportnumber'=>$mNum));
	            //unset($cVal,$mNum);
	            echo '公司id'.$cVal['companyid'].'会员等级编号'.$cVal['number'].'/'.$mNum.'/'.$cVal['reportnumber']."<br/>";
	        }
	    }
	}
	
	
	public function takeoutCopy(){
		$time = time();
		$shopid = M('company_shops')->where(array('companyid'=>'20272'))->field('id')->select();    // 公司门店
		$a = M('takeout_setup')->where(array('id'=>'345WS2FMELMOK'))->find();     // 外卖配置
		$b = M('takeout_menu_cat')->where(array('setid'=>'345WS2FMELMOK'))->select();  // 菜品品类
		$c = M('takeout_menu')->where(array('setid'=>'345WS2FMELMOK'))->select();      // 菜品  (,catids,)关联takeout_menu_cat的id
		$d = M('takeout_menu_sku')->where(array('setid'=>'345WS2FMELMOK'))->select();  // 菜品SKU  (menuid)关联takeout_menu的id
		if($shopid){
			foreach ($shopid as $skey=>$sval){
				$isshow = M('takeout_setup')->where(array('shopid'=>$sval['id']))->getField('id');
				if($a && !$isshow){
					$a['id'] = guidNow();
					$a['shopid'] = $sval['id'];
					$a['updatetime'] = $a['createtime'] = $time;
					M('takeout_setup')->add($a);
					if($b){
						foreach ($b as $bkey=>$bval){
							$id1 = '';
							$id1 = $bval['id'];
							$bval['id'] = guidNow();
							$bval['shopid'] = $sval['id'];
							$bval['setid'] = $a['id'];
							$bval['updatetime'] = $bval['createtime'] = $time;
							M('takeout_menu_cat')->add($bval);
							if($c){
								foreach ($c as $ckey=>$cval){
									$id2 = '';
									if($cval['catids'] == ','.$id1.','){
										$id2 = $cval['id'];
										$cval['id'] = guidNow();
										$cval['shopid'] = $sval['id'];
										$cval['setid'] = $a['id'];
										$cval['catids'] = ','.$bval['id'].',';
										$cval['updatetime'] = $cval['createtime'] = $time;
										M('takeout_menu')->add($cval);
										if($d){
											foreach ($d as $dkey=>$dval){
												if($dval['menuid'] == $id2){
													$dval['id'] = guidNow();
													$dval['shopid'] = $sval['id'];
													$dval['setid'] = $a['id'];
													$dval['menuid'] = $cval['id'];
													$dval['updatetime'] = $dval['createtime'] = $time;
													M('takeout_menu_sku')->add($dval);
												}
												unset($dval);
											}
										}
									}
									unset($cval);
								}
							}
							unset($bval);
						}
	
					}
				}
				unset($isshow,$sval);
			}
		}
		echo 'success';
	}
	
	
	/**
	 * 
	 *  修复储值充值金额统计
	 * 
	 * @author Lando<806728685@qq.com>
	 * @since  2017-1-9
	 */
	public function jisuanchuzhi(){
	    /* $companyList = M('company')->where(array('_string'=>"isclose ='0' and  (viptime > ".time()." or gid=5)"))->field('id,totalrecharge')->select();
	    if($companyList){
	        foreach ($companyList as $cKey=>$cVal){
	                $trueTotalrecharge1 = M('member_spending')->where(array('companyid'=>$cVal['id'],'type'=>'112','_string'=>"(rechargetype='1' OR rechargetype='4' OR rechargetype='2')"))->sum('spendingamount2');
	                $trueTotalrecharge3 = M('member_spending')->where(array('companyid'=>$cVal['id'],'type'=>'203'))->sum('spendingamount2');
	                $trueTotalrecharge = $trueTotalrecharge1+$trueTotalrecharge3;
                    if($trueTotalrecharge != $cVal['totalrecharge']){
                        echo '公司id：'.$cVal['id'].'/真实'.$trueTotalrecharge.'/'.$cVal['totalrecharge'].'<br/>';
                        M('company')->where(array('id'=>$cVal['id']))->save(array('totalrecharge'=>$trueTotalrecharge));
                        echo M()->getLastSql();exit();
                    }	            
	                    
	        }
	    } */
	}
	
	/**
	 *
	 * SCRM5添加公司权限
	 *
	 * @author Mark<1311013341@qq.com>
	 * @since  2016-9-13
	 */
	public function permissions_copy(){
		//权限ID，若无二级菜单只写一级菜单的ID   若有二级菜单只写二级菜单的ID，一级不写
		$per = M('system_scrm5_permissions_list_new')->where(array('isshow'=>'1'))->order('id asc')->field('id')->select();
		if($per){
			$a='';
			foreach($per as $key=>$val){
				$a = $a.','.$val['id'];
			}
		}
		$_POST['scrm5permissions'] = $a.',';
		$_POST['updatetime'] = time();
		$User = M("company");
		$a = $User->where(array('status'=>3))->save($_POST);
		if($a){
			echo 'success';
		}else{
			echo 'error';
		}
	}
	/**
	 *
	 * SCRM5添加公司权限
	 *
	 * @author Mark<1311013341@qq.com>
	 * @since  2016-9-13
	 */
	public function permissions_copy2(){
		//权限ID，追加ID，就是每次都需呀改
		$lists = M("company")->where(array("status"=>3))->field("id,scrm5permissions")->select();
		foreach($lists as $key => $val){
			$data['scrm5permissions'] = $val['scrm5permissions'].'119,';
			$res = M("company")->where(array("id"=>$val['id']))->save($data);
			if(!$res){
				echo $val['id'];
				echo '<br />';
			}
		}
	}
	/**
	 * 
	 * 统计积分
	 * 
	 * @author Mark<1311013341@qq.com>
	 * @since  2016-12-14
	 */
	public function Integral(){
		$time = time();
		$memberlist = M()->table('tp_member_register_info as register')->join(array(' left join tp_company as company on company.id=register.companyid'))->where(array('_string'=>' company.gid = "5" OR company.viptime > '.time(),'register.id'=>array('gt','294083')))->field('register.id')->order('register.id ASC')->select();
		if($memberlist){
			foreach ($memberlist as $mkey=>$mval){ 
				$num1 = $num2 = $num3 = $num4 = $a = 0;
				// $num1 = 有效期为次年的可用积分(2016年的积分);$num2 = 2015年获得积分;$num3 = 2015年消耗积分;$num4 = 2016年消耗积分;$a = 有效期为当年的可用积分
				$num1 = M('member_integral')->where(array('mid'=>$mval['id'],'createtime'=>array('between','1451577600,1483200000'),'type'=>array('in','1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,24,25,26,27,28,29,101,102,103,104,105,106,107,108,109,110,111,112,113,114,115,116,117')))->sum('integralnum');
				$num2 = M('member_integral')->where(array('mid'=>$mval['id'],'createtime'=>array('between','1420041600,1451577599'),'type'=>array('in','1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,24,25,26,27,28,29,101,102,103,104,105,106,107,108,109,110,111,112,113,114,115,116,117')))->sum('integralnum');
				$num3 = M('member_integral')->where(array('mid'=>$mval['id'],'createtime'=>array('between','1420041600,1451577599'),'type'=>array('in','20,21,22,23,201,202,203,204,205')))->sum('integralnum');
				$num4 = M('member_integral')->where(array('mid'=>$mval['id'],'createtime'=>array('between','1451577600,1483200000'),'type'=>array('in','20,21,22,23,201,202,203,204,205')))->sum('integralnum');
				$a = $num2-$num3-$num4;
				if($a<0){
					$num1 = $num1+$a;
				}
				$data['nextyearcanuseintegral'] = $num1 > 0 ? $num1 : 0 ; 
				$data['thisyearcanuseintegral'] = $a > 0 ? $a : 0;
				$data['updatetime'] = time();
				$result = M('member_register_info')->where(array('id'=>$mval['id']))->save($data);
				if($result){
					M('rd_test_log')->add(array('log'=>'mid:'.$mval['id'].';thiisyear:'.$data['thisyearcanuseintegral'].';nextyear:'.$data['nextyearcanuseintegral'],'createtime'=>time()));
				}
				unset($mval,$result,$data);
			}
		}
	}
	/**
	 * 核销卡券测试
	 * @author Thomas<416369046@qq.com>
	 * @since  2016-12-9
	 */
	public function verificationVouchers(){
		$option['vouchertype'] = '1';
		$option['vouchernumber'] = '2001217168747';
		$option['cid'] = '1217';
		$option['usetype'] = '2';
		$option['users'] = 'o0XI7wHtYwfrjCiOsmIca1m-JvOA';
		$option['getway'] = '1';
		$this->verificationVouchersSCRM5($option);		
	}
	public function echosession(){
		print_r($_SESSION);
	}
	public function clearsession(){
		session(null);
		echo 'success';
	}
	public function cardadd(){
		echo $_SERVER["REMOTE_ADDR"];exit;
		$memberlist = M('member_register_info')->where(array('id'=>array('in','24174')))->field('id,companyid,totalexperiencevalue,moblie')->select();
		print_r($memberlist);exit;
		foreach($memberlist as $key=>$memberRegisterInfo){
			$rankList = M('member_card_rank')->where(array('companyid'=>$memberRegisterInfo['companyid']))->field('id,number,beginscore,endscore')->select();
			if($rankList){
				foreach ($rankList as $rkey=>$mcrlVal){
					if($mcrlVal['number'] == '1'){
					/* 	if($memberRegisterInfo['id']=="1892"){
							echo $mcrlVal['number'];exit;
						} */
						if($memberRegisterInfo['totalexperiencevalue']<=$mcrlVal['endscore']){
							//echo '11111';exit;
							$srankid = $mcrlVal['id'];
						}
					}elseif($mcrlVal['number'] == '4'){
						if($memberRegisterInfo['totalexperiencevalue']>=$mcrlVal['beginscore']){
							$srankid = $mcrlVal['id'];
						}
					}else{
						if ($mcrlVal['beginscore']<=$memberRegisterInfo['totalexperiencevalue'] && $memberRegisterInfo['totalexperiencevalue'] <= $mcrlVal['endscore']){
							$srankid = $mcrlVal['id'];
						}
					}
					unset($mcrlVal);
				}
				//echo $mcrlVal['number'];exit;
				//echo $memberRegisterInfo['totalexperiencevalue'];exit;
				$addCardDate['companyid'] = $memberRegisterInfo['companyid'];
				$addCardDate['updatetime'] = $addCardDate['createtime'] = time();
				$addCardDate['rankid'] = $srankid;
				$addCardDate['cardnum'] = $memberRegisterInfo['moblie'];
				$addCardDate['mid'] = $memberRegisterInfo['id'];
				$cardInfoReturn = M('member_card_info')->add($addCardDate);
				echo M()->getLastSql();
				if($cardInfoReturn){
					$this->memberTagCount($memberRegisterInfo['companyid'], array(array('name'=>'rankid','after'=>$addCardDate['rankid'])));
					echo 'success'.$memberRegisterInfo['id']."<br>";
				}else{
					echo 'error'.$memberRegisterInfo['id']."<br>";
				}
			}
			unset($memberRegisterInfo);
		}
	}
	public function cardinfo(){
		$list = M('company')->where(array('_string'=>' gid = "5" OR viptime > '.time()))->field('id')->select();
		if($list){
			foreach ($list as $key=>$val){
				$memberlist = M('member_register_info')->where(array('companyid'=>$val['id'],'isregister'=>'1'))->field('id,totalexperiencevalue')->select();
				if($memberlist){
					foreach ($memberlist as $mkey=>$mval){
						$count = M('member_card_info')->where(array('companyid'=>$val['id'],'mid'=>$mval['id']))->count();
						if(!$count){
							echo $val['id'].'///'.$mval['id'].'///'.$mval['moblie']."<br>";
						}
						unset($mval,$count);
					}
				}
				unset($val,$memberlist);
			}
		}
	}
	public function membercount(){
		$list = M('company')->where(array('_string'=>' gid = "5" OR viptime > '.time()))->field('id')->select();
		if($list){
			foreach ($list as $key=>$val){
				$count = '';
				$count = M('member_register_info')->where(array('companyid'=>$val['id'],'isregister'=>'1'))->count();
				M('company')->where(array('id'=>$val['id']))->save(array('registermembernum'=>$count,'updatetime'=>time()));
				echo $val['id'].'///'.$count."<br>";
			}
		}
	}
	public function testVouchersIsLink(){
		$option['companyid'] = 1217;
		$option['vouchertype'] = 5;
		$option['voucherid'] = 'pg0121700027';
		$option['voucherskuid'] = '3';
		$this->vouchersIsLink($option);
	}
	/**
	 * 测试发送 会员升级等级送券
	 * @author Thomas<416369046@qq.com>
	 * @since  2016-11-25
	 */
	public function memberquan(){
		$a = $this->sendMemberVouchersSCRM5('[{"voucherid":"pg0121700036","vouchertype":"7","vouchersku":"3183"},{"voucherid":"pg0121700036","vouchertype":"7","vouchersku":"3183"}]','1085','1217','14');
		print_R($a);exit;
		//$this->sendMemberVouchersSCRM5('E16120823034701217001','1080','1217','8');
		//$this->sendMemberVouchersSCRM5('1F24KHZ693MIZ','243986','1','9');
		/* $this->sendMemberVouchersSCRM5('18FO8PQ1MW4CM','204842','1','9');
		$this->sendMemberVouchersSCRM5('1F24KHZ693MIZ','204842','1','9');
		$this->sendMemberVouchersSCRM5('2435FMZIJ7GGS','204842','1','9');
		$this->sendMemberVouchersSCRM5('26DKO0FQ0V6S4','204842','1','9');
		$this->sendMemberVouchersSCRM5('2P0LI9ONRZ0GG','204842','1','9');
		$this->sendMemberVouchersSCRM5('2Y4OHFP2AQWWO','204842','1','9');
		$this->sendMemberVouchersSCRM5('37MTIASSNNUOS','204842','1','9');
		$this->sendMemberVouchersSCRM5('3AMTV1BIYQSK0','204842','1','9');
		$this->sendMemberVouchersSCRM5('3AS85P16A6UCK','204842','1','9');
		$this->sendMemberVouchersSCRM5('3I2TS3XS5Q4G8','204842','1','9'); */
	}
	
	public function savemember(){
		$time = time();
		$list = M('company')->where(array('_string'=>' gid = "5" OR viptime > '.time()))->field('id')->select();
		if($list){
			foreach ($list as $key=>$val){
				$memberList = M('member_register_info')->where(array('companyid'=>$val['id'],'isregister'=>'1'))->field('id,totalexperiencevalue')->select();
				$rankList = M('member_card_rank')->where(array('companyid'=>$val['id']))->field('id,number,beginscore,endscore')->select();
				if($rankList){
					foreach ($rankList as $rkey=>$rval){
						$where['companyid'] = $val['id'];
						$where['isregister'] = '1';
						if($rval['number'] == '1'){
							$where['totalexperiencevalue'] = array('elt',$rval['endscore']);
						}elseif($rval['number'] == '4'){
							$where['totalexperiencevalue'] = array('egt',$rval['beginscore']);
						}else{
							$where['totalexperiencevalue'] = array('between',array($rval['beginscore'],$rval['endscore']+1));
						}
						$array = M('member_register_info')->where($where)->getField('id',true);
						if($array){
							$mid = '';
							foreach ($array as $val){
								$mid .=$val.',';
							}
							$mid = substr($mid, 0,-1);
							M('member_card_info')->where(array('companyid'=>$val['id'],'mid'=>array('in',$mid)))->save(array('rankid'=>$rval['id'],'updatetime'=>$time));
							$count = M('member_card_info')->where(array('companyid'=>$val['id'],'rankid'=>$rval['id']))->count();
							M('member_card_rank')->where(array('companyid'=>$val['id'],'id'=>$rval['id']))->save(array('reportnumber'=>$count,'updatetime'=>$time));
						}
					}
				}
			}
		}
	}
	
	
	
	public function membercard(){
		M('member_card_rank')->where(array('number'=>array('gt','4')))->delete();
		$list = M('test_member_card_rank')->where(array('issave'=>'2'))->select();
		if($list){
			//print_r($list);
			foreach ($list as $key=>$val){
				M('member_card_rank')->where(array('companyid'=>$val['companyid'],'number'=>array('gt','4')))->delete();
				$data['name'] = $val['name'];
				if($val['beginscore']){
					$data['beginscore'] = $val['beginscore'];
				}
				if($val['endscore']){
					$data['endscore'] = $val['endscore'];
				}
				$data['updatetime'] = time();
				$count = M('member_card_rank')->where(array('companyid'=>$val['companyid'],'number'=>$val['number']))->count();
				if($count>0){
					$a = M('member_card_rank')->where(array('companyid'=>$val['companyid'],'number'=>$val['number']))->save($data);
				}else{
					$data['id'] = guidNow();
					$data['companyid'] = $val['companyid'];
					$data['number'] = $val['number'];
					$data['createtime'] = time();
					$a = M('member_card_rank')->add($data);
				}
				if($a){
					M('test_member_card_rank')->where(array('companyid'=>$val['companyid'],'number'=>$val['number']))->save(array('issave'=>'1'));
				}
				unset($data,$a);
			}
		}
	}
	public function quan(){
		$this->sendMemberVouchersSCRM5('20','301984','1','4');
	}
	/**
	 * 测试 eshop购买的发券
	 * @author Thomas<416369046@qq.com>
	 * @since  2016-11-25
	 */
	public function  eshopVouchers(){
		$this->sendMemberVouchersSCRM5('E16112521022401217001', '1080', '1217','8');
	}
	/**
	 * 测试未付款通知消息模板的发送
	 * @author Thomas<416369046@qq.com>
	 * @since  2016-11-25
	 */
	public function noPay(){
		$this->WeChatTemplateMessageSend('7','o0XI7wL-vuKvt2ujEIEhR9BTjItE','1217','','',array('0.01','ryann测试计次卡 100','','E16112513072901217001'),'');
	}
	/**
	 * 测试储值支付
	 * @author Thomas<416369046@qq.com>
	 * @since  2016-11-25
	 */
	public function remainOrderPay(){
		$this->mallOrderPaySCRM5('E16112521022401217001', '1217');
	}
	public function company(){
		$where['gid'] = '5';
		$where['_logic'] = 'or';
		$where['viptime'] = array('gt',time());
		M('company')->where($where)->select();
		echo M()->getLastSql();
	}
	public function lafenma(){
		$list = M('quick_response_code')->where(array('companyid'=>'1088','userid'=>array('neq','0'),'type'=>'1'))->field('content')->select();
		if($list){
			foreach ($list as $key=>$val){
				$sub = M('history_wechat_request')->where(array('token'=>'iacbss1413426846','Event'=>'subscribe','EventKey'=>'qrscene_'.$val['content']))->count();
				$date[$key] = $unsub = M('history_wechat_request')->where(array('token'=>'iacbss1413426846','Event'=>'unsubscribe','EventKey'=>'qrscene_'.$val['content']))->count();
				$sc = M('member_wechat_info')->where(array('companyid'=>'1088','scene_id'=>$val['content']))->count();
				if($sub>0){
					$data['subscribe'] = $sub;
				}
				if($unsub>0){
					$data['unsubscribe'] = $unsub;
				}
				if($sc>0){
					$data['scannum'] = $sc;
				}
				$data['updatetime']=time();
				M('quick_response_code')->where(array('companyid'=>'1088','content'=>$val['content']))->save($data);
				unset($data,$val);
			}
		}
		print_r($date);exit;
	}
	/**
	 * 
	 * SCRM5添加公司权限
	 * 
	 * @author Mark<1311013341@qq.com>
	 * @since  2016-9-13
	 */
	public function permissions(){
		//权限ID，若无二级菜单只写一级菜单的ID   若有二级菜单只写二级菜单的ID，一级不写
		$per = M('system_scrm5_permissions_list')->where(array('isshow'=>'1','isedit'=>'1'))->order('id asc')->field('id')->select();
		if($per){
			$a='';
			foreach($per as $key=>$val){
				$a = $a.','.$val['id'];
			}
		}
		$_POST['scrm5permissions'] = $a.',';   
		$_POST['updatetime'] = time();
		$User = M("company"); 
		$a = $User->where(array('status'=>3))->save($_POST);
		if($a){
			echo 'success';
		}else{
			echo 'error';
		}
	}
	/**
	 * session权限测试
	 */
	public function sessiontest(){
		$per = M('system_scrm5_permissions_list')->where(array('isshow'=>'1','isedit'=>'1'))->order('id asc')->field('id')->select();
		if($per){
			$a='';
			foreach($per as $key=>$val){
				$a = $a.','.$val['id'];
			}
		}
		$a = $a.',';
		session('S5permissions',explode(',', $a));
		print_r(session('S5permissions'));
	}
	/**
	 * 
	 * 添加表情
	 * 
	 * @author Mark<1311013341@qq.com>
	 * @since  2016-9-13
	 */
	public function index(){
		$google = array('U+FE000','U+FE327','U+FE32C','U+FE330','U+FEB69','U+FE190','U+FE4DD','U+FE4F6','U+FE510','U+FEB5E','U+FEB9E','U+FEB97','U+FE19A','U+FE4B7','U+FE524','U+FE7E9','U+FE7D9','U+FE532');
		$emoji = array('☀','😍','😘','😃','🌟','👀','💰','🔥','🎁','💪','👏','👍','👤','🏨','📞','✈','🏃','💬');
		for($i=0;$i<18;$i++){
			$data['emoji'] = '[[EMOJI:'.rawurlencode($emoji[$i]).']]';
			$data['google'] = $google[$i];
			$data['updatetime'] = time();
			M('emoji')->add($data);
		}
	}
	public function info(){
		$info = M('emoji')->select();
		if($info){
			foreach ($info as $k=>$v){
				echo $a = preg_replace_callback("/\[\[EMOJI:(.*?)\]\]/", function($matches){return rawurldecode($matches[1]);}, $v['emoji'])."<br/>";
			}
		}
	}
	
/*************************签名请求*****************************/
	/**
	 * 
	 * 请求数据方
	 * 
	 * @author Mark<1311013341@qq.com>
	 * @since  2016-8-23
	 */
	public function test1(){
		$data['a'] = 'aaaaa';
		$data['b'] = 'bbbbb';
		$data['c'] = 'ccccc';
		//使用http_post方法提交数据
		$result = $this->http_post(C('site_url').'/index.php?g=User5&m=Test&a=test2&companyid=1',self::SignaTure(json_encode($data)));
		$data = json_decode($result,true);
		$b = json_decode(base64_decode($data['data']),true);
		print_r($b);exit;
	}
	/**
	 * 
	 * 接收数据方并返回数据给请求方
	 * 
	 * @author Mark<1311013341@qq.com>
	 * @since  2016-8-23
	 */
	public function test2(){
		//接收提交的数据
		$result = file_get_contents('php://input');
		$a = self::verity($result);
		if($a == '1'){
			echo $result;
		}else{
			echo 'error';
		}
	}
	/**
	 * POST 请求
	 * @param string $url
	 * @param array $param
	 * @param boolean $post_file 是否文件上传
	 * @return string content
	 */
	private function http_post($url,$param,$post_file=false){
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
	 * 消息加密并生成签名
	 * 
	 * @param unknown $json   json格式
	 * @return string
	 * @author Mark<1311013341@qq.com>
	 * @since  2016-8-23
	 */
	private function SignaTure($json){
		$encrypted = "";
		$json_data = base64_encode($json);  //对数据进行Base加密
		$priKey = file_get_contents(C('site_url')."/key/rsa_private_key.pem");     //读取私钥
		$pri_key = openssl_pkey_get_private($priKey);    //判断私钥是否可用
		openssl_sign($json_data, $encrypted, $pri_key, OPENSSL_ALGO_SHA1); //openssl_private_encrypt($data,$encrypted,$pri_key);      //私钥加密
		$encrypted = base64_encode($encrypted);
		$date = '{"data":"'.$json_data.'","sign":"'.$encrypted.'"}';
		return $date;
	}
	/**
	 * 
	 * 验证签名是否正确      返回值：1：success；其他：error
	 * 
	 * @param unknown $data
	 * @return boolean
	 * @author Mark<1311013341@qq.com>
	 * @since  2016-8-23
	 */
	private function verity($data){
		$decrypted = "";
		$sign = json_decode($data,true);
		$pubKey = file_get_contents(C('site_url')."/key/rsa_public_key.pem");
		$pu_key = openssl_get_publickey($pubKey);
		$result = (bool)openssl_verify($sign['data'], base64_decode($sign['sign']), $pu_key);
		openssl_free_key($pu_key);
		return $result;
	}
	/**
	 * 
	 * 消息模板
	 * 
	 * @author Mark<1311013341@qq.com>
	 * @since  2016-9-20
	 */
	public function jsonmuban(){
		//$this->WeChatTemplateMessageSend('17','o0XI7wOg39a5WsL4f-wIoFEmtyKs','1217','','',array('预订订单提交成功','手机预订'),array('mark','15000451717',format_time(time(),'ymdhi')));
		$data['first'] = '您好，Eshop微商城有一个新订单。';
		//$data['firstadd'] = '';
		$data['content'] = array(array('任务名称','',''),array('通知类型','',''));
		//$data['remark'] = array(array('订单编号','',''),array('订单金额','',''),array('商品详情','',''));//,array('退还金额','',''),array('现有储值余额','',''),array('预订时间&时段','',''));//
		$data['end'] = '请您尽快前往后台处理！';
		echo json_encode($data);
	}
	/**
	 * 






	 * 调研造假
	 * 
	 * @author Mark<1311013341@qq.com>
	 * @since  2016-10-20
	 */
	public function survey(){
		$data['companyid'] = '1338';
		$data['tid'] = '10S69RT3PNVFX';
		$data['qid'] = '3W5BAMB24TC08';
		$data['openid'] = 'oT56NjgWpBg-6aMDrEpR7cQeUGM0';
		$data['nickname'] = '普莱恩';
		$data['updatetime'] = $data['createtime'] = time();
		//1
		for($i1=0;$i1<16;$i1++){
			$data['answer'] = json_encode(array(array('Option'=>'1','Answer'=>'Hans','Image'=>'http://www.mobiwind.cn/Uploads/1338/20161019/20161019113359_61806.jpg')));
			$data['id'] = guidNow();
			M('survey_activity_member')->add($data);
		}
		//2
		for($i2=0;$i2<15;$i2++){
			$data['answer'] = json_encode(array(array('Option'=>'2','Answer'=>'辰辰','Image'=>'http://www.mobiwind.cn/Uploads/1338/20161019/20161019113359_30384.jpg')));
			$data['id'] = guidNow();
			M('survey_activity_member')->add($data);
		}
		//3
		for($i3=0;$i3<13;$i3++){
			$data['answer'] = json_encode(array(array('Option'=>'3','Answer'=>'天宝','Image'=>'http://www.mobiwind.cn/Uploads/1338/20161019/20161019113400_12925.jpg')));
			$data['id'] = guidNow();
			M('survey_activity_member')->add($data);
		}
		//4
		for($i4=0;$i4<25;$i4++){
			$data['answer'] = json_encode(array(array('Option'=>'4','Answer'=>'Yuno','Image'=>'http://www.mobiwind.cn/Uploads/1338/20161019/20161019113351_63625.jpg.jpg')));
			$data['id'] = guidNow();
			M('survey_activity_member')->add($data);
		}
		//5
		for($i5=0;$i5<30;$i5++){
			$data['answer'] = json_encode(array(array('Option'=>'5','Answer'=>'Kenny','Image'=>'http://www.mobiwind.cn/Uploads/1338/20161019/20161019113351_43578.jpg')));
			$data['id'] = guidNow();
			M('survey_activity_member')->add($data);
		}
		//6
		for($i6=0;$i6<21;$i6++){
			$data['answer'] = json_encode(array(array('Option'=>'6','Answer'=>'小丸子','Image'=>'http://www.mobiwind.cn/Uploads/1338/20161019/20161019113345_44551.jpg')));
			$data['id'] = guidNow();
			M('survey_activity_member')->add($data);
		}
		//7
		for($i7=0;$i7<12;$i7++){
			$data['answer'] = json_encode(array(array('Option'=>'7','Answer'=>'阿泽','Image'=>'http://www.mobiwind.cn/Uploads/1338/20161019/20161019113340_40830.jpg')));
			$data['id'] = guidNow();
			M('survey_activity_member')->add($data);
		}
		//8
		for($i8=0;$i8<18;$i8++){
			$data['answer'] = json_encode(array(array('Option'=>'8','Answer'=>'快乐兄妹','Image'=>'http://www.mobiwind.cn/Uploads/1338/20161019/20161019113336_70949.jpg')));
			$data['id'] = guidNow();
			M('survey_activity_member')->add($data);
		}
		//9
		for($i9=0;$i9<23;$i9++){
			$data['answer'] = json_encode(array(array('Option'=>'9','Answer'=>'女汉子','Image'=>'http://www.mobiwind.cn/Uploads/1338/20161019/20161019113332_87796.jpg')));
			$data['id'] = guidNow();
			M('survey_activity_member')->add($data);
		}
		//10
		for($i10=0;$i10<13;$i10++){
			$data['answer'] = json_encode(array(array('Option'=>'10','Answer'=>'Oscar','Image'=>'http://www.mobiwind.cn/Uploads/1338/20161019/20161019113322_49533.jpg')));
			$data['id'] = guidNow();
			M('survey_activity_member')->add($data);
		}
	}
	/**
	 * 
	 * 修复秀米的图片，下载到本地，并替换原图片地址
	 * 
	 * @return string
	 * @author Lando<806728685@qq.com>
	 * @since  2017-1-16
	 */
	public function repairXiuMiPic(){
        header("content-type:text/html;charset=utf8");
	    include("./Tpl/static/ueditor1.4.3/php/Uploader.class.php");
	    $source = array(); //图片数组
	    
	    //http://statics.xiumi.us/stc/images/templates-assets/tpl-paper/image/2017-1-5-22.jpeg
	    //http://img.xiumi.us/xmi/ua/NfC4/i/62646a6c53e2d1ba144d0fd235e78348-sz_3777009.jpg@1l_640w.jpg
	    // ueditor/php/upload/image/20151204/
	    // Eshop商品详情页
	    //$repairData = M('mall_goods')->field('id,companyid,info')->where(array('info'=>array('like','%.xiumi.us%')))->order('companyid ASC')->select();
	    
	    // 积分商城商品详情页
	    //$repairData = M('mall_member_integral_goods')->field('id,companyid,info')->order('companyid ASC')->select();
	    
	    //积分商城如何获取更多积分
	    //$repairData = M('mall_member_integral_base')->field('id,companyid,info')->order('companyid ASC')->select();
	    
	    //手机预订，项目详情页
	    //$repairData = M('mobile_book_project_set')->field('id,companyid,bookinfo')->order('companyid ASC')->select();

	    //会员制说明
	    //$repairData = M('member_card_rank_set')->field('id,companyid,info')->order('companyid ASC')->select();
	    
	    //会员等级权益
	    //$repairData = M('member_card_rank')->field('id,companyid,desc')->order('companyid ASC')->select();
	     
	    //微页面
	    //$repairData = M('wei_assembly')->field('id,companyid,textinfo')->where(array('textinfo'=>array('like','%.xiumi.us%')))->order('companyid ASC')->select();
	    
	    //print_r($repairData);exit();
	    //调研
	    //$repairData = M('survey_activity_theme')->field('id,companyid,info')->order('companyid ASC')->select();
	    if($repairData){
	        $i = 0;
            $pattern = '/http\:([^\"\']+)\.xiumi\.us([^\"\']+)\.(jpg|jpeg|png|gif|bmp)/i';
            //$pattern = '/ueditor\/php\/upload\/([^\"\']+)\.(jpg|jpeg|png|gif|bmp)/i';
	        foreach ($repairData as $mKey=>$mVal){
	            echo $mVal['id']."<br/>";
	            $mVal['info'] = $mVal['bookinfo'];// 注意这个地方的字段需要根据上方读取的字段进行替换
	            $subject = $mVal['info'] ? htmlspecialchars_decode($mVal['info']) : '';
	            $isMatched = preg_match_all($pattern, $subject,$matches);
	            if($isMatched){
    	            $source =  $matches[0];
    	            if($source){
    	               $_SESSION['cid'] = $mVal['companyid'];
    	                $config = array(
    	                        "pathFormat" => "/Uploads/".$_SESSION['cid']."/catchimage/{yyyy}{mm}{dd}/{time}{rand:6}",
    	                        "maxSize" => 2048000,
    	                        "allowFiles" => array('.png','.jpg','.jpeg','.gif','.bmp'),
    	                        "oriName" => "remote.png"
    	                );
    	                foreach ($source as $skey=>$simgUrl) {
    	                    $item = new Uploader($simgUrl, $config, "remote");
    	                    $info = $item->getFileInfo();
    	                    //print_r($info);
    	                    $list[$skey]['url'] = $info["url"];
    	                    $list[$skey]['source'] = $simgUrl;
    	                }
    	                //$list = $source;
    	                if($list){
    	                    foreach ($list as $lKey=>$lVal){
    	                        $subject = str_replace($lVal['source'],$lVal['url'],$subject);
    	                        //$subject = str_replace('ueditor/php/upload/image','Uploads/publiccatchimage',$subject);
    	                    }
    	                    // Eshop商品详情页
    	                    //$saveReturn = M('mall_goods')->where(array('id'=>$mVal['id']))->save(array('info'=>$subject,'updatetime'=>time()));
    	                    // 积分商城商品详情页
    	                    //$saveReturn = M('mall_member_integral_goods')->where(array('id'=>$mVal['id'],'companyid'=>$mVal['companyid']))->save(array('info'=>$subject,'updatetime'=>time()));
    	                    //积分商城如何获取更多积分
    	                    //$saveReturn = M('mall_member_integral_base')->where(array('id'=>$mVal['id']))->save(array('info'=>$subject,'updatetime'=>time()));
    	                    //手机预订，项目详情页
    	                    //$saveReturn = M('mobile_book_project_set')->where(array('id'=>$mVal['id']))->save(array('bookinfo'=>$subject,'updatetime'=>time()));
    	                    //会员制说明
    	                    //$saveReturn = M('member_card_rank_set')->where(array('id'=>$mVal['id']))->save(array('info'=>$subject,'updatetime'=>time()));
    	                    //微页面
    	                    //$saveReturn = M('wei_assembly')->where(array('id'=>$mVal['id']))->save(array('textinfo'=>htmlspecialchars($subject),'updatetime'=>time()));
    	                    
    	                    if($saveReturn){
    	                        echo 'success<br/>'.$saveReturn;
    	                        //echo M()->getLastSql();
    	                        echo "<br/>";
    	                    }else{
    	                        echo M()->getLastSql();
    	                        echo 'fail<br/>';
    	                    }
    	                    unset($list,$lVal,$subject);
    	                }
    	                //echo $subject."<br/>";
    	            }
	                $i++;
	            }
	        }
	    }
	    /* 
	    // 返回抓取数据
	    return json_encode(array(
	            'state'=> count($list) ? 'SUCCESS':'ERROR',
	            'list'=> $list
	    )); */
	}
}
?>