-- Adminer 4.0.3 MySQL dump

SET NAMES utf8;
SET foreign_key_checks = 0;
SET time_zone = '+08:00';
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `weixin_admin`;
CREATE TABLE `weixin_admin` (
  `user` text NOT NULL,
  `pwd` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `weixin_admin` (`user`, `pwd`) VALUES
('admin',	'admin');

DROP TABLE IF EXISTS `weixin_cookie`;
CREATE TABLE `weixin_cookie` (
  `cookie` text NOT NULL,
  `cookies` text NOT NULL,
  `token` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `weixin_cookie` (`cookie`, `cookies`, `token`, `id`) VALUES
('',	'',	0,	1);

DROP TABLE IF EXISTS `weixin_flag`;
CREATE TABLE `weixin_flag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `openid` varchar(255) NOT NULL,
  `flag` int(11) DEFAULT NULL,
  `vote` varchar(255) DEFAULT NULL,
  `nickname` varchar(255) DEFAULT NULL,
  `avatar` text,
  `content` text,
  `fakeid` varchar(255) DEFAULT NULL,
  `sex` varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `othid` int(11) NOT NULL DEFAULT '0',
  `cjstatu` tinyint(4) NOT NULL DEFAULT '0',
  `datetime` int(10) DEFAULT NULL,
  `verify` varchar(4) DEFAULT NULL,
  `phone` varchar(11) DEFAULT NULL,
  `shady` tinyint(2) NOT NULL DEFAULT '0',
  `cjgrade` tinyint(1) DEFAULT NULL,
  `fromtype` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `openid` (`openid`),
  KEY `openid_2` (`openid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `weixin_plugs`;
CREATE TABLE `weixin_plugs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `keyword` varchar(255) NOT NULL,
  `files` varchar(255) NOT NULL,
  `hasfiles` tinyint(1) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '0',
  `switch` tinyint(4) NOT NULL DEFAULT '0',
  `needword` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `weixin_plugs` (`id`, `name`, `title`, `description`, `keyword`, `files`, `hasfiles`, `status`, `switch`, `needword`) VALUES
(5,	'ppl',	'拍拍乐',	'【拍拍乐】参与拍拍乐请回复【~~[keyword]~~】	',	'拍拍乐',	'ppl.ext',	1,	4,	0,	1),
(7,	'shake',	'摇一摇',	'【摇一摇】参与摇一摇请回复【~~[keyword]~~】',	'摇一摇',	'shake.ext',	1,	0,	0,	1),
(6,	'vote',	'投票',	'【投票】参与投票请回复【~~[keyword]~~】',	'投票',	'vote.ext',	1,	0,	0,	1),
(1,	'cj',	'抽奖',	'【抽奖】发送消息上墙后将获得抽奖资格，回复【~~[keyword]~~】查看中奖状态',	'查询',	'',	0,	0,	0,	0),
(3,	'ddp',	'对对碰',	'【对对碰】发送消息上墙后将获得对对碰抽奖资格',	'对对碰',	'',	0,	0,	0,	0),
(2,	'qdq',	'签到墙',	'【签到】您已经成功签到！',	'签到墙',	'',	0,	0,	0,	0);

DROP TABLE IF EXISTS `weixin_shake_toshake`;
CREATE TABLE `weixin_shake_toshake` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `phone` varchar(255) NOT NULL,
  `wecha_id` varchar(255) NOT NULL,
  `point` int(11) NOT NULL,
  `avatar` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `wecha_id` (`wecha_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `weixin_vote`;
CREATE TABLE `weixin_vote` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `res` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `res` (`res`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `weixin_vote` (`id`, `name`, `res`) VALUES
(1,	'微信上墙',	0),
(2,	'图片上墙',	0),
(3,	'签到墙',	0),
(4,	'抽奖',	0),
(5,	'摇一摇',	0),
(6,	'对对碰',	0),
(7,	'拍拍乐',	0);

DROP TABLE IF EXISTS `weixin_wall`;
CREATE TABLE `weixin_wall` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `messageid` varchar(255) DEFAULT NULL,
  `fakeid` varchar(255) DEFAULT NULL,
  `num` int(11) DEFAULT NULL,
  `content` text,
  `nickname` text,
  `avatar` text,
  `ret` int(11) DEFAULT NULL,
  `fromtype` varchar(255) DEFAULT NULL,
  `image` text,
  `datetime` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `weixin_wall_config`;
CREATE TABLE `weixin_wall_config` (
  `huati` text NOT NULL,
  `huanying1` text NOT NULL,
  `huanying2` text NOT NULL,
  `success` text NOT NULL,
  `endtail` text NOT NULL,
  `acttitle` text NOT NULL,
  `isopen` int(1) NOT NULL,
  `endshake` int(11) NOT NULL,
  `show_num` int(11) NOT NULL,
  `shenghe` int(11) NOT NULL,
  `cjreplay` tinyint(4) NOT NULL DEFAULT '0',
  `timeinterval` int(3) NOT NULL,
  `shakeopen` tinyint(4) NOT NULL DEFAULT '1',
  `shakekeyword` varchar(255) NOT NULL,
  `voteopen` tinyint(4) NOT NULL DEFAULT '1',
  `votekeyword` varchar(255) NOT NULL,
  `votetitle` text NOT NULL,
  `votefresht` tinyint(4) NOT NULL,
  `fusionopen` tinyint(4) NOT NULL DEFAULT '0',
  `fusionkeyword` varchar(255) NOT NULL,
  `fusionurl` varchar(255) NOT NULL,
  `fusiontoken` varchar(255) NOT NULL,
  `circulation` tinyint(1) NOT NULL DEFAULT '0',
  `refreshtime` tinyint(2) NOT NULL,
  `voteshowway` tinyint(1) DEFAULT '1',
  `votecannum` varchar(255) DEFAULT '1',
  `black_word` text,
  `cj_switch` tinyint(1) NOT NULL DEFAULT '0',
  `ddp_switch` tinyint(1) NOT NULL DEFAULT '0',
  `weibo_switch` tinyint(1) NOT NULL DEFAULT '0',
  `weixin_switch` tinyint(1) NOT NULL DEFAULT '0',
  `web_root` varchar(255) NOT NULL,
  `screenpaw` varchar(255) NOT NULL,
  `shady_switch` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `weixin_wall_config` (`huati`, `huanying1`, `huanying2`, `success`, `endtail`, `acttitle`, `isopen`, `endshake`, `show_num`, `shenghe`, `cjreplay`, `timeinterval`, `shakeopen`, `shakekeyword`, `voteopen`, `votekeyword`, `votetitle`, `votefresht`, `fusionopen`, `fusionkeyword`, `fusionurl`, `fusiontoken`, `circulation`, `refreshtime`, `voteshowway`, `votecannum`, `black_word`, `cj_switch`, `ddp_switch`, `weibo_switch`, `weixin_switch`, `web_root`, `screenpaw`, `shady_switch`) VALUES
('',	'扫描二维码，按照提示回复即可上墙',	'赶快上墙体验吧！！！',	'你已经成功发送，等待审核通过即可上墙了',	'回复【重置】重新获取信息，回复【帮助】查看帮助信息！',	'激情狂欢夜',	1,	200,	9,	0,	0,	3,	1,	'摇一摇',	1,	'投票',	'你最喜欢微信墙的哪个功能？',	5,	0,	'',	'',	'',	0,	2,	3,	'1',	'操,sb,傻逼,艹,日你妈,干你妹,老子,bitch,婊子',	1,	1,	0,	0,	'http://xxxxxxxxxx',	'admin',	0);

DROP TABLE IF EXISTS `weixin_wall_num`;
CREATE TABLE `weixin_wall_num` (
  `num` int(11) NOT NULL,
  `lastmessageid` varchar(255) NOT NULL,
  PRIMARY KEY (`num`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `weixin_wall_num` (`num`, `lastmessageid`) VALUES
(1,	'0');

DROP TABLE IF EXISTS `weixin_weixin_config`;
CREATE TABLE `weixin_weixin_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nickname` varchar(255) NOT NULL,
  `dianplu_wxh` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `erweima` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `weixin_weixin_config` (`id`, `nickname`, `dianplu_wxh`, `username`, `password`, `erweima`) VALUES
(1,	'xxxxx',	'xxxxxxxx',	'xxxxxxx',	'xxxxxxxxx',	'http://xxxxxx');

-- 2015-03-29 15:47:00


DROP TABLE IF EXISTS `weixin_cj_shady`;
CREATE TABLE  `weixin_cj_shady` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `phone` varchar(11) NOT NULL,
  `grade` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `weixin_cj_shady`
--

INSERT INTO `weixin_cj_shady` (`id`, `phone`, `grade`) VALUES
(1, '13000000000', 3);


DROP TABLE IF EXISTS `weixin_weibo_config`;
CREATE TABLE  `weixin_weibo_config` (
  `id` int(11) NOT NULL,
  `nickname` varchar(255) NOT NULL,
  `access_token` varchar(255) NOT NULL,
  `folllow` tinyint(1) NOT NULL DEFAULT '1',
  `mention` tinyint(1) NOT NULL DEFAULT '0',
  `letter` tinyint(1) NOT NULL DEFAULT '1',
  `erweima` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `weixin_weibo_config`
--

INSERT INTO `weixin_weibo_config` (`id`, `nickname`, `access_token`, `folllow`, `mention`, `letter`, `erweima`) VALUES
(1, 'dianplu', 'dianplu', 1, 0, 1, 'http://shmily7080.sinaapp.com/wall/images/ma.jpg');

