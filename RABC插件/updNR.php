<?php
# 引入配置文件
include 'config.php';

$id     = $_GET['id']; // 节点id
if(!empty($_POST)){
	$array = array(
		'id'   => $id,
		'data' => $_POST['id']
	);
	$rbac->updNR($array);
	
}else{
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Rbac 权限插件DEMO - www.junphp.com</title>
</head>

<body>
<form action="" method="post">
分配权限节点 : <br/>
<?php foreach ($rbac->selectNR() as $v) {?>
	
    <!--顶级权限-->
	<input name="id[]" type="checkbox" value="<?php echo $v['id']?>" <?php if($rbac->choiceNR($id,$v['id'])){ echo 'checked';}?>>
	<?php echo $v['title'];?><br/>
    
    <!--1级权限-->
    <?php if(!empty($v['arr'])){foreach ($v['arr'] as $vv) {?>
    	----<input name="id[]" type="checkbox" value="<?php echo $vv['id']?>" <?php if($rbac->choiceNR($id,$vv['id'])){ echo 'checked';}?>>
		<?php echo $vv['title'];?><br/>
        
        <!--2级权限-->
		<?php if(!empty($vv['arr'])){foreach ($vv['arr'] as $vvv) {?>
            --------<input name="id[]" type="checkbox" value="<?php echo $vvv['id']?>" <?php if($rbac->choiceNR($id,$vvv['id'])){ echo 'checked';}?>>
			<?php echo $vvv['title'];?><br/>
            
            <!--3级权限-->
			<?php if(!empty($vvv['arr'])){foreach ($vvv['arr'] as $vvvv) {?>
                ------------<input name="id[]" type="checkbox" value="<?php echo $vvvv['id']?>" <?php if($rbac->choiceNR($id,$vvvv['id'])){ echo 'checked';}?>>
				<?php echo $vvvv['title'];?><br/>
            <?php }}?>
            
        <?php }}?> 
        
    <?php }}?> 
    
<?php }?>     
    <br/>
    <input type="submit" value="添加">
</form>
				
			

</body>
</html>
<?php }?>
