<?php
/**
 * 
 * 会员标签
 * 
 * @author    Leo<1251868177@qq.com>
 * @since     2016-10-26
 * @version   1.0
 */
class MemberGroupAction extends UserAction{

	private $uid;
	
	private $companyid;
	
	private $shopsid;
	
	public function __construct(){
		parent::__construct();
		$this->uid = session('uid');
		$this->companyid = session('cid');
		$this->shopsid = session('shopsid');
	}
	/**
	 * 
	 * 首页
	 * 
	 * @author Leo<1251868177@qq.com>
	 * @since  2016-10-26
	 */
	public function index(){
	    $this->makeTopUrl = $this->makeTopUrl_User(array($this->CRM_WELCOME,array('name'=>'我的会员','url'=>'','rel'=>'','target'=>''),array('name'=>'会员标签','url'=>'','rel'=>'','target'=>'')));   
	    // 自定义标签
	    $count = M('member_group')->where(array('companyid'=>$this->companyid))->count();
	    $page = new NewPage($count,10);
	    $list = M('member_group')->where(array('companyid'=>$this->companyid))->field('id,name,membernum')->order('createtime DESC')->limit($page->firstRow.','.$page->listRows)->select();
	    $this->assign('list',$list);
		$this->assign('page',$page->diyshow());
	    $this->display();    
	}
	/**
	 * 
	 * 异步统计自动会员标签人数
	 * 
	 * @author Leo<1251868177@qq.com>
	 * @since  2016-10-27
	 */
	public function ajaxReportLable(){
	    $returnData['code'] = 300;
	    $returnData['tips'] = '抱歉，服务器繁忙，请稍后重试';
	    $type = $this->_post('type');
	    if($type){
	        $list = $this->memberTags();
	        $where['companyid'] = $this->companyid;
	        if($type == 'registertype'){  // 会员来源
	            $lableList = $list['registertype'];
	            $json = M('report_member_system_group')->where(array('companyid'=>$this->companyid))->getField('registertype');
	            $lableReturn = 1;
	        }elseif($type == 'grade'){   // 等级
	            $rankList = M('member_card_rank')->where(array('companyid'=>$this->companyid))->field('id,name,reportnumber')->order('beginscore ASC')->select();
	            $lableReturn = 1;
	        }elseif($type == 'gender'){   // 性别
	            $lableList = $list['gender'];
	            $json = M('report_member_system_group')->where(array('companyid'=>$this->companyid))->getField('gender');
	            $lableReturn = 1;
	        }elseif($type == 'age'){   // 年龄
	            $type = 'agetag';
	            $lableList = $list['age'];
	            $json = M('report_member_system_group')->where(array('companyid'=>$this->companyid))->getField('age');
	            $lableReturn = 1;
	        }elseif($type == 'constellation'){   // 星座
	            $type = 'constellationtag';
	            $lableList = $list['constellation'];
	            $json = M('report_member_system_group')->where(array('companyid'=>$this->companyid))->getField('constellation');
	            $lableReturn = 1;
	        }elseif($type == 'subscribetype'){   // 微信关注
	            $lableList = $list['subscribetype'];
	            $json = M('report_member_system_group')->where(array('companyid'=>$this->companyid))->getField('subscribetype');
	            $lableReturn = 1;
	        }elseif($type == 'howlongspending'){   // 多久未消费
	            $lableList = $list['howlongspending'];
	            $json = M('report_member_system_group')->where(array('companyid'=>$this->companyid))->getField('howlongspending');
	            $lableReturn = 1;
	        }elseif($type == 'spendingfrequency'){   // 年消费频次
	            $lableList = $list['spendingfrequency'];
	            $json = M('report_member_system_group')->where(array('companyid'=>$this->companyid))->getField('spendingfrequency');
	            $lableReturn = 1;
	        }elseif($type == 'totalspending'){   // 累计消费
	            $lableList = $list['totalspending'];
	            $json = M('report_member_system_group')->where(array('companyid'=>$this->companyid))->getField('totalspending');
	            $lableReturn = 1;
	        }elseif($type == 'howlongusevouchers'){   // 多久未使用卡券
	            $lableList = $list['howlongusevouchers'];
	            $json = M('report_member_system_group')->where(array('companyid'=>$this->companyid))->getField('howlongusevouchers');
	            $lableReturn = 1;
	        }elseif($type == 'usevouchersfrequency'){   // 年消费频次
	            $lableList = $list['usevouchersfrequency'];
	            $json = M('report_member_system_group')->where(array('companyid'=>$this->companyid))->getField('usevouchersfrequency');
	            $lableReturn = 1;
	        }
	        if($lableReturn){
	            $string = '';
	            if($type == 'grade'){   // 等级
	                if($rankList){
	                    foreach ($rankList as $key=>$val){
	                        $string .= '<tr>';
	                        $string .= '<td>';
	                        $string .= $val['name'];
	                        $string .= '</td>';
	                        $string .= '<td><a href="';
	                        $string .= U('Member/myClients',array($type=>','.$val['id'].','));
	                        $string .= '" class="tips">';
	                        $string .= $val['reportnumber']?$val['reportnumber']:'0';
	                        $string .= '</a></td>';
	                        $string .= '</tr>';
	                    }    
	                }else{
	                    $string .= '<tr><td class="content text-center" colspan="2">暂无数据</td></tr>';
	                }
	            }else{
	                if($json){
	                    $data = json_decode($json, true);
	                    foreach($lableList as $lKey=>$lVal){
	                        $id = $lVal['id'];
	                        $lableList[$lKey]['number'] = $data[$id];
	                        unset($id);
	                    }
	                }
	                if($lableList){
	                    foreach ($lableList as $key=>$val){
	                        $string .= '<tr>';
	                        $string .= '<td>';
	                        $string .= $val['name'];
	                        $string .= '</td>';
	                        $string .= '<td><a href="';
	                        $string .= U('Member/myClients',array($type=>','.$val['id'].','));
	                        $string .= '" class="tips">';
	                        $string .= $val['number']?$val['number']:'0';
	                        $string .= '</a></td>';
	                        $string .= '</tr>';
	                    }
	                }else{
	                    $string .= '<tr><td class="content text-center" colspan="2">暂无数据</td></tr>';
	                }
	            }    
	        }
	        if($string){
	            $returnData['code'] = 200;
	            $returnData['tips'] = '查询成功';
	            $returnData['html'] = $string;
	        }
	    }
	    echo json_encode($returnData); 
	}
	/**
	 * 
	 * 【异步】删除标签
	 * 
	 * @author Leo<1251868177@qq.com>
	 * @since  2016-10-26
	 */
	public function ajaxDeleteLabel(){
	    $returnData['code'] = 300;
	    $returnData['tips'] = '抱歉，服务器繁忙，请稍后重试';
	    $id = $this->_post('id');
	    if($id){
	        M()->startTrans();
	        $memberCount = M('member_register_info')->where(array('companyid'=>$this->companyid,'membertagsid'=>array('like','%,'.$id.',%')))->count();
	        if($memberCount){
	            $updateMember = M()->execute("UPDATE tp_member_register_info set `membertagsid` = REPLACE(`membertagsid`,',$id,',','),updatetime='".time()."' where companyid='".$this->companyid."' AND membertagsid like '%$id%';");
	        }else{
	            $updateMember = 1;
	        }
	        $groupLinkCount = M('member_group_link')->where(array('groupid'=>$id))->count();
	        if($groupLinkCount){
	            $updateGroupLink = M('member_group_link')->where(array('groupid'=>$id))->delete();
	        }else{
	            $updateGroupLink = 1;
	        }
	        $deleteGroup = M('member_group')->where(array('id'=>$id,'companyid'=>$this->companyid))->delete();
	        if($deleteGroup&&$updateGroupLink&&$updateMember){
	            M()->commit();
	            $returnData['code'] = 200;
	            $returnData['tips'] = '删除成功';
	        }else{
	            M()->rollback();
	            $returnData['tips'] = 'errorCode:300002';
	        }
	    }
	    echo json_encode($returnData);
	}
	/**
	 * 
	 * 【异步】编辑标签
	 * 
	 * @author Leo<1251868177@qq.com>
	 * @since  2016-10-26
	 */
	public function ajaxEditLabel(){
	    $returnData['code'] = 300;
	    $returnData['tips'] = '抱歉，服务器繁忙，请稍后重试';
	    $time = time();
	    $id = $this->_post('id');
	    $name = $this->_post('name');
	    
	    $data['name'] = $name?$name:'';
	    $data['updatetime'] = $time;
	    if($id){
	        $where['companyid'] = $this->companyid;
	        $where['id'] = $id;
	        $return = M('member_group')->where($where)->save($data);
	    }else{
	        $data['id'] = guidNow();
	        $data['companyid'] = $this->companyid;
	        $data['createtime'] = $time;
	        $return = M('member_group')->add($data);
	    }
	    if($return){
	        $returnData['code'] = 200;
	        $returnData['tips'] = '编辑成功';
	    }
	    echo json_encode($returnData);
	}
	
}
?>