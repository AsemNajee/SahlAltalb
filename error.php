<!-- updated  -->
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="css/error.css">
   <link rel="stylesheet" href= './../fontawsom/css/all.min.css'  >
<?php 
define('LOCAL_ROOT' , '');

// require_once 'static/page_head.php';
const ERRORS = [
   'NO_PERMISSIONS'=>'you can\'t use this page.',
   'ONLY_USERS'=>'please create account to use this feature',
   'ERROR_WRONG_PAGE_ID'=>'',
   'ERROR_WRONG_DATA_SENT'=>'',
   'ERROR_WRONG_POST_ID'=>'',
   'ERROR_NO_DATA_SENT'=>'',
];

if(isset($_GET['error']) and isset(ERRORS[$_GET['error']])){
   $error = ERRORS[$_GET['error']];
}else{
   $error = 'unkown error';
}
?>
   <title>ERROR</title>
</head>
<body>
   <main class="error-main">
      <div class="container">
         <i class="fas fa-message"></i>
         <span><?php echo $_GET['error'] ?></span>
         <p><?php echo $error ?></p>
         <a href="index.php"><button class="button">الصفحة الرئيسية</button></a>
      </div>
   </main>
</body>
</html>