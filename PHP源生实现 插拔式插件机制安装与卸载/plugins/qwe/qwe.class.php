<?php
// +----------------------------------------------------------------------
// | 小黄牛MVC框架 - qwe插件 - qwe2
// +----------------------------------------------------------------------
// | Copyright (c) 2016 www.junphp.com 
// +----------------------------------------------------------------------
// | Author: JunPHP官方 - JunPHP框架插件机制 <1731223728@qq.com>
// +---------------------------------------------------------------------
class qwe {
	//插件配置参数,一维数组
	private $config;
	//构造方法
	public function __construct(&$pluginManager) {
        //注册这个插件
        //第一个参数是钩子的名称
        //第二个参数是pluginManager的引用
        //第三个是插件所执行的方法
        $pluginManager->register("qwe2", $this,"Yun");
		//获得配置参数
		$url = "./plugins/qwe/Parameter.php";
		if(file_exists($url)){
			$this->config = include $url;
		}
    }
	//插件安装方法
	public function Add(){
		//执行成功后需要返回true
		return true;
	}
	//插件卸载方法
	public function Del(){
		//执行成功后需要返回true
		return true;
	}
 	//插件默认执行的方法
    public function Yun() {

    }
}