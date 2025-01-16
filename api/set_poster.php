<?php

// updated but not tested

session_start();
session_regenerate_id();
header('Content-Type: application/json');
define('LOCAL_ROOT' , '../');
require_once '../newFiles/function.php';

function print_api($ok , $message , $poster = ''){
   echo json_encode([
      'ok' => $ok, 
      'message' => $message,
      'poster' => $poster
   ]);
   exit;
}

$method = $_SERVER['REQUEST_METHOD'];
if($method == 'GET'){
   if(isset($_GET['page_id'] , $_GET['user_id'])){
      $page_id = filter_var($_GET['page_id'] , FILTER_VALIDATE_INT);
      $user_id = filter_var($_GET['user_id'] , FILTER_VALIDATE_INT);
      $page_info = get_page($page_id);
      if($_SESSION['id'] == $page_info['creator']){
         $path = get_page_path($page_info) . 'users.json';
         if(is_file($path)){
            $all_members = read_file($path);
            if(isset($all_members[$user_id])){
               if($all_members[$user_id]['status'] == PAGE_LEVEL_POSTER){
                  unset($all_members[$user_id]['status']);
                  save_data($path , json_encode($all_members));
                  print_api('succes' , 'user');
               }else{
                  $all_members[$user_id] = PAGE_LEVEL_POSTER;
                  save_data($path , json_encode($all_members));
                  print_api('succes' , 'poster');
               }
            }else{
               print_api('faild', 'this user is not in the page');
            }
         }else{
            print_api('faild', 'no users in this page');
         }
      }else{
         print_api('faild', 'you dont have permission');
      }
   }else{
      print_api('faild', 'some data are required');
   }
}elseif($method == 'POST'){
   print_api('error'  , 'no page id');
}else{
 print_api('error'  , 'can not reach this api');
}
print_api('error'  , 'imposible error ');
