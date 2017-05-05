<?php
// +----------------------------------------------------------------------
// | 小黄牛MVC框架 - Hooks 插件机制的核心类 
// +----------------------------------------------------------------------
// | Copyright (c) 2016 www.junphp.com 
// +----------------------------------------------------------------------
// | Author: 冯俊豪 - 小黄牛 <1731223728@qq.com>
// +----------------------------------------------------------------------
class Hooks{
	//插件根目录
	public  $root_dir= './plugins/';
	//有关插件的信息的文件名
	public  $config  = 'config';
	//插件目录
	public  $_array = array();
	//所有插件的配置信息
	public  $can    = array();
	/** 
     * 监听已注册的插件 
     * 
     * @access private 
     * @var array 
    */ 
	private $_listeners = array(); 
	/** 
	 * 构造函数 
	 *  
	 * @access public 
	 * @return void 
	*/
	public function __construct() {
		//监听插件目录
		$this->directory($this->root_dir);
		//解析插件参数
		foreach ($this->_array as $key=>$value) {
			$arr = explode('/',$value);
			$plugins[$key]['directory'] = $arr[0];//插件的目录名
			$plugins[$key]['name']      = trim($arr[1],'.class.php');//插件的名称
		} 
		$this->_array = $plugins;
    }
	/**
	 * 初始化所有插件
	 * @access public 
	*/
	public function hook(){
		if ($this->_array) { 
            foreach ($this->_array as $plugin) {
				$type = $this->config($plugin['directory']);
				//只能激活已经安装的钩子 或者 申明不需要安装的钩子
				if ($type['type']!='3' || $type['install'] == '2') {
					//假定每个插件文件夹中包含一个(未知).class.php文件，它是插件的具体实现 
					if (@file_exists($this->root_dir.$plugin['directory'].'/'.$plugin['name'].'.class.php')) { 
						//初始化所有插件
						include_once($this->root_dir.$plugin['directory'].'/'.$plugin['name'].'.class.php'); 
						$class = $plugin['name']; 
						if (class_exists($class)) {
							//$this 是本类的引用
						   new $class($this); 
						}
					} 
				}
            } 
        } 
	#此处做些日志记录方面的东西 
	}
	/**
	 * 获得所有插件的配置信息
	 * @access public
     * @var array 
	*/
	public function ont(){
		foreach ($this->_array as $plugin) {
		//假定每个插件文件夹中包含一个(未知).class.php文件，它是插件的具体实现 
			$can[] = $this->config($plugin['directory']);
		}
		return $can;
	}
	
	/**
	 * 获得插件的名称和安装目录
	 * @access public 
	 * @dir_name 插件根目录
     * @var array 
	*/
	public function directory($dir_name){
		//抑制错误信息显示  便于自定义错误显示
		$dir_handle=opendir($dir_name);
		if (!$dir_handle) {
			die("目录打开错误！");
		}
		//文件名为'0'时，readdir返回FALSE，判断返回值是否不全等
		while (false!==($filename=readdir($dir_handle))) {
			if ($filename!='.' && $filename!='..') {
				//判断 是否为一个目录
				if (is_dir($dir_name.$filename)) {
					//$dir_flag标志目录树层次
					$this->directory($dir_name.$filename.'/');
				}else{ 
					if (strpos($filename,'.class.php') !== false) {
						$this->_array[] = ltrim($dir_name,$this->root_dir).$filename;
					}
				}			
			}
		}
		closedir($dir_handle);//关闭目录句柄
	}
	/**
	 * 获得钩子的介绍信息
	 * @access public
	 * @dir    插件目录名 
	 * @return string
	*/
	public function config($dir='demo'){
		$url = $this->root_dir.$dir.'/'.$this->config.'.php';
		if(file_exists($url)){
			$dir  =  include $url;
			$txt['name']    = $dir['name'];//插件名
			$txt['hook']    = $dir['hook'];//钩子名
			$txt['title']   = $dir['title'];//插件别名
			$txt['edition'] = $dir['edition'];//版本
			$txt['content'] = $dir['content'];//插件描述
			$txt['author']  = $dir['author'];//作者
			$txt['type']    = $dir['type'];//插件状态
			$txt['install'] = $dir['install'];//是否需要安装才能使用
			$txt['time']    = $dir['time'];//发布时间
			return $txt;
		}
		return false;
	}
	/** 
     * 注册需要监听的插件方法（钩子） 
     * 
     * @param string $hook 
     * @param object $reference 
     * @param string $method 
    */ 
	public function register($hook, &$reference, $method) { 
		//获取插件要实现的方法 
		$key = get_class($reference).'->'.$method; 
		//将插件的引用连同方法push进监听数组中 
		$this->_listeners[$hook][$key] = array(&$reference, $method); 
		#此处做些日志记录方面的东西 
	}
	/** 
     * 触发一个钩子 
     * 
     * @param string $hook 钩子的名称 
     * @param mixed $data 钩子的入参 
	 * @param mixed $type 动态调用钩子内的方法
     * @return mixed 
    */
	public function trigger($hook, $data='',$type='') { 
        $result = ''; 
        //查看要实现的钩子，是否在监听数组之中 
        if (isset($this->_listeners[$hook]) && is_array($this->_listeners[$hook]) && count($this->_listeners[$hook]) > 0) { 
            // 循环调用开始 
            foreach ($this->_listeners[$hook] as $listener) { 
                // 取出插件对象的引用和方法 
                $class  =& $listener[0]; 
                $method = $listener[1]; 
                if (method_exists($class,$method)) { 
					if(empty($type)){
                   		$result .= $class->$method($data); 
					}else{
						// 动态调用插件的方法 
                    	$result .= $class->$type($data); 
					}
                } 
            } 
        }
        #此处做些日志记录方面的东西 
        return $result; 
    }
}