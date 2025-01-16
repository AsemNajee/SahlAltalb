<?php

   // add page updated
   // edit page updated
   require_once 'check.php';
   require_once 'newFiles/function.php';

   if(!$sign){
      header('Location: error.php?error=ONLY_USERS');
      exit;
   }
   $_SESSION = get_user_data($_SESSION['id']);
   if(!has_permission($_SESSION , STATUS_POSTER)){
      header('Location: error.php?error=NO_PERMISSIONS');
      exit;
   }

   if(isset($_GET['mode']) and $_GET['mode'] == 'edit'){
      if(isset($_GET['page_id']) and is_numeric($_GET['page_id'])){
         $id = filter_var($_GET['page_id'] , FILTER_VALIDATE_INT);
         if(($old_data = get_page($id)) != false){
            $mode = 'edit';
         }else{
            $mode = 'new';
         }
      }else{
         $mode = 'new';
      }
   }else{
      $mode = 'new';
   }

   if(isset($_SERVER['REQUEST_METHOD']) and $_SERVER['REQUEST_METHOD'] == 'POST'){
      if(($data = filter_page_data($_POST)) != false){
         if($mode == 'edit'){
            $id = edit_page($data , $old_data );
         }else{
            $id = create_page($data);
         }
         header('Location: ' . 'p/index.php?page_id=' . $id);
         exit;
      }else{
         $message = 'please fill all blanks';
      }
   }
?>



   <main class="section">
      <?php if(isset($message)){ ?>
         <div class="error-notify">
            <?php echo $message; ?>
         </div>
      <?php } ?>
      <div class="container contact-container center-container main-container">
         <div class="input-box">
            <div class="title">
            <?php echo (($mode == 'edit') ? 'تعديل (' . $old_data['title'] . ')' : 'انشاء صفحة') ?>
            </div>
            <form action="<?php echo $_SERVER['PHP_SELF'] . (($mode == 'edit') ? '?mode=edit&page_id=' . $id  : '') ?>" method="POST">
               <div class="icon">
                  <i class="fas fa-newspaper"></i>
               </div>   
               <div class="info">
                  <input class="text-input grow" type="text" name="title" placeholder="العنوان" value="<?php echo (isset($_POST['title']) ? $_POST['title'] : (isset($old_data['title']) ? $old_data['title'] : ''))  ?>" size="<?php echo MAX_NAME_LETTERS ?>">
                  <input class="text-input grow" type="text" name="bio" placeholder="الوصف" value="<?php echo (isset($_POST['bio']) ? $_POST['bio'] : (isset($old_data['bio']) ? $old_data['bio'] : ''))  ?>" size="<?php echo MAX_BIO_LETTERS ?>">
               </div>
               <select name="dir" class="text-input grow">
                  <option <?php echo (isset($data['dir']) ? (($data['dir'] == 'rtl') ? 'selected' : '') : '') ?> value="rtl">عربي</option>
                  <option <?php echo (isset($data['dir']) ? (($data['dir'] == 'ltr') ? 'selected' : '') : '') ?> value="ltr">انجليزي</option>
               </select>
               <textarea class="text-input" type="text" name="tags" placeholder="الكلمات المفتاحية" size="<?php echo MAX_TAGS_LETTERS ?>"><?php echo (isset($_POST['tags']) ? $_POST['tags'] : (isset($old_data['tags']) ? implode(', ' , $old_data['tags']) : ''))  ?></textarea>
               <button class="button" type="submit"><?php echo (($mode == 'edit') ? 'تعديل الصفحة' : 'انشاء') ?></button>
            </form>
         </div>
      </div>
   </main>


<?php
   require_once 'static/footer.php';

?>