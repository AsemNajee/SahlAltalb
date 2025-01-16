<?php


   require_once 'check.php';

   $_SESSION = get_user_data($_SESSION['id']);
   if(!has_permission($_SESSION , STATUS_OWNER)){
      header('Location: error.php?error=NO_PERMISSIONS');
      exit;
   }

   if(isset($_GET['type'])){
      if(!is_numeric($_GET['type'])){
         exit;
      }
      if($_GET['type'] <= 16 and $_GET['type'] >= -2){
         if($_GET['type'] >= 12){
            // this prosess wil take some time , not alot of time .
            $pages = get_all_pages();
         }else{
            // $users = get_all_users(); 
            // is so easy to do but its take a lot of time in server .
         }
      }else{
         exit;
      }
   }


   // foreach($data_list as $item){
   //    if($item['type'] == $_GET['type']){
   //       $list[] = $item;
   //    }
   // }
   ?>

<script>
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
         return {'color':'#000','icon':'fa-circle','title':'unknown'}
      }
      return typesIcons[icon];
   }
   // const users = <?php #echo json_encode($users); ?> ; // not the best time to do this prosess .
   const pages = <?php echo json_encode($pages); ?> ;
   onload = function(){
      printData()
   }
   function printData(){
      const grid = document.getElementById('grid')
      // globalThis grid;
      for(page in pages){
         let mainLink = document.createElement('a');
         mainLink.href = 'profile.php?id=' + page;
         let item = document.createElement('div');
         item.classList.add('item');
         let i = document.createElement('i');
         i.classList.add('fas')
         i.classList.add((type = getIconData(pages[page]['type']))['icon'])
         i.classList.add('fa-2x')
         console.log(type)
         i.style.color = type['color'] + 'cc'
         i.style.backgroundColor = type['color'] + '25'
         item.appendChild(i)
         let info = document.createElement('div');
         info.classList.add('info');
         let h4 = document.createElement('h4')
         h4.innerHTML = pages[page]['title']
         let span = document.createElement('span')
         span.innerHTML = pages[page]['id']
         info.appendChild(h4)
         info.appendChild(span)
         let div = document.createElement('div');
         div.classList.add('div');
         span.innerHTML = type['text']
         div.appendChild(span)
         span.innerHTML = page
         div.appendChild(span)
         span.innerHTML = 'not programed yet .'
         div.appendChild(span)
         info.appendChild(div)
         item.appendChild(info)
         mainLink.appendChild(item)
         grid.appendChild(mainLink)
      }
   }
</script>
   
   <main class="">
      <div class="container dashboard grid" id="grid">

      </div>





    <?php /*
   if(isset($users)){
   foreach($users as $id => $item){
      if((isset($item['status']) and $item['status'] == $_GET['type']) or  $_GET['type'] == 2 ){?>
      <a href="<?php echo 'profile.php?id=' . $id;?>">
         <div class="items">
            <i class="<?php echo get_icon(get_status($status['status'])) ?> fa-2x"></i>
            <div class="info">
               <h4><?php echo $item['name'] ?></h4>
               <span><?php echo $item['username'] ?></span>
               <div class="div">
                  <span><?php echo get_status($item['status']) ?></span>
                  <span><?php echo $id ?></span>
                  <span><?php echo get_time($item['date']) ?></span>
               </div>
            </div>
         </div>
      </a>
      <?php } 
      }
      }else{
         foreach($pages as $id => $item){
            if((isset($item['type']) and $item['type'] == $_GET['type']) or  $_GET['type'] == 2 ){?>
            <a href="<?php echo 'profile.php?id=' . $id;?>">
               <div class="items">
                  <i class="<?php echo get_icon(get_status($status['type'])) ?> fa-2x"></i>
                  <div class="info">
                     <h4><?php echo $item['title'] ?></h4>
                     <span><?php echo $item['id'] ?></span>
                     <div class="div">
                        <span><?php echo get_status($item['type']) ?></span>
                        <span><?php echo $id ?></span>
                        <span><?php echo get_time($item['date']) ?></span>
                     </div>
                  </div>
               </div>
            </a>
            <?php } 
            }
      }
      */?>
   </main>

   </body>
</html>