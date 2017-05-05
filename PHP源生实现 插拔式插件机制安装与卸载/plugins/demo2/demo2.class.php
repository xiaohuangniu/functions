<?php
// +----------------------------------------------------------------------
// | 小黄牛MVC框架 - 这是一个插件的demo
// +----------------------------------------------------------------------
// | Copyright (c) 2016 www.junphp.com 
// +----------------------------------------------------------------------
// | Author: 冯俊豪 - 小黄牛 <1731223728@qq.com>
// +----------------------------------------------------------------------
class demo2 {
	//插件配置参数,一维数组
	private $config;
	//构造方法
	public function __construct(&$pluginManager) {
        //注册这个插件
        //第一个参数是钩子的名称
        //第二个参数是pluginManager的引用
        //第三个是插件所执行的方法
        $pluginManager->register('demo2', $this, 'Hello');
		//获得配置参数
		$url = './plugins/demo2/Parameter.php';
		if(file_exists($url)){
			$this->config = include $url;
		}
    }
	//插件安装方法
	public function Add(){
		//连接数据库
		$dbn = $this->config['DB_TYPE'].':host='.$this->config['DB_HOST'].';port='.$this->config['DB_PORT'].';dbname='.$this->config['DB_NAME'].';charset='.$this->config['DB_CHARSET'];
		$pdo = new PDO($dbn,$this->config['DB_USER'],$this->config['DB_PWD']);
		$sql = "CREATE TABLE IF NOT EXISTS `ftc_member` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `name` varchar(20) NOT NULL COMMENT '用户名',
  `pwd` varchar(20) NOT NULL COMMENT '密码',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
		$res = $pdo->exec($sql);//执行安装
		if ($res !== false) {
			return true;
		}
		return false;
	}
	//插件卸载方法
	public function Del(){
		$dbn = $this->config['DB_TYPE'].':host='.$this->config['DB_HOST'].';port='.$this->config['DB_PORT'].';dbname='.$this->config['DB_NAME'].';charset='.$this->config['DB_CHARSET'];
		$pdo = new PDO($dbn,$this->config['DB_USER'],$this->config['DB_PWD']);
		$res = $pdo->exec('drop table ftc_member');//执行卸载
		if ($res !== false) {
			return true;
		}
		return false;
	}
 	//DEMO2插件默认执行的方法
    public function Hello() {
        echo 'Hello 这是一个会员插件!<br/>';
    }

}