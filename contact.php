<?php
// no need to upoade 
   require_once 'check.php';
?>



   <main class="section">
      <div class="container contact-container center-container main-container">
         <div class="input-box">
            <div class="title">
               اتصل بنا
            </div>
            <form action="#">
               <div class="info">
                  <input class="text-input grow" type="text" name="name" placeholder="الاسم">
                  <input class="text-input grow" type="text" name="title" placeholder="عنوان الرسالة">
               </div>
               <textarea class="text-input" type="text" name="message" placeholder="الرسالة"></textarea>
               <button class="button" type="submit">ارسال</button>
            </form>
         </div>
      </div>
   </main>


<?php
   require_once 'static/footer.php';
?>