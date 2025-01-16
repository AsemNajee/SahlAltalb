
<div id="list-sections" class="button-list">
   <div id="all" class="super-item item" onclick="view('all')">
      <i class="fas fa-news-paper"></i>
      <span>الكل</span>
   </div>
   <div id="post" class="super-item item " onclick="view('post')">
      <i class="fas fa-message"></i>
      <span>المنشورات</span>
   </div>
   <div id="code" class="super-item item " onclick="view('code')">
      <i class="fas fa-code"></i>
      <span>الاكواد</span>
   </div>
   <div id="url" class="super-item item " onclick="view('url')">
      <i class="fas fa-globe"></i>
      <span>الروابط</span>
   </div>
   <div id="notify" class="super-item item " onclick="view('notify')">
      <i class="fab fa-slack" ></i>
      <span>الاشعارات</span>
   </div>
</div>


<main>
   <div class="container main-container">
      <div class="viewer">
         <div id="turn-posts" class="turn-posts" style="display: none;">
            <i id="next" class="fas fa-angle-right"></i>
            <span id="turn-mode"><i class="fas fa-angle-down"></i>
               <span>
                  قائمة المنشورات
               </span>
            </span>
            <i id="last" class="fas fa-angle-left"></i>
         </div>
         <div id="all-posts" class="all-posts" >
            <div id="messages-list" class="messages-list" dir="<?php echo $page['dir']; ?>">
               <div id="grid" class="grid">
               
               </div>
            </div>
         </div>
         <div id="post-viewer" class="post-viewer" style="display: <?php echo (isset($post) ? 'flex' : 'none') ?>;" >
            <div class="message">
               <a href="#" id="profile" style="display: none;">
                  <div class="preview" dir="rtl">
                     <i id="prev-i" class="fas"></i>
                     <div class="content">
                        <h3 id="prev-title">loading ...</h3>
                        <span id="prev-bio">...</span>
                     </div>
                  </div>
               </a>
               <div class="code-viewer" dir="ltr" lang="en" id="code-viewer" style="display: none;">
               </div>
               <div class="title" >
                  <i id="post-icon" class="fas"></i>
                  <h2 id='title' class="title">Loading ...</h2>
               </div>
               <p id="content">Loading ...</p>
               <div class="wrapper">
               <div class="share button-list">
                  <div id="share" class="item">
                     <i class="fas fa-share"></i>
                     <span>مشاركة</span>
                  </div>
                  <?php if($sign){ ?>
                     <div id="unlike" class="item">
                        <i class="fa-solid fa-thumbs-down"></i>
                        <span>لا يعجبني</span>
                     </div>
                     <div id="like" class="item">
                        <i class="fas fa-thumbs-up"></i>
                        <span>يعجبني</span>
                     </div>
                     <div id="favorite" class="item">
                        <i class="fas fa-heart"></i>
                        <span>مفضل</span>
                     </div>
                     <div id='copy'class="item">
                        <i class="fas fa-copy"></i>
                        <span>نسخ</span>
                     </div>
                        <?php if(isset($_SESSION['id']) and $_SESSION['id'] == $page['creator'] ){ ?>
                           <a id="editPost" href="">
                              <div class="item">
                                 <i class="fas fa-pen"></i>
                                 <span>تعديل</span>
                              </div>
                           </a>
                           <div id="delPost" class="item">
                              <i class="fas fa-trash-can"></i>
                              <span>حذف</span>
                           </div>
                        <?php } ?>
                     <?php } ?>
                  </div>
               </div>
               <div class="wrapper post-attributs">
                  <span id="post-views"></span>
                  <span id="post-poster"></span>
                  <span id="post-time"></span>
               </div>
            </div>
         </div>
      </div>
      
   </div>
</main>

