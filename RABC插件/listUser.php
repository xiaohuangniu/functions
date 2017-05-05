<?php
# 引入配置文件
include 'config.php';
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Rbac 权限插件DEMO - www.junphp.com</title>
</head>

<body>
<?php /*管理ID*/foreach ($rbac->listUser(3) as $v) {?>
	
    <!--顶级权限-->
	<?php echo $v['title'];?><br/>
    
    <!--1级权限-->
    <?php if(!empty($v['arr'])){foreach ($v['arr'] as $vv) {?>
    	----<?php echo $vv['title'];?><br/>
        
        <!--2级权限-->
		<?php if(!empty($vv['arr'])){foreach ($vv['arr'] as $vvv) {?>
            --------<?php echo $vvv['title'];?><br/>
            
            <!--3级权限-->
			<?php if(!empty($vvv['arr'])){foreach ($vvv['arr'] as $vvvv) {?>
                ------------<?php echo $vvvv['title'];?><br/>
            <?php }}?>
            
        <?php }}?> 
        
    <?php }}?> 
    
<?php }?>   			
			

</body>
</html>
