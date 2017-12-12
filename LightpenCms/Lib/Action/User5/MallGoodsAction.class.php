<?php
/**
 * 商品管理
 * @author 徐建鹏
 * @since  2017-10-31
 */
class MallGoodsAction extends UserAction{
	
	private $uid;
	
	private $companyid;
	
	private $shopsid;
	
	private $mallGoodsModel;
	
	public function _initialize(){
		parent::_initialize();
		$this->uid = session('uid');
		$this->shopsid = session('shopsid');
		$this->companyid = 1;//session('cid');
		$this->mallGoodsModel=D('Mall_goods');
	}
	/**
	 * 商品管理
	 * @author 徐建鹏
	 * @since  2017-10-31
	 */
	public function index(){
		$this->makeTopUrl = $this->makeTopUrl_User(array($this->MANAGE_WELCOME,array('name'=>'商品管理','url'=>U('MallGoods/index',array('isoffshelves'=>2,'issoldout'=>2)),'rel'=>'','target'=>'')));
		$this->check_url = 'mallgoodsindex';
		$where['companyid'] = $this->companyid;
		$where['pricetype'] = 1;
		$isoffshelves = $this->_request('isoffshelves');
		if($isoffshelves){
			$where['isoffshelves'] = $isoffshelves;
			$this->assign('isoffshelves',$isoffshelves);
		}
		$issoldout = $this->_request('issoldout');
		if($issoldout){
			$where['issoldout'] = $issoldout;
			$this->assign('issoldout',$issoldout);
		}
		$goodtype = $this->_request('goodtype');
		if($goodtype){
			$where['goodtype'] = $goodtype;
			$this->assign('goodtype',$goodtype);
		}
		$title = $this->_request("title");
		if($title){
			$where['title'] = array('like','%'.$title.'%');
			$this->assign('title',$title);
		}
		$goodnum = $this->_request("goodnum");
		if($goodnum){
			$where['goodnum'] = array('like','%'.$goodnum.'%');
			$this->assign('goodnum',$goodnum);
		}
		$ordertype = $this->_request('ordertype'); //排序类型 1：库存；2：销量；3：PV；4：更新时间；
		$orderclass = $this->_request('orderclass'); //排序属性 1：正序；2：倒序；
		if($ordertype == 1){
			if($orderclass=='' || $orderclass==1){
				$order = 'stockamount';
				$this->assign('orderclass','2');
			}elseif($orderclass == 2){
				$order = 'stockamount DESC';
				$this->assign('orderclass','1');
			}
		}elseif($ordertype == 2){
			if($orderclass=='' || $orderclass==3){
				$order = 'salenum';
				$this->assign('orderclass2','4');
			}elseif($orderclass == 4){
				$order = 'salenum DESC';
				$this->assign('orderclass2','3');
			}
		}elseif($ordertype == 3){
			if($orderclass=='' || $orderclass==5){
				$order = 'viewnum';
				$this->assign('orderclass3','6');
			}elseif($orderclass == 6){
				$order = 'viewnum DESC';
				$this->assign('orderclass3','5');
			}
		}elseif($ordertype == 4){
			if($orderclass=='' || $orderclass==7){
				$order = 'updatetime';
				$this->assign('orderclass4','8');
			}elseif($orderclass == 8){
				$order = 'updatetime DESC';
				$this->assign('orderclass4','7');
			}
		}else{
			$order = 'sort asc,updatetime DESC';
		}
		$this->assign('ordertype',$ordertype);
		$count = M('mall_goods')->where($where)->count();
		$page = new NewPage($count,15);
		$list = M('mall_goods')->where($where)->field('id,title,goodnum,saleprice,stockamount,salenum,viewnum,updatetime,goodtype,sort')->order($order)->limit($page->firstRow.','.$page->listRows)->select();
		if($list){
			foreach($list as $key=>$val){
				$list[$key]['saleprice'] = M('mall_goods_sku')->where(array('companyid'=>$this->companyid,'goodid'=>$val['id']))->min('saleprice');
			}
		}
		$this->assign('list',$list);
		$this->assign('page',$page->show());
		$this->display();
	}
	/**
	 * 创建/编辑实物商品
	 * @author 徐建鹏
	 * @since  2017-10-31
	 */
	public function set(){
		if(IS_POST){
			$return['code'] = 'error';
			$return['tips'] = '操作失败';
			$timeAll = time();
			$goodsSave = 1;
			$id = $this->_post('id');
			$type = $this->_post('goodtype');
			if($type == 1){
				if($type == 1){
					// 商品SKU
					$skutring = explode(";", substr(htmlspecialchars_decode($_POST['skutring']),0,-1));
					foreach($skutring as $key=>$val){
						$skutring[$key] = explode(",", $val);
						foreach($skutring[$key] as $key2=>$val2){
							$skutring[$key][$key2] = explode("|", $val2);
						}
					}
					$skutringNum = count($skutring);
				}
				// 商品图片
				$imgtring = explode(";", substr(htmlspecialchars_decode($_POST['imgtring']),0,-1));
				foreach($imgtring as $key=>$val){
					$imgtring[$key] = explode(",", $val);
					foreach($imgtring[$key] as $key2=>$val2){
						$imgtring[$key][$key2] = explode("|", $val2);
					}
				}
				$imgtringNum = count($imgtring);
			}
			$_POST['isoffshelves'] = $_POST['isoffshelves'] ? $_POST['isoffshelves'] : 1;
			//商品是否售罄1：是；2：否；默认：2；
            $_POST['issoldout'] = 2;
			$_POST['updatetime'] = $timeAll;
			if($id){
				//关联标签，品类管理自定义排序
				$tagsArr = explode(',', substr($_POST['tags'],1,-1));
				if($tagsArr){
					M('mall_tags_goods_link')->where(array('companyid'=>$this->companyid,'goodid'=>$id))->delete();
					foreach($tagsArr as $val){
						$dataTag['id'] = guidNow();
						$dataTag['companyid'] = $this->companyid;
						$dataTag['tagid'] = $val;
						$dataTag['goodid'] = $id;
						$dataTag['updatetime'] = $dataTag['createtime'] = $timeAll;
						M('mall_tags_goods_link')->add($dataTag);
					}
				}
				if($type == 1){
					if($type == 1){
						// 编辑商品SKU
						$skuId = M('mall_goods_sku')->where(array('companyid'=>$this->companyid,'goodid'=>$id))->field('id')->select();
						foreach($skuId as $key=>$val){
							$skuIdes[] = $val['id'];
						}
						$stockamount = 0;
						$stockamount2 = 0;
						$stockamount3 = 0;
						$salenum = $salenum2 = $salenum3 = 0;
						$skuids = ',';
						for($i=0; $i<$skutringNum; $i++){
							if(in_array($skutring[$i][0][1],$skuIdes)){
								$dataSku['companyid'] = $this->companyid;
								$dataSku['goodid'] = $id;
								$dataSku['name'] = $skutring[$i][1][1];
								$dataSku['originalprice'] = $skutring[$i][2][1];
								$dataSku['saleprice'] = $skutring[$i][3][1];
								$dataSku['imgurl'] = $skutring[$i][4][1];
								$dataSku['sort'] = $skutring[$i][5][1];
								$dataSku['stockamount'] = $skutring[$i][6][1];
								$dataSku['salenum'] = $skutring[$i][7][1];
								$dataSku['updatetime'] = $timeAll;
								M('mall_goods_sku')->where(array('companyid'=>$this->companyid,'goodid'=>$id,'id'=>$skutring[$i][0][1]))->save($dataSku);
								$skuids .= $skutring[$i][0][1].',';
								$stockamount2 += $dataSku['stockamount'];
								$salenum2 += $dataSku['salenum'];
							}else{
								$alldataSku[$i]['companyid'] = $this->companyid;
								$alldataSku[$i]['goodid'] = $id;
								$alldataSku[$i]['name'] = $skutring[$i][1][1];
								$alldataSku[$i]['originalprice'] = $skutring[$i][2][1];
								$alldataSku[$i]['saleprice'] = $skutring[$i][3][1];
								$alldataSku[$i]['imgurl'] = $skutring[$i][4][1];
								$alldataSku[$i]['sort'] = $skutring[$i][5][1];
								$alldataSku[$i]['stockamount'] = $skutring[$i][6][1];
								$alldataSku[$i]['salenum'] = $skutring[$i][7][1];
								$alldataSku[$i]['updatetime'] = $alldataSku[$i]['createtime'] = $timeAll;
							}
						}
						M('mall_goods_sku')->where(array('companyid'=>$this->companyid,'goodid'=>$id,'id'=>array('not in',$skuids)))->delete();
						
						foreach($alldataSku as $val){
							if($val['name']){
								$data['companyid'] = $this->companyid;
								$data['goodid'] = $id;
								$data['name'] = $val['name'];
								$data['originalprice'] = $val['originalprice'];
								$data['saleprice'] = $val['saleprice'];
								$data['imgurl'] = $val['imgurl'];
								$data['sort'] = $val['sort'];
								$data['stockamount'] = $val['stockamount'];
								$data['salenum'] = $val['salenum'];
								$data['updatetime'] = $data['createtime'] = $timeAll;
								M('mall_goods_sku')->add($data);
								$stockamount3 += $data['stockamount'];
								$salenum3 += $data['salenum'];
							}
						}
						$stockamount = $stockamount2 + $stockamount3;
						$salenum = $salenum2 + $salenum3;
						$sku_price = M('mall_goods_sku')->where(array('companyid'=>$this->companyid,'goodid'=>$id))->order('saleprice')->field('originalprice,saleprice')->find();
						$_POST['issoldout'] = $stockamount>0 ? '2' : '1';
						$_POST['stockamount'] = $stockamount;
						$_POST['salenum'] = $salenum;
						$_POST['originalprice'] = $sku_price['originalprice'];
						$_POST['saleprice'] = $sku_price['saleprice'];
					}
					//编辑商品商品图
					M('mall_goods_pics')->where(array('companyid'=>$this->companyid,'goodid'=>$id))->delete();
					for($i=0; $i<$imgtringNum; $i++){
						if($imgtring[$i][0][1]){
							$dataImg['id'] = guidNow();
							$dataImg['companyid'] = $this->companyid;
							$dataImg['goodid'] = $id;
							$dataImg['pic'] = $imgtring[$i][0][1];
							$dataImg['sort'] = $imgtring[$i][1][1];
							$dataImg['updatetime'] = $dataImg['createtime'] = $timeAll;
							M('mall_goods_pics')->add($dataImg);
						}
					}
				}
				$where['id'] = $id;
				$where['companyid'] = $this->companyid;
				$_POST['useinfo'] = $this->_post('useinfo');
				$_POST['info'] = $this->_post('info');
				$_POST['goodnum'] = $_POST['goodnum'] ? $_POST['goodnum'] : $id;
				$_POST['updatetime'] = $timeAll;
				$goodsSave = M('mall_goods')->where($where)->save($_POST);
			}else{
				$serialNumber = M('mall_goods')->where(array('companyid'=>$this->companyid))->order('createtime DESC')->getField('id');
				$serialNumber = str_pad(substr($serialNumber, -5)+1, 5, '0', STR_PAD_LEFT);
				$_POST['id'] = $id = orderID('1','g',$this->companyid,$serialNumber);
				$_POST['companyid'] = $this->companyid;
				$_POST['info'] = $this->_post('info');
				$_POST['pricetype'] = 1;
				$_POST['goodnum'] = $_POST['goodnum'] ? $_POST['goodnum'] : $id;
				$_POST['updatetime'] = $_POST['createtime'] = $timeAll;
				M('mall_goods')->add($_POST);
				
				//$this->insertStatus('Mall_goods');
				
				//关联标签，品类管理自定义排序
				$tagsArr = explode(',', substr($_POST['tags'],1,-1));
				if($tagsArr){
					M('mall_tags_goods_link')->where(array('companyid'=>$this->companyid,'goodid'=>$id))->delete();
					foreach($tagsArr as $val){
						$dataTag['id'] = guidNow();
						$dataTag['companyid'] = $this->companyid;
						$dataTag['tagid'] = $val;
						$dataTag['goodid'] = $id;
						$dataTag['updatetime'] = $dataTag['createtime'] = $timeAll;
						M('mall_tags_goods_link')->add($dataTag);
					}
				}
				$stockamount = 0;
				$salenum = 0;
				if($type == 1){
					if($type == 1){
						// 创建商品SKU
						for($i=0; $i<$skutringNum; $i++){
							if($skutring[$i][1][1]){
								$skuData['companyid'] = $this->companyid;
								$skuData['goodid'] = $id;
								$skuData['name'] = $skutring[$i][1][1];
								$skuData['originalprice'] = $skutring[$i][2][1];
								$skuData['saleprice'] = $skutring[$i][3][1];
								if($type == 1){
									$skuData['imgurl'] = $skutring[$i][4][1];
									$skuData['sort'] = $skutring[$i][5][1];
									$skuData['stockamount'] = $skutring[$i][6][1];
									$skuData['salenum'] = $skutring[$i][7][1];
								}elseif($type == 3 || $type == 4 || $type == 5){
									$skuData['sort'] = $skutring[$i][4][1];
									$skuData['stockamount'] = $skutring[$i][5][1];
									$skuData['salenum'] = $skutring[$i][6][1];
								}
								$skuData['updatetime'] = $skuData['createtime'] = $timeAll;
								M('mall_goods_sku')->add($skuData);
								$stockamount += $skuData['stockamount'];
								$salenum += $skuData['salenum'];
							}
						}
						$sku_price = M('mall_goods_sku')->where(array('companyid'=>$this->companyid,'goodid'=>$id))->order('saleprice')->field('originalprice,saleprice')->find();
						$_POST['issoldout'] = $stockamount>0 ? '2' : '1';
						$_POST['originalprice'] = $sku_price['originalprice'];
						$_POST['saleprice'] = $sku_price['saleprice'];
						$_POST['stockamount'] = $stockamount;
						$_POST['salenum'] = $salenum;
					}
					// 创建商品图
					for($i=0; $i<$imgtringNum; $i++){
						if($imgtring[$i][0][1]){
							$imgData['id'] = guidNow();
							$imgData['companyid'] = $this->companyid;
							$imgData['goodid'] = $id;
							$imgData['pic'] = $imgtring[$i][0][1];
							$imgData['sort'] = $imgtring[$i][1][1];
							$imgData['updatetime'] = $imgData['createtime'] = $timeAll;
							M('mall_goods_pics')->add($imgData);
						}
					}
					$_POST['updatetime'] = time()+1; //创建与编辑时间相差太短，保存会报错， 需要+1
					$goodsSave = M('mall_goods')->where(array('companyid'=>$this->companyid,'id'=>$id))->save($_POST);
				}
			}
			if($goodsSave){
				$return['code'] = 'success';
				$return['tips'] = '操作成功';
				if($_POST['isoffshelves'] == 1){
					$return['url'] = U('MallGoods/index',array('isoffshelves'=>$_POST['isoffshelves']));
				}else{
					$return['url'] = U('MallGoods/index',array('isoffshelves'=>$_POST['isoffshelves'],'issoldout'=>$_POST['issoldout']));
				}
			}
			echo json_encode($return);
		}else{
			$this->ImagesAll($this->companyid);
			$id = $this->_get('id');
			if($id){
				$info = $this->mallGoodsModel->getMallGoodsInfo(array('companyid'=>$this->companyid,'id'=>$id));
				$info['tag'] = M('eshop_tag')->where(array('companyid'=>$this->companyid,'id'=>array('in',$info['tags'])))->field('id,title')->select();
				$info['sku'] = M('mall_goods_sku')->where(array('companyid'=>$this->companyid,'goodid'=>$info['id']))->field('id,name,originalprice,saleprice,intprice,imgurl,sort,stockamount,salenum')->order('sort,createtime DESC')->select();
				$info['pic'] = M('mall_goods_pics')->where(array('companyid'=>$this->companyid,'goodid'=>$info['id']))->field('id,pic,sort')->order('sort,createtime DESC')->select();
				$goodTypeTitle = '商品';
				$this->makeTopUrl = $this->makeTopUrl_User(array($this->SCENE_WELCOME,array('name'=>'商品管理','url'=>U('MallGoods/index',array('isoffshelves'=>2,'issoldout'=>2)),'rel'=>'','target'=>''),array('name'=>'编辑'.$goodTypeTitle,'url'=>'','rel'=>'','target'=>'')));
			}else{
				$goodtype = $this->_get('goodtype');
				$goodTypeTitle = '商品';
				$this->makeTopUrl = $this->makeTopUrl_User(array($this->SCENE_WELCOME,array('name'=>'商品管理','url'=>U('MallGoods/index',array('isoffshelves'=>2,'issoldout'=>2)),'rel'=>'','target'=>''),array('name'=>'创建'.$goodTypeTitle,'url'=>'','rel'=>'','target'=>'')));
				$info['goodtype'] = $this->_get('goodtype','intval');
				$info['goodtype'] = $goodtype;
				$info['pricetype'] = '1';
				$info['freighttype'] = '2';
				$info['issoldout'] = '2';
			}
			$info['tplList'] = M('mall_freight_tpl')->where(array('companyid'=>$this->companyid))->field('id,name')->select();
			$shops = M('company_shops')->where(array('companyid'=>$this->companyid))->field('id,name,shopname')->select();
			$this->assign('shops',$shops);
			$this->assign('info',$info);
			$this->display();
		}
	}
	/**
	 * 操作
	 * 删除（单条、多条）/下架（单条、多条）/复制商品
	 * $type 1：删除；2：下架（至仓库）；3：上架；4：复制；
	 * @author 徐建鹏
	 * @since  2017-10-31
	 */
	public function ajaxOperation(){
		$return['code'] = '300';
		$return['tips'] = '操作失败';
		
		$time = time();
		$id = $this->_post('id');
		if($id){
			$type = $this->_post('type');
			if($type == 1){
				$list = explode(',', $id);
				$num = '0';
				foreach($list as $val){
					$info = M('mall_goods')->where(array('companyid'=>$this->companyid,'id'=>$id))->field('id,title,goodtype')->find();
					$goodskuid = M('mall_goods_sku')->where(array('companyid'=>$this->companyid,'goodid'=>$val))->getField('id');
					M('mall_goods_sku')->where(array('companyid'=>$this->companyid,'goodid'=>$val))->delete();
					M('mall_goods_pics')->where(array('companyid'=>$this->companyid,'goodid'=>$val))->delete();
					$delMallGoods = M('mall_goods')->where(array('companyid'=>$this->companyid,'id'=>$val))->delete();
					if($delMallGoods){
						$num++;
					}
				}
				M('mall_tags_goods_link')->where(array('companyid'=>$this->companyid,'goodid'=>array('in',$id)))->delete();//删除tag关联
			}elseif($type == 2){
				$num = M('mall_goods')->where(array('companyid'=>$this->companyid,'id'=>array('in',$id)))->save(array('isoffshelves'=>1,'updatetime'=>$time));
			}elseif($type == 3){
				$num = M('mall_goods')->where(array('companyid'=>$this->companyid,'id'=>array('in',$id)))->save(array('isoffshelves'=>2,'updatetime'=>$time));
			}elseif($type == 4){
				$info = M('mall_goods')->where(array('companyid'=>$this->companyid,'id'=>$id))->field('issoldout,title,saleprice,tags,info,weight,goodtype,freighttype,freighttplid,stockamount,shareimg,sharefriendstitle,sharedes')->find();
				//金钱商品 都为g
				$info['id'] = $info['goodnum'] = $num = $this->newOrderID('1','g',$this->companyid);
				$info['companyid'] = $this->companyid;
				$info['isoffshelves'] = 1;
				$info['pricetype'] = 1;
				$info['updatetime'] = $info['createtime'] = $time;
				M('mall_goods')->add($info);
				if($num){
					//关联标签，品类管理自定义排序
					$tagsArr = explode(',', substr($info['tags'],1,-1));
					if($tagsArr){
						foreach($tagsArr as $val){
							$dataTag['id'] = guidNow();
							$dataTag['companyid'] = $this->companyid;
							$dataTag['tagid'] = $val;
							$dataTag['goodid'] = $num;
							$dataTag['updatetime'] = $dataTag['createtime'] = $time;
							M('mall_tags_goods_link')->add($dataTag);
						}
					}
					if($info['goodtype'] == 1){
						//商品SKU
						$skulist = M('mall_goods_sku')->where(array('companyid'=>$this->companyid,'goodid'=>$id))->field('name,originalprice,saleprice,imgurl,sort,stockamount')->select();
						if($skulist){
							foreach($skulist as $sval){
								$sku['companyid'] = $this->companyid;
								$sku['goodid'] = $num;
								$sku['name'] = $sval['name'];
								$sku['originalprice'] = $sval['originalprice'];
								$sku['saleprice'] = $sval['saleprice'];
								$sku['imgurl'] = $sval['imgurl'];
								$sku['sort'] = $sval['sort'];
								$sku['stockamount'] = $sval['stockamount'];
								$sku['updatetime'] = $sku['createtime'] = $time;
								M('mall_goods_sku')->add($sku);
							}
						}
						
						//商品图片
						$piclist = M('mall_goods_pics')->where(array('companyid'=>$this->companyid,'goodid'=>$id))->field('pic,sort')->select();
						if($piclist){
							foreach($piclist as $pval){
								$pic['id'] = guidNow();
								$pic['companyid'] = $this->companyid;
								$pic['goodid'] = $num;
								$pic['pic'] = $pval['pic'];
								$pic['sort'] = $pval['sort'];
								$pic['updatetime'] = $pic['createtime'] = $time;
								M('mall_goods_pics')->add($pic);
							}
						}
					}
				}
			}
			if($num){
				$return['code'] = '200';
				$return['tips'] = '操作成功';
			}
		}
		echo json_encode($return);
	}
	/**
	 * 二维码
	 * @author 徐建鹏
	 * @since  2017-10-31
	 */
	public function erweima(){
		$url = base64_decode($this->_get('link'));
		$this->getQRcode($url);
	}
	/**
	 * 更新运费模板
	 * @author 徐建鹏
	 * @since  2017-10-31
	 */
	public function ajaxUpdateTemplet(){
		$return['html'] = '<option value="">请选择运费模板</option>';
		$id = $this->_post('id');
		$list = M('mall_freight_tpl')->where(array('companyid'=>$this->companyid))->field('id,name')->order('createtime DESC')->select();
		if($list){
			foreach($list as $key=>$val){
				$return['html'] .= '<option value="'.$val['id'].'"';
				if($val['id']==$id){ $return['html'] .= 'selected="selected"';}
				$return['html'] .= '>'.$val['name'].'</option>';
			}
		}
		echo json_encode($return);
	}
	public function indexSlide(){
	    if(IS_POST){
            $_POST['slide'] = htmlspecialchars_decode($_POST['slide']);
            $_POST['update_time'] = time();
            $res = M("eshop_index")->where(array("id"=>1))->save($_POST);
            if($res){
                $return['code'] = '200';
                $return['msg'] = '操作成功';
            }
            echo json_encode($return);
        }else{
            $this->info = M("eshop_index")->where(array("id"=>1))->find();
	        $this->display();
        }
    }
}
?>