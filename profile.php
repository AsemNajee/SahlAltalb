<?php
   require_once 'check.php';

   $private = array('favorite' , 'readletter');
   
   if($_GET['id']){
      if(in_array($_GET['id'] , $private) or $_GET['id'] <= ID_PAGE){
         if(($data = get_page($_GET['id'])) != false){
            $data['name'] = $data['title'];
            $data['username'] = (isset($data['username']) ? $data['username'] : 'non');
            $data['bio'] = $data['bio'];
            $data['status'] = $data['type'];
         }else{
            header('Location: error.php?error=ERROR_WRONG_PAGE_ID');
            exit;
         }
      }elseif($_GET['id'] >= ID){
         $data = getUserData($_GET['id']);
         $data['dir'] = 'rtl';
      }else{
         header('Location: error.php?error=ERROR_WRONG_DATA_SENT');
         exit;
      }
      $dir = (isset($data['dir'])) ? $data['dir'] : 'rtl';
   }else{
      header('Location: error.php?error=ERROR_NO_DATA_SENT');
      exit;
   }
?>

   <div class="hr"></div>
   <main class="section">
      <div class="container main-container" dir="<?php echo $dir; ?>">
         <div class="card-profile">
            <div class="head">
               <div class="img">
                  <i id="icon" class="fas"></i>
               </div>
               <h3 id="name"></h3>
               <div class="dis">
                  <span id="status"></span>
                  <p dir="ltr"><i class="fa-solid fa-at"></i><span id="username" ></span></p>
               </div>
            </div>
            <div class="info">
               <div><span id="bio"></span></div>
            </div>
         </div>
         <div class="list">
            <?php if(isset($data['tags'])){ ?>
               <div class="button-list">
                  <?php foreach($data['tags'] as $item){ ?>
                     <a href="<?php  echo 'search.php?search=' . $item ?>">
                        <div class="item super-item fit-content">
                           <i class="<?php echo 'fas fa-tags'?>"></i>
                           <span><?php echo $item?></span>
                        </div>
                     </a>
                  <?php } ?>
               </div>
             <?php } ?>
         </div>
         <div class="button-list">
            <?php if(($sign) and isset($_SESSION['status']) and $_SESSION['status'] >= STATUS_OWNER){ ?>
               <a href="<?php echo 'edit_status.php?id=' . $data['id'] ?>">
                  <div class="item">
                     <i class="fas fa-user-shield"></i>
                     <span>الصلاحيات</span>
                  </div>
               </a>
            <?php } if(($sign) and $_SESSION['id'] == $data['id']){?>
            <a href="<?php echo 'edit_profile.php'  ?>">
               <div class="item">
                  <i class="fas fa-pencil"></i>
                  <span>تعديل</span>
               </div>
            </a>
            <?php } if(isset($data['status']) and get_id_type($data['status']) == 'page'){?>
            <a href="<?php echo 'p/index.php?page_id=' . $data['id']  ?>">
               <div class="item super-item">
                  <i class="fas fa-share"></i>
                  <span>اننقال الى الصفحة</span>
               </div>
            </a>
            <?php } if($sign and isset($data['creator']) and $data['creator'] == $_SESSION['id']){?>
            <a href="<?php echo 'add_page.php?mode=edit&page_id=' . $data['id']  ?>">
               <div class="item super-item">
                  <i class="fas fa-pen"></i>
                  <span>تعديل الصفحة</span>
               </div>
            </a>
            <?php } ?>
            <div class="item super-item" onclick="share()">
               <i class="fas fa-share"></i>
               <span>مشاركة</span>
            </div>
         </div>
      </div>
   </main>



<?php
   require_once 'static/footer.php';
?>


<script>
   const data = <?php echo json_encode($data) ?> ;
   const info = {
      'name': (data['name'] != undefined) ? data['name'] : '' ,
      'bio': (data['bio'] != undefined) ? data['bio'] : '',
      'username': (data['username'] != undefined) ? data['username'] : '',
      'id': (data['id'] != undefined) ? data['id'] : '',
      'tags': (data['tags'] != undefined) ? data['tags'] : '',
      'status': (data['status'] != undefined) ? data['status'] : '',
      'dir': (data['dir'] != undefined) ? data['dir'] : '',
   }
   // need to update to be like consts in function.php
   function getIconData(icon){
      const typesIcons = {
         '-1':{'color':'#ff0000','icon':'fa-wrong','text':'محظور'},
         1:{'color':'#000','icon':'fa-user','text':'مستخدم'},
         2:{'color':'#000','icon':'fa-user','text':'مستخدم'},
         3:{'color':'#000','icon':'fa-user-graduate','text':'طالب'},
         4:{'color':'#000','icon':'fa-chalkboard-user','text':'معلم'},
         5:{'color':'#000','icon':'fa-user-check','text':'موثق'},
         6:{'color':'#000','icon':'fa-user-tag','text':'مميز'},
         7:{'color':'#000','icon':'fa-user-pen','text':'كاتب'},
         8:{'color':'#666','icon':'fa-user-tie','text':'ناشر'},
         9:{'color':'#000','icon':'fa-user-tie','text':'مشرف'},
         10:{'color':'#1197bc','icon':'fa-user-gear','text':'مطور'},
         11:{'color':'#','icon':'fa-','text':''},
         12:{'color':'#deb887','icon':'fa-newspaper','text':'صفحة'},
         13:{'color':'#4a7fa0','icon':'fa-bullhorn','text':'صفحة موثقة'},
         14:{'color':'#deb887','icon':'fa-landmark','text':'صفحة تقنية'},
         15:{'color':'#deb887','icon':'fa-landmark','text':'صفحة اساسية'},
         16:{'color':'#2e8b57','icon':'fa-circle-check','text':'صفحة رسمية'},
         'private':{'color':'#7e6f6f','icon':'fa-lock','text':'خاص'},
         'public':{'color':'#900300','icon':'fa-circle-question','text':'اختبار عام'},
      }
      if(typesIcons[icon] == undefined){
         return {'color':'#000','icon':'fa-circle','title':''}
      }
      return typesIcons[icon];
   }
   onload = function(){
      const i = document.getElementById('icon');
      i.classList.add(getIconData(info['status'])['icon'])
      i.style.color = getIconData(info['status'])['color'];
      // i.parentElement.style.backgroundColor = getIconData(info['status'])['color'] + '20';
      i.parentElement.style.border = '2px solid ' + getIconData(info['status'])['color'] + 'aa';
      const status = document.getElementById('status');
      status.innerText = getIconData(info['status'])['text'];
      status.style.color = getIconData(info['status'])['color'];
      document.getElementById('name').innerText = info['name'];
      document.getElementById('username').innerText = info['username'];
      document.getElementById('bio').innerText = info['bio'];
   }
   function share(){
         const title = document.getElementById('name').innerText;
         if(navigator.share){
            navigator.share({
               'title' : title,
               'text' : 'visit in Sahl Altalb',
               'url' : location.href
            })
         }
      }
</script>