<?php
/**
 * 
 * 卡券核销
 * 
 * @author    Leo<1251868177@qq.com>
 * @since     2016-7-29
 * @version   1.0
 */
class UseVouchersAction extends UserAction{
	
	private $companyid;
	
	public function __construct(){
		parent::__construct();
		$this->checkCompanyScrm5Permissions(77,TRUE);
		$this->companyid = session('cid');
	}
	/**
	 * 
	 * 卡券核销历史
	 * 
	 * @author Leo<1251868177@qq.com>
	 * @since  2016-7-29
	 */
	public function index(){
        $vouchernumber = $this->_request('vouchernumber');   // 券号
        if($vouchernumber){
            $where['vouchernumber'] = array('LIKE','%'.$vouchernumber.'%');
            $this->assign('vouchernumber',$vouchernumber);   
        }
        $mobile = $this->_request('mobile');   // 会员手机号
        if($mobile){
            $where['mobile'] = array('LIKE','%'.$mobile.'%');
            $this->assign('mobile',$mobile);
        }
        $vouchertype = $this->_request('vouchertype');  // 券类型
        if($vouchertype){
            $where['vouchertype'] = $vouchertype;
            $this->assign('vouchertype',$vouchertype);
        }
        $staffname = $this->_request('staffname');   // 处理人
        if($staffname){
            $where['staffname'] = $staffname;
            $this->assign('staffname',$staffname);
        }
        $vouchername = $this->_request('vouchername');  // 券名称
        if($vouchername){
            $where['vouchername'] = array('LIKE','%'.$vouchername.'%');
            $this->assign('vouchername',$vouchername);
        }
        $shopid = $this->_request('shopid');  // 所属门店
        if($shopid){
            $where['shopid'] = $shopid;
            $this->assign('shopid',$shopid);
        }
        // 核销时间
        $usetime1 = $this->_request('usetime1');
        $usetime2 = $this->_request('usetime2');
        if($usetime1){
            $usetimeWhere[] = array('egt',strtotime($usetime1.' 00:00:00'));
            $this->assign('usetime1',$usetime1);
        }
        if($usetime2){
            $usetimeWhere[] = array('elt',strtotime($usetime2.' 23:59:59'));
            $this->assign('usetime2',$usetime2);
        }
        if($usetime1 || $usetime2){
            $where['usetime'] = $usetimeWhere;
        }
	    $where['companyid'] = $this->companyid;
	    // $count = M()->table('tp_use_vouchers AS vouchers')->join('tp_company_shops AS shops ON vouchers.shopid=shops.id')->where($where)->count();
	    $count = M('use_vouchers')->where($where)->count();
	    $page = new NewPage($count,15);
	    // $list = M()->table('tp_use_vouchers AS vouchers')->join('tp_company_shops AS shops ON vouchers.shopid=shops.id')->where($where)->field('vouchers.id,vouchers.vouchernumber,vouchers.vouchertype,vouchers.vouchername,vouchers.utility,vouchers.mobile,vouchers.usetime,vouchers.staffname,vouchers.shopid,vouchers.type,shops.shopname,shops.name')->limit($page->firstRow.','.$page->listRows)->order('usetime DESC')->select();
	    $list = M('use_vouchers')->where($where)->field('id,mid,shopname,vouchernumber,vouchertype,vouchername,utility,mobile,usetime,staffname,shopid,type,singleprice')->limit($page->firstRow.','.$page->listRows)->order('usetime DESC,updatetime DESC')->select();
	    $this->assign('page',$page->show());
	    $this->assign('list',$list);
	    // 处理人
	    $nameList = M('use_vouchers')->field('staffname')->where(array('companyid'=>$this->companyid))->field('staffname')->group('staffname')->select();
	    $this->assign('nameList',$nameList);
	    // 所属门店
	    $shopList = M('use_vouchers')->where(array('companyid'=>$this->companyid))->field('shopid,shopname')->group('shopid')->select();
	    $this->makeTopUrl = $this->makeTopUrl_User(array($this->SCENE_WELCOME,array('name'=>'门店收银','url'=>'','rel'=>'','target'=>''),array('name'=>'卡券核销历史','url'=>'','rel'=>'','target'=>'')));
	    $this->assign('shopList',$shopList);
	    $this->display();    
	}
	/**
	 *
	 * 导出CSV
	 *
	 * @author Mark<1311013341@qq.com>
	 * @since  2016-9-29
	 */
	public function exportExcel(){
		$vouchernumber = $this->_request('vouchernumber');   // 券号
        if($vouchernumber){
            $where['vouchernumber'] = array('LIKE','%'.$vouchernumber.'%');
        }
        $mobile = $this->_request('mobile');   // 会员手机号
        if($mobile){
            $where['mobile'] = array('LIKE','%'.$mobile.'%');
        }
        $vouchertype = $this->_request('vouchertype');  // 券类型
        if($vouchertype){
            $where['vouchertype'] = $vouchertype;
        }
        $staffname = $this->_request('staffname');   // 处理人
        if($staffname){
            $where['staffname'] = $staffname;
        }
        $vouchername = $this->_request('vouchername');  // 券名称
        if($vouchername){
            $where['vouchername'] = array('LIKE','%'.$vouchername.'%');
        }
        $shopid = $this->_request('shopid');  // 所属门店
        if($shopid){
            $where['shopid'] = $shopid;
        }
        // 核销时间
        $usetime1 = $this->_request('usetime1');
        $usetime2 = $this->_request('usetime2');
        if($usetime1){
            $usetimeWhere[] = array('egt',strtotime($usetime1.' 00:00:00'));
        }
        if($usetime2){
            $usetimeWhere[] = array('elt',strtotime($usetime2.' 23:59:59'));
        }
        if($usetime1 || $usetime2){
            $where['usetime'] = $usetimeWhere;
        }
	    $where['companyid'] = $this->companyid;
	    // $list = M()->table('tp_use_vouchers AS vouchers')->join('tp_company_shops AS shops ON vouchers.shopid=shops.id')->where($where)->field('vouchers.id,vouchers.vouchernumber,vouchers.vouchertype,vouchers.vouchername,vouchers.utility,vouchers.mobile,vouchers.usetime,vouchers.staffname,vouchers.shopid,vouchers.type,shops.shopname,shops.name')->order('usetime DESC')->select();
	    $list = M('use_vouchers')->where($where)->field('id,shopname,vouchernumber,vouchertype,vouchername,utility,mobile,usetime,staffname,singleprice,shopid,type')->order('usetime DESC')->select();
	    $content = "卡券号,卡券类型,卡券名称,单次价格,会员手机号,核销时间,处理人,收银渠道,处理人所属门店\r\n";
		if($list){
			foreach($list as $key=>$val){
				$content .= '"'.$val['vouchernumber'].'",';
				if($val['vouchertype'] == '2'){
					$content .= "线下优惠券,";
				}elseif($val['vouchertype'] == '3'){
					$content .= "计次卡券,";
				}elseif($val['vouchertype'] == '4'){
					$content .= "团购券,";
				}elseif($val['vouchertype'] == '5'){
					$content .= "门票,";
				}elseif($val['vouchertype'] == '7'){
					$content .= "eshop优惠券,";
				}elseif($val['vouchertype'] == '8'){
					$content .= "门店使用优惠券,";
				}elseif($val['vouchertype'] == '9'){
					$content .= "兑换券,";
				}elseif($val['vouchertype'] == '10'){
					$content .= "红包,";
				}elseif($val['vouchertype'] == '40'){
					$content .= "微信互通券,";
				}else{
					$content .= "-,";
				}
				$content .= '"'.$val['vouchername'].'",';
				if($val['singleprice'] != '0.00'){
					$content .= '"'.$val['singleprice'].'",';
				}else{
					$content .= "/,";
				}
				$content .= '"'.$val['mobile'].'","'.format_time($val['usetime'],'ymdhis').'","'.$val['staffname'].'",';
				if($val['type'] == '1'){
					$content .= "风助手微信,";
				}elseif($val['type'] == '2'){
					$content .= "风助手POS+,";
				}else{
					$content .= "-,";
				}
				$content .= '"'.$val['shopname'].'"';
				$content .= "\r\n";
			}
		}
		$date = date("YmdHis",time());
		$fileName .= '卡券核销历史'."_{$date}.csv";
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
	 * 
	 * 订单数据处理
	 * 
	 * @author Leo<1251868177@qq.com>
	 * @since  2016-11-11
	 */
	public function orderDataSave(){
	    $lists = M('use_vouchers')->field('id,companyid,shopid,shopname')->select();
	    if($lists){
	        $time = time();
	        foreach($lists as $key=>$val){
	            $id = $val['id'];
	            $companyid = $val['companyid'];
	            $shopid = $val['shopid'];
	            $shopname = $val['shopname'];
	            if($shopid == '-1'){
	                $data['shopname'] = '总部';
	            }else{
	                if($shopid){
    	                $info = M('company_shops')->where(array('companyid'=>$companyid, 'id'=>$shopid))->field('id,shopname,name')->find();
    	                if($info){
    	                    $data['shopname'] = $info['shopname']?$info['shopname']:$info['name'];
    	                }
	                }
	            }
	            $data['updatetime'] = $time;
	            $where['id'] = $id;
	            $where['companyid'] = $companyid;
	            M('use_vouchers')->where($where)->save($data);
	            unset($id,$companyid,$shopid,$shopname,$data,$where);
	        }
	    }
        echo 'success';
	}
}
?>