<?php
/**
 * Sql  批量添加
 * 
 * @author    Lando<806728685@qq.com>
 * @since     2016-8-30
 * @version   1.0
 */
class SqlAction extends BaseAction{
    
    
    
    public function testzhengze(){
        //匹配中文、英文字符、数字、特殊符号全角除外
        /* echo $str  = '我是测ert试&爱上我jskHJ234D';
        $isMatched = preg_match_all('/[\u4e00-\u9fa5a-zA-Z0-9]+/', $str, $matches);
        print_r($matches);
        var_dump($isMatched, $matches); */
        //提取中文、英文字符、数字(带全、半角标点常用符号)
        echo $str  = '我&-@#测ert试&一下，_===提取jsk￥H想要！的J.2,中3英+p数!字?4字符/D。';
        preg_match_all('/[x{4e00}-\x{9fa5}a-zA-Z0-9]+/u', $str, $matches);
        print_r($matches);
        
        
    }
    
    
    public function testmoban(){
        $a = $this->WeChatTemplateMessageSend('3','ojd4KwlaNucqigflYk91fsl-BzQ0','20039','','',array(format_time(time(),'ymdhis'),'10','人工加积分','200'),'');
        var_dump($a);
        
    }
    public function delRyannInfo(){
        
        $mobile = $this->_get('mobile')?$this->_get('mobile'):'18616250318';
        $info = M('member_register_info')->where(array('moblie'=>$mobile,'companyid'=>'1'))->field('id')->find();
        session(null);
        if($info){ 
            $register = M('member_register_info')->where(array('id'=>$info['id']))->delete();
            $wechat = M('member_wechat_info')->where(array('mid'=>$info['id']))->delete();
            if($register){
                echo '注册信息删除成功<br/>';
            }
            if($wechat){
                echo '微信资料删除成功,继续测试请重新关注公众号';
            }
        }else{
            echo '找不到'.$mobile.'的注册信息';
        }
    
    
    }
    /**
     * 
     *  同步注册
     * 
     * @author Lando<806728685@qq.com>
     * @since  2016-9-21
     */
    public function testRegister() {
        
        /* $a = $this->sendSms('13564012907','你有一条新的短信','1186','【人来风】','','181818');
        print_r($a);
        exit(); */
        // 注意：先处理本站内注册，当本站内注册处理完成后再处理同步注册，以下为同步注册具体逻辑
       /*  require './LightpenCms/Lib/ORG/UcApi.Class.php';
        $_POST['username'] = 'mobiwind12';
        $_POST['password'] = 'Mobiwind888881';
        $_POST['email'] = strtolower(guidNow()).'@mail.net';// 这里的邮箱信息是必填项Ucenter无法设置非必填，目前规则是通过username来设置邮箱信息保证唯一性
        $reg = UcApi::reg($_POST['username'], $_POST['password'], $_POST['email']);
        if ($reg <= 0) {
            // 通过数据表记录错误日志
            echo (UcApi::getError());
        } else {
            // 注册文档接下来继续处理其他逻辑
            echo ('注册成功');
        } */
    }
    
