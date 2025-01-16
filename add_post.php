<?php

require_once 'check.php';


   if(!$sign){
      header('Location: error.php?error=ONLY_USERS');
      exit;
   }
   $_SESSION = get_user_data($_SESSION['id']);
   if(!has_permission($_SESSION , STATUS_WRITER)){
      header('Location: error.php?error=NO_PERMISSIONS');
      exit;
   }

   if(isset($_GET['page_id'])){
      $page_id = $_GET['page_id'];
   }else{
      header('Location: index.php');
      exit;
   }

   if(($page_info = get_page($page_id)) != false){
      if($_SESSION['status'] >= STATUS_POSTER 
         and ($_SESSION['id'] == $page_info['creator']
         or ($page_users = get_page_users($page_id)) != false 
         and isset($page_users[$_SESSION['id']])
         and $page_users[$_SESSION['id']]['level'] >= PAGE_LEVEL_POSTER ))
      {
      }else{
         header('Location: error.php?error=NO_PERMISSIONS');
         exit;
      }
   }else{
      $message = 'حصل خطا , اعد تحميل الصفحة';
      header('Location: ../');
      exit;
   }
   
   if(isset($_GET['mod'], $_GET['post_id']) and $_GET['mod'] == 'edit' ){
      $mode = 'edit';
   }else{
      $mode = 'new';
   }

   // if(isset($_GET['post_id'])){
   //    $all_posts = get_all_messages($page_info , true);
   //    if(isset($all_posts[$_GET['post_id']])){
   //       $data = $all_posts[$_GET['post_id']];
   //    }else{
   //       header('Location: error.php?error=ERROR_WRONG_POST_ID');
   //       exit;
   //    }
   // }
   
   if(isset($_SERVER['REQUEST_METHOD']) and $_SERVER['REQUEST_METHOD'] == 'POST'){
      if(($info = filter_post_data($_POST)) != false){
         if($mode == 'new'){               
            $id = create_post($info, $page_info);
            unset($_POST);
            header('Location: ' . 'p/index.php?page_id=' . $page_info['id'] . '&post_id=' . $id);
            exit;
         }else if($mode == 'edit' and isset($_GET['post_id'])){
            if(($id = edit_post($info , $page_info , $_GET['post_id'])) == false){
               $message = 'faild to edit post ';
            }else{
               unset($_POST);
               header('Location: ' . 'p/index.php?page_id=' . $page_info['id'] . '&post_id=' . $id);
               exit;
            }
         }else{
            $message = 'معرف رسالة خاطئ';
         }
      }else{
         $message = 'الرجاء ملئ جميع الحقول';
      }
      $data = $_POST;
   }elseif($mode == 'edit'){
      if(($data = getPostData($page_id, $_GET['post_id'])) == false){
         unset($data);
      }
   }
   $dir = ((isset($page_info['dir'])) ? $page_info['dir'] : 'rtl');
   
?>


   <div class="page-title">
      <a href=<?php echo 'profile.php?id=' . $page_info['id']?>><h2><?php echo $page_info['title']?></h2></a>
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
            <?php echo (($mode == 'edit') ? 'تعديل المنشور (' . $data['title'] . ')' : 'انشاء منشور') ?>
            </div>
            <form action="<?php echo $_SERVER['PHP_SELF'] . '?page_id=' . $page_id . '&mod=' . $mode . ((isset($_GET['post_id'])) ? '&post_id=' . $_GET['post_id'] : '')?>" method="POST">
               <div class="icon">
                  <i class="fas fa-image"></i>
               </div>
               <div class="button-list">
                  <div id="code" class="item super-item" onclick="setActive('code')">
                     <i class="fas fa-code"></i>
                     <span>كود</span>
                  </div>
                  <div id="url" class="item super-item" onclick="setActive('url')">
                     <i class="fas fa-globe"></i>
                     <span>رابط</span>
                  </div>
                  <div id="post" class="item super-item" onclick="setActive('post')">
                     <i class="fas fa-message"></i>
                     <span>منشور</span>
                  </div>
               </div>
               <div class="info">
                  <input class="text-input grow" type="text" name="title" placeholder="العنوان" maxlength="<?php echo MAX_POST_TITLE_LETTERS?>" value="<?php echo (isset($data['title']) ? $data['title'] : '' )  ?>">
                  <select name="dir" class="text-input grow">
                     <option <?php echo (isset($data['dir']) ? (($data['dir'] == 'rtl') ? 'selected' : '') : (($dir == 'rtl') ? 'selected' : '')) ?> value="rtl">عربي</option>
                     <option <?php echo (isset($data['dir']) ? (($data['dir'] == 'ltr') ? 'selected' : '') : (($dir == 'ltr') ? 'selected' : '')) ?> value="ltr">انجليزي</option>
                  </select>
               </div>
               <textarea name="code" id="code-input" class="text-input" placeholder="ادخل الكود"><?php echo (isset($data['code']) ? $data['code'] : '' )  ?></textarea>
               <input id="url-input" class="text-input grow" type="text" name="url" placeholder="الرابط" value="<?php echo (isset($data['url']) ? $data['url'] : '' )  ?>">
               <textarea class="text-input" type="text" name="content" placeholder="محتوى المنشور" maxlength="<?php echo MAX_CONTENT_LETTERS?>" ><?php echo(isset($data['content']) ? $data['content'] : '' )  ?></textarea>
               <button class="button" type="submit">نشر</button>
            </form>
         </div>
      </div>
   </main>



   <script>
      function setActive(btn){
         const post = document.getElementById('post');
         const url = document.getElementById('url');
         const code = document.getElementById('code');
         post.classList.remove('on');
         url.classList.remove('on');
         code.classList.remove('on');
         document.getElementById(btn).classList.add('on');
         const urlInput = document.getElementById('url-input');
         const codeInput = document.getElementById('code-input');
         codeInput.style.display = 'none';
         urlInput.style.display = 'none';
         if(btn == 'url'){
            urlInput.style.display = 'block';
         }else if(btn == 'code'){
            codeInput.style.display = 'block';
         }
      }
   </script>
<?php
   if(isset($data['code'])){ ?>
      <script>setActive('code')</script>
   <?php }else
   if(isset($data['url'])){ ?>
      <script>setActive('url')</script>
   <?php }else{ ?>
      <script>setActive('post')</script>
   <?php }
   require_once 'static/footer.php';
?>