<?php
# 引入配置文件
include 'config.php';

if(!empty($_POST)){
	# 添加权限节点
	$name   = $_POST['name'];  // 权限节点
	$title  = $_POST['title']; // 显示名称
	$status = $_POST['status'];// 状态
	$level  = $_POST['level']; // 类型
	$pid    = $_POST['pid'];   // 父ID
	$sort   = $_POST['sort'];  // 排序
	$remark = $_POST['remark'];// 描述
	
	# 参数
	$array = array(
		'name'   => $name,
		'title'  => $title,
		'status' => $status,
		'level'  => $level,
		'pid'    => $pid,
		'sort'   => $sort,
		'remark' => $remark
	);
	# 执行添加
	if ($rbac->addNode($array)) {
		echo '权限节点添加成功,2秒后自动跳转……';
		header("Refresh:2;url=listNode.php");
	}
	
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
	权限节点 : <input name="name" type="text" /> 英文，为MODEL_NAME的时候首字母大写<br/>
    显示名称 : <input name="title" type="text" /><br/>
    状态    : 
    		<select name="status">
            	<option value="1">开启</option>
                <option value="0">禁用</option>
            </select>默认开启
            <br/>
    类型    : 
    		<select name="level">
            	<option value="1">分组</option>
                <option value="2">控制器</option>
                <option value="3">操作方法</option>
            </select>
            <br/>
    父级节点 : 
    	<select name="pid">
        	<option value="0">根节点</option>
            <?php foreach ($rbac->listNode() as $k=>$v) {?>
            	<option value="<?php echo $v['id']?>"><?php echo $v['title']?></option>
            <?php }?>
        </select>
    <br/>
    排序   : <input name="sort" type="text" value="0" />默认为0<br/>
    描述 : <input name="remark" type="text" />默认为空<br/>
    <input type="submit" value="添加">
</form>
</body>
</html>


<?php }?>