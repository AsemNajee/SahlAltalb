<?php



   require_once 'check.php';

   
   $_SESSION = get_user_data($_SESSION['id']);
   if(!has_permission($_SESSION, STATUS_POSTER)){
      header('Location: error.php?error=NO_PERMISSIONS');
      exit;
   }

   if(isset($_GET['id'])){
      $page_id = filter_var($_GET['id'] , FILTER_VALIDATE_INT);
   }
   $path = get_page_path($page_id) . 'users.json';
   if(($page_users = read_file($path)) == false){
      $page_users = [];
   }
   // $users = get_all_users();
   
?>


   <!-- <div class="page-title">
      <a href=<?php #echo 'profile.php?id=' . $page_info['id']?>>
         <h2><?php #echo $page_info['title']?></h2>
      </a>
   </div> -->

   <main >
      <div class="container">
         <span>المشتركون : <span><?php echo count($page_users) ?></span></span>
         <div id="list-users" class="list-users grid">
         
         </div>
      </div>
   </main>

   <script>
      const pageID = <?PHP ECHO $page_id ?> ;
      const PAGE_LEVEL_POSTER = <?PHP ECHO PAGE_LEVEL_POSTER ?> ;
      const PAGE_LEVEL_ADMIN = <?PHP ECHO PAGE_LEVEL_ADMIN ?> ;
      onload = function(){
         printUsers();
      }
      function printUsers(){
         const users = <?php echo json_encode($page_users); ?> ;
         const listUsers = document.getElementById('list-users');
         for(user in users){
            let container = document.createElement('div')
            container.classList.add('item');
            let a = document.createElement('a');
            a.href = 'profile.php?id=' + user;
            let div = document.createElement('div');
            div.id = user;
            let i = document.createElement('i');
            i.classList.add('fas')
            if(users[user]['status'] != undefined){
               i.classList.add((iconData = getIconData(users[user]['status']))['icon']);
            }else{
               i.classList.add((iconData = getIconData(1))['icon']);
            }
            i.style.color = iconData['color'];
            div.style.backgroundColor = iconData['color'] + '29';
            let h4 = document.createElement('h4');
            h4.innerText = users[user]['name'];
            div.appendChild(i);
            div.appendChild(h4);
            a.appendChild(div);
            if(users[user]['status'] == PAGE_LEVEL_POSTER){
               container.style.order = '-1';
            }else
            if(users[user]['status'] == PAGE_LEVEL_ADMIN){
               container.style.order = '-2';
            }
            i = document.createElement('i');
            i.classList.add('fas');
            i.classList.add('fa-user-shield');
            i.onclick = function(){
               setPoster(user)
            }
            container.appendChild(a)
            container.appendChild(i)
            listUsers.appendChild(container);
         }
      }
      function setPoster(userID){
         const div = document.getElementById(userID);
         const url = './api/set_poster.php?page_id=' + pageID + '&user_id=' + userID;
         fetch(url).then(response => response.json())
         .then(data => {
            if(data['ok'] == 'succes'){
               if(data['message'] == 'poster'){
                  div.firstElementChild.style.color = getIconData(PAGE_LEVEL_POSTER)['color']
                  div.parentElement.style.order = -1;
               }else if(data['message'] == 'user'){
                  div.parentElement.style.order = 1;
               }else{
                  alert('some thig is wrong');
               }
            }else{
               alert(data['message'])
            }
         })
      }

      function getIconData(icon){
         const typesIcons = {
            '-1':{'color':'#ff0000','icon':'fa-wrong','text':'محظور'},
            1:{'color':'#666666','icon':'fa-user','text':'مستخدم'},
            2:{'color':'#deb887','icon':'fa-user-pen','text':'ناشر'},
            3:{'color':'#2e8b57','icon':'fa-user-tie','text':'مشرف'}
         }
         if(typesIcons[icon] == undefined){
            return {'color':'#000','icon':'fa-circle','title':''}
         }
         return typesIcons[icon];
      }
   </script>

<?php
   require_once 'static/footer.php';

?>