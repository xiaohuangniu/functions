<?php
// +----------------------------------------------------------------------
// | 小黄牛MVC框架 - demo插件的介绍信息
// +----------------------------------------------------------------------
// | Copyright (c) 2016 www.junphp.com 
// +----------------------------------------------------------------------
// | Author: 冯俊豪 - 小黄牛 <1731223728@qq.com>
// +----------------------------------------------------------------------
return array(
	'name'    => 'demo2',//插件名 不带.class.php
	'hook'    => 'demo2',//钩子名
	'title'   => '这demo插件',//插件别名
	'edition' => 'V1.0',//版本
	'content' => '这是一个测试的插件2',//插件介绍
	'author'  => 'JunPHP',//开发者
	'type' => '2',//插件状态     1=未安装   2=已安装   3=禁用
	'install' => '1',
	'time'    => '2016-07-02',//发布时间
);