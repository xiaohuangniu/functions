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
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>主键</td>
    <td>角色名</td>
    <td>描述</td>
    <td>状态</td>
    <td>父节点ID</td>
    <td>分配权限</td>
    <td>修改</td>
    <td>删除</td>
  </tr>
  <?php foreach ($rbac->listRole() as $k=>$v) {?>
  <!--具体使用请参考 Rbac类中的listRole方法-->
  <tr>
    <td><?php echo $v['id']?></td>
    <td><?php echo $v['title']?></td>
    <td><?php echo $v['remark']?></td>
    <td><?php echo $v['status']?></td>
    <td><?php echo $v['pid']?></td>
    <td><a href="updNR.php?id=<?php echo $v['id']?>">分配权限节点</a></td>
    <td><a href="updRole.php?id=<?php echo $v['id']?>">修改</a></td>
    <td><a href="deleteRole.php?id=<?php echo $v['id']?>">删除</a></td>
  </tr>
  <?php }?>
</table>

            
				
			

</body>
</html>
