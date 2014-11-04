<?php
  session_start();
   date_default_timezone_set('PRC');
   

 if(!isset($_SESSION['last_submit_time'])){

   if(!empty($_POST['project_code'])){
   $verifycode = $_POST['project_code'];
   }else{
   $verifycode = "";
   }
   if($verifycode == $_SESSION["login_check_num"]){
   $_SESSION['last_submit_time'] = time();
     print_r(1);
   }else{
     print_r(0);
   }
 
 }else if(intval((time()-$_SESSION['last_submit_time'])/60) > 5){

   print_r($_SESSION['last_submit_time']);
   print_r(time());
   if(!empty($_POST['project_code'])){
   $verifycode = $_POST['project_code'];
   }else{
   $verifycode = "";
   }
   if($verifycode == $_SESSION["login_check_num"]){
     unset($_SESSION['last_submit_time']);
     print_r(1);
   }else{
     print_r(0);
   }
 }else{
    
   print_r(2);
   
 }
?>