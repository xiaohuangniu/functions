<?php
include './Verify.class.php';
# 实例化验证码类
$vif = new Verify();
# 验证码配置  默认不需要设置
$Type = array(
  'FontSize' => 20,      // 验证码字体大小(px)
  'ImageH'   => 50,      // 验证码图片高度
  'ImageW'   => 150,     // 验证码图片宽度
  'ImageT'   => 4,       // 验证码位数
  'FontType' => '5.ttf', // 验证码字体样式
  'Expire'   => 60,      // 验证码过期时间,单位:秒
);

# 生成验证码
# 默认为英数混合类型
$vif->entry($Type,2);

/*
  $vif->entry();//英数混合
  $vif->entry('',1);//英数混合
  $vif->entry('',2);//中英混合
  $vif->entry('',3);//数字运算
*/

/*
  # 验证码效验
  if ($vif->check($_POST['NAME名'])) {
    真
  }else{
    假
  }
*/
