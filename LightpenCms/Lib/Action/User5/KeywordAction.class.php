<?php
/**
 * 微信触发关键词列表
 * Enter description here ...
 * @author yongfei.zhao
 *
 */
class KeywordAction extends UserAction{
	private $token;
	
	private $uid;
	
	private $companyid;
	
	private $shopsid;
	
	private $keywordModel;
	
	private $moduleList;
	
	private $moduleArray;
	
	private $moduleUrlArray;
	
	private $moduleSystemUrlArray;
	
	public function __construct(){
		parent::__construct();
		//检查公司配置
		$this->checkCompanyScrm5Permissions(8,TRUE);
		$this->companyid=session('cid');
		$this->shopsid=session('shopsid');
		$this->keywordModel = D('keyword');
		$this->wechatsModel = D('wechats');
		$this->token = M('wechats')->where(array('companyid'=>$this->companyid))->getField('token');
		if($this->token){
			$this->assign('token',$this->token);
		}else{
			$this->error(L('ServerBusyPrompt'));
		}
	}
	public function index(){
		$this->display();
	}
	/**
	 * 
	 * 关键词列表
	 * 
	 * @author Mark<1311013341@qq.com>
	 * @since  2016-8-5
	 */
	public function lists(){
		$this->makeTopUrl = $this->makeTopUrl_User(array($this->MANAGE_WELCOME,array('name'=>'我的API接入','url'=>U('Wechat/lists'),'rel'=>'','target'=>''),array('name'=>'微信自动回复设置','url'=>U('Other/set',array('wechatid'=>$this->_request('wechatid'))),'rel'=>'','target'=>''),array('name'=>'微信关键词回复','url'=>'','rel'=>'','target'=>'')));
		$this->wechatManage();
		$keywordWhere['companyid'] = $this->companyid;
		$keywordWhere['token'] = $this->token;
		if($this->_request('searchtype')){
			$searchtype = $this->_request('searchtype');
			if($searchtype == '1'){
				$keywordWhere['module'] = 'Text';
			}elseif($searchtype == '2'){
				$keywordWhere['module'] = 'News';
			}elseif($searchtype == '3'){
				$keywordWhere['module'] = 'Image';
			}elseif($searchtype == '4'){
				$keywordWhere['module'] = 'Voice';
			}elseif($searchtype == '5'){
				$keywordWhere['module'] = 'Video';
			}
			$this->assign('searchval',$searchtype);
		}
		if($this->_request('searchkeyword')){
			$keywordWhere['keyword'] = array('like','%'.$this->_request('searchkeyword').'%');
			$this->assign('searchkeyword',$this->_request('searchkeyword'));
		}
		$keywordList = M("keyword_list")->where($keywordWhere)->field('keyword,module,token,id,clicknum')->order('createtime DESC')->select();
		$this->assign('list',$keywordList);
		$this->display();
	}
	/**
	 * 
	 * 删除关键词
	 * 
	 * @author Mark<1311013341@qq.com>
	 * @since  2016-8-5
	 */
	public function Del(){
		$Textid = $this->_post('id');
		$result = M('keyword_list')->where(array('companyid'=>$this->companyid,'id'=>$Textid))->delete();
		if($result){
			$info['code'] = 200;
			$info['message'] = '删除成功！';
		}else{
			$info['code'] = 300;
			$info['message'] = '删除失败！';
		}
		echo json_encode($info);
	}
}
?>