<?php

// updated
   session_start();
   session_regenerate_id();
   
   define('LOCAL_ROOT' , '');

   require_once 'newFiles/function.php';

   if(!isset($_SESSION['id'])){
      if(!(isset($_SESSION['type']) and $_SESSION['type'] == 'visitor')){
         header('Location: login.php');
         exit;
      }
   }
   require_once 'static/page_head.php';
   require_once 'static/header.php';
?>