<?php
/**
 * 商品列表/详情页
 * Enter description here ...
 * @author yaochengkai
 *
 */
class MallGoodsAction extends WapBaseAction{
	
	private $mid;
	
	private $companyid;
	
	private $homeInfo;
	
	public function __construct(){
		parent::__construct();
		$this->mid = session('mid'.session('wapcid'));
		$this->companyid = session('wapcid');
		$this->homeInfo['tplid'] = 1;
		/* $this->homeInfo = D('Mall_home')->where(array('companyid'=>$this->companyid))->field('id,title,tplid,sharefriendstitle,sharedes,shareimg')->find();
		if(!$this->homeInfo){
			$this->redirect(U('System/notFound',array('companyid'=>$this->companyid)));
		} */
	}
    public function lists()
    {
        $where['isoffshelves'] = '2';
        $where['issoldout'] = '2';
        $lists = M("mall_goods")->where($where)->order("sort asc,updatetime desc")->select();
        $this->info = M("eshop_index")->where(array("id"=>1))->find();
        $this->assign("lists",$lists);
        $this->display();
    }
    public function Listss()
    {
        $where['isoffshelves'] = '2';
        $where['issoldout'] = '2';
        $lists = M("mall_goods")->where($where)->select();
        $this->info = M("eshop_index")->where(array("id"=>1))->order("update_time desc")->find();
        $this->assign("lists",$lists);
        $this->display();
    }
	/**
	 * 商品输入值与库存作对比
	 * @author 姚成凯<kevin@renlaifeng.cn>
	 * @since  2015-7-29
	 */
	public function inputGoodMax(){
		$id = $this->_post('id');
		$goodtype = $this->_post('goodtype');
		$skuId = $this->_post('skuId');
		$num = $this->_post('num');
		if($goodtype == 1 || $goodtype == 3 || $goodtype == 4 || $goodtype == 5){
			$stockamount = M('mall_goods_sku')->where(array('companyid'=>$this->companyid,'id'=>$skuId,'goodid'=>$id))->getField('stockamount');
		}elseif($goodtype == 2 || $goodtype == 6 || $goodtype == 7){
			$stockamount = M('mall_goods')->where(array('companyid'=>$this->companyid,'id'=>$id))->getField('stockamount');
		}
		if($num > $stockamount){
			$ajaxReturn['code'] = 300;
			$ajaxReturn['tpis'] = '商品数量超过库存';
		}else{
			$ajaxReturn['code'] = 200;
		}
		echo json_encode($ajaxReturn);
	}
	
