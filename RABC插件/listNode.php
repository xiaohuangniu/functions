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
    <td>显示名</td>
    <td>节点名</td>
    <td>状态</td>
    <td>类型</td>
    <td>父节点ID</td>
    <td>排序</td>
    <td>描述</td>
    <td>修改</td>
    <td>删除</td>
  </tr>
  <?php foreach ($rbac->listNode() as $k=>$v) {?>
  <!--具体分页方法请参考 Rbac类中的listNode方法-->
  <tr>
    <td><?php echo $v['id']?></td>
    <td><?php echo $v['title']?></td>
    <td><?php echo $v['name']?></td>
    <td><?php echo $v['status']?></td>
    <td><?php echo $v['level']?></td>
    <td><?php echo $v['pid']?></td>
    <td><?php echo $v['sort']?></td>
    <td><?php echo $v['remark']?></td>
    <td><a href="updNode.php?id=<?php echo $v['id']?>">修改</a></td>
    <td><a href="deleteNode.php?id=<?php echo $v['id']?>">删除</a></td>
  </tr>
  <?php }?>
</table>

            
				
			

</body>
</html>
