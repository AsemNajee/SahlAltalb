<?php

// updated
session_start();
header('Content-Type: application/json');
define('LOCAL_ROOT' , '../');
require_once '../newFiles/function.php';


function print_api($ok , $message , $post = ''){
   echo json_encode([
      'ok' => $ok, 
      'message' => $message,
      'post' => $post
   ]);
   exit;
}

$method = $_SERVER['REQUEST_METHOD'];
if($method == 'GET'){
   if(isset($_GET['page_id'] , $_GET['post_id'])){
      if(($post = getPostData($_GET['page_id'] , $_GET['post_id'])) == false){
         print_api('faild' , 'wrong id');
      }else{
         print_api('succes', '', $post);
      }
      print_api('faild', 'anather exception');
   }
}elseif($method == 'POST'){
   print_api('error'  , 'no page id');
}else{
 print_api('error'  , 'can not reach this api');
}

print_api('error'  , 'imposible error ');
