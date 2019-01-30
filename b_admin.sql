/*
Navicat MySQL Data Transfer

Source Server         : mysql
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : bool_wms

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2019-01-30 14:11:32
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for b_admin
-- ----------------------------
DROP TABLE IF EXISTS `b_admin`;
CREATE TABLE `b_admin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `username` varchar(16) NOT NULL COMMENT '用户名',
  `password` varchar(32) NOT NULL COMMENT '密码',
  `head_img` varchar(255) DEFAULT NULL COMMENT '头像',
  `nickname` varchar(40) DEFAULT NULL COMMENT '用户昵称',
  `phone` varchar(12) DEFAULT NULL COMMENT '手机',
  `email` varchar(40) DEFAULT NULL COMMENT '邮箱',
  `role_id` varchar(255) DEFAULT NULL COMMENT '角色id',
  `last_time` int(11) unsigned DEFAULT NULL COMMENT '最后登陆时间',
  `last_ip` varchar(40) DEFAULT NULL COMMENT '最后登陆ip',
  `create_time` int(11) unsigned NOT NULL DEFAULT '1320981071' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='管理员表';

-- ----------------------------
-- Records of b_admin
-- ----------------------------
INSERT INTO `b_admin` VALUES ('1', 'admin', '21232f297a57a5a743894a0e4a801fc3', 'https://avatars1.githubusercontent.com/u/37901036?v=4', '管理员', '17052850083', '81001985@qq.com', '2', '1548811532', '127.0.0.1', '1320981071');
INSERT INTO `b_admin` VALUES ('2', 'seho', '126dfa8c609fc04f3aa38c015a862fec', null, 'seho', '2222', '111@qq.com', null, '1540196808', '127.0.0.1', '1540193412');
INSERT INTO `b_admin` VALUES ('3', 'admin1231111', '0192023a7bbd73250516f069df18b500', null, '1111', '', '', '0', null, null, '1540540637');

-- ----------------------------
-- Table structure for b_admin_node
-- ----------------------------
DROP TABLE IF EXISTS `b_admin_node`;
CREATE TABLE `b_admin_node` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级id',
  `ico` varchar(20) DEFAULT NULL COMMENT 'ico图标',
  `name` varchar(20) NOT NULL COMMENT '节点名称',
  `controller` varchar(20) NOT NULL COMMENT '控制器',
  `action` varchar(20) NOT NULL DEFAULT '' COMMENT '方法',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `desc` varchar(200) NOT NULL COMMENT '备注',
  `create_time` int(11) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='节点表';

-- ----------------------------
-- Records of b_admin_node
-- ----------------------------
INSERT INTO `b_admin_node` VALUES ('1', '0', 'fa-home', '控制台', 'index', 'index', '0', '', '1540533340');
INSERT INTO `b_admin_node` VALUES ('2', '0', null, '权限管理', '', '', '0', '', '1540533340');
INSERT INTO `b_admin_node` VALUES ('3', '2', null, '管理员', 'admin', 'index', '0', '', '1540533340');
INSERT INTO `b_admin_node` VALUES ('4', '2', null, '角色', 'role', 'index', '0', '', '1540533340');
INSERT INTO `b_admin_node` VALUES ('6', '3', 'fa-plus', '添加', 'admin', 'create', '0', '', '1540533340');
INSERT INTO `b_admin_node` VALUES ('7', '3', '', '编辑', 'admin', 'edit', '0', '', '1540533340');

-- ----------------------------
-- Table structure for b_admin_oauth
-- ----------------------------
DROP TABLE IF EXISTS `b_admin_oauth`;
CREATE TABLE `b_admin_oauth` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `uid` int(11) unsigned NOT NULL COMMENT '用户id',
  `type` varchar(40) NOT NULL COMMENT '平台名称',
  `open_id` varchar(255) NOT NULL COMMENT '唯一id',
  `data` text NOT NULL COMMENT '返回数据',
  `url` varchar(255) NOT NULL COMMENT '平台个人网址',
  `add_time` int(11) unsigned NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='管理员第三方授权表';

-- ----------------------------
-- Records of b_admin_oauth
-- ----------------------------
INSERT INTO `b_admin_oauth` VALUES ('1', '1', 'Github', 'MDQ6VXNlcjM3OTAxMDM2', '{\"username\":\"admin\",\"password\":\"admin\",\"open_id\":\"MDQ6VXNlcjM3OTAxMDM2\",\"head_img\":\"https:\\/\\/avatars1.githubusercontent.com\\/u\\/37901036?v=4\",\"nickname\":\"\\u5e03\\u5c14\",\"location\":\"\\u5c71\\u4e1c-\\u4e34\\u6c82\",\"email\":\"\",\"bio\":\"qq:30024167\",\"url\":\"https:\\/\\/api.github.com\\/users\\/chenbool\",\"type\":\"Github\"}', 'https://api.github.com/users/chenbool', '1539740084');
INSERT INTO `b_admin_oauth` VALUES ('2', '1', 'Gitee', '5dcrRBBhvsHVCFznyDEU', '{\"username\":\"admin\",\"password\":\"admin\",\"open_id\":\"5dcrRBBhvsHVCFznyDEU\",\"head_img\":\"https:\\/\\/gitee.com\\/uploads\\/26\\/1126226_bool_admin.png?1523589304\",\"nickname\":\"bool\",\"location\":\"\",\"email\":\"\",\"phone\":\"17052850083\",\"bio\":\"\",\"url\":\"https:\\/\\/gitee.com\\/api\\/v5\\/users\\/bool_admin\",\"type\":\"Gitee\"}', 'https://gitee.com/api/v5/users/bool_admin', '1539751312');
INSERT INTO `b_admin_oauth` VALUES ('6', '1', 'Oschina', '2616840', '{\"username\":\"admin\",\"password\":\"admin\",\"open_id\":\"2616840\",\"head_img\":\"https:\\/\\/static.oschina.net\\/uploads\\/user\\/1308\\/2616840_50.jpg?t=1453261633000\",\"nickname\":\"\\u9648\\u58eb\\u9f99\",\"location\":\"\\u6c5f\\u82cf \\u82cf\\u5dde\",\"email\":\"81001985@qq.com\",\"phone\":\"\",\"bio\":\"\",\"url\":\"https:\\/\\/my.oschina.net\\/sloan521\",\"type\":\"Oschina\"}', 'https://my.oschina.net/sloan521', '1539755202');
INSERT INTO `b_admin_oauth` VALUES ('7', '1', 'Coding', '1840968', '{\"username\":\"admin\",\"password\":\"admin\",\"open_id\":\"1840968\",\"head_img\":\"https:\\/\\/coding.net\\/static\\/fruit_avatar\\/Fruit-14.png\",\"nickname\":\"bool1993\",\"location\":\"\",\"email\":\"\",\"phone\":\"\",\"bio\":\"\",\"url\":\"\",\"type\":\"Coding\"}', '', '1539757802');

-- ----------------------------
-- Table structure for b_admin_role
-- ----------------------------
DROP TABLE IF EXISTS `b_admin_role`;
CREATE TABLE `b_admin_role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级角色',
  `name` varchar(60) NOT NULL COMMENT '角色名称',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态 0正常 1禁用',
  `ids` varchar(255) DEFAULT NULL COMMENT '节点id',
  `desc` varchar(200) DEFAULT NULL COMMENT '备注',
  `create_time` int(11) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='角色表';

-- ----------------------------
-- Records of b_admin_role
-- ----------------------------
INSERT INTO `b_admin_role` VALUES ('1', '2', '角色', '0', '3,4,1,2,6,7,j2_1', '222', '1542093926');
INSERT INTO `b_admin_role` VALUES ('2', '3', '测试', '0', '3,4,1,2,6,7,j1_1', '', '1542093926');
INSERT INTO `b_admin_role` VALUES ('3', '0', '123', '0', '', '', '1542180791');

-- ----------------------------
-- Table structure for b_brand
-- ----------------------------
DROP TABLE IF EXISTS `b_brand`;
CREATE TABLE `b_brand` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) DEFAULT NULL COMMENT '名称',
  `tel` varchar(15) DEFAULT NULL COMMENT '电话',
  `url` varchar(200) DEFAULT NULL COMMENT '网址',
  `status` tinyint(1) unsigned DEFAULT '0' COMMENT '状态: 0正常 1禁用',
  `create_time` int(11) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='快递物流';

-- ----------------------------
-- Records of b_brand
-- ----------------------------
INSERT INTO `b_brand` VALUES ('1', '小米', '95338', 'http://www.sf-express.com', '0', '1548662771');
INSERT INTO `b_brand` VALUES ('2', '华为', '', '', '0', '1548723862');

-- ----------------------------
-- Table structure for b_cate
-- ----------------------------
DROP TABLE IF EXISTS `b_cate`;
CREATE TABLE `b_cate` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(11) unsigned DEFAULT NULL COMMENT '父级id',
  `name` varchar(20) NOT NULL COMMENT '分类名称',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态: 0正常 1禁用',
  `desc` varchar(200) DEFAULT NULL COMMENT '备注',
  `create_time` int(11) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='分类';

-- ----------------------------
-- Records of b_cate
-- ----------------------------
INSERT INTO `b_cate` VALUES ('1', '0', '测试', '0', null, '1544518700');
INSERT INTO `b_cate` VALUES ('2', '1', '测试2', '0', '', '1544519834');
INSERT INTO `b_cate` VALUES ('3', '1', '123', '0', '', '1544575809');

-- ----------------------------
-- Table structure for b_color
-- ----------------------------
DROP TABLE IF EXISTS `b_color`;
CREATE TABLE `b_color` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) DEFAULT NULL COMMENT '单位名称',
  `code` varchar(20) DEFAULT NULL COMMENT '颜色码',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态: 0正常 1禁用',
  `create_time` int(11) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='颜色';

-- ----------------------------
-- Records of b_color
-- ----------------------------
INSERT INTO `b_color` VALUES ('1', '黑色', '#808000', '0', '1548726184');

-- ----------------------------
-- Table structure for b_customer
-- ----------------------------
DROP TABLE IF EXISTS `b_customer`;
CREATE TABLE `b_customer` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) unsigned DEFAULT NULL COMMENT '客户类型，0 个人，1商家',
  `title` varchar(255) DEFAULT NULL COMMENT '客户名称',
  `contact` varchar(20) DEFAULT NULL COMMENT '联系人',
  `tel` varchar(20) DEFAULT NULL COMMENT '电话',
  `fax` varchar(20) DEFAULT NULL COMMENT '传真',
  `phone` varchar(12) NOT NULL COMMENT '手机号码',
  `email` varchar(40) DEFAULT NULL,
  `province` varchar(20) DEFAULT NULL COMMENT '省份',
  `city` varchar(20) DEFAULT NULL COMMENT '城市',
  `district` varchar(20) DEFAULT NULL COMMENT '县区',
  `street` varchar(200) DEFAULT NULL COMMENT '街道',
  `credit_id` varchar(50) DEFAULT NULL COMMENT '统一社会信用代码',
  `taxpayer_id` varchar(255) DEFAULT NULL COMMENT '纳税人识别号',
  `desc` varchar(200) DEFAULT NULL COMMENT '备注',
  `bank` varchar(20) DEFAULT NULL COMMENT '开户行',
  `bank_number` varchar(30) DEFAULT NULL COMMENT '开户银行卡号',
  `bank_address` varchar(200) DEFAULT NULL COMMENT '开户行地址',
  `status` tinyint(1) unsigned DEFAULT '0' COMMENT '状态: 0正常 1禁用',
  `create_time` int(11) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='客户管理';

-- ----------------------------
-- Records of b_customer
-- ----------------------------
INSERT INTO `b_customer` VALUES ('1', '1', '苏州智慧龙', '测试', '0512-5689107', '0512-5689107', '1111', 'test@bigzhl.com', '北京市', '北京城区', '朝阳区', '', '', '', '11111111', '中国银行', '', '', '0', '1542779833');
INSERT INTO `b_customer` VALUES ('2', '0', '', '111', '', '', '11', '111', '山东省', '临沂市', '兰山区', '', '', '', '', '中国银行', '', '', null, '1544493865');

-- ----------------------------
-- Table structure for b_depot
-- ----------------------------
DROP TABLE IF EXISTS `b_depot`;
CREATE TABLE `b_depot` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL COMMENT '仓库名称',
  `contact` varchar(20) NOT NULL COMMENT '联系人',
  `phone` varchar(13) DEFAULT NULL COMMENT '电话号码',
  `email` varchar(100) DEFAULT NULL COMMENT '邮箱',
  `province` varchar(20) DEFAULT NULL COMMENT '省份',
  `city` varchar(20) DEFAULT NULL COMMENT '城市',
  `district` varchar(20) DEFAULT NULL COMMENT '县区',
  `street` varchar(200) NOT NULL COMMENT '街道',
  `desc` varchar(200) DEFAULT NULL COMMENT '备注',
  `status` tinyint(1) unsigned DEFAULT '0' COMMENT '状态: 0正常 1禁用',
  `create_time` int(11) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='仓库表';

-- ----------------------------
-- Records of b_depot
-- ----------------------------
INSERT INTO `b_depot` VALUES ('1', '华北仓库', '布尔', '17052850085', '30024167@qq.com', '河北省', '石家庄市', '井陉矿区', '河北省石家庄市', '111', '0', '1544424818');
INSERT INTO `b_depot` VALUES ('2', '华东仓库', '华东', '111', 'admin@qq.com', '上海市', '上海城区', '黄浦区', '1111', 'beizhu', '0', '1544427134');

-- ----------------------------
-- Table structure for b_express
-- ----------------------------
DROP TABLE IF EXISTS `b_express`;
CREATE TABLE `b_express` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) DEFAULT NULL COMMENT '名称',
  `tel` varchar(15) DEFAULT NULL COMMENT '电话',
  `url` varchar(200) DEFAULT NULL COMMENT '网址',
  `status` tinyint(1) unsigned DEFAULT '0' COMMENT '状态: 0正常 1禁用',
  `create_time` int(11) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='快递物流';

-- ----------------------------
-- Records of b_express
-- ----------------------------
INSERT INTO `b_express` VALUES ('1', '顺丰快递', '95338', 'http://www.sf-express.com', '0', '1548662771');

-- ----------------------------
-- Table structure for b_location
-- ----------------------------
DROP TABLE IF EXISTS `b_location`;
CREATE TABLE `b_location` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `pid` int(11) unsigned DEFAULT NULL COMMENT '所属仓库',
  `name` varchar(20) DEFAULT NULL COMMENT '库位',
  `status` tinyint(1) unsigned NOT NULL COMMENT '状态: 0正常 1禁用',
  `shelve` varchar(255) NOT NULL DEFAULT '默认' COMMENT '货架',
  `desc` varchar(200) DEFAULT NULL COMMENT '备注',
  `create_time` int(11) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='库位管理';

-- ----------------------------
-- Records of b_location
-- ----------------------------
INSERT INTO `b_location` VALUES ('1', '1', 'A区', '0', 'A,B,C,D', '', '1544513047');
INSERT INTO `b_location` VALUES ('2', '1', 'B区', '0', '', '', '1548658491');

-- ----------------------------
-- Table structure for b_plug
-- ----------------------------
DROP TABLE IF EXISTS `b_plug`;
CREATE TABLE `b_plug` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL COMMENT '名称',
  `code` varchar(255) NOT NULL DEFAULT '',
  `author` varchar(255) NOT NULL,
  `type` varchar(20) NOT NULL,
  `status` tinyint(1) unsigned NOT NULL COMMENT '状态',
  `path` varchar(255) NOT NULL,
  `create_time` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COMMENT='插件表';

-- ----------------------------
-- Records of b_plug
-- ----------------------------
INSERT INTO `b_plug` VALUES ('6', 'GitHub', 'github', 'bool', 'oauth', '1', 'plug/oauth/github', '1540285355');
INSERT INTO `b_plug` VALUES ('19', 'Coding', 'coding', 'bool', 'oauth', '1', 'plug/oauth/coding', '1548405633');
INSERT INTO `b_plug` VALUES ('12', '码云', 'gitee', 'bool', 'oauth', '1', 'plug/oauth/gitee', '1542331978');
INSERT INTO `b_plug` VALUES ('13', '开源中国', 'oschina', 'bool', 'oauth', '1', 'plug/oauth/oschina', '1542332030');
INSERT INTO `b_plug` VALUES ('17', '微信支付', 'wepay', 'bool', 'pay', '1', 'plug/pay/wechat', '1542596813');
INSERT INTO `b_plug` VALUES ('18', '支付宝', 'alipay', 'bool', 'pay', '1', 'plug/pay/alipay', '1542596818');

-- ----------------------------
-- Table structure for b_product
-- ----------------------------
DROP TABLE IF EXISTS `b_product`;
CREATE TABLE `b_product` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `sn` varchar(100) NOT NULL COMMENT '商品编号',
  `unique_sn` varchar(100) DEFAULT NULL COMMENT '内部编号',
  `name` varchar(10) NOT NULL COMMENT '商品名称',
  `cate` int(11) unsigned DEFAULT NULL COMMENT '分类id',
  `brand` int(11) unsigned DEFAULT NULL COMMENT '品牌id',
  `model` varchar(50) DEFAULT NULL COMMENT '型号',
  `spec` varchar(100) DEFAULT NULL COMMENT '规格',
  `color` int(11) unsigned DEFAULT NULL COMMENT '颜色id',
  `price` decimal(10,2) unsigned NOT NULL COMMENT '默认价格',
  `unit` int(11) unsigned DEFAULT NULL COMMENT '单位id',
  `supplier` int(11) unsigned DEFAULT NULL COMMENT '默认供应商',
  `customer` int(11) unsigned DEFAULT NULL COMMENT '默认客户',
  `depot` int(11) unsigned DEFAULT NULL COMMENT '默认仓库',
  `location` int(11) unsigned DEFAULT NULL COMMENT '默认库位',
  `desc` varchar(200) DEFAULT NULL COMMENT '备注',
  `create_time` int(11) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='产品表';

-- ----------------------------
-- Records of b_product
-- ----------------------------
INSERT INTO `b_product` VALUES ('1', '20190129100738252', '', '小米8', '1', '1', 'mi8', '64G', '1', '10.00', '2', '2', '1', '1', '1', '', '1548727700');
INSERT INTO `b_product` VALUES ('3', '20190130021053962', '', '华为', '0', '2', '', '111', '1', '0.00', '1', '1', '1', '1', '0', '', '1548828664');

-- ----------------------------
-- Table structure for b_sale
-- ----------------------------
DROP TABLE IF EXISTS `b_sale`;
CREATE TABLE `b_sale` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sn` varchar(100) NOT NULL COMMENT '编号',
  `channel` varchar(100) DEFAULT NULL COMMENT '销售渠道',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态: 0正常 1禁用',
  `create_time` int(11) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='销售';

-- ----------------------------
-- Records of b_sale
-- ----------------------------

-- ----------------------------
-- Table structure for b_supplier
-- ----------------------------
DROP TABLE IF EXISTS `b_supplier`;
CREATE TABLE `b_supplier` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL COMMENT '客户名称',
  `contact` varchar(20) DEFAULT NULL COMMENT '联系人',
  `tel` varchar(20) DEFAULT NULL COMMENT '电话',
  `fax` varchar(20) DEFAULT NULL COMMENT '传真',
  `phone` varchar(12) NOT NULL COMMENT '手机号码',
  `email` varchar(40) DEFAULT NULL,
  `province` varchar(20) DEFAULT NULL COMMENT '省份',
  `city` varchar(20) DEFAULT NULL COMMENT '城市',
  `district` varchar(20) DEFAULT NULL COMMENT '县区',
  `street` varchar(200) DEFAULT NULL COMMENT '街道',
  `credit_id` varchar(50) DEFAULT NULL COMMENT '统一社会信用代码',
  `taxpayer_id` varchar(255) DEFAULT NULL COMMENT '纳税人识别号',
  `desc` varchar(200) DEFAULT NULL COMMENT '备注',
  `bank` varchar(20) DEFAULT NULL COMMENT '开户行',
  `bank_number` varchar(30) DEFAULT NULL COMMENT '开户银行卡号',
  `bank_address` varchar(200) DEFAULT NULL COMMENT '开户行地址',
  `status` tinyint(1) unsigned DEFAULT '0' COMMENT '状态: 0正常 1禁用',
  `create_time` int(11) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='供应商管理';

-- ----------------------------
-- Records of b_supplier
-- ----------------------------
INSERT INTO `b_supplier` VALUES ('1', '苏州智慧龙', '测试', '0512-5689107', '0512-5689107', '111', 'test@bigzhl.com', '', '', '', '', '', '', '11111111', '华夏银行', '', '', '0', '1542779833');
INSERT INTO `b_supplier` VALUES ('2', '11', '111', '', '', '11', '111', '山东省', '临沂市', '兰山区', '11111', '1111111111111111111', '111', '', '中国银行', '111', '111', '0', '1544493865');

-- ----------------------------
-- Table structure for b_unit
-- ----------------------------
DROP TABLE IF EXISTS `b_unit`;
CREATE TABLE `b_unit` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) DEFAULT NULL COMMENT '单位名称',
  `en_name` varchar(20) DEFAULT NULL COMMENT '英文名称',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态: 0正常 1禁用',
  `create_time` int(11) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='计量单位';

-- ----------------------------
-- Records of b_unit
-- ----------------------------
INSERT INTO `b_unit` VALUES ('1', '箱', null, '0', '1548667132');
INSERT INTO `b_unit` VALUES ('2', '台', null, '0', '1548721738');
INSERT INTO `b_unit` VALUES ('3', '个', null, '0', '1548721753');
