<?php


session_start();
header('Content-Type: application/json');
define('LOCAL_ROOT' , '../');
require_once '../newFiles/function.php';

// print_r($_GET);
function print_api($ok , $message , $favorite = ''){
   echo json_encode([
      'ok' => $ok, 
      'message' => $message,
      'favorite' => $favorite
   ]);
   exit;
}

$method = $_SERVER['REQUEST_METHOD'];
if($method == 'GET'){
   if(isset($_GET['page_id'] , $_GET['post_id'] , $_SESSION['id'])){
      $page_id = filter_var($_GET['page_id'] , FILTER_VALIDATE_INT);
      $post_id = filter_var($_GET['post_id'] , FILTER_VALIDATE_INT);
      $user_id = $_SESSION['id'];
      if(($return = add_to_favorite($page_id, $post_id)) == false){
         print_api('faild' , 'canot add to favorite');
      }else{
         print_api('succes' , $return);
      }
   }else{
      print_api('error' , 'some thig is required');
   }
}elseif($method == 'POST'){
   print_api('error'  , 'unknown error');
}else{
 print_api('error'  , 'unknown error');
}
