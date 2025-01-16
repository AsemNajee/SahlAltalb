<?php



   require_once 'check.php';

   if(!$sign){
      header('Location: error.php?error=1');
      exit;
   }
   $_SESSION = get_user_data($_SESSION['id']);
   if(!has_permission($_SESSION , STATUS_TEACHER)){
      header('Location: error.php?error=1');
      exit;
   }

   // if(isset($_GET['id'])){
   //    $page_id = $_GET['id'];
   // }else{
   //    header('Location: index.php');
   //    exit;
   // }


   if(isset($_GET['mode'])){
      if($_GET['mode'] == 'edit'){
         $mode = 'edit';
      }else{
         $mode = 'new';
      }
   }else{
      $mode = 'new';
   }
   if(($exam = get_exam_data($_GET['id'])) != false){
      if($_SESSION['id'] != $exam['creator']){
         header('Location: error.php?error=3');
         exit;
      }
   }else{
      $message = 'حصل خطا , اعد تحميل الصفحة';
      // header('Location: ../');
      exit;
   }

   // if(isset($_GET['id'])){
   //    $all_posts = get_all_messages($page_info , true);
   //    if(isset($all_posts[$_GET['id']])){
   //       $data = $all_posts[$_GET['id']];
   //    }else{
   //       header('Location: error.php?error=5');
   //       exit;
   //    }
   // }
   
   if(isset($_SERVER['REQUEST_METHOD']) and $_SERVER['REQUEST_METHOD'] == 'POST'){
      if(($ques = filter_question_data($_POST)) != false){
         if($mode == 'new'){               
            // $id = create_question($ques, $exam);
            unset($_POST);
            header('Location: ' . 'p/index.php?page_id=' . $exam['id'] );
            exit;
         }else if($mode == 'edit' and isset($_GET['post_id'])){
            // $id = edit_post($info , $page_info , $_GET['post_id']);
            // unset($_POST);
            // header('Location: ' . 'p/index.php?page_id=' . $page_info['id'] . '&post_id=' . $id);
            // exit;
         }else{
            $message = 'معرف رسالة خاطئ';
         }
      }else{
         $message = 'الرجاء ملئ جميع الحقول';
      }
      $data = $_POST;
   }
   $dir = ((isset($exam['dir'])) ? $exam['dir'] : 'rtl');
   
?>


   <div class="page-title">
      <a href=<?php echo 'p/index.php?page_id=' . $exam['id']?>><h2><?php echo $exam['title']?></h2></a>
   </div>

   <main class="section" dir="<?php echo $dir; ?>">
      <?php if(isset($message)){ ?>
         <div class="error-notify">
            <?php echo $message; ?>
         </div>
      <?php } ?>
      <div class="container contact-container center-container main-container">
         <div class="input-box">
            <div class="title">
            <?php echo (($mode == 'edit') ? 'تعديل الاختبار (' . $data['title'] . ')' : 'انشاء اختبار') ?>
            </div>
            <form action="<?php echo $_SERVER['PHP_SELF'] . '?id=' . $exam['id'] . '&mode=' . $mode ?>" method="POST">
               <div class="info">
                  <input class="text-input grow" type="text" name="title" placeholder="العنوان" maxlength="<?php echo MAX_POST_TITLE_LETTERS?>" value="<?php echo (isset($data['title']) ? $data['title'] : '' )  ?>">
                  <select name="dir" class="text-input grow">
                     <option <?php echo (isset($data['dir']) ? (($data['dir'] == 'rtl') ? 'selected' : '') : (($dir == 'rtl') ? 'selected' : '')) ?> value="rtl">عربي</option>
                     <option <?php echo (isset($data['dir']) ? (($data['dir'] == 'ltr') ? 'selected' : '') : (($dir == 'ltr') ? 'selected' : '')) ?> value="ltr">انجليزي</option>
                  </select>
               </div>
               <input class="text-input grow" type="text" name="choose[]" placeholder="اختيار" >
               <input class="text-input grow" type="text" name="choose[]" placeholder="اختيار" >
               <input class="text-input grow" type="text" name="choose[]" placeholder="اختيار" >
               <input class="text-input grow" type="text" name="choose[answer]" placeholder="الاجابة" >
               <button class="button" type="submit">نشر</button>
            </form>
         </div>
      </div>
   </main>


<?php
   require_once 'static/footer.php';

?>