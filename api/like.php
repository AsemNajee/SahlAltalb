<?php

// updated
session_start();
header('Content-Type: application/json');
define('LOCAL_ROOT' , '../');
require_once '../newFiles/function.php';

function print_api($ok , $message , $like = ''){
   echo json_encode([
      'ok' => $ok, 
      'message' => $message,
      'like' => $like
   ]);
   exit;
}

// https://a.com/index.php/username=5&password=1234
$user = $_GET['username'];
$method = $_SERVER['REQUEST_METHOD'];
if($method == 'GET'){
   if(isset($_GET['page_id'] , $_GET['post_id'], $_GET['state'] , $_SESSION['id'])){
      $page_id = filter_var($_GET['page_id'] , FILTER_VALIDATE_INT);
      $post_id = filter_var($_GET['post_id'] , FILTER_VALIDATE_INT);
      // if(($page = get_page($page_id)) == false ){
      //    print_api('error' , 'wrong page id');
      // }
      if($_GET['state'] == 'like'){
         if(($return = like($page_id , $post_id , 'like'))['return'] != false){
            print_api('succes' , 'like ' . $return['message'] , (($return['message'] == 'added') ? 1 : 0));
         }else{
            print_api('faild' , $return['message']);
         }
      }elseif($_GET['state'] == 'unlike'){
         if(($return = like($page_id , $post_id ,'unlike')) != false){
            print_api('succes' , 'unlike ' . $return['message'] , (($return['message'] == 'added') ? -1 : 0));
         }else{
            print_api('faild' , $return['message']);
         }
      }else{
         print_api($state , 'wrong action');
      }
   }else{
      print_api('error' , 'some thig is required');
   }
}elseif($method == 'POST'){
   print_api('error'  , 'no page and post ids');
}else{
 print_api('error'  , 'can not reach this api');
}
print_api('error'  , 'imposible error');



