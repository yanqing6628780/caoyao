/*
Navicat MySQL Data Transfer

Source Server         : 本地mysql
Source Server Version : 50540
Source Host           : localhost:3306
Source Database       : caoyao

Target Server Type    : MYSQL
Target Server Version : 50540
File Encoding         : 65001

Date: 2015-12-29 20:42:03
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for admin_login_attempts
-- ----------------------------
DROP TABLE IF EXISTS `admin_login_attempts`;
CREATE TABLE `admin_login_attempts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(40) COLLATE utf8_bin NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=58 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Table structure for admin_permissions
-- ----------------------------
DROP TABLE IF EXISTS `admin_permissions`;
CREATE TABLE `admin_permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL COMMENT '角色ID',
  `data` text COLLATE utf8_bin COMMENT '角色权限数据',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='角色权限关系';

-- ----------------------------
-- Table structure for admin_roles
-- ----------------------------
DROP TABLE IF EXISTS `admin_roles`;
CREATE TABLE `admin_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT '父ID',
  `name` varchar(30) COLLATE utf8_bin NOT NULL COMMENT '角色名称',
  `cnname` varchar(100) COLLATE utf8_bin NOT NULL COMMENT '角色组中文名',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='角色表';

-- ----------------------------
-- Table structure for admin_users
-- ----------------------------
DROP TABLE IF EXISTS `admin_users`;
CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL DEFAULT '1' COMMENT '角色ID',
  `username` varchar(25) COLLATE utf8_bin NOT NULL COMMENT '用户名',
  `password` varchar(34) COLLATE utf8_bin NOT NULL COMMENT '密码',
  `email` varchar(100) COLLATE utf8_bin NOT NULL COMMENT 'email',
  `banned` tinyint(1) NOT NULL DEFAULT '0' COMMENT '禁止进入',
  `ban_reason` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT '禁止原因',
  `newpass` varchar(34) COLLATE utf8_bin DEFAULT NULL,
  `newpass_key` varchar(32) COLLATE utf8_bin DEFAULT NULL,
  `newpass_time` datetime DEFAULT NULL,
  `last_ip` varchar(40) COLLATE utf8_bin NOT NULL,
  `last_login` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='用户表';

-- ----------------------------
-- Table structure for admin_user_autologin
-- ----------------------------
DROP TABLE IF EXISTS `admin_user_autologin`;
CREATE TABLE `admin_user_autologin` (
  `key_id` char(32) COLLATE utf8_bin NOT NULL,
  `user_id` mediumint(8) NOT NULL DEFAULT '0',
  `user_agent` varchar(150) COLLATE utf8_bin NOT NULL,
  `last_ip` varchar(40) COLLATE utf8_bin NOT NULL,
  `last_login` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`key_id`,`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Table structure for admin_user_profile
-- ----------------------------
DROP TABLE IF EXISTS `admin_user_profile`;
CREATE TABLE `admin_user_profile` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `country` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `website` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `name` varchar(30) COLLATE utf8_bin NOT NULL COMMENT '名字',
  `mobile` varchar(20) COLLATE utf8_bin NOT NULL COMMENT '手机',
  `email` varchar(50) COLLATE utf8_bin NOT NULL,
  `qq` varchar(20) COLLATE utf8_bin NOT NULL,
  `photo` text CHARACTER SET utf8 COMMENT '图片',
  `lng` double DEFAULT NULL COMMENT '经度',
  `lat` double DEFAULT NULL COMMENT '纬度',
  `address` text COLLATE utf8_bin COMMENT '地址',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='用户资料';

-- ----------------------------
-- Table structure for admin_user_temp
-- ----------------------------
DROP TABLE IF EXISTS `admin_user_temp`;
CREATE TABLE `admin_user_temp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_bin NOT NULL COMMENT '用户名',
  `password` varchar(34) COLLATE utf8_bin NOT NULL COMMENT '密码',
  `email` varchar(100) COLLATE utf8_bin NOT NULL,
  `activation_key` varchar(50) COLLATE utf8_bin NOT NULL COMMENT '激活钥',
  `last_ip` varchar(40) COLLATE utf8_bin NOT NULL COMMENT '最近IP',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='临时用户表--新注册用户会先记录在此表，通过邮件或管理员激活放可进入系统';

-- ----------------------------
-- Table structure for appointment
-- ----------------------------
DROP TABLE IF EXISTS `appointment`;
CREATE TABLE `appointment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL COMMENT '预约人',
  `phone` varchar(32) NOT NULL COMMENT '预约人电话',
  `book_date` datetime NOT NULL COMMENT '预约时间',
  `doctor_id` int(11) unsigned NOT NULL COMMENT '医生id',
  `history` text,
  `is_show` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `doctor_forkey_id` (`doctor_id`),
  CONSTRAINT `doctor_forkey_id` FOREIGN KEY (`doctor_id`) REFERENCES `doctor` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ci_sessions
-- ----------------------------
DROP TABLE IF EXISTS `ci_sessions`;
CREATE TABLE `ci_sessions` (
  `session_id` varchar(40) COLLATE utf8_bin NOT NULL DEFAULT '0',
  `ip_address` varchar(16) COLLATE utf8_bin NOT NULL DEFAULT '0',
  `user_agent` varchar(150) COLLATE utf8_bin NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Table structure for doctor
-- ----------------------------
DROP TABLE IF EXISTS `doctor`;
CREATE TABLE `doctor` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL COMMENT '姓名',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for login_attempts
-- ----------------------------
DROP TABLE IF EXISTS `login_attempts`;
CREATE TABLE `login_attempts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(40) COLLATE utf8_bin NOT NULL,
  `login` varchar(50) COLLATE utf8_bin NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Table structure for permissions_code
-- ----------------------------
DROP TABLE IF EXISTS `permissions_code`;
CREATE TABLE `permissions_code` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `action_code` varchar(100) NOT NULL COMMENT '权限英文名称',
  `name` varchar(60) NOT NULL COMMENT '中文名称',
  `is_show` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否显示(0不显示1显示)',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='权限种类';

-- ----------------------------
-- Table structure for regions
-- ----------------------------
DROP TABLE IF EXISTS `regions`;
CREATE TABLE `regions` (
  `region_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `package` varchar(20) NOT NULL DEFAULT '',
  `p_region_id` int(10) unsigned DEFAULT NULL,
  `region_path` varchar(255) DEFAULT NULL,
  `region_grade` mediumint(8) unsigned DEFAULT NULL,
  `local_name` varchar(50) NOT NULL DEFAULT '',
  `en_name` varchar(50) DEFAULT NULL,
  `p_1` varchar(50) DEFAULT NULL,
  `p_2` varchar(50) DEFAULT NULL,
  `ordernum` mediumint(8) unsigned DEFAULT NULL,
  `disabled` enum('true','false') DEFAULT 'false',
  PRIMARY KEY (`region_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3267 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for sys_config
-- ----------------------------
DROP TABLE IF EXISTS `sys_config`;
CREATE TABLE `sys_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `label` varchar(192) DEFAULT NULL COMMENT '标识',
  `name` varchar(192) NOT NULL COMMENT '变量名',
  `value` varchar(192) NOT NULL COMMENT '值',
  `cat` varchar(192) NOT NULL COMMENT '分类',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8_bin NOT NULL,
  `password` varchar(255) COLLATE utf8_bin NOT NULL,
  `email` varchar(100) COLLATE utf8_bin NOT NULL,
  `activated` tinyint(1) NOT NULL DEFAULT '1',
  `banned` tinyint(1) NOT NULL DEFAULT '0',
  `ban_reason` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `new_password_key` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `new_password_requested` datetime DEFAULT NULL,
  `new_email` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `new_email_key` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `last_ip` varchar(40) COLLATE utf8_bin NOT NULL,
  `last_login` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Table structure for user_autologin
-- ----------------------------
DROP TABLE IF EXISTS `user_autologin`;
CREATE TABLE `user_autologin` (
  `key_id` char(32) COLLATE utf8_bin NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `user_agent` varchar(150) COLLATE utf8_bin NOT NULL,
  `last_ip` varchar(40) COLLATE utf8_bin NOT NULL,
  `last_login` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`key_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Table structure for user_profiles
-- ----------------------------
DROP TABLE IF EXISTS `user_profiles`;
CREATE TABLE `user_profiles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `country` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `website` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `name` varchar(50) COLLATE utf8_bin DEFAULT NULL COMMENT '门店name',
  `sex` tinyint(1) DEFAULT NULL COMMENT '性别',
  `email` varchar(20) CHARACTER SET utf8 NOT NULL,
  `phone` varchar(20) COLLATE utf8_bin DEFAULT NULL COMMENT '电话',
  `fax` varchar(64) COLLATE utf8_bin DEFAULT NULL COMMENT '传真',
  `address` varchar(64) CHARACTER SET utf8 DEFAULT NULL COMMENT '地址',
  `contact` varchar(32) CHARACTER SET utf8 DEFAULT NULL COMMENT '联系人',
  `job_title` varchar(32) CHARACTER SET utf8 DEFAULT NULL COMMENT '职称',
  `photo` text COLLATE utf8_bin COMMENT '照片',
  `credit` bigint(20) DEFAULT NULL COMMENT '信用',
  `branch_id` int(11) DEFAULT NULL COMMENT '分公司id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