	/**
	 * SCRM5商品详情页
	 * @author Thomas<416369046@qq.com>
	 * @since  2017-1-16
	 */
	public function goodInfo(){
		$shareopenid = $this->_get('shareopenid');
		$id = $this->_get('id');
		session('historyUrl',U('Wap/MallGoods/goodInfo',array('id'=>$id,'shareopenid'=>$shareopenid,'companyid'=>$this->companyid)));
		//获取openid(用于发送提醒的消息模板)
		if(!session('openid'.$this->companyid)){
		    $this->checkMemberAutoLogin();
		}
		// 接收分享链接传递过来的shareopenid 并生产session
		session('shareopenid',$shareopenid);
		// 通过分享过来的shareopenid获取分享用户的销售等级
		$memberInfo = M()->table('tp_member_register_info as rinfo')->join(array("LEFT JOIN tp_member_wechat_info as winfo ON winfo.mid = rinfo.id"))->where(array('winfo.openid'=>$shareopenid))->field('rinfo.salestype')->find();
		if($memberInfo['salestype']){
			session('salestype',$memberInfo['salestype']);
		}
		$where['id'] = $id;
		$where['companyid'] = $this->companyid;
		$where['isoffshelves'] = 2;
		$info = M('mall_goods')->where($where)->field('id,salenum,companyid,goodtype,isoffshelves,issoldout,title,pricetype,originalprice,saleprice,grouponprice,isopenvipprice,intprice,stockamount,canbuynum,goodnum,info,shareimg,sharefriendstitle,sharedes,freighttype,freighttplid')->find();
		if($info){
			// 运费
			// 获取运费模板的最高与最低运费假
			$info['frieghtLargestPrice'] = M('mall_freight_tpl_child')->where(array('companyid'=>$this->companyid,'tplid'=>$info['freighttplid']))->field('firstpiece')->order('firstpiece desc')->find();
			$info['frieghtSmallestPrice'] = M('mall_freight_tpl_child')->where(array('tplid'=>$info['freighttplid']))->field('firstpiece')->order('firstpiece asc')->find();
			
			//商品图片以及SKU
			$info['pic'] = M('mall_goods_pics')->where(array('goodid'=>$id))->field('pic')->order('sort,createtime DESC')->select();
			if($info['goodtype'] == 1 ){
				$info['sku'] = M('mall_goods_sku')->where(array('goodid'=>$id))->field('id,name,originalprice,saleprice,grouponprice,intprice,stockamount,imgurl')->order('sort')->select();
				$info['totalstock'] = M('mall_goods_sku')->where(array('goodid'=>$id))->sum('stockamount');
				$info['saleprice'] = M('mall_goods_sku')->where(array('goodid'=>$info['id']))->min('saleprice');
			}
			if(!$info['shareimg']){
				$info['shareimg'] = $info['pic'][0]['pic']; //分享图片
			}
			
			if($this->mid){
				$info['favourite'] = M('mall_goods_favourite')->where(array('companyid'=>$this->companyid,'mid'=>$this->mid,'goodid'=>$id))->count();
			}
			$this->setPageTitle(array('title'=>$info['title']));
			if(!$info['sharefriendstitle']){
				$info['sharefriendstitle'] = $info['title'];   // 分享标题
			}
			if(!$info['sharedes']){
				$info['sharedes'] = get_substr(get_text($info['info']), 100);  // 分享描述
			}
			if($info['sharefriendstitle']==''){
				$info['sharefriendstitle']=' ';
			}
			if($info['shareimg']==''){
				$info['shareimg'] = 'http://www.mobiwind.cn/Tpl/Wap/default/common/img/common.jpg';
			}else{
				if(strpos($info['shareimg'], C('site_url')) === false){
					$info['shareimg'] = C('site_url').$info['shareimg'];
				}
			}
			if($info['sharedes']==''){
				$info['sharedes']= ' ';
			}
			$this->assign('isshowsystemdiymen','2');//隐藏自定义菜单
			//viewnum 浏览量自增
			$savereturn = M('mall_goods')->where($where)->setInc('viewnum');
		}
        $res = 1;
		if($this->mid){
            $indexVoucherInfo = M("vouchers")->where(array("is_index"=>1))->find();
            if($indexVoucherInfo)
            $res = M("member_vouchers")->where(array("mid"=>$this->mid,"is_index"=>1))->count();
        }
        $this->assign('info',$info);
        $this->assign('indexVoucher',$res);
        $this->assign('indexVoucherInfo',$indexVoucherInfo);
		$this->display();
	}

	public function addVoucher()
    {
        $indexVoucherInfo = M("vouchers")->where(array("is_index"=>1))->find();
        $data['mid'] = $this->mid;
        $data['sn'] = $indexVoucherInfo['sn'];
        $data['title'] = $indexVoucherInfo['title'];
        $data['end_time'] = $indexVoucherInfo['end_time'];
        $data['type'] = $indexVoucherInfo['type'];
        $data['is_index'] = $indexVoucherInfo['is_index'];
        $data['full'] = $indexVoucherInfo['full'];
        $data['reduce'] = $indexVoucherInfo['reduce'];
        $data['status'] = 2;
        $data['createtime'] = $data['updatetime'] = time();
        M("member_vouchers")->add($data);
    }
	/**
	 * SCRM5
	 * ajax获取地址列表信息
	 * @author Thomas<416369046@qq.com>
	 * @since  2017-1-17
	 */
	public function ajaxShopInfo(){
		$where['companyid'] = $this->companyid;
		$where['id'] = $this->_post('id');
		$companyShopsInfo = M('Company_shops')->where($where)->field('id,companyid,tel,name,shopname,address,logourl,latitude,longitude,isopenmobilebook')->find();
		if($companyShopsInfo){
			$tudeInfo = get_distance($companyShopsInfo['latitude'],$companyShopsInfo['longitude'],$_POST['lat'],$_POST['lng']);
			$tudeInfo2 = distance($tudeInfo);
			if($tudeInfo){
				$companyShopsInfo['julim'] = $tudeInfo;
				$companyShopsInfo['juli'] = $tudeInfo2;
			}
			$tudeInfo = '';
			$companyShopsInfo = arraySort($companyShopsInfo, 'julim','SORT_ASC');
		}
		$str = '';
		$str .= '<div class="distance-box"><div class="distance-left"><h6>'.$companyShopsInfo['shopname'].'</h6><p>'.$companyShopsInfo['address'].'</p> <a href="tel:'.$companyShopsInfo['tel'].'">'.$companyShopsInfo['tel'].'</a></div><p class="distance-number">'.$companyShopsInfo['juli'].'</p></div>';
		if($str){
			$ajax['code'] = 200;
			$ajax['msg'] = $str;
		}else{
			$ajax['code'] = 300;
			$ajax['msg'] = '搜索不到门店信息';
		}
		echo json_encode($ajax);
	}
}
?>