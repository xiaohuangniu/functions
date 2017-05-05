<?php
// +----------------------------------------------------------------------
// | 小黄牛MVC框架 - Vendor - Verify - 内置验证码类
// +----------------------------------------------------------------------
// | 支持英数混合、英汉混合、运算三种验证码
// +----------------------------------------------------------------------
// | Copyright (c) 2016 www.junphp.com 
// +----------------------------------------------------------------------
// | Author: 冯俊豪 - 小黄牛 <1731223728@qq.com>
// +----------------------------------------------------------------------
class Verify {
	// 验证码的SESSION下标
	public static $seKey = '';
	// 验证码中使用的字符，01IO容易混淆，建议不用
	public static $codeSet = '2346789ABCDEFGHJKLMNPQRTUVWXY'; //英数混合
	public static $clcode  = array('七','A','万','B','三','上','下','D','不','E','与','F','专','G','且','H','世','J','丘','K','业','L','丛','M','东','N','P','Q','丝','丢','两','严','R','丧','个','中','丰','T','串','临','U','丸','丹','为','V','主','举','久','W','么','义','之','X','鸟','乍','Y','乎','乏','乐','乔','乘','九','也','习','乡','书','买','乱','乳','了','予','争','事','二','于','云','互','五','井','亚','些','亡','交','亥','亦','产','亨','享','京','亭','亮','亲','人','亿','什','仅','今','介','仍','从','仓','仔','他','付'); //中文
	public static $useCurve = true;   // 是否画混淆曲线
	public static $useNoise = true;   // 是否添加杂点	
	public $Type = array(
						'FontSize' => 20,      // 验证码字体大小(px)
						'ImageH'   => 50,      // 验证码图片高度
						'ImageW'   => 150,     // 验证码图片宽度
						'ImageT'   => 4,       // 验证码位数
						'FontType' => '5.ttf', // 验证码字体样式
						'Expire'   => 60,      // 验证码过期时间,单位:秒
				   );
	public static $bg = array(243, 251, 254);       // 验证码背景颜色
	public static $url = 'ttf/';                    // 验证码字体样式部分路径
	protected static $_image = null;                // 验证码图片实例
	protected static $_color = null;                // 验证码字体颜色
	/**
	 * 输出验证码;
	 * $Type ：验证码属性
	 * $Num  ：验证码模式,默认为英数混合、1英数混合、2英汉混合、3数字运算
	*/
	public function entry($Type='',$Num=1) {
		if(!empty($Type)){$this->Type = $Type;}                                                              //初始化验证码属性
		self::$_image = imagecreate($this->Type['ImageW'],$this->Type['ImageH']); 							 //画布宽高
		imagecolorallocate(self::$_image,self::$bg['0'],self::$bg['1'],self::$bg['2']);						 //画布背景颜色
		self::$_color = imagecolorallocate(self::$_image, mt_rand(1,120), mt_rand(1,120), mt_rand(1,120));   //画布文字颜色
		if (self::$useNoise) {
			// 绘杂点
			self::_writeNoise();
		} 
		if (self::$useCurve) {
			// 绘干扰线
			$this->_writeCurve();
		}
		//判断验证码模式
		switch ($Num)
		{
		case 1:
			$code = $this->nbcode();//英数
			$this->_session($code); //将验证码存入session中
			break;  
		case 2:
			$code = $this->clcode();//英汉
			$this->_session($code); //将验证码存入session中
			break;
		case 3:
			$code = $this->aocode();//运算
			$this->_session($code); //将验证码存入session中
			break;
		}
		
		//响应到页面头部
		header('Cache-Control: private, max-age=0, no-store, no-cache, must-revalidate');
		header('Cache-Control: post-check=0, pre-check=0', false);		
		header('Pragma: no-cache');		
		header("content-type: image/png");
		//如果报错,将下面OB缓存代码删除
		ob_clean();
		// 输出图像
		imagepng(self::$_image); 
		imagedestroy(self::$_image);
	}
	/** 
	 * 画一条由两条连在一起构成的随机正弦函数曲线作干扰线(你可以改成更帅的曲线函数) 
     *      
	 *	正弦型函数解析式：y=Asin(ωx+φ)+b
	 *  各常数值对函数图像的影响：
	 *  A：决定峰值（即纵向拉伸压缩的倍数）
	 *  b：表示波形在Y轴的位置关系或纵向移动距离（上加下减）
	 *  φ：决定波形与X轴位置关系或横向移动距离（左加右减）
	 *  ω：决定周期（最小正周期T=2π/∣ω∣）
	 *
	*/
	protected function _writeCurve() {
		$ImageH = $this->Type['ImageH'];
		$ImageW = $this->Type['ImageW'];
		$A = mt_rand(1, $ImageH/2);            // 振幅
		$b = mt_rand(-$ImageH/4, $ImageH/4);   // Y轴方向偏移量
		$f = mt_rand(-$ImageH/4, $ImageH/4);   // X轴方向偏移量
		$T = mt_rand($ImageH*1.5, $ImageW*2);  // 周期
		$w = (2* M_PI)/$T;
						
		$px1 = 0;  // 曲线横坐标起始位置
		$px2 = mt_rand($ImageW/2, $ImageW * 0.667);  // 曲线横坐标结束位置 	    	
		for ($px=$px1; $px<=$px2; $px=$px+ 0.9) {
			if ($w!=0) {
				$py = $A * sin($w*$px + $f)+ $b + $ImageH/2;  // y = Asin(ωx+φ) + b
				$i = (int) (($this->Type['FontSize'] - 6)/4);
				while ($i > 0) {	
				    imagesetpixel(self::$_image, $px + $i, $py + $i, self::$_color);  // 这里画像素点比imagettftext和imagestring性能要好很多				
				    $i--;
				}
			}
		}
		
		$A = mt_rand(1, $ImageH/2);                  // 振幅		
		$f = mt_rand(-$ImageH/4, $ImageH/4);   // X轴方向偏移量
		$T = mt_rand($ImageH*1.5, $ImageW*2);  // 周期
		$w = (2* M_PI)/$T;		
		$b = $py - $A * sin($w*$px + $f) - $ImageH/2;
		$px1 = $px2;
		$px2 = $ImageW;
		for ($px=$px1; $px<=$px2; $px=$px+ 0.9) {
			if ($w!=0) {
				$py = $A * sin($w*$px + $f)+ $b + $ImageH/2;  // y = Asin(ωx+φ) + b
				$i = (int) (($this->Type['FontSize'] - 8)/4);
				while ($i > 0) {			
				    imagesetpixel(self::$_image, $px + $i, $py + $i, self::$_color);  // 这里(while)循环画像素点比imagettftext和imagestring用字体大小一次画出（不用这while循环）性能要好很多	
				    $i--;
				}
			}
		}
	}
	/**
	 * 画杂点
	 * 往图片上写不同颜色的字母或数字
	*/
	protected function _writeNoise() {
		for($i = 0; $i < 10; $i++){
			//杂点颜色
		    $noiseColor = imagecolorallocate(
		                      self::$_image, 
		                      mt_rand(150,225), 
		                      mt_rand(150,225), 
		                      mt_rand(150,225)
		                  );
			for($j = 0; $j < 10; $j++) {
				// 绘杂点
			    imagestring(
			        self::$_image,
			        mt_rand(1,5), 
			        mt_rand(-10, $this->Type['ImageW']), 
			        mt_rand(-10, $this->Type['ImageW']), 
			        self::$codeSet[mt_rand(0, 28)], // 杂点文本为随机的字母或数字
			        $noiseColor
			    );
			}
		}
	}

