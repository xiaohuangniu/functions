<?php
// +----------------------------------------------------------------------
// | 小黄牛MVC框架 - demo插件的介绍信息
// +----------------------------------------------------------------------
// | Copyright (c) 2016 www.junphp.com 
// +----------------------------------------------------------------------
// | Author: 冯俊豪 - 小黄牛 <1731223728@qq.com>
// +----------------------------------------------------------------------
return array(
	'name'    => 'demo',//插件名 不带.class.php
	'hook'    => 'demo',//钩子名
	'title'   => '这demo插件',//插件别名
	'edition' => 'V1.0',//版本
	'content' => '这是一个测试的插件',//插件介绍
	'author'  => 'JunPHP',//开发者
	'type'    => '1',//插件状态     1=未安装   2=启用   3=禁用
	'install' => '2',//是否需要安装  1=需要    2=不需要 
	'time'    => '2016-07-02',//发布时间
);