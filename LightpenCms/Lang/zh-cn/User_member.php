<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2012 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 公众号管理-会员管理

return array(
	'PTBusSetting'         =>  '商家设置', //----------商家设置----------//
	'PTBusName'         =>  '商家名称',
	'BusAddr'         =>  '商家详细地址',
	'BusAddrPrompt'         =>  '将会显示在会员卡背面，最多只能输入60个字符。',
	'CoverSlogan'         =>  '微时代会员卡，方便携带收藏，永不挂失',
	'ContInfo'         =>  '联系方式',
	'ContInfoPrompt'         =>  '会显示在会员卡背面，如13888888888或者021-2345678。',
	'LocationPrompt'         =>  '注意：这个只是模糊定位，准确位置请地图上标注!',
	'MemCardClaimLink'         =>  '会员卡领取地址',
	'PTMemCardSetting'         =>  '会员卡设置', //----------会员卡设置----------//
	'CardPictCover'         =>  '图文消息封面',
	'CardTitle'         =>  '会员卡名称',
	'CardTitleColor'         =>  '会员卡名称颜色',
	'CardBg'         =>  '会员卡的背景',
	'SelCardBg'         =>  '请选择会员卡背景图',
	'CardAvatar'         =>  '会员卡的图标',
	
	'DiyCardBg'         =>  '自己设计背景',
	'DiyCardBgPrompt'         =>  '建议图片尺寸：宽152像素*高70像素  （建议使用PNG格式透明图片）',
	'SelBg'         =>  '选择背景',
	'SelAvatar'         =>  '选择图标',
	'CardNumColor'         =>  '卡号文字颜色',
	'TextPrompt'         =>  '首页提示文字',
	'CardNumCode'         =>  '卡号英文编号',
	'CardNumCodePrompt1'         =>  '例：BSD-65535 BSD',
	'CardNumCodePrompt2'         =>  '就是英文编号',
	'CardNumStart'         =>  '起始卡号',
	'CardNumStartPrompt'         =>  '最小起始卡为',
	'PTMemInfoSetting'         =>  '会员信息设置', //----------会员信息设置----------//
	'PTMemInfoEdit'         =>  '编辑会员信息',
	'SingFieldDefTitleRealName'         =>  '真实姓名',
	'MemDefSel'         =>  '会员默认项',
	'Changeable'         =>  '可修改',
	'MustFilToClaim'         =>  '领卡必填',
	'PTMemPrivilege'         =>  '特权管理', //----------特权管理----------//
	'PTMemPrivilegeEdit'         =>  '编辑特权内容',
	'PTMemPrivilegeAdd'         =>  '添加特权内容',
	'MemPriviTopNotifi1'         =>  '1.进行中或已经结束的特权不可以修改！',
	'MemPriviTopNotifi2'         =>  '2.您最多可以创建8条特权!',
	'PrivilTime'         =>  '特权时间',
	'PrivilTitle'         =>  '特权标题',
	'PrivilTitlePrompt'         =>  '特权标题必须填写',
	'PrivilTitleLenPrompt1'         =>  '特权标题长度只能在2-{0}位字符之间',
	'PrivilTitleLenPrompt2'         =>  '特权标题长度只能在{0}-30位字符之间',
	'PrivilTimesSetPromt'         =>  '设为0表示不限次数',
	
	'PrivilGroup'         =>  '适用人群',
	'PrivilCon'         =>  '特权内容',
	'UsedTimes'         =>  '使用次数',
	'CanUsedTimes'         =>  '可使用次数',
	'PrivilStatus'         =>  '状态',
	'PrivilMemCheck'         =>  '查看员特权',
	'SelUserGroup'         =>  '选择人群',	
	'UserGroup'         =>  '使用人群',
	'AllMem'         =>  '所有会员',
	'Normal'         =>  '普通',
	'Vip1'         =>  '白金',
	'Vip2'         =>  '钻石',
	'MemSilver'         =>  '银卡会员',
	'MemGold'         =>  '金卡会员',
	'MemDiamond'         =>  '钻石会员',						
	'SelTime'         =>  '时间设置',
	'PTMemPointPlan'         =>  '积分策略', //----------积分策略----------//
	'PlanName'         =>  '策略名称',
	'PointsAward'         =>  '奖励积分(必须为整数)',
	'PointsSettingPrompt'         =>  '积分设置(必须为整数)',
	'DailySigninPoints'         =>  '每天签到奖励',
	'Consec6DaysSigninPoints'         =>  '连续6天签到奖励',
	'Spend1YuanPoints'         =>  '消费1元奖励',
	'PTMemLvSetting'         =>  '会员等级设置', //----------积分策略----------//
	'MemLv'         =>  '会员等级',
	'MemLvPrompt'         =>  '最多添加10个等级',
	'PTNotifMan'         =>  '通知管理', //----------通知管理----------//
	'NotifManPrompt'         =>  '*通知一但添加成功后，立即发送，用户到达率100%，请谨慎使用。',
	'PTSendNotifMan'         =>  '发布通知', //----------发布----------//
	'PTMemInfoList'         =>  '账户会员信息列表', //----------账户会员信息列表----------//
	'PTNotificationEdit'         =>  '编辑通知',
	'WcOpenid'         =>  '微信OPENID',
	'MemCardNum'         =>  '会员卡号',
	'MemName'         =>  '姓名',
	'CardColTime'         =>  '领卡时间',
	'MemCoupMan'         =>  '会员优惠卷管理',
	'MemCoupRelease'         =>  '发布优惠券',
	'MemGiftCertificates'         =>  '查看礼品券',
	'MemCoupName'         =>  '券名称',
	'MemCoupType'         =>  '券类型',
	'MemCoupDiscount'         =>  '打折优惠券',
	'MemCoupCashVoucher'         =>  '现金抵用券 ',
	'MemCoupCashVoucherValue'         =>  '抵用金额',
	'MemCanGet'         =>  '每个用户可以获得',
	'NumOfCoupons'         =>  '张券',
	'MemCoupInstruPrompt'         =>  '在此说明券的使用方式，如最低消费金额，优惠券打折信息，不与其他优惠同时使用、节假日不可使用等。',

	//-------V2.31新页面翻译----------//
	'PTVipCardWeChatTriggerSettings'         =>  '会员卡微信触发配置',
	'VipCardName'         =>  '会员卡名称',


);
