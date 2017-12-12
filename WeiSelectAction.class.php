<?php
/**
 * 新的网页链接地图
 * @author    Asa<asa@renlaifeng.cn>
 * @since     2016-8-8
 * @version   1.0
 */
class WeiSelectAction extends UserAction{
	public function _initialize(){
		parent::_initialize();
		//检查公司配置
		$this->companyid = session('cid');
		$this->shopsid = session('shopsid');
		$this->limit = 7;
		$this->weiSelect=$this->mapArray();
		$this->yuming = C('site_url')=='http://www.morningforce.com' ? 'http://www.mobiwind.cn' : C('site_url');
	}
	/**
	 * 加载所有列表
	 * @since  2016-8-8
	 */
	public function lists(){
		$weiSelect = $this->weiSelect;
		echo json_encode($weiSelect);
	}
	/**
	 * 获取网页链接
	 */
	public function getUrl(){
		$weiSelect = $this->weiSelect;
		$val = $this->_post('val');
		$page = $page = $this->_post('page')>0 ? $this->_post('page') : 1;
		switch ($val)
		{
			case 1:
				$str = $this->publicUrl('wei_list',$page,'Wap/Wei/index',array("type"=>1));
				break;
			case 2:
				$str['html'] = "LOOKBOOK";
				break;
			case 3:
				$str['html'] = "相册";
				break;
			case 4:
				$str = $this->publicUrl('product_promotion',$page,'Wap/ProductPromotion/indexZn','','proid');
				break;
			case 5:
				$str = $this->publicUrl('eshop_class',$page,'Wap/MallTagsSearch/lists','','id','name');
				break;
			case 6:
				$str = $this->publicUrl('mall_goods',$page,'Wap/MallGoods/goodInfo','','id');
				break;
			case 7:
				$str['html'] = $this->unique($weiSelect[$val],$this->yuming.U('Wap/MallShoppingCart/index',array('companyid'=>$this->companyid)));
				break;
			case 8:
				$str['html'] = $this->unique($weiSelect[$val],$this->yuming.U('Wap/MemberMallOrder/index',array('companyid'=>$this->companyid)));
				break;
			case 80:
				$str['html'] = $this->unique($weiSelect[$val],$this->yuming.U('Wap/MallNotices/customer',array('companyid'=>$this->companyid)));
				break;
			case 9:
				$str['html'] = $this->unique($weiSelect[$val],$this->yuming.U('Wap/MemberDining/shops',array('companyid'=>$this->companyid)));
				break;
			case 10:
				$str['html'] = $this->unique($weiSelect[$val],$this->yuming.U('Wap/MobileBook/history',array('companyid'=>$this->companyid)));
				break;
			case 11:
				$str = $this->publicUrl('survey_activity_theme',$page,'Wap/SurveyActivity/index','','tid','name');
				break;
			case 12:
				$str['html'] = $this->unique($weiSelect[$val],C('site_url').U('Dms/Dms/Index',array('companyid'=>$this->companyid)));
				break;
			case 13:
				$str['html'] = $this->unique($weiSelect[$val],C('site_url').U('Wap/MemberDining/shops',array('companyid'=>$this->companyid)));
				break;
			case 14:
				$str = $this->publicUrl('company_shops',$page,'Wap/ShanHui/check','','shopid','shopname');
				break;
			case 15:
				$str['html'] = $this->unique($weiSelect[$val],C('site_url').U('Wap/ShanHui/historyList',array('companyid'=>$this->companyid)));
				break;
			case 16:
				$str['html'] = $this->unique($weiSelect[$val],C('site_url').U('Wap/MemberDining/shops',array('companyid'=>$this->companyid)));
				break;
			case 17:
				$str = $this->publicUrl('company_shops',$page,'Wap/MemberDining/shopsInfo','','id','shopname');
				break;
			case 18:
				$str['html'] = $this->unique($weiSelect[$val],C('site_url').U('Wap/MallIntegral/index',array('companyid'=>$this->companyid)));
				break;
			case 19:
				$str = $this->publicUrl('mall_member_integral_class',$page,'Wap/MallIntegral/lists');
				break;
			case 20:
				$str = $this->publicUrl('mall_member_integral_goods',$page,'Wap/MallIntegral/info');;
				break;
			case 21:
				$str['html'] = $this->unique($weiSelect[$val],C('site_url').U('Wap/MallIntegralOrder/index',array('companyid'=>$this->companyid)));
				break;
			case 22:
				$list = array(
						array('title'=>'会员中心','url'=>C('site_url').'/index.php?&g=Wap&m=Member&a=center&companyid='.$this->companyid),
						array('title'=>'我的资料','url'=>C('site_url').'/index.php?g=Wap&m=Member&a=editMyInformation&companyid='.$this->companyid),
						array('title'=>'我的累计积分','url'=>C('site_url').'/index.php?g=Wap&m=Member&a=totalintegral&companyid='.$this->companyid),
						array('title'=>'使用积分','url'=>C('site_url').'/index.php?g=Wap&m=Member&a=integralUse&companyid='.$this->companyid),
						array('title'=>'会员制说明','url'=>C('site_url').'/index.php?g=Wap&m=Member&a=rankExplain&companyid='.$this->companyid)
							
				);
				$str['html'] = $this->mallUrl($list);
				$str['pageCount'] = 5;
				break;
			case 23:
				$list = array(
					array('title'=>'可用卡券','url'=>C('site_url').'/index.php?g=Wap&m=MemberVouchers&a=myVouchers&companyid='.$this->companyid),
					array('title'=>'历史卡券','url'=>C('site_url').'/index.php?g=Wap&m=MemberVouchers&a=myHistoryVouchers&companyid='.$this->companyid)
				);
				$str['html'] = $this->mallUrl($list);
				$str['pageCount'] = 2;
				break;
			case 24:
				$list = array(
					array('title'=>'eshop实物商品订单','url'=>C('site_url').'/index.php?g=Wap&m=MemberMallOrder&a=index&ordertype=1&isshow=2&companyid='.$this->companyid),
					array('title'=>'券商品订单','url'=>C('site_url').'/index.php?g=Wap&m=MemberMallOrder&a=index&ordertype=2&isshow=2&companyid='.$this->companyid),
					array('title'=>'计次卡商品订单','url'=>C('site_url').'/index.php?g=Wap&m=MemberMallOrder&a=index&ordertype=3&isshow=2&companyid='.$this->companyid),
					array('title'=>'团购商品订单','url'=>C('site_url').'/index.php?g=Wap&m=MemberMallOrder&a=index&ordertype=4&isshow=2&companyid='.$this->companyid),
					array('title'=>'门票商品订单','url'=>C('site_url').'/index.php?g=Wap&m=MemberMallOrder&a=index&ordertype=5&isshow=2&companyid='.$this->companyid),
					array('title'=>'权益卡商品订单','url'=>C('site_url').'/index.php?g=Wap&m=MemberMallOrder&a=index&ordertype=6&isshow=2&companyid='.$this->companyid),
					array('title'=>'卡券礼包商品订单','url'=>C('site_url').'/index.php?g=Wap&m=MemberMallOrder&a=index&ordertype=7&isshow=2&companyid='.$this->companyid)
				);
				$str['html'] = $this->mallUrl($list);
				$str['pageCount'] = 5;
				break;
			case 25:
				$list = array(
					array('title'=>'储值卡','url'=>C('site_url').'/index.php?g=Wap&m=Storedvalue&a=index&companyid='.$this->companyid),
					array('title'=>'储值卡-充值页面','url'=>C('site_url').'/index.php?g=Wap&m=Storedvalue&a=recharge&companyid='.$this->companyid),
					array('title'=>'储值历史','url'=>C('site_url').'/index.php?g=Wap&m=Storedvalue&a=historicalValue&companyid='.$this->companyid),
				);
				$str['html'] = $this->mallUrl($list);
				$str['pageCount'] = 3;
				break;
			case 27://风外卖-门店详情
				$str = $this->publicUrl('company_shops',$page,'Wap/TakeOut/index',array("isopentakeout"=>1),'id','shopname');
				break;
			case 29://订单历史
				$str['html'] = $this->unique($weiSelect[$val],C('site_url').U('Wap/TakeOut/orderList',array('companyid'=>$this->companyid)));
				break;
			case 30://CRM活动
				$str = $this->publicUrl('member_marketing_activities_scrm',$page,'Wap/MemberGiveawayVoucher/index',array('type'=>6,'endtime'=>array("gt",time())));
				break;
			case 31://咨询表单
				$str = $this->publicUrl('consult_table',$page,'Wap/ConsultTable/index');
				break;
			case 32://付费内容商店 
				$list = array(
					array('title'=>'内容商店-首页','url'=>C('site_url').'/index.php?g=Wap&m=PayShop&a=index&companyid='.$this->companyid),
					array('title'=>'内容商店-年费权益购买页','url'=>C('site_url').'/index.php?g=Wap&m=PayShop&a=shopInfo&type=3&companyid='.$this->companyid),
					array('title'=>'内容商店-购买历史','url'=>C('site_url').'/index.php?g=Wap&m=PayShop&a=history&companyid='.$this->companyid),
				);
				$str['html'] = $this->mallUrl($list);
				$str['pageCount'] = 5;
				break;
			case 33://付费内容商店-商品详情页
				$str = $this->publicUrl('pay_shop_goods',$page,'Wap/PayShop/shopInfo',array('isshow'=>1),'id','shopname');;
				break;
			case 34://预订（SPA行业版）-项目列表页
				$str['html'] = $this->unique($weiSelect[$val],C('site_url').U('Wap/SpaMobileBook/project',array('companyid'=>$this->companyid,'isopen'=>1)));
				break;
			case 35://预订（SPA行业版）-项目详情页
				$str = $this->publicUrl('spa_mobile_book_project_set',$page,'Wap/SpaMobileBook/projectInfo',array('isopen'=>1),'pid','bookname');;
				break;
			case 36://预订（SPA行业版）-订单历史
				$str['html'] = $this->unique($weiSelect[$val],C('site_url').U('Wap/SpaMobileBook/history',array('companyid'=>$this->companyid)));
				break;
			case 37://订阅闹钟
				$str['html'] = $this->unique($weiSelect[$val],C('site_url').U('Wap/SubscribeClock/index',array('companyid'=>$this->companyid)));
				break;
			default:
				echo "No number between 1 and 50";
		}
		if($str){
			$ajax['code']=200;
			$ajax['msg']='成功';
			$ajax['html']=$str['html'];
			$ajax['page']=$str['page'];
			$ajax['pageCount']=$str['pageCount']?$str['pageCount']:'1';
		}else{
			$ajax['code']=300;
			$ajax['msg']='暂无数据';
		}
		echo json_encode($ajax);
	}
	/**
	 * 唯一链接组装
	 */
	public function unique($title,$url){
		$str ='';
		$str .='<tr>';
		$str .='<td>'.$title.'</td>';
		$str .='<td>';
		$str .='<a href="javascript:void(0);" data-url="'.$url.'" data-title="'.$title.'" class="useUrl tips">使用</a>';
		$str .='<a href="javascript:void(0);" url="'.$url.'" class="open-window-attr-url tips">在线预览</a>';
		$str .='</td>';
		$str .='</tr>';
		return $str;
	}
	public function mallUrl($infoUrl){
		$str = '';
		foreach($infoUrl as $val){
			$str .='<tr>';
			$str .='<td>'.$val['title'].'</td>';
			$str .='<td>';
			$str .='<a href="javascript:void(0);" data-url="'.$val['url'].'" data-title="'.$val['title'].'" class="useUrl tips">使用</a>';
			$str .='<a href="javascript:void(0);" url="'.$val['url'].'" class="open-window-attr-url tips">在线预览</a>';
			$str .='</td>';
			$str .='</tr>';
		}
		return $str;
	}
	/**
	 * 共用的链接方法。。
	 *
	 */
	public function publicUrl($table,$page,$url,$where,$id='id',$title='title'){
		$where['companyid'] = $this->companyid;
		$count = M($table)->where($where)->count();
		$list = M($table)->where($where)->order('createtime desc')->page($page,$this->limit)->select();
	
		$str['page'] = $this->pageInfo($count,$page);
		$str['pageCount'] = $count;
	
		$str['html'] = $this->htmlInfo($list,$url,$id,$title);
	
		return $str;
	}
	/**
	 * 组装HTML
	 */
	public function htmlInfo($list,$url,$id='id',$title="title"){
		if($list){
			$str = '';
			foreach ($list as $key => $val){
				$url = C('site_url').U($url,array('companyid'=>$this->companyid,"$id"=>$val['id']));
				$str .='<tr>';
				$str .='<td>'.$val["$title"].'</td>';
				$str .='<td>';
				$str .='<a href="javascript:void(0);" data-url="'.$url.'" data-title="'."$val[$title]".'" class="useUrl tips">使用</a>';
				$str .='<a href="javascript:void(0);" url="'.$url.'" class="open-window-attr-url tips">在线预览</a>';
				$str .='</td>';
				$str .='</tr>';
			}
		}else{
			$str='<tr class="text-center"><td colspan="2">暂无</td></tr>';
		}
		return $str;
	}
	public function getPage(){
		$str['page'] = $this->pageInfo($this->_post("count"),1);
		echo json_encode($str);
	}
	/**
	 * 组装分页
	 */
	public function pageInfo($count,$page){
		$pageInfo['count'] = $count;
		$this->nowPage = $pageInfo['page'] = $page;
		$this->totalPages =  $pageInfo['list'] = ceil($count/$this->limit);
		$downRow = $pageInfo['nextPage'] = $page+1;
		$upRow = $pageInfo['prevPage'] = $page-1;
		//上下翻页字符串
		if($this->totalPages>5){
			$showNum = 5;
		}else{
			$showNum = $this->totalPages;
		}
		$pageHtml = '';
		//$pageHtml = '<span class="item-count">共'.$this->totalRows.'条记录</span><ul>';
		if($this->totalPages > 1){
			if($this->nowPage!=1){
				$pageHtml .='<li class="page-prev-asa" data-page="'.$pageInfo['prevPage'].'"><a href="javascript:void(0);"><i class="page-prev-icon"></i></a></li>';
			}
			if($this->totalPages < 6 ){
				for ($page=1;$page<=$showNum;$page++){
					if($this->nowPage == $page){
						$pageHtml .='<li class="item-active"><a href="javascript:void(0);">'.$page.'</a></li>';
					}else{
						$pageHtml .='<li data-page="'.$page.'" class="pageNow"><a href="javascript:void(0);" >'.$page.'</a></li>';
					}
				}
			}else{
				if ($this->totalPages <= $showNum) {
					for ($page=1;$page<=$showNum;$page++){
						if($this->nowPage == $page){
							$pageHtml .='<li class="item-active"><a href="javascript:void(0);">'.$page.'</a></li>';
						}else{
							$pageHtml .='<li data-page="'.$page.'" class="pageNow"><a href="javascript:void(0);" >'.$page.'</a></li>';
						}
					}
				}else{
					if($this->nowPage==1 || $this->nowPage<$showNum){
						for ($page=1;$page<=$showNum;$page++){
							if($this->nowPage == $page){
								$pageHtml .='<li class="item-active"><a href="javascript:void(0);">'.$page.'</a></li>';
							}else{
								$pageHtml .='<li data-page="'.$page.'" class="pageNow"><a href="javascript:void(0);" >'.$page.'</a></li>';
							}
						}
						$pageHtml .='<li><span><b>···</b></span></li><li data-page="'.$this->totalPages.'" class="pageNow"><a href="javascript:void(0);" >'.$this->totalPages.'</a><li>';
					}elseif ($this->nowPage>=$showNum && $this->nowPage <= ($this->totalPages-$showNum)){
						$pageHtml .='<li data-page="1" class="pageNow"><a href="javascript:void(0);" >1</a></li><li><span><b>···</b></span></li>';
						for ($page=($this->nowPage-2);$page<=($this->nowPage+2);$page++){
							if($this->nowPage == $page){
								$pageHtml .='<li class="item-active"><a href="javascript:void(0);">'.$page.'</a></li>';
							}else{
								$pageHtml .='<li data-page="'.$page.'" class="pageNow"><a href="javascript:void(0);" >'.$page.'</a></li>';
							}
						}
						$pageHtml .='<li><span><b>···</b></span></li><li data-page="'.$this->totalPages.'" class="pageNow"><a href="javascript:void(0);" >'.$this->totalPages.'</a></li>';
					}elseif ($this->nowPage==$this->totalPages || $this->nowPage>($this->totalPages-$showNum)){
						$pageHtml .='<li data-page="1" class="pageNow"><a href="javascript:void(0);" >1</a></li><li><span><b>···</b></span></li>';
						for ($page=($this->totalPages-$showNum+1);$page<=$this->totalPages;$page++){
							if($this->nowPage == $page){
								$pageHtml .='<li class="item-active"><a href="javascript:void(0);">'.$page.'</a></li>';
							}else{
								$pageHtml .='<li data-page="'.$page.'" class="pageNow"><a href="javascript:void(0);" >'.$page.'</a></li>';
							}
						}
					}
				}
			}
			if($this->nowPage!=$this->totalPages){
				$pageHtml .='<li class="page-next-asa" data-page="'.$pageInfo['nextPage'].'"><a href="javascript:void(0);"><i class="page-next-icon"></i></a></li>';
			}
		}
		return $pageHtml;
		
	}
}