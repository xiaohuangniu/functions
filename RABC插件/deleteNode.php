<?php
# 引入配置文件
include 'config.php';
$rbac->deleteNode($_GET['id']);
echo '权限节点删除成功,2秒后自动跳转……';
header("Refresh:2;url=listNode.php");
?>