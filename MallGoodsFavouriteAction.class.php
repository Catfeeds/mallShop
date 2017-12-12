<?php
/**
 * 我的收藏
 * Enter description here ...
 * @author yaochengkai
 *
 */
class MallGoodsFavouriteAction extends WapBaseAction{
	
	private $mid;
	
	private $companyid;
	
	public function __construct(){
		parent::__construct();
		$this->mid = session('mid'.session('wapcid'));
		$this->companyid = session('wapcid');
	}
	/**
	 * 我的收藏
	 */
	public function index(){
		$this->checkMemberLogin();
		$this->setPageTitle(array('title'=>'我的收藏'));
		$limit = '10'; 
		$count = M()->table('tp_mall_goods_favourite AS fav')->join(array('LEFT JOIN tp_mall_goods AS good ON good.id=fav.goodid'))->where(array('fav.companyid'=>$this->companyid,'fav.mid'=>$this->mid))->count();
		$list = M()->table('tp_mall_goods_favourite AS fav')->join(array('LEFT JOIN tp_mall_goods AS good ON good.id=fav.goodid'))->where(array('fav.companyid'=>$this->companyid,'fav.mid'=>$this->mid))->field('fav.id,fav.goodid,good.title,good.goodtype,good.pricetype,good.saleprice,good.intprice,good.vouchertype,good.vouchersid')->order('fav.id DESC')->limit($limit)->select();
		if($list){
			foreach($list as $key=>$val){
				if($val['goodtype'] == 1){
					$list[$key]['pic'] = M('mall_goods_pics')->where(array('companyid'=>$this->companyid,'goodid'=>$val['goodid']))->order('sort')->limit('0,1')->getField('pic');
				}else{
					$list[$key]['pic'] = M('member_marketing_activities_voucher_info')->where(array('companyid'=>$this->companyid,'id'=>$val['vouchersid']))->field('parvalue,minparvalue,maxparvalue,israndom')->find();
				}
			}
		}
		$pages = ceil($count/$limit);
		$this->assign('pages',$pages);
		$this->assign('list',$list);
		$this->display();
	}
	/**
	 * 显示更多-->ajax
	 */
	public function getMoreList(){
		$maxpage = $this->_post('maxpage');
		$page = $this->_post('page')+1;
		$ajaxReturn['code'] = 300;
		$ajaxReturn['html'] = '';
		$ajaxReturn['page'] = '1';
		$ajaxReturn['isshow'] = '1';
		$list = M()->table('tp_mall_goods_favourite AS fav')->join(array('LEFT JOIN tp_mall_goods AS good ON good.id=fav.goodid'))->where(array('fav.companyid'=>$this->companyid,'fav.mid'=>$this->mid))->field('fav.id,fav.goodid,good.title,good.goodtype,good.pricetype,good.saleprice,good.intprice,good.vouchertype,good.vouchersid,good.isopenvipprice')->order('fav.id DESC')->page($page,10)->select();
		if($list){
			foreach($list as $key=>$val){
				if($val['goodtype'] == 1){
					$pic = M('mall_goods_pics')->where(array('companyid'=>$this->companyid,'goodid'=>$val['goodid']))->order('sort')->limit('0,1')->getField('pic');
					if($val['isopenvipprice'] == 1){
						$vipdis = M()->table('tp_member_card_info AS info')->join(array('LEFT JOIN tp_mall_goods_rank_discount AS dis ON dis.rankid=info.rankid'))->where(array('info.companyid'=>$this->companyid,'info.mid'=>$this->mid,'dis.goodsid'=>$val['id']))->getField('vipdiscount');
						if(0< $vipdis && $vipdis <= 1){
							$val['saleprice'] = format_number($val['saleprice']*$vipdis);
							$val['intprice'] = format_number($val['intprice']*$vipdis);
						}
					}
					$ajaxReturn['html'] .='<li><a href="'.U('MallGoods/goodInfo',array('id'=>$val['goodid'],'companyid'=>$this->companyid)).'"><div class="pro-img"><img class="scrollLoading" src="'.$pic.'" /></div><article class="pro-txt"><h2 class="pro-tit">'.$val['title'].'</h2><p class="pro-price">';
          			if($val['pricetype'] == 1){
	       				$ajaxReturn['html'] .='<span>￥'.$val['saleprice'].'</span>';
	       			}elseif ($val['pricetype'] == 2){
	       				$ajaxReturn['html'] .='<span>'.$val['intprice'].'积分</span>';
	       			}
					$ajaxReturn['html'] .='</p></article></a><article class="por-sundry"><span class="delFavourite pro-delete" data-id="'.$val['id'].'">删除 <i class="english">Delete</i></span></article></li>';
				}else{
					$pic = M('member_marketing_activities_voucher_info')->where(array('companyid'=>$this->companyid,'id'=>$val['vouchersid']))->field('parvalue,minparvalue,maxparvalue,israndom')->find();
					if($val['isopenvipprice'] == 1){
						$vipdis = M()->table('tp_member_card_info AS info')->join(array('LEFT JOIN tp_mall_goods_rank_discount AS dis ON dis.rankid=info.rankid'))->where(array('info.companyid'=>$this->companyid,'info.mid'=>$this->mid,'dis.goodsid'=>$val['id']))->getField('vipdiscount');
						if(0< $vipdis && $vipdis <= 1){
							$val['saleprice'] = format_number($val['saleprice']*$vipdis);
							$val['intprice'] = format_number($val['intprice']*$vipdis);
						}
					}
					$ajaxReturn['html'] .='<li><a href="'.U('MallGoods/goodInfo',array('id'=>$val['goodid'],'companyid'=>$this->companyid)).'"><div class="pro-img">';
          			if($val['vouchertype'] == 1){
		       			$ajaxReturn['html'] .='<article class="virtual-ticket coupon-bg"><aside class=""><img src="';if(session('clogo')){$ajaxReturn['html'] .=session('clogo');}else{$ajaxReturn['html'] .='./Tpl/User/Default/common/images/home/default-share.jpg';}
		       			$ajaxReturn['html'] .='" /><p class="face-value">¥<strong>'.$pic['israndom']==1 ? $pic['minparvalue'].' ~ '.$pic['maxparvalue'] : $pic['parvalue'].'</strong></p><p class="label">优惠券 <i class="english">Coupon</i></p></aside></article>';
	       			}elseif($val['vouchertype'] == 2){
		       			$ajaxReturn['html'] .='<article class="virtual-ticket gift-bg"><aside class=""><img src="';if(session('clogo')){ $ajaxReturn['html'] .=session('clogo');}else{ $ajaxReturn['html'] .='./Tpl/User/Default/common/images/home/default-share.jpg';}
		       			$ajaxReturn['html'] .='" /><p class="label">赠品 <i class="english">Gift Voucher</i></p></aside></article>';
					}elseif($val['vouchertype'] == 3){
		       			$ajaxReturn['html'] .='<article class="virtual-ticket prepaid-bg"><aside class=""><img src="';if(session('clogo')){ $ajaxReturn['html'] .=session('clogo');}else{ $ajaxReturn['html'] .='./Tpl/User/Default/common/images/home/default-share.jpg';}
		       			$ajaxReturn['html'] .='" /><p class="face-value">¥<strong>'.$pic['parvalue'].'</strong></p><p class="label">充值卡 <i class="english">Prepaid card</i></p></aside></article>';
	       			}
          			$ajaxReturn['html'] .='</div><article class="pro-txt"><h2 class="pro-tit">'.$val['title'].'</h2><p class="pro-price">';
          			if($val['pricetype'] == 1){
	       				$ajaxReturn['html'] .='<span>￥'.$val['saleprice'].'</span>';
	       			}elseif ($val['pricetype'] == 2){
	       				$ajaxReturn['html'] .='<span>'.$val['intprice'].'积分</span>';
	       			}
          			$ajaxReturn['html'] .='</p></article></a><article class="por-sundry"><span class="delFavourite pro-delete" data-id="'.$val['id'].'">删除 <i class="english">Delete</i></span></article></li>';
				}
			}
			$ajaxReturn['code'] = 200;
			$ajaxReturn['page'] = $page;
		}
		if($maxpage <= $page){
			$ajaxReturn['isshow'] = '2';
		}
		echo json_encode($ajaxReturn);
	}
	/**
	 * 删除收藏  --> ajax
	 */
	public function delFavourite(){
		$id = $this->_post('id');
		$return = M('mall_goods_favourite')->where(array('companyid'=>$this->companyid,'id'=>$id))->delete();
		if($return){
			$ajaxReturn['code'] = '200';
			$ajaxReturn['tpis'] = '删除成功';
		}else{
			$ajaxReturn['code'] = '300';
			$ajaxReturn['tpis'] = '删除失败';
		}
		echo json_encode($ajaxReturn);
	}
	/**
	 * 收藏 --> ajax
	 */
	public function addMemberGoodsFavourite(){
		C('TOKEN_ON',false);
		$gid = $this->_post('gid');
		$ajaxReturn['code'] = '300';
		$ajaxReturn['tpis'] = '系统繁忙，请稍后重试';
		if($gid){
			if($this->ajaxCheckMemberLogin()){
				$count = M('mall_goods_favourite')->where(array('companyid'=>$this->companyid,'mid'=>$this->mid,'goodid'=>$gid))->count();
				if($count){
					$deleteReturn = M('mall_goods_favourite')->where(array('companyid'=>$this->companyid,'mid'=>$this->mid,'goodid'=>$gid))->delete();
				}else{
					$add['companyid'] = $this->companyid;
					$add['mid'] = $this->mid;
					$add['goodid'] = $gid;
					$addReturn = M('mall_goods_favourite')->add($add);
				}
				if($deleteReturn){
					$ajaxReturn['code'] = '202';
					$ajaxReturn['tpis'] = '商品取消收藏成功';
				}
				if($addReturn){
					$ajaxReturn['code'] = '200';
					$ajaxReturn['tpis'] = '商品添加收藏成功';
				}
			}else{
				$ajaxReturn['code'] = '201';
			}
		}
		echo json_encode($ajaxReturn);
	}
}
?>