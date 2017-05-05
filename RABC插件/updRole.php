<?php
# 引入配置文件
include 'config.php';

$id     = $_GET['id']; // 角色id
if(!empty($_POST)){
	# 修改角色
	$title   = $_POST['title'];  // 角色名称
	$status = $_POST['status'];// 状态
	$pid    = $_POST['pid'];   // 父ID
	$remark = $_POST['remark'];// 描述
	
	# 参数
	$array = array(
		'id'     => $id,
		'title'   => $title,
		'status' => $status,
		'pid'    => $pid,
		'remark' => $remark
	);
	# 执行修改
	if ($rbac->updRole($array)) {
		echo '角色修改成功,2秒后自动跳转……';
		header("Refresh:2;url=listRole.php");
	}
	
}else{

# 获得修改的资料
$list = $rbac->selectRole($id);
?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Rbac 权限插件DEMO - www.junphp.com</title>
</head>

<body>
<form action="" method="post">
	角色名称 : <input name="title" type="text" value="<?php echo $list['title']?>" /><br/> 
    状态    : 
    		<select name="status">
            	<option value="1" <?php if($list['status'] == 1){echo 'selected';} ?>>开启</option>
                <option value="0" <?php if($list['status'] == 0){echo 'selected';} ?>>禁用</option>
            </select>默认开启
            <br/>
    父级ID : 
    	<select name="pid">
            <?php foreach ($rbac->listRole() as $k=>$v) { if($v['id'] != $list['id'] || $list['pid'] == 0){?>
            	<option value="<?php echo $v['id']?>" <?php if($v['id'] == $list['pid']){echo 'selected';}?>><?php echo $v['title']?></option>
            <?php }}?>
        </select>
    <br/>
    描述 : <input name="remark" type="text" value="<?php echo $list['remark']?>" />默认为空<br/>
    <input type="submit" value="添加">
</form>
</body>
</html>


<?php }?>