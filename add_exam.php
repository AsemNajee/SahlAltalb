<?php



   require_once 'check.php';
   require_once 'function.php';

   if(!$sign){
      header('Location: error.php?error=1');
      exit;
   }
   $_SESSION = get_user_data($_SESSION['id']);
   if(!is_user($_SESSION , STATUS_TEACHER)){
      header('Location: error.php?error=1');
      exit;
   }

   if(isset($_GET['mode']) and $_GET['mode'] == 'edit'){
      if(isset($_GET['id'])){
         $id = filter_var($_GET['id'] , FILTER_VALIDATE_INT);
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
      if(($data = filter_exam_data($_POST)) != false){
         if($mode == 'edit'){
         }else{
            $id = create_exam($data);
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
            <?php echo (($mode == 'edit') ? 'تعديل (' . $old_data['title'] . ')' : 'انشاء اختبار') ?>
            </div>
            <form action="<?php echo $_SERVER['PHP_SELF'] . (($mode == 'edit') ? '?mode=edit&id=' . $id  : '') ?>" method="POST">
               <div class="icon">
                  <i class="fas fa-exam"></i>
               </div>
               <div class="info">
                  <input class="text-input grow" type="text" name="title" placeholder="العنوان" value="<?php echo (isset($_POST['title']) ? $_POST['title'] : (isset($old_data['title']) ? $old_data['title'] : ''))  ?>" size="<?php echo MAX_NAME_LETTERS ?>">
                  <input class="text-input grow" type="text" name="bio" placeholder="الوصف" value="<?php echo (isset($_POST['bio']) ? $_POST['bio'] : (isset($old_data['bio']) ? $old_data['bio'] : ''))  ?>" size="<?php echo MAX_BIO_LETTERS ?>">
               </div>
               <div class="info grow">
                  <input class="text-input grow" type="number" name="time" placeholder="المدة بالساعات" value="<?php echo (isset($_POST['time']) ? $_POST['time'] : (isset($old_data['time']) ? $old_data['time'] : ''))  ?>" size="2">
                  <select name="dir" class="text-input grow">
                     <option <?php echo (isset($data['dir']) ? (($data['dir'] == 'rtl') ? 'selected' : '') : '') ?> value="rtl">عربي</option>
                     <option <?php echo (isset($data['dir']) ? (($data['dir'] == 'ltr') ? 'selected' : '') : '') ?> value="ltr">انجليزي</option>
                  </select>
               </div>
               <button class="button" type="submit"><?php echo (($mode == 'edit') ? 'تعديل الصفحة' : 'انشاء') ?></button>
            </form>
         </div>
      </div>
   </main>


<?php
   require_once 'static/footer.php';

?>