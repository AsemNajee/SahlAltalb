<?php

// updated
session_start();
header('Content-Type: application/json');
define('LOCAL_ROOT' , '../');
require_once '../newFiles/function.php';

function print_api($ok , $message , $subscripe = ''){
   echo json_encode([
      'ok' => $ok, 
      'message' => $message,
      'subscripe' => $subscripe
   ]);
   exit;
}

$method = $_SERVER['REQUEST_METHOD'];
if($method == 'GET'){
   if(isset($_GET['page_id'] , $_SESSION['id'])){
      $page_id = filter_var($_GET['page_id'] , FILTER_VALIDATE_INT);
      if(($sub = subscripe($_SESSION['id'] , $page_id))['return'] == false){
         print_api('error' , $sub['message']);
      }else{
         print_api('succes' , $sub['message']);
      }
   }else{
      print_api('error' , 'some thig is required');
   }
}elseif($method == 'POST'){
   print_api('error'  , 'no page id');
}else{
 print_api('error'  , 'can not reach this api');
}
print_api('error'  , 'imposible error');