<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/highlight.min.js"></script>
<!-- functions -->
<script >
   const page = <?php echo '\'' . $page['id'] . '\'';?> ;
   const posts = <?php echo json_encode($posts); ?>;

   const profile = document.getElementById('profile');
   const codeViewer = document.getElementById('code-viewer');
   const title = document.getElementById('title');
   const content = document.getElementById('content');
   const views = document.getElementById('post-views');
   const poster = document.getElementById('post-poster');
   const time = document.getElementById('post-time');
   const turnModeBtn = document.getElementById('turn-mode');
   const next = document.getElementById('next');
   const last = document.getElementById('last');

      

   const btnPost= {
      'icon':'fa-copy',
      'text':'نسخ',
   };
   const btnURL = {
      'icon':'fa-globe',
      'text':'انتقال',
   };
   const icons = {
      'post':{
         'title':'POST',
         'icon':'fa-message',
         'btn': btnPost
      },
      'code':{
         'title':'CODE',
         'icon':'fa-code',
         'btn': btnPost
      },
      'url':{
         'title':'URL',
         'icon':'fa-globe',
         'btn': btnURL
      },
   };


   
   onload = function(){
      turnMode('list');
      view('all');
      <?php 
      if(isset($post['id'])){?>
         var postID = <?php echo '\'' . $post['id'] . '\''?>;
         getPost(pageID, postID);
      <?php }else{?>
         var postID = 0;
      <?php } ?>
   }
   turnModeBtn.onclick = function(){
      turnMode('list');
   }
   next.onclick = function(){
      getPost(postID + 1);
   }
   last.onclick = function(){
      getPost(postID - 1);
   }
   
   // updated
   function turnMode(mode){
      const list = document.getElementById('all-posts');
      const postTemp = document.getElementById('post-viewer');
      // // console.log(list, postTemp)
      if(mode == 'post'){
         postTemp.style.display = 'block';
         list.style.display = 'none';
         document.getElementById('turn-posts').style.display = 'flex';
         document.getElementById('list-sections').style.display = 'none';
      }else{
         postTemp.style.display = 'none';
         list.style.display = 'grid';
         document.getElementById('list-sections').style.display = 'flex';
         document.getElementById('turn-posts').style.display = 'none';
      }
   }
   // updated
   function changePost(postid){
      codeViewer.style.display = 'none';
      profile.style.display = 'none';
      title.innerText = document.getElementById('title-' + postid).innerHTML;
      views.innerHTML = `<i class="fas fa-eye"></i>`;
      views.innerHTML += document.getElementById('views-' + postid).innerText;
      poster.innerHTML = `<i class="fas fa-pen"></i>`;
      poster.innerHTML += document.getElementById('poster-' + postid).innerText;
      content.innerText = '...تحميل المنشور';
      turnMode('post');
      getPost(postid);
   }


   const share = document.getElementById('share');
   const unlike = document.getElementById('unlike');
   const like = document.getElementById('like');
   const favorite = document.getElementById('favorite');
   const copy = document.getElementById('copy');
   const editPost = document.getElementById('editPost');
   const delPost = document.getElementById('delPost');
   
   share.onclick = function(){
      shareData(
         title.innerText, 
         'اقرأ المنشور كاملا من خلال الرابط التالي',
         location.origin + location.pathname + '?page_id=' + pageID + '&post_id=' + postID
      );
   }
   <?php if($sign){ ?>
      unlike.onclick = function(){
         setlike(postID, 'unlike');
      }
      like.onclick = function(){
         setlike(postID, 'like');
      }
      favorite.onclick = function(){
         setfavorite(postID);
      }
   <?php } ?>



// updated
function printPosts(posts){
   const grid = document.getElementById('grid');
   for(let post in posts){
      div = document.createElement('div');
      if(posts[post]['type'] == 'notify'){
         div.classList.add('notify');
         const i = document.createElement('i');
         i.classList.add('fab');
         i.classList.add('fa-slack');
         div.appendChild(i);
         const span = document.createElement('span');
         span.innerText = posts[post]['content'];
         div.appendChild(span);
         item = document.createElement('item');
         item.appendChild(div);
         grid.appendChild(item);
      }else{
         div = posting(posts[post]);
         grid.appendChild(div);
      }
   }
}

// updated
function posting(post){
   const item = document.createElement('div');
   const container = document.createElement('div');
   let text;
   item.classList.add('item');
   container.classList.add('post-templete');
   container.classList.add('mark');
   const content = document.createElement('div');
   content.classList.add('content');
   const title = document.createElement('div');
   title.classList.add('title');
   const h3 = document.createElement('h3');
   h3.innerText = post['title'];
   h3.id = 'title-' + post['id'];
   const type = document.createElement('div');
   type.classList.add('type');
   let i = document.createElement('i');
   i.classList.add('fas');
   i.classList.add(icons[post['type']]['icon']);
   type.appendChild(i);
   text = document.createTextNode(icons[post['type']]['title']);
   type.appendChild(text);
   title.appendChild(h3);
   title.appendChild(type);
   content.appendChild(title);
   let p = document.createElement('p');
   p.innerText = post['content'];
   content.appendChild(p);
   const attributs = document.createElement('div');
   attributs.classList.add('attributs');
   const show = document.createElement('span');
   show.classList.add('show-post');
   show.id = post['id'];
   i = document.createElement('i');
   i.classList.add('fas');
   i.classList.add('fa-quote-righ');
   show.appendChild(i);
   text = document.createTextNode('عرض');
   show.appendChild(text);
   attributs.appendChild(show); ///////////////////////
   span = document.createElement('span');
   i = document.createElement('i');
   i.classList.add('fas');
   i.classList.add('fa-pen');
   span.appendChild(i);
   text = document.createTextNode(post['poster']);
   span.appendChild(text);
   span.id = 'poster-' + post['id'];
   attributs.appendChild(span);
   span = document.createElement('span');
   i = document.createElement('i');
   i.classList.add('fas');
   i.classList.add('fa-eye');
   span.appendChild(i);
   text = document.createTextNode(post['views']);
   span.appendChild(text);
   span.id = 'views-' + post['id'];
   attributs.appendChild(span);
   content.appendChild(attributs);
   container.appendChild(content);
   item.appendChild(container);
   return item;
}

