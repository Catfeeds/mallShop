/*kevin 商户·积分商城 2017-3-18 16：42*/
1、ALTER TABLE `tp_mall_member_integral_goods` CHANGE `adduid` `adduid` INT(11) DEFAULT 0 NOT NULL COMMENT '添加用户id', CHANGE `edituid` `edituid` INT(11) DEFAULT 0 NOT NULL COMMENT '编辑用户id', CHANGE `shopsid` `shopsid` INT(11) DEFAULT 0 NOT NULL COMMENT '分店id', CHANGE `cid` `cid` VARCHAR(30) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL COMMENT '礼品类型id', CHANGE `sendtype` `sendtype` TINYINT(1) DEFAULT 0 NOT NULL COMMENT '配送方式 1：快递配送；2：到店领取；', CHANGE `vouchertype` `vouchertype` TINYINT(3) DEFAULT 0 NOT NULL COMMENT '虚拟券类型：1：Eshop优惠券；2：门店使用优惠券；3：兑换券；40：通用券；', CHANGE `vouchersid` `vouchersid` VARCHAR(30) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL COMMENT '虚拟关联券id', ADD COLUMN `voucherskuid` VARCHAR(30) DEFAULT '' NOT NULL COMMENT '卡券规格（skuid）' AFTER `vouchersid`, CHANGE `prefix` `prefix` VARCHAR(20) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL COMMENT '虚拟券前缀', CHANGE `viewnum` `viewnum` INT(11) DEFAULT 0 NOT NULL COMMENT '浏览量'; 
2、ALTER TABLE `tp_mall_member_integral_goods` CHANGE `vouchertype` `vouchertype` TINYINT(3) DEFAULT 0 NOT NULL COMMENT '虚拟券类型：1、Eshop优惠券；2、门店使用优惠券；3、兑换券；5、计次卡；6、团购；7、门票；9、卡券礼包；40：通用券；', CHANGE `prefix` `prefix` VARCHAR(20) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL COMMENT '虚拟券前缀（废除）'; 
3、ALTER TABLE `tp_mall_member_integral_order_goods` CHANGE `adduid` `adduid` INT(11) DEFAULT 0 NOT NULL COMMENT '添加用户id', CHANGE `edituid` `edituid` INT(11) DEFAULT 0 NOT NULL COMMENT '编辑用户id', CHANGE `shopsid` `shopsid` INT(11) DEFAULT 0 NOT NULL COMMENT '分店id', CHANGE `orderid` `orderid` VARCHAR(50) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '0' NOT NULL COMMENT '订单编号', CHANGE `goodtype` `goodtype` TINYINT(1) DEFAULT 0 NOT NULL COMMENT '商品类型：1：实物，2：虚拟；默认：0；', ADD COLUMN `vouchertype` TINYINT(3) DEFAULT 0 NOT NULL COMMENT '虚拟券类型：1、Eshop优惠券；2、门店使用优惠券；3、兑换券；5、计次卡；6、团购；7、门票；9、卡券礼包；40：通用券；' AFTER `goodtype`, CHANGE `vouchersid` `vouchersid` VARCHAR(30) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL COMMENT '虚拟关联券id', ADD COLUMN `voucherskuid` VARCHAR(30) DEFAULT '' NOT NULL COMMENT '卡券规格（skuid）' AFTER `vouchersid`, ADD COLUMN `voucherskuname` VARCHAR(50) DEFAULT '' NOT NULL COMMENT '卡券规格名称（skuid）' AFTER `voucherskuid`, CHANGE `prefix` `prefix` VARCHAR(20) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL COMMENT '虚拟券前缀（废除）', ADD COLUMN `usetimelimittype` TINYINT(1) DEFAULT 0 NOT NULL COMMENT '使用时间限制类型：1：购买后几日内有效精确到时分秒在支付完成时间的基础上加上填写的天数时间；2：按照时间段进行限制精确到时分秒例如2016-10-21至2016-10-22，那就是2016-10-21 00:00:00 至2016-10-22 23:59:59有效；' AFTER `prefix`, ADD COLUMN `usetimelimitset` VARCHAR(100) DEFAULT '' NOT NULL COMMENT '使用时间限制设置：1：购买后几日内数据存储为罗马数字1/2/34/99...;2:按照时间段进行限制储存为array(\'begintime\'=>\'2016-10-20\',\'enttime\'=>\'2016-10-22\')的JSON字符串；3：限制选定的某一天内存储格式为2016-10-22；' AFTER `usetimelimittype`, ADD COLUMN `useshopslimitset` VARCHAR(5000) DEFAULT ',' NOT NULL COMMENT '使用门店限制设置：数据存储为门店ID拼接的字符串，例如：,1,2,3,12,34,15,注意前后都需要\",\"开头结尾，防止1,11,111，会出现错误数据，筛选时需要使用\",1,\"进行筛选；' AFTER `usetimelimitset`, ADD COLUMN `backorderpolicyset` VARCHAR(20) DEFAULT ',' NOT NULL COMMENT '退单政策：1：随时退；2：过期退；默认：,；数据存储样例：,1,2,' AFTER `useshopslimitset`;  
4、ALTER TABLE `tp_mall_member_integral_order_goods` CHANGE `vouchertype` `vouchertype` TINYINT(3) DEFAULT 0 NOT NULL COMMENT '虚拟券类型：1、Eshop优惠券；2、门店使用优惠券；3、兑换券；5、计次卡；6、团购；7、门票；8、权益卡；9、卡券礼包；40：通用券；'; 
/*【已执行】kevin 风助手储值充值 2017-2-18 16：00*/1、CREATE TABLE `tp_storedvalue_helper_set` (
  `id` varchar(30) NOT NULL DEFAULT '' COMMENT '主键ID（系统随机生成）',
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  `isopennotquota` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否启用不定额充值：1：启用；2：不启用 默认：启用',
  `isopenquota` tinyint(1) NOT NULL DEFAULT '2' COMMENT '是否启用定额充值：1：启用；2：不启用 默认：不启用',
  `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updatetime` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  KEY `companyid` (`companyid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='风助手储值-设置主表'
2、CREATE TABLE `tp_storedvalue_helper_goods` (
  `id` varchar(30) NOT NULL DEFAULT '' COMMENT '主键ID（系统随机生成）',
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  `price` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '售价',
  `recharge` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '实际储值',
  `discount` decimal(3,1) NOT NULL DEFAULT '0.0' COMMENT '折扣',
  `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updatetime` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='风助手储值-商品信息主表'
3、CREATE TABLE `tp_storedvalue_helper_goods_order` (
  `id` varchar(30) NOT NULL DEFAULT '' COMMENT '主键ID（系统随机生成）',
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  `mid` int(11) NOT NULL DEFAULT '0' COMMENT '会员id',
  `orderid` varchar(50) NOT NULL DEFAULT '0' COMMENT '订单编号',
  `linkoutorderid` varchar(50) DEFAULT '' COMMENT '商户订单号关联第三方支付商户平台订单号',
  `borderid` varchar(50) DEFAULT '' COMMENT '交易编号',
  `storedtype` tinyint(1) NOT NULL DEFAULT '0' COMMENT '储值类型 1、不定额；2、定额',
  `paytype` tinyint(1) NOT NULL DEFAULT '0' COMMENT '支付类型 1、微信；2、支付宝；3、现金；4、刷卡；',
  `goodname` varchar(100) DEFAULT '' COMMENT '商品名称',
  `price` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '商品售价',
  `recharge` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '实际储值',
  `discount` decimal(3,1) DEFAULT '0.0' COMMENT '折扣',
  `payprice` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '支付金额',
  `paytime` int(11) DEFAULT '0' COMMENT '支付时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 1、未支付；2、支付成功；3、支付失败；默认：1；',
  `note` varchar(500) DEFAULT '' COMMENT '店员备注',
  `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updatetime` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='风助手储值-储值商品购买订单表'
/*【已执行】kevin scrm5 Eshop改版 2017-1-16 10：57*/1、ALTER TABLE `tp_mall_goods` CHANGE `goodtype` `goodtype` TINYINT(2) DEFAULT 0 NOT NULL COMMENT '商品类型：1：实物商品，2：券商品；3：计次卡商品；4：团购商品；5：门票商品；6：权益卡商品；7：卡券礼包；默认：0；'; 
2、ALTER TABLE `tp_mall_order_info` CHANGE `goodtype` `goodtype` TINYINT(2) DEFAULT 0 NOT NULL COMMENT '商品类型：1：实物商品，2：券商品；3：计次卡商品；4：团购商品；5：门票商品；6：权益卡商品；7：卡券礼包；默认：0；'; 
3、ALTER TABLE `tp_mall_order_goods` CHANGE `goodtype` `goodtype` TINYINT(2) DEFAULT 0 NOT NULL COMMENT '商品类型：1：实物商品，2：券商品；3：计次卡商品；4：团购商品；5：门票商品；6：权益卡商品；7：卡券礼包；默认：0；'; 
4、ALTER TABLE `tp_mall_order_service` CHANGE `type` `type` TINYINT(1) DEFAULT 0 NOT NULL COMMENT '类型：1：退款，2：退货；3：换货；4：过期退；5：随时退；默认：0；'; 
5、ALTER TABLE `tp_mall_order_info` ADD COLUMN `vouchertitle` VARCHAR(100) DEFAULT '' NULL COMMENT '优惠券名称' AFTER `eshopdiscountmoney`, ADD COLUMN `vouchermoney` DECIMAL(12,2) DEFAULT 0.00 NULL COMMENT '优惠券优惠金额' AFTER `vouchertitle`; 
/*【已执行】kevin scrm5 Eshop运费模板 2016-12-26 11：39*/1、ALTER TABLE `tp_mall_freight_tpl` ADD COLUMN `type` TINYINT(1) DEFAULT 0 NOT NULL COMMENT '模板类型 1、按件数；2、按重量；默认：0；' AFTER `name`;
2、ALTER TABLE `tp_mall_freight_tpl_child` ADD COLUMN `firstpiece` DECIMAL(12,2) DEFAULT 0.00 NULL COMMENT '首件价格' AFTER `areaids`, ADD COLUMN `continuedpiece` DECIMAL(12,2) DEFAULT 0.00 NULL COMMENT '续件价格' AFTER `firstpiece`, CHANGE `firstheavy` `firstheavy` DECIMAL(12,2) DEFAULT 0.00 NULL COMMENT '首重价格', CHANGE `continuedheavy` `continuedheavy` DECIMAL(12,2) DEFAULT 0.00 NULL COMMENT '续重价格'; 
3、ALTER TABLE `tp_mall_freight_tpl_child` ADD COLUMN `areastring` VARCHAR(3000) DEFAULT '' NULL COMMENT '所选地区 用于列表显示' AFTER `continuedheavy`; 
/*【已执行】kevin 商户·积分商城·浏览量 2016-11-08 12：31*/1、ALTER TABLE `tp_mall_member_integral_class` ADD COLUMN `viewnum` INT(11) DEFAULT 0 NULL COMMENT '浏览量' AFTER `sort`; 
2、ALTER TABLE `tp_mall_member_integral_goods` ADD COLUMN `viewnum` INT(11) DEFAULT 0 NULL COMMENT '浏览量' AFTER `prefix`; 
/*【已执行】kevin 收货地址（人来风积分商城） 2016-10-14 10：25*/1、ALTER TABLE `tp_mall_integral_address` ADD COLUMN `district` VARCHAR(20) DEFAULT '' NOT NULL COMMENT '区/县' AFTER `city`, ADD COLUMN `areacode` VARCHAR(50) DEFAULT '' NOT NULL COMMENT '地区编码 存储格式：省,市,区' AFTER `address`; 
/*【已执行】kevin 收货地址（eshop、积分商城） 2016-10-13 11：33*/1、ALTER TABLE `tp_member_shop_address` CHANGE `country` `country` VARCHAR(20) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL COMMENT '国家' AFTER `mobile`, CHANGE `province` `province` VARCHAR(20) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL COMMENT '省份' AFTER `country`, CHANGE `city` `city` VARCHAR(20) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL COMMENT '城市', ADD COLUMN `district` VARCHAR(20) DEFAULT '' NOT NULL COMMENT '区/县' AFTER `city`, ADD COLUMN `areacode` VARCHAR(50) DEFAULT '' NOT NULL COMMENT '地区编码 存储格式：省,市,区' AFTER `address`; 
/*【已执行】kevin scrm5 商户·积分商城·订单详情 2016-10-11 10：13*/1、ALTER TABLE `tp_mall_member_integral_order_goods` ADD COLUMN `goodtype` TINYINT(1) DEFAULT 0 NULL COMMENT '商品类型：1：实物，2：虚拟；默认：0；' AFTER `goodnum`, ADD COLUMN `vouchersid` VARCHAR(30) DEFAULT '' NULL COMMENT '虚拟关联券id' AFTER `goodtype`, ADD COLUMN `prefix` VARCHAR(20) DEFAULT '' NULL COMMENT '虚拟券前缀' AFTER `vouchersid`; 
2、ALTER TABLE `tp_mall_member_integral_order_info` CHANGE `orderstatus` `orderstatus` TINYINT(1) DEFAULT 0 NOT NULL COMMENT '1：待发货（待领取）；2：配送中；3：已签收（已领取/电子券已发送）；4：关闭订单；5：……（超时未领取） 默认：0；';
3、ALTER TABLE `tp_mall_member_integral_order_info` ADD COLUMN `goodtype` TINYINT(1) DEFAULT 0 NOT NULL COMMENT '商品类型：1：实物；2：虚拟；默认：0；' AFTER `mid`;
/*【已执行】kevin scrm5 商户·积分商城·礼品详情 2016-10-11 10：13*/1、ALTER TABLE `tp_mall_member_integral_goods` ADD COLUMN `type` TINYINT(1) DEFAULT 0 NOT NULL COMMENT '礼品类型 1：实物；2：虚拟；默认：0' AFTER `info`, CHANGE `cid` `cid` VARCHAR(30) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NULL COMMENT '礼品类型id', CHANGE `sendtype` `sendtype` TINYINT(1) DEFAULT 0 NULL COMMENT '配送方式 1：快递配送；2：到店领取；' AFTER `cid`, ADD COLUMN `vouchertype` TINYINT(1) DEFAULT 0 NULL COMMENT '虚拟券类型：1：优惠券；2：赠品券；3：充值卡；5：电子门票' AFTER `sendtype`, ADD COLUMN `vouchersid` INT(11) DEFAULT 0 NULL COMMENT '虚拟关联券id' AFTER `vouchertype`, ADD COLUMN `prefix` VARCHAR(20) DEFAULT '' NULL COMMENT '虚拟券前缀' AFTER `vouchersid`; 
/*【已执行】kevin scrm5、2 订单导出 2016-10-09 14：40*/1、ALTER TABLE `tp_member_vouchers` ADD COLUMN `orderid` VARCHAR(50) DEFAULT '' NULL COMMENT '订单编号 tp_mall_order_info：orderid' AFTER `getvouchertype`, CHANGE `createtime` `createtime` INT(10) DEFAULT 0 NOT NULL COMMENT '创建时间'; 
/*【已执行】kevin scrm5、2 商品标签管理 2016-8-23 12：15*/1、ALTER TABLE `tp_eshop_tag` CHANGE `id` `id` VARCHAR(15) DEFAULT 0 NOT NULL COMMENT '主键ID（系统随机生成）'; 
/*【已执行】kevin scrm5、2 商品品类管理 2016-8-23 12：43*/1、ALTER TABLE `tp_eshop_class` CHANGE `id` `id` VARCHAR(15) DEFAULT '0' NOT NULL COMMENT '主键ID（系统随机生成）';
2、ALTER TABLE `tp_eshop_class` CHANGE `ptid` `ptid` VARCHAR(15) DEFAULT '' NULL COMMENT '一级品类ID'; 
/*【已执行】kevin scrm5、2 商品信息 2016-8-11 12：23*/1、ALTER TABLE `tp_mall_goods` CHANGE `isoffshelves` `isoffshelves` TINYINT(1) DEFAULT 0 NOT NULL COMMENT '商品是否下架：1：是；2：否；默认：0；' AFTER `goodtype`, CHANGE `issoldout` `issoldout` TINYINT(1) DEFAULT 0 NOT NULL COMMENT '商品是否售罄：1：是；2：否；默认：0；' AFTER `isoffshelves`, CHANGE `goodnum` `goodnum` VARCHAR(20) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL COMMENT '商品编码' AFTER `title`, CHANGE `stockamount` `stockamount` INT(11) DEFAULT 0 NOT NULL COMMENT '虚拟商品库存数量（实物商品库存量）', CHANGE `viewnum` `viewnum` INT(11) DEFAULT 0 NOT NULL COMMENT '浏览量', CHANGE `scannum` `scannum` INT(11) DEFAULT 0 NOT NULL COMMENT '二维码扫描次数' AFTER `viewnum`, CHANGE `sort` `sort` SMALLINT(5) DEFAULT 50 NOT NULL COMMENT '商品排序：默认：50；' AFTER `scannum`;
2、ALTER TABLE `tp_mall_goods` CHANGE `freighttplid` `freighttplid` VARCHAR(15) DEFAULT '0' NOT NULL COMMENT '运费模板id;默认：0；';
3、ALTER TABLE `tp_mall_goods` CHANGE `id` `id` VARCHAR(15) DEFAULT '0' NOT NULL COMMENT '主键ID（系统随机生成）'; 
/*【已执行】kevin scrm5、2 商品与标签关联信息  2016-8-23 16：09*/1、ALTER TABLE `tp_mall_tags_goods_link` CHANGE `id` `id` VARCHAR(15) DEFAULT '0' NOT NULL COMMENT '主键ID（系统随机生成）', CHANGE `tagid` `tagid` VARCHAR(15) DEFAULT '0' NOT NULL COMMENT '标签id'; 
2、ALTER TABLE `tp_mall_tags_goods_link` CHANGE `goodid` `goodid` VARCHAR(15) DEFAULT '0' NOT NULL COMMENT '商品id'; 
/*【已执行】kevin scrm5、2 商品SKU 2016-8-23 16：16*/1、ALTER TABLE `tp_mall_goods_sku` CHANGE `adduid` `adduid` INT(11) DEFAULT 0 NULL COMMENT '添加用户id', CHANGE `edituid` `edituid` INT(11) DEFAULT 0 NULL COMMENT '编辑用户id', CHANGE `shopsid` `shopsid` INT(11) DEFAULT 0 NULL COMMENT '分店id', CHANGE `name` `name` VARCHAR(200) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL COMMENT '规格', ADD COLUMN `originalprice` DECIMAL(12,2) DEFAULT 0.00 NOT NULL COMMENT '原价，仅用于显示' AFTER `name`, ADD COLUMN `saleprice` DECIMAL(12,2) DEFAULT 0.00 NOT NULL COMMENT '售价' AFTER `originalprice`, ADD COLUMN `salenum` INT(10) DEFAULT 0 NULL COMMENT '销量' AFTER `stockamount`; 
2、ALTER TABLE `tp_mall_goods_sku` ADD COLUMN `intprice` DECIMAL(12,2) DEFAULT 0.00 NOT NULL COMMENT '积分价' AFTER `saleprice`; 
3、ALTER TABLE `tp_mall_goods_sku` CHANGE `goodid` `goodid` VARCHAR(15) DEFAULT '0' NOT NULL COMMENT '商品id';  
/*【已执行】kevin scrm5、2 商品图片 2016-8-23 16：16*/1、ALTER TABLE `tp_mall_goods_pics` CHANGE `id` `id` VARCHAR(15) DEFAULT '0' NOT NULL COMMENT '主键ID（系统随机生成）'; 
2、ALTER TABLE `tp_mall_goods_pics` CHANGE `goodid` `goodid` VARCHAR(15) DEFAULT '0' NOT NULL COMMENT '商品id'; 
/*【已执行】kevin scrm2 会员折扣 2016-8-23 16：16*/1、ALTER TABLE `tp_mall_goods_rank_discount` CHANGE `goodsid` `goodsid` VARCHAR(15) DEFAULT '0' NOT NULL COMMENT '商品id(唯一)'; 
/*【已执行】kevin scrm5 整单优惠活动 2016-8-23 16：56*/CREATE TABLE `tp_eshop_discount` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8
/*【已执行】kevin scrm5、2 运费模板 2016-8-23 17：19*/1、ALTER TABLE `tp_mall_freight_tpl` CHANGE `id` `id` VARCHAR(15) DEFAULT '0' NOT NULL COMMENT '主键ID（系统随机生成）'; 
2、ALTER TABLE `tp_mall_freight_tpl_child` CHANGE `id` `id` VARCHAR(15) DEFAULT '0' NOT NULL COMMENT '主键ID（系统随机生成）', CHANGE `tplid` `tplid` VARCHAR(30) DEFAULT '0' NOT NULL COMMENT '父级模版名称'; 
/*【已执行】kevin scrm5、2 订单信息  2016-8-16 16：00*/1、ALTER TABLE `tp_mall_order_info` ADD COLUMN `offtime` INT(11) DEFAULT 0 NULL COMMENT '关闭时间' AFTER `receivaltime`; 
2、ALTER TABLE `tp_mall_order_info` ADD COLUMN `returntime` INT(11) DEFAULT 0 NULL COMMENT '退货时间' AFTER `offtime`; 
3、ALTER TABLE `tp_mall_order_goods` CHANGE `goodid` `goodid` VARCHAR(30) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '0' NOT NULL COMMENT '商品id', CHANGE `goodskuid` `goodskuid` VARCHAR(30) DEFAULT '0' NOT NULL COMMENT '商品skuid'; 
4、ALTER TABLE `tp_mall_order_goods` CHANGE `goodid` `goodid` VARCHAR(15) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '0' NOT NULL COMMENT '商品id', CHANGE `goodskuid` `goodskuid` INT(11) DEFAULT 0 NOT NULL COMMENT '商品skuid'; 
5、ALTER TABLE `tp_mall_order_info` ADD COLUMN `eshopdiscounttitle` VARCHAR(100) DEFAULT '' NULL COMMENT '整单优惠活动名称' AFTER `stockamount`, ADD COLUMN `eshopdiscountmoney` DECIMAL(12,2) DEFAULT 0.00 NULL COMMENT '整单优惠活动金额' AFTER `eshopdiscounttitle`; 
6、ALTER TABLE `tp_mall_order_info` CHANGE `id` `id` VARCHAR(15) NOT NULL COMMENT '主键ID（系统随机生成）';
7、ALTER TABLE `tp_mall_order_goods` CHANGE `id` `id` VARCHAR(15) NOT NULL COMMENT '主键ID（系统随机生成）'; 
/*【已执行】kevin scrm5 售后服务  2016-8-24 21：54*/CREATE TABLE `tp_mall_order_service` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8
/*【已执行】kevin scrm5 客服  2016-8-24 21：54*/CREATE TABLE `tp_mall_notices` (
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8
/* 客房点单微信消息模板【修改字段名称】 */
ALTER TABLE `renlaifeng`.`tp_wechat_event` CHANGE `type` `type` TINYINT(1) DEFAULT 0 NOT NULL COMMENT '触发事件类型 1:手机订座; 2:外卖管理; 3:优惠券；4:客房点单；默认:0'; 
/* tp_guestroom_goods【添加新字段】 */
ALTER TABLE `renlaifeng`.`tp_guestroom_goods` ADD COLUMN `starttimeslot` VARCHAR(10) DEFAULT '' NOT NULL COMMENT '开始供应时间段 AM/PM' AFTER `supplystart`, ADD COLUMN `endtimeslot` VARCHAR(10) DEFAULT '' NOT NULL COMMENT '结束供应时间段 AM/PM' AFTER `supplyend`; 
/* 【已添加】公司表【tp_company】新添加客房点单字段 */
ALTER TABLE `renlaifeng`.`tp_company` ADD COLUMN `roomwebtitle` VARCHAR(200) DEFAULT '' NOT NULL COMMENT '客房点单门店列表页标题' AFTER `sendtime`, ADD COLUMN `roomtitle` VARCHAR(200) DEFAULT '' NOT NULL COMMENT '客房点单分享标题' AFTER `roomwebtitle`, ADD COLUMN `roomimg` VARCHAR(200) DEFAULT '' NOT NULL COMMENT '客房点单分享图片' AFTER `roomtitle`, ADD COLUMN `roomdescribe` VARCHAR(1000) DEFAULT '' NOT NULL COMMENT '客房点单分享描述' AFTER `roomimg`;
ALTER TABLE `renlaifeng`.`tp_company` CHANGE `orderpv` `orderpv` INT(8) DEFAULT 0 NOT NULL COMMENT '点单页面浏览量', CHANGE `orderscann` `orderscann` INT(8) DEFAULT 0 NOT NULL COMMENT '点单二维码扫描次数', ADD COLUMN `roompv` INT(8) DEFAULT 0 NOT NULL COMMENT '客房页面浏览量' AFTER `roomdescribe`, ADD COLUMN `roomscann` INT(8) DEFAULT 0 NOT NULL COMMENT '客房二维码扫描次数' AFTER `roompv`;
/* 【已添加】手机点单【tp_mobilephoneorder_menu】添加新字段 */
ALTER TABLE `renlaifeng`.`tp_mobilephoneorder_menu` ADD COLUMN `content` VARCHAR(500) DEFAULT '' NOT NULL COMMENT '菜品介绍' AFTER `note`; 
/* 【已添加】风助手绑定shopid */
ALTER TABLE `renlaifeng`.`tp_users` CHANGE `helperopenid` `helperopenid` VARCHAR(100) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL COMMENT '风助手绑定的openid', ADD COLUMN `helpershopid` INT(11) DEFAULT 0 NOT NULL COMMENT '风助手绑定的shopid' AFTER `helperopenid`; 
/* 【已添加】手机收款（tp_helper_mobile_pay添加新字段[收款人id]）*/
ALTER TABLE `renlaifeng`.`tp_helper_mobile_pay` ADD COLUMN `helpermid` INT(11) DEFAULT 0 NOT NULL COMMENT '收款人id' AFTER `receivablestime`; 
/* 【已添加】手机订座（门店预约设置表：添加新字段[是否启用定金功能/订金说明]） */
ALTER TABLE `renlaifeng`.`tp_mobile_book_set` ADD COLUMN `isdeposit` TINYINT(1) DEFAULT 2 NOT NULL COMMENT '是否启用订金功能；1:是; 2:否; 默认:2' AFTER `isshow`, ADD COLUMN `explain` TEXT NOT NULL COMMENT '订金说明' AFTER `isdeposit`; 
/* 【已添加】手机订座（订座信息表：添加新字段[订座类型/订座数量]）*/
ALTER TABLE `renlaifeng`.`tp_mobile_book` ADD COLUMN `booktype` SMALLINT(5) DEFAULT 0 NOT NULL COMMENT '订座类型' AFTER `updatetime6`, ADD COLUMN `booknumber` SMALLINT(5) DEFAULT 0 NOT NULL COMMENT '订座数量' AFTER `booktype`; 
ALTER TABLE `renlaifeng`.`tp_mobile_book` ADD COLUMN `bookunit` DECIMAL(12,2) DEFAULT 0.00 NOT NULL COMMENT '订座单价' AFTER `booknumber`, ADD COLUMN `booktotal` DECIMAL(12,2) DEFAULT 0.00 NOT NULL COMMENT '订座总价' AFTER `bookunit`; 
ALTER TABLE `renlaifeng`.`tp_mobile_book` CHANGE `booktype` `booktypeid` SMALLINT(5) DEFAULT 0 NOT NULL COMMENT '订座类型id', ADD COLUMN `booktypename` VARCHAR(200) DEFAULT '' NOT NULL COMMENT '订座类型名称' AFTER `booktypeid`; 
ALTER TABLE `renlaifeng`.`tp_mobile_book` ADD COLUMN `isneedpay` TINYINT(1) DEFAULT 0 NOT NULL COMMENT '是否需要支付  1:需要; 2:不需要' AFTER `booktotal`, ADD COLUMN `ispay` TINYINT(1) DEFAULT 1 NOT NULL COMMENT '是否支付定金  1:未支付; 2:支付中; 3:支付完成; 4:支付失败; 默认:1' AFTER `isneedpay`;
ALTER TABLE `renlaifeng`.`tp_mobile_book` ADD COLUMN `isdeposit` TINYINT(1) DEFAULT 2 NOT NULL COMMENT '是否启用订金功能；1:是; 2:否; 默认:2' AFTER `booktotal`; 
ALTER TABLE `renlaifeng`.`tp_mobile_book` ADD COLUMN `out_trade_no` VARCHAR(100) DEFAULT '' NOT NULL COMMENT '商户订单号' AFTER `ispay`; 
ALTER TABLE `renlaifeng`.`tp_mobile_book` ADD COLUMN `orderid` VARCHAR(100) DEFAULT '' NOT NULL COMMENT '订单号' AFTER `ispay`; 
ALTER TABLE `renlaifeng`.`tp_mobile_book` CHANGE `bookstatus` `bookstatus` TINYINT(1) DEFAULT 1 NOT NULL COMMENT '预约状态：1：待处理；2：预订成功；3：拒绝；4：客户取消；5：已到店；6：未履约；7：待付款；默认：0；'; 
ALTER TABLE `renlaifeng`.`tp_mobile_book` ADD COLUMN `paytime` INT(11) DEFAULT 0 NOT NULL COMMENT '支付完成时间' AFTER `out_trade_no`; 

/* 新加载模板 */
INSERT INTO `tp_system_home_template_info` (`cid`) VALUES ('4'); 
UPDATE `tp_system_home_template_info` SET `tplid` = '93' WHERE `id` = '133'; 
UPDATE `tp_system_home_template_info` SET `name` = '明格荟定制列表页' WHERE `id` = '133'; 
UPDATE `tp_system_home_template_info` SET `picurl` = './Tpl/User/default/common/images/list93.jpg' WHERE `id` = '133'; 
UPDATE `tp_system_home_template_info` SET `isshow` = '1' WHERE `id` = '133';

/* 【已修改】手机点单公司表添加pv和二维码分享次数的字段 */
ALTER TABLE `renlaifeng`.`tp_company` ADD COLUMN `orderpv` INT(11) DEFAULT 0 NOT NULL COMMENT '点单页面浏览量' AFTER `orderdescribe`, ADD COLUMN `orderscann` INT(11) DEFAULT 0 NOT NULL COMMENT '点单二维码扫描次数' AFTER `orderpv`; 
/* 【已修改】手机点单公司表添加分享信息字段 */
ALTER TABLE `renlaifeng`.`tp_company` ADD COLUMN `orderimg` VARCHAR(200) DEFAULT '' NOT NULL COMMENT '点单分享图片' AFTER `takshartdescribe`, ADD COLUMN `ordertitle` VARCHAR(200) DEFAULT '' NOT NULL COMMENT '点单分享标题' AFTER `orderimg`, ADD COLUMN `orderdescribe` VARCHAR(1000) DEFAULT '' NOT NULL COMMENT '点单分享描述' AFTER `ordertitle`; 

/* 【已修改】公司添加外卖分享字段 */
ALTER TABLE `renlaifeng`.`tp_company` ADD COLUMN `takshareimg` VARCHAR(200) DEFAULT '' NOT NULL COMMENT '外卖分享图片' AFTER `isshowpoweredby`, ADD COLUMN `taksharetitle` VARCHAR(200) DEFAULT '' NOT NULL COMMENT '外卖分享标题' AFTER `takshareimg`, ADD COLUMN `takshartdescribe` VARCHAR(1000) DEFAULT '' NOT NULL COMMENT '外卖分享描述' AFTER `taksharetitle`; 

/* tp_dms_order:DMS添加新字段 */
ALTER TABLE `renlaifeng`.`tp_dms_order` CHANGE `discoutype` `discoutype` TINYINT(1) DEFAULT 0 NOT NULL COMMENT '口令类别 1.无优惠; 2.立减优惠; 3.满减优惠; 4.立折优惠; 5.满折优惠; 6.礼品赠送; 7.礼品满赠; 8.每满减优惠; 默认:0'; 
ALTER TABLE `renlaifeng`.`tp_dms_order` CHANGE `startdiscoumoney` `startdiscoumoney` DECIMAL(12,2) DEFAULT 0.00 NOT NULL COMMENT '开始优惠金额（满/每满）', CHANGE `discoumoney` `discoumoney` DECIMAL(12,2) DEFAULT 0.00 NOT NULL COMMENT '优惠金额（减）', CHANGE `discouratio` `discouratio` VARCHAR(10) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '0' NOT NULL COMMENT '优惠比例（折）', CHANGE `giftname` `giftname` VARCHAR(100) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL COMMENT '礼品名称（赠）', CHANGE `orderstatus` `orderstatus` TINYINT(1) DEFAULT 0 NOT NULL COMMENT '订单状态 1:待付款；2：待发货；3：配送中；4：支付成功（已签收）；5：已取消；7：确认到账中；默认：0；'; 
ALTER TABLE `renlaifeng`.`tp_dms_order` DROP COLUMN `wages`, ADD COLUMN `discount` VARCHAR(500) DEFAULT '' NOT NULL COMMENT '立享优惠' AFTER `ordermoney`, ADD COLUMN `wagesmoney` DECIMAL(12,2) DEFAULT 0.00 NOT NULL COMMENT '佣金金额' AFTER `discount`, ADD COLUMN `wagesprop` DECIMAL(12,2) DEFAULT 0.00 NOT NULL COMMENT '佣金比例' AFTER `wagesmoney`, ADD COLUMN `utility` VARCHAR(200) DEFAULT '' NOT NULL COMMENT '口令效用' AFTER `discoukey`; 
ALTER TABLE `renlaifeng`.`tp_dms_order` ADD COLUMN `paymoney` DECIMAL(12,2) DEFAULT 0.00 NOT NULL COMMENT '支付金额' AFTER `ordermoney`, CHANGE `wagesmoney` `wagesmoney` DECIMAL(12,2) DEFAULT 0.00 NOT NULL COMMENT '佣金金额' AFTER `paymoney`, CHANGE `discount` `discount` VARCHAR(500) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL COMMENT '立享优惠' AFTER `discoukey`, CHANGE `utility` `utility` VARCHAR(200) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL COMMENT '口令效用' AFTER `discount`; 
ALTER TABLE `renlaifeng`.`tp_dms_order` ADD COLUMN `rednmoney` DECIMAL(12,2) DEFAULT 0.00 NOT NULL COMMENT '减免金额' AFTER `paymoney`; 

/* 优惠口令 "tp_dms_discoukey" 添加新字段 */
ALTER TABLE `renlaifeng`.`tp_dms_discoukey` CHANGE `discoutype` `discoutype` TINYINT(1) DEFAULT 1 NOT NULL COMMENT '口令类别 1.无优惠; 2.立减优惠; 3.满减优惠; 4.立折优惠; 5.满折优惠; 6.礼品赠送; 7.礼品满赠; 8.每满减优惠; 默认:1', ADD COLUMN `startdiscoumoney8` DECIMAL(12,2) DEFAULT 0.00 NOT NULL COMMENT '每满减优惠->满' AFTER `discoumoney3`, ADD COLUMN `discoumoney8` DECIMAL(12,2) DEFAULT 0.00 NOT NULL COMMENT '每满减优惠->减' AFTER `startdiscoumoney8`;

/* 积分商城 积分字段*/
ALTER TABLE `renlaifeng`.`tp_mall_integral_goods` CHANGE `integral` `integral` DECIMAL(12,2) DEFAULT 0.00 NOT NULL COMMENT '所需积分'; 

/* 风助手订单表添加字段 */
ALTER TABLE `renlaifeng`.`tp_helper_mobile_pay` ADD COLUMN `dmsorderid` VARCHAR(100) DEFAULT '' NOT NULL COMMENT '(关联的)DMS订单号' AFTER `vouchersn`; 

/*[15:22:09][97 ms] 用户订座添加是否需要包间字段*/
ALTER TABLE `renlaifeng`.`tp_common_book` ADD COLUMN `bookroom` TINYINT(1) DEFAULT 0 NOT NULL COMMENT '是否需要包间：1：需要包间但不可以是大厅，2：需要包间，可以是大厅' AFTER `select5`; 

/*微信订座添加openid*/
ALTER TABLE `renlaifeng`.`tp_common_book` ADD COLUMN `openid` VARCHAR(50) DEFAULT '0' NOT NULL COMMENT 'openID' AFTER `shopsid`;

/* 【已修改】门店列表添加两个字段小票机id，小票机key */
ALTER TABLE `renlaifeng`.`tp_company_shops` ADD COLUMN `printid` VARCHAR(100) DEFAULT '' NOT NULL COMMENT '门店小票机id' AFTER `template`, ADD COLUMN `printkey` VARCHAR(100) DEFAULT '' NOT NULL COMMENT '门店小票机key' AFTER `printid`; 
/* 【已修改】添加微信买单的经验值/ 积分信息 */
ALTER TABLE `renlaifeng`.`tp_member_integral_set` ADD COLUMN `wechatpaybillisopen` TINYINT(1) DEFAULT 0 NOT NULL COMMENT '微信买单：1：开启；2：关闭' AFTER `windhelpercashspendingint`, ADD COLUMN `wechatpaybillexp` DECIMAL(12,2) DEFAULT 0.00 NOT NULL AFTER `wechatpaybillisopen`, ADD COLUMN `wechatpaybillint` DECIMAL(12,2) DEFAULT 0.00 NOT NULL AFTER `wechatpaybillexp`; 

/*新添微信信息字段*/
ALTER TABLE `renlaifeng`.`tp_yx_lotterycode_member` ADD COLUMN `typeid` TINYINT(1) DEFAULT 0 NOT NULL COMMENT '券等级' AFTER `couponname`, ADD COLUMN `typename` VARCHAR(100) DEFAULT '' NOT NULL COMMENT '奖品名' AFTER `typeid`;
ALTER TABLE `renlaifeng`.`tp_yx_lotterycode_member` ADD COLUMN `nickname` VARCHAR(200) DEFAULT '' NOT NULL COMMENT '昵称' AFTER `openid`, ADD COLUMN `headimgurl` VARCHAR(1000) DEFAULT '' NOT NULL COMMENT '头像' AFTER `nickname`; 
/*新建亚新活动中奖表*/
CREATE TABLE `tp_yx_lotterycode_member` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `adduid` int(11) NOT NULL DEFAULT '0' COMMENT '添加uid',
  `edituid` int(11) NOT NULL DEFAULT '0' COMMENT '编辑uid',
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  `shopsid` int(11) NOT NULL DEFAULT '0' COMMENT '门店id',
  `openid` varchar(100) NOT NULL DEFAULT '' COMMENT 'openid',
  `wxtype` tinyint(1) NOT NULL DEFAULT '0' COMMENT '关注状态，1：已关注；0：未关注；默认：0',
  `lotterycode` int(6) NOT NULL DEFAULT '0' COMMENT '中奖券号',
  `couponname` varchar(500) NOT NULL DEFAULT '' COMMENT '券名称',
  `remark` longtext NOT NULL COMMENT '备注',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '券状态 ：1：未兑奖；2：已兑奖；默认：0',
  `canceltime` int(10) NOT NULL DEFAULT '0' COMMENT '核销时间',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
/*新建亚新活动表*/
CREATE TABLE `tp_yx_lotterycode_prize` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `prizetype` tinyint(1) NOT NULL DEFAULT '0' COMMENT '奖品等级：1：一等奖：2：二等奖；3：三等奖；4：参与奖；默认：0',
  `prizename` varchar(200) NOT NULL DEFAULT '' COMMENT '奖品名称',
  `prizeunit` varchar(10) NOT NULL DEFAULT '' COMMENT '单价',
  `prizenum` int(5) NOT NULL DEFAULT '0' COMMENT '数量',
  `daynum` int(5) NOT NULL DEFAULT '0' COMMENT '天数',
  `num` int(5) NOT NULL DEFAULT '0' COMMENT '总数量',
  `grant` tinyint(1) NOT NULL DEFAULT '0' COMMENT '发放时间：1：5.6.8号；5：5号；6：6号；8：8号',
  `remark` longtext NOT NULL COMMENT '备注',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
/*新建抽奖记录表*/
CREATE TABLE `tp_lotterycode_member` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `adduid` int(11) NOT NULL DEFAULT '0' COMMENT '添加uid',
  `edituid` int(11) NOT NULL DEFAULT '0' COMMENT '编辑uid',
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  `shopsid` int(11) NOT NULL DEFAULT '0' COMMENT '门店id',
  `lid` int(11) NOT NULL DEFAULT '0' COMMENT '活动id',
  `openid` varchar(100) NOT NULL DEFAULT '' COMMENT 'openid',
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '姓名',
  `mobile` varchar(15) NOT NULL DEFAULT '' COMMENT '手机号',
  `email` varchar(100) NOT NULL DEFAULT '' COMMENT '邮箱',
  `address` varchar(200) NOT NULL DEFAULT '' COMMENT '地址',
  `photo` longtext NOT NULL COMMENT '照片',
  `lotterycode` int(8) NOT NULL DEFAULT '0' COMMENT '抽奖码',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '券状态 ：1：未兑奖；2：已兑奖；默认：0',
  `remark` longtext NOT NULL COMMENT '备注',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
/*新建抽奖码活动表*/
CREATE TABLE `tp_lotterycode_activity` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `adduid` int(11) NOT NULL DEFAULT '0' COMMENT '添加uid',
  `edituid` int(11) NOT NULL DEFAULT '0' COMMENT '编辑uid',
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  `shopsid` int(11) NOT NULL DEFAULT '0' COMMENT '门店id',
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '活动名称',
  `endtime` int(10) NOT NULL DEFAULT '0' COMMENT '结束时间',
  `background` varchar(200) NOT NULL DEFAULT '' COMMENT '活动背景图',
  `mcode` varchar(200) NOT NULL DEFAULT '' COMMENT '活动二维码',
  `isname` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否禁用姓名字段；1：是；2：否；默认：0',
  `isemail` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否禁用邮箱字段；1：是；2：否；默认：0',
  `isaddress` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否禁用地址字段；1：是；2：否；默认：0',
  `isphoto` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否禁用照片字段；1：是；2：否；默认：0',
  `warmhint` text NOT NULL COMMENT '温馨提示',
  `activityhint` text NOT NULL COMMENT '活动提示',
  `pv` int(11) NOT NULL DEFAULT '0' COMMENT 'pv数',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
/* [tp_system_home_template_class添加一条数据，定制详情页模板] */
INSERT INTO `renlaifeng`.`tp_system_home_template_class` (`type`, `name`, `isshow`) VALUES ('3', '定制详情页模板', '1'); 

/* [tp_system_home_template_info添加贰千金定制模板信息] */
INSERT INTO `renlaifeng`.`tp_system_home_template_info` (`cid`, `tplid`, `name`, `picurl`, `isshow`) VALUES ('1', '1161', '一千金定制首页', './Tpl/User/default/common/images/ladybundindex.jpg', '1'); 
INSERT INTO `renlaifeng`.`tp_system_home_template_info` (`cid`, `tplid`, `name`, `picurl`, `isshow`) VALUES ('3', '1161', '贰千金定制列表页', './Tpl/User/default/common/images/ladybundlist.jpg', '1'); 
INSERT INTO `renlaifeng`.`tp_system_home_template_info` (`cid`, `tplid`, `name`, `picurl`, `isshow`) VALUES ('9', '1161', '贰千金定制详情页', './Tpl/User/default/common/images/ladybundinfo.jpg', '1'); 

/* [tp_dining_dishes]添加字段是否推荐 */
ALTER TABLE `renlaifeng`.`tp_dining_dishes` ADD COLUMN `isrecomm` TINYINT(1) DEFAULT 0 NOT NULL COMMENT '是否推荐 1:推荐；2:不推荐；默认:0' AFTER `isshow`; 

/* [tp_company_shops] 餐饮菜单添加字段 */
ALTER TABLE `renlaifeng`.`tp_company_shops` ADD COLUMN `template` TINYINT(1) DEFAULT 1 NOT NULL COMMENT '选中的模板 1:2014老版菜单模板；2:2015新版菜单模板；默认:1' AFTER `viewnum`; 

/*hcms_article添加字段*/
ALTER TABLE `renlaifeng`.`tp_hcms_article` ADD COLUMN `img3` VARCHAR(100) DEFAULT '' NOT NULL COMMENT '图片3' AFTER `img2`, ADD COLUMN `sort` TINYINT(2) DEFAULT 50 NOT NULL COMMENT '排序' AFTER `author`; 
/*agent表添加字段*/
ALTER TABLE `renlaifeng`.`tp_agent` ADD COLUMN `rank` VARCHAR(200) DEFAULT '' NOT NULL COMMENT '跟单备注' AFTER `qq`; 
/* DMS 会员信息表中添加关于DMS的字段 */
ALTER TABLE `renlaifeng`.`tp_member_register_info` ADD COLUMN `peopleid` INT(11) DEFAULT 0 NOT NULL COMMENT '关联代理人id' AFTER `constellation`, ADD COLUMN `shopptime` INT(10) DEFAULT 0 NOT NULL COMMENT '最后一次购买代理人产品时间' AFTER `peopleid`; 
ALTER TABLE `renlaifeng`.`tp_member_register_info` ADD COLUMN `peopletime` INT(11) DEFAULT 0 NOT NULL COMMENT '关联代理人时间' AFTER `peopleid`, CHANGE `shopptime` `shopptime` INT(11) DEFAULT 0 NOT NULL COMMENT '最后一次购买代理人产品时间'; 

/* 风助手添加字段（['tp_member_integral_set']） */
ALTER TABLE `renlaifeng`.`tp_member_integral_set` ADD COLUMN `windhelperwechatspendingisopen` TINYINT(1) DEFAULT 0 NOT NULL COMMENT '风助手微信支付：1：开启；2：否；默认：0；' AFTER `tripadvisorpassword`, ADD COLUMN `windhelperwechatspendingexp` DECIMAL(12,2) DEFAULT 0.00 NOT NULL AFTER `windhelperwechatspendingisopen`, ADD COLUMN `windhelperwechatspendingint` DECIMAL(12,2) DEFAULT 0.00 NOT NULL AFTER `windhelperwechatspendingexp`, ADD COLUMN `windhelperalipaypendingisopen` TINYINT(1) DEFAULT 0 NOT NULL COMMENT '风助手支付宝支付：1：开启；2：否；默认：0；' AFTER `windhelperwechatspendingint`, ADD COLUMN `windhelperalipaypendingexp` DECIMAL(12,2) DEFAULT 0.00 NOT NULL AFTER `windhelperalipaypendingisopen`, ADD COLUMN `windhelperalipaypendingint` DECIMAL(12,2) DEFAULT 0.00 NOT NULL AFTER `windhelperalipaypendingexp`, ADD COLUMN `windhelpercashspendingisopen` TINYINT(1) DEFAULT 0 NOT NULL COMMENT '风助手现金支付：1：开启；2：否；默认：0；' AFTER `windhelperalipaypendingint`, ADD COLUMN `windhelpercashspendingexp` DECIMAL(12,2) DEFAULT 0.00 NOT NULL AFTER `windhelpercashspendingisopen`, ADD COLUMN `windhelpercashspendingint` DECIMAL(12,2) DEFAULT 0.00 NOT NULL AFTER `windhelpercashspendingexp`, CHANGE `updatetime` `updatetime` INT(10) DEFAULT 0 NOT NULL COMMENT '更新时间' AFTER `windhelpercashspendingint`, CHANGE `createtime` `createtime` INT(10) DEFAULT 0 NOT NULL COMMENT '创建时间' AFTER `updatetime`; 

/* 已更新 门店管理数据库（tp_company_shops）修改字段数据类型 */
ALTER TABLE `renlaifeng`.`tp_company_shops` CHANGE `province` `province` INT(11) DEFAULT 0 NOT NULL COMMENT '门店地址-省', CHANGE `city` `city` INT(11) DEFAULT 0 NOT NULL COMMENT '门店地址-市', CHANGE `district` `district` INT(11) DEFAULT 0 NOT NULL COMMENT '门店地址-区', CHANGE `typename` `typename` INT(11) DEFAULT 0 NOT NULL COMMENT '分类名称', CHANGE `seriesname` `seriesname` INT(11) DEFAULT 0 NOT NULL COMMENT '系列名称'; 
/* 已更新 门店管理数据库（tp_company_shops）添加字段 */
ALTER TABLE `renlaifeng`.`tp_company_shops` 
	ADD COLUMN `province` varchar (150) DEFAULT '' NOT NULL COMMENT '门店地址-省' AFTER `companyid`, 
	ADD COLUMN `city` varchar (150) DEFAULT '' NOT NULL COMMENT '门店地址-市' AFTER `province`,
	ADD COLUMN `district` varchar (150) DEFAULT '' NOT NULL COMMENT '门店地址-区' AFTER `city`,
	ADD COLUMN `shopname` varchar (100) DEFAULT '' NOT NULL COMMENT '门店名称' AFTER `district`,
	ADD COLUMN `typename` varchar (100) DEFAULT '' NOT NULL COMMENT '分类名称' AFTER `shopname`,
	ADD COLUMN `seriesname` varchar (100) DEFAULT '' NOT NULL COMMENT '系列名称' AFTER `typename`,
	ADD COLUMN `avgprice` decimal (8,2) DEFAULT 0.00 NOT NULL COMMENT '人均价格' AFTER `seriesname`,
	ADD COLUMN `hoursstarth` varchar (50) DEFAULT 0 NOT NULL COMMENT '营业开始时' AFTER `avgprice`,
	ADD COLUMN `hoursstarti` varchar (50) DEFAULT 00 NOT NULL COMMENT '营业开始分' AFTER `hoursstarth`,
	ADD COLUMN `hoursendh` varchar (50) DEFAULT 0 NOT NULL COMMENT '营业结束时' AFTER `hoursstarti`,
	ADD COLUMN `hoursendi` varchar (50) DEFAULT 00 NOT NULL COMMENT '营业结束分' AFTER `hoursendh`,
	ADD COLUMN `recommend` text  NOT NULL COMMENT '推荐' AFTER `hoursendi`,
	ADD COLUMN `characteristic` text  NOT NULL COMMENT '特色服务' AFTER `recommend`,
	ADD COLUMN `shopsignature` varchar (1000) DEFAULT '' NOT NULL COMMENT '门店签名' AFTER `characteristic`,
	ADD COLUMN `auditstatus` tinyint (1) DEFAULT 0 NOT NULL COMMENT '审核状态  0:未审核；1:审核中；2:审核通过；3:审核未通过' AFTER `shopsignature`,
	ADD COLUMN `poiid` int (20) DEFAULT 0 NOT NULL COMMENT 'POI_ID' AFTER `auditstatus`;

/*多图文表新增字段*/
ALTER TABLE `renlaifeng`.`tp_message_wechats_manynews` ADD COLUMN `newstitle` VARCHAR(2000) DEFAULT '' NOT NULL COMMENT '多个图文标题分号隔开' AFTER `newsid`, ADD COLUMN `newsauthor` VARCHAR(2000) DEFAULT '' NOT NULL COMMENT '多个图文的作者' AFTER `newstitle`, ADD COLUMN `newsdigest` VARCHAR(2000) DEFAULT '' NOT NULL COMMENT '多个图文的描述' AFTER `newsauthor`;
/*messagewechats表 重新创建*/
CREATE TABLE `tp_message_wechats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `adduid` int(11) NOT NULL DEFAULT '0' COMMENT '添加uid',
  `edituid` int(11) NOT NULL DEFAULT '0' COMMENT '编辑uid',
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  `shopsid` int(11) NOT NULL DEFAULT '0' COMMENT '分店id',
  `msg_id` varchar(20) NOT NULL DEFAULT '' COMMENT '消息id',
  `token` varchar(50) NOT NULL DEFAULT '' COMMENT '微信公众号',
  `sendtype` tinyint(1) NOT NULL DEFAULT '0' COMMENT '发送类型：1：立即发送；2：定时发送；',
  `sendtime` int(10) NOT NULL DEFAULT '0' COMMENT '定时发送时间',
  `msgtype` varchar(10) NOT NULL DEFAULT '' COMMENT '群发的消息类型，图文消息为mpnews，多图文消息为manynews，文本消息为text，语音为voice，音乐为music，图片为image，视频为video',
  `msgid` int(10) NOT NULL DEFAULT '0' COMMENT '关联发送内容',
  `grouptype` tinyint(1) NOT NULL DEFAULT '0' COMMENT '分组类型：1:全部；2:会员等级；3:自定义分组',
  `groupvalue` varchar(500) NOT NULL DEFAULT '' COMMENT '分组内容：分组类型为2时，存储内容为用‘,’分隔的字符串；分组类型为3时：存储内容为分组的id',
  `unsentnum` int(11) NOT NULL DEFAULT '0' COMMENT '未发送：总的发送量',
  `sentnum` int(11) NOT NULL DEFAULT '0' COMMENT '已发送量',
  `iscompleteed` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否完成：1：待发送；2：发送中；3：结束；',
  `completetime` int(10) NOT NULL DEFAULT '0' COMMENT '任务完成时间',
  `month` varchar(10) NOT NULL DEFAULT '' COMMENT '任务发送月份',
  `card` int(10) NOT NULL DEFAULT '0' COMMENT '等级id',
  `group` int(10) NOT NULL DEFAULT '0' COMMENT '标签id',
  `gender` tinyint(1) NOT NULL DEFAULT '0' COMMENT '性别：1:男；2:女',
  `country` int(10) NOT NULL DEFAULT '0' COMMENT '国家',
  `province` int(10) NOT NULL DEFAULT '0' COMMENT '省份',
  `city` int(10) NOT NULL DEFAULT '0' COMMENT '城市',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
/*修改多图文字段*/
ALTER TABLE `renlaifeng`.`tp_message_wechats_manynews` CHANGE `isuse` `issend` TINYINT(1) ZEROFILL DEFAULT 2 NOT NULL COMMENT '是否发送，1：是；2:否；默认：2'; 
/*修改红包活动数据表字段长度*/
ALTER TABLE `renlaifeng`.`tp_wechat_packet_activity_data` CHANGE `sendtime` `sendtime` INT(20) DEFAULT 0 NOT NULL COMMENT '红包发送时间';
/*修改支付宝字段*/
ALTER TABLE `renlaifeng`.`tp_company_pay_wechat` CHANGE `isempower` `isempower` TINYINT(1) DEFAULT 0 NOT NULL COMMENT '是否授权回调页面域名已设置为 “www.mobiwind.cn\"：1：是；2：否；默认：2；'; 
/*支付表新添加三个字段*/
ALTER TABLE `renlaifeng`.`tp_company_pay_wechat` ADD COLUMN `payversion` INT(1) DEFAULT 0 NOT NULL COMMENT '支付版本：1:新版本;2:老版本;默认:0' AFTER `keypassword`, ADD COLUMN `apicert` VARCHAR(400) DEFAULT '' NOT NULL COMMENT 'cert证书路径pem格式' AFTER `payversion`, ADD COLUMN `apikey` VARCHAR(400) DEFAULT '' NOT NULL COMMENT 'key证书路径pem格式' AFTER `apicert`; 
/*新建红包活动数据表*/
CREATE TABLE `tp_wechat_packet_activity_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '数据id',
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  `aid` int(11) NOT NULL DEFAULT '0' COMMENT '红包活动id',
  `mchbillno` varchar(50) NOT NULL DEFAULT '' COMMENT '商户订单号',
  `mchid` varchar(50) NOT NULL DEFAULT '' COMMENT '商户号',
  `wxappid` varchar(50) NOT NULL DEFAULT '' COMMENT '商户appid',
  `reopenid` varchar(50) NOT NULL DEFAULT '' COMMENT '接受收红包的用户openid',
  `totalamount` int(11) NOT NULL DEFAULT '0' COMMENT '付款金额，单位 分',
  `sendtime` int(11) NOT NULL DEFAULT '0' COMMENT '红包发送时间',
  `sendlistid` varchar(50) NOT NULL DEFAULT '' COMMENT '红包订单的微信单号',
  `updatetime` int(11) NOT NULL DEFAULT '0' COMMENT '修改时间',
  `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
/*新建红包活动表*/
CREATE TABLE `tp_wechat_packet_activity` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '活动id',
  `adduid` int(11) NOT NULL DEFAULT '0' COMMENT '添加uid',
  `edituid` int(11) NOT NULL DEFAULT '0' COMMENT '编辑uid',
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  `shopsid` int(11) NOT NULL DEFAULT '0' COMMENT '门店id',
  `sendname` varchar(100) NOT NULL DEFAULT '' COMMENT '商户名称',
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '活动名称',
  `code` varchar(400) NOT NULL DEFAULT '' COMMENT '二维码',
  `starttime` int(11) NOT NULL DEFAULT '0' COMMENT '开始时间',
  `endtime` int(11) NOT NULL DEFAULT '0' COMMENT '结束时间',
  `activityrule` varchar(500) NOT NULL DEFAULT '' COMMENT '活动规则',
  `alwaysmoney` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '红包总金额',
  `allottype` tinyint(1) NOT NULL DEFAULT '0' COMMENT '分配方式：1:等额;2:随机;默认:0',
  `minmoney` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '最小金额',
  `maxmoney` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '最大金额',
  `singlemoney` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '单个金额',
  `getpeople` int(11) NOT NULL DEFAULT '0' COMMENT '领取人数',
  `grantnum` int(11) NOT NULL DEFAULT '0' COMMENT '发放次数',
  `getmoney` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '被领取金额',
  `isshare` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否可以分享：1:是;2:否;默认:0',
  `remark` varchar(100) NOT NULL DEFAULT '' COMMENT '备注',
  `greetings` varchar(100) NOT NULL DEFAULT '' COMMENT '红包祝福语',
  `isshowpeople` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否显示领取人数：1:显示;2:不显示;默认:0',
  `activitytype` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态：1:已关闭;2:进行中;3:已结束(活动到期)4:未开始;默认:0',
  `shareimg` varchar(400) NOT NULL DEFAULT '' COMMENT '微信分享图片',
  `sharefriendstitle` varchar(200) NOT NULL DEFAULT '' COMMENT '微信分享标题',
  `sharedes` varchar(300) NOT NULL DEFAULT '' COMMENT '微信分享描述',
  `pv` int(10) NOT NULL DEFAULT '0' COMMENT '浏览量',
  `sharenum` int(10) NOT NULL DEFAULT '0' COMMENT '分享数',
  `updatetime` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8

/*修改字段*/
ALTER TABLE `renlaifeng`.`tp_send_email_code_log` CHANGE `creatatime` `createtime` INT(11) DEFAULT 0 NOT NULL COMMENT '创建时间';
/*新建邮箱验证表*/
CREATE TABLE `tp_send_email_code_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `email` varchar(60) NOT NULL DEFAULT '' COMMENT '申请邮箱',
  `code` varchar(50) NOT NULL DEFAULT '' COMMENT '验证code',
  `creatatime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
/*删除原有的openid1字段*/
ALTER TABLE `renlaifeng`.`tp_users` DROP COLUMN `openid1`; 
/*新建loginopenid字段*/
ALTER TABLE `renlaifeng`.`tp_users` ADD COLUMN `loginopenid` VARCHAR(100) DEFAULT '' NOT NULL COMMENT '账号绑定openid,登录时用。' AFTER `permissions`; 
/*新建登录错误日志表*/
CREATE TABLE `tp_login_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `loginip` varchar(15) NOT NULL DEFAULT '' COMMENT '登录IP',
  `errortype` int(1) NOT NULL DEFAULT '0' COMMENT '错误类型:1:用户名错误;2:密码错误;3:验证码错误',
  `creattime` int(10) NOT NULL DEFAULT '0' COMMENT '登录时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8
ALTER TABLE `renlaifeng`.`tp_login_log` CHANGE `errortype` `errortype` INT(1) DEFAULT 0 NOT NULL COMMENT '错误类型:0:全部正确;1:用户名错误;2:密码错误;3:未输入密码4:验证码不正确'; 
/* 修改任务表(tp_member_data_import_task)中status字段的备注 */
ALTER TABLE `renlaifeng`.`tp_member_data_import_task` CHANGE `status` `status` TINYINT(1) DEFAULT 0 NOT NULL COMMENT '记录状态1:未开始  2:可以导入；3:导入中；4:已完成 默认为:0'; 
ALTER TABLE `renlaifeng`.`tp_login_log` CHANGE `errortype` `errortype` INT(1) DEFAULT 0 NOT NULL COMMENT '错误类型:0:全部正确;1:用户名错误;2:密码错误;3:未输入密码;4:未输入验证码;5:验证码不正确'; 
===========================================================
/* 新建任务表 */
CREATE TABLE `tp_member_data_import_task` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `adduid` int(11) NOT NULL DEFAULT '0' COMMENT '添加uid',
  `edituid` int(11) NOT NULL DEFAULT '0' COMMENT '修改uid',
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  `shopid` int(11) NOT NULL DEFAULT '0' COMMENT '门店id',
  `allnum` int(10) NOT NULL DEFAULT '0' COMMENT '导入时统计的总条数',
  `sucnum` int(10) NOT NULL DEFAULT '0' COMMENT '导入成功条数',
  `fainum` int(10) NOT NULL DEFAULT '0' COMMENT '导入失败条数',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '记录状态1:未开始  2:进行中；3：已完成 默认为:0',
  `tstime` int(10) NOT NULL DEFAULT '0' COMMENT '开始时间',
  `tetime` int(10) NOT NULL DEFAULT '0' COMMENT '结束时间',
  `filename` varchar(400) NOT NULL DEFAULT '' COMMENT '文件名称',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8

/* 会员表register_info中添加字段 */
ALTER TABLE `renlaifeng`.`tp_member_register_info` ADD COLUMN `taskdatatype` TINYINT(1) NOT NULL COMMENT '通过会员导入任务保存的数据类型  1:本手机会员已存在，填充空白字段  2:新会员数据  默认为0' AFTER `tasklinkid1`, ADD COLUMN `tasklinkid` INT(10) NOT NULL COMMENT '关联会员数据导入任务表（tp_member_data_import_task）ID' AFTER `taskdatatype`; 
/* 修改邮箱长度（改为60） */
ALTER TABLE `renlaifeng`.`tp_member_register_info` CHANGE `email` `email` VARCHAR(60) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL COMMENT '邮箱'; 
/* 添加会员信息表的字段（职位，公司，座机电话，联系地址） */
ALTER TABLE `renlaifeng`.`tp_member_register_info` ADD COLUMN `position` VARCHAR(36) NOT NULL COMMENT '职位' AFTER `numbertimesday`, ADD COLUMN `company` VARCHAR(36) NOT NULL COMMENT '公司' AFTER `position`, ADD COLUMN `telephone` VARCHAR(36) NOT NULL COMMENT '座机电话' AFTER `company`, ADD COLUMN `contactaddress` VARCHAR(100) NOT NULL COMMENT '联系地址' AFTER `telephone`; 
================================================================================================================
/*扫码授权登陆*/
ALTER TABLE `renlaifeng`.`tp_users` ADD COLUMN `openid1` VARCHAR(50) DEFAULT '' NOT NULL COMMENT 'openid1' AFTER `truePassword`; 
/*添加公众号安全码*/
ALTER TABLE `tp_wechats` ADD COLUMN `encodingaeskey` CHAR(50) DEFAULT '' NOT NULL COMMENT 'encodingaeskey' AFTER `appsecret`;
/**已更新商城使用优惠券设置*/
 ALTER TABLE `renlaifeng`.`tp_member_marketing_activities_voucher_info` CHANGE `useissite` `useissite` VARCHAR(10) DEFAULT ',' NULL COMMENT '券可使用环境（类型）：1：现场使用，2:商城使；默认：，；此处为多选，用“,”链接例如：,1,2,', ADD COLUMN `shopcanuserspend` VARCHAR(12) DEFAULT '' NOT NULL COMMENT '消费满*元可用的设置' AFTER `usepassword`;
 ALTER TABLE `renlaifeng`.`tp_mall_order_info` ADD COLUMN `orderderateprice` DECIMAL(12,2) DEFAULT 0.00 NOT NULL COMMENT '订单减免优惠' AFTER `orderprice`;
/* 已更新在商品详情表中添加三个字段 */
ALTER TABLE `renlaifeng`.`tp_mall_goods` ADD COLUMN `shareimg` VARCHAR(400) NOT NULL COMMENT '分享图片' AFTER `viewnum`, ADD COLUMN `sharefriendstitle` VARCHAR(100) NOT NULL COMMENT '分享标题' AFTER `shareimg`, ADD COLUMN `sharedes` VARCHAR(100) NOT NULL COMMENT '分享描述' AFTER `sharefriendstitle`; 
================================================================================================
/* 修改邮箱字段长度 */
ALTER TABLE `10cc`.`tp_member_register_info` CHANGE `email` `email` VARCHAR(60) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL COMMENT 'ipos 邮箱'; 
=================================================================================================================
/**
 * 已更新
 */
CREATE TABLE `tp_member_treasure_box_lottery_num` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `companyid` int(10) NOT NULL COMMENT '公司ID',
  `mid` int(10) NOT NULL COMMENT '用户id',
  `activitesid` int(10) NOT NULL COMMENT '活动id',
  `jidianlotternumber` int(10) NOT NULL COMMENT '积点可使用次数',
  `jifenlotternumber` int(10) NOT NULL COMMENT '积分可使用次数',
  `creratetime` int(10) NOT NULL COMMENT '添加时间',
  `updatetime` int(10) NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='百宝箱可用抽奖数量记录'
/**
 * 已更新
 */
CREATE TABLE `tp_member_treasure_box_lottery_num` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `companyid` int(10) NOT NULL COMMENT '公司ID',
  `mid` int(10) NOT NULL COMMENT '用户id',
  `activitesid` int(10) NOT NULL COMMENT '活动id',
  `jidianlotternumber` int(10) NOT NULL COMMENT '积点可使用次数',
  `jifenlotternumber` int(10) NOT NULL COMMENT '积分可使用次数',
  `creratetime` int(10) NOT NULL COMMENT '添加时间',
  `updatetime` int(10) NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='百宝箱可用抽奖数量记录'
/* 已更新创建tp_member_treasure_box_lottery_num表 */
ALTER TABLE `renlaifeng`.`tp_member_treasure_box_activities` CHANGE `maxjifennumber` `maxjifelotternumber` INT(10) NOT NULL COMMENT '用每次消耗积点后的最大抽奖次数' AFTER `consumejidian`, CHANGE `maxlotterynumber` `maxlotterynumber` INT(10) DEFAULT 0 NOT NULL COMMENT '每次消耗积点后的最大抽奖次数' AFTER `maxjifelotternumber`;
/* 已更新修改积分最大数的字段 */
ALTER TABLE `renlaifeng`.`tp_member_treasure_box_activities` ADD COLUMN `maxjifennumber` INT(10) NOT NULL COMMENT '用积分抽奖的次数' AFTER `everydaylimit`; 
/* 已更新添加用积分抽奖次数字段 */
ALTER TABLE `renlaifeng`.`tp_member_treasure_box_activities` CHANGE `everyfew` `lotterylimittype` TINYINT(1) DEFAULT 0 NOT NULL COMMENT '每天能抽奖次数判断 1：无限次；2：限次 默认：0', CHANGE `daynumber` `everydaylimit` INT(10) NOT NULL COMMENT '每天抽奖的次数';
/* 已更新修改字段名 */
ALTER TABLE `renlaifeng`.`tp_member_treasure_box_activities` DROP COLUMN `lastdrawtime`, DROP COLUMN `numbertimesday`, DROP COLUMN `everyfew`, CHANGE `consumetype` `consumetype` TINYINT(1) DEFAULT 0 NOT NULL COMMENT '消耗限制：1：消耗积分，2：消耗积点，3：根据时间判断。默认：0；', ADD COLUMN `everyfew` TINYINT(1) DEFAULT 0 NOT NULL COMMENT '每天能抽奖次数判断 1：无限次；2：限次 默认：0' AFTER `createtime`, ADD COLUMN `daynumber` INT(10) NOT NULL COMMENT '每天抽奖的次数' AFTER `everyfew`; 
/* 已更新添加字段每天抽奖次数判断标示和每天抽奖次数 */
======================================================================================================================
ALTER TABLE `renlaifeng`.`tp_member_treasure_box_activities` ADD COLUMN `everyfew` INT(10) NOT NULL COMMENT '每次能抽几次的标示' AFTER `numbertimesday`; 
/* 已更新判断每次能抽几次的标示 */
ALTER TABLE `renlaifeng`.`tp_member_treasure_box_activities` ADD COLUMN `lastdrawtime` INT(10) NOT NULL COMMENT '最后抽奖时间' AFTER `createtime`, ADD COLUMN `numbertimesday` INT(10) NOT NULL COMMENT '每天抽奖次数' AFTER `lastdrawtime`; 
/* 已更新添加最后抽奖时间和每天抽奖次数字段 */
==================

ALTER TABLE `renlaifeng`.`tp_member_register_info` ADD COLUMN `numbertimesday` INT(10) NOT NULL COMMENT '每天抽奖次数' AFTER `lastdrawtime`; 
/* 已更新添加每天抽奖次数字段 */
ALTER TABLE `renlaifeng`.`tp_member_register_info` ADD COLUMN `lastdrawtime` INT(10) NOT NULL COMMENT '最后抽奖时间' AFTER `createtime`; 
/* 已更新添加字段最后抽奖时间 */


=======================================================================
ALTER TABLE `renlaifeng`.`tp_common_book` CHANGE `updatetime` `updatetime` INT(10) DEFAULT 0 NOT NULL COMMENT '更新时间'; 
/*已更新张江女神  阅读数字段修改*/
ALTER TABLE `renlaifeng`.`tp_beauties_list` CHANGE `read` `reading` INT(11) DEFAULT 0 NOT NULL COMMENT '阅读数';
/*已更新张江女神 公司简称*/
ALTER TABLE `renlaifeng`.`tp_beauties_list` ADD COLUMN `company` VARCHAR(20) DEFAULT '' NOT NULL COMMENT '公司简称' AFTER `ballot`;
/*已更新画廊定制列表页*/
insert  into `tp_system_home_template_info`(`id`,`cid`,`tplid`,`name`,`desc`,`picurl`,`sort`,`isshow`,`updatetime`,`createtime`) values (121,3,100,'MUSEO列表页1','','./Tpl/User/default/common/images/museo100.jpg',50,1,0,0),(122,3,101,'MUSEO列表页2','','./Tpl/User/default/common/images/museo101.jpg',50,1,0,0);
/*已更新画廊内页*/
CREATE TABLE `tp_gallery_inside_page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `adduid` int(11) NOT NULL DEFAULT '0' COMMENT '添加uid',
  `edituid` int(11) NOT NULL DEFAULT '0' COMMENT '编辑uid',
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  `shopsid` int(11) NOT NULL DEFAULT '0' COMMENT '分店id',
  `gid` int(11) NOT NULL DEFAULT '0' COMMENT '杂志ID',
  `picture` varchar(500) NOT NULL DEFAULT '' COMMENT '封面背景图',
  `floatpic` varchar(500) NOT NULL DEFAULT '' COMMENT '插入图',
  `btnpic` varchar(500) NOT NULL DEFAULT '' COMMENT '按钮图',
  `btnurl` varchar(400) NOT NULL DEFAULT '' COMMENT '按钮链接',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `isusing` smallint(1) NOT NULL DEFAULT '1' COMMENT '是否启用 1：启用；2禁用；默认：1',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='3g网站头部flash'
/*已更新画廊杂志 */
CREATE TABLE `tp_gallery_magazine` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `adduid` int(11) NOT NULL DEFAULT '0' COMMENT '添加uid',
  `edituid` int(11) NOT NULL DEFAULT '0' COMMENT '编辑uid',
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  `shopsid` int(11) NOT NULL DEFAULT '0' COMMENT '分店id',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '网页标题',
  `musicurl` varchar(400) NOT NULL DEFAULT '' COMMENT '音乐地址',
  `musicisshow` tinyint(1) NOT NULL DEFAULT '2' COMMENT '是否显示 1：启用；2：禁用；默认：2',
  `shareimg` varchar(400) NOT NULL DEFAULT '' COMMENT '微信分享图片',
  `sharefriendstitle` varchar(100) NOT NULL DEFAULT '' COMMENT '朋友圈分享标题',
  `sharedes` varchar(200) NOT NULL DEFAULT '' COMMENT '分享描述',
  `picture` varchar(400) NOT NULL DEFAULT '' COMMENT '封面背景图',
  `btnpic` varchar(400) NOT NULL DEFAULT '' COMMENT '按钮图',
  `btnurl` varchar(400) NOT NULL DEFAULT '' COMMENT '按钮链接',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='3g网站头部flash'
/*已更新张江女神投票*/
CREATE TABLE `tp_beauties_vote_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `adduid` int(11) NOT NULL DEFAULT '0' COMMENT '添加uid',
  `edituid` int(11) NOT NULL DEFAULT '0' COMMENT '编辑uid',
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  `shopsid` int(11) NOT NULL DEFAULT '0' COMMENT '门店id',
  `mid` int(11) NOT NULL DEFAULT '0' COMMENT 'MID',
  `bid` int(11) NOT NULL DEFAULT '0' COMMENT '被投票人ID',
  `ballot` int(11) NOT NULL DEFAULT '0' COMMENT '有效票数',
  `type` smallint(1) NOT NULL DEFAULT '1' COMMENT '是否关注 1：是；2：否；默认：1',
  `isshow` smallint(1) NOT NULL DEFAULT '1' COMMENT '是否显示 1：是；2：否；默认：1',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `companyid` (`companyid`),
  KEY `mid` (`mid`),
  KEY `ballot` (`ballot`),
  KEY `bid` (`bid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
/*已更新张江女神列表*/
CREATE TABLE `tp_beauties_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `adduid` int(11) NOT NULL DEFAULT '0' COMMENT '添加uid',
  `edituid` int(11) NOT NULL DEFAULT '0' COMMENT '编辑uid',
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  `shopsid` int(11) NOT NULL DEFAULT '0' COMMENT '门店id',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '女神姓名',
  `ballot` int(5) NOT NULL DEFAULT '0' COMMENT '票数',
  `companyLogo` varchar(200) NOT NULL DEFAULT '' COMMENT '企业Logo',
  `pic` varchar(200) NOT NULL DEFAULT '' COMMENT '女神头像',
  `pic2` varchar(200) NOT NULL DEFAULT '' COMMENT '女神全身照',
  `content` varchar(2000) NOT NULL DEFAULT '' COMMENT '女神介绍',
  `adName` varchar(12) NOT NULL DEFAULT '' COMMENT '广告名称',
  `adUrl` varchar(200) NOT NULL DEFAULT '' COMMENT '广告URL',
  `read` int(11) NOT NULL DEFAULT '0' COMMENT '阅读数',
  `relay` int(11) NOT NULL DEFAULT '0' COMMENT '转发数',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8

/*已更新张江女神排行榜幻灯片*/
CREATE TABLE `tp_beauties_ranking_slide` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `adduid` int(11) NOT NULL DEFAULT '0' COMMENT '添加uid',
  `edituid` int(11) NOT NULL DEFAULT '0' COMMENT '编辑uid',
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  `shopsid` int(11) NOT NULL DEFAULT '0' COMMENT '门店id',
  `pic` varchar(200) NOT NULL DEFAULT '' COMMENT '幻灯片图片',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `isUse` smallint(1) NOT NULL DEFAULT '1' COMMENT '是否启用 1：是；2：否；默认：1',
  `url` varchar(200) NOT NULL DEFAULT '' COMMENT '关联网页链接',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `createtime` int(10) DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8

/*已更新张江女神页面阅读（转发）数*/
CREATE TABLE `tp_beauties_page_count` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `adduid` int(11) NOT NULL DEFAULT '0' COMMENT '添加uid',
  `edituid` int(11) NOT NULL DEFAULT '0' COMMENT '编辑uid',
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  `shopsid` int(11) NOT NULL DEFAULT '0' COMMENT '门店id',
  `welcomeRead` int(11) NOT NULL DEFAULT '0' COMMENT '欢迎页阅读数',
  `welcomeRelay` int(11) NOT NULL DEFAULT '0' COMMENT '欢迎页转发数',
  `indexRead` int(11) NOT NULL DEFAULT '0' COMMENT '首页阅读数',
  `indexRelay` int(11) NOT NULL DEFAULT '0' COMMENT '首页转发数',
  `listRead` int(11) NOT NULL DEFAULT '0' COMMENT '排名阅读数',
  `listRelay` int(11) NOT NULL DEFAULT '0' COMMENT '排名转发数',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8
/*已更新张江女神欢迎页*/
CREATE TABLE `tp_beauties_welcome_ad` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `adduid` int(11) NOT NULL DEFAULT '0' COMMENT '添加uid',
  `edituid` int(11) NOT NULL DEFAULT '0' COMMENT '编辑uid',
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  `shopsid` int(11) NOT NULL DEFAULT '0' COMMENT '门店id',
  `adPic` varchar(200) NOT NULL DEFAULT '' COMMENT '广告图片',
  `adUrl` varchar(200) NOT NULL DEFAULT '' COMMENT '广告URL',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8
/*已更新杂志特效浮层 2015-6-5 */
ALTER TABLE `renlaifeng`.`tp_magazine_page` ADD COLUMN `pngImg` VARCHAR(400) DEFAULT '' NOT NULL COMMENT 'PNG特效浮层' AFTER `img`, ADD COLUMN `pngWay` SMALLINT(1) DEFAULT 0 NOT NULL COMMENT '浮层划出方式 1：从左到右；2；从右到左；3：从上到下；4：从下到上；5：淡入；默认：0' AFTER `pngImg`; 
/*已更新自然谷 短信通知模板*/
CREATE TABLE `tp_dinosaurpark_sms_notification_templet` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `adduid` int(11) NOT NULL DEFAULT '0' COMMENT '添加uid',
  `edituid` int(11) NOT NULL DEFAULT '0' COMMENT '编辑uid',
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  `shopsid` int(1) NOT NULL DEFAULT '0' COMMENT '门店id',
  `acquirenew` varchar(500) NOT NULL DEFAULT '' COMMENT '获得新门票',
  `becomedue` varchar(500) NOT NULL DEFAULT '' COMMENT '门票到期提醒',
  `used` varchar(500) NOT NULL DEFAULT '' COMMENT '门票已使用提醒',
  `expire` varchar(500) NOT NULL DEFAULT '' COMMENT '门票已过期提醒',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8

/*已更新门票标示 2015-5-19 14：21*/
ALTER TABLE `renlaifeng`.`tp_member_marketing_activities_voucher_info` CHANGE `vouchertype` `vouchertype` TINYINT(3) DEFAULT 0 NOT NULL COMMENT '券类型：1：优惠券；2：赠品券；3：充值卡；4：红包；5：门票；'; 

/**
 * 已更新新增订单状态
 */
ALTER TABLE `renlaifeng`.`tp_mall_order_info` CHANGE `orderstatus` `orderstatus` TINYINT(1) DEFAULT 0 NOT NULL COMMENT '1:待付款；2：待发货；3：配送中；4：已签收；5：已取消；6：电子券已发送；7：确认到账中；默认：0；'; 
/*已更新修改券生成方式*/
ALTER TABLE `renlaifeng`.`tp_member_marketing_activities_voucher_info` ADD COLUMN `usetimetype` TINYINT(1) NOT NULL COMMENT '券生效日期类型：1：固定生效日期有效期模式；2：收到日递延有效期模式；默认：0；' AFTER `url`, ADD COLUMN `usetimedeferred` SMALLINT(5) DEFAULT 0 NOT NULL COMMENT '券有效日期递延时间设置，按天递延' AFTER `useendtime`, ADD COLUMN `vouchercreatetype` TINYINT(1) DEFAULT 0 NOT NULL COMMENT '券生成方式：1：系统自动生成(7位)；2：券池中按照选择的券类取' AFTER `iswechattips`, ADD COLUMN `vouchercreatecatid` INT(11) DEFAULT 0 NOT NULL COMMENT '券生成的券池类id' AFTER `vouchercreatetype`;
ALTER TABLE `renlaifeng`.`tp_member_marketing_activities_voucher` CHANGE `type` `type` TINYINT(1) DEFAULT 0 NOT NULL COMMENT '类型：1：领卡促销活动；2:直赠活动活动；3：单次消费满赠活动；4：沉睡客户唤醒活动活动；5：节日关怀活动；6：生日关怀活动；7:快捷赠券活动；8:累积消费金额满赠；9：亲友生日关怀活动；10：周年店庆关怀活动；11：会员周年纪念关怀活动；12：结婚纪念日关怀活动；13：绑定Ipos/微信会员赠券活动；'; 
/*已更新添加券池类*/
ALTER TABLE `renlaifeng`.`tp_member_voucher_pool` ADD COLUMN `cid` INT(11) DEFAULT 0 NOT NULL COMMENT '券池类id' AFTER `shopsid`; 
CREATE TABLE `tp_member_voucher_pool_cat` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `adduid` int(11) NOT NULL DEFAULT '0' COMMENT '添加uid',
  `edituid` int(11) NOT NULL DEFAULT '0' COMMENT '编辑uid',
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  `shopsid` int(11) NOT NULL DEFAULT '0' COMMENT '门店id',
  `name` varchar(200) NOT NULL DEFAULT '' COMMENT '类名',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `createtime` int(10) DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
ALTER TABLE `renlaifeng`.`tp_member_vouchers` ADD COLUMN `usestarttime` INT(11) DEFAULT 0 NOT NULL COMMENT '券生效日期' AFTER `parvalue`, ADD COLUMN `useendtime` INT(11) DEFAULT 0 NOT NULL COMMENT '券有效期' AFTER `usestarttime`;


/*已更新运费模版*/
CREATE TABLE `tp_mall_freight_tpl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `adduid` int(11) NOT NULL DEFAULT '0' COMMENT '添加用户id',
  `edituid` int(11) NOT NULL DEFAULT '0' COMMENT '编辑用户id',
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  `shopsid` int(11) NOT NULL DEFAULT '0' COMMENT '分店id',
  `name` varchar(200) NOT NULL DEFAULT '' COMMENT '模版名称',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8

CREATE TABLE `tp_mall_freight_tpl_child` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `adduid` int(11) NOT NULL DEFAULT '0' COMMENT '添加用户id',
  `edituid` int(11) NOT NULL DEFAULT '0' COMMENT '编辑用户id',
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  `shopsid` int(11) NOT NULL DEFAULT '0' COMMENT '分店id',
  `tplid` int(11) NOT NULL DEFAULT '0' COMMENT '父级模版名称',
  `areanames` varchar(500) NOT NULL DEFAULT '' COMMENT '地区名称',
  `parentareaids` varchar(2000) NOT NULL DEFAULT '' COMMENT '省市区域ids，中间以,隔开',
  `areaids` varchar(2000) NOT NULL DEFAULT '' COMMENT '城市区域ids，中间以,隔开',
  `firstheavy` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '首重价格',
  `continuedheavy` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '续重价格',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8

/*已更新绑定实体卡时间*/
ALTER TABLE `renlaifeng`.`tp_member_register_info` ADD COLUMN `bindingtime` INT(11) DEFAULT 0 NOT NULL COMMENT '绑定时间' AFTER `entitynumber`; 
/*已更新实体卡导入管理*/
CREATE TABLE `tp_member_entitycard` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `adduid` int(11) NOT NULL DEFAULT '0' COMMENT '添加uid',
  `edituid` int(11) NOT NULL DEFAULT '0' COMMENT '编辑uid',
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  `shopsid` int(11) NOT NULL DEFAULT '0' COMMENT '门店id',
  `mid` int(11) NOT NULL DEFAULT '0' COMMENT '会员MID',
  `number` varchar(50) NOT NULL DEFAULT '' COMMENT '实体卡号',
  `isbinding` tinyint(1) NOT NULL DEFAULT '2' COMMENT '是否被绑定：1：是；2：否；默认：2；',
  `updatetime` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8


/*已更新renlaifeng添加首页模版*/
insert  into `tp_system_home_template_info`(`id`,`cid`,`tplid`,`name`,`desc`,`picurl`,`sort`,`isshow`,`updatetime`,`createtime`) values (115,2,422,'罗斯福首页','','./Tpl/User/default/common/images/cate1422.png',50,1,0,0),(116,4,422,'罗斯福列表页','','./Tpl/User/default/common/images/cate1422.png',50,1,0,0);


/*renlaifeng删除多余的阅读原文链接*/
ALTER TABLE `tp_message_wechats_news` DROP COLUMN `content_source_url`;
/*renlaifeng图文封面media_id*/
ALTER TABLE `tp_message_wechats_news` ADD COLUMN `thumb_media_id` VARCHAR(200) DEFAULT '' NOT NULL COMMENT '图文消息缩略图的media_id，可以在基础支持-上传多媒体文件接口中获得' AFTER `media_id`, CHANGE `thumb_media` `thumb_media` VARCHAR(200) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL COMMENT '图文消息缩略图';  
/*renlaifeng多图文 表修改*/
ALTER TABLE `tp_message_wechats_many_news` DROP COLUMN `desc`, ADD COLUMN `newsnum` TINYINT(1) DEFAULT 0 NOT NULL COMMENT '图文数量' AFTER `title`, ADD COLUMN `newsid` VARCHAR(200) DEFAULT '' NOT NULL COMMENT '单图文ids用，分隔' AFTER `newsnum`; 
RENAME TABLE `tp_message_wechats_many_news` TO `tp_message_wechats_manynews`;
DROP TABLE `tp_message_wechats_many_new`;
ALTER TABLE `tp_message_wechats` DROP COLUMN `info`, ADD COLUMN `sendtype` TINYINT(1) DEFAULT 0 NOT NULL COMMENT '发送类型：1：立即发送；2：定时发送；' AFTER `token`, CHANGE `sendtime` `sendtime` INT(10) DEFAULT 0 NOT NULL COMMENT '定时发送时间', ADD COLUMN `msgid` INT(10) DEFAULT 0 NOT NULL COMMENT '关联发送内容' AFTER `msgtype`, CHANGE `groupid` `grouptype` TINYINT(1) DEFAULT 0 NOT NULL COMMENT '分组类型：1:全部；2:会员等级；3:自定义分组', ADD COLUMN `groupvalue` VARCHAR(500) DEFAULT '' NOT NULL COMMENT '分组内容：分组类型为2时，存储内容为用‘,’分隔的字符串；分组类型为3时：存储内容为分组的id' AFTER `grouptype`;       
/*renlaifeng模板标题 2014-10-29*/
ALTER TABLE `tp_message_wechats_text` ADD COLUMN `title` VARCHAR(100) DEFAULT '' NOT NULL COMMENT '模板标题' AFTER `token`;  
/*renlaifeng系统通知群发 2014-10-30*/
ALTER TABLE  `tp_message_notices` ADD COLUMN `sendtype` TINYINT(1) DEFAULT 0 NOT NULL COMMENT '1:立即发送；2:定时发送' AFTER `info`, ADD COLUMN `grouptype` TINYINT(1) DEFAULT 0 NOT NULL COMMENT '分组类型：1:全部；2:会员等级；3:自定义分组' AFTER `completetime`, ADD COLUMN `groupvalue` VARCHAR(200) DEFAULT '' NOT NULL COMMENT '分组内容：分组类型为2时，存储内容为用‘,’分隔的字符串；分组类型为3时：存储内容为分组的id' AFTER `grouptype`;
/*renlaifeng 24hourmessage是否已读、是否回复 2014-11-01*/
ALTER TABLE  `tp_member_wechat_24hourmessage` ADD COLUMN `isread` TINYINT(1) DEFAULT 1 NOT NULL COMMENT '消息是否已读：1：否，2：是。' AFTER `msgtype`;
ALTER TABLE  `tp_member_wechat_24hourmessage` ADD COLUMN `isreply` TINYINT(1) DEFAULT 2 NOT NULL COMMENT '消息是否回复：1：是，2：否。' AFTER `isread`;
/*renlaifeng  送券活动+线下2014-10-26*/
CREATE TABLE `tp_member_line_apply_activities` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `adduid` int(11) NOT NULL DEFAULT '0' COMMENT '添加uid',
  `edituid` int(11) NOT NULL DEFAULT '0' COMMENT '编辑uid',
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  `shopsid` int(11) NOT NULL DEFAULT '0' COMMENT '门店id',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '活动标题',
  `applystarttime` int(10) NOT NULL DEFAULT '0' COMMENT '开始时间',
  `applyendtime` int(10) NOT NULL DEFAULT '0' COMMENT '截止时间',
  `numislimit` tinyint(1) NOT NULL DEFAULT '0' COMMENT '报名人数是否限制：1：是：2：否；默认：0；',
  `numlimit` int(10) NOT NULL DEFAULT '0' COMMENT '报名限制人数',
  `orderischeck` tinyint(1) NOT NULL DEFAULT '0' COMMENT '报名订单是否需要人工审核：1：是；2：否；默认：0；',
  `timelimittype` tinyint(1) NOT NULL DEFAULT '0' COMMENT '时间限制类型：1：活动期间内接受报名时间一致；2：活动期间内接受报名时间每日不一致：默认：0；',
  `activitiestarttime` int(10) NOT NULL DEFAULT '0' COMMENT '活动开始时间',
  `activitieendtime` int(10) NOT NULL DEFAULT '0' COMMENT '活动结束时间',
  `activitietimesset` varchar(200) NOT NULL DEFAULT ',' COMMENT '活动可预定时间设置中间以，连接',
  `activitiedaytimeset` varchar(1000) NOT NULL DEFAULT ',' COMMENT '活动每天不同的可预订时间设置：每一天的设置用↑分隔，日期与时间的分隔用|，时间与时间的分隔用，',
  `hiddenactivitiestarttime` int(10) NOT NULL DEFAULT '0' COMMENT '隐藏活动开始时间用于储存每天设置不同的活动时间',
  `hiddenactivitieendtime` int(10) NOT NULL DEFAULT '0' COMMENT '隐藏活动结束时间用于储存每天设置不同的活动时间',
  `desc` varchar(3000) NOT NULL DEFAULT '' COMMENT '活动注意事项',
  `info` text COMMENT '活动详情',
  `grouptype` tinyint(1) NOT NULL DEFAULT '1' COMMENT '关联客户分组类型：1:全部，2：会员等级，3：自定义客户分组。',
  `groupvalue` varchar(500) NOT NULL DEFAULT ',' COMMENT '关联分组内容：当分组类型为2,存储内容为一，分隔的字符串。当分组类型为3时：存储内容为分组的id.',
  `vouchertype` tinyint(1) NOT NULL DEFAULT '0' COMMENT '券类型：1：优惠券，2：赠品券。',
  `vouchersid` int(10) NOT NULL DEFAULT '0' COMMENT '关联券id',
  `prefix` varchar(10) NOT NULL DEFAULT '' COMMENT '券号前缀',
  `shareimg` varchar(400) NOT NULL DEFAULT '' COMMENT '微信分享图片',
  `sharefriendstitle` varchar(100) NOT NULL DEFAULT '' COMMENT '朋友圈分享标题',
  `sharedes` varchar(200) NOT NULL DEFAULT '' COMMENT '分享描述',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8
CREATE TABLE `tp_member_line_apply_activities_checkin` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `adduid` int(11) NOT NULL DEFAULT '0' COMMENT '添加uid',
  `edituid` int(11) NOT NULL DEFAULT '0' COMMENT '编辑uid',
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  `shopsid` int(11) NOT NULL DEFAULT '0' COMMENT '门店id',
  `orderid` varchar(20) NOT NULL DEFAULT '' COMMENT '签到编号',
  `activitiesid` int(11) NOT NULL DEFAULT '0' COMMENT '活动编号',
  `vouchersid` int(11) NOT NULL DEFAULT '0' COMMENT '券id',
  `vsid` varchar(20) NOT NULL DEFAULT '' COMMENT '送出的券编号',
  `mid` int(11) NOT NULL DEFAULT '0' COMMENT '会员id',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8
  
CREATE TABLE `tp_member_line_apply_activities_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `adduid` int(11) NOT NULL DEFAULT '0' COMMENT '添加uid',
  `edituid` int(11) NOT NULL DEFAULT '0' COMMENT '编辑uid',
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  `shopsid` int(11) NOT NULL DEFAULT '0' COMMENT '门店id',
  `orderid` varchar(20) NOT NULL DEFAULT '' COMMENT '订单编号',
  `activitiesid` int(11) NOT NULL DEFAULT '0' COMMENT '活动编号',
  `applydate` int(11) NOT NULL DEFAULT '0' COMMENT '报名日期',
  `applytime` varchar(50) NOT NULL DEFAULT '' COMMENT '报名时间',
  `mid` int(11) NOT NULL DEFAULT '0' COMMENT '会员id',
  `mname` varchar(50) NOT NULL DEFAULT '' COMMENT '报名姓名',
  `mmobile` varchar(20) NOT NULL DEFAULT '' COMMENT '报名手机',
  `msex` tinyint(1) NOT NULL DEFAULT '0' COMMENT '报名性别：1：男；2：女；',
  `maddress` varchar(200) NOT NULL DEFAULT '' COMMENT '地址',
  `memail` varchar(50) NOT NULL DEFAULT '' COMMENT '邮箱',
  `fname1` varchar(50) NOT NULL DEFAULT '' COMMENT '朋友姓名',
  `fmobile1` varchar(20) NOT NULL DEFAULT '' COMMENT '朋友手机',
  `fname2` varchar(50) NOT NULL DEFAULT '',
  `fmobile2` varchar(20) NOT NULL DEFAULT '',
  `fname3` varchar(50) NOT NULL DEFAULT '',
  `fmobile3` varchar(20) NOT NULL DEFAULT '',
  `fname4` varchar(50) NOT NULL DEFAULT '',
  `fmobile4` varchar(20) NOT NULL DEFAULT '',
  `fname5` varchar(50) NOT NULL DEFAULT '',
  `fmobile5` varchar(20) NOT NULL DEFAULT '',
  `adminnote` varchar(1000) NOT NULL DEFAULT '' COMMENT '管理员笔记',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '订单状态：1：待处理；2；报名成功；3：拒绝；4：客户取消；5：已签到；6：未履约；',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8
CREATE TABLE `tp_member_marketing_activities_voucher` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `adduid` int(11) NOT NULL DEFAULT '0' COMMENT '添加uid',
  `edituid` int(11) NOT NULL DEFAULT '0' COMMENT '编辑uid',
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  `shopsid` int(11) NOT NULL DEFAULT '0' COMMENT '门店id',
  `prefix` varchar(3) NOT NULL COMMENT '券号前缀',
  `voucherid` varchar(10) NOT NULL DEFAULT '' COMMENT '券编码',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '类型：1：领卡促销活动；2:直赠活动活动；3：单次消费满赠活动；4：沉睡客户唤醒活动活动；5：节日关怀活动；6：生日关怀活动；7:快捷赠券活动；8:累积消费金额满赠；9：亲友生日关怀活动；10：周年店庆关怀活动；11：会员周年纪念关怀活动；12：结婚纪念日关怀活动；',
  `sendparameter` varchar(20) NOT NULL DEFAULT '' COMMENT '投放参数（节日关怀:具体的节日;消费满赠：具体的数字；生日关怀：具体的提前几天；）',
  `sendtime` int(10) NOT NULL COMMENT '投放条件（节日关怀：投放时间；生日关怀：投放时间；）',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '活动标题',
  `starttime` int(10) NOT NULL DEFAULT '0' COMMENT '开始时间',
  `endtime` int(10) NOT NULL DEFAULT '0' COMMENT '截止时间',
  `grouptype` tinyint(1) NOT NULL DEFAULT '1' COMMENT '关联客户分组类型：1:全部，2：会员等级，3：自定义客户分组。',
  `groupvalue` varchar(200) NOT NULL DEFAULT ',' COMMENT '关联分组内容：当分组类型为2,存储内容为用'',''分隔的字符串。当分组类型为3时：存储内容为分组的id.',
  `vouchertype` tinyint(1) NOT NULL DEFAULT '0' COMMENT '券类型：1：优惠券，2：赠品券。',
  `vouchersid` int(10) NOT NULL DEFAULT '0' COMMENT '关联券id',
  `url` varchar(400) NOT NULL DEFAULT '' COMMENT '关联网页link',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8

CREATE TABLE `tp_member_marketing_activities_voucher_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `adduid` int(11) NOT NULL DEFAULT '0' COMMENT '添加uid',
  `edituid` int(11) NOT NULL DEFAULT '0' COMMENT '编辑uid',
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  `shopsid` int(11) NOT NULL DEFAULT '0' COMMENT '门店id',
  `vouchertype` tinyint(3) NOT NULL DEFAULT '0' COMMENT '券类型：1：优惠券；2：赠品券；',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '券标题',
  `parvalue` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '券面值',
  `minparvalue` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '最小券面值',
  `maxparvalue` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '最大券面值',
  `israndom` tinyint(1) NOT NULL DEFAULT '0' COMMENT '面值是否随机',
  `url` varchar(400) NOT NULL DEFAULT '' COMMENT '赠品链接',
  `usestarttime` int(11) NOT NULL DEFAULT '0' COMMENT '券生效日期',
  `useendtime` int(11) NOT NULL DEFAULT '0' COMMENT '券有效日期',
  `useshops` varchar(200) NOT NULL DEFAULT '' COMMENT '可使用门店，中间一，分隔',
  `voucherdesc` varchar(1000) NOT NULL DEFAULT '' COMMENT '券说明',
  `iscansend` tinyint(1) NOT NULL DEFAULT '0' COMMENT '券是否可转赠，1：可以，0：不可以。',
  `useissite` tinyint(1) NOT NULL DEFAULT '0' COMMENT '券可使用环境（类型）：1：现场使用，',
  `usepassword` varchar(20) NOT NULL DEFAULT '' COMMENT '券使用密码',
  `useismall` tinyint(1) NOT NULL DEFAULT '0' COMMENT '券可使用环境（类型）：1：商城使用，',
  `useisrestrict` tinyint(1) NOT NULL DEFAULT '0' COMMENT '商城使用是否限制：1：有；2：没有',
  `userestrictvalue` varchar(100) NOT NULL DEFAULT '' COMMENT '商城使用限制内容中间以，分隔，',
  `fullmanycanuse` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '商城满多少元可使用 限制。',
  `canusegoodids` varchar(500) NOT NULL DEFAULT '' COMMENT '商城中指定可使用的商品id,中间以，分隔',
  `issystemtips` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否券到期前四天站内信提醒',
  `issmstips` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否券到期前四天短信提醒',
  `iswechattips` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否券到期前四天微信提醒',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8

CREATE TABLE `tp_member_vouchers` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `adduid` int(11) NOT NULL DEFAULT '0' COMMENT '添加uid',
  `edituid` int(11) NOT NULL DEFAULT '0' COMMENT '编辑uid',
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  `shopsid` int(11) NOT NULL DEFAULT '0' COMMENT '门店id',
  `shopid` int(11) NOT NULL DEFAULT '0' COMMENT '所属门店',
  `mid` int(11) NOT NULL DEFAULT '0' COMMENT '会员id',
  `getvouchertype` tinyint(1) NOT NULL DEFAULT '0' COMMENT '会员活动获得券的方式：1:会员触发活动，2；线下报名活动；',
  `voucherid` int(11) NOT NULL DEFAULT '0' COMMENT '活动id',
  `voucherinfoid` int(11) NOT NULL DEFAULT '0' COMMENT '券id',
  `sn` varchar(20) NOT NULL DEFAULT '' COMMENT 'sn',
  `parvalue` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '券面值或价值',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `createtime` int(10) DEFAULT '0' COMMENT '创建时间',
  `isused` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否使用：1：是；2：否；3：冻结；',
  `issend` tinyint(1) NOT NULL DEFAULT '2' COMMENT '是否转赠：1是；2：否；默认：2；',
  `usetime` int(10) NOT NULL DEFAULT '0' COMMENT '使用时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8
/*renlaifeng  券 赠送 亲友生日*/
ALTER TABLE `tp_member_register_info` CHANGE `birthday` `birthday` DATE DEFAULT '0000-00-00' NULL COMMENT '生日' AFTER `totalintegration`, ADD COLUMN `weddingday` DATE DEFAULT '0000-00-00' NOT NULL COMMENT '结婚纪念日' AFTER `birthday`, ADD COLUMN `loverbirthday` DATE DEFAULT '0000-00-00' NOT NULL COMMENT '太太/先生生日' AFTER `weddingday`, ADD COLUMN `fatherbrithday` DATE DEFAULT '0000-00-00' NOT NULL COMMENT '父亲生日' AFTER `loverbirthday`, ADD COLUMN `motherbirthday` DATE DEFAULT '0000-00-00' NOT NULL COMMENT '母亲生日' AFTER `fatherbrithday`, ADD COLUMN `childbirthday` DATE DEFAULT '0000-00-00' NOT NULL COMMENT '孩子生日' AFTER `motherbirthday`; 
/*renlaifeng  自动提醒通知模板管理 2014-11-08*/
CREATE TABLE `tp_company_system_notic_set` ( `id` INT(11) NOT NULL AUTO_INCREMENT, `adduid` INT(11) NOT NULL DEFAULT '0' COMMENT '添加uid', `edituid` INT(11) NOT NULL DEFAULT '0' COMMENT '编辑uid', `companyid` INT(11) NOT NULL DEFAULT '0' COMMENT '公司id', `shopsid` INT(11) NOT NULL DEFAULT '0' COMMENT '分店id', `membergetcard` VARCHAR(500) NOT NULL DEFAULT '' COMMENT '成功领卡通知', `membergetcardisopen` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '成功领卡通知是否开启：1：开启，0：否；默认：1', `memberchangecardrank` VARCHAR(500) NOT NULL DEFAULT '' COMMENT '会员等级变更通知', `memberchangecardrankisopen` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '会员等级变更通知是否开启：1：开启，0：否；默认：1', `getvoucher` VARCHAR(500) NOT NULL DEFAULT '' COMMENT '获得新电子券', `getvoucherisopen` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '获得新电子券通知是否开启：1：开启，0：否；默认：1', `voucherbeforeexpire` VARCHAR(500) NOT NULL DEFAULT '' COMMENT '电子券到期提醒', `voucherbeforeexpireisopen` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '电子券到期提醒通知是否开启：1：开启，0：否；默认：1', `voucherused` VARCHAR(500) NOT NULL DEFAULT '' COMMENT '电子券已使用提醒', `voucherusedisopen` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '电子券已使用提醒通知是否开启：1：开启，0：否；默认：1', `voucherafterexpire` VARCHAR(500) NOT NULL DEFAULT '' COMMENT '电子券过期提醒', `voucherafterexpireisopen` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '电子券过期提醒通知是否开启：1：开启，0：否；默认：1', `booksubmit` VARCHAR(500) NOT NULL DEFAULT '' COMMENT '预约待确认', `booksubmitisopen` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '预约待确认通知是否开启：1：开启，0：否；默认：1', `booksuccess` VARCHAR(500) NOT NULL DEFAULT '' COMMENT '预约成功', `booksuccessisopen` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '预约成功通知是否开启：1：开启，0：否；默认：1', `bookfail` VARCHAR(500) NOT NULL DEFAULT '' COMMENT '预约拒绝', `bookfailisopen` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '预约拒绝通知是否开启：1：开启，0：否；默认：1', `bookbeforeexpire` VARCHAR(500) NOT NULL DEFAULT '' COMMENT '预约到期提醒', `bookbeforeexpireisopen` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '预约到期提醒通知是否开启：1：开启，0：否；默认：1', `applysubmit` VARCHAR(500) NOT NULL DEFAULT '' COMMENT '活动报名待确认', `applysubmitisopen` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '活动报名待确认通知是否开启：1：开启，0：否；默认：1', `applysuccess` VARCHAR(500) NOT NULL DEFAULT '' COMMENT '活动报名成功', `applysuccessisopen` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '活动报名成功通知是否开启：1：开启，0：否；默认：1', `applyfail` VARCHAR(500) NOT NULL DEFAULT '' COMMENT '活动报名拒绝', `applyfailisopen` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '活动报名拒绝通知是否开启：1：开启，0：否；默认：1', `applybeforeexpire` VARCHAR(500) NOT NULL DEFAULT '' COMMENT '已成功报名活动到期提醒', `applybeforeexpireisopen` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '已成功报名活动到期提醒通知是否开启：1：开启，0：否；默认：1', `applycheckinsuccess` VARCHAR(500) NOT NULL DEFAULT '' COMMENT '活动成功签到', `applycheckinsuccessisopen` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '活动成功签到通知是否开启：1：开启，0：否；默认：1', `updatetime` INT(10) NOT NULL DEFAULT '0' COMMENT '更新时间', `createtime` INT(10) NOT NULL DEFAULT '0' COMMENT '创建时间', PRIMARY KEY (`id`) ) ENGINE=INNODB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8; 
/*renlaifeng  系统自定义菜单 2014-11-10*/
CREATE TABLE `tp_system_diymen` ( `id` INT(11) NOT NULL AUTO_INCREMENT, `adduid` INT(11) NOT NULL DEFAULT '0' COMMENT '添加uid', `edituid` INT(11) NOT NULL DEFAULT '0' COMMENT '编辑uid', `companyid` INT(11) NOT NULL DEFAULT '0' COMMENT '公司id', `shopsid` INT(11) NOT NULL DEFAULT '0' COMMENT '分店id', `pid` INT(11) NOT NULL DEFAULT '0' COMMENT '父级分类', `title` VARCHAR(20) NOT NULL DEFAULT '' COMMENT '名称', `is_show` TINYINT(1) NOT NULL DEFAULT '2' COMMENT '是否显示：1：是；2：否；', `sort` SMALLINT(5) NOT NULL DEFAULT '50' COMMENT '排序：默认50', `url` VARCHAR(400) NOT NULL DEFAULT '' COMMENT '调整url', `clicknum` INT(11) NOT NULL DEFAULT '0' COMMENT '点击次数', `createtime` INT(10) NOT NULL DEFAULT '0' COMMENT '创建时间', `updatetime` INT(10) NOT NULL DEFAULT '0' COMMENT '更新时间', PRIMARY KEY (`id`) ) ENGINE=INNODB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8; 

//renlaifeng积点
ALTER TABLE `tp_member_register_info` ADD COLUMN `totaljidian` INT(10) DEFAULT 0 NOT NULL COMMENT '积点' AFTER `totalintegration`; 
//renlaifeng百宝箱可抽奖次数
ALTER TABLE `tp_member_register_info` ADD COLUMN `lotterynumber` INT(10) DEFAULT 0 NOT NULL COMMENT '百宝箱可抽奖次数' AFTER `childbirthday`;
//renlaifeng新增积分 交易类型
ALTER TABLE `tp_member_integral` CHANGE `type` `type` TINYINT(1) DEFAULT 0 NOT NULL COMMENT '积分类型：1.积分获取-注册赠送积分；2：积分获取-领卡赠送积分；3：积分获取-消费积分；4：积分获取-签到积分；5：积分获取-推荐积分；6：积分获取-人工加积分；7:积分兑出-积分换礼；8：积分兑出-人工减积分；9：积分获取-大众点评好评加积分；10：积分获取-tripadvisor好评加积分；11:积分消耗-参与活动';
//renlaifeng积点交易表
CREATE TABLE `tp_member_jidian` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `adduid` int(11) NOT NULL DEFAULT '0' COMMENT '添加uid',
  `edituid` int(11) NOT NULL DEFAULT '0' COMMENT '编辑uid',
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  `shopsid` int(11) NOT NULL DEFAULT '0' COMMENT '门店id',
  `mid` int(11) NOT NULL DEFAULT '0' COMMENT '会员id',
  `sn` varchar(20) NOT NULL DEFAULT '' COMMENT '流水编号',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '积点获得类型：1:积点增加-人工增加;2：积点消耗-人工减少；3：积点消耗-参与活动；',
  `jidiannum` int(10) NOT NULL DEFAULT '0' COMMENT '积点数',
  `jidianstatus` tinyint(1) NOT NULL DEFAULT '0' COMMENT '积点状态：1：正常；2：撤销；默认：0；',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8

//renlaifeng增加参与互动 获得积分类型
ALTER TABLE `tp_member_integral` CHANGE `type` `type` TINYINT(1) DEFAULT 0 NOT NULL COMMENT '积分类型：1.积分获取-注册赠送积分；2：积分获取-领卡赠送积分；3：积分获取-消费积分；4：积分获取-签到积分；5：积分获取-推荐积分；6：积分获取-人工加积分；7:积分兑出-积分换礼；8：积分兑出-人工减积分；9：积分获取-大众点评好评加积分；10：积分获取-tripadvisor好评加积分；11:积分消耗-参与活动;12:积分获取-参与活动；';
//renlaifeng增加获得电子券类型
ALTER TABLE `tp_member_vouchers` CHANGE `getvouchertype` `getvouchertype` TINYINT(1) DEFAULT 0 NOT NULL COMMENT '会员活动获得券的方式：1:会员触发活动，2；线下报名活动；3:抽奖活动赠券；'; 
/*renlaifeng会员卡版式设计*/
ALTER TABLE `tp_member_card_info_set` ADD COLUMN `name` VARCHAR(20) DEFAULT '' NOT NULL COMMENT '会员卡中文名称' AFTER `shopsid`, ADD COLUMN `enname` VARCHAR(50) DEFAULT '' NOT NULL COMMENT '会员卡英文名称' AFTER `name`; 
/*renlaifeng编辑个人资料*/
 ALTER TABLE `tp_member_register_info` ADD COLUMN `address` VARCHAR(100) DEFAULT '' NOT NULL COMMENT '收货地址' AFTER `lotterynumber`, ADD COLUMN `percent` DECIMAL(10,1) DEFAULT 0.0 NOT NULL COMMENT '个人资料完整度' AFTER `address`; 
CREATE TABLE `tp_member_treasure_box_activities` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `adduid` int(11) NOT NULL DEFAULT '0' COMMENT '添加uid',
  `edituid` int(11) NOT NULL DEFAULT '0' COMMENT '编辑uid',
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  `shopsid` int(11) NOT NULL DEFAULT '0' COMMENT '门店id',
  `title` varchar(200) NOT NULL DEFAULT '' COMMENT '活动标题',
  `activitiestarttime` int(10) NOT NULL DEFAULT '0' COMMENT '活动开始时间',
  `activitieendtime` int(10) NOT NULL DEFAULT '0' COMMENT '活动结束时间',
  `url` varchar(400) NOT NULL COMMENT '活动详情 url',
  `grouptype` tinyint(1) NOT NULL DEFAULT '1' COMMENT '关联客户分组类型：1:全部，2：会员等级，3：自定义客户分组。',
  `groupvalue` varchar(500) NOT NULL DEFAULT ',' COMMENT '关联分组内容：当分组类型为2,存储内容为一，分隔的字符串。当分组类型为3时：存储内容为分组的id.',
  `consumetype` tinyint(1) NOT NULL DEFAULT '0' COMMENT '消耗限制：1：消耗积分，2：消耗积点。默认：0；',
  `consumejifen` int(10) NOT NULL DEFAULT '0' COMMENT '消耗积分：默认：0',
  `consumejidian` int(10) NOT NULL DEFAULT '0' COMMENT '消耗积点：默认：0',
  `maxlotterynumber` int(10) NOT NULL DEFAULT '0' COMMENT '每次消耗积分/几点后的最大抽奖次数',
  `probability` smallint(2) NOT NULL DEFAULT '0' COMMENT '中奖概率，1-100.',
  `prizetype1` tinyint(1) NOT NULL DEFAULT '0' COMMENT '一等奖奖品类型：1：赠积分。2：赠电子券。',
  `prizejifen1` int(10) NOT NULL DEFAULT '0' COMMENT '一等奖赠送积分 默认：0',
  `prizevouchertype1` smallint(5) NOT NULL DEFAULT '0' COMMENT '一等奖赠送电子券类型：1：优惠券，2：赠品券。',
  `prizevouchersid1` int(10) NOT NULL DEFAULT '0' COMMENT '一等奖赠送关联券id',
  `prizeprefix1` varchar(10) NOT NULL DEFAULT '' COMMENT '一等奖赠送券号前缀',
  `prizenum1` int(10) NOT NULL DEFAULT '0' COMMENT '一等奖 数量',
  `prizepic1` varchar(400) NOT NULL DEFAULT '' COMMENT '一等奖 奖品图片',
  `prizetype2` tinyint(1) NOT NULL DEFAULT '0' COMMENT '二等奖奖品类型：1：赠积分。2：赠电子券。',
  `prizejifen2` int(10) NOT NULL DEFAULT '0' COMMENT '二等奖赠送积分 默认：0',
  `prizevouchertype2` smallint(5) NOT NULL DEFAULT '0' COMMENT '二等奖赠送电子券类型：1：优惠券，2：赠品券。',
  `prizevouchersid2` int(10) NOT NULL DEFAULT '0' COMMENT '二等奖赠送关联券id',
  `prizeprefix2` varchar(10) NOT NULL DEFAULT '' COMMENT '二等奖赠送券号前缀',
  `prizenum2` int(10) NOT NULL DEFAULT '0' COMMENT '二等奖 数量',
  `prizepic2` varchar(400) NOT NULL DEFAULT '' COMMENT '二等奖 奖品图片',
  `prizetype3` tinyint(1) NOT NULL DEFAULT '0',
  `prizejifen3` int(10) NOT NULL DEFAULT '0',
  `prizevouchertype3` smallint(5) NOT NULL DEFAULT '0',
  `prizevouchersid3` int(10) NOT NULL DEFAULT '0',
  `prizeprefix3` varchar(10) NOT NULL DEFAULT '',
  `prizenum3` int(10) NOT NULL DEFAULT '0',
  `prizepic3` varchar(400) NOT NULL DEFAULT '',
  `prizetype4` tinyint(1) NOT NULL DEFAULT '0',
  `prizejifen4` int(10) NOT NULL DEFAULT '0',
  `prizevouchertype4` smallint(5) NOT NULL DEFAULT '0',
  `prizevouchersid4` int(10) NOT NULL DEFAULT '0',
  `prizeprefix4` varchar(10) NOT NULL DEFAULT '',
  `prizenum4` int(10) NOT NULL DEFAULT '0',
  `prizepic4` varchar(400) NOT NULL DEFAULT '',
  `bannerpic` varchar(400) NOT NULL DEFAULT '' COMMENT '百宝箱头部banner',
  `shareimg` varchar(400) NOT NULL DEFAULT '' COMMENT '微信分享图片',
  `sharefriendstitle` varchar(100) NOT NULL DEFAULT '' COMMENT '朋友圈分享标题',
  `sharedes` varchar(200) NOT NULL DEFAULT '' COMMENT '分享描述',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='百宝箱'

CREATE TABLE `tp_member_treasure_box_activities_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `adduid` int(11) NOT NULL DEFAULT '0' COMMENT '添加uid',
  `edituid` int(11) NOT NULL DEFAULT '0' COMMENT '编辑uid',
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  `shopsid` int(11) NOT NULL DEFAULT '0' COMMENT '门店id',
  `mid` int(11) NOT NULL DEFAULT '0' COMMENT '参与者mid',
  `activitesid` int(10) NOT NULL DEFAULT '0' COMMENT '活动id',
  `prize` smallint(5) NOT NULL DEFAULT '0' COMMENT '获得的奖项',
  `weizhi` smallint(5) NOT NULL DEFAULT '0' COMMENT '转动的位置0-11',
  `prizejifen` int(10) NOT NULL DEFAULT '0' COMMENT '获得的奖品积分数',
  `prizievouchersid` int(10) NOT NULL DEFAULT '0' COMMENT '获得奖品发送出的电子券交易id',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='百宝箱'
/*renlaifeng发布*/
CREATE TABLE `tp_home_info_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `adduid` int(11) NOT NULL DEFAULT '0' COMMENT '添加用户id',
  `edituid` int(11) NOT NULL DEFAULT '0' COMMENT '编辑用户id',
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  `shopsid` int(11) NOT NULL DEFAULT '0' COMMENT '分店id',
  `name` varchar(11) NOT NULL DEFAULT '' COMMENT '发送者姓名',
  `listid` int(11) NOT NULL DEFAULT '0' COMMENT '列表页ID',
  `infoid` int(11) NOT NULL DEFAULT '0' COMMENT '详情页ID',
  `info` varchar(1000) NOT NULL DEFAULT '' COMMENT '回复内容',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8

//renlaifeng 删除mid
UPDATE `tp_member_register_info` SET `name`='' WHERE `name` LIKE 'MID%'
//renlaifeng 个人资料完整度
ALTER TABLE `renlaifeng`.`tp_member_register_info` CHANGE `percent` `percent` SMALLINT(5) DEFAULT 0 NOT NULL COMMENT '个人资料完整度'; 
//renlaifeng修改券号生成方添加默认值，注意老数据的修改
ALTER TABLE `renlaifeng`.`tp_company` ADD COLUMN `vouchersendtype` TINYINT(1) DEFAULT 0 NOT NULL COMMENT '电子券生成方式：1：人来风系统自动生成（7位无序数字)，2：券池中随机抽取券号；' AFTER `wechatfollowlink`; 
ALTER TABLE `renlaifeng`.`tp_company` CHANGE `vouchersendtype` `vouchersendtype` TINYINT(1) DEFAULT 1 NOT NULL COMMENT '电子券生成方式：1：人来风系统自动生成（7位无序数字)，2：券池中随机抽取券号；'; 
//renlaifeng改积分记录表
ALTER TABLE `renlaifeng`.`tp_member_integral` ADD COLUMN `borderid` VARCHAR(30) DEFAULT '' NOT NULL COMMENT '关联交易主表交易编号orderid' AFTER `mid`, CHANGE `sn` `orderid` VARCHAR(30) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL COMMENT '本条记录的交易编号', CHANGE `type` `type` TINYINT(2) DEFAULT 0 NOT NULL COMMENT '积分类型：1:完善100%会员资料；2:成功开卡；3:推荐开卡；4:会员等级升级；5:线下确认记录消费；6:后台人工记录消费；7:微信支付；8:银行卡支付；9:支付宝支付；10:商城货到付款；11:线上储值支付；12:后台人工储值支付；13:线上自助充值；14:后台人工充值；15:LBS签到；16:参与活动（所有的经营活动）；17:大众点评好评；18:Tripadvisor好评；19:人工加积分；20:人工减积分；21:积分消耗-参与活动；22:积分消耗-积分兑换；23:积分消耗-积分自动清零；', CHANGE `integralnum` `integralnum` DECIMAL(12,2) DEFAULT 0.00 NOT NULL COMMENT '积分数', CHANGE `integralstatus` `status` TINYINT(1) DEFAULT 0 NOT NULL COMMENT '积分状态：1：正常；2：撤销；默认：0；';
ALTER TABLE `renlaifeng`.`tp_member_register_info` CHANGE `totalspending` `totalspending` DECIMAL(12,2) DEFAULT 0.00 NOT NULL COMMENT '总的消费金额', CHANGE `totalintegration` `totalintegration` DECIMAL(12,2) DEFAULT 0.00 NOT NULL COMMENT '积分', CHANGE `totalexperiencevalue` `totalexperiencevalue` DECIMAL(12,2) DEFAULT 0.00 NOT NULL COMMENT '经验值', CHANGE `accountbalance` `accountbalance` DECIMAL(12,2) DEFAULT 0.00 NOT NULL COMMENT '余额'; 
ALTER TABLE `renlaifeng`.`tp_member_spending` CHANGE `shopid` `shopid` INT(11) DEFAULT 0 NOT NULL COMMENT '线下消费所属门店id' AFTER `shopsid`, ADD COLUMN `borderid` VARCHAR(30) DEFAULT '' NOT NULL COMMENT '关联交易主表交易编号orderid' AFTER `mid`, CHANGE `sn` `orderid` VARCHAR(30) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL COMMENT '本条记录的交易编号', ADD COLUMN `type` TINYINT(2) DEFAULT 0 NOT NULL COMMENT '消费类型：1：线下确认记录消费，2：后台人工记录消费；3:微信支付：4:银行卡支付；5：支付宝支付；6：商城货到付款；7：线上储值支付；8：后台人工储值支付；9：线上自动充值；10：后台人工充值；' AFTER `orderid`, CHANGE `spendingamount` `spendingamount` DECIMAL(12,2) DEFAULT 0.00 NOT NULL COMMENT '金额', CHANGE `spendingstatus` `status` TINYINT(1) DEFAULT 0 NOT NULL COMMENT '状态：1：正常；2：撤销；默认：0；'; 
ALTER TABLE `renlaifeng`.`tp_member_register_info` ADD COLUMN `issend100expint` TINYINT(1) DEFAULT 2 NOT NULL COMMENT '是否发送了完整资料100%积分+经验；1：是；2：否；默认:2;' AFTER `note`;
//renlaifeng经验值记录表
CREATE TABLE `tp_member_experiencevalue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `adduid` int(11) NOT NULL DEFAULT '0' COMMENT '添加uid',
  `edituid` int(11) NOT NULL DEFAULT '0' COMMENT '编辑uid',
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  `shopsid` int(11) NOT NULL DEFAULT '0' COMMENT '门店id',
  `mid` int(11) NOT NULL DEFAULT '0' COMMENT '会员id',
  `borderid` varchar(30) NOT NULL DEFAULT '' COMMENT '关联交易主表交易编号orderid',
  `orderid` varchar(30) NOT NULL DEFAULT '' COMMENT '本条记录的交易编号',
  `type` tinyint(2) NOT NULL DEFAULT '0' COMMENT '类型：1:完善100%会员资料；2:成功开卡；3:推荐开卡；4:会员等级升级；5:线下确认记录消费；6:后台人工记录消费；7:微信支付；8:银行卡支付；9:支付宝支付；10:商城货到付款；11:线上储值支付；12:后台人工储值支付；13:线上自助充值；14:后台人工充值；15:LBS签到；16:参与活动（所有的经营活动）；17:大众点评好评；18:Tripadvisor好评；19:人工加经验值；20:人工减经验值；',
  `experiencevalue` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '经验值',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态：1：正常；2：撤销；默认：0；',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8
//renlaifeng交易记录表
CREATE TABLE `tp_member_business` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `adduid` int(11) NOT NULL DEFAULT '0' COMMENT '添加uid',
  `edituid` int(11) NOT NULL DEFAULT '0' COMMENT '编辑uid',
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  `shopsid` int(11) NOT NULL DEFAULT '0' COMMENT '门店id',
  `mid` int(11) NOT NULL DEFAULT '0' COMMENT '会员id',
  `orderid` varchar(30) NOT NULL DEFAULT '' COMMENT '交易编号',
  `businesstype` tinyint(1) NOT NULL DEFAULT '0' COMMENT '交易大类型：1:单纯经验值交易；2：单纯积分交易；3：复杂消费交易包括积分或经验值。',
  `types` varchar(200) NOT NULL DEFAULT '' COMMENT '交易小类型 中间以，分隔，经验值交易e+数字类型，积分交易i+数字类型，消费交易s+数字类型；',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态：1：正常；2：撤销；默认：0；',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8
CREATE TABLE `tp_member_integral_set` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `adduid` int(11) NOT NULL DEFAULT '0' COMMENT '添加uid',
  `edituid` int(11) NOT NULL DEFAULT '0' COMMENT '编辑uid',
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  `shopsid` int(11) NOT NULL DEFAULT '0' COMMENT '门店id',
  `integralisautoclear` tinyint(1) NOT NULL DEFAULT '2' COMMENT '积分是否自动清理 1：每自然年最后一天23:59分自动清零；2：不自动清零；',
  `integralgetinfo` varchar(5000) NOT NULL DEFAULT '' COMMENT '积分经验获取规则',
  `perfectreginfoisopen` tinyint(1) NOT NULL DEFAULT '0' COMMENT '完善100%会员资料 是否开启：1：开启2：否；默认：0；',
  `perfectreginfoexp` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '赠送经验值',
  `perfectreginfoint` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '赠送积分',
  `createcardisopen` tinyint(1) NOT NULL DEFAULT '0' COMMENT '开卡：1：开启；2：否；默认：0；',
  `createcardexp` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '赠送经验值',
  `createcardint` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '赠送积分',
  `recommendcreatecardisopen` tinyint(1) NOT NULL DEFAULT '0' COMMENT '推荐开卡：1：开启；2：否；默认：0；',
  `recommendcreatecardexp` decimal(12,2) NOT NULL DEFAULT '0.00',
  `recommendcreatecardint` decimal(12,2) NOT NULL DEFAULT '0.00',
  `cardrankchangisopen` tinyint(1) NOT NULL DEFAULT '0' COMMENT '会员等级升级：1：开启；2：否；默认：0；',
  `cardrankchangexp` decimal(12,2) NOT NULL DEFAULT '0.00',
  `cardrankchangint` decimal(12,2) NOT NULL DEFAULT '0.00',
  `offlinespendingisopen` tinyint(1) NOT NULL DEFAULT '0' COMMENT '线下确认消费：1：开启；2：否；默认：0；',
  `offlinespendingexp` decimal(12,2) NOT NULL DEFAULT '0.00',
  `offlinespendingint` decimal(12,2) NOT NULL DEFAULT '0.00',
  `houtaispendingisopen` tinyint(1) NOT NULL DEFAULT '0' COMMENT '后台人工消费记录：1：开启；2：否；默认：0；',
  `houtaispendingexp` decimal(12,2) NOT NULL DEFAULT '0.00',
  `houtaispendingint` decimal(12,2) NOT NULL DEFAULT '0.00',
  `wechatspendingisopen` tinyint(1) NOT NULL DEFAULT '0' COMMENT '微信支付：1：开启；2：否；默认：0；',
  `wechatspendingexp` decimal(12,2) NOT NULL DEFAULT '0.00',
  `wechatspendingint` decimal(12,2) NOT NULL DEFAULT '0.00',
  `yinhangkapendingisopen` tinyint(1) NOT NULL DEFAULT '0' COMMENT '银行卡支付：1：开启；2：否；默认：0；',
  `yinhangkapendingexp` decimal(12,2) NOT NULL DEFAULT '0.00',
  `yinhangkapendingint` decimal(12,2) NOT NULL DEFAULT '0.00',
  `alipaypendingisopen` tinyint(1) NOT NULL DEFAULT '0' COMMENT '支付宝支付：1：开启；2：否；默认：0；',
  `alipaypendingexp` decimal(12,2) NOT NULL DEFAULT '0.00',
  `alipaypendingint` decimal(12,2) NOT NULL DEFAULT '0.00',
  `shophuodaofukuanisopen` tinyint(1) NOT NULL DEFAULT '0' COMMENT '商城货到付款：1：开启；2：否；默认：0；',
  `shophuodaofukuanexp` decimal(12,2) NOT NULL DEFAULT '0.00',
  `shophuodaofukuanint` decimal(12,2) NOT NULL DEFAULT '0.00',
  `onlinechuzhipayisopen` tinyint(1) NOT NULL DEFAULT '0' COMMENT '线上储值支付：1：开启；2：否；默认：0；',
  `onlinechuzhipayexp` decimal(12,2) NOT NULL DEFAULT '0.00',
  `onlinechuzhipayint` decimal(12,2) NOT NULL DEFAULT '0.00',
  `houtaichuzhipayisopen` tinyint(1) NOT NULL DEFAULT '0' COMMENT '后台人工储值支付：1：开启；2：否；默认：0；',
  `houtaichuzhipayexp` decimal(12,2) NOT NULL DEFAULT '0.00',
  `houtaichuzhipayint` decimal(12,2) NOT NULL DEFAULT '0.00',
  `onlinechongzhiisopen` tinyint(1) NOT NULL DEFAULT '0' COMMENT '线上自动充值：1：开启；2：否；默认：0；',
  `onlinechongzhiexp` decimal(12,2) NOT NULL DEFAULT '0.00',
  `onlinechongzhiint` decimal(12,2) NOT NULL DEFAULT '0.00',
  `houtaichongzhiisopen` tinyint(1) NOT NULL DEFAULT '0' COMMENT '后台人工充值：1：开启；2：否；默认：0；',
  `houtaichongzhiexp` decimal(12,2) NOT NULL DEFAULT '0.00',
  `houtaichongzhiint` decimal(12,2) NOT NULL DEFAULT '0.00',
  `lbsqiandaoisopen` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'LBS签到（自动）：1：开启；2：否；默认：0；',
  `lbsqiandaoexp` decimal(12,2) NOT NULL DEFAULT '0.00',
  `lbsqiandaoint` decimal(12,2) NOT NULL DEFAULT '0.00',
  `dianpingisopen` tinyint(1) NOT NULL DEFAULT '0' COMMENT '大众点评留好评：1：开启；2：否；默认：0；',
  `dianpingexp` decimal(12,2) NOT NULL DEFAULT '0.00',
  `dianpingint` decimal(12,2) NOT NULL DEFAULT '0.00',
  `dianpingpassword` varchar(20) NOT NULL DEFAULT '' COMMENT '点评确认密码',
  `tripadvisorisopen` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'TripAdvisor留好评：1：开启；2：否；默认：0；',
  `tripadvisorexp` decimal(12,2) NOT NULL DEFAULT '0.00',
  `tripadvisorint` decimal(12,2) NOT NULL DEFAULT '0.00',
  `tripadvisorpassword` varchar(20) NOT NULL DEFAULT '' COMMENT '点评确认密码',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `token` (`adduid`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8

//renlaifeng积分记录
CREATE TABLE `tp_member_integral` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `adduid` int(11) NOT NULL DEFAULT '0' COMMENT '添加uid',
  `edituid` int(11) NOT NULL DEFAULT '0' COMMENT '编辑uid',
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  `shopsid` int(11) NOT NULL DEFAULT '0' COMMENT '门店id',
  `mid` int(11) NOT NULL DEFAULT '0' COMMENT '会员id',
  `borderid` varchar(30) NOT NULL DEFAULT '' COMMENT '关联交易主表交易编号orderid',
  `orderid` varchar(30) NOT NULL DEFAULT '' COMMENT '本条记录的交易编号',
  `type` tinyint(2) NOT NULL DEFAULT '0' COMMENT '积分类型：1:完善100%会员资料；2:成功开卡；3:推荐开卡；4:会员等级升级；5:线下确认记录消费；6:后台人工记录消费；7:微信支付；8:银行卡支付；9:支付宝支付；10:商城货到付款；11:线上储值支付；12:后台人工储值支付；13:线上自助充值；14:后台人工充值；15:LBS签到；16:参与活动（所有的经营活动）；17:大众点评好评；18:Tripadvisor好评；19:人工加积分；20:人工减积分；21:积分消耗-参与活动；22:积分消耗-积分兑换；23:积分消耗-积分自动清零；',
  `integralnum` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '积分数',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '积分状态：1：正常；2：撤销；默认：0；',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8
//renlaifeng记录消费表
CREATE TABLE `tp_member_spending` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `adduid` int(11) NOT NULL DEFAULT '0' COMMENT '添加uid',
  `edituid` int(11) NOT NULL DEFAULT '0' COMMENT '编辑uid',
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  `shopsid` int(11) NOT NULL DEFAULT '0' COMMENT '门店id',
  `shopid` int(11) NOT NULL DEFAULT '0' COMMENT '线下消费所属门店id',
  `mid` int(11) NOT NULL DEFAULT '0' COMMENT '会员id',
  `borderid` varchar(30) NOT NULL DEFAULT '' COMMENT '关联交易主表交易编号orderid',
  `orderid` varchar(30) NOT NULL DEFAULT '' COMMENT '本条记录的交易编号',
  `type` tinyint(2) NOT NULL DEFAULT '0' COMMENT '消费类型：1：线下确认记录消费，2：后台人工记录消费；3:微信支付：4:银行卡支付；5：支付宝支付；6：商城货到付款；7：线上储值支付；8：后台人工储值支付；9：线上自动充值；10：后台人工充值；',
  `spendingamount` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '金额',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态：1：正常；2：撤销；默认：0；',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8
 /*renlaifeng优惠管理类型 2014-11-24*/
ALTER TABLE `renlaifeng`.`tp_member_marketing_activities_voucher_info` CHANGE `vouchertype` `vouchertype` TINYINT(3) DEFAULT 0 NOT NULL COMMENT '券类型：1：优惠券；2：赠品券；3：充值卡；4：红包；'; 
/*renlaifeng充值卡密码 2014-11-24*/
ALTER TABLE `renlaifeng`.`tp_member_vouchers` ADD COLUMN `prepaidcardpassword` VARCHAR(20) DEFAULT '' NOT NULL COMMENT '充值卡密码' AFTER `parvalue`; 
/*renlaifeng经验值、余额 2014-11-25*/
ALTER TABLE `renlaifeng`.`tp_member_register_info` ADD COLUMN `totalexperiencevalue` INT(10) DEFAULT 0 NOT NULL COMMENT '经验值' AFTER `totaljidian`; 
ALTER TABLE `renlaifeng`.`tp_member_register_info` CHANGE `totalexperiencevalue` `totalexperiencevalue` DECIMAL(10,2) DEFAULT 0.00 NOT NULL COMMENT '经验值'; 
ALTER TABLE `renlaifeng`.`tp_member_register_info` ADD COLUMN `accountbalance` DECIMAL(10,2) DEFAULT 0.00 NOT NULL COMMENT '余额' AFTER `totalexperiencevalue`; 
/*renlaifeng实体卡号、管理备注 2014-11-26*/
ALTER TABLE `renlaifeng`.`tp_member_register_info` ADD COLUMN `entitynumber` VARCHAR(30) DEFAULT '' NOT NULL COMMENT '实体卡号' AFTER `percent`; 
ALTER TABLE `renlaifeng`.`tp_member_register_info` CHANGE `birthday` `birthday` DATE DEFAULT '0000-00-00' NOT NULL COMMENT '生日', CHANGE `note` `note` VARCHAR(1000) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL COMMENT '管理备注'; 
/*renlaifeng会员卡等级 权益 2014-11-27*/
ALTER TABLE `renlaifeng`.`tp_member_card_rank` ADD COLUMN `desc` VARCHAR(2000) DEFAULT '' NOT NULL COMMENT '会员等级权益' AFTER `endscore`; 
/*renlaifeng关联券 2014-11-27*/
CREATE TABLE `tp_member_cardrank_voucher` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `adduid` int(11) NOT NULL DEFAULT '0' COMMENT '添加uid',
  `edituid` int(11) NOT NULL DEFAULT '0' COMMENT '编辑uid',
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  `shopsid` int(11) NOT NULL DEFAULT '0' COMMENT '门店id',
  `rankid` int(11) NOT NULL DEFAULT '0' COMMENT '等级ID',
  `voucherid` int(11) NOT NULL DEFAULT '0' COMMENT '券ID',
  `prefix` varchar(5) NOT NULL DEFAULT '' COMMENT '前缀',
  `num` int(11) NOT NULL DEFAULT '0' COMMENT '赠券数量',
  `updatetime` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
/*会员等级经验值备注 2014-11-28*/
ALTER TABLE `renlaifeng`.`tp_member_card_rank` CHANGE `beginscore` `beginscore` INT(11) DEFAULT 0 NOT NULL COMMENT '起始经验值', CHANGE `endscore` `endscore` INT(11) DEFAULT 0 NOT NULL COMMENT '截止经验值'; 
/*经验值类型 2014-11-28*/
ALTER TABLE `renlaifeng`.`tp_member_card_rank` CHANGE `beginscore` `beginscore` DECIMAL(12,2) DEFAULT 0.00 NOT NULL COMMENT '起始经验值', CHANGE `endscore` `endscore` DECIMAL(12,2) DEFAULT 0.00 NOT NULL COMMENT '截止经验值'; 
/*renlaifeng线下确认记录消费密码 2014-12-1*/
ALTER TABLE `renlaifeng`.`tp_company` ADD COLUMN `password` VARCHAR(4) DEFAULT '' NOT NULL COMMENT '线下确认记录消费密码' AFTER `vouchersendtype`; 
/*renlaifeng通知是否显示  2014-12-2*/
ALTER TABLE `renlaifeng`.`tp_member_notices` ADD COLUMN `isshow` TINYINT(1) DEFAULT 1 NOT NULL COMMENT '是否显示：1：是； 2：否； 默认：1' AFTER `isreply`;  
/*renlaifeng微信客服是否回复 2014-12-3*/
ALTER TABLE `renlaifeng`.`tp_member_wechat_info` ADD COLUMN `wechatmessageisreply` TINYINT(1) DEFAULT 0 NOT NULL COMMENT '是否回复 1：是；2：否；默认：0；' AFTER `wechatmessageisread`; 
/*renlaifeng官方客服是否回复 2014-12-3*/
ALTER TABLE `renlaifeng`.`tp_member_register_info` ADD COLUMN `noticesisreply` TINYINT(1) DEFAULT 0 NOT NULL COMMENT '是否回复 1：是；2：否；默认：0；' AFTER `issend100expint`;
/*renlaifeng地址用于搜索*/
ALTER TABLE `renlaifeng`.`tp_member_register_info` CHANGE `address` `address` VARCHAR(1000) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL COMMENT '收货地址用于搜索'; 
ALTER TABLE `renlaifeng`.`tp_member_wechat_24hourmessage` CHANGE `isread` `isread` TINYINT(1) DEFAULT 1 NOT NULL COMMENT '消息是否已读：1：未读，2：已读。'; 
//renlaifeng统计报表 未知性别
ALTER TABLE `renlaifeng`.`tp_report_member_card_info` ADD COLUMN `gender3` INT(11) DEFAULT 0 NOT NULL COMMENT '开卡会员性别未知统计' AFTER `gender2`;
ALTER TABLE `renlaifeng`.`tp_report_member_register_info` ADD COLUMN `gender3` INT(11) DEFAULT 0 NOT NULL COMMENT '注册会员性别未知统计' AFTER `gender2`;
ALTER TABLE `renlaifeng`.`tp_report_member_wechat_info` ADD COLUMN `gender3` INT(11) DEFAULT 0 NOT NULL COMMENT '微信粉丝性别未知统计' AFTER `gender2`; 
ALTER TABLE `renlaifeng`.`tp_wechats` CHANGE `fansaddpercentage` `fansaddpercentage` DECIMAL(12,2) DEFAULT 0.00 NOT NULL COMMENT '粉丝相对昨天比的增幅', CHANGE `gender1num` `gender1num` INT(11) DEFAULT 0 NOT NULL COMMENT '男粉丝数', CHANGE `gender2num` `gender2num` INT(11) DEFAULT 0 NOT NULL COMMENT '女粉丝数', ADD COLUMN `gender3num` INT(11) DEFAULT 0 NOT NULL COMMENT '未知性别粉丝数' AFTER `gender2num`;
ALTER TABLE `renlaifeng`.`tp_member_register_info` ADD COLUMN `membertagsid` VARCHAR(1000) DEFAULT '' NOT NULL COMMENT '会员标签id储存，用于搜索' AFTER `noticesisreply`;
ALTER TABLE `renlaifeng`.`tp_member_register_info` CHANGE `membertagsid` `membertagsid` VARCHAR(1000) CHARSET utf8 COLLATE utf8_general_ci DEFAULT ',' NOT NULL COMMENT '会员标签id储存，用于搜索'; 
ALTER TABLE `renlaifeng`.`tp_member_register_info` CHANGE `address` `address` VARCHAR(200) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL COMMENT '默认收货地址', ADD COLUMN `allshopaddress` VARCHAR(2000) DEFAULT '' NOT NULL COMMENT '收货地址用于搜索' AFTER `address`; 
ALTER TABLE `renlaifeng`.`tp_member_register_info` ADD COLUMN `subscribetype` TINYINT(1) DEFAULT 0 NOT NULL COMMENT '公众号关注状态：1：关注；2:未关注；默认：0' AFTER `membertagsid`; 
//ranlaifeng 签到活动
ALTER TABLE `renlaifeng`.`tp_member_register_info` ADD COLUMN `checkindaysnum` TINYINT(5) DEFAULT 0 NOT NULL COMMENT '连续签到天数' AFTER `subscribetype`;
ALTER TABLE `renlaifeng`.`tp_member_vouchers` CHANGE `getvouchertype` `getvouchertype` TINYINT(1) DEFAULT 0 NOT NULL COMMENT '会员活动获得券的方式：1:会员触发活动，2；线下报名活动；3:抽奖活动赠券；4:会员升级卡类型送电子券；5：签到活动赠券；';
CREATE TABLE `tp_member_everyday_checkin_activities` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `adduid` int(11) NOT NULL DEFAULT '0' COMMENT '添加uid',
  `edituid` int(11) NOT NULL DEFAULT '0' COMMENT '编辑uid',
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  `shopsid` int(11) NOT NULL DEFAULT '0' COMMENT '门店id',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '活动标题',
  `isshow` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否启用：1：启用；2：否；',
  `url` varchar(400) NOT NULL DEFAULT '' COMMENT '关联网页',
  `shareimg` varchar(400) NOT NULL DEFAULT '' COMMENT '微信分享图片',
  `sharefriendstitle` varchar(100) NOT NULL DEFAULT '' COMMENT '朋友圈分享标题',
  `sharedes` varchar(200) NOT NULL DEFAULT '' COMMENT '分享描述',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8
CREATE TABLE `tp_member_everyday_checkin_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `adduid` int(11) NOT NULL DEFAULT '0' COMMENT '添加uid',
  `edituid` int(11) NOT NULL DEFAULT '0' COMMENT '编辑uid',
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  `shopsid` int(11) NOT NULL DEFAULT '0' COMMENT '门店id',
  `mid` int(11) NOT NULL DEFAULT '0' COMMENT '会员mid',
  `activitiesid` int(11) NOT NULL DEFAULT '0' COMMENT '关联签到活动id',
  `checkindaysnum` smallint(5) NOT NULL DEFAULT '0' COMMENT '联系签到天数',
  `issendprize` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否发送奖励：1：是；2：否；默认：0；',
  `day` varchar(20) NOT NULL DEFAULT '' COMMENT '签到日期：2014-12-09；默认：'''';',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8
CREATE TABLE `tp_member_everyday_checkin_set` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `adduid` int(11) NOT NULL DEFAULT '0' COMMENT '添加uid',
  `edituid` int(11) NOT NULL DEFAULT '0' COMMENT '编辑uid',
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  `shopsid` int(11) NOT NULL DEFAULT '0' COMMENT '门店id',
  `activitiesid` int(11) NOT NULL DEFAULT '0' COMMENT '关联签到活动id',
  `days` smallint(5) NOT NULL DEFAULT '0' COMMENT '连续签到天数设置',
  `rewardtype` tinyint(1) NOT NULL DEFAULT '0' COMMENT '奖励类型：1:赠经验值；2：赠积分；3：赠优惠；默认：0；',
  `expnum` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '奖励经验值',
  `intnum` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '奖励积分值',
  `vouchertype` tinyint(1) NOT NULL DEFAULT '0' COMMENT '券类型：1：优惠券，2：赠品券。',
  `vouchersid` int(10) NOT NULL DEFAULT '0' COMMENT '关联券id',
  `prefix` varchar(10) NOT NULL DEFAULT '' COMMENT '券号前缀',
  `rewardlimit` tinyint(1) NOT NULL DEFAULT '0' COMMENT '奖励领取限制：1;每人仅限领取一次 ;2:每人可无限领取;默认：0；',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8
//renlaifeng数字 跳动
CREATE TABLE `tp_system_num` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `adduid` int(11) NOT NULL DEFAULT '0' COMMENT '添加uid',
  `edituid` int(11) NOT NULL DEFAULT '0' COMMENT '编辑uid',
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  `shopsid` int(11) NOT NULL DEFAULT '0' COMMENT '分店id',
  `num` int(9) NOT NULL DEFAULT '0' COMMENT '数字',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8
//renlaifeng收获地址
CREATE TABLE `tp_member_shop_address` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `adduid` int(11) NOT NULL DEFAULT '0' COMMENT '添加uid',
  `edituid` int(11) NOT NULL DEFAULT '0' COMMENT '编辑uid',
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  `shopsid` int(11) NOT NULL DEFAULT '0' COMMENT '门店id',
  `mid` int(11) NOT NULL DEFAULT '0' COMMENT '会员id',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '姓名',
  `mobile` varchar(20) NOT NULL DEFAULT '' COMMENT '手机',
  `city` varchar(20) NOT NULL DEFAULT '' COMMENT '所在城市',
  `country` varchar(20) NOT NULL DEFAULT '' COMMENT '所在国家',
  `province` varchar(20) NOT NULL DEFAULT '' COMMENT '用户所在省份',
  `address` varchar(500) NOT NULL DEFAULT '' COMMENT '地址详情',
  `isdefault` tinyint(1) NOT NULL DEFAULT '2' COMMENT '是否是默认地址；1：是；2：否；默认：2；',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8

ALTER TABLE `renlaifeng`.`tp_member_register_info` CHANGE `note` `note` VARCHAR(1000) CHARSET utf8 COLLATE utf8_general_ci NULL COMMENT '管理备注'; 

//renlaifeng索引 提高速度
ALTER TABLE `renlaifeng`.`tp_home_list_link` ADD INDEX `companyidlistid` (`companyid`, `listid`); 
ALTER TABLE `renlaifeng`.`tp_home_info` ADD INDEX `comapnyidid` (`id`, `companyid`); 
ALTER TABLE `renlaifeng`.`tp_history_page_browsing` ADD INDEX `comapanyidpangelink` (`companyid`, `pagelink`); 

//人来风已更新商城模块
ALTER TABLE `renlaifeng`.`tp_company` ADD COLUMN `unifyfreight` DECIMAL(12,2) DEFAULT 0.00 NOT NULL COMMENT '统一运费设置' AFTER `password`; 
ALTER TABLE `renlaifeng`.`tp_company` ADD COLUMN `mallorderautoset` INT(11) DEFAULT 0 NOT NULL COMMENT '商城订单过期自动取消时间设置（分钟）' AFTER `unifyfreight`; 

//人来风已更新 ALTER TABLE `renlaifeng`.`tp_mall_order_info` ADD COLUMN `consigneename` VARCHAR(100) DEFAULT '' NOT NULL COMMENT '收货人姓名' AFTER `ordernote`, ADD COLUMN `consigneephone` VARCHAR(50) DEFAULT '' NOT NULL COMMENT '收货人电话' AFTER `consigneename`, CHANGE `consigneeinfo` `consigneeaddress` VARCHAR(500) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL COMMENT '收货人地址；赵总 13564088888 中国 上海市 上海市 详细地址';
//人来风 已更新
CREATE TABLE `tp_mall_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `adduid` int(11) NOT NULL DEFAULT '0' COMMENT '添加用户id',
  `edituid` int(11) NOT NULL DEFAULT '0' COMMENT '编辑用户id',
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  `shopsid` int(11) NOT NULL DEFAULT '0' COMMENT '分店id',
  `goodtype` tinyint(1) NOT NULL DEFAULT '0' COMMENT '商品类型：1：实物商品，2：虚拟商品；默认：0；',
  `title` varchar(200) NOT NULL DEFAULT '' COMMENT '网页标题',
  `tags` varchar(500) NOT NULL DEFAULT ',' COMMENT '商品tagsid，中间以，隔开。',
  `goodnum` varchar(20) NOT NULL DEFAULT '' COMMENT '商品编码',
  `info` longtext COMMENT '商品详情',
  `pricetype` tinyint(1) NOT NULL DEFAULT '0' COMMENT '定价策略：1：仅支持货币购买；2：仅支持积分购买；默认：0；',
  `originalprice` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '原价，仅用于显示',
  `saleprice` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '优惠后的售价',
  `isopenvipprice` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否开启vip折扣；1：开启；2;不开启；默认：0；',
  `intprice` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '积分价',
  `canbuynum` int(11) NOT NULL DEFAULT '0' COMMENT '每人限购数量；0：表示不限购',
  `weight` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '重量KG 单位；',
  `freighttype` tinyint(1) NOT NULL DEFAULT '0' COMMENT '运费设置：1：统一运费；2:包邮；3：运费模板；默认：0；',
  `freighttplid` int(11) NOT NULL DEFAULT '0' COMMENT '运费模板id;默认：0；',
  `vouchertype` tinyint(1) NOT NULL DEFAULT '0' COMMENT '虚拟关联优惠类型：1：优惠券；2：赠品券；3：充值卡；',
  `vouchersid` int(11) NOT NULL DEFAULT '0' COMMENT '虚拟关联券id',
  `stockamount` int(11) NOT NULL DEFAULT '0' COMMENT '虚拟商品库存数量',
  `sort` smallint(5) NOT NULL DEFAULT '50' COMMENT '商品排序：默认：50；',
  `salenum` int(11) NOT NULL DEFAULT '0' COMMENT '销量',
  `isoffshelves` tinyint(1) NOT NULL DEFAULT '0' COMMENT '商品是否下架：1：是；2：否；默认：0；',
  `issoldout` tinyint(1) NOT NULL DEFAULT '0' COMMENT '商品是否售罄：1：是；2：否；默认：0；',
  `viewnum` int(11) NOT NULL DEFAULT '0' COMMENT '商品浏览量',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8
CREATE TABLE `tp_mall_goods_favourite` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `adduid` int(11) NOT NULL DEFAULT '0' COMMENT '添加用户id',
  `edituid` int(11) NOT NULL DEFAULT '0' COMMENT '编辑用户id',
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  `shopsid` int(11) NOT NULL DEFAULT '0' COMMENT '分店id',
  `mid` int(11) NOT NULL DEFAULT '0' COMMENT 'mid',
  `goodid` varchar(50) NOT NULL DEFAULT '0' COMMENT '商品id',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8

CREATE TABLE `tp_mall_goods_pics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `adduid` int(11) NOT NULL DEFAULT '0' COMMENT '添加用户id',
  `edituid` int(11) NOT NULL DEFAULT '0' COMMENT '编辑用户id',
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  `shopsid` int(11) NOT NULL DEFAULT '0' COMMENT '分店id',
  `goodid` int(11) NOT NULL DEFAULT '0' COMMENT '商品id',
  `pic` varchar(400) NOT NULL DEFAULT '' COMMENT '图片地址',
  `sort` tinyint(5) NOT NULL DEFAULT '50' COMMENT '排序',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8

CREATE TABLE `tp_mall_goods_rank_discount` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `adduid` int(11) NOT NULL DEFAULT '0' COMMENT '添加uid',
  `edituid` int(11) NOT NULL DEFAULT '0' COMMENT '编辑uid',
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  `shopsid` int(11) NOT NULL DEFAULT '0' COMMENT '门店id',
  `rankid` int(11) NOT NULL DEFAULT '0' COMMENT '会员等级id(唯一)',
  `goodsid` int(11) NOT NULL DEFAULT '0' COMMENT '商品id(唯一)',
  `vipdiscount` decimal(3,2) NOT NULL DEFAULT '0.00' COMMENT '会员折扣',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8
CREATE TABLE `tp_mall_goods_sku` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `adduid` int(11) NOT NULL DEFAULT '0' COMMENT '添加用户id',
  `edituid` int(11) NOT NULL DEFAULT '0' COMMENT '编辑用户id',
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  `shopsid` int(11) NOT NULL DEFAULT '0' COMMENT '分店id',
  `goodid` int(11) NOT NULL DEFAULT '0' COMMENT '商品id',
  `name` varchar(200) NOT NULL DEFAULT '' COMMENT '参数名称',
  `sort` tinyint(5) NOT NULL DEFAULT '50' COMMENT '排序',
  `stockamount` int(10) NOT NULL DEFAULT '0' COMMENT '库存',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8

CREATE TABLE `tp_mall_home` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `adduid` int(11) NOT NULL DEFAULT '0' COMMENT '添加用户id',
  `edituid` int(11) NOT NULL DEFAULT '0' COMMENT '编辑用户id',
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  `shopsid` int(11) NOT NULL DEFAULT '0' COMMENT '分店id',
  `title` varchar(200) NOT NULL DEFAULT '' COMMENT '标题',
  `info` varchar(500) NOT NULL DEFAULT '' COMMENT '详细信息',
  `backgroundImage` varchar(200) NOT NULL DEFAULT '' COMMENT '背景图片',
  `tplid` smallint(5) NOT NULL DEFAULT '0' COMMENT '模板id',
  `tplname` varchar(10) NOT NULL DEFAULT '' COMMENT '模板名称',
  `click` smallint(5) NOT NULL DEFAULT '0' COMMENT '触发量',
  `shareimg` varchar(400) NOT NULL DEFAULT '' COMMENT '微信分享图片',
  `sharefriendstitle` varchar(100) NOT NULL DEFAULT '' COMMENT '朋友圈分享标题',
  `sharedes` varchar(100) NOT NULL DEFAULT '' COMMENT '分享描述',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8

CREATE TABLE `tp_mall_home_channel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司Id',
  `shopsid` int(11) NOT NULL DEFAULT '0' COMMENT '分店Id',
  `adduid` int(11) NOT NULL DEFAULT '0' COMMENT '添加uid',
  `edituid` int(11) NOT NULL DEFAULT '0' COMMENT '用户uid',
  `homeid` int(11) NOT NULL DEFAULT '0' COMMENT '微官网id',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '标题',
  `info` varchar(200) NOT NULL DEFAULT '' COMMENT '摘要',
  `sort` smallint(5) NOT NULL DEFAULT '50' COMMENT '显示顺序',
  `img` varchar(200) NOT NULL DEFAULT '' COMMENT '图片',
  `url` varchar(200) NOT NULL DEFAULT '' COMMENT '链接',
  `icon` varchar(50) NOT NULL DEFAULT '' COMMENT '分类icon',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '修改时间',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8

CREATE TABLE `tp_mall_home_flash` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `adduid` int(11) NOT NULL DEFAULT '0' COMMENT '添加uid',
  `edituid` int(11) NOT NULL DEFAULT '0' COMMENT '编辑uid',
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  `shopsid` int(11) NOT NULL DEFAULT '0' COMMENT '分店id',
  `homeid` int(11) NOT NULL DEFAULT '0' COMMENT '微官网id',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '名称',
  `img` varchar(200) NOT NULL DEFAULT '' COMMENT '图片地址',
  `url` varchar(200) NOT NULL DEFAULT '' COMMENT '跳转地址',
  `sort` smallint(5) NOT NULL DEFAULT '50' COMMENT '排序',
  `isshow` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否显示 1：启用；0：禁用；默认：0',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='3g网站头部flash'

CREATE TABLE `tp_mall_order_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `adduid` int(11) NOT NULL DEFAULT '0' COMMENT '添加用户id',
  `edituid` int(11) NOT NULL DEFAULT '0' COMMENT '编辑用户id',
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  `shopsid` int(11) NOT NULL DEFAULT '0' COMMENT '分店id',
  `mid` int(11) NOT NULL DEFAULT '0' COMMENT 'mid',
  `orderid` varchar(50) NOT NULL DEFAULT '0' COMMENT '订单编号 关联mall_order_info  orderid',
  `goodtype` tinyint(1) NOT NULL DEFAULT '0' COMMENT '商品类型：1：实物商品，2：虚拟商品；默认：0；',
  `vouchersid` varchar(50) NOT NULL DEFAULT '0' COMMENT '虚拟关联券id',
  `pricetype` tinyint(1) NOT NULL DEFAULT '0' COMMENT '定价策略：1：仅支持货币购买；2：仅支持积分购买；默认：0；',
  `goodid` varchar(50) NOT NULL DEFAULT '0' COMMENT '商品id',
  `goodname` varchar(200) NOT NULL DEFAULT '' COMMENT '商品名称',
  `goodpic` varchar(400) NOT NULL DEFAULT '' COMMENT '商品缩略图',
  `goodprice` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '商品价格',
  `goodint` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '商品积分价',
  `goodnum` int(11) NOT NULL DEFAULT '0' COMMENT '商品数量',
  `goodskuid` int(11) NOT NULL DEFAULT '0' COMMENT '商品skuid',
  `goodweight` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '商品重量',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8
CREATE TABLE `tp_mall_shopping_cart` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  `mid` int(11) NOT NULL DEFAULT '0' COMMENT 'mid',
  `goodid` varchar(50) NOT NULL DEFAULT '0' COMMENT '商品id',
  `goodskuid` varchar(50) NOT NULL DEFAULT '0' COMMENT '商品skuid',
  `goodnum` int(11) NOT NULL DEFAULT '0' COMMENT '商品数量',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8

CREATE TABLE `tp_mall_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `adduid` int(11) NOT NULL DEFAULT '0' COMMENT '添加用户id',
  `edituid` int(11) NOT NULL DEFAULT '0' COMMENT '编辑用户id',
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  `shopsid` int(11) NOT NULL DEFAULT '0' COMMENT '分店id',
  `name` varchar(200) NOT NULL DEFAULT '' COMMENT 'tag 名称',
  `title` varchar(200) NOT NULL DEFAULT '' COMMENT 'tag 网页标题',
  `tplid` smallint(5) NOT NULL DEFAULT '0' COMMENT '模板id',
  `tplname` varchar(10) NOT NULL DEFAULT '' COMMENT '模板名称',
  `backgroundImage` varchar(200) NOT NULL DEFAULT '' COMMENT '背景图片',
  `ordertype` tinyint(1) NOT NULL DEFAULT '0' COMMENT '排序类型：1:最新排序：售卖中的全部商品，以商品更新时间动态排序；2：最热排序：售卖中的全部商品，系统将按商品页阅读数动态排序；3：最旺排序：售卖中的全部商品，系统将按商品销量动态排序；4：自定义排序；',
  `shareimg` varchar(400) NOT NULL DEFAULT '' COMMENT '微信分享图片',
  `sharefriendstitle` varchar(100) NOT NULL DEFAULT '' COMMENT '朋友圈分享标题',
  `sharedes` varchar(100) NOT NULL DEFAULT '' COMMENT '分享描述',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8


CREATE TABLE `tp_mall_tags_flash` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `adduid` int(11) NOT NULL DEFAULT '0' COMMENT '添加uid',
  `edituid` int(11) NOT NULL DEFAULT '0' COMMENT '编辑uid',
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  `shopsid` int(11) NOT NULL DEFAULT '0' COMMENT '分店id',
  `tagid` int(11) NOT NULL DEFAULT '0' COMMENT 'tagid',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '名称',
  `img` varchar(200) NOT NULL DEFAULT '' COMMENT '图片地址',
  `url` varchar(200) NOT NULL DEFAULT '' COMMENT '跳转地址',
  `sort` smallint(5) NOT NULL DEFAULT '50' COMMENT '排序',
  `isshow` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否显示 1：启用；0：禁用；默认：0',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='3g网站头部flash'

CREATE TABLE `tp_mall_tags_goods_link` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `adduid` int(11) NOT NULL DEFAULT '0' COMMENT '添加用户id',
  `edituid` int(11) NOT NULL DEFAULT '0' COMMENT '编辑用户id',
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  `shopsid` int(11) NOT NULL DEFAULT '0' COMMENT '分店id',
  `tagid` int(11) NOT NULL DEFAULT '0' COMMENT '标签id',
  `goodid` int(11) NOT NULL DEFAULT '0' COMMENT '商品id',
  `sort` smallint(5) NOT NULL DEFAULT '50' COMMENT '商品排序',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8

CREATE TABLE `tp_mall_tags_search` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `adduid` int(11) NOT NULL DEFAULT '0' COMMENT '添加用户id',
  `edituid` int(11) NOT NULL DEFAULT '0' COMMENT '编辑用户id',
  `companyid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  `shopsid` int(11) NOT NULL DEFAULT '0' COMMENT '分店id',
  `tplid` smallint(5) NOT NULL DEFAULT '0' COMMENT '模板id',
  `tplname` varchar(10) NOT NULL DEFAULT '' COMMENT '模板名称',
  `ordertype` tinyint(1) NOT NULL DEFAULT '0' COMMENT '排序类型：1:最新排序：售卖中的全部商品，以商品更新时间动态排序；2：最热排序：售卖中的全部商品，系统将按商品页阅读数动态排序；3：最旺排序：售卖中的全部商品，系统将按商品销量动态排序；',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8
insert  into `tp_system_permissions_list`(`id`,`name`,`desc`,`parentid`,`sort`,`isshow`,`updatetime`,`createtime`) values (138,'首页-中控台','',0,49,1,1421899844,1421899832);
//已更新
ALTER TABLE `renlaifeng`.`tp_mall_order_info` ADD COLUMN `ordertitle` VARCHAR(200) DEFAULT '' NOT NULL COMMENT '订单标题，用于微信支付。' AFTER `borderid`; 
ALTER TABLE `renlaifeng`.`tp_company` CHANGE `mallorderautoset` `mallorderautoset` INT(11) DEFAULT 0 NOT NULL COMMENT '商城订单过期自动取消时间设置（小时）'; 
ALTER TABLE `renlaifeng`.`tp_member_vouchers` CHANGE `getvouchertype` `getvouchertype` TINYINT(1) DEFAULT 0 NOT NULL COMMENT '会员活动获得券的方式：1:会员触发活动，2；线下报名活动；3:抽奖活动赠券；4:会员升级卡类型送电子券；5：签到活动赠券；6:在线商城购买；';
ALTER TABLE `renlaifeng`.`tp_mall_goods` ADD COLUMN `prefix` VARCHAR(20) DEFAULT '' NOT NULL COMMENT '虚拟券前缀' AFTER `vouchersid`;
ALTER TABLE `renlaifeng`.`tp_mall_order_goods` ADD COLUMN `prefix` VARCHAR(20) DEFAULT '' NOT NULL COMMENT '虚拟券前缀' AFTER `vouchersid`; 
ALTER TABLE `renlaifeng`.`tp_system_permissions_list` ADD COLUMN `isedit` TINYINT(1) DEFAULT 1 NOT NULL COMMENT '是否可编辑：1：是；2：否；默认：1；' AFTER `isshow`; 
//已更新实物商品 、虚拟商品
ALTER TABLE  `tp_mall_order_info` ADD COLUMN `goodtype` TINYINT(1) DEFAULT 0 NOT NULL COMMENT '订单商品类型：1：实物商品；2：虚拟商品；默认：0；' AFTER `ordertitle`; 
ALTER TABLE `tp_mall_order_info` CHANGE `orderstatus` `orderstatus` TINYINT(1) DEFAULT 0 NOT NULL COMMENT '1:待付款；2：待发货；3：配送中；4：已签收；5：已取消；6：电子券已发送；默认：0；';