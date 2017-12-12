<?php
/**
 * 微官网模板设置
 * @author    Asa<asa@renlaifeng.cn>
 * @since     2016-7-26
 * @version   1.0
 */
class WeiAction extends UserAction{
	
	private $companyid;
	
	private $shopsid;
	
	private $uid;
	
	public function _initialize(){
		parent::_initialize();
		$this->companyid = session('cid');
		$this->shopsid = session('shopsid');
		$this->wlist = M('wei_list');
		$this->wass = M('wei_assembly');
		$this->limit = 7;
		$this->uid = session('uid');
		$this->checkCompanyScrm5Permissions(12,true);
	}
	/**
	 * 
	 * 底部菜单
	 * 
	 * @author Mark<1311013341@qq.com>
	 * @since  2016-12-9
	 */
	public function Wapmenu(){
		$this->makeTopUrl = $this->makeTopUrl_User(array($this->MANAGE_WELCOME,array('name'=>'微页面','url'=>U('Wei/index'),'rel'=>'','target'=>''),array('name'=>'WAP页面通底设置','url'=>'','rel'=>'','target'=>'')));
		$info = M('wap_bottom_menu')->where(array('companyid'=>$this->companyid))->find();
		$time = time();
		if(!$info){
			$id = $this->wlist->where(array('companyid'=>$this->companyid,'ishomepage'=>'2','type'=>'1'))->getField('id');
			if($id){
				$indexUrl = C('site_url').'/index.php?g=Wap&m=Wei&a=index&companyid='.$this->companyid.'&id='.$id;
			}else{
				$homeIndex = M("home")->where(array("companyid"=>$this->companyid))->find();
				if($homeIndex){
					$indexUrl = C('site_url').'/index.php?g=Wap&m=Index&a=index&companyid='.$this->companyid;
				}else{
					$indexUrl = '';
				}
			}
			$info = array('id'=>guidNow(),'companyid'=>$this->companyid,'isopen'=>'1',
					'name1'=>'首页','relevancetype1'=>'1','relevanceurl1'=>$indexUrl,'isopen1'=>'1',
					'name2'=>'关注我们','relevancetype2'=>'2','relevanceurl2'=>'','isopen2'=>'1',
					'name3'=>'全部门店','relevancetype3'=>'1','relevanceurl3'=>C('site_url').'/index.php?g=Wap&m=MemberDining&a=shops&companyid='.$this->companyid,'isopen3'=>'1',
					'name4'=>'会员中心','relevancetype4'=>'1','relevanceurl4'=>C('site_url').'/index.php?&g=Wap&m=Member&a=center&companyid='.$this->companyid,'isopen4'=>'1',
					'updatetime'=>$time,'createtime'=>$time
			);
			M('wap_bottom_menu')->add($info);
		}
		$this->assign('info',$info);
		$this->display();
	}
	/**
	 * 
	 * 
	 * 
	 * @author Mark<1311013341@qq.com>
	 * @since  2016-12-9
	 */
	public function ajaxWapmenu(){
		$ajax['code'] = 'error';
		$ajax['msg'] = '网络繁忙，请稍后重试';
		if(IS_POST){
			$_POST['updatetime'] = time();
			$result = M('wap_bottom_menu')->where(array('companyid'=>$this->companyid))->save($_POST);
			if($result){
				$ajax['code'] = 'success';
				$ajax['msg'] = '保存成功';
			}else{
				$ajax['msg'] = '保存失败';
			}
		}
		echo json_encode($ajax);
	}
	/**
	 * ****************************** 微页面列表页 *****************************************************************************
	 * @since  2016-7-26
	 */
	public function index(){
		$this->makeTopUrl = $this->makeTopUrl_User(array($this->MANAGE_WELCOME,array('name'=>'微页面','url'=>'','rel'=>'','target'=>'')));
		$companytime = M('company')->where(array('id'=>$this->companyid))->getField('createtime');
		$this->weiSelect = $this->mapArray();
		if($this->_request('wtype')){
			$this->wtype = $where['wtype'] = $this->_request('wtype');
		}else{
			$this->wtype = $where['wtype'] = 1;
		}
		$sorttype = $this->_request('sorttype');
		$sortclass = $this->_request('sortclass'); 
		if($sorttype == 1){
			if($sortclass=='' || $sortclass==1){
				$order = 'updatetime';
				$this->assign('sortclass1','2');
			}elseif($sortclass == 2){
				$order = 'updatetime DESC';
				$this->assign('sortclass1','1');
			}
		}elseif($sorttype == 2){
			if($sortclass=='' || $sortclass==1){
				if($where['wtype'] == '4' || $where['wtype'] == '5' || $where['wtype'] == '6'||$where['wtype'] == '11'||$where['wtype'] == '17'){
					$order = 'viewnum';
				}elseif($where['wtype'] == '1'){
					$order = 'pv';
				}
				$this->assign('sortclass2','2');
			}elseif($sortclass == 2){
				if($where['wtype'] == '4' || $where['wtype'] == '5' || $where['wtype'] == '6'||$where['wtype'] == '11'||$where['wtype'] == '17'){
					$order = 'viewnum desc';
				}elseif($where['wtype'] == '1'){
					$order = 'pv desc';
				}
				$this->assign('sortclass2','1');
			}
		}else{
			$order = 'createtime desc';
		}
		$this->assign('sorttype',$sorttype);
		$countwhere['companyid'] = $groupwhere['companyid'] = $where['companyid'] = $where4['companyid'] = $wheres['companyid'] = $this->companyid;
		$countwhere['type'] = $groupwhere['type'] = $where['type']=1;
		if($where['wtype'] == '1'){
			/* 分组 */
			if($this->_request('gid')){
				$this->gid = $where['gid'] = $this->_request('gid');
				$this->grouptitle = M('wei_list_group')->where(array('companyid'=>$this->companyid,'id'=>$where['gid']))->field('id,title')->find();
			}
			/* 获取分组 */
			$this->counts = $this->wlist->where($groupwhere)->count();
			$group = M('wei_list_group')->where($groupwhere)->field('id,title')->order('createtime asc')->select();
			foreach ($group as $key=>$val){
				$countwhere['gid'] = $val['id'];
				$group[$key]['count'] = $this->wlist->where($countwhere)->count();
			}
			$this->assign('group',$group);
			$where4['ishomepage'] = 2;
			$this->homeinfo = $this->wlist->where($where4)->find();
			$count=$this->wlist->where($where)->count();
			$page = new NewPage($count,15);
			$list = $this->wlist->where($where)->limit($page->firstRow.','.$page->listRows)->field('id,title,ishomepage,pv,updatetime,isencrypt,encryptinfo')->order($order)->select();
			$this->assign('page',$page->diyshow());
			$this->url = '/index.php?g=Wap&m=Wei&a=index&companyid='.$this->companyid.'&id=';
		}/* elseif($where['wtype'] == '2'){
			$str['html'] = "LOOKBOOK";
		}elseif($where['wtype'] == '3'){
			$str['html'] = "相册";
		}elseif($where['wtype'] == '4'){
			$count = M('product_promotion')->where($where)->count();
			$page = new NewPage($count,15);
			$list = M('product_promotion')->where($where)->limit($page->firstRow.','.$page->listRows)->order($order)->select();
			//dump($list);
			$this->assign('page',$page->diyshow());
			$this->url = '/index.php?g=Wap&m=ProductPromotion&a=indexZn&companyid='.$this->companyid.'&proid=';
		} */elseif($where['wtype'] == '5'){
			$count = M('eshop_class')->where($where)->count();
			$page = new NewPage($count,15);
			$list = M('eshop_class')->where($where)->limit($page->firstRow.','.$page->listRows)->order($order)->select();
			$this->assign('page',$page->diyshow());
			$this->url = '/index.php?g=Wap&m=MallTagsSearch&a=lists&companyid='.$this->companyid.'&id=';
		}elseif($where['wtype'] == '6'){
			$count = M('mall_goods')->where($where)->count();
			$page = new NewPage($count,15);
			$list = M('mall_goods')->where($where)->limit($page->firstRow.','.$page->listRows)->order($order)->select();
			$this->assign('page',$page->diyshow());
			$this->url = '/index.php?g=Wap&m=MallGoods&a=goodInfo&companyid='.$this->companyid.'&id=';
		}elseif($where['wtype'] == '7'){
			$list = array(array('title'=>'购物车','updatetime'=>$companytime));
			$this->url = '/index.php?g=Wap&m=MallShoppingCart&a=index&companyid='.$this->companyid;
			$this->page = '<span class="item-count">共1条记录</span>';
		}elseif($where['wtype'] == '8'){
			$list = array(array('title'=>'我的订单','updatetime'=>$companytime));
			$this->url = '/index.php?g=Wap&m=MemberMallOrder&a=index&companyid='.$this->companyid;
			$this->page = '<span class="item-count">共1条记录</span>';
		}elseif($where['wtype'] == '80'){
			$list = array(array('title'=>'eshop客服','updatetime'=>$companytime));
			$this->url = '/index.php?g=Wap&m=MallNotices&a=customer&companyid='.$this->companyid;
			$this->page = '<span class="item-count">共1条记录</span>';
		}elseif($where['wtype'] == '9'){
			$list = array(array('title'=>'手机预订-选择门店','updatetime'=>$companytime));
			$this->url = '/index.php?g=Wap&m=MemberDining&a=shops&companyid='.$this->companyid;
			$this->page = '<span class="item-count">共1条记录</span>';
		}elseif($where['wtype'] == '10'){
			$list = array(array('title'=>'预订历史','updatetime'=>$companytime));
			$this->url = '/index.php?g=Wap&m=MobileBook&a=history&companyid='.$this->companyid;
			$this->page = '<span class="item-count">共1条记录</span>';
		}elseif($where['wtype'] == '11'){
			$count = M('survey_activity_theme')->where($where)->count();
			$page = new NewPage($count,15);
			$list = M('survey_activity_theme')->where($where)->limit($page->firstRow.','.$page->listRows)->order($order)->select();
			$this->assign('page',$page->diyshow());
			$this->url = '/index.php?g=Wap&m=SurveyActivity&a=index&companyid='.$this->companyid.'&tid=';
		}elseif($where['wtype'] == '12'){
			$list = array(array('title'=>'DMS代理人系统','updatetime'=>$companytime));
			$this->url = '/index.php?g=Dms&m=Dms&a=Index&companyid='.$this->companyid;
			$this->page = '<span class="item-count">共1条记录</span>';
		}elseif($where['wtype'] == '13'){
			$list = array(array('title'=>'闪惠-门店列表页','updatetime'=>$companytime));
			$this->url = '/index.php?g=Wap&m=MemberDining&a=shops&companyid='.$this->companyid;
			$this->page = '<span class="item-count">共1条记录</span>';
		}elseif($where['wtype'] == '14'){
			$count = M('company_shops')->where($where)->count();
			$page = new NewPage($count,15);
			$list = M('company_shops')->where($where)->limit($page->firstRow.','.$page->listRows)->order($order)->select();
			$this->assign('page',$page->diyshow());
			$this->url = '/index.php?g=Wap&m=ShanHui&a=check&companyid='.$this->companyid.'&shopid=';
		}elseif($where['wtype'] == '15'){
			$list = array(array('title'=>'闪惠-买单历史','updatetime'=>$companytime));
			$this->url = '/index.php?g=Wap&m=ShanHui&a=historyList&companyid='.$this->companyid;
			$this->page = '<span class="item-count">共1条记录</span>';
		}elseif($where['wtype'] == '16'){
			$list = array(array('title'=>'全部门店','updatetime'=>$companytime));
			$this->url = '/index.php?g=Wap&m=MemberDining&a=shops&companyid='.$this->companyid;
			$this->page = '<span class="item-count">共1条记录</span>';
		}elseif($where['wtype'] == '17'){
			$count = M('company_shops')->where($where)->count();
			$page = new NewPage($count,15);
			$list = M('company_shops')->where($where)->limit($page->firstRow.','.$page->listRows)->order($order)->select();
			$this->assign('page',$page->diyshow());
			$this->url = '/index.php?g=Wap&m=MemberDining&a=shopsInfo&companyid='.$this->companyid.'&id=';
		}elseif($where['wtype'] == '18'){
			$list = array(array('title'=>'积分商城-首页','updatetime'=>$companytime));
			$this->url = '/index.php?g=Wap&m=MallIntegral&a=index&companyid='.$this->companyid;
			$this->page = '<span class="item-count">共1条记录</span>';
		}elseif($where['wtype'] == '19'){
			$count = M('mall_member_integral_class')->where($where)->count();
			$page = new NewPage($count,15);
			$list = M('mall_member_integral_class')->where($where)->limit($page->firstRow.','.$page->listRows)->order($order)->select();
			$this->assign('page',$page->diyshow());
			$this->url = '/index.php?g=Wap&m=MallIntegral&a=lists&companyid='.$this->companyid.'&id=';
		}elseif($where['wtype'] == '20'){
			$count = M('mall_member_integral_goods')->where($where)->count();
			$page = new NewPage($count,15);
			$list = M('mall_member_integral_goods')->where($where)->limit($page->firstRow.','.$page->listRows)->order($order)->select();
			$this->assign('page',$page->diyshow());
			$this->url = '/index.php?g=Wap&m=MallIntegral&a=info&companyid='.$this->companyid.'&id=';
		}elseif($where['wtype'] == '21'){
			$list = array(array('title'=>'积分商城-兑换记录','updatetime'=>$companytime));
			$this->url = '/index.php?g=Wap&m=MallIntegralOrder&a=index&companyid='.$this->companyid;
			$this->page = '<span class="item-count">共1条记录</span>';
		}elseif($where['wtype'] == '22'){
			$list = array(
					array('title'=>'会员中心','updatetime'=>$companytime,'id'=>'/index.php?&g=Wap&m=Member&a=center&companyid='.$this->companyid),
					array('title'=>'我的资料','updatetime'=>$companytime,'id'=>'/index.php?g=Wap&m=Member&a=editMyInformation&companyid='.$this->companyid),
					array('title'=>'我的累计积分','updatetime'=>$companytime,'id'=>'/index.php?g=Wap&m=Member&a=totalintegral&companyid='.$this->companyid),
					array('title'=>'使用积分','updatetime'=>$companytime,'id'=>'/index.php?g=Wap&m=Member&a=integralUse&companyid='.$this->companyid),
					array('title'=>'会员制说明','updatetime'=>$companytime,'id'=>'/index.php?g=Wap&m=Member&a=rankExplain&companyid='.$this->companyid)
					
			);
			$this->url = '';
			$this->page = '<span class="item-count">共5条记录</span>';
		}elseif($where['wtype'] == '23'){
			$list = array(
					array('title'=>'可用卡券','updatetime'=>$companytime,'id'=>'/index.php?g=Wap&m=MemberVouchers&a=myVouchers&companyid='.$this->companyid),
					array('title'=>'历史卡券','updatetime'=>$companytime,'id'=>'/index.php?g=Wap&m=MemberVouchers&a=myHistoryVouchers&companyid='.$this->companyid)
					
			);
			$this->url = '';
			$this->page = '<span class="item-count">共2条记录</span>';
		}elseif($where['wtype'] == '24'){
			$list = array(
					array('title'=>'eshop实物商品订单','updatetime'=>$companytime,'id'=>'/index.php?g=Wap&m=MemberMallOrder&a=index&ordertype=1&isshow=2&companyid='.$this->companyid),
					array('title'=>'券商品订单','updatetime'=>$companytime,'id'=>'/index.php?g=Wap&m=MemberMallOrder&a=index&ordertype=2&isshow=2&companyid='.$this->companyid),
					array('title'=>'计次卡商品订单','updatetime'=>$companytime,'id'=>'/index.php?g=Wap&m=MemberMallOrder&a=index&ordertype=3&isshow=2&companyid='.$this->companyid),
					array('title'=>'团购商品订单','updatetime'=>$companytime,'id'=>'/index.php?g=Wap&m=MemberMallOrder&a=index&ordertype=4&isshow=2&companyid='.$this->companyid),
					array('title'=>'门票商品订单','updatetime'=>$companytime,'id'=>'/index.php?g=Wap&m=MemberMallOrder&a=index&ordertype=5&isshow=2&companyid='.$this->companyid),
					array('title'=>'权益卡商品订单','updatetime'=>$companytime,'id'=>'/index.php?g=Wap&m=MemberMallOrder&a=index&ordertype=6&isshow=2&companyid='.$this->companyid),
					array('title'=>'卡券礼包商品订单','updatetime'=>$companytime,'id'=>'/index.php?g=Wap&m=MemberMallOrder&a=index&ordertype=7&isshow=2&companyid='.$this->companyid)
			);
			$this->url = '';
			$this->page = '<span class="item-count">共5条记录</span>';
		}elseif($where['wtype'] == '25'){
			$list = array(
					array('title'=>'储值卡','updatetime'=>$companytime,'id'=>'/index.php?g=Wap&m=Storedvalue&a=index&companyid='.$this->companyid),
					array('title'=>'储值卡-充值页面','updatetime'=>$companytime,'id'=>'/index.php?g=Wap&m=Storedvalue&a=recharge&companyid='.$this->companyid),
					array('title'=>'储值历史','updatetime'=>$companytime,'id'=>'/index.php?g=Wap&m=Storedvalue&a=historicalValue&companyid='.$this->companyid),
			);
			$this->url = '';
			$this->page = '<span class="item-count">共3条记录</span>';
		}elseif($where['wtype'] == '27'){
			$where['isopentakeout'] = 1;
			$count = M('company_shops')->where($where)->count();
			$page = new NewPage($count,15);
			$list = M('company_shops')->where($where)->limit($page->firstRow.','.$page->listRows)->order($order)->select();
			$this->assign('page',$page->diyshow());
			$this->url = '/index.php?g=Wap&m=TakeOut&a=index&companyid='.$this->companyid.'&id=';
		}elseif($where['wtype'] == '29'){
			$list = array(array('title'=>'风外卖-订单历史','updatetime'=>$companytime));
			$this->url = '/index.php?g=Wap&m=TakeOut&a=orderList&companyid='.$this->companyid;
			$this->page = '<span class="item-count">共1条记录</span>';
		}elseif($where['wtype'] == '30'){
			$where['type'] = 6;
			$where['endtime'] = array("gt",time());
			$count = M('member_marketing_activities_scrm')->where($where)->count();
			$page = new NewPage($count,15);
			$list = M('member_marketing_activities_scrm')->where($where)->limit($page->firstRow.','.$page->listRows)->order($order)->select();
			$this->assign('page',$page->diyshow());
			$this->url = '/index.php?g=Wap&m=MemberGiveawayVoucher&a=index&companyid='.$this->companyid.'&id=';
		}elseif($where['wtype'] == '31'){
			unset($where['type']);
			$count = M('consult_table')->where($where)->count();
			$page = new NewPage($count,15);
			$list = M('consult_table')->where($where)->limit($page->firstRow.','.$page->listRows)->order($order)->select();
			$this->assign('page',$page->diyshow());
			$this->url = '/index.php?g=Wap&m=ConsultTable&a=index&companyid='.$this->companyid.'&id=';
		}elseif($where['wtype'] == '32'){
			$list = array(
					array('title'=>'内容商店-首页','updatetime'=>$companytime,'id'=>'/index.php?g=Wap&m=PayShop&a=index&companyid='.$this->companyid),
					array('title'=>'内容商店-年费权益购买页','updatetime'=>$companytime,'id'=>'/index.php?g=Wap&m=PayShop&a=shopInfo&type=3&companyid='.$this->companyid),
					array('title'=>'内容商店-购买历史','updatetime'=>$companytime,'id'=>'/index.php?g=Wap&m=PayShop&a=history&companyid='.$this->companyid),
			);
			$this->url = '';
			$this->page = '<span class="item-count">共3条记录</span>';
		}elseif($where['wtype'] == '33'){
			unset($where['type']);
			$where['isshow'] = 1;
			$count = M('pay_shop_goods')->where($where)->count();
			$page = new NewPage($count,15);
			$list = M('pay_shop_goods')->where($where)->limit($page->firstRow.','.$page->listRows)->order($order)->select();
			$this->assign('page',$page->diyshow());
			$this->url = '/index.php?g=Wap&m=PayShop&a=shopInfo&companyid='.$this->companyid.'&id=';
		}elseif($where['wtype'] == '34'){
			$list = array(array('title'=>'预订（SPA行业版）-项目列表页','updatetime'=>$companytime));
			$this->url = '/index.php?g=Wap&m=SpaMobileBook&a=project&isopen=1&companyid='.$this->companyid;
			$this->page = '<span class="item-count">共1条记录</span>';
		}elseif($where['wtype'] == '35'){
			$where['isopen'] = 1;
			$count = M('spa_mobile_book_project_set')->where($where)->count();
			$page = new NewPage($count,15);
			$list = M('spa_mobile_book_project_set')->where($where)->limit($page->firstRow.','.$page->listRows)->order($order)->select();
			$this->assign('page',$page->diyshow());
			$this->url = '/index.php?g=Wap&m=SpaMobileBook&a=projectInfo&companyid='.$this->companyid.'&pid=';
		}elseif($where['wtype'] == '36'){
			$list = array(array('title'=>'预订（SPA行业版）-订单历史','updatetime'=>$companytime));
			$this->url = '/index.php?g=Wap&m=SpaMobileBook&a=history&companyid='.$this->companyid;
			$this->page = '<span class="item-count">共1条记录</span>'; 
		}elseif($where['wtype'] == '37'){
			$list = array(array('title'=>'订阅闹钟','updatetime'=>$companytime));
			$this->url = '/index.php?g=Wap&m=SubscribeClock&a=index&companyid='.$this->companyid;
			$this->page = '<span class="item-count">共1条记录</span>';
		}
		$this->assign('list',$list);
		$this->display();
	}
	/**
	 *
	 * 素材删除公用
	 *
	 * @author Mark<1311013341@qq.com>
	 * @since  2016-7-27
	 */
	public function dellist(){
		if(IS_POST){
			$where['id'] = array('in',$this->_post('id'));
			$where['companyid'] = $this->companyid;
			$result = $this->wlist->where($where)->delete();
			if($result){
				$ajax['code'] = '200';
			}else{
				$ajax['code'] = '300';
			}
		}else{
			$ajax['code'] = '300';
		}
		echo json_encode($ajax);
	}
	/**
	 *
	 * 修改名字（图片/分组公用）
	 *
	 * @author Mark<1311013341@qq.com>
	 * @since  2016-7-27
	 */
	public function editname(){
		if(IS_POST){
			$where['id'] = $this->_post('id');
			$where['companyid'] = $this->companyid;
			$data['title'] = $this->_post('name');
			$data['updatetime'] = time();
			$result = M('wei_list_group')->where($where)->save($data);
			if($result){
				$ajax['code'] = '200';
			}else{
				$ajax['code'] = '400';
			}
		}else{
			$ajax['code'] = '300';
		}
		echo json_encode($ajax);
	}
	/**
	 *
	 * 移动分组
	 *
	 * @author Mark<1311013341@qq.com>
	 * @since  2016-7-27
	 */
	public function editgroupid(){
		if(IS_POST){
			$where['id'] = array('in',$this->_post('id'));
			$where['companyid'] = $this->companyid;
			$data['gid'] = $this->_post('gid');
			$data['updatetime'] = time();
			$result = $this->wlist->where($where)->save($data);
			if($result){
				$ajax['code'] = '200';
			}else{
				$ajax['code'] = '300';
			}
		}else{
			$ajax['code'] = '300';
		}
		echo json_encode($ajax);
	}
	/**
	 *
	 * 删除分组
	 *
	 * @author Mark<1311013341@qq.com>
	 * @since  2016-7-27
	 */
	public function delgroup(){
		if(IS_POST){
			M()->startTrans();
			$where['id'] = $cwhere['gid'] = $this->_post('gid');
			$where['companyid'] = $cwhere['companyid'] = $this->companyid;
			$result = M('wei_list_group')->where($where)->delete();
			if($result){
				$count = $this->wlist->where($cwhere)->count();
				if($count>0){
					$save = $this->wlist->where($cwhere)->save(array('gid'=>'0','updatetime'=>time()));
					if($save){
						M()->commit();
						$ajax['code'] = '200';
					}else{
						M()->rollback();
						$ajax['code'] = '300';
					}
				}else{
					M()->commit();
					$ajax['code'] = '200';
				}
			}else{
				M()->rollback();
				$ajax['code'] = '300';
			}
		}else{
			$ajax['code'] = '300';
		}
		echo json_encode($ajax);
	}
	/**
	 *
	 * 新建分组
	 *
	 * @author Mark<1311013341@qq.com>
	 * @since  2016-7-27
	 */
	public function addgroup(){
		if(IS_POST){
			$data['id'] = guidNow();
			$data['companyid'] = $this->companyid;
			$data['title'] = $this->_post('name');
			$data['updatetime'] = $data['createtime'] = time();
			$result = M('wei_list_group')->add($data);
			if($result){
				$ajax['code'] = '200';
				$ajax['gid'] = $data['id'];
			}else{
				$ajax['code'] = '300';
			}
		}else{
			$ajax['code'] = '300';
		}
		echo json_encode($ajax);
	}
	/**
	 * 动态获取ID
	 */
	public function ajaxGuid(){
		$ajax['code'] = 300;
		$ajax['msg'] = '网络繁忙，请稍后重试';
		if(guidNow()){
			$ajax['code'] = 200;
			$ajax['id'] = guidNow();
		}
		echo json_encode($ajax);
	}
	/**
	 * 设为首页
	 */
	public function setIndex(){
		$ajax['code'] = 300;
		$ajax['msg'] = 'error:500';
		$where['companyid'] = $this->companyid;
		$where['type']=1;
		$where['ishomepage']=2;
		$info = $this->wlist->where($where)->find();
		if($info){
			$this->wlist->where($where)->save(array('ishomepage'=>1));
		}
		$where2['companyid'] = $this->companyid;
		$where2['id']= $this->_request('id');
		$res = $this->wlist->where($where2)->save(array('ishomepage'=>$this->_request('type'),'updatetime'=>time()));
		if($res){
			$ajax['code']=200;
			$ajax['msg']='设置首页成功';
			if($this->_request('type')==1){
				$ajax['msg']='已成功取消首页设置';
			}
		}
		echo json_encode($ajax);
	}
	/**
	 * 批量加密
	 */
	public function ajaxSetEncrtpy(){
		$ajax['code'] = 300;
		$ajax['msg'] = 'error:500';
		foreach ($this->_request('id') as $val){
			$data['isencrypt']=$this->_request('isencrypt');
			$data['encryptinfo']=$this->_request('encryptinfo');
			$data['updatetime']=time();
			$where['id']=$val;
			$res = $this->wlist->where($where)->save($data);
		}
		if($res){
			$ajax['code']=200;
			$ajax['msg']='批量设置加密页成功';
		}
		echo json_encode($ajax);
	}
	/**
	 * 复制页面
	 */
	public function clonePage(){
		$ajax['code'] = 300;
		$ajax['msg'] = 'error:500';
		$where['companyid'] = $this->companyid;
		$where['id'] = $this->_request('id');
		$info = $this->wlist->where($where)->find();
		$data = $info;
		$data['id'] = guidNow();
		$data['ishomepage']=1;
		$data['pv']=0;
		$data['updatetime'] = $data['createtime'] = time();	
		$res1 = $this->wlist->add($data);
		if($res1){
			$where2['parentid']=$this->_request('id');
			$where2['companyid'] = $this->companyid;
			$list = $this->wass->where($where2)->select();
			if($list){
				foreach ($list as $val){
					$data1 = $val;
					$data1['id'] = guidNow();
					$data1['parentid'] = $data['id'];
					$data1['updatetime'] = $data1['createtime'] = time();
					$this->wass->add($data1);
					$data1='';
				}
				$ajax['code']=200;
				$ajax['id']=$data['id'];
				$ajax['title']=$data['title'];
				$ajax['msg']='复制页面成功';
			}else{
				$ajax['code']=400;
				$ajax['msg']='当前页只有页面没有组件';
			}
		}else{
			$ajax['code']=500;
			$ajax['msg']='复制页面数据错误，请稍后重试';
		}
		echo json_encode($ajax);
	}
	/**
	 * 删除页面
	 */
	public function ajaxDel(){
		$ajax['code'] = 300;
		$ajax['msg'] = 'error:500';
		$id = $this->_post('id');
		$sucAss = $this->wlist->where(array('companyid'=>$this->companyid,'id'=>$id))->delete();
		$sucAss2 =  $this->wass->where(array('companyid'=>$this->companyid,'parentid'=>$id))->delete();
		if($sucAss){
			$ajax['code'] = 200;
			$ajax['msg'] = '操作成功';
		}
		echo json_encode($ajax);
	}
	/**
	 * 新建/编辑详首页
	 * asa 0810
	 */
	public function addInfo(){
		$ajax['code']=300;
		$ajax['msg']='服务器繁忙，请稍后重试';
		$id = $this->_post('id');
		if($id){
			$_POST['updatetime'] = time();
			$res = $this->wlist->where(array('companyid'=>$this->companyid,'id'=>$id))->save($_POST);
		}else{
			if($this->_post('pagetypeasa')==1){
				$where['companyid'] = $this->companyid;
				$where['type']=1;
				$where['ishomepage']=2;
				$info = $this->wlist->where($where)->find();
				if(!$info){
					$_POST['ishomepage'] = 2;
				}
			}else{
				$_POST['type'] = 2;
			}
			
			$id = $_POST['id'] = guidNow();
			$_POST['companyid'] = $this->companyid;
			$_POST['updatetime'] = $_POST['createtime'] = time();
			$res = $this->wlist->add($_POST);
		}
		if($res){
			$ajax['code'] = 200;
			$ajax['id'] = $id;
			$ajax['msg'] = '页面信息操作成功';
		}
		echo json_encode($ajax);
	}
	/**
	 * 新建/编辑组件页
	 * asa  0811
	 */
	public function addAssembly(){
		$ajax['code'] = 300;
		$ajax['msg'] = 'error:500';
		$id = $this->_post('id');
		$_POST['updatetime'] = time();
		if($id==''){
			$parentid = $_POST['parentid'];
			$sort = $this->wass->where(array('companyid'=>$this->companyid,'parentid'=>$parentid))->order("sort desc")->find();
			if($sort){
				$_POST['sort'] = ($sort['sort']+1);
			}else{
				$_POST['sort'] = 1;
			}
			$_POST['id'] = guidNow();
			$_POST['companyid'] = $this->companyid;
			$_POST['createtime'] = time();
			$res = $this->wass->add($_POST);
			
		}else{
			$res = $this->wass->where(array('companyid'=>$this->companyid,'id'=>$id))->save($_POST);
		}
		
		//通头的导航设置
		$type = $_POST['type'];
		if($type==11&&$_REQUEST['navarr']){
			M("wei_list_nav")->where(array("assid"=>$_POST['id']))->delete();
			$navarr = json_decode($_REQUEST['navarr'],TRUE);
			$navid = '';
			foreach ($navarr as $nval){
				$data = $nval;
				$data['assid'] = $_POST['id'];
				$data['companyid'] =$this->companyid;
				$data['updatetime'] = time();
				$data['createtime'] = time();
				$navres = M("wei_list_nav")->add($data);
				$navid .=$data['id'].",";
			}
			$data2['headnavid'] = $navid;
			$this->wass->where(array('companyid'=>$this->companyid,'id'=>$_POST['id']))->save($data2);
		}
		
		if($res){
			$str = '';
			$inner = '';
			$ajax['code'] = 200;
			$ajax['msg'] = '操作成功';
			$info = $this->wass->where(array('companyid'=>$this->companyid,'id'=>$_POST['id']))->find();
			$addinner = ' data-type='.$info['type'];
			$addinner .=' data-id='.$info['id'];
			if($info['type']==9||$info['type']==2||$info['type']==3||$info['type']==4){// 2 3 4 9
				if($info['type']==9){ $num = 1; }
				if($info['type']==4){ $num = 2; }
				if($info['type']==3){ $num = 3; }
				if($info['type']==2){ $num = 4; }
				$hreaderadd ='mod-img-'.$num;
				$editclass="edit-photo-asa";
				$str .='<div class="img-'.$num.'-group text-center">';
				for($i = 1;$i<=$num;$i++){
					$addinner .=' data-img'.$i.'="'.$info['small'.$i].'"';
					$addinner .=' data-url'.$i.'="'.$info['link'.$i].'"';
					$str .='<img src="';
					$str .=$info['small'.$i];
					$str .='" width="100%">  ';
				}
				$str .='</div>';
			}elseif($info['type']==5){
				$hreaderadd ='mod-slide';
				$editclass="edit-slide-asa";
				$a5='';
				$b5='';
				$c5=0;
				for($i=1;$i<=5;$i++){
					if($info['small'.$i]){
						$addinner .=' data-img'.$i.'="'.$info['small'.$i].'"';
						$addinner .=' data-url'.$i.'="'.$info['link'.$i].'"';
						$a5 .='<li><img src="'.$info['small'.$i].'" alt="" width="100%"></li> ';
						$b5 .='<li></li> ';$c5++;
					}
				}
				if($c5<=1){$b='';}
				$str .='<div class="demo-slideBox">';
				$str .='<div class="hd"><ul>'.$b5.'</ul></div>';
				$str .='<div class="bd"><ul>'.$a5.'</ul></div>';
				$str .='</div>';
				$str .='<script>';
				$str .='$(".demo-slideBox").slide({';
            	$str .='mainCell: ".bd ul",';
            	$str .='effect: "leftLoop",';
            	$str .='autoPlay: true,';
            	$str .='trigger: "click"';
        		$str .='});';
				$str .='</script>';
			}elseif($info['type']==7){
				$hreaderadd ='mod-blank';
				$editclass="edit-blank-asa";
				$addinner .=' data-blank='.$info['blank'];
                $str .='<div class="FuZhuKongBai" style="height:'.$info['blank'].'px;"><!-- 我是辅助空白，其实你根本就看不见我 --></div>';
			}elseif($info['type']==10){
				$hreaderadd ='mod-textarea';
				$editclass="edit-bigtext-asa";
				$str .='<div class="FuWenBen" id="b'.$info['id'].'">'.htmlspecialchars_decode($info['textinfo']).'</div>';
			}elseif($info['type']==6){
				$hreaderadd ='product-small-wrap-cover';
				$editclass="edit-goods-asa";
				$addinner .=' data-goodid="'.$info['goodid'].'" data-goodprice="'.$info['goodprice'].'" data-goodshopcart="'.$info['goodshopcart'].'"';
				$addinner .=' data-goodshopcartclass="'.$info['goodshopcartclass'].'" data-display="'.$info['display'].'"';
				
				if($info['display']==3){
					$str .='<div class="product-small-wrap-cover product-small-wrap-cover-tempFixed clearfix">';
				}else if($info['display']==1){
					$str .='<div class="product-small-wrap-cover product-small-wrap-cover-tempFixed-Big clearfix">';
				}else{
					$str .='<div class="product-small-wrap-cover clearfix">';
				}
				$goodslist = M('mall_goods')->where(array('companyid'=>$this->companyid,'id'=>array('in',$info['goodid'])))->field('id,goodtype,title,pricetype,saleprice,intprice,voucherimgurl')->order('updatetime DESC')->select();
				if($goodslist){
					foreach($goodslist as $gkey=>$gval){
						if($gval['goodtype'] == 1 || $gval['goodtype'] == 3 || $gval['goodtype'] == 4 || $gval['goodtype'] == 5 || $gval['goodtype'] == 6|| $gval['goodtype'] == 7){
							$pic = M('mall_goods_pics')->where(array('companyid'=>$this->companyid,'goodid'=>$gval['id']))->order('sort')->getField('pic');
						}else{
							$pic = $gval['voucherimgurl'];
						}
						$str .=' <div class="product-small-wrap fl">';
						$str .='<img src="'.$pic.'" width="165px" style="margin:auto;display:block">';
						$str .='<h4>';
						if($info['goodname']!=2){
							$str .=$gval['title'];
						}
						$str .='</h4>';
						if($info['goodshopcart']!=2||$info['goodprice']!=2){
							$str .='<div class="product-price clearfix">';
							$str .='<h3 class="fl w100">';
							if($info['goodprice']!=2){
								if($gval['pricetype']==1){
									$str .='￥'.$gval['saleprice'];
								}else{
									$str .=$gval['intprice'].'积分';
								}
							}
							$str .='</h3>';
							if($gval['goodtype'] == 1){
								if($info['goodshopcart']!=2){
									$str .='<i class="icon-buy-buy-buy fr"></i>';
								}
							}elseif($gval['goodtype'] == 3){
								$str .='<i class="icon-buy-buy-buy icon-jicika fr"></i>';
							}elseif($gval['goodtype'] == 4){
								$str .='<i class="icon-buy-buy-buy icon-tuangouka fr"></i>';
							}elseif($gval['goodtype'] == 5){
								$str .='<i class="icon-buy-buy-buy icon-piao fr"></i>';
							}elseif($gval['goodtype'] == 2){
								$str .='<i class="icon-buy-buy-buy icon-quan fr"></i>';
							}elseif($gval['goodtype'] == 6){
								$str .='<i class="icon-buy-buy-buy icon-qianyika fr"></i>';
							}elseif($gval['goodtype'] == 7){
								$str .='<i class="icon-buy-buy-buy icon-libao fr"></i>';
							}
							$str .='</div>';
						}
						$str .='</div> ';
					}
				}
				$str .='</div>';
			}elseif($info['type'] == 14){
				$hreaderadd ='mod-product-big-1-small-2 mod-groupon';
				$editclass="edit-groupon-goods-asa";
				$addinner .=' data-goodid="'.$info['goodid'].'"';
				
				$str .='<div class="product-small-wrap-cover product-small-wrap-cover-tempFixed clearfix">';
				$goodslist = M('mall_goods')->where(array('companyid'=>$this->companyid,'id'=>array('in',$info['goodid'])))->field('id,goodtype,title,pricetype,saleprice,intprice,voucherimgurl,grouponprice')->select();
				if($goodslist){
					foreach($goodslist as $gkey=>$gval){
						if($gval['goodtype'] == 1 || $gval['goodtype'] == 3 || $gval['goodtype'] == 4 || $gval['goodtype'] == 5 || $gval['goodtype'] == 6 || $gval['goodtype'] == 7){
							if($gval['goodtype'] == 6 || $gval['goodtype'] == 7){
								$grouponprice = $gval['grouponprice'];
							}else{
								$grouponprice = M('mall_goods_sku')->where(array('companyid'=>$this->companyid,'goodid'=>$gval['id']))->order('grouponprice')->getField('grouponprice');
							}
							$pic = M('mall_goods_pics')->where(array('companyid'=>$this->companyid,'goodid'=>$gval['id']))->order('sort')->getField('pic');
						}else{
							$pic = $gval['voucherimgurl'];
							$grouponprice = $gval['grouponprice'];
						}
						$groupnum = M('mall_groupon_activity')->where(array('companyid'=>$this->companyid,'goodid'=>$gval['id']))->getField('groupnum');
						$str .='<div class="product-small-wrap fl">';
						$str .='<img src="'.$pic.'" style="margin:auto;display:block;" width="165px">';
						$str .='<h4>';
						$str .=$gval['title'];
						$str .='</h4>';
						$str .='<div class="product-price clearfix">';
						$str .='<h3 class="fl w150 grab-at-once-h3">';
						$str .='￥'.$grouponprice.'<span class="grab-at-once-originalprice">￥'.$gval['saleprice'].'</span>';
						$str .='</h3>';
						$str .='<div class="fr"><p class="grab-at-once-nb mr-6 fl">'.$groupnum.'人团</p><button class="btn-small grab-at-once-btn fl" style="cursor:pointer;">马上抢</button></div>';
						$str .='</div> ';
					}
				}
				$str .='</div>';
			}elseif($info['type']==12){
				$hreaderadd ='mod-footer';
				$editclass="edit-footer-asa";
				$addinner .=' data-footisstatus='.$info['footisstatus'];
				$addinner .=' data-footclass='.$info['footclass'];
				$str .='<div class="mod-footer-group"><i class="footer-group-img"></i></div>';
			}elseif($info['type']==11){
				$hreaderadd ='mod-top text-center';
				$ajax['headertype'] = $info['headclasstype'];
				$ajax['headcolor'] = $info['headcolor'];
				$editclass="edit-header-asa";
				$addinner .=' data-headlogo="'.$info['headlogo'].'"';
				$addinner .=' data-headnavid="'.$info['headnavid'].'"';
				$addinner .=' data-headsearchstatus="'.$info['headsearchstatus'].'"';
				$addinner .=' data-headnavstatus="'.$info['headnavstatus'].'"';
				$addinner .=' data-headsearchclass="'.$info['headsearchclass'].'"';
				$addinner .=' data-headclasstype="'.$info['headclasstype'].'"';
				$addinner .=' data-headcolor="'.$info['headcolor'].'"';

				if($info['headnavstatus']==1){
					$str .='<i class="icon-menu-btn fl"></i>';
				}
				if($info['headlogo']!=''){
					$str .='<img class="inline" src="'.$info['headlogo'].'" style="margin:4px auto;height:38px;">';
				}
				if($info['headsearchstatus']==1){
					$str .='<i class="icon-search-btn fr"></i>';
				}
			}elseif($info['type']==1){
				$hreaderadd ='mod-banner';
				$editclass="edit-Banner-asa";
				$addinner .=' data-id="'.$info['id'].'"';
				$addinner .=' data-blink="'.$info['blink'].'"';
				$addinner .=' data-banner="'.$info['banner'].'"';
				$str .= '<img class="" src="'.$info['banner'].'" width="100%">';
			}elseif($info['type']==13){
				$editclass="edit-article-asa";
				$addinner .=' data-id="'.$info['id'].'"';
				$addinner .=' data-isshowtime="'.$info['isshowtime'].'"';
				$addinner .=' data-blink="'.$info['blink'].'"';
				$addinner .=' data-banner="'.$info['banner'].'"';
				$addinner .=' data-articletype="'.$info['articletype'].'"';
				if($info['articletype']=='2'){
					$hreaderadd ='mod-page-list-small';
					$str .= '<ul><li class="page-box clearfix">';
					$str .= '<img src="'.$info['banner'].'" class="page-img">';
					$str .= '<h3 class="page-tit js-articletitle-'.$info['id'].'">'.$info['articletitle'].'</h3>';
					if($info['isshowtime']=='1'){
						$str .= '<h6 class="page-date">'.format_time($info['updatetime'],'ymd').'</h6>';
					}
					$str .= '<h6 class="page-con text-gray js-textinfo-'.$info['id'].'">'.$info['textinfo'].'</h6>';
					$str .= '</li></ul>';
				}else{
					$hreaderadd ='mod-page-list-big';
					$str .= '<div class="page-box">';
					$str .= '<h3 class="page-tit js-articletitle-'.$info['id'].'">'.$info['articletitle'].'</h3>';
					if($info['isshowtime']=='1'){
						$str .= '<h6 class="page-date">'.format_time($info['updatetime'],'ymd').'</h6>';
					}
					$str .= '<img width="100%" src="'.$info['banner'].'" class="page-img">';
					$str .= '<h6 class="page-con text-gray js-textinfo-'.$info['id'].'">'.$info['textinfo'].'</h6>';
					$str .= '</div>';
				}
			}
			$str .='';
			$innera .='<div class="edit-mod-group clearfix on">';
			$innera .='<span class="mod-edit-btn '.$editclass.' fl"'.$addinner.'>编辑模块</span>';
			$innera .='<ul class="edit-icon-group fr">';
			$innerb .='<li><i class="icon-mod-to-up js-icon-mod-to-up"></i></li>';
			$innerb .='<li><i class="icon-mod-to-down js-icon-mod-to-down"></i></li>';
			$innerc .='<li><i class="icon-mod-be-remove js-icon-mod-be-remove del-assembly-asa" data-type="'.$info['type'].'" data-id="'.$info['id'].'"></i></li>';
			$innerc .='</ul>';
			$innerc .='</div>';
			
			$header = '<div class="inner-mod on '.$hreaderadd.' a'.$info['id'].'" data-id="'.$info['id'].'">';
			$footer = '<div>';
			
			$ajax['type'] = $num?$num:$info['type'];
			
			$htmlasa = $header.$inner.$str.$footer;
			if($id==''){
				if($ajax['type']==11||$ajax['type']==12){
					$htmlasa = $header.$innera.$innerc.$str.$footer;
				}else{
					$htmlasa = $header.$innera.$innerb.$innerc.$str.$footer;
				}
			}else{
				if($ajax['type']==11||$ajax['type']==12){
					$htmlasa = $innera.$innerc.$str;
				}else{
					$htmlasa = $innera.$innerb.$innerc.$str;
				}
			}
			$ajax['html'] = $htmlasa;
		}
		echo json_encode($ajax);
	}
	
