<?php
/**
 * 留言板
 * Enter description here ...
 * @author Asa
 */
class MessageBoardAction extends WapBaseAction{
	
	public function __construct(){
		parent::__construct();
		$this->mescon=M("message_board_content");
		$this->mestit=M("message_board_title");
		$this->mescli=M("message_board_click_like");
		$this->companyid=session('wapcid');
		//session('openid'.session('wapcid'),1);
	}
	public function test(){
		$_SESSION['openid'.$this->companyid]=null;
	}
	
	/**
	 * 留言显示
	 */
	public function index(){
		session('AhistoryUrl','http://' . $_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"].'&companyid='.$this->companyid);
		$this->companyid = session('wapcid');//获取公司ID
		if(!isset($_SESSION['Aopenid'.$this->companyid])){
			self::wechat();
		}
		//echo $_SESSION['openid'].'222';
		$a=0;$b=0;
		$topid=$this->_get('topid');
		$this->pp=$pp=$this->mestit->where(array('id'=>$topid))->find();
		$info['sharefriendstitle'] = $pp['wechattitle'];
		$info['shareimg'] = $pp['wechatimg'];
		$info['sharedes'] = $pp['wechattext'];
		$info['shareurl'] = 'http://' .$_SERVER['SERVER_NAME'].U("MessageBoard/index",array("topid"=>$topid,"companyid"=>$this->companyid));
		$this->assign('info',$info);
		if($this->_request("iscode")==1){
			$data['codenum']=$pp['codenum']+1;
		}
		$data['viewnum']=$pp['viewnum']+1;
		$this->mestit->where(array('id'=>$pp['id']))->save($data);
		
		$this->setPageTitle(array('title'=>$pp['title']));
		$this->countasa=$this->mescon->where(array('topid'=>$topid,'type'=>1))->count();
		$mescontop=$this->mescon->where(array('topid'=>$topid,'type'=>1))
						->limit(0,10)->order('createtime desc')->select();
		$mesconhui=$this->mescon
						->table("tp_message_board_content mbt")
						->join("tp_message_board_content mb on mb.id=mbt.peoid")
						->where(array('mbt.topid'=>$topid,'mbt.type'=>2))->field("mbt.*,mb.nickname as nickname2")
						->order("createtime asc")->select();
		foreach ($mescontop as $val){
			$mescontop1[$a]=$val;
			$mescontop1[$a]['clicknum']=$this->mescli->where(array('conid'=>$val['id'],'status'=>1))->count();
			$clis=$this->mescli->where(array('conid'=>$val['id'],'openid'=>session('Aopenid'.$this->companyid)))->find();
			$mescontop1[$a]['clickstatus']=$clis['status'];
			$mescontop1[$a]['clickid']=$clis['id'];
			$mescontop1[$a]['answernum']=$this->mescon->where(array('parentid'=>$val['id']))->count();
			$a++;
		}
		foreach ($mesconhui as $val){
			$mesconhui1[$b]=$val;
			$mesconhui1[$b]['clicknum']=$this->mescli->where(array('conid'=>$val['id']))->count();
			$clis=$this->mescli->where(array('conid'=>$val['id'],'openid'=>session('Aopenid'.$this->companyid)))->find();
			$mesconhui1[$b]['clickstatus']=$clis['status'];
			$b++;
		}
		//dump($mescontop1);
		$this->mescontop=$mescontop1;
		$this->mesconhui=$mesconhui1;
		$this->display();
	}
	public function addmes(){
		$this->setPageTitle(array('title'=>'发布新留言'));
		$this->id=$this->_get('topid');
		$this->display();
	}
	public function info(){
		session('AhistoryUrl','http://' . $_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"].'&companyid='.$this->companyid);
		$this->companyid = session('wapcid');//获取公司ID
		if(!isset($_SESSION['Aopenid'.$this->companyid])){
			self::wechat();
		}
		//echo $_SESSION['openid'].'222';
		$a=0;$b=0;
		$topid=$this->_get('topid');
		$this->pp=$pp=$this->mestit->where(array('id'=>$topid))->find();
		if($this->_request("iscode")==1){
			$data['codenum']=$pp['codenum']+1;
		}
		$data['viewnum']=$pp['viewnum']+1;
		$this->mestit->where(array('id'=>$pp['id']))->save($data);
		$companyname=M('company')->where(array('id'=>$this->companyid))->getField('name');
		//$info['sharefriendstitle'] = '点你所想，评你所爱，参与'.$companyname.'互动点评。';
		$info['sharefriendstitle'] = $pp['ftitle']?$pp['ftitle']:'点你所想，评你所爱，参与'.$companyname.'互动点评。';
		$this->setPageTitle(array('title'=>$pp['title']));
		$mescontop=$this->mescon->where(array('topid'=>$topid,'type'=>1,'id'=>$this->_get('id')))
		->limit(0,10)->order('createtime desc')->select();
		$mesconhui=$this->mescon
		->table("tp_message_board_content mbt")
		->join("tp_message_board_content mb on mb.id=mbt.peoid")
		->where(array('mbt.topid'=>$topid,'mbt.type'=>2))->field("mbt.*,mb.nickname as nickname2")
		->order("createtime asc")->select();
		foreach ($mescontop as $val){
			//$info['sharefriendstitle'] = mb_substr($val['content'],0,21,'utf-8');
			$info['shareimg'] = $val['headimgurl'];
			$content=mb_substr($val['content'],0,26,'utf-8');
			$info['sharedes'] = $content.'...';
			$mescontop1[$a]=$val;
			$mescontop1[$a]['clicknum']=$this->mescli->where(array('conid'=>$val['id'],'status'=>1))->count();
			$clis=$this->mescli->where(array('conid'=>$val['id'],'openid'=>session('Aopenid'.$this->companyid)))->find();
			$mescontop1[$a]['clickstatus']=$clis['status'];
			$mescontop1[$a]['clickid']=$clis['id'];
			$mescontop1[$a]['answernum']=$this->mescon->where(array('parentid'=>$val['id']))->count();
			$a++;
		}
		foreach ($mesconhui as $val){
			$mesconhui1[$b]=$val;
			$mesconhui1[$b]['clicknum']=$this->mescli->where(array('conid'=>$val['id']))->count();
			$clis=$this->mescli->where(array('conid'=>$val['id'],'openid'=>session('Aopenid'.$this->companyid)))->find();
			$mesconhui1[$b]['clickstatus']=$clis['status'];
			$b++;
		}
		//dump($mescontop1);
		$this->assign('info',$info);
		$this->mescontop=$mescontop1;
		$this->mesconhui=$mesconhui1;
		$this->display();
	}
	/**
	 * 发布留言/评论
	 */
	public function messageAdd(){
		$data['topid']=$this->_request('topid');
		$data['content']=$this->_request('content');
		$data['type']=$this->_request('type');
		$data['openid']=session('Aopenid'.$this->companyid)?session('Aopenid'.$this->companyid):'1';
		$data['nickname']=session('Anickname')?session('Anickname'):'过客';
		$data['headimgurl']=session('Aheadimgurl')?session('Aheadimgurl'):'1';
		if($data['type']==1){
				if(!is_dir('./Uploads/messageboard/'.$this->companyid)){
					check_dir('./Uploads/messageboard/'.$this->companyid);//创建文件夹
				}
				//dump($data);exit;
				//创建文件夹
				for($f=1;$f<=9;$f++){
					if($this->_request('imgurl'.$f)){
						for($b=1;$b<=5;$b++){$rank1=range(0,9);shuffle($rank1);$rank.=$rank1['1'];}
						$data['imgurl'.$f]=$this->_request('imgurl'.$f);
						if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $data['imgurl'.$f], $result)){
							$type = $result[2];
							$new_file = "./Uploads/messageboard/".$this->companyid."/2016".time().$rank.".{$type}";///".$this->companyid."
							if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $data['imgurl'.$f])))){
								$data['imgurl'.$f]=$new_file;
							}
						}
							
