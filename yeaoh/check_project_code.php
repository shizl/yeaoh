<?php
session_start();

   if(!empty($_POST['project_code'])){
   $verifycode = $_POST['project_code'];
   }else{
   $verifycode = "";
   }
   if($verifycode == $_SESSION["login_check_num"]){
     print_r(1);
   }else{
     print_r(0);
   }

?>