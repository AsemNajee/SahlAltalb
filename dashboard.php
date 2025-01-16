<?php


   require_once 'check.php';

   $_SESSION = get_user_data($_SESSION['id']);
   if(!has_permission($_SESSION , STATUS_ADMIN)){
      header('Location: error.php?error=NO_PERMISSIONS');
      exit;
   }

   $report = read_file(FILE_REPORT);
   if($report == false){
      echo 'no reports yet';
      exit;
   }

   foreach($report as $id => $value){
      if(in_array($id, ['users','pages'])){
         continue;
      }
      if($id < TYPE_PAGE){ // what about 11 ?
         $all_users[$id] = $value; 
      }elseif($id >= TYPE_PAGE){
         $all_pages[$id] = $value;
      }
   }
   if(!isset($all_pages)){
      $all_pages[TYPE_PAGE] = [];
   }


   ?>
   <script>
      const pages = <?php echo json_encode($all_pages); ?> ;
      const users = <?php echo json_encode($all_users); ?> ;
      // const exams = <?php # echo json_encode($all_exams); ?> ;
      const typesIcons = {
         '-1':{'color':'#ff0000','icon':'fa-wrong','text':'محظور'},
         2:{'color':'#000','icon':'fa-user','text':'مستخدم'},
         3:{'color':'#000','icon':'fa-user-graduate','text':'طالب'},
         4:{'color':'#000','icon':'fa-chalkboard-user','text':'معلم'},
         5:{'color':'#000','icon':'fa-user-check','text':'موثق'},
         6:{'color':'#000','icon':'fa-user-tag','text':'مميز'},
         7:{'color':'#000','icon':'fa-user-pen','text':'كاتب'},
         8:{'color':'#000','icon':'fa-user-tie','text':'ناشر'},
         9:{'color':'#000','icon':'fa-','text':'مشرف'},
         10:{'color':'#','icon':'fa-user-gear','text':'مطور'},
         11:{'color':'#','icon':'fa-','text':''},
         12:{'color':'#deb887','icon':'fa-newspaper','text':'صفحة'},
         13:{'color':'#4a7fa0','icon':'fa-bullhorn','text':'صفحة موثقة'},
         14:{'color':'#deb887','icon':'fa-landmark','text':'صفحة تقنية'},
         15:{'color':'#deb887','icon':'fa-landmark','text':'صفحة اساسية'},
         16:{'color':'#2e8b57','icon':'fa-circle-check','text':'صفحة رسمية'},
         'private':{'color':'#7e6f6f','icon':'fa-lock','text':'اختبار خاص'},
         'public':{'color':'#900300','icon':'fa-circle-question','text':'اختبار عام'},
      }
      function printBody(sections , id){
         const body = document.getElementById(id);
         for(type in sections){
            let a = document.createElement('a');
            let div = document.createElement('div');
            let i = document.createElement('i');
            let h4 = document.createElement('h4');
            let span = document.createElement('span');
            a.href = 'view_report_list.php?type='+ type;
            div.classList.add('item');
            i.classList.add('fas');
            i.classList.add(typesIcons[type]['icon']);
            i.classList.add('fa-2x');
            i.style.color =  typesIcons[type]['color'];
            h4.innerHTML = type;  // need to change into count , not the type .
            span.innerHTML =  typesIcons[type]['text'];
            div.appendChild(i);
            div.appendChild(h4);
            div.appendChild(span);
            a.appendChild(div);
            body.appendChild(a);
         };
      }
      function print(data, id){
         body.appendChild(printBody(data, id));
      }
      onload = function(){
         printBody(users, 'users');
         printBody(pages, 'pages');
      }
   </script>
   
   <main class="">
      <div class="dash-container">
         <!-- <div class="sidebar">
            <div class="head">
               <i class="fas fa-user-tie"></i>
            </div>
            <div class="body">
               <div class="item">
                  <i class="fas fa-gear"></i>
                  <h4>users dashboard</h4>
               </div>
               <div class="item">
                  <i class="fas fa-gear"></i>
                  <h4>pages dashboard</h4>
               </div>
               <div class="item">
                  <i class="fas fa-gear"></i>
                  <h4>website dashboard</h4>
               </div>
               <div class="item">
                  <i class="fas fa-gear"></i>
                  <h4>settings</h4>
               </div>
            </div>
         </div> -->
         <div class="dash-page">
            <div class="main">
               <div class="section">
                  <div class="section-title">
                     <h3>PAGES</h3>
                     normal pages and all another types of pages .
                  </div>
                  <div id="pages" class="body">
                     
                  </div>
               </div>
               <div class="section">
                  <div class="section-title">
                     <h3>USERS</h3>
                     all users in the website, with deferent status.
                  </div>
                  <div id="users" class="body">
                     
                  </div>
               </div>
               <div class="section">
                  <div class="section-title">
                     <h3>EXAMS</h3>
                     exams are to kinds here, public for all users, 
                     and private that cannot any body enter in without its link.
                  </div>
                  <div id="exams" class="body">
                     
                  </div>
               </div>
            </div>
         </div>
      </div>
   </main>
   </body>
</html>