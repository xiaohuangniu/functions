<?php
// +----------------------------------------------------------------------
// | 小黄牛MVC框架 - Index 插件目录
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
?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>插件列表|仿OneThink</title>
    <link href="/Public/favicon.ico" type="image/x-icon" rel="shortcut icon">
    <link rel="stylesheet" type="text/css" href="Public/css/base.css" media="all">
    <link rel="stylesheet" type="text/css" href="Public/css/common.css" media="all">
    <link rel="stylesheet" type="text/css" href="Public/css/module.css">
    <link rel="stylesheet" type="text/css" href="Public/css/style.css" media="all">
	<link rel="stylesheet" type="text/css" href="Public/css/default_color.css" media="all">
    
</head>
<body>
    <!-- 头部 -->
    <div class="header">
        <span class="logo"></span>
        <!-- 主导航 -->
        <ul class="main-nav">
			<li class="current"><a href="Index.php" >扩展</a></li>
        </ul>
        <!-- /主导航 -->
    </div>
    <!-- /头部 -->

    <!-- 边栏 -->
    <div class="sidebar">
        <!-- 子导航 -->
            <div id="subnav" class="subnav">
                    <h3><i class="icon icon-unfold"></i>扩展</h3>
                    <ul class="side-sub-menu">
                            <li><a class="item" href="Index.php">插件管理</a> </li>
                            <li><a class="item" href="Preview.php">测试预览</a></li> 
                    </ul> 
             </div>
        <!-- /子导航 -->
    </div>
    <!-- /边栏 -->

    <!-- 内容区 -->
    <div id="main-content">
        <div id="main" class="main">
            
        <!-- 标题栏 -->
        <div class="main-title">
            <h2>插件列表</h2>
        </div>
        <div>
            <a href="Add.php" class="btn">快速创建</a>
        </div>
    
        <div class="data-table table-striped">
            <table>
                <thead>
                    <tr>
                        <th>插件名</th>
                        <th>钩子名</th>
                        <th>插件别名</th>
                        <th>描述</th>
                        <th width="53px">状态</th>
                        <th width="43px">安装</th>
                        <th>作者</th>
                        <th width="43px">版本</th>
                        <th>时间</th>
                        <th width="94px">操作</th>
                    </tr>
                </thead>
                <tbody>
                	<?php foreach ($info as $k){?>
                    <tr>
                        <td><?php echo $k['name']?></td>
                        <td><?php echo $k['hook']?></td>
                        <td><?php echo $k['title']?></td>
                        <td><?php echo $k['content']?></td>
                        <td width="43px"><?php
						if ($k['install'] == '1'){
							switch ($k['type']) {
								case '1': echo '未安装';break;
								case '2': echo '启用';break;
								case '3': echo '禁用';break; 
							}
						}else{
							echo '无需安装';
						}
						?></td><!--状态-->
                        <td width="43px"><?php
						if ($k['install'] == '1'){
							echo 'Yes';
						}else{
							echo 'No';
						}
						?></td><!--是否需要安装-->
                        <td><?php echo $k['author']?></td>
                        <td width="43px"><?php echo $k['edition']?></td>
                        <td><?php echo $k['time']?></td>
                        <td><?php
							if ($k['install'] == '1'){
								switch ($k['type']) {
									case '1': echo '<a class="ajax-get" href="Info.php?a='.$k['hook'].'&b='.$k['name'].'&c=add">安装</a>';break;
									case '2': echo '<a class="ajax-get" href="Info.php?a='.$k['hook'].'&b='.$k['name'].'&c=jin">禁用</a>
													<a class="ajax-get" href="Info.php?a='.$k['hook'].'&b='.$k['name'].'&c=del">卸载</a>';break;
									case '3': echo '<a class="ajax-get" href="Info.php?a='.$k['hook'].'&b='.$k['name'].'&c=qi">启用</a>';break; 
								}
							}else{
								echo '<span class="ajax-get">NO</span>';//不能操作
							}
						?>
                        <a class="ajax-get" href="Del.php?a=<?php echo $k['name']?>">删除</a>
                        </td>
                    </tr>	
                    <?php }?>						
                </tbody>
            </table>
        </div>
    	</div>
	</div>
	<!-- 数据列表 -->
</body>
</html>