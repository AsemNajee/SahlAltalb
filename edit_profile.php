<?php
// updated
   require_once 'check.php';
   

   if(!$sign){
      header('Location: error.php?error=ONLY_USERS');
      exit;
   }
   

   if(isset($_SERVER['REQUEST_METHOD']) and $_SERVER['REQUEST_METHOD'] == 'POST'){
      if(($data = edit_user($_POST)) != false){
         header('Location: profile.php?id=' . $_SESSION['id']);
         exit;
      }else{
         $message = 'field to edit profile';
      }
   }
   $_SESSION = get_user_data($_SESSION['id']);
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
            <?php echo $_SESSION['name'] ?>
            </div>
            <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
               <div class="icon">
                  <i id="icon-i" class="fas"></i>
               </div>
               <div class="info">
                  <input class="text-input grow" type="text" name="name" placeholder="name" value="<?php echo (isset($_SESSION['name']) ? $_SESSION['name'] : '')  ?>">
                  <input class="text-input grow" type="text" name="bio" placeholder="bio" value="<?php echo (isset($_SESSION['bio']) ? $_SESSION['bio'] : '')  ?>">
               </div>
               <div class="info">
                  <input class="text-input grow" type="text" name="username" placeholder="username" value="@<?php echo (isset($_SESSION['username']) ? $_SESSION['username'] : '')  ?>">
                  <input disabled class="text-input grow" type="text" name="id" placeholder="id" value="<?php echo (isset($_SESSION['id']) ? $_SESSION['id'] : '')  ?>">
               </div>
               <textarea class="text-input" type="text" name="tags" placeholder="tags" ><?php echo (isset($_SESSION['tags']) ? implode(', ' , $_SESSION['tags']) : '')  ?></textarea>
               <button class="button" type="submit">حفظ</button>
            </form>
         </div>
      </div>
   </main>


<?php
   require_once 'static/footer.php';
   function filter_data($data){
      if(isset($data['name'] , $data['username'] , $data['bio'] , $data['tags'] )){
         $output['name'] = str_filter($data['name']);
         $output['username'] = preg_replace('[^a-zA-Z0-9_]' , '' , $data['username']);
         $output['bio'] = str_filter($data['bio']);
         $output['tags'] = str_filter($data['tags']);
         return $output;
      }else{
         return false;
      }
   }

?>
<script>

   function getIconData(icon){
      const allIcons = {
         '-1':{'color':'#deb887','icon':'fa-user-slash','title':'pan'},
         1:{'color':'#deb887','icon':'fa-user-graduate','title':'beginner'},
         2:{'color':'#deb887','icon':'fa-user','title':'user'},
         3:{'color':'#deb887','icon':'fa-book-open-reader','title':''},
         4:{'color':'#deb887','icon':'fa-newspaper','title':''},
         5:{'color':'#deb887','icon':'fa-newspaper','title':''},
         6:{'color':'#deb887','icon':'fa-user-tag','title':'vip'},
         7:{'color':'#deb887','icon':'fa-newspaper','title':''},
         8:{'color':'#deb887','icon':'fa-user-pen','title':'poster'},
         9:{'color':'#deb887','icon':'fa-user-tie','title':'admin'},
         10:{'color':'#deb887','icon':'fa-user-gear','title':'div'},
      }
      if(allIcons[icon] == undefined){
         return {'color':'#000','icon':'circle','title':'undefind'}
      }
      return allIcons[icon];
   }
   onload = function(){
      document.getElementById('icon-i').classList.add(getIconData(<?php echo (isset($_SESSION['status']) ? $_SESSION['status'] : 2) ?>)['icon'])
   }
</script>