						$exif = exif_read_data($new_file);//获取exif信息
						//echo json_encode($exif);exit;
						if (isset($exif['Orientation']) && $exif['Orientation'] == 6) {
							//旋转
							imgturn($new_file,1);
						}
					}//图片一
					if($data['imgurl'.$f]=='null'){$data['imgurl'.$f]='';}
				}
		}else{
			$data['peoid']=$this->_request('peoid');
			$data['parentid']=$this->_request('parentid');
			$annum = $this->mescon->where(array('parentid'=>$data['parentid']))->count();
		}
		$data['createtime']=$data['updatetime']=time();
		$con=$this->mescon->add($data);
		$info=$this->mescon->table("tp_message_board_content mbt")
						->join("tp_message_board_content mb on mb.id=mbt.peoid")
						->where(array('mbt.id'=>$con,'mbt.type'=>2))->field("mb.nickname as nickname2")->find();
		if($con){
			$ajax=$data;
			$ajax['code']='200';
			$ajax['msg']='评论回复成功';
			$info['nickname2']?$ajax['nickname2']=$info['nickname2']:'';
			if($info['nickname2']==''){$ajax['asa']=400;}
			$ajax['id']=$con;
			$ajax['createtime']=date('Y-m-d H:i',time());
			$ajax['msg2']='发布评论成功';
			$ajax['annum']=($annum+1);
		}else{
			$ajax['code']='300';
			$ajax['msg']='评论回复失败';
			$ajax['msg2']='发布评论失败';
			$ajax['annum']=($annum);
		}
		echo json_encode($ajax);
	}
	/**
	 * 删除留言信息
	 */
	public function ajaxInfoDel(){
		$id=$this->_request('id');
		$ajax['code']='300';
		if($id){
			$pro=$this->mescon->delete($id);
			$this->mescon->where(array('parentid'=>$id))->delete();
			$this->mescli->where(array('conid'=>$id))->delete();
			if($pro){
				$ajax['code']='200';
				$ajax['msg']='删除成功';
			}else{
				$ajax['code']='300';
				$ajax['msg']='删除失败'; 
			}
		}
		echo json_encode($ajax);
	}
	public function ajaxInfoDel2(){
		$id=$this->_request('id');
		$info=$this->mescon->where(array('id'=>$id))->find();
		$annum = $this->mescon->where(array('parentid'=>$info['parentid']))->count();
		$ajax['code']='300';
		if($id){
			$pro=$this->mescon->delete($id);
			if($pro){
				$ajax['code']='200';
				$ajax['msg']='删除成功';
				$ajax['parentid']=$info['parentid'];
				$ajax['annum']=($annum-1);
			}else{
				$ajax['code']='300';
				$ajax['msg']='删除失败';
				$ajax['annum']=($annum);
			}
		}
		echo json_encode($ajax);
	}
	/**
	 * 添加点赞数
	 */
	public function ajaxClick(){
		$conid=$this->_request('conid');
		$clickid=$this->_request('clickid');
		$status=$this->_request('status');
		$data['updatetime']=time();
		if($clickid==''){
			$data['openid']=session('Aopenid'.$this->companyid);
			$data['status']=$status;
			$data['conid']=$conid;
			$data['createtime']=time();
			$cli=M('message_board_click_like')->add($data);
		}else{
			$data['status']=$status;
			$cli=M('message_board_click_like')->where(array('id'=>$clickid))->save($data);
		}
		$num = $this->mescli->where(array('conid'=>$conid,'status'=>1))->count();
		if($cli){
			$ajax['code']='200';
			$ajax['msg']=$clickid?$clickid:$cli;
			$ajax['num']= $num;
		}else{
			$ajax['code']='300';
			$ajax['msg']='点赞失败';
		}
		echo json_encode($ajax);
	}

	/**
	 * 显示更多（123模板）
	 * @author Asa<asa@renlaifeng.cn>
	 * @since  2016-3-29
	 */