	/**
	 * ajax--获取商品列表
	 * @author asa<asa@renlaifeng.cn>
	 * @since  2016-8-11
	 */
	public function ajaxGoods(){
		$return['code'] = 400;
		$return['msg'] = '好像出错了%>_<%';

		$title = $this->_post('title');
		$ids = $this->_post('ids');
		$type = $this->_post('type');
		$goodtype = $this->_post("goodtype");
		$where['companyid'] = $this->companyid;
		if($title){
			$where['title'] = array('like','%'.$title.'%');
			$where2['title'] = array('like','%'.$title.'%');
		}
		if($goodtype){
			$where['goodtype'] = $goodtype;
			$where2['goodtype'] = $goodtype;
		}
		if($ids){
			if($type==1){
				$where['id'] = array('not in',$ids);
			}else{
				$where['id'] = array('in',$ids);
			}
		}
		$where['isoffshelves'] = '2';
		$where['issoldout'] = '2';
		$where2['isoffshelves'] = '2';
		$where2['issoldout'] = '2';
		$where2['id'] = array('in',$ids);
		$tags = M('mall_goods')->where($where)->field('id,goodtype,title,voucherimgurl,updatetime')->order('updatetime DESC')->select();
		
		$ishave = M('mall_goods')->where($where2)->count();
		
		if($tags){
			$return['code'] = 200;
			foreach($tags as $key=>$val){
				if($type==1){
					$return['html'] .= '<li data-id="'.$val['id'].'">'.$val['title'].'</li>';
				}else{
					$return['html'] .= '<li class="slideinfo" data-id="'.$val['id'].'">';
					$return['html'] .= '<span>'.$val['title'].'</span>';
					$return['html'] .= '<ul class="edit-group-wrap-2 clearfix">';
					/* $return['html'] .= '<li><i class="edit-icon-group to-left js-to-left"></i></li>';
					$return['html'] .= '<li><i class="edit-icon-group to-right js-to-right"></i></li>'; */
					$return['html'] .= '<li><i class="edit-icon-group be-romove js-be-romove"></i></li>';
					$return['html'] .= '</ul>';
					$return['html'] .= '</li>';
				}
			}
		}else if($ishave){
			$return['code'] = 300;
			$return['msg'] = '这个商品已经在右侧选中栏了';
		}else{
			$return['code'] = 300;
			$return['msg'] = '没有这个商品哦';
		}
		echo json_encode($return);
	}
	/**
	 * ajax--获取拼团商品列表
	 * @author asa<asa@renlaifeng.cn>
	 * @since  2016-8-11
	 */
	public function ajaxGroupGoods(){
		$return['code'] = 400;
		$return['msg'] = '好像出错了%>_<%';
	
		$title = $this->_post('title');
		$ids = $this->_post('ids');
		$type = $this->_post('type');
		$where['companyid'] = $this->companyid;
		if($title){
			$where['title'] = array('like','%'.$title.'%');
			$where2['title'] = array('like','%'.$title.'%');
		}
		if($ids){
			if($type==1){
				$where['id'] = array('not in',$ids);
			}else{
				$where['id'] = array('in',$ids);
			}
		}
		$where['isoffshelves'] = '2';
		$where['issoldout'] = '2';
		$where['isgroupon'] = '1';
		$where2['isoffshelves'] = '2';
		$where2['issoldout'] = '2';
		$where2['id'] = array('in',$ids);
		$tags = M('mall_goods')->where($where)->field('id,goodtype,title,voucherimgurl,updatetime')->order('updatetime DESC')->select();
	
		$ishave = M('mall_goods')->where($where2)->count();
	
		if($tags){
			$return['code'] = 200;
			foreach($tags as $key=>$val){
				if($type==1){
					$return['html'] .= '<li data-id="'.$val['id'].'">'.$val['title'].'</li>';
				}else{
					$return['html'] .= '<li class="slideinfo" data-id="'.$val['id'].'">';
					$return['html'] .= '<span>'.$val['title'].'</span>';
					$return['html'] .= '<ul class="edit-group-wrap-2 clearfix">';
					$return['html'] .= '<li><i class="edit-icon-group be-romove js-groupon-be-romove"></i></li>';
					$return['html'] .= '</ul>';
					$return['html'] .= '</li>';
				}
			}
		}else if($ishave){
			$return['code'] = 300;
			$return['msg'] = '这个商品已经在右侧选中栏了';
		}else{
			$return['code'] = 300;
			$return['msg'] = '没有这个商品哦';
		}
		echo json_encode($return);
	}
	//获取导航
	public function ajaxHeadNav(){
		$return['code'] = 300;
		$return['msg'] = '好像出错了%>_<%';
		$ids = $this->_post('ids');
		$ids=substr($ids,0,-1);
		$where['companyid'] = $this->companyid;
		$where['assid'] = $this->_post("assid");
		if($ids){
			$where['id'] = array('in',$ids);
		}
		$where['_string'] = "parentid='0' or parentid=''";
		$tags = M("wei_list_nav")->where($where)->field('id,parentid,title,url,isstatus,sort')->order('sort asc')->select();
		if($tags){
			$return['code'] = 200;
			foreach($tags as $key=>$val){
				if($val['isstatus']==1){$val['isstatus1']="启用";}else{$val['isstatus1']="禁用";}
				$return['html'] .= '<tr id="n'.$val['id'].'" data-id="'.$val['id'].'" data-url="'.$val['url'].'" data-parentid="'.$val['parentid'].'" data-type="1" data-title="'.$val['title'].'" data-sort="'.$val['sort'].'" data-isstatus="'.$val['isstatus'].'">';
				$return['html'] .= '<td>'.$val['sort'].'</td> ';
				$return['html'] .= '<td>'.$val['title'].'</td> ';
				$return['html'] .= '<td><a href="javascript:void(0);" class="tips js-navisstatus-asa">'.$val['isstatus1'].'</a></td> ';
				$return['html'] .= '<td>';
				$return['html'] .= '<a href="javascript:void(0);" class="tips edit-header-nav-asa">编辑</a> ';
				$return['html'] .= '<a href="javascript:void(0);" class="tips del-header-nav-asa" data-id="'.$val['id'].'">删除</a>';
				$return['html'] .= '</td> ';
				$return['html'] .= '</tr> ';
				$tags2 = M("wei_list_nav")->where(array("companyid"=>$this->companyid,'parentid'=>$val['id']))->order('sort asc')->select();
				if($tags2){
					foreach($tags2 as $key2=>$val2){
						if($val2['isstatus']==1){$val2['isstatus1']="启用";}else{$val2['isstatus1']="禁用";}
						$return['html'] .= '<tr id="n'.$val2['id'].'" data-id="'.$val2['id'].'" data-url="'.$val2['url'].'" data-parentid="'.$val2['parentid'].'" data-type="2" data-title="'.$val2['title'].'" data-sort="'.$val2['sort'].'" data-isstatus="'.$val2['isstatus'].'">';
						$return['html'] .= '<td>'.$val2['sort'].'</td> ';
						$return['html'] .= '<td>&nbsp;&nbsp;&nbsp;-- &nbsp;'.$val2['title'].'</td> ';
						$return['html'] .= '<td><a href="javascript:void(0);" class="tips js-navisstatus-asa">'.$val2['isstatus1'].'</a></td> ';
						$return['html'] .= '<td>';
						$return['html'] .= '<a href="javascript:void(0);" class="tips edit-header-nav-asa">编辑</a> ';
						$return['html'] .= '<a href="javascript:void(0);" class="tips del-header-nav-asa" data-id="'.$val2['id'].'">删除</a>';
						$return['html'] .= '</td> ';
						$return['html'] .= '</tr> ';
					}
				}
			}
		}else{
			$return['code'] = 400;
		}
		echo json_encode($return);
	}
	/**
	 * 判断是否含有通头或通底
	 */
	public function ishaveHF(){
		$where3['parentid']=$this->_post('parentid');
		$where3['companyid'] = $this->companyid;
		$type = $this->_post('type');
		$where3['type'] = $type;
		$info =  $this->wass->where($where3)->field('id,type,footisstatus,footclass')->select();
		if($info){
			$return['code'] = 300;
			if($this->_post('type')==12){
				$return['msg'] = '一个页面只能有一个通底';
			}elseif($this->_post('type')==11){
				$return['msg'] = '一个页面只能有一个通头';
			}
		}else{
			$where['type'] = -1;
			$where['parentid']=$this->_post('parentid');
			$info3 =  $this->wass->where($where)->field('id,type,assid')->select();
			foreach ($info3 as $val){
				$where2['parentid']=$val['assid'];
				$where2['companyid'] = $this->companyid;
				$where2['type'] = $type;
				$info4 = $this->wass->where($where2)->find();
				if($info4){
					$ishavehf = 1;
				}
			}
			if($ishavehf == 1){
				$return['code'] = 300;
				if($this->_post('type')==12){
					$return['msg'] = '一个页面只能有一个通底';
				}elseif($this->_post('type')==11){
					$return['msg'] = '一个页面只能有一个通头';
				}
			}else{
				$return['code'] = 200;
				$return['msg'] = '2';
			}
		}
		echo json_encode($return);
	}
	/**
	 * 自定义模块判断是否含有通头或通底
	 */
	public function ishaveHF2(){
		$ass['companyid'] = $this->companyid;
		$ass['parentid']=$this->_post('assid');
		$ass['type'] = array("in",'11,12');
		$assinfo = $this->wass->where($ass)->select();
		if($assinfo){
			$where['companyid'] = $this->companyid;
			$where['parentid']=$this->_post('weiid');
			$where['type'] = array("in",'11,12');
			$info =  $this->wass->where($where)->select();
			if($info){
				$where['parentid']=$this->_post('assid');
				$where['type'] = array("in",'11,12');
				$info2 =  $this->wass->where($where)->select();
				if($info2){
					$return['code'] = 300;
					$return['msg'] = '一个页面只能有一个通头或通底';
				}else{
					$where['type'] = -1;
					$where['parentid']=$this->_post('weiid');
					$info3 =  $this->wass->where($where)->field('id,type,assid')->select();
					foreach ($info3 as $val){
						$where2['parentid']=$val['assid'];
						$where2['companyid'] = $this->companyid;
						$where2['type'] = array("in",'11,12');
						$info4 = $this->wass->where($where2)->find();
						if($info4){
							$ishavehf = 1;
						}
					}
					if($ishavehf == 1){
						$return['code'] = 300;
						$return['msg'] = '一个页面只能有一个通头或通底';
					}else{
						$return['code'] = 200;
						$return['msg'] = '1';
					}
				}
			}else{
				$where['type'] = -1;
				$where['parentid']=$this->_post('weiid');
				$info3 =  $this->wass->where($where)->field('id,type,parentid,assid')->select();
				foreach ($info3 as $val){
					$where2['parentid']=$val['assid'];
					$where2['companyid'] = $this->companyid;
					$where2['type'] = array("in",'11,12');
					$info4 = $this->wass->where($where2)->find();
					if($info4){
						$ishavehf = 1;
					}
				}
				if($ishavehf == 1){
					$return['code'] = 300;
					$return['msg'] = '一个页面只能有一个通头或通底';
				}else{
					$return['code'] = 200;
					$return['msg'] = '2';
				}
			}
		}else{
			$return['code'] = 200;
			$return['msg'] = '2';
		}
		
		
		echo json_encode($return);
	}
	/**
	 * 选择自定义模块的
	 */
	public function assInfo(){
		$where['companyid'] = $this->companyid;
		$where['parentid']=$this->_post('parentid');
		
		$sort = $this->wass->where($where)->order("sort desc")->find();
		if($sort){
			$_POST['sort'] = ($sort['sort']+1);
		}else{
			$_POST['sort'] = 1;
		}
		$_POST['id'] = guidNow();
		$_POST['companyid'] = $this->companyid;
		$_POST['type'] = -1;
		$_POST['createtime'] = $_POST['updatetime'] = time();
		$res = $this->wass->add($_POST);
		
		$where['parentid']=$this->_post('assid');
		$where['type'] = array("not in",'11,12');
		$info2 =  $this->wass->where($where)->order('sort asc,updatetime DESC')->select();
		$str ='';
		if($info2){ //优先组件的判断
			foreach ($info2 as $key => $info){
				if($info['type']==9||$info['type']==2||$info['type']==3||$info['type']==4){// 2 3 4 9
					if($info['type']==9){ $num = 1; }
					if($info['type']==4){ $num = 2; }
					if($info['type']==3){ $num = 3; }
					if($info['type']==2){ $num = 4; }
					$str .='<div class="img-'.$num.'-group text-center">';
					for($i = 1;$i<=$num;$i++){
						$addinner .=' data-img'.$i.'='.$info['small'.$i];
						$addinner .=' data-url'.$i.'='.$info['link'.$i];
						$str .='<img src="';
						$str .=$info['small'.$i];
						$str .='" width="100%">  ';
					}
					$str .='</div>';
				}elseif($info['type']==5){
					$a5='';
					$b5='';
					$c5=0;
					for($i=1;$i<=5;$i++){
						if($info['small'.$i]){
							$a5 .='<li><img src="'.$info['small'.$i].'" alt="" width="100%"></li> ';
							$b5 .='<li></li> ';$c5++;
						}
					}
					if($c5<=1){$b='';}
					$str .='<div class="demo-slideBox">';
					$str .='<div class="hd"><ul>'.$b5.'</ul></div>';
					$str .='<div class="bd"><ul>'.$a5.'</ul></div>';
					$str .='</div>';
					$str .='<script>';
					$str .='$(".demo-slideBox").slide({';
	            	$str .='mainCell: ".bd ul",';
	            	$str .='effect: "leftLoop",';
	            	$str .='autoPlay: true,';
	            	$str .='trigger: "click"';
	        		$str .='});';
					$str .='</script>';
				}elseif($info['type']==7){
	                $str .='<div class="FuZhuKongBai" style="height:'.$info['blank'].'px;"><!-- 我是辅助空白，其实你根本就看不见我 --></div>';
				}elseif($info['type']==10){
					$str .='<div class="FuWenBen" id="b'.$info['id'].'">'.htmlspecialchars_decode($info['textinfo']).'</div>';
				}elseif($info['type']==6){
					
					if($info['display']==3){
						$str .='<div class="product-small-wrap-cover product-small-wrap-cover-tempFixed clearfix">';
					}else if($info['display']==1){
						$str .='<div class="product-small-wrap-cover product-small-wrap-cover-tempFixed-Big clearfix">';
					}else{
						$str .='<div class="product-small-wrap-cover clearfix">';
					}
					/* if($info['display']==3){
						$str .='<div class="product-small-wrap-cover product-small-wrap-cover-tempFixed clearfix">';
					}else{
						$str .='<div class="product-small-wrap-cover clearfix">';
					} */
					$goodslist = M('mall_goods')->where(array('companyid'=>$this->companyid,'id'=>array('in',$info['goodid'])))->field('id,goodtype,title,pricetype,saleprice,intprice,voucherimgurl')->order('updatetime DESC')->select();
					if($goodslist){
						foreach($goodslist as $gkey=>$gval){
							if($gval['goodtype'] == 1 || $gval['goodtype'] == 3 || $gval['goodtype'] == 4 || $gval['goodtype'] == 5 || $gval['goodtype'] == 6 || $gval['goodtype'] == 7){
								$pic = M('mall_goods_pics')->where(array('companyid'=>$this->companyid,'goodid'=>$gval['id']))->order('sort')->getField('pic');
							}else{
								$pic = $gval['voucherimgurl'];
							}
							$str .=' <div class="product-small-wrap fl">';
							$str .='<img src="'.$pic.'" width="98%" style="margin:auto;display:block">';
							$str .='<h4>';
							if($info['goodname']!=2){
								$str .=$gval['title'];
							}
							$str .='</h4>';
							if($info['goodshopcart']!=2||$info['goodprice']!=2){
								$str .='<div class="product-price clearfix">';
								$str .='<h3 class="fl w100">';
								if($info['goodprice']!=2){
									if($gval['pricetype']==1){
										$str .='￥'.$gval['saleprice'];
									}else{
										$str .=$gval['intprice'].'积分';
									}
								}
								$str .='</h3>';
								if($gval['goodtype'] == 1){
									if($info['goodshopcart']!=2){
										$str .='<i class="icon-buy-buy-buy fr"></i>';
									}
								}elseif($gval['goodtype'] == 3){
									$str .='<i class="icon-buy-buy-buy icon-jicika fr"></i>';
								}elseif($gval['goodtype'] == 4){
									$str .='<i class="icon-buy-buy-buy icon-tuangouka fr"></i>';
								}elseif($gval['goodtype'] == 5){
									$str .='<i class="icon-buy-buy-buy icon-piao fr"></i>';
								}elseif($gval['goodtype'] == 2){
									$str .='<i class="icon-buy-buy-buy icon-quan fr"></i>';
								}elseif($gval['goodtype'] == 6){
									$str .='<i class="icon-buy-buy-buy icon-qianyika fr"></i>';
								}elseif($gval['goodtype'] == 7){
									$str .='<i class="icon-buy-buy-buy icon-libao fr"></i>';
								}
								$str .='</div>';
							}
							$str .='</div> ';
						}
					}
					$str .='</div>';
				}elseif($info['type'] == 14){
					$str .='<div class="product-small-wrap-cover product-small-wrap-cover-tempFixed clearfix">';
					$goodslist = M('mall_goods')->where(array('companyid'=>$this->companyid,'id'=>array('in',$info['goodid'])))->field('id,goodtype,title,pricetype,saleprice,intprice,voucherimgurl,grouponprice')->select();
					if($goodslist){
						foreach($goodslist as $gkey=>$gval){
							if($gval['goodtype'] == 1 || $gval['goodtype'] == 3 || $gval['goodtype'] == 4 || $gval['goodtype'] == 5 || $gval['goodtype'] == 6 || $gval['goodtype'] == 7){
								if($gval['goodtype'] == 6 || $gval['goodtype'] == 7){
									$grouponprice = $gval['grouponprice'];
								}else{
									$grouponprice = M('mall_goods_sku')->where(array('companyid'=>$this->companyid,'goodid'=>$gval['id']))->order('grouponprice')->getField('grouponprice');
								}
								$pic = M('mall_goods_pics')->where(array('companyid'=>$this->companyid,'goodid'=>$gval['id']))->order('sort')->getField('pic');
							}else{
								$pic = $gval['voucherimgurl'];
								$grouponprice = $gval['grouponprice'];
							}
							$groupnum = M('mall_groupon_activity')->where(array('companyid'=>$this->companyid,'goodid'=>$gval['id']))->getField('groupnum');
							$str .='<div class="product-small-wrap fl">';
							$str .='<img src="'.$pic.'" style="margin:auto;display:block;" width="165px">';
							$str .='<h4>';
							$str .=$gval['title'];
							$str .='</h4>';
							$str .='<div class="product-price clearfix">';
							$str .='<h3 class="fl w150 grab-at-once-h3">';
							$str .='￥'.$grouponprice.'<span class="grab-at-once-originalprice">￥'.$gval['saleprice'].'</span>';
							$str .='</h3>';
							$str .='<div class="fr"><p class="grab-at-once-nb mr-6 fl">'.$groupnum.'人团</p><button class="btn-small grab-at-once-btn fl" style="cursor:pointer;">马上抢</button></div>';
							$str .='</div> ';
						}
					}
					$str .='</div>';
				}elseif($info['type'] == '1'){
					$hreaderadd ='mod-banner';
					$str .= '<img class="" src="'.$info['banner'].'" width="100%">';
				}elseif($info['type']==13){
					if($info['articletype']=='2'){
						$str .= '<div class="inner-mod mod-page-list-small">';
						$str .= '<ul><li class="page-box clearfix">';
						$str .= '<img src="'.$info['banner'].'" class="page-img">';
						$str .= '<h3 class="page-tit js-articletitle-'.$info['id'].'">'.$info['articletitle'].'</h3>';
						if($info['isshowtime']=='1'){
							$str .= '<h6 class="page-date">'.format_time($info['updatetime'],'ymd').'</h6>';
						}
						$str .= '<h6 class="page-con text-gray js-textinfo-'.$info['id'].'">'.$info['textinfo'].'</h6>';
						$str .= '</li></ul></div>';
					}else{
						$str .= '<div class="inner-mod mod-page-list-big">';
						$str .= '<div class="page-box">';
						$str .= '<h3 class="page-tit js-articletitle-'.$info['id'].'">'.$info['articletitle'].'</h3>';
						if($info['isshowtime']=='1'){
							$str .= '<h6 class="page-date">'.format_time($info['updatetime'],'ymd').'</h6>';
						}
						$str .= '<img width="100%" src="'.$info['banner'].'" class="page-img">';
						$str .= '<h6 class="page-con text-gray js-textinfo-'.$info['id'].'">'.$info['textinfo'].'</h6>';
						$str .= '</div></div>';
					}
				}
			}
			$inner .='<div class="edit-mod-group clearfix on">';
			$inner .='';
			$inner .='<ul class="edit-icon-group fr">';
			$inner .='<li><i class="icon-mod-to-up js-icon-mod-to-up"></i></li>';
			$inner .='<li><i class="icon-mod-to-down js-icon-mod-to-down"></i></li>';
			$inner .='<li><i class="icon-mod-be-remove js-icon-mod-be-remove del-assembly-asa" data-type="'.$info['type'].'" data-id="'.$_POST['id'].'"></i></li>';
			$inner .='</ul>';
			$inner .='</div>';
				
			$header = '<div class="inner-mod on a'.$_POST['id'].'" data-id="'.$_POST['id'].'" >';
			$footer = '<div>';
			$ajax['html'] = $header.$inner.$str.$footer;
			$ajax['type'] = 1;
		
		} //否则选通头或通底
		$where3['parentid']=$this->_post('assid');
		$where3['companyid'] = $this->companyid;
		$where3['type'] = 12;
		$footerinfo =  $this->wass->where($where3)->field('id,type,footisstatus,footclass')->find();
		if($footerinfo){//是否含有通底
			$strf .='<div class="inner-mod mod-footer clearfix on a'.$_POST['id'].'">';
			$strf .='<div class="edit-mod-group clearfix">';
			$strf .='';
			$strf .='<ul class="edit-icon-group fr">';
			$strf .='<li><i class="icon-mod-be-remove js-icon-mod-be-remove del-assembly-asa" data-type="12" data-id="'.$_POST['id'].'"></i></li>';
			$strf .='</ul>';
			$strf .='</div>';
			$strf .='<div class="mod-footer-group">';
			$strf .='<i class="footer-group-img"></i>';
			$strf .='</div>';
			$strf .='</div>';
			$ajax['typef']=$where3['type'];
			$ajax['htmlf']= $strf;
		}
		$where3['type'] = 11;
		$headerinfo =  $this->wass->where($where3)->field('id,type,headlogo,headnavid,headnavstatus,headsearchstatus,headsearchclass,headclasstype,headcolor')->find();
		if($headerinfo){
			$strh .='<div class="inner-mod  mod-top clearfix text-center on a'.$_POST['id'].'">';
			$strh .='<div class="edit-mod-group clearfix">';
			$strh .='';
			$strh .='<ul class="edit-icon-group fr">';
			$strh .='<li><i class="icon-mod-be-remove js-icon-mod-be-remove del-assembly-asa" data-type="11" data-id="'.$_POST['id'].'"></i></li>';
			$strh .='</ul>';
			$strh .='</div>';
			if($headerinfo['headnavstatus']==1){
				$strh .='<i class="icon-menu-btn fl"></i>';
			}
			$strh .='<img class="inline" src="'.$headerinfo['headlogo'].'" style="margin:4px auto;height:38px;">';
			if($headerinfo['headsearchstatus']==1){
				$strh .='<i class="icon-search-btn fr"></i>';
			}
			
			$strh .='</div>';
			$ajax['typeh']=$where3['type'];
			$ajax['htmlh']= $strh;
		}
		echo json_encode($ajax);
	}
	
