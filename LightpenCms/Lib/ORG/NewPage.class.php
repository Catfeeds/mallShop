<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2009 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// |         lanfengye <zibin_5257@163.com>
// +----------------------------------------------------------------------

class NewPage {
    
    // 分页栏每页显示的页数
    public $rollPage = 5;
    // 页数跳转时要带的参数
    public $parameter  ;
    // 分页URL地址
    public $url     =   '';
    // 默认列表每页显示行数
    public $listRows = 20;
    // 起始行数
    public $firstRow    ;
    // 分页总页面数
    protected $totalPages  ;
    // 总行数
    protected $totalRows  ;
    // 当前页数
    protected $nowPage    ;
    // 分页的栏的总页数
    protected $coolPages   ;
    // 分页显示定制
    protected $config  =    array('header'=>'条记录','prev'=>'上一页','next'=>'下一页','first'=>'第一页','last'=>'最后一页','theme'=>' %totalRow% %header% %nowPage%/%totalPage% 页 %upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%');
    // 默认分页变量名
    protected $varPage;
    /**
     * 架构函数
     * @access public
     * @param array $totalRows  总的记录数
     * @param array $listRows  每页显示记录数
     * @param array $parameter  分页跳转的参数
     */
    public function __construct($totalRows,$listRows='',$parameter='',$url='') {
    	$this->config['header'] = L('Records');
    	$this->config['prev'] = L('PrevPage');
    	$this->config['next'] = L('NextPage');
    	$this->config['first'] = L('FirstPage');
    	$this->config['last'] = L('LastPage');
    	$this->config['page'] = L('PageNum');
    	$this->config['theme'] = '%totalRow% %header% %nowPage%/%totalPage% '.L('Page').' %upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%';
        $this->totalRows    =   $totalRows;
        $this->parameter    =   $parameter;
        $this->varPage      =   C('VAR_PAGE') ? C('VAR_PAGE') : 'p' ;//分页参数
        if(!empty($listRows)) {
            $this->listRows =   intval($listRows);
        }
        $this->totalPages   =   ceil($this->totalRows/$this->listRows);     //总页数
        $this->coolPages    =   ceil($this->totalPages/$this->rollPage);  //每5页一栏，查看有多少栏
        $this->nowPage      =   !empty($_GET[$this->varPage])?intval($_GET[$this->varPage]):1; //当前分页
        if($this->nowPage<1){
            $this->nowPage  =   1;
        }elseif(!empty($this->totalPages) && $this->nowPage>$this->totalPages) {
            $this->nowPage  =   $this->totalPages;
        }
        $this->firstRow     =   $this->listRows*($this->nowPage-1); //当前页第一条数据
    }
	
    public function setConfig($name,$value) {
        if(isset($this->config[$name])) {
            $this->config[$name]    =   $value;
        }
    }

