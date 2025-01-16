<?php
   
   require_once 'check.php';

   if(!$sign){
      header('Location: error.php?error=ONLY_USERS');
      exit;
   }

   $_SESSION = get_user_data($_SESSION['id']); // important
   if(!has_permission($_SESSION , STATUS_OWNER)){
      header('Location: error.php?error=NO_PERMISSIONS');
      exit;
   }


   $message = '';
   if((isset($_SERVER['REQUEST_METHOD']) and $_SERVER['REQUEST_METHOD'] == 'POST')){
      if(isset($_GET['id'] , $_POST['status'])){
         if(set_status($_GET['id'] , $_POST['status']) != false){
            header('Location: index.php');
            exit;
         }else{
            $message = 'faild to change';
         }
      }else{
         $message = 'no data sent';
      }
   }
   if(isset($_GET['id'])){
      $id = filter_var($_GET['id'] , FILTER_VALIDATE_INT);
      if($id <= ID_PAGE){
         $mode = 'page';
      }elseif($id >= ID){
         $mode = 'user';
      }else{
         header('Location: error.php?error=ERROR_WRONG_DATA_SENT');
         exit;
      }
   }else{
      header('Location: error.php?error=ERROR_NO_DATA_SENT');
      exit;
   }
?>


<main class="section">
   <?php if(!empty($message)){ ?>
      <div class="error-notify">
         <?php echo $message; ?>
      </div>
   <?php } ?>
      <div class="container contact-container center-container main-container">
         <?php 
         if($mode == 'user'){
            if(($user = get_user_data($id)) != false){ ?>
               <div class="input-box">
                  <div class="title">
                     edit user info
                  </div>
                  <form action="<?php echo $_SERVER['PHP_SELF'] . ((isset($_GET['id'])? '?id=' . $_GET['id'] : ''))?>" method="POST">
                     <div class="icon">
                        <i class="fas fa-user-tie"></i>
                     </div>
                     <div class="info">
                        <input class="text-input grow" type="text" name="name" placeholder="" value="<?php echo $user['name']  ?>" disabled>
                        <input class="text-input grow" type="text" name="user_id" placeholder="user id" value="<?php echo $user['id']  ?>">
                        <select name="status" class="text-input grow">
                           <option value="1">graduate</option>
                           <option value="2" selected>user</option>
                           <option value="3">student</option>
                           <option value="4">teacher</option>
                           <option value="5">real</option>
                           <option value="6">vip</option>
                           <option value="7">writter</option>
                           <option value="8">poster</option>
                           <option value="9">admin</option>
                           <option value="10">dev</option>
                           <option value="-2">panned</option>
                        </select>
                     </div>
                     <button class="button" type="submit">Set</button>
                  </form>
               </div>
               <?php 
            }else{
               exit;
            }
         }else{ 
            if(($page = get_page($id)) != false){
            ?>
            <div class="input-box">
               <div class="title">
                  تعديل معلومات الصفحة
               </div>
               <form action="<?php echo $_SERVER['PHP_SELF'] . ((isset($_GET['id'])? '?id=' . $_GET['id'] : ''))?>" method="POST">
                  <div class="icon">
                     <i class="fas fa-user-tie"></i>
                  </div>
                  <div class="info">
                     <input class="text-input grow" type="text" name="title" placeholder="العنوان" value="<?php echo $page['title']  ?>" disabled>
                     <input class="text-input grow" type="text" name="page_id" placeholder="المعرف" value="<?php echo $page['id']  ?>">
                     <select name="status" class="text-input grow">
                        <option value="12">عادية</option>
                        <option value="13">موثقة</option>
                        <option value="14">مميزة</option>
                        <option value="15">اساسية</option>
                        <option value="16">رسمية</option>
                        <option value="-1">محظورة</option>
                     </select>
                  </div>
                  <button class="button" type="submit">حفظ</button>
               </form>
            </div>
            <?php 
            }else{
               exit;
            }
         }?>
      </div>
   </main>



<?php
   require_once 'static/footer.php';
?>