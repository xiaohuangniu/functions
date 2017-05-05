<?php
// +----------------------------------------------------------------------
// | 小黄牛MVC框架 - Info 插件处理
// +----------------------------------------------------------------------
// | Copyright (c) 2016 www.junphp.com 
// +----------------------------------------------------------------------
// | Author: 冯俊豪 - 小黄牛 <1731223728@qq.com>
// +----------------------------------------------------------------------

header("Content-type:text/html;charset=utf-8");//设置页面编码
//引入Hooks核心类
include './Hooks.class.php';
$plugin=new Hooks();
//执行插件初始化
$plugin->hook();
//钩子名
$hook = $_GET['a'];
//插件名
$name = $_GET['b'];
//处理方法
$type = $_GET['c'];
//获得插件对应的介绍信息
$url  = $plugin->root_dir.$name.'/'.$plugin->config.'.php';
$dir  = include $url;
$str  = file_get_contents($url);
$pattern = '/[\'"]type[\'"]\s*=>\s*[\'"]([\s\S]*?)[\'"],/'; 
//处理分类
switch ($type) {
	case 'add'://安装
		$A = $plugin->trigger($hook,'','Add');//安装
		if($A){
			$str = preg_replace($pattern,"'type' => '2',", $str);
			//修改插件状态
			$res = file_put_contents($url,$str);
		}else{$res = false;}
	break;
	case 'del'://卸载
		$A = $plugin->trigger($hook,'','Del');//卸载
		if($A){
			$str = preg_replace($pattern,"'type' => '1',", $str);
			//修改插件状态
			$res = file_put_contents($url,$str);
		}else{$res = false;}
	break;
	case 'jin'://禁用
		$str = preg_replace($pattern,"'type' => '3',", $str);
		//修改插件状态
		$res = file_put_contents($url,$str);
	break;
	case 'qi'://启用
		$str = preg_replace($pattern,"'type' => '1',", $str);
		//修改插件状态
		$res = file_put_contents($url,$str);
	break; 
	default:
		exit('非法操作处理!');
}
if($res){
	header("refresh:2;url=/Index.php");
	echo '执行成功，请稍等...<br>两秒后自动跳转';
}else{
	header("refresh:2;url=/Index.php");
	echo '执行失败，请稍等...<br>两秒后自动跳转';
}