    /**
     *
     * 拉粉码 Sql
     *
     * @author Lando<806728685@qq.com>
     * @since  2016-8-30
     */
    public function lafenmasql(){
        echo '已完成';exit();
        echo '######################### 粉码 SQL 开始#######################################<br/>';
        $sql2 = M()->execute("ALTER TABLE `tp_quick_response_code` ADD COLUMN `userid` INT(11) DEFAULT 0 NOT NULL COMMENT '子账号的ID' AFTER `shopsid`; ");
        echo $sql2.'这是第2个sql<br/>';
        $sql1 = M()->execute("ALTER TABLE `tp_quick_response_code` ADD COLUMN `isboss` TINYINT(1) DEFAULT 0 NOT NULL COMMENT '是否超级管理员；1：是；2：否；默认：0；' AFTER `userid`;");
        echo $sql1.'这是第1个sql<br/>';
        $sql3 = M()->execute("ALTER TABLE `tp_quick_response_code` ADD COLUMN `background` VARCHAR(100) DEFAULT '' NOT NULL COMMENT '背景图' AFTER `dimension`; ");
        echo $sql3.'这是第3个sql<br/>';
        $sql4 = M()->execute("ALTER TABLE `tp_quick_response_code` ADD COLUMN `issave` TINYINT(1) DEFAULT 0 NOT NULL COMMENT '是否需要修改，1：是；2：否；默认：0；' AFTER `isboss`; ");
        echo $sql4.'这是第4个sql<br/>';
        $sql5 = M()->execute("ALTER TABLE `tp_quick_response_code` ADD COLUMN `quickimg` VARCHAR(500) DEFAULT ',' NOT NULL COMMENT '合成后的关注二维码' AFTER `issave`; ");
        echo $sql5.'这是第5个sql<br/>';
        $sql6 = M()->execute("ALTER TABLE `tp_quick_response_code` CHANGE `isboss` `isboss` TINYINT(1) DEFAULT 2 NOT NULL COMMENT '是否超级管理员；1：是；2：否；默认：2；';");
        echo $sql6.'这是第6个sql<br/>';
        echo '######################### 粉码 SQL 结束#######################################<br/>';
    }
    /**
     *
     * 会员卡券购买 Sql
     *
     * @author Lando<806728685@qq.com>
     * @since  2016-8-30
     */
    public function memberVoucherssql(){
        echo '已完成';exit();
        echo '######################### 我的注册会员 SQL 开始#######################################<br/>';
        $sql1 = M()->execute("ALTER TABLE `tp_mall_goods` CHANGE `id` `id` VARCHAR(30) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '0' NOT NULL COMMENT '主键ID（系统随机生成）', CHANGE `goodtype` `goodtype` TINYINT(2) DEFAULT 0 NOT NULL COMMENT '商品类型：1：实物商品，2：券商品；3：计次卡商品；4：团购商品；5：门票商品；6：权益卡商品；默认：0；', ADD COLUMN `usetimelimittype` TINYINT(2) DEFAULT 1 NULL COMMENT '使用时间限制类型：1：购买后几日内有效精确到时分秒在支付完成时间的基础上加上填写的天数时间；2：按照时间段进行限制精确到时分秒例如2016-10-21至2016-10-22，那就是2016-10-21 00:00:00 至2016-10-22 23:59:59有效；' AFTER `isgroupon`, ADD COLUMN `usetimelimitset` VARCHAR(50) DEFAULT '0' NULL COMMENT '使用时间限制设置：1：购买后几日内数据存储为罗马数字1/2/34/99...;2:按照时间段进行限制储存为array(\'begintime\'=>\'2016-10-20\',\'enttime\'=>\'2016-10-22\')的JSON字符串；3：限制选定的某一天内存储格式为2016-10-22；默' AFTER `usetimelimittype`, ADD COLUMN `useshopslimitset` VARCHAR(5000) DEFAULT ',' NULL COMMENT '使用门店限制设置：数据存储为门店ID拼接的字符串，例如：,1,2,3,12,34,15,注意前后都需要\",\"开头结尾，防止1,11,111，会出现错误数据，筛选时需要使用\",1,\"进行筛选；默认：0；' AFTER `usetimelimitset`, ADD COLUMN `backorderpolicyset` VARCHAR(20) DEFAULT ',' NULL COMMENT '退单政策：1：随时退；2：过期退；默认：,；数据存储样例：,1,2,' AFTER `useshopslimitset`, ADD COLUMN `useinfo` VARCHAR(3000) DEFAULT '' NULL COMMENT '卡券类商品使用说明' AFTER `backorderpolicyset`;");
        echo $sql1.'这是第1个sql<br/>';
        $sql2 = M()->execute("ALTER TABLE `tp_mall_goods` COMMENT='商品主表';");
        echo $sql2.'这是第2个sql<br/>';
        $sql3 = M()->execute("ALTER TABLE `tp_mall_goods` CHANGE `usetimelimitset` `usetimelimitset` VARCHAR(100) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '0' NULL COMMENT '使用时间限制设置：1：购买后几日内数据存储为罗马数字1/2/34/99...;2:按照时间段进行限制储存为array(\'begintime\'=>\'2016-10-20\',\'enttime\'=>\'2016-10-22\')的JSON字符串；3：限制选定的某一天内存储格式为2016-10-22；默';");
        echo $sql3.'这是第3个sql<br/>';
        $sql4 = M()->execute("ALTER TABLE `tp_mall_order_info` CHANGE `goodtype` `goodtype` TINYINT(2) DEFAULT 0 NOT NULL COMMENT '商品类型：1：实物商品，2：券商品；3：计次卡商品；4：团购商品；5：门票商品；6：权益卡商品；默认：0；', CHANGE `ordertype` `ordertype` TINYINT(2) DEFAULT 0 NOT NULL COMMENT '订单类型：1：实物商品订单(实物商品+提货券商品因为都有发货流程)，2：券商品订单；3：计次卡商品订单；4：团购商品订单；5：门票商品订单；6：权益卡商品订单；默认：0；', CHANGE `orderprice` `orderprice` DECIMAL(12,2) DEFAULT 0.00 NOT NULL COMMENT '实付金额', CHANGE `orderderateprice` `orderderateprice` DECIMAL(12,2) DEFAULT 0.00 NOT NULL COMMENT '订单优惠金额', CHANGE `ordersubtotal` `ordersubtotal` DECIMAL(12,2) DEFAULT 0.00 NOT NULL COMMENT '订单金额', COMMENT='订单主表';");
        echo $sql4.'这是第4个sql<br/>';
        $sql5 = M()->execute("ALTER TABLE `tp_mall_order_info` CHANGE `orderstatus` `orderstatus` TINYINT(2) DEFAULT 0 NOT NULL COMMENT '订单状态：1:待付款；2：待发货；3：配送中；4：已签收；5：已取消；6：卡券已发送；7：确认到账中；8：退货退款；9：到期退单已退单；10：随时退单已退单；默认：0；';");
        echo $sql5.'这是第5个sql<br/>';
        $sql6 = M()->execute("ALTER TABLE `tp_mall_order_goods` CHANGE `id` `id` VARCHAR(30) CHARSET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '主键ID（系统随机生成）', CHANGE `goodtype` `goodtype` TINYINT(2) DEFAULT 0 NOT NULL COMMENT '商品类型：1：实物商品，2：券商品；3：计次卡商品；4：团购商品；5：门票商品；6：权益卡商品；默认：0；', ADD COLUMN `usetimelimittype` TINYINT(2) DEFAULT 1 NULL COMMENT '使用时间限制类型：1：购买后几日内有效精确到时分秒在支付完成时间的基础上加上填写的天数时间；2：按照时间段进行限制精确到时分秒例如2016-10-21至2016-10-22，那就是2016-10-21 00:00:00 至2016-10-22 23:59:59有效；' AFTER `goodweight`, ADD COLUMN `usetimelimitset` VARCHAR(50) DEFAULT '0' NULL COMMENT '使用时间限制设置：1：购买后几日内数据存储为罗马数字1/2/34/99...;2:按照时间段进行限制储存为array(\'begintime\'=>\'2016-10-20\',\'enttime\'=>\'2016-10-22\')的JSON字符串；3：限制选定的某一天内存储格式为2016-10-22；默' AFTER `usetimelimittype`, ADD COLUMN `useshopslimitset` VARCHAR(5000) DEFAULT ',' NULL COMMENT '使用门店限制设置：数据存储为门店ID拼接的字符串，例如：,1,2,3,12,34,15,注意前后都需要\",\"开头结尾，防止1,11,111，会出现错误数据，筛选时需要使用\",1,\"进行筛选；默认：0；' AFTER `usetimelimitset`, ADD COLUMN `backorderpolicyset` VARCHAR(20) DEFAULT ',' NULL COMMENT '退单政策：1：随时退；2：过期退；默认：,；数据存储样例：,1,2,' AFTER `useshopslimitset`, ADD COLUMN `useinfo` VARCHAR(3000) DEFAULT '' NULL COMMENT '卡券类商品使用说明' AFTER `backorderpolicyset`, ADD COLUMN `skuname` VARCHAR(50) DEFAULT '' NULL COMMENT '规格内容' AFTER `useinfo`, ADD COLUMN `skusaleprice` DECIMAL(12,2) DEFAULT 0.00 NULL COMMENT 'SKU 售价' AFTER `skuname`, ADD COLUMN `skuimgurl` VARCHAR(400) DEFAULT '' NULL COMMENT 'SKU 图片' AFTER `skusaleprice`, COMMENT='订单商品表';");
        echo $sql6.'这是第6个sql<br/>';
        $sql8 = M()->execute("ALTER TABLE `tp_mall_order_goods` DROP COLUMN `skusaleprice`, DROP COLUMN `skuimgurl`, CHANGE `skuname` `goodskuname` VARCHAR(50) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NULL COMMENT '规格内容';");
        echo $sql8.'这是第8个sql<br/>';
        $sql9 = M()->execute("ALTER TABLE `tp_mall_order_goods` CHANGE `usetimelimitset` `usetimelimitset` VARCHAR(100) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '0' NULL COMMENT '使用时间限制设置：1：购买后几日内数据存储为罗马数字1/2/34/99...;2:按照时间段进行限制储存为array(\'begintime\'=>\'2016-10-20\',\'enttime\'=>\'2016-10-22\')的JSON字符串；3：限制选定的某一天内存储格式为2016-10-22；默'; ");
        echo $sql9.'这是第9个sql<br/>';
        $sql10 = M()->execute("ALTER TABLE `tp_mall_order_info` ADD COLUMN `truegoodtype` TINYINT(2) DEFAULT 0 NOT NULL COMMENT '实物商品订单类型：1：实物商品；2：提货券商品；默认：0；' AFTER `ordertype`; ");
        echo $sql10.'这是第10个sql<br/>';
        $sql11 = M()->execute("ALTER TABLE `tp_member_vouchers` ADD COLUMN `mallordergoodsid` VARCHAR(30) DEFAULT '0' NULL COMMENT '关联商城订单商品ID，用于在订单详情页面可以直接点击查看关联卡券进入卡券详情页，关联tp_mall_order_goods表id' AFTER `shopid`, ADD COLUMN `usetimelimittype` TINYINT(2) DEFAULT 1 NOT NULL COMMENT '使用时间限制类型：1：购买后几日内有效精确到时分秒在支付完成时间的基础上加上填写的天数时间；2：按照时间段进行限制精确到时分秒例如2016-10-21至2016-10-22，那就是2016-10-21 00:00:00 至2016-10-22 23:59:59有效；' AFTER `usetime`, ADD COLUMN `useshopslimitset` VARCHAR(5000) DEFAULT ',' NOT NULL COMMENT '使用门店限制设置：数据存储为门店ID拼接的字符串，例如：,1,2,3,12,34,15,注意前后都需要\",\"开头结尾，防止1,11,111，会出现错误数据，筛选时需要使用\",1,\"进行筛选；默认：0；' AFTER `usetimelimittype`, ADD COLUMN `backorderpolicyset` VARCHAR(20) DEFAULT ',' NOT NULL COMMENT '退单政策：1：随时退；2：过期退；默认：,；数据存储样例：,1,2,' AFTER `useshopslimitset`, ADD COLUMN `useinfo` VARCHAR(3000) DEFAULT '' NOT NULL COMMENT '卡券类商品使用说明' AFTER `backorderpolicyset`, ADD COLUMN `usenumberlimit` SMALLINT(5) DEFAULT 0 NOT NULL COMMENT '计次卡可使用次数限制' AFTER `useinfo`, ADD COLUMN `usednumber` SMALLINT(5) DEFAULT 0 NOT NULL COMMENT '计次卡当前已使用次数' AFTER `usenumberlimit`, ADD COLUMN `vouchername` VARCHAR(200) DEFAULT '' NOT NULL COMMENT '卡券名称/计次卡名称/门票名称 等等' AFTER `usednumber`, ADD COLUMN `voucherskuname` VARCHAR(50) DEFAULT '' NOT NULL COMMENT '团购/门票等的SKU名称' AFTER `vouchername`, ADD COLUMN `vouchertype` TINYINT(1) DEFAULT 2 NOT NULL COMMENT '卡券类型：2：普通卡券；3：计次卡；4：团购券；5：门票；6：权益卡；默认：0；' AFTER `voucherskuname`, ADD COLUMN `handleruserid` VARCHAR(30) DEFAULT '0' NOT NULL COMMENT '卡券核销员工账号ID，关联tp_users表id' AFTER `vouchertype`, ADD COLUMN `handlerusername` VARCHAR(30) DEFAULT '' NOT NULL COMMENT '卡券核销员工姓名' AFTER `handleruserid`, ADD COLUMN `handlershopid` INT(11) DEFAULT 0 NOT NULL COMMENT '卡券核销门店ID，关联tp_company_shops表id' AFTER `handlerusername`, ADD COLUMN `handlershopname` VARCHAR(100) DEFAULT '' NOT NULL COMMENT '卡券核销门店名称' AFTER `handlershopid`; ");
        echo $sql11.'这是第11个sql<br/>';
        $sql12 = M()->execute("ALTER TABLE `tp_mall_goods_sku` CHANGE `name` `name` VARCHAR(200) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL COMMENT 'SKU 规格，计次卡可使用次数等', COMMENT='商品Sku表'; ");
        echo $sql12.'这是第12个sql<br/>';
        $sql14 = M()->execute("ALTER TABLE `tp_mall_order_info`
ADD INDEX `out_trade_no` (`out_trade_no`); ");
        echo $sql14.'这是第14个sql<br/>';
        $sql15 = M()->execute("ALTER TABLE `tp_mall_order_goods`
ADD INDEX `companyid` (`companyid`) ,
ADD INDEX `orderid` (`orderid`);");
        echo $sql15.'这是第15个sql<br/>';
        $sql16 = M()->execute("ALTER TABLE `tp_mall_order_info`
ADD INDEX `companyid` (`companyid`); ");
        echo $sql16.'这是第16个sql<br/>';
        $sql17 = M()->execute("ALTER TABLE `tp_mall_goods_sku`
ADD INDEX `companyid` (`companyid`) ,
ADD INDEX `goodid` (`goodid`); ");
        echo $sql17.'这是第17个sql<br/>';
        $sql18 = M()->execute("ALTER TABLE `tp_member_marketing_activities_voucher_info`
ADD INDEX `companyid` (`companyid`); ");
        echo $sql18.'这是第18个sql<br/>';
        /* $sql19 = M()->execute("insert  into `tp_wechat_event_type`(`id`,`fid`,`typename`,`triggering`,`page`,`url`,`data`,`isshow`,`type`) values (28,4,'任务处理通知','计次卡使用确认','计次卡消费扣次确认页','/index.php?g=Wap&m=MemberVouchers&a=meterCardConsumConfirm&companyid=','{"first":"\u8ba1\u6b21\u5361\u4f7f\u7528\u786e\u8ba4","content":[["\u4efb\u52a1\u540d\u79f0","",""],["\u901a\u77e5\u7c7b\u578b","",""]],"remark":[["\u5361\u5238\u540d\u79f0","",""]],"end":"\u60a8\u7684\u8ba1\u6b21\u5361\u6b63\u5728\u88ab\u4f7f\u7528\uff0c\u8bf7\u70b9\u51fb\u786e\u8ba4"}',1,1),(29,4,'任务处理通知','您已成功使用计次卡！','卡券包','/index.php?g=Wap&m=MemberVouchers&a=myVouchers&companyid=','{"first":"\u60a8\u5df2\u6210\u529f\u4f7f\u7528\u8ba1\u6b21\u5361\uff01","content":[["\u4efb\u52a1\u540d\u79f0","",""],["\u901a\u77e5\u7c7b\u578b","",""]],"remark":[["\u5361\u5238\u540d\u79f0","",""],["\u5269\u4f59\u53ef\u7528\u6b21\u6570","",""]]}',1,1);");
        echo $sql19.'这是第19个sql<br/>'; */
        echo '######################### 我的注册会员 SQL 结束#######################################<br/>';
    }
    
    /**
     *
     * 会员管理+标签+导入 Sql
     * 
     * @author Lando<806728685@qq.com>
     * @since  2016-8-30
     */
    public function memberInfosql(){
    	echo '######################### 我的注册会员 SQL 开始#######################################<br/>';
    	$sql1 = M()->execute("ALTER TABLE `tp_member_register_info` DROP COLUMN `test`;");
    	echo $sql1.'这是第1个sql<br/>';
    	$sql2 = M()->execute("ALTER TABLE `tp_member_register_info` CHANGE `isregister` `isregister` TINYINT(1) DEFAULT 0 NOT NULL COMMENT '是否注册；1：是(包含网页注册+老会员导入)，0；否，默认：0';");
    	echo $sql2.'这是第2个sql<br/>';
    	$sql3 = M()->execute("ALTER TABLE `tp_member_register_info` ADD COLUMN `registertypetag` TINYINT(1) DEFAULT 0 NOT NULL COMMENT '会员注册类型：1：网页注册；2：老会员导入；默认：0；' AFTER `isregister`; ");
    	echo $sql3.'这是第3个sql<br/>';
    	$sql4 = M()->execute("ALTER TABLE `tp_member_register_info` CHANGE `gender` `gender` TINYINT(1) DEFAULT 0 NOT NULL COMMENT '性别：0：未填写性别；1：男；2：女；默认：0；';");
    	echo $sql4.'这是第4个sql<br/>';
    	$sql5 = M()->execute("ALTER TABLE `tp_member_register_info` ADD COLUMN `agetag` TINYINT(2) DEFAULT 0 NOT NULL COMMENT '会员年龄段标签：1：10后；2：00后；3：90后；4：80后；5：70后；6：60后；7：50后及以上；默认：0（未填写生日）；' AFTER `accountbalance`; ");
    	echo $sql5.'这是第5个sql<br/>';
    	$sql6 = M()->execute("ALTER TABLE `tp_member_register_info` ADD COLUMN `constellationtag` TINYINT(2) DEFAULT 0 NOT NULL COMMENT '会员星座标签：1：（水瓶座1月20-2月18）；2：（双鱼座2月19-3月20）；3：白羊座（3月21-4月19）；4：金牛座（4月20-5月20）；5：双子座（5月21-6月21）；6：巨蟹座（6月22-7月22）；7：（狮子座7月23-8月22）；8：（处女座8月23-9月22）；9：（天秤座9月23-10月23）；10：（天蝎座10月24-11月22）；11：（射手座11月23-12月21）；12：（摩羯座12月22-1月19）；默认：0（未填写生日无星座）；' AFTER `agetag`; ");
    	echo $sql6.'这是第6个sql<br/>';
    	$sql7 = M()->execute("ALTER TABLE `tp_member_register_info` CHANGE `subscribetype` `subscribetype` TINYINT(1) DEFAULT 0 NOT NULL COMMENT '关联微信关注状态：1：已关注；2:取消关注；默认：0（未关注）；'; ");
    	echo $sql7.'这是第7个sql<br/>';
    	$sql8 = M()->execute("ALTER TABLE `tp_member_register_info` ADD COLUMN `howlongspendingtag` TINYINT(2) DEFAULT 0 NOT NULL COMMENT '多久未消费标签：1：2周未消费；2：1月未消费；3：2月未消费；4：3月未消费；5：半年未消费；6：1年未消费；默认：0（从未消费）；周按照7天计算，月按照3天计算，半年180按照天计算，一年按照365天计算；' AFTER `truepassword`; ");
    	echo $sql8.'这是第8个sql<br/>';
    	$sql9 = M()->execute("ALTER TABLE `tp_member_register_info` ADD COLUMN `spendingfrequencytag` TINYINT(1) DEFAULT 0 NOT NULL COMMENT '消费次数标签：1：1次消费；2：2-10次消费；3：11-50次消费；4：51次以上消费；默认：0（从未消费）；' AFTER `howlongspendingtag`; ");
    	echo $sql9.'这是第9个sql<br/>';
    	$sql10 = M()->execute("ALTER TABLE `tp_member_register_info` DROP COLUMN `constellation`;");
    	echo $sql10.'这是第10个sql<br/>';
    	$sql11 = M()->execute("ALTER TABLE `tp_member_register_info` ADD COLUMN `totalspendingtag` TINYINT(2) DEFAULT 0 NOT NULL COMMENT '累计消费额标签：1：1-200元消费（程序算法0.01-200.00）；2：201-500元消费（程序算法200.01-500.00）；3：501-1000元消费（程序算法500.01-1000.00）；4：1001-3000元消费（程序算法1000.01-3000.00）；5：3001-5000元消费（程序算法3000.01-5000.00）；6：5001-10000元消费（程序算法5000.01-10000.00）；7：10001元以上消费（程序算法10000.01-无穷大）；8：；默认：0（消费金额为0）；' AFTER `spendingfrequencytag`; ");
    	echo $sql11.'这是第11个sql<br/>';
    	$sql12 = M()->execute("ALTER TABLE `tp_member_register_info` CHANGE `note` `note` TEXT CHARSET utf8 COLLATE utf8_general_ci NULL COMMENT '会员备注信息，支持富文本编辑';");
    	echo $sql12.'这是第12个sql<br/>';
    	$sql13 = M()->execute("ALTER TABLE `tp_member_register_info` CHANGE `totalintegration` `totalintegration` DECIMAL(12,2) DEFAULT 0.00 NOT NULL COMMENT '会员可用积分'; ");
    	echo $sql13.'这是第13个sql<br/>';
    	$sql14 = M()->execute("ALTER TABLE `tp_member_register_info` CHANGE `totalexperiencevalue` `totalexperiencevalue` DECIMAL(12,2) DEFAULT 0.00 NOT NULL COMMENT '会员累计积分（由原来的累计经验值字段修改而来，字段名称不做修改）'; ");
    	echo $sql14.'这是第14个sql<br/>';
    	$sql15 = M()->execute("ALTER TABLE `tp_member_register_info` CHANGE `totalintegration` `totalintegration` INT(11) DEFAULT 0 NOT NULL COMMENT '会员可用积分', CHANGE `totalexperiencevalue` `totalexperiencevalue` INT(11) DEFAULT 0 NOT NULL COMMENT '会员累计积分（由原来的累计经验值字段修改而来，字段名称不做修改）';");
    	echo $sql15.'这是第15个sql<br/>';
    	$sql16 = M()->execute("ALTER TABLE `tp_member_register_info` CHANGE `membertagsid` `membertagsid` VARCHAR(4000) CHARSET utf8 COLLATE utf8_general_ci DEFAULT ',' NOT NULL COMMENT '会员关联自定义标签ID集合，通过\",\"进行连接，集合必须以\",\"开头结尾；例如,2,3,4,6, 默认：,；'; ");
    	echo $sql16.'这是第16个sql<br/>';
    	$sql17 = M()->execute("ALTER TABLE `tp_member_register_info` CHANGE `address` `address` VARCHAR(200) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NULL COMMENT '默认收货地址'; ");
    	echo $sql17.'这是第17个sql<br/>';
    	$sql18 = M()->execute("ALTER TABLE `tp_member_register_info` CHANGE `createtime` `createtime` INT(10) DEFAULT 0 NOT NULL COMMENT '创建时间（只关注未注册时时间无效，注册后时间更新）'; ");
    	echo $sql18.'这是第18个sql<br/>';
    	$sql19 = M()->execute("ALTER TABLE `tp_member_card_info` CHANGE `cardnum` `cardnum` INT(15) UNSIGNED ZEROFILL DEFAULT 00000000000 NOT NULL COMMENT '会员卡号(新会员卡号等同于手机号)'; ");
    	echo $sql19.'这是第19个sql<br/>';
    	$sql20 = M()->execute("ALTER TABLE `tp_member_group` CHANGE `id` `id` VARCHAR(30) DEFAULT '0' NOT NULL COMMENT 'id'; ");
    	echo $sql20.'这是第20个sql<br/>';
    	$sql21 = M()->execute("ALTER TABLE `tp_member_group` CHANGE `adduid` `adduid` VARCHAR(30) DEFAULT '0' NOT NULL COMMENT '添加uid'; ");
    	echo $sql21.'这是第21个sql<br/>';
    	$sql22 = M()->execute("ALTER TABLE `tp_member_group` CHANGE `edituid` `edituid` VARCHAR(30) DEFAULT '0' NOT NULL COMMENT '编辑uid'; ");
    	echo $sql22.'这是第22个sql<br/>';
    	$sql23 = M()->execute("ALTER TABLE `tp_member_group` CHANGE `companyid` `companyid` VARCHAR(30) DEFAULT '0' NOT NULL COMMENT '公司id';");
    	echo $sql23.'这是第23个sql<br/>';
    	$sql24 = M()->execute("ALTER TABLE `tp_member_group` CHANGE `shopsid` `shopsid` VARCHAR(30) DEFAULT '0' NOT NULL COMMENT '门店id';");
    	echo $sql24.'这是第24个sql<br/>';
    	$sql25 = M()->execute("ALTER TABLE `tp_member_integral` CHANGE `integralnum` `integralnum` INT(11) DEFAULT 0 NOT NULL COMMENT '积分数'; ");
    	echo $sql25.'这是第25个sql<br/>';
    	echo '----------------------Leo ----------------------';
    	$sql26 = M()->execute("ALTER TABLE `tp_member_card_rank` ADD COLUMN `reportnumber` VARCHAR(10) DEFAULT '' NOT NULL COMMENT '本等级人数' AFTER `desc`;");
    	echo $sql26.'这是第26个sql<br/>';
    	$sql27 = M()->execute("ALTER TABLE `tp_member_data_import_task` CHANGE `id` `id` VARCHAR(30) NOT NULL COMMENT '主键(系统自动生成)', CHANGE `status` `status` TINYINT(1) DEFAULT 1 NOT NULL COMMENT '记录状态  1:未开始  2:导入中；3:导入成功；4:导入失败 默认为:1';");
    	echo $sql27.'这是第27个sql<br/>';
    	$sql28 = M()->execute("CREATE TABLE `tp_report_member_system_group` (`id` varchar(30) NOT NULL DEFAULT '0',`adduid` int(11) NOT NULL DEFAULT '0' COMMENT '添加uid',`editid` int(11) NOT NULL DEFAULT '0' COMMENT '编辑uid',`shopsid` int(11) NOT NULL DEFAULT '0' COMMENT '门店id',`companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',`registertype` varchar(100) NOT NULL DEFAULT '' COMMENT '注册来源标签统计: 1:网页注册; 2:老会员导入; 数据储存格式JSON, 如：{\"1\":12,\"2\":20}',`gender` varchar(50) NOT NULL DEFAULT '' COMMENT '性别标签统计: 1:男; 2:女; 0:未填写性别; 数据格式JSON, 如:{\"1\":12,\"2\":88\"0\":234}',`age` varchar(500) NOT NULL DEFAULT '' COMMENT '会员年龄段标签统计: 1:10后; 2:00后; 3:90后; 4:80后; 5:70后; 6:60后; 7:50后及以上; 0:未填写生日; 数据格式JSON',`constellation` varchar(1200) NOT NULL COMMENT '星座标签: 1:水瓶座; 2:双鱼座; 3:白羊座; 4:金牛座; 5:双子座; 6:巨蟹座; 7:狮子座; 8:处女座; 9:天秤座; 10:天蝎座； 11:射手座; 12:摩羯座; 0:未填写星座; 数据格式JSON',`subscribetype` varchar(30) NOT NULL DEFAULT '' COMMENT '微信关注标签: 1:已关注; 2:取消关注; 0:未关注; 数据储存格式JSON',`howlongspending` varchar(500) NOT NULL DEFAULT '' COMMENT '多久未消费标签: 1:2周未消费; 2:1月未消费; 3:2月未消费; 4:3月未消费; 5:半年未消费; 6:1年未消费; 0:从未消费; 数据储存格式JSON',`spendingfrequency` varchar(500) NOT NULL DEFAULT '' COMMENT '消费次数标签: 1:1次消费; 2:2-10次消费; 3:11-50次消费; 4:51次以上消费; 0:从未消费; 数据储存格式JSON',`totalspending` varchar(500) NOT NULL DEFAULT '' COMMENT '累计消费额标签: 1:1-200元; 2:201-500元; 3:501-1000元; 4:1001-3000元; 5:3001-5000元; 6:5001-10000元; 7:10001元以上; 0:未消费; 数据储存格式JSON',`createtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',`updatetime` int(11) NOT NULL DEFAULT '0' COMMENT '编辑时间') ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='会员系统标签 统计报表'; ");
    	echo $sql28.'这是第28个sql<br/>';
    	$sql29 = M()->execute("CREATE TABLE `tp_log_member_data_import_task` (`id` varchar(30) NOT NULL COMMENT '主键(系统自动生成)',`log` varchar(2000) NOT NULL DEFAULT '' COMMENT '日志记录:本次导入的文件名, 中间用\",\"隔开',`createtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    	echo $sql29.'这是第29个sql<br/>';
    	$sql30 = M()->execute("INSERT INTO `tp_system_scrm5_permissions_list` (`id`, `name`, `desc`, `parentid`, `sort`, `isshow`, `isedit`, `updatetime`, `createtime`) VALUES ('27', '我的会员', '', '0', '12', '1', '1', '1473735650', '1473735650');");
    	echo $sql30.'这是第30个sql<br/>';
    	$sql31 = M()->execute("CREATE TABLE `tp_log_export_task` (
    	`id` varchar(30) DEFAULT NULL COMMENT '主键ID',
    	`cid` int(10) DEFAULT '0' COMMENT '公司ID',
    	`mid` varchar(30) DEFAULT '0' COMMENT '会员MID',
    	`log` longtext COMMENT '日志描述',
    	`createtime` int(11) DEFAULT '0' COMMENT '创建时间'
    	) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    	echo $sql31.'这是第31个sql<br/>';
    	$sql32 = M()->execute("UPDATE tp_member_register_info set isregister = 1 where moblie!='';");
    	echo $sql32.'这是第32个sql<br/>';
    	$sql33 = M()->execute("UPDATE tp_member_register_info set registertypetag = 1 where moblie!='';");
    	echo $sql33.'这是第33个sql<br/>';
    	$sql34 = M()->execute("ALTER TABLE `tp_member_group_link`
    	MODIFY COLUMN `id`  varchar(30) NOT NULL COMMENT 'id' FIRST ,
    	MODIFY COLUMN `groupid`  varchar(30) NOT NULL DEFAULT 0 COMMENT '分组id' AFTER `id`;");
    	echo $sql34.'这是第34个sql<br/>';
    	
    	
    	echo '######################### 我的注册会员 SQL 结束#######################################<br/>';
    }
    /**
     * 
     * 同步登陆
     * 
     * @author Lando<806728685@qq.com>
     * @since  2016-9-19
     */
    public function testLogin(){
        // 注意：先处理本站内登陆，当本站内登陆处理完成后再处理同步登陆，以下为同步登陆具体逻辑
        require './LightpenCms/Lib/ORG/UcApi.Class.php';//引入文件
        $_POST['username'] = 'mobiwind12';
        $_POST['password'] = 'Mobiwind888881';
        if (!empty($_POST['username']) && !empty($_POST['password'])) {
            $login = UcApi::login($_POST['username'], $_POST['password']);
            if ($login === FALSE) {
                // 通过数据表记录错误日志
                echo UcApi::getError();
            } else {
                echo $login['synlogin'];// 必须输出同步登录代码在页面上(一段JS，用于同步登陆，这步很重要)，否则无法同步登陆
                // 接下来继续处理其他逻辑
            }
        }
    }
   
    /**
     *
     * 闪惠 Sql
     *
     * @author Lando<806728685@qq.com>
     * @since  2016-8-30
     */
    public function shanhuiSql(){
        echo '######################### 手机预定优化 SQL 开始#######################################<br/>';
        $sql1 = M()->execute("INSERT INTO `tp_system_scrm5_permissions_list` (`id`,`name`, `isshow`) VALUES ('25','闪惠', '1');");
        echo $sql1.'这是第1个sql<br/>';
        $sql2 = M()->execute("UPDATE `tp_system_scrm5_permissions_list` SET `updatetime` = '1473735650' , `createtime` = '1473735650' WHERE `id` = '25'; ");
        echo $sql2.'这是第2个sql<br/>';
        $sql3 = M()->execute("ALTER TABLE `tp_company_shops` ADD COLUMN `isopenshanhui` TINYINT(1) DEFAULT 0 NOT NULL COMMENT '闪惠是否开启 1:是; 2:否; 默认:0' AFTER `isopenmobilebook`; ");
        echo $sql3.'这是第3个sql<br/>';
        $sql4 = M()->execute("ALTER TABLE `tp_dms_order` CHANGE `ordertype` `ordertype` TINYINT(1) DEFAULT 0 NOT NULL COMMENT '订单类别 1:微信商城订单；2:风助手门店收银订单；3:风助手储值收款订单；4:闪惠订单';");
        echo $sql4.'这是第4个sql<br/>';
        // $sql5 = M()->execute("INSERT INTO `tp_wechat_event_type` (`fid`, `typename`, `triggering`, `page`, `url`) VALUES ('7', '任务处理通知', '闪惠支付成功', '闪惠历史', '/index.php?g=Wap&m=ShanHui&a=historyList&companyid=');");
        // echo $sql5.'这是第5个sql<br/>';
        // $sql6 = M()->execute("INSERT INTO `tp_wechat_event_type` (`fid`, `typename`, `triggering`, `page`, `url`) VALUES ('7', '任务处理通知', '闪惠支付成功', '闪惠历史', '/index.php?g=Helper&m=ShanHui&a=index&companyid='); ");
        // echo $sql6.'这是第6个sql<br/>';
        $sql7 = M()->execute("CREATE TABLE `tp_shanhui_activity` ( `id` varchar(30) NOT NULL COMMENT '主键(系统随机生成)', `adduid` int(11) NOT NULL DEFAULT '0' COMMENT '添加uid', `edituid` int(11) NOT NULL DEFAULT '0' COMMENT '编辑uid', `shopsid` int(11) NOT NULL DEFAULT '0' COMMENT '门店id', `companyid` varchar(30) NOT NULL DEFAULT '' COMMENT '公司id', `shopid` varchar(30) NOT NULL DEFAULT '' COMMENT '门店id', `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '优惠活动类别  1:无优惠; 2:立减优惠; 3:立折优惠; 4:满减优惠; 5:满折优惠; 6:每满减优惠;', `weekstart` tinyint(1) NOT NULL DEFAULT '0' COMMENT '优惠活动开始周', `weekend` tinyint(1) NOT NULL DEFAULT '0' COMMENT '优惠活动结束周', `timestart` varchar(10) NOT NULL DEFAULT '00:00' COMMENT '优惠活动开始时间', `timeend` varchar(10) NOT NULL DEFAULT '00:59' COMMENT '优惠活动结束时间', `discount2money` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '立减优惠 -> 减', `discount3ratio` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '立折优惠 -> 折', `discount4qualified1` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '满减优惠 -> 满1', `discount4money1` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '满减优惠 -> 减1', `discount4qualified2` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '满减优惠 -> 满2', `discount4money2` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '满减优惠 -> 减2', `discount4qualified3` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '满减优惠 -> 满3', `discount4money3` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '满减优惠 -> 减3', `discount5qualified1` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '满折优惠 -> 满1', `discount5ratio1` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '满折优惠 -> 折1', `discount5qualified2` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '满折优惠 -> 满2', `discount5ratio2` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '满折优惠 -> 折2', `discount5qualified3` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '满折优惠 -> 满3', `discount5ratio3` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '满折优惠 -> 折3', `discount6qualified1` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '每满减优惠 -> 每满1', `discount6money1` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '每满减优惠 -> 减1', `discount6qualified2` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '每满减优惠 -> 每满2', `discount6money2` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '每满减优惠 -> 减2', `discount6qualified3` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '每满减优惠 -> 每满3', `discount6money3` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '每满减优惠 -> 减3', `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间', `updatetime` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间', PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8");
        echo $sql7.'这是第7个sql<br/>';
        $sql8 = M()->execute("CREATE TABLE `tp_shanhui_order` ( `id` varchar(30) NOT NULL COMMENT '主键(系统随机生成)', `adduid` int(11) NOT NULL DEFAULT '0' COMMENT '添加uid', `edituid` int(11) NOT NULL DEFAULT '0' COMMENT '编辑uid', `shopsid` int(11) NOT NULL DEFAULT '0' COMMENT '门店id', `companyid` varchar(30) NOT NULL DEFAULT '' COMMENT '公司id', `shopid` varchar(30) NOT NULL DEFAULT '' COMMENT '门店id', `mid` varchar(30) NOT NULL DEFAULT '' COMMENT 'MID', `mobile` varchar(20) NOT NULL DEFAULT '' COMMENT '手机号', `name` varchar(100) NOT NULL DEFAULT '' COMMENT '姓名', `orderid` varchar(50) NOT NULL DEFAULT '' COMMENT '订单号', `tradeid` varchar(50) NOT NULL DEFAULT '' COMMENT '交易号', `out_trade_no` varchar(50) NOT NULL DEFAULT '' COMMENT '商户订单号', `transaction_id` varchar(50) NOT NULL DEFAULT '' COMMENT '微信支付订单号(微信返回)', `receivables` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '消费总额(应收金额)', `nopreferential` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '不参与优惠金额', `actualamount` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '实付金额', `shanhuidiscount` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '闪惠活动优惠金额', `activitytype` tinyint(1) NOT NULL DEFAULT '0' COMMENT '闪惠优惠活动类型  1:无优惠; 2:立减优惠; 3:立折优惠; 4:满减优惠; 5:满折优惠; 6:每满减优惠;', `activityname` varchar(200) NOT NULL DEFAULT '' COMMENT '闪惠活动名称', `dmsdiscount` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT 'DMS优惠金额', `dmsorderid` varchar(30) NOT NULL DEFAULT '' COMMENT 'DMS订单号【tp_dms_order:orderid】', `paytime` int(11) NOT NULL DEFAULT '0' COMMENT '支付时间', `paytype` tinyint(1) NOT NULL DEFAULT '0' COMMENT '支付方式  1:微信支付; 2:储值支付', `paystate` tinyint(1) NOT NULL DEFAULT '1' COMMENT '支付状态  1:支付中; 2:支付成功; 3:支付失败; 默认:1', `paydonetime` int(11) NOT NULL DEFAULT '0' COMMENT '支付完成时间', `payopenid` varchar(50) NOT NULL DEFAULT '' COMMENT '支付OPENID', `note` varchar(2000) NOT NULL DEFAULT '' COMMENT '管理员备注', `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间', `updatetime` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间', PRIMARY KEY (`id`))ENGINE=InnoDB DEFAULT CHARSET=utf8");
        echo $sql8.'这是第8个sql<br/>';
        $sql9 = M()->execute("CREATE TABLE `tp_shanhui_setup` (`id` varchar(30) NOT NULL COMMENT '主键(系统随机生成)', `adduid` int(11) NOT NULL DEFAULT '0' COMMENT '添加uid', `edituid` int(11) NOT NULL DEFAULT '0' COMMENT '编辑uid', `shopsid` int(11) NOT NULL DEFAULT '0' COMMENT '门店id', `companyid` varchar(30) NOT NULL DEFAULT '' COMMENT '公司id', `shopid` varchar(30) NOT NULL DEFAULT '' COMMENT '门店id', `todaynum` int(11) NOT NULL DEFAULT '0' COMMENT '今天订单数(今天单量)', `totalnum` int(11) NOT NULL DEFAULT '0' COMMENT '累计订单数(累计单量)', `isopen` tinyint(1) NOT NULL DEFAULT '2' COMMENT '是否启用  1:是; 2:否; 默认:2', `printid` varchar(50) NOT NULL DEFAULT '' COMMENT '小票机id', `printkey` varchar(50) NOT NULL DEFAULT '' COMMENT '小票机key', `object` tinyint(1) NOT NULL DEFAULT '1' COMMENT '优惠对象  1:所有顾客; 2:仅限会员; 默认:1', `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '优惠活动类型  1:无优惠; 2:立减优惠; 3:立折优惠; 4:满减优惠; 5:满折优惠; 6:每满减优惠;', `content` text NOT NULL COMMENT '闪惠说明', `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间', `updatetime` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间', PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8");
        echo $sql9.'这是第9个sql<br/>';
        $sql10 = M()->execute("INSERT INTO `tp_wechat_event` (`id`,`name`, `isshow`) VALUES ('7','闪惠活动', '1'); ");
        echo $sql10.'这是第10个sql<br/>';
       /*  $sql10 = M()->execute("UPDATE `tp_wechat_event_type` SET `data` = '{"first":"\u65b0\u95ea\u60e0\u8ba2\u5355\u3002","content":[["\u4efb\u52a1\u540d\u79f0","",""],["\u901a\u77e5\u7c7b\u578b","",""]],"remark":[["\u6d88\u8d39\u91d1\u989d","",""],["\u5b9e\u4ed8\u91d1\u989d","",""]]}' , `isshow` = '1' , `type` = '2' WHERE `id` = '27';");
        echo $sql10.'这是第10个sql<br/>';
        $sql11 = M()->execute("UPDATE `tp_wechat_event_type` SET `data` = '{"first":"\u60a8\u5df2\u4f7f\u7528\u95ea\u60e0\u652f\u4ed8\u6210\u529f\uff01","content":[["\u4efb\u52a1\u540d\u79f0","",""],["\u901a\u77e5\u7c7b\u578b","",""]],"remark":[["\u6d88\u8d39\u91d1\u989d","",""],["\u5b9e\u4ed8\u91d1\u989d","",""]]}' , `isshow` = '1' , `type` = '1' WHERE `id` = '26';");
        echo $sql11.'这是第11个sql<br/>'; */
        echo '######################### 手机预定优化 SQL 结束#######################################<br/>';
    }
    
    
    /**
     *
     * 商户积分商城 Sql
     *
     * @author Lando<806728685@qq.com>
     * @since  2016-8-30
     */
    public function shopIntShopMallSql(){
        echo '######################### 手机预定优化 SQL 开始#######################################<br/>';
        $sql1 = M()->execute("CREATE TABLE `tp_mall_member_integral_base` (
  `id` varchar(30) NOT NULL DEFAULT '' COMMENT '主键ID（系统随机生成）',
  `adduid` int(11) DEFAULT '0' COMMENT '添加用户id',
  `edituid` int(11) DEFAULT '0' COMMENT '编辑用户id',
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  `shopsid` int(11) DEFAULT '0' COMMENT '分店id',
  `title` varchar(12) NOT NULL DEFAULT '' COMMENT '网页标题',
  `pic1` varchar(400) DEFAULT '' COMMENT '幻灯片1',
  `url1` varchar(400) DEFAULT '' COMMENT '链接1',
  `pic2` varchar(400) DEFAULT '',
  `url2` varchar(400) DEFAULT '',
  `pic3` varchar(400) DEFAULT '',
  `url3` varchar(400) DEFAULT '',
  `pic4` varchar(400) DEFAULT '',
  `url4` varchar(400) DEFAULT '',
  `pic5` varchar(400) DEFAULT '',
  `url5` varchar(400) DEFAULT '',
  `info` longtext COMMENT '如何获得积分',
  `overtime` tinyint(2) DEFAULT '15' COMMENT '超时未领取时间设置（单位：天）',
  `shareimg` varchar(200) DEFAULT '' COMMENT '微信分享图片',
  `sharefriendstitle` varchar(100) DEFAULT '' COMMENT '朋友圈分享标题',
  `sharedes` varchar(500) DEFAULT '' COMMENT '分享描述',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        echo $sql1.'这是第1个sql<br/>';
        $sql2 = M()->execute("CREATE TABLE `tp_mall_member_integral_class` (
  `id` varchar(30) NOT NULL DEFAULT '' COMMENT '主键ID（系统自动生成）',
  `adduid` int(11) DEFAULT '0' COMMENT '添加用户id',
  `edituid` int(11) DEFAULT '0' COMMENT '编辑用户id',
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  `shopsid` int(11) DEFAULT '0' COMMENT '分店id',
  `title` varchar(12) NOT NULL DEFAULT '' COMMENT '礼品名称',
  `pic` varchar(400) NOT NULL DEFAULT '' COMMENT '主图',
  `sort` int(11) NOT NULL DEFAULT '50' COMMENT '排序 默认：50；',
  `isshow` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否显示 1：是；2：否；默认：1；',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        echo $sql2.'这是第2个sql<br/>';
        $sql3 = M()->execute("CREATE TABLE `tp_mall_member_integral_goods` (
  `id` varchar(30) NOT NULL DEFAULT '' COMMENT '主键ID（系统随机生成）',
  `adduid` int(11) DEFAULT '0' COMMENT '添加用户id',
  `edituid` int(11) DEFAULT '0' COMMENT '编辑用户id',
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  `shopsid` int(11) DEFAULT '0' COMMENT '分店id',
  `title` varchar(12) NOT NULL DEFAULT '' COMMENT '礼品名称',
  `pic` varchar(400) NOT NULL DEFAULT '' COMMENT '主图',
  `price` int(7) NOT NULL DEFAULT '0' COMMENT '价值',
  `integral` int(7) NOT NULL DEFAULT '0' COMMENT '所需积分',
  `stock` int(11) NOT NULL DEFAULT '0' COMMENT '当前库存',
  `info` longtext NOT NULL COMMENT '礼品信息',
  `cid` varchar(15) NOT NULL DEFAULT '' COMMENT '礼品类型id',
  `sendtype` tinyint(1) NOT NULL DEFAULT '0' COMMENT '配送方式 1：快递配送；2：到店领取；',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        echo $sql3.'这是第3个sql<br/>';
        $sql4 = M()->execute("CREATE TABLE `tp_mall_member_integral_order_info` (
  `id` varchar(30) NOT NULL DEFAULT '' COMMENT '主键ID（系统随机生成）',
  `adduid` int(11) DEFAULT '0' COMMENT '添加用户id',
  `edituid` int(11) DEFAULT '0' COMMENT '编辑用户id',
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  `shopsid` int(11) DEFAULT '0' COMMENT '分店id',
  `mid` int(11) NOT NULL DEFAULT '0' COMMENT 'mid',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '订单类型 1：配送到家；2：现场领取；默认：0；',
  `orderid` varchar(50) NOT NULL DEFAULT '0' COMMENT '订单编号',
  `borderid` varchar(50) DEFAULT '0' COMMENT '交易编号 关联 Member_business  order_id',
  `ordertitle` varchar(200) NOT NULL DEFAULT '' COMMENT '订单标题',
  `orderstatus` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1：待发货（待领取）；2：配送中；3：已签收（已领取）；4：关闭订单；5：……（超时未领取） 默认：0；',
  `paytime` int(11) DEFAULT '0' COMMENT '支付时间',
  `shippingtime` int(11) DEFAULT '0' COMMENT '发货时间',
  `receivaltime` int(11) DEFAULT '0' COMMENT '签收时间',
  `offtime` int(11) DEFAULT '0' COMMENT '关闭时间',
  `overtime` int(11) DEFAULT '0' COMMENT '超时时间',
  `orderint` int(7) NOT NULL DEFAULT '0' COMMENT '订单积分',
  `consigneename` varchar(50) NOT NULL DEFAULT '' COMMENT '收货人姓名',
  `consigneephone` varchar(50) NOT NULL DEFAULT '' COMMENT '收货人电话',
  `consigneeaddress` varchar(500) NOT NULL DEFAULT '' COMMENT '收货人地址',
  `membernote` varchar(500) NOT NULL DEFAULT '' COMMENT '买家留言',
  `ordernote` varchar(500) DEFAULT '' COMMENT '订单备注',
  `logisticsid` tinyint(5) NOT NULL DEFAULT '0' COMMENT '物流公司：1：顺丰速运；2：韵达货运；3：圆通速递；4：申通快递；5：天天快递；6：中通速递；7：汇通快运；8：全峰快递；9：EMS；10：宅急送快运；11：中国邮政；12：黑猫宅急便；默认：0；',
  `logisticsnum` varchar(50) NOT NULL DEFAULT '' COMMENT '物流编号；',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        echo $sql4.'这是第4个sql<br/>';
        $sql5 = M()->execute("CREATE TABLE `tp_mall_member_integral_order_goods` (
  `id` varchar(30) NOT NULL DEFAULT '' COMMENT '主键ID（系统随机生成）',
  `adduid` int(11) DEFAULT '0' COMMENT '添加用户id',
  `edituid` int(11) DEFAULT '0' COMMENT '编辑用户id',
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  `shopsid` int(11) DEFAULT '0' COMMENT '分店id',
  `mid` int(11) NOT NULL DEFAULT '0' COMMENT 'mid',
  `orderid` varchar(50) NOT NULL DEFAULT '0' COMMENT '订单编号 关联mall_order_info  orderid',
  `goodid` varchar(15) NOT NULL DEFAULT '' COMMENT '商品id',
  `goodname` varchar(12) NOT NULL DEFAULT '' COMMENT '商品名称',
  `goodpic` varchar(400) NOT NULL DEFAULT '' COMMENT '商品缩略图',
  `goodint` int(7) NOT NULL DEFAULT '0' COMMENT '商品积分',
  `goodnum` tinyint(3) NOT NULL DEFAULT '0' COMMENT '商品数量',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        echo $sql5.'这是第5个sql<br/>';
        $sql6 = M()->execute("insert  into `tp_system_scrm5_permissions_list`(`id`,`name`,`desc`,`parentid`,`sort`,`isshow`,`isedit`,`updatetime`,`createtime`) values (21,'积分制','',0,8,1,1,1473735650,1473735650),(22,'积分商城','',21,1,1,1,1473735650,1473735650);");
        echo $sql6.'这是第6个sql<br/>';
        $sql7 = M()->execute("insert  into `tp_wechat_event`(`id`,`name`,`isshow`) values (6,'积分商城',1);");
        echo $sql7.'这是第7个sql<br/>';
        echo '消息模板记得添加';
        
        echo '######################### 手机预定优化 SQL 结束#######################################<br/>';
    }
    
    /**
     *
     * 手机预定优化 Sql
     *
     * @author Lando<806728685@qq.com>
     * @since  2016-8-30
     */
    public function shoujiyudingSql(){
        exit();
        echo '######################### 手机预定优化 SQL 开始#######################################<br/>';
        $sql1 = M()->execute("INSERT INTO `tp_system_scrm5_permissions_list` (`id`, `name`, `desc`, `parentid`, `sort`, `isshow`, `isedit`, `updatetime`, `createtime`) VALUES ('18', '手机预订', '', '0', '7', '1', '1', '1473735650', '1473735650');");
        echo $sql1.'这是第1个sql<br/>';
        $sql2 = M()->execute("INSERT INTO `tp_system_scrm5_permissions_list` (`id`, `name`, `desc`, `parentid`, `sort`, `isshow`, `isedit`, `updatetime`, `createtime`) VALUES ('19', '手机预订管理', '', '18', '1', '1', '1', '1473735650', '1473735650');");
        echo $sql2.'这是第2个sql<br/>';
        $sql3 = M()->execute("INSERT INTO `tp_system_scrm5_permissions_list` (`id`, `name`, `desc`, `parentid`, `sort`, `isshow`, `isedit`, `updatetime`, `createtime`) VALUES ('20', '预定项目管理', '', '18', '2', '1', '1', '1473735650', '1473735650');");
        echo $sql3.'这是第3个sql<br/>';
        $sql4 = M()->execute("ALTER TABLE `tp_company_shops` ADD COLUMN `isopenmobilebook` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否开启；1：是；2：否；默认：0' AFTER `shophours`;");
        echo $sql4.'这是第4个sql<br/>';
        $sql5 = M()->execute("ALTER TABLE `tp_system_book_time` ADD COLUMN `isshowmobilebook` TINYINT(1) DEFAULT 0 NOT NULL COMMENT '在手机订座是否启用：1:是；0：否；' AFTER `isshow`;");
        echo $sql5.'这是第5个sql<br/>';
        $sql6 = M()->execute("UPDATE `tp_system_book_time` SET `isshowmobilebook`='1' WHERE `id` IN (1,3,5,7,9,11,13,15,17,19,21,23,25,27,29,31,33,35,37,39,41,43,45,47);");
        echo $sql6.'这是第6个sql<br/>';
        $sql7 = M()->execute("CREATE TABLE `tp_mobile_book_order` (
  `id` varchar(30) NOT NULL DEFAULT '' COMMENT 'id',
  `adduid` varchar(30) NOT NULL DEFAULT '' COMMENT '添加uid',
  `edituid` varchar(30) NOT NULL DEFAULT '' COMMENT '编辑uid',
  `companyid` varchar(30) NOT NULL DEFAULT '' COMMENT '公司id',
  `shopsid` varchar(30) NOT NULL DEFAULT '' COMMENT '门店id',
  `setid` varchar(30) NOT NULL DEFAULT '' COMMENT '预约项目id',
  `bookshopid` varchar(30) NOT NULL DEFAULT '' COMMENT '门店id（真）',
  `bookprejectname` varchar(200) NOT NULL DEFAULT '' COMMENT '预约项目',
  `openid` varchar(50) NOT NULL DEFAULT '' COMMENT 'openID',
  `mid` varchar(30) NOT NULL DEFAULT '' COMMENT '会员id',
  `bookname` varchar(50) NOT NULL DEFAULT '' COMMENT '预约联系人',
  `bookmobile` varchar(20) NOT NULL DEFAULT '' COMMENT '预约联系人手机',
  `bookgender` tinyint(1) NOT NULL DEFAULT '0' COMMENT '预约人性别：1：男；2：女；默认：0；',
  `bookdate` int(11) NOT NULL DEFAULT '0' COMMENT '预约日期',
  `booktime` int(11) NOT NULL DEFAULT '0' COMMENT '预约时间',
  `bookupdatetime` int(10) NOT NULL DEFAULT '0' COMMENT '预约时间戳',
  `tplstatus` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否已发送模板；1:否,2:是；默认:1',
  `bookremark` varchar(1000) DEFAULT NULL COMMENT '预约备注；',
  `bookstatus` tinyint(1) NOT NULL DEFAULT '1' COMMENT '预约状态：1：待处理；2：预订成功；3：拒绝；4：客户取消；5：已到店；6：未履约；7：待付款；默认：0；',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '管理员备注',
  `isneedpay` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否需要支付  1:需要; 2:不需要',
  `ispay` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否支付定金  1:未支付; 2:支付中; 3:支付完成; 4:支付失败; 默认:1',
  `booktotal` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '订座总价',
  `orderid` varchar(100) NOT NULL DEFAULT '' COMMENT '订单号',
  `out_trade_no` varchar(100) NOT NULL DEFAULT '' COMMENT '商户订单号',
  `paytime` int(11) NOT NULL DEFAULT '0' COMMENT '支付完成时间',
  `successtime` int(10) NOT NULL DEFAULT '0' COMMENT '预定成功时间',
  `confirmtime` int(10) NOT NULL DEFAULT '0' COMMENT '到店确定时间',
  `noconfirmtime` int(10) NOT NULL DEFAULT '0' COMMENT '未到店确认时间',
  `canceltime` int(10) NOT NULL DEFAULT '0' COMMENT '取消时间',
  `rejecttime` int(10) NOT NULL DEFAULT '0' COMMENT '拒绝时间',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        echo $sql7.'这是第7个sql<br/>';
        $sql8 = M()->execute("CREATE TABLE `tp_mobile_book_project_set` (
  `id` varchar(30) NOT NULL DEFAULT '' COMMENT '自增id',
  `adduid` varchar(30) NOT NULL DEFAULT '' COMMENT '添加uid',
  `edituid` varchar(30) NOT NULL DEFAULT '' COMMENT '编辑uid',
  `companyid` varchar(30) NOT NULL DEFAULT '' COMMENT '公司id',
  `shopsid` varchar(30) NOT NULL DEFAULT '' COMMENT '门店id(员工)',
  `bookname` varchar(100) NOT NULL DEFAULT '' COMMENT '预约名称',
  `isopen` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否开启；1：是；2：否',
  `bookshopid` varchar(5000) NOT NULL DEFAULT ',' COMMENT '关联门店；默认'','';多个逗号分割',
  `subscription` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '订金',
  `bookpeople` smallint(5) NOT NULL DEFAULT '0' COMMENT '接受预订人数',
  `bookdatetype` tinyint(1) NOT NULL DEFAULT '1' COMMENT '接受订座日期类型：1：不限制；2：提前；3：几天内',
  `bookbeforedays` smallint(3) NOT NULL DEFAULT '0' COMMENT '接收提前几天预订的天数设置',
  `bookinsidedays` smallint(3) NOT NULL DEFAULT '0' COMMENT '接收预订几天内的天数设置',
  `booktimetype` tinyint(1) NOT NULL DEFAULT '1' COMMENT '接受订座时间类型：1：不限制；2：全部限制;3:一周的每一天都有限制；',
  `booktimeids` varchar(200) NOT NULL DEFAULT ',' COMMENT 'booktimetype为2时的接收预订的时间段',
  `booktimeids1` varchar(200) NOT NULL DEFAULT ',' COMMENT 'booktimetype为3时周一接收预订的时间段',
  `booktimeids2` varchar(200) NOT NULL DEFAULT ',' COMMENT 'booktimetype为3周二接收预订的时间段',
  `booktimeids3` varchar(200) NOT NULL DEFAULT ',' COMMENT 'booktimetype为3周三接收预订的时间段',
  `booktimeids4` varchar(200) NOT NULL DEFAULT ',' COMMENT 'booktimetype为3周四接收预订的时间段',
  `booktimeids5` varchar(200) NOT NULL DEFAULT ',' COMMENT 'booktimetype为3周五接收预订的时间段',
  `booktimeids6` varchar(200) NOT NULL DEFAULT ',' COMMENT 'booktimetype为3周六接收预订的时间段',
  `booktimeids7` varchar(200) NOT NULL DEFAULT ',' COMMENT 'booktimetype为3周日接收预订的时间段',
  `bookimg` varchar(400) NOT NULL DEFAULT '' COMMENT '项目图片',
  `bookinfo` longtext NOT NULL COMMENT '项目描述',
  `sort` tinyint(5) NOT NULL DEFAULT '0',
  `shareimg` varchar(400) NOT NULL DEFAULT '' COMMENT '微信分享小图',
  `sharefriendstitle` varchar(100) NOT NULL DEFAULT '' COMMENT '微信分享标题',
  `sharedes` varchar(100) NOT NULL DEFAULT '' COMMENT '微信分享描述',
  `viewnum` int(11) NOT NULL DEFAULT '0' COMMENT '网页访问pv数',
  `scannum` int(11) NOT NULL DEFAULT '0' COMMENT '二维码扫描次数',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8; ");
        echo $sql8.'这是第8个sql<br/>';
        $sql9 = M()->execute("ALTER TABLE `tp_wechat_event_type` ADD COLUMN `type` TINYINT(1) DEFAULT 1 NOT NULL COMMENT '1：Wap；2：风助手' AFTER `isshow`; ");
        echo $sql9.'这是第9个sql<br/>';
        $sql10 = M()->execute("INSERT INTO `tp_wechat_event` (`id`, `name`, `isshow`) VALUES ('5', '手机预订', '1');");
        echo $sql10.'这是第10个sql<br/>';
        $sql11 = M()->execute("INSERT INTO `tp_wechat_event_type` (`id`, `fid`, `typename`, `triggering`, `page`, `url`, `data`, `isshow`, `type`) VALUES ('12', '5', '任务处理通知\r\n', '提交订单成功', '手机预订历史', '/index.php?g=Wap&m=MobileBook&a=history&companyid=', '{\"first\":\"\\u60a8\\u7684\\u9884\\u8ba2\\u8ba2\\u5355\\u5df2\\u63d0\\u4ea4\\u6210\\u529f\\uff01\",\"content\":[[\"\\u4efb\\u52a1\\u540d\\u79f0\",\"\",\"\"],[\"\\u901a\\u77e5\\u7c7b\\u578b\",\"\",\"\"]],\"remark\":[[\"\\u9884\\u8ba2\\u4eba\\u59d3\\u540d\",\"\",\"\"],[\"\\u9884\\u8ba2\\u4eba\\u624b\\u673a\",\"\",\"\"],[\"\\u9884\\u8ba2\\u65e5\\u671f&\\u65f6\\u6bb5\",\"\",\"\"]]}', '1', '1');");
        echo $sql11.'这是第11个sql<br/>';
        $sql12 = M()->execute("INSERT INTO `tp_wechat_event_type` (`id`, `fid`, `typename`, `triggering`, `page`, `url`, `data`, `isshow`, `type`) VALUES ('13', '5', '任务处理通知\r\n', '预定成功', '手机预订历史', '/index.php?g=Wap&m=MobileBook&a=history&companyid=', '{\"first\":\"\\u60a8\\u5df2\\u9884\\u8ba2\\u6210\\u529f\\uff01\",\"content\":[[\"\\u4efb\\u52a1\\u540d\\u79f0\",\"\",\"\"],[\"\\u901a\\u77e5\\u7c7b\\u578b\",\"\",\"\"]],\"remark\":[[\"\\u9884\\u8ba2\\u4eba\\u59d3\\u540d\",\"\",\"\"],[\"\\u9884\\u8ba2\\u4eba\\u624b\\u673a\",\"\",\"\"],[\"\\u9884\\u8ba2\\u65e5\\u671f&\\u65f6\\u6bb5\",\"\",\"\"]]}', '1', '1');");
        echo $sql12.'这是第12个sql<br/>';
        $sql13 = M()->execute("INSERT INTO `tp_wechat_event_type` (`id`, `fid`, `typename`, `triggering`, `page`, `url`, `data`, `isshow`, `type`) VALUES ('14', '5', '任务处理通知\r\n', '已满座', '手机预订历史', '/index.php?g=Wap&m=MobileBook&a=history&companyid=', '{\"first\":\"\\u5bf9\\u4e0d\\u8d77\\uff0c\\u60a8\\u7684\\u9884\\u8ba2\\u672a\\u901a\\u8fc7\\u3002\",\"content\":[[\"\\u4efb\\u52a1\\u540d\\u79f0\",\"\",\"\"],[\"\\u901a\\u77e5\\u7c7b\\u578b\",\"\",\"\"]],\"remark\":[[\"\\u672a\\u901a\\u8fc7\\u539f\\u56e0\",\"\",\"\"]],\"end\":\"\\u60a8\\u5df2\\u652f\\u4ed8\\u7684\\u8ba2\\u91d1\\uff0c\\u6211\\u4eec\\u5c06\\u5c3d\\u5feb\\u8054\\u7cfb\\u60a8\\u9000\\u8fd8\\uff01\"}', '1', '1');");
        echo $sql13.'这是第13个sql<br/>';
        $sql14 = M()->execute("INSERT INTO `tp_wechat_event_type` (`id`, `fid`, `typename`, `triggering`, `page`, `url`, `data`, `isshow`, `type`) VALUES ('15', '5', '任务处理通知\r\n', '已到店', '手机预订历史', '/index.php?g=Wap&m=MobileBook&a=history&companyid=', '{\"first\":\"\\u60a8\\u5df2\\u5230\\u5e97\\u6d88\\u8d39\\u3002\",\"content\":[[\"\\u4efb\\u52a1\\u540d\\u79f0\",\"\",\"\"],[\"\\u901a\\u77e5\\u7c7b\\u578b\",\"\",\"\"]],\"remark\":[[\"\\u9884\\u8ba2\\u4eba\\u59d3\\u540d\",\"\",\"\"],[\"\\u9884\\u8ba2\\u4eba\\u624b\\u673a\",\"\",\"\"],[\"\\u9884\\u8ba2\\u65e5\\u671f&\\u65f6\\u6bb5\",\"\",\"\"]],\"end\":\"\\u6b22\\u8fce\\u60a8\\u518d\\u6b21\\u9884\\u8ba2\\u3002\"}', '1', '1');");
        echo $sql14.'这是第14个sql<br/>';
        $sql15 = M()->execute("INSERT INTO `tp_wechat_event_type` (`id`, `fid`, `typename`, `triggering`, `page`, `url`, `data`, `isshow`, `type`) VALUES ('16', '5', '任务处理通知\r\n', '未到店', '手机预订历史', '/index.php?g=Wap&m=MobileBook&a=history&companyid=', '{\"first\":\"\\u60a8\\u672a\\u5230\\u5e97\\u6d88\\u8d39\\u3002\",\"content\":[[\"\\u4efb\\u52a1\\u540d\\u79f0\",\"\",\"\"],[\"\\u901a\\u77e5\\u7c7b\\u578b\",\"\",\"\"]],\"remark\":[[\"\\u9884\\u8ba2\\u4eba\\u59d3\\u540d\",\"\",\"\"],[\"\\u9884\\u8ba2\\u4eba\\u624b\\u673a\",\"\",\"\"],[\"\\u9884\\u8ba2\\u65e5\\u671f&\\u65f6\\u6bb5\",\"\",\"\"]],\"end\":\"\\u7531\\u4e8e\\u60a8\\u672a\\u5230\\u5e97\\u6d88\\u8d39\\uff0c\\u60a8\\u7684\\u8ba2\\u91d1\\u5c06\\u4e0d\\u4e88\\u9000\\u8fd8\\u3002\"}', '1', '1');");
        echo $sql15.'这是第15个sql<br/>';
        $sql16 = M()->execute("INSERT INTO `tp_wechat_event_type` (`id`, `fid`, `typename`, `triggering`, `page`, `url`, `data`, `isshow`, `type`) VALUES ('17', '5', '任务处理通知\r\n', '提交订单成功', '预订管理-该订单详情页', '/index.php?g=Helper&m=MobileBook&a=orderInfo&companyid=', '{\"first\":\"\\u60a8\\u6709\\u4e00\\u4e2a\\u65b0\\u7684\\u9884\\u8ba2\\u8ba2\\u5355\",\"content\":[[\"\\u4efb\\u52a1\\u540d\\u79f0\",\"\",\"\"],[\"\\u901a\\u77e5\\u7c7b\\u578b\",\"\",\"\"]],\"remark\":[[\"\\u9884\\u8ba2\\u4eba\\u59d3\\u540d\",\"\",\"\"],[\"\\u9884\\u8ba2\\u4eba\\u624b\\u673a\",\"\",\"\"],[\"\\u9884\\u8ba2\\u65f6\\u95f4\",\"\",\"\"]]}', '1', '2');");
        echo $sql16.'这是第16个sql<br/>';
        echo '######################### 手机预定优化 SQL 结束#######################################<br/>';
    }
    
    /**
     *
     * Check后台 SAT优化 Sql
     *
     * @author Lando<806728685@qq.com>
     * @since  2016-8-30
     */
    public function checkSatSql(){
        echo '######################### Check后台 SAT优化 SQL 开始#######################################<br/>';
        $sql1 = M()->execute("ALTER TABLE `tp_check_sat_back` ADD COLUMN `priority` TINYINT(1) DEFAULT 0 NULL COMMENT '优先级：1：立即处理；2：当天处理；3：产品优化，暂不处理；默认：0；' AFTER `companyid`, ADD COLUMN `functionalmodule` VARCHAR(100) DEFAULT '' NULL COMMENT '功能模块' AFTER `img3`, ADD COLUMN `handlerid` INT(11) DEFAULT 0 NOT NULL COMMENT '处理人id' AFTER `functionalmodule`, ADD COLUMN `handlestatus` TINYINT(1) DEFAULT 0 NOT NULL COMMENT '处理状态：1：待分配；2：待处理；3：待测试；4：已处理；5：暂不处理；默认：0' AFTER `handlerid`;");
        echo $sql1.'这是第1个sql<br/>';
        $sql2 = M()->execute("ALTER TABLE `tp_check_sat_back` CHANGE `img1` `img1` VARCHAR(200) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NULL, CHANGE `img2` `img2` VARCHAR(200) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NULL, CHANGE `img3` `img3` VARCHAR(200) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NULL;");
        echo $sql2.'这是第2个sql<br/>';
        $sql3 = M()->execute("ALTER TABLE `tp_check_sat_back` CHANGE `img1` `img1` VARCHAR(200) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NULL COMMENT 'BUG图片1', CHANGE `img2` `img2` VARCHAR(200) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NULL COMMENT 'BUG图片2', CHANGE `img3` `img3` VARCHAR(200) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NULL COMMENT 'BUG图片3', ADD COLUMN `img4` VARCHAR(200) DEFAULT '' NULL COMMENT 'BUG图片4' AFTER `img3`, ADD COLUMN `img5` VARCHAR(200) DEFAULT '' NULL COMMENT 'BUG图片5' AFTER `img4`; ");
        echo $sql3.'这是第3个sql<br/>';
        $sql4 = M()->execute("ALTER TABLE `tp_check_sat_back` CHANGE `handlestatus` `handlestatus` TINYINT(1) DEFAULT 1 NOT NULL COMMENT '处理状态：1：待分配；2：待处理；3：待测试；4：已处理；5：暂不处理；默认：0'; ");
        echo $sql4.'这是第4个sql<br/>';
        $sql5 = M()->execute("ALTER TABLE `tp_check_sat_back` ADD COLUMN `updatetime` INT(10) DEFAULT 0 NULL COMMENT '修改时间' AFTER `handlestatus`; ");
        echo $sql5.'这是第5个sql<br/>';
        $sql6 = M()->execute("ALTER TABLE `tp_sell_staffs` CHANGE `position` `position` TINYINT(1) DEFAULT 0 NOT NULL COMMENT '职位：1：CEO；2：VP；3：AMD；4：AM；5：AED；6：AE；7：RD；8：UED；9：PM；默认：0；', CHANGE `role` `role` TINYINT(1) DEFAULT 0 NOT NULL COMMENT '权限：1：BOSS,2：AE,3：AM；4：RD；5：UE；6：UI；7：Admin；8：PM；默认：0'; ");
        echo $sql6.'这是第6个sql<br/>';
        $sql7 = M()->execute("ALTER TABLE `tp_check_sat_back` ADD COLUMN `bugtype` TINYINT(1) DEFAULT 0 NOT NULL COMMENT 'bug类型：1：客户提交的bug；2：内部提交的bug；默认：0；' AFTER `updatetime`; ");
        echo $sql7.'这是第7个sql<br/>'; 
        $sql8 = M()->execute("ALTER TABLE `tp_check_sat_back` AUTO_INCREMENT=1000; ");
        echo $sql8.'这是第8个sql<br/>';
    
        echo '######################### Check后台 SAT优化 SQL 结束#######################################<br/>';
    }
    
    /**
     *
     * 执行人来风商户积分商城 Sql
     *
     * @author Lando<806728685@qq.com>
     * @since  2016-8-30
     */
    public function jiFenShangChengShangHuSql(){
        exit();
        echo '######################### 人来风商户积分商城 SQL 开始#######################################<br/>';
        $sql1 = M()->execute("CREATE TABLE `tp_mall_integral_base` (
  `id` varchar(15) NOT NULL DEFAULT '' COMMENT '主键ID（系统随机生成）',
  `title` varchar(12) NOT NULL DEFAULT '' COMMENT '网页标题',
  `pic1` varchar(400) DEFAULT '' COMMENT '幻灯片1',
  `url1` varchar(400) DEFAULT '' COMMENT '链接1',
  `pic2` varchar(400) DEFAULT '',
  `url2` varchar(400) DEFAULT '',
  `pic3` varchar(400) DEFAULT '',
  `url3` varchar(400) DEFAULT '',
  `pic4` varchar(400) DEFAULT '',
  `url4` varchar(400) DEFAULT '',
  `pic5` varchar(400) DEFAULT '',
  `url5` varchar(400) DEFAULT '',
  `info` longtext COMMENT '如何获得积分',
  `overtime` tinyint(2) DEFAULT '15' COMMENT '超时未领取时间设置（单位：天）',
  `shareimg` varchar(200) DEFAULT '' COMMENT '微信分享图片',
  `sharefriendstitle` varchar(100) DEFAULT '' COMMENT '朋友圈分享标题',
  `sharedes` varchar(500) DEFAULT '' COMMENT '分享描述',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        echo $sql1.'这是第1个sql<br/>';
        $sql2 = M()->execute("CREATE TABLE `tp_mall_integral_class` (
  `id` varchar(15) NOT NULL DEFAULT '' COMMENT '主键ID（系统自动生成）',
  `title` varchar(12) NOT NULL DEFAULT '' COMMENT '礼品名称',
  `pic` varchar(400) NOT NULL DEFAULT '' COMMENT '主图',
  `sort` int(11) NOT NULL DEFAULT '50' COMMENT '排序 默认：50；',
  `isshow` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否显示 1：是；2：否；默认：1；',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        echo $sql2.'这是第2个sql<br/>';
        $sql3 = M()->execute("CREATE TABLE `tp_mall_integral_goods` (
  `id` varchar(15) NOT NULL DEFAULT '' COMMENT '主键ID（系统随机生成）',
  `title` varchar(12) NOT NULL DEFAULT '' COMMENT '礼品名称',
  `pic` varchar(400) NOT NULL DEFAULT '' COMMENT '主图',
  `price` int(7) NOT NULL DEFAULT '0' COMMENT '价值',
  `integral` int(7) NOT NULL DEFAULT '0' COMMENT '所需积分',
  `stock` int(11) NOT NULL DEFAULT '0' COMMENT '当前库存',
  `info` longtext NOT NULL COMMENT '礼品信息',
  `cid` varchar(15) NOT NULL DEFAULT '' COMMENT '礼品类型id',
  `sendtype` tinyint(1) NOT NULL DEFAULT '0' COMMENT '配送方式 1：快递配送；2：到店领取；',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        echo $sql3.'这是第3个sql<br/>';
        $sql4 = M()->execute("CREATE TABLE `tp_mall_integral_address` (
  `id` varchar(15) NOT NULL DEFAULT '' COMMENT '主键ID（系统随机生成）',
  `mid` int(11) NOT NULL DEFAULT '0' COMMENT '会员id',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '姓名',
  `mobile` varchar(20) NOT NULL DEFAULT '' COMMENT '手机',
  `country` varchar(20) NOT NULL DEFAULT '' COMMENT '所在国家',
  `province` varchar(20) NOT NULL DEFAULT '' COMMENT '用户所在省份',
  `city` varchar(20) NOT NULL DEFAULT '' COMMENT '所在城市',
  `address` varchar(500) NOT NULL DEFAULT '' COMMENT '地址详情',
  `isdefault` tinyint(1) NOT NULL DEFAULT '2' COMMENT '是否是默认地址；1：是；2：否；默认：2；',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        echo $sql4.'这是第4个sql<br/>';
        $sql5 = M()->execute("CREATE TABLE `tp_mall_integral_order_info` (
  `id` varchar(15) NOT NULL DEFAULT '' COMMENT '主键ID（系统随机生成）',
  `mid` int(11) NOT NULL DEFAULT '0' COMMENT 'mid',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '订单类型 1：配送到家；2：现场领取；默认：0；',
  `orderid` varchar(50) NOT NULL DEFAULT '0' COMMENT '订单编号',
  `borderid` varchar(50) DEFAULT '0' COMMENT '交易编号 关联 Member_business  order_id',
  `ordertitle` varchar(200) NOT NULL DEFAULT '' COMMENT '订单标题',
  `orderstatus` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1：待发货（待领取）；2：配送中；3：已签收（已领取）；4：关闭订单；5：……（超时未领取） 默认：0；',
  `paytime` int(11) DEFAULT '0' COMMENT '支付时间',
  `shippingtime` int(11) DEFAULT '0' COMMENT '发货时间',
  `receivaltime` int(11) DEFAULT '0' COMMENT '签收时间',
  `offtime` int(11) DEFAULT '0' COMMENT '关闭时间',
  `orderint` int(7) NOT NULL DEFAULT '0' COMMENT '订单积分',
  `consigneename` varchar(50) NOT NULL DEFAULT '' COMMENT '收货人姓名',
  `consigneephone` varchar(50) NOT NULL DEFAULT '' COMMENT '收货人电话',
  `consigneeaddress` varchar(500) NOT NULL DEFAULT '' COMMENT '收货人地址',
  `membernote` varchar(500) NOT NULL DEFAULT '' COMMENT '买家留言',
  `ordernote` varchar(500) DEFAULT '' COMMENT '订单备注',
  `logisticsid` tinyint(5) NOT NULL DEFAULT '0' COMMENT '物流公司：1：顺丰速运；2：韵达货运；3：圆通速递；4：申通快递；5：天天快递；6：中通速递；7：汇通快运；8：全峰快递；9：EMS；10：宅急送快运；11：中国邮政；12：黑猫宅急便；默认：0；',
  `logisticsnum` varchar(50) NOT NULL DEFAULT '' COMMENT '物流编号；',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        echo $sql5.'这是第5个sql<br/>';
        $sql6 = M()->execute("CREATE TABLE `tp_mall_integral_order_goods` (
  `id` varchar(15) NOT NULL DEFAULT '' COMMENT '主键ID（系统随机生成）',
  `mid` int(11) NOT NULL DEFAULT '0' COMMENT 'mid',
  `orderid` varchar(50) NOT NULL DEFAULT '0' COMMENT '订单编号 关联mall_order_info  orderid',
  `goodid` varchar(15) NOT NULL DEFAULT '' COMMENT '商品id',
  `goodname` varchar(12) NOT NULL DEFAULT '' COMMENT '商品名称',
  `goodpic` varchar(400) NOT NULL DEFAULT '' COMMENT '商品缩略图',
  `goodint` int(7) NOT NULL DEFAULT '0' COMMENT '商品积分',
  `goodnum` tinyint(3) NOT NULL DEFAULT '0' COMMENT '商品数量',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        echo $sql6.'这是第6个sql<br/>';
        
        echo '######################### 人来风商户积分商城 SQL 结束#######################################<br/>';
    }
    
    
    
    /**
     *
     * 执行 摇摇乐 Sql
     *
     * @author Lando<806728685@qq.com>
     * @since  2016-8-30
     */
    public function yaoYaoLeSql(){
        echo '已上线';
        exit();
        echo '######################### 摇摇乐SQL 开始#######################################<br/>';
        $sql1 = M()->execute("insert  into `tp_system_permissions_list`(`id`,`name`,`desc`,`parentid`,`sort`,`isshow`,`isedit`,`updatetime`,`createtime`) values (216,'摇摇乐活动管理','',70,11,1,1,1472460399,1472460356);");
        echo $sql1.'这是第1个sql<br/>';
        $sql2 = M()->execute("CREATE TABLE `tp_member_shake_activities` (
  `id` varchar(15) NOT NULL COMMENT 'id',
  `adduid` int(11) NOT NULL DEFAULT '0' COMMENT '添加uid',
  `edituid` int(11) NOT NULL DEFAULT '0' COMMENT '编辑uid',
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  `shopsid` int(11) NOT NULL DEFAULT '0' COMMENT '门店id',
  `title` varchar(200) NOT NULL DEFAULT '' COMMENT '活动标题',
  `activitiestarttime` int(10) NOT NULL DEFAULT '0' COMMENT '活动开始时间',
  `activitieendtime` int(10) NOT NULL DEFAULT '0' COMMENT '活动结束时间',
  `bannerpic` varchar(400) NOT NULL DEFAULT '' COMMENT '百宝箱头部banner',
  `describe` longtext NOT NULL COMMENT '活动描述',
  `lotterylimittype` tinyint(1) NOT NULL DEFAULT '0' COMMENT '抽奖次数判断 1：每人无限次；2：每人限制限次；3：每天限次 默认：0',
  `everybodylimit` int(10) NOT NULL DEFAULT '0' COMMENT '没人抽奖次数',
  `everydaylimit` int(10) NOT NULL DEFAULT '0' COMMENT '每天抽奖的次数',
  `lotterynums` int(10) NOT NULL DEFAULT '0' COMMENT '每人中奖次数',
  `issubscribe` smallint(1) NOT NULL DEFAULT '0' COMMENT '非关注用户是否可以参加活动，1：是，2：否，默认0',
  `codepic` varchar(400) NOT NULL DEFAULT '' COMMENT '关注二维码',
  `isemail` smallint(1) NOT NULL DEFAULT '2' COMMENT '是否填写邮箱，1：是；2：否；默认：2',
  `password` varchar(4) NOT NULL DEFAULT '0000' COMMENT '核销密码',
  `probability` smallint(2) NOT NULL DEFAULT '0' COMMENT '中奖概率，1-100.',
  `isshownum` smallint(1) NOT NULL DEFAULT '0' COMMENT '是否显示剩余奖品，1：是，2：否，默认：0',
  `shareimg` varchar(400) NOT NULL DEFAULT '' COMMENT '微信分享图片',
  `sharefriendstitle` varchar(100) NOT NULL DEFAULT '' COMMENT '朋友圈分享标题',
  `sharedes` varchar(200) NOT NULL DEFAULT '' COMMENT '分享描述',
  `scannum` int(11) NOT NULL DEFAULT '0' COMMENT '二维码扫描次数',
  `grouptype` tinyint(1) NOT NULL DEFAULT '1' COMMENT '关联客户分组类型：1:全部，2：会员等级，3：自定义客户分组。',
  `groupvalue` varchar(500) NOT NULL DEFAULT ',' COMMENT '关联分组内容：当分组类型为2,存储内容为一，分隔的字符串。当分组类型为3时：存储内容为分组的id.',
  `consumetype` tinyint(1) NOT NULL DEFAULT '0' COMMENT '消耗限制：1：消耗积分，2：消耗积点，3：根据时间判断。默认：0；',
  `consumejifen` int(10) NOT NULL DEFAULT '0' COMMENT '消耗积分：默认：0',
  `consumejidian` int(10) NOT NULL DEFAULT '0' COMMENT '消耗积点：默认：0',
  `maxjifelotternumber` int(10) NOT NULL DEFAULT '1' COMMENT '用每次消耗积分后的最大抽奖次数',
  `maxlotterynumber` int(10) NOT NULL DEFAULT '1' COMMENT '每次消耗积点后的最大抽奖次数',
  `djtype` tinyint(1) NOT NULL DEFAULT '2' COMMENT '兑奖方式：1：物流配送；2：到店领取；默认：2',
  `djmessage` varchar(100) NOT NULL DEFAULT '' COMMENT '用户中奖时需填写的信息：1：姓名；2：电话；3：性别；4：年龄；5：邮箱；6：收货地址',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `url` varchar(400) NOT NULL COMMENT '活动详情 url',
  `viewnum` int(11) NOT NULL DEFAULT '0' COMMENT '网页访问pv数',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idcompanyid` (`id`,`companyid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='摇摇乐';");
        echo $sql2.'这是第2个sql<br/>';
        $sql3 = M()->execute("CREATE TABLE `tp_member_shake_lottery_set` (
  `id` varchar(15) NOT NULL,
  `companyid` int(10) NOT NULL DEFAULT '0' COMMENT '公司ID',
  `activitesid` varchar(15) NOT NULL DEFAULT '0' COMMENT '活动id',
  `weizhi` smallint(5) NOT NULL DEFAULT '0' COMMENT '位置',
  `islottery` smallint(1) NOT NULL DEFAULT '0' COMMENT '是否有奖，1：有，2：否，默认0',
  `picurl` varchar(400) NOT NULL DEFAULT '' COMMENT '样式路径',
  `lotteryname` varchar(20) NOT NULL DEFAULT '' COMMENT '奖品名称',
  `lotterynum` int(10) NOT NULL DEFAULT '0' COMMENT '奖品数量',
  `validitytime` smallint(1) NOT NULL DEFAULT '0' COMMENT '兑奖码有效期，1：固定有效期，2：设置天数，默认：0',
  `validitystarttime` int(10) NOT NULL DEFAULT '0' COMMENT '开始有效期',
  `validityendtime` int(10) NOT NULL DEFAULT '0' COMMENT '结束有效期',
  `validitystartday` int(10) NOT NULL DEFAULT '0' COMMENT '中奖后多少天换取',
  `validityendday` int(10) NOT NULL DEFAULT '0' COMMENT '中奖后多少天过期',
  `instructions` longtext NOT NULL COMMENT '使用说明',
  `updatetime` int(10) NOT NULL COMMENT '修改时间',
  `creratetime` int(10) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='摇摇乐';");
        echo $sql3.'这是第3个sql<br/>';
        $sql4 = M()->execute("CREATE TABLE `tp_member_shake_activities_log` (
  `id` varchar(15) NOT NULL COMMENT 'id',
  `adduid` int(11) NOT NULL DEFAULT '0' COMMENT '添加uid',
  `edituid` int(11) NOT NULL DEFAULT '0' COMMENT '编辑uid',
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  `shopsid` int(11) NOT NULL DEFAULT '0' COMMENT '门店id',
  `activitesid` varchar(15) NOT NULL DEFAULT '0' COMMENT '活动id',
  `openid` varchar(50) NOT NULL DEFAULT '' COMMENT 'openid',
  `nickname` varchar(100) NOT NULL DEFAULT '' COMMENT '微信昵称',
  `headurl` varchar(500) NOT NULL DEFAULT '' COMMENT '头像',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '姓名',
  `mobile` varchar(50) NOT NULL DEFAULT '' COMMENT '手机号',
  `gender` tinyint(1) NOT NULL DEFAULT '0' COMMENT '性别：1：男；2：女；默认：0',
  `age` smallint(3) NOT NULL DEFAULT '0' COMMENT '年龄',
  `address` varchar(500) DEFAULT '' COMMENT '收货地址',
  `email` varchar(50) NOT NULL DEFAULT '' COMMENT '邮箱',
  `lotterycode` varchar(10) NOT NULL DEFAULT '' COMMENT '兑奖码',
  `weizhi` smallint(5) NOT NULL DEFAULT '0' COMMENT '转动的位置1-12',
  `islottery` smallint(1) NOT NULL DEFAULT '0' COMMENT '是否中奖1：是，2：否，默认：0',
  `lotterytype` smallint(1) NOT NULL DEFAULT '0' COMMENT '是否兑奖；1：是；2：否；默认：0',
  `canceltime` int(10) NOT NULL DEFAULT '0' COMMENT '核销时间',
  `recordid` smallint(2) NOT NULL DEFAULT '0' COMMENT '记录id',
  `prizievouchersid` int(10) NOT NULL DEFAULT '0' COMMENT '获得奖品发送出的电子券交易id',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `mid` int(11) NOT NULL DEFAULT '0' COMMENT '参与者mid',
  `prize` smallint(5) NOT NULL DEFAULT '0' COMMENT '获得的奖项',
  `prizejifen` int(10) NOT NULL DEFAULT '0' COMMENT '获得的奖品积分数',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='摇摇乐';");
        echo $sql4.'这是第4个sql<br/>';
        echo '######################### 摇摇乐SQL 结束#######################################<br/>';
    }
    
    
    /**
     *
     * 执行 Eshop5 Sql
     *
     * @author Lando<806728685@qq.com>
     * @since  2016-8-30
     */
    public function eshop5Sql(){
        echo '######################### Eshop5SQL 开始#######################################<br/>';
        $sql1 = M()->execute("ALTER TABLE `tp_eshop_tag` CHANGE `id` `id` VARCHAR(15) DEFAULT 0 NOT NULL COMMENT '主键ID（系统随机生成）';");
        echo $sql1.'这是第1个sql<br/>';
        $sql2 = M()->execute("ALTER TABLE `tp_eshop_class` CHANGE `id` `id` VARCHAR(15) DEFAULT '0' NOT NULL COMMENT '主键ID（系统随机生成）';");
        echo $sql2.'这是第2个sql<br/>';
        $sql3 = M()->execute("ALTER TABLE `tp_eshop_class` CHANGE `ptid` `ptid` VARCHAR(15) DEFAULT '' NULL COMMENT '一级品类ID';");
        echo $sql3.'这是第3个sql<br/>';
        $sql4 = M()->execute("ALTER TABLE `tp_mall_goods` CHANGE `isoffshelves` `isoffshelves` TINYINT(1) DEFAULT 0 NOT NULL COMMENT '商品是否下架：1：是；2：否；默认：0；' AFTER `goodtype`, CHANGE `issoldout` `issoldout` TINYINT(1) DEFAULT 0 NOT NULL COMMENT '商品是否售罄：1：是；2：否；默认：0；' AFTER `isoffshelves`, CHANGE `goodnum` `goodnum` VARCHAR(20) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL COMMENT '商品编码' AFTER `title`, CHANGE `stockamount` `stockamount` INT(11) DEFAULT 0 NOT NULL COMMENT '虚拟商品库存数量（实物商品库存量）', CHANGE `viewnum` `viewnum` INT(11) DEFAULT 0 NOT NULL COMMENT '浏览量', CHANGE `scannum` `scannum` INT(11) DEFAULT 0 NOT NULL COMMENT '二维码扫描次数' AFTER `viewnum`, CHANGE `sort` `sort` SMALLINT(5) DEFAULT 50 NOT NULL COMMENT '商品排序：默认：50；' AFTER `scannum`;");
        echo $sql4.'这是第4个sql<br/>';
        $sql5 = M()->execute("ALTER TABLE `tp_mall_goods` CHANGE `freighttplid` `freighttplid` VARCHAR(15) DEFAULT '0' NOT NULL COMMENT '运费模板id;默认：0；';");
        echo $sql5.'这是第5个sql<br/>';
        $sql6 = M()->execute("ALTER TABLE `tp_mall_goods` CHANGE `id` `id` VARCHAR(15) DEFAULT '0' NOT NULL COMMENT '主键ID（系统随机生成）';");
        echo $sql6.'这是第6个sql<br/>';
        $sql7 = M()->execute("ALTER TABLE `tp_mall_tags_goods_link` CHANGE `id` `id` VARCHAR(15) DEFAULT '0' NOT NULL COMMENT '主键ID（系统随机生成）', CHANGE `tagid` `tagid` VARCHAR(15) DEFAULT '0' NOT NULL COMMENT '标签id';");
        echo $sql7.'这是第7个sql<br/>';
        $sql8 = M()->execute("ALTER TABLE `tp_mall_tags_goods_link` CHANGE `goodid` `goodid` VARCHAR(15) DEFAULT '0' NOT NULL COMMENT '商品id';");
        echo $sql8.'这是第8个sql<br/>';
        $sql9 = M()->execute("ALTER TABLE `tp_mall_goods_sku` CHANGE `adduid` `adduid` INT(11) DEFAULT 0 NULL COMMENT '添加用户id', CHANGE `edituid` `edituid` INT(11) DEFAULT 0 NULL COMMENT '编辑用户id', CHANGE `shopsid` `shopsid` INT(11) DEFAULT 0 NULL COMMENT '分店id', CHANGE `name` `name` VARCHAR(200) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL COMMENT '规格', ADD COLUMN `originalprice` DECIMAL(12,2) DEFAULT 0.00 NOT NULL COMMENT '原价，仅用于显示' AFTER `name`, ADD COLUMN `saleprice` DECIMAL(12,2) DEFAULT 0.00 NOT NULL COMMENT '售价' AFTER `originalprice`, ADD COLUMN `salenum` INT(10) DEFAULT 0 NULL COMMENT '销量' AFTER `stockamount`;");
        echo $sql9.'这是第9个sql<br/>';
        $sql10 = M()->execute("ALTER TABLE `tp_mall_goods_sku` ADD COLUMN `intprice` DECIMAL(12,2) DEFAULT 0.00 NOT NULL COMMENT '积分价' AFTER `saleprice`;");
        echo $sql10.'这是第10个sql<br/>';
        $sql11 = M()->execute("ALTER TABLE `tp_mall_goods_sku` CHANGE `goodid` `goodid` VARCHAR(15) DEFAULT '0' NOT NULL COMMENT '商品id';");
        echo $sql11.'这是第11个sql<br/>';
        $sql12 = M()->execute("ALTER TABLE `tp_mall_goods_pics` CHANGE `id` `id` VARCHAR(15) DEFAULT '0' NOT NULL COMMENT '主键ID（系统随机生成）';");
        echo $sql12.'这是第12个sql<br/>';
        $sql13 = M()->execute("ALTER TABLE `tp_mall_goods_pics` CHANGE `goodid` `goodid` VARCHAR(15) DEFAULT '0' NOT NULL COMMENT '商品id';");
        echo $sql13.'这是第13个sql<br/>';
        $sql14 = M()->execute("ALTER TABLE `tp_mall_goods_rank_discount` CHANGE `goodsid` `goodsid` VARCHAR(15) DEFAULT '0' NOT NULL COMMENT '商品id(唯一)';");
        echo $sql14.'这是第14个sql<br/>';
        $sql15 = M()->execute("CREATE TABLE `tp_eshop_discount` (
  `id` varchar(30) NOT NULL DEFAULT '0' COMMENT '主键ID（系统随机生成）',
  `adduid` int(11) DEFAULT '0' COMMENT '添加uid',
  `edituid` int(11) DEFAULT '0' COMMENT '修改uid',
  `shopsid` int(11) DEFAULT '0' COMMENT '门店id',
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '活动标题',
  `starttime` int(11) NOT NULL DEFAULT '0' COMMENT '活动有效期',
  `endtime` int(11) NOT NULL DEFAULT '0' COMMENT '活动有效期',
  `isoff` tinyint(1) NOT NULL DEFAULT '1' COMMENT '活动是否开启 1：开启；2：关闭；默认：1；',
  `memberclass` tinyint(1) NOT NULL DEFAULT '1' COMMENT '成员 1：全体会员；2：新注册会员；默认：1；',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '优惠类型 1：立减；2：立折；3：满减；4：满折；5；满件减；6：满件折；',
  `money` decimal(12,2) DEFAULT '0.00' COMMENT '立减多少元',
  `discount` tinyint(2) DEFAULT '0' COMMENT '立折多少折扣',
  `fulljian` varchar(50) DEFAULT '' COMMENT '满减优惠',
  `fullzhe` varchar(50) DEFAULT '' COMMENT '满折优惠',
  `fullnumjian` varchar(50) DEFAULT '' COMMENT '满件减',
  `fullnumzhe` varchar(50) DEFAULT '' COMMENT '满件折',
  `isopen` tinyint(1) NOT NULL DEFAULT '1' COMMENT '设置商品参与活动 1：禁止；2：参与；默认：1；',
  `codingno` longtext COMMENT '禁止以下商品参与活动',
  `codingok` longtext COMMENT '商品参与活动',
  `updatetime` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        echo $sql15.'这是第15个sql<br/>';
        $sql16 = M()->execute("ALTER TABLE `tp_mall_freight_tpl` CHANGE `id` `id` VARCHAR(15) DEFAULT '0' NOT NULL COMMENT '主键ID（系统随机生成）';");
        echo $sql16.'这是第16个sql<br/>';
        $sql17 = M()->execute("ALTER TABLE `tp_mall_freight_tpl_child` CHANGE `id` `id` VARCHAR(15) DEFAULT '0' NOT NULL COMMENT '主键ID（系统随机生成）', CHANGE `tplid` `tplid` VARCHAR(30) DEFAULT '0' NOT NULL COMMENT '父级模版名称';");
        echo $sql17.'这是第17个sql<br/>';
        $sql18 = M()->execute("ALTER TABLE `tp_mall_order_info` ADD COLUMN `offtime` INT(11) DEFAULT 0 NULL COMMENT '关闭时间' AFTER `receivaltime`;");
        echo $sql18.'这是第18个sql<br/>';
        $sql19 = M()->execute("ALTER TABLE `tp_mall_order_info` ADD COLUMN `returntime` INT(11) DEFAULT 0 NULL COMMENT '退货时间' AFTER `offtime`;");
        echo $sql19.'这是第19个sql<br/>';
        $sql20 = M()->execute("ALTER TABLE `tp_mall_order_goods` CHANGE `goodid` `goodid` VARCHAR(30) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '0' NOT NULL COMMENT '商品id', CHANGE `goodskuid` `goodskuid` VARCHAR(30) DEFAULT '0' NOT NULL COMMENT '商品skuid';");
        echo $sql20.'这是第20个sql<br/>';
        $sql21 = M()->execute("ALTER TABLE `tp_mall_order_goods` CHANGE `goodid` `goodid` VARCHAR(15) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '0' NOT NULL COMMENT '商品id', CHANGE `goodskuid` `goodskuid` INT(11) DEFAULT 0 NOT NULL COMMENT '商品skuid';");
        echo $sql21.'这是第21个sql<br/>';
        $sql22 = M()->execute("ALTER TABLE `tp_mall_order_goods` CHANGE `id` `id` VARCHAR(15) NOT NULL COMMENT '主键ID（系统随机生成）';");
        echo $sql22.'这是第22个sql<br/>';
        $sql23 = M()->execute("ALTER TABLE `tp_mall_order_info` ADD COLUMN `eshopdiscounttitle` VARCHAR(100) DEFAULT '' NULL COMMENT '整单优惠活动名称' AFTER `stockamount`, ADD COLUMN `eshopdiscountmoney` DECIMAL(12,2) DEFAULT 0.00 NULL COMMENT '整单优惠活动金额' AFTER `eshopdiscounttitle`;");
        echo $sql23.'这是第23个sql<br/>';
        $sql24 = M()->execute("CREATE TABLE `tp_mall_order_service` (
  `id` varchar(15) NOT NULL DEFAULT '0' COMMENT '主键ID（系统随机生成）',
  `adduid` int(11) DEFAULT '0' COMMENT '添加用户id',
  `edituid` int(11) DEFAULT '0' COMMENT '编辑用户id',
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  `shopsid` int(11) DEFAULT '0' COMMENT '分店id',
  `mid` int(11) NOT NULL DEFAULT '0' COMMENT 'mid',
  `orid` varchar(15) NOT NULL DEFAULT '0' COMMENT '订单主表ID',
  `ogid` varchar(15) NOT NULL DEFAULT '0' COMMENT '订单商品详情ID',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '类型：1：退款，2：退货；3：换货；默认：0；',
  `price` decimal(12,2) DEFAULT '0.00' COMMENT '退款价格',
  `info` longtext COMMENT '退换货说明',
  `pic` varchar(400) DEFAULT '' COMMENT '图片',
  `pic2` varchar(400) DEFAULT '',
  `pic3` varchar(400) DEFAULT '',
  `handle` tinyint(1) NOT NULL DEFAULT '2' COMMENT '是否处理 1：处理；2：未处理；默认：1；',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        echo $sql24.'这是第24个sql<br/>';
        $sql25 = M()->execute("CREATE TABLE `tp_mall_notices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mid` int(11) NOT NULL COMMENT '会员id',
  `mnid` int(11) DEFAULT NULL COMMENT '群发消息id',
  `companyid` int(11) NOT NULL COMMENT '公司id',
  `info` varchar(2000) NOT NULL COMMENT '通知内容',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '类型：1:系统通知；2:eshop客服；默认：1；',
  `msgtype` tinyint(1) NOT NULL DEFAULT '0' COMMENT '信息类型：1：客服信息 2：客户信息 默认：0',
  `isread` tinyint(1) NOT NULL DEFAULT '2' COMMENT '是否已读：1:已读；2：未读；默认：2；',
  `isreply` tinyint(1) NOT NULL DEFAULT '2' COMMENT '是否回复：1：是 2：否 默认：2',
  `isshow` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否显示：1：是； 2：否； 默认：1',
  `eadtime` int(10) DEFAULT NULL COMMENT '读取时间',
  `createtime` int(10) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;");
        echo $sql25.'这是第25个sql<br/>';
        
        echo '######################### 风助手SQL 结束#######################################<br/>';
        //$this->fengZhuShouSql();
    }
    
    /**
     *
     * 执行 风助手 Sql
     *
     * @author Lando<806728685@qq.com>
     * @since  2016-8-30
     */
    public function fengZhuShouSql(){
        echo '######################### 风助手SQL 开始#######################################<br/>';
        $sql1 = M()->execute("ALTER TABLE `tp_wechat_voucher_consume_history` CHANGE `isconsume` `isconsume` TINYINT(1) DEFAULT 0 NOT NULL COMMENT '券是否已核销:1:是;2:否;3:冻结;默认:0';");
        echo $sql1.'这是第1个sql<br/>';
        $sql2 = M()->execute("ALTER TABLE `tp_member_spending` ADD COLUMN `out_trade_no` VARCHAR(50) DEFAULT '' NOT NULL COMMENT '商户订单号' AFTER `status`, ADD COLUMN `paystate` TINYINT(1) DEFAULT 0 NOT NULL COMMENT '支付状态: 1.未支付; 2.支付中; 3.支付成功;  4.支付失败;' AFTER `out_trade_no`, ADD COLUMN `paytime` INT(11) DEFAULT 0 NOT NULL COMMENT '支付时间' AFTER `paystate`, ADD COLUMN `paydonetime` INT(11) DEFAULT 0 NOT NULL COMMENT '支付完成时间' AFTER `paytime`, ADD COLUMN `payopenid` VARCHAR(50) DEFAULT '' NOT NULL COMMENT '支付openid' AFTER `paydonetime`;");
        echo $sql2.'这是第2个sql<br/>';
        $sql3 = M()->execute("ALTER TABLE `tp_member_spending` ADD COLUMN `orderflow` INT(3) UNSIGNED ZEROFILL NULL COMMENT '订单流水(订单号生成的依据)' AFTER `borderid`;");
        echo $sql3.'这是第3个sql<br/>';
        $sql4 = M()->execute("ALTER TABLE `tp_member_spending` ADD COLUMN `transaction_id` VARCHAR(40) DEFAULT '' NOT NULL COMMENT '支付订单号' AFTER `out_trade_no`;");
        echo $sql4.'这是第4个sql<br/>';
        $sql5 = M()->execute("ALTER TABLE `tp_wechat_voucher_consume_history` CHANGE `isconsume` `isconsume` TINYINT(1) DEFAULT 0 NOT NULL COMMENT '券是否已核销:1:是;2:否;3:冻结;默认:0';");
        echo $sql5.'这是第5个sql<br/>';
        $sql6 = M()->execute("CREATE TABLE `tp_shop_cashier` (
  `id` varchar(30) NOT NULL DEFAULT '0' COMMENT '主键(系统随机生成)',
  `adduid` int(11) NOT NULL DEFAULT '0' COMMENT '添加uid',
  `edituid` int(11) NOT NULL DEFAULT '0' COMMENT '编辑uid',
  `shopsid` int(11) NOT NULL DEFAULT '0' COMMENT '门店id',
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  `shopid` int(11) NOT NULL DEFAULT '0' COMMENT '门店id',
  `orderflow` int(3) unsigned zerofill NOT NULL DEFAULT '000' COMMENT '订单流水(订单号生成的依据)',
  `orderid` varchar(30) NOT NULL DEFAULT '' COMMENT '订单号',
  `testtype` tinyint(1) NOT NULL DEFAULT '0' COMMENT '会员验证方法  1.输入手机号  2.扫码',
  `mobile` varchar(20) NOT NULL DEFAULT '' COMMENT '会员手机号',
  `mid` varchar(30) NOT NULL DEFAULT '' COMMENT '会员mid',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '会员名称',
  `receivables` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '应收金额',
  `isnotdiscount` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否输入不参与优惠金额   1:是;  2:否  默认:2',
  `notdiscountmoney` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '不参与优惠金额',
  `discount` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '共计减免金额',
  `actualamount` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '实收金额',
  `integral` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '积分',
  `dmsorderid` varchar(30) NOT NULL DEFAULT '' COMMENT 'dms订单号【tp_dms_order：orderid】',
  `collectiontime` int(11) NOT NULL DEFAULT '0' COMMENT '收款时间',
  `collectionendtime` int(11) NOT NULL DEFAULT '0' COMMENT '收款完成时间',
  `orderstate` tinyint(1) NOT NULL DEFAULT '1' COMMENT '收款状态 1:收款中; 2:收款完成; 默认:1',
  `note` varchar(2000) NOT NULL DEFAULT '' COMMENT '收款备注',
  `adminmid` varchar(30) NOT NULL DEFAULT '' COMMENT '收款人mid',
  `adminopenid` varchar(50) NOT NULL DEFAULT '' COMMENT '收款人openid',
  `adminname` varchar(50) NOT NULL DEFAULT '' COMMENT '收款人姓名',
  `nickname` varchar(100) NOT NULL DEFAULT '' COMMENT '收款人昵称',
  `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `updatetime` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        echo $sql6.'这是第6个sql<br/>';
        $sql7 = M()->execute("CREATE TABLE `tp_shop_cashier_voucher` (
  `id` varchar(30) NOT NULL DEFAULT '0' COMMENT '主键(系统随机生成)',
  `adduid` int(11) NOT NULL DEFAULT '0' COMMENT '添加uid',
  `edituid` int(11) NOT NULL DEFAULT '0' COMMENT '编辑uid',
  `shopsid` int(11) NOT NULL DEFAULT '0' COMMENT '门店id',
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  `shopid` int(11) NOT NULL DEFAULT '0' COMMENT '门店id',
  `orderid` varchar(30) NOT NULL DEFAULT '' COMMENT '【tp_shop_cashier：orderid】',
  `vouchertype` tinyint(1) NOT NULL DEFAULT '0' COMMENT '券类型:  1.代金券  2.线下优惠券  3.微信互通券',
  `wechatvouchertype` tinyint(1) NOT NULL DEFAULT '0' COMMENT '微信互通券类型:  1:折扣券  2:代金券',
  `voucherid` varchar(30) NOT NULL DEFAULT '' COMMENT '券id',
  `vouchernumber` varchar(20) NOT NULL DEFAULT '' COMMENT '券号',
  `vouchername` varchar(50) NOT NULL DEFAULT '' COMMENT '券名称',
  `startoffer` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '【微信互通券：代金券】启用金额',
  `reliefprice` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '【微信互通券: 代金券】减免金额',
  `quota` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '【微信互通券：折扣券】打折额度',
  `couponvalue` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '【线下优惠券】面值',
  `amount` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '优惠金额',
  `usestate` tinyint(1) NOT NULL DEFAULT '0' COMMENT '使用状态  1.使用中;  2.核销成功;  3:核销失败;',
  `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updatetime` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        echo $sql7.'这是第7个sql<br/>';
        $sql8 = M()->execute("CREATE TABLE `tp_use_vouchers` (
  `id` varchar(30) NOT NULL DEFAULT '0' COMMENT '主键(系统随机生成)',
  `adduid` int(11) NOT NULL DEFAULT '0' COMMENT '添加uid',
  `edituid` int(11) NOT NULL DEFAULT '0' COMMENT '编辑uid',
  `shopsid` int(11) NOT NULL DEFAULT '0' COMMENT '门店id',
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  `shopid` int(11) NOT NULL DEFAULT '0' COMMENT '门店id',
  `vouchernumber` varchar(50) NOT NULL DEFAULT '' COMMENT '券号',
  `vouchername` varchar(100) NOT NULL DEFAULT '' COMMENT '券名称',
  `vouchertype` tinyint(1) NOT NULL DEFAULT '0' COMMENT '券类别:  1.团购券  2.代金券  3.线下优惠券  4.微信互通券',
  `wechatvouchertype` tinyint(1) NOT NULL DEFAULT '0' COMMENT '微信互通券类别:  1.通用券  2.团购券  3.折扣券  4.礼品券  5.代金券',
  `value` varchar(50) NOT NULL DEFAULT '' COMMENT '价值',
  `utility` varchar(100) NOT NULL DEFAULT '' COMMENT '效用',
  `remarks` varchar(2000) NOT NULL DEFAULT '' COMMENT '使用说明',
  `isconsume` tinyint(1) NOT NULL DEFAULT '2' COMMENT '是否核销:  1.是  2.否  默认:2',
  `usetime` int(11) NOT NULL DEFAULT '0' COMMENT '核销时间',
  `getway` tinyint(1) NOT NULL DEFAULT '0' COMMENT '获得券号途径: 1.手动输入  2.扫码',
  `voucherid` varchar(30) NOT NULL DEFAULT '0' COMMENT '关联的券id',
  `voucherstarttime` int(11) NOT NULL DEFAULT '0' COMMENT '券有效期开始时间',
  `voucherendtime` int(11) NOT NULL DEFAULT '0' COMMENT '券有效期结束时间',
  `staffopenid` varchar(50) NOT NULL DEFAULT '' COMMENT '核销人openid',
  `staffname` varchar(50) NOT NULL DEFAULT '' COMMENT '核销人姓名',
  `mid` varchar(30) NOT NULL DEFAULT '0' COMMENT '用户id',
  `openid` varchar(50) NOT NULL DEFAULT '' COMMENT '会员openid',
  `mobile` varchar(20) NOT NULL DEFAULT '' COMMENT '会员手机号',
  `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `updatetime` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        echo $sql8.'这是第8个sql<br/>';
        $sql9 = M()->execute("ALTER TABLE `tp_member_business` ADD COLUMN `cashierorderid` VARCHAR(30) DEFAULT '' NOT NULL COMMENT '门店收银订单号【tp_shop_cashier：orderid】' AFTER `status`;");
        echo $sql9.'这是第9个sql<br/>';
        $sql10 = M()->execute("ALTER TABLE `tp_company` ADD COLUMN `helpercode` VARCHAR(200) DEFAULT '' NOT NULL COMMENT '风助手关注二维码' AFTER `roomscann`;");
        echo $sql10.'这是第10个sql<br/>';
        $sql11 = M()->execute("ALTER TABLE`tp_company_shops` ADD COLUMN `shopcashierprintid` VARCHAR(100) DEFAULT '' NOT NULL COMMENT '门店收银小票机id' AFTER `printkey`, ADD COLUMN `shopcashierprintkey` VARCHAR(100) DEFAULT '' NOT NULL COMMENT '门店收银小票机key' AFTER `shopcashierprintid`;");
        echo $sql11.'这是第11个sql<br/>';
        echo '######################### 风助手SQL 结束#######################################<br/>';
    }
    
    
    /**
     * 
     * 执行Sql
     * 
     * @author Lando<806728685@qq.com>
     * @since  2016-8-30
     */
    public function addSql1(){
        exit();
        echo '######################### 微页面SQL 开始#######################################<br/>'; 
        $sql1 = M()->execute("ALTER TABLE `tp_wei_assembly` ADD COLUMN `goodshopcart` TINYINT(1) DEFAULT 0 NULL COMMENT '是否显示购物车 1：是；' AFTER `goodprice`, ADD COLUMN `goodshopcartclass` TINYINT(1) DEFAULT 1 NULL COMMENT '购物车样式：1：；默认：1；' AFTER `goodshopcart`;");
        echo $sql1.'这是第1个sql<br/>';
        $sql2 = M()->execute("ALTER TABLE `tp_wei_assembly` CHANGE `type` `type` TINYINT(2) DEFAULT 0 NOT NULL COMMENT '类型 1、banner；2、多图（4*1）；3、多图（3*1）；4、多图（2*1）；5、幻灯片；6、商品；7、辅助空白；8、搜索栏；9：多图（1*1）10：富文本；11：通头；12：通底；默认：0', CHANGE `goodid` `goodid` VARCHAR(1000) CHARSET utf8 COLLATE utf8_general_ci DEFAULT ',' NOT NULL COMMENT '商品 储存样式：,1,2, 默认：,', CHANGE `textinfo` `textinfo` LONGTEXT CHARSET utf8 COLLATE utf8_general_ci NULL COMMENT '富文本编辑器', ADD COLUMN `footisstatus` TINYINT(1) DEFAULT 0 NULL COMMENT '是否显示通底：1：是；2：否；默认：0；' AFTER `textinfo`, ADD COLUMN `footclass` TINYINT(1) DEFAULT 1 NULL COMMENT '通底样式：1：eshop通底；默认：1；' AFTER `footisstatus`, ADD COLUMN `headlogo` VARCHAR(400) DEFAULT '' NULL COMMENT '通头LOGO' AFTER `footclass`, ADD COLUMN `headnavid` VARCHAR(500) DEFAULT '' NULL COMMENT '通头导航的ID' AFTER `headlogo`, ADD COLUMN `headsearchstatus` TINYINT(1) DEFAULT 0 NULL COMMENT '通头搜索栏设置：1：开启；2：关闭；默认：0；' AFTER `headnavid`, ADD COLUMN `headsearchclass` TINYINT(1) DEFAULT 0 NULL COMMENT '通头搜索栏样式：1：商品搜索栏' AFTER `headsearchstatus`;");
        echo $sql2.'这是第2个sql<br/>';
        $sql3 = M()->execute("ALTER TABLE `tp_wei_assembly` ADD COLUMN `headnavstatus` TINYINT(1) DEFAULT 0 NULL COMMENT '通头导航设置：1：开启；2：关闭；默认：0；' AFTER `headnavid`;");
        echo $sql3.'这是第3个sql<br/>';
        $sql4 = M()->execute("ALTER TABLE `tp_wei_assembly` ADD COLUMN `assid` VARCHAR(30) DEFAULT '' NULL COMMENT '自定义组件的ID' AFTER `parentid`;");
        echo $sql4.'这是第4个sql<br/>';
        $sql5 = M()->execute("CREATE TABLE `tp_wei_list_nav` (
  `id` varchar(30) NOT NULL COMMENT '微页面导航的ID',
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司ID',
  `assid` varchar(30) NOT NULL DEFAULT '' COMMENT '组件的ID',
  `parentid` varchar(30) NOT NULL DEFAULT '0' COMMENT '父级导航ID',
  `title` varchar(200) NOT NULL DEFAULT '' COMMENT '标题。。',
  `url` varchar(400) NOT NULL DEFAULT '' COMMENT '关联网页链接',
  `isstatus` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否开启：1：开启；2：禁用；默认：1；',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '导航排序',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '最后修改时间',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        echo $sql5.'这是第5个sql<br/>';
        $sql6 = M()->execute("ALTER TABLE `tp_wei_assembly` CHANGE `headsearchclass` `headsearchclass` TINYINT(1) DEFAULT 0 NULL COMMENT '通头搜索栏样式：1：商品搜索栏;2:微页面搜索栏';");
        echo $sql6.'这是第6个sql<br/>';
        $sql7 = M()->execute("ALTER TABLE `tp_wei_assembly` ADD COLUMN `isshowtime` TINYINT(1) DEFAULT 2 NULL COMMENT '是否显示发布时间' AFTER `sort`, ADD COLUMN `articletitle` VARCHAR(500) DEFAULT '' NULL COMMENT '文章标题' AFTER `isshowtime`;");
        echo $sql7.'这是第7个sql<br/>';
        $sql8 = M()->execute("ALTER TABLE `tp_wei_assembly` CHANGE `isshowtime` `isshowtime` TINYINT(1) DEFAULT 1 NULL COMMENT '是否显示发布时间1：是；2：否；默认：1';");
        echo $sql8.'这是第8个sql<br/>';
        $sql9 = M()->execute("ALTER TABLE `tp_wei_assembly` ADD COLUMN `articletype` TINYINT(1) DEFAULT 1 NULL COMMENT '列表样式；默认：1' AFTER `articletitle`;");
        echo $sql9.'这是第9个sql<br/>';
        $sql10 = M()->execute("ALTER TABLE `tp_wei_assembly` CHANGE `type` `type` TINYINT(2) DEFAULT 0 NOT NULL COMMENT '类型 1、banner；2、多图（4*1）；3、多图（3*1）；4、多图（2*1）；5、幻灯片；6、商品；7、辅助空白；8、搜索栏；9：多图（1*1）10：富文本；11：通头；12：通底；13:文章列表；默认：0';");
        echo $sql10.'这是第10个sql<br/>';
        $sql11 = M()->execute("CREATE TABLE `tp_wei_list_group` (
  `id` varchar(30) NOT NULL DEFAULT '',
  `adduid` int(11) NOT NULL DEFAULT '0' COMMENT '添加uid',
  `edituid` int(11) NOT NULL DEFAULT '0' COMMENT '编辑uid',
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  `shopsid` int(11) NOT NULL DEFAULT '0' COMMENT '分店id',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '分组名称',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '修改时间',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        echo $sql11.'这是第11个sql<br/>';
        echo '######################### 微页面SQL 结束#######################################<br/>';
    }
	/**
	 * 券商品改版的SQL执行语句
	 * @author Thomas<416369046@qq.com>
	 * @since  2016-11-21
	 */
    public function eshopSql(){
    	echo '######################### eshop券商品SQL 开始#######################################<br/>';
    	$sql1 = M()->execute("ALTER TABLE `tp_member_marketing_activities_voucher_info` CHANGE `vouchertype` `vouchertype` TINYINT(3) DEFAULT 0 NOT NULL COMMENT '卡券类型：1：eshop优惠券，2：门店使用优惠券；3：兑换券；4：红包；默认：0；', ADD COLUMN `discounttype` TINYINT(2) DEFAULT 0 NOT NULL COMMENT '优惠类型：1：立减；2：立折；3：满减；4：满折；5：每满减；默认：0；' AFTER `vouchercreatecatid`, ADD COLUMN `minus` DECIMAL(10,2) DEFAULT 0.00 NOT NULL COMMENT '立减多少元' AFTER `discounttype`, ADD COLUMN `discount` TINYINT(2) DEFAULT 0 NOT NULL COMMENT '立折多少折扣' AFTER `minus`, ADD COLUMN `fullminus` VARCHAR(50) DEFAULT '' NOT NULL COMMENT '满减多少元' AFTER `discount`, ADD COLUMN `fulldiscount` VARCHAR(50) DEFAULT '' NOT NULL COMMENT '满折多少折扣' AFTER `fullminus`, ADD COLUMN `eachfullminus` VARCHAR(50) DEFAULT '' NOT NULL COMMENT '每满多少元减多少元' AFTER `fulldiscount`, ADD COLUMN `usetimelimittype` TINYINT(2) DEFAULT 0 NOT NULL COMMENT '使用时间限制类型：1：购买后几日内有效精确到时分秒在支付完成时间的基础上加上填写的天数时间；2：按照时间段进行限制精确到时分秒例如2016-10-21至2016-10-22，那就是2016-10-21 00:00:00 至2016-10-22 23:59:59有效；3：限制选定的某一天内有效' AFTER `eachfullminus`, ADD COLUMN `usetimelimitset` VARCHAR(50) DEFAULT '' NOT NULL COMMENT '精确到时分秒例如：2016-10-21 就是2016-10-21 00:00:00 至2016-10-21 23:59:59内有效；默认：1；' AFTER `usetimelimittype`;");
    	echo $sql1.'这是第1个sql<br/>';
    	$sql2 = M()->execute("ALTER TABLE `tp_member_marketing_activities_voucher_info` CHANGE `id` `id` VARCHAR(30) NOT NULL COMMENT '系统自定义'; ");
    	echo $sql2.'这是第2个sql<br/>';
    	$sql3 = M()->execute("ALTER TABLE `tp_member_marketing_activities_voucher_info` CHANGE `minus` `minus` DECIMAL(10,2) DEFAULT 0.00 NULL COMMENT '立减多少元', CHANGE `discount` `discount` TINYINT(2) DEFAULT 0 NULL COMMENT '立折多少折扣', CHANGE `fullminus` `fullminus` VARCHAR(50) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NULL COMMENT '满减多少元', CHANGE `fulldiscount` `fulldiscount` VARCHAR(50) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NULL COMMENT '满折多少折扣', CHANGE `eachfullminus` `eachfullminus` VARCHAR(50) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NULL COMMENT '每满多少元减多少元'; ");
    	echo $sql3.'这是第3个sql<br/>';
    	$sql4 = M()->execute("ALTER TABLE `tp_member_marketing_activities_voucher_info` CHANGE `usetimelimitset` `usetimelimitset` VARCHAR(200) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL COMMENT '精确到时分秒例如：2016-10-21 就是2016-10-21 00:00:00 至2016-10-21 23:59:59内有效；默认：1；'; ");
    	echo $sql4.'这是第4个sql<br/>';
    	$sql5 = M()->execute("ALTER TABLE `tp_member_vouchers` CHANGE `voucherinfoid` `voucherinfoid` VARCHAR(30) DEFAULT '0' NOT NULL COMMENT '券id'; ");
    	echo $sql5.'这是第5个sql<br/>';
    	$sql6 = M()->execute("ALTER TABLE `tp_member_marketing_activities_voucher_info` CHANGE `discounttype` `discounttype` TINYINT(2) DEFAULT 0 NULL COMMENT '优惠类型：1：立减；2：立折；3：满减；4：满折；5：每满减；默认：0；', CHANGE `usetimelimittype` `usetimelimittype` TINYINT(2) DEFAULT 0 NULL COMMENT '使用时间限制类型：1：购买后几日内有效精确到时分秒在支付完成时间的基础上加上填写的天数时间；2：按照时间段进行限制精确到时分秒例如2016-10-21至2016-10-22，那就是2016-10-21 00:00:00 至2016-10-22 23:59:59有效；3：限制选定的某一天内有效', CHANGE `usetimelimitset` `usetimelimitset` VARCHAR(200) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NULL COMMENT '精确到时分秒例如：2016-10-21 就是2016-10-21 00:00:00 至2016-10-21 23:59:59内有效；默认：1；'; ");
    	echo $sql6.'这是第6个sql<br/>';
    	$sql7 = M()->execute("ALTER TABLE `tp_member_treasure_box_activities` CHANGE `prizevouchertype1` `prizevouchertype1` SMALLINT(5) DEFAULT 0 NULL COMMENT '一等奖赠送电子券类型：1：优惠券，2：赠品券。', CHANGE `prizevouchersid1` `prizevouchersid1` VARCHAR(30) DEFAULT '' NOT NULL COMMENT '一等奖赠送关联券id', CHANGE `prizevouchersid2` `prizevouchersid2` VARCHAR(30) DEFAULT '' NOT NULL COMMENT '二等奖赠送关联券id', CHANGE `prizevouchersid3` `prizevouchersid3` VARCHAR(30) DEFAULT '' NOT NULL, CHANGE `prizevouchersid4` `prizevouchersid4` VARCHAR(30) DEFAULT '' NOT NULL; ");
    	echo $sql7.'这是第7个sql<br/>';
    	$sql8 = M()->execute("ALTER TABLE `tp_mall_goods` CHANGE `vouchertype` `vouchertype` TINYINT(1) DEFAULT 0 NOT NULL COMMENT '虚拟关联优惠类型：1：eshop优惠券；2：门店使用优惠券；3：兑换券；', CHANGE `vouchersid` `vouchersid` VARCHAR(30) DEFAULT '' NOT NULL COMMENT '虚拟关联券id'; ");
    	echo $sql8.'这是第8个sql<br/>';
    	$sql9 = M()->execute("ALTER TABLE `tp_member_vouchers` CHANGE `vouchertype` `vouchertype` TINYINT(2) DEFAULT 2 NOT NULL COMMENT '卡券类型：2：普通卡券；3：计次卡；4：团购券；5：门票；6：权益卡；7：eshop优惠券；8：门店使用优惠券；9：兑换券；10：红包；默认：0', ADD COLUMN `iscansend` TINYINT(2) DEFAULT 0 NULL COMMENT '卡券是否可转赠，1：可以；0：不可以；默认：0；' AFTER `vouchertype`, ADD COLUMN `useissite` TINYINT(2) DEFAULT 0 NULL COMMENT '卡券使用环境：1：商城使用；2：门店使用；默认：0；' AFTER `iscansend`, ADD COLUMN `discounttype` TINYINT(2) DEFAULT 0 NULL COMMENT '优惠类型：1：立减；2：立折；3：满减；4：满折；5：每满减；默认：0；' AFTER `useissite`, ADD COLUMN `minus` DECIMAL(10,2) DEFAULT 0.00 NULL COMMENT '立减多少元' AFTER `discounttype`, ADD COLUMN `discount` TINYINT(2) DEFAULT 0 NULL COMMENT '立折多少折扣' AFTER `minus`, ADD COLUMN `fullminus` VARCHAR(50) DEFAULT '' NULL COMMENT '满多少元立减' AFTER `discount`, ADD COLUMN `fulldiscount` VARCHAR(50) DEFAULT '' NULL COMMENT '满多少元立折' AFTER `fullminus`, ADD COLUMN `eachfullminus` VARCHAR(50) DEFAULT '' NULL COMMENT '每满多少元立减' AFTER `fulldiscount`;  ");
    	echo $sql9.'这是第9个sql<br/>';
    	$sql15 = M()->execute("ALTER TABLE `tp_company` CHANGE COLUMN `cumulativesales` `totalincome`  decimal(12,2) NOT NULL DEFAULT 0.00 COMMENT '累计总收入，包含所有收单口的消费，例如：eshop,闪惠，微信外卖，手机预订，手机点单，风助手，拉卡拉；' AFTER `registermembernum`;");
    	echo $sql15.'这是第15个sql<br/>';
    	$sql16 = M()->execute("ALTER TABLE `tp_company`
ADD COLUMN `totalrecharge`  decimal(12,2) NOT NULL DEFAULT 0.00 COMMENT '累计充值金额，包含所有的充值方式；例如：后台充值，在线充值，红包充值，积分兑换储值充值；' AFTER `totalincome`,
ADD COLUMN `totalwechatpayincome`  decimal(12,2) NOT NULL DEFAULT 0.00 COMMENT '微信支付累计收入' AFTER `totalrecharge`,
ADD COLUMN `totalalipayincome`  decimal(12,2) NOT NULL DEFAULT 0.00 COMMENT '支付宝支付累计收入' AFTER `totalwechatpayincome`,
ADD COLUMN `totalrechargepayincome`  decimal(12,2) NOT NULL DEFAULT 0.00 COMMENT '储值支付累计收入' AFTER `totalalipayincome`,
ADD COLUMN `totalmoneypayincome`  decimal(12,2) NOT NULL DEFAULT 0.00 COMMENT '现金支付累计收入' AFTER `totalrechargepayincome`,
ADD COLUMN `totalbankcardpayincome`  decimal(12,2) NOT NULL DEFAULT 0.00 COMMENT '银行卡支付累计收入' AFTER `totalmoneypayincome`,
ADD COLUMN `totaleshopincome`  decimal(12,2) NOT NULL DEFAULT 0.00 COMMENT 'eshop累计收入' AFTER `totalbankcardpayincome`,
ADD COLUMN `totaltakeoutincome`  decimal(12,2) NOT NULL DEFAULT 0.00 COMMENT '微信外卖累计收入' AFTER `totaleshopincome`,
ADD COLUMN `totalmobilebookincome`  decimal(12,2) NOT NULL DEFAULT 0.00 COMMENT '手机预订累计收入' AFTER `totaltakeoutincome`,
ADD COLUMN `totalmobilephoneorderincome`  decimal(12,2) NOT NULL DEFAULT 0.00 COMMENT '手机点单累计收入' AFTER `totalmobilebookincome`,
ADD COLUMN `totalshanhuiincome`  decimal(12,2) NOT NULL DEFAULT 0.00 COMMENT '闪惠累计收入' AFTER `totalmobilephoneorderincome`,
ADD COLUMN `totalhelperincome`  decimal(12,2) NOT NULL DEFAULT 0.00 COMMENT '风助手累计收入' AFTER `totalshanhuiincome`,
ADD COLUMN `totallakalaincome`  decimal(12,2) NOT NULL DEFAULT 0.00 COMMENT '拉卡拉累计收入' AFTER `totalhelperincome`");
    	echo $sql16.'这是第16个sql<br/>';
    	$sql17 = M()->execute("ALTER TABLE `tp_company`
ADD COLUMN `totalsellrechargeincome`  decimal(12,2) NOT NULL DEFAULT 0.00 COMMENT '储值卡累计售出金额；仅仅包含 WAP段出售的储值卡金额；' AFTER `totallakalaincome`
    			");
    	echo $sql17.'这是第17个sql<br/>';
    	$sql18 = M()->execute("ALTER TABLE `tp_company_shops`
ADD COLUMN `shoptotalincome`  decimal(12,2) NOT NULL DEFAULT 0.00 COMMENT '门店累计收入' AFTER `viewnum`
    			");
    	echo $sql18.'这是第18个sql<br/>';
    	$sql10 = M()->execute("ALTER TABLE `tp_company`
MODIFY COLUMN `gid`  smallint(5) NOT NULL DEFAULT 0 COMMENT '账号类型 关联company_group' AFTER `servicenumber`,
ADD COLUMN `totalvouchertype1sendnum`  int(11) NOT NULL DEFAULT 0 COMMENT 'eshop优惠券 累计投放量' AFTER `totalsellrechargeincome`,
ADD COLUMN `totalvouchertype2sendnum`  int(11) NOT NULL DEFAULT 0 COMMENT '门店使用优惠券 累计投放量' AFTER `totalvouchertype1sendnum`,
ADD COLUMN `totalvouchertype3sendnum`  int(11) NOT NULL DEFAULT 0 COMMENT '兑换券 累计投放量' AFTER `totalvouchertype2sendnum`,
ADD COLUMN `totalvouchertype4sendamount`  decimal(12,2) NOT NULL DEFAULT 0.00 COMMENT '红包 累计投放总额' AFTER `totalvouchertype3sendnum`");
    	echo $sql10.'这是第10个sql<br/>';
    	$sql11 = M()->execute("CREATE TABLE `tp_member_vouchers_metercard_usetime_log` (
  `id` varchar(30) DEFAULT NULL COMMENT '主键ID',
  `companyid` int(10) DEFAULT NULL COMMENT '公司ID',
  `sn` varchar(30) DEFAULT NULL COMMENT '券号',
  `usetime` int(11) DEFAULT '0' COMMENT '最新核销终止时间',
  `status` tinyint(1) DEFAULT '0' COMMENT '状态 1；未核销；2：核销中；3：核销成功；4；核销失败；默认：1',
  `createtime` int(11) DEFAULT '0' COMMENT '创建时间',
  `updatetime` int(11) DEFAULT '0' COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='计次卡最新核销确认终止时间日志表'");
    	echo $sql11.'这是第11个sql<br/>';
    	$sql12 = M()->execute("CREATE TABLE `tp_member_vouchers_use_record` (
  `id` varchar(30) NOT NULL COMMENT '系统自定义',
  `sn` varchar(30) NOT NULL DEFAULT '0' COMMENT '券号，关联tp_member_vouchers的sn',
  `vouchername` varchar(100) NOT NULL DEFAULT '' COMMENT '卡券名称',
  `vouchertype` tinyint(2) NOT NULL DEFAULT '0' COMMENT '卡券类型：3：计次卡；4：团购券；5：门票；6：权益卡；7：eshop优惠券；8：门店使用优惠券；9：兑换券；10：红包；默认：0',
  `usetime` int(11) NOT NULL DEFAULT '0' COMMENT '核销时间',
  `handleruserid` varchar(30) NOT NULL DEFAULT '0' COMMENT '卡券核销员工账号ID，关联tp_users表id',
  `handlerusername` varchar(30) NOT NULL DEFAULT '' COMMENT '卡券核销员工姓名',
  `handletype` tinyint(2) NOT NULL DEFAULT '0' COMMENT '操作类型：1：核销使用；2：核销撤回；默认：0；',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='后台核销记录表'");
    	echo $sql12.'这是第12个sql<br/>';
    	$sql13 = M()->execute("CREATE TABLE `tp_log_send_vouchers` (
  `id` varchar(30) DEFAULT NULL COMMENT '主键ID',
  `companyid` int(10) DEFAULT NULL COMMENT '公司ID',
  `log` longtext COMMENT '日志描述',
  `createtime` int(11) DEFAULT '0' COMMENT '创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='发券不成功日志记录表'");
    	echo $sql13.'这是第13个sql<br/>';
    	$sql14 = M()->execute("CREATE TABLE `tp_log_refund` (
  `id` varchar(30) DEFAULT NULL COMMENT '主键ID',
  `companyid` int(10) DEFAULT NULL COMMENT '公司ID',
  `log` longtext COMMENT '日志描述',
  `createtime` int(11) DEFAULT '0' COMMENT '创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='退款失败日志记录表'");
    	echo $sql14.'这是第14个sql<br/>';
    	echo '######################### eshop券商品SQL 结束#######################################<br/>';
    }
    /**
     * kevin 储值
     * @author 姚成凯<kevin@renlaifeng.cn>
     * @since  2016-11-21
     */
    public function chuzhiSQL(){
    	echo '######################### 储值 SQL 开始#######################################<br/>';
    	$sql1 = M()->execute("ALTER TABLE `tp_member_spending` CHANGE `type` `type` TINYINT(3) DEFAULT 0 NOT NULL COMMENT '消费类型 完善会员资料:101;注册:102;点评奖励:103;手动加积分:104;风助手手机收银:105;闪惠支付:106;风助手手机收银:107;微信关注:108;首次消费:109;eshop支付:110;拉卡拉手机收银:111;充值:112;手机预订:113;微信外卖：114；手机点单：115；手动扣除积分:201;到期自动清零:202;积分换储值:203;积分商城:204;默认：0；'; ");
    	echo $sql1.'这是第1个sql<br/>';
    	$sql2 = M()->execute("ALTER TABLE `tp_member_spending` CHANGE `type` `type` INT(3) DEFAULT 0 NOT NULL COMMENT '消费类型 风助手手机收银:105;闪惠支付:106;风助手手机收银:107;首次消费:109;eshop支付:110;拉卡拉手机收银:111;充值:112;手机预订:113;微信外卖：114；手机点单：115；积分换储值:203;后台储值消费:301；默认：0；'; ");
    	echo $sql2.'这是第2个sql<br/>';
    	$sql3 = M()->execute("ALTER TABLE `tp_member_spending` ADD COLUMN `paytype`  tinyint(2) NOT NULL DEFAULT 0 COMMENT '支付方式: 微信支付：1；支付宝支付：2；现金支付：3；储值支付：4；银行卡支付：5；' AFTER `type`;");
    	echo $sql3.'这是第3个sql<br/>';
    	$sql4 = M()->execute("ALTER TABLE `tp_member_spending` ADD COLUMN `linkorderid`  varchar(30) NOT NULL DEFAULT 0 COMMENT '交易存入需要把对应的订单号一起存入，并不是所有的交易都带有订单号' AFTER `orderid`;");
    	echo $sql4.'这是第4个sql<br/>';
    	$sql5 = M()->execute("ALTER TABLE `tp_member_spending` ADD COLUMN `note` VARCHAR(2000) DEFAULT '' NOT NULL COMMENT '备注' AFTER `status`, ADD COLUMN `userid` INT(11) DEFAULT 0 NOT NULL COMMENT '操作人id' AFTER `note`, ADD COLUMN `username` VARCHAR(50) DEFAULT '' NOT NULL COMMENT '操作人名称' AFTER `userid`, ADD COLUMN `shopname` VARCHAR(100) DEFAULT '' NOT NULL COMMENT '门店名称' AFTER `shopid`, CHANGE `updatetime` `updatetime` INT(10) DEFAULT 0 NOT NULL COMMENT '更新时间' AFTER `createtime`;");
    	echo $sql5.'这是第5个sql<br/>';
    	$sql6 = M()->execute("ALTER TABLE `tp_member_spending` ADD COLUMN `rechargetype` TINYINT(2) DEFAULT 0 NOT NULL COMMENT '充值方式，用于充值记录；后台充值：1；在线充值：2；积分换储值：3；红包：4；默认：0；' AFTER `paytype`;");
    	echo $sql6.'这是第6个sql<br/>';
    	$sql7 = M()->execute("ALTER TABLE `tp_member_spending` CHANGE `spendingamount` `spendingamount` DECIMAL(12,2) DEFAULT 0.00 NOT NULL COMMENT '消费金额', ADD COLUMN `spendingamount2` DECIMAL(12,2) DEFAULT 0.00 NOT NULL COMMENT '储值改变记录金额' AFTER `spendingamount`;");
    	echo $sql7.'这是第7个sql<br/>';
    	$sql8 = M()->execute("CREATE TABLE `tp_storedvalue_goods` (
  `id` varchar(30) NOT NULL DEFAULT '' COMMENT '主键ID（系统随机生成）',
  `adduid` int(11) DEFAULT '0' COMMENT '添加用户id',
  `edituid` int(11) DEFAULT '0' COMMENT '编辑用户id',
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  `shopsid` int(11) DEFAULT '0' COMMENT '分店id',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '名称',
  `price` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '售价',
  `recharge` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '实际储值',
  `discount` decimal(12,1) DEFAULT '0.0' COMMENT '折扣',
  `updatetime` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8");
    	echo $sql8.'这是第8个sql<br/>';
    	$sql9 = M()->execute("CREATE TABLE `tp_storedvalue_order` (
  `id` varchar(30) NOT NULL DEFAULT '' COMMENT '主键ID（系统随机生成）',
  `adduid` int(11) DEFAULT '0' COMMENT '添加用户id',
  `edituid` int(11) DEFAULT '0' COMMENT '编辑用户id',
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  `shopsid` int(11) DEFAULT '0' COMMENT '分店id',
  `mid` int(11) NOT NULL DEFAULT '0' COMMENT '会员id',
  `orderid` varchar(50) NOT NULL DEFAULT '0' COMMENT '订单编号',
  `out_trade_no` varchar(50) DEFAULT '' COMMENT '商户订单号,和微信商户平台统一',
  `borderid` varchar(50) DEFAULT '' COMMENT '交易编号',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '商品名称',
  `price` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '商品售价',
  `recharge` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '实际储值',
  `tatus` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态 1、未支付；2、支付成功；默认：0；',
  `paytime` int(11) DEFAULT '0' COMMENT '支付时间',
  `payprice` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '支付金额',
  `updatetime` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8");
    	echo $sql9.'这是第9个sql<br/>';
    	$sql10 = M()->execute("insert  into `tp_wechat_event`(`id`,`name`,`isshow`) values (9,'储值',1);");
    	echo $sql10.'这是第10个sql<br/>';
    	$sql11 = M()->execute("insert  into `tp_wechat_event_type`(`id`,`fid`,`typename`,`triggering`,`page`,`url`,`data`,`isshow`,`type`,`tplid`) values (30,9,'任务处理通知','储值充值','储值首页','/index.php?g=Wap&m=Storedvalue&a=index&companyid=','{\"first\":\"\\u50a8\\u503c\\u5145\\u503c\\u6210\\u529f\\uff01\",\"content\":[[\"\\u4efb\\u52a1\\u540d\\u79f0\",\"\",\"\"],[\"\\u901a\\u77e5\\u7c7b\\u578b\",\"\",\"\"]],\"remark\":[[\"\\u5145\\u503c\\u91d1\\u989d\",\"\",\"\"],[\"\\u53ef\\u7528\\u4f59\\u989d\",\"\",\"\"]]}',1,1,''),(31,9,'任务处理通知','储值支付','储值首页','/index.php?g=Wap&m=Storedvalue&a=index&companyid=','{\"first\":\"\\u60a8\\u4ea7\\u751f\\u4e86\\u4e00\\u7b14\\u50a8\\u503c\\u4ea4\\u6613\\uff01\",\"content\":[[\"\\u4efb\\u52a1\\u540d\\u79f0\",\"\",\"\"],[\"\\u901a\\u77e5\\u7c7b\\u578b\",\"\",\"\"]],\"remark\":[[\"\\u6d88\\u8d39\\u91d1\\u989d\",\"\",\"\"],[\"\\u53ef\\u7528\\u4f59\\u989d\",\"\",\"\"]]}',1,1,''),(32,9,'任务处理通知','积分兑储值','储值首页','/index.php?g=Wap&m=Storedvalue&a=index&companyid=','{\"first\":\"\\u79ef\\u5206\\u5151\\u6362\\u50a8\\u503c\\u6210\\u529f\\uff01\",\"content\":[[\"\\u4efb\\u52a1\\u540d\\u79f0\",\"\",\"\"],[\"\\u901a\\u77e5\\u7c7b\\u578b\",\"\",\"\"]],\"remark\":[[\"\\u6d88\\u8d39\\u79ef\\u5206\",\"\",\"\"],[\"\\u5151\\u6362\\u50a8\\u503c\",\"\",\"\"],[\"\\u50a8\\u503c\\u4f59\\u989d\",\"\",\"\"]]}',1,1,''),(33,4,'任务处理通知','获得红包','会员中心','/index.php?g=Wap&m=Member&a=center&companyid=','{\"first\":\"\\u606d\\u559c\\u60a8\\u83b7\\u5f97\\u4e00\\u4e2a\\u7ea2\\u5305\\uff01\",\"content\":[[\"\\u4efb\\u52a1\\u540d\\u79f0\",\"\",\"\"],[\"\\u901a\\u77e5\\u7c7b\\u578b\",\"\",\"\"]],\"remark\":[[\"\\u7ea2\\u5305\\u540d\\u79f0\",\"\",\"\"],[\"\\u7ea2\\u5305\\u9762\\u503c\",\"\",\"\"]],\"end\":\"\\u7ea2\\u5305\\u83b7\\u5f97\\u540e\\uff0c\\u76f4\\u63a5\\u5145\\u5165\\u50a8\\u503c\\u4f59\\u989d\"}',1,1,'');");
    	echo $sql11.'这是第11个sql<br/>';
    	$sql12 = M()->execute("CREATE TABLE `tp_log_storedvalue_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `adduid` int(11) NOT NULL DEFAULT '0' COMMENT '添加用户id',
  `edituid` int(11) NOT NULL DEFAULT '0' COMMENT '编辑用户id',
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  `shopsid` int(11) NOT NULL DEFAULT '0' COMMENT '分店id',
  `out_trade_no` varchar(50) NOT NULL DEFAULT '0' COMMENT '订单编号',
  `log` varchar(2000) NOT NULL DEFAULT '' COMMENT 'log 日志',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8");
    	echo $sql12.'这是第12个sql<br/>';
    	$sql13 = M()->execute("ALTER TABLE `tp_storedvalue_order` ADD COLUMN `discount` DECIMAL(12,1) DEFAULT 0.0 NOT NULL COMMENT '折扣' AFTER `recharge`;");
    	echo $sql13.'这是第13个sql<br/>';
    	$sql14 = M()->execute("CREATE TABLE `tp_log_storedvalue_paymentcode` (
  `id` varchar(30) NOT NULL DEFAULT '' COMMENT '主键ID（系统随机生成）',
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  `mid` int(11) NOT NULL DEFAULT '0' COMMENT '会员id',
  `price` decimal(12,2) DEFAULT '0.00' COMMENT '扣除金额',
  `balance` decimal(12,2) DEFAULT '0.00' COMMENT '可用余额',
  `expirytime` int(11) NOT NULL DEFAULT '0' COMMENT '二维码过期时间',
  `tatus` tinyint(1) NOT NULL DEFAULT '2' COMMENT '状态 1、支付成功；2、未支付；默认：2；',
  `updatetime` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8");
    	echo $sql14.'这是第14个sql<br/>';
    	echo '######################### 粉码 SQL 结束#######################################<br/>';
    }
    /**
     * 今日头条通Sql
     * @author Thomas<416369046@qq.com>
     * @since  2016-12-8
     */
    public function headingLineSQL(){
        echo '######################### 今日头条通 SQL 开始#######################################<br/>';
        $sql1 = M()->execute("insert  into `tp_system_scrm5_permissions_list`(`id`,`name`,`desc`,`parentid`,`sort`,`isshow`,`isedit`,`updatetime`,`createtime`) values (42,'今日头条通','',0,42,1,1,1473735650,1473735650);");
        echo $sql1.'这是第1个sql<br/>';
        $sql2 = M()->execute("CREATE TABLE `tp_heading_line` (
  `id` varchar(30) NOT NULL DEFAULT '0' COMMENT '主键ID（系统随机生成）',
  `adduid` int(11) NOT NULL DEFAULT '0' COMMENT '添加用户id',
  `edituid` int(11) NOT NULL DEFAULT '0' COMMENT '编辑用户id',
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  `title` varchar(200) NOT NULL DEFAULT '' COMMENT '网页标题',
  `content` longtext NOT NULL COMMENT '文章内容',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '文章状态：1：草稿：2：未通过：3：审核中；4：已发表；默认：1；',
  `facetype` tinyint(2) NOT NULL DEFAULT '1' COMMENT '封面图片类型：1：一张封面图；2：三张封面图；默认：1；',
  `facepic1` varchar(400) DEFAULT '' COMMENT '封面图片1',
  `facepic2` varchar(400) DEFAULT '' COMMENT '封面图片2',
  `facepic3` varchar(400) DEFAULT '' COMMENT '封面图片3',
  `facepic4` varchar(400) DEFAULT '' COMMENT '封面图片4',
  `category` tinyint(2) NOT NULL DEFAULT '0' COMMENT '文章分类：1：新闻；2：社会；3：娱乐；4：电影；5：科技；6：数码；7：时尚；8：奇葩；9：育儿；10：瘦身；11：养身；12：美食；13：历史；14：故事；15：情感；16：健康；17：教育；18：家具；19：房产；20；文化；21：摄影；22：三农；默认：0；',
  `linkurl` varchar(200) NOT NULL DEFAULT '' COMMENT '文章链接',
  `failreason` varchar(5000) NOT NULL DEFAULT '' COMMENT '失败缘由',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='今日头条通';");
        echo $sql2.'这是第2个sql<br/>';
        echo '######################### 今日头条通 SQL 结束#######################################<br/>';
    }
    
    /**
     * 
     * 风外卖执行sql语句
     * 
     * @author Leo<1251868177@qq.com>
     * @since  2016-12-8
     */
    public function takeoutSQL(){
        echo '######################### 风外卖 SQL 开始 #######################################<br/>';
        $sql1 = M()->execute("INSERT INTO `tp_system_scrm5_permissions_list` (`name`, `isshow`) VALUES ('微信外卖', '1');");
        echo $sql1.'这是第1个sql<br/>';
        $sql2 = M()->execute("UPDATE `tp_system_scrm5_permissions_list` SET `updatetime` = '1473735650' , `createtime` = '1473735650' WHERE `id` = '43';");
        echo $sql2.'这是第2个sql<br/>';
        $sql3 = M()->execute("ALTER TABLE `tp_member_shop_address` ADD COLUMN `lng` DECIMAL(10,6) DEFAULT 0.00 NOT NULL COMMENT '经度' AFTER `isdefault`, ADD COLUMN `lat` DECIMAL(10,6) DEFAULT 0.00 NOT NULL COMMENT '纬度' AFTER `lng`;");
        echo $sql3.'这是第3个sql<br/>';
        $sql4 = M()->execute("INSERT INTO `tp_wechat_event` (`id`, `name`, `isshow`) VALUES ('10', '风外卖', '1');");
        echo $sql4.'这是第4个sql<br/>';
        // $sql5 = M()->execute("INSERT INTO `tp_wechat_event_type` (`fid`, `typename`, `triggering`, `page`) VALUES ('10', '任务处理通知', '确认接单', '订单历史');");
        // echo $sql5.'这是第5个sql<br/>';
        // $sql6 = M()->execute("UPDATE `tp_wechat_event_type` SET `url` = '/index.php?g=Wap&m=TakeOut&a=orderList&companyid=' , `isshow` = '1' WHERE `id` = '34';");
        // echo $sql6.'这是第6个sql<br/>';
        // $sql7 = M()->execute("UPDATE `tp_wechat_event_type` SET `data` = '{\"first\":\"\\u62b1\\u6b49\\uff0c\\u60a8\\u7684\\u8ba2\\u5355\\u65e0\\u6cd5\\u4e3a\\u60a8\\u914d\\u9001.\",\"content\":[[\"\\u4efb\\u52a1\\u540d\\u79f0\",\"\",\"\"],[\"\\u901a\\u77e5\\u7c7b\\u578b\",\"\",\"\"]],\"remark\":[[\"\\u4e0b\\u5355\\u65f6\\u95f4\",\"\",\"\"]],\"end\":\"\\u60a8\\u5df2\\u652f\\u4ed8\\u7684\\u91d1\\u989d\\u5c06\\u539f\\u8def\\u9000\\u8fd8\\uff0c\\u5982\\u6709\\u7591\\u95ee\\u8bf7\\u60a8\\u8054\\u7cfb\\u5546\\u5bb6\\uff1a020-2909010\\uff08\\u95e8\\u5e97\\u7535\\u8bdd\\uff09\"}}' WHERE `id` = '34';");
        // echo $sql7.'这是第7个sql<br/>';
        // $sql8 = M()->execute("INSERT INTO `tp_wechat_event_type` (`data`) VALUES ('{\"first\":\"\\u62b1\\u6b49\\uff0c\\u60a8\\u7684\\u8ba2\\u5355\\u65e0\\u6cd5\\u4e3a\\u60a8\\u914d\\u9001.\",\"content\":[[\"\\u4efb\\u52a1\\u540d\\u79f0\",\"\",\"\"],[\"\\u901a\\u77e5\\u7c7b\\u578b\",\"\",\"\"]],\"remark\":[[\"\\u4e0b\\u5355\\u65f6\\u95f4\",\"\",\"\"]],\"end\":\"\\u60a8\\u5df2\\u652f\\u4ed8\\u7684\\u91d1\\u989d\\u5c06\\u539f\\u8def\\u9000\\u8fd8\\uff0c\\u5982\\u6709\\u7591\\u95ee\\u8bf7\\u60a8\\u8054\\u7cfb\\u5546\\u5bb6\\uff1a020-2909010\\uff08\\u95e8\\u5e97\\u7535\\u8bdd\\uff09\"}');");
        // echo $sql8.'这是第8个sql<br/>';
        // $sql9 = M()->execute("UPDATE `tp_wechat_event_type` SET `url` = '/index.php?g=Wap&m=TakeOut&a=orderList&companyid=' WHERE `id` = '35';");
        // echo $sql9.'这是第9个sql<br/>';
        // $sql10 = M()->execute("UPDATE `tp_wechat_event_type` SET `fid` = '10' , `typename` = '任务处理通知' , `triggering` = '拒绝接单' , `page` = '订单历史' , `isshow` = '1' WHERE `id` = '35';");
        // echo $sql10.'这是第10个sql<br/>';
        // $sql11 = M()->execute("INSERT INTO `tp_wechat_event_type` (`fid`, `typename`, `triggering`, `page`) VALUES ('10', '任务处理通知', '提醒取餐', '订单历史');");
        // echo $sql11.'这是第11个sql<br/>';
        // $sql12 = M()->execute("UPDATE `tp_wechat_event_type` SET `url` = '/index.php?g=Wap&m=TakeOut&a=orderList&companyid=' , `isshow` = '1' WHERE `id` = '36';");
        // echo $sql12.'这是第12个sql<br/>';
        // $sql13 = M()->execute("UPDATE `tp_wechat_event_type` SET `data` = '{\"first\":\"\\u60a8\\u597d\\uff0c\\u60a8\\u7684\\u9910\\u54c1\\u5df2\\u5907\\u597d.\",\"content\":[[\"\\u4efb\\u52a1\\u540d\\u79f0\",\"\",\"\"],[\"\\u901a\\u77e5\\u7c7b\\u578b\",\"\",\"\"]],\"remark\":[[\"\\u4e0b\\u5355\\u65f6\\u95f4\",\"\",\"\"],[\"\\u95e8\\u5e97\\u5730\\u5740\",\"\",\"\"]],\"end\":\"\\u4e3a\\u4e86\\u4fdd\\u8bc1\\u9910\\u54c1\\u65b0\\u9c9c\\u7f8e\\u5473\\uff0c\\u8bf7\\u60a8\\u53ca\\u65f6\\u53d6\\u9910\"}' WHERE `id` = '36';");
        // echo $sql13.'这是第13个sql<br/>';
        // $sql14 = M()->execute("INSERT INTO `tp_wechat_event_type` (`fid`, `typename`, `triggering`) VALUES ('10', '任务处理通知', '取消订单');");
        // echo $sql14.'这是第14个sql<br/>';
        // $sql15 = M()->execute("UPDATE `tp_wechat_event_type` SET `page` = '订单历史' WHERE `id` = '37';");
        // echo $sql15.'这是第15个sql<br/>';
        // $sql16 = M()->execute("UPDATE `tp_wechat_event_type` SET `url` = '/index.php?g=Wap&m=TakeOut&a=orderList&companyid=' WHERE `id` = '37';");
        // echo $sql16.'这是第16个sql<br/>';
        // $sql17 = M()->execute("UPDATE `tp_wechat_event_type` SET `isshow` = '1' WHERE `id` = '37';");
        // echo $sql17.'这是第17个sql<br/>';
        // $sql19 = M()->execute("UPDATE `tp_wechat_event_type` SET `data` = '{\"first\":\"\\u62b1\\u6b49\\uff0c\\u60a8\\u7684\\u8ba2\\u5355\\u65e0\\u6cd5\\u4e3a\\u60a8\\u914d\\u9001.\",\"content\":[[\"\\u4efb\\u52a1\\u540d\\u79f0\",\"\",\"\"],[\"\\u901a\\u77e5\\u7c7b\\u578b\",\"\",\"\"]],\"remark\":[[\"\\u4e0b\\u5355\\u65f6\\u95f4\",\"\",\"\"]],\"end\":\"\\u60a8\\u5df2\\u652f\\u4ed8\\u7684\\u91d1\\u989d\\u5c06\\u539f\\u8def\\u9000\\u8fd8\\uff0c\\u5982\\u6709\\u7591\\u95ee\\u8bf7\\u60a8\\u8054\\u7cfb\\u5546\\u5bb6\\uff1a020-2909010\\uff08\\u95e8\\u5e97\\u7535\\u8bdd\\uff09\"}' WHERE `id` = '37';");
        // echo $sql19.'这是第19个sql<br/>';
        // $sql20 = M()->execute("INSERT INTO `tp_wechat_event_type` (`fid`) VALUES ('10');");
        // echo $sql20.'这是第20个sql<br/>';
        // $sql21 = M()->execute("UPDATE `tp_wechat_event_type` SET `typename` = '任务处理通知' , `triggering` = '取消订单' , `page` = '订单历史' WHERE `id` = '38';");
        // echo $sql21.'这是第21个sql<br/>';
        // $sql22 = M()->execute("UPDATE `tp_wechat_event_type` SET `url` = '/index.php?g=Wap&m=TakeOut&a=orderList&companyid=' , `data` = '{\"first\":\"15\\u5206\\u949f\\u5185\\u672a\\u5b8c\\u6210\\u652f\\u4ed8\\uff0c\\u8ba2\\u5355\\u5df2\\u81ea\\u52a8\\u53d6\\u6d88.\",\"content\":[[\"\\u4efb\\u52a1\\u540d\\u79f0\",\"\",\"\"],[\"\\u901a\\u77e5\\u7c7b\\u578b\",\"\",\"\"]],\"remark\":[[\"\\u4e0b\\u5355\\u65f6\\u95f4\",\"\",\"\"]]}' , `isshow` = '1' WHERE `id` = '38';");
        // echo $sql22.'这是第22个sql<br/>';
        // $sql23 = M()->execute("INSERT INTO `tp_wechat_event_type` (`fid`, `typename`) VALUES ('10', '任务处理通知');");
        // echo $sql23.'这是第23个sql<br/>';
        // $sql24 = M()->execute("UPDATE `tp_wechat_event_type` SET `triggering` = '外卖订单' , `page` = '风助手-外卖管理' , `url` = '/index.php?g=Helper&m=TakeOut&a=index&companyid=' , `isshow` = '1' , `type` = '2' WHERE `id` = '39';");
        // echo $sql24.'这是第24个sql<br/>';
        // $sql25 = M()->execute("UPDATE `tp_wechat_event_type` SET `tplid` = 'B7fnXRdp_tjwWX0enNtc2c9zlN-Ax1-NG-hsh1swdRE' WHERE `id` = '39';");
        // echo $sql25.'这是第25个sql<br/>';
        // $sql26 = M()->execute("UPDATE `tp_wechat_event_type` SET `data` = '{\"first\":\"\\u60a8\\u597d\\uff0c\\u60a8\\u7684\\u65b0\\u7684\\u5916\\u5356\\u8ba2\\u5355.\",\"content\":[[\"\\u4efb\\u52a1\\u540d\\u79f0\",\"\",\"\"],[\"\\u901a\\u77e5\\u7c7b\\u578b\",\"\",\"\"]],\"remark\":[[\"\\u4e0b\\u5355\\u65f6\\u95f4\",\"\",\"\"],[\"\\u914d\\u9001\\u65b9\\u5f0f\",\"\",\"\"],[\"\\u8ba2\\u5355\\u91d1\\u989d\",\"\",\"\"]]}' WHERE `id` = '39';");
        // echo $sql26.'这是第26个sql<br/>';
        $sql27 = M()->execute("ALTER TABLE `tp_company_shops` ADD COLUMN `isopentakeout` TINYINT(1) DEFAULT 0 NOT NULL COMMENT '风外卖是否开启  1:是; 2:否; 默认:0' AFTER `isopenshanhui`;");
        echo $sql27.'这是第27个sql<br/>';
        $sql28 = M()->execute("CREATE TABLE `tp_takeout_setup` ( `id` varchar(30) NOT NULL COMMENT '主键(系统自动生成)', `adduid` int(11) NOT NULL DEFAULT '0' COMMENT '添加uid', `edituid` int(11) NOT NULL DEFAULT '0' COMMENT '编辑uid', `shopids` int(11) NOT NULL DEFAULT '0' COMMENT '门店id', `companyid` varchar(30) NOT NULL DEFAULT '' COMMENT '公司id', `shopid` varchar(30) NOT NULL DEFAULT '' COMMENT '门店id', `banner` varchar(150) NOT NULL DEFAULT '' COMMENT '门店banner', `notice` varchar(300) NOT NULL DEFAULT '' COMMENT '公告', `distributiontype` varchar(10) NOT NULL DEFAULT '' COMMENT '配送方式  1:送餐上门; 2:到店自提;  保存格式:'' ,1,2, ''',  `distributionrange` varchar(100) NOT NULL DEFAULT '' COMMENT '配送范围',  `distributionfee` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '配送费',  `startingprice` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '起订价',  `mealtime` int(10) NOT NULL DEFAULT '0' COMMENT '订单提交后取餐时间',  `paytype` varchar(10) NOT NULL DEFAULT '' COMMENT '支付方式   1:微信支付;  2:储值支付;  保存格式:'' ,1,2, ''',  `discountype` tinyint(1) NOT NULL DEFAULT '1' COMMENT '优惠活动类型  1:无优惠;  2:立减优惠  3:立折优惠  4:满减优惠  5:满折优惠  默认:1',  `discount2money` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '立减优惠 -> 减',  `discount3ratio` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '立折优惠 -> 折',  `discount4qualified1` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '满减优惠 -> 满1',  `discount4money1` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '满减优惠 -> 减1',  `discount4qualified2` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '满减优惠 -> 满2',  `discount4money2` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '满减优惠 -> 减2',  `discount4qualified3` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '满减优惠 -> 满3',  `discount4money3` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '满减优惠 -> 减3',  `discount5qualified1` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '满折优惠 -> 满1',  `discount5ratio1` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '满折优惠 -> 折1',  `discount5qualified2` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '满折优惠 -> 满2',  `discount5ratio2` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '满折优惠 -> 折2',  `discount5qualified3` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '满折优惠 -> 满3',  `discount5ratio3` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '满折优惠 -> 折3',  `takeimg` varchar(200) NOT NULL DEFAULT '' COMMENT '微信分享图片',  `taketitle` varchar(400) NOT NULL DEFAULT '' COMMENT '微信分享标题',  `takedescribe` varchar(800) NOT NULL DEFAULT '' COMMENT '微信分享描述',  `printid` varchar(50) NOT NULL DEFAULT '' COMMENT '小票机id',  `printkey` varchar(50) NOT NULL DEFAULT '' COMMENT '小票机key',  `totalorder` int(11) NOT NULL DEFAULT '0' COMMENT '累计订单数',  `monthorder` int(11) NOT NULL DEFAULT '0' COMMENT '月订单数(所有支付完成的订单:签收+拒绝)',  `todayorder` int(11) NOT NULL DEFAULT '0' COMMENT '日订单数(所有支付完成的订单:签收+拒绝)',  `confirmedorder` int(11) NOT NULL DEFAULT '0' COMMENT '已确认订单数',  `pendingorder` int(11) NOT NULL DEFAULT '0' COMMENT '未确认订单数',  `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',  `updatetime` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',  PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8");
        echo $sql28.'这是第28个sql<br/>';
        $sql29 = M()->execute("CREATE TABLE `tp_takeout_order_menu` (`id` varchar(30) NOT NULL DEFAULT '' COMMENT '主键(系统自动生成)', `adduid` int(11) NOT NULL DEFAULT '0' COMMENT '添加uid', `edituid` int(11) NOT NULL DEFAULT '0' COMMENT '编辑uid', `shopids` int(11) NOT NULL DEFAULT '0' COMMENT '门店id', `companyid` varchar(30) NOT NULL DEFAULT '' COMMENT '公司id', `shopid` varchar(30) NOT NULL DEFAULT '' COMMENT '门店id', `setid` varchar(30) NOT NULL DEFAULT '' COMMENT '设置表id【tp_takeout_setup:id】', `orderid` varchar(50) NOT NULL DEFAULT '' COMMENT '订单号', `menuid` varchar(30) NOT NULL DEFAULT '' COMMENT '菜品id', `menuname` varchar(50) NOT NULL DEFAULT '' COMMENT '菜品名称', `skuid` varchar(30) NOT NULL DEFAULT '' COMMENT 'SKUid', `skuname` varchar(50) NOT NULL DEFAULT '' COMMENT 'SKU名称', `menunumber` int(5) NOT NULL DEFAULT '0' COMMENT '数量', `menuunitprice` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '菜品单价', `menutotal` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '菜品总价', `boxunitprice` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '餐盒单价', `boxtotal` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '餐盒总价', `total` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT 'SKU总价', `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间', `updatetime` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间', PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8");
        echo $sql29.'这是第29个sql<br/>';
        $sql30 = M()->execute("CREATE TABLE `tp_takeout_order_log` (`id` int(11) NOT NULL AUTO_INCREMENT, `adduid` int(11) NOT NULL DEFAULT '0' COMMENT '添加uid', `edituid` int(11) NOT NULL DEFAULT '0' COMMENT '编辑uid', `shopsid` int(11) NOT NULL DEFAULT '0' COMMENT '门店id', `companyid` varchar(30) NOT NULL DEFAULT '' COMMENT '公司id', `out_trade_no` varchar(50) NOT NULL DEFAULT '' COMMENT '商户订单号', `log` varchar(2000) NOT NULL DEFAULT '' COMMENT 'log日志', `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间', PRIMARY KEY (`id`)) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8");
        echo $sql30.'这是第30个sql<br/>';
        $sql31 = M()->execute("CREATE TABLE `tp_takeout_order` ( `id` varchar(30) NOT NULL COMMENT '主键(系统自动生成)',  `adduid` int(11) NOT NULL DEFAULT '0' COMMENT '添加uid',  `edituid` int(11) NOT NULL DEFAULT '0' COMMENT '编辑uid',  `shopids` int(11) NOT NULL DEFAULT '0' COMMENT '门店id',  `companyid` varchar(30) NOT NULL DEFAULT '' COMMENT '公司id',  `shopid` varchar(30) NOT NULL DEFAULT '' COMMENT '门店id',  `setid` varchar(30) NOT NULL DEFAULT '' COMMENT '设置表id【tp_takeout_setup:id】',  `mid` varchar(30) NOT NULL DEFAULT '' COMMENT '会员id',  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '会员姓名',  `mobile` varchar(50) NOT NULL DEFAULT '' COMMENT '会员手机号',  `openid` varchar(50) NOT NULL DEFAULT '' COMMENT 'OPENID(提交订单openid)',  `istodoor` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否送餐上门 1:是;  2:否',  `ispickup` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否到店自提 1:是;  2:否',  `ordertype` tinyint(1) NOT NULL DEFAULT '0' COMMENT '订单类型：1：送餐上门;  2:到店自提',  `addressid` varchar(30) NOT NULL DEFAULT '' COMMENT '地址id【tp_member_shop_address:id】',  `addressname` varchar(50) NOT NULL DEFAULT '' COMMENT '地址姓名',  `addressmobile` varchar(20) NOT NULL DEFAULT '' COMMENT '地址手机号',  `address` varchar(500) NOT NULL DEFAULT '' COMMENT '地址',  `picktime` varchar(50) NOT NULL DEFAULT '' COMMENT '取餐时间(到店自提)',  `note` varchar(1000) NOT NULL DEFAULT '' COMMENT '备注',  `mealfee` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '餐盒费',  `menuprice` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '菜品总金额',  `distributionfee` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '配送费',  `orderprice` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '订单金额(不包括配送费)',  `discountprice` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '优惠金额',  `discountname` varchar(100) NOT NULL DEFAULT '' COMMENT '优惠名称',  `discounttype` tinyint(1) NOT NULL DEFAULT '0' COMMENT '优惠类型  1:无优惠; 2:立减优惠 3:立折优惠 4:满减优惠 5:满折优惠 默认:1',  `discountutility` varchar(100) NOT NULL DEFAULT '' COMMENT '优惠效用',  `todoorprice` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '送餐上门金额(包括配送费)',  `pickupprice` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '到店自提金额(没有配送费)',  `payprice` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '实付金额',  `paytype` tinyint(1) NOT NULL DEFAULT '0' COMMENT '支付方式  1:微信支付; 2:储值支付;',  `paystate` tinyint(1) NOT NULL DEFAULT '1' COMMENT '支付状态  1:未支付; 2:支付完成; 3:支付失败; 默认:1',  `paytime` int(11) NOT NULL DEFAULT '0' COMMENT '开始支付时间',  `paydonetime` int(11) NOT NULL DEFAULT '0' COMMENT '支付完成时间',  `payopenid` varchar(50) NOT NULL DEFAULT '' COMMENT '支付openid',  `orderstate` tinyint(1) NOT NULL DEFAULT '0' COMMENT '订单状态：1:未提交; 2:已提交(未支付); 3:已支付(待确认); 4:已确认(待签收); 5:已签收; 6:已取消; 7:已关闭; 默认:1',  `statetime` int(11) NOT NULL DEFAULT '0' COMMENT '最后一次修改时间',  `submittime` int(11) NOT NULL DEFAULT '0' COMMENT '提交时间',  `confirmtime` int(11) NOT NULL DEFAULT '0' COMMENT '确认时间',  `signtime` int(11) NOT NULL DEFAULT '0' COMMENT '签收时间',  `canceltime` int(11) NOT NULL DEFAULT '0' COMMENT '取消时间',  `closetime` int(11) NOT NULL DEFAULT '0' COMMENT '关闭时间',  `invalidtime` int(11) NOT NULL DEFAULT '0' COMMENT '订单失效时间(提交15分钟后失效)',  `orderid` varchar(50) NOT NULL DEFAULT '' COMMENT '订单号',  `tradeid` varchar(50) NOT NULL DEFAULT '' COMMENT '交易号',  `out_trade_no` varchar(50) NOT NULL DEFAULT '' COMMENT '商户订单号',  `transaction_id` varchar(50) NOT NULL DEFAULT '' COMMENT '支付订单号(微信支付返回)',  `userid` varchar(30) NOT NULL DEFAULT '' COMMENT '管理员id',  `username` varchar(50) NOT NULL DEFAULT '' COMMENT '管理员名称',  `useshopid` varchar(30) NOT NULL DEFAULT '' COMMENT '管理员所属门店',  `usershopname` varchar(50) NOT NULL DEFAULT '' COMMENT '管理员门店名称',  `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',  `updatetime` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',  PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8");
        echo $sql31.'这是第31个sql<br/>';
        $sql32 = M()->execute("CREATE TABLE `tp_takeout_menu_sku` (`id` varchar(30) NOT NULL DEFAULT '' COMMENT '主键(系统自动生成)',  `adduid` int(11) NOT NULL DEFAULT '0' COMMENT '添加uid',  `edituid` int(11) NOT NULL DEFAULT '0' COMMENT '编辑uid',  `shopids` int(11) NOT NULL DEFAULT '0' COMMENT '门店id',  `companyid` varchar(30) NOT NULL DEFAULT '' COMMENT '公司id',  `shopid` varchar(30) NOT NULL DEFAULT '' COMMENT '门店id',  `setid` varchar(30) NOT NULL DEFAULT '' COMMENT '配置id【tp_takeout_setup:id】',  `menuid` varchar(30) NOT NULL DEFAULT '' COMMENT '菜品id【tp_takeout_menu:id】',  `specname` varchar(100) NOT NULL DEFAULT '' COMMENT '规格名称',  `specprice` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '价格',  `surplusstock` int(11) NOT NULL DEFAULT '0' COMMENT '每日剩余库存',  `totalstock` int(11) NOT NULL DEFAULT '0' COMMENT '总库存',  `mealfee` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '餐盒费',  `sort` smallint(5) NOT NULL DEFAULT '50' COMMENT '排序',  `pricesort` tinyint(5) NOT NULL DEFAULT '0' COMMENT '价格排序(手机端选中顺序; 价格->排序->更新时间)',  `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',  `updatetime` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',  PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8");
        echo $sql32.'这是第32个sql<br/>';
        $sql33 = M()->execute("CREATE TABLE `tp_takeout_menu_cat` (`id` varchar(30) NOT NULL DEFAULT '' COMMENT '主键(系统自动生成)',  `adduid` int(11) NOT NULL DEFAULT '0' COMMENT '添加uid',  `edituid` int(11) NOT NULL DEFAULT '0' COMMENT '编辑uid',  `shopids` int(11) NOT NULL DEFAULT '0' COMMENT '门店id',  `companyid` varchar(30) NOT NULL DEFAULT '' COMMENT '公司id',  `shopid` varchar(30) NOT NULL DEFAULT '' COMMENT '门店id',  `setid` varchar(30) NOT NULL DEFAULT '' COMMENT '配置id【tp_takeout_setup:id】',  `catname` varchar(30) NOT NULL DEFAULT '' COMMENT '品类名称',  `sort` smallint(5) NOT NULL DEFAULT '50' COMMENT '排序',  `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',  `updatetime` int(11) NOT NULL DEFAULT '0' COMMENT '编辑时间',  PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8");
        echo $sql33.'这是第33个sql<br/>';
        $sql34 = M()->execute("CREATE TABLE `tp_takeout_menu` (`id` varchar(30) NOT NULL DEFAULT '' COMMENT '主键(系统自动生成)',  `adduid` int(11) NOT NULL DEFAULT '0' COMMENT '添加uid',  `edituid` int(11) NOT NULL DEFAULT '0' COMMENT '编辑uid',  `shopids` int(11) NOT NULL DEFAULT '0' COMMENT '门店id',  `companyid` varchar(30) NOT NULL DEFAULT '' COMMENT '公司id',  `shopid` varchar(30) NOT NULL DEFAULT '' COMMENT '门店id',  `setid` varchar(30) NOT NULL DEFAULT '' COMMENT '配置id【tp_takeout_setup:id】',  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '菜品名称',  `catids` varchar(300) NOT NULL DEFAULT '' COMMENT '品类  '',1,2,3,4,''',  `menuimg` varchar(200) NOT NULL DEFAULT '' COMMENT '菜品图片',  `note` varchar(150) NOT NULL DEFAULT '' COMMENT '菜品简述',  `isupshelf` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否上架 1:上架; 2:下架; 默认:0',  `sort` smallint(5) NOT NULL DEFAULT '50' COMMENT '排序',  `totalsales` int(11) NOT NULL DEFAULT '0' COMMENT '累计销量',  `monthsales` int(11) NOT NULL DEFAULT '0' COMMENT '月销量',  `todaysales` int(11) NOT NULL DEFAULT '0' COMMENT '日销量',  `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',  `updatetime` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',  PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8");
        echo $sql34.'这是第34个sql<br/>';
        $sql35 = M()->execute("CREATE TABLE `tp_takeout_delivery_time` ( `id` varchar(30) NOT NULL DEFAULT '' COMMENT '主键(系统自动生成)',  `adduid` int(11) NOT NULL DEFAULT '0' COMMENT '添加uid',  `edituid` int(11) NOT NULL DEFAULT '0' COMMENT '编辑uid',  `shoopids` int(22) NOT NULL DEFAULT '0' COMMENT '门店id',  `companyid` varchar(30) NOT NULL DEFAULT '' COMMENT '公司id',  `shopid` varchar(30) NOT NULL DEFAULT '' COMMENT '门店id',  `setid` varchar(30) NOT NULL DEFAULT '' COMMENT '配置id【tp_takeout_setup:id】',  `startdeliverytime` varchar(10) NOT NULL DEFAULT '' COMMENT '开始配送时间',  `enddeliverytime` varchar(10) NOT NULL DEFAULT '' COMMENT '结束配送时间',  `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',  `updatetime` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',  PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8");
        echo $sql35.'这是第35个sql<br/>';
        $sql36 = M()->execute("ALTER TABLE `tp_takeout_setup` ADD COLUMN `startdeliverytime` VARCHAR(10) DEFAULT '' NOT NULL COMMENT '开始配送时间' AFTER `distributiontype`, ADD COLUMN `enddeliverytime` VARCHAR(10) DEFAULT '' NOT NULL COMMENT '结束配送时间' AFTER `startdeliverytime`;");
        echo $sql36.'这是第36个sql<br/>';
        echo '######################### 风外卖 SQL 结束 #######################################<br/>';
    }   
    /**
     * 权益卡的SQL执行语句
     * @author Thomas<416369046@qq.com>
     * @since  2016-12-16
     */
    public function quanSQL(){
    	echo '######################### 权益卡和过期退 SQL 开始 #######################################<br/>';
    	$sql1 = M()->execute("ALTER TABLE `tp_member_vouchers` ADD COLUMN `scrmactivityid` VARCHAR(30) DEFAULT '0' NULL COMMENT 'SCRM5活动关联每一张券的关联id' AFTER `eachfullminus`; ");
    	echo $sql1.'这是第1个sql<br/>';
    	$sql2 = M()->execute("ALTER TABLE `tp_member_vouchers` ADD COLUMN `isrefund` TINYINT(2) DEFAULT 2 NOT NULL COMMENT '是否已经退款：1：已退款;2:未退款；3:退款中；默认：2；' AFTER `scrmactivityid`; ");
    	echo $sql2.'这是第2个sql<br/>';
    	$sql4 = M()->execute("ALTER TABLE `tp_member_vouchers` ADD COLUMN `issendmessage` TINYINT(2) DEFAULT 2 NOT NULL COMMENT '是否已经发送消息模板：1：已发送；2：未发送；默认：2；' AFTER `isrefund`; ");
    	echo $sql4.'这是第4个sql<br/>';
    	$sql5 = M()->execute("ALTER TABLE `tp_mall_order_info` ADD COLUMN `isrefund` TINYINT(2) DEFAULT 2 NOT NULL COMMENT '是否已经退款：1：已退款;2:未退款；3:退款中；默认：2；' AFTER `issendmessage`; ");
    	echo $sql5.'这是第5个sql<br/>';
    	$sql6 = M()->execute("CREATE TABLE `tp_mall_vouchers_order_service` (
    	`id` varchar(15) NOT NULL DEFAULT '0' COMMENT '主键ID（系统随机生成）',
    	`adduid` int(11) DEFAULT '0' COMMENT '添加用户id',
    	`edituid` int(11) DEFAULT '0' COMMENT '编辑用户id',
    	`companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
    	`shopsid` int(11) DEFAULT '0' COMMENT '分店id',
    	`vouchername` varchar(100) NOT NULL DEFAULT '' COMMENT '卡券名称',
    	`vouchernumber` varchar(20) NOT NULL DEFAULT '0' COMMENT '卡券号',
    	`mid` int(11) NOT NULL DEFAULT '0' COMMENT 'mid',
    	`orderid` varchar(30) NOT NULL DEFAULT '0' COMMENT '订单主表ID',
    	`ordergoodid` varchar(30) NOT NULL DEFAULT '0' COMMENT '订单商品详情ID',
    	`type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '服务类型：1：过期退；2：随时退；默认：0；',
    	`price` decimal(12,2) DEFAULT '0.00' COMMENT '退款价格',
    	`info` longtext COMMENT '申请退货退款说明',
    	`handle` tinyint(1) NOT NULL DEFAULT '2' COMMENT '是否处理 1：处理；2：未处理；默认：1；',
    	`handlernote` varchar(500) DEFAULT '' COMMENT '处理人备注',
    	`handletime` int(10) NOT NULL DEFAULT '0' COMMENT '处理时间',
    	`updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
    	`createtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
    	PRIMARY KEY (`id`)
    	) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    	echo $sql6.'这是第6个sql<br/>';
    	echo '######################### 权益卡和过期退 SQL 结束 #######################################<br/>';
    }
    /**
     * 卡券礼包SQL
     * @author Thomas<416369046@qq.com>
     * @since  2016-12-23
     */
    public function libaoSQL(){
    	echo '######################### 卡券礼包 SQL 开始 #######################################<br/>';
    	$sql1 = M()->execute("ALTER TABLE `tp_member_vouchers` ADD COLUMN `notrefund` TINYINT(2) DEFAULT 0 NOT NULL COMMENT '是否执行过期退：1：不执行；0：执行；默认：0；' AFTER `issendmessage`;  ");
    	echo $sql1.'这是第1个sql<br/>';
    	$sql2 = M()->execute("CREATE TABLE `tp_mall_goods_vouchers_bag` (
  `id` varchar(15) NOT NULL DEFAULT '0' COMMENT '主键ID（系统随机生成）',
  `adduid` int(11) NOT NULL DEFAULT '0' COMMENT '添加用户id',
  `edituid` int(11) NOT NULL DEFAULT '0' COMMENT '编辑用户id',
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  `shopsid` int(11) NOT NULL DEFAULT '0' COMMENT '分店id',
  `goodid` varchar(30) NOT NULL DEFAULT '0' COMMENT '商品id',
  `voucherid` varchar(30) NOT NULL DEFAULT '0' COMMENT '卡券id',
  `vouchername` varchar(400) NOT NULL DEFAULT '' COMMENT '卡券名称',
  `voucherskuid` varchar(30) NOT NULL DEFAULT '0' COMMENT '规格id',
  `voucherskuname` varchar(200) DEFAULT '' COMMENT '规格名称',
  `vouchertype` tinyint(2) NOT NULL DEFAULT '0' COMMENT '卡券类型：1：eshop优惠券；2：门店使用优惠券；3：兑换券；5：计次卡；6：团购；7：门票；8：权益卡',
  `cansendmaxnum` int(11) NOT NULL DEFAULT '0' COMMENT '券可发送数量',
  `usetimelimittype` tinyint(2) NOT NULL DEFAULT '0' COMMENT '使用时间限制类型：1：购买后几日内有效精确到时分秒在支付完成时间的基础上加上填写的天数时间；2：按照时间段进行限制精确到时分秒例如2016-10-21至2016-10-22，那就是2016-10-21 00:00:00 至2016-10-22 23:59:59有效；',
  `usetimelimitset` varchar(200) DEFAULT '' COMMENT '精确到时分秒例如：2016-10-21 就是2016-10-21 00:00:00 至2016-10-21 23:59:59内有效；默认：1；',
  `sort` tinyint(5) NOT NULL DEFAULT '50' COMMENT '排序',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='卡券礼包商品关联券';");
    	echo $sql2.'这是第2个sql<br/>';
    	echo '######################### 卡券礼包 SQL 结束 #######################################<br/>';
    }
    
    /**
     * 
     * 门店收银SQL
     * 
     * @author Leo<1251868177@qq.com>
     * @since  2016-12-23
     */
    public function ShopCashierSQL(){
        echo '######################### 门店收银 SQL 开始 #######################################<br/>';
        $sql1 = M()->execute("ALTER TABLE `tp_shop_cashier_voucher` CHANGE `orderid` `orderid` VARCHAR(30) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL COMMENT '【tp_shop_cashier_order：orderid】', CHANGE `vouchertype` `vouchertype` TINYINT(1) DEFAULT 0 NOT NULL COMMENT '券类型:  1.代金券  2.线下优惠券  3.微信互通券  4:门店使用优惠券';");
        echo $sql1.'这是第1个sql<br/>';
        $sql2 = M()->execute("Create Table CREATE TABLE `tp_shop_cashier_order` (`id` varchar(30) NOT NULL COMMENT '主键(系统自动生成)', `adduid` int(11) NOT NULL DEFAULT '0' COMMENT '添加uid', `edituid` int(11) NOT NULL DEFAULT '0' COMMENT '编辑uid', `shopsid` int(11) NOT NULL DEFAULT '0' COMMENT '门店id', `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id', `receivables` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '消费金额', `notdiscountmoney` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '不参与优惠金额', `discountprice` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '优惠金额', `payprice` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '实收金额', `orderstate` tinyint(1) NOT NULL DEFAULT '1' COMMENT '订单状态  1:收款中;  2:收款完成;  3:收款失败;  默认:1', `startprocesstime` int(11) NOT NULL DEFAULT '0' COMMENT '订单开始处理时间', `doneprocesstime` int(11) NOT NULL DEFAULT '0' COMMENT '订单结束处理时间', `dmsorderid` varchar(50) NOT NULL DEFAULT '' COMMENT 'DMS订单号', `orderid` varchar(50) NOT NULL DEFAULT '' COMMENT '订单号', `cashiertype` tinyint(1) NOT NULL DEFAULT '0' COMMENT '收银方式  1:风助手收银;  2:APP收银;', `note` varchar(1000) NOT NULL DEFAULT '' COMMENT '备注', `shopid` varchar(30) NOT NULL DEFAULT '' COMMENT '门店id', `shopname` varchar(30) NOT NULL DEFAULT '' COMMENT '门店名称', `memberid` varchar(30) NOT NULL DEFAULT '' COMMENT '会员id', `membername` varchar(30) NOT NULL DEFAULT '' COMMENT '会员名称', `membermobile` varchar(20) NOT NULL DEFAULT '' COMMENT '会员手机号', `userid` varchar(30) NOT NULL DEFAULT '' COMMENT '处理人id', `username` varchar(30) NOT NULL DEFAULT '' COMMENT '处理人姓名', `usernickname` varchar(30) NOT NULL DEFAULT '' COMMENT '处理人昵称', `updatetime` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间', `creratetime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间', PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='门店收银：收银订单表' ");
        echo $sql2.'这是第2个sql<br/>';
        $sql3 = M()->execute("CREATE TABLE `tp_shop_cashier_order_pay` (`id` varchar(30) NOT NULL COMMENT '主键(系统自动生成)', `adduid` int(11) NOT NULL DEFAULT '0' COMMENT '添加uid', `edituid` int(11) NOT NULL DEFAULT '0' COMMENT '编辑uid', `shopsid` int(11) NOT NULL DEFAULT '0' COMMENT '门店id', `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id', `shopid` varchar(30) NOT NULL DEFAULT '' COMMENT '门店id', `orderid` varchar(50) NOT NULL DEFAULT '' COMMENT '订单号', `paytype` tinyint(1) NOT NULL DEFAULT '0' COMMENT '收银方式  1:微信支付;  2:支付宝支付;  3:储值支付;  4:现金支付;  5:银行卡支付【拉卡拉】', `spendingamount` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '本次消费金额', `paystate` tinyint(1) NOT NULL DEFAULT '0' COMMENT '支付状态  1:未支付; 2:支付中; 3:支付成功; 4:支付失败;', `tradeid` varchar(50) NOT NULL DEFAULT '' COMMENT '交易号', `out_trade_no` varchar(50) NOT NULL DEFAULT '' COMMENT '商户号【商户订单号】', `transaction_id` varchar(50) NOT NULL DEFAULT '' COMMENT '商户订单号[微信/支付宝返回]', `merid` varchar(50) NOT NULL DEFAULT '' COMMENT '商户号【拉卡拉】', `termid` varchar(50) NOT NULL DEFAULT '' COMMENT '终端号【拉卡拉】', `batchno` varchar(50) NOT NULL DEFAULT '' COMMENT '批次号【拉卡拉】', `systraaceno` varchar(50) NOT NULL DEFAULT '' COMMENT '凭证号【拉卡拉】', `authcode` varchar(50) NOT NULL DEFAULT '' COMMENT '授权号【拉卡拉】', `orderid_scan` varchar(50) NOT NULL DEFAULT '' COMMENT '扫码订单号【拉卡拉】', `paystarttime` int(11) NOT NULL DEFAULT '0' COMMENT '开始支付时间', `paydonetime` int(11) NOT NULL DEFAULT '0' COMMENT '支付完成时间', `payopenid` varchar(50) NOT NULL DEFAULT '' COMMENT '支付人openid', `updatetime` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间', `creratetime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间', PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='门店收银：订单支付记录表' ");
        echo $sql3.'这是第3个sql<br/>';
        
        echo '######################### 门店收银 SQL 结束 #######################################<br/>';
    }
    
    /**
     * 
     * 风外卖第二次修改SQL
     * 
     * @author Leo<1251868177@qq.com>
     * @since  2017-1-6
     */
    public function TakeOut2SQL(){
        echo '######################### 风外卖 SQL 开始 #######################################<br/>';
        $sql1 = M()->execute("ALTER TABLE `tp_takeout_setup` ADD COLUMN `deliverfeetype` TINYINT(1) DEFAULT 0 NOT NULL COMMENT '配送费计费方式  1:统一计费; 2:梯度计费;' AFTER `distributionrange`, ADD COLUMN `unifiedprices` DECIMAL(12,2) DEFAULT 0.00 NOT NULL COMMENT '配送费统一计费价格' AFTER `deliverfeetype`, ADD COLUMN `gradientdistance` VARCHAR(10) DEFAULT '' NOT NULL COMMENT '配送费梯度计费X公里内' AFTER `unifiedprices`, ADD COLUMN `gradientprices` DECIMAL(12,2) DEFAULT 0.00 NOT NULL COMMENT 'X公里内，配送费价格' AFTER `gradientdistance`, ADD COLUMN `exceedprices` DECIMAL(12,2) DEFAULT 0.00 NOT NULL COMMENT '超过每公里价格' AFTER `gradientprices`;");
        echo $sql1.'这是第1个sql<br/>';
        $sql2 = M()->execute("ALTER TABLE `tp_takeout_setup` ADD COLUMN `discounname` VARCHAR(200) DEFAULT '' NOT NULL COMMENT '活动名称' AFTER `paytype`, ADD COLUMN `discounstatus` TINYINT(1) DEFAULT 0 NOT NULL COMMENT '活动状态  1:开启; 0:关闭  默认:0' AFTER `discounname`;");
        echo $sql2.'这是第2个sql<br/>';
        $sql3 = M()->execute("ALTER TABLE `tp_takeout_setup` CHANGE `discountype` `discountype` TINYINT(1) DEFAULT 1 NOT NULL COMMENT '优惠活动类型  1:无优惠;  2:立减优惠  3:立折优惠  4:满减优惠  5:满折优惠  6:减免配送费优惠  默认:1', ADD COLUMN `discount6qualified` DECIMAL(12,2) DEFAULT 0.00 NOT NULL COMMENT '减免配送费优惠 -> 满' AFTER `discount5ratio3`;");
        echo $sql3.'这是第3个sql<br/>';
        $sql4 = M()->query("ALTER TABLE `tp_takeout_menu` ADD COLUMN `skunumber` INT(10) DEFAULT 0 NOT NULL COMMENT '菜品SKU数量' AFTER `todaysales`;");
        echo $sql4.'这是第4个sql<br/>';
        $sql5 = M()->query("ALTER TABLE `tp_takeout_menu` ADD COLUMN `ispromotion` TINYINT(1) DEFAULT 2 NOT NULL COMMENT '是否为优惠菜品  1:是;  2:否;  默认:2' AFTER `skunumber`;");
        echo $sql5.'这是第6个sql<br/>';
        $sql6 = M()->query("ALTER TABLE `tp_takeout_order` CHANGE `discountprice` `discountprice` DECIMAL(12,2) DEFAULT 0.00 NOT NULL COMMENT '优惠金额(临时字段,下次删除)', ADD COLUMN `todoordiscount` DECIMAL(12,2) DEFAULT 0.00 NOT NULL COMMENT '送餐上门优惠金额(包括配送费)' AFTER `discountprice`, ADD COLUMN `pickupdiscount` DECIMAL(12,2) DEFAULT 0.00 NOT NULL COMMENT '门店自提优惠金额(没有配送费)' AFTER `todoordiscount`, CHANGE `discounttype` `discounttype` TINYINT(1) DEFAULT 0 NOT NULL COMMENT '优惠类型  1:无优惠或未开启优惠; 2:立减优惠 3:立折优惠 4:满减优惠 5:满折优惠 6:满免配送费 默认:1';");
        echo $sql6.'这是第6个sql<br/>';
        $sql7 = M()->query("ALTER TABLE `tp_takeout_order` ADD COLUMN `discount6qualified` DECIMAL(12,2) DEFAULT 0.00 NOT NULL COMMENT '减免配送费优惠 -> 满' AFTER `discounttype`, ADD COLUMN `deliverfeetype` TINYINT(1) DEFAULT 0 NOT NULL COMMENT '配送费计费方式 1:统一计费; 2:梯度计费;' AFTER `discount6qualified`, ADD COLUMN `unifiedprices` DECIMAL(12,2) DEFAULT 0.00 NOT NULL COMMENT '配送费统一计费价格' AFTER `deliverfeetype`, ADD COLUMN `gradientdistance` VARCHAR(10) DEFAULT '' NOT NULL COMMENT '配送费梯度计费X公里内' AFTER `unifiedprices`, ADD COLUMN `gradientprices` DECIMAL(12,2) DEFAULT 0.00 NOT NULL COMMENT 'X公里内，配送费价格' AFTER `gradientdistance`, ADD COLUMN `exceedprices` DECIMAL(12,2) DEFAULT 0.00 NOT NULL COMMENT '超过每公里价格' AFTER `gradientprices`;");
        echo $sql7.'这是第7个sql<br/>';

        echo '######################### 风外卖 SQL 结束 #######################################<br/>';
    }
    
    /**
     * 
     * 手机点单SQL
     * 
     * @author Leo<1251868177@qq.com>
     * @since  2017-1-9
     */
    public function MobilePhoneOrderSQL(){
        echo '######################### 手机点单 SQL 开始 #######################################<br/>';
        $sql1 = M()->execute("INSERT INTO `tp_wechat_event` (`id`, `name`, `isshow`) VALUES ('11', '手机点单', '1');");
        echo $sql1.'这是第1个sql<br/>';
        $sql2 = M()->execute("INSERT INTO `tp_wechat_event_type` (`id`, `fid`, `typename`, `triggering`, `page`, `isshow`, `type`) VALUES ('44', '11', '任务处理通知', '当有手机点单订单时，店员风助手收到消息模板', '风助手手机点单订单详情', '1', '2');");
        echo $sql2.'这是第2个sql<br/>';
        $sql3 = M()->execute("UPDATE `tp_wechat_event_type` SET `url` = '/index.php?g=Helper&m=MobilePhoneOrder&a=orderInfo&companyid=' WHERE `id` = '44';");
        echo $sql3.'这是第3个sql<br/>';
        echo '######################### 手机点单 SQL 结束 #######################################<br/>';
    }
    /**
     * 	快捷赠券SQL
     * @author Thomas<416369046@qq.com>
     * @since  2017-1-16
     */
    public function fastSendSql(){
        echo '######################### 快捷赠券 SQL 开始 #######################################<br/>';
        /* $sql1 = M()->execute("ALTER TABLE `tp_mall_goods` ADD COLUMN `grouponprice` DECIMAL(12,2) DEFAULT 0.00 NOT NULL COMMENT '拼团价格，创建拼团商品时将价格写入' AFTER `saleprice`; ");
        	echo $sql1.'这是第1个sql<br/>'; */
        $sql2 = M()->execute("ALTER TABLE `tp_mall_order_info` ADD COLUMN `isgroupgoods` TINYINT(2) DEFAULT 2 NOT NULL COMMENT '是否为拼团商品：1：是；2：否；默认：2；' AFTER `isrefund`;");
        echo $sql2.'这是第2个sql<br/>';
        $sql3 = M()->execute("ALTER TABLE `tp_mall_order_info` CHANGE `orderstatus` `orderstatus` TINYINT(2) DEFAULT 0 NOT NULL COMMENT '订单状态：1:待付款；2：待发货；3：配送中；4：已签收；5：已取消；6：卡券已发送；7：确认到账中；8：退货退款；9：到期退单已退单；10：随时退单已退单；11:待成团；默认：0；';");
        echo $sql3.'这是第3个sql<br/>';
        $sql1 = M()->execute("ALTER TABLE `tp_mall_order_info` CHANGE `isgroupgoods` `groupgoodsid` TINYINT(2) DEFAULT 2 NOT NULL COMMENT '拼团信息的id';");
        echo $sql1.'这是第4个sql<br/>';
        $sql1 = M()->execute("ALTER TABLE `tp_mall_order_info` CHANGE `groupgoodsid` `groupgoodsid` VARCHAR(30) DEFAULT '0' NOT NULL COMMENT '拼团信息的id'; ");
        echo $sql1.'这是第5个sql<br/>';
        $sql1 = M()->execute(" ALTER TABLE `tp_mall_order_info` CHANGE `groupgoodsid` `groupinfoid` VARCHAR(30) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '0' NOT NULL COMMENT '拼团信息的id';");
        echo $sql1.'这是第6个sql<br/>';
        $sql1 = M()->execute("ALTER TABLE `tp_use_vouchers` ADD COLUMN `singleprice` DECIMAL(12,2) DEFAULT 0.00 NOT NULL COMMENT '计次卡核销的单次价格' AFTER `type`; ");
        echo $sql1.'这是第7个sql<br/>';
        $sql1 = M()->execute("ALTER TABLE `tp_member_vouchers` CHANGE `isused` `isused` TINYINT(1) DEFAULT 0 NOT NULL COMMENT '是否使用：1：是；2：否；3：冻结；' AFTER `prepaidcardpassword`, CHANGE `createtime` `createtime` INT(10) DEFAULT 0 NOT NULL COMMENT '创建时间' AFTER `handlershopname`, CHANGE `updatetime` `updatetime` INT(10) DEFAULT 0 NOT NULL COMMENT '更新时间' AFTER `createtime`;");
        echo $sql1.'这是第8个sql<br/>';
        $sql1 = M()->execute("ALTER TABLE `tp_member_vouchers` ADD COLUMN `originalprice` DECIMAL(12,2) DEFAULT 0.00 NULL COMMENT '原价' AFTER `handlershopid`, ADD COLUMN `saleprice` DECIMAL(12,2) DEFAULT 0.00 NULL COMMENT '售价' AFTER `originalprice`;");
        echo $sql1.'这是第9个sql<br/>';
        $sql1 = M()->execute("CREATE TABLE `tp_log_meter_card_import_task` (
  `id` varchar(30) DEFAULT NULL COMMENT '主键ID',
  `cid` int(10) DEFAULT '0' COMMENT '公司ID',
  `mid` varchar(30) DEFAULT '0' COMMENT '会员MID',
  `log` longtext COMMENT '日志描述',
  `createtime` int(11) DEFAULT '0' COMMENT '创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8");
        echo $sql1.'这是第10个sql<br/>';
        $sql1 = M()->execute("CREATE TABLE `tp_meter_card_import_task` (
  `id` varchar(30) NOT NULL COMMENT '主键(系统自动生成)',
  `adduid` int(11) DEFAULT '0' COMMENT '添加uid',
  `edituid` int(11) DEFAULT '0' COMMENT '修改uid',
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  `shopid` int(11) DEFAULT '0' COMMENT '门店id',
  `allnum` int(10) NOT NULL DEFAULT '0' COMMENT '导入时统计的总条数',
  `sucnum` int(10) NOT NULL DEFAULT '0' COMMENT '导入成功条数',
  `fainum` int(10) NOT NULL DEFAULT '0' COMMENT '导入失败条数',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '记录状态  1:未开始  2:导入中；3:导入成功；4:导入失败 默认为:1',
  `tstime` int(10) NOT NULL DEFAULT '0' COMMENT '开始时间',
  `tetime` int(10) NOT NULL DEFAULT '0' COMMENT '结束时间',
  `filename` varchar(400) NOT NULL DEFAULT '' COMMENT '文件名称',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8");
        echo $sql1.'这是第11个sql<br/>';
        $sql1 = M()->execute("insert  into `tp_system_scrm5_permissions_list_new`(`id`,`name`,`desc`,`parentid`,`sort`,`ismenu`,`isshow`,`classname`,`wabpagelink`,`updatetime`,`createtime`) values (95,'快捷赠券活动','',59,11,2,1,'','',1484313086,1484277451);");
        echo $sql1.'这是第12个sql<br/>';
        echo '######################### 快捷赠券 SQL 结束 #######################################<br/>';
    }
    /**
     * 
     * 手机点单第二次修改
     * 
     * @author Leo<1251868177@qq.com>
     * @since  2017-1-22
     */
    public function MobilePhoneOrder2SQL(){
        echo '######################### 手机点单2 SQL 开始 #######################################<br/>';
        $sql1 = M()->execute("ALTER TABLE `tp_mobilephoneorder_order` ADD COLUMN `usernote` VARCHAR(1000) DEFAULT '' NOT NULL COMMENT '会员备注' AFTER `updatetime`;");
        echo $sql1.'这是第1个sql<br/>';
        $sql2 = M()->execute("ALTER TABLE `tp_mobilephoneorder_order` CHANGE `usernote` `usernote` VARCHAR(1000) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL COMMENT '会员备注' AFTER `adminname`, CHANGE `adminnote` `adminnote` VARCHAR(1000) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL COMMENT '管理员备注' AFTER `usernote`;");
        echo $sql2.'这是第2个sql<br/>';
        echo '######################### 手机点单2 SQL 结束 #######################################<br/>';
    }
    /**
     * 
     * 优惠口令Sql
     * 
     * @author Leo<1251868177@qq.com>
     * @since  2017-2-10
     */
    public function DMSSql(){
        echo '######################### DMS SQL 开始 #######################################<br/>';
        $sql1 = M()->execute("ALTER TABLE `tp_dms_discoukey` ADD COLUMN `totalbonus` DECIMAL(12,2) DEFAULT 0.00 NOT NULL COMMENT '累计奖金' AFTER `logintruepwd`;");
        echo $sql1.'这是第1个sql<br/>';
        $sql2 = M()->execute("ALTER TABLE `tp_dms_bill` CHANGE `billtype` `billtype` TINYINT(1) DEFAULT 0 NOT NULL COMMENT '账单类型 1:佣金收入；2:申请提现；3:提现成功；4:提现取消；5:上月结算；6:奖金；默认:0';");
        echo $sql2.'这是第2个sql<br/>';
        $sql3 = M()->execute("ALTER TABLE `tp_dms_bill` ADD COLUMN `userid` VARCHAR(30) DEFAULT '' NOT NULL COMMENT '操作人id' AFTER `mid`, ADD COLUMN `username` VARCHAR(200) DEFAULT '' NOT NULL COMMENT '操作人姓名' AFTER `userid`, ADD COLUMN `note` VARCHAR(2000) DEFAULT '' NOT NULL COMMENT '备注' AFTER `username`;");
        echo $sql3.'这是第3个sql<br/>';
        echo '######################### DMS SQL 结束 #######################################<br/>';
    }
    /**
     * 
     * 风外卖优化Sql
     * 
     * @author Leo<1251868177@qq.com>
     * @since  2017-2-12
     */
    public function TakeOut3Sql(){
        echo '######################### 风外卖 SQL 开始 #######################################<br/>';
        $sql1 = M()->execute("ALTER TABLE `tp_takeout_menu` ADD COLUMN `actid` VARCHAR(30) DEFAULT '' NOT NULL COMMENT '活动id' AFTER `ispromotion`, ADD COLUMN `actstock` INT(5) DEFAULT 0 NOT NULL COMMENT '活动库存' AFTER `actid`, CHANGE `createtime` `createtime` INT(11) DEFAULT 0 NOT NULL COMMENT '创建时间' AFTER `actstock`, CHANGE `updatetime` `updatetime` INT(11) DEFAULT 0 NOT NULL COMMENT '更新时间' AFTER `createtime`;");
        echo $sql1.'这是第1个sql<br/>';
        $sql2 = M()->execute("ALTER TABLE `tp_takeout_menu` ADD COLUMN `actstarttime` INT(11) DEFAULT 0 NOT NULL COMMENT '活动开始时间' AFTER `actid`, ADD COLUMN `actendtime` INT(11) DEFAULT 0 NOT NULL COMMENT '活动结束时间' AFTER `actstarttime`;");
        echo $sql2.'这是第2个sql<br/>';
        $sql3 = M()->execute("ALTER TABLE `tp_takeout_menu` ADD COLUMN `now` DECIMAL(12,2) DEFAULT 0.00 NOT NULL COMMENT '优惠菜品活动优惠后价格' AFTER `actendtime`;");
        echo $sql3.'这是第3个sql<br/>';
        $sql4 = M()->execute("ALTER TABLE `tp_takeout_order_menu` ADD COLUMN `ispromotion` TINYINT(1) DEFAULT 2 NOT NULL COMMENT '是否为优惠活动菜品  1是; 2:否;  默认:2' AFTER `total`, ADD COLUMN `actid` VARCHAR(30) DEFAULT '' NOT NULL COMMENT '活动id' AFTER `ispromotion`, ADD COLUMN `actnum` INT(5) DEFAULT 0 NOT NULL COMMENT '优惠数量(参加优惠的菜品数量)' AFTER `actid`, ADD COLUMN `actprice` DECIMAL(12,2) DEFAULT 0.00 NOT NULL COMMENT '优惠单价' AFTER `actnum`, ADD COLUMN `acttotal` DECIMAL(12,2) DEFAULT 0.00 NOT NULL COMMENT '优惠总价' AFTER `actprice`;");
        echo $sql4.'这是第4个sql<br/>';
        $sql5 = M()->execute("ALTER TABLE `tp_takeout_order_menu` ADD COLUMN `isshare` TINYINT(1) DEFAULT 0 NOT NULL COMMENT '是否与其他活动共享 1:是; 2:否;' AFTER `acttotal`;");
        echo $sql5.'这是第5个sql<br/>';
        $sql6 = M()->execute("ALTER TABLE `tp_takeout_order` ADD COLUMN `canordermoney` DECIMAL(12,2) DEFAULT 0.00 NOT NULL COMMENT '可使用订单优惠的金额' AFTER `discountutility`, ADD COLUMN `ordermoney` DECIMAL(12,2) DEFAULT 0.00 NOT NULL COMMENT '订单活动优惠金额' AFTER `canordermoney`, ADD COLUMN `menumoney` DECIMAL(12,2) DEFAULT 0.00 NOT NULL COMMENT '菜品活动优惠金额' AFTER `ordermoney`;");
        echo $sql6.'这是第6个sql<br/>';
        $sql7 = M()->execute("ALTER TABLE `tp_takeout_order` CHANGE `canordermoney` `canordermoney` DECIMAL(12,2) DEFAULT 0.00 NOT NULL COMMENT '可使用订单优惠的金额' AFTER `discountprice`, CHANGE `todoordiscount` `todoordiscount` DECIMAL(12,2) DEFAULT 0.00 NOT NULL COMMENT '送餐上门优惠金额(包括配送费)' AFTER `menumoney`, CHANGE `todoorprice` `todoorprice` DECIMAL(12,2) DEFAULT 0.00 NOT NULL COMMENT '送餐上门金额(包括配送费)' AFTER `discountutility`;");
        echo $sql7.'这是第7个sql<br/>';
        $sql8 = M()->execute("ALTER TABLE `tp_takeout_order` CHANGE `ordermoney` `ordermoney` DECIMAL(12,2) DEFAULT 0.00 NOT NULL COMMENT '订单活动优惠金额' AFTER `canordermoney`, CHANGE `pickupdiscount` `pickupdiscount` DECIMAL(12,2) DEFAULT 0.00 NOT NULL COMMENT '门店自提优惠金额(没有配送费)' AFTER `menumoney`, CHANGE `todoordiscount` `todoordiscount` DECIMAL(12,2) DEFAULT 0.00 NOT NULL COMMENT '送餐上门优惠金额(包括配送费)' AFTER `todoorprice`;");
        echo $sql8.'这是第8个sql<br/>';
        $sql9 = M()->execute("ALTER TABLE `tp_takeout_order` CHANGE `discountname` `discountname` VARCHAR(100) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL COMMENT '订单优惠名称', CHANGE `discounttype` `discounttype` TINYINT(1) DEFAULT 0 NOT NULL COMMENT '订单优惠类型  1:无优惠或未开启优惠; 2:立减优惠 3:立折优惠 4:满减优惠 5:满折优惠 6:满免配送费 默认:1';");
        echo $sql9.'这是第9个sql<br/>';
        $sql10 = M()->execute("ALTER TABLE `tp_takeout_order` CHANGE `canordermoney` `notcanmenuactmoney` DECIMAL(12,2) DEFAULT 0.00 NOT NULL COMMENT '不能参与其他优惠活动的菜品优惠金额';");
        echo $sql10.'这是第10个sql<br/>';
        $sql11 = M()->execute("CREATE TABLE `tp_takeout_menu_activity` ( `id` varchar(30) NOT NULL COMMENT '主键(系统自动生成)', `adduid` int(11) NOT NULL DEFAULT '0' COMMENT '添加uid', `edituid` int(11) NOT NULL DEFAULT '0' COMMENT '编辑uid', `shopids` int(11) NOT NULL DEFAULT '0' COMMENT '门店id', `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id', `shopid` varchar(30) NOT NULL DEFAULT '' COMMENT '门店id', `setid` varchar(30) NOT NULL DEFAULT '' COMMENT '配置id【tp_takeout_setup】', `name` varchar(200) NOT NULL DEFAULT '' COMMENT '优惠活动名称', `starttime` int(11) NOT NULL DEFAULT '0' COMMENT '活动开始时间', `endtime` int(11) NOT NULL DEFAULT '0' COMMENT '活动结束时间', `number` int(10) NOT NULL DEFAULT '0' COMMENT '参与活动菜品数量', `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '优惠类型 1:特价; 2:折扣;', `price` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '特价->元', `discount` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '折扣->折', `islimit` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否限购 1:不限购; 2:限购;', `limitnumber` int(10) NOT NULL DEFAULT '0' COMMENT '限购->件', `isshare` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否与其他活动共享  1:不共享;  2:共享;', `state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '活动状态  1:未开始; 2:进行中; 3:暂停; 4:已结束;', `menuids` varchar(2000) NOT NULL DEFAULT '' COMMENT '已选菜品', `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间', `updatetime` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间', PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8");
        echo $sql11.'这是第11个sql<br/>';
        $sql12 = M()->execute("CREATE TABLE `tp_takeout_menu_activity_menudetails` ( `id` varchar(30) NOT NULL COMMENT '主键(系统自动生成)', `adduid` int(11) NOT NULL DEFAULT '0' COMMENT '添加uid', `edituid` int(11) NOT NULL DEFAULT '0' COMMENT '编辑uid', `shopids` int(11) NOT NULL DEFAULT '0' COMMENT '门店id', `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id', `shopid` varchar(30) NOT NULL DEFAULT '' COMMENT '门店id', `setid` varchar(30) NOT NULL DEFAULT '' COMMENT '配置id', `actid` varchar(30) NOT NULL DEFAULT '' COMMENT '活动id', `menuid` varchar(30) NOT NULL DEFAULT '' COMMENT '菜品id', `skuid` varchar(30) NOT NULL DEFAULT '' COMMENT 'SKUid', `actname` varchar(200) NOT NULL DEFAULT '' COMMENT '活动名称', `menuname` varchar(200) NOT NULL DEFAULT '' COMMENT '菜品名称', `skuname` varchar(200) NOT NULL DEFAULT '' COMMENT 'SKU名称', `orig` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '(原)价格', `now` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '(优惠后)价格', `stock` int(11) NOT NULL DEFAULT '0' COMMENT '库存', `surplus` int(11) NOT NULL DEFAULT '0' COMMENT '剩余库存', `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间', `updatetime` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',  PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8");
        echo $sql12.'这是第12个sql<br/>';
        // $sql13 = M()->execute("CREATE TABLE `tp_takeout_print` (`id` varchar(30) NOT NULL COMMENT '主键(系统自动生成)', `adduid` int(11) NOT NULL DEFAULT '0' COMMENT '添加uid', `edituid` int(11) NOT NULL DEFAULT '0' COMMENT '编辑uid', `shopsid` int(11) NOT NULL DEFAULT '0' COMMENT '门店ids', `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id', `shopid` varchar(30) NOT NULL DEFAULT '' COMMENT '门店id', `setid` varchar(30) NOT NULL DEFAULT '' COMMENT '外卖设置id', `printid` varchar(50) NOT NULL DEFAULT '' COMMENT '小票机id', `printkey` varchar(50) NOT NULL DEFAULT '' COMMENT '小票机key', `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间', `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '编辑时间',  PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8");
        // echo $sql13.'这是第13个sql<br/>';
        echo '######################### 风外卖 SQL 结束 #######################################<br/>';
    }
    /**
     * 
     * 风外卖优化
     * 
     * @author Leo<1251868177@qq.com>
     * @since  2017-2-18
     */
   public function TakeOut4Sql(){
       echo '######################### 风外卖 SQL 开始 #######################################<br/>';
       $sql1 = M()->execute("ALTER TABLE `tp_takeout_setup` ADD COLUMN `isbook` TINYINT(1) DEFAULT 2 NOT NULL COMMENT '是否可预约  1:是(可预约); 2:否(不可预约); 默认:2' AFTER `paytype`, ADD COLUMN `bookday` INT(5) DEFAULT 0 NOT NULL COMMENT '预约天数' AFTER `isbook`;");
       echo $sql1.'这是第1个sql<br/>';
       $sql2 = M()->execute("ALTER TABLE `tp_takeout_order` ADD COLUMN `isbook` TINYINT(1) DEFAULT 2 NOT NULL COMMENT '是否可预约 1:可预约; 2:不可预约' AFTER `address`, CHANGE `picktime` `picktime` VARCHAR(50) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL COMMENT '取餐时间';");
       echo $sql2.'这是第2个sql<br/>';
       $sql3 = M()->execute("ALTER TABLE `tp_takeout_setup` ADD COLUMN `isorder` TINYINT(1) DEFAULT 2 NOT NULL COMMENT '是否自动接单 1:是; 2:否; 默认:2' AFTER `pendingorder`;");
       echo $sql3.'这是第3个sql<br/>';
       echo '######################### 风外卖 SQL 结束 #######################################<br/>';
   }
   /**
    * 
    * 闪惠优化
    * 
    * @author Leo<1251868177@qq.com>
    * @since  2017-2-21
    */
   public function ShanHui2Sql(){
       echo '######################### 闪惠 SQL 开始 #######################################<br/>';
       $sql1 = M()->execute("ALTER TABLE `tp_shanhui_setup` ADD COLUMN `totalprice` DECIMAL(12,2) DEFAULT 0.00 NOT NULL COMMENT '累计收款金额(实收金额)' AFTER `totalnum`, ADD COLUMN `todayprice` DECIMAL(12,2) DEFAULT 0.00 NOT NULL COMMENT '今天收款金额' AFTER `totalprice`, ADD COLUMN `yesterdayprice` DECIMAL(12,2) DEFAULT 0.00 NOT NULL COMMENT '昨天收款金额' AFTER `todayprice`, ADD COLUMN `beforeyesterdayprice` DECIMAL(12,2) DEFAULT 0.00 NOT NULL COMMENT '前天收款金额' AFTER `yesterdayprice`;");
       echo $sql1.'这是第1个sql<br/>';
       $sql2 = M()->execute("ALTER TABLE `tp_shanhui_order` ADD COLUMN `orderstate` VARCHAR(1) DEFAULT '1' NOT NULL COMMENT '订单状态 1:正常; 2:已退款;' AFTER `note`;");
       echo $sql2.'这是第2个sql<br/>';
       $sql3 = M()->execute("ALTER TABLE `tp_shanhui_order` ADD COLUMN `refundtime` INT(11) DEFAULT 0 NOT NULL COMMENT '申请退款时间' AFTER `orderstate`;");
       echo $sql3.'这是第3个sql<br/>';
       $sql4 = M()->execute("ALTER TABLE `tp_shanhui_order` CHANGE `orderstate` `orderstate` VARCHAR(1) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '1' NOT NULL COMMENT '订单状态 1:正常; 2:申请退款中; 3:已退款;';");
       echo $sql4.'这是第4个sql<br/>';
       echo '######################### 闪惠 SQL 结束 #######################################<br/>';
   }
   /**
    * 
    * 风外卖
    * 
    * @author Leo<1251868177@qq.com>
    * @since  2017-2-24
    */
   public function TakeOut5Sql(){
       echo '######################### 风外卖 SQL 开始 #######################################<br/>';
       $sql1 = M()->execute("ALTER TABLE `tp_takeout_setup` ADD COLUMN `themestyle` TINYINT(1) DEFAULT 1 NOT NULL COMMENT '界面样式 1:商城样式 2:经典样式  默认:1' AFTER `shopid`;");
       echo $sql1.'这是第1个sql<br/>';
       echo '######################### 风外卖 SQL 结束 #######################################<br/>';
   }
   /**
    * 
    * 风外卖
    * 
    * @author Leo<1251868177@qq.com>
    * @since  2017-2-27
    */
   public function TakeOut6Sql(){
       echo '######################### 风外卖 SQL 开始 #######################################<br/>';
       $sql1 = M()->execute("ALTER TABLE `tp_takeout_order` CHANGE `orderstate` `orderstate` TINYINT(1) DEFAULT 0 NOT NULL COMMENT '订单状态：1:未提交; 2:已提交(未支付); 3:已支付(待确认); 4:已确认(待签收); 5:已签收; 6:已取消(已退款); 7:已关闭; 8:退款中; 默认:1', ADD COLUMN `applytime` INT(11) DEFAULT 0 NOT NULL COMMENT '申请退款时间' AFTER `signtime`;");
       echo $sql1.'这是第1个sql<br/>';
       $sql2 = M()->execute("ALTER TABLE `tp_takeout_order` CHANGE `orderstate` `orderstate` TINYINT(1) DEFAULT 0 NOT NULL COMMENT '订单状态：1:未提交; 2:已提交(未支付); 3:已支付(待确认); 4:已确认(待签收); 5:已签收; 6:已取消(已退款); 7:已关闭; 8:退款中; 9:退款失败; 默认:1', ADD COLUMN `applynum` TINYINT(1) DEFAULT 0 NOT NULL COMMENT '申请退款次数' AFTER `closetime`;");
       echo $sql2.'这是第2个sql<br/>';
       $sql3 = M()->execute("ALTER TABLE `tp_takeout_order` CHANGE `applynum` `applynum` TINYINT(1) DEFAULT 0 NOT NULL COMMENT '申请退款次数(微信退款使用,暂定最多7次)';");
       echo $sql3.'这是第3个sql<br/>';
       echo '######################### 风外卖 SQL 结束 #######################################<br/>';
   }
   /**
    * 展业伙伴
    * @author 徐建鹏<tomas@mobiwind.cn>
    * @since  2017-3-2
    */
   public function exhibitionPartnerSql(){
      	echo '######################### 展业伙伴  SQL 开始 #######################################<br/>';
      	$sql1 = M()->execute("CREATE TABLE `tp_mall_exhibition_partner_base` (
  `id` varchar(30) NOT NULL DEFAULT '' COMMENT '主键ID（系统随机生成）',
  `adduid` int(11) DEFAULT '0' COMMENT '添加用户id',
  `edituid` int(11) DEFAULT '0' COMMENT '编辑用户id',
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  `shopsid` int(11) DEFAULT '0' COMMENT '分店id',
  `recruitisopen` tinyint(2) NOT NULL DEFAULT '2' COMMENT '展业伙伴招募是否开启：1：开启；2：不开启；默认：2；',
  `applycondition` tinyint(2) NOT NULL DEFAULT '2' COMMENT '申请条件：1：完成首单；2：无；默认：2；',
  `isexamine` tinyint(2) NOT NULL DEFAULT '2' COMMENT '展业伙伴是否审核：1：审核；2：不审核；默认：2；',
  `settlementmethod` tinyint(2) NOT NULL DEFAULT '2' COMMENT '结算方式：1：系统自动结算；2：人工结算；默认：2',
  `commissionrate` tinyint(2) NOT NULL DEFAULT '0' COMMENT '佣金比例',
  `recruitplan` longtext COMMENT '招募计划',
  `invitationposter` varchar(400) NOT NULL DEFAULT '' COMMENT '邀请海报',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='展业伙伴 - 基础设置';");
      	echo $sql1.'这是第1个sql<br/>';
   
      	$sql2 = M()->execute("CREATE TABLE `tp_mall_exhibition_partner_bill` (
  `id` varchar(30) NOT NULL DEFAULT '0' COMMENT '主键ID(系统随机生成)',
  `adduid` int(11) NOT NULL DEFAULT '0' COMMENT '添加uid',
  `edituid` int(11) NOT NULL DEFAULT '0' COMMENT '修改uid',
  `shopsid` int(11) NOT NULL DEFAULT '0' COMMENT '门店id',
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  `mid` int(11) NOT NULL DEFAULT '0' COMMENT 'mid',
  `orderid` varchar(30) NOT NULL DEFAULT '0' COMMENT '关联订单号(tp_mall_exhibition_partner_order:id)',
  `wcashid` varchar(30) NOT NULL DEFAULT '0' COMMENT '提现申请id(tp_mall_exhibition_partner_withdrawcash:id)',
  `billtype` tinyint(1) NOT NULL DEFAULT '0' COMMENT '账单类型 1:佣金收入；2:申请提现；3:提现成功；4:提现失败；默认:0',
  `searchtime` int(11) NOT NULL DEFAULT '0' COMMENT '搜索时间',
  `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updatetime` int(1) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
      	echo $sql2.'这是第2个sql<br/>';
   
      	$sql3 = M()->execute("CREATE TABLE `tp_mall_exhibition_partner_member_code` (
  `id` varchar(30) NOT NULL COMMENT '会员生成邀请赠礼二维码的ID',
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司ID',
  `mid` int(11) NOT NULL DEFAULT '0' COMMENT '会员',
  `content` varchar(600) NOT NULL DEFAULT '' COMMENT '二维码类型',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
      	echo $sql3.'这是第3个sql<br/>';
   
      	$sql4 = M()->execute("CREATE TABLE `tp_mall_exhibition_partner_list` (
  `id` varchar(30) NOT NULL DEFAULT '' COMMENT '主键ID（系统随机生成）',
  `adduid` int(11) DEFAULT '0' COMMENT '添加用户id',
  `edituid` int(11) DEFAULT '0' COMMENT '编辑用户id',
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  `shopsid` int(11) DEFAULT '0' COMMENT '分店id',
  `mid` int(11) NOT NULL DEFAULT '0' COMMENT '展业伙伴mid',
  `totalorder` int(11) NOT NULL DEFAULT '0' COMMENT '累计推广订单数',
  `totalcustomer` int(11) NOT NULL DEFAULT '0' COMMENT '累计推广客户数',
  `totalmoney` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '累计佣金',
  `availablemoney` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '可提现佣金',
  `totalreward` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '累计奖金',
  `isclear` tinyint(2) NOT NULL DEFAULT '2' COMMENT '是否清退（清退的展业伙伴不再计算佣金）：1：是；2：否；默认：2；',
  `alipayaccount` varchar(30) NOT NULL DEFAULT '' COMMENT '支付宝账号',
  `bankaccount` varchar(30) NOT NULL DEFAULT '' COMMENT '银行卡账号',
  `bankholder` varchar(30) NOT NULL DEFAULT '' COMMENT '银行卡开户人',
  `bankbranch` varchar(300) NOT NULL DEFAULT '' COMMENT '银行卡开户行以及支行',
  `lastcashmethod` tinyint(2) NOT NULL DEFAULT '0' COMMENT '上次提现方式：1：支付宝；2：银行卡；默认：0；',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '是否通过审核：1：待审核；2：已审核；3：已拒绝；默认：1；',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='展业伙伴 - 主表';");
      	echo $sql4.'这是第4个sql<br/>';
   
      	$sql5 = M()->execute("CREATE TABLE `tp_mall_exhibition_partner_order` (
  `id` varchar(30) NOT NULL DEFAULT '' COMMENT '主键ID（系统随机生成）',
  `adduid` int(11) DEFAULT '0' COMMENT '添加用户id',
  `edituid` int(11) DEFAULT '0' COMMENT '编辑用户id',
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  `shopsid` int(11) DEFAULT '0' COMMENT '分店id',
  `mid` int(11) NOT NULL DEFAULT '0' COMMENT '展业伙伴mid',
  `buymid` int(11) NOT NULL DEFAULT '0' COMMENT '购买人的mid',
  `orderid` varchar(30) NOT NULL DEFAULT '0' COMMENT '订单id',
  `orderprice` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '成交金额',
  `commission` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '佣金',
  `status` tinyint(2) NOT NULL DEFAULT '2' COMMENT '结算状态：1：已结算；2：未结算；默认：2；',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='展业伙伴 - 业绩统计订单';");
      	echo $sql5.'这是第5个sql<br/>';
   
      	$sql6 = M()->execute("insert  into `tp_system_scrm5_permissions_list_new`(`id`,`name`,`desc`,`parentid`,`sort`,`ismenu`,`isshow`,`classname`,`wabpagelink`,`updatetime`,`createtime`) values (111,'展业伙伴','',3,11,1,1,'','',1487651203,1487651090),(112,'基础设置','',111,1,1,1,'','/index.php?g=User5&amp;m=MallExhibitionPartner&amp;a=index',1487651421,1487651366),(113,'展业伙伴管理','',111,2,1,1,'','/index.php?g=User5&amp;m=MallExhibitionPartner&amp;a=partnerList',1487663825,1487651479),(114,'业绩统计','',111,3,1,1,'','/index.php?g=User5&amp;m=MallExhibitionPartner&amp;a=achievement',1487739292,1487651802),(115,'提现申请管理','',111,4,1,1,'','/index.php?g=User5&amp;m=MallExhibitionPartner&amp;a=withDrawCash',1487739355,1487651836);");
      	echo $sql6.'这是第6个sql<br/>';
   
      	$sql7 = M()->execute("CREATE TABLE `tp_mall_exhibition_partner_reward` (
  `id` varchar(30) NOT NULL DEFAULT '' COMMENT '主键ID（系统随机生成）',
  `adduid` int(11) DEFAULT '0' COMMENT '添加用户id',
  `edituid` int(11) DEFAULT '0' COMMENT '编辑用户id',
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  `shopsid` int(11) DEFAULT '0' COMMENT '分店id',
  `mid` int(11) NOT NULL DEFAULT '0' COMMENT '展业伙伴mid',
  `listid` varchar(30) NOT NULL DEFAULT '' COMMENT '展业伙伴id',
  `reward` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '奖金',
  `remark` varchar(500) NOT NULL DEFAULT '' COMMENT '备注',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='展业伙伴 - 奖金记录';");
      	echo $sql7.'这是第7个sql<br/>';
   
      	$sql8 = M()->execute("CREATE TABLE `tp_mall_exhibition_partner_withdrawcash` (
  `id` varchar(30) NOT NULL DEFAULT '' COMMENT '主键ID（系统随机生成）',
  `adduid` int(11) DEFAULT '0' COMMENT '添加用户id',
  `edituid` int(11) DEFAULT '0' COMMENT '编辑用户id',
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  `shopsid` int(11) DEFAULT '0' COMMENT '分店id',
  `mid` int(11) NOT NULL DEFAULT '0' COMMENT '展业伙伴mid',
  `withdrawcash` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '提现金额',
  `accounttype` tinyint(2) NOT NULL DEFAULT '0' COMMENT '提现账户类型：1：支付宝；2：银行卡；默认：0；',
  `pic1` varchar(400) NOT NULL DEFAULT '' COMMENT '凭证图片1',
  `pic2` varchar(400) NOT NULL DEFAULT '' COMMENT '凭证图片2',
  `pic3` varchar(400) NOT NULL DEFAULT '' COMMENT '凭证图片3',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '提现申请状态：1：待结算；2：已结算；3：拒绝申请；4：已取消；默认：1；',
  `remark` varchar(400) NOT NULL DEFAULT '' COMMENT '管理员备注',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='展业伙伴 - 提现申请管理';");
      	echo $sql8.'这是第8个sql<br/>';
   
      	$sql9 = M()->execute("insert  into `tp_wechat_event_type`(`id`,`fid`,`typename`,`triggering`,`page`,`url`,`data`,`isshow`,`type`,`tplid`) values (49,12,'任务处理通知','申请成为展业伙伴成功','展业伙伴主页','/index.php?g=Wap&m=MallExhibitionPartner&a=index&companyid=','{\"first\":\"\\u606d\\u559c\\uff0c\\u7533\\u8bf7\\u6210\\u4e3a\\u5c55\\u4e1a\\u4f19\\u4f34\\u6210\\u529f\",\"content\":[[\"\\u4efb\\u52a1\\u540d\\u79f0\",\"\",\"\"],[\"\\u901a\\u77e5\\u7c7b\\u578b\",\"\",\"\"]],\"end\":\"\\u5feb\\u53bb\\u5411\\u60a8\\u7684\\u597d\\u53cb\\u63a8\\u8350\\u6211\\u4eec\\u5427\\uff01\"}',1,1,''),(50,12,'任务处理通知','申请成为展业伙伴失败','展业伙伴主页','/index.php?g=Wap&m=MallExhibitionPartner&a=index&companyid=','{\"first\":\"\\u62b1\\u6b49\\uff0c\\u7533\\u8bf7\\u6210\\u4e3a\\u5c55\\u4e1a\\u4f19\\u4f34\\u5931\\u8d25\",\"content\":[[\"\\u4efb\\u52a1\\u540d\\u79f0\",\"\",\"\"],[\"\\u901a\\u77e5\\u7c7b\\u578b\",\"\",\"\"]],\"end\":\"\\u8bf7\\u4ed4\\u7ec6\\u9605\\u8bfb\\u62db\\u52df\\u8bf4\\u660e\\uff0c\\u7b26\\u5408\\u62db\\u52df\\u6761\\u4ef6\\u518d\\u6765\\u7533\\u8bf7\\u5427\"}',1,1,'');");
      	echo $sql9.'这是第9个sql<br/>';
   
      	$sql10 = M()->execute(" ALTER TABLE `tp_member_wechat_info` ADD COLUMN `zpartnermid` INT(11) DEFAULT 0 NOT NULL COMMENT '展业伙伴通过邀请海报绑定推荐人的mid' AFTER `isregister`; ");
      	echo $sql10.'这是第10个sql<br/>';
   
      	$sql11 = M()->execute(" ALTER TABLE `tp_mall_order_info` ADD COLUMN `iszorder` TINYINT(2) DEFAULT 2 NOT NULL COMMENT '是否为展业订单：1：是；2：否；默认：2；' AFTER `groupinfoid`; ");
      	echo $sql11.'这是第11个sql<br/>';
   
      	$sql12 = M()->execute("ALTER TABLE `tp_mall_order_info` CHANGE `iszorder` `iszorder` TINYINT(2) DEFAULT 2 NOT NULL COMMENT '是否为展业订单：1：是；2：否；3:展业订单已付款成功；默认：2；';  ");
      	echo $sql12.'这是第11个sql<br/>';
   
      	$sql13 = M()->execute(" ALTER TABLE `tp_mall_order_info` CHANGE `iszorder` `iszorder` TINYINT(2) DEFAULT 2 NOT NULL COMMENT '是否为展业订单：1：是（为结算的展业订单）；2：否；（非展业订单）3:已结算的展业订单成功；4：可提现的展业订单默认：2；'; ");
      	echo $sql13.'这是第13个sql<br/>';
   
      	$sql14 = M()->execute("ALTER TABLE `tp_mall_order_info` CHANGE `iszorder` `iszorder` TINYINT(2) DEFAULT 2 NOT NULL COMMENT '是否为展业订单：1：是；2：否；默认：2；', ADD COLUMN `zorderstatus` TINYINT(2) DEFAULT 0 NOT NULL COMMENT '展业订单结算状态：1：未结算；2：已结算；3：可提现；默认：0；' AFTER `iszorder`;  ");
      	echo $sql14.'这是第14个sql<br/>';
   
      	$sql15 = M()->execute(" INSERT INTO `tp_wechat_event` (`id`, `name`, `isshow`) VALUES ('12', '展业伙伴', '1'); ");
      	echo $sql15.'这是第15个sql<br/>';
   
      	$sql16 = M()->execute(" ALTER TABLE `tp_mall_exhibition_partner_bill` ADD COLUMN `wages` DECIMAL(12,2) DEFAULT 0.00 NOT NULL COMMENT '佣金收入' AFTER `mid`, ADD COLUMN `withdrawcash` DECIMAL(12,2) DEFAULT 0.00 NOT NULL COMMENT '提现金额' AFTER `wages`;  ");
      	echo $sql16.'这是第16个sql<br/>';
   
      	echo '######################### 展业伙伴 SQL 结束 #######################################<br/>';
   }
   /**
    * 
    * 风外卖 2017/03/03
    * 
    * @author Leo<1251868177@qq.com>
    * @since  2017-3-3
    */
   public function TakeOut7Sql(){
       echo '######################### 风外卖 SQL 开始 #######################################<br/>';
       $sql1 = M()->execute("ALTER TABLE `tp_takeout_menu` ADD COLUMN `desc` TEXT NULL COMMENT '菜品详情' AFTER `isupshelf`;");
       echo $sql1.'这是第1个sql<br/>';
       echo '######################### 风外卖 SQL 结束 #######################################<br/>';
   }
   /**
    * 
    * 风外卖 2017/03/06
    * 
    * @author Leo<1251868177@qq.com>
    * @since  2017-3-6
    */
   public function TakeOut8Sql(){
       echo '######################### 风外卖 SQL 开始 #######################################<br/>';
       $sql1 = M()->execute("ALTER TABLE `tp_company` ADD COLUMN `takeoutisopen` TINYINT(1) DEFAULT 2 NOT NULL COMMENT '风外卖新订单提醒是否开启 1:是; 2:否; 默认:2' AFTER `membercustomfield`, ADD COLUMN `eshopisopen` TINYINT(1) DEFAULT 2 NOT NULL COMMENT 'Ecshop新订单提醒是否开启 1:是; 2:否; 默认:2' AFTER `takeoutisopen`, ADD COLUMN `commonbookisopen` TINYINT(1) DEFAULT 2 NOT NULL COMMENT '预定新订单提醒是否开启 1:是; 2:否; 默认:2' AFTER `eshopisopen`;");
       echo $sql1.'这是第1个sql<br/>';
       echo '######################### 风外卖 SQL 结束 #######################################<br/>';
   }
   /**
    * 
    * 闪惠 2017/03/07
    * 
    * @author Leo<1251868177@qq.com>
    * @since  2017-3-7
    */
   public function ShanHui3Sql(){
       echo '######################### 闪惠 SQL 开始 #######################################<br/>';
       $sql1 = M()->execute("INSERT INTO `tp_system_helper_permissions_list` (`id`, `name`, `parentid`, `sort`, `isshow`, `updatetime`, `createtime`) VALUES ('', '闪惠退款', '3', '1', '1', '1488856183', '1488856183');");
       echo $sql1.'这是第1个sql<br/>';
       echo '######################### 闪惠 SQL 结束 #######################################<br/>';
   }
   /**
    * 
    * 风外卖 2017/03/13
    * 
    * @author Leo<1251868177@qq.com>
    * @since  2017-3-13
    */
   public function TakeOut9Sql(){
       echo '######################### 风外卖 SQL 开始 #######################################<br/>';
       $sql1 = M()->execute("CREATE TABLE `tp_takeout_remark` ( `id` varchar(30) NOT NULL COMMENT '主键(系统自动生成)', `adduid` int(11) NOT NULL DEFAULT '0' COMMENT '添加uid', `edituid` int(11) NOT NULL DEFAULT '0' COMMENT '编辑uid', `shopids` int(11) NOT NULL DEFAULT '0' COMMENT '门店id', `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id', `shopid` varchar(30) NOT NULL DEFAULT '' COMMENT '门店id', `setid` varchar(30) NOT NULL DEFAULT '' COMMENT '设置id', `name` varchar(100) NOT NULL DEFAULT '' COMMENT '备注标签/表单名称', `selenum` int(5) NOT NULL DEFAULT '0' COMMENT '被选中次数(标签)', `remarktype` tinyint(1) NOT NULL DEFAULT '0' COMMENT '备注类型 1:备注标签; 2:备注表单', `formtype` tinyint(1) NOT NULL DEFAULT '0' COMMENT '表单类型 1:单行文本; 2:多行文本', `prompt` varchar(100) NOT NULL DEFAULT '' COMMENT '提示文字(表单)', `isrequired` tinyint(1) NOT NULL DEFAULT '2' COMMENT '是否必填 1:是; 2:否  默认:2', `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间', `updatetime` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间', PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8 ");
       echo $sql1.'这是第1个sql<br/>';
       echo '######################### 风外卖 SQL 结束 #######################################<br/>';
   }
   /**
    * 
    * 风外卖 2017/03/18
    * 
    * @author Leo<1251868177@qq.com>
    * @since  2017-3-18
    */
   public function TakeOut10Sql(){
       echo '######################### 风外卖 SQL 开始 #######################################<br/>';
       $sql1 = M()->execute("ALTER TABLE `tp_takeout_menu` DROP COLUMN `acttype`, CHANGE `ispromotion` `ispromotion` TINYINT(1) DEFAULT 2 NOT NULL COMMENT '是否为活动菜品  1:是;  2:否;  默认:2', ADD COLUMN `acttype` TINYINT(1) DEFAULT 1 NOT NULL COMMENT '活动类型  1:特惠菜品活动; 2:秒杀活动  默认:1' AFTER `ispromotion`;");
       echo $sql1.'这是第1个sql<br/>';
       $sql2 = M()->execute("ALTER TABLE `tp_takeout_menu_sku` ADD COLUMN `buyprice` DECIMAL(12,2) DEFAULT 0.00 NOT NULL COMMENT '秒杀价' AFTER `pricesort`, ADD COLUMN `buystock` INT(5) DEFAULT 0 NOT NULL COMMENT '秒杀(活动)库存' AFTER `buyprice`;");
       echo $sql2.'这是第2个sql<br/>';
       $sql3 = M()->execute("DROP TABLE `tp_takeout_delivery_time`; ");
       echo $sql3.'这是第3个sql<br/>';
       $sql4 = M()->execute("DROP TABLE `tp_takeout_menu_activity_menudetails`;");
       echo $sql4.'这是第4个sql<br/>';
       $sql5 = M()->execute("ALTER TABLE `tp_takeout_order_menu` ADD COLUMN `acttype` TINYINT(1) DEFAULT 0 NOT NULL COMMENT '活动类型 1:菜品优惠活动; 2:秒杀活动;' AFTER `ispromotion`; ");
       echo $sql5.'这是第5个sql<br/>';
       $sql6 = M()->execute("ALTER TABLE `tp_takeout_order_menu` ADD COLUMN `actbacktotal` DECIMAL(12,2) DEFAULT 0.00 NOT NULL COMMENT '优惠后菜品总价(不包括餐盒费)' AFTER `acttotal`; ");
       echo $sql6.'这是第6个sql<br/>';
       
       $sql7 = M()->execute("CREATE TABLE `tp_takeout_menu_activity_timedbuy` (`id` varchar(30) NOT NULL COMMENT '主键(系统自动生成)', `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id', `shopid` varchar(30) NOT NULL DEFAULT '' COMMENT '门店id', `setid` varchar(30) NOT NULL DEFAULT '' COMMENT '设置id 关联表tp_takeout_setup ID', `menuid` varchar(30) NOT NULL DEFAULT '' COMMENT '菜品id', `menuname` varchar(50) NOT NULL DEFAULT '' COMMENT '菜品名称', `starttime` int(11) NOT NULL DEFAULT '0' COMMENT '活动开始时间', `endtime` int(11) NOT NULL DEFAULT '0' COMMENT '活动结束时间', `totalsales` int(10) NOT NULL DEFAULT '0' COMMENT '总销量', `islimit` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否限购 1:是; 2:否', `limitnumber` int(5) NOT NULL DEFAULT '0' COMMENT '限购 -> 件', `isshare` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否与其他活动共享  1:是; 2:否', `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间', `updatetime` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间', PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='风外卖-秒杀活动表'");
       echo $sql7.'这是第7个sql<br/>';
       
       echo '######################### 风外卖 SQL 结束 #######################################<br/>';
   }
   /**
    * 
    * 风外卖 2017/03/18
    * 
    * @author Leo<1251868177@qq.com>
    * @since  2017-3-20
    */
   public function TakeoutOut11Sql(){
       echo '######################### 风外卖 SQL 开始 #######################################<br/>';
       $sql1 = M()->execute("ALTER TABLE `tp_takeout_menu_sku` ADD COLUMN `totalbuystock` INT(5) DEFAULT 0 NOT NULL COMMENT '总秒杀(活动)库存' AFTER `buystock`;");
       echo $sql1.'这是第1个sql<br/>';
       
       echo '######################### 风外卖 SQL 结束 #######################################<br/>';
   }
}
?>