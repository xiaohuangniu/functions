<?php
if (!empty($_POST)){
	# 引入类
	include 'Csv.php';
	$str = new Csv();
	$str->set('dbName','shop');              // 数据库名
	$str->set('dbUser','root');              // 账号
	$str->set('dbPwd','');                // 密码
	$str->set('Table','dan');            // 表名
	$str->set('tablePrefix','ftc_');    // 表前缀
	$str->M();
	$field = array('title','content'); //字段
	$info = $str->Import('pic',$field); //参数 : 上传域的name 字段数组 隐藏参数: 导入行数 默认1W行
	if($info){
		die ('导入成功');
	}
	die ('导入失败');
}else{
?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body>
<form action="" method="post" enctype="multipart/form-data">
	<input name="suibian" type="hidden" value="这个只是用来撑场子的" />
	选择需要导入的Excel/Csv: 
    <br/><input name="pic" type="file" />
    <br/><input type="submit" />
</form>
</body>
</html>

<?php }?>