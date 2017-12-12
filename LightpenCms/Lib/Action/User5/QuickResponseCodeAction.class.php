<?php
/**
 * 
 * 二维码管理
 * @author    Mark<1311013341@qq.com>
 * @since     2016-10-11
 * @version   1.0
 */
class QuickResponseCodeAction extends UserAction{
	private $uid;
	private $companyid;
	private $shopsid;
	private $quickResponseCodeModel;
	public  $wechatsModel;
	private $companyShopsModel;
	public function __construct(){
		parent::__construct();
		$this->quickResponseCodeModel 	= M('quick_response_code');
		$this->wechatsModel 	= D('Wechats');		$this->companyShopsModel = M('company_shops');
		$this->uid = session('uid');
		$this->companyid = session('cid');
		$this->shopsid = session('shopsid');
	}
	/**
	 * 首页(non-PHPdoc)
	 * @see UserAction::index()
	 */
	public function index(){
		$this->checkCompanyScrm5Permissions(17,TRUE,16);
		$this->makeTopUrl = $this->makeTopUrl_User(array($this->MANAGE_WELCOME,array('name'=>'二维码管理','url'=>'','rel'=>'','target'=>''),array('name'=>'自定义网页二维码','url'=>'','rel'=>'','target'=>'')));
		$where['companyid'] = $this->companyid;
		$count = M('quick_response_webpage_code')->where($where)->count();
		$page = new NewPage($count,15);
		$list = M('quick_response_webpage_code')->where($where)->field('id,wechatName,name,picurl,scannum,content,dimension')->order('createtime DESC')->limit($page->firstRow.','.$page->listRows)->select();
		$this->assign('list',$list);
		$this->assign('page',$page->diyshow());
		$this->display();
	}
	/**
	 * 二维码
	 */
	public function lists(){
		$this->checkCompanyScrm5Permissions(18,TRUE);
		$this->makeTopUrl = $this->makeTopUrl_User(array($this->MANAGE_WELCOME,array('name'=>'二维码管理','url'=>'','rel'=>'','target'=>''),array('name'=>'微信关注二维码','url'=>'','rel'=>'','target'=>'')));
		$keywordWhere['companyid'] = $wechatWhere['companyid'] = $where['companyid'] = $this->companyid;
		if($this->_request('type') == '1'){
			$this->type = '1';
			$where['userid'] = array('eq','0');
			$order = 'createtime DESC';
		}else{
			$where['userid'] = array('neq','0');
			$order = 'isboss ASC,createtime DESC';
		}
		$where['type'] = '1';
		$count = $this->quickResponseCodeModel->where($where)->count();
		$page = new NewPage($count,15);
		$list = $this->quickResponseCodeModel->where($where)->field('id,userid,boundshopid,wechatName,name,picurl,subscribe,unsubscribe,scannum,content,dimension,boundkeyword,isboss,registernum')->order($order)->limit($page->firstRow.','.$page->listRows)->select();
		if($list){
			if($this->_request('type') == '1'){
				foreach ($list as $key=>$val){
					$list[$key]['shopname'] = M('company_shops')->where(array('company'=>$this->companyid,'id'=>$val['boundshopid']))->getField('shopname');
					unset($val);
				}
			}else{
				foreach ($list as $key=>$val){
					$shopid = M('users')->where(array('companyid'=>$this->companyid,'id'=>$val['userid']))->getField('helpershopid');
					if($shopid == '-1'){
						$list[$key]['shopname'] = '总部';
					}else{
						$list[$key]['shopname'] = M('company_shops')->where(array('company'=>$this->companyid,'id'=>$shopid))->getField('shopname');
					}
					unset($val,$shopid);
				}
			}
		}
		$this->assign('list',$list);
		$this->assign('page',$page->diyshow());
		$wechatWhere['wechattype'] = 4;
		$wechatList = $this->wechatsModel->getCompanyWechatss($wechatWhere);
		$shopList = M('company_shops')->where(array('companyid'=>$this->companyid))->field('id,shopname')->select();
		$this->assign('shopList',$shopList);
		$this->assign('wechatList',$wechatList);
		$this->display();
	}
	/**
	 * 二维码统计
	 *
	 *
	 * @author Mark<1311013341@qq.com>
	 * @since  2017-3-14
	 */
	public function statistics(){
		$this->checkCompanyScrm5Permissions(134,TRUE);
		$this->makeTopUrl = $this->makeTopUrl_User(array($this->MANAGE_WELCOME,array('name'=>'二维码管理','url'=>'','rel'=>'','target'=>''),array('name'=>'门店拉粉统计','url'=>'','rel'=>'','target'=>'')));
		$hwhere['qrc.companyid'] = $qwhere['companyid'] = $where['companyid'] = $this->companyid;
		$sortname = $this->_get('sortname');
		if($sortname){
			$this->assign('sortname',$sortname);
			$order = $sortname;
		}else{
			$order = 'createtime';
		}
		$sorttype = $this->_get('sorttype');
		if($sorttype){
			$this->assign('sorttype',$sorttype);
			$order .= ' '.$sorttype;
		}else{
			$order .= ' asc';
		}
		$count = M('company_shops')->where($where)->count();
		$page = new NewPage($count,15);
		$shopList = M('company_shops')->where($where)->field('id,shopname,wechatfansnum,todaywechatfansnum,yesterdaywechatfansnum')->order($order)->select();
		if($shopList){
			$db = M('quick_response_code');
			$hwhere['qrc.type'] = $qwhere['type'] = '1';
 			foreach ($shopList as $skey=>$sval){
				$hwhere['user.helpershopid'] = $qwhere['boundshopid'] = $sval['id'];
				$qwhere['userid'] = array('eq','0');
				$hwhere['qrc.userid'] = array('neq','0');
 				$shopList[$skey]['customcode'] = $db->where($qwhere)->field('id,picurl,content,name,wechatfansnum,todaywechatfansnum,yesterdaywechatfansnum')->order($order)->select();
 				$shopList[$skey]['fanscode'] = M()->table('tp_quick_response_code as qrc')->join('left join tp_users as user on user.id=qrc.userid')->where($hwhere)->field('qrc.id,qrc.content,qrc.picurl,qrc.name,qrc.wechatfansnum,qrc.todaywechatfansnum,qrc.yesterdaywechatfansnum')->order('qrc.'.$order)->select();
 				unset($sval);
 			}
		}
		$this->assign('page',$page->diyshow());
		$this->assign('shopList',$shopList);
		$this->display();
	}
	/**
	 * 
	 * ajax网页二维码报表
	 * 
	 * @author Mark<1311013341@qq.com>
	 * @since  2016-10-13
	 */
	public function quicklog(){
		$ajax['code'] = '300';
		$ajax['msg'] = '网络繁忙，请稍候重试';
		if(IS_POST){
			$where['companyid'] = $keywhere['companyid'] = $wechatWhere['companyid'] = $this->companyid;
			$where['qid'] = $wechatWhere['id'] = $this->_post('qid');
			$page = $this->_post('page')?$this->_post('page'):1;
			$limit = 15;
			if($this->_post('type') == '2'){    //每日业绩
				$colspan = '5';
				/* $info = M('quick_response_code')->where(array('companyid'=>$this->companyid,'content'=>$this->_post('qid')))->field('name,createtime')->find();
				if($info){
					$ajax['name'] = $info['name'];
					$ajax['createtime'] = format_time($info['createtime'],'ymdhis');
				} */
				$count = M('quick_response_code_daylog')->where($where)->count();
				$list = M('quick_response_code_daylog')->where($where)->field('day,subscribe,unsubscribe,scannum,registernum')->order('day desc')->limit($limit)->page($page)->select();
			}elseif($this->_post('type') == '3'){ //关键词
				$colspan = '4';
				$keywhere['token'] = M('wechats')->where(array('companyid'=>$this->companyid))->getField('token');
				$count = M("keyword")->where($keywhere)->count();
				$list = M("keyword")->where($keywhere)->field('keyword,id')->limit($limit)->page($page)->order('createtime DESC')->select();
			}elseif($this->_post('type') == '4'){  //   获取粉丝
				$colspan = '5';
				/* $info = M('quick_response_code')->where(array('companyid'=>$this->companyid,'content'=>$this->_post('qid')))->field('name,createtime')->find();
				if($info){
					$ajax['name'] = $info['name'];
					$ajax['createtime'] = format_time($info['createtime'],'ymdhis');
				} */
				$count = M("member_wechat_info")->where(array('companyid'=>$this->companyid,'scene_id'=>$this->_post('qid')))->count();
				$list = M('member_wechat_info')->where(array('companyid'=>$this->companyid,'scene_id'=>$this->_post('qid')))->field('mid,openid,nickname')->select();
				foreach ($list as $lkey=>$lval){
					$list[$lkey]['subscribe_time'] = M('history_wechat_request')->where(array('companyid'=>$this->companyid,'FromUserName'=>$lval['openid'],'Event'=>'subscribe'))->order('CreateTime desc')->group('CreateTime')->getField('CreateTime');
					$list[$lkey]['unsubscribe_time'] = M('history_wechat_request')->where(array('companyid'=>$this->companyid,'FromUserName'=>$lval['openid'],'Event'=>'unsubscribe'))->order('CreateTime desc')->group('CreateTime')->getField('CreateTime');
					$list[$lkey]['isregister'] = M('member_register_info')->where(array('companyid'=>$this->companyid,'id'=>$lval['mid']))->getField('isregister');
				}
				$list = arraySort($list,'subscribe_time','SORT_DESC');
				$list = arraypage($list,$limit,$page);
			}else{    //网页二维码报表
				$colspan = '4';
				/* $info = M('quick_response_webpage_code')->where($wechatWhere)->field('name,createtime')->find();
				if($info){
					$ajax['name'] = $info['name'];
					$ajax['createtime'] = format_time($info['createtime'],'ymdhis');
				} */
				$count = M('quick_response_webpage_code_daylog')->where($where)->count();
				$list = M('quick_response_webpage_code_daylog')->where($where)->field('day,scannum')->order('day desc')->limit($limit)->page($page)->select();
			}
			if($list){
				$ajax['page'] = $this->ajaxpage($count, $page, $limit);
				$ajax['code'] = '200';
				$ajax['html'] .= '';
				if($this->_post('type') == '2'){
					foreach($list as $key=>$val){
						$ajax['html'] .= '<tr>';
						$ajax['html'] .= '<td>'.format_time($val['day'],'ymd').'</td>';
						$ajax['html'] .= '<td>'.$val['subscribe'].'</td>';
						$ajax['html'] .= '<td>'.$val['unsubscribe'].'</td>';
						$ajax['html'] .= '<td>'.$val['scannum'].'</td>';
						$ajax['html'] .= '<td>'.$val['registernum'].'</td>';
						$ajax['html'] .= '</tr>';
					}
				}elseif($this->_post('type') == '3'){
					foreach($list as $key=>$val){
						$ajax['html'] .= '<tr>';
						$ajax['html'] .= '<td>'.emoji_decode($val['keyword']).'</td>';
                    	$ajax['html'] .= '<td><a href="javascript:void(0);" class="tips js-click-keyword" data-id="'.$val['id'].'">选取</a></td>';
						$ajax['html'] .= '</tr>';
					}
				}elseif($this->_post('type') == '4'){
					foreach($list as $key=>$val){
						$ajax['html'] .= '<tr>';
						$ajax['html'] .= $val['nickname']?'<td>'.$val['nickname'].'</td>':'<td>-</td>';
						$ajax['html'] .= $val['subscribe_time']?'<td>'.format_time($val['subscribe_time'],'ymdhis').'</td>':'<td>-</td>';
						$ajax['html'] .= $val['unsubscribe_time']?'<td>'.format_time($val['unsubscribe_time'],'ymdhis').'</td>':'<td>-</td>';
						if($val['unsubscribe_time']<$val['subscribe_time']){
							$ajax['html'] .= '<td>已关注</td>';
						}else{
							$ajax['html'] .= '<td>取消关注</td>';
						}
						if($val['isregister'] == '1'){
							$ajax['html'] .= '<td>是</td>';
						}else{
							$ajax['html'] .= '<td>否</td>';
						}
						$ajax['html'] .= '</tr>';
					}
				}else{
					foreach($list as $key=>$val){
						$ajax['html'] .= '<tr>';
						$ajax['html'] .= '<td>'.format_time($val['day'],'ymd').'</td>';
						$ajax['html'] .= '<td>'.$val['scannum'].'</td>';
						$ajax['html'] .= '</tr>';
					}
				}
			}else{
				$ajax['code'] = '200';
				$ajax['html'] = '<tr class="text-center not-hover"><td colspan="'.$colspan.'">暂无</td></tr>';
			}
		}
		echo json_encode($ajax);
	}
	/**
	 * 生成微信二维码(non-PHPdoc)
	 * @see UserAction::index()
	 */
	public function wechatqr(){
		$ajax['code'] = '300';
		$ajax['msg'] = '网络繁忙，请稍后重试';
		if (IS_POST) {
			M()->startTrans();
			$wechat = M('wechats')->where(array('companyid'=>$this->companyid))->field('token,appid,appsecret,wxname')->find();
			$date['content'] = M('quick_response_code_max_scene_id')->where(array('id'=>1))->getField('max_scene_id')+1;
			if($wechat && $date['content']){
				$weixin = new Wechat(array('token'=>$wechat['token'],'appid'=>$wechat['appid'],'appsecret'=>$wechat['appsecret']));
				$QRCodeInfo = $weixin->getQRCode($date['content'],1);
				if($QRCodeInfo){
	                $QRCodeInfoSrc = $weixin->getQRUrl($QRCodeInfo['ticket']);
	                $date['type'] = '1';
	                $date['picurl'] = $QRCodeInfoSrc;
	                $date['dimension'] = '430*430';
	                $date['companyid'] = $this->companyid;
	                $date['id'] = guidNow();
	                $date['wechatName'] = $wechat['wxname'];
	                $date['name'] = $this->_post('title');
	                $date['boundkeyword'] = $this->_post('keyword');
	                $date['boundshopid'] = $this->_post('boundshopid') ? $this->_post('boundshopid') : '0';
	                $date['updatetime'] = $date['createtime'] = time();
	                $return = M('quick_response_code')->add($date);
	                $data['scene_id'] = $maxSceneId['max_scene_id'] = $date['content'];
	                $data['updatetime'] = $maxSceneId['updatetime'] = time();
	                $companyReturn = M('company')->where(array('id'=>$this->companyid))->save($data);
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
		}
		echo json_encode($ajax);
	}
	/**
	 * 生成普通URL 二维码(non-PHPdoc)
	 * @see UserAction::index()
	 */
	public function urlqr(){
		$ajax['code'] = '300';
		$ajax['msg'] = '网络繁忙，请稍后重试';
		if (IS_POST){
			M()->startTrans();
			if($this->_post('id')){
				$where['id'] = $this->_post('id');
				$where['companyid'] = $this->companyid;
				$data['content'] = $_POST['url'];
				$data['name'] = $this->_post('title');
				$data['updatetime'] = time();
				$return = M('quick_response_webpage_code')->where($where)->save($data);
				$id = '1';
			}else{
				$data['id'] = guidNow();
				$data['companyid'] = $this->companyid;
				$data['content'] = $_POST['url'];
	            $data['type'] = '2';
	            $data['name'] = $this->_post('title');
	            $save['updatetime'] = $data['updatetime'] = $data['createtime'] = time();
	            $id = M('quick_response_webpage_code')->add($data);
	            $savePath = 'Uploads/'.$this->companyid.'/image/QRCodeImg/';
	            if(!is_dir($savePath)){
	            	check_dir($savePath);//创建文件夹
	            }
	            $saveImgName = getUniqueNumber().'.jpg';
	            $widthHeight = $this->_post('dimension');
	            $size = $widthHeight/41;
	            $localImgSrc = get_google_api_qrCode($savePath,$saveImgName,C('site_url').U('Wap/QuickResponseCode/index',array('id'=>$data['id'],'companyid'=>$this->companyid)),$size);
	            if($localImgSrc){
		            $save['picurl'] = $localImgSrc;
		            $return = M('quick_response_webpage_code')->where(array('id'=>$data['id'],'companyid'=>$this->companyid))->save($save);
	            }
			}
			if($return&&$id){
				M()->commit();
				$ajax['code'] = '200';
				$ajax['msg'] = '操作成功';
			}else{
				M()->rollback();
				$ajax['code'] = '300';
				$ajax['msg'] = '操作失败';
			}
		}
		echo json_encode($ajax);
	}
	/**
	 * 下载二维码文件
	 */
	public function downloadQRCode(){
		$filePath = $this->_get('filePath');
		$fileName = explode('/', $filePath);
		$file_name = $fileName[count($fileName)-1];
		//$filePath = $_SERVER['DOCUMENT_ROOT'].'/Uploads/'.$this->companyid.'/image/QRCodeImg/'.$fileName;
		//download_file($filePath,$fileName);
		header("Content-type:text/html;charset=utf-8");
		// $file_name="cookie.jpg";
		//$file_name="圣诞狂欢.jpg";
		//用以解决中文不能显示出来的问题
		$file_name=iconv("utf-8","GBK",$file_name);
		$file_sub_path=$_SERVER['DOCUMENT_ROOT'].'/Uploads/'.$this->companyid.'/image/QRCodeImg/';
		$file_path=$file_sub_path.$file_name;
		//首先要判断给定的文件存在与否
		if(!file_exists($file_path)){
			echo "没有该文件文件";
			return ;
		}
		$fp=fopen($file_path,"r");
		$file_size=filesize($file_path);
		//下载文件需要用到的头
		Header("Content-type: application/octet-stream");
		Header("Accept-Ranges: bytes");
		Header("Accept-Length:".$file_size);
		Header("Content-Disposition: attachment; filename=".$file_name);
		$buffer=1024;
		$file_count=0;
		//向浏览器返回数据
		while(!feof($fp) && $file_count<$file_size){
			$file_con=fread($fp,$buffer);
			$file_count+=$buffer;
			echo $file_con;
		}
		fclose($fp);
	}
	/**
	 * 删除二维码
	 */
	public function qrCodeDel(){
		$ajax['code'] = '300';
		$ajax['msg'] = '网络繁忙，请稍后重试';
		if(IS_POST){
			$type = $this->_post('type');
			$where['companyid'] = $this->companyid;
			$where['id'] = $this->_post('id');
			if($type == '1'){
				$db = 'quick_response_code';
			}else{
				$db = 'quick_response_webpage_code';
			}
			$return = M($db)->where($where)->delete();
			if($return){
				$ajax['code'] = '200';
				$ajax['msg'] = '删除成功';
			}else{
				$ajax['code'] = '300';
				$ajax['msg'] = '删除失败';
			}
		}
		echo json_encode($ajax);
	}
	/**
	 * 
	 * 导出CSV
	 * 
	 * @author Mark<1311013341@qq.com>
	 * @since  2016-6-30
	 */
	public function export(){
		$ajax['code'] = '300';
		$ajax['msg'] = '网络繁忙，请稍后重试';
		if(IS_POST){
			$where['companyid'] = $keywhere['companyid'] = $wechatWhere['companyid'] = $this->companyid;
			$where['qid'] = $wechatWhere['id'] = $this->_post('qid');
			if($this->_post('type') == '2'){
				$type = '2';
				$name = M('quick_response_code')->where(array('companyid'=>$this->companyid,'content'=>$this->_post('qid')))->getField('name');
				$name = $name.'每日明细';
				$list = M('quick_response_code_daylog')->where($where)->field('day,subscribe,unsubscribe,scannum,registernum')->order('day desc')->select();
			}elseif($this->_post('type') == '4'){
				$type = '3';
				$name = M('quick_response_code')->where(array('companyid'=>$this->companyid,'content'=>$this->_post('qid')))->getField('name');
				$name = $name.'获取粉丝';
				$list = M('member_wechat_info')->where(array('companyid'=>$this->companyid,'scene_id'=>$this->_post('qid')))->field('mid,openid,nickname')->select();
			}else{
				$type = '1';
				$name = M('quick_response_webpage_code')->where($wechatWhere)->getField('name');
				$list = M('quick_response_webpage_code_daylog')->where($where)->field('day,scannum')->order('day desc')->select();
			}
			$data['rule'] = M()->getLastSql();
			$data['type'] = $type;
			$data['companyid'] = $this->companyid;
			$id = guidNow();
			$data['name'] = $name;
			$data['remarkname'] = $id;
			$data['updatetime'] = $data['createtime'] = time();
			$addSuc = M('export_task')->add($data);
			if($addSuc){
				$ajax['code'] = 200;
			}else{
				$ajax['msg'] = '任务创建失败';
			}
		}
		echo json_encode($ajax);
	}
	/**
	 * 
	 * 设置风助手出示模板
	 * 
	 * @author Mark<1311013341@qq.com>
	 * @since  2016-11-1
	 */
	public function set(){
		$this->checkCompanyScrm5Permissions(18,TRUE);
		$this->ImagesAll($this->companyid);
		$this->Info = M('quick_response_code')->where(array('companyid'=>$this->companyid,'isboss'=>'1'))->field('id,picurl,background')->find();
		$this->display();
	}
	/**
	 * 
	 * ajax设置背景
	 * 
	 * @author Mark<1311013341@qq.com>
	 * @since  2016-11-1
	 */
	public function ajaxSaveimg(){
		$ajax['code'] = '300';
		$ajax['msg'] = '网络繁忙，请稍后重试';
		if(IS_POST){
			$where['companyid'] = $this->companyid;
			$where['isboss'] = '1';
			$data['background'] = $this->_post('img');
			$data['updatetime'] = time();
			M()->startTrans();
			$result = M('quick_response_code')->where($where)->save($data);
			$resulta = M('quick_response_code')->where(array('companyid'=>$this->companyid))->save(array('issave'=>'1','updatetime'=>time()));
			if($result && $resulta){
				M()->commit();
				$ajax['code'] = 200;
				$ajax['msg'] = '操作成功';
			}else{
				M()->rollback();
				$ajax['msg'] = '操作失败';
			}
		}
		echo json_encode($ajax);
	}
	/**
	 * 
	 * ajax导出全部
	 * 
	 * @author Mark<1311013341@qq.com>
	 * @since  2017-1-11
	 */
	public function exportall(){
		$ajax['code'] = '300';
		$ajax['msg'] = '网络繁忙，请稍后重试';
		if(IS_POST){
			$where['companyid'] = $this->companyid;
			if($this->_request('type') == '1'){
				$this->type = '1';
				$where['userid'] = array('eq','0');
				$order = 'createtime DESC';
				$name = '自定义微信关注二维码-'.date('YmdHi',time());
			}else{
				$where['userid'] = array('neq','0');
				$order = 'isboss ASC,createtime DESC';
				$name = '内部拉粉码-'.date('YmdHi',time());
			}
			$where['type'] = '1';
			$list = $this->quickResponseCodeModel->where($where)->field('id,name,subscribe,unsubscribe,scannum,registernum')->order($order)->select();
			$data['rule'] = M()->getLastSql();
			$data['type'] = 30;
			$data['companyid'] = $this->companyid;
			$id = guidNow();
			$data['name'] = $name;
			$data['remarkname'] = $id;
			$data['updatetime'] = $data['createtime'] = time();
			$addSuc = M('export_task')->add($data);
			if($addSuc){
				$ajax['code'] = 200;
			}else{
				$ajax['msg'] = '任务创建失败';
			}
		}
		echo json_encode($ajax);
	}
}
?>