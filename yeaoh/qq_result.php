<?php
session_start();
/*
    $username=$_POST[ 'username'];
    $email=$_POST['email'];
    $question=$_POST['question']; 
    db_query("INSERT INTO TB_QQ VALUES('$username','$email','$question)'");
    print_r("Thank you!"); 
    */  
    
 
function rand_create() 
{ 
    //通知浏览器将要输出PNG图片 
    Header("Content-type: image/PNG"); 
    //准备好随机数发生器种子  
    srand((double)microtime()*1000000); 
    //准备图片的相关参数   
    $im = imagecreate(60,20); 
    $black = ImageColorAllocate($im, 0,0,0);  //RGB黑色标识符 
    $white = ImageColorAllocate($im, 255,255,255); //RGB白色标识符 
    $gray = ImageColorAllocate($im, 200,200,200); //RGB灰色标识符 
    //开始作图     
    imagefill($im,0,0,$gray); 
    while(($randval=rand()%100000)<10000);{ 
        $_SESSION["login_check_num"] = $randval; 
        //将四位整数验证码绘入图片  
        imagestring($im, 5, 10, 3, $randval, $black); 
    } 
    //加入干扰象素    
    for($i=0;$i<400;$i++){ 
        $randcolor = ImageColorallocate($im,rand(0,255),rand(0,255),rand(0,255)); 
        imagesetpixel($im, rand()%70 , rand()%30 , $randcolor); 
    } 
    //输出验证图片 
    ImagePNG($im); 
    //销毁图像标识符 
    ImageDestroy($im); 
    
} 

echo rand_create();
    
?>
