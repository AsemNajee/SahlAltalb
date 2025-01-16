<?php 

// login updated
session_start();
session_regenerate_id();
define('LOCAL_ROOT' , '');
// print_r($_COOKIE);

$message;
$info;

require_once 'newFiles/function.php';



$errors = array(
   'name-short' => 'name is short , [' . MIN_NAME_LETTERS . ' - ' . MAX_NAME_LETTERS . '] letters',
   'name-long' => 'name is long , [' . MIN_NAME_LETTERS . ' - ' . MAX_NAME_LETTERS . '] letters',
   'user-long' => 'username is long  , [' . MIN_USERNAME_LETTERS . ' - ' . MAX_USERNAME_LETTERS . '] letters',
   'user-short' => 'username is so short  , [' . MIN_USERNAME_LETTERS . ' - ' . MAX_USERNAME_LETTERS . '] letters',
   'pass-long' => 'password is long  , [' . MIN_PASSWORD_LETTERS . ' - ' . MAX_PASSWORD_LETTERS . '] letters',
   'pass-short' => 'password is week , [' . MIN_PASSWORD_LETTERS . ' - ' . MAX_PASSWORD_LETTERS . '] letters',
   'wrong-pass' => 'invalid password',
   'pass-not-conf' => 'passwords are not equals'
);
if(isset($_GET['type']) and $_GET['type'] == 'visitor'){
   $_SESSION['type'] = 'visitor';
   header('Location: index.php');
   exit;
}

if(isset($_COOKIE['userinfo'])){
   $info = json_decode($_COOKIE['userinfo'] , true);
   if(isset($info['id']) and isset($info['password'])){
      if(($id = check_login($info['id'], $info['password'])) == false){
         unset($info);
         setcookie('userinfo' , '');
      }else{
         $_SESSION = get_user_data($id);
         header('Location: index.php');
         exit;
      }
   }else{
   }
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
   $info = filtering_input($_POST);
   unset($_POST);
}else {
   unset($info);
}
if(isset($info) and ($type = type_account($info)) != false){
   if($type == 'signup'){
      if(check_username_validation($info['username']) === true){
         if(($user = login($info)) != false){
            $_SESSION = $user;
            $temp['id'] = $user['id'];
            $temp['password'] = $info['password'];
            setcookie('userinfo', json_encode($temp) , time() + (3600 * 24 * 30 * 12) , true);
            header('Location: ' . 'index.php');
            exit;
         }else{
            $message = 'faild to create account .!';
         }
      }else{
         $message = 'this username is not avaliable';
      }
   }else if($type == 'login'){
      if(($id = check_login($info['username'] , $info['password'])) != false){
         $_SESSION = get_user_data($id);
         header('Location: ' . 'index.php');
         exit;
      }else{
         $message = 'username or password is wrong .';
      }
   }else{
      $message = 'please fil all gaps';
   }
}


?>


<?php 
require_once 'static/page_head.php'
?>
   <title>تسجيل الدخول</title>
</head>
<body dir="rtl">
   <div class="login-container">
      <div id="sign-up-div" class="sign-up">
         <h3>انشاء حساب</h3>
         <form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">    
            <input class="text-input" id="name" type="text" placeholder="الاسم" name="name" value="<?php echo (isset($info['name'])) ?  $info['name'] : '' ?>" size="<?php echo MAX_NAME_LETTERS ?>">
            <input class="text-input" type="text" placeholder="اسم المستخدم" name="username" value="<?php echo (isset($info['username'])) ?  $info['username'] : '' ?>" size="<?php echo MAX_USERNAME_LETTERS ?>">
            <input class="text-input" type="password" name="password" placeholder="كلمة السر" size="<?php echo MAX_PASSWORD_LETTERS ?>">
            <input class="text-input" id="con-pass" type="password" name="con-password" placeholder="اعد كلمة السر">
            <div class="agree">
               <!-- <input type="checkbox" name="agree"> -->
               <!-- <span><a href="#">اوافق على شروط الخدمة</a></span> -->
            </div>
            <div class="hint">
               هل لديك حساب بالفعل؟ <span id="login">تسجيل الدخول</span>
            </div>
            <button class="button" type="submit">انشاء حساب</button>
         </form>
      </div>
      <div id="log-in-div" class="log-in" style="display: none;">
         <h3>تسجيل الدخول</h3>
         <form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
            <input class="text-input" type="text" placeholder="اسم المستخدم" name="username" size="<?php echo MAX_USERNAME_LETTERS ?>">
            <input class="text-input" type="password" name="password" placeholder="كلمة السر" size="<?php echo MAX_PASSWORD_LETTERS ?>">
            <div class="hint">
                ليس لديك حساب ؟ <span id="create-account">انشاء حساب جديد</span></span>
            </div>
            <button class="button" type="submit">دخول</button>
         </form>
      </div>
   </div>
   <!-- <main>
   <?php if(isset($message)){ ?>
         <div class="error-notify">
            <?php echo $message; ?>
         </div>
   <?php } ?>

   
   </main> -->
   <script>
      const logDiv = document.getElementById('log-in-div');
      const signDiv = document.getElementById('sign-up-div');
      const signup = document.getElementById('create-account');
      const login = document.getElementById('login');
      login.addEventListener('click' , ()=> {
         signDiv.style.display = 'none';
         logDiv.style.display = 'flex'; 
      });
      signup.addEventListener('click' , ()=> {
         signDiv.style.display = 'flex';
         logDiv.style.display = 'none'; 
      });
   </script>
</body>
</html>

<?php

function type_account($data){
   if(isset($data['username'] , $data['password'])){
      if(isset($data['name'] , $data['con-password'])){
         return 'signup';
      }else{
         return 'login';
      }
   }else{
      return false;
   }
}


function filtering_input($input){
   $output = null;
   if(isset($input['name'])){
      $output['name'] = str_filter($input['name']);
   }
   if(isset($input['password'])){
      $output['password'] = $input['password'];
   }
   if(isset($input['con-password'])){
      $output['con-password'] = $input['con-password'];
   }
   if(isset($input['username'])){
      $output['username'] = preg_replace('/[^a-zA-Z0-9_]/' , '' , $input['username']);
   }
   return $output;
}
