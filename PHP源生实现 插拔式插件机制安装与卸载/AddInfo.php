<?php
// +----------------------------------------------------------------------
// | 小黄牛MVC框架 - 添加新插件
// +----------------------------------------------------------------------
// | Copyright (c) 2016 www.junphp.com 
// +----------------------------------------------------------------------
// | Author: 冯俊豪 - 小黄牛 <1731223728@qq.com>
// +----------------------------------------------------------------------
header("Content-type:text/html;charset=utf-8");//设置页面编码
//引入Hooks核心类
include './Hooks.class.php';
$plugin=new Hooks();
//获得插件的所有配置信息
$info = $plugin->ont();
$name    = $_POST['name'];//插件名 不带.class.php
$hook    = $_POST['hook'];//钩子名
$title   = $_POST['title'];//插件别名
$edition = $_POST['edition'];//版本
$content = $_POST['content'];//插件介绍
$author  = $_POST['author'];//开发者
$type    = 1;//插件状态     1=未安装   2=启用   3=禁用
$install = empty($_POST['install']) ? 2 : 1;//是否需要安装  1=需要    2=不需要
$peizhi  = empty($_POST['peizhi']) ? 2 : 1;//是否需要创建插件配置文件
$time    = date('Y-m-d h:i:s',time());//发布时间
foreach ($info as $value) {
	if ($value['name'] == $name) {//判断插件名是否存在
		header("refresh:2;url=/Add.php");
		exit('插件名已存在，请返回修改...<br>两秒后自动跳转');
	}else if($value['hook'] == $hook){//判断钩子名是否存在
		header("refresh:2;url=/Add.php");
		exit('钩子名已存在，请返回修改...<br>两秒后自动跳转');		
	}
}
//获得插件的根目录地址
$dir = $plugin->root_dir;
//创建目录，并设置权限
mkdir($dir.$name.'/');
chmod($dir.$name.'/', 0777);
//创建文件
#1.首先创建介绍文件
$txt = "<?php
// +----------------------------------------------------------------------
// | 小黄牛MVC框架 - '$name'插件的介绍信息
// +----------------------------------------------------------------------
// | Copyright (c) 2016 www.junphp.com 
// +----------------------------------------------------------------------
// | Author: 冯俊豪 - 小黄牛 <1731223728@qq.com>
// +----------------------------------------------------------------------
return array(
	'name'    => '$name',//插件名
	'hook'    => '$hook',//钩子名
	'title'   => '$title',//插件别名
	'edition' => '$edition',//版本
	'content' => '$content',//插件介绍
	'author'  => '$author',//开发者
	'type'    => '$type',//插件状态
	'install' => '$install',//是否需要安装
	'time'    => '$time',//发布时间
);"; 
$fopen = fopen($dir.$name.'/config.php','wb');//新建文件
fputs($fopen,$txt);//向文件中写入内容; 
fclose($fopen);
#2.判断是否需要生成配置文件
if($peizhi === 1){
$txt = "<?php
// +----------------------------------------------------------------------
// | 小黄牛MVC框架 - '$name'插件的配置文件
// +----------------------------------------------------------------------
// | Copyright (c) 2016 www.junphp.com 
// +----------------------------------------------------------------------
// | Author: 冯俊豪 - 小黄牛 <1731223728@qq.com>
// +----------------------------------------------------------------------
return array(
	//'键名' => '键值',
);"; 
	$fopen = fopen($dir.$name.'/Parameter.php','wb');//新建文件
	fputs($fopen,$txt);//向文件中写入内容; 
	fclose($fopen);
}
#3.创建插件文件，并判断是否需要配置文件，以及插件安装
if($peizhi===1 && $install===1){
$txt = 'class '.$name.' {
	//插件配置参数,一维数组
	private $config;
	//构造方法
	public function __construct(&$pluginManager) {
        //注册这个插件
        //第一个参数是钩子的名称
        //第二个参数是pluginManager的引用
        //第三个是插件所执行的方法
        $pluginManager->register("'.$hook.'", $this,"Yun");
		//获得配置参数
		$url = "'.$dir.$name.'/Parameter.php";
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
}';
}else if($peizhi===1){
$txt = 'class '.$name.' {
	//插件配置参数,一维数组
	private $config;
	//构造方法
	public function __construct(&$pluginManager) {
        //注册这个插件
        //第一个参数是钩子的名称
        //第二个参数是pluginManager的引用
        //第三个是插件所执行的方法
        $pluginManager->register("'.$hook.'", $this,"Yun");
		//获得配置参数
		$url = "'.$dir.$name.'/Parameter.php";
		if(file_exists($url)){
			$this->config = include $url;
		}
    }
 	//插件默认执行的方法
    public function Yun() {

    }
}';	
}else{
$txt = 'class '.$name.' {
	//构造方法
	public function __construct(&$pluginManager) {
        //注册这个插件
        //第一个参数是钩子的名称
        //第二个参数是pluginManager的引用
        //第三个是插件所执行的方法
        $pluginManager->register("'.$hook.'", $this,"Yun");
    }
 	//插件默认执行的方法
    public function Yun() {

    }
}';		
}
$header = '<?php
// +----------------------------------------------------------------------
// | 小黄牛MVC框架 - '.$name.'插件 - '.$title.'
// +----------------------------------------------------------------------
// | Copyright (c) 2016 www.junphp.com 
// +----------------------------------------------------------------------
// | Author: '.$author.' - JunPHP框架插件机制 <1731223728@qq.com>
// +---------------------------------------------------------------------
';
$fopen = fopen($dir.$name.'/'.$name.'.class.php','wb');//新建文件
fputs($fopen,$header.$txt);//向文件中写入内容; 
fclose($fopen);

header("refresh:2;url=/Index.php");
exit('插件创建成功...<br>两秒后自动跳转');		