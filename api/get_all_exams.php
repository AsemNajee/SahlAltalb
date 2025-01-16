<?php


header('Content-Type: application/json');
define('LOCAL_ROOT' , '../');
require_once '../function.php';


function print_api($ok , $data){
   echo json_encode([
      'ok' => $ok, 
      'data' => $data,
   ]);
   exit;
}

if(file_exists(FILE_EXAMS)){
   if(($data = file_get_contents(FILE_EXAMS)) != false){
      print_api('succes', $data);
   }else{
      print_api('faild', 'exams are finished');
   }
}else{
   print_api('faild', 'no exams yet');
}