public function ajaxMoreList1234(){
		$data['code'] = 300;
		$topid=$this->_get('topid');
		$limit = 10;
		$startNumber = $this->_post('startNumber'); // 本次查询的开始条数
		$startNumber = $startNumber ? $startNumber : 0;
		$str='';
		$mescontop=$this->mescon->where(array('topid'=>$topid,'type'=>1))
			->limit($startNumber,$limit)->order('createtime desc')->select();
		$mesconhui=$this->mescon
			->table("tp_message_board_content mbt")
			->join("tp_message_board_content mb on mb.id=mbt.peoid")
			->where(array('mbt.topid'=>$topid,'mbt.type'=>2))->field("mbt.*,mb.nickname as nickname2")
			->order("createtime asc")->select();
		foreach ($mescontop as $val){
			$val['clicknum']=$this->mescli->where(array('conid'=>$val['id'],'status'=>1))->count();
			$clis=$this->mescli->where(array('conid'=>$val['id'],'openid'=>session('Aopenid'.$this->companyid)))->find();
			$val['clickstatus']=$clis['status'];
			$val['clickid']=$clis['id'];
			$val['answernum']=$this->mescon->where(array('parentid'=>$val['id']))->count();
			$str .="<div class='way'><div class='top'><div><i class='userp'>";
			$str .="<img src='".$val['headimgurl']."' /></i><dl><dd>".$val['nickname']."</dd>";
			$str .="<dt>".date('Y-m-d H:i',$val['createtime'])."</dt></dl></div></div><div class='content' style='clear:both;'><div><p>".$val['content']."</p></div><ul><li><a href='".$val['imgurl1']."' data-at-1366='".$val['imgurl1']."' ><img src='".$val['imgurl1']."' /></a></li><li><a href='".$val['imgurl2']."' data-at-1366='".$val['imgurl2']."'><img src='".$val['imgurl2']."' /></a></li><li><a href='".$val['imgurl3']."' data-at-1366='".$val['imgurl3']."'><img src='".$val['imgurl3']."' /></a></li></ul></div><div class='shanchu' style='height:50px;position:relative;top:1px;' id='a".$val['id']."'>";
	        if($val['openid']==session('Aopenid'.$this->companyid)){
			$str .= "<a href='javascript:void(0)' class='shanchu1 del-asa' data-id='".$val['id']."'>删除</a>";
			}
			$str .= " <span><a href='javascript:void(0)' class='dinda-lufu'  data-parentid='".$val['id']."' data-peoid='' ><i class='ly_pen'></i>回复(<g>";
			if($val['answernum']){
				if($val['answernum']>99){$str.= '99+';}else{$str .= $val['answernum'];}
			}else{$str.= '0';}
			$str .= "</g>)</a><a href='javascript:void(0)' class='starns btn_dianzan' data-conid='".$val['id']."' data-clickid='".$val['clickid']."'>";
			if($val['clickstatus']==1){$str.= "<i class='ly_hxin'></i>";}else{$str.= "<i class='ly_huixin'></i>";}
			$str .="赞(<e>";
			if($val['clicknum']){
				if($val['clicknum']>99){$str.= '99+';}else{$str.= $val['clicknum'];}
			}else{$str.= '0';}
			$str .="</e>)</a></span></div><div class='huifu'";
			if($val['answernum']==0){ $str.= "style='display:none'";}
			$str.= "><img src='./Tpl/Wap/default/common/image/tu6.jpg' class='jiao'/><div class='huifuye'><ul><li id='".$val['id']."'>";
			foreach ($mesconhui as $val2){
				if($val2['parentid']==$val['id']){
					$str .= "<div class='huifu1'><div class='top'><div><i class='userp'><img src='".$val2['headimgurl']."'/></i><dl><dd>".$val2['nickname'];
					if($val2['peoid']){$str .= " 回复：".$val2['nickname2'];}
					$str .= "</dd><dt>".date('Y-m-d H:i',$val2['createtime'])."</dt></dl></div></div><div style='clear:both;'><p>".$val2['content']."</p></div><span class='hfs dinda-lufu' data-parentid=".$val['id']." data-peoid=".$val2['id']."><i class='ly_pen'></i><a href='javascript:void(0)'>回复</a></span></div>";
				}
			}
			$str .= '</li> </ul></div></div></div>';
		}
		if($str){
			$data['code'] = 200;
			$data['tips'] = $str;
		}
		echo json_encode($data);
		
	}
	
	public function ajaxMoreList123(){
		$data['code'] = 300;
		$topid=$this->_get('topid');
		$limit = 10;
		$startNumber = $this->_post('startNumber'); // 本次查询的开始条数
		$startNumber = $startNumber ? $startNumber : 0;
		$str='';
		$mescontop=$this->mescon->where(array('topid'=>$topid,'type'=>1))
		->limit($startNumber,$limit)->order('createtime desc')->select();
		$mesconhui=$this->mescon
		->table("tp_message_board_content mbt")
		->join("tp_message_board_content mb on mb.id=mbt.peoid")
		->where(array('mbt.topid'=>$topid,'mbt.type'=>2))->field("mbt.*,mb.nickname as nickname2")
		->order("createtime asc")->select();
		foreach ($mescontop as $val){
			$val['clicknum']=$this->mescli->where(array('conid'=>$val['id'],'status'=>1))->count();
			$clis=$this->mescli->where(array('conid'=>$val['id'],'openid'=>session('Aopenid'.$this->companyid)))->find();
			$val['clickstatus']=$clis['status'];
			$val['clickid']=$clis['id'];
			$val['answernum']=$this->mescon->where(array('parentid'=>$val['id']))->count();
			$str .= '<div class="way">';
			$str .= '<div class="top">';
			$str .= '<div class="hesd_box">';
			$str .= '<i class="userp"><img src="'.$val['headimgurl'].'" alt=""/></i>';
			$str .= '<dl>';
			$str .= '<dd>'.$val['nickname'].'</dd>';
			$str .= '<dt>'.date("Y-m-d H:i",$val['createtime']).'</dt>';
			$str .= '</dl>';
			$str .= '</div>';
			$str .= '</div>';
			$str .= '<div class="content">';
			$str .= '<div class="conep_box" style="clear:both"><p class="textRow"><a href="'.U('MessageBoard/info',array('companyid'=>$this->companyid,'topid'=>$topid,'id'=>$val['id'])).'" style="color:#666;">'.$val['content'].'</a></p></div>';
			$str .= '<a class="moreText" style="display: block; color: darkgray;" onclick=""></a>';
			$str .= '<ul class="contenga baguetteBox">';
			/* if($val['imgurl1']){
				$str .= '<li><a href="'.$val['imgurl1'].'"  data-at-1366="'.$val['imgurl1'].'"><img src="'.$val['imgurl1'].'" alt=""/></a></li>';
			}if($val['imgurl2']){
				$str .= '<li><a href="'.$val['imgurl2'].'" data-at-1366="'.$val['imgurl2'].'"><img src="'.$val['imgurl2'].'" alt=""/></a></li>';
			}if($val['imgurl3']){
				$str .= '<li><a href="'.$val['imgurl3'].'" data-at-1366="'.$val['imgurl3'].'"><img src="'.$val['imgurl3'].'" alt=""/></a></li>'; 
			} */
			for($g=1;$g<=9;$g++){
				if($val['imgurl'.$g]){
							$str .= '<li><a href="'.$val['imgurl'.$g].'"  data-at-1366="'.$val['imgurl'.$g].'" class="img_box_wh"><img src="'.$val['imgurl'.$g].'" alt="" class="img_hopol" /></a></li>';
			} }
			$str .= '</ul>';
			$str .= '</div>';
			$str .= '<div class="shanchu" style="position:relative;top:1px;" id="a'.$val['id'].'">';
			if($val['openid']==session('Aopenid'.$this->companyid)){
				$str .= '<a href="javascript:void(0)" class="shanchu1 del-asa" data-id="'.$val['id'].'">删除</a>';
			}
			$str .= '<span>';
			$str .= '<a href="javascript:void(0)" class="dinda-lufu"  data-parentid="'.$val['id'].'" data-peoid="" ><i class="ly_pen"></i>回复(<g class="huifunum">';
			if($val['answernum']){
				if($val['answernum']>99){echo $str .= '99+';}else{ $str .= $val['answernum'];}
			}else{$str .= '0';} 
			$str .= '</g>)</a>';
			$str .= '<a href="javascript:void(0)" class="starns btn_dianzan" data-conid="'.$val['id'].'" data-clickid="'.$val['clickid'].'">';
			if($val['clickstatus']==1){$str .= "<i class='ly_hxin'></i>";}else{ $str .= "<i class='ly_huixin'></i>";}
			$str .= '赞(<e>';
			if($val['clicknum']){if($val['clicknum']>99){$str .= '99+';}else{ $str .= $val['clicknum'];}}else{$str .= '0';}
			$str .= '</e>)</a>';
			$str .= '</span>';
			$str .= '</div>';
			$str .= '<div class="huifu"';
			if($val['answernum']==0){ $str .= 'style="display:none"'; }
			$str .= '>';
			$str .= '<img src="./Tpl/Wap/default/common/image/tu6.jpg" alt="" class="jiao"/>';
			$str .= '<div class="huifuye">';
			$str .= '<ul>';
			$str .= '<li id="'.$val['id'].'">';
			foreach($mesconhui as $val2){ 
				if($val2['parentid']==$val['id']){
					$str .= '<div class="huifu1">';
					$str .= '<div class="top">';
					$str .= '<div>';
					$str .= '<i class="userp"><img src="'.$val2['headimgurl'].'" alt=""/></i>';
					$str .= '<dl>';
					$str .= '<dd>'.$val2['nickname'];
					if($val2['peoid']){$str .= " 回复  ".$val2['nickname2'].'';}
					$str .= '</dd>';
					$str .= '<dt>'.date('Y-m-d H:i',$val2['createtime']).'</dt>';
					$str .= '</dl>';
					$str .= '</div>';
					$str .= '</div>';
					$str .= '<div style="clear:both;" class="huifu_maina">'.$val2['content'].'</div>';
					$str .= '<span class="hfs dinda-lufu" data-parentid="'.$val['id'].'" data-peoid="'.$val2['id'].'"><i class="ly_pen"></i><a href="javascript:void(0)">回复</a></span>';
					if($val2['openid']==session('Aopenid'.$this->companyid)){
					$str .= '<a href="javascript:void(0)" class="del2-asa" data-id="'.$val2['id'].'"><i class="hf_scu_icon">删除</i></a>';
					}
					$str .= '</div>';
				}
			}
			$str .= '</li>';
			$str .= '</ul>';
			$str .= '</div>';
			$str .= '</div>';
			$str .= '</div>';
		}
			
		if($str){
			$data['code'] = 200;
			$data['tips'] = $str;
		}
		echo json_encode($data);
	}
	public function wechat(){
		$wechats = M('wechats')->where(array('companyid'=>$this->companyid))->field('appid,appsecret')->find();
		$code = $_GET['code'];
		if($code){
			$wechatOptions = array('appid'=>$wechats['appid'],'appsecret'=>$wechats['appsecret']);
			$wechat  = new Wechat($wechatOptions);
			$wInfo = $wechat->getOauthAccessToken();
			session('Aopenid'.$this->companyid,null);
			session('Aopenid'.$this->companyid,$wInfo['openid']);
			$wInfo2 = $wechat->getOauthUserinfo($wInfo['access_token'],session('Aopenid'.$this->companyid));
			session('Anickname',null);
			session('Anickname',$wInfo2['nickname']);
			session('Aheadimgurl',null);
			session('Aheadimgurl',$wInfo2['headimgurl']);
			$this->redirect(session('AhistoryUrl'));
		}else{
			$wechatOptions = array('appid'=>$wechats['appid'],'appsecret'=>$wechats['appsecret']);
			$wechat  = new Wechat($wechatOptions);
			$info = $wechat->getOauthRedirect(session('AhistoryUrl'),'','snsapi_userinfo');
			if($info){
				redirect($info);
			}else{
				echo '获取code失败，请重新获取';
			}
		}
	}
}