	/**
	 * 更改排序
	 */
	public function ajaxSort(){
		$id1 = $this->_post("id1");
		$id2 = $this->_post("id2");
		$where1['companyid'] = $where2['companyid'] = $this->companyid;
		$where1['id'] = $id1;
		$data2['sort'] = $sort1 = $this->wass->where($where1)->getField("sort");
		$where2['id'] = $id2;
		$data1['sort'] = $sort2 = $this->wass->where($where2)->getField("sort");
		$res1 = $this->wass->where($where1)->save($data1);
		$res2 = $this->wass->where($where2)->save($data2);
		if($res1&&$res2){
			$ajax['code']=200;
			$ajax['msg'] = "更改排序成功".$id1.'  s '.$sort1.'   2 '.$id2.'   s '.$sort2;;
		}else{
			$ajax['code']=300;
			$ajax['msg'] = "网络繁忙，请稍后重试";
		}
		echo json_encode($ajax);
		
	}
	
	/**
	 * ****************************** 微页面模板设置 *****************************************************************************
	 * @author 姚成凯<kevin@renlaifeng.cn>
	 * @since  2016-4-1
	 */
	public function info(){
		$this->ImagesAll($this->companyid);
		$groupwhere['companyid'] = $where['companyid'] = $this->companyid;
		$this->group = M('wei_list_group')->where($groupwhere)->field('id,title')->order('createtime asc')->select();
	    $where['id']=$this->_get('id');
	    if($where['id']){
	    	$this->makeTopUrl = $this->makeTopUrl_User(array($this->MANAGE_WELCOME,array('name'=>'微页面','url'=>U('Wei/index'),'rel'=>'','target'=>''),array('name'=>'编辑微页面','url'=>'','rel'=>'','target'=>'')));
	    }else{
	    	$this->makeTopUrl = $this->makeTopUrl_User(array($this->MANAGE_WELCOME,array('name'=>'微页面','url'=>U('Wei/index'),'rel'=>'','target'=>''),array('name'=>'新建微页面','url'=>'','rel'=>'','target'=>'')));
	    }
	    $info = $this->wlist->where($where)->field('id,title,shareimg,sharefriendstitle,sharedes,isencrypt,iswechat,encryptinfo,gid,bgcolor')->find();
	    $where2['parentid']=$this->_get('id');
	    $where2['companyid'] = $this->companyid;
	    $info['assembly'] =  $this->wass->where($where2)->order('sort asc,updatetime DESC')->select();
	    //头和底
	    $where3['parentid']=$this->_get('id');
	    $where3['companyid'] = $this->companyid;
	    $where3['type'] = 12;
	    $footerinfo =  $this->wass->where($where3)->field('id,type,footisstatus,footclass')->find();
	    if($footerinfo){
	    	$this->footerinfo=$footerinfo;
	    }else{
	    	$wheref['type'] = -1;
	    	$wheref['parentid']=$this->_get('id');
	    	$info3 =  $this->wass->where($wheref)->field('id,assid,type,footisstatus,footclass')->select();
	    	foreach ($info3 as $val){
	    		$wheref1['parentid']=$val['assid'];
	    		$wheref1['companyid'] = $this->companyid;
	    		$wheref1['type'] = 12;
	    		$info4 = $this->wass->where($wheref1)->find();
	    		if($info4){
	    			$info4['asstype'] = -1;
	    			$info4['id']=$val['id'];
	    			$this->footerinfo=$info4;
	    		}
	    	}
	    }
	    $where3['type'] = 11;
	    $headerinfo =  $this->wass->where($where3)->field('id,type,assid,headlogo,headnavid,headnavstatus,headsearchstatus,headsearchclass,headclasstype,headcolor')->find();
	    if($headerinfo){
	    	$this->headerinfo=$headerinfo;
	    }else{
	    	$whereh['type'] = -1;
	    	$whereh['parentid']=$this->_get('id');
	    	$info4 =  $this->wass->where($whereh)->field('id,assid,type,headlogo,headnavid,headnavstatus,headsearchstatus,headsearchclass,headclasstype,headcolor')->select();
	    	foreach ($info4 as $val){
	    		$whereh1['parentid']=$val['assid'];
	    		$whereh1['companyid'] = $this->companyid;
	    		$whereh1['type'] = 11;
	    		$info5 = $this->wass->where($whereh1)->find();
	    		if($info5){
	    			$info5['asstype'] = -1;
	    			$info5['id']=$val['id'];
	    			$this->headerinfo=$info5;
	    		}
	    	}
	    }
	    if($info['assembly']){
	    	foreach($info['assembly'] as $key=>$val){
	    		if($val['type'] == 6 || $val['type'] == 14){
	    			
	    			//$goodsid = explode(',',$val['goodid']);
	    			//foreach ($goodsid as $key =>$gval){
	    			//	M('mall_goods')->where(array('companyid'=>$this->companyid,'id'=>$gval))->field('id,goodtype,title,saleprice,voucherimgurl')->order('updatetime DESC')->select();
	    			//}
	    			
	    			$info['assembly'][$key]['goods'] = M('mall_goods')->where(array('companyid'=>$this->companyid,'id'=>array('in',$val['goodid'])))->field('id,goodtype,pricetype,title,originalprice,saleprice,voucherimgurl,grouponprice')->order('updatetime DESC')->select();
	    			if($info['assembly'][$key]['goods']){
	    				foreach($info['assembly'][$key]['goods'] as $gkey=>$gval){
	    					if($gval['goodtype'] == 1 || $gval['goodtype'] == 3 || $gval['goodtype'] == 4 || $gval['goodtype'] == 5 || $gval['goodtype'] == 6 || $gval['goodtype'] == 7){
	    						if($gval['goodtype'] == 6 || $gval['goodtype'] == 7){
	    							$info['assembly'][$key]['goods'][$gkey]['grouponprice'] = $gval['grouponprice'];
	    							$info['assembly'][$key]['goods'][$gkey]['originalprice'] = $gval['saleprice'];
	    						}else{
		    						$info['assembly'][$key]['goods'][$gkey]['grouponprice'] = M('mall_goods_sku')->where(array('companyid'=>$this->companyid,'goodid'=>$gval['id']))->order('grouponprice')->getField('grouponprice');
		    						$info['assembly'][$key]['goods'][$gkey]['originalprice'] = M('mall_goods_sku')->where(array('companyid'=>$this->companyid,'goodid'=>$gval['id']))->order('originalprice DESC')->getField('originalprice');
	    						}
	    						$info['assembly'][$key]['goods'][$gkey]['pic'] = M('mall_goods_pics')->where(array('companyid'=>$this->companyid,'goodid'=>$gval['id']))->order('sort')->getField('pic');
	    					}else{
	    						$info['assembly'][$key]['goods'][$gkey]['pic'] = $gval['voucherimgurl'];
	    						$info['assembly'][$key]['goods'][$gkey]['grouponprice'] = $gval['grouponprice'];
	    						$info['assembly'][$key]['goods'][$gkey]['originalprice'] = $gval['saleprice'];
	    					}
    						$info['assembly'][$key]['goods'][$gkey]['groupnum'] = M('mall_groupon_activity')->where(array('companyid'=>$this->companyid,'goodid'=>$gval['id']))->getField('groupnum');
	    				}
	    			}
	    		}
	    		if($val['type'] ==-1 ){
	    			$whereass['parentid']=$val['assid'];
	    			$whereass['companyid'] = $this->companyid;
	    			$whereass['type'] = array("not in",'11,12');
	    			$info['assembly'][$key]['assinfo'] = M("wei_assembly")->where($whereass)->order("sort asc")->select();
	    			foreach($info['assembly'][$key]['assinfo'] as $key2=>$val2){
	    				$info['assembly'][$key]['assinfo'][$key2]['goods'] = M('mall_goods')->where(array('companyid'=>$this->companyid,'id'=>array('in',$val2['goodid'])))->field('id,goodtype,pricetype,title,saleprice,voucherimgurl,originalprice,grouponprice')->order('updatetime DESC')->select();
		    			if($info['assembly'][$key]['assinfo'][$key2]['goods']){
		    				foreach($info['assembly'][$key]['assinfo'][$key2]['goods'] as $gkey=>$gval){
		    					if($gval['goodtype'] == 1 || $gval['goodtype'] == 3 || $gval['goodtype'] == 4 || $gval['goodtype'] == 5 || $gval['goodtype'] == 6 || $gval['goodtype'] == 7){
		    						if($gval['goodtype'] == 6 || $gval['goodtype'] == 7){
		    							$info['assembly'][$key]['assinfo'][$key2]['goods'][$gkey]['grouponprice'] = $gval['grouponprice'];
		    							$info['assembly'][$key]['assinfo'][$key2]['goods'][$gkey]['grouponprice'] = $gval['saleprice'];
		    						}else{
			    						$info['assembly'][$key]['assinfo'][$key2]['goods'][$gkey]['grouponprice'] = M('mall_goods_sku')->where(array('companyid'=>$this->companyid,'goodid'=>$gval['id']))->order('grouponprice')->getField('grouponprice');
			    						$info['assembly'][$key]['assinfo'][$key2]['goods'][$gkey]['grouponprice'] = M('mall_goods_sku')->where(array('companyid'=>$this->companyid,'goodid'=>$gval['id']))->order('originalprice DESC')->getField('originalprice');
		    						}
		    						$info['assembly'][$key]['assinfo'][$key2]['goods'][$gkey]['pic'] = M('mall_goods_pics')->where(array('companyid'=>$this->companyid,'goodid'=>$gval['id']))->order('sort')->getField('pic');
		    					}else{
		    						$info['assembly'][$key]['assinfo'][$key2]['goods'][$gkey]['pic'] = $gval['voucherimgurl'];
		    						$info['assembly'][$key]['assinfo'][$key2]['goods'][$gkey]['grouponprice'] = $gval['grouponprice'];
		    						$info['assembly'][$key]['assinfo'][$key2]['goods'][$gkey]['originalprice'] = $gval['saleprice'];
		    					}
	    						$info['assembly'][$key]['assinfo'][$key2]['goods'][$gkey]['groupnum'] = M('mall_groupon_activity')->where(array('companyid'=>$this->companyid,'goodid'=>$gval['id']))->getField('groupnum');
		    				}
		    			}
	    			}
	    			
	    		}
	    	}
	    }
	    $this->assign('info',$info);
	    //加载网页链接
	    $weiPageList = $this->wlist->where(array('companyid'=>$this->companyid,'type'=>1))->order('createtime desc')->limit(0,$this->limit)->field('id,title')->select();
	    $this->pageCount = $weiPageCount = $this->wlist->where(array('companyid'=>$this->companyid,'type'=>1))->order('createtime desc')->field('id,title')->count();
	    $this->pageList = ceil($weiPageCount/$this->limit);
	    foreach ($weiPageList as $key => $val){
	    	$weiPageList[$key]['url']= C('site_url').U('Wap/Wei/index',array('companyid'=>$this->companyid,'id'=>$val['id']));
	    }
	    if($this->_request('title')){
	    	$where['title']=array('like','%'.$this->_request('title').'%');
	    	$this -> title = $this->_request('title');
	    }
	    
	    $wherea['companyid'] = $this->companyid;
	    $wherea['type']=2;
	    $asslist = $this->wlist->where($wherea)->field('id,title')->order("createtime desc")->select();
	    $this->assign('asslist',$asslist);
	    //dump($list);
	    $this->weiPageList = $weiPageList;
	    $this->publicSelectUrl();
	    $this->display();
	}
	public function definePage(){
		$this->ImagesAll($this->companyid);
		$where['companyid'] = $this->companyid;
		$where['id']=$this->_get('id');
		if($where['id']){
	    	$this->makeTopUrl = $this->makeTopUrl_User(array($this->MANAGE_WELCOME,array('name'=>'微页面','url'=>U('Wei/index'),'rel'=>'','target'=>''),array('name'=>'编辑自定义模块','url'=>'','rel'=>'','target'=>'')));
	    }else{
	    	$this->makeTopUrl = $this->makeTopUrl_User(array($this->MANAGE_WELCOME,array('name'=>'微页面','url'=>U('Wei/index'),'rel'=>'','target'=>''),array('name'=>'新建自定义模块','url'=>'','rel'=>'','target'=>'')));
	    }
		$info = $this->wlist->where($where)->field('id,title,shareimg,sharefriendstitle,sharedes,isencrypt,iswechat,encryptinfo')->find();
		$where2['parentid']=$this->_get('id');
		$where2['companyid'] = $this->companyid;
		$info['assembly'] =  $this->wass->where($where2)->order('sort asc,id DESC')->select();
		$where3['parentid']=$this->_get('id');
		$where3['companyid'] = $this->companyid;
		$where3['type'] = 12;
		$this -> footerinfo =  $this->wass->where($where3)->field('id,type,footisstatus,footclass')->find();
		$where3['type'] = 11;
		$this -> headerinfo =  $this->wass->where($where3)->field('id,type,headlogo,headnavid,headnavstatus,headsearchstatus,headsearchclass,headclasstype,headcolor')->find();
		if($info['assembly']){
			foreach($info['assembly'] as $key=>$val){
				if($val['type'] == 6 || $val['type'] == 14){
					$info['assembly'][$key]['goods'] = M('mall_goods')->where(array('companyid'=>$this->companyid,'id'=>array('in',$val['goodid'])))->field('id,goodtype,pricetype,title,saleprice,voucherimgurl,grouponprice')->order('updatetime DESC')->select();
					if($info['assembly'][$key]['goods']){
						foreach($info['assembly'][$key]['goods'] as $gkey=>$gval){
							if($gval['goodtype'] == 1 || $gval['goodtype'] == 3 || $gval['goodtype'] == 4 || $gval['goodtype'] == 5 || $gval['goodtype'] == 6 || $gval['goodtype'] == 7){
								if($gval['goodtype'] == 6 || $gval['goodtype'] == 7){
									$info['assembly'][$key]['goods'][$gkey]['grouponprice'] = $gval['grouponprice'];
									$info['assembly'][$key]['goods'][$gkey]['originalprice'] = $gval['saleprice'];
								}else{
									$info['assembly'][$key]['goods'][$gkey]['grouponprice'] = M('mall_goods_sku')->where(array('companyid'=>$this->companyid,'goodid'=>$gval['id']))->order('grouponprice')->getField('grouponprice');
									$info['assembly'][$key]['goods'][$gkey]['originalprice'] = M('mall_goods_sku')->where(array('companyid'=>$this->companyid,'goodid'=>$gval['id']))->order('originalprice DESC')->getField('originalprice');
								}
								$info['assembly'][$key]['goods'][$gkey]['pic'] = M('mall_goods_pics')->where(array('companyid'=>$this->companyid,'goodid'=>$gval['id']))->order('sort')->getField('pic');
							}else{
								$info['assembly'][$key]['goods'][$gkey]['pic'] = $gval['voucherimgurl'];
								$info['assembly'][$key]['goods'][$gkey]['grouponprice'] = $gval['grouponprice'];
								$info['assembly'][$key]['goods'][$gkey]['originalprice'] = $gval['saleprice'];
							}
							$info['assembly'][$key]['goods'][$gkey]['groupnum'] = M('mall_groupon_activity')->where(array('companyid'=>$this->companyid,'goodid'=>$gval['id']))->getField('groupnum');
						}
					}
				}
			}
		}
		$this->assign('info',$info);
		//加载网页链接
		$weiPageList = $this->wlist->where(array('companyid'=>$this->companyid,'type'=>1))->order('createtime desc')->limit(0,$this->limit)->field('id,title')->select();
		$this->pageCount = $weiPageCount = $this->wlist->where(array('companyid'=>$this->companyid,'type'=>1))->order('createtime desc')->field('id,title')->count();
		$this->pageList = ceil($weiPageCount/$this->limit);
		foreach ($weiPageList as $key => $val){
			$weiPageList[$key]['url']= C('site_url').U('Wap/Wei/index',array('companyid'=>$this->companyid,'id'=>$val['id']));
		}
		$this->weiPageList = $weiPageList;
		$this->publicSelectUrl();
		$this->display();
	}
	/**
	 * ajax--删除自定义组件
	 * @since  2016-7-27
	 */
	public function ajaxDelAssembly(){
		$return['code'] = 300;
		$return['tips'] = 'error:500';
	
		$id = $this->_post('id');
		$sucAssinfo =  $this->wass->where(array('companyid'=>$this->companyid,'id'=>$id))->find();
		if($sucAssinfo['type']==11){
			$sucAss2 =  M("wei_list_nav")->where(array('companyid'=>$this->companyid,'assid'=>$id))->delete();
		}else{
			$sucAss2 = 1;
		}
		$sucAss =  $this->wass->where(array('companyid'=>$this->companyid,'id'=>$id))->delete();
		if($sucAss){
			$return['code'] = 200;
			$return['tips'] = '操作成功';
		}
		echo json_encode($return);
	}
	