	/**
	 * 英数混合验证码
	 * @返回验证码
	*/
	protected function nbcode() {
		// 绘验证码
		$code = array(); // 验证码
		$codeNX = 0; // 验证码第N个字符的左边距
		for ($i = 0; $i<$this->Type['ImageT']; $i++) {
			$code[$i] = strtolower(self::$codeSet[mt_rand(0, 28)]);
			$codeNX += mt_rand($this->Type['FontSize']*1.2, $this->Type['FontSize']*1.8);
			echo $code[$i];
			// 写一个验证码字符
			imagettftext(self::$_image, $this->Type['FontSize'], mt_rand(-20, 50), $codeNX, $this->Type['FontSize']*1.5, self::$_color, self::$url.$this->Type['FontType'], $code[$i]);
		}
		return $code;
	}
	/**
	 * 英汉混合验证码
	 * @返回验证码
	*/
	protected function clcode() {
		// 绘验证码
		$code = array(); // 验证码
		$codeNX = 0; // 验证码第N个字符的左边距
		for ($i = 0; $i<$this->Type['ImageT']; $i++) {
			$code[$i] = strtolower(self::$clcode[mt_rand(0,103)]);
			$codeNX += mt_rand($this->Type['FontSize']*1.2, $this->Type['FontSize']*1.8);
			$lenA= strlen($code[$i]); //检测字符串实际长度
            $lenB= mb_strlen($code[$i], 'utf-8'); //文件的编码方式要是UTF8     
            //判断汉字还是字母
			if ($lenA=== $lenB) {    
				$fontsize =  $this->Type['FontSize']*1.7;
            } else {
				$fontsize =  $this->Type['FontSize'];
			}
			// 写入一个验证码字符
			imagettftext(self::$_image, $this->Type['FontSize'], mt_rand(0, 40), $codeNX, $this->Type['FontSize']*1.5, self::$_color, self::$url.'msyhbd.ttf', $code[$i]);
		}
		return $code;
	}
	/**
	 * 运算型验证码
	 * @返回验证码
	*/
	protected function aocode() {
		// 绘验证码
		$code = 0; // 验证码
		$left = $this->Type['ImageW']/10; 
		//第一个数字
		$shu=rand(1,9);  
		                                                                                       //定义第一个数字参数
		imagettftext(self::$_image, $this->Type['FontSize'], mt_rand(0, 20), $left, $this->Type['FontSize']*1.5, self::$_color, self::$url.$this->Type['FontType'],$shu);
		$left += $this->Type['ImageW']/5;   																	//左间距的值变化,保证文字不会叠加到一起
		//第二个数字
		$shu2=rand(1,9);                                                                                        //定义第二个数字参数
		imagettftext(self::$_image, $this->Type['FontSize'], mt_rand(0, 20), $left*2, $this->Type['FontSize']*1.5, self::$_color, self::$url.$this->Type['FontType'],$shu2);
		$left += $this->Type['ImageW']/14; 
		//中间的运算符
		$num= rand(1,3);                                                                                             	
		switch ($num)
		{
		case 1:
		  $count='x';                                                                                            //定义输出到画布的运算符
		  $code = $shu*$shu2;                                                                         //得到运算的结果
		  break;  
		case 2:
		  $count='+';
		  $code = $shu+$shu2;
		  break;
		case 3:
		  $count='-';
		  $code = $shu-$shu2;
		  break;
		}
		imagettftext(self::$_image, $this->Type['FontSize'], mt_rand(0, 20), $left, $this->Type['FontSize']*1.5, self::$_color, self::$url.$this->Type['FontType'],$count);                                                                                           //将运算符添加到画布中间层
		$left += $this->Type['ImageW']/2.5;                                                                    //左间距的值变化,保证文字不会叠加到一起
		//最后的等号
		imagettftext(self::$_image, $this->Type['FontSize']*1.3, mt_rand(0, 30), $left*1.1, $this->Type['FontSize']*1.5, self::$_color, self::$url.$this->Type['FontType'],'=');																							   //将等号添加到画布末尾	
		return $code;
	}
	/**
	 * 验证码保存
	*/
	protected function _session($code) {
		// 保存验证码
		isset($_SESSION) || session_start();
		if(is_array($code)){
			$_SESSION[self::$seKey]['code'] = join('', $code); // 把校验码保存到session
		}else{
			$_SESSION[self::$seKey]['code'] = $code;
		}
		$_SESSION[self::$seKey]['time'] = time();          // 验证码创建时间
	}
	/**
	 * 验证验证码是否正确
	 *
	 * $code ：用户验证码
	 * 返回验证码是否正确  TRUE || FALSE
	*/
	public function check($code) {
		isset($_SESSION) || session_start();
		// 验证码不能为空
		if(empty($code) || empty($_SESSION[self::$seKey])) {
			return false;
		}
		// session 过期
		if(time() - $_SESSION[self::$seKey]['time'] > $this->Type['Expire']) {
			unset($_SESSION[self::$seKey]);
			return false;
		}
		if(strtolower($code) == $_SESSION[self::$seKey]['code']) {
			return true;
		}
		return false;
	}
}