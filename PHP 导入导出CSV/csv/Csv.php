<?php 
/*
 +--------------------------------------------------------------------------
 + Title        : CSV 导出导入
 + Author       : www.junphp.com - 极资源PHPer - 小黄牛
 + Version      : V1.0.0.1
 + Initial-Time : 2016-12-15 17:16
 + Last-time    : 2016-12-15 17:16 + 小黄牛
 + Desc         : 使用该类可导出数据库信息与导入CSV信息到数据库中
 +--------------------------------------------------------------------------
*/
class Csv{
	protected $dbMs   = 'mysql';          //数据库类型
	protected $dbHost = 'localhost';      //数据库主机名
    protected $dbName;                    //数据库名称
	protected $dbCharset = 'utf8';        //数据库编码
	protected $dbPort    = '3306';        //数据库端口
	protected $dbUser;        			  //数据库连接用户名
	protected $dbPwd;         			  //对应的密码
	protected $Table = '';   		      //数据表前缀
    protected $tablePrefix = '';   		  //数据表前缀
	private   $instance;                  //数据库PDO连接实例
	
	# 设置初始化参数
	public function set($key, $value){
		$this->$key = $value;
	}
	
	/*
	 * @Title  : 初始化PDO实例
	 * @Author : 小黄牛
	 * 
	*/
	public function M(){
		$this->dbPdo();//初始化PDO类
		return $this;
	}
	
	# 数据库连接
	private function dbPdo(){
		$dbn = $this->dbMs.':host='.$this->dbHost.';port='.$this->dbPort.';dbname='.$this->dbName.';charset='.$this->dbCharset;
		$dbh = new PDO($dbn,$this->dbUser,$this->dbPwd);
		$this->instance = $dbh;
	}
	
	public function Import($File, $Field=array() ,$length=10000){
		$filename = $_FILES[$File]['tmp_name'];
		if (empty ($filename)) {return false;}
		# 解析Excel 并获得数据
		$result = $this->inputCsv($filename, $length);
		$len_result = count($result);
		# 验证长度
		if ($len_result==0) {return false;}
		
		# 获得字段
		$str = '';
		foreach ($Field as $k=>$v) {
			$str .= $v.",";
		}
		$str = rtrim($str, ',');
		
		# 获得数据
		$data_values = '';
		for ($i = 1; $i < $len_result; $i++) { //循环获取各字段值
			$data_values .= "(";
			foreach ($Field as $k=>$v) {
				$res = iconv('gb2312', 'utf-8', $result[$i][$k]);
				$data_values .= "'$res'".",";
			}
			$data_values = rtrim($data_values, ',');
			$data_values .= "),";
		}
		$data_values = rtrim($data_values, ',');
		$pdo = $this->instance;
		return $pdo->exec("INSERT INTO ".$this->tablePrefix.$this->Table." ($str) VALUES $data_values".';');
	}
	
	/*
	 * @Title  : 遍历Excel的内容,并返回一个二维数组
	 * @Author : 小黄牛
	 * @param  : $filename Excel临时文件地址
	 * @param  : $length   导入条数 0为不限制 默认1W条
	*/
	private function inputCsv($filename, $length = 10000) {
		$out = array();
		$n   = 0;
		$handle = fopen($filename, 'r');
		while ($data = fgetcsv($handle, $length)) {
			$num = count($data);
			for ($i = 0; $i < $num; $i++) {
				$out[$n][$i] = $data[$i];
			}
			$n++;
		}
		fclose($handle); //关闭指针
		return $out;
	}

	/**
	 * @Title        : 导出CSV 
	 * @param array  : $Title  横的标题,是一个一维数组
	 * @param array  : $Type   列的参数,是一个二维数组
	 * @return       : 直接Header下载
	*/
	public function Export($Title= array(), $Type= array()){
		# 参数类型检测
		if (!is_array($Title) || !is_array($Type)) {return false;}
		$str = "";
		foreach ($Title as $v) {
			$str .= $v.",";
		}
		$str = rtrim($str, ',');
		$str .= "\n";
		$str = iconv('utf-8','gb2312',$str);
		
		foreach ($Type as $key => $value) {
			foreach ($value as $k=>$v) {
				$$k = iconv('utf-8','gb2312',$v);
				$str .= $$k.",";
			}
			$str = rtrim($str, ',');
			$str .= "\n";
		}
		$filename = date('Ymd').'.csv';
    	$this->exportCsv($filename,$str);
	}
	
	/**
	 * @Title        : 输出CSV 
	 * @param        : $filename  保存名称
	 * @param        : $data      csv的内容
	 * @return       : 直接Header下载
	*/
	private function exportCsv($filename, $data) {
		header("Content-type:text/csv");
		header("Content-Disposition:attachment;filename=".$filename);
		header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
		header('Expires:0');
		header('Pragma:public');
		echo $data;
	}
}