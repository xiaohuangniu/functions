<?php
/*
 +----------------------------------------------------------------------
 + Title        : PHP类的反射
 + Author       : 小黄牛
 + Version      : V1.0.0.1
 + Initial-Time : 2017-5-17 10:22:00
 + Last-time    : 2017-5-17 10:22:00 + 小黄牛
 + Desc         : 利用反射机制，简单的实现PHP插件模式
 +    假设，我们有一款开源产品，所有开发者都必须在我定制的需求之上，进行二次开发，
 +    而开发完成后的新模块，就是一个不一样的新插件，可以放在特定的位置进行自动加载
 +----------------------------------------------------------------------
*/

header("Content-type: text/html; charset=utf-8");

/**
 * 这是我们的开源产品
 */
interface Demo{  
    # 所有插件都必须实现这个方法
    public function msg();  
}

/**
 * 这是小明开发的插件-1
 */
class xiaoming implements Demo{
    public function msg(){
        echo '小明：我就静静地看着你装逼<br/>';
    }
}
/**
 * 这是小李开发的插件-2
 */
class xiaoli implements Demo{
    public function msg(){
        echo '小李：我就装逼了，你能拿我咋滴？<br/>';
    }
}


/** 
 * 我们先搜索该插件类，并且判断它是否实现了msg方法 
 */  
function find(){  
    # 定义描述插件的数组(是一个实例)
    $plugin = array();  

    foreach (get_declared_classes() as  $class) {  
        $reclass = new ReflectionClass($class);  

        # 检测类是否继承与接口Demo
        if ($reclass->implementsInterface('Demo')) {  
            $plugin[] = $reclass;  
        }  
    }  
    return $plugin;  
}  

/** 
 * 编写一个监听所有插件对应的msg方法 的函数
 */  
function myexec(){  
    foreach (find() as $plugin) {  
        # 判断该插件是否拥有msg方法  
        if($plugin->hasMethod('msg')){  
            # 得到这个方法类的一个实例  
            $remethod = $plugin->getMethod('msg');  
            # 如果它是静态方法，则直接调用即可  
            if($remethod->isStatic()){  
                $remethod->invoke(null);  
            }else{  
                # 先声明插件类的一个实例，然后调用它  
                $pluins = $plugin->newInstance();  
                $remethod->invoke($pluins);  
            }  
        }  
    }  
}  

# 监听所有插件
myexec();