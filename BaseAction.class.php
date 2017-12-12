<?php
/**
 * 基础文件 
 * Enter description here ...
 * @author Administrator 
 *
 */
class BaseAction extends Action{
    
    private $weChatTemplateIntDesc = array('1'=>'完善100%会员资料','2'=>'成功开卡','3'=>'推荐开卡','4'=>'会员等级升级','5'=>'线下确认消费记录','6'=>'后台人工消费记录',
                '7'=>'微信支付','8'=>'','9'=>'支付宝支付','10'=>'商城货到付款','11'=>'线上储值支付','12'=>'后台人工储值支付','13'=>'线上自助充值',
                '14'=>'后台人工充值','15'=>'LBS签到','16'=>'参与活动','17'=>'大众点评留好评','18'=>'TripAdvisor留好评','19'=>'人工加积分','20'=>'人工减积分',
                '21'=>'参与活动','22'=>'积分兑换','23'=>'可用积分自动清零','24'=>'风助手微信支付','25'=>'风助手支付宝支付','26'=>'风助手现金支付','27'=>'微信买单',
                '28'=>'风助手储值支付','29'=>'风助手银行卡支付');
    private $changeMemberIntegralType = array('101'=>'完善会员资料','102'=>'注册','103'=>'点评奖励','104'=>'手动加积分','105'=>'摇摇签到','106'=>'闪惠支付','107'=>'风助手手机收银','108'=>'微信关注','109'=>'首次消费',
            '110'=>'eshop支付','111'=>'拉卡拉手机收银','112'=>'充值','113'=>'手机预订','114'=>'微信外卖','116'=>'风助手加积分','117'=>'手机预订奖励积分','118'=>'手机预订','119'=>'手机预订','120'=>'手机预订','121'=>'付费内容商店','122'=>'手机预订奖励积分','123'=>'手机预订奖励积分','124'=>'手机预订奖励积分','125'=>'储值快捷收银(风助手)','126'=>'储值快捷收银(拉卡拉)','127'=>'声悦传情','201'=>'手动扣除积分','202'=>'到期自动清零','203'=>'会员WAP积分换储值','204'=>'积分商城','205'=>'风助手减积分','301'=>'后台储值消费');
   
    protected function _initialize(){
        define('RES', THEME_PATH . 'common');
        define('STATICS', TMPL_PATH . 'static');
        $this->assign('action', $this->getActionName());
        $this->keywordType = array('Img','Text','Voiceresponse','Host','Home','Liuyan','Member_business','Coupon','Lottery','Guajiang','Zadan','Photo','Panorama');
    }

    /**
     * 
     * Memcached/Memcache 设置缓存
     * 
     * @param string $op 操作类型：set(设置)，append(添加键值)，get(获取)，delete(删除缓存)，deleteAll(删除所有缓存)，getVersion(获取版本号)，getStats(获取状态)，getError(查看返回的错误)
     * @param unknown $key
     * @param string $value
     * @param number $expire
     * @return boolean|Ambigous <NULL, unknown, multitype:, boolean, mixed, Ambigous, multitype:>
     * @author Lando<806728685@qq.com>
     * @since  2016-6-25
     */
    public function memcManager($op = 'get', $key, $value = '', $expire = 86400){
    	$data = NULL;
    	$mem = new Memc();
    	$mem->addServer(array('host'=>'127.0.0.1','port'=>11211,'weight'=>111));
    	if($mem){
    		switch($op){
    			case 'set':
    				//缓存
    				//使用说明：$this->memcManager('set','key',array(array('1','2'),array('3','4')),10);
    			    $format_value = is_array($value)?json_encode($value):$value;
    				$data = $mem->set($key, $format_value, $expire);
    				break;
				case 'append':
				    //追加缓存,追加内容只能是字符串，不可以是数组
				    //使用说明：$this->memcManager('append','key','aaaaaaaaaaaaa');
				    $format_value = is_array($value)?json_encode($value):$value;
				    $data = $mem->append($key, $format_value);
				    break;
    			case 'get':
    				//获取
    			    //使用说明：$this->memcManager('get','key');
    				$data = $mem->get($key);
    				//如果是json格式转成数组,否则直接返回数据
    				$json_data = @json_decode($data, true);
    				if(!is_null($json_data)){
    					$data = $json_data;
    				}
    				break;
    			case 'delete':
    				//删除单个缓存
    				//使用说明：$this->memcManager('delete','key');
    				$data = $mem->delete($key,$expire);
    				break;
				case 'deleteAll':
				    //删除全部缓存,目前无论成功与否返回值都是false
				    //使用说明：$this->memcManager('deleteAll');
				    $data = $mem->deleteAll();
				    break;
			    case 'getVersion':
			        //获取软件版本号
			        $data = $mem->getVersion();
			        break;
		        case 'getStats':
		            //获取运行状态
		            $data = $mem->getStats();
		            break;
		        case 'getError':
		            //获取错误信息
		            $data = $mem->getError();
		            break;
    			default:
    				exit('请指定操作方式：操作类型：set(设置)，append(添加键值)，get(获取)，delete(删除缓存)，deleteAll(删除所有缓存)，getVersion(获取版本号)，getStats(获取状态)，getError(查看返回的错误)');
    				break;
    		}
    	}else{
    		return false;
    	}
    	return $data;
    }
    
    
	/**
	 * 检测登录
	 */
	public function checkUserLogin(){
		if (!session('uid')||!session('uname')||!session('cid')||!session('gid')){
	         session(null);
    	     $this->redirect('Home/Index/login');
    	}
	}
    /**
     * 全局插入
     * Enter description here ...
     * @param unknown_type $name
     * @param unknown_type $back
     */
    protected function all_insert($name = '', $back = '/index'){
        $name = $name ? $name : MODULE_NAME;
        $db   = D($name);
        if ($db->create() === false) {
            $this->error($db->getError());
        } else {
            $id = $db->add();
            if ($id) {
                if (in_array($name, $this->keywordType)) {
                	$data['pid']     = $id;
                	$data['companyid']     = session('cid');
                	$data['shopsid']     = session('shopsid');
                    $data['module']  = $name;
                    $tokens   = $this->_request('tokens');
                    $data['keyword'] = $this->_post('keyword');
                	foreach ($tokens as $tKey=>$tVal){
                		if ($tVal){
		                    $data['token'] = $tVal;
		                    M('Keyword')->add($data);
                		}
                	}
                }
                $this->success(L('AddedSucessful'), U(MODULE_NAME . $back));
            } else {
                $this->error(L('ServerBusyPrompt'), U(MODULE_NAME . $back));
            }
        }
    }
    /**
     * 插入数据
     * @param string $name module 名称
     * @param string $back 返回链接
     */
    protected function insert($name = '', $back = '/index'){
        $name = $name ? $name : MODULE_NAME;
        $db   = D($name);
        $back = U(MODULE_NAME . $back);
        if ($db->create() === false) {
            $this->error($db->getError());
        } else {
            $id = $db->add();
            if ($id == true) {
                $this->success(L('AddedSucessful'), $back);
            } else {
                $this->error(L('ServerBusyPrompt'), $back);
            }
        }
    }
    /**
     * 插入数据
     * @param string $name module 名称
     * @param string $back 返回链接 (全连接)
     */
    protected function insertback($name = '', $back = ''){
    	$name = $name ? $name : MODULE_NAME;
    	$db   = D($name);
    	if ($db->create() === false) {
    		$this->error($db->getError());
    	} else {
    		$id = $db->add();
    		if ($id == true) {
    			$this->success(L('AddedSucessful'), $back);
    		} else {
    			$this->error(L('ServerBusyPrompt'), $back);
    		}
    	}
    }
    /**
     * 不带提示只带返回的单条数据插入
     */
    protected function insertStatus($name = ''){
        $name = $name ? $name : MODULE_NAME;
        $db   = D($name);
        if ($db->create() === false) {
            $this->error($db->getError());
        } else {
            return $id = $db->add();
        }
    }
    /**
     * 带提示的单条数据修改
     */
    protected function save($name = '', $back = '/index',$where = ''){
        $name = $name ? $name : MODULE_NAME;
        $db   = D($name);
        if ($db->create() === false) {
            $this->error($db->getError());
        } else {
        	if ($where){
        		$id = $db->where($where)->save();
        	}else{
        		$id = $db->save();
        	}
            if ($id == true) {
                $this->success(L('UpdateSuccessful'), U(MODULE_NAME . $back));
            }elseif($id !== FALSE){
                $this->error(L('OpNoEffect'), U(MODULE_NAME . $back));
            }else{
                $this->error(L('ServerBusyPrompt'), U(MODULE_NAME . $back));
            }
        }
    }
    /**
     * 修改 支持 全连接跳转
     * @param string $name module 名称
     * @param string $back 跳转全链接
     * @param string $where 条件
     */
    protected function saveback($name = '', $back = '',$where = ''){
    	$name = $name ? $name : MODULE_NAME;
    	$db   = D($name);
    	if ($db->create() === false) {
    		$this->error($db->getError());
    	} else {
    		if ($where){
    			$id = $db->where($where)->save();
    		}else{
    			$id = $db->save();
    		}
    		if ($id == true) {
    			$this->success(L('UpdateSuccessful'), $back);
    		}elseif($id !== FALSE){
    			$this->error(L('OpNoEffect'), $back);
    		}else{
    			$this->error(L('ServerBusyPrompt'), $back);
    		}
    	}
    }
	/**
     * 不带提示的单条数据修改
     */
    protected function saveStatus($name = '',$where = ''){
        $name = $name ? $name : MODULE_NAME;
        $db   = D($name);
        if ($db->create() === false) {
            $this->error($db->getError());
        } else {
        	if ($where){
        		return $num = $db->where($where)->save();
        	}else{
        		return $num = $db->save();
        	}
        }
    }
    /**
     * 全部修改
     * Enter description here ...
     * @param unknown_type $name
     * @param unknown_type $back
     */
    protected function all_save($name = '', $back = '/index'){
        $name = $name ? $name : MODULE_NAME;
        $db   = D($name);
        if ($db->create() === false) {
            $this->error($db->getError());
        } else {
        	$dbWhere['id'] = $this->_post('id','intval');
        	$dbWhere['companyid'] = session('cid');
        	$id = $db->where($dbWhere)->save();
            if ($id) {
                if (in_array($name, $this->keywordType)) {
                	$data['pid']     = $_POST['id'];
                	$data['companyid']     = session('cid');
                	$data['shopsid']     = session('shopsid');
                    $data['module']  = $name;
                	M('Keyword')->where($data)->delete();
                	/*foreach (explode(' ', $_POST['keyword']) as $kKey=>$kVal){
                		if ($kVal){
		                    $data['keyword'] = $kVal;
		                    M('Keyword')->add($data);
                		}
                	}*/
                	$tokens   = $this->_request('tokens');
                    $data['keyword'] = $this->_post('keyword');
                	foreach ($tokens as $tKey=>$tVal){
                		if ($tVal){
		                    $data['token'] = $tVal;
		                    M('Keyword')->add($data);
                		}
                	}
                }
                $this->success(L('UpdateSuccessful'), U(MODULE_NAME . $back));
            }elseif($id !== FALSE){
				$this->error(L('OpNoEffect'), U(MODULE_NAME . $back));
            }else{
                $this->error(L('ServerBusyPrompt'), U(MODULE_NAME . $back));
            }
        }
    }
    /**
     * 插入触发关键词(多个 空格分开)
     */
	protected function insertKeyword($name = '',$id = ''){
        		$name = $name ? $name : MODULE_NAME;
            
                if (in_array($name, $this->keywordType)) {
                	$data['pid']     = $id;
                	$data['companyid']     = session('cid');
                	$data['shopsid']     = session('shopsid');
                    $data['module']  = $name;
                    $data['token']   = session('token');
                    $postKeyword = $this->_post('keyword') ? $this->_post('keyword') : '';
                	foreach (explode(' ', $postKeyword) as $kKey=>$kVal){
                		if ($kVal){
		                    $data['keyword'] = $kVal;
		                    M('Keyword')->add($data);
                		}
                	}
                }
    }
	/**
     * 插入触发关键词(多个 空格分开)
     */
	protected function saveKeyword($name = '',$id = ''){
        		$name = $name ? $name : MODULE_NAME;
            
                if (in_array($name, $this->keywordType)) {
                	$data['pid']     = $id;
                	$data['companyid']   = session('cid');
                	$data['shopsid']     = session('shopsid');
                    $data['module']  = $name;
                    $data['token']   = session('token');
                	M('Keyword')->where($data)->delete();
                    $postKeyword = $this->_post('keyword') ? $this->_post('keyword') : '';
                	foreach (explode(' ', $postKeyword) as $kKey=>$kVal){
                		if ($kVal){
		                    $data['keyword'] = $kVal;
		                    M('Keyword')->add($data);
                		}
                	}
                }
             
        
    }
	/**
     * 删除触发关键词
     */
	protected function delKeyword($module = '',$pid = ''){
        		$module = $module ? $module : MODULE_NAME;
        		$keywordWhere['pid'] = $pid;
        		$keywordWhere['companyid'] = session('cid');
        		//$keywordWhere['shopsid'] = session('shopsid');
        		$keywordWhere['module'] = $module;
                return M('Keyword')->where($keywordWhere)->delete();
    }
    /**
     * 插入 图文信息
     * Enter description here ...
     */
    protected function insertKeywordSet($module = '',$back = '/index'){
    	$module = $module ? $module : MODULE_NAME;
        if (in_array($module, $this->keywordType)) {
	    	$tokens = $this->_request('tokens');
	    	$insterNum = 0;
	    	if($tokens){
		    	$pid = $this->_post('pid','intval');
		    	if ($pid){
		    		$keywordWhere['pid'] = $pid;
		    		$keywordWhere['module'] = $module;
		    		$keywordWhere['companyid'] = session('cid');
		    		M('keyword')->where($keywordWhere)->delete();
		    	}
		    	$db = D('Keyword');
		    	if ($db->create() === false) {
		            $this->error($db->getError());
		        }else{
		    		foreach ($tokens as $tKey=>$tVal){
		    			$_POST['companyid'] = session('cid');
		    			$_POST['shopsid'] = session('shopsid');
		    			$_POST['pid'] = $pid;
		    			$_POST['module'] = $module;
		    			$_POST['token'] = $tVal;
		    			$db->add($_POST);
		    			$insterNum +=1;
		    		}
			    	if ($insterNum > 0){
			    		$this->success('图文触发设置成功关联'.$insterNum.'个公众号',U(MODULE_NAME . $back));
			    	}else{
			    		$this->error(L('ServerBusyPrompt'));
			    	}
		        }
	    	}else{
	    		$this->error('关联公众号必须选择');
	    	}
        }
    }
    /**
     * 删除 信息
     */
    protected function deleteInfo($model = '',$where = array(),$back = '/index'){
    	$module = $module ? $module : MODULE_NAME;
    	$deleteReturn = M($model)->where($where)->delete();
    	if ($deleteReturn){
    		$this->success(L('DelSuccessful'), U(MODULE_NAME . $back));
    	}else {
    		$this->error(L('ServerBusyPrompt'), U(MODULE_NAME . $back));
    	}
    }
    /**
     * 带返回值的 删除 操作
     */
    protected function deleteInfoStatus($model = '',$where = array()){
    	$module = $module ? $module : MODULE_NAME;
    	return M($model)->where($where)->delete();
    }
    /**
     * 发送站内信通知
     * @param unknown 
     * $option['companyid'] 接受公司id
     * $option['mid'] 接受会员id   
     * $option['message'] 通知内容   
     * @return string
     */
    public function sendSystemNotice($option){
    	$returnData['code'] = 300;
    	$returnData['msg'] = '参数错误';
    	if($option['companyid']){
    		if($option['mid']){
    			if($option['info']){
    				$data['mid'] = $option['mid'];
    				$data['info'] = $option['info'];
    				$data['companyid'] = $option['companyid'];
    				$data['type'] = 1;
    				$data['createtime'] = time();
    				$insterId = M('member_notices')->add($data);
    				if($insterId){
    					$returnData['code'] = 200;
    					$returnData['noticeid'] = $insterId;
    					$returnData['msg'] = '通知信息发送成功';
    				}else{
    					$returnData['msg'] = '通知信息发送失败';
    				}
    			}else{
    				$returnData['msg'] = '缺少通知信息内容';
    			}
    		}else{
    			$returnData['msg'] = '缺少接收会员id';
    		}
    	}else{
    		$returnData['msg'] = '缺少公司id';
    	}
    	return $returnData;
    }
    /**
     * 设置 页面title
     */
    public function setPageTitle($data =array('title'=>'会员中心')){
    	$this->assign('pageTitle',$data['title']);
    }
    /**
     * $option['companyid']公司id
     * $option['mid']会员mid
     * $option['type'] 类型type 类型：1：领卡促销；3：单次消费满赠；8：累积消费满赠；
     * $option['sendparameter'] 投放参数（节日关怀:具体的节日;消费满赠：具体的数字；生日关怀：具体的提前几天；）
     * @param unknown $option
     */
    public function sendMemberActivitiesVoucher($option){
    	//$typeArr = array(1,2,8);
    	$companyid = $option['companyid'];
		$groupLinkModel = D('Member_group_link');
		$addTrue = 0;
		$voucherCount = 0;
		$isCheckCount = '1';//1 ：需要检查发券数量； 2：不需要检测发券数量
		$voucherList = D('Member_marketing_activities_voucher')->getMemberMarketingActivitiesVoucherLinkVoucherInfoList(array('voucher.companyid'=>$companyid,'voucher.type'=>$option['type'],'voucher.starttime'=>array('elt',time()),'voucher.endtime'=>array('gt',time())));
		$rlVal = D('Member_register_info')->getMemberRegisterLinkRankInfo(array('register.companyid'=>$companyid,'register.id'=>$option['mid']));
		$accumulate = 0;
		if($voucherList && $rlVal){
			foreach ($voucherList as $vlKey=>$vlVal){
				if($option['type'] == '1'){
					//直赠
					$addTrue++;
				}elseif($option['type'] == '3'){
					//单次消费满赠
					$isCheckCount = '2';
					if($vlVal['sendparameter'] && $option['sendparameter'] >= $vlVal['sendparameter']){
						$addTrue++;
					}
				}elseif($option['type'] == '8'){
					//累积消费满赠
					$accumulate = M('member_spending')->where(array('mid'=>$rlVal['id'],'createtime'=>array('between',array($vlVal['starttime'],$vlVal['endtime']))))->sum('spendingamount');
					if($accumulate && $option['sendparameter'] <= $accumulate){
						$addTrue++;
					}
				}
				if($addTrue){
					if($isCheckCount == '1'){
						$voucherCount = M('member_vouchers')->where(array('companyid'=>$companyid,'voucherid'=>$vlVal['id'],'getvouchertype'=>'1','mid'=>$rlVal['id']))->count();
						if($voucherCount){
							$addTrue = 0;
							continue;
						}
					}
					for ($i = 0;$i < $addTrue;$i++){
						$this->sendVouchersBase($vlVal['id'], $rlVal['id'], $companyid,'1');
					}
					$addTrue = 0;
				}
			}
		}
    }
    /**
     * 待处理订座订单
     */
    public function diningCount(){
    	$diningBookCount = M('dining_book')->where(array('companyid'=>session('cid'),'bookstatus'=>1))->count();
    	$this->assign('diningBookCount',$diningBookCount);
    }
    /**
     * 待处理预约订单
     */
    public function commonCount(){
    	$commonBookCount = M('common_book')->where(array('companyid'=>session('cid'),'bookstatus'=>1))->count();
    	$this->assign('commonBookCount',$commonBookCount);
    }
    /**
     * 待回复客服信息
     */
    public function wechatMessageCount(){
    	$wechatMessageCount = M('member_wechat_info')->where(array('companyid'=>session('cid'),'wechatmessageisread'=>2,'wechatmessagetime'=>array('gt',strtotime('-2 day'))))->count();
    	$this->assign('wechatMessageCount',$wechatMessageCount);
    }
    /**
     * 新收到留言
     */
    public function liuyanCount(){
    	$liuyanCount = M('liuyan')->where(array('companyid'=>session('cid'),'isshow'=>1))->Sum('unreadNum');
    	$this->assign('liuyanCount',$liuyanCount);
    }
    /**
     * 导出 xls 文件 设置
     * @param unknown $fileName 文件名
     * @param unknown $headArr  第一行的 列名称
     * @param unknown $data 数据数组
     */
    public function getExcel($fileName,$headArr =array(),$data =array()){
    	//对数据进行检验
    	if(empty($data) || !is_array($data)){
    		exit();
    	}
    	//检查文件名
    	if(empty($fileName)){
    		exit;
    	}
    
    	$date = date("Y_m_d",time());
    	$fileName .= "_{$date}.xls";
    	//导入PHPExcel类库，因为PHPExcel没有用命名空间，只能import导入
    	import("ORG.Util.PHPExcel");
    	import("ORG.Util.PHPExcel.Writer.Excel5");
    	import("ORG.Util.PHPExcel.IOFactory.php");
    	//创建PHPExcel对象，注意，不能少了\
    	$objPHPExcel = new \PHPExcel();
    	$objProps = $objPHPExcel->getProperties();
    
    	//设置表头
    	$key = ord("A");
    	foreach($headArr as $v){
    		$colum = chr($key);
    		$objPHPExcel->setActiveSheetIndex(0) ->setCellValue($colum.'1', $v);
    		$key += 1;
    	}
    		
    	$column = 2;
    	$objActSheet = $objPHPExcel->getActiveSheet();
    	foreach($data as $key => $rows){ //行写入
    		$span = ord("A");
    		foreach($rows as $keyName=>$value){// 列写入
    			$j = chr($span);
    			$objActSheet->setCellValue($j.$column, ' '.$value);
    			$span++;
    		}
    		$column++;
    	}
    
    	$fileName = iconv("utf-8", "GBK", $fileName);
    	//重命名表
    	// $objPHPExcel->getActiveSheet()->setTitle('test');
    	//设置活动单指数到第一个表,所以Excel打开这是第一个表
    	$objPHPExcel->setActiveSheetIndex(0);
    	header('Content-Type: application/vnd.ms-excel');
    	header("Content-Disposition: attachment;filename=\"$fileName\"");
    	header('Cache-Control: max-age=0');
    
    	$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    	$objWriter->save('php://output'); //文件通过浏览器下载
    	exit;
    }
	/**
		发送验证码
	*/
	public function sendSms($content,$mobile){
		$post_data['userid'] = '5122';
		$post_data['account'] = 'weixinfeng';
		$post_data['password'] = 'weixinfeng123';
		$post_data['content'] = $content;
		//$post_data['content'] = '【微新风】您好，这里是微新风商城，你购买的商品已经发货了，订单号：20171030521360942';
		//多个手机号码用英文半角豆号‘,’分隔
		//$post_data['mobile'] = '18146625330';'130xxxxxxxx,131xxxxxxxx';
		$post_data['mobile'] = $mobile;
		$url='http://sms.kingtto.com:9999/sms.aspx?action=send';
		$o='';
		foreach ($post_data as $k=>$v)
		{
		//短信内容需要用urlencode编码，否则可能收到乱码
		$o.="$k=".urlencode($v).'&'; 
		}
		$post_data=substr($o,0,-1);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //如果需要将结果直接返回到变量里，那加上这句。
		$file_contents = curl_exec($ch);
		if(strpos($file_contents, '1,')!==FALSE){
			$returnData['code'] = 200;
			$returnData['msg'] = '短信发送请求提交成功';
		}else{
		}
		$returnData['data'][] = $file_contents;
		return $returnData;
	}
    /*--------------------------------
     功能:		上海助通短信PHP HTTP接口 发送短信
    auther   henry
    说明:		发送传递的参数
    http://www.ztsms.cn:8800/sendXSms.do?username=用户名&password=密码&mobile=手机号码&content=内容&dstime=&productid=产品ID&xh=留空
    --------------------------------
    $username = 'test';		//用户账号
    $password = '123';		//密码
    $mobile	 = '13916888888';	//号码
    $content = '测试PHP HTTP接口';		//内容
    $content=iconv("UTF-8", "UTF-8", $content);
    $dstime = '20130202120212';		//为空代表立即发送  如果加了时间代表定时发送  精确到秒
    $productid = 435227;	//商超会员营销短信
    $productid = 181818;	//优质订单提醒短信
    $productid = 676767;	//即时短信 验证码专用
    $xh = '';		//留空*/
    
    public function sendSms1($mobile,$content,$companyid,$qianMing='【人来风】',$dstime='',$productid='676767',$username='chenyang',$password='gUH1Vow8',$sendid=0){
    	$returnData['code'] = 300;
    	$returnData['msg'] = '短信发送请求提交失败';
    	$returnData['data'] = '';
    	if($mobile && $content){
    	    if($companyid){
        		$companyName = M('company')->where(array('id'=>$companyid))->getField('name');
        		$companyName = str_replace('&', '', htmlspecialchars_decode($companyName));
    	    }
    	    //替换空格 防止短信发送失败
    	    $qian=array(" ","　","\t","\n","\r","&");
    	    $hou=array("","","","","","");
    		$qianMing = $companyName ? '【'.$companyName.'】' : '【人来风】';
    		$content=iconv("UTF-8", "UTF-8", $qianMing.$content);
    		$content = str_replace($qian,$hou,$content);
    		//[\u4e00-\u9fa5] 
    		//if (preg_match("^[\x{4e00}-\x{9fa5}][a-zA-Z0-9]{3,16}$", $str)) { 
    		//计算短信条数 不满70为70一条，多于70按67一条计算 用于扣费的
    		$contentLen = mb_strlen($content,'utf-8');
    		if($contentLen<=70) $smsnum = 1;
    		elseif($contentLen>70) $smsnum = ceil($contentLen/67);
    		//判断短信发送的类型
    		if($productid=='676767') $smstype = 3;
    		else if($productid=='181818') $smstype = 2;
    		else if($productid=='435227') $smstype = 1;
    		else $smstype = 0;
    		//短信发送
    		if(strpos($mobile, ',')){
	    		$mobileArr = explode(',',$mobile);
	    		$mobileCout = count($mobileArr);
	    		$page = ceil($mobileCout/200);
	    		$sendMobile = '';
	    		$i = 1;
	    		$sendNum = 0;
	    		for ($i == 1;$i <= $page;$i++){
	    			unset($sendMobile);
	    			foreach ($mobileArr as $key => $value) {
	    				if(($i-1)*200 <= $key && $key < $i*200){ 
		    				$sendMobile .= $value.',';
	    				}else{
	    					continue;
	    				}
	    			}
		    		if($sendMobile){
		    			$url='http://www.ztsms.cn:8800/sendSms.do?username='.$username.'&password='.$password.'&mobile='.$sendMobile.'&content='.$content.'&dstime='.$dstime.'&productid='.$productid.'&xh=';
		    			if(function_exists('file_get_contents')){
		    				$file_contents = file_get_contents($url);
		    			}else{
		    				$ch = curl_init();
		    				$timeout = 5;
		    				curl_setopt ($ch, CURLOPT_URL, $url);
		    				curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		    				curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		    				$file_contents = curl_exec($ch);
		    				curl_close($ch);
		    			}
		    			if(strpos($file_contents, '1,')!==FALSE){
		    				$sendNum++;
		    			}
		    			$returnData['data'][] = $file_contents;
		    		}
	    		}
	    		if($sendNum > 0){
	    			$returnData['code'] = 200;
	    			$returnData['msg'] = '短信发送请求提交成功';
	    			$this->smsInfo($companyid, $sendMobile, $smstype, $smsnum, $file_contents[0], '2',$sendid);
	    		}else{
	    			$this->smsInfo($companyid, $sendMobile, $smstype, $smsnum, $file_contents[0], '1',$sendid);
	    		}
    		}else{
    			$sendMobile = $mobile;
    			if($sendMobile){
    				$url='http://www.ztsms.cn:8800/sendSms.do?username='.$username.'&password='.$password.'&mobile='.$sendMobile.'&content='.$content.'&dstime='.$dstime.'&productid='.$productid.'&xh=';
    				if(function_exists('file_get_contents')){
    					$file_contents = file_get_contents($url);
    				}else{
    					$ch = curl_init();
    					$timeout = 5;
    					curl_setopt ($ch, CURLOPT_URL, $url);
    					curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
    					curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    					$file_contents = curl_exec($ch);
    					curl_close($ch);
    				}
    			    if(strpos($file_contents, '1,')!==FALSE){
    					$returnData['code'] = 200;
    					$returnData['msg'] = '短信发送请求提交成功';
    					$this->smsInfo($companyid, $sendMobile, $smstype, $smsnum, $file_contents[0], '2',$sendid);
    				}else{
    					$this->smsInfo($companyid, $sendMobile, $smstype, $smsnum, $file_contents[0], '1',$sendid);
    				}
    				$returnData['data'][] = $file_contents;
    			}
    		}
    		
    	}
    	return $returnData;
    }
    /**
     * 
     * 短信发送的日志存储和统计方法
     * 
     * @param unknown $companyid 公司ID
     * @param unknown $mobile 手机号
     * @param unknown $smstype 短信发送类型：1：营销短信；2：优质短信；3：验证码
     * @param string $smslog 短信发送的返回码
     * @param number $smsnum 短信条数，默认为1
     * @param unknown $smsstate 短信发送状态：2：发送成功；1：发送失败
     * @author Asa<asa@renlaifeng.cn>
     * @since  2016-11-21
     */
    public function smsInfo($companyid,$mobile,$smstype,$smslog = '',$smsnum = 1,$smsstate,$sendid){
    	//短信发送日志写入
    	$data['sid'] = $sendid;
    	$data['returndata'] = $smslog;//短信接口返回值+发送时间
    	$data['mobile'] = $mobile?$mobile:'';
    	$data['smstype'] = $smstype;
    	$data['companyid']= $companyid?$companyid:'';
    	$data['createtime'] = time();
    	$data['smsstate'] =$smsstate;
    	$sendLog = M('log_sms_send')->add($data);
    	//减少公司库存的
    	if($companyid){
    		//必须发送成功才会扣费
    		if($smsstate==2){
    			$companyInfo = M("company")->where(array("id"=>$companyid))->field("id,balance,smsnum")->find();
    			$balance = format_number($companyInfo['balance']-0.08*$smsnum);
    			//if($balance < 0){
    			//	$balance = '0.00';
    			//}
    			$smsnum3 = ($companyInfo['smsnum']-1*$smsnum);
    			if($smsnum3 < '0'){
    				$smsnum3 = '0';
    			}
    			$comData['balance'] = $balance;
    			$comData['smsnum'] = $smsnum3;
    			$comData['sendtime'] = time();
    			$com = M('company')->where(array('id'=>$companyid))->save($comData);
    		}
    		//写推送统计的
    		$where['today'] = strtotime('today');
    		$where['companyid'] = $companyid;
    		$smsInfo = M("log_sms_send_everyday")->where($where)->find();
    		$smsData['updatetime'] = time();
    		if($smsInfo){
    			$smsData['znum'] = $smsInfo['znum']+1;
    			if($smsstate==2){
    				$smsData['snum'] = $smsInfo['snum']+1;
    				$smsData['mnum'] = $smsInfo['mnum']+1;
    			}
    			if($smstype==1){
    				$smsData['sellnum'] = $smsInfo['sellnum']+1;
    			}else{
    				$smsData['notifynum'] = $smsInfo['notifynum']+1;
    			}
    			M("log_sms_send_everyday")->where($where)->save($smsData);
    		}else{
    			$smsData['id'] = guidNow();
    			$smsData['today'] = strtotime('today');
    			$smsData['companyid'] = $companyid;
    			$smsData['createtime'] = time();
    			$smsData['znum'] = 1;
    			if($smsstate==2){
    				$smsData['snum'] = 1;
    				$smsData['mnum'] = 1;
    			}
    			if($smstype==1){
    				$smsData['sellnum'] = 1;
    			}else{
    				$smsData['notifynum'] = 1;
    			}
    			M("log_sms_send_everyday")->where($where)->add($smsData);
    		}
    	}
    }
    /**
     * 获得任务详情
     * @return Ambigous <string, mixed>
     */
    public function getSmsTaskInfo($username,$password){
    	$url='http://www.ztsms.cn:8800/batchreportget.do?username='.$username.'&password='.$password;
    	if(function_exists('file_get_contents')){
    		$file_contents = file_get_contents($url);
    	}else{
    		$ch = curl_init();
    		$timeout = 5;
    		curl_setopt ($ch, CURLOPT_URL, $url);
    		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
    		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    		$file_contents = curl_exec($ch);
    		curl_close($ch);
    	}
    	return $file_contents;
    }
    /**
     * 统一调用发券放方法
     * @param unknown $activitiesid 当$activitiestype==4 时 为rankid
     * @param unknown $mid
     * @param string $activitiestype 活动赠券类型 1：会员触发活动，2：线下活动报名。3:百宝箱抽奖活动,4：会员升级卡类型送电子券
     */
    public function sendVouchersBase($activitiesid,$mid,$companyid,$activitiestype = '1'){
    	if($activitiesid&&$mid&&$companyid){
    		$infos = '';
    		if($activitiestype == '1'){
    			$infos = M()->table('tp_member_marketing_activities_voucher as voucher')->join('tp_member_marketing_activities_voucher_info as vinfo on vinfo.id=voucher.vouchersid')->where(array('voucher.companyid'=>$companyid,'voucher.id'=>$activitiesid))
    			->field('vinfo.voucherdesc,voucher.prefix,voucher.title as titles,vinfo.id,vinfo.vouchertype,vinfo.parvalue,vinfo.minparvalue,vinfo.maxparvalue,vinfo.israndom,vinfo.title,vinfo.usestarttime,vinfo.useendtime,vinfo.usetimetype,vinfo.usetimedeferred,vinfo.vouchercreatetype,vinfo.vouchercreatecatid')->select();
    		}elseif ($activitiestype=='2'){
    			$infos = M()->table('tp_member_line_apply_activities as activities')->join('tp_member_marketing_activities_voucher_info as vinfo on vinfo.id=activities.vouchersid')->where(array('activities.companyid'=>$companyid,'activities.id'=>$activitiesid))
    			->field('activities.prefix,vinfo.id,vinfo.vouchertype,vinfo.parvalue,vinfo.minparvalue,vinfo.maxparvalue,vinfo.israndom,vinfo.title,vinfo.usestarttime,vinfo.useendtime,vinfo.usetimetype,vinfo.usetimedeferred,vinfo.vouchercreatetype,vinfo.vouchercreatecatid')->select();
    		}elseif ($activitiestype=='3'){
    			$infos = M()->table('tp_member_treasure_box_activities as activities')->join('tp_member_marketing_activities_voucher_info as vinfo on vinfo.id=activities.vouchersid')->where(array('activities.companyid'=>$companyid,'activities.id'=>$activitiesid))
    			->field('activities.prefix,vinfo.id,vinfo.vouchertype,vinfo.parvalue,vinfo.minparvalue,vinfo.maxparvalue,vinfo.israndom,vinfo.title,vinfo.usestarttime,vinfo.useendtime,vinfo.usetimetype,vinfo.usetimedeferred')->select();
    		}elseif ($activitiestype=='4'){
    			$infos = M()->table('tp_member_cardrank_voucher as activities')->join('tp_member_marketing_activities_voucher_info as vinfo on vinfo.id=activities.voucherid')->where(array('activities.companyid'=>$companyid,'activities.rankid'=>$activitiesid))
    			->field('activities.num,activities.prefix,vinfo.id,vinfo.vouchertype,vinfo.parvalue,vinfo.minparvalue,vinfo.maxparvalue,vinfo.israndom,vinfo.title,vinfo.usestarttime,vinfo.useendtime,vinfo.usetimetype,vinfo.usetimedeferred')->select();
    		}
    		if($infos){
    			$issuccess = 0;
    			//券发送方式
    			//$voucherSendType = M('company')->where(array('id'=>$companyid))->getField('vouchersendtype');
    			//积分设置
    			//$memberIntegralSet = D('Member_integral_set')->getMemberIntegralSetInfo(array('companyid'=>$companyid));
    			$memberIntegralSet = '';
    			//系统通知发送
    			$noticInfo = D('Company_system_notic_set')->getCompanySystemNoticSetInfo(array('companyid'=>$companyid));
    			$noticType = '';
    			$noticContent = '';
    			$vouchertype = '';
    			$companyInfo = D('Company')->getComapnyInfo($companyid);
    			$registerInfo = D('Member_register_info')->getMemberRegisterLinkRankInfo(array('register.companyid'=>$companyid,'register.id'=>$mid));
    			$sendNum = 1;
    			foreach ($infos as $key=>$info){
    				if($info['num'] && $info['num'] > 0){
    					$sendNum = $info['num'];
    				}
    				$voucherSendType = $info['vouchercreatetype'] ? $info['vouchercreatetype'] : 1;
    				for ($i = 0;$i < $sendNum;$i++){
    					
		    			M()->startTrans();
						$vouchers['adduid'] =$vouchers['edituid'] = $vouchers['shopsid']= $vouchers['shopid'] = 0;
						$vouchers['companyid'] = $companyid;
						$vouchers['mid'] = $mid;
						$vouchers['getvouchertype'] = $activitiestype;
						$vouchers['voucherid'] = $activitiesid;
						$vouchers['voucherinfoid'] = $info['id'];
						if($voucherSendType == 2){
							$sn = M('member_voucher_pool')->where(array('companyid'=>$companyid,'cid'=>$info['vouchercreatecatid'],'issend'=>'2'))->getField('sn');
							if($sn){
								$vouchers['sn'] = $sn;
							}else{
								return false;
							}
						}elseif ($voucherSendType == 1){
							$vouchers['sn'] = $info['prefix'].get_seven_number();
						}
						
						if($info['israndom'] == '1'){
							$vouchers['parvalue'] = rand($info['minparvalue'], $info['maxparvalue']);
						}else{
							$vouchers['parvalue'] = $info['parvalue'];
						}
						if($info['vouchertype'] == 3){
							//充值卡
							$vouchers['prepaidcardpassword'] = get_order_id();
						}
						if($info['vouchertype'] == 4){
							//红包 消费类型：9：线上自动充值；
							$spendingReturn = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->setInc('accountbalance',$vouchers['parvalue']);
								$borderid = get_order_id();
								$data['adduid'] = $data['edituid'] = session('uid')? session('uid') : 0 ;
								$data['companyid'] = $companyid;
								$data['shopsid'] = session('shopsid') ? session('shopsid') : 0 ;
								$data['mid'] = $mid;
								$data['status'] = '1';
								$data['updatetime'] = $data['createtime'] = time();
								$spendingData = $data;
								$spendingData['shopid'] =  session('shopsid') ? session('shopsid') : 0 ;
								$spendingData['borderid'] =  $borderid;
								$spendingData['orderid'] =  get_order_id();
								$spendingData['type'] =  '9';
								$spendingData['spendingamount'] =  $vouchers['parvalue'];
								$types = ',s9,';//用于列表页记录搜索
							$spendingChange = M('member_spending')->add($spendingData);
							if($memberIntegralSet['onlinechongzhiisopen'] == '1' &&$vouchers['parvalue']>0){
								$expNum = $memberIntegralSet['onlinechongzhiexp']*$vouchers['parvalue'];
								if($expNum>0){
									$experienceData = $data;
									$experienceData['borderid'] =  $borderid;
									$experienceData['orderid'] =  get_order_id();
									$experienceData['type'] =  '13';
									$types .= 'e13,';
									$experienceData['experiencevalue'] =  $expNum;
									$experienceChange = M('member_experiencevalue')->add($experienceData);
									$registerExperienceChange = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->setInc('totalexperiencevalue',$expNum);
								}else{
									$experienceChange = $registerExperienceChange = 1;
								}
								$intNum = $memberIntegralSet['onlinechongzhiint']*$vouchers['parvalue'];
								if($intNum>0){
									$integralData = $data;
									$integralData['borderid'] =  $borderid;
									$integralData['orderid'] =  get_order_id();
									$integralData['type'] =  '13';
									$types .= 'i13,';
									$integralData['integralnum'] =  $intNum;
									$integralChange = M('member_integral')->add($integralData);
									$registerIntegralChange = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->setInc('totalintegration',$intNum);
								}else{
									$integralChange = $registerIntegralChange = 1;
								}
							}else{
								$experienceChange = $registerExperienceChange = $integralChange = $registerIntegralChange = 1;
							}
							$bdata = $data;
							$bdata['orderid'] = $borderid;
							$bdata['businesstype'] = '3';
							$bdata['types'] = $types;
							$businessReturn = M('member_business')->add($bdata);
						}else{
							$spendingReturn = $spendingChange = $businessReturn = $experienceChange = $registerExperienceChange = $integralChange = $registerIntegralChange = 1;
						}
						if($info['usetimetype']==1){
							$vouchers['usestarttime'] = $info['usestarttime'];
							$vouchers['useendtime'] = $info['useendtime'];
						}elseif ($info['usetimetype']==2){
							$vouchers['usestarttime'] = time();
							$vouchers['useendtime'] = strtotime('+'.$info['usetimedeferred'].' day');
						}
						$vouchers['isused'] = '2';
						$vouchers['issend'] = '2';
						$vouchers['updatetime'] =$vouchers['createtime'] = time();
						$vouchersid = M('member_vouchers')->add($vouchers);
						if($sn){
							$snSave = M('member_voucher_pool')->where(array('companyid'=>$companyid,'sn'=>$sn))->save(array('issend'=>1,'sendtime'=>time()));
						}else{
							$snSave = 1;
						}
						/* return $vouchersid.'/'.$snSave.'/'.$spendingReturn.'/'.$spendingChange.'/'.$businessReturn.'/'.$experienceChange.'/'.$registerExperienceChange.'/'.$integralChange.'/'.$registerIntegralChange; */
		    			if($vouchersid && $snSave && $spendingReturn && $spendingChange && $businessReturn && $experienceChange && $registerExperienceChange && $integralChange && $registerIntegralChange){
		    				M()->commit();
		    				if($noticInfo['getvoucher'] && $noticInfo['getvoucherisopen'] == 1){
								$noticType .='1,';
								if($noticType){
									//准备通知内容
									$searchArr = array('<-姓名->','<-会员卡号后4位->','<-公司名称->','<-会员等级名称->','<-优惠类型->','<-优惠标题->','<-生效日期->','<-有效期->');
									if($info['vouchertype'] == '1'){
										$vouchertype = '优惠券';
									}elseif ($info['vouchertype'] == '2'){
										$vouchertype = '赠品券';
									}elseif ($info['vouchertype'] == '3'){
										$vouchertype = '充值卡';
									}elseif ($info['vouchertype'] == '4'){
										$vouchertype = '红包';
									}
									$replaceArr = array($registerInfo['name'],substr($registerInfo['cardnum'], -4),$companyInfo['name'],$registerInfo['rankname'],$vouchertype,$info['title'],format_time($vouchers['usestarttime']+60,'ymd'),format_time($vouchers['useendtime'],'ymd'));
									$noticContent = str_replace($searchArr, $replaceArr, htmlspecialchars_decode($noticInfo['getvoucher']));
									if($noticContent){
										$this->sendSystemNoticBase($companyid, $mid, $noticContent,$noticType);
									}
								}
							}
							if($info['vouchertype'] == '1' || $info['vouchertype'] == '2'){
								$openid = M('member_wechat_info')->where(array('companyid'=>$companyid,'mid'=>$mid))->getField('openid');
								if($openid){
									$this->WeChatTemplateMessageSend('9',$openid,$companyid,'','',array('获得电子券','券通知'),array($info['title'],format_time($vouchers['usestarttime']+60,'ymd').'-'.format_time($vouchers['useendtime'],'ymd')));
								}
							}
	    					$issuccess = $vouchersid;
		    			}else{
		    				M()->rollback();
		    			}
    				}
    			}
    			return $issuccess;
    		}else{
    			return false;
    		}
    	}else{
    		return false;
    	}
    }
    /**
     * 发送系统通知
     * @param unknown $companyid
     * @param unknown $mid 接受者mid
     * @param unknown $content 发送内容类型  
     * @param string $sendtype  1:站内信，2：微信；3：短信。如果用多个方式发送 ：1,2,3
     * @return boolean
     */
    public function sendSystemNoticBase($companyid,$mid,$content,$sendtype='1',$qianMing='【MobiWind】'){
    	if($companyid && $mid && $content){
    		if(strpos($sendtype, '1') !== false){
    			return M('member_notices')->add(array('companyid'=>$companyid,'mid'=>$mid,'info'=>$content,'isread'=>2,'type'=>1,'createtime'=>time()));
    		}
    		if (strpos($sendtype, '2') !== false){
    			
    		}
    		if (strpos($sendtype, '3') !== false){
    			$mobile = M('member_register_info')->where(array('companyid'=>$companyid,'id'=>$mid))->getField('moblie');
    			if($mobile){
	    			return $this->sendSms($mobile, $content,$companyid,$qianMing);
    			}else{
    				return array('msg'=>'请输入手机号');
    			}
    		}
    	}else{
    		return false;
    	}
    }
    /**
     * $option['orderid'] 交易表交易id 用于判断是否修改 修改时必填
     * $option['shopid']  线下门店支付 门店id
     * $option['cid'] 公司id 添加修改时必填
     * $option['mid'] 会员id 添加修改时必填
     * $option['type'] 交易类型 添加时必填
     * $option['num'] 数值 添加时必填
     * $option['status'] 交易状态 1：正常，2：撤销  添加/修改都必填
     * $option['businesstype'] 全局操作类型  1：单纯经验值操作  2：单纯积分操作，3：混合记录消费操作  添加时必填，4：经验值积分混合操作
     */
    public function changeMemberBusiness($option){
    	M()->startTrans();
    	if($option['orderid']){
    		$data['edituid'] = session('uid') ? session('uid') : 0;
    		$data['status'] = $option['status'];
    		$data['updatetime'] = time();
    	}else{
    		$borderid = get_order_id();
    		$data['adduid'] = $data['edituid'] = session('uid') ? session('uid') : 0;
    		$data['companyid'] = $option['cid'] ? $option['cid'] : session('cid') ;
    		$data['shopsid'] = session('shopsid') ? session('shopsid') : 0;
    		$data['mid'] = $option['mid'];
    		$data['status'] = $option['status'];
    		$data['updatetime'] = $data['createtime'] = time();
    	}
    	$bdata = $data;
    	$option['num'] = $option['num'] > 0 ? $option['num'] : 0;
    	$companyid = $option['cid'] ? $option['cid'] : session('cid') ;
    	$mid = $option['mid'];
    	$sendOpenid = M('member_wechat_info')->where(array('companyid'=>$companyid,'mid'=>$mid))->getField('openid');
    	if($option['orderid']){
    		$businessReturn = M('member_business')->where(array('orderid'=>$option['orderid'],'companyid'=>$companyid))->save($bdata);
    	}
    	if($option['businesstype'] == '1'){
    		if($option['orderid']){
    			$experienceData = $data;
    			$experienceChange = M('member_experiencevalue')->where(array('borderid'=>$option['orderid'],'companyid'=>$companyid))->save($experienceData);
    			$experienceInfo = M('member_experiencevalue')->where(array('borderid'=>$option['orderid'],'companyid'=>$companyid))->field('experiencevalue,type')->find();
    			if($experienceInfo['type'] == 20){
    				if($option['status'] == 1){
    					$allReturn = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->setDec('totalexperiencevalue',$experienceInfo['experiencevalue']);
    				}elseif ($option['status'] == 2){
    					$allReturn = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->setInc('totalexperiencevalue',$experienceInfo['experiencevalue']);
    				}
    			}elseif($experienceInfo['type'] == 16||$experienceInfo['type'] == 19){
    				if($option['status'] == 1){
    					$allReturn = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->setInc('totalexperiencevalue',$experienceInfo['experiencevalue']);
    				}elseif ($option['status'] == 2){
    					$allReturn = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->setDec('totalexperiencevalue',$experienceInfo['experiencevalue']);
    				}
    			}
    		}else{
    			$experienceData = $data;
    			$experienceData['type'] =  $option['type'];
    			$experienceData['experiencevalue'] =  $option['num'];
    			$experienceData['borderid'] =  $borderid;
    			$experienceData['orderid'] =  get_order_id();
    			$experienceChange = M('member_experiencevalue')->add($experienceData);
    			if($option['type'] == 20){
    				$allReturn = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->setDec('totalexperiencevalue',$option['num']);
    			}elseif($option['type'] == 16||$option['type'] == 19){
    				$allReturn = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->setInc('totalexperiencevalue',$option['num']);
    			}
    			$bdata['orderid'] = $borderid;
    			$bdata['businesstype'] = $option['businesstype'];
    			$bdata['types'] = ',e'.$option['type'].',';
    			$businessReturn = M('member_business')->add($bdata);
    		}
    		$totalexperiencevalue = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->getField('totalexperiencevalue');
    		if($businessReturn&&$experienceChange&&$allReturn&&$totalexperiencevalue>=0){
    			M()->commit();
	    		$this->changMemberCardRank($companyid,$mid);//改变会员卡等级
    			return true;
    		}else{
    			M()->rollback();
    			return false;
    		}
    	}elseif($option['businesstype'] == '2'){
    		if($option['orderid']){
    			$integralData = $data;
    			$integralChange = M('member_integral')->where(array('borderid'=>$option['orderid'],'companyid'=>$companyid))->save($integralData);
    			$integralInfo = M('member_integral')->where(array('borderid'=>$option['orderid'],'companyid'=>$companyid))->field('integralnum,type')->find();
    			if($integralInfo['type'] == 20||$integralInfo['type'] == 21||$integralInfo['type'] == 22||$integralInfo['type'] == 23){
    				if($option['status'] == 1){
    					$allReturn = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->setDec('totalintegration',$integralInfo['integralnum']);
    				}elseif ($option['status'] == 2){
    					$allReturn = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->setInc('totalintegration',$integralInfo['integralnum']);
    				}
    			}elseif($integralInfo['type'] == 16||$integralInfo['type'] == 19){
    				if($option['status'] == 1){
    					$allReturn = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->setInc('totalintegration',$integralInfo['integralnum']);
    				}elseif ($option['status'] == 2){
    					$allReturn = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->setDec('totalintegration',$integralInfo['integralnum']);
    				}
    			}
    		}else{
    			//积分类型：1:完善100%会员资料；2:成功开卡；3:推荐开卡；4:会员等级升级；5:线下确认记录消费；6:后台人工记录消费；7:微信支付；8:银行卡支付；9:支付宝支付；10:商城货到付款；11:线上储值支付；12:后台人工储值支付；13:线上自助充值；14:后台人工充值；15:LBS签到；16:参与活动（所有的经营活动）；17:大众点评好评；18:Tripadvisor好评；19:人工加积分；20:人工减积分；21:积分消耗-参与活动；22:积分消耗-积分兑换；23:积分消耗-积分自动清零；
    			$integralData = $data;
    			$integralData['borderid'] =  $borderid;
    			$integralData['orderid'] =  get_order_id();
    			$integralData['type'] =  $option['type'];
    			$integralData['integralnum'] =  $option['num'];
    			$integralChange = M('member_integral')->add($integralData);
    			if($option['type'] == 20||$option['type'] == 21||$option['type'] == 22||$option['type'] == 23){
    				$allReturn = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->setDec('totalintegration',$option['num']);
    			}elseif($option['type'] == 16||$option['type'] == 19){
    				$allReturn = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->setInc('totalintegration',$option['num']);
    			}
    			$bdata['orderid'] = $borderid;
    			$bdata['businesstype'] = $option['businesstype'];
    			$bdata['types'] = ',i'.$option['type'].',';
    			$businessReturn = M('member_business')->add($bdata);
    		}
    		$totalintegral = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->getField('totalintegration');
    		if($businessReturn&&$integralChange&&$allReturn&&$totalintegral>=0){
    			M()->commit();
    			// 发送积分改变消息模板
    			if($option['status']&&$sendOpenid){
    			    $nowTotalintegral = $totalintegral;
    			    $optionType = $integralData['type'] ? $integralData['type'] : $integralInfo['type'];
    			    $changeIntNum = $integralData['integralnum'] ? $integralData['integralnum'] : $integralInfo['integralnum'] ;
    			    if(($option['status'] == '1' && ($optionType == '16' ||$optionType == '19')) || ($option['status'] == '2' && ($optionType == '20' ||$optionType == '21' ||$optionType == '22' ||$optionType == '23'))){
    			        $sendType = '3';// 积分添加
    			        $sendDesc = $this->weChatTemplateIntDesc[$optionType];
    			        if($optionType == '20' ||$optionType == '21' ||$optionType == '22' ||$optionType == '23'){
    			            $sendDesc = '撤销'.$sendDesc;
    			        }
        			    $this->WeChatTemplateMessageSend($sendType,$sendOpenid,$companyid,'','',array(format_time(time(),'ymdhis'),$changeIntNum,$sendDesc,$nowTotalintegral),'');
    			    }elseif (($option['status'] == '2' && ($optionType == '16' ||$optionType == '19')) || ($option['status'] == '1' && ($optionType == '20' ||$optionType == '21' ||$optionType == '22' ||$optionType == '23'))){
    			        $sendType = '4';// 积分减少
    			        $sendDesc = $this->weChatTemplateIntDesc[$optionType];
    			        if($optionType == '16' ||$optionType == '19'){
    			            $sendDesc = '撤销'.$sendDesc;
    			        }
        			    $this->WeChatTemplateMessageSend($sendType,$sendOpenid,$companyid,'','',array($sendDesc,$changeIntNum,$nowTotalintegral),'');
    			    }
    			}
	    		$this->changMemberCardRank($companyid,$mid);//改变会员卡等级
    			return true;
    		}else{
    			M()->rollback();
    			return false;
    		}
    	}elseif($option['businesstype'] == '3'){
    		//消费类型：1：线下确认记录消费，2：后台人工记录消费；3:微信支付：4:银行卡支付；5：支付宝支付；6：商城货到付款；7：线上储值支付；8：后台人工储值支付；9：线上自动充值；10：后台人工充值；24:风助手微信支付；25:风助手支付宝支付；26:风助手现金支付；27:微信买单

    		// 查找积分经验值的设置信息
    	    // $memberIntegralSet = D('Member_integral_set')->getMemberIntegralSetInfo(array('companyid'=>$companyid));
    	    $memberIntegralSet = M('member_integral_set')->where(array('companyid'=>$companyid))->field('integralisautoclear,integralgetinfo,perfectreginfoisopen,perfectreginfoexp,perfectreginfoint,createcardisopen,createcardexp,createcardint,recommendcreatecardisopen,recommendcreatecardexp,recommendcreatecardint,cardrankchangisopen,cardrankchangexp,cardrankchangint,offlinespendingisopen,offlinespendingexp,offlinespendingint,houtaispendingisopen,houtaispendingexp,houtaispendingint,wechatspendingisopen,wechatspendingexp,wechatspendingint,yinhangkapendingisopen,yinhangkapendingexp,yinhangkapendingint,alipaypendingisopen,alipaypendingexp,alipaypendingint,shophuodaofukuanisopen,shophuodaofukuanexp,shophuodaofukuanint,onlinechuzhipayisopen,onlinechuzhipayexp,onlinechuzhipayint,houtaichuzhipayisopen,houtaichuzhipayexp,houtaichuzhipayint,onlinechongzhiisopen,onlinechongzhiexp,onlinechongzhiint,houtaichongzhiisopen,houtaichongzhiexp,houtaichongzhiint,lbsqiandaoisopen,lbsqiandaoexp,lbsqiandaoint,dianpingisopen,dianpingexp,dianpingint,tripadvisorisopen,tripadvisorexp,tripadvisorint,updatetime,windhelperwechatspendingisopen,windhelperwechatspendingexp,windhelperwechatspendingint,windhelperalipaypendingisopen,windhelperalipaypendingexp,windhelperalipaypendingint,windhelpercashspendingisopen,windhelpercashspendingexp,windhelpercashspendingint,wechatpaybillisopen,wechatpaybillexp,wechatpaybillint,createtime')->find();
    		if($option['orderid']){  // 有订单号
    			$spendingData = $data;
    			// 修改订单记录信息
    			$spendingChange = M('member_spending')->where(array('borderid'=>$option['orderid'],'companyid'=>$companyid))->save($spendingData);
    			// 查询订单信息
    			$spendingInfo = M('member_spending')->where(array('borderid'=>$option['orderid'],'companyid'=>$companyid))->field('spendingamount,type')->find();
    			if($spendingInfo['type'] == 9 ||$spendingInfo['type'] == 10 ){
    			// type : 9.线上自动充值；10.后台人工充值
    			    // 查询交易记录信息
    				$experienceInfo = M('member_experiencevalue')->where(array('borderid'=>$option['orderid'],'companyid'=>$companyid))->field('experiencevalue,status')->find();
    				if($experienceInfo){
    					$experienceData = $data;
    					// 修改交易记录信息
    					$experienceChange = M('member_experiencevalue')->where(array('borderid'=>$option['orderid'],'companyid'=>$companyid))->save($experienceData);
    					if($option['status'] == 1){
    					    // 状态正常
    					    // 修改会员信息(添加经验值)
	    					$registerExperienceChange = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->setInc('totalexperiencevalue',$experienceInfo['experiencevalue']);
    					}elseif ($option['status'] == 2){
    					    // 撤销
    					    // 修改会员信息(减经验值)
    						$registerExperienceChange = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->setDec('totalexperiencevalue',$experienceInfo['experiencevalue']);
    					}
    				}else{
    				    // 没有交易记录信息
    					$experienceChange = $registerExperienceChange = 1;
    				}
    				// 查询积分信息
    				$integralInfo = M('member_integral')->where(array('borderid'=>$option['orderid'],'companyid'=>$companyid))->field('integralnum,type,status')->find();
    				if($integralInfo){
    					$integralData = $data;
    					// 修改积分信息
    					$integralChange = M('member_integral')->where(array('borderid'=>$option['orderid'],'companyid'=>$companyid))->save($integralData);
    					if($option['status'] == 1){
    					    // 状态正常
    					    // 修改会员信息表(添加积分)
    						$registerIntegralChange = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->setInc('totalintegration',$integralInfo['integralnum']);
    					}elseif ($option['status'] == 2){
    					    // 撤销
    					    // 修改会员信息表(减积分)
    						$registerIntegralChange = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->setDec('totalintegration',$integralInfo['integralnum']);
    					}
    				}else{
    				    // 没有积分信息
    					$integralChange = $registerIntegralChange = 1;
    				}
    				if($option['status'] == 1){
    				    // 状态正常
    				    // 加余额
    					$allReturn = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->setInc('accountbalance',$spendingInfo['spendingamount']);
    				}elseif ($option['status'] == 2){
    				    // 撤销
    				    // 减余额
    					$allReturn = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->setDec('accountbalance',$spendingInfo['spendingamount']);
    				}
    				$updateSpendingFrequency = $spendingReturn = '1';
    			}elseif($spendingInfo['type'] == 7 || $spendingInfo['type'] == 8 ){
    			    // type : 7.线上储值支付；8.后台人工储值支付
    			    // 查询经验值信息
    				$experienceInfo = M('member_experiencevalue')->where(array('borderid'=>$option['orderid'],'companyid'=>$companyid))->field('experiencevalue,status')->find();
    				if($experienceInfo){
    					$experienceData = $data;
    					// 修改经验值信息
    					$experienceChange = M('member_experiencevalue')->where(array('borderid'=>$option['orderid'],'companyid'=>$companyid))->save($experienceData);
    					if($option['status'] == 1){
    					    // 状态正常
    					    // 修改会员信息表(添加经验值)
    						$registerExperienceChange = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->setInc('totalexperiencevalue',$experienceInfo['experiencevalue']);
    					}elseif ($option['status'] == 2){
    					    // 撤销
    					    // 修改会员信息表(减经验值)
    						$registerExperienceChange = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->setDec('totalexperiencevalue',$experienceInfo['experiencevalue']);
    					}
    				}else{
    				    // 没有经验值信息
    					$experienceChange = $registerExperienceChange = 1;
    				}
    				// 查询积分信息
    				$integralInfo = M('member_integral')->where(array('borderid'=>$option['orderid'],'companyid'=>$companyid))->field('integralnum,type,status')->find();
    				if($integralInfo){
    					$integralData = $data;
    					// 修改积分
    					$integralChange = M('member_integral')->where(array('borderid'=>$option['orderid'],'companyid'=>$companyid))->save($integralData);
    					if($option['status'] == 1){
    					    // 状态正常
    					    // 修改会员信息表(添加经验值)
    						$registerIntegralChange = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->setInc('totalintegration',$integralInfo['integralnum']);
    					}elseif ($option['status'] == 2){
    					    // 撤销
    					    // 修改会员信息表(减积分)
    						$registerIntegralChange = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->setDec('totalintegration',$integralInfo['integralnum']);
    					}
    				}else{
    				    // 没有积分信息
    					$integralChange = $registerIntegralChange = 1;
    				}
    				if($option['status'] == 1){
    				    // 状态正常
    				    // 修改会员信息表(加余额)
    					$allReturn = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->setDec('accountbalance',$spendingInfo['spendingamount']);
    					//$spendingReturn = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->setInc('totalspending',$spendingInfo['spendingamount']);
    				}elseif ($option['status'] == 2){
    				    // 撤销
    				    // 修改会员信息表(减余额)
    					$allReturn = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->setInc('accountbalance',$spendingInfo['spendingamount']);
    					//$spendingReturn = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->setDec('totalspending',$spendingInfo['spendingamount']);
    				}
    				//修改消费频率 消费次数/月数
    				// 查询消费记录数
    				$allSpendingCount = M('member_spending')->where(array('companyid'=>$companyid,'mid'=>$mid,'type'=>array('in','1,2,3,4,5,6,7,8'),'status'=>1))->count();
    				// 查询会员数据的创建时间
    				$memberRegisterInfo = M('member_register_info')->where(array('companyid'=>$companyid,'id'=>$mid))->field('createtime')->find();
    				// 获得两个时间的月份
    				$remberRegisterMonths = get_time_months(time(),$memberRegisterInfo['createtime']);
    				// 处理月份
    				$remberRegisterMonths = $remberRegisterMonths > 0 ? $remberRegisterMonths : 1 ;
    				//修改消费频率 消费次数/月数
    				// 修改会员信息表中的消费频次
    				$updateSpendingFrequency = M('member_register_info')->where(array('companyid'=>$companyid,'id'=>$mid))->save(array('spendingfrequency'=>format_number($allSpendingCount/$remberRegisterMonths),'updatetime'=>time()));
    				$spendingReturn =1;
    			}elseif($spendingInfo['type'] == 1 || $spendingInfo['type'] == 2 || $spendingInfo['type'] == 3 || $spendingInfo['type'] == 4 || $spendingInfo['type'] == 5 || $spendingInfo['type'] == 6 || $spendingInfo['type'] == 24 || $spendingInfo['type'] == 25 || $spendingInfo['type'] == 26 || $spendingInfo['type'] == 27 ){
    				// type : 1:线下确认记录消费；2:后台人工记录消费；3:商城微信支付；4:商城支付宝支付；5:商城货到付款
    			    // 查询经验值信息
    			    $experienceInfo = M('member_experiencevalue')->where(array('borderid'=>$option['orderid'],'companyid'=>$companyid))->field('experiencevalue,status')->find();
    				if($experienceInfo){
    					$experienceData = $data;
    					// 修改经验值信息表信息
    					$experienceChange = M('member_experiencevalue')->where(array('borderid'=>$option['orderid'],'companyid'=>$companyid))->save($experienceData);
    					if($option['status'] == 1){
    					    // 状态 正常
    					    // 修改会员信息(加经验值)
    						$registerExperienceChange = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->setInc('totalexperiencevalue',$experienceInfo['experiencevalue']);
    					}elseif ($option['status'] == 2){
    					    // 撤销
    					    // 修改会员信息(减经验值)
    						$registerExperienceChange = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->setDec('totalexperiencevalue',$experienceInfo['experiencevalue']);
    					}
    				}else{
    				    // 没有经验值信息
    					$experienceChange = $registerExperienceChange = 1;
    				}
    				// 查询积分表信息
    				$integralInfo = M('member_integral')->where(array('borderid'=>$option['orderid'],'companyid'=>$companyid))->field('integralnum,type,status')->find();
    				if($integralInfo){
    					$integralData = $data;
    					// 修改积分信息
    					$integralChange = M('member_integral')->where(array('borderid'=>$option['orderid'],'companyid'=>$companyid))->save($integralData);
    					if($option['status'] == 1){
    					    // 状态 正常
    					    // 修改会员信息(加积分)
    						$registerIntegralChange = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->setInc('totalintegration',$integralInfo['integralnum']);
    					}elseif ($option['status'] == 2){
    					    // 撤销
    					    // 修改会员信息表(减积分)
    						$registerIntegralChange = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->setDec('totalintegration',$integralInfo['integralnum']);
    					}
    				}else{
    				    // 没有积分信息
    					$integralChange = $registerIntegralChange = 1;
    				}
    				$allReturn = '1';
    				if($option['status'] == 1){
    				    // 状态 正常
    				    // 修改会员信息(添加总消费数)
    					$spendingReturn = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->setInc('totalspending',$spendingInfo['spendingamount']);
    				}elseif ($option['status'] == 2){
    				    // 撤销
    				    // 修改会员信息(减少总消费数)
    					$spendingReturn = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->setDec('totalspending',$spendingInfo['spendingamount']);
    				}
    				//修改消费频率 消费次数/月数
    				// 查询总消费次数
    				$allSpendingCount = M('member_spending')->where(array('companyid'=>$companyid,'mid'=>$mid,'type'=>array('in','1,2,3,4,5,6,7,8'),'status'=>1))->count();
    				// 查询会员创建时间
    				$memberRegisterInfo = M('member_register_info')->where(array('companyid'=>$companyid,'id'=>$mid))->field('createtime')->find();
    				// 查询会员创建的总月份
    				$remberRegisterMonths = get_time_months(time(),$memberRegisterInfo['createtime']);
    				// 处理总月份
    				$remberRegisterMonths = $remberRegisterMonths > 0 ? $remberRegisterMonths : 1 ;
    				//修改消费频率 消费次数/月数
    				// 修改会员信息表(修改消费频次)
    				$updateSpendingFrequency = M('member_register_info')->where(array('companyid'=>$companyid,'id'=>$mid))->save(array('spendingfrequency'=>format_number($allSpendingCount/$remberRegisterMonths),'updatetime'=>time()));
    			}
    		}else{
    		    // 没有订单号 添加
    			//消费类型：1：线下确认记录消费，2：后台人工记录消费；3:微信支付：4:银行卡支付；5：支付宝支付；6：商城货到付款；7：线上储值支付；8：后台人工储值支付；9：线上自动充值；10：后台人工充值；
    			//9,10:充值操作：修改储值，不修改消费，不修改消费频率；7,8:储值支付操作：修改储值，不修改消费，修改消费频率；1,2,3,4,5,6：消费操作：不修改储值，修改消费，修改消费频率；
    			$spendingData = $data;
    			$spendingData['shopid'] =  $option['shopid']?$option['shopid']:'0' ;
    			$spendingData['borderid'] =  $borderid;
    			$spendingData['orderid'] =  get_order_id();  // 创建订单号
    			$spendingData['type'] =  $option['type'];
    			$spendingData['spendingamount'] =  $option['num'];
    			$types = ',s'.$option['type'].',';//用于列表页记录搜索
    			// 把创建的消费信息添加到消费记录表中
    			$spendingChange = M('member_spending')->add($spendingData);
    			if($option['type'] == 9 ||$option['type'] == 10 ){
    			    // type : 9.线上自动充值；10.后台人工充值；
    			    // 修改会员信息表(添加余额)
    				$allReturn = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->setInc('accountbalance',$option['num']);
    				if($memberIntegralSet['onlinechongzhiisopen'] == '1' && $option['type'] == 9){
    				    // 开启线上储值支付获得积分/经验值 && 线上自动充值
    				    // 获得余额
    					$expNum = $memberIntegralSet['onlinechongzhiexp']*$option['num'];
						if($expNum>0){
		    					$experienceData = $data;
		    					$experienceData['borderid'] = $borderid;
		    					$experienceData['orderid'] = get_order_id();
		    					$experienceData['type'] = '13';
		    					$types .= 'e13,';
		    					$experienceData['experiencevalue'] = $expNum;
		    				// 添加经验值记录
	    					$experienceChange = M('member_experiencevalue')->add($experienceData);
	    					// 修改会员信息表(加经验值)
	    					$registerExperienceChange = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->setInc('totalexperiencevalue',$expNum);
						}else{
						    // 没有余额
							$experienceChange = $registerExperienceChange = 1;
						}
						// 对应的积分
    					$intNum = $memberIntegralSet['onlinechongzhiint']*$option['num'];
    					if($intNum>0){
		    					$integralData = $data;
		    					$integralData['borderid'] = $borderid;
		    					$integralData['orderid'] = get_order_id();   // 生成属于积分记录表中的订单号
		    					$integralData['type'] =  '13';
		    					$types .= 'i13,';
		    					$integralData['integralnum'] =  $intNum;
		    				// 把本条积分记录信息添加到积分记录表中
	    					$integralChange = M('member_integral')->add($integralData);
	    					// 修改会员信息表(添加积分)
	    					$registerIntegralChange = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->setInc('totalintegration',$intNum);
    					}else{
    					    // 没有积分
    						$integralChange = $registerIntegralChange = 1;
    					}
    				}elseif ($memberIntegralSet['houtaichongzhiisopen'] == '1' && $option['type'] == 10){
    				    // 开启后台人工充值加经验值/积分 && 后台人工充值
    				    // 获得对应的经验值
    					$expNum = $memberIntegralSet['houtaichongzhiexp']*$option['num'];
						if($expNum>0){
		    					$experienceData = $data;
		    					$experienceData['borderid'] = $borderid;
		    					$experienceData['orderid'] = get_order_id();  // 生成经验值表中的订单号
		    					$experienceData['type'] = '14';
		    					$types .= 'e14,';
		    					$experienceData['experiencevalue'] = $expNum;
		    				// 把本条经验值信息添加到经验值表中
	    					$experienceChange = M('member_experiencevalue')->add($experienceData);
	    					// 修改会员信息(添加经验值)
	    					$registerExperienceChange = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->setInc('totalexperiencevalue',$expNum);
						}else{
						    // 没有对应的经验值
							$experienceChange = $registerExperienceChange = 1;
						}
    					$intNum = $memberIntegralSet['houtaichongzhiint']*$option['num'];
    					if($intNum>0){
		    					$integralData = $data;
		    					$integralData['borderid'] =  $borderid;
		    					$integralData['orderid'] = get_order_id();
		    					$integralData['type'] = '14';
		    					$types .= 'i14,';
		    					$integralData['integralnum'] = $intNum;
	    					$integralChange = M('member_integral')->add($integralData);
	    					$registerIntegralChange = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->setInc('totalintegration',$intNum);
    					}else{
    						$integralChange = $registerIntegralChange = 1;
    					}
    				}else{
    					$experienceChange = $registerExperienceChange = $integralChange = $registerIntegralChange = 1;
    				}
    				$updateSpendingFrequency = $spendingReturn = 1;
    			}elseif($option['type'] == 7 || $option['type'] == 8 ){
    				$allReturn = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->setDec('accountbalance',$option['num']);
    				//$spendingReturn = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->setInc('totalspending',$option['num']);
    				$spendingReturn = 1;
    				if($memberIntegralSet['onlinechuzhipayisopen'] == '1' && $option['type'] == 7){
    					$expNum = $memberIntegralSet['onlinechuzhipayexp']*$option['num'];
    					if($expNum>0){
    						$experienceData = $data;
    						$experienceData['borderid'] = $borderid;
    						$experienceData['orderid'] = get_order_id();
    						$experienceData['type'] = '11';
    						$types .= 'e11,';
    						$experienceData['experiencevalue'] = $expNum;
    						$experienceChange = M('member_experiencevalue')->add($experienceData);
    						$registerExperienceChange = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->setInc('totalexperiencevalue',$expNum);
    					}else{
    						$experienceChange = $registerExperienceChange = 1;
    					}
    					$intNum = $memberIntegralSet['onlinechuzhipayint']*$option['num'];
    					if($intNum>0){
    						$integralData = $data;
    						$integralData['borderid'] = $borderid;
    						$integralData['orderid'] = get_order_id();
    						$integralData['type'] = '11';
    						$types .= 'i11,';
    						$integralData['integralnum'] = $intNum;
    						$integralChange = M('member_integral')->add($integralData);
    						$registerIntegralChange = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->setInc('totalintegration',$intNum);
    					}else{
    						$integralChange = $registerIntegralChange = 1;
    					}
    				}elseif ($memberIntegralSet['houtaichuzhipayisopen'] == '1' && $option['type'] == 8){
    					$expNum = $memberIntegralSet['houtaichuzhipayexp']*$option['num'];
    					if($expNum>0){
    						$experienceData = $data;
    						$experienceData['borderid'] =  $borderid;
    						$experienceData['orderid'] =  get_order_id();
    						$experienceData['type'] =  '12';
    						$types .= 'e12,';
    						$experienceData['experiencevalue'] =  $expNum;
    						$experienceChange = M('member_experiencevalue')->add($experienceData);
    						$registerExperienceChange = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->setInc('totalexperiencevalue',$expNum);
    					}else{
    						$experienceChange = $registerExperienceChange = 1;
    					}
    					$intNum = $memberIntegralSet['houtaichuzhipayint']*$option['num'];
    					if($intNum>0){
    						$integralData = $data;
    						$integralData['borderid'] =  $borderid;
    						$integralData['orderid'] =  get_order_id();
    						$integralData['type'] =  '12';
    						$types .= 'i12,';
    						$integralData['integralnum'] =  $intNum;
    						$integralChange = M('member_integral')->add($integralData);
    						$registerIntegralChange = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->setInc('totalintegration',$intNum);
    					}else{
    						$integralChange = $registerIntegralChange = 1;
    					}
    				}else{
    					$experienceChange = $registerExperienceChange = $integralChange = $registerIntegralChange = 1;
    				}
    				//修改消费频率 消费次数/月数
    				$allSpendingCount = M('member_spending')->where(array('companyid'=>$companyid,'mid'=>$mid,'type'=>array('in','1,2,3,4,5,6,7,8'),'status'=>1))->count();
    				$memberRegisterInfo = M('member_register_info')->where(array('companyid'=>$companyid,'id'=>$mid))->field('createtime')->find();
    				$remberRegisterMonths = get_time_months(time(),$memberRegisterInfo['createtime']);
    				$remberRegisterMonths = $remberRegisterMonths > 0 ? $remberRegisterMonths : 1 ;
    				//修改消费频率 消费次数/月数
    				$updateSpendingFrequency = M('member_register_info')->where(array('companyid'=>$companyid,'id'=>$mid))->save(array('spendingfrequency'=>format_number($allSpendingCount/$remberRegisterMonths),'lastspendingtime'=>time(),'updatetime'=>time()));
    			}elseif($option['type'] == 1 || $option['type'] == 2 || $option['type'] == 3 || $option['type'] == 4 || $option['type'] == 5 || $option['type'] == 6 || $option['type'] == 24 || $option['type'] == 25 || $option['type'] == 26 || $option['type'] == 27){
    				$allReturn = '1';
    				// 添加总消费金额
    				$spendingReturn = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->setInc('totalspending',$option['num']);
    				if($memberIntegralSet['offlinespendingisopen'] == '1' && $option['type'] == 1){
    					$expNum = $memberIntegralSet['offlinespendingexp']*$option['num'];
    					if($expNum>0){
    						$experienceData = $data;
    						$experienceData['borderid'] =  $borderid;
    						$experienceData['orderid'] =  get_order_id();
    						$experienceData['type'] =  '5';
    						$types .= 'e5,';
    						$experienceData['experiencevalue'] =  $expNum;
    						$experienceChange = M('member_experiencevalue')->add($experienceData);
    						$registerExperienceChange = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->setInc('totalexperiencevalue',$expNum);
    					}else{
    						$experienceChange = $registerExperienceChange = 1;
    					}
    					$intNum = $memberIntegralSet['offlinespendingint']*$option['num'];
    					if($intNum>0){
    						$integralData = $data;
    						$integralData['borderid'] =  $borderid;
    						$integralData['orderid'] =  get_order_id();
    						$integralData['type'] =  '5';
    						$types .= 'i5,';
    						$integralData['integralnum'] =  $intNum;
    						$integralChange = M('member_integral')->add($integralData);
    						$registerIntegralChange = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->setInc('totalintegration',$intNum);
    					}else{
    						$integralChange = $registerIntegralChange = 1;
    					}
    				}elseif ($memberIntegralSet['houtaispendingisopen'] == '1'  && $option['type'] == 2){
    					$expNum = $memberIntegralSet['houtaispendingexp']*$option['num'];
    					if($expNum>0){
    						$experienceData = $data;
    						$experienceData['borderid'] =  $borderid;
    						$experienceData['orderid'] =  get_order_id();
    						$experienceData['type'] =  '6';
    						$types .= 'e6,';
    						$experienceData['experiencevalue'] =  $expNum;
    						$experienceChange = M('member_experiencevalue')->add($experienceData);
    						$registerExperienceChange = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->setInc('totalexperiencevalue',$expNum);
    					}else{
    						$experienceChange = $registerExperienceChange = 1;
    					}
    					$intNum = $memberIntegralSet['houtaispendingint']*$option['num'];
    					if($intNum>0){
    						$integralData = $data;
    						$integralData['borderid'] =  $borderid;
    						$integralData['orderid'] =  get_order_id();
    						$integralData['type'] =  '6';
    						$types .= 'i6,';
    						$integralData['integralnum'] =  $intNum;
    						$integralChange = M('member_integral')->add($integralData);
    						$registerIntegralChange = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->setInc('totalintegration',$intNum);
    					}else{
    						$integralChange = $registerIntegralChange = 1;
    					}
    				}elseif ($memberIntegralSet['wechatspendingisopen'] == '1' && $option['type'] == 3){
    					$expNum = $memberIntegralSet['wechatspendingexp']*$option['num'];
    					if($expNum>0){
    						$experienceData = $data;
    						$experienceData['borderid'] =  $borderid;
    						$experienceData['orderid'] =  get_order_id();
    						$experienceData['type'] =  '7';
    						$types .= 'e7,';
    						$experienceData['experiencevalue'] =  $expNum;
    						$experienceChange = M('member_experiencevalue')->add($experienceData);
    						$registerExperienceChange = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->setInc('totalexperiencevalue',$expNum);
    					}else{
    						$experienceChange = $registerExperienceChange = 1;
    					}
    					$intNum = $memberIntegralSet['wechatspendingint']*$option['num'];
    					if($intNum>0){
    						$integralData = $data;
    						$integralData['borderid'] =  $borderid;
    						$integralData['orderid'] =  get_order_id();
    						$integralData['type'] =  '7';
    						$types .= 'i7,';
    						$integralData['integralnum'] =  $intNum;
    						$integralChange = M('member_integral')->add($integralData);
    						$registerIntegralChange = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->setInc('totalintegration',$intNum);
    					}else{
    						$integralChange = $registerIntegralChange = 1;
    					}
    				}elseif ($memberIntegralSet['yinhangkapendingisopen'] == '1' && $option['type'] == 4){
    					$expNum = $memberIntegralSet['yinhangkapendingexp']*$option['num'];
    					if($expNum>0){
    						$experienceData = $data;
    						$experienceData['borderid'] =  $borderid;
    						$experienceData['orderid'] =  get_order_id();
    						$experienceData['type'] =  '8';
    						$types .= 'e8,';
    						$experienceData['experiencevalue'] =  $expNum;
    						$experienceChange = M('member_experiencevalue')->add($experienceData);
    						$registerExperienceChange = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->setInc('totalexperiencevalue',$expNum);
    					}else{
    						$experienceChange = $registerExperienceChange = 1;
    					}
    					$intNum = $memberIntegralSet['yinhangkapendingint']*$option['num'];
    					if($intNum>0){
    						$integralData = $data;
    						$integralData['borderid'] =  $borderid;
    						$integralData['orderid'] =  get_order_id();
    						$integralData['type'] =  '8';
    						$types .= 'i8,';
    						$integralData['integralnum'] =  $intNum;
    						$integralChange = M('member_integral')->add($integralData);
    						$registerIntegralChange = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->setInc('totalintegration',$intNum);
    					}else{
    						$integralChange = $registerIntegralChange = 1;
    					}
    				}elseif ($memberIntegralSet['alipaypendingisopen'] == '1' && $option['type'] == 5){
    					$expNum = $memberIntegralSet['alipaypendingexp']*$option['num'];
    					if($expNum>0){
    						$experienceData = $data;
    						$experienceData['borderid'] =  $borderid;
    						$experienceData['orderid'] =  get_order_id();
    						$experienceData['type'] =  '9';
    						$types .= 'e9,';
    						$experienceData['experiencevalue'] =  $expNum;
    						$experienceChange = M('member_experiencevalue')->add($experienceData);
    						$registerExperienceChange = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->setInc('totalexperiencevalue',$expNum);
    					}else{
    						$experienceChange = $registerExperienceChange = 1;
    					}
    					$intNum = $memberIntegralSet['alipaypendingint']*$option['num'];
    					if($intNum>0){
    						$integralData = $data;
    						$integralData['borderid'] =  $borderid;
    						$integralData['orderid'] =  get_order_id();
    						$integralData['type'] =  '9';
    						$types .= 'i9,';
    						$integralData['integralnum'] =  $intNum;
    						$integralChange = M('member_integral')->add($integralData);
    						$registerIntegralChange = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->setInc('totalintegration',$intNum);
    					}else{
    						$integralChange = $registerIntegralChange = 1;
    					}
    				}elseif ($memberIntegralSet['shophuodaofukuanisopen'] == '1' && $option['type'] == 6){
    					$expNum = $memberIntegralSet['shophuodaofukuanexp']*$option['num'];
    					if($expNum>0){
    						$experienceData = $data;
    						$experienceData['borderid'] =  $borderid;
    						$experienceData['orderid'] =  get_order_id();
    						$experienceData['type'] =  '10';
    						$types .= 'e10,';
    						$experienceData['experiencevalue'] =  $expNum;
    						$experienceChange = M('member_experiencevalue')->add($experienceData);
    						$registerExperienceChange = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->setInc('totalexperiencevalue',$expNum);
    					}else{
    						$experienceChange = $registerExperienceChange = 1;
    					}
    					$intNum = $memberIntegralSet['shophuodaofukuanint']*$option['num'];
    					if($intNum>0){
    						$integralData = $data;
    						$integralData['borderid'] =  $borderid;
    						$integralData['orderid'] =  get_order_id();
    						$integralData['type'] =  '10';
    						$types .= 'i10,';
    						$integralData['integralnum'] =  $intNum;
    						$integralChange = M('member_integral')->add($integralData);
    						$registerIntegralChange = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->setInc('totalintegration',$intNum);
    					}else{
    						$integralChange = $registerIntegralChange = 1;
    					}
    				}else if($memberIntegralSet['windhelperwechatspendingisopen'] == '1' && $option['type'] == 24){
    				    // 风助手微信支付
    				    $expNum = $memberIntegralSet['windhelperwechatspendingexp']*$option['num'];
    				    if($expNum>0){
    				        $experienceData = $data;
    				        $experienceData['borderid'] =  $borderid;
    				        $experienceData['orderid'] =  get_order_id();
    				        $experienceData['type'] =  '24';
    				        $types .= 'e24,';
    				        $experienceData['experiencevalue'] =  $expNum;
    				        $experienceChange = M('member_experiencevalue')->add($experienceData);
    				        $registerExperienceChange = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->setInc('totalexperiencevalue',$expNum);
    				    }else{
    				        $experienceChange = $registerExperienceChange = 1;
    				    } 
    				    $intNum = $memberIntegralSet['windhelperwechatspendingint']*$option['num'];
    				    if($intNum>0){
    				        $integralData = $data;
    				        $integralData['borderid'] = $borderid;
    				        $integralData['orderid'] =  get_order_id();
    				        $integralData['type'] =  '24';
    				        $types .= 'i24,';
    				        $integralData['integralnum'] =  $intNum;
    				        $integralChange = M('member_integral')->add($integralData);
    				        $registerIntegralChange = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->setInc('totalintegration',$intNum);
    				    }else{
    				        $integralChange = $registerIntegralChange = 1;
    				    }   
    				}else if($memberIntegralSet['windhelperalipaypendingisopen'] == '1' && $option['type'] == 25){
    				    // 风助手支付宝支付
    				    $expNum = $memberIntegralSet['windhelperalipaypendingexp']*$option['num'];
    				    if($expNum>0){
    				        $experienceData = $data;
    				        $experienceData['borderid'] = $borderid;
    				        $experienceData['orderid'] = get_order_id();
    				        $experienceData['type'] =  '25';
    				        $types .= 'e25,';
    				        $experienceData['experiencevalue'] = $expNum;
    				        $experienceChange = M('member_experiencevalue')->add($experienceData);
    				        $registerExperienceChange = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->setInc('totalexperiencevalue',$expNum);
    				    }else{
    				        $experienceChange = $registerExperienceChange = 1;
    				    } 
    				    $intNum = $memberIntegralSet['windhelperalipaypendingint']*$option['num'];
    				    if($intNum>0){
    				        $integralData = $data;
    				        $integralData['borderid'] = $borderid;
    				        $integralData['orderid'] = get_order_id();
    				        $integralData['type'] =  '25';
    				        $types .= 'i25,';
    				        $integralData['integralnum'] =  $intNum;
    				        $integralChange = M('member_integral')->add($integralData);
    				        $registerIntegralChange = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->setInc('totalintegration',$intNum);
    				    }else{
    				        $integralChange = $registerIntegralChange = 1;
    				    }
    				}else if($memberIntegralSet['windhelpercashspendingisopen'] == '1' && $option['type'] == 26){
    				    // 风助手现金支付
    				    $expNum = $memberIntegralSet['windhelpercashspendingexp']*$option['num'];
    				    if($expNum>0){
    				        $experienceData = $data;
    				        $experienceData['borderid'] =  $borderid;
    				        $experienceData['orderid'] = get_order_id();
    				        $experienceData['type'] =  '26';
    				        $types .= 'e26,';
    				        $experienceData['experiencevalue'] =  $expNum;
    				        $experienceChange = M('member_experiencevalue')->add($experienceData);
    				        $registerExperienceChange = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->setInc('totalexperiencevalue',$expNum);
    				    }else{
    				        $experienceChange = $registerExperienceChange = 1;
    				    }
    				    $intNum = $memberIntegralSet['windhelpercashspendingint']*$option['num'];
    				    if($intNum>0){
    				        $integralData = $data;
    				        $integralData['borderid'] =  $borderid;
    				        $integralData['orderid'] = get_order_id();
    				        $integralData['type'] =  '26';
    				        $types .= 'i26,';
    				        $integralData['integralnum'] =  $intNum;
    				        $integralChange = M('member_integral')->add($integralData);
    				        $registerIntegralChange = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->setInc('totalintegration',$intNum);
    				    }else{
    				        $integralChange = $registerIntegralChange = 1;
    				    }
    				}else if($memberIntegralSet['wechatpaybillisopen'] == '1' && $option['type'] == 27){
    				    // 微信买单
    				    $expNum = $memberIntegralSet['wechatpaybillexp']*$option['num'];
    				    if($expNum>0){
    				        $experienceData = $data;
    				        $experienceData['borderid'] =  $borderid;
    				        $experienceData['orderid'] = get_order_id();
    				        $experienceData['type'] =  '27';
    				        $types .= 'e27,';
    				        $experienceData['experiencevalue'] =  $expNum;
    				        $experienceChange = M('member_experiencevalue')->add($experienceData);
    				        $registerExperienceChange = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->setInc('totalexperiencevalue',$expNum);
    				    }else{
    				        $experienceChange = $registerExperienceChange = 1;
    				    }
    				    $intNum = $memberIntegralSet['wechatpaybillint']*$option['num'];
    				    if($intNum>0){
    				        $integralData = $data;
    				        $integralData['borderid'] =  $borderid;
    				        $integralData['orderid'] = get_order_id();
    				        $integralData['type'] =  '27';
    				        $types .= 'i27,';
    				        $integralData['integralnum'] =  $intNum;
    				        $integralChange = M('member_integral')->add($integralData);
    				        $registerIntegralChange = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->setInc('totalintegration',$intNum);
    				    }else{
    				        $integralChange = $registerIntegralChange = 1;
    				    }
    				}else{
    					$experienceChange = $registerExperienceChange = $integralChange = $registerIntegralChange = 1;
    				}
    				//修改消费频率 消费次数/月数
    				$allSpendingCount = M('member_spending')->where(array('companyid'=>$companyid,'mid'=>$mid,'type'=>array('in','1,2,3,4,5,6,7,8'),'status'=>1))->count();
    				$memberRegisterInfo = M('member_register_info')->where(array('companyid'=>$companyid,'id'=>$mid))->field('createtime')->find();
    				$remberRegisterMonths = get_time_months(time(),$memberRegisterInfo['createtime']);
    				$remberRegisterMonths = $remberRegisterMonths > 0 ? $remberRegisterMonths : 1 ;
    				//修改消费频率 消费次数/月数
    				$updateSpendingFrequency = M('member_register_info')->where(array('companyid'=>$companyid,'id'=>$mid))->save(array('spendingfrequency'=>format_number($allSpendingCount/$remberRegisterMonths),'lastspendingtime'=>time(),'updatetime'=>time()));
    			}
    			$bdata['orderid'] = $borderid;
    			$bdata['businesstype'] = $option['businesstype'];
    			$bdata['types'] = $types;
    			$businessReturn = M('member_business')->add($bdata);
    		}
    		$accountbalance = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->getField('accountbalance');
    		if($businessReturn&&$spendingChange&&$allReturn&&$spendingReturn&&$accountbalance>=0&&$updateSpendingFrequency&&$experienceChange&&$registerExperienceChange&&$integralChange&&$registerIntegralChange){
    		    M()->commit();
    		    // 发送积分改变消息模板
    			if($option['status']&&$sendOpenid){
    			    $nowTotalintegral = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->getField('totalintegration');
    			    $optionType = $integralData['type'] ? $integralData['type'] : $integralInfo['type'];
    			    $changeIntNum = $integralData['integralnum'] ? $integralData['integralnum'] : $integralInfo['integralnum'] ;
    			    if($option['status'] == '1'){
    			        $sendType = '3';// 积分添加
    			        $sendDesc = $this->weChatTemplateIntDesc[$optionType];
    			        $this->WeChatTemplateMessageSend($sendType,$sendOpenid,$companyid,'','',array(format_time(time(),'ymdhis'),$changeIntNum,$sendDesc,$nowTotalintegral),'');
    			    }elseif ($option['status'] == '2'){
    			        $sendType = '4';// 积分减少
    			        $sendDesc = '撤销'.$this->weChatTemplateIntDesc[$optionType];
    			        $this->WeChatTemplateMessageSend($sendType,$sendOpenid,$companyid,'','',array($sendDesc,$changeIntNum,$nowTotalintegral),'');
    			    }
    			}
	    		$this->changMemberCardRank($companyid,$mid);//改变会员卡等级
    			return true;
    		}else{
    			M()->rollback();
    			return false;
    		}
    	}elseif($option['businesstype'] == '4'){
    		if($option['type'] == '17'){
    			$type = '大众点评好评';
    		}
    		//积分类型：1:完善100%会员资料；2:成功开卡；3:推荐开卡；4:会员等级升级；15:LBS签到；16:参与活动（所有的经营活动）；17:大众点评好评；18:Tripadvisor好评；
    		$memberIntegralSet = D('Member_integral_set')->getMemberIntegralSetInfo(array('companyid'=>$companyid));
    		if($option['orderid']){
    				$experienceInfo = M('member_experiencevalue')->where(array('borderid'=>$option['orderid'],'companyid'=>$companyid))->field('experiencevalue,status')->find();
    				if($experienceInfo){
    					$experienceData = $data;
    					$experienceChange = M('member_experiencevalue')->where(array('borderid'=>$option['orderid'],'companyid'=>$companyid))->save($experienceData);
    					if($option['status'] == 1){
	    					$registerExperienceChange = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->setInc('totalexperiencevalue',$experienceInfo['experiencevalue']);
    					}elseif ($option['status'] == 2){
    						$registerExperienceChange = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->setDec('totalexperiencevalue',$experienceInfo['experiencevalue']);
    					}
    				}else{
    					$experienceChange = $registerExperienceChange = 1;
    				}
    				$integralInfo = M('member_integral')->where(array('borderid'=>$option['orderid'],'companyid'=>$companyid))->field('integralnum,type,status')->find();
    				if($integralInfo){
    					$integralData = $data;
    					$integralChange = M('member_integral')->where(array('borderid'=>$option['orderid'],'companyid'=>$companyid))->save($integralData);
    					if($option['status'] == 1){
    						$registerIntegralChange = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->setInc('totalintegration',$integralInfo['integralnum']);
    					}elseif ($option['status'] == 2){
    						$registerIntegralChange = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->setDec('totalintegration',$integralInfo['integralnum']);
    					}
    				}else{
    					$integralChange = $registerIntegralChange = 1;
    				}
    		}else{
    			$types = ',';
    			//积分类型：1:完善100%会员资料(只修改不添加)；2:成功开卡(只修改不添加)；3:推荐开卡(只修改不添加)；4:会员等级升级(只修改不添加)；15:LBS签到；16:参与活动（所有的经营活动）；17:大众点评好评；18:Tripadvisor好评；
    			if($option['type'] == 1 ||$option['type'] == 2 ||$option['type'] == 3 ||$option['type'] == 4 ||$option['type'] == 15 ||$option['type'] == 17 ||$option['type'] == 18){
    				if ($memberIntegralSet['lbsqiandaoisopen'] == '1' && $option['type'] == 15){
    					$expNum = $memberIntegralSet['lbsqiandaoexp'];
						if($expNum>0){
		    					$experienceData = $data;
		    					$experienceData['borderid'] =  $borderid;
		    					$experienceData['orderid'] =  get_order_id();
		    					$experienceData['type'] =  '15';
		    					$types .= 'e15,';
		    					$experienceData['experiencevalue'] =  $expNum;
	    					$experienceChange = M('member_experiencevalue')->add($experienceData);
	    					$registerExperienceChange = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->setInc('totalexperiencevalue',$expNum);
						}else{
							$experienceChange = $registerExperienceChange = 1;
						}
    					$intNum = $memberIntegralSet['lbsqiandaoint'];
    					if($intNum>0){
		    					$integralData = $data;
		    					$integralData['borderid'] =  $borderid;
		    					$integralData['orderid'] =  get_order_id();
		    					$integralData['type'] =  '15';
		    					$types .= 'i15,';
		    					$integralData['integralnum'] =  $intNum;
	    					$integralChange = M('member_integral')->add($integralData);
	    					$registerIntegralChange = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->setInc('totalintegration',$intNum);
    					}else{
    						$integralChange = $registerIntegralChange = 1;
    					}
    				}elseif ($memberIntegralSet['dianpingisopen'] == '1' && $option['type'] == 17){
    					$expNum = $memberIntegralSet['dianpingexp'];
						if($expNum>0){
		    					$experienceData = $data;
		    					$experienceData['borderid'] =  $borderid;
		    					$experienceData['orderid'] =  get_order_id();
		    					$experienceData['type'] =  '17';
		    					$types .= 'e17,';
		    					$experienceData['experiencevalue'] =  $expNum;
		    				$experienceChange = M('member_experiencevalue')->add($experienceData);
	    					$registerExperienceChange = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->setInc('totalexperiencevalue',$expNum);
						}else{
							$experienceChange = $registerExperienceChange = 1;
						}
    					$intNum = $memberIntegralSet['dianpingint'];
    					if($intNum>0){
		    					$integralData = $data;
		    					$integralData['borderid'] =  $borderid;
		    					$integralData['orderid'] =  get_order_id();
		    					$integralData['type'] =  '17';
		    					$types .= 'i17,';
		    					$integralData['integralnum'] =  $intNum;
	    					$integralChange = M('member_integral')->add($integralData);
	    					$registerIntegralChange = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->setInc('totalintegration',$intNum);
    						$totalintegration1 = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->getField('totalintegration');
    					}else{
    						$integralChange = $registerIntegralChange = 1;
    					}
    				}elseif ($memberIntegralSet['tripadvisorisopen'] == '1' && $option['type'] == 18){
    					$expNum = $memberIntegralSet['tripadvisorexp'];
						if($expNum>0){
		    					$experienceData = $data;
		    					$experienceData['borderid'] =  $borderid;
		    					$experienceData['orderid'] =  get_order_id();
		    					$experienceData['type'] =  '18';
		    					$types .= 'e18,';
		    					$experienceData['experiencevalue'] =  $expNum;
	    					$experienceChange = M('member_experiencevalue')->add($experienceData);
	    					$registerExperienceChange = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->setInc('totalexperiencevalue',$expNum);
						}else{
							$experienceChange = $registerExperienceChange = 1;
						}
    					$intNum = $memberIntegralSet['tripadvisorint'];
    					if($intNum>0){
		    					$integralData = $data;
		    					$integralData['borderid'] =  $borderid;
		    					$integralData['orderid'] =  get_order_id();
		    					$integralData['type'] =  '18';
		    					$types .= 'i18,';
		    					$integralData['integralnum'] =  $intNum;
	    					$integralChange = M('member_integral')->add($integralData);
	    					$registerIntegralChange = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->setInc('totalintegration',$intNum);
    					}else{
    						$integralChange = $registerIntegralChange = 1;
    					}
    				}else{
    					$experienceChange = $registerExperienceChange = $integralChange = $registerIntegralChange = 1;
    				}
    			}
    			$bdata['orderid'] = $borderid;
    			$bdata['businesstype'] = $option['businesstype'];
    			$bdata['types'] = $types;
    			$businessReturn = M('member_business')->add($bdata);
    		}
    		if($businessReturn&&$experienceChange&&$registerExperienceChange&&$integralChange&&$registerIntegralChange){
    			M()->commit();
    			// 发送积分改变消息模板
    			if($option['status']&&$sendOpenid){
    			    $nowTotalintegral = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->getField('totalintegration');
    			    $optionType = $integralData['type'] ? $integralData['type'] : $integralInfo['type'];
    			    $changeIntNum = $integralData['integralnum'] ? $integralData['integralnum'] : $integralInfo['integralnum'] ;
    			    if($option['status'] == '1'){
    			        $sendType = '3';// 积分添加
    			        $sendDesc = $this->weChatTemplateIntDesc[$optionType];
    			        $this->WeChatTemplateMessageSend($sendType,$sendOpenid,$companyid,'','',array(format_time(time(),'ymdhis'),$changeIntNum,$sendDesc,$nowTotalintegral),'');
    			    }elseif ($option['status'] == '2'){
    			        $sendType = '4';// 积分减少
    			        $sendDesc = '撤销'.$this->weChatTemplateIntDesc[$optionType];
    			        $this->WeChatTemplateMessageSend($sendType,$sendOpenid,$companyid,'','',array($sendDesc,$changeIntNum,$nowTotalintegral),'');
    			    }
    			}
	    		$this->changMemberCardRank($companyid,$mid);//改变会员卡等级
    		    return true;
    		}else{
    			M()->rollback();
    			return false;
    		}
    	}else{
    		M()->rollback();
    		return false;
    	}
    }
    /**
     * $option['borderid']        非必填:退款操作时必填，目前仅有type为110,113,114 支持退款的后续操作储值退还，积分退还
     * $option['cid']             必填:操作时使用的公司id,也就是我们用到的companyid
     * $option['shopid']          必填:操作时使用的shopid,后面需进行数据统计
     * $option['type']            必填,添加时必填：数据获取类型，添加时注意对应关系；完善会员资料:101;注册:102;点评奖励:103;手动加积分:104;风助手手机收银:105;闪惠支付:106;风助手手机收银:107;微信关注:108;首次消费:109;eshop支付:110;拉卡拉手机收银:111;充值:112（包含：后台充值、在线充值、红包充值）;手机预订:113;微信外卖：114；手机点单：115；风助手手动加积分：116；手机预订奖励：117；手动扣除积分:201;到期自动清零:202;会员WAP积分换储值:203;积分商城:204;风助手手动减积分:205;后台储值消费:301;
     * $option['uid']             非必填:操作时使用的子账号uid,需根据此id 查到对应门店shopid，shopname
     * $option['mid']             非必填,操作时的会员id,也就是我们使用的mid，也有可能是没有MID的，比如门店收银顾客需要送积分
     * $option['num']             非必填,操作时产生的数据1，消费时必填，积分交易时有可能非必填,例如首次消费的积分是直接数据库中读取，直接添加的积分需要参数传入再或者消费的金额再或者充值的金额，积分是整型数据：10;消费是浮点型数据:88.89;
     * $option['num2']            非必填，操作时产生的数据2，例如在线充值，订单金额与充值金额是两个不同的值，需要同时传入
     * $option['linkorderid']     非必填，如果产生的交易带有订单号，需把对应的订单号传入，方便后续产品优化查询消费或者积分获取来源；
     * $option['linkoutorderid']  非必填，如果产生的交易带有外部商户订单号，需把对应的商户订单号传入，方便后续产品优化查询消费或者积分获取来源；比如：微信支付，支付宝支付，拉卡拉支付
     * $option['paytype']         非必填，消费时必填，对应支付方式； 微信支付：1；支付宝支付：2；现金支付：3；储值支付：4；银行卡支付：5；
     * $option['note']            非必填，交易备注
     * $option['rechargetype']    非必填，充值方式，用于充值记录；后台充值：1；在线充值：2；会员WAP积分换储值：3；红包：4；
     * $option['issendint']       非必填，消费时是否赠送积分：1是；2：否；默认：1;
     * @author Lando<806728685@qq.com>
     * @since  2016-11-16
     */
    public function changeMemberBusinessSCRM5($option){
        $time = time();
        M()->startTrans();
        $changeNum = $option['num'] ? $option['num'] : 0 ; // 操作时产生的数据1
        $changeNum2 = $option['num2'] ? $option['num2'] : 0 ; // 操作时产生的数据2
        if($changeNum > 999999 || $changeNum2 > 999999){
            return false;
        }
        $type = $option['type'] ? $option['type'] : 0 ;
        $companyid = $option['cid'] ? $option['cid'] : 0 ;
        $shopid = $option['shopid'] ? $option['shopid'] : 0 ;
        $mid = $option['mid'] ? $option['mid'] : 0 ;   // 客户id
        $uid = $option['uid'] ? $option['uid'] : 0 ;   // 操作人id
        $rechargetype = $option['rechargetype'] ? $option['rechargetype'] : 0 ;// 充值方式， 后台储值充值：1；会员WAP自助充值：2；会员WAP积分换储值：3；红包：4；风助手储值充值:5;
        $linkOrderId = $option['linkorderid'] ? $option['linkorderid'] : '' ;// 关联订单号
        $linkOutOrderId = $option['linkoutorderid'] ? $option['linkoutorderid'] : '' ;// 关联外部商户订单号
        $borderid = $option['borderid'] ? $option['borderid'] : '';
        $issendint = $option['issendint'] ? $option['issendint'] : '1';
        $createtime = $option['createtime'] ? $option['createtime'] : $time;
        if($borderid){
            //  当传入$borderid 默认为撤销消费，使用场景：过期退，随时退
            $data['status'] = 2 ;
            $data['updatetime'] = $time;
        }else{
            $data['adduid'] = $data['edituid'] =  0;
            $data['companyid'] = $companyid;
            $data['shopsid'] = $shopid;
            $data['mid'] = $mid ;
            $data['linkorderid'] = $linkOrderId ;
            $data['linkoutorderid'] = $linkOutOrderId ;
            $paytype = $option['paytype'] ? $option['paytype'] : 0 ;// 支付方式 微信支付：1；支付宝支付：2；现金支付：3；储值支付：4；银行卡支付：5；
            $data['paytype'] = $paytype ;// 支付方式
            // 消费 使用场景，例如：闪惠支付：106；风助手手机收银：107；eshop支付：110；拉卡拉手机收银：111；手机预订：113；微信外卖：114:；手机点单：115；预订(SPA):118;预订(餐饮):119;预订(酒店):120;付费内容商店(年卡+单品):121;
            $data['borderid'] = get_order_id(10);
            $data['status'] = 1 ;
            $data['note'] = $option['note'] ? $option['note'] : '' ;
            $data['rechargetype'] = $rechargetype ;
            $data['updatetime'] = $data['createtime'] = $createtime;
        }
        if($uid > 0){
            $userInfo = M('users')->where(array('companyid'=>$companyid, 'id'=>$uid))->field('id,username,truename,isboss,helpershopid')->find();
            if($userInfo['helpershopid'] == '-1' || $userInfo['isboss'] == '1'){
                $shopInfo = array('id'=>'-1','shopname'=>'总部');
            }else{
                $shopInfo = M('company_shops')->where(array('companyid'=>$companyid, 'id'=>$userInfo['helpershopid']))->field('id,shopname')->find();
            }
        }
        if(!$userInfo){
            $userInfo = array('id'=>'0','username'=>'','truename'=>'系统','isbosss'=>'0','helpershopid'=>'0');
        }
        if($shopid){
            $shopInfo = M('company_shops')->where(array('companyid'=>$companyid, 'id'=>$shopid))->field('id,shopname')->find();
        }
        if(!$shopInfo){
            $shopInfo = array('id'=>'0','shopname'=>'');// 备用数据
        }
        // 目前先读取所有字段，后面优化，因为有很多字段都是需要废弃的，现在还不确定
        $integralSet = M('member_integral_set')->where(array('companyid'=>$companyid))->find();
        $changeIntNum = $changeSpendingNum = $changeSpendingNum2 = $isrecharge = $nextyearcanuseintegralChangeInt = 0;
        // 获取当前公司是否设置积分清零规则
        $memberIntIsAutoClear = $integralSet['integralisautoclear'];
        if($borderid){
            // 撤销消费
            if($type == '106' || $type == '107' || $type == '110' || $type == '111' || $type == '112' || $type == '113' || $type == '114' || $type == '115' || $type == '301' || $type == '118'|| $type == '119'|| $type == '120'|| $type == '127'){
                $memberSpendingInfo = M('member_spending')->where(array('companyid'=>$companyid,'borderid'=>$borderid,'type'=>$type))->field('mid,paytype,rechargetype,spendingamount,spendingamount2,shopid')->find();
                if($memberSpendingInfo){
                    $status = 2; // 标识扣除
                    $linkOrderIdSaveReturn = 1;
                    $mid = $memberSpendingInfo['mid'] ? $memberSpendingInfo['mid'] : 0;
                    $paytype = $memberSpendingInfo['paytype'] ? $memberSpendingInfo['paytype'] : 0;
                    $rechargetype = $memberSpendingInfo['rechargetype'] ? $memberSpendingInfo['rechargetype'] : 0 ;
                    $changeSpendingNum = $memberSpendingInfo['spendingamount'] ? $memberSpendingInfo['spendingamount'] : 0 ;
                    $changeSpendingNum2 = $memberSpendingInfo['spendingamount2'] ? $memberSpendingInfo['spendingamount2'] : 0 ;
    
                    if($type == '106' || $type == '110' || $type == '113' || $type == '114' || $type == '118'|| $type == '119'|| $type == '120'|| $type == '127'){
                        if($paytype == '4'){
                            $memberBeforeAccountbalance = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->getField('accountbalance');
                        }
                        if($paytype == '4'  && $changeSpendingNum2>0 && $mid){
                            // 个收单口储值支付+后台储值支付撤销，增加账户余额
                            $memberRegisterInfoSaveData['accountbalance'] = array('exp', '`accountbalance`+'.$changeSpendingNum2.'');
                        }elseif(($type == '112' && ($rechargetype == '1' || $rechargetype == '2' || $rechargetype == '5')) && $changeSpendingNum2>0 && $mid){
                            // 后台储值充值+会员WAP自助充值+风助手储值充值撤销，减少账户余额
                            $memberRegisterInfoSaveData['accountbalance'] = array('exp', '`accountbalance`-'.$changeSpendingNum2.'');
                        }
                        if($changeSpendingNum2>0){
                            if($type == '112' || $type == '203'){
                                // 累计充值金额 ，充值进会员账户的金额 包含：后台储值充值，会员WAP自助充值，红包充值，会员WAP积分换储值，风助手储值充值；
                                $companySaveData['totalrecharge'] = array('exp', '`totalrecharge`-'.$changeSpendingNum2.'');
                            }
                        }
                        if($changeSpendingNum>0){
                            if(($type !='112' && $paytype !='4') || ($type=='112' && ($rechargetype =='2' || $rechargetype =='5'))){
                                // 累计总收入 包含所有收单口的消费，例如:eshop;充值(在线+风助手);闪惠;微信外卖;预订(通用版);手机点单;风助手;拉卡拉;预订(SPA);预订(餐饮);预订(酒店);付费内容商店(年卡+单品);支付方式排除储值支付
                                $companySaveData['totalincome'] = array('exp', '`totalincome`-'.$changeSpendingNum.'');
                            }
                            // 累计总收入 包含所有收单口的消费，例如:eshop;充值(在线+风助手);闪惠;微信外卖;预订(通用版);手机点单;风助手;拉卡拉;预订(SPA);预订(餐饮);预订(酒店);付费内容商店(年卡+单品);支付方式不排除储值支付
                            $companyShopsSaveData['shoptotalincome'] = array('exp', '`shoptotalincome`-'.$changeSpendingNum.'');
                            //  按照收单口算累积收入
                            if($type == '106'){
                                $companySaveData['totalshanhuiincome'] = array('exp', '`totalshanhuiincome`-'.$changeSpendingNum.'');
                            }elseif($type == '107'){
                                $companySaveData['totalhelperincome'] = array('exp', '`totalhelperincome`-'.$changeSpendingNum.'');
                            }elseif($type == '110' ){
                                $companySaveData['totaleshopincome'] = array('exp', '`totaleshopincome`-'.$changeSpendingNum.'');
                            }elseif($type == '111'){
                                $companySaveData['totallakalaincome'] = array('exp', '`totallakalaincome`-'.$changeSpendingNum.'');
                            }elseif($type == '112'){
                                if($rechargetype == '2'){
                                    // 在线充值累计;仅仅包含会员WAP自助充值所有累计支付金额统计，不是充值进会员账户金额统计；
                                    $companySaveData['totalsellrechargeincome'] = array('exp', '`totalsellrechargeincome`-'.$changeSpendingNum.'');
                                }
                                if($rechargetype == '1' || $rechargetype == '2' || $rechargetype == '5'){
                                    // 多渠道累计储值卡售出金额累计;仅仅包含后台储值充值,会员WAP自助充值,风助手储值充值 所有累计支付金额统计，不是充值进会员账户金额统计；
                                    $companySaveData['totalallsellrechargeincome'] = array('exp', '`totalallsellrechargeincome`-'.$changeSpendingNum.'');
                                }
                            }elseif($type == '113'){
                                // 预订(通用版)
                                $companySaveData['totalmobilebookincome'] = array('exp', '`totalmobilebookincome`-'.$changeSpendingNum.'');
                            }elseif($type == '114'){
                                $companySaveData['totaltakeoutincome'] = array('exp', '`totaltakeoutincome`-'.$changeSpendingNum.'');
                            }elseif($type == '115'){
                                $companySaveData['totalmobilephoneorderincome'] = array('exp', '`totalmobilephoneorderincome`-'.$changeSpendingNum.'');
                            }elseif($type == '118'){
                                // 预订(SPA)
                                $companySaveData['totalmobilebookspaincome'] = array('exp', '`totalmobilebookspaincome`-'.$changeSpendingNum.'');
                            }elseif($type == '119'){
                                // 预订(餐饮)
                                $companySaveData['totalmobilebookrestaurantincome'] = array('exp', '`totalmobilebookrestaurantincome`-'.$changeSpendingNum.'');
                            }elseif($type == '120'){
                                // 预订(酒店)
                                $companySaveData['totalmobilebookhotelincome'] = array('exp', '`totalmobilebookhotelincome`-'.$changeSpendingNum.'');
                            }elseif($type == '127'){
                                // 声悦传情
                                $companySaveData['totalsoundjoyincome'] = array('exp', '`totalsoundjoyincome`-'.$changeSpendingNum.'');
                            }
                            // 按照支付方式算累积收入
                            if($paytype == '1'){
                                $companySaveData['totalwechatpayincome'] = array('exp', '`totalwechatpayincome`-'.$changeSpendingNum.'');
                            }elseif($paytype == '2'){
                                $companySaveData['totalalipayincome'] = array('exp', '`totalalipayincome`-'.$changeSpendingNum.'');
                            }elseif($paytype == '3'){
                                $companySaveData['totalmoneypayincome'] = array('exp', '`totalmoneypayincome`-'.$changeSpendingNum.'');
                            }elseif($paytype == '4'){
                                $companySaveData['totalrechargepayincome'] = array('exp', '`totalrechargepayincome`-'.$changeSpendingNum.'');
                            }elseif($paytype == '5'){
                                $companySaveData['totalbankcardpayincome'] = array('exp', '`totalbankcardpayincome`-'.$changeSpendingNum.'');
                            }
                        }
                        // 公司累计总收入统计
                        if($companySaveData){
                            $companySaveData['updatetime'] = $time;
                            $companySaveReturn = M('company')->where(array('id'=>$companyid))->save($companySaveData);
                        }else{
                            $companySaveReturn = 1;
                        }
                        // 门店累计总收入统计
                        if($companyShopsSaveData && $memberSpendingInfo['shopid']>0){
                            $companyShopsSaveData['updatetime'] = $time;
                            $companyShopsSaveReturn = M('company_shops')->where(array('id'=>$memberSpendingInfo['shopid']))->save($companyShopsSaveData);
                        }else{
                            $companyShopsSaveReturn = 1;
                        }
                        $memberIntegralInfo = M('member_integral')->where(array('companyid'=>$companyid,'borderid'=>$borderid))->field('integralnum')->find();
                        $changeIntNum = $memberIntegralInfo['integralnum'] ? $memberIntegralInfo['integralnum'] : 0;
                        if($changeIntNum>0 && $mid){
                            // 获取当前会员几个关键的积分值
                            $nowMemberRegisterInfo = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->field('totalintegration,totalexperiencevalue,thisyearcanuseintegral,nextyearcanuseintegral')->find();
                            // 积分清零规则处理
                            if($memberIntIsAutoClear == '1'){
                                if($nowMemberRegisterInfo['nextyearcanuseintegral']>$changeIntNum){
                                    $memberRegisterInfoSaveData['nextyearcanuseintegral'] = array('exp', '`nextyearcanuseintegral`-'.$changeIntNum.'');
                                }else{
                                    $memberRegisterInfoSaveData['nextyearcanuseintegral'] = 0 ;
                                }
                            }else{
                                if($nowMemberRegisterInfo['thisyearcanuseintegral']>$changeIntNum){
                                    $memberRegisterInfoSaveData['thisyearcanuseintegral'] = array('exp', '`thisyearcanuseintegral`-'.$changeIntNum.'');
                                }else{
                                    $memberRegisterInfoSaveData['thisyearcanuseintegral'] = 0 ;
                                }
                                if($nowMemberRegisterInfo['nextyearcanuseintegral']>$changeIntNum){
                                    $memberRegisterInfoSaveData['nextyearcanuseintegral'] = array('exp', '`nextyearcanuseintegral`-'.$changeIntNum.'');
                                }else{
                                    $memberRegisterInfoSaveData['nextyearcanuseintegral'] = 0 ;
                                }
                            }
                            if($nowMemberRegisterInfo['totalexperiencevalue']>$changeIntNum){
                                $memberRegisterInfoSaveData['totalexperiencevalue'] = array('exp', '`totalexperiencevalue`-'.$changeIntNum.'');
                            }else{
                                $memberRegisterInfoSaveData['totalexperiencevalue'] = 0 ;
                            }
                            if($nowMemberRegisterInfo['totalintegration']>$changeIntNum){
                                $memberRegisterInfoSaveData['totalintegration'] = array('exp', '`totalintegration`-'.$changeIntNum.'');
                            }else{
                                $memberRegisterInfoSaveData['totalintegration'] = 0 ;
                            }
                            $integralData = $data;
                            $changeIntReturn = M('member_integral')->where(array('companyid'=>$companyid,'borderid'=>$borderid))->save($integralData);
                        }else {
                            $changeIntReturn = 1;
                        }
                        // tag 统计  会员 消费频次  累计消费金额
                        if($mid&&$changeSpendingNum>0){
                            $memberRegisterInfo = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->field('spendingfrequencytag,totalspendingtag,totalspending,totalspendingfrequency,lastspendingtime,howlongspendingtag')->find();
                            // 统计修改消费频次标签
                            $totalspendingfrequency = $memberRegisterInfoSaveData['totalspendingfrequency'] = $memberRegisterInfo['totalspendingfrequency']-1;
                            if($totalspendingfrequency==1){
                                $memberRegisterInfoSaveData['spendingfrequencytag'] = '1';
                            }elseif ($totalspendingfrequency>1 && $totalspendingfrequency <11){
                                $memberRegisterInfoSaveData['spendingfrequencytag'] = '2';
                            }elseif ($totalspendingfrequency>11 && $totalspendingfrequency <51){
                                $memberRegisterInfoSaveData['spendingfrequencytag'] = '3';
                            }elseif ($totalspendingfrequency>50){
                                $memberRegisterInfoSaveData['spendingfrequencytag'] = '4';
                            }
                            if($memberRegisterInfo['spendingfrequencytag'] != $memberRegisterInfoSaveData['spendingfrequencytag']){
	                            $this->memberTagCount($companyid, array(array('name'=>'spendingfrequency','before'=>$memberRegisterInfo['spendingfrequencytag'],'after'=>$memberRegisterInfoSaveData['spendingfrequencytag'])));
                            }
                            // 统计累计消费金额标签
                            $totalspending = $memberRegisterInfoSaveData['totalspending'] = $memberRegisterInfo['totalspending']-$changeSpendingNum;
                            if($totalspending>0 && $totalspending<=201){
                                $memberRegisterInfoSaveData['totalspendingtag'] = '1';
                            }elseif($totalspending>201 && $totalspending<=501){
                                $memberRegisterInfoSaveData['totalspendingtag'] = '2';
                            }elseif($totalspending>501 && $totalspending<=1001){
                                $memberRegisterInfoSaveData['totalspendingtag'] = '3';
                            }elseif($totalspending>1001 && $totalspending<=3001){
                                $memberRegisterInfoSaveData['totalspendingtag'] = '4';
                            }elseif($totalspending>3001 && $totalspending<=5001){
                                $memberRegisterInfoSaveData['totalspendingtag'] = '5';
                            }elseif($totalspending>5001 && $totalspending<=10001){
                                $memberRegisterInfoSaveData['totalspendingtag'] = '6';
                            }elseif($totalspending>10001){
                                $memberRegisterInfoSaveData['totalspendingtag'] = '7';
                            }
                            if($memberRegisterInfo['totalspendingtag'] != $memberRegisterInfoSaveData['totalspendingtag']){
	                            $this->memberTagCount($companyid, array(array('name'=>'totalspending','before'=>$memberRegisterInfo['totalspendingtag'],'after'=>$memberRegisterInfoSaveData['totalspendingtag'])));
                            }
                            // 统计多久未消费标签  获取最后一次有效的消费 交易记录时间
                            $lastSpendingInfo = M('member_spending')->where(array('companyid'=>$companyid,'mid'=>$mid,'status'=>'1','_string'=>"type = '106' OR type = '107' OR type = '110' OR type = '111' OR (type = '112' AND rechargetype = '2') OR (type = '112' AND rechargetype = '5') OR type = '113' OR type = '118' OR type = '119' OR type = '120' OR type = '114' OR type = '115' OR type = '121'"))->order('createtime DESC')->field('createtime')->find();
                            $beforeDayTime14 = strtotime('-14 day');
                            $beforeDayTime30 = strtotime('-30 day');
                            $beforeDayTime60 = strtotime('-60 day');
                            $beforeDayTime90 = strtotime('-90 day');
                            $beforeDayTime182 = strtotime('-182 day');
                            $beforeDayTime365 = strtotime('-365 day');
                            $memberRegisterInfoSaveData['lastspendingtime'] = $lastSpendingInfo['createtime'];
                            if($memberRegisterInfoSaveData['lastspendingtime']>0){
                                if($memberRegisterInfoSaveData['lastspendingtime']>=$beforeDayTime14){
                                    $memberRegisterInfoSaveData['howlongspendingtag'] = '11'; // 两周内已消费，每晚定时任务还会重新统计
                                }elseif ($memberRegisterInfoSaveData['lastspendingtime']<$beforeDayTime14 && $memberRegisterInfoSaveData['lastspendingtime']>=$beforeDayTime30){
                                    $memberRegisterInfoSaveData['howlongspendingtag'] = '1'; // 两周内未消费，每晚定时任务还会重新统计
                                }elseif ($memberRegisterInfoSaveData['lastspendingtime']<$beforeDayTime30 && $memberRegisterInfoSaveData['lastspendingtime']>=$beforeDayTime60){
                                    $memberRegisterInfoSaveData['howlongspendingtag'] = '2'; // 一个月内未消费，每晚定时任务还会重新统计
                                }elseif ($memberRegisterInfoSaveData['lastspendingtime']<$beforeDayTime60 && $memberRegisterInfoSaveData['lastspendingtime']>=$beforeDayTime90){
                                    $memberRegisterInfoSaveData['howlongspendingtag'] = '3'; // 两个月内未消费，每晚定时任务还会重新统计
                                }elseif ($memberRegisterInfoSaveData['lastspendingtime']<$beforeDayTime90 && $memberRegisterInfoSaveData['lastspendingtime']>=$beforeDayTime182){
                                    $memberRegisterInfoSaveData['howlongspendingtag'] = '4'; // 三个月内未消费，每晚定时任务还会重新统计
                                }elseif ($memberRegisterInfoSaveData['lastspendingtime']<$beforeDayTime182 && $memberRegisterInfoSaveData['lastspendingtime']>=$beforeDayTime365){
                                    $memberRegisterInfoSaveData['howlongspendingtag'] = '5'; // 半年内未消费，每晚定时任务还会重新统计
                                }elseif ($memberRegisterInfoSaveData['lastspendingtime']<$beforeDayTime365){
                                    $memberRegisterInfoSaveData['howlongspendingtag'] = '6'; // 一年内未消费，每晚定时任务还会重新统计
                                }
                            }else{
                                $memberRegisterInfoSaveData['howlongspendingtag'] = '0'; // 从未消费，每晚定时任务还会重新统计
                            }
                            if($memberRegisterInfo['howlongspendingtag'] != $memberRegisterInfoSaveData['howlongspendingtag']){
	                            $this->memberTagCount($companyid, array(array('name'=>'howlongspending','before'=>$memberRegisterInfo['howlongspendingtag'],'after'=>$memberRegisterInfoSaveData['howlongspendingtag'])));
                            }
                        }
                        $spendingData = $data;
                        $changeSpendingReturn = M('member_spending')->where(array('companyid'=>$companyid,'borderid'=>$borderid))->save($spendingData);
                    }
                }
            }elseif ($type == '101' || $type == '102' || $type == '103' || $type == '104' || $type == '105' || $type == '108' || $type == '109' || $type == '116' || $type == '117' || $type == '201' || $type == '202' || $type == '203' || $type == '204' || $type == '205'){
                // 单独撤销积分先不做
            }
        }else{
            // 根据支付方式 生成交易号
            if($paytype == '1'){
                $changeSpendingOrderid = $this->newOrderID(3,'W',$companyid);
            }elseif($paytype == '2'){
                $changeSpendingOrderid = $this->newOrderID(3,'A',$companyid);
            }elseif($paytype == '3'){
                $changeSpendingOrderid = $this->newOrderID(3,'C',$companyid);
            }elseif($paytype == '4'){
                $changeSpendingOrderid = $this->newOrderID(3,'S',$companyid);
            }elseif($paytype == '5'){
                $changeSpendingOrderid = $this->newOrderID(3,'D',$companyid);
            }
            // 全部需要 计算+添加
            if($type == '101' || $type == '102' || $type == '103' || $type == '104' || $type == '105' || $type == '106' || $type == '107' || $type == '108' || $type == '109' || $type == '110' || $type == '111' || $type == '112' || $type == '113' || $type == '114' || $type == '115' || $type == '116' || $type == '117' || $type == '118' || $type == '119' || $type == '120' || $type == '121' || $type=='122' || $type=='123' || $type=='124' || $type=='125' || $type=='126' || $type=='127' || $type == '301'){
                $status = '1'; //对积分做加
                $isConsume = 2; // 是否是消费；1：是；2：否；默认：2；  
                // 完善会员资料:101;注册:102;点评奖励:103;手动加积分:104;风助手手机收银:105;闪惠支付:106;风助手手机收银:107;微信关注:108;首次消费:109;eshop支付:110;拉卡拉手机收银:111;充值:112;手机预订:113;微信外卖：114；手机点单：115；风助手手动加积分：116；手机预订奖励：117；预订(SPA):118;预订(餐饮):119;预订(酒店):120;付费内容商店(年卡+单品):121;
                $changeIntOrderid = $this->newOrderID(3,'H',$companyid);// 生成交易号
                if($type=='101'){
                    if($integralSet['perfectreginfoisopen']=='1'){
                        // 完善资料送积分
                        $changeIntNum = $integralSet['perfectreginfoint'];
                        $memberRegisterInfoSaveData['issend100expint'] = 1;
                    }
                }elseif ($type=='102'){
                    if($integralSet['createcardisopen']=='1'){
                        // 注册送积分
                        $changeIntNum = $integralSet['createcardint'];
                    }
                }elseif ($type=='103'){
                    if($integralSet['dianpingisopen']=='1'){
                        // 点评送积分
                        $changeIntNum = $integralSet['dianpingint'];
                    }
                }elseif ($type=='104'){
                    // 手动加积分
                    $changeIntNum = $changeNum;
                }elseif ($type=='105'){
                    if($integralSet['yaoqiandaoisopen']=='1'){
                        // 摇摇签到
                        $changeIntNum = $integralSet['yaoqiandaoint'];
                    }
                }elseif ($type=='106'){
                    // 闪惠支付
                    $changeSpendingNum = $changeNum;
                    if($integralSet['shanhuipayisopen']=='1' && $changeNum > 0){
                        $changeIntNum = $changeNum/$integralSet['shanhuipayconversion'];
                    }
                    $isrecharge = 1;  // 减余额
                    $isConsume = 1;
                }elseif ($type=='107'){
                    // 风助手手机收银
                    $changeSpendingNum = $changeNum;
                    if($integralSet['windhelperpayisopen']=='1' && $changeNum > 0){
                        $changeIntNum = $changeNum/$integralSet['windhelperpayconversion'];
                    }
                    $isrecharge = 1;  // 减余额
                    $isConsume = 1;
                }elseif ($type=='108'){
                    if($integralSet['wechatsubscribeisopen']=='1'){
                        // 微信关注
                        $changeIntNum = $integralSet['wechatsubscribeint'];
                        $memberRegisterInfoSaveData['issendwechatsubscribeint'] = '1'; // 标识已经赠送过关注赠积分
                    }
                }elseif ($type=='109'){
                    if($integralSet['firstconsumptionisopen']=='1'){
                        // 首次消费
                        $changeIntNum = $integralSet['firstconsumptionint'];
                    }
                }elseif ($type=='110'){
                    // eshop 支付
                    $changeSpendingNum = $changeNum;
                    if($integralSet['eshoppayisopen']=='1' && $changeNum > 0){
                        $changeIntNum = $changeNum/$integralSet['eshoppayconversion'];
                    }
                    $isrecharge = 1;  // 减余额
                    $isConsume = 1;
                }elseif ($type=='111'){
                    // 拉卡拉手机收银
                    $changeSpendingNum = $changeNum;
                    if($integralSet['lakalapayisopen']=='1' && $changeNum > 0){
                        $changeIntNum = $changeNum/$integralSet['lakalapayconversion'];
                    }
                    $isrecharge = 1;  // 减余额
                    $isConsume = 1;
                }elseif ($type=='112'){
                    // 充值
                    $changeSpendingOrderid = $this->newOrderID(3,'B',$companyid);
                    if($rechargetype == '1' || $rechargetype == '2'|| $rechargetype == '5'){
                        // 后台储值充值:1;会员WAP自助充值:2;风助手储值充值:5;
                        $changeSpendingNum = $changeNum;
                        $changeSpendingNum2 = $changeNum2;
                    }elseif ($rechargetype=='4'){
                        // 红包
                        $changeSpendingNum2 = $changeNum2;
                    }
                    $isrecharge = 2;  // 加余额
                }elseif ($type=='113'||$type=='118'||$type=='119'||$type=='120'){
                    // 注意：预订类积分获取规则相同 ，113:预订(通用版);118:预订(SPA);119:预订(餐饮);120:预订(酒店);
                    $changeSpendingNum = $changeNum;
                    if($integralSet['mobilebookpayisopen']=='1' && $changeNum > 0){
                        $changeIntNum = $changeNum/$integralSet['mobilebookpayconversion'];
                    }
                    $isrecharge = 1;  // 减余额
                    $isConsume = 1;
                }elseif ($type=='114'){
                    // 微信外卖
                    $changeSpendingNum = $changeNum;
                    if($integralSet['takeoutisopen']=='1' && $changeNum > 0){
                        $changeIntNum = $changeNum/$integralSet['takeoutconversion'];
                    }
                    $isrecharge = 1;  // 减少账户余额
                    $isConsume = 1;
                }elseif ($type=='115'){
                    // 手机点单
                    $changeSpendingNum = $changeNum;
                    if($integralSet['mobilephoneorderisopen']=='1' && $changeNum > 0){
                        $changeIntNum = $changeNum/$integralSet['mobilephoneorderconversion'];
                    }
                    $isrecharge = 1;  // 减少账户余额
                    $isConsume = 1;
                }elseif ($type=='116'){
                    // 风助手手动加积分
                    $changeIntNum = $changeNum;
                }elseif ($type=='117'||$type=='122'||$type=='123'||$type=='124'){
                    // 预订(通用版)，预订(SPA版)，预订(餐饮版)，预订(酒店版)预订奖励
                    if($integralSet['mobilebookisopen']=='1'){
                        $changeIntNum = $integralSet['mobilebookint'];
                    }
                }elseif($type == '301'){
                    // 后台储值消费
                    $changeSpendingNum = $changeNum;
                    if($integralSet['storedvalueisopen']=='1' && $changeNum > 0){
                        $changeIntNum = $changeNum/$integralSet['storedvalueconversion'];
                    }
                    $isrecharge = 1; // 减少账户余额
                    $isConsume = 1;
                }elseif($type == '121'){
                    // 付费内容商店(年卡+单品)
                    $changeSpendingNum = $changeNum;
                    if($integralSet['paidcontentshopisopen']=='1' && $changeNum > 0){
                        $changeIntNum = $changeNum/$integralSet['paidcontentshopversion'];
                    }
                    $isrecharge = 1; // 减少账户余额
                    $isConsume = 1;
                }elseif($type == '125'){
                    // 储值快捷收银(风助手)
                    $changeSpendingNum = $changeNum;
                    if($integralSet['rechargequickpayisopen']=='1' && $changeNum > 0){
                        $changeIntNum = $changeNum/$integralSet['rechargequickpayversion'];
                    }
                    $isrecharge = 1; // 减少账户余额
                    $isConsume = 1;
                }elseif($type == '126'){
                    // 储值快捷收银(拉卡拉)
                    $changeSpendingNum = $changeNum;
                    if($integralSet['rechargequickpayisopen']=='1' && $changeNum > 0){
                        $changeIntNum = $changeNum/$integralSet['rechargequickpayversion'];
                    }
                    $isrecharge = 1; // 减少账户余额
                    $isConsume = 1;
                }elseif($type == '127'){
                    // 声悦传情
                    $changeSpendingNum = $changeNum;
                    if($integralSet['soundjoyisopen']=='1' && $changeNum > 0){
                        $changeIntNum = $changeNum/$integralSet['soundjoyversion'];
                    }
                    $isrecharge = 1; // 减少账户余额
                    $isConsume = 1;
                }
            	if($isConsume == 1){
	            	//根据不同会员等级设置送对应多倍积分
	                if($integralSet['integralmultipleisopen']=='1' && $changeIntNum > 0 && $mid){
	                	//查询会员等级和该等级对应的倍数
	                	$memberRankid = M('member_card_info')->where(array('companyid'=>$companyid,'mid'=>$mid))->getField('rankid');
	                	$integralmultiple = json_decode($integralSet['integralmultiple'],true);
	                	if($memberRankid && $integralmultiple){
	                		$changeIntNum = $changeIntNum*$integralmultiple[$memberRankid]['val'];
	                	}
	                }
	                //根据会员生日送多倍积分
	                if($integralSet['birthdayisopen']=='1' && $changeIntNum > 0 && $mid){
	                	//查询会员生日对应的倍数
	                	$memberBirthday = M('member_register_info')->where(array('companyid'=>$companyid,'id'=>$mid))->getField('birthday');
	                	if((substr($memberBirthday,-5) == format_time($time,'md')) && $integralSet['birthdaymultiple']){
	                		$changeIntNum = $changeIntNum*$integralSet['birthdaymultiple'];
	                	}
	                }
                }
                if($issendint == '2'){
                    // 如果标识不赠送积分，重置积分计算字段
                    $changeIntNum = 0;
                }
                if($changeIntNum){
                    $changeIntNum =floor($changeIntNum);
                    if($changeIntNum > 0){
                        // 积分清零规则处理
                        if($memberIntIsAutoClear == '1'){
                            $memberRegisterInfoSaveData['nextyearcanuseintegral'] = array('exp', '`nextyearcanuseintegral`+'.$changeIntNum.'');
                        }else{
                            $memberRegisterInfoSaveData['thisyearcanuseintegral'] = array('exp', '`thisyearcanuseintegral`+'.$changeIntNum.'');
                            $memberRegisterInfoSaveData['nextyearcanuseintegral'] = array('exp', '`nextyearcanuseintegral`+'.$changeIntNum.'');
                        }
                        $memberRegisterInfoSaveData['totalexperiencevalue'] = array('exp', '`totalexperiencevalue`+'.$changeIntNum.'');
                        $memberRegisterInfoSaveData['totalintegration'] = array('exp', '`totalintegration`+'.$changeIntNum.'');
                    }
                }
            }elseif ($type == '201' || $type == '202' || $type == '203' || $type == '204'  || $type == '205' ){
                $status = '2';   //对积分做减
                // 手动扣除积分:201;到期自动清零:202;会员WAP积分换储值:203;积分商城:204;风助手手动减积分：205；
                $changeIntOrderid = $this->newOrderID(3,'G',$companyid);// 生成交易号
                if ($type=='201'){
                    // 手动扣除积分
                    $changeIntNum = $changeNum;
                }elseif ($type=='202'){
                    // 到期自动清零
                    //$changeIntNum = $integralSet['mobilebookint']*$changeNum;
                }elseif ($type=='203'){
                    // 会员WAP积分换储值
                    $isrecharge = 2;  // 增加账户余额
                    if($integralSet['integralconvertmoney']=='1' && $changeNum > 0){
                        // 换算获得的累加 金额
                        $changeSpendingOrderid = $this->newOrderID(3,'B',$companyid);
                        $changeSpendingNum2 = $changeNum/$integralSet['integralconvertmoneyconversion'];
                    }
                    $changeIntNum = $changeNum;
                }elseif ($type=='204'){
                    // 积分商城
                    $changeIntNum = $changeNum;
                }elseif ($type=='205'){
                    // 风助手手动减积分
                    $changeIntNum = $changeNum;
                }
                if($changeIntNum){
                    $changeIntNum =floor($changeIntNum);
                    if($changeIntNum > 0){
                        // 积分清零规则处理
                        /*
                                                             积分清零规则
                                                            最新规则如下
                        1.会员表新增两个字段：有效期为次年的可用积分  nextyearcanuseintegral ，有效期为当年的可用积分 thisyearcanuseintegral；
                                                             关于积分清零规则总共涉及三个字段：当前总可用积分，有效期为次年的可用积分，有效期为当年的可用积分；
                        2.这三个字段的数值改变规则如下：
                                                            开启清零规则：
                        1.消耗积分：消耗积分先在有效期为当年的可用积分中减掉，如果有效期为当年的可用积分大于当前消耗积分时，有效期为次年的可用积分不做改变；如果有效期为当年的可用积分小于当前消耗积分时不足扣除积分需要在有效期为次年的可用积分中进行扣除；以上两种情况需要同时在当前总可用积分中进行扣除。
                        2.获取积分：当前总可用积分、有效期为次年的可用积分同时增加，有效期为当年的可用积分不做改变。
                                                            未开启清零规则：当前总可用积分、有效期为次年的可用积分、有效期为当年的可用积分三个字段同加同减。
                        3.每年****-12-31 23:59:59 定时任务执行规则
                                                            开启清零规则：清空有效期为当年的可用积分，并把有效期为次年的可用积分复制填充至有效期为当年的可用积分的位置，然后将有效期为次年的可用积分分置为零。
                                                            未开启清零规则：仅仅将有效期为次年的可用积分值置为零。
                                                            此规则执行前，需要初始化数据当前总可用积分，有效期为次年的可用积分，有效期为当年的可用积分；
                                                            例如当前时间为2016
                                                            有效期为次年的可用积分:整个2016年获得所有积分(如果$a 小于零，需要在这里减掉)；
                                                            有效期为当年的可用积分:$a = 2016年前获得的所有积分-2016年前消耗的所有积分-2016年消耗的积分；
                        */
                        $nowMemberCanUseInt = M('member_register_info')->where(array('companyid'=>$companyid,'id'=>$mid))->field('totalexperiencevalue,thisyearcanuseintegral,nextyearcanuseintegral')->find();
                        if($memberIntIsAutoClear == '1'){
                            if($nowMemberCanUseInt['thisyearcanuseintegral']>=$changeIntNum){
                                $memberRegisterInfoSaveData['thisyearcanuseintegral'] = array('exp', '`thisyearcanuseintegral`-'.$changeIntNum.'');
                            }else{
                                $memberRegisterInfoSaveData['thisyearcanuseintegral'] = '0';
                                $nextyearcanuseintegralChangeInt = $changeIntNum - $nowMemberCanUseInt['thisyearcanuseintegral'];
                                if($nextyearcanuseintegralChangeInt >= $nowMemberCanUseInt['nextyearcanuseintegral']){
                                    $memberRegisterInfoSaveData['nextyearcanuseintegral'] = '0';
                                }else{
                                    $memberRegisterInfoSaveData['nextyearcanuseintegral'] = array('exp', '`nextyearcanuseintegral`-'.$nextyearcanuseintegralChangeInt.'');
                                }
                            }
                        }else{
                            if($nowMemberCanUseInt['thisyearcanuseintegral']>=$changeIntNum){
                                $memberRegisterInfoSaveData['thisyearcanuseintegral'] = array('exp', '`thisyearcanuseintegral`-'.$changeIntNum.'');
                            }else{
                                $memberRegisterInfoSaveData['thisyearcanuseintegral'] = '0';
                            }
                            if($nowMemberCanUseInt['nextyearcanuseintegral']>=$changeIntNum){
                                $memberRegisterInfoSaveData['nextyearcanuseintegral'] = array('exp', '`nextyearcanuseintegral`-'.$changeIntNum.'');
                            }else{
                                $memberRegisterInfoSaveData['nextyearcanuseintegral'] = '0';
                            }
                        }
                        $memberRegisterInfoSaveData['totalintegration'] = array('exp', '`totalintegration`-'.$changeIntNum.'');
                    }
                }
            }
            // 关联各个收单口订单中的交易号
            if($type == '110' && $linkOrderId){
                $linkOrderIdSaveReturn = M('mall_order_info')->where(array('companyid'=>$companyid,'orderid'=>$linkOrderId,'mid'=>$mid))->save(array('borderid'=>$changeSpendingOrderid,'updatetime'=>$time));
            }else{
                $linkOrderIdSaveReturn = 1;
            }
            // 归总，整理 储值字段 改变的消费记录,$changeSpendingNum2 数据产生情况：1.充值(后台储值充值:1;会员WAP自助充值:2;积分兑换储值:3;红包:4;风助手储值充值:5;)；2：各个收单口的储值支付(包含后台储值支付)
            if($paytype=='4'){
                $changeSpendingNum2 = $changeSpendingNum;
            }
            if($isrecharge == 2 && $changeSpendingNum2>0 && $mid){
                // 增加账户余额
                $memberRegisterInfoSaveData['accountbalance'] = array('exp', '`accountbalance`+'.$changeSpendingNum2.'');
            }elseif($isrecharge == 1 && $changeSpendingNum2>0 && $mid){
                // 减少账户余额
                $memberRegisterInfoSaveData['accountbalance'] = array('exp', '`accountbalance`-'.$changeSpendingNum2.'');
            }
            if($changeSpendingNum2>0){
                if($type == '112' || $type == '203'){
                    // 累计充值金额 ，充值进会员账户的金额 包含：后台储值充值，会员WAP自助充值，红包充值，会员WAP积分换储值，风助手储值充值；
                    $companySaveData['totalrecharge'] = array('exp', '`totalrecharge`+'.$changeSpendingNum2.'');
                }
                if ($rechargetype == '4'){
                    // 储值卡累计红包充值金额；也就是卡券中心的 红包累计投放金额
                    $companySaveData['totalvouchertype4sendamount'] = array('exp', '`totalvouchertype4sendamount`+'.$changeSpendingNum2.'');
                }
            }
            if($changeSpendingNum>0){
                if(($type !='112' && $paytype !='4') || ($type=='112' && ($rechargetype =='2' || $rechargetype =='5'))){
                    // 累计总收入 包含所有收单口的消费，例如:eshop;充值(在线+风助手);闪惠;微信外卖;预订(通用版);手机点单;风助手;拉卡拉;预订(SPA);预订(餐饮);预订(酒店);付费内容商店(年卡+单品);声悦传情;支付方式排除储值支付
                    $companySaveData['totalincome'] = array('exp', '`totalincome`+'.$changeSpendingNum.'');
                }
                // 累计总收入 包含所有收单口的消费，例如:eshop;充值(在线+风助手);闪惠;微信外卖;预订(通用版);手机点单;风助手;拉卡拉;预订(SPA);预订(餐饮);预订(酒店);付费内容商店(年卡+单品);储值快捷收银(风助手);储值快捷收银(拉卡拉);声悦传情;支付方式不排除储值支付
                $companyShopsSaveData['shoptotalincome'] = array('exp', '`shoptotalincome`+'.$changeSpendingNum.'');
                //  按照收单口算累积收入
                if($type == '106'){
                    $companySaveData['totalshanhuiincome'] = array('exp', '`totalshanhuiincome`+'.$changeSpendingNum.'');
                }elseif($type == '107'){
                    $companySaveData['totalhelperincome'] = array('exp', '`totalhelperincome`+'.$changeSpendingNum.'');
                }elseif($type == '110' ){
                    $companySaveData['totaleshopincome'] = array('exp', '`totaleshopincome`+'.$changeSpendingNum.'');
                }elseif($type == '111'){
                    $companySaveData['totallakalaincome'] = array('exp', '`totallakalaincome`+'.$changeSpendingNum.'');
                }elseif($type == '112'){
                    if($rechargetype == '2'){
                        // 在线充值累计;仅仅包含会员WAP自助充值所有累计支付金额统计，不是充值进会员账户金额统计；
                        $companySaveData['totalsellrechargeincome'] = array('exp', '`totalsellrechargeincome`+'.$changeSpendingNum.'');
                    }
                    if($rechargetype == '1' || $rechargetype == '2' || $rechargetype == '5'){
                        // 多渠道累计储值卡售出金额累计;仅仅包含后台储值充值,会员WAP自助充值,风助手储值充值 所有累计支付金额统计，不是充值进会员账户金额统计；
                        $companySaveData['totalallsellrechargeincome'] = array('exp', '`totalallsellrechargeincome`+'.$changeSpendingNum.'');
                    }
                }elseif($type == '113'){
                    // 预订(通用版)
                    $companySaveData['totalmobilebookincome'] = array('exp', '`totalmobilebookincome`+'.$changeSpendingNum.'');
                }elseif($type == '114'){
                    $companySaveData['totaltakeoutincome'] = array('exp', '`totaltakeoutincome`+'.$changeSpendingNum.'');
                }elseif($type == '115'){
                    $companySaveData['totalmobilephoneorderincome'] = array('exp', '`totalmobilephoneorderincome`+'.$changeSpendingNum.'');
                }elseif($type == '118'){
                    // 预订(SPA)
                    $companySaveData['totalmobilebookspaincome'] = array('exp', '`totalmobilebookspaincome`+'.$changeSpendingNum.'');
                }elseif($type == '119'){
                    // 预订(餐饮)
                    $companySaveData['totalmobilebookrestaurantincome'] = array('exp', '`totalmobilebookrestaurantincome`+'.$changeSpendingNum.'');
                }elseif($type == '120'){
                    // 预订(酒店)
                    $companySaveData['totalmobilebookhotelincome'] = array('exp', '`totalmobilebookhotelincome`+'.$changeSpendingNum.'');
                }elseif($type == '121'){
                    // 付费内容商店
                    $companySaveData['totalpaidcontentshopincome'] = array('exp', '`totalpaidcontentshopincome`+'.$changeSpendingNum.'');
                }elseif($type == '125'){
                    // 储值快捷收银(风助手)
                    $companySaveData['totalrechargeqphelperincome'] = array('exp', '`totalrechargeqphelperincome`+'.$changeSpendingNum.'');
                }elseif($type == '126'){
                    // 储值快捷收银(风助手)
                    $companySaveData['totalrechargeqplakalaincome'] = array('exp', '`totalrechargeqplakalaincome`+'.$changeSpendingNum.'');
                }elseif($type == '127'){
                    // 声悦传情
                    $companySaveData['totalsoundjoyincome'] = array('exp', '`totalsoundjoyincome`+'.$changeSpendingNum.'');
                }
                // 按照支付方式算累积收入
                if($paytype == '1'){
                    $companySaveData['totalwechatpayincome'] = array('exp', '`totalwechatpayincome`+'.$changeSpendingNum.'');
                }elseif($paytype == '2'){
                    $companySaveData['totalalipayincome'] = array('exp', '`totalalipayincome`+'.$changeSpendingNum.'');
                }elseif($paytype == '3'){
                    $companySaveData['totalmoneypayincome'] = array('exp', '`totalmoneypayincome`+'.$changeSpendingNum.'');
                }elseif($paytype == '4'){
                    $companySaveData['totalrechargepayincome'] = array('exp', '`totalrechargepayincome`+'.$changeSpendingNum.'');
                }elseif($paytype == '5'){
                    $companySaveData['totalbankcardpayincome'] = array('exp', '`totalbankcardpayincome`+'.$changeSpendingNum.'');
                }
            }
            // 公司累计总收入统计
            if($companySaveData){
                $companySaveData['updatetime'] = $time;
                $companySaveReturn = M('company')->where(array('id'=>$companyid))->save($companySaveData);
            }else{
                $companySaveReturn = 1;
            }
            // 门店累计总收入统计
            if($companyShopsSaveData && $shopInfo['id']>0){
                $companyShopsSaveData['updatetime'] = $time;
                $companyShopsSaveReturn = M('company_shops')->where(array('id'=>$shopInfo['id']))->save($companyShopsSaveData);
            }else{
                $companyShopsSaveReturn = 1;
            }
            if($changeIntNum>0 && $mid){
                /* if($type == '113' || $type == '118' || $type == '119' || $type == '120'){
                    // 预订类 积分获取方式共用
                    $intType = '113';
                }else{
                    $intType = $type;
                } */
                $integralData = $data;
                $integralData['orderid'] = $changeIntOrderid;
                $integralData['type'] = $type;
                $integralData['userid'] = $userInfo['id'];
                $integralData['username'] = $userInfo['truename']?$userInfo['truename']:$userInfo['username'];
                $integralData['shopid'] = $shopInfo['id'];
                $integralData['shopname'] = $shopInfo['shopname'];
                $integralData['integralnum'] = $changeIntNum;
                $changeIntReturn = M('member_integral')->add($integralData);
            }else {
                $changeIntReturn = 1;
            }
            if($changeSpendingOrderid){
                // tag 统计  会员 消费频次  累计消费金额
                if($mid&&$changeSpendingNum>0){
                    $memberRegisterInfo = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->field('spendingfrequencytag,totalspendingtag,totalspending,totalspendingfrequency,lastspendingtime,howlongspendingtag')->find();
                    // 重新获取年消费频次，按照  -1年计算
                    $memberRegisterInfo['totalspendingfrequency'] = M('member_spending')->where(array('companyid'=>$companyid,'mid'=>$mid,'createtime'=>array('egt',strtotime('-1 year')),'status'=>'1','_string'=>"type = '106' OR type = '107' OR type = '110' OR type = '111' OR (type = '112' AND rechargetype = '2') OR (type = '112' AND rechargetype = '5') OR type = '113' OR type = '118' OR type = '119' OR type = '120' OR type = '114' OR type = '115' OR type = '121'"))->count();
                    // 统计修改消费频次标签
                    $totalspendingfrequency = $memberRegisterInfoSaveData['totalspendingfrequency'] = $memberRegisterInfo['totalspendingfrequency']+1;
                    if($totalspendingfrequency==1){
                        $memberRegisterInfoSaveData['spendingfrequencytag'] = '1';
                    }elseif ($totalspendingfrequency>1 && $totalspendingfrequency <11){
                        $memberRegisterInfoSaveData['spendingfrequencytag'] = '2';
                    }elseif ($totalspendingfrequency>11 && $totalspendingfrequency <51){
                        $memberRegisterInfoSaveData['spendingfrequencytag'] = '3';
                    }elseif ($totalspendingfrequency>50){
                        $memberRegisterInfoSaveData['spendingfrequencytag'] = '4';
                    }
                    if($memberRegisterInfo['spendingfrequencytag'] != $memberRegisterInfoSaveData['spendingfrequencytag']){
	                    $this->memberTagCount($companyid, array(array('name'=>'spendingfrequency','before'=>$memberRegisterInfo['spendingfrequencytag'],'after'=>$memberRegisterInfoSaveData['spendingfrequencytag'])));
                    }
                    // 统计累计消费金额标签
                    $totalspending = $memberRegisterInfoSaveData['totalspending'] = $memberRegisterInfo['totalspending']+$changeSpendingNum;
                    if($totalspending>0 && $totalspending<=201){
                        $memberRegisterInfoSaveData['totalspendingtag'] = '1';
                    }elseif($totalspending>201 && $totalspending<=501){
                        $memberRegisterInfoSaveData['totalspendingtag'] = '2';
                    }elseif($totalspending>501 && $totalspending<=1001){
                        $memberRegisterInfoSaveData['totalspendingtag'] = '3';
                    }elseif($totalspending>1001 && $totalspending<=3001){
                        $memberRegisterInfoSaveData['totalspendingtag'] = '4';
                    }elseif($totalspending>3001 && $totalspending<=5001){
                        $memberRegisterInfoSaveData['totalspendingtag'] = '5';
                    }elseif($totalspending>5001 && $totalspending<=10001){
                        $memberRegisterInfoSaveData['totalspendingtag'] = '6';
                    }elseif($totalspending>10001){
                        $memberRegisterInfoSaveData['totalspendingtag'] = '7';
                    }
                    if($memberRegisterInfo['totalspendingtag'] != $memberRegisterInfoSaveData['totalspendingtag']){
	                    $this->memberTagCount($companyid, array(array('name'=>'totalspending','before'=>$memberRegisterInfo['totalspendingtag'],'after'=>$memberRegisterInfoSaveData['totalspendingtag'])));
                    }
                    // 统计多久未消费标签
                    $memberRegisterInfoSaveData['lastspendingtime'] = $time;
                    $memberRegisterInfoSaveData['howlongspendingtag'] = '11'; // 两周内已消费，每晚定时任务还会重新统计
                    if($memberRegisterInfo['howlongspendingtag'] != $memberRegisterInfoSaveData['howlongspendingtag']){
	                    $this->memberTagCount($companyid, array(array('name'=>'howlongspending','before'=>$memberRegisterInfo['howlongspendingtag'],'after'=>$memberRegisterInfoSaveData['howlongspendingtag'])));
                    }
                }
                $spendingData = $data;
                $spendingData['orderid'] = $changeSpendingOrderid;
                $spendingData['type'] = $type;
                $spendingData['userid'] = $userInfo['id'];
                $spendingData['username'] = $userInfo['truename']?$userInfo['truename']:$userInfo['username'];
                $spendingData['shopid'] = $shopInfo['id'];
                $spendingData['shopname'] = $shopInfo['shopname'];
                $spendingData['spendingamount'] = $changeSpendingNum;
                $spendingData['spendingamount2'] = $changeSpendingNum2;
                $changeSpendingReturn = M('member_spending')->add($spendingData);
            }else{
                $changeSpendingReturn = 1;
            }
        }
        if($mid){
            // 储存会员信息的修改
            if($memberRegisterInfoSaveData){
                $memberRegisterInfoSaveData['updatetime'] = $time;
                $memberRegisterInfoSaveReturn = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->save($memberRegisterInfoSaveData);
            }else{
                $memberRegisterInfoSaveReturn = 1;
            }
            $nowTotalintegral = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->getField('totalintegration');
            $nowAccountbalance = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->getField('accountbalance');
        }else{
            $memberRegisterInfoSaveReturn = $nowTotalintegral = $nowAccountbalance = 1;
        }
        if($linkOrderIdSaveReturn&&$companySaveReturn&&$changeIntReturn&&$nowTotalintegral>=0&&$changeSpendingReturn&&$memberRegisterInfoSaveReturn&&$nowAccountbalance>=0&&$companyShopsSaveReturn){
            M()->commit();
            if($mid){
                // 发送微信消息模板
                $sendOpenid = M('member_wechat_info')->where(array('companyid'=>$companyid,'mid'=>$mid))->getField('openid');
                if($sendOpenid){
                    // 发送积分改变模板
                    if($integralData['integralnum'] && !$borderid){
                        $optionType = $integralData['type'];
                        $changeIntNum = $integralData['integralnum'];
                        $sendDesc = $this->changeMemberIntegralType[$optionType];
                        if($status == '1'){
                            $this->WeChatTemplateMessageSend('3', $sendOpenid, $companyid, '', '', array(format_time($time,'ymdhis'), $changeIntNum, $sendDesc, $nowTotalintegral), '');
                        }elseif ($status == '2'){
                            $this->WeChatTemplateMessageSend('4', $sendOpenid, $companyid, '', '', array($sendDesc,$changeIntNum,$nowTotalintegral), '');
                        }
                    }elseif ($borderid && $memberIntegralInfo['integralnum']){
                        if($status=='2'){
                            if($type=='110'){
                                $this->WeChatTemplateMessageSend('41', $sendOpenid, $companyid, '', '', array('积分扣除','积分通知'), array('eshop支付退款',$memberIntegralInfo['integralnum'].'分'));
                            }elseif ($type=='114'){
                                $this->WeChatTemplateMessageSend('41', $sendOpenid, $companyid, '', '', array('积分扣除','积分通知'), array('风外卖支付退款',$memberIntegralInfo['integralnum'].'分'));
                            }elseif ($type=='113'||$type=='118'||$type=='119'||$type=='120'){
                                // 预订(通用):113;预订(SPA):118;预订(餐饮):119;预订(酒店):120;
                                $this->WeChatTemplateMessageSend('41', $sendOpenid, $companyid, '', '', array('积分扣除','积分通知'), array('手机预订订金退款',$memberIntegralInfo['integralnum'].'分'));
                            }
                        }
                    }
                    // 发送储值改变模板
                    if($changeSpendingNum2 && !$borderid){
                        $messageType = '储值通知';
                        if($type == '112' && ($rechargetype == '1' || $rechargetype == '2' || $rechargetype == '5')){
                            // 后台储值充值:1;会员WAP自助充值:2;风助手储值充值:5;
                            $messageName = '储值充值';
                            $messageId = '30';
                            $messageData = array($changeSpendingNum2,$nowAccountbalance);
                            // 会员名称与手机号
                            $memberInfo = M('member_register_info')->where(array('companyid'=>$companyid,'id'=>$mid))->field('name,moblie')->find();
                            $openidList = M('users')->where(array('companyid'=>$companyid))->field('helperopenid as openid,isboss,helperpermissions,phone')->select();
                            $storedvalueorderisopen = M('company')->where(array('id'=>$companyid))->getField('storedvalueorderisopen');
                            if($storedvalueorderisopen == 1){
                            	$message = '您好，您有新的储值充值订单。交易号：'.$borderid.'。下单会员：'.$memberInfo['name'].' '.$memberInfo['moblie'].'。下单时间：'.date('Y-m-d,H:i', $time).'。订单金额：'.$changeSpendingNum.'。';
                            }
                            foreach ($openidList as $oKey=>$oVal){
                            	$phone = $oVal['phone']?$oVal['phone']:'';
                            	if($storedvalueorderisopen == 1 && $phone){
                            		if($oVal['isboss'] == '1' || in_array('17',explode(',',$oVal['helperpermissions']))){
                            			$this->sendSms($phone, $message, $companyid);
                            		}
                            	}
                            	unset($phone);
                            }
                        }elseif ($type == '203'){
                            // 会员WAP积分换储值
                            $messageName = '会员WAP积分换储值';
                            $messageId = '32';
                            $messageData = array($integralData['integralnum'], sprintf("%.2f", $changeSpendingNum2).'元', $nowAccountbalance.'元');
                        }elseif ($paytype == '4'){
                            // 储值消费
                            $messageName = '储值消费';
                            $messageId = '31';
                            $messageData = array(sprintf("%.2f", $changeSpendingNum2), $nowAccountbalance);
                        }
                        if($messageName && $messageId && $messageData){
                            $this->WeChatTemplateMessageSend($messageId, $sendOpenid, $companyid, '', '', array($messageName, '储值通知'), $messageData);
                        }
                    }elseif ($borderid && $memberSpendingInfo['spendingamount2'] && $memberBeforeAccountbalance){
                        $this->WeChatTemplateMessageSend('42', $sendOpenid, $companyid, '', '', array('储值退还','储值通知'), array($memberBeforeAccountbalance.'元',$memberSpendingInfo['spendingamount2'].'元',$nowAccountbalance.'元'));
                    }
                }
                if($changeSpendingNum > 0 && !$borderid){
                    if($paytype !='4'){
                        //邀请赠礼首次消费时绑定这个人的mid
                        $this->yVoucherInfo(array("mid"=>$mid,"companyid"=>$companyid));
                    }
                    // 参与储值送券活动
                    if($rechargetype == '1' || $rechargetype == '2' || $rechargetype == '5'){
                        if($companyid && $mid && $changeSpendingNum){
                            $crmSVActOptione['companyid']=$companyid;
                            $crmSVActOptione['mid']=$mid;
                            $crmSVActOptione['spendingamount']=$changeSpendingNum;
                            $this->crmActivitiesStoredvalue($crmSVActOptione);
                        }
                    }
                }
                $this->NewchangMemberCardRank($companyid,$mid);//改变会员卡等级
                if(!$borderid && $changeSpendingReturn && ($type=='106' || $type=='107' || $type=='110' || $type=='111' || $type=='113' || $type=='114' || $type=='115' || $type=='118' || $type=='119' || $type=='120' || $type=='121'|| $type=='125' || $type=='126' || $type=='127')){
                    // 首次消费产生的使用场景，例如：闪惠支付：106；风助手手机收银：107；eshop支付：110；拉卡拉手机收银：111；预订(通用版)：113；微信外卖：114；手机点单：115；预订(SPA版)：118；预订(餐饮版)：119；预订(酒店版)：120；付费内容商店(年卡+单品)：121；储值快捷收银(风助手)：125；储值快捷收银(拉卡拉)：126；声悦传情：127；
                    $spendingNum = M('member_spending')->where(array('companyid'=>$companyid,'mid'=>$mid,'type'=>array('in','106,107,110,111,113,114,115,118,119,120,121,125,126,127')))->count();
                    if($spendingNum == 1){
                        unset($companyShopsSaveData,$shopInfo,$nowMemberCanUseInt);
                        $this->sendConsumptionMemberActivitiesVouchers($companyid,$mid);
                        $firstSpendingData['uid'] = $uid;
                        $firstSpendingData['shopid'] = $shopid;
                        $firstSpendingData['cid'] = $companyid;
                        $firstSpendingData['mid'] = $mid;
                        $firstSpendingData['type'] = '109';
                        $firstSpendingData['linkorderid'] = $linkOrderId;
                        $firstSpendingData['linkoutorderid'] = $linkOutOrderId;
                        $this->changeMemberBusinessSCRM5($firstSpendingData);
                        
                    }
                }
            }
            return true;
        }else{
            M()->rollback();
            $logData['id'] = guidNow();
            $logData['cid'] = $companyid;
            $logData['mid'] = $mid;
            $logData['log'] = format_time(time(),'ymdhis').'$linkOrderIdSaveReturn:'.$linkOrderIdSaveReturn.';$companySaveReturn:'.$companySaveReturn.'$changeIntReturn:'.$changeIntReturn.';&&$nowTotalintegral>=0:'.$nowTotalintegral.';&&$changeSpendingReturn:'.$changeSpendingReturn.';&&$memberRegisterInfoSaveReturn:'.$memberRegisterInfoSaveReturn.';$nowAccountbalance:'.$nowAccountbalance.';$companyShopsSaveReturn:'.$companyShopsSaveReturn.';option:'.json_encode($option);
            $logData['createtime'] = time();
            M('log_member_business')->add($logData);
            //$this->sendSms('13564012907', '你有一笔新的交易没能成功记录交易记录或者赠送积分，请核查！log日志ID：'.$logData['id'],'1186','【人来风】','','181818');
            return false;
        }
    }
    /**
     * $option['cid']             必填:操作时使用的公司id,也就是我们用到的companyid
     * $option['shopid']          必填:操作时使用的shopid,后面需进行数据统计
     * $option['type']            必填,添加时必填：数据获取类型，添加时注意对应关系；完善会员资料:101;注册:102;点评奖励:103;手动加积分:104;风助手手机收银:105;闪惠支付:106;风助手手机收银:107;微信关注:108;首次消费:109;eshop支付:110;拉卡拉手机收银:111;充值:112（包含：后台充值、在线充值、红包充值）;手机预订:113;微信外卖：114；手机点单：115；风助手手动加积分：116；手机预订奖励：117；手动扣除积分:201;到期自动清零:202;会员WAP积分换储值:203;积分商城:204;风助手手动减积分:205;后台储值消费:301;
     * $option['uid']             非必填:操作时使用的子账号uid,需根据此id 查到对应门店shopid，shopname
     * $option['mid']             非必填,操作时的会员id,也就是我们使用的mid，也有可能是没有MID的，比如门店收银顾客需要送积分
     * $option['num']             非必填,操作时产生的数据1，消费时必填，积分交易时有可能非必填,例如首次消费的积分是直接数据库中读取，直接添加的积分需要参数传入再或者消费的金额再或者充值的金额，积分是整型数据：10;消费是浮点型数据:88.89;
     * $option['num2']            非必填，操作时产生的数据2，例如在线充值，订单金额与充值金额是两个不同的值，需要同时传入
     * $option['linkorderid']     非必填，如果产生的交易带有订单号，需把对应的订单号传入，方便后续产品优化查询消费或者积分获取来源；
     * $option['linkoutorderid']  非必填，如果产生的交易带有外部商户订单号，需把对应的商户订单号传入，方便后续产品优化查询消费或者积分获取来源；比如：微信支付，支付宝支付，拉卡拉支付
     * $option['paytype']         非必填，消费时必填，对应支付方式； 微信支付：1；支付宝支付：2；现金支付：3；储值支付：4；银行卡支付：5；
     * $option['note']            非必填，交易备注
     * $option['rechargetype']    非必填，充值方式，用于充值记录；后台充值：1；在线充值：2；会员WAP积分换储值：3；红包：4；
     * @author Lando<806728685@qq.com>
     * @since  2016-11-16
     */
    public function changeMemberBusinessSCRM5Int($option){
        $time = time();
        M()->startTrans();
        $changeNum = $option['num'] ? $option['num'] : 0 ; // 操作时产生的数据1
        $changeNum2 = $option['num2'] ? $option['num2'] : 0 ; // 操作时产生的数据2
        if($changeNum > 999999 || $changeNum2 > 999999){
            return false;
        }
        $type = $option['type'] ? $option['type'] : 0 ;
        $companyid = $option['cid'] ? $option['cid'] : 0 ;
        $shopid = $option['shopid'] ? $option['shopid'] : 0 ;
        $mid = $option['mid'] ? $option['mid'] : 0 ;   // 客户id
        $uid = $option['uid'] ? $option['uid'] : 0 ;   // 操作人id
        $rechargetype = $option['rechargetype'] ? $option['rechargetype'] : 0 ;// 充值方式， 后台充值：1；在线充值：2；会员WAP积分换储值：3；红包：4；
        $linkOrderId = $option['linkorderid'] ? $option['linkorderid'] : '' ;// 关联订单号
        $linkOutOrderId = $option['linkoutorderid'] ? $option['linkoutorderid'] : '' ;// 关联外部商户订单号
        $borderid = '';
        if($borderid){
            //  当传入$borderid 默认为撤销消费，使用场景：过期退，随时退
            $data['status'] = 2 ;
            $data['updatetime'] = $time;
        }else{
            $data['adduid'] = $data['edituid'] =  0;
            $data['companyid'] = $companyid;
            $data['shopsid'] = $shopid;
            $data['mid'] = $mid ;
            $data['linkorderid'] = $linkOrderId ;
            $data['linkoutorderid'] = $linkOutOrderId ;
            $paytype = $option['paytype'] ? $option['paytype'] : 0 ;// 支付方式 微信支付：1；支付宝支付：2；现金支付：3；储值支付：4；银行卡支付：5；
            $data['paytype'] = $paytype ;// 支付方式
            // 消费 使用场景，例如：闪惠支付：106；风助手手机收银：107；eshop支付：110；拉卡拉手机收银：111；手机预订：113；微信外卖：114:；手机点单：115；
            $data['borderid'] = get_order_id(10);
            $data['status'] = 1 ;
            $data['note'] = $option['note'] ? $option['note'] : '' ;
            $data['rechargetype'] = $rechargetype ;
            $data['updatetime'] = $data['createtime'] = $time;
        }
        if($uid > 0){
            $userInfo = M('users')->where(array('companyid'=>$companyid, 'id'=>$uid))->field('id,username,truename,isboss,helpershopid')->find();
            if($userInfo['helpershopid'] == '-1' || $userInfo['isboss'] == '1'){
                $shopInfo = array('id'=>'-1','shopname'=>'总部');
            }else{
                $shopInfo = M('company_shops')->where(array('companyid'=>$companyid, 'id'=>$userInfo['helpershopid']))->field('id,shopname')->find();
            }
        }
        if(!$userInfo){
            $userInfo = array('id'=>'0','username'=>'','truename'=>'系统','isbosss'=>'0','helpershopid'=>'0');
        }
        if($shopid){
            $shopInfo = M('company_shops')->where(array('companyid'=>$companyid, 'id'=>$shopid))->field('id,shopname')->find();
        }
        if(!$shopInfo){
            $shopInfo = array('id'=>'0','shopname'=>'');// 备用数据
        }
        // 目前先读取所有字段，后面优化，因为有很多字段都是需要废弃的，现在还不确定
        $integralSet = M('member_integral_set')->where(array('companyid'=>$companyid))->find();
        $changeIntNum = $changeSpendingNum = $changeSpendingNum2 = $isrecharge = $nextyearcanuseintegralChangeInt = 0;
        // 获取当前公司是否设置积分清零规则
        $memberIntIsAutoClear = $integralSet['integralisautoclear'];
        if($borderid){
            
        }else{
            // 全部需要 计算+添加
            if($type == '101' || $type == '102' || $type == '103' || $type == '104' || $type == '105' || $type == '106' || $type == '107' || $type == '108' || $type == '109' || $type == '110' || $type == '111' || $type == '112' || $type == '113' || $type == '114' || $type == '115' || $type == '116' || $type == '117' || $type == '301'){
                $status = '1';    //对积分做加
                // 完善会员资料:101;注册:102;点评奖励:103;手动加积分:104;风助手手机收银:105;闪惠支付:106;风助手手机收银:107;微信关注:108;首次消费:109;eshop支付:110;拉卡拉手机收银:111;充值:112;手机预订:113;微信外卖：114；手机点单：115；风助手手动加积分：116；手机预订奖励：117；
                $changeIntOrderid = $this->newOrderID(3,'H',$companyid);// 生成交易号
                if($type=='101'){
                    if($integralSet['perfectreginfoisopen']=='1'){
                        // 完善资料送积分
                        $changeIntNum = $integralSet['perfectreginfoint'];
                        $memberRegisterInfoSaveData['issend100expint'] = 1;
                    }
                }elseif ($type=='102'){
                    if($integralSet['createcardisopen']=='1'){
                        // 注册送积分
                        $changeIntNum = $integralSet['createcardint'];
                    }
                }elseif ($type=='103'){
                    if($integralSet['dianpingisopen']=='1'){
                        // 点评送积分
                        $changeIntNum = $integralSet['dianpingint'];
                    }
                }elseif ($type=='104'){
                    // 手动加积分
                    $changeIntNum = $changeNum;
                }elseif ($type=='105'){
                    if($integralSet['yaoqiandaoisopen']=='1'){
                        // 摇摇签到
                        $changeIntNum = $integralSet['yaoqiandaoint'];
                    }
                }elseif ($type=='106'){
                    // 闪惠支付
                    $changeSpendingNum = $changeNum;
                    if($integralSet['shanhuipayisopen']=='1' && $changeNum > 0){
                        $changeIntNum = $changeNum/$integralSet['shanhuipayconversion'];
                    }
                    $isrecharge = 1;  // 减余额
                }elseif ($type=='107'){
                    // 风助手手机收银
                    $changeSpendingNum = $changeNum;
                    if($integralSet['windhelperpayisopen']=='1' && $changeNum > 0){
                        $changeIntNum = $changeNum/$integralSet['windhelperpayconversion'];
                    }
                    $isrecharge = 1;  // 减余额
                }elseif ($type=='108'){
                    if($integralSet['wechatsubscribeisopen']=='1'){
                        // 微信关注
                        $changeIntNum = $integralSet['wechatsubscribeint'];
                        $memberRegisterInfoSaveData['issendwechatsubscribeint'] = '1'; // 标识已经赠送过关注赠积分
                    }
                }elseif ($type=='109'){
                    if($integralSet['firstconsumptionisopen']=='1'){
                        // 首次消费
                        $changeIntNum = $integralSet['firstconsumptionint'];
                    }
                }elseif ($type=='110'){
                    // eshop 支付
                    $changeSpendingNum = $changeNum;
                    if($integralSet['eshoppayisopen']=='1' && $changeNum > 0){
                        $changeIntNum = $changeNum/$integralSet['eshoppayconversion'];
                    }
                    $isrecharge = 1;  // 减余额
                }elseif ($type=='111'){
                    // 拉卡拉手机收银
                    $changeSpendingNum = $changeNum;
                    if($integralSet['lakalapayisopen']=='1' && $changeNum > 0){
                        $changeIntNum = $changeNum/$integralSet['lakalapayconversion'];
                    }
                    $isrecharge = 1;  // 减余额
                }elseif ($type=='112'){
                    // 充值
                    $changeSpendingOrderid = $this->newOrderID(3,'B',$companyid);
                    if($rechargetype == '2'){
                        $changeSpendingNum = $changeNum;
                        $changeSpendingNum2 = $changeNum2;
                    }elseif ($rechargetype == '1' || $rechargetype=='4'){
                        $changeSpendingNum2 = $changeNum2;
                    }
                    $isrecharge = 2;  // 加余额
                }elseif ($type=='113'){
                    // 手机预订
                    $changeSpendingNum = $changeNum;
                    if($integralSet['mobilebookpayisopen']=='1' && $changeNum > 0){
                        $changeIntNum = $changeNum/$integralSet['mobilebookpayconversion'];
                    }
                    $isrecharge = 1;  // 减余额
                }elseif ($type=='114'){
                    // 微信外卖
                    $changeSpendingNum = $changeNum;
                    if($integralSet['takeoutisopen']=='1' && $changeNum > 0){
                        $changeIntNum = $changeNum/$integralSet['takeoutconversion'];
                    }
                    $isrecharge = 1;  // 减少账户余额
                }elseif ($type=='115'){
                    // 手机点单
                    $changeSpendingNum = $changeNum;
                    if($integralSet['mobilephoneorderisopen']=='1' && $changeNum > 0){
                        $changeIntNum = $changeNum/$integralSet['mobilephoneorderconversion'];
                    }
                    $isrecharge = 1;  // 减少账户余额
                }elseif ($type=='116'){
                    // 风助手手动加积分
                    $changeIntNum = $changeNum;
                }elseif ($type=='117'){
                    // 手机预订奖励
                    if($integralSet['mobilebookisopen']=='1'){
                        $changeIntNum = $integralSet['mobilebookint'];
                    }
                }elseif($type == '301'){
                    // 后台储值消费
                    $changeSpendingNum = $changeNum;
                    if($integralSet['storedvalueisopen']=='1' && $changeNum > 0){
                        $changeIntNum = $changeNum/$integralSet['storedvalueconversion'];
                    }
                    $isrecharge = 1; // 减少账户余额
                }
                if($changeIntNum){
                    $changeIntNum =floor($changeIntNum);
                    if($changeIntNum > 0){
                        // 积分清零规则处理
                        if($memberIntIsAutoClear == '1'){
                            $memberRegisterInfoSaveData['nextyearcanuseintegral'] = array('exp', '`nextyearcanuseintegral`+'.$changeIntNum.'');
                        }else{
                            $memberRegisterInfoSaveData['thisyearcanuseintegral'] = array('exp', '`thisyearcanuseintegral`+'.$changeIntNum.'');
                            $memberRegisterInfoSaveData['nextyearcanuseintegral'] = array('exp', '`nextyearcanuseintegral`+'.$changeIntNum.'');
                        }
                        $memberRegisterInfoSaveData['totalexperiencevalue'] = array('exp', '`totalexperiencevalue`+'.$changeIntNum.'');
                        $memberRegisterInfoSaveData['totalintegration'] = array('exp', '`totalintegration`+'.$changeIntNum.'');
                    }
                }
            }elseif ($type == '201' || $type == '202' || $type == '203' || $type == '204'  || $type == '205' ){
                $status = '2';   //对积分做减
                // 手动扣除积分:201;到期自动清零:202;会员WAP积分换储值:203;积分商城:204;风助手手动减积分：205；
                $changeIntOrderid = $this->newOrderID(3,'G',$companyid);// 生成交易号
                if ($type=='201'){
                    // 手动扣除积分
                    $changeIntNum = $changeNum;
                }elseif ($type=='202'){
                    // 到期自动清零
                    //$changeIntNum = $integralSet['mobilebookint']*$changeNum;
                }elseif ($type=='203'){
                    // 会员WAP积分换储值
                    $isrecharge = 2;  // 增加账户余额
                    if($integralSet['integralconvertmoney']=='1' && $changeNum > 0){
                        // 换算获得的累加 金额
                        $changeSpendingOrderid = $this->newOrderID(3,'B',$companyid);
                        $changeSpendingNum2 = $changeNum/$integralSet['integralconvertmoneyconversion'];
                    }
                    $changeIntNum = $changeNum;
                }elseif ($type=='204'){
                    // 积分商城
                    $changeIntNum = $changeNum;
                }elseif ($type=='205'){
                    // 风助手手动减积分
                    $changeIntNum = $changeNum;
                }
                if($changeIntNum){
                    $changeIntNum =floor($changeIntNum);
                    if($changeIntNum > 0){
                        // 积分清零规则处理
                        /*
                                                             积分清零规则
                                                            最新规则如下
                        1.会员表新增两个字段：有效期为次年的可用积分  nextyearcanuseintegral ，有效期为当年的可用积分 thisyearcanuseintegral；
                                                            关于积分清零规则总共涉及三个字段：当前总可用积分，有效期为次年的可用积分，有效期为当年的可用积分；
                        2.这三个字段的数值改变规则如下：
                                                            开启清零规则：
                        1.消耗积分：消耗积分先在有效期为当年的可用积分中减掉，如果有效期为当年的可用积分大于当前消耗积分时，有效期为次年的可用积分不做改变；如果有效期为当年的可用积分小于当前消耗积分时不足扣除积分需要在有效期为次年的可用积分中进行扣除；以上两种情况需要同时在当前总可用积分中进行扣除。
                        2.获取积分：当前总可用积分、有效期为次年的可用积分同时增加，有效期为当年的可用积分不做改变。
                                                            未开启清零规则：当前总可用积分、有效期为次年的可用积分、有效期为当年的可用积分三个字段同加同减。
                        3.每年****-12-31 23:59:59 定时任务执行规则
                                                            开启清零规则：清空有效期为当年的可用积分，并把有效期为次年的可用积分复制填充至有效期为当年的可用积分的位置，然后将有效期为次年的可用积分分置为零。
                                                            未开启清零规则：仅仅将有效期为次年的可用积分值置为零。
                                                            此规则执行前，需要初始化数据当前总可用积分，有效期为次年的可用积分，有效期为当年的可用积分；
                                                            例如当前时间为2016
                                                            有效期为次年的可用积分:整个2016年获得所有积分(如果$a 小于零，需要在这里减掉)；
                                                            有效期为当年的可用积分:$a = 2016年前获得的所有积分-2016年前消耗的所有积分-2016年消耗的积分；
                        */
                        $nowMemberCanUseInt = M('member_register_info')->where(array('companyid'=>$companyid,'id'=>$mid))->field('totalexperiencevalue,thisyearcanuseintegral,nextyearcanuseintegral')->find();
                        if($memberIntIsAutoClear == '1'){
                            if($nowMemberCanUseInt['thisyearcanuseintegral']>=$changeIntNum){
                                $memberRegisterInfoSaveData['thisyearcanuseintegral'] = array('exp', '`thisyearcanuseintegral`-'.$changeIntNum.'');
                            }else{
                                $memberRegisterInfoSaveData['thisyearcanuseintegral'] = '0';
                                $nextyearcanuseintegralChangeInt = $changeIntNum - $nowMemberCanUseInt['thisyearcanuseintegral'];
                                if($nextyearcanuseintegralChangeInt >= $nowMemberCanUseInt['nextyearcanuseintegral']){
                                    $memberRegisterInfoSaveData['nextyearcanuseintegral'] = '0';
                                }else{
                                    $memberRegisterInfoSaveData['nextyearcanuseintegral'] = array('exp', '`nextyearcanuseintegral`-'.$nextyearcanuseintegralChangeInt.'');
                                }
                            }
                        }else{
                            if($nowMemberCanUseInt['thisyearcanuseintegral']>=$changeIntNum){
                                $memberRegisterInfoSaveData['thisyearcanuseintegral'] = array('exp', '`thisyearcanuseintegral`-'.$changeIntNum.'');
                            }else{
                                $memberRegisterInfoSaveData['thisyearcanuseintegral'] = '0';
                            }
                            if($nowMemberCanUseInt['nextyearcanuseintegral']>=$changeIntNum){
                                $memberRegisterInfoSaveData['nextyearcanuseintegral'] = array('exp', '`nextyearcanuseintegral`-'.$changeIntNum.'');
                            }else{
                                $memberRegisterInfoSaveData['nextyearcanuseintegral'] = '0';
                            }
                        }
                        $memberRegisterInfoSaveData['totalintegration'] = array('exp', '`totalintegration`-'.$changeIntNum.'');
                    }
                }
            }
        }
        if($changeIntNum>0 && $mid){
            $integralData = $data;
            $integralData['orderid'] = $changeIntOrderid;
            $integralData['type'] = $type;
            $integralData['userid'] = $userInfo['id'];
            $integralData['username'] = $userInfo['truename']?$userInfo['truename']:$userInfo['username'];
            $integralData['shopid'] = $shopInfo['id'];
            $integralData['shopname'] = $shopInfo['shopname'];
            $integralData['integralnum'] = $changeIntNum;
            $changeIntReturn = M('member_integral')->add($integralData);
        }else {
            $changeIntReturn = 1;
        }
        if($mid){
            // 储存会员信息的修改
            if($memberRegisterInfoSaveData){
                $memberRegisterInfoSaveData['updatetime'] = $time;
                $memberRegisterInfoSaveReturn = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->save($memberRegisterInfoSaveData);
            }else{
                $memberRegisterInfoSaveReturn = 1;
            }
            $nowTotalintegral = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->getField('totalintegration');
            $nowAccountbalance = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->getField('accountbalance');
        }else{
            $memberRegisterInfoSaveReturn = $nowTotalintegral = $nowAccountbalance = 1;
        }
        if($changeIntReturn&&$nowTotalintegral>=0&&$memberRegisterInfoSaveReturn&&$nowAccountbalance>=0){
            M()->commit();
            if($mid){
                // 发送微信消息模板
                $sendOpenid = M('member_wechat_info')->where(array('companyid'=>$companyid,'mid'=>$mid))->getField('openid');
                if($sendOpenid){
                    // 发送积分改变模板
                    if($integralData['integralnum'] && !$borderid){
                        $optionType = $integralData['type'];
                        $changeIntNum = $integralData['integralnum'];
                        $sendDesc = $this->changeMemberIntegralType[$optionType];
                        if($status == '1'){
                            $this->WeChatTemplateMessageSend('3', $sendOpenid, $companyid, '', '', array(format_time($time,'ymdhis'), $changeIntNum, $sendDesc, $nowTotalintegral), '');
                        }elseif ($status == '2'){
                            $this->WeChatTemplateMessageSend('4', $sendOpenid, $companyid, '', '', array($sendDesc,$changeIntNum,$nowTotalintegral), '');
                        }
                    }
                }
                $this->NewchangMemberCardRank($companyid,$mid);//改变会员卡等级
            }
            return true;
        }else{
            M()->rollback();
            $logData['id'] = guidNow();
            $logData['cid'] = $companyid;
            $logData['mid'] = $mid;
            $logData['log'] = format_time(time(),'ymdhis').'changeMemberBusinessSCRM5Int-----$changeIntReturn:'.$changeIntReturn.';&&$nowTotalintegral>=0:'.$nowTotalintegral.';&&$memberRegisterInfoSaveReturn:'.$memberRegisterInfoSaveReturn.';$nowAccountbalance:'.$nowAccountbalance.';option:'.json_encode($option);
            $logData['createtime'] = time();
            M('log_member_business')->add($logData);
            //$this->sendSms('13564012907', '你有一笔新的交易没能成功记录交易记录或者赠送积分，请核查！log日志ID：'.$logData['id'],'1186','【人来风】','','181818');
            return false;
        }
    }
    /**
     * 根据经验值 改变 会员卡 等级
     * @param number $companyid
     * @param number $mid
     * @return boolean
     */
    public function changMemberCardRank($companyid = 0,$mid = 0){
    		$memberRegisterInfo = M()->table('tp_member_register_info AS register')->join(array('tp_member_card_info AS card ON card.mid=register.id','tp_member_card_rank AS rank ON rank.id=card.rankid'))->where(array('register.companyid'=>$companyid,'register.id'=>$mid))->field('register.id,register.name,register.isregister,register.moblie,register.totalexperiencevalue,card.rankid,card.cardnum,rank.name as rankname,rank.number')->find();
    		$memberCardRankList = M('member_card_rank')->where(array('companyid'=>$companyid))->field('id,name,number,beginscore,endscore')->select();
    		if($memberRegisterInfo&&$memberCardRankList){
    			$success = $addCardDate = $srankid = $srankname = $isWeChatTemplate = $number = '';
    			foreach ($memberCardRankList as $mcrlKey=>$mcrlVal){
    				if($mcrlVal['number'] == '1'){
    					if($memberRegisterInfo['totalexperiencevalue']<=$mcrlVal['endscore']){
    						$srankid = $mcrlVal['id'];
    						$number = $mcrlVal['number'];
    						$srankname = $mcrlVal['name'];
    					}
    				}elseif($mcrlVal['number'] == '4'){
    					if($memberRegisterInfo['totalexperiencevalue']>=$mcrlVal['beginscore']){
    						$srankid = $mcrlVal['id'];
    						$number = $mcrlVal['number'];
    						$srankname = $mcrlVal['name'];
    					}
    				}else{
	    				if ($mcrlVal['beginscore']<=$memberRegisterInfo['totalexperiencevalue'] && $memberRegisterInfo['totalexperiencevalue'] <= $mcrlVal['endscore']){
	    					$srankid = $mcrlVal['id'];
	    					$number = $mcrlVal['number'];
	    					$srankname = $mcrlVal['name'];
	    				}
    				}
    				unset($mcrlVal);
    			}
    			if($srankid && $srankname && $number && $memberRegisterInfo['isregister'] == '1'){
    				if($memberRegisterInfo['rankid'] != $srankid){
    					if($memberRegisterInfo['rankid']){
    						$cardInfoReturn = M('member_card_info')->where(array('companyid'=>$companyid,'mid'=>$mid))->save(array('rankid'=>$srankid,'updatetime'=>time()));
    						if($cardInfoReturn){
    						    // 会员等级 会员数统计
        						$this->memberTagCount($companyid, array(array('name'=>'rankid','before'=>$memberRegisterInfo['rankid'],'after'=>$srankid)));
        						$logData['id'] = guidNow();
        						$logData['companyid'] = $companyid;
        						$logData['mid'] = $mid;
        						$logData['log'] = format_time(time(),'ymdhis').'之前的等级ID：'.$memberRegisterInfo['rankid'].'，之后的等级ID：'.$srankid.'++++++http://' . $_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"];
        						$logData['createtime'] = time();
        						M('log_card_rank_error')->add($logData);
    						}
    						$isnumonecreatecard = 2;//升级
    					}else{
    						$isnumonecreatecard = 1;//是否首次开卡
    						$addCardDate['companyid'] = $companyid;
    						$addCardDate['edituid'] = $addCardDate['adduid'] =  $addCardDate['shopsid'] = 0;
    						$addCardDate['updatetime'] = $addCardDate['createtime'] = time();
    						$addCardDate['rankid'] = $srankid;
    						$addCardDate['cardnum'] = $memberRegisterInfo['moblie'];
    						$addCardDate['mid']=$mid;
							$cardInfoReturn = M('member_card_info')->add($addCardDate);
							if($cardInfoReturn){
							    // 会员等级 会员数统计
        						$this->memberTagCount($companyid, array(array('name'=>'rankid','after'=>$addCardDate['rankid'])));
        						$logData['id'] = guidNow();
        						$logData['companyid'] = $companyid;
        						$logData['mid'] = $mid;
        						$logData['log'] = format_time(time(),'ymdhis').'之前的等级ID：0，之后的等级ID：'.$srankid.'++++++http://' . $_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"];
        						$logData['createtime'] = time();
        						M('log_card_rank_error')->add($logData);
							}
    					}
    					if($cardInfoReturn){
    						if($isnumonecreatecard == '2'){
    							$time = time();
    							// 发送消息模板
    							$openid = M('member_wechat_info')->where(array('companyid'=>$companyid,'mid'=>$mid))->getField('openid');
    							$companyname = M('company')->where(array('id'=>$companyid))->getField('name');
    							$this->WeChatTemplateMessageSend('2',$openid,$companyid,'','',array($companyname,$srankname,format_time($time,'ymdhis')),'');
    						}
    						
    						$cardnum = $number-$memberRegisterInfo['number'];
    						if($cardnum>0){
    							for ($i=($memberRegisterInfo['number']+1);$i<=$number;$i++){
    								$cardid = M('member_card_rank')->where(array('companyid'=>$companyid,'number'=>$i))->getField('id');
    								$this->sendMemberVouchersSCRM5($cardid,$mid,$companyid,'4');
    							}
    						}
    					}else{
    						$log['id'] = guidNow();
    						$log['companyid'] = $companyid;
    						$log['log'] = '$isnumonecreatecard = '.$isnumonecreatecard.'$addCardDate = '.json_encode($addCardDate);
    						$log['createtime'] = time();
    						M('log_card_rank')->add($log);
    					}
    				}
    			}else{
    				$log['id'] = guidNow();
    				$log['companyid'] = $companyid;
    				$log['log'] = '$mcrlVal["id"] = '.$mcrlVal['id'].'$mcrlVal["beginscore"] = '.$mcrlVal["beginscore"].'$memberRegisterInfo["totalexperiencevalue"] = '.$memberRegisterInfo['totalexperiencevalue'].'$mcrlVal["endscore"] = '.$mcrlVal['endscore'];
    				$log['createtime'] = time();
    				M('log_card_rank')->add($log);
    			}
    			return true;
    		}else{
    			$log['id'] = guidNow();
    			$log['companyid'] = $companyid;
    			$log['log'] = '$memberRegisterInfo = '.json_encode($memberRegisterInfo).'$memberCardRankList = '.json_encode($memberCardRankList);
    			$log['createtime'] = time();
    			M('log_card_rank')->add($log);
    			return TRUE;
    		}
    }
    /**
     * 根据经验值 改变 会员卡 等级
     * @param number $companyid
     * @param number $mid
     * @return boolean
     */
    public function NewchangMemberCardRank($companyid = 0,$mid = 0){
            $memberRegisterInfo = M()->table('tp_member_register_info AS register')->join(array('tp_member_card_info AS card ON card.mid=register.id','tp_member_card_rank AS rank ON rank.id=card.rankid'))->where(array('register.companyid'=>$companyid,'register.id'=>$mid))->field('register.id,register.name,register.isregister,register.moblie,register.totalexperiencevalue,card.rankid,card.cardnum,rank.name as rankname,rank.number')->find();
    		$memberCardRankList = M('member_card_rank')->where(array('companyid'=>$companyid))->field('id,name,number,beginscore,endscore')->select();
    		if($memberRegisterInfo&&$memberCardRankList){
    			$success = $addCardDate = $srankid = $srankname = $isWeChatTemplate = $number = '';
    			foreach ($memberCardRankList as $mcrlKey=>$mcrlVal){
    				if($mcrlVal['number'] == '1'){
    					if($memberRegisterInfo['totalexperiencevalue']<=$mcrlVal['endscore']){
    						$srankid = $mcrlVal['id'];
    						$number = $mcrlVal['number'];
    						$srankname = $mcrlVal['name'];
    					}
    				}elseif($mcrlVal['number'] == '4'){
    					if($memberRegisterInfo['totalexperiencevalue']>=$mcrlVal['beginscore']){
    						$srankid = $mcrlVal['id'];
    						$number = $mcrlVal['number'];
    						$srankname = $mcrlVal['name'];
    					}
    				}else{
	    				if ($mcrlVal['beginscore']<=$memberRegisterInfo['totalexperiencevalue'] && $memberRegisterInfo['totalexperiencevalue'] <= $mcrlVal['endscore']){
	    					$srankid = $mcrlVal['id'];
	    					$number = $mcrlVal['number'];
	    					$srankname = $mcrlVal['name'];
	    				}
    				}
    				unset($mcrlVal);
    			}
    			if($srankid && $srankname && $number && $memberRegisterInfo['isregister'] == '1'){
    				if($memberRegisterInfo['rankid'] != $srankid){
    					if($memberRegisterInfo['rankid']){
    						$cardInfoReturn = M('member_card_info')->where(array('companyid'=>$companyid,'mid'=>$mid))->save(array('rankid'=>$srankid,'updatetime'=>time()));
    						if($cardInfoReturn){
    						    // 会员等级 会员数统计
        						$this->memberTagCount($companyid, array(array('name'=>'rankid','before'=>$memberRegisterInfo['rankid'],'after'=>$srankid)));
        						$logData['id'] = guidNow();
        						$logData['companyid'] = $companyid;
        						$logData['mid'] = $mid;
        						$logData['log'] = format_time(time(),'ymdhis').'之前的等级ID：'.$memberRegisterInfo['rankid'].'，之后的等级ID：'.$srankid.'++++++http://' . $_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"];
        						$logData['createtime'] = time();
        						M('log_card_rank_error')->add($logData);
    						}
    						$isnumonecreatecard = 2;//升级
    					}else{
    						$isnumonecreatecard = 1;//是否首次开卡
    						$addCardDate['companyid'] = $companyid;
    						$addCardDate['edituid'] = $addCardDate['adduid'] =  $addCardDate['shopsid'] = 0;
    						$addCardDate['updatetime'] = $addCardDate['createtime'] = time();
    						$addCardDate['rankid'] = $srankid;
    						$addCardDate['cardnum'] = $memberRegisterInfo['moblie'];
    						$addCardDate['mid']=$mid;
							$cardInfoReturn = M('member_card_info')->add($addCardDate);
							if($cardInfoReturn){
							    // 会员等级 会员数统计
        						$this->memberTagCount($companyid, array(array('name'=>'rankid','after'=>$addCardDate['rankid'])));
        						$logData['id'] = guidNow();
        						$logData['companyid'] = $companyid;
        						$logData['mid'] = $mid;
        						$logData['log'] = format_time(time(),'ymdhis').'之前的等级ID：0，之后的等级ID：'.$srankid.'++++++http://' . $_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"];
        						$logData['createtime'] = time();
        						M('log_card_rank_error')->add($logData);
							}
    					}
    					if($cardInfoReturn){
    						if($isnumonecreatecard == '2'){
    							$time = time();
    							// 发送消息模板
    							$openid = M('member_wechat_info')->where(array('companyid'=>$companyid,'mid'=>$mid))->getField('openid');
    							$companyname = M('company')->where(array('id'=>$companyid))->getField('name');
    							$this->WeChatTemplateMessageSend('2',$openid,$companyid,'','',array($companyname,$srankname,format_time($time,'ymdhis')),'');
    						}
    						
    						$cardnum = $number-$memberRegisterInfo['number'];
    						if($cardnum>0){
    							for ($i=($memberRegisterInfo['number']+1);$i<=$number;$i++){
    								$cardid = M('member_card_rank')->where(array('companyid'=>$companyid,'number'=>$i))->getField('id');
    								$this->sendMemberVouchersSCRM5($cardid,$mid,$companyid,'4');
    							}
    						}
    					}else{
    						$log['id'] = guidNow();
    						$log['companyid'] = $companyid;
    						$log['log'] = '$isnumonecreatecard = '.$isnumonecreatecard.'$addCardDate = '.json_encode($addCardDate);
    						$log['createtime'] = time();
    						M('log_card_rank')->add($log);
    					}
    				}
    			}else{
    				$log['id'] = guidNow();
    				$log['companyid'] = $companyid;
    				$log['log'] = '$mcrlVal["id"] = '.$mcrlVal['id'].'$mcrlVal["beginscore"] = '.$mcrlVal["beginscore"].'$memberRegisterInfo["totalexperiencevalue"] = '.$memberRegisterInfo['totalexperiencevalue'].'$mcrlVal["endscore"] = '.$mcrlVal['endscore'];
    				$log['createtime'] = time();
    				M('log_card_rank')->add($log);
    			}
    			return true;
    		}else{
    			$log['id'] = guidNow();
    			$log['companyid'] = $companyid;
    			$log['log'] = '$memberRegisterInfo = '.json_encode($memberRegisterInfo).'$memberCardRankList = '.json_encode($memberCardRankList);
    			$log['createtime'] = time();
    			M('log_card_rank')->add($log);
    			return TRUE;
    		}
    }
    /**
     * 商户·积分商城
     * @param unknown $orderid
     * @param unknown $mid
     * @param unknown $companyid
     * @return boolean|number
     * @author 姚成凯<kevin@renlaifeng.cn>
     * @since  2016-10-11
     */
    public function mallIntegralOrderPay($orderid,$companyid){
    	$where['companyid'] = $companyid;
    	$where['orderid'] = $orderid;
    	$orderInfo = M('mall_member_integral_order_info')->where($where)->field('mid,goodtype,type,orderid,orderint,ordertitle')->find();
    	if($orderInfo){
        	$mid = $orderInfo['mid'];
    		M()->startTrans();
    		$time = time();
    		// 订单信息
    		$editOrderInfo['paytime'] = $time;
    		if($orderInfo['type'] == 2){
    			$editOrderInfo['shippingtime'] = $time;
    			$editOrderInfo['receivaltime'] = $time;
    		}
    		$orderInfoSave = M('mall_member_integral_order_info')->where($where)->save($editOrderInfo);
    		// 扣商品库存
    		$orderGoods = M('mall_member_integral_order_goods')->where(array('companyid'=>$companyid,'orderid'=>$orderInfo['orderid']))->field('goodid,vouchertype,vouchersid,voucherskuid')->find();
    		$stockamountReturn = M('mall_member_integral_goods')->where(array('companyid'=>$companyid,'id'=>$orderGoods['goodid']))->setDec('stock');
    		// 虚拟商品发券
    		if($orderInfoSave && $stockamountReturn){
    			M()->commit();
    			// 积分历史、个人积分
    			$option = array('cid'=>$companyid, 'type'=>'204', 'mid'=>$mid, 'num'=>$orderInfo['orderint'], 'linkorderid'=>$orderInfo['orderid']);
    			$this->changeMemberBusinessSCRM5($option);
    			$openid = M('member_wechat_info')->where(array('companyid'=>$companyid,'mid'=>$mid))->getField('openid');
    			if($orderInfo['goodtype'] == 2){
    				if($orderGoods['vouchertype']==5 || $orderGoods['vouchertype']==6 || $orderGoods['vouchertype']==7 || $orderGoods['vouchertype']==8){
    					$sendType = '15';
    				}elseif($orderGoods['vouchertype'] == 9){
    					$sendType = '16';
    				}else{
    					$sendType = '7';
    				}
    				//发送获得卡券的消息模板
    				$sendSuc = $this->sendMemberVouchersSCRM5($orderInfo['orderid'], $mid, $companyid, $sendType, $orderGoods['voucherskuid'], '', $orderGoods['vouchertype']);
    				// 卡券发送success，修改订单状态
    				if($sendSuc['code'] == 200){
    					M('mall_member_integral_order_info')->where(array('companyid'=>$companyid,'orderid'=>$orderid))->setField('orderstatus', '3');
    				}
    			}else{
	    			// 兑换成功通知
	    			if($orderInfo['type'] == 1){
	    			    $this->WeChatTemplateMessageSend('22', $openid, $companyid, '', '', array('积分兑换礼品', '积分商城通知'), array($orderInfo['ordertitle']));
	    			}else{
	    			    $this->WeChatTemplateMessageSend('23', $openid, $companyid, '', '', array('积分兑换礼品', '积分商城通知'), array($orderInfo['ordertitle']));
	    			}
    			}
    			// 风助手信息模板
    			if($orderInfo['goodtype'] == 1){
    				if($orderInfo['type'] == 1){
    					$sendType = '快递配送';
    				}else{
    					$sendType = '到店领取';
    				}
    			}else{
    				$sendType = '到店领取';
    			}
    			$openidList = M('users')->where(array('companyid'=>$companyid,'helperopenid'=>array('neq', '')))->field('isboss,helperopenid,helperpermissions')->select();
    			foreach($openidList as $oKey=>$oVal){
    				if($oVal['isboss']=='1' || in_array('23',explode(',',$oVal['helperpermissions']))){
    					$this->WeChatTemplateMessageSend('25', $oVal['helperopenid'], $companyid, '', '', array('积分兑换礼品','积分商城通知'), array($orderInfo['ordertitle'], $sendType));
    				}
    				unset($oVal);
    			}
    			$return['code'] = 200;
    		}else{
    			M()->rollback();
    			$return['code'] = 300;
    		}
    	}
    	return $return;
    }
    /**
     * 
     * Sendcloud 发送邮件
     * 
     * @param unknown $var
     * @param unknown $template
     * @return string
     * @author Lando<806728685@qq.com>
     * @since  2016-6-25
     */
    public function sendEmail($var,$template){
    	$url = 'http://sendcloud.sohu.com/webapi/mail.send_template.json';
    	$vars = json_encode( $var);
    	$API_USER = 'Jasonwoo_test_4FaMha';
    	$API_KEY = '8IBgw3ZhA5iWHDeL';
    	$param = array(
    			'api_user' => $API_USER,     //使用api_user和api_key进行验证
    			'api_key' => $API_KEY,
    			'from' => 'sendcloud@sendcloud.org',     //发信人，用正确邮件地址替代
    			'fromname' => 'MobiWind',
    			'substitution_vars' => $vars,
    			'template_invoke_name' => $template,    //邮件模板
    			'resp_email_id' => 'true'
    	);
    	$data = http_build_query($param);
    	$options = array(
    			'http' => array(
    					'method' => 'POST',
    					'header' => 'Content-Type: application/x-www-form-urlencoded',
    					'content' => $data
    			));
    	$context  = stream_context_create($options);
    	$result = file_get_contents($url, FILE_TEXT, $context);
    	return $result;
    }
    /**
     * 二维码
     * @author   Tomas<416369046@qq.com>
     * @since    2015-10-28
     */
    public function getQRcode($value='',$level='L',$size=4){
    	if($value){
    		include "/LightpenCms/Lib/ORG/QRcode.class.php";
    		$errorCorrectionLevel = 'L';
    		$matrixPointSize = $size;
    		QRcode::png($value, false, $errorCorrectionLevel, $matrixPointSize);
    		exit;
    	}else{
    		return false;
    	}
    	
    }
    /**
     * 共用方法计算pv数
     * @param unknown $group	所属组
     * @param unknown $method	所属控制器
     * @param unknown $action	所属方法
     * @author   Tomas<416369046@qq.com>
     * @since  2015-11-28
     */
    public function countPv($group,$method,$action){
    	$result = M('h5_all_link')->where(array('companyid'=>$this->companyid,'group'=>$group,'method'=>$method,'action'=>$action))->setInc('viewnum');
    }
    /**
     * 有归属链接方法计算pv数
     * @param unknown $id			接收id
     * @param unknown $tableName	接收表名
     * @author   Tomas<416369046@qq.com>
     * @since  2015-12-31
     */
    public function getPv($id,$tableName){
    	$result = M($tableName)->where(array('companyid'=>$this->companyid,'id'=>$id))->setInc('viewnum');
    }
    
    /**
     * 计算二维码扫描次数
     * @param unknown $id			接收id
     * @param unknown $tabName		接收表名
     * @author   Tomas<416369046@qq.com>
     * @since  2015-12-23
     */
    public function CountScannum($id,$tabName){
    	$result = M("$tabName")->where(array('companyid'=>$this->companyid,'id'=>$id))->setInc('scannum');
    }
    /**
     * 系统消息添加
     * @param unknown $typeid 	消息类型所关联表的id
     * @param unknown $type 	消息类型 1、为待处理预约订单，2、待处理订座订单，3、线下报名活动订单，4、商城订单
     * @param unknown $content 	消息内容
     * @author   Tomas<416369046@qq.com>
     * @since  2015-12-30
     */
    public function systemNotices($typeid,$content,$type = 0){
    	$data['companyid'] = $this->companyid;
    	$data['typeid'] = $typeid;
    	$data['type'] = $type;
    	$data['isread'] = 1;
    	$data['content'] = $content;
    	$data['createtime'] = $data['updatetime'] = time();
    	$result = M('system_notices')->add($data);
    	if($result){
    		return  TRUE;
    	}else {
    		return  FALSE;
    	}
    }
    /**
     * 微信公众号管理共用头部
     * @author   Tomas<416369046@qq.com>
     * @since  2016-1-5
     */
    public function wechatManage(){
    	$wechatID = $this->_request('wechatid');
    	if($wechatID){
    	    $where1['id'] = $wechatID;
    	}
    	$where1['companyid'] = $this->companyid;
    	$wechatsInfo = D('Wechats')->where($where1)->find();
    	$this->assign('wechatid',$wechatsInfo['id']);
    	if($wechatsInfo){
    		$today = date('N');
    		$nextUpdateDay = '';
    		switch ($today){
    			case 1:
    				$nextUpdateDay = format_time(strtotime('+5 day'),'ymd');
    				break;
    			case 2:
    				$nextUpdateDay = format_time(strtotime('+4 day'),'ymd');
    				break;
    			case 3:
    				$nextUpdateDay = format_time(strtotime('+3 day'),'ymd');
    				break;
    			case 4:
    				$nextUpdateDay = format_time(strtotime('+2 day'),'ymd');
    				break;
    			case 5:
    				$nextUpdateDay = format_time(strtotime('+8 day'),'ymd');
    				break;
    			case 6:
    				$nextUpdateDay = format_time(strtotime('+7 day'),'ymd');
    				break;
    			case 7:
    				$nextUpdateDay = format_time(strtotime('+6 day'),'ymd');
    				break;
    		}
    		if($wechatsInfo['wechattype'] == 4 && $wechatsInfo['isgetfans'] == 1){
    			$wechatsInfo['html'] = '您的微信粉丝资料已导入成功，下次系统自动更新时间为<span class="red">'.$nextUpdateDay.' 00:00</span>。';
    		}
    	}
    	//session('token',$wechatsInfo['token']);
    	$this->assign('wechatsInfo',$wechatsInfo);
    }
	/**
	 * 
	 * 
	 * 
	 * @param unknown $typeid   模板类型的id
	 * @param unknown $openid   openid
	 * @param unknown $companyid   公司id
	 * @param string $parameter   跳转URL参数
	 * @param unknown $firstadd  array()
	 * @param unknown $content  array()
	 * @param unknown $remark   array()
	 * @return Ambigous <boolean, multitype:, mixed>
	 * @author Mark<1311013341@qq.com>
	 * @since  2016-7-29
	 */
    public function WeChatTemplateMessageSend($typeid, $openid, $companyid, $parameter='', $firstadd='', $content, $remark='',$url=false,$end='',$first=''){
    	if($typeid && $openid && $companyid){ 
    		$tplWhere['wed.companyid'] = $companyid;
    		$tplWhere['wed.typeid'] = $typeid;
    		$tplWhere['wed.isopen'] = $tplWhere['wet.isshow'] = '1';
    		$tplInfo = M()->table('tp_wechat_event_data as wed')->join('left join tp_wechat_event_type as wet on wed.typeid=wet.id')->where($tplWhere)
				->field('wed.tplid,wet.url,wet.data,wet.type')->find();
    		if($tplInfo){
    			if($tplInfo['type'] == '2'){
    				$wechatsInfo = array('token'=>C('helper_wechat_token'),'appid'=>C('helper_wechat_appid'),'appsecret'=>C('helper_wechat_appsecret'));
    			}else{
    				$wechatsInfo = M('wechats')->where(array('companyid'=>$companyid,'wechattype'=>'4'))->field('token,appid,appsecret')->find();
    			}
    			if($wechatsInfo){
    				$color = '#000000';
    				$dataStr['touser'] = $openid;
    				$dataStr['template_id'] = $tplInfo['tplid'];
    				if($url){
    					$dataStr['url'] = $url;
    				}else{
	    				if($tplInfo['url']){
	    					$dataStr['url'] = C('site_url').$tplInfo['url'].$companyid.$parameter;
	    				}
    				}
    				$templatedata = json_decode($tplInfo['data'],true);
    				if($templatedata['frist']){
    					$datacontent['frist']['value'] = $templatedata['frist'];
    					$datacontent['frist']['color'] = $color;
    					if($templatedata['firstadd']){
    						foreach ($templatedata['firstadd'] as $fkey=>$fval){
    							$datacontent['frist']['value'] .= "\n".$fval['0'].'：'.$firstadd[$fkey]."\n";
    						}
    					}
    				}else{
    					if($first){
    						$datacontent['first']['value'] = $first;
    						$datacontent['first']['color'] = $color;
    					}else{
	    					$datacontent['first']['value'] = $templatedata['first'];
	    					$datacontent['first']['color'] = $color;
	    					if($templatedata['firstadd']){
	    						foreach ($templatedata['firstadd'] as $fkey=>$fval){
	    							$datacontent['first']['value'] .= "\n".$fval['0'].'：'.$firstadd[$fkey]."\n";
	    						}
	    					}
    					}
    				}
    				foreach ($templatedata['content'] as $ckey=>$cval){
    					if($cval['2']){
    						$datacontent[$cval['2']]['value'] = $content[$ckey];
    						$datacontent[$cval['2']]['color'] = $color;
    					}else{
	    					$datacontent['keyword'.($ckey+1)]['value'] = $content[$ckey];
	    					$datacontent['keyword'.($ckey+1)]['color'] = $color;
    					}
    				}
    				if($templatedata['remark']){
    					foreach ($templatedata['remark'] as $rkey=>$rval){
    						 $remarkval .= $rval['0'].'：'.$remark[$rkey]."\n";
    					}
    				}
    				if($end){
    					$remarkval.=$end;
    				}else{
    					$remarkval.=$templatedata['end'];
    				}
    				$datacontent['remark']['value'] = $remarkval;
    				$datacontent['remark']['color'] = $color;
    				$dataStr['data'] = $datacontent;
    				$wechat = new Wechat(array('token'=>$wechatsInfo['token'],'appid'=>$wechatsInfo['appid'],'appsecret'=>$wechatsInfo['appsecret']));
    				$sendTemplateMessageData = $wechat->sendTemplateMessage(json_encode($dataStr));
    				return $sendTemplateMessageData;
    			}
    		}
    	}
    }
    /**
     * 
     * 图片弹框
     * 
     * @param string $type(locality="本地图片",wechat="微信素材库")
     * @author Mark<1311013341@qq.com>
     * @since  2016-8-2
     */
    public function ImagesAll($companyid,$type = 'locality'){
		$dbimgname = M('message_locality_images');    //暂无数据库
		$dbgroupname = M('message_locality_images_group');
		$Lists = array();
		$Lists['img'] = $dbimgname->where(1)->field('id,title,token,gid,imageurl,createtime,updatetime')->order('updatetime desc')->select();
		$Lists['group'] = $dbgroupname->where(1)->field('id,title')->order('createtime asc')->select();
		foreach($Lists['group'] as $gkey=>$gval){
			$where['gid'] = $gval['id'];
			$Lists['group'][$gkey]['count'] = $dbimgname->where($where)->count();
		}
    	$this->assign($type.'Lists',$Lists);
    }
    /**
     *
     * 音频弹框
     *
     * @param string $type(locality="本地音频",wechat="微信素材库")
     * @author Mark<1311013341@qq.com>
     * @since  2016-8-2
     */
    public function VoiceAll($companyid,$type = 'locality'){
    	$this->memcManager('get','Voice'.$type.$companyid);
    	$getCont = $this->memcManager('get','Voice'.$type.$companyid);
    	if($getCont){
    		$Lists = $getCont;
    	}else{
    		$where['companyid'] = $companyid;
    		if($type == 'wechat'){
    			$where['token'] = M('wechats')->where(array('companyid'=>$companyid,'wechattype'=>'4'))->getField('token');
    			$dbname = M('message_wechats_voices');
    		}else{
    			$dbname = M('message_locality_voices');    //暂无数据库
    		}
    		$Lists = $dbname->where($where)->field('id,title,token,voicesurl,time,size,createtime,updatetime')->order('updatetime desc')->select();
    		$this->memcManager('set','Voice'.$type.$companyid,$Lists,86400);
    	}
    	$this->assign($type.'VLists',$Lists);
    }
    /**
     *
     * 视频弹窗
     *
     * @param string $type(locality="本地视频",wechat="微信素材库")
     * @author Mark<1311013341@qq.com>
     * @since  2016-8-2
     */
    public function VideoAll($companyid,$type = 'locality'){
    	$this->memcManager('get','Video'.$type.$companyid);
    	$getCont = $this->memcManager('get','Video'.$type.$companyid);
    	if($getCont){
    		$Lists = $getCont;
    	}else{
    		$where['companyid'] = $companyid;
    		if($type == 'wechat'){
    			$where['token'] = M('wechats')->where(array('companyid'=>$companyid,'wechattype'=>'4'))->getField('token');
    			$dbname = M('message_wechats_videos');
    		}else{
    			$dbname = M('message_locality_videos');    //暂无数据库
    		}
    		$Lists = $dbname->where($where)->field('id,title,token,videosurl,createtime,updatetime')->order('updatetime desc')->select();
    		$this->memcManager('set','Video'.$type.$companyid,$Lists,86400);
    	}
    	$this->assign($type.'DLists',$Lists);
    }
    /**
     *
     * ajax分页样式
     *
     * @param unknown $count
     * @param unknown $nowpage
     * @param unknown $limit
     * @return string
     * @author Mark<1311013341@qq.com>
     * @since  2016-10-17
     */
    public function ajaxpage($count,$nowpage,$limit){
    	if(0 == $count) return '';
    	$totalPages   =   ceil($count/$limit);
    	//上下翻页字符串
    	$upRow          =   $nowpage-1;
    	$downRow        =   $nowpage+1;
    	if($totalPages>3){
    		$showNum = 3;
    	}else{
    		$showNum = $totalPages;
    	}
    	$pageHtml = '<span class="item-count">共'.$count.'条记录</span><ul>';
    	if($totalPages > 1){
    		if($nowpage!=1){
    			$pageHtml .='<li><a href="javascript:void(0);" class="js-ajaxpage-num" data-page="'.$upRow.'"><i class="page-prev-icon"></i></a></li>';
    		}
    		if($totalPages < 4 ){
    			for ($page=1;$page<=$showNum;$page++){
    				if($nowpage == $page){
    					$pageHtml .='<li class="item-active"><a href="javascript:void(0);">'.$page.'</a></li>';
    				}else{
    					$pageHtml .='<li><a href="javascript:void(0);" class="js-ajaxpage-num" data-page="'.$page.'" >'.$page.'</a></li>';
    				}
    			}
    		}else{
    			if ($totalPages <= $showNum) {
    				for ($page=1;$page<=$showNum;$page++){
    					if($nowpage == $page){
    						$pageHtml .='<li class="item-active"><a href="javascript:void(0);">'.$page.'</a></li>';
    					}else{
    						$pageHtml .='<li><a href="javascript:void(0);" class="js-ajaxpage-num" data-page="'.$page.'" >'.$page.'</a></li>';
    					}
    				}
    			}else{
    				if($nowpage==1 || $nowpage<$showNum){
    					for ($page=1;$page<=$showNum;$page++){
    						if($nowpage == $page){
    							$pageHtml .='<li class="item-active"><a href="javascript:void(0);">'.$page.'</a></li>';
    						}else{
    							$pageHtml .='<li><a href="javascript:void(0);" class="js-ajaxpage-num" data-page="'.$page.'" >'.$page.'</a></li>';
    						}
    					}
    					$pageHtml .='<li><span><b>···</b></span></li><li><a href="javascript:void(0);" class="js-ajaxpage-num" data-page="'.$totalPages.'" >'.$totalPages.'</a><li>';
    				}elseif ($nowpage>=$showNum && $nowpage <= ($totalPages-$showNum)){
    					$pageHtml .='<li><a href="javascript:void(0);" class="js-ajaxpage-num" data-page="1" >1</a></li><li><span><b>···</b></span></li>';
    					for ($page=($nowpage-1);$page<=($nowpage+1);$page++){
    						if($nowpage == $page){
    							$pageHtml .='<li class="item-active"><a href="javascript:void(0);">'.$page.'</a></li>';
    						}else{
    							$pageHtml .='<li><a href="javascript:void(0);" class="js-ajaxpage-num" data-page="'.$page.'" >'.$page.'</a></li>';
    						}
    					}
    					$pageHtml .='<li><span><b>···</b></span></li><li><a href="javascript:void(0);" class="js-ajaxpage-num" data-page="'.$totalPages.'" >'.$totalPages.'</a></li>';
    				}elseif ($nowpage==$totalPages || $nowpage>($totalPages-$showNum)){
    					$pageHtml .='<li><a href="javascript:void(0);" class="js-ajaxpage-num" data-page="1" >1</a></li><li><span><b>···</b></span></li>';
    					for ($page=($totalPages-$showNum+1);$page<=$totalPages;$page++){
    						if($nowpage == $page){
    							$pageHtml .='<li class="item-active"><a href="javascript:void(0);">'.$page.'</a></li>';
    						}else{
    							$pageHtml .='<li><a href="javascript:void(0);" class="js-ajaxpage-num" data-page="'.$page.'" >'.$page.'</a></li>';
    						}
    					}
    				}
    			}
    		}
    		if($nowpage!=$totalPages){
    			$pageHtml .='<li><a href="javascript:void(0);" class="js-ajaxpage-num" data-page="'.$downRow.'"><i class="page-next-icon"></i></a></li>';
    		}
    		$pageHtml .= '</ul>';
    		if($totalPages > 3 ){
    			$pageHtml .= '<label class="page-go">跳转至 <input class="inline text-center js-pagenum" type="text" value="'.$nowpage.'"> 页 <i class="page-go-icon js-page-go-icon"></i></label>';
    		}
    	}
    	return $pageHtml;
    }
    /**
     * 递归生成券号
     * @param unknown $companyid
     * @return string|boolean
     * @author Tomas<416369046@qq.com>
     * @since  2016-10-20
     */
    public function getSNCode($type,$companyid){
    	$sncode = rand(1,9).rand(10,99).rand(10,99).rand(1,9);
    	if($type == 3){
    		$sn = '19'.str_pad($companyid, 5, '0', STR_PAD_LEFT).$sncode;
    	}elseif($type == 4){
    		$sn = '16'.str_pad($companyid, 5, '0', STR_PAD_LEFT).$sncode;
    	}elseif($type == 5){
    		$sn = '18'.str_pad($companyid, 5, '0', STR_PAD_LEFT).$sncode;
    	}elseif($type == 6){
    		$sn = '20'.str_pad($companyid, 5, '0', STR_PAD_LEFT).$sncode;
    	}elseif($type == 7){
    		$sn = '11'.str_pad($companyid, 5, '0', STR_PAD_LEFT).$sncode;
    	}elseif($type == 8){
    		$sn = '12'.str_pad($companyid, 5, '0', STR_PAD_LEFT).$sncode;
    	}elseif($type == 9){
    		$sn = '14'.str_pad($companyid, 5, '0', STR_PAD_LEFT).$sncode;
    	}elseif($type == 10){
    		$sn = '15'.str_pad($companyid, 5, '0', STR_PAD_LEFT).$sncode;
    	}elseif($type == 40){
    		$sn = '21'.str_pad($companyid, 5, '0', STR_PAD_LEFT).$sncode;
    	}
    	if($sn){
    		$count = M('member_vouchers')->where(array('companyid'=>$companyid,'sn'=>$sn))->count();
    		if($count>0){
    			return $this->getSNCode($type, $companyid);
    		}else{
    			return  $sn;
    		}
    	}else{
    		return false;
    	}
    }
    /**
     *
     * 会员标签
     *
     * @return multitype:multitype:multitype:string
     * @author Mark<1311013341@qq.com>
     * @since  2016-10-22
     */
    public function memberTags($data){
    	$Tage = array(
    			'registertype'=>array(   //会员来源
    					array('id'=>'1','name'=>'网页注册'),
    					array('id'=>'2','name'=>'老会员导入')
    			),
    			'gender'=>array(     //性别
    					array('id'=>'0','name'=>'未填写性别'),
    					array('id'=>'1','name'=>'先生'),
    					array('id'=>'2','name'=>'女士')
    			),
    			'age'=>array(      //年龄
    					array('id'=>'0','name'=>'未填写年龄'),
    					array('id'=>'1','name'=>'10后'),
    					array('id'=>'2','name'=>'00后'),
    					array('id'=>'3','name'=>'90后'),
    					array('id'=>'4','name'=>'80后'),
    					array('id'=>'5','name'=>'70后'),
    					array('id'=>'6','name'=>'60后'),
    					array('id'=>'7','name'=>'50后及以上')
    			),
    			'constellation'=>array(      //星座
    					array('id'=>'1','name'=>'水瓶座'),
    					array('id'=>'2','name'=>'双鱼座'),
    					array('id'=>'3','name'=>'白羊座'),
    					array('id'=>'4','name'=>'金牛座'),
    					array('id'=>'5','name'=>'双子座'),
    					array('id'=>'6','name'=>'巨蟹座'),
    					array('id'=>'7','name'=>'狮子座'),
    					array('id'=>'8','name'=>'处女座'),
    					array('id'=>'9','name'=>'天秤座'),
    					array('id'=>'10','name'=>'天蝎座'),
    					array('id'=>'11','name'=>'射手座'),
    					array('id'=>'12','name'=>'摩羯座')
    			),
    			'subscribetype'=>array(       //微信关注状态
    					array('id'=>'1','name'=>'微信已关注'),
    					array('id'=>'0','name'=>'微信未关注'),
    					array('id'=>'2','name'=>'微信取关')
    			),
    			'howlongspending'=>array(      //多久未消费
    					array('id'=>'0','name'=>'从未消费'),
    					array('id'=>'1','name'=>'2周未消费'),
    					array('id'=>'2','name'=>'1月未消费'),
    					array('id'=>'3','name'=>'2月未消费'),
    					array('id'=>'4','name'=>'3月未消费'),
    					array('id'=>'5','name'=>'半年未消费'),
    					array('id'=>'6','name'=>'1年未消费')
    			),
    			'spendingfrequency'=>array(    //年消费频次
    			        array('id'=>'0','name'=>'0次消费'),
    			        array('id'=>'1','name'=>'1次消费'),
    					array('id'=>'2','name'=>'2-10次消费'),
    					array('id'=>'3','name'=>'11-50次消费'),
    					array('id'=>'4','name'=>'51次以上消费')
    			),
    			'totalspending'=>array(      //累计消费
    			        array('id'=>'0','name'=>'0元消费'),
    			        array('id'=>'1','name'=>'1-200元消费'),
    					array('id'=>'2','name'=>'201-500元消费'),
    					array('id'=>'3','name'=>'501-1000元消费'),
    					array('id'=>'4','name'=>'1001-3000元消费'),
    					array('id'=>'5','name'=>'3001-5000元消费'),
    					array('id'=>'6','name'=>'5001-10000元消费'),
    					array('id'=>'7','name'=>'10001元以上消费')
    			),
    			'howlongusevouchers'=>array(      // 多久使用卡券
    					array('id'=>'1','name'=>'从未使用'),
    					array('id'=>'2','name'=>'1周未使用'),
    					array('id'=>'3','name'=>'2周未使用'),
    					array('id'=>'4','name'=>'1月未使用'),
    					array('id'=>'5','name'=>'2月未使用'),
    					array('id'=>'6','name'=>'3月未使用'),
    					array('id'=>'7','name'=>'半年未使用'),
    					array('id'=>'8','name'=>'1年未使用')
    			),
    			'usevouchersfrequency'=>array(    // 卡券使用频次
    					array('id'=>'1','name'=>'从未使用'),
    					array('id'=>'2','name'=>'1周使用一次'),
    					array('id'=>'3','name'=>'8-14天使用一次'),
    					array('id'=>'4','name'=>'15-30天使用一次'),
    					array('id'=>'5','name'=>'31-60天使用一次'),
    					array('id'=>'6','name'=>'61-180天使用一次'),
    					array('id'=>'7','name'=>'181天以上使用一次')
    			)
    	);
    	if($data){
    		return $Tage[$data];
    	}else{
    		return $Tage;
    	}
    }
    /**
     * 
     * 根据出生日期判断星座
     * 
     * @param unknown $birthday 日期格式  0000-00-00
     * @return boolean|multitype:
     * @author Mark<1311013341@qq.com>
     * @since  2016-10-27
     */
    public function constellation($birthday){
    	if ($birthday == '0000-00-00'||!$birthday) return '0';
    	$day   = intval(format_time(strtotime($birthday),'d'));
    	$month = intval(format_time(strtotime($birthday),'m'));
    	if ($month < 1 || $month > 12 || $day < 1 || $day > 31) return false;
    	$signs = array(
    			array('20'=>'1'),
    			array('19'=>'2'),
    			array('21'=>'3'),
    			array('20'=>'4'),
    			array('21'=>'5'),
    			array('22'=>'6'),
    			array('23'=>'7'),
    			array('23'=>'8'),
    			array('23'=>'9'),
    			array('24'=>'10'),
    			array('22'=>'11'),
    			array('22'=>'12')
    	);
    	list($start, $constellationTagid) = each($signs[$month-1]);
    	if ($day < $start)
    		list($start, $constellationTagid) = each($signs[($month-2 < 0) ? 11 : $month-2]);
    	return $constellationTagid;
    }
    /**
     * 
     * 根据出生日期判断年龄
     * 
     * @param unknown $birthday
     * @return boolean|multitype:
     * @author Mark<1311013341@qq.com>
     * @since  2016-10-27
     */
    public function agetag($birthday){
    	if ($birthday == '0000-00-00'||!$birthday) return '0';
    	$year   = intval(format_time(strtotime($birthday),'y'));
    	if($year<1960){
    		$ageTagid = '7';
    	}elseif(1960<=$year && $year<1970){
    		$ageTagid = '6';
    	}elseif(1970<=$year && $year<1980){
    		$ageTagid = '5';
    	}elseif(1980<=$year && $year<1990){
    		$ageTagid = '4';
    	}elseif(1990<=$year && $year<2000){
    		$ageTagid = '3';
    	}elseif(2000<=$year && $year<2010){
    		$ageTagid = '2';
    	}else{//if(2010<=$year && $year<2010){
    		$ageTagid = '1';
    	}
    	return $ageTagid;
    }
    /**
     * 
     * registertype:注册来源标签统计:1:网页注册; 2:老会员导入; 数据储存格式JSON, 如：{"1":12,"2":20}
     * spendingfrequency:消费频次标签: 0:0次标签;1:1次消费; 2:2-10次消费; 3:11-50次消费; 4:51次以上消费; 数据储存格式JSON
     * totalspending:累计消费额标签: 0:0元消费；1:1-200元; 2:201-500元; 3:501-1000元; 4:1001-3000元; 5:3001-5000元; 6:5001-10000元; 7:10001元以上; 数据储存格式JSON
     * 
     * 
     * @param unknown $companyid   公司ID
     * @param unknown $data   array(array('name'=>'需要修改的标签字段','before'=>'之前的id','after'=>'需要修改的id'));
     * @return boolean
     * @author Mark<1311013341@qq.com>
     * @since  2016-10-27
     */
    public function memberTagCount($companyid,$data){
    	if(!$data) return false;
    	$num = 0;
    	$where['companyid'] = $companyid;
    	foreach($data as $key=>$val){
    		if($val['name'] == 'membertagsid'){
    			if($val['before']&&$val['before']!=','&&$val['after']!=','&&$val['after']){
	    			$before = explode(',',$val['before']);
	    			$after = explode(',',$val['after']);
    				$newafter = array_diff($after,$before);
    				$newbefore = array_diff($before,$after);
    				if($newafter){
    					$List = M('member_group')->where(array('companyid'=>$companyid,'id'=>array('in',$newafter)))->field('id,membernum')->select();
    					$numa = 0;
    					foreach ($List as $lkey=>$lval){
    						$resulta = M('member_group')->where(array('companyid'=>$companyid,'id'=>$lval['id']))->save(array('membernum'=>$lval['membernum']+1,'updatetime'=>time()));
    						if($resulta){
    							$numa+=1;
    						}
    					}
    					if($numa == count($List)){
    						$resulta = 1;
    					}
    				}
    				if($newbefore){
    					$List = M('member_group')->where(array('companyid'=>$companyid,'id'=>array('in',$newbefore)))->field('id,membernum')->select();
    					$numb = 0;
    					foreach ($List as $lkey=>$lval){
    						if(!$lval['membernum'] || $val['membernum'] == '0'){
    							$resultb = '1';
    						}else{
    							$resultb = M('member_group')->where(array('companyid'=>$companyid,'id'=>$lval['id']))->save(array('membernum'=>$lval['membernum']-1,'updatetime'=>time()));
    						}
    						if($resultb){
    							$numb+=1;
    						}
    					}
    					if($numb == count($List)){
    						$resulta = 1;
    					}
    				}
    				if($newbefore&&$newafter){
    					if($resulta&&$resultb){
    						$result = 1;
    					}
    				}elseif($newbefore){
    					if($resultb){
    						$result = 1;
    					}
    				}elseif($newafter){
    					if($resulta){
    						$result = 1;
    					}
    				}
    			}elseif($val['after']&&$val['after']!=','){
    				$newafter = $val['after'];
    				$List = M('member_group')->where(array('companyid'=>$companyid,'id'=>array('in',$newafter)))->field('id,membernum')->select();
    				$numa = 0;
    				foreach ($List as $lkey=>$lval){
    					$resulta = M('member_group')->where(array('companyid'=>$companyid,'id'=>$lval['id']))->save(array('membernum'=>$lval['membernum']+1,'updatetime'=>time()));
    					if($resulta){
    						$numa+=1;
    					}
    				}
    				if($numa == count($List)){
    					$result = 1;
    				}
    			}elseif($val['before']&&$val['before']!=','){
    				$newbefore = $val['before'];
    				$List = M('member_group')->where(array('companyid'=>$companyid,'id'=>array('in',$newbefore)))->field('id,membernum')->select();
    				$numb = 0;
    				foreach ($List as $lkey=>$lval){
    					if(!$lval['membernum'] || $val['membernum'] == '0'){
    						$resultb = '1';
    					}else{
    						$resultb = M('member_group')->where(array('companyid'=>$companyid,'id'=>$lval['id']))->save(array('membernum'=>$lval['membernum']-1,'updatetime'=>time()));
    					}
    					if($resultb){
    						$numb+=1;
    					}
    				}
    				if($numb == count($List)){
    					$result = 1;
    				}
    			}
    		}elseif($val['name'] == 'rankid'){
    			if($val['after']){
	    			$after = M('member_card_rank')->where(array('companyid'=>$companyid,'id'=>$val['after']))->getField('reportnumber');
	    			if(!$after || $after == '0'){
	    				$resulta = M('member_card_rank')->where(array('companyid'=>$companyid,'id'=>$val['after']))->save(array('reportnumber'=>'1','updatetime'=>time()));
	    			}else{
	    				$resulta = M('member_card_rank')->where(array('companyid'=>$companyid,'id'=>$val['after']))->save(array('reportnumber'=>$after+1,'updatetime'=>time()));
	    			}
    			}
    			if($val['before']){
	    			$before = M('member_card_rank')->where(array('companyid'=>$companyid,'id'=>$val['before']))->getField('reportnumber');
	    			if(!$before || $before == '0'){
	    				$resultb = M('member_card_rank')->where(array('companyid'=>$companyid,'id'=>$val['before']))->save(array('reportnumber'=>'0','updatetime'=>time()));
	    			}else{
	    				$resultb = M('member_card_rank')->where(array('companyid'=>$companyid,'id'=>$val['before']))->save(array('reportnumber'=>$before-1,'updatetime'=>time()));
	    			}
    			}
    			if($val['before']&&$val['after']){
    				if($resulta&&$resultb){
    					$result = 1;
    				}
    			}elseif($val['before']){
    				if($resultb){
    					$result = 1;
    				}
    			}elseif($val['after']){
    				if($resulta){
    					$result = 1;
    				}
    			}
    		}else{
    			$count = M('report_member_system_group')->where($where)->count();
    			$tagList = $this->memberTags($val['name']);
    			if($count>0){
    				$groupInfo = M('report_member_system_group')->where($where)->getField($val['name']);
    				$array = array();
    				if($groupInfo){
    					$groupInfo = json_decode($groupInfo,true);
    					foreach ($groupInfo as $gkey=>$gval){
    						if($gkey == $val['before']){
    							if(!$gval){
    								$array[$gkey] = '0';
    							}else{
    								$array[$gkey] = $gval-1;
    							}
    						}elseif($gkey == $val['after']){
    							$array[$gkey] = $gval+1;
    						}else{
    							if(!$gval){
    								$array[$gkey] = '0';
    							}else{
    								$array[$gkey] = $gval;
    							}
    						}
    					} 
    				}else{
    					foreach ($tagList as $tkey=>$tval){
    						if($val['after'] == $tval['id']){
    							$array[$tval['id']] = '1';
    						}else{
    							$array[$tval['id']] = '0';
    						}
    					}
    				}
    				$sava[$val['name']] = json_encode($array);
    				$sava['updatetime'] = time();
    				$result = M('report_member_system_group')->where($where)->save($sava);
    			}else{
    				$gdata['id'] = guidNow();
    				$gdata['companyid'] = $companyid;
    				$gdata['updatetime'] = $gdata['createtime'] = time();
	    			foreach ($tagList as $tkey=>$tval){
	    				if($val['after'] == $tval['id']){
	    					$array[$tval['id']] = '1';
	    				}else{
	    					$array[$tval['id']] = '0';
	    				}
	    			}
	    			$gdata[$val['name']] = json_encode($array);
	    			$result = M('report_member_system_group')->add($gdata);
    			}
    		}
    		if($result){
    			$num+=1;
    		}
    	}
    	if($num == count($data)){
	    	return true;
    	}else{
    		return false;
    	}
    }
    /**
     * 
     * 生成拉粉二维码
     * 
     * @param unknown $companyid 公司ID	
     * @param number $userid 子账号ID
     * @param number $isboss 是否是BOSS账号
     * @author Asa<asa@renlaifeng.cn>
     * @since  2016-11-1
     */
    public function createLafenCode($companyid,$userid=0,$username,$isboss=2){
    	M()->startTrans();
    	$wechat = M('wechats')->where(array('companyid'=>$companyid))->field('token,appid,appsecret,wxname')->find();
    	$date['content'] = M('quick_response_code_max_scene_id')->where(array('id'=>1))->getField('max_scene_id')+1;
    	$userInfo = M('quick_response_code')->where(array('companyid'=>$companyid,'userid'=>$userid))->find();
    	if(!$userInfo){
    		if($wechat && $date['content']){
    			$weixin = new Wechat(array('token'=>$wechat['token'],'appid'=>$wechat['appid'],'appsecret'=>$wechat['appsecret']));
    			$QRCodeInfo = $weixin->getQRCode($date['content'],1);
    			if($QRCodeInfo){
    				$QRCodeInfoSrc = $weixin->getQRUrl($QRCodeInfo['ticket']);
    				$date['type'] = '1';
    				$date['picurl'] = $QRCodeInfoSrc;
    				$date['dimension'] = '430*430';
    				$date['companyid'] = $companyid;
    				$date['id'] = guidNow();
    				$date['wechatName'] = $wechat['wxname'];
    				$date['name'] = $username."的拉粉码";
    				$date['userid'] = $userid;
    				$date['isboss'] = $isboss;
    				$date['updatetime'] = $date['createtime'] = time();
    				$return = M('quick_response_code')->add($date);
    				$data['scene_id'] = $maxSceneId['max_scene_id'] = $date['content'];
    				$data['updatetime'] = $maxSceneId['updatetime'] = time();
    				$companyReturn = M('company')->where(array('id'=>$companyid))->save($data);
    				$maxSceneIdReturn = M('quick_response_code_max_scene_id')->where(array('id'=>1))->save($maxSceneId);
    				if($return && $companyReturn && $maxSceneIdReturn){
    					M()->commit();
    					$ajax['code'] = '200';
    					$ajax['msg'] = '生成成功';
    				}else{
    					M()->rollback();
    					$ajax['msg'] = '生成失败';
    				}
    			}else{
    				$ajax['msg'] = '生成失败';
    			}
    		}
    	}else{
    		$ajax['code'] = '600';
    		$ajax['msg'] = '已经存在过了';
    	}
    	return $ajax;
    }
    public function updateLafenCode($companyid,$userid,$username){
    	$date['name'] = $username."的拉粉码";
    	$where['companyid'] = $companyid;
    	$where['userid'] = $userid;
    	$return = M('quick_response_code')->where($where)->save($date);
    }
    /**
     * 获取、修改积分接口
     * @param unknown $methodName 1可用积分：getUserCanUseIntegral；2累计积分：getUserCumulativeIntegral；3积分修改：modifyUserCumulativeIntegral；
     * @param unknown $userName 用户账号名称
     * @param unknown $integral 需要修改的积分数量 ，增加是为正数，减少时为负数，积分不能为零（只有在积分修改时用）
     * @author 姚成凯<kevin@renlaifeng.cn>
     * @since  2016-9-9
     */
    public function integralJK($methodName, $userName, $integral = ''){
    	$InterfaceUrl = 'http://bbs.mobiwind.cn/api/scrm5/integral.php';    // 接口地址
    	// $data['methodName'] = 'getUserCanUseIntegral';
    	// $data['methodName'] = 'getUserCumulativeIntegral';
    	// $data['userName'][0] = 'renlaifeng';
    	// $data['userName'][1] = 'renlaifeng1';
    	$data['methodName'] = $methodName;
    	$data['userName'] = $userName;
    	if($methodName == 'modifyUserCumulativeIntegral'){
    		$data['integral'] = $integral;
    	}
    	$result = http_post($InterfaceUrl, self::SignaTure(json_encode($data)));
    	return $result;
    }
    /**
     * 消息加密并生成签名
     * @param unknown $json json格式
     * @return string
     * @author Mark<1311013341@qq.com>
     * @since  2016-8-23
     */
    private function SignaTure($json){
    	$encrypted = "";
    	$json_data = base64_encode($json);  // 对数据进行Base加密
    	//$priKey = file_get_contents("http://www.mobiwind.cn/LightpenData/apiKey/rsa_private_key.pem");  // 读取私钥（加密规则）
    	//$pri_key = openssl_pkey_get_private($priKey);    // 判断私钥是否可用
    	//openssl_sign($json_data, $encrypted, $pri_key, OPENSSL_ALGO_SHA1);    // 加密函数加密(生成签名)
    	//openssl_private_encrypt($data,$encrypted,$pri_key);      // 私钥加密
    	$encrypted = base64_encode($encrypted);
    	$date = '{"data":"'.$json_data.'","sign":"'.$encrypted.'"}';
    	return $date;
    }
    /**
     * 【新】SCRM5商品ID、订单号、交易号、券号生成规则
     * 商品ID：p+商品代表字母+公司号+5位定位流水 =》实物商品：g；积分商品：p；
     * 订单号：代表字母+生成日期时间+companyID+3位定位流水 =》 eshop：E、手机预订：B、门店收银：P、闪惠：W、外卖：P、手机点单：T、客房点单：H、代金券销售：V、团购券销售：G、计次卡销售：A、打赏：K、积分商城：J、增值业务：N、储值交易：S
     * 交易号：x+交易类型代表字母+生成日期时间+companyID+3位定位流水 =》微信支付 ：W、支付宝支付：A、现金支付：C、储值支付 ：S、 APPLEPAY支付：P、 银联借记卡支付：Y、VISA信用卡支付 ：V、万事达信用卡支付 ：M、积分获取 ：H、积分消耗 ：G、 储值充值：B
     * 券号    ：代表数字+companyID+6位定位随机数 =》eshop优惠券：11；线下优惠券：12；微信互通券：13；兑换券：14；红包：15；团购券：16；代金券：17；门票：18；记次卡：19；权益卡：20；
     * @param unknown $type 类型 =》1：商品ID；2：订单号；3：交易号；4：券号；
     * @param unknown $prefix 代表字母（数字）
     * @param unknown $companyid 公司ID
     * @return string
     * @author 姚成凯<kevin@renlaifeng.cn>
     * @since  2016-11-15
     */
    public function newOrderID($type, $prefix, $companyid){
    	$time = time();
    	if($type == 1){ //--------------------------- 商品id ---------------------------\\
    		if($prefix == 'g'){
    			$serialNumber = M('mall_goods')->where(array('companyid'=>$companyid))->order('createtime DESC')->getField('id');
    		}else{
    			$serialNumber = M('mall_member_integral_goods')->where(array('companyid'=>$companyid))->order('createtime DESC')->getField('id');
    		}
    		$serialNumber = str_pad(substr($serialNumber, -5)+1, 5, '0', STR_PAD_LEFT);
    		return 'p' . $prefix . str_pad($companyid, 5, '0', STR_PAD_LEFT) . str_pad($serialNumber, 5, '0', STR_PAD_LEFT);
    	}elseif($type == 2){ //--------------------------- 订单号 ---------------------------\\
    		if($prefix == 'E'){ //eshop订单
    			$actionName = 'mall_order_info';
    		}elseif($prefix == 'B'){ //手机预订
    			$actionName = 'mobile_book_order';
    		}elseif($prefix == 'P'){ //门店收银
    			$actionName = 'member_spending';
    		}elseif($prefix == 'W'){ //闪惠
    			$actionName = 'shanhui_order';
    			$shanhuiInfo = M($actionName)->field('orderid')->order('createtime DESC')->find();
    			if(strlen($shanhuiInfo['orderid']) > 10){
    				$orderid = '2017031301'; // 起始值
    			}else{
    				$orderid = $shanhuiInfo['orderid'] + 1; // 有起始值之后的订单id
    			}
    			return $orderid;
    		}elseif($prefix == 'F'){ //外卖
    			$actionName = 'takeout_order';
    		}elseif($prefix == 'T'){ //手机点单
    			$actionName = 'mobilephoneorder_order';
    		}elseif($prefix == 'H'){ //客房点单
    			$actionName = 'guestroom_order';
    		}elseif($prefix == 'V'){ //代金券销售
    			$actionName = '';
    		}elseif($prefix == 'G'){ //团购券销售
    			$actionName = '';
    		}elseif($prefix == 'A'){ //记次卡销售
    			$actionName = '';
    		}elseif($prefix == 'K'){ //打赏
    			$actionName = '';
    		}elseif($prefix == 'J'){ //积分商城
    			$actionName = 'mall_member_integral_order_info';
    		}elseif($prefix == 'N'){ //增值业务
    			$actionName = 'check_hardware_order';
    		}elseif($prefix == 'S'){ //储值交易
    			$actionName = 'storedvalue_order';
    		}elseif($prefix == 'CC'){ //付费商店年卡
    			$actionName = 'pay_shop_member_card_order';
    		}elseif($prefix == 'CG'){ //付费商店单个商品
    			$actionName = 'pay_shop_goods_order';
    		}elseif($prefix == 'ST'){ //声悦传情
    			$actionName = 'sound_joy_goods_order';
    		}
    		$serialNumber = M($actionName)->where(array('companyid'=>$companyid,'createtime'=>array('between',array($time,$time+1))))->count();
    		return $prefix . substr(date('YmdHis',time()),2) . str_pad($companyid, 5, '0', STR_PAD_LEFT) . str_pad($serialNumber+1, 3, '0', STR_PAD_LEFT);
    	}elseif($type == 3){ //--------------------------- 订交易号 ---------------------------\\
    		if($prefix=='B' || $prefix=='W' || $prefix=='A' || $prefix=='C' || $prefix=='S' || $prefix=='P' || $prefix=='D' || $prefix=='S'){ //微信支付、支付宝支付、现金支付、储值支付、APPLEPAY支付、刷卡支付、储值充值
    			$actionName = 'member_spending';
    		}elseif($prefix == 'H' || $prefix == 'G'){ //积分获取、积分消耗
    			$actionName = 'member_integral';
    		}
    		$serialNumber = M($actionName)->where(array('companyid'=>$companyid,'createtime'=>array('between',array($time,$time+1))))->count();
    		return 'x' . $prefix . substr(date('YmdHis',time()),2) . str_pad($companyid, 5, '0', STR_PAD_LEFT) . str_pad($serialNumber+1, 3, '0', STR_PAD_LEFT);
    	}elseif($type == 4){ //--------------------------- 券号 ---------------------------\\
    		return $this->newGetSNCode($prefix, $companyid);
    	}
    }
    /**
     * 【新】券号生成规则
     * @param unknown $type
     * @param unknown $prefix
     * @param unknown $companyid
     * @author 姚成凯<kevin@renlaifeng.cn>
     * @since  2016-11-15
     */
    public function newGetSNCode($prefix, $companyid){
    	$sncode = rand(1,9).rand(10,99).rand(10,99).rand(1,9);
    	$sn = $prefix . str_pad(1, 5, '0', STR_PAD_LEFT) . $sncode;
    	$count = M('vouchers')->where(array('sn'=>$sn))->count();
    	if($count>0){
    		return $this->newGetSNCode($prefix, $companyid);
    	}else{
    		return $sn;
    	}
	}
    /**
     * 【新版】统一调用发券放方法
     * @param $activitiesid （多种类型：商城购买的参数是orderid，SCRM5活动是活动id，会员等级升级是rankid）
     * @param $mid （会员id）
     * @param $activitiestype 活动赠券类型： 
     * 	1：会员触发活动						（暂未用到）
     * 	2：线下活动报名						（暂未用到）
     * 	4：会员升级卡类型送电子券
     * 	5：签到活动赠券						（暂未用到）
     * 	6：eshop商城购买-券商品
     * 	7：积分商城积分兑换券发券
     * 	8：eshop商城购买-计次卡-团购-门票
     * 	9：SCRM活动（定时批量赠券活动生日送券活动；首次微信关注奖励活动；完善会员100%资料立赠活动；新会员注册奖励活动；首次消费赠券活动；沉睡用户唤醒活动；）
     * 	10：SCRM活动（领券活动-固定每人发一张券，总数固定，发完即止；）
     * 	11：eshop商城购买-卡券礼包
     * 	12：裂变卡券与裂变红包
     * 	13：快捷赠券、邀请赠礼、声悦传情
     * 	14：百宝箱
     * 	15：积分商城购买计次卡、团购、门票、权益卡
     * 	16：积分商城购买卡券礼包
     * @param $voucherskuid  卡券的SKUid（13可用）
     * @param $vouchertype   卡券的类型（13可用）1、Eshop优惠券；2、门店使用优惠券；3、兑换券；4、红包；5、记次卡；6、团购；7、门票；8、权益卡 ；40、通用券；
     * @param $goodtype   商品的类型（购物车购买卡券可用）
     * @param $getVouchersType 获取卡券方式（用于区别快捷赠券与邀请赠礼）1：快捷赠券；2：声悦传情；默认：邀请赠礼
     */
    public function sendMemberVouchersSCRM5($activitiesid,$mid,$companyid,$activitiestype = '1',$voucherskuid,$vouchertype,$goodtype,$getVouchersType){
    	$returnData['code'] = '300';
    	$returnData['msg'] = '';
    	$returnData['data'] = array('sendnum'=>0);
    	if($activitiesid&&$mid&&$companyid){
    		//获取会员的openid
    		$openid = M('member_wechat_info')->where(array('companyid'=>$companyid,'mid'=>$mid))->getField('openid');
    		$infos = '';
    		if($activitiestype == '1'){
    			//会员触发活动
    			$infos = M()->table('tp_member_marketing_activities_voucher as voucher')->join('tp_member_marketing_activities_voucher_info as vinfo on vinfo.id=voucher.vouchersid')->where(array('voucher.companyid'=>$companyid,'voucher.id'=>$activitiesid))
    			->field('vinfo.voucherdesc,voucher.prefix,voucher.title as titles,vinfo.id,vinfo.useshops,vinfo.voucherdesc,vinfo.vouchertype,vinfo.parvalue,vinfo.minparvalue,vinfo.maxparvalue,vinfo.israndom,vinfo.title,vinfo.usestarttime,vinfo.useendtime,vinfo.usetimetype,vinfo.usetimedeferred,vinfo.vouchercreatetype,vinfo.vouchercreatecatid,vinfo.usetimelimittype,vinfo.usetimelimitset,vinfo.iscansend,vinfo.useissite,vinfo.discounttype,vinfo.minus,vinfo.discount,vinfo.fullminus,vinfo.fulldiscount,vinfo.eachfullminus')->select();
    		}elseif ($activitiestype=='2'){
    			//线下活动报名
    			$infos = M()->table('tp_member_line_apply_activities as activities')->join('tp_member_marketing_activities_voucher_info as vinfo on vinfo.id=activities.vouchersid')->where(array('activities.companyid'=>$companyid,'activities.id'=>$activitiesid))
    			->field('activities.prefix,vinfo.id,vinfo.useshops,vinfo.voucherdesc,vinfo.vouchertype,vinfo.parvalue,vinfo.minparvalue,vinfo.maxparvalue,vinfo.israndom,vinfo.title,vinfo.usestarttime,vinfo.useendtime,vinfo.usetimetype,vinfo.usetimedeferred,vinfo.vouchercreatetype,vinfo.vouchercreatecatid,vinfo.usetimelimittype,vinfo.usetimelimitset,vinfo.iscansend,vinfo.useissite,vinfo.discounttype,vinfo.minus,vinfo.discount,vinfo.fullminus,vinfo.fulldiscount,vinfo.eachfullminus')->select();
    		}elseif ($activitiestype=='4'){
    			//会员升级卡类型送电子券
    		    $infos = array();
    			$lists = M('member_cardrank_voucher')->where(array('companyid'=>$companyid,'rankid'=>$activitiesid))->field('voucherid,type,sku,num')->select();
    			if($lists){
    				foreach ($lists as $lkey=>$lval){
    					if($lval['type'] == '1'||$lval['type'] == '2'||$lval['type'] == '3'||$lval['type'] == '4'||$lval['type'] == '40'){
	    					$infos[$lkey] = M('member_marketing_activities_voucher_info')->where(array('companyid'=>$companyid,'id'=>$lval['voucherid']))->field('id,useshops,usescene,voucherdesc,vouchertype,parvalue,minparvalue,maxparvalue,israndom,title,usestarttime,useendtime,usetimetype,usetimedeferred,usetimelimittype,usetimelimitset,iscansend,useissite,discounttype,minus,discount,fullminus,fulldiscount,eachfullminus')->find();
    					}elseif($lval['type'] == '8'){
    						$infos[$lkey] = M('mall_goods')->where(array('id'=>$lval['voucherid'],'companyid'=>$companyid))->field('id,goodtype as vouchertype,vouchersid,prefix,pricetype,title,usetimelimittype,usetimelimitset,useshopslimitset as useshops,backorderpolicyset,useinfo as voucherdesc')->find();
    					}else{
    						$infos[$lkey] = M('mall_goods')->where(array('id'=>$lval['voucherid'],'companyid'=>$companyid))->field('id,goodtype as vouchertype,vouchersid,prefix,pricetype,title,usetimelimittype,usetimelimitset,useshopslimitset as useshops,backorderpolicyset,useinfo as voucherdesc')->find();
    						$infos[$lkey]['goodskuid'] = $lval['sku'];
    						$infos[$lkey]['goodskuname'] = M('mall_goods_sku')->where(array('companyid'=>$companyid,'goodid'=>$lval['voucherid'],'id'=>$lval['sku']))->getField('name');
    					}
    					$infos[$lkey]['num'] = $lval['num'];
    					$infos[$lkey]['memberType'] = $lval['type'];
    				}
    			}
    			$deliverychannel = 1;
    		}elseif($activitiestype == '5'){
    			//签到互动赠券
    			 
    		}elseif($activitiestype == '6'){
    			//eshop商城购买券
    			$infos = M()->table('tp_mall_order_goods as activities')->join('tp_member_marketing_activities_voucher_info as vinfo on vinfo.id = activities.vouchersid')->where(array('activities.companyid'=>$companyid,'activities.orderid'=>$activitiesid,'activities.goodtype'=>$goodtype))
    			->field('activities.goodnum AS num,activities.vouchersid,activities.goodid,activities.orderid,activities.goodskuid,activities.backorderpolicyset,vinfo.id,vinfo.useshops,vinfo.usescene,vinfo.voucherdesc,vinfo.vouchertype,vinfo.parvalue,vinfo.minparvalue,vinfo.maxparvalue,vinfo.vouchercreatetype,vinfo.vouchercreatecatid,vinfo.israndom,vinfo.title,vinfo.usestarttime,vinfo.useendtime,vinfo.usetimetype,vinfo.usetimedeferred,vinfo.usetimelimittype,vinfo.usetimelimitset,vinfo.iscansend,vinfo.useissite,vinfo.discounttype,vinfo.minus,vinfo.discount,vinfo.fullminus,vinfo.fulldiscount,vinfo.eachfullminus')->select();
    			foreach($infos as $key=>$val){
    				$infos[$key]['originalprice'] = M('mall_goods')->where(array('companyid'=>$companyid,'id'=>$val['goodid']))->getField('originalprice');
    				$orderprice = M('mall_order_info')->where(array('companyid'=>$companyid,'orderid'=>$val['orderid']))->getField('orderprice');
    				$infos[$key]['saleprice'] = $orderprice/$val['num'];
    			}
    			$deliverychannel = 2;
    		}elseif($activitiestype == '7'){
    			//积分商城购买券
    			$infos = M()->table('tp_mall_member_integral_order_goods as activities')->join('tp_member_marketing_activities_voucher_info as vinfo on vinfo.id = activities.vouchersid')->where(array('activities.companyid'=>$companyid,'activities.orderid'=>$activitiesid))
    			->field('vinfo.id,vinfo.useshops,vinfo.usescene,vinfo.voucherdesc,vinfo.vouchertype,vinfo.parvalue,vinfo.minparvalue,vinfo.maxparvalue,vinfo.vouchercreatetype,vinfo.vouchercreatecatid,vinfo.israndom,vinfo.title,vinfo.usestarttime,vinfo.useendtime,vinfo.usetimetype,vinfo.usetimedeferred,vinfo.usetimelimittype,vinfo.usetimelimitset,vinfo.iscansend,vinfo.useissite,vinfo.discounttype,vinfo.minus,vinfo.discount,vinfo.fullminus,vinfo.fulldiscount,vinfo.eachfullminus')->select();
    			$deliverychannel = 3;
    		}elseif($activitiestype == '8'){
    			// 计次卡，团购，门票，权益卡
    			$infos = M('mall_order_goods')->where(array('orderid'=>$activitiesid,'companyid'=>$companyid,'goodtype'=>$goodtype,'goodskuid'=>$voucherskuid))->field('id,goodtype,orderid,vouchersid,prefix,pricetype,goodid,goodname,goodpic,goodnum as num,goodskuid,usetimelimittype,usetimelimitset,useshopslimitset,backorderpolicyset,useinfo,goodskuname')->select();
    			foreach($infos as $key=>$val){
    				$infos[$key]['originalprice'] = M('mall_goods_sku')->where(array('companyid'=>$companyid,'goodid'=>$val['goodid'],'id'=>$val['goodskuid']))->getField('originalprice');
    				$orderprice = M('mall_order_info')->where(array('companyid'=>$companyid,'orderid'=>$val['orderid']))->getField('orderprice');
    				$infos[$key]['saleprice'] = $orderprice/$val['num'];
    			}
    			$deliverychannel = 2;
    		}elseif($activitiestype == '9'){
    			// SCRM5 活动
    			$infos = array();
    			$lists = M('member_marketing_activities_scrm_link_voucher')->where(array('companyid'=>$companyid,'parentid'=>$activitiesid,'isdel'=>array('neq',1)))->field('id,parentid,voucherid,vouchertype,voucherskuid,vouchersku,cansendmaxnum,sendnum,usenum')->select();
    			if($lists){
    				foreach ($lists as $lkey=>$lval){
    					if($lval['vouchertype'] == '1'||$lval['vouchertype'] == '2'||$lval['vouchertype'] == '3'||$lval['vouchertype'] == '4'||$lval['vouchertype'] == '40'){
    						$infos[$lkey] = M('member_marketing_activities_voucher_info')->where(array('companyid'=>$companyid,'id'=>$lval['voucherid']))->field('id,useshops,usescene,voucherdesc,vouchertype,parvalue,minparvalue,maxparvalue,israndom,title,usestarttime,useendtime,usetimetype,usetimedeferred,usetimelimittype,usetimelimitset,iscansend,useissite,discounttype,minus,discount,fullminus,fulldiscount,eachfullminus')->find();
    					}elseif($lval['vouchertype'] == '8'){
    						$infos[$lkey] = M('mall_goods')->where(array('id'=>$lval['voucherid'],'companyid'=>$companyid))->field('id,goodtype as vouchertype,vouchersid,prefix,pricetype,title,usetimelimittype,usetimelimitset,useshopslimitset as useshops,backorderpolicyset,useinfo as voucherdesc')->find();
    					}else{
    						$infos[$lkey] = M('mall_goods')->where(array('id'=>$lval['voucherid'],'companyid'=>$companyid))->field('id,goodtype as vouchertype,vouchersid,prefix,pricetype,title,usetimelimittype,usetimelimitset,useshopslimitset as useshops,backorderpolicyset,useinfo as voucherdesc')->find();
    						$infos[$lkey]['goodskuid'] = $lval['voucherskuid'];
    						$infos[$lkey]['goodskuname'] = M('mall_goods_sku')->where(array('companyid'=>$companyid,'goodid'=>$lval['voucherid'],'id'=>$lval['voucherskuid']))->getField('name');
    					}
    					$infos[$lkey]['num'] = $lval['cansendmaxnum']; //需要发送券的数量
    					$infos[$lkey]['sendnum'] = $lval['sendnum'];
    					$infos[$lkey]['memberType'] = $lval['vouchertype'];
    					$infos[$lkey]['scrmActivityId'] = $lval['id'];
    					$scrmActivitynum += $lval['cansendmaxnum']; //需要发送券的总数量
    				}
    			}
    			// 获取 关联的活动的类型
    			$activitiesScrmType = M('member_marketing_activities_scrm')->where(array('id'=>$activitiesid))->getField('type');
    			if($activitiesScrmType == '1'){
    				// 定时批量赠券活动
    				$deliverychannel = 8;
    			}elseif($activitiesScrmType == '2'){
    				// 生日送券活动
    				$deliverychannel = 9;
    			}elseif($activitiesScrmType == '3'){
    				// 首次微信关注奖励活动
    				$deliverychannel = 10;
    			}elseif($activitiesScrmType == '4'){
    				// 完善会员100%资料立赠活动
    				$deliverychannel = 11;
    			}elseif($activitiesScrmType == '5'){
    				// 新会员注册奖励活动
    				$deliverychannel = 12;
    			}elseif($activitiesScrmType == '7'){
    				// 首次消费赠券
    				$deliverychannel = 14;
    			}elseif($activitiesScrmType == '12'){
    				// 沉睡用户唤醒活动
    				$deliverychannel = 17;
    			}elseif($activitiesScrmType == '13'){
    				// 储值充值赠券
    				$deliverychannel = 19;
    			}
    		}elseif($activitiestype == '10'){
    			// SCRM5 活动
    			$a = 2;
    			$b = 1;
    			$c = 1;
    			$infos = array();
    			$lists = M('member_marketing_activities_scrm_link_voucher')->where(array('companyid'=>$companyid,'parentid'=>$activitiesid,'isdel'=>array('neq',1)))->field('id,voucherid,vouchertype,voucherskuid,vouchersku,cansendmaxnum,sendnum,usenum')->select();
    			if($lists){
    				foreach ($lists as $lkey=>$lval){
    					if($lval['sendnum'] < $lval['cansendmaxnum']){
    						$a = 1;
    						if($lval['vouchertype'] == '1'||$lval['vouchertype'] == '2'||$lval['vouchertype'] == '3'||$lval['vouchertype'] == '4'||$lval['vouchertype'] == '40'){
    							$infos[$lkey] = M('member_marketing_activities_voucher_info')->where(array('companyid'=>$companyid,'id'=>$lval['voucherid']))->field('id,useshops,usescene,voucherdesc,vouchertype,parvalue,minparvalue,maxparvalue,israndom,title,usestarttime,useendtime,usetimetype,usetimedeferred,usetimelimittype,usetimelimitset,iscansend,useissite,discounttype,minus,discount,fullminus,fulldiscount,eachfullminus')->find();
    						}elseif($lval['vouchertype'] == '8'){
    							$infos[$lkey] = M('mall_goods')->where(array('id'=>$lval['voucherid'],'companyid'=>$companyid))->field('id,goodtype as vouchertype,vouchersid,prefix,pricetype,title,usetimelimittype,usetimelimitset,useshopslimitset as useshops,backorderpolicyset,useinfo as voucherdesc')->find();
    						}else{
    							$infos[$lkey] = M('mall_goods')->where(array('id'=>$lval['voucherid'],'companyid'=>$companyid))->field('id,goodtype as vouchertype,vouchersid,prefix,pricetype,title,usetimelimittype,usetimelimitset,useshopslimitset as useshops,backorderpolicyset,useinfo as voucherdesc')->find();
    							$infos[$lkey]['goodskuid'] = $lval['voucherskuid'];
    							$infos[$lkey]['goodskuname'] = M('mall_goods_sku')->where(array('companyid'=>$companyid,'goodid'=>$lval['voucherid'],'id'=>$lval['voucherskuid']))->getField('name');
    						}
    						$infos[$lkey]['num'] = 1; //需要发送券的数量
    						$infos[$lkey]['memberType'] = $lval['vouchertype'];
    						$infos[$lkey]['scrmActivityId'] = $lval['id'];
    					}
    					//如果发券数量大于等于总发券数量减1那么说明都发完了
    					if($lval['sendnum'] >= $lval['cansendmaxnum']-1){
    						$b++;
    					}
    					$c++;
    				}
    				if($a == 2 || $b==$c){
    					//将活动 停止掉
    					M('member_marketing_activities_scrm')->where(array('companyid'=>$companyid,'id'=>$activitiesid))->setField('status',1);
    					//$infos = '';
    				}
    			}
    			$deliverychannel = 13;
    		}elseif($activitiestype == '11'){
    			// eshop 商城购买卡券礼包
    			$infos = array();
    			$goodInfo = M('mall_order_goods')->where(array('orderid'=>$activitiesid,'companyid'=>$companyid,'goodtype'=>$goodtype))->field('id,goodtype,vouchersid,pricetype,goodid,goodname,goodpic,goodnum')->find();
    			$lists = M('mall_goods_vouchers_bag')->where(array('companyid'=>$companyid,'goodid'=>$goodInfo['goodid']))->field('id,goodid,voucherid,vouchername,voucherskuid,voucherskuname,vouchertype,cansendmaxnum,usetimelimittype,usetimelimitset')->select();
    			if($lists){
    				foreach ($lists as $lkey=>$lval){
    					if($lval['vouchertype'] == '1'||$lval['vouchertype'] == '2'||$lval['vouchertype'] == '3'||$lval['vouchertype'] == '4'||$lval['vouchertype'] == '40'){
    						$infos[$lkey] = M('member_marketing_activities_voucher_info')->where(array('companyid'=>$companyid,'id'=>$lval['voucherid']))->field('id,useshops,usescene,voucherdesc,vouchertype,parvalue,minparvalue,maxparvalue,israndom,title,usestarttime,useendtime,usetimetype,usetimedeferred,usetimelimittype,usetimelimitset,iscansend,useissite,discounttype,minus,discount,fullminus,fulldiscount,eachfullminus')->find();
    					}elseif($lval['vouchertype'] == '8'){
    						$infos[$lkey] = M('mall_goods')->where(array('id'=>$lval['voucherid'],'companyid'=>$companyid))->field('id,goodtype as vouchertype,vouchersid,pricetype,title,usetimelimittype,usetimelimitset,useshopslimitset as useshops,backorderpolicyset,useinfo as voucherdesc')->find();
    					}else{
    						$infos[$lkey] = M('mall_goods')->where(array('id'=>$lval['voucherid'],'companyid'=>$companyid))->field('id,goodtype as vouchertype,vouchersid,pricetype,title,usetimelimittype,usetimelimitset,useshopslimitset as useshops,backorderpolicyset,useinfo as voucherdesc')->find();
    						$infos[$lkey]['goodskuid'] = $lval['voucherskuid'];
    						$infos[$lkey]['goodskuname'] = M('mall_goods_sku')->where(array('companyid'=>$companyid,'goodid'=>$lval['voucherid'],'id'=>$lval['voucherskuid']))->getField('name');
    					}
    					$infos[$lkey]['num'] = $lval['cansendmaxnum']*$goodInfo['goodnum']; //需要发送券的数量
    					$infos[$lkey]['memberType'] = $lval['vouchertype'];
    				}
    			}
    			$deliverychannel = 2;
    		}elseif($activitiestype == '12'){
    			// SCRM5 活动
    			$infos = array();
    			$activitiesidAsa = M("member_marketing_activities_scrm_fission")->where(array('companyid'=>$companyid,'id'=>$activitiesid))->getField("parentid");
    			$lists = M('member_marketing_activities_scrm_link_voucher')->where(array('companyid'=>$companyid,'parentid'=>$activitiesidAsa,'isdel'=>array('neq',1)))->field('id,voucherid,vouchertype,voucherskuid,vouchersku,cansendmaxnum,sendnum,usenum')->order("createtime desc")->limit(0,1)->select();
    			if($lists){
    				foreach ($lists as $lkey=>$lval){
    					if($lval['vouchertype'] == '1'||$lval['vouchertype'] == '2'||$lval['vouchertype'] == '3'||$lval['vouchertype'] == '4'||$lval['vouchertype'] == '40'){
    						$infos[$lkey] = M('member_marketing_activities_voucher_info')->where(array('companyid'=>$companyid,'id'=>$lval['voucherid']))->field('id,useshops,usescene,voucherdesc,vouchertype,parvalue,minparvalue,maxparvalue,israndom,title,usestarttime,useendtime,usetimetype,usetimedeferred,usetimelimittype,usetimelimitset,iscansend,useissite,discounttype,minus,discount,fullminus,fulldiscount,eachfullminus')->find();
    					}elseif($lval['vouchertype'] == '8'){
    						$infos[$lkey] = M('mall_goods')->where(array('id'=>$lval['voucherid'],'companyid'=>$companyid))->field('id,goodtype as vouchertype,vouchersid,prefix,pricetype,title,usetimelimittype,usetimelimitset,useshopslimitset as useshops,backorderpolicyset,useinfo as voucherdesc')->find();
    					}else{
    						$infos[$lkey] = M('mall_goods')->where(array('id'=>$lval['voucherid'],'companyid'=>$companyid))->field('id,goodtype as vouchertype,vouchersid,prefix,pricetype,title,usetimelimittype,usetimelimitset,useshopslimitset as useshops,backorderpolicyset,useinfo as voucherdesc')->find();
    						$infos[$lkey]['goodskuid'] = $lval['voucherskuid'];
    						$infos[$lkey]['goodskuname'] = M('mall_goods_sku')->where(array('companyid'=>$companyid,'goodid'=>$lval['voucherid'],'id'=>$lval['voucherskuid']))->getField('name');
    					}
    					$infos[$lkey]['num'] = 1; //需要发送券的数量
    					$infos[$lkey]['memberType'] = $lval['vouchertype'];
    					$infos[$lkey]['scrmActivityId'] = $lval['id'];
    				}
    			}
    			$type = M('member_marketing_activities_scrm')->where(array('id'=>$activitiesidAsa))->getField('type');
    			if($type == '8'){
    				// 裂变卡券
    				$deliverychannel = 6;
    			}elseif($type == '9'){
    				// 裂变红包
    				$deliverychannel = 7;
    			}
    		}elseif($activitiestype == '13'){
    			// 快捷赠券
    			$infos = array();
    			if($vouchertype == 1 || $vouchertype == 2 || $vouchertype == 3 || $vouchertype == 4 || $vouchertype == 40){
    				$infos[] = M('member_marketing_activities_voucher_info')->where(array('companyid'=>$companyid,'id'=>$activitiesid))->field('id,useshops,usescene,voucherdesc,vouchertype,parvalue,minparvalue,maxparvalue,israndom,title,usestarttime,useendtime,usetimetype,usetimedeferred,usetimelimittype,usetimelimitset,iscansend,useissite,discounttype,minus,discount,fullminus,fulldiscount,eachfullminus')->find();
    				foreach($infos as $key=>$val){
    					$infos[$key]['num'] = 1; //需要发送券的数量
    					$infos[$key]['memberType'] = $vouchertype;
    				}
    			}elseif($vouchertype == 5 || $vouchertype == 6 || $vouchertype == 7 || $vouchertype == 8){
    				$infos[] = M('mall_goods')->where(array('companyid'=>$companyid,'id'=>$activitiesid))->field('id,goodtype as vouchertype,vouchersid,prefix,pricetype,title,usetimelimittype,usetimelimitset,useshopslimitset as useshops,backorderpolicyset,useinfo as voucherdesc')->find();
    				foreach($infos as $key=>$val){
    					if($vouchertype == 5 || $vouchertype == 6 || $vouchertype == 7){
	    					$infos[$key]['goodskuid'] = $voucherskuid;
	    					$infos[$key]['goodskuname'] = M('mall_goods_sku')->where(array('companyid'=>$companyid,'goodid'=>$val['id'],'id'=>$voucherskuid))->getField('name');
    					}
    					$infos[$key]['num'] = 1; //需要发送券的数量
    					$infos[$key]['memberType'] = $vouchertype;
    				}
    			}
    			if($getVouchersType == 1){
    				// 快捷赠券
    				$deliverychannel = 16;
    			}elseif($getVouchersType == 2){
    				// 声悦传情
    				$deliverychannel = 18;
    			}else{
    				// 邀请赠礼
    				$deliverychannel = 15;
    			}
    		}elseif ($activitiestype=='14'){
    			// 百宝箱
    		    $lists = json_decode($activitiesid,true);
    		    if($lists){
	    			foreach ($lists as $lkey=>$lval){
    					if($lval['vouchertype'] == '1'||$lval['vouchertype'] == '2'||$lval['vouchertype'] == '3'||$lval['vouchertype'] == '4'||$lval['vouchertype'] == '40'){
    						$infos[$lkey] = M('member_marketing_activities_voucher_info')->where(array('companyid'=>$companyid,'id'=>$lval['voucherid']))->field('id,useshops,usescene,voucherdesc,vouchertype,parvalue,minparvalue,maxparvalue,israndom,title,usestarttime,useendtime,usetimetype,usetimedeferred,usetimelimittype,usetimelimitset,iscansend,useissite,discounttype,minus,discount,fullminus,fulldiscount,eachfullminus')->find();
    					}elseif($lval['type'] == '8'){
    						$infos[$lkey] = M('mall_goods')->where(array('id'=>$lval['voucherid'],'companyid'=>$companyid))->field('id,goodtype as vouchertype,vouchersid,prefix,pricetype,title,usetimelimittype,usetimelimitset,useshopslimitset as useshops,backorderpolicyset,useinfo as voucherdesc')->find();
    					}else{
    						$infos[$lkey] = M('mall_goods')->where(array('id'=>$lval['voucherid'],'companyid'=>$companyid))->field('id,goodtype as vouchertype,vouchersid,prefix,pricetype,title,usetimelimittype,usetimelimitset,useshopslimitset as useshops,backorderpolicyset,useinfo as voucherdesc')->find();
    						$infos[$lkey]['goodskuid'] = $lval['vouchersku'];
    						$infos[$lkey]['goodskuname'] = M('mall_goods_sku')->where(array('companyid'=>$companyid,'goodid'=>$lval['voucherid'],'id'=>$lval['vouchersku']))->getField('name');
    					}
    					$infos[$lkey]['num'] = 1;
    					$infos[$lkey]['memberType'] = $lval['vouchertype'];
	    			}
    		    }
    		    $deliverychannel = 4;
    		}elseif($activitiestype == '15'){
    			// 积分商城兑换计次卡，团购，门票，权益卡
    			$infos = M('mall_member_integral_order_goods')->where(array('orderid'=>$activitiesid,'companyid'=>$companyid,'vouchertype'=>$goodtype,'voucherskuid'=>$voucherskuid))->field('id,orderid,goodnum as num,vouchertype,vouchersid,voucherskuid,voucherskuname,usetimelimittype,usetimelimitset,useshopslimitset,backorderpolicyset')->select();
    			foreach($infos as $key=>$val){
    				$goodInfo = M('mall_goods')->where(array('companyid'=>$companyid,'id'=>$val['vouchersid']))->field('title,useinfo')->find();
    				$infos[$key]['title'] = $goodInfo['title'];
    				$infos[$key]['useinfo'] = $goodInfo['useinfo'];
    			}
    			$deliverychannel = 3;
    		}elseif($activitiestype == '16'){
    			// 积分商城     兑换卡券礼包
    			$infos = array();
    			$goodInfo = M('mall_member_integral_order_goods')->where(array('orderid'=>$activitiesid,'companyid'=>$companyid,'vouchertype'=>$goodtype))->field('id,goodnum,vouchertype,vouchersid')->find();
    			$lists = M('mall_goods_vouchers_bag')->where(array('companyid'=>$companyid,'goodid'=>$goodInfo['vouchersid']))->field('id,goodid,voucherid,vouchername,voucherskuid,voucherskuname,vouchertype,cansendmaxnum,usetimelimittype,usetimelimitset')->select();
    			if($lists){
    				foreach ($lists as $lkey=>$lval){
    					if($lval['vouchertype'] == '1'||$lval['vouchertype'] == '2'||$lval['vouchertype'] == '3'||$lval['vouchertype'] == '4'||$lval['vouchertype'] == '40'){
    						$infos[$lkey] = M('member_marketing_activities_voucher_info')->where(array('companyid'=>$companyid,'id'=>$lval['voucherid']))->field('id,useshops,usescene,voucherdesc,vouchertype,parvalue,minparvalue,maxparvalue,israndom,title,usestarttime,useendtime,usetimetype,usetimedeferred,usetimelimittype,usetimelimitset,iscansend,useissite,discounttype,minus,discount,fullminus,fulldiscount,eachfullminus')->find();
    					}elseif($lval['vouchertype'] == '8'){
    						$infos[$lkey] = M('mall_goods')->where(array('id'=>$lval['voucherid'],'companyid'=>$companyid))->field('id,goodtype as vouchertype,vouchersid,title,usetimelimittype,usetimelimitset,useshopslimitset as useshops,backorderpolicyset,useinfo as voucherdesc')->find();
    					}else{
    						$infos[$lkey] = M('mall_goods')->where(array('id'=>$lval['voucherid'],'companyid'=>$companyid))->field('id,goodtype as vouchertype,vouchersid,title,usetimelimittype,usetimelimitset,useshopslimitset as useshops,backorderpolicyset,useinfo as voucherdesc')->find();
    						$infos[$lkey]['goodskuid'] = $lval['voucherskuid'];
    						$infos[$lkey]['goodskuname'] = M('mall_goods_sku')->where(array('companyid'=>$companyid,'goodid'=>$lval['voucherid'],'id'=>$lval['voucherskuid']))->getField('name');
    					}
    					$infos[$lkey]['num'] = $lval['cansendmaxnum']*$goodInfo['goodnum']; //需要发送券的数量
    					$infos[$lkey]['memberType'] = $lval['vouchertype'];
    				}
    			}
    			$deliverychannel = 3;
    		}
    		if($infos){
    		    $time = time();
    			$sendSuccessNum = 0;
    			// $snType  计次卡:3;团购:4;门票:5;权益卡:6;eshop优惠券:7;门店使用优惠券:8;兑换券:9;红包:10;
    			foreach ($infos as $key=>$info){
    				if($info){
	    			    // 券发送数量，默认发送一张
	    			    $sendNum = $info['num'] ? $info['num'] : 1;
	    			    // 券号生成规则，1：按照SCRM5券号规则系统自动生成；2：券池中获取；
	    				$voucherSendType = $info['vouchercreatetype'] ? $info['vouchercreatetype'] : 1;
	    				for ($i = 0;$i < $sendNum;$i++){
	    					M()->startTrans();
	    					//商城购买直接发券没有活动id
	    					// $activitiestype 活动赠券类型 1：会员触发活动；2：线下活动报名；4：会员升级卡类型送电子券；5：签到活动赠券；6：eshop商城金钱购买券发券；7：积分商城积分兑换券发券；
	    					if($activitiestype == '1' || $activitiestype == '2'  || $activitiestype == '4' || $activitiestype == '6' || $activitiestype == '7' || $activitiestype == '9' || $activitiestype == '10' || $activitiestype == '11' || $activitiestype == '12' || $activitiestype == '13' || $activitiestype == '14' || $activitiestype == '16'){
	    					    if($activitiestype == '6' || $activitiestype == '7' || $activitiestype == '11' || $activitiestype == '16'){
	    							$vouchers['voucherid'] = '0';
	    							$vouchers['orderid'] = $activitiesid;
	    							//退单政策
	    							$vouchers['backorderpolicyset'] = $info['backorderpolicyset'] ? $info['backorderpolicyset'] : ',';
	    						}else{
	    							$vouchers['voucherid'] = $activitiesid;
	    							$vouchers['orderid'] = '0';
	    							//退单政策
	    							$vouchers['backorderpolicyset'] = ',';
	    						}
	    						$activityCountData['updatetime'] = time(); //统计活动的券的投放量
	    						if($activitiestype == '9' || $activitiestype == '10'|| $activitiestype == '12'){
	    							$activityCountData['sendnum'] = array('exp', '`sendnum`+1');
	    							$activityCountSaveReturn = M('member_marketing_activities_scrm_link_voucher')->where(array('id'=>$info['scrmActivityId']))->save($activityCountData);
	    							M('member_marketing_activities_scrm_fission')->where(array('id'=>$activitiesid))->save($activityCountData);
	    							//将活动关联券表记录id 存入卡券包  这里为了核销量的统计能精确到每一个关联券 【 同一个活动活动关联两张同一种券 】
	    							$vouchers['scrmactivityid'] = $info['scrmActivityId'];
	    						}else{
	    							$activityCountSaveReturn = 1;
	    						}
	    						$vouchers['voucherinfoid'] = $info['id']?$info['id']:'';
	    						$vouchers['useshopslimitset'] = $info['useshops']?$info['useshops']:',';
	    						$vouchers['usescenelimitset'] = $info['usescene']?$info['usescene']:',';
	    						$vouchers['useinfo'] = $info['voucherdesc']?$info['voucherdesc']:'';
	    						$title = $info['title'];
	    						$vouchers['mallordergoodsid'] = '0';
	    						$vouchers['voucherskuname'] = $info['goodskuname']?$info['goodskuname']:'';
	    						// 红包面值是否随机
	    						if($info['israndom'] == '1'){
	    							$vouchers['parvalue'] = rand($info['minparvalue'], $info['maxparvalue']);
	    						}else{
	    							$vouchers['parvalue'] = $info['parvalue']?$info['parvalue']:'0.00';
	    						}
	    						$vouchers['isused'] = '2'; //先默认为未使用状态，当发送红包时使用状态赋值为已使用
	    						$memberType = $info['memberType']?$info['memberType']:'';
	    						$vouchers['discounttype'] = $info['discounttype']?$info['discounttype']:'';
	    						//券号生成规则
	    						if($voucherSendType == 2){
	    							$sn = M('member_voucher_pool')->where(array('companyid'=>$companyid,'cid'=>$info['vouchercreatecatid'],'issend'=>'2'))->getField('sn');
	    							if($sn){
	    								$vouchers['sn'] = $sn;
	    							}else{
	    								$returnData['code'] = '301';
	    								$returnData['msg'] = '券池券号获取失败';
	    							}
	    							if($sn){
	    								$snSave = M('member_voucher_pool')->where(array('companyid'=>$companyid,'sn'=>$sn))->save(array('issend'=>1,'sendtime'=>time()));
	    							}
	    						}elseif($voucherSendType == 1){
	    						    $companySaveData['updatetime'] = $time;
	    						    if($activitiestype == '4' || $activitiestype == '9' || $activitiestype == '10' || $activitiestype == '11'|| $activitiestype == '12'|| $activitiestype == '13'|| $activitiestype == '14'|| $activitiestype == '16'){
	    						    	if($memberType == 1 || $memberType == 2 || $memberType == 3 || $memberType == 4 || $memberType == 40){
	    						    		if($info['vouchertype'] == 1){
	    						    			// eshop优惠券
	    						    			$snType = 7;
	    						    			$companySaveData['totalvouchertype1sendnum'] = array('exp', '`totalvouchertype1sendnum`+1');
	    						    		}elseif($info['vouchertype'] == 2){
	    						    			// 门店使用优惠券
	    						    			$snType = 8;
	    						    			$companySaveData['totalvouchertype2sendnum'] = array('exp', '`totalvouchertype2sendnum`+1');
	    						    		}elseif($info['vouchertype'] == 3){
	    						    			// 兑换券
	    						    			$snType = 9;
	    						    			$companySaveData['totalvouchertype3sendnum'] = array('exp', '`totalvouchertype3sendnum`+1');
	    						    		}elseif($info['vouchertype'] == 4){
	    						    			// 红包
	    						    			$snType = 10;
	    						    			$vouchers['isused'] = '1';
	    						    		}elseif($info['vouchertype'] == 40){
	    						    			// 通用券
	    						    			$snType = 40;
	    						    			$companySaveData['totalvouchertype40sendnum'] = array('exp', '`totalvouchertype40sendnum`+1');
	    						    		}
	    						    	}else{
	    						    		if($info['vouchertype'] == 3){
	    						    			$vouchers['usenumberlimit'] = $info['goodskuname']?$info['goodskuname']:'';
	    						    			$vouchers['voucherskuname'] = $info['goodskuname']?$info['goodskuname']:'';
	    						    			//计次卡
	    						    			$snType = 3;
	    						    		}elseif($info['vouchertype'] == 4){
	    						    			$vouchers['voucherskuname'] = $info['goodskuname']?$info['goodskuname']:'';
	    						    			//团购
	    						    			$snType = 4;
	    						    		}elseif($info['vouchertype'] == 5){
	    						    			$vouchers['voucherskuname'] = $info['goodskuname']?$info['goodskuname']:'';
	    						    			//门票
	    						    			$snType = 5;
	    						    		}elseif($info['vouchertype'] == 6){
	    						    			$vouchers['voucherskuname'] = $info['goodskuname']?$info['goodskuname']:'';
	    						    			//权益卡
	    						    			$snType = 6;
	    						    		}
	    						    	}
	    						    }else{
	    						    	if($info['vouchertype'] == 1){
	    						    		// eshop优惠券
	    						    		$snType = 7;
	    						    		$companySaveData['totalvouchertype1sendnum'] = array('exp', '`totalvouchertype1sendnum`+1');
	    						    	}elseif($info['vouchertype'] == 2){
	    						    		// 门店使用优惠券
	    						    		$snType = 8;
	    						    		$companySaveData['totalvouchertype2sendnum'] = array('exp', '`totalvouchertype2sendnum`+1');
	    						    	}elseif($info['vouchertype'] == 3){
	    						    		// 兑换券
	    						    		$snType = 9;
	    						    		$companySaveData['totalvouchertype3sendnum'] = array('exp', '`totalvouchertype3sendnum`+1');
	    						    	}elseif($info['vouchertype'] == 4){
	    						    		// 红包
	    						    		$snType = 10;
	    						    		$vouchers['isused'] = '1';
	    						    	}elseif($info['vouchertype'] == 40){
	    						    		// 通用券
	    						    		$snType = 40;
	    						    		$companySaveData['totalvouchertype40sendnum'] = array('exp', '`totalvouchertype40sendnum`+1');
	    						    	}
	    						    }
	    							if($snType == 7 || $snType== 8 || $snType == 9 || $snType == 40){
	        							$compaySaveReturn = M('company')->where(array('id'=>$companyid))->save($companySaveData);
	    							}else{
	    							    $compaySaveReturn = 1;
	    							}
	    							//将发券的的投放量增加到company表中 
	    							$sn = $this->getSNCode($snType,$companyid);
	    							$vouchers['sn'] = $sn;
	    							$snSave = 1;
	    						}
	    						if($info['discounttype'] == 1){
	    							$vouchers['minus'] = $info['minus'];
	    						}elseif($info['discounttype'] == 2){
	    							$vouchers['discount'] = $info['discount'];
	    						}elseif($info['discounttype'] == 3){
	    							$shouldPay = explode(',',$info['fullminus']);
	    							$vouchers['parvalue'] = $shouldPay[0];
	    							$vouchers['fullminus'] = $info['fullminus'];
	    						}elseif($info['discounttype'] == 4){
	    							$shouldPay = explode(',',$info['fulldiscount']);
	    							$vouchers['parvalue'] = $shouldPay[0];
	    							$vouchers['fulldiscount'] = $info['fulldiscount'];
	    						}elseif($info['discounttype'] == 5){
	    							$shouldPay = explode(',',$info['eachfullminus']);
	    							$vouchers['parvalue'] = $shouldPay[0];
	    							$vouchers['eachfullminus'] = $info['eachfullminus'];
	    						}
	    					}elseif($activitiestype == '8'){
	    						$vouchers['voucherid'] = '0';
	    						$vouchers['orderid'] = $activitiesid;
	    						// 退单政策
	    						$vouchers['backorderpolicyset'] = $info['backorderpolicyset']?$info['backorderpolicyset']:',';
		    					// 卡券主表
		    					if($info['vouchertype'] != 6){
		    						$mallgoodssku = M('mall_goods_sku')->where(array('companyid'=>$companyid,'id'=>$info['goodskuid']))->field('name,saleprice,intprice,imgurl,stockamount')->find();
		    						if(!$mallgoodssku){
		    							$returnData['code'] = '301';
		    							$returnData['msg'] = '卡券Sku获取失败';
		    						}
		    					}
	    						$vouchers['voucherinfoid'] = $info['goodid']?$info['goodid']:'';
	    						$vouchers['useshopslimitset'] = $info['useshopslimitset']?$info['useshopslimitset']:'';
	    						$vouchers['useinfo'] = $info['useinfo']?$info['useinfo']:'';
	    						$title = M('mall_goods')->where(array('companyid'=>$companyid,'id'=>$info['goodid']))->getField('title');
	    						$vouchers['mallordergoodsid'] = $info['id']?$info['id']:'';
	    						$vouchers['voucherskuname'] = $mallgoodssku['name']?$mallgoodssku['name']:'';
	    						//其他类型
	    						$vouchers['parvalue'] = '';
	    						//券号生成规则
	    						if($info['goodtype'] == 3){
	    							//计次卡
	    							$vouchers['usenumberlimit'] = $mallgoodssku['name'];
	    							$snType = 3;
	    						}elseif($info['goodtype'] == 4){
	    							//团购
	    							$snType = 4;
	    						}elseif($info['goodtype'] == 5){
	    							//门票
	    							$snType = 5;
	    						}elseif($info['goodtype'] == 6){
	    							//权益卡
	    							$snType = 6;
	    						}
	    						$sn = $this->getSNCode($snType,$companyid);
	    						$vouchers['sn'] = $sn;
	    						if($sn){
		    						$snSave = 1;
	    						}
	    						$vouchers['isused'] = '2';
	    						$compaySaveReturn = 1;
	    						$activityCountSaveReturn = 1;
	    					}elseif($activitiestype == '15'){
	    						$vouchers['voucherid'] = '0';
	    						$vouchers['orderid'] = $activitiesid;
	    						// 退单政策
	    						$vouchers['backorderpolicyset'] = $info['backorderpolicyset']?$info['backorderpolicyset']:',';
	    						$vouchers['voucherinfoid'] = $info['vouchersid']?$info['vouchersid']:'';
	    						$vouchers['useshopslimitset'] = $info['useshopslimitset']?$info['useshopslimitset']:'';
	    						$vouchers['useinfo'] = $info['useinfo']?$info['useinfo']:'';
	    						$title = $info['title']?$info['title']:'';
	    						$vouchers['mallordergoodsid'] = $info['id']?$info['id']:'';
	    						$vouchers['voucherskuname'] = $info['voucherskuname']?$info['voucherskuname']:'';
	    						//其他类型
	    						$vouchers['parvalue'] = '';
	    						//券号生成规则
	    						if($info['vouchertype'] == 5){
	    							//计次卡
	    							$vouchers['usenumberlimit'] = $info['voucherskuname'];
	    							$snType = 3;
	    						}elseif($info['vouchertype'] == 6){
	    							//团购
	    							$snType = 4;
	    						}elseif($info['vouchertype'] == 7){
	    							//门票
	    							$snType = 5;
	    						}elseif($info['vouchertype'] == 8){
	    							//权益卡
	    							$snType = 6;
	    						}
	    						$sn = $this->getSNCode($snType,$companyid);
	    						$vouchers['sn'] = $sn;
	    						if($sn){
		    						$snSave = 1;
	    						}
	    						$vouchers['isused'] = '2';
	    						$compaySaveReturn = 1;
	    						$activityCountSaveReturn = 1;
	    					}
	    					if($activitiestype == 6 || $activitiestype == 8 || $activitiestype == 11){
	    						$vouchers['originalprice'] = $info['originalprice'];
	    						$vouchers['saleprice'] = $info['saleprice'];
	    					}
	    					$vouchers['adduid'] =$vouchers['edituid'] = $vouchers['shopsid']= $vouchers['shopid'] = 0;
	    					$vouchers['companyid'] = $companyid;
	    					$vouchers['mid'] = $mid;
	    					$vouchers['vouchername'] = $title?$title:'';
	    					$vouchers['getvouchertype'] = $activitiestype;
	    					$vouchers['vouchertype'] = $snType;
	    					//计算投放量
	    					$countData['updatetime'] = time();
	    					if($snType == 7 || $snType == 8 || $snType == 9 || $snType == 10 || $snType == 40){
	    						if($snType == 10){
	    							//红包的核销量
	    							$countData['verificationnum'] = array('exp', '`verificationnum`+1');
	    						}
	    						$countData['deliverynum'] = array('exp', '`deliverynum`+1');
		    					$countSaveReturn = M('member_marketing_activities_voucher_info')->where(array('id'=>$info['id']))->save($countData);
	    					}else{
	    						$countSaveReturn = 1;
	    					}
	    					// 定值 这个值用来判断卡券是否可以过期退 不管商品是否勾选了过期退
	    					if($activitiestype == '11'){
	    						$vouchers['notrefund'] = 1;
	    					}
	    					//券的有效期时间
	    					$vouchers['usetimelimittype'] = $info['usetimelimittype']?$info['usetimelimittype']:'';
	    					//券发出的那一刻将有效期限存入
	    					if($info['usetimelimittype'] == 1){
	    						$vouchers['usestarttime'] = time();
	    						$vouchers['useendtime'] = strtotime('+'.$info['usetimelimitset'].' day');
	    					}elseif($info['usetimelimittype'] == 2){
	    						$usetimelimitset = json_decode($info['usetimelimitset'],true);
	    						$vouchers['usestarttime'] = strtotime($usetimelimitset['usebegintime'].'00:00');
	    						$vouchers['useendtime'] = strtotime($usetimelimitset['useendtime'].'23:59');
	    					}elseif($info['usetimelimittype'] == 3){
	    						$vouchers['usestarttime'] = strtotime($info['usetimelimitset'].'00:00');
	    						$vouchers['useendtime'] = strtotime($info['usetimelimitset'].'23:59');
	    					}
	    					$vouchers['deliverychannel'] = $deliverychannel?$deliverychannel:'';
	    					$vouchers['issend'] = '2';
	    					$vouchers['updatetime'] = $vouchers['createtime'] = time();
	    					if($snType>0){
		    					$vouchersid = M('member_vouchers')->add($vouchers);
	    					}
	    					if($vouchersid && $snSave && $compaySaveReturn && $countSaveReturn){
	    						M()->commit();
	    						if($snType == 10){
	    							$option['cid'] = $companyid;
	    							$option['type'] = '112';
	    							$option['mid'] = $mid;
	    							$option['num2'] = $vouchers['parvalue']; // 红包面值
	    							$option['rechargetype'] = '4';
	    							$this->changeMemberBusinessSCRM5($option);
	    							$this->WeChatTemplateMessageSend('33',$openid,$companyid,'','',array('获得红包','卡券通知'),array(htmlspecialchars_decode($title),$vouchers['parvalue'].'元'));
	    						}else{
	    							$this->WeChatTemplateMessageSend('9',$openid,$companyid,'','',array('获得卡券','卡券通知'),array(htmlspecialchars_decode($title),format_time($vouchers['usestarttime'],'ymdhi').' 至 '.format_time($vouchers['useendtime'],'ymdhi')));
	    						}
	    						$msg = '发券成功';
	    						$sendSuccessNum++;
	    					}else{
	    						M()->rollback();
	    						$msg = '发券失败';
	    						//存失败log日志
	    						$logData['id'] = guidNow();
	    						$logData['companyid'] = $companyid;
	    						$logData['log'] = format_time(time(),'zhymd').'$vouchersid:'.$vouchersid.';$snSave:'.$snSave.';$compaySaveReturn:'.$compaySaveReturn.';$countSaveReturn:'.$countSaveReturn;
	    						$logData['createtime'] = time();
	    						M('log_send_vouchers')->add($logData);
	    						//$this->sendSms('13564012907', '新产生一条风券发送失败日志，请核查！log日志ID：'.$logData['id'],'1186','【人来风】','','181818');
	    					}
	    				}
	    			}
	    			unset($info,$snType,$vouchers);
	    			$returnData['box'][$key]['vouchersid'] = $vouchersid;
	    			$returnData['box'][$key]['sn'] = $sn;
	    			$returnData['box'][$key]['vouchername'] = $title?$title:'';
    			}
    			$returnData['code'] = '200';
    			$returnData['msg'] = $msg;
    			if($activitiestype == '9'){
    				if($sendSuccessNum >= 1){
    					$activitiesScrmType = $activitiesScrmType?$activitiesScrmType:'';  //SCRM5 活动 改变 是否发券的活动状态
    					if($activitiesScrmType == 3){
    						//issendwechatsubscribevoucher微信关注时是否赠送活动券：1：是；2：否；默认：2
    						$issendWechatSubscribeVoucherData['issendwechatsubscribevoucher'] = 1;
    						$issendWechatSubscribeVoucherData['updatetime'] = time();
    						$issendWechatSubscribeVoucherResult = M('member_register_info')->where(array('id'=>$mid))->save($issendWechatSubscribeVoucherData);
    						$issend100expVoucherResult = 1;
    					}elseif($activitiesScrmType == 4){
    						//issend100expvoucher是否发送了完整资料100%活动券；1：是；2：否；默认:2;
    						$issend100expvoucherData['issend100expvoucher'] = 1;
    						$issend100expvoucherData['updatetime'] = time();
    						$issend100expVoucherResult = M('member_register_info')->where(array('id'=>$mid))->save($issend100expvoucherData);
    						$issendWechatSubscribeVoucherResult = 1;
    					}else{
    						$issendWechatSubscribeVoucherResult = 1;
    						$issend100expVoucherResult = 1;
    					}
    				}elseif($sendSuccessNum != $scrmActivitynum){
    					//存日志
    					$logData['id'] = guidNow();
    					$logData['companyid'] = $companyid;
    					$logData['log'] = format_time(time(),'zhymd').'实发数:'.$sendSuccessNum.';应发数:'.$scrmActivitynum;
    					$logData['createtime'] = time();
    					M('log_send_vouchers')->add($logData);
    					//$this->sendSms('13564012907', '新产生一条风券实际发送数与应发数不一致，请核查！log日志ID：'.$logData['id'],'1186','【人来风】','','181818');
    				}
    			}
    			$returnData['data'] = array('sendnum'=>$sendSuccessNum);
    			return $returnData;
    		}else{
    			return false;
    		}
    	}else{
    		return false;
    	}
    }
    /**
     * 
     * 【新版】商城订单支付
     * 
     * @param string $orderid      订单ID
     * @param string $companyid    公司ID，我们常用的
     * @return number
     * @author Lando<806728685@qq.com>
     * @since  2016-11-21
     */
    public function mallOrderPaySCRM5($orderID,$companyid){
    	$return['code'] = 300;
    	if($orderID && $companyid){
    		$where['orderid'] = $orderID;
    		$where['companyid'] = $companyid;
    		$orderInfo = M('mall_order_info')->where($where)->field('goodtype,mid,companyid,borderid,orderid,out_trade_no,ordertitle,temporderstatus,orderstatus,paytime,createtime,orderprice,orderpaymethod,groupinfoid')->find();
    		if($orderInfo['temporderstatus']==1){
    			M()->startTrans();
    			$time = time();
    			$sendReturn = 0 ;
    			$mid = $orderInfo['mid'];
    			$orderid = $orderInfo['orderid'];
    			$editOrderInfo['temporderstatus'] = 2;
    			$editOrderInfo['updatetime'] = time();
    			$saveOrderInfo = M('mall_order_info')->where(array('orderid'=>$orderid,'companyid'=>$companyid))->save($editOrderInfo);
    			// 付款成功 增加展业伙伴佣金记录
    			/* $zOrderInfo = M('mall_exhibition_partner_order')->where(array('companyid'=>$companyid,'orderid'=>$orderid))->field('id,mid,buymid,orderprice,commission')->find();
    			if($zOrderInfo){
    				$zbillData['id'] = guidNow();
    				$zbillData['companyid'] = $companyid;
    				$zbillData['mid'] = $zOrderInfo['mid'];
    				$zbillData['orderid'] = $orderid;
    				$zbillData['billtype'] = 1;
    				$zbillData['createtime'] = $zbillData['updatetime'] = $time;
    				$zorderReturn = M('mall_exhibition_partner_bill')->add($zbillData);
    				// 累计推广订单数
    				M('mall_exhibition_partner_list')->where(array('companyid'=>$companyid,'mid'=>$zOrderInfo['mid']))->setInc('totalorder');
    				// 累计佣金
    				M('mall_exhibition_partner_list')->where(array('companyid'=>$companyid,'mid'=>$zOrderInfo['mid']))->setInc('totalmoney',$zOrderInfo['orderprice']);
    				$zorderCount = M('mall_exhibition_partner_order')->where(array('companyid'=>$companyid,'mid'=>$zOrderInfo['mid']))->count();
    				if(!$zorderCount){
    					// 累计推广客户数
    					M('mall_exhibition_partner_list')->where(array('companyid'=>$companyid,'mid'=>$zOrderInfo['mid']))->setInc('totalcustomer');
    				}
    			} */
    			$orderGoods = M('mall_order_goods')->where(array('orderid'=>$orderid,'companyid'=>$companyid))->field('id,goodtype,vouchersid,prefix,pricetype,goodid,goodname,goodpic,goodnum,goodskuid,usetimelimittype,usetimelimitset,useshopslimitset,backorderpolicyset,useinfo,goodskuname')->select();
    			$orderGoodsCount = count($orderGoods);
    			$saveGoodsNum = 0;
    			if($orderGoods){
    				foreach ($orderGoods as $oKey=>$oVal){
    					if($oVal['goodtype'] == 1){
    						// 实物商品
    						$mallgoodssalenum = M('mall_goods')->where(array('id'=>$oVal['goodid'],'companyid'=>$companyid))->setInc('salenum',$oVal['goodnum']);
    						$mallgoodsskusalenum = M('mall_goods_sku')->where(array('goodid'=>$oVal['goodid'],'id'=>$oVal['goodskuid'],'companyid'=>$companyid))->setInc('salenum',$oVal['goodnum']);
    					}elseif($oVal['goodtype'] == 2){
    						// 券商品
    						$mallgoods[$oKey]['salenum'] = M('mall_goods')->where(array('id'=>$oVal['goodid'],'companyid'=>$companyid))->setInc('salenum',$oVal['goodnum']);
    					}elseif($oVal['goodtype'] == 3 || $oVal['goodtype'] == 4 || $oVal['goodtype'] == 5 || $oVal['goodtype'] == 6){
    						// 计次卡+团购+门票+权益卡
    						if($oVal['goodtype'] == 6){
    							$mallgoodsskusalenum = 1;
    						}else{
    							$mallgoodsskusalenum = M('mall_goods_sku')->where(array('goodid'=>$oVal['goodid'],'id'=>$oVal['goodskuid'],'companyid'=>$companyid))->setInc('salenum',$oVal['goodnum']);
    						}
    						$mallgoodssalenum = M('mall_goods')->where(array('id'=>$oVal['goodid'],'companyid'=>$companyid))->setInc('salenum',$oVal['goodnum']);
    					}elseif($oVal['goodtype'] == 7){
    						// 卡券礼包
    						$mallgoodssalenum = M('mall_goods')->where(array('id'=>$oVal['goodid'],'companyid'=>$companyid))->setInc('salenum',$oVal['goodnum']);
    					}
    					$saveGoodsNum++;
    				}
    			}
    			if($saveOrderInfo&&$saveGoodsNum==$orderGoodsCount){
    				M()->commit();
    				$return['code'] = 200;
    				// 券订单+计次卡订单+团购+门票+权益卡订单发送虚拟券
    				foreach ($orderGoods as $ogKey=>$ogVal){
    					if($ogVal['goodtype'] == 2){
    						// 券商品
    						$sendReturn = 6;
    					}elseif($ogVal['goodtype'] == 3 || $ogVal['goodtype'] == 4 || $ogVal['goodtype'] == 5 || $ogVal['goodtype'] == 6){
    						// 计次卡+团购+门票+权益卡
    						$sendReturn = 8;
    					}elseif($ogVal['goodtype'] == 7){
    						// 卡券礼包
    						$sendReturn = 11;
    					}
    					if($sendReturn>0){
    						$this->sendMemberVouchersSCRM5($orderInfo['orderid'], $mid, $companyid,$sendReturn,$ogVal['goodskuid'],'',$ogVal['goodtype']);
    					}
    					unset($sendReturn);
    				}
    				$option['cid'] = $companyid;
    				$option['type'] = '110';
    				$option['mid'] = $mid;
    				$option['num'] = $orderInfo['orderprice'];
    				$option['linkorderid'] = $orderInfo['orderid'];
    				$option['linkoutorderid'] = $orderInfo['out_trade_no'];
    				// 注意以前老的版本：1：微信支付；2：银行卡支付；3：支付宝支付；4:货到付款；5：积分支付；6：微信提货；默认：0；这个type 值需要更新为最新的请注意
    				if($orderInfo['orderpaymethod'] == 1){
    					$option['paytype'] = $orderInfo['orderpaymethod'];
    				}elseif($orderInfo['orderpaymethod'] == 7){
    					$option['paytype'] = 4; //储值支付
    				}
    				if($orderInfo['groupinfoid'] != 0){
    					// 拼团成功之后统一送积分
    					$this->changeMemberBusinessSCRM5Int($option);
    				}else{
	    				// 支付成功后记录消费+送积分
	    				$this->changeMemberBusinessSCRM5($option);
    				}
    				// 发送风助手通知消息模板
    				// 会员名称与手机号
    				$memberInfo = M('member_register_info')->where(array('companyid'=>$companyid,'id'=>$mid))->field('name,moblie')->find();
    				$openidList = M('users')->where(array('companyid'=>$companyid))->field('helperopenid as openid,isboss,helperpermissions,phone')->select();
    				$eshoporderisopen = M('company')->where(array('id'=>$companyid))->getField('eshoporderisopen');
    				if($eshoporderisopen == 1){
    					$message = '您好，您有新的Eshop微商城订单。订单号：'.$orderInfo['orderid'].'。下单会员：'.$memberInfo['name'].' '.$memberInfo['moblie'].'。下单时间：'.date('Y-m-d,H:i', $time).'。订单金额：'.$orderInfo['orderprice'].'，请您尽快处理。';
    				}
    				foreach ($openidList as $oKey=>$oVal){
    					$userOpenid = $oVal['openid']?$oVal['openid']:'';
    					$phone = $oVal['phone']?$oVal['phone']:'';
    					if($userOpenid){
    						if($oVal['isboss'] == '1' || in_array('12',explode(',',$oVal['helperpermissions']))){
    							$this->WeChatTemplateMessageSend('47', $userOpenid, $companyid, '', '', array('新eshop订单','Eshop微商城'), array($orderid,$memberInfo['name'].' '.$memberInfo['moblie'],$orderInfo['orderprice'],$orderInfo['ordertitle']));
    						}
    					}
    					if($eshoporderisopen == 1 && $phone){
							if($oVal['isboss'] == '1' || in_array('12',explode(',',$oVal['helperpermissions']))){
								$this->sendSms($phone, $message, $companyid);
	                        }    
	                    }
    					unset($userOpenid,$phone);
    				}
    			}else{
    				M()->rollback();
    				$logData['id'] = guidNow();
    				$logData['cid'] = $companyid;
    				$logData['mid'] = $mid;
    				$logData['log'] = format_time(time(),'ymdhis').'$saveOrderInfo:'.$saveOrderInfo.'$saveGoodsNum==$orderGoodsCount:'.$saveGoodsNum.'=='.$orderGoodsCount.';OrderInfo:'.json_encode($orderInfo);
    				$logData['createtime'] = time();
    				M('log_mall_order_info')->add($logData);
    				$this->sendSms('13564012907', '你有一笔新的商城订单未能完成订单状态的更新，请核查！log日志ID：'.$logData['id'],'1186','【人来风】','','181818');
    			}
    		}elseif (($orderInfo['orderstatus'] == 2||$orderInfo['orderstatus'] == 6) && $orderInfo['temporderstatus']==2 && $orderInfo['paytime']>0){
    			$return['code'] = 201;//已经支付过
    		}
    	}
    	return $return;
    }
     /**
     * 核销卡券统一方法
     * $option['vouchertype']      必填：核销券类型：1：SCRM5卡券；2：微信互通券；
     * $option['vouchernumber']    必填：卡券号
     * $option['companyid']        必填：公司id
     * $option['usetype']          必填：核销类型：1、风助手，2：拉卡拉核销；3：后台核销；4:eshop商城购买实物商品使用优惠券
     * $option['users']            必填：核销人：Wap端核销为核销人的OPENID（1、2）；PC后台核销为核销人的UID（3）；系统（4）；
     * $option['getway']           必填：风助手&拉卡拉 核销方式 ：1：手动输入券号核销；2：扫描二维码进行核销/无核销方式；默认为：2；
     * @return boolean
     * @author Thomas<416369046@qq.com>
     * @since  2016-12-5
     */
    public function verificationVouchersSCRM5($option){
    	$returnData['code'] = 300;
    	$returnData['tips'] = '抱歉，服务器繁忙，请稍后重试';
    	if($option){
    		$time = time();
    		$vouchertype = $option['vouchertype'] ? $option['vouchertype'] : 0 ; // 核销券类型：1：SCRM5卡券；2：微信互通券；
    		$vouchernumber = $option['vouchernumber'] ? $option['vouchernumber'] : 0 ; // 卡券号
    		$companyid = $option['cid'] ? $option['cid'] : 0 ;  // 公司id
    		$usetype = $option['usetype'] ? $option['usetype'] : 0 ;   // 核销类型 1、风助手，2：拉卡拉核销；3：后台核销；
    		$users = $option['users'] ? $option['users'] : 0 ;   // 核销人ID；
    		$getway = $option['getway'] ? $option['getway'] : 0 ;// 风助手&拉卡拉 核销方式 ：1：手动输入券号核销；2：扫描二维码进行核销；
    		$vouchernumber = preg_replace("/\s/","",$vouchernumber);
    		$getway = $getway?$getway:''; //风助手&拉卡拉核销方式：不存在则为空 
    		M()->startTrans();
    		if($vouchertype == '1'){
    			// 获取券的信息
    			$where['companyid'] = $companyid;
    			$where['sn'] = $vouchernumber;
    			$snInfo = M('member_vouchers')->where($where)->field('id,mid,voucherinfoid,sn,parvalue,usestarttime,useendtime,isused,vouchertype,voucherskuname,useinfo,useshopslimitset,usenumberlimit,usednumber,vouchername,scrmactivityid,saleprice,originalprice')->find();
    			// 获取核销人以及核销门店的信息
    			$openWhere['companyid'] = $companyid;
    			$openWhere['id'] = $users;
    			/*
    			if($usetype == 1 || $usetype == 2){
    				$openWhere['helperopenid'] = $users; //风助手&拉卡拉
    			}elseif($usetype == 3){
    				 //pc后台
    			}
    			*/
    			$shopidInfo = M('users')->where($openWhere)->field('id,truename,username,helpershopid,isboss')->find();
    			if($snInfo){
    				//如果核销类型为风助手或者拉卡拉
    				if($usetype == 1 || $usetype == 2){
	    				// 如果券类型为 计次卡、团购、门票、权益卡、门店使用优惠券、兑换券 ，这里判断门店使用限制。
	    				if($snInfo['vouchertype'] == 3 || $snInfo['vouchertype'] == 4 || $snInfo['vouchertype'] == 5 || $snInfo['vouchertype'] == 6 || $snInfo['vouchertype'] == 8 || $snInfo['vouchertype'] == 9){
	    					$isboss = $shopidInfo['isboss'];
	    					$helpershopid = $shopidInfo['helpershopid'];
	    					if($isboss == '1'){
	    						
	    					}else{
	    						if($helpershopid == '-1'){
	    							
	    						}elseif(strpos($snInfo['useshopslimitset'],$shopidInfo['helpershopid']) === FALSE){
	    							$returnData['tips'] = '抱歉，本卡券不可在此门店使用';
	    							echo json_encode($returnData);exit();
	    						}
	    					}
	    				}
    				}elseif($usetype == 3){
    					//新增后台核销记录
    					$recordData['id'] = guidNow();
    					$recordData['sn'] = $snInfo['sn'];
    					$recordData['companyid'] = $companyid;
    					$recordData['vouchername'] = $snInfo['vouchername'];
    					$recordData['vouchertype'] = $snInfo['vouchertype'];
    					$recordData['handleruserid'] = $users;
    					$recordData['handlerusername'] = $shopidInfo['truename'];
    					$recordData['handletype'] = 1;
    					$recordData['usetime'] = $recordData['createtime'] = $recordData['updatetime'] = $time;
    					$recordResult = M('member_vouchers_use_record')->add($recordData);
    				}
    				//卡券在可使用期限内
    				if($snInfo['usestarttime'] <= $time && $snInfo['useendtime'] >= $time){
    					//如果为计次卡 则需要对核销次数进行判断
    					if($snInfo['vouchertype'] == 3){
    						if($snInfo['usednumber'] < $snInfo['usenumberlimit']){
    							// 修改券库并把券改为已使用
    							$voucherWhere['companyid'] = $companyid;
    							$voucherWhere['id'] = $snInfo['id'];
    							if(($snInfo['usednumber']+1) == $snInfo['usenumberlimit']){
    								$voucherData['isused'] = '1';
    								if($snInfo['scrmactivityid']){
	    								//SCRM5活动的券核销量（计次卡只有次数全部核销完成才算一次核销）
	    								$SCRM5ActivityData['updatetime'] = $time;
	    								$SCRM5ActivityData['usenum'] = array('exp', '`usenum`+1');
	    								$SCRM5ActivityReturn = M('member_marketing_activities_scrm_link_voucher')->where(array('id'=>$snInfo['scrmactivityid']))->save($SCRM5ActivityData);
    								}
    							}
    							$voucherData['usetime'] = $time;
    							//手动输入券号的核销方式
    							if($getway == 1){
    								//日志表中是否有计次卡核销记录 （此日志表记录的是核销计次卡时将核销确认时间记录，超过三分钟未确认则核销失败）
    								$logCount = M('member_vouchers_metercard_usetime_log')->where(array('companyid'=>$companyid,'sn'=>$snInfo['sn']))->count();
    								$meterCardData['companyid'] = $companyid;
    								$meterCardData['usetime'] = time() + 180;
    								$meterCardData['status'] = 2; //将核销状态改为核销中
    								if($logCount){
    									$meterCardData['updatetime'] = time();
    									$meterCardResult = M('member_vouchers_metercard_usetime_log')->where(array('companyid'=>$companyid,'sn'=>$snInfo['sn']))->save($meterCardData);
    								}else{
    									//存日志表 记录计次卡最新核销确认时间
    									$meterCardData['id'] = guidNow();
    									$meterCardData['sn'] = $snInfo['sn'];
    									$meterCardData['createtime'] = $meterCardData['updatetime'] = time();
    									$meterCardResult = M('member_vouchers_metercard_usetime_log')->add($meterCardData);
    								}
    								//获取计次卡拥有人的openid
    								$sendopenid = M('member_wechat_info')->where(array('companyid'=>$companyid,'mid'=>$snInfo['mid']))->getField('openid');
    								$messageReturn = $this->WeChatTemplateMessageSend('28', $sendopenid, $companyid,'&sn='.$snInfo['sn'].'&staffOpenid='.$users,'',array('计次卡消使用确认','卡券通知'),array(htmlspecialchars_decode($snInfo['vouchername'])));
    								M()->commit();
    								$returnData['code'] = 201;
    								$returnData['usednumber'] = $snInfo['usednumber'];
    								$returnData['sn'] = $snInfo['sn'];
    								$returnData['tips'] = '消费确认消息已经发送，请等待三分钟，若三分钟内未提示则核销不成功！';
    								//echo json_encode($returnData);exit();
    							}else{
    								//直接扫描二维码的核销方式
    								$vouchersReturn = M('member_vouchers')->where($voucherWhere)->save($voucherData);
    								//计次卡核销使用次数+1
    								M('member_vouchers')->where($voucherWhere)->setInc('usednumber');
    							}
    							if($snInfo['saleprice']){
    								$data['singleprice'] = format_number($snInfo['saleprice']/$snInfo['usenumberlimit']);
    							}
    						}else{
    							$returnData['tips'] = '抱歉，本卡券核销次数已达上限';
    							//echo json_encode($returnData);exit();
    						}
    					}elseif($snInfo['vouchertype'] == 6){
    							$voucherWhere['companyid'] = $companyid;
    							$voucherWhere['id'] = $snInfo['id'];
    							//SCRM5活动的券核销量（权益卡只要核销一次就算，后面就不算了）
    							//获取核销记录表中的权益卡核销记录
    							$equityCount = M('use_vouchers')->where(array('vouchernumber'=>$snInfo['sn']))->count();
    							if(!$equityCount){
    								if($snInfo['scrmactivityid']){
		    							$SCRM5ActivityData['updatetime'] = $time;
		    							$SCRM5ActivityData['usenum'] = 1;
		    							$SCRM5ActivityReturn = M('member_marketing_activities_scrm_link_voucher')->where(array('id'=>$snInfo['scrmactivityid']))->save($SCRM5ActivityData);
    								}
    							}
    							$voucherData['usetime'] = $time;
    							$vouchersReturn = M('member_vouchers')->where($voucherWhere)->save($voucherData);
    							//权益卡核销使用次数+1
    							M('member_vouchers')->where($voucherWhere)->setInc('usednumber');
    							if($snInfo['saleprice']){
    								$data['singleprice'] = format_number($snInfo['saleprice']);
    							}
    					}else{
    						if($snInfo['scrmactivityid']){
	    						// SCRM5 活动 的核销 +1
	    						$SCRM5ActivityData['updatetime'] = $time;
	    						$SCRM5ActivityData['usenum'] = array('exp', '`usenum`+1');
	    						$SCRM5ActivityReturn = M('member_marketing_activities_scrm_link_voucher')->where(array('id'=>$snInfo['scrmactivityid']))->save($SCRM5ActivityData);
    						}
    						// 修改券库并把券改为已使用(普通卡券的券核销)
    						$voucherWhere['companyid'] = $companyid;
    						$voucherWhere['id'] = $snInfo['id'];
    						$voucherData['isused'] = '1';
    						$voucherData['usetime'] = $time;
    						$vouchersReturn = M('member_vouchers')->where($voucherWhere)->save($voucherData);
    						if($snInfo['saleprice']){
    							$data['singleprice'] = format_number($snInfo['saleprice']);
    						}
    					}
    					//券的基本信息
    					$data['vouchername'] = $snInfo['vouchername']; // 券名称
    					if($snInfo['vouchertype'] == 3){
    						$data['value'] = ($snInfo['usednumber']+1).'/'.$snInfo['usenumberlimit'];
    						$data['utility'] = $data['value'] . '次';  // 效用
    					}elseif($snInfo['vouchertype'] == 4 || $snInfo['vouchertype'] == 5){
    						$data['value'] = $snInfo['voucherskuname'];
    						$data['utility'] = $data['value'];  // 规格名称
    					}
    					$data['remarks'] = htmlspecialchars_decode(htmlspecialchars_decode($snInfo['useinfo'])); // 使用说明
    					$snTitle = $snInfo['vouchername']; //发送券标题
    					$data['vouchernumber'] = $snInfo['sn'];
    					$data['vouchertype'] = $snInfo['vouchertype'];
    					//这里只增加 eshop优惠券、门店使用优惠券、兑换券、通用券的核销量（核销量+1）
    					$countSaveData['updatetime'] = $time;
    					if($snInfo['vouchertype'] == 7 || $snInfo['vouchertype'] == 8 || $snInfo['vouchertype'] == 9 || $snInfo['vouchertype'] == 40){
    						$countSaveData['verificationnum'] = array('exp', '`verificationnum`+1');
    						$countSaveReturn = M('member_marketing_activities_voucher_info')->where(array('id'=>$snInfo['voucherinfoid']))->save($countSaveData);
    					}else{
    						$countSaveReturn = 1;
    					}
    					$data['voucherid'] = $snInfo['id'];
    					$data['voucherstarttime'] = $snInfo['usestarttime'];
    					$data['voucherendtime'] = $snInfo['useendtime'];
    					// 会员信息
    					if($snInfo['mid']){
    						$data['mid'] = $snInfo['mid'];
    						$registerWhere['companyid'] = $companyid;
    						$registerWhere['id'] = $snInfo['mid'];
    						$registerInfo = M('member_register_info')->where($registerWhere)->field('id,moblie')->find();
    						if($registerInfo){
    							$data['mobile'] = $registerInfo['moblie'];
    						}
    						$sendOpenid = M('member_wechat_info')->where(array('companyid'=>$companyid,'mid'=>$snInfo['mid']))->getField('openid');
    						/* if($openid){
    							if($snInfo['vouchertype'] == 3){
    								$this->WeChatTemplateMessageSend('29',$openid,$companyid,'','',array('使用计次卡','卡券通知'),array($snTitle,($snInfo['usenumberlimit']-($snInfo['usednumber']+1))));
    							}else{
    								$this->WeChatTemplateMessageSend('10',$openid,$companyid,'','',array('核销卡券','卡券通知'),array($snTitle));
    							}
    						} */
    					}
    				}else{
    					$returnData['tips'] = '抱歉，本卡券不在可使用时间内';
    				}
    			}else{
    				$returnData['tips'] = '本卡券号不存在';
    			}
    		}elseif($vouchertype == '2'){   // 微信互通券
    			$where['apply.companyid'] = $companyid;
    			$where['consume.cardcode'] = $vouchernumber;
    			// $where['consume.isconsume'] = '2';
    			// $where['consume.codestarttime'] = array('elt',$time);
    			// $where['consume.codeendtime'] = array('egt',$time);
    			$where['apply.checkstatus'] = '1';
    			$where['apply.cardtype'] = array('IN','1,2,3,4,5');
    			$info = M()->table('tp_wechat_voucher_consume_history AS consume')->join('LEFT JOIN tp_wechat_voucher_apply_history AS apply ON apply.cardid = consume.cardid')->where($where)->field('consume.id,cardcode,cardtype,maintitle,discount,giftname,reduction,enable,  consume.isconsume,consume.codestarttime,consume.codeendtime, remarks,openid')->find();
    			if($info){
    				if($info['isconsume'] == '2'){
    					if($info['codestarttime']<=$time && $info['codeendtime']>=$time){
    						// 核销券
    						$wechatsInfo = M('wechats')->where(array('companyid'=>$companyid,'wechattype'=>'4'))->field('id,token,appid,appsecret,encodingaeskey')->find();
    						if($wechatsInfo){
    							$wechat = new Wechat(array('token'=>$wechatsInfo['token'],'appid'=>$wechatsInfo['appid'],'appsecret'=>$wechatsInfo['appsecret']));
    						}
    						if($wechat){
    							$consumeCardCodeData = $wechat->consumeCardCode($vouchernumber);
    							if($consumeCardCodeData['errcode'] == '0'){
    								/*
    								 $voucherWhere['companyid'] = $companyid;
    								$voucherWhere['cardsn'] = $vouchernumber;
    								$voucherData['isconsume'] = '1';
    								$voucherData['consumetime'] = $voucherData['updatetime'] = $time;
    								$vouchersReturn = M('helper_card_consume_temporary')->where($voucherWhere)->save($voucherData);
    								*/
    								$vouchersReturn = '1';
    								$data['vouchernumber'] = $info['cardcode'];
    								$data['vouchername'] = $info['maintitle'];
    								$data['vouchertype'] = '4';
    								if($info['cardtype'] == '1'){    // 通用券
    									$data['wechatvouchertype'] = '1';
    								}elseif($info['cardtype'] == '2'){   // 团购券
    									$data['wechatvouchertype'] = '2';
    								}elseif($info['cardtype'] == '3'){   // 折扣券
    									$data['wechatvouchertype'] = '3';
    									$discount = $info['discount'] ? $info['discount'] : '0.00';
    									$utility = $discount.'%';
    									$data['utility'] = $utility;
    								}elseif($info['cardtype'] == '4'){   // 礼品券
    									$data['wechatvouchertype'] = '4';
    									$giftname = $info['giftname'] ? $info['giftname'] : '礼物一份';
    									$utility = $giftname;
    									$data['utility'] = $utility;
    								}elseif($info['cardtype'] == '5'){   // 代金券
    									$data['wechatvouchertype'] = '5';
    									$reduction = $info['reduction'] ? $info['reduction'] : '0.00';
    									$enable = $info['enable'] ? $info['enable'] : '0.00';
    									$utility = '满'.$enable.'减'.$reduction;
    									$data['utility'] = $utility;
    								}
    								$data['remarks'] = $info['remarks'];
    								$data['voucherid'] = $info['id'];
    								$data['voucherstarttime'] = $info['codestarttime'];
    								$data['voucherendtime'] = $info['codeendtime'];
    								$countSaveReturn = 1;
    								// 会员信息
    								if($info['openid']){
    									$wechatWhere['wechatInfo.companyid'] = $companyid;
    									$wechatWhere['wechatInfo.openid'] = $info['openid'];
    									$wechatInfo = M()->table('tp_member_wechat_info AS wechatInfo')->join('tp_member_register_info AS registerInfo ON wechatInfo.mid=registerInfo.id')->where($wechatWhere)->field('wechatInfo.id,wechatInfo.mid,registerInfo.moblie')->find();
    									if($wechatInfo){
    										$data['mid'] = $wechatInfo['mid'];
    										$data['openid'] = $info['openid'];
    										$data['mobile'] = $wechatInfo['moblie'];
    										//$this->WeChatTemplateMessageSend('10',$info['openid'],$companyid,'','',array('核销微信卡券','券通知'),array($info['maintitle']));
    									}
    								}
    							}
    						}else{
    							$returnData['tips'] = '微信信息配置错误，请检查';
    						}
    					}else{
    						$returnData['tips'] = '抱歉，本卡券不在使用范围内';
    					}
    				}else{
    					$returnData['tips'] = '抱歉，本卡券已经使用';
    				}
    			}else{
    				$returnData['tips'] = '抱歉，本券号不存在';
    			}
    		}
    		if($vouchersReturn){
    			if($usetype == 1 || $usetype == 2){
    				$openid = $users; //风助手&拉卡拉处理人
    			}elseif($usetype == 3){
    				$openid = $users; //pc后台处理人
    			}elseif($usetype == 4){
    				$openid = 1; //eshop使用优惠券
    			}
    			if($openid){
    				$openWhere['companyid'] = $companyid;
    				if($usetype == 1 || $usetype == 2){
    					$data['staffopenid'] = $openid;   // 核销人openid
    					$openWhere['id'] = $openid; //风助手/拉卡拉处理人
    				}elseif($usetype == 3){
    					$data['staffopenid'] = '';   // 核销人openid
    					$openWhere['id'] = $openid; //pc后台处理人
    				}elseif($usetype == 4){
    					$data['staffopenid'] = '';   // 核销人openid
    				}
    				if($usetype == 1 || $usetype == 2 || $usetype == 3){
	    				$openInfo = M('users')->where($openWhere)->field('id,truename,username,helpershopid,isboss')->find();
	    				if($openInfo){
	    					$data['staffname'] = $openInfo['truename'];   // 核销人姓名
	    					$isboss = $openInfo['isboss'];
	    					$helpershopid = $openInfo['helpershopid'];
	    					if($isboss == '1'){
	    						$data['shopid'] = '-1';  // 核销门店
	    						$data['shopname'] = '总部';  // 核销门店名称
	    					}else{
	    						if($helpershopid == '-1'){
	    							$data['shopid'] = '-1';
	    							$data['shopname'] = '总部';
	    						}else{
	    							$shopInfo = M('company_shops')->where(array('companyid'=>$companyid, 'id'=>$helpershopid))->field('id,shopname,name')->find();
	    							$data['shopid'] = $helpershopid;
	    							$data['shopname'] = $shopInfo['shopname']?$shopInfo['shopname']:$shopInfo['name'];
	    						}
	    					}
	    					if($usetype == 1){
	    						$data['type'] = '1';
	    					}elseif($usetype == 2){
	    						$data['type'] = '2';
	    					}elseif($usetype == 3){
	    						$data['type'] = '3';
	    					}
	    				}
    				}elseif($usetype == 4){
    					$data['staffname'] = '系统';   // 核销人姓名
    					$data['shopid'] = '';  // 核销门店id
    					$data['shopname'] = '';  // 核销门店名称
    					$data['type'] = '4';
    				}
    				$data['id'] = guidNow();
    				$data['companyid'] = $companyid;
    				$data['isconsume'] = '1';
    				$data['usetime'] = $time;
    				$data['createtime'] = $data['updatetime'] = $time;
    				$return = M('use_vouchers')->add($data);
    			}
    		}
    		if($return && $countSaveReturn){
    			M()->commit();
    			$returnData['code'] = 200;
    			$returnData['tips'] = '恭喜，卡券核销成功';
				if($vouchertype == 1){
					if($snInfo['vouchertype'] == 3){
						$this->WeChatTemplateMessageSend('29',$sendOpenid,$companyid,'','',array('使用计次卡','卡券通知'),array(htmlspecialchars_decode($snTitle),($snInfo['usenumberlimit']-($snInfo['usednumber']+1))));
					}else{
						$this->WeChatTemplateMessageSend('10',$sendOpenid,$companyid,'','',array('核销卡券','卡券通知'),array(htmlspecialchars_decode($snTitle)));
					}
				}elseif($vouchertype == 2){
					$this->WeChatTemplateMessageSend('10',$info['openid'],$companyid,'','',array('核销微信卡券','券通知'),array(htmlspecialchars_decode($info['maintitle'])));
				}
				// tag 统计会员 卡券使用频次 多久未使用卡券
				if($snInfo['mid']){
					$memberRegisterInfo = M('member_register_info')->where(array('id'=>$snInfo['mid'],'companyid'=>$companyid))->field('usevouchersfrequencytag,lastusevoucherstime,howlongusevoucherstag,createtime')->find();
					// 查询这个人总共使用了多少次卡券记录
					$usevouchersCount = M('use_vouchers')->where(array('mid'=>$snInfo['mid'],'companyid'=>$companyid))->count();
					// 判断这个会员注册时间
					$diffTime = get_days($memberRegisterInfo['createtime']);
					if($diffTime < 365){
						$usevoucherssection = $diffTime/($usevouchersCount);
					}else{
						$usevoucherssection = 365/($usevouchersCount);
					}
					// 统计卡券使用频次标签
					if ($usevoucherssection > 0 && $usevoucherssection <= 7){
						$memberRegisterInfoSaveData['usevouchersfrequencytag'] = '2';
					}elseif ($usevoucherssection > 7 && $usevoucherssection <= 15){
						$memberRegisterInfoSaveData['usevouchersfrequencytag'] = '3';
					}elseif ($usevoucherssection > 15 && $usevoucherssection <= 30){
						$memberRegisterInfoSaveData['usevouchersfrequencytag'] = '4';
					}elseif ($usevoucherssection > 30 && $usevoucherssection <= 60){
						$memberRegisterInfoSaveData['usevouchersfrequencytag'] = '5';
					}elseif ($usevoucherssection > 60 && $usevoucherssection <= 180){
						$memberRegisterInfoSaveData['usevouchersfrequencytag'] = '6';
					}elseif ($usevoucherssection > 180){
						$memberRegisterInfoSaveData['usevouchersfrequencytag'] = '7';
					}
					if($memberRegisterInfo['usevouchersfrequencytag'] != $memberRegisterInfoSaveData['usevouchersfrequencytag']){
						$this->memberTagCount($companyid, array(array('name'=>'usevouchersfrequency','before'=>$memberRegisterInfo['usevouchersfrequencytag'],'after'=>$memberRegisterInfoSaveData['usevouchersfrequencytag'])));
					}
					// 统计多久未使用卡券标签  获取最后一次有效的使用卡券记录时间
					$lastUseVouchersInfo = M('use_vouchers')->where(array('companyid'=>$companyid,'mid'=>$snInfo['mid']))->order('createtime DESC')->field('createtime')->find();
					$memberRegisterInfoSaveData['lastusevoucherstime'] = $lastUseVouchersInfo['createtime'];
					$memberRegisterInfoSaveData['howlongusevoucherstag'] = '0'; // 这里存储为空标签
					if($memberRegisterInfo['howlongusevoucherstag'] != $memberRegisterInfoSaveData['howlongusevoucherstag']){
						$this->memberTagCount($companyid, array(array('name'=>'howlongusevouchers','before'=>$memberRegisterInfo['howlongusevoucherstag'],'after'=>$memberRegisterInfoSaveData['howlongusevoucherstag'])));
					}
					// 储存会员信息的修改
					if($memberRegisterInfoSaveData){
						$memberRegisterInfoSaveData['updatetime'] = $time;
						$memberRegisterInfoSaveReturn = M('member_register_info')->where(array('id'=>$snInfo['mid'],'companyid'=>$companyid))->save($memberRegisterInfoSaveData);
					}
				}
    		}else{
    			M()->rollback();
    			$returnData['code'] = 300;
    		}
    	}else{
    		$returnData['tips'] = '参数设置错误';
    	}
    	return $returnData;
    	//echo json_encode($returnData);
    }
    /**
     *
     * 首次消费直赠
     *
     * @return boolean
     * @author Mark<1311013341@qq.com>
     * @since  2016-12-1
     */
    public function sendConsumptionMemberActivitiesVouchers($companyid,$mid){
        $time = time();
        $whereA['activity.type'] = 7;
        $whereA['activity.companyid'] = $companyid;
        $whereA['activity.starttime'] = array('elt',time());
        $whereA['activity.endtime'] = array('egt',time());
        $whereA['activity.issuspend'] = array('neq','1');
        $whereA['activity.status'] = array('neq','1');
        $whereA['_string'] = ' company.gid = "5" OR company.viptime > '.$time;
        $voucherList = M()->table('tp_member_marketing_activities_scrm as activity')->join('left join tp_company as company on company.id=activity.companyid')
        ->where($whereA)->field('activity.id,activity.tagjsoninfo,activity.companyid')->select();
        if($voucherList){
            foreach ($voucherList as $vKey=>$vVal){
                //  标签判断
                $tag = json_decode($vVal['tagjsoninfo'],true);
                if($tag){
                    foreach ($tag as $tKey=>$tVal){
                        if($tVal){
                            if($tKey == 'rankid'){
                                //等级标签
                                $memberWhere['info.rankid'] =  array('in',substr($tVal,1,-1));;
                            }elseif($tKey == 'membertagsid'){
                                //自定义标签
                                if($tVal && $tVal != ','){
                                    $wheretags = '';
                                    $arrtags = explode(',', $tVal);
                                    foreach ($arrtags as $atKey=>$atVal){
                                        if($atVal){
                                            $wheretags .=" (member.membertagsid like '%,".$atVal.",%') AND";
                                        }
                                        unset($atVal);
                                    }
                                    if($wheretags){
                                        $wheretags = substr($wheretags, 0,-3);
                                        $memberWhere['_string'] = $wheretags;
                                    }
                                }
                            }else{
                                //  其他
                                $memberWhere['member.'.$tKey] =  array('in',substr($tVal,1,-1));;
                            }
                        }
                        unset($tVal,$wheretags,$arrtags);
                    }
                }
                $memberWhere['member.companyid'] = $vVal['companyid'];
                $memberWhere['member.id'] = $mid;
                //  查询符合的会员
                $memberList = M()->table('tp_member_register_info as member')->join(array('left join tp_member_card_info as info on info.mid=member.id'))->where($memberWhere)->field('member.id')->find();
                if($memberList){
                    $sql = M()->getLastSql();
                    $register = $this->sendMemberVouchersSCRM5($vVal['id'], $memberList['id'], $vVal['companyid'],'9');
                    $logdata['id'] = guidNow();
                    if($register){
                        $logdata['type'] = '1';
                        $sql = '';
                    }else{
                        $logdata['type'] = '2';
                    }
                    $logdata['log'] = json_encode(array('开始时间:'=>$time,'结束时间:'=>time(),'活动id:'=>$vVal['id'],'mid:'=>$mid,'错误SQL:'=>$sql));
                    $logdata['createtime'] = time();
                    M('log_member_marketing_activities_scrm')->add($logdata);
                }
                unset($vVal,$saveActivityAutoStatus,$logdata);
            }
            unset($cVal,$voucherList);
        }
    }
    /**
     * 【新版】商城拼团订单支付
     * @param  $orderID		订单id
     * @param  $companyid	公司id
     * @return number
     * @author Thomas<416369046@qq.com>
     * @since  2017-1-4
     */
    public function mallGroupOrderPaySCRM5($orderID,$companyid){
    	$return['code'] = 300;
    	if($orderID && $companyid){
    		$where['orderid'] = $orderID;
    		$where['companyid'] = $companyid;
    		$orderInfo = M('mall_order_info')->where($where)->field('goodtype,mid,companyid,borderid,orderid,out_trade_no,ordertitle,temporderstatus,orderstatus,paytime,createtime,orderprice,orderpaymethod,groupinfoid')->find();
    		$groupMemberInfo = M('mall_groupon_info_member')->where(array('companyid'=>$companyid,'mid'=>$orderInfo['mid'],'groupinfoid'=>$orderInfo['groupinfoid']))->field('isleader')->find();
    		if($orderInfo['temporderstatus']==1){
    			M()->startTrans();
    			$time = time();
    			//修改拼团商品表信息
    			if($orderInfo['groupinfoid'] != 0){
    				$wheregroup['companyid'] = $companyid;
    				$wheregroup['id'] = $orderInfo['groupinfoid'];
    				if($groupMemberInfo['isleader'] == 2){
	    				$groupData['joingroupnum'] = array('exp', '`joingroupnum`+1');
	    				$groupData['updatetime'] = $time;
	    				$groupData['groupendtime'] = strtotime('+30 min');
    				}else{
    					$groupData['updatetime'] = $time;
    					$groupData['groupendtime'] = strtotime('+30 min');
    				}
    				$groupResult = M('mall_groupon_info')->where($wheregroup)->save($groupData);
    				//将付款状态改为已付款
    				$groupMemberData['ispay'] = 1;
    				$groupMemberData['updatetime'] = $time;
    				$groupMemberResult = M('mall_groupon_info_member')->where(array('companyid'=>$companyid,'mid'=>$orderInfo['mid']))->save($groupMemberData);
    			}
    			if($groupResult && $groupMemberResult){
    				M()->commit();
    				$return['code'] = 200;
    				
    				// 支付成功后记录消费+不送积分
    				$option['cid'] = $companyid;
    				$option['type'] = '110';
    				$option['mid'] = $orderInfo['mid'];
    				$option['num'] = $orderInfo['orderprice'];
    				$option['linkorderid'] = $orderInfo['orderid'];
    				$option['linkoutorderid'] = $orderInfo['out_trade_no'];
    				$option['issendint'] = 2;
    				// 注意以前老的版本：1：微信支付；2：银行卡支付；3：支付宝支付；4:货到付款；5：积分支付；6：微信提货；默认：0；这个type 值需要更新为最新的请注意
    				if($orderInfo['orderpaymethod'] == 1){
    					$option['paytype'] = $orderInfo['orderpaymethod'];
    				}elseif($orderInfo['orderpaymethod'] == 7){
    					$option['paytype'] = 4; //储值支付
    				}
    				$this->changeMemberBusinessSCRM5($option);
    				
    				$info['groupMemberInfo'] = M('mall_groupon_info_member')->where(array('companyid'=>$companyid,'groupinfoid'=>$orderInfo['groupinfoid']))->field('id,mid,groupid,groupinfoid,orderid,nickname,headimgurl,base64nickname,isleader,createtime,ispay')->select();
    				//查询成团人数与参团人数对比 若人数相等，实物商品改成待发货状态，券商品改为卡券已签收状态，否则还是待成团状态
    				$groupInfo = M('mall_groupon_info')->where(array('companyid'=>$companyid,'id'=>$orderInfo['groupinfoid']))->field('id,groupid,goodid,grouporderid,goodskuid,groupstatus,groupnum,joingroupnum,createtime')->find();
    				if($groupInfo['groupnum'] == $groupInfo['joingroupnum']){
    					if($orderInfo['goodtype'] == 1){
    						//这里去修改订单状态 改为待发货状态
    						$data['orderstatus'] = 2;
    						$data['updatetime'] = $time;
    						$result = M('mall_order_info')->where(array('companyid'=>$companyid,'groupinfoid'=>$orderInfo['groupinfoid']))->save($data);
    					}else{
    						//这里去修改订单状态 改为卡券已签收
    						$data['orderstatus'] = 4;
    						$data['updatetime'] = $time;
    						$result = M('mall_order_info')->where(array('companyid'=>$companyid,'groupinfoid'=>$orderInfo['groupinfoid']))->save($data);
    					}
    					//将拼团状态改为已成团
    					$whereinfo['companyid'] = $companyid;
    					$whereinfo['id'] = $orderInfo['groupinfoid'];
    					$infoData['groupstatus'] = 2;
    					$infoData['updatetime'] = $time+1;
    					$infoResult = M('mall_groupon_info')->where($whereinfo)->save($infoData);
    					foreach($info['groupMemberInfo'] as $key=>$val){
    						if($val['ispay'] == 1){
    							//付款状态为1 的订单才去执行方法
    							$this->mallOrderPaySCRM5($val['orderid'],$companyid);
    							$sendOpenid = M('member_wechat_info')->where(array('companyid'=>$companyid,'mid'=>$val['mid']))->getField('openid');
    							//发送拼团成功的消息模板
    							$this->WeChatTemplateMessageSend('45', $sendOpenid, $companyid, '', '', array('组团成功','Eshop微商城'), array($orderInfo['ordertitle']));
    						}elseif($val['ispay'] == 2){
    							//这里将其余的参团人的订单状态改为已取消
    							$orderData['orderstatus'] = 5;
    							$orderData['updatetime'] = $time+1;
    							$orderResult = M('mall_order_info')->where(array('companyid'=>$companyid,'orderid'=>$val['orderid']))->save($orderData);
    						}
    					}
    				}else{
    					//这里去修改订单状态 改为待成团状态
    					$data['orderstatus'] = 11;
    					$data['updatetime'] = $time;
    					$result = M('mall_order_info')->where(array('companyid'=>$companyid,'groupinfoid'=>$orderInfo['groupinfoid']))->save($data);
    				}
    			}else{
    				M()->rollback();
    				$logData['id'] = guidNow();
    				$logData['cid'] = $companyid;
    				$logData['mid'] = $orderInfo['mid'];
    				$logData['log'] = format_time(time(),'ymdhis').'$groupResult:'.$groupResult;
    				$logData['createtime'] = time();
    				M('log_mall_order_info')->add($logData);
    			}
    		}elseif (($orderInfo['orderstatus'] == 2||$orderInfo['orderstatus'] == 4) && $orderInfo['temporderstatus']==2 && $orderInfo['paytime']>0){
    			$return['code'] = 201;//已经支付过
    		}
    	}
    	return $return;
    }
    /**
     * 邀请赠礼时的发券方法
     * @param array $option  companyid,mid
     * @author Asa<asa@renlaifeng.cn>
     * @since  2017-1-22
     */
    public function yVoucherInfo($option){
        if($option['mid']){
            $Awhere['mid'] = $option['mid'];
            $Awhere['parentmid'] =array('neq',0);
            $Awhere['companyid'] = $option['companyid'];
            $Awhere['ismoney'] = array('neq',1);
            $Minfo = M("member_marketing_activities_scrm_link_voucher_list")->where($Awhere)->find();
            //M("log_member_marketing_activities_scrm")->add(array("log"=>"1+".$Minfo['actid'],"id"=>guidNow()));
            if($Minfo){
                $where['id'] = $Minfo['actid'];
                $where['starttime'] = array("elt",time());
                $where['endtime'] = array("egt",time());
                $where['companyid'] = $option['companyid'];
                $where['type'] = 10;
                $info = M("member_marketing_activities_scrm")->where($where)->order("createtime desc")->find();
                if($info){
                    $inviteraward = json_decode($info['inviteraward'],true);
                    $voucherCount = M("member_marketing_activities_scrm_link_voucher_list")->where(array("companyid"=>$option['companyid'],'actid'=>$info['id'],"parentmid"=>$Minfo['parentmid'],"ismoney"=>1))->count();
                    foreach ($inviteraward as $key =>$val){
                        if($val['cansendmaxnum']==($voucherCount+1)){
                            $inviteraward[$key]['znum'] = $val['znum']+1;
                            $register3 = $this->sendMemberVouchersSCRM5($val['voucherid'], $Minfo['parentmid'], $option['companyid'],'13',$val['voucherskuid'],$val['vouchertype']);
                        }
                    }
                    $dataA['inviteraward'] = json_encode($inviteraward);
                    $dataA['updatetime'] = time();
                    $register2 = M("member_marketing_activities_scrm")->where(array("companyid"=>$option['companyid'],'id'=>$info['id']))->save($dataA);
                    if($register2){
                        M("member_marketing_activities_scrm_link_voucher_list")->where($Awhere)->save(array('ismoney'=>1));
                    }
                }
            }
        }
    }
    /**
     *
     * 邀请赠礼-通过二维码扫描过来的值
     *
     * @param array $option  companyid,mid
     * @author Asa<asa@renlaifeng.cn>
     * @since  2017-2-9
     */
    public function wechatYVoucher($option){
        if($option['mid']){
            $Sinfo = M("member_marketing_activities_scrm_member_code")->where(array("id"=>$option['scene_id'],"companyid"=>$option['companyid']))->find();
            if($Sinfo){
                $Rwhere['id'] = $option['mid'];
                $Rwhere['companyid'] = $option['companyid'];
                $Rwhere['isregister'] = 1;
                $Rinfo = M("member_register_info")->where($Rwhere)->find();
                if($Rinfo){
                    M("log_member_marketing_activities_scrm")->add(array("log"=>"扫码邀请失败，原因是表里存在这个人已经是老会员了，公司id：".$option['companyid'].";mid：".$option['mid'],"id"=>guidNow()));
                }else{
                    $Awhere['mid'] = $option['mid'];
                    $Awhere['parentmid'] =array('neq',0);
                    $Awhere['companyid'] = $option['companyid'];
                    //$Awhere['ismoney'] = array('neq',1);
                    $Minfo = M("member_marketing_activities_scrm_link_voucher_list")->where($Awhere)->find();
                    if($Minfo){
                        M("log_member_marketing_activities_scrm")->add(array("log"=>"扫码邀请失败，原因是表里存在这个人的邀请记录了公司id：".$option['companyid'].";mid：".$option['mid'],"id"=>guidNow()));
                    }else{
                        $Vwhere['companyid'] = $option['companyid'];
                        $Vwhere['type'] = 10;
                        $Vwhere['starttime'] = array("elt",time());
                        $Vwhere['endtime'] = array("egt",time());
                        $Vinfo = M("member_marketing_activities_scrm")->where($Vwhere)->field("id,inviteraward")->find();
                        if($Vinfo){
                            $voucherList = M("member_marketing_activities_scrm_link_voucher")->where(array("companyid"=>$option['companyid'],'parentid'=>$Vinfo['id']))->select();
                            foreach ($voucherList as $key => $val){
                                //dump($val);
                                $register2 = $this->sendMemberVouchersSCRM5($val['voucherid'], $option['mid'], $option['companyid'],'13',$val['voucherskuid'],$val['vouchertype']);
                            }
                            //存列表
                            $dataasa2['id'] = guidNow();
                            $dataasa2['companyid'] = $option['companyid'];
                            $dataasa2['actid'] = $Vinfo['id'];
                            $dataasa2['headimgurl'] = $option['headimgurl']?$option['headimgurl']:'';
                            $dataasa2['nickname'] = $option['nickname']?$option['nickname']:'';
                            $dataasa2['parentmid'] = $Sinfo['mid'];
                            $dataasa2['createtime'] = time();
                            $dataasa2['mid'] = $option['mid'];
                            M("member_marketing_activities_scrm_link_voucher_list")->add($dataasa2);
                        }else{
                            M("log_member_marketing_activities_scrm")->add(array("log"=>"没有活动","id"=>guidNow()));
                        }
                    }
                }
                 
            }
        }
    }
    /**
     *
     * 展业伙伴-通过二维码扫描过来的值终生绑定
     *
     * @param array $option  companyid,mid
     * @author Asa<asa@renlaifeng.cn>
     * @since  2017-2-9
     */
    public function wechatYVoucher2($option){
    	if($option['openid']){
    		$Sinfo = M("mall_exhibition_partner_member_code")->where(array("id"=>$option['scene_id'],"companyid"=>$option['companyid']))->find();
    		if($Sinfo){
    			// 查询这个用户是否已经绑定关系
    			$wechatInfo = M('member_wechat_info')->where(array('companyid'=>$option['companyid'],'openid'=>$option['openid']))->field('id,mid,zpartnermid')->find();
    			if(!$wechatInfo['zpartnermid']){
	    			$orderCount = M('mall_order_info')->where(array('companyid'=>$option['companyid'],'mid'=>$wechatInfo['mid'],'orderstatus'=>4))->count();
	    			if(!$orderCount){
		    			$Rwhere['openid'] = $option['openid'];
		    			$Rwhere['companyid'] = $option['companyid'];
		    			$Rdata['zpartnermid'] = $Sinfo['mid'];
		    			$Rdata['updatetime'] = time();
		    			$Rinfo = M("member_wechat_info")->where($Rwhere)->save($Rdata);
	    			}
    			}
    		}
    	}
    }
    /**
     *
     * 储值充值送卡券
     *
     * @param array $option  companyid,mid,spendingamount
     * @author Asa<asa@renlaifeng.cn>
     * @since  2017-3-18
     */
    public function crmActivitiesStoredvalue($option){
    	if($option['mid']){
    		$where['companyid'] = $option['companyid'];
    		$where['type'] = 13;
    		$where['issuspend'] = array('neq',1);
    		$where['starttime'] = array('lt',time());
    		$where['endtime'] = array('gt',time());
    		$Qinfo = M("member_marketing_activities_scrm")->where($where)->field("id,starttime,endtime,tagjsoninfo,ystatus,howmoneyl,howmoneyd")->select();
    		if($Qinfo){
    			// 遍历这个公司正在进行的活动
    			foreach($Qinfo as $key => $val){
    				if($val['ystatus']==1){ //累计充值奖励
    				$spendingamount = M("member_spending")->where(array('createtime'=>array('between',array($val['starttime'],$val['endtime'])),'companyid'=>$option['companyid'],'mid'=>$option['mid'],'rechargetype'=>array('in','1,2,5'),'status'=>1))
    					->sum('spendingamount');
    					if($val['howmoneyl']<=$spendingamount){
    						$voucherCount = M("member_vouchers")->where(array('companyid'=>$option['companyid'],"voucherid"=>$val['id'],"mid"=>$option['mid']))->count();
    						if($voucherCount<=0)
    							$status = 1; //可以发券
    						else
    							$status = 2; //不能发券
    					}else{
    						$status = 2; //不能发券
    					}
    				}else{  //单次充值奖励、
    					$spendingamount = $option['spendingamount'];
    					if($val['howmoneyd']<=$spendingamount){
    						$status = 1; //可以发券
    					}else{
    						$status = 2; //不能发券
    					}
    				}
    				/* 下面是真发券  */
    				if($status==1){
    					$tag = json_decode($val['tagjsoninfo'],true);
    					if($tag){
    						foreach ($tag as $tKey=>$tVal){
    							if($tVal){
    								if($tKey == 'rankid'){
    									//等级标签
    									$memberWhere['info.rankid'] = array('in',substr($tVal,1,-1));
    								}elseif($tKey == 'membertagsid'){
    									//自定义标签
    									if($tVal && $tVal != ','){
    										$wheretags = '';
    										$arrtags = explode(',', $tVal);
    										foreach ($arrtags as $atKey=>$atVal){
    											if($atVal){
    												$wheretags .=" (member.membertagsid like '%,".$atVal.",%') AND";
    											}
    											unset($atVal);
    										}
    										if($wheretags){
    											$wheretags = substr($wheretags, 0,-3);
    											$memberWhere['_string'] = $wheretags;
    										}
    									}
    								}else{
    									//  其他
    									$memberWhere['member.'.$tKey] = array('in',substr($tVal,1,-1));
    								}
    							}
    							unset($tVal,$wheretags,$arrtags);
    						}
    					} //the end is if
    					$memberWhere['member.companyid'] = $option['companyid'];
    					$memberWhere['member.id'] = $option['mid'];
    					//  查询符合的会员
    					$memberList = M()->table('tp_member_register_info as member')->join(array('left join tp_member_card_info as info on info.mid=member.id'))->where($memberWhere)->field('member.id')->select();
    					if($memberList){
    						$register = $this->sendMemberVouchersSCRM5($val['id'], $option['mid'], $option['companyid'],'9');
    					}
    				}//结束发券
    			}
    		}
    	}
    }
}
?>