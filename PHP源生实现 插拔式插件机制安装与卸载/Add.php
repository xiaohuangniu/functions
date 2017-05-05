<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>添加插件|仿OneThink</title>
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

            
	<div class="main-title cf">
		<h2>插件快速创建</h2>
	</div>
	<!-- 表单 -->
	<form id="form" action="AddInfo.php" method="post" class="form-horizontal doc-modal-form">
		<div class="form-item">
			<label class="item-label"><span class="must">*</span>插件名 <span class="check-tips">（也是插件的目录名,唯一）</span></label>
			<div class="controls">
				<input type="text" class="text input-large" name="name" value="">
			</div>
		</div>
		<div class="form-item">
			<label class="item-label">钩子名<span class="check-tips">（请输入钩子名,唯一）</span></label>
			<div class="controls">
				<input type="text" class="text input-large" name="hook" value="">
			</div>
		</div>
        <div class="form-item">
			<label class="item-label">插件别名<span class="check-tips">（我是一个插件）</span></label>
			<div class="controls">
				<input type="text" class="text input-large" name="title" value="">
			</div>
		</div>
		<div class="form-item">
			<label class="item-label">版本<span class="check-tips">（请输入插件版本）</span></label>
			<div class="controls">
				<input type="text" class="text input-large" name="edition" value="V1.0">
			</div>
		</div>
		<div class="form-item">
			<label class="item-label">作者<span class="check-tips">（请输入插件作者）</span></label>
			<div class="controls">
				<input type="text" class="text input-large" name="author" value="JunPHP官方">
			</div>
		</div>
		<div class="form-item">
			<label class="item-label">描述<span class="check-tips">（请输入描述）</span></label>
			<div class="controls">
				<label class="textarea input-large">
					<textarea name="content">这是一个临时描述</textarea>
				</label>
			</div>
		</div>
		<div class="form-item">
			<label class="item-label">是否需要安装</label>
			<div class="controls">
				<label class="checkbox">
					<input name="install" type="checkbox" value="1" checked>
				</label>
			</div>
		</div>
		<div class="form-item">
			<label class="item-label">是否需要配置</label>
			<div class="controls">
				<label class="checkbox"><input name="peizhi" type="checkbox" value="1" checked></label>
			</div>
		</div>

		
		<div class="form-item">
			<button class="btn ajax-post_custom submit-btn" target-form="form-horizontal" id="submit">确 定</button>
			<button class="btn btn-return" onClick="javascript:history.back(-1);return false;">返 回</button>
		</div>
	</form>
    </div>
    
</div>

</body>
</html>