<?php
/**
 * 
 * 
 * @author    Asa<asa@renlaifeng.cn>
 * @since     2016-11-12
 * @version   1.0
 */
class SmsSendAction extends UserAction{
	public function _initialize(){
		parent::_initialize();
		$this->checkCompanyScrm5Permissions(84,true,4);
		$this->companyid = session('cid');
	}
	/**
	 * SMS短信首页
	 */
	public function index(){
		$this->makeTopUrl = $this->makeTopUrl_User(array($this->APPRECIATION_WELCOME,array('name'=>'SMS短信通道 ','url'=>U("SmsSend/index")),array('name'=>'我的短信')));
		$this->comInfo = M("company")->where(array("id"=>$this->companyid))->find();
		$this->logCount = M("log_sms_send")->where(array("companyid"=>$this->companyid,'smsstate'=>2))->count();
		$this->display();
	}
	/**
	 * 推送统计
	 */
	public function smsInfoLog(){
		
		$this->makeTopUrl = $this->makeTopUrl_User(array($this->APPRECIATION_WELCOME,array('name'=>'SMS短信通道 ','url'=>U("SmsSend/index")),array('name'=>'推送统计')));
		$count = M("log_sms_send_everyday")->where(array("companyid"=>$this->companyid))->order("createtime desc")->count();
		$page = new NewPage($count,15);
		$this->lists = M("log_sms_send_everyday")->where(array("companyid"=>$this->companyid))->limit($page->firstRow.','.$page->listRows)->order("today desc")->select();
		$this->assign('page',$page->diyshow());
		
		$this->display();
	}
	/**
	 * 充值记录
	 */
	public function smsMoneyLog(){
		$this->makeTopUrl = $this->makeTopUrl_User(array($this->APPRECIATION_WELCOME,array('name'=>'SMS短信通道 ','url'=>U("SmsSend/index")),array('name'=>'充值记录')));
		$count = M("check_hardware_order")->where(array("companyid"=>$this->companyid,'ordertype'=>3,'goodtype'=>3))->order("createtime desc")->count();
		$page = new NewPage($count,15);
		$this->lists = M("check_hardware_order")->where(array("companyid"=>$this->companyid,'ordertype'=>3,'goodtype'=>3))->limit($page->firstRow.','.$page->listRows)->order("createtime desc")->select();
		$this->assign('page',$page->diyshow());
		$this->display();
	}
	/**
	 * 营销短信信息
	 */
	public function market(){
		if(IS_POST){
			$data = $_POST;
			$data['companyid'] = $this->companyid;
			$data['state'] = 1;
			$data['money'] = $this->_post("mobilenum")*0.08;
			$data['createtime'] = $data['updatetime'] = time();
			$res = M("check_sms_send")->add($data);
			if($res){
				$ajax['code']='200';
				$ajax['msg']='提交成功';
				$this->sendSms('13564012907', '有一条新的营销短信等待审核，请前往check查看详细信息：公司id:'.$this->companyid,'1186','【人来风】','','181818');
			}else{
				$ajax['code']='300';
				$ajax['msg']='提交失败';
			}
			echo json_encode($ajax);
		}else{
			$where['sms.companyid'] = $this->companyid;
			$count = M()->table('tp_check_sms_send as sms')->join('tp_company as com on com.id=sms.companyid')
			->where($where)->count();
			$page = new NewPage($count,15);
			$list = M()->table('tp_check_sms_send as sms')->join('tp_company as com on com.id=sms.companyid')
			->field('sms.id,sms.content,sms.mobilenum,sms.createtime,sms.state,sms.username,sms.balance,sms.file,sms.money,sms.starttime,errorinfo')
			->order('sms.createtime DESC')->where($where)->limit($page->firstRow.','.$page->listRows)->select();
			foreach($list as $key => $val){
				$list[$key]['successnum'] = M("log_sms_send")->where(array("companyid"=>$this->companyid,"sid"=>$val['id'],'smsstate'=>2))->count();
			}
			
			$this->assign('page',$page->diyshow());
			$this->assign('list',$list);
			$this->makeTopUrl = $this->makeTopUrl_User(array($this->APPRECIATION_WELCOME,array('name'=>'SMS短信通道','url'=>U("SmsSend/index")),array('name'=>'营销短信')));
			$this->companyInfo = M("company")->where(array("id"=>$this->companyid))->field("balance,smsnum")->find();
			$this->display();
		}
	}
	/**
	 * 检测手机号的数量
	 */
	public function ajaxMobnum(){
		if($this->_request('file')){
			$data['file'] = $this->_request('file');
			$ajax['code']='200';
			$filename = $data['file'];
			$j = 0;
			$i = 0;
			$f = fopen($filename,'r');
			while(!feof($f)){
				$j++;
				$info = '';
				$arr = '';
				$line = fgets($f);
				if($j >= 2){
					$info = explode(',',$line);
					$arr['mob'] = trim(preg_replace('/(^\s*")|("\s*$)/', '', $info[0]));
					$arr['mob'] = mb_convert_encoding($arr['mob'], "UTF-8", "GBK");
					if(preg_match("/^1[34578]{1}\d{9}$/",$arr['mob'])&&$arr['mob']){
						$i++;
					}
				}
			}
			$ajax['msg2'] = '成功导入有效手机号'.$i.'个';
			$ajax['msg'] = $i;
		}
		echo json_encode($ajax);
	}
	/**
	 * 推送日志
	 */
	public function ajaxNotify(){
		$info = M("log_sms_send")->where(array("companyid"=>$this->companyid,"sid"=>$this->_post("id"),'smsstate'=>array("in","1,3")))
		->limit(0,49)->select();
		$i=1;
		$str = '';
		foreach ($info as $val){
			if($i==1){
				$i=1;
				$str .='<tr class="not-hover">';
				$str .='<td>'.$val['mobile'].'</td>';
			}elseif($i==7){
				$str .='<td>'.$val['mobile'].'</td>';
				$str .='</tr>';
			}else{
				$str .='<td>'.$val['mobile'].'</td>';
			}
			$i++;
		}
		if($str){
			$ajax['code'] = 200;
			$ajax['msg'] = $str;
		}else{
			$ajax['code'] = 300;
			$ajax['msg'] = '<tr class="text-center not-hover"><td colspan="7">暂无</td></tr>';
		}
		echo json_encode($ajax);
	}
	/**
	 * 取消发送任务的
	 */
	public function ajaxStatus(){
		$data['state'] = 5;
		$data['updatetime'] = time();
		$res = M("check_sms_send")->where(array("companyid"=>$this->companyid,"id"=>$this->_post("id")))->save($data);
		if($res){
			$ajax['code']='200';
			$ajax['msg']='修改成功';
		}else{
			$ajax['code']='300';
			$ajax['msg']='修改失败';
		}
		echo json_encode($ajax);
	}
	/**
	 * 跑数据
	 */
	public function sql(){
		$lists = M("log_sms_send")->select();
		foreach ($lists as $val){
			//写推送统计的
			if($val['companyid']){
				$where['datetime'] = strtotime(date("Y-m-d",$val['createtime']));
				$where['companyid'] = $val['companyid'];
				$smsInfo = M("log_sms_send_everyday")->where($where)->find();
				$smsData['updatetime'] = time();
				if($smsInfo){
					$smsData['znum'] = $smsInfo['znum']+1;
					if($smsstate==2){
						$smsData['snum'] = $smsInfo['snum']+1;
						$smsData['mnum'] = $smsInfo['mnum']+1;
					}
					if($val['smstype']==1){
						$smsData['sellnum'] = $smsInfo['sellnum']+1;
					}else{
						$smsData['notifynum'] = $smsInfo['notifynum']+1;
					}
					$res = M("log_sms_send_everyday")->where($where)->save($smsData);
				}else{
					$smsData['id'] = guidNow();
					$smsData['companyid'] = $val['companyid'];
					$smsData['today'] = strtotime('today');
					$smsData['createtime'] = time();
					$smsData['znum'] = 1;
					if($smsstate==2){
						$smsData['snum'] = 1;
						$smsData['mnum'] = 1;
					}
					if($val['smstype']==1){
						$smsData['sellnum'] = 1;
					}else{
						$smsData['notifynum'] = 1;
					}
					$res = M("log_sms_send_everyday")->where($where)->add($smsData);
				}
				if(!$res){
					$arrA['log'] = $val['companyid'];
					$arrA['id'] = $val['id'];
				}
			}else{
				$arrA['log'] = "没有公司ID";
				$arrA['id'] = $val['id'];
				$arr[] = $arrA;
				$arrA = '';
			}
			
		}
		dump($arr);
	}
	/**
	 * 会员注册
	 */
	public function smsregister(){
		$this->makeTopUrl = $this->makeTopUrl_User(array($this->APPRECIATION_WELCOME,array('name'=>'SMS短信通道 ','url'=>U("SmsSend/index")),array('name'=>'我的短信','url'=>U("SmsSend/index")),array("name"=>"会员注册验证码")));
		$this->display();
	}
	/**
	 * 
	 * 是否启用新订单提醒
	 * 
	 * @author Leo<1251868177@qq.com>
	 * @since  2017-3-6
	 */
	public function orderRemind(){
	    if(IS_POST){
	        $returnData['code'] = 300;
	        $returnData['tips'] = '抱歉，服务器繁忙，请稍后重试';
	        $companyid = $this->companyid;
	        $type = $this->_post('type');
	        $isopen = $this->_post('isopen');
	        if($companyid && $type && $isopen){
	            if($type == 1){
	                $data['takeoutorderisopen'] = $isopen;     
	            }elseif($type == 2){
	                $data['eshoporderisopen'] = $isopen;
	            }elseif($type == 3){
	                $data['commonbookorderisopen'] = $isopen;
	            }elseif($type == 4){
	                $data['shanhuiorderisopen'] = $isopen;
	            }elseif($type == 5){
	                $data['shopcashierorderisopen'] = $isopen;
	            }elseif($type == 6){
	                $data['storedvalueorderisopen'] = $isopen;
	            }
	            $data['updatetime'] = time();
	            $return = M('company')->where(array('id'=>$companyid))->save($data);
	            if($return){
	                $returnData['code'] = 200;
	                $returnData['tips'] = '操作成功';
	            }
	        }
	        echo json_encode($returnData);
	    }else{
    	    $type = $this->_get('type');
    	    // type 1:风外卖  2:Eshop  3:预定
    	    if($type == 1){
    	        $name = '风外卖订单提醒';
    	    }elseif($type == 2){
    	        $name = 'Eshop订单提醒';
    	    }elseif($type == 3){
    	        $name = '预订订单提醒';
    	    }elseif($type == 4){
    	        $name = '闪惠订单提醒';
    	    }elseif($type == 5){
    	        $name = '门店收银订单提醒';
    	    }elseif($type == 6){
    	        $name = '储值充值订单提醒';
    	    }
    	    $this->makeTopUrl = $this->makeTopUrl_User(array($this->APPRECIATION_WELCOME,array('name'=>'SMS短信通道 ','url'=>U("SmsSend/index")),array('name'=>'我的短信','url'=>U("SmsSend/index")),array('name'=>$name)));
    	    if($type){
        	    $info = M('company')->where(array('id'=>$this->companyid))->field('id,takeoutorderisopen,eshoporderisopen,commonbookorderisopen,shanhuiorderisopen,shopcashierorderisopen,storedvalueorderisopen')->find();
        	    $this->assign('info', $info);
        	    $this->assign('type', $type);
        	    $this->display();
    	    }
	    }
	}
	/**
	 * 会员登陆
	 */
	public function smslogin(){
		$this->makeTopUrl = $this->makeTopUrl_User(array($this->APPRECIATION_WELCOME,array('name'=>'SMS短信通道 ','url'=>U("SmsSend/index")),array('name'=>'我的短信','url'=>U("SmsSend/index")),array("name"=>"会员登陆验证码")));
		$this->display();
	}
	/**
	 * 可能不用了
	 * 
	 * @author Asa<asa@renlaifeng.cn>
	 * @since  2016-11-25
	 */
	public function excelNotify(){
		$info = M("log_sms_send")->where(array("companyid"=>$this->companyid,"sid"=>$this->_request("id"),'smsstate'=>array("in","1,3")))
		->select();
		$content = "手机号\r\n";
		foreach($info as $key=>$value){
			$content .= '"'.$value['mobile'].'",';
			$content .= "\r\n";
		}
		$date = date("YmdHis",time());
		$fileName .= '发送失败的手机号'."_{$date}.csv";
		$fileName = iconv("utf-8", "GBK", $fileName); //转化编码，否则会出现乱码
		$content = iconv("utf-8", "GBK//IGNORE", $content); //转化编码，否则会出现乱码
		header("Content-type:text/csv");
		header("Content-Disposition:attachment;filename=".$fileName);
		header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
		header('Expires:0');
		header('Pragma:public');
		echo $content;
	}
	/**
	 * 跑方法的
	 */
	public function asaSqltest(){
		$info = M("company")->where(array("status"=>3))->field("id,smsnum,balance")->select();
		$smsnum = 200;
		$balance = 200*0.08;
		foreach($info as $val){
			$data['smsnum'] = ($val['smsnum'] + $smsnum);
			$data['balance'] = ($val['balance'] + $balance);
			$res = M("company")->where(array("id"=>$val['id']))->save($data);
			if(!$res){
				$arr[] = $val['companyid'];
			}
		}
		dump($arr);
	}
	
}