function getList(list,type){
   let output = {};
   if(type == 'all'){
      return list;
   } 
   for(let id in list){
      if(list[id]['type'] == type){
         output[id] = list[id];
      }
   }
   return output;
}
function turn(type){
   const all = document.getElementById('all');
   const post = document.getElementById('post');
   const code = document.getElementById('code');
   const url = document.getElementById('url');
   const notify = document.getElementById('notify');
   all.classList.remove('on');
   post.classList.remove('on');
   code.classList.remove('on');
   url.classList.remove('on');
   notify.classList.remove('on');
   document.getElementById(type).classList.add('on');
}
  
function getPost(postID){
   const path = '../api/get_post.php?page_id=' + pageID + '&post_id=' + postID;
   const data = fetch(path)
   .then(response => response.json())
   .then(data => {         
      if(data['ok'] == 'succes'){
         turnMode('post');
         printPost(data['post']);
      }else{
         // console.log(data['message']);
      }
   });
}
function getUrl(url){
   const path = '../api/get_url.php?url=' + url ;
   fetch(path)
   .then(response => response.json())
   .then(data => {
      if(data['ok'] == 'succes'){
         printProfiel(data['data'])
      }else{
         profile.style.display = 'none';
      }
   })
   .then(err => {
      profile.style.display = 'none';
      // console.log('faild to get data')
   })
}

// updated
function printPost(DataPost){
   postID = DataPost['id'];
   title.innerText = DataPost['title'];
   content.innerText = DataPost['content'];
   time.innerHTML = `<i class="fas fa-gauge"></i>`;
   time.innerHTML += DataPost['date'];
   <?php if($sign and $page['id'] != 'favorite'){ ?>
      editPost.href = '../add_post.php?page_id=' + pageID + '&post_id=' + postID + '&mod=edit';
   <?php } ?>
   if(DataPost['type'] == 'post'){
      codeViewer.style.display = 'none';
      profile.style.display = 'none';
   }else if(DataPost['type'] == 'code'){
      profile.style.display = 'none';
      codeViewer.innerHTML = DataPost['code'];
      const copybtn = document.createElement('div');
      copybtn.classList.add('copydiv');
      copybtn.innerText = 'COPY CODE';
      codeViewer.append(copybtn);
      codeViewer.style.display = 'block';
      hljs.highlightAll();
   }else if(DataPost['type'] == 'url'){
      codeViewer.style.display = 'none';
      const i = document.getElementById('prev-i');
      const pT = document.getElementById('prev-title');
      const pB = document.getElementById('prev-bio');
      i.classList.add('fas');
      i.classList.add('fa-user');
      profile.style.display = 'block';
      pT.innerText = 'تحميل ...';
      pB.innerText = '...';
      getUrl(DataPost['url']);
   }
}
// updated
function subscripe(){
   const url = './../api/subscripe.php?page_id= '+ pageID;
   fetch(url)
   .then(response => response.json())
   .then(data => {
      // console.log(data)
      const sub = document.getElementById('sub');
      if(data['ok'] == 'succes'){
         if(data['message'] == 'join'){
            sub.classList.add('on');
         }else{
            sub.classList.remove('on');
         }
      }else{
         alert(data.message);
      }
   })
}
// updated
function shareData(title, text, url){
   if(navigator.share){
      navigator.share({
         'title' : title,
         'text' : text,
         'url' : url
      })
   }
}
function setlike(postID, state){
   const url = './../api/like.php?page_id=' + pageID + '&post_id=' + postID + '&state=' + state;
   fetch(url+'&state=' + state).then(respons => respons.json())
   .then(data =>{
      if(data['ok'] == 'succes'){
         if(data['like'] == 1){
            like.classList.add('on');
            unlike.classList.remove('on');
         }else if(data['like'] == -1){
            unlike.classList.add('on');
            like.classList.remove('on');
         }else{
            like.classList.remove('on');
            unlike.classList.remove('on');
         }
      }else{
         alert(data['message'])
      }
   })
   .then(err =>{
      // console.log(err);
   });
}
function setfavorite(postID){
   const url = './../api/add_to_favorite.php?page_id='+pageID+'&post_id='+postID;
   fetch(url).then(response => response.json())
   .then(data => {
      if(data['message'] == 'added'){
         favorite.classList.add('on');
      }else{
         favorite.classList.remove('on');
      }
   })
}
function copyPost(){
   text = document.getElementById('post').innerHTML;
   navigator.clipboard.writeText(text)
   .then(() => alert('post copied'))
   .catch(() => alert('faild to copy post'));
}
function deletePost(postID){
   const url = './../api/delete_post.php?page_id=' + pageID + '&post_id=' + postID;
   fetch(url).then(response => response.json())
   .then(data => {
      if(data['message'] == 'deleted'){
         post.innerHTML = '';
      }else{
         // console.log('faild to delete the post');
      }
   })
}
function view(type){
   const main = document.getElementById('messages-list');
   main.innerHTML = '<div id="grid" class="grid"></div>';
   printPosts(getList(posts,type));
   const shows = document.querySelectorAll('.show-post');
   shows.forEach(element => {
      element.addEventListener('click' , function(){
         changePost(element.id);
      })
   });
   turn(type);
}
</script>


<!-- for posts in grid -->