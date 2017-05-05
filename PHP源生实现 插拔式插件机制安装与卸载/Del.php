<?php
// +----------------------------------------------------------------------
// | 小黄牛MVC框架 - 删除插件 - 无法回复
// +----------------------------------------------------------------------
// | Copyright (c) 2016 www.junphp.com 
// +----------------------------------------------------------------------
// | Author: 冯俊豪 - 小黄牛 <1731223728@qq.com>
// +----------------------------------------------------------------------
header("Content-type:text/html;charset=utf-8");//设置页面编码
//引入Hooks核心类
include './Hooks.class.php';
$plugin=new Hooks();
$dir = $plugin->root_dir;
$dir = $dir.$_GET['a'].'/';
//执行删除
function check($path){ 
    if(file_exists($path)){
        $dh = opendir($path);
        while($item = readdir($dh)){
            if('.' == $item || '..' == $item){
                continue;
            }else if(is_dir($path.'/'.$item)){        
                check($path.'/'.$item);            
            }else{
                unlink($path.'/'.$item);//删除文件
            }
        }
        closedir($dh);
        if (rmdir($path.'/'.$item)){//删除目录
			header("refresh:2;url=/Index.php");
			echo "删除插件 ".$_GET['a']." 成功<br>两秒后自动跳转";
        }
    }  
}
check($dir);