	/**
	 * 二维码
	 * @author Tomas<416369046@qq.com>
	 * @since  2016-7-15
	 */
	public function erweima(){
		$url=base64_decode($this->_get('link'));
		$this->getQRcode($url);
	}
	/**
	 * 数据Eshop和微页面互通。
	 */
	public function ewsqlt(){
		$a=1;$b=1;$c=1;$d=1;$arr='';
		$lists = M("eshop")->select();
		foreach ($lists as $key => $val ){
			
			//页面
			$data = $val;
			$data['id'] = guidNow();
			$data['pv'] = $val['viewnum'];
			$data['ishomepage'] = 1;
			$data['isencrypt'] = 1;
			$data['iswechat'] = 1;
			$data['createtime'] = $data['updatetime'] = time();
			$listHtml = M("wei_list")->add($data);
			if($listHtml){
				$arr[$key]['页面成功'] = $data['id'];
				//组件
				$asslists = M("eshop_assembly")->where(array("companyid"=>$val['companyid']))->select();
				foreach ($asslists as $key2 => $val2 ){
					$data2 = $val2;
					$data2['id'] = guidNow();
					$data2['createtime'] = $data['updatetime'] = time();
					$data2['parentid'] = $data['id'];
					$asshtml = M("wei_assembly")->add($data2);
					if($asshtml){
						$arr[$key]['组件ID'] .= $data2['id'].'   ';
					}else{
						$arr['组件失败'] .= $val2['id'];
					}
				}
				//通头
				$data3['id'] = guidNow();
				$data3['type'] = 11;
				$data3['parentid'] = $data['id'];
				$data3['companyid'] = $val['companyid'];
				$data3['headnavstatus'] = 1;
				$data3['headsearchstatus'] = 1;
				$data3['headsearchclass'] = 1;
				$data3['headlogo'] = $val['logo'];
				$headhtml = M("wei_assembly")->add($data3);
				if($headhtml){
					$arr[$key]['头部导航ID'] .= $data3['id'].'   ';
				}else{
					$arr['导航失败'] .= $val2['id'];
				}
				//导航
				$navlists = M("eshop_class")->where(array("companyid"=>$val['companyid']))->select();
				foreach ($navlists as $navkey => $navval ){
					$nav['id'] = guidNow();
					$nav['assid'] = $data3['id'];
					$nav['companyid'] = $val['companyid'];
					$nav['title'] = $navval['name'];
					$nav['parentid'] = $navval['ptid'];
					$nav['sort'] = $navval['sort'];
					$nav['updatetime'] = time();
					$nav['createtime'] = time();
					$nav['url'] = U('Wap/MallTagsSearch/lists',array('companyid'=>$val['companyid'],'id'=>$navval['id']));
					$navres = M("wei_list_nav")->add($nav);
					if($navres){
						$navid .=$nav['id'].",";
					}
				}
				//设置通头的导航ID
				M("wei_assembly")->where(array("id"=>$data3['id']))->save(array("headnavid"=>$navid));
				//通低
				$data4['id'] = guidNow();
				$data4['type'] = 12;
				$data4['companyid'] = $val['companyid'];
				$data4['parentid'] = $data['id'];
				$data4['footisstatus'] = 1;
				$data4['footclass'] = 1;
				$foothtml = M("wei_assembly")->add($data4);
				if($foothtml){
					$arr[$key]['底部组件'] .= $data4['id'].'   ';
				}else{
					$arr['底部组件失败'] .= $val2['id'];
				}
			}else{
				$arr['页面失败'] .= $val['id'].'<br />';
			}
		}
		dump($arr);
	}
}
?>