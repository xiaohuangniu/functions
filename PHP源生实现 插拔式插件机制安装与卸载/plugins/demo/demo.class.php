<?php
// +----------------------------------------------------------------------
// | 小黄牛MVC框架 - 这是一个插件的demo
// +----------------------------------------------------------------------
// | Copyright (c) 2016 www.junphp.com 
// +----------------------------------------------------------------------
// | Author: 冯俊豪 - 小黄牛 <1731223728@qq.com>
// +----------------------------------------------------------------------

/**
 *需要注意的几个默认规则：
 *    1. 插件类的名称必须是  插件名.class.php  = class 插件名
*/
class demo {
	public function __construct(&$pluginManager) {
        //注册这个插件
        //第一个参数是钩子的名称
        //第二个参数是pluginManager的引用
        //第三个是插件所默认执行的方法
        $pluginManager->register('demo', $this, 'Hello');
    }
	//我就是demo插件默认执行的方法，运行我您将获得 Hello JunPHP!
    public function Hello() {
        echo 'Hello JunPHP!';
	}
	//我将会被动态调用，暂时只能传递一个变量参数
	public function Ceshi($type=''){
		echo $type;
	}
}