    /**
     * 分页显示输出
     * @access public
     */
    public function show() {
        if(0 == $this->totalRows) return '';
        $p              =   $this->varPage;
        $nowCoolPage    =   ceil($this->nowPage/$this->rollPage);

        // 分析分页参数
        if($this->url){
            $depr       =   C('URL_PATHINFO_DEPR');
            $url        =   rtrim(U('/'.$this->url,'',false),$depr).$depr.'__PAGE__';
        }else{
            if($this->parameter && is_string($this->parameter)) {
                parse_str($this->parameter,$parameter);
            }elseif(is_array($this->parameter)){
                $parameter      =   $this->parameter;
            }elseif(empty($this->parameter)){
                unset($_GET[C('VAR_URL_PARAMS')]);
                $var =  $_REQUEST;//!empty($_POST)?$_POST:$_GET;
                if(empty($var)) {
                    $parameter  =   array();
                }else{
                    $parameter  =   $var;
                }
            }
            $parameter[$p]  =   '__PAGE__';
            $url            =   U('',$parameter);
        }
        //上下翻页字符串
        $upRow          =   $this->nowPage-1;
        $downRow        =   $this->nowPage+1;
        if($this->totalPages>5){
            $showNum = 5;
        }else{
            $showNum = $this->totalPages;
        }
        $pageHtml = '<div class="group pagination text-right pb-10"><span class="item-count">共'.$this->totalRows.'条记录</span><ul>';
        if($this->totalPages > 1){
        	if($this->nowPage!=1){
        		$pageHtml .='<li><a href="'.str_replace('__PAGE__',$upRow,$url).'"><i class="page-prev-icon"></i></a></li>';
        	}
        	if($this->totalPages < 6 ){
        		for ($page=1;$page<=$showNum;$page++){
        			if($this->nowPage == $page){
        				$pageHtml .='<li class="item-active"><a href="javascript:void(0);">'.$page.'</a></li>';
        			}else{
        				$pageHtml .='<li><a href="'.str_replace('__PAGE__',$page,$url).'" >'.$page.'</a></li>';
        			}
        		}
        	}else{
        		if ($this->totalPages <= $showNum) {
        			for ($page=1;$page<=$showNum;$page++){
        				if($this->nowPage == $page){
        					$pageHtml .='<li class="item-active"><a href="javascript:void(0);">'.$page.'</a></li>';
        				}else{
        					$pageHtml .='<li><a href="'.str_replace('__PAGE__',$page,$url).'" >'.$page.'</a></li>';
        				}
        			}
        		}else{
        			if($this->nowPage==1 || $this->nowPage<$showNum){
        				for ($page=1;$page<=$showNum;$page++){
        					if($this->nowPage == $page){
        						$pageHtml .='<li class="item-active"><a href="javascript:void(0);">'.$page.'</a></li>';
        					}else{
        						$pageHtml .='<li><a href="'.str_replace('__PAGE__',$page,$url).'" >'.$page.'</a></li>';
        					}
        				}
        				$pageHtml .='<li><span><b>···</b></span></li><li><a href="'.str_replace('__PAGE__',$this->totalPages,$url).'" >'.$this->totalPages.'</a><li>';
        			}elseif ($this->nowPage>=$showNum && $this->nowPage <= ($this->totalPages-$showNum)){
        				$pageHtml .='<li><a href="'.str_replace('__PAGE__',1,$url).'" >1</a></li><li><span><b>···</b></span></li>';
        				for ($page=($this->nowPage-2);$page<=($this->nowPage+2);$page++){
        					if($this->nowPage == $page){
        						$pageHtml .='<li class="item-active"><a href="javascript:void(0);">'.$page.'</a></li>';
        					}else{
        						$pageHtml .='<li><a href="'.str_replace('__PAGE__',$page,$url).'" >'.$page.'</a></li>';
        					}
        				}
        				$pageHtml .='<li><span><b>···</b></span></li><li><a href="'.str_replace('__PAGE__',$this->totalPages,$url).'" >'.$this->totalPages.'</a></li>';
        			}elseif ($this->nowPage==$this->totalPages || $this->nowPage>($this->totalPages-$showNum)){
        				$pageHtml .='<li><a href="'.str_replace('__PAGE__',1,$url).'" >1</a></li><li><span><b>···</b></span></li>';
        				for ($page=($this->totalPages-$showNum+1);$page<=$this->totalPages;$page++){
        					if($this->nowPage == $page){
        						$pageHtml .='<li class="item-active"><a href="javascript:void(0);">'.$page.'</a></li>';
        					}else{
        						$pageHtml .='<li><a href="'.str_replace('__PAGE__',$page,$url).'" >'.$page.'</a></li>';
        					}
        				}
        			}
        		}
        	}
        	if($this->nowPage!=$this->totalPages){
        		$pageHtml .='<li><a href="'.str_replace('__PAGE__',$downRow,$url).'"><i class="page-next-icon"></i></a></li>';
        	}
        	$pageHtml .= '</ul>';
        	if($this->totalPages > 5 ){
        		$pageHtml .= '<label class="page-go">跳转至 <input class="inline text-center pagenum" type="text" value="'.$this->nowPage.'"> 页 <i class="page-go-icon" data-page-url="'.str_replace('__PAGE__','',$url).'"></i></label>';
        		$pageHtml .= "<script>$(function(){ $('.page-go-icon').click(function(){ var page = $('.pagenum').val() ? $('.pagenum').val() :'1' ; window.location.href=$(this).attr('data-page-url')+page;});})</script>";
        	}
        }
       	$pageHtml .= '</div>';
        return $pageHtml;
    }
    /**
     * 分页显示输出
     * @access public
     */
    public function diyshow() {
    	if(0 == $this->totalRows) return '';
    	$p              =   $this->varPage;
    	$nowCoolPage    =   ceil($this->nowPage/$this->rollPage);
    
    	// 分析分页参数
    	if($this->url){
    		$depr       =   C('URL_PATHINFO_DEPR');
    		$url        =   rtrim(U('/'.$this->url,'',false),$depr).$depr.'__PAGE__';
    	}else{
    		if($this->parameter && is_string($this->parameter)) {
    			parse_str($this->parameter,$parameter);
    		}elseif(is_array($this->parameter)){
    			$parameter      =   $this->parameter;
    		}elseif(empty($this->parameter)){
    			unset($_GET[C('VAR_URL_PARAMS')]);
    			$var =  $_REQUEST;//!empty($_POST)?$_POST:$_GET;
    			if(empty($var)) {
    				$parameter  =   array();
    			}else{
    				$parameter  =   $var;
    			}
    		}
    		$parameter[$p]  =   '__PAGE__';
    		$url            =   U('',$parameter);
    	}
    	//上下翻页字符串
    	$upRow          =   $this->nowPage-1;
    	$downRow        =   $this->nowPage+1;
    	if($this->totalPages>5){
    		$showNum = 5;
    	}else{
    		$showNum = $this->totalPages;
    	}
    	$pageHtml = '<span class="item-count">共'.$this->totalRows.'条记录</span><ul>';
    	if($this->totalPages > 1){
    		if($this->nowPage!=1){
    			$pageHtml .='<li><a href="'.str_replace('__PAGE__',$upRow,$url).'"><i class="page-prev-icon"></i></a></li>';
    		}
    		if($this->totalPages < 6 ){
    			for ($page=1;$page<=$showNum;$page++){
    				if($this->nowPage == $page){
    					$pageHtml .='<li class="item-active"><a href="javascript:void(0);">'.$page.'</a></li>';
    				}else{
    					$pageHtml .='<li><a href="'.str_replace('__PAGE__',$page,$url).'" >'.$page.'</a></li>';
    				}
    			}
    		}else{
    			if ($this->totalPages <= $showNum) {
    				for ($page=1;$page<=$showNum;$page++){
    					if($this->nowPage == $page){
    						$pageHtml .='<li class="item-active"><a href="javascript:void(0);">'.$page.'</a></li>';
    					}else{
    						$pageHtml .='<li><a href="'.str_replace('__PAGE__',$page,$url).'" >'.$page.'</a></li>';
    					}
    				}
    			}else{
    				if($this->nowPage==1 || $this->nowPage<$showNum){
    					for ($page=1;$page<=$showNum;$page++){
    						if($this->nowPage == $page){
    							$pageHtml .='<li class="item-active"><a href="javascript:void(0);">'.$page.'</a></li>';
    						}else{
    							$pageHtml .='<li><a href="'.str_replace('__PAGE__',$page,$url).'" >'.$page.'</a></li>';
    						}
    					}
    					$pageHtml .='<li><span><b>···</b></span></li><li><a href="'.str_replace('__PAGE__',$this->totalPages,$url).'" >'.$this->totalPages.'</a><li>';
    				}elseif ($this->nowPage>=$showNum && $this->nowPage <= ($this->totalPages-$showNum)){
    					$pageHtml .='<li><a href="'.str_replace('__PAGE__',1,$url).'" >1</a></li><li><span><b>···</b></span></li>';
    					for ($page=($this->nowPage-2);$page<=($this->nowPage+2);$page++){
    						if($this->nowPage == $page){
    							$pageHtml .='<li class="item-active"><a href="javascript:void(0);">'.$page.'</a></li>';
    						}else{
    							$pageHtml .='<li><a href="'.str_replace('__PAGE__',$page,$url).'" >'.$page.'</a></li>';
    						}
    					}
    					$pageHtml .='<li><span><b>···</b></span></li><li><a href="'.str_replace('__PAGE__',$this->totalPages,$url).'" >'.$this->totalPages.'</a></li>';
    				}elseif ($this->nowPage==$this->totalPages || $this->nowPage>($this->totalPages-$showNum)){
    					$pageHtml .='<li><a href="'.str_replace('__PAGE__',1,$url).'" >1</a></li><li><span><b>···</b></span></li>';
    					for ($page=($this->totalPages-$showNum+1);$page<=$this->totalPages;$page++){
    						if($this->nowPage == $page){
    							$pageHtml .='<li class="item-active"><a href="javascript:void(0);">'.$page.'</a></li>';
    						}else{
    							$pageHtml .='<li><a href="'.str_replace('__PAGE__',$page,$url).'" >'.$page.'</a></li>';
    						}
    					}
    				}
    			}
    		}
    		if($this->nowPage!=$this->totalPages){
    			$pageHtml .='<li><a href="'.str_replace('__PAGE__',$downRow,$url).'"><i class="page-next-icon"></i></a></li>';
    		}
    		$pageHtml .= '</ul>';
    		if($this->totalPages > 5 ){
    			$pageHtml .= '<label class="page-go">跳转至 <input class="inline text-center pagenum" type="text" value="'.$this->nowPage.'"> 页 <i class="page-go-icon" data-page-url="'.str_replace('__PAGE__','',$url).'"></i></label>';
    			$pageHtml .= "<script>$(function(){ $('.page-go-icon').click(function(){ var page = $('.pagenum').val() ? $('.pagenum').val() :'1' ; window.location.href=$(this).attr('data-page-url')+page;});})</script>";
    		}
    	}
    	return $pageHtml;
    }
    /**
     * 分页显示输出
     * @access public
     */
    public function oldshow2() {
    	if(0 == $this->totalRows) return '';
    	$p              =   $this->varPage;
    	$nowCoolPage    =   ceil($this->nowPage/$this->rollPage);
    
    	// 分析分页参数
    	if($this->url){
    		$depr       =   C('URL_PATHINFO_DEPR');
    		$url        =   rtrim(U('/'.$this->url,'',false),$depr).$depr.'__PAGE__';
    	}else{
    		if($this->parameter && is_string($this->parameter)) {
    			parse_str($this->parameter,$parameter);
    		}elseif(is_array($this->parameter)){
    			$parameter      =   $this->parameter;
    		}elseif(empty($this->parameter)){
    			unset($_GET[C('VAR_URL_PARAMS')]);
    			$var =  !empty($_POST)?$_POST:$_GET;
    			if(empty($var)) {
    				$parameter  =   array();
    			}else{
    				$parameter  =   $var;
    			}
    		}
    		$parameter[$p]  =   '__PAGE__';
    		$url            =   U('',$parameter);
    	}
    	//上下翻页字符串
    	$upRow          =   $this->nowPage-1;
    	$downRow        =   $this->nowPage+1;
    	if($this->totalPages>10){
    		$showNum = 10;
    	}else{
    		$showNum = $this->totalPages;
    	}
    	$pageHtml = '<div class="pagination">';
    	if($this->totalPages > 1){
    		if($this->nowPage==1){
    			$pageHtml .='<a href="javascript:void(0);" class="noclick">上一页</a>';
    		}else{
    			$pageHtml .='<a href="'.str_replace('__PAGE__',$upRow,$url).'" >上一页</a>';
    		}
    		if ($this->totalPages <= $showNum) {
    			for ($page=1;$page<=$showNum;$page++){
    				if($this->nowPage == $page){
    					$pageHtml .='<a href="javascript:void(0);" class="current">'.$page.'</a>';
    				}else{
    					$pageHtml .='<a href="'.str_replace('__PAGE__',$page,$url).'" >'.$page.'</a>';
    				}
    			}
    		}else{
    			if($this->nowPage==1 || $this->nowPage<$showNum){
    				for ($page=1;$page<=$showNum;$page++){
    					if($this->nowPage == $page ){
    						$pageHtml .='<a href="javascript:void(0);" class="current">'.$page.'</a>';
    					}else{
    						$pageHtml .='<a href="'.str_replace('__PAGE__',$page,$url).'" >'.$page.'</a>';
    					}
    				}
    				$pageHtml .='<span>...</span><a href="'.str_replace('__PAGE__',$this->totalPages,$url).'" >'.$this->totalPages.'</a>';
    			}elseif ($this->nowPage>=$showNum && $this->nowPage <= ($this->totalPages-$showNum)){
    				$pageHtml .='<a href="'.str_replace('__PAGE__',1,$url).'" >1</a><span>...</span>';
    				for ($page=($this->nowPage-$showNum/2);$page<=($this->nowPage+$showNum/2);$page++){
    					if($this->nowPage == $page){
    						$pageHtml .='<a href="javascript:void(0);" class="current">'.$page.'</a>';
    					}else{
    						$pageHtml .='<a href="'.str_replace('__PAGE__',$page,$url).'" >'.$page.'</a>';
    					}
    				}
    				$pageHtml .='<span>...</span><a href="'.str_replace('__PAGE__',$this->totalPages,$url).'" >'.$this->totalPages.'</a>';
    			}elseif ($this->nowPage==$this->totalPages || $this->nowPage>($this->totalPages-$showNum)){
    				$pageHtml .='<a href="'.str_replace('__PAGE__',1,$url).'" >1</a><span>...</span>';
    				for ($page=($this->totalPages-$showNum);$page<=$this->totalPages;$page++){
    					if($this->nowPage == $page){
    						$pageHtml .='<a href="javascript:void(0);" class="current">'.$page.'</a>';
    					}else{
    						$pageHtml .='<a href="'.str_replace('__PAGE__',$page,$url).'" >'.$page.'</a>';
    					}
    				}
    			}
    		}
    		if($this->nowPage==$this->totalPages){
    			$pageHtml .='<a href="javascript:void(0);" class="noclick">下一页</a>';
    		}else{
    			$pageHtml .='<a href="'.str_replace('__PAGE__',$downRow,$url).'" >下一页</a>';
    		}
    	}
    	$pageHtml .= '<span class="current3">共'.$this->totalPages.'页</span></div>';
    	return $pageHtml;
    }
    /**
     * 分页显示输出
     * @access public
     */
    public function oldshow() {
        if(0 == $this->totalRows) return '';
        $p              =   $this->varPage;
        $nowCoolPage    =   ceil($this->nowPage/$this->rollPage);
    
        // 分析分页参数
        if($this->url){
            $depr       =   C('URL_PATHINFO_DEPR');
            $url        =   rtrim(U('/'.$this->url,'',false),$depr).$depr.'__PAGE__';
        }else{
            if($this->parameter && is_string($this->parameter)) {
                parse_str($this->parameter,$parameter);
            }elseif(is_array($this->parameter)){
                $parameter      =   $this->parameter;
            }elseif(empty($this->parameter)){
                unset($_GET[C('VAR_URL_PARAMS')]);
                $var =  !empty($_POST)?$_POST:$_GET;
                if(empty($var)) {
                    $parameter  =   array();
                }else{
                    $parameter  =   $var;
                }
            }
            $parameter[$p]  =   '__PAGE__';
            $url            =   U('',$parameter);
        }
        //上下翻页字符串
        $upRow          =   $this->nowPage-1;
        $downRow        =   $this->nowPage+1;
        if ($upRow>0){
            $upPage     =   "<a href='".str_replace('__PAGE__',$upRow,$url)."'>".$this->config['prev']."</a>";
        }else{
            $upPage     =   '';
        }
    
        if ($downRow <= $this->totalPages){
            $downPage   =   "<a href='".str_replace('__PAGE__',$downRow,$url)."'>".$this->config['next']."</a>";
        }else{
            $downPage   =   '';
        }
        // << < > >>
        if($nowCoolPage == 1){
            $theFirst   =   '';
            $prePage    =   '';
        }else{
            $preRow     =   $this->nowPage-$this->rollPage;
            $prePage    =   "<a href='".str_replace('__PAGE__',$preRow,$url)."' >".L('PageGoForward').L('Space').$this->rollPage.L('Page')."</a>";
            $theFirst   =   "<a href='".str_replace('__PAGE__',1,$url)."' >".$this->config['first']."</a>";
        }
        if($nowCoolPage == $this->coolPages){
            $nextPage   =   '';
            $theEnd     =   '';
        }else{
            $nextRow    =   $this->nowPage+$this->rollPage;
            $theEndRow  =   $this->totalPages;
            $nextPage   =   "<a href='".str_replace('__PAGE__',$nextRow,$url)."' >".L('PageGoBack').L('Space').$this->rollPage.L('Page')."</a>";
            $theEnd     =   "<a href='".str_replace('__PAGE__',$theEndRow,$url)."' >".$this->config['last']."</a>";
        }
        // 1 2 3 4 5
        $linkPage = "";
        for($i=1;$i<=$this->rollPage;$i++){
            $page       =   ($nowCoolPage-1)*$this->rollPage+$i;
            if($page!=$this->nowPage){
                if($page<=$this->totalPages){
                    $linkPage .= "<a href='".str_replace('__PAGE__',$page,$url)."'>".$page."</a>";
                }else{
                    break;
                }
            }else{
                if($this->totalPages != 1){
                    $linkPage .= "<span class='current'>".$page."</span>";
                }
            }
        }
        $pageStr     =   str_replace(
                array('%header%','%nowPage%','%totalRow%','%totalPage%','%upPage%','%downPage%','%first%','%prePage%','%linkPage%','%nextPage%','%end%'),
                array($this->config['header'],$this->nowPage,$this->totalRows,$this->totalPages,$upPage,$downPage,$theFirst,$prePage,$linkPage,$nextPage,$theEnd),$this->config['theme']);
        return $pageStr;
    }

}
