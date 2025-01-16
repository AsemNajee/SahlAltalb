<?php


session_start();
session_regenerate_id();
define('LOCAL_ROOT' , '../');

require_once './../static/page_head.php';
require_once './../newFiles/function.php';

echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/styles/default.min.css">';
require_once './../static/header.php';

if(isset($_GET['page_id'], $_GET['post_id'])){
   $data = getPageDataToDisplay($_GET['page_id'], $_GET['post_id']);
}elseif(isset($_GET['page_id'])){
   $data = getPageDataToDisplay($_GET['page_id']);
}else{
   header('Location: error.php?error=ERROR_WRONG_PAGE_ID');
   exit;
}
$posts = $data['posts'];
$page = $data['page'];
if($sign) $user_subscripes = read_file(get_user_path($_SESSION['id'])  . 'subscripe.json');
require_once('page_nav.php');
if(isset($data['post'])){
   $post = $data['post'];
   require_once 'view_posts.php';
}else{
   require_once 'view_posts.php';
}
require_once './../static/footer.php';

?>


