<?php
/**
 * User 基本操作 
 * Enter description here ...
 * @author yongfei.zhao
 */
class UserAction extends BaseAction{
	private $companyid;
	protected function _initialize(){
		parent::_initialize();
		/* 四大菜单对应的欢迎页 */
		$this->MANAGE_WELCOME = array('name'=>'管理','url'=>'','rel'=>'','target'=>'');
		$this->CRM_WELCOME = array('name'=>'CRM','url'=>'','rel'=>'','target'=>'');
		$this->SCENE_WELCOME = array('name'=>'场景','url'=>'','rel'=>'','target'=>'');
		$this->APPRECIATION_WELCOME = array('name'=>'增值','url'=>'','rel'=>'','target'=>'');
		// $this->checkUserLogin();//检测是否登录
		// if(session('cid')){
			// $this->companyid = session('cid');
			// $this->assign('companyid',$this->companyid);
		// }else{
			// $this->error('请先登录本系统');
		// }
		session('cid','1');
		$this->companyid = 1;
		//系统通知统计
		$num['commonBook'] = $num['diningBook'] = $num['offlineApply'] = 0;
		if(in_array(14,session('permissions'))){
			$num['commonBook'] = M('common_book')->where(array('companyid'=>$this->companyid,'bookstatus'=>'1'))->count();
		}
		if(in_array(93,session('permissions'))){
			$num['diningBook'] = M('dining_book')->where(array('companyid'=>$this->companyid,'bookstatus'=>'1'))->count();
		}
		if(in_array(69,session('permissions'))){
			$num['offlineApply'] = M('member_line_apply_activities_order')->where(array('companyid'=>$this->companyid,'status'=>'1'))->count();
		}
		if(in_array(209,session('permissions'))){
			$num['mallorder'] = M('mall_order_info')->where(array('companyid'=>$this->companyid,'orderstatus'=>'2'))->count();
		}
		$num['all'] = $num['commonBook']+$num['diningBook']+$num['offlineApply']+$num['mallorder'];
		//站内信统计
		$num['notices'] = $num['message'] = 0;
		if(in_array(27,session('permissions'))){
			//$num['notices'] = M('member_notices')->where(array('companyid'=>$this->companyid,'type'=>'2','msgtype'=>'2','isread'=>'2'))->count('distinct(mid)');
			$num['notices'] = M()->table('tp_member_notices as notice')->join(array('LEFT JOIN tp_member_register_info as register on register.id=notice.mid'))->where(array('notice.companyid'=>$this->companyid,'notice.type'=>'2','notice.msgtype'=>'2','notice.isread'=>'2','register.noticesisreply'=>array('in','1,2')))->count('distinct(notice.mid)');
		}
		//微信统计
		if(in_array(28,session('permissions'))){
			//$num['message'] = M('member_wechat_24hourmessage')->where(array('companyid'=>$this->companyid,'msgtype'=>'2','isread'=>'1'))->count('distinct(mid)');
			$num['message'] = M()->table('tp_member_wechat_24hourmessage as 24h')->join(array('LEFT JOIN tp_member_wechat_info AS wechat on wechat.mid=24h.mid'))->where(array('24h.companyid'=>$this->companyid,'24h.msgtype'=>'2','24h.isread'=>'1','wechat.wechatmessageisreply'=>array('in','1,2')))->count('distinct(wechat.mid)');
		}
		//导出任务统计
		$num['count'] = M('export_task')->where(array('companyid'=>$this->companyid,'state'=>1))->count();
		//接入api名称
		$APIName = M('wechats')->where(array('companyid'=>$this->companyid))->getField('wxname');
		//获取API接入管理的微信id
		$wechats = D('Wechats')->getCompanyWechatss(array('companyid'=>$this->companyid));
		$this->assign('APIName',$APIName);
		$this->assign('wechats',$wechats);
		$this->assign('num',$num);
		/* SAT */
		$checkcompanyid = M('company')->where(array('id'=>$this->companyid))->getField('companyid');
		//$list_sat = M('check_customer_info')->where(array('id'=>$checkcompanyid))->field('aeuser,amuser')->find();
		//$aemobile = M('sell_staffs')->where(array('name'=>$list_sat['aeuser']))->field('mobile')->find();
		//$ammobile = M('sell_staffs')->where(array('name'=>$list_sat['amuser']))->field('mobile')->find();
		$this->assign('list_sat',$list_sat);
		$this->assign('aemobile',$aemobile['mobile']);
		$this->assign('ammobile',$ammobile['mobile']);
		$this->assign('systemScrm5PermissionsList',session('systemPermissionsList'));
		$this->islanguage = M('company')->where(array('id'=>$this->companyid))->getField('isopenlanguage');
	}
	/**
	 * 
	 * 网页地图
	 * 
	 * @author Mark<1311013341@qq.com>
	 * @since  2016-10-20
	 */
	public function mapArray(){
		$result['1'] = '自定义微页面';
						/*$result['2'] = 'LOOKBOOK',$result['3'] = '相册',$result['4'] = '品宣H5', */
		$result['5'] = 'eshop-商品品类页';
		$result['6'] = 'eshop-商品详情页';
		$result['7'] = 'eshop-购物车';
		$result['8'] = 'eshop-我的订单';
		$result['80']= 'eshop-eshop客服';
		if(array_intersect(array(81,82,83), session('companyS5Permissions')) && array_intersect(array(81,82,83), session('S5permissions'))){
			$result['9'] = '手机预订-预订门店列表页';
			$result['10'] = '手机预订-预订历史';
		}
		$result['11'] = '线上调研-调研活动页面';
		if(array_intersect(array(71,72,73), session('companyS5Permissions')) && array_intersect(array(71,72,73), session('S5permissions'))){
			$result['12'] = 'DMS代理人系统-登录';
		}
		if(array_intersect(array(78), session('companyS5Permissions')) && array_intersect(array(78), session('S5permissions'))){
			$result['13'] = '闪惠-门店列表页';
			$result['14'] = '闪惠-闪惠详情页';
			$result['15'] = '闪惠-买单历史';
		}
		$result['16'] = '门店-全部门店';
		$result['17'] = '门店-门店详情页';
		$result['18'] = '积分商城-首页';
		$result['19'] = '积分商城-分类页';
		$result['20'] = '积分商城-礼品详情页';
		$result['21'] = '积分商城-兑换记录';
		$result['22'] = '会员中心';
		$result['23'] = '卡券包';
		$result['24'] = '交易订单';
		$result['25'] = '储值';
		$result['27'] = '风外卖-门店详情';
		$result['29'] = '风外卖-订单历史';
		$result['30'] = 'CRM活动-领券活动';
		$result['31'] = '咨询表单';
		$result['32'] = '付费内容商店';
		$result['33'] = '付费内容商店-商品详情页';
		$result['34'] = '预定(SPA行业版)-项目列表页';
		$result['35'] = '预定(SPA行业版)-项目详情页';
		$result['36'] = '预定(SPA行业版)-订单历史';
		$result['37'] = '订阅闹钟';
		
		return $result;
	}
	/**
	 * 生成面包屑Url
	 *
	 * @param array  $now_path 生成面包屑URL
	 * @return null
	 */
	public function makeTopUrl_User($now_path = array()){
		$return_url = '<div class="breadcrumb clearfix"> ';
		if (isset($now_path)){
			$return_url .= '<h5 class="breadcrumb-nav fl"> ';
			foreach ($now_path as $npKey=>$npVal){
				if ($npKey==count($now_path)-1){
					$return_url .= '<a class="nav-L3 curr-nav">'.$npVal['name'].'</a> ';
				}else{
					if($npVal['url']){
						$return_url .= '<a target="'.$npVal['target'].'" class="nav-L'.($npKey+1).' tips" href="'.$npVal['url'].'">'.$npVal['name'].'</a> <i class="icon-brea-icon"></i> ';
					}else{
						$return_url .= '<a class="nav-L3 curr-nav">'.$npVal['name'].'</a> <i class="icon-brea-icon"></i> ';
					}
				}
			}
			$return_url .= ' </h5>';
		}
		$return_url .= '<div class="back fr"> ';
		$return_url .= '<a href="javascript:history.go(-1);" class="text-purple"><i class="icon-breadcrumb-back"></i> 返回</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href="javascript:history.go(0);" class="text-purple"><i class="icon-breadcrumb-F5"></i> 刷新</a> </div> </div>';
		return $return_url;
	}
    /**
     * 检测公司信息设置
     * Enter description here ...
     */
    public function checkCompanySet($isChannle){
    	if (!session('cname') || !session('cid')){
    		if($isChannle ==1){
	    		$this->error('请先设置公司信息',U('User/Company/index'));
    		}else{
    			$this->error('请先设置公司信息',U('User/Company/set'));
    		}
    	}
    }
	/**
     * 检测门店信息设置
     * Enter description here ...
     */
    public function checkCompanyShopsNum($isChannle){
    	$shopsNum = M('company_shops')->where(array('companyid'=>$this->companyid))->count();
    	if ($shopsNum < 1){
    		if($isChannle ==1){
    			$this->error('请先添加门店信息',U('User/Company/index'));
    		}else{
	    		$this->error('请先添加门店信息',U('User/Company/shops'));
    		}
    	}
    }
    /**
     * 检查门店最大值
     */
    public function checkComapnyShopsMaxNum(){
    	$shopsNum = M('company_shops')->where(array('companyid'=>$this->companyid))->count();
    	$companyInfo = M('company')->where(array('id'=>$this->companyid))->field('shopsnum')->find();
    	if($shopsNum >= $companyInfo['shopsnum'] && '-1' != $companyInfo['shopsnum']){
    		$this->error('对不起，您无法接入更多的门店。如有疑问请联系您的业务代表！');
    	}
    }
	/**
     * 检测公众号信息设置
     * Enter description here ...
     */
    public function checkCompanyWechatNum($isChannle){
    	$WechatNum = M('wechats')->where(array('companyid'=>$this->companyid))->count();
    	if ($WechatNum < 1){
    		if($isChannle ==1){
	    		$this->error('请先添加微信公众号信息',U('User/Platform/index'));
    		}else{
    			$this->error('请先添加微信公众号信息',U('User/Wechat/lists'));
    		}
    	}
    }
    /**
     * 检查公众号最大值
     */
    public function checkCompanyWechatMaxNum(){
    	$WechatNum = M('wechats')->where(array('companyid'=>$this->companyid))->count();
    	$companyInfo = M('company')->where(array('id'=>$this->companyid))->field('wechatnum')->find();
    	if($WechatNum >= $companyInfo['wechatnum'] && '-1' != $companyInfo['wechatnum']){
    		$this->error('对不起，您无法接入更多的微信公众号。如有疑问请联系您的业务代表！');
    	}
    }
    /**
     * 检查员工账号
     */
    public function checkCompanyWorkerMaxNum(){
    	$usersNum = M('users')->where(array('companyid'=>$this->companyid,'isboss'=>0))->count();
    	$companyInfo = M('company')->where(array('id'=>$this->companyid))->field('workernum')->find();
    	if($usersNum >= $companyInfo['workernum'] && '-1' != $companyInfo['workernum']){
    		$this->error('对不起，您无法接入更多的子账号。如有疑问请联系您的业务代表！');
    	}
    }
    /**
     * 
     * User2.0版本 后台权限控制通用方法
     * 
     * @param unknown $power 权限ID
     * @param string $tip  提示
     * @return boolean
     * @author Lando<806728685@qq.com>
     * @since  2017-2-8
     */
    public function checkCompanyPermissions($power,$tip = FALSE){
    	if (session('companyPermissions')){
    		$power = explode(',', $power);
    		if (array_intersect($power, session('companyPermissions')) && array_intersect($power, session('permissions'))){
    				return TRUE;
    		}else{
    			if($tip){
    				$this->error('对不起，您当前没有相应权限。');
    			}else{
	    			return FALSE;
    			}
    		}
    	}else{
    		if($tip){
    			$this->error('对不起，您当前没有相应权限。');
    		}else{
	    		return FALSE;
    		}
    	}
    }
    /**
     * 检测公司权限设置
     * Enter description here ...
     */
    /**
     * 
     * SCRM5 后台通用权限控制方法
     * 
     * @param int $power  权限ID
     * @param boolean $redirect  是否跳转
     * @param string $parentid
     * @param string $common
     * @return boolean
     * @author Lando<806728685@qq.com>
     * @since  2017-2-8
     */
    public function checkCompanyScrm5Permissions($power,$redirect = FALSE,$parentid = null,$common=null){
    	if (session('companyS5Permissions')){
    		if (in_array($power, session('companyS5Permissions')) && in_array($power, session('S5permissions'))){
    			$this->assign('Permissionsid',$power);
    			return TRUE;
    		}else{
    			if($parentid){
    				$permissions = M('system_scrm5_permissions_list_new')->where(array('parentid'=>$parentid,'isshow'=>'1'))->field('id,parentid,wabpagelink')->order('sort asc')->select();
    				if($permissions){
    					foreach($permissions as $pkey=>$pval){
    						if(in_array($pval['id'], session('companyS5Permissions')) && in_array($pval['id'], session('S5permissions'))){
    							if($pval['wabpagelink']){
    								if($common){
    									$this->redirect(C('site_url').htmlspecialchars_decode($pval['wabpagelink']).$common);
    								}else{
    									$this->redirect(C('site_url').htmlspecialchars_decode($pval['wabpagelink']));
    								}
    							}else {
    							    $parentInfo = M('system_scrm5_permissions_list_new')->where(array('id'=>$pval['parentid'],'isshow'=>'1'))->field('id,parentid,wabpagelink')->find();
    							    if($parentInfo['wabpagelink']){
    							        if($common){
    							            $this->redirect(C('site_url').htmlspecialchars_decode($parentInfo['wabpagelink']).$common);
    							        }else{
    							            $this->redirect(C('site_url').htmlspecialchars_decode($parentInfo['wabpagelink']));
    							        }
    							    }else{
    							        $this->redirect(U('System/Error'));
    							    }
    							}
    						}else{
    						    $permissionsChild = M('system_scrm5_permissions_list_new')->where(array('parentid'=>$pval['id'],'isshow'=>'1'))->field('id,parentid,wabpagelink')->order('sort asc')->select();
    						    if ($permissionsChild) { // 这里不需要加 else,否则无法递归选择找链接
    						        foreach($permissionsChild as $pckey=>$pcval){
    						            if(in_array($pcval['id'], session('companyS5Permissions')) && in_array($pcval['id'], session('S5permissions'))){// 这里不需要加 else,否则无法递归选择找链接
    						                if($pcval['wabpagelink']){
    						                    if($common){
    						                        $this->redirect(C('site_url').htmlspecialchars_decode($pcval['wabpagelink']).$common);
    						                    }else{
    						                        $this->redirect(C('site_url').htmlspecialchars_decode($pcval['wabpagelink']));
    						                    }
    						                }else{
    						                    $parentInfo = M('system_scrm5_permissions_list_new')->where(array('id'=>$pcval['parentid'],'isshow'=>'1'))->field('id,parentid,wabpagelink')->find();
    						                    if($parentInfo['wabpagelink']){
    						                        if($common){
    						                            $this->redirect(C('site_url').htmlspecialchars_decode($parentInfo['wabpagelink']).$common);
    						                        }else{
    						                            $this->redirect(C('site_url').htmlspecialchars_decode($parentInfo['wabpagelink']));
    						                        }
    						                    }else{
    						                        $this->redirect(U('System/Error'));
    						                    }
    						                }
    						            }
    						        }
    						    }
    						}
    					}
    				}else{
    					$this->redirect(U('System/Error'));
    				}
    			}else{
	    			if($redirect){
	    				$this->redirect(U('System/Error'));
	    			}else{
	    				return FALSE;
	    			}
    			}
    		}
    	}else{
    		if($redirect){
    			$this->redirect(U('System/Error'));
    		}else{
    			return FALSE;
    		}
    	}
    }
    /**
     *  权限检测 提示
     * Enter description here ...
     * @param unknown_type $power
     */
    public function checkCompanyPowerTips($power){
    	if(!$this->checkCompanyPower($power)){
    		$this->error('对不起，您当前没有相应权限，请联系人来风客服');
    	}
    }
    /**
     * 判断 是否操作的对应可操作的内容　　进行　数据比对　来提示
     * Enter description here ...
     * @param unknown_type $id
     */
    public function checkCompanyTrue($data = '',$contrastData = ''){
    	if ($data != $contrastData){
			$this->error('捣蛋可耻,ip 已记录！');
		}
    }
    /**
     * 获得 公司 或门店 公众号
     * Enter description here ...
     * @param unknown_type $companyid
     * @param unknown_type $shopsid
     */
    public function getCompanyWechatList($companyid = '0',$shopsid = '0',$token = ''){
    	$wechatsWhere = array();
    	if ($companyid > 0){
	    	$wechatsWhere['companyid'] = $companyid;
    	}
    	if ($shopsid > 0){
    		$wechatsWhere['shopsids'] = $shopsid;
    	}
    	if ($token){
    		$wechatsWhere['token'] = $token;
    	}
    	if ($wechatsWhere){
	    	return M('wechats')->getCompanyWechatss($wechatsWhere);
    	}
    }
    /**
     * 获得 电子券唯一编码
     */
    public function getMemberVouchersNumber(){
    	$memberVouchers = M('member_marketing_activities_voucher')->where(array('companyid'=>session('cid')))->field('voucherid')->select();
		return $this->checkMemberVoucherId($memberVouchers);
	}
	/**
	 * 获得电子券唯一编码辅助方法
	 * @param unknown $memberVouchers
	 * @return string
	 */
	public function checkMemberVoucherId($memberVouchers){
		$number = '#'.get_seven_number();
		foreach ($memberVouchers as $key => $val) {
			$memberVouchersNew[]=$val['voucherid'];
		}
		if (in_array($number, $memberVouchersNew)) {
			 return $this->checkMemberVoucherId($memberVouchers);
		}else{
			return $number;
		}
	}
	/**
	 * 选择关联网页链接（除列表页、详情页）
	 */
	public function publicSelectUrl(){
		//会员中心
		$list['list1'] = array(
			array('title'=>'我的会员中心','url'=>C('site_url').U('Wap/Member/center',array('companyid'=>$this->companyid))),
			array('title'=>'会员权益说明','url'=>C('site_url').U('Wap/Member/rankExplain',array('companyid'=>$this->companyid))),
			array('title'=>'完善个人资料','url'=>C('site_url').U('Wap/Member/editMyInformation',array('companyid'=>$this->companyid))),
			array('title'=>'会员卡充值','url'=>C('site_url').U('Wap/MemberVouchers/vouchersUse',array('companyid'=>$this->companyid,'vouchertype'=>'3'))),
			array('title'=>'积分获取规则','url'=>C('site_url').U('Wap/Member/gainIntegral',array('companyid'=>$this->companyid))),
			array('title'=>'我的电子券','url'=>C('site_url').U('Wap/MemberVouchers/myVouchers',array('companyid'=>$this->companyid))),
			array('title'=>'我的红包','url'=>C('site_url').U('Wap/MemberRedPacket/index',array('companyid'=>$this->companyid))),
			array('title'=>'我的活动日历','url'=>C('site_url').U('Wap/MemberLineApplyActivities/index',array('companyid'=>$this->companyid))),
			array('title'=>'我的订单','url'=>C('site_url').U('Wap/MemberMallOrder/index',array('companyid'=>$this->companyid))),
			array('title'=>'我的交易记录','url'=>C('site_url').U('Wap/MemberTransaction/index',array('companyid'=>$this->companyid))),
			array('title'=>'线下确认记录消费','url'=>C('site_url').U('Wap/MemberSpending/spending',array('companyid'=>$this->companyid))),
			array('title'=>'每日签到','url'=>C('site_url').U('Wap/MemberEverydayCheckin/index',array('companyid'=>$this->companyid))),
			array('title'=>'LBS签到','url'=>C('site_url').U('Wap/MemberCheckin/myCheckin',array('companyid'=>$this->companyid))),
			//array('title'=>'会员卡绑定','url'=>C('site_url').U('Wap/Member/gainIntegral',array('companyid'=>$this->companyid))),
			array('title'=>'我的预约','url'=>C('site_url').U('Wap/CommonBook/myCommonBook',array('companyid'=>$this->companyid))),
			array('title'=>'官方客服','url'=>C('site_url').U('Wap/MemberNotices/customer',array('companyid'=>$this->companyid))),
			array('title'=>'我的收藏','url'=>C('site_url').U('Wap/MallGoodsFavourite/index',array('companyid'=>$this->companyid))),
			array('title'=>'实体卡绑定','url'=>C('site_url').U('Wap/Member/binding',array('companyid'=>$this->companyid))),
		);
		//官网首页
		if($this->companyid == 1){
			//人来风新官网
			$list['list2'] = array(
					array('title'=>'人来风首页','url'=>C('site_url').U('Wap/Index/wapIndex',array('companyid'=>$this->companyid))),
			);
		}else{
			$list['list2'] = array(
					array('title'=>'官网首页','url'=>C('site_url').U('Wap/Index/index',array('companyid'=>$this->companyid))),
			);
		}   
		//官网列表页、详情页
		//门店实景相册
		$list['list5'] = D('Company_shop_photo')->getCompanyShoPhotoList(array('photo.companyid'=>$this->companyid,'photo.shopid'=>array('gt',0)));
		//其他相册
		$list['list6'] = D('Photo')->getPhotoList(array('companyid'=>$this->companyid));
		//商城首页
		if($this->companyid == 1){
			$list['list7'] = array(
				array('title'=>'人来风商城首页','url'=>C('site_url').U('Wap/MallGoods/index',array('companyid'=>$this->companyid))),
				array('title'=>'商品筛选','url'=>C('site_url').U('Wap/MallTagsSearch/index',array('companyid'=>$this->companyid))),
			);
		}else{
			$list['list7'] = array(
				array('title'=>'商城首页','url'=>C('site_url').U('Wap/MallGoods/index',array('companyid'=>$this->companyid))),
				array('title'=>'商品筛选','url'=>C('site_url').U('Wap/MallTagsSearch/index',array('companyid'=>$this->companyid))),
			);
		}
		//商城列表页、详情页
		//线下活动报名
		$list['list10'] = M('member_line_apply_activities')->where(array('companyid'=>$this->companyid))->field('id,companyid,title')->order('id DESC')->select();
		//在线调研
		//百宝箱
		$list['list12'] = M('member_treasure_box_activities')->where(array('companyid'=>$this->companyid))->field('id,companyid,title')->order('id DESC')->select();
		//电子杂志
		/* //门店预约
		//$list['list14'] = D('Dining_book_set')->getComapnyDiningBookList(array('dining.companyid'=>$this->companyid,'dining.isshow'=>1)); */
		//万能预约
		$list['list15'] = D('Common_book_set')->getCommonBookSetList(array('companyid'=>$this->companyid,'isshow'=>1));
		//线下门店列表页
		$list['list16'] = array(
			array('title'=>'线下门店列表页','url'=>C('site_url').U('Wap/MemberDining/shops',array('companyid'=>$this->companyid))),
		);
		//门店详情
		$list['list17'] = D('Company_shops')->getWhereCompanyShopsInfo(array('companyid'=>$this->companyid));
		//大众点评
		$list['list18'] = M()->table('tp_company_shops AS shop')->join('tp_company_shop_comments_dianping AS dianping ON shop.id = dianping.shopid')->where(array('dianping.companyid'=>$this->companyid,'dianping.isshow'=>1))->field('shop.id,shop.companyid,shop.name')->order('shop.sort ASC,shop.id DESC')->select();
		//TripAdvisor点评
		$list['list19'] = M()->table('tp_company_shops AS shop')->join('tp_company_shop_comments_tripadvisor AS tripadvisor ON shop.id = tripadvisor.shopid')->field('shop.id,shop.companyid,shop.name')->where(array('tripadvisor.companyid'=>$this->companyid,'tripadvisor.isshow'=>1))->order('shop.sort ASC,shop.id DESC')->select();
		//关注桥页
		$this->assign('webpageUrl',$list);
	}
	/**
	 * 收单口
	 */
	public function spendingTypeAsa(){
		return array(
			'106'=>'闪惠支付','107'=>'风助手手机收银','109'=>'首次消费',
			'110'=>'eshop支付','111'=>'拉卡拉手机收银','112'=>'充值',
			'113'=>'手机预订','114'=>'微信外卖','115'=>'手机点单',
			'203'=>'会员WAP积分换储值','301'=>'后台储值消费'
		);
	}
	/**
	 * 支付方式
	 */
	public function spendingPayTypeAsa(){
		return array(
			'1'=>'微信支付','2'=>'支付宝支付','3'=>'现金支付',
			'4'=>'储值支付','5'=>'银行卡支付'
		);
	}
	/**
	 * 充值方式
	 */
	public function spendingRechargeTypeAsa(){
		return array(
			'1'=>'后台充值','2'=>'在线充值',
			'3'=>'会员WAP积分换储值','4'=>'红包'
		);
	}
	/**
	 * 积分类型
	 */
	public function integralTypeAsa(){
		return 	array(
		  '101' => "完善会员资料",'102' => "注册",'103' => "点评奖励",
		  '104' => "手动加积分",'107' => "风助手手机收银",'106' => "闪惠支付",
		  '108' => "微信关注",'109' => "首次消费",'110' => "eshop支付",
		  '111' => "拉卡拉手机收银",'112' => "充值",'113' => "手机预订",'117' => "手机预订奖励",'118' => "预订(SPA)",'122' => "预订(SPA)奖励",
		  '114' => "微信外卖",'115' => "手机点单",'201' => "手动扣除积分",
		  '202' => "到期自动清零",'203' => "会员WAP积分换储值",'204' => "积分商城",
		  '301' => "后台储值消费"
		);
	}
}