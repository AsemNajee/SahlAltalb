<?php


header('Content-Type: application/json');
define('LOCAL_ROOT' , '../');

require_once '../function.php';

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
   if(isset($_GET['page_id'] , $_GET['post_id'] , $_GET['test'])){
      $page_id = filter_var($_GET['page_id'] , FILTER_VALIDATE_INT);
      $pass = preg_replace('[^a-fA-F0-9_]' , '' , $_GET['test']);
      $post_id = filter_var($_GET['post_id'] , FILTER_VALIDATE_INT);
      $page_info = get_page($page_id);
      $admin = get_user_data($page_info['creator']);
      if($admin['password'] == $pass){
         $posts = get_all_messages($page_info);
         if(isset($posts[$post_id])){
            unset($posts[$post_id]);
            file_put_contents(get_page_path($page_info) . 'posts.json' , json_encode($posts));
            print_api('succes' , 'deleted');
         }
      }
   }
}elseif($method == 'POST'){
   print_api('error'  , 'unknown error');
}else{
 print_api('error'  , 'unknown error');
}
print_api('error'  , 'unknown error');
