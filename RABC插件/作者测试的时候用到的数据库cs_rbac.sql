-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2016-12-23 04:18:16
-- 服务器版本： 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `cs_rbac`
--

-- --------------------------------------------------------

--
-- 表的结构 `access`
--

CREATE TABLE IF NOT EXISTS `access` (
  `role_id` smallint(6) unsigned NOT NULL COMMENT '角色ID',
  `node_id` smallint(6) unsigned NOT NULL COMMENT '节点ID',
  `level` tinyint(1) NOT NULL COMMENT '节点表中的等级项',
  `pid` smallint(6) DEFAULT NULL COMMENT '节点表中的父ID项',
  KEY `groupId` (`role_id`),
  KEY `nodeId` (`node_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='权限分配表';

--
-- 转存表中的数据 `access`
--

INSERT INTO `access` (`role_id`, `node_id`, `level`, `pid`) VALUES
(7, 82, 1, 0),
(7, 84, 3, 83),
(7, 83, 2, 81),
(7, 88, 3, 87),
(7, 87, 2, 81),
(7, 81, 1, 0),
(7, 89, 2, 82),
(7, 90, 3, 89),
(7, 85, 2, 82),
(7, 86, 3, 85);

-- --------------------------------------------------------

--
-- 表的结构 `node`
--

CREATE TABLE IF NOT EXISTS `node` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(20) NOT NULL COMMENT '路由',
  `title` varchar(50) DEFAULT NULL COMMENT '显示名',
  `status` tinyint(1) DEFAULT '1' COMMENT '0禁用/1开启',
  `remark` varchar(255) DEFAULT NULL COMMENT '描述',
  `sort` smallint(6) unsigned DEFAULT '0' COMMENT '排序',
  `pid` smallint(6) unsigned NOT NULL COMMENT '父ID',
  `level` tinyint(1) unsigned NOT NULL COMMENT '级别;1/分组/2控制器/3操作方法',
  PRIMARY KEY (`id`),
  KEY `level` (`level`),
  KEY `pid` (`pid`),
  KEY `status` (`status`),
  KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='权限节点表' AUTO_INCREMENT=91 ;

--
-- 转存表中的数据 `node`
--

INSERT INTO `node` (`id`, `name`, `title`, `status`, `remark`, `sort`, `pid`, `level`) VALUES
(81, 'Admin', '后台入口', 1, '', 0, 0, 1),
(82, 'Home', '前台入口', 1, '', 0, 0, 1),
(83, 'Index', 'Index', 1, '', 0, 81, 2),
(84, 'Left', 'Left', 1, '', 0, 83, 3),
(85, 'Shop', 'Shop', 1, '', 0, 82, 2),
(86, 'List', 'List', 1, '', 0, 85, 3),
(87, 'Ceshi', 'Ceshi', 1, '', 0, 81, 2),
(88, 'Left', 'Left', 1, '', 0, 87, 3),
(89, 'Ceshi', 'Ceshi', 1, '', 0, 82, 2),
(90, 'Left', 'Left', 1, '', 0, 89, 3);

-- --------------------------------------------------------

--
-- 表的结构 `role`
--

CREATE TABLE IF NOT EXISTS `role` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `title` varchar(20) NOT NULL COMMENT '角色名',
  `pid` smallint(6) DEFAULT NULL COMMENT '父ID',
  `status` tinyint(1) unsigned DEFAULT '1' COMMENT '0禁用/1开启',
  `remark` varchar(255) DEFAULT NULL COMMENT '描述',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `status` (`status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='权限角色表' AUTO_INCREMENT=8 ;

--
-- 转存表中的数据 `role`
--

INSERT INTO `role` (`id`, `title`, `pid`, `status`, `remark`) VALUES
(6, '超级管理员', 0, 1, '系统内置超级管理员组，不受权限分配账号限制'),
(7, '测试的角色', 6, 1, '');

-- --------------------------------------------------------

--
-- 表的结构 `role_user`
--

CREATE TABLE IF NOT EXISTS `role_user` (
  `role_id` mediumint(9) unsigned DEFAULT NULL COMMENT '角色组ID',
  `user_id` char(32) DEFAULT NULL COMMENT '管理员ID',
  KEY `group_id` (`role_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户角色表';

--
-- 转存表中的数据 `role_user`
--

INSERT INTO `role_user` (`role_id`, `user_id`) VALUES
(7, '2'),
(6, '3');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
