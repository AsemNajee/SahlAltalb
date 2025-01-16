<?php


// error_reporting(0);
define('LANGUAGE' , 'ar');

define('DATA_ROOT' ,  LOCAL_ROOT . 'data/');
define('FILE_PAGES' , DATA_ROOT . 'pages.json');
define('FILE_EXAMS' , DATA_ROOT . 'exams.json');
define('FILE_USERNAMES' , DATA_ROOT . 'usernames.json');
define('FILE_REPORT' , DATA_ROOT . 'report.json');
define('ID_PAGE' , -1001);
define('ID' , 1001);
define('ENCRIPT' , 'sahlAltalb.net');

define('MAX_POST_TITLE_LETTERS' , 70);
define('MAX_CONTENT_LETTERS' , 1000);


define('MIN_USERNAME_LETTERS' , 5);
define('MAX_USERNAME_LETTERS' , 16);
define('MIN_NAME_LETTERS' , 3);
define('MAX_NAME_LETTERS' , 30);
define('MIN_PASSWORD_LETTERS' , 8);
define('MAX_PASSWORD_LETTERS' , 16);

define('MAX_BIO_LETTERS' , 70);
define('MAX_TAGS_LETTERS' , 100);
define('MAX_TAGS_COUNT' , 10);

define('STATUS_USER' , 2);
define('STATUS_REAL' , 3);
define('STATUS_DEV' , 4);
define('STATUS_TEACHER' , 5);
define('STATUS_VIP' , 6);
define('STATUS_WRITER' , 7);
define('STATUS_POSTER' , 8);
define('STATUS_ADMIN' , 9);
define('STATUS_OWNER' , 10);

define('TYPE_PAGE' , 12);
define('TYPE_SUPER_PAGE' , 13);

define('PAGE_LEVEL_POSTER' , 2);
define('PAGE_LEVEL_ADMIN' , 3);


// login functions
function get_id_by_username($username){
   if(!is_file(FILE_USERNAMES)){
      return false;
   }
   $username = strtolower($username);
   if(($data = file_get_contents(FILE_USERNAMES)) != false){
      $data = json_decode($data, true);
      if(isset($data[$username])){
         return $data[$username];
      }
   }
   return false;
}
function check_username_validation($username){
   if(is_numeric(substr($username , 0 , 1))){
      return false;
   }
   if(get_id_by_username($username) != false){
      return false;
   }
   return true;
}
function check_login($user_id , $password){
   if(!is_numeric($user_id)){
      if(($user_id = get_id_by_username($user_id)) == false){
         return false;
      }
   }
   $file_name = DATA_ROOT . get_file_name($user_id, 'user');
   if(($data = read_file($file_name)) != false){
      if(isset($data[$user_id])){
         if($data[$user_id]['password'] == md5($password . ENCRIPT)){
            return $user_id;
         }
      }
   }
   return false;
}
function login($data_user){
   if(check_username_validation($data_user['username']) !== false){
      if(($report = read_file(FILE_REPORT)) === false){
         $report = ['users'=> 0];
      }
      $temp['name'] = $data_user['name'];
      $temp['username'] = $data_user['username'];
      $temp['password'] = md5($data_user['password'] . ENCRIPT);
      $temp['date'] = (int)(time());
      $id = ID + $report['users']++;
      $report[STATUS_USER][] = $id;
      $report['users'] ++ ;
      if(($data = read_file(($file_name = DATA_ROOT . get_file_name($id, 'user')))) === false){
         $data = [];
      }
      $data[$id] = $temp;
      if(!is_dir(DATA_ROOT)){
         mkdir(DATA_ROOT , 0777 , true);
      }
      save_data($file_name , json_encode($data));
      save_data(FILE_REPORT, json_encode($report));
      save_username($temp['username'], $id);
      // save_data(FILE_USERNAMES, json_encode([$temp['username'] => $id]));
      $temp['id'] = $id;
      return $temp;
   }else{
      return false;
   }
}

// page functions
// private function
function get_all_pages(string $path = 'all'){
   if($path == 'all'){
      if(!file_exists(FILE_PAGES)){
         return false;
      }
      if(($pages = file_get_contents(FILE_PAGES)) != false){
         return json_decode($pages, true);
      }else{
         return [];
      }
   }
}
// public function
function getPagesData(){
   if(($all = get_all_pages()) != false){
      foreach($all as $id => $page){
         if($page['type'] == -1){
            continue;
         }
         $output[$id]['title'] = $page['title'];
         $output[$id]['members'] = $page['members'];
         $output[$id]['bio'] = $page['bio'];
         $output[$id]['id'] = $page['id'];
         $output[$id]['type'] = $page['type'];
      }
      return $output;
   }else{
      return null;
   }
}
function get_page($id){ 
   if($id == 'favorite'){
      return array(
         'id' => 'favorite',
         'type' => 'private',
         'title' => 'favorite',
         'bio' => 'all your favorite posts',
         'creator' => '',
         'members' => '0',
      );
   }
   if(($all_pages = get_all_pages()) == false){
      return false;
   }
   if(isset($all_pages[$id])){
      return $all_pages[$id];
   }
   return false;
}
function create_page($page){
   if(($pages = read_file(FILE_PAGES)) === false){
      $pages = [];
   }
   if(($report = read_file(FILE_REPORT)) === false){
      $report = ['pages'=> 0];
   }
   $temp['title'] = $page['title'];
   $temp['type'] = '12';
   $temp['bio'] = $page['bio'];
   $temp['tags'] = $page['tags'];
   $temp['dir'] = $page['dir'];
   $temp['date'] = (int)(time());
   $temp['creator'] = $_SESSION['id'];
   $temp['members'] = 1;
   $temp['id'] = ID_PAGE - $report['pages']++;
   $temp['posts'] = 1;
   $path = get_page_path($temp['id']);
   $pages[$temp['id']] = $temp;
   $report[TYPE_PAGE][] = $temp['id'];
   save_data(FILE_PAGES , json_encode($pages));
   save_data(FILE_REPORT , json_encode($report));
   $post['create']['type'] = 'notify';
   $post['create']['content'] = 'created';
   $post['create']['date'] = $temp['date'];
   mkdir($path . 'posts/' , 0777 , true);
   save_data(($path . 'posts/' . get_file_name($temp['posts'] , 'post')) , json_encode($post));
   return $temp['id'];
}
function get_page_path($page_id){
   if($page_id == 'favorite'){
      return get_user_path($_SESSION['id']) . 'favorite/';
   }
   return DATA_ROOT . 'pages/' . $page_id . '/';
}
function edit_page($new_page , $page){
   $pages = get_all_pages();
   $page = $pages[$page['id']];
   $page['title'] = $new_page['title'];
   $page['bio'] = $new_page['bio'];
   $page['tags'] = $new_page['tags'];
   $page['dir'] = $new_page['dir'];
   $page['posts'] = (isset($new_page['posts']) ? $new_page['posts'] : $page['posts']);
   $pages[$page['id']] = $page;
   save_data(FILE_PAGES, json_encode($pages));
   return $page['id'];
}


// user functions
function get_user_path($user_id){
   $path = DATA_ROOT . 'users/' . $user_id . '/';
   if(!is_dir($path)){
      mkdir($path , 777 , true);
   }
   return $path;
}
function get_user_subscripes($user_id){
   $path = get_user_path($user_id);
   if(!is_dir($path)){
      return false;
   }
   if(!file_exists($path . 'subscripe.json')){
      return false;
   }
   return json_decode(file_get_contents($path . 'subscripe.json') , true);
}
// private function
function get_user_data($id){
   $file_name = DATA_ROOT . get_file_name($id, 'user');
   if(!file_exists($file_name)){
      return false;
   }
   if(($data = file_get_contents($file_name)) != false){
      $data = json_decode($data, true);
   }
   if(isset($data[$id])){
      $info = $data[$id];
      $info['id'] = $id;
      return $info;
   }
   return false;
}
// public function
function getUserData($id){
   if(($data = get_user_data($id)) == false){
      return false;
   }
   unset($data['password']);
   $data['status'] = (isset($data['status'])? $data['status'] : 1); 
   return $data;
}
function edit_user($user){
   if(($data = filter_user_data($user)) != false){
      if($data['username'] != $_SESSION['username']){
         if(check_username_validation($data['username']) == false){
            return false;
         }
         if(($usernames = read_file(FILE_USERNAMES)) == false){
            $usernames = [];
         }
         unset($usernames[$_SESSION['username']]);
         $usernames[$data['username']] = $_SESSION['id'];
         save_data(FILE_USERNAMES , json_encode($usernames));
      }
      $path = DATA_ROOT . get_file_name($_SESSION['id'], 'user');
      $users = read_file($path);
      if(isset($users[$_SESSION['id']])){
         $users[$_SESSION['id']]['name'] = $data['name'];
         $users[$_SESSION['id']]['username'] = $data['username'];
         if(isset($data['bio'])) 
            $users[$_SESSION['id']]['bio'] = $data['bio'];
         else 
            unset($users[$_SESSION['id']]['bio']);
         if(isset($data['tags'])) 
            $users[$_SESSION['id']]['tags'] = $data['tags']; 
         else 
            unset($users[$_SESSION['id']]['tags']);
         save_data($path , json_encode($users));
         save_username($users[$_SESSION['id']]['username'], $_SESSION['id']);
         return true;
      }else{
         session_destroy();
         setcookie('userinfo', '');
         header('Location: login.php');
         exit;
      }
   }else{
      return false;
   }
}
// get users in the page
function get_page_users($page_id){
   if(!file_exists(($file_path = get_page_path($page_id)) . 'users.json')){
      return false;
   }
   return read_file($file_path);
}
function subscripe($user_id , $page_id){
   if(($report = read_file(FILE_REPORT)) == false){
      return ['return' => false , 'message' =>'unknown error'];
   }
   $pages = get_all_pages();
   if(!isset($pages[$page_id])){
      return ['return' => false , 'message' =>'wrong page data'];
   }
   $page = $pages[$page_id];
   $user = get_User_Data($user_id);
   if($user == false){
      return ['return' => false , 'message' =>'wrong user data'];
   }
   $path = get_page_path($page_id);
   $user_path = get_user_path($user_id);
   if(($page_users = read_file($path . 'users.json')) == false){
      if(!is_dir($path)) mkdir($path , 0777 , true);
      $page_users = [];
   }
   if(($user_pages = read_file($user_path . 'subscripe.json')) == false){
      $user_pages = [];
   }
   if(isset($user_pages[$page_id])){
      unset($user_pages[$page_id]);
      if(empty($user_pages)){ // must subscripe one page at least .
         return ['return' => false , 'message' =>' must subscripe one page at least'];
      }
      unset($page_users[$user_id]);
      $output = 'leave';
      $page['members'] -= 1;
      if($page['members'] <= 0){
         $page['members'] = 0 ;
      }
   }else{
      $temp['name'] = $user['name'];
      $temp['status'] = 1;
      $page_users[$user_id] = $temp;
      $user_pages[$page_id] = time();
      $output = 'join';
      $page['members'] += 1;
   }
   $pages[$page['id']] = $page;
   save_data(FILE_PAGES , json_encode($pages));
   save_data($path . 'users.json' , json_encode($page_users));
   save_data($user_path . 'subscripe.json' , json_encode($user_pages));
   return ['return' => true , 'message' =>$output];
}
function get_favorite_path($user_id){
   $path = get_user_path($user_id) . 'posts/';
   if(!is_dir($path)){
      mkdir($path , 0777 , true);
   }
   return $path . 'favorite.json';
}

// exam functions 
function get_exam_path($exam){
   if(!isset($exam['creator'])){
      return false;
   }
   $path = DATA_ROOT . 'users/' . $exam['creator'] . '/'; 
   if(!is_dir($path)){
      mkdir($path , 777 , true);
   }
   return $path . 'exam.json';
}
// this function need to filter data and private exams
function get_all_exams(){
   if(!file_exists(FILE_EXAMS)){
      return false;
   }
   if(($exams = file_get_contents(FILE_EXAMS)) != false){
      return json_decode($exams , true);
   }
   return false;
}
function get_exam_data($id){
   if(($exams = get_all_exams()) != false){
      if(isset($exams[$id])){
         return $exams[$id];
      }
   }
   return false;
}


// filtering data 
function str_filter($string){
   $output = htmlentities($string);
   $output = str_replace('\n', '</br>' , $output);
   $output = str_replace('  ', ' ' , $output);
   $output = trim($output);
   return $output;
}
function filter_page_data($input){
   if(!empty($input['title'])){
      $output['title'] = substr($input['title'] , 0 , MAX_NAME_LETTERS);
      $output['title'] = str_filter($output['title']);
   }
   if(!empty($input['bio'])){
      $output['bio'] = substr($input['bio'] , 0 , MAX_BIO_LETTERS);
      $output['bio'] = str_filter($output['bio']);
   }
   if(!empty($input['dir'])){
      $output['dir'] = ($input['dir'] == 'ltr' ? 'ltr' : 'rtl');
   }
   if(!empty($input['tags'])){
      $output['tags'] = substr($input['tags'] , 0 , MAX_TAGS_LETTERS);
      $output['tags'] = str_filter($output['tags']);
      $output['tags'] = explode(',' , $output['tags']);
   }
   if(!(empty($output['title']) or empty($output['bio']) or empty($output['tags']))){
      return $output;
   }else{
      return false;
   }
}
function filter_post_data($input){
   if(!empty($input['title'])){
      $output['title'] = substr($input['title'] , 0 , MAX_POST_TITLE_LETTERS);
      $output['title'] = str_filter($output['title']);
   }
   if(!empty($input['content'])){
      $output['content'] = substr($input['content'] , 0 , MAX_CONTENT_LETTERS);
      $output['content'] = str_filter($output['content']);
   }
   if(!empty($input['type'])){
      $output['type'] = preg_replace('/[^a-zA-Z0-9_ ]/' , '' , $input['type']);
   }
   if(!empty($input['dir'])){
      $output['dir'] = ($input['dir'] == 'ltr' ? 'ltr' : 'rtl');
   }
   if(!empty($input['url'])){
      $output['url'] = filter_var($input['url'] , FILTER_SANITIZE_URL);
   }
   if(!empty($input['code'])){
      $output['code'] = '<pre><code>' . htmlentities($input['code']) . '</code></pre>';
   }
   if(!(empty($output['title']) or empty($output['content']))){
      return $output;
   }else{
      return false;
   }
}
function filter_question_data($input){
   $output = null;
   if(!empty($input['title'])){
      $output['title'] = substr($input['title'] , 0 , MAX_POST_TITLE_LETTERS);
      $output['title'] = str_filter($output['title']);
   }
   if(isset($input['choose'] , $input['choose']['answer'])){
      $output['choose'] = $input['choose'];
   }
   if(!(empty($output['title']) or empty($output['choose']))){
      return $output;
   }else{
      return false;
   }
}
function filter_user_data($input){
   if(!empty($input['name'])){
      $output['name'] = substr($input['name'] , 0 , MAX_NAME_LETTERS);
      $output['name'] = str_filter($output['name']);
   }
   if(!empty($input['username'])){
      $output['username'] = preg_replace('/[^a-zA-Z0-9_]/' , '' , $input['username']);
      $output['username'] = substr($output['username'] , 0 , MAX_USERNAME_LETTERS);
   }
   if(!empty($input['bio'])){
      $output['bio'] = substr($input['bio'] , 0 , MAX_BIO_LETTERS);
      $output['bio'] = str_filter($output['bio']);
   }
   if(!empty($input['tags'])){
      $output['tags'] = substr($input['tags'] , 0 , MAX_TAGS_LETTERS);
      $output['tags'] = str_filter($output['tags']);
      $output['tags'] = explode(',' , $output['tags']);
   }
   if(!isset($output['name'],$output['username'])){
      return false;
   }
   if(check_user_data($output) != false){
      return $output;
   }else{
      return false;
   }
}
function check_user_data($input){
   if(isset($input['name'] , $input['username'])){
      if(strlen($input['name']) >= MIN_NAME_LETTERS and strlen($input['username']) >= MIN_USERNAME_LETTERS){
         return true;
      }else{
         return false;
      }
   }else{
      return false;
   }
}
function filter_exam_data($input){
   if(!empty($input['title'])){
      $output['title'] = substr($input['title'] , 0 , MAX_NAME_LETTERS);
      $output['title'] = str_filter($output['title']);
   }
   if(!empty($input['bio'])){
      $output['bio'] = substr($input['bio'] , 0 , MAX_BIO_LETTERS);
      $output['bio'] = str_filter($output['bio']);
   }
   if(!empty($input['dir'])){
      $output['dir'] = ($input['dir'] == 'ltr' ? 'ltr' : 'rtl');
   }
   if(!empty($input['time'])){
      $output['time'] = ($input['time'] < 100 and $input['time'] > 0) ? $input['time'] : 24;
   }
   $output['type'] = 'private';
   if(!(empty($output['title']) or empty($output['bio']) or empty($output['time']))){
      return $output;
   }else{
      return false;
   }
}

// posts function 
function create_post($post , $page){
   $path = get_page_path($page['id']) . 'posts/';
   $page['posts'] += 1;
   $path .= get_file_name($page['posts'] , 'post');
   if(($posts = read_file($path)) == false){
      $posts = [];
   }
   if(isset($post['type'])){
      $temp['type'] = $post['type'];
   }
   $temp['title'] = $post['title'];
   $temp['content'] = $post['content'];
   $temp['dir'] = (isset($post['dir']) ? $post['dir'] : $page['dir']);
   if(isset($post['url'])){
      $temp['url'] = $post['url'];
   }
   if(isset($post['code'])){
      $temp['code'] = $post['code'];
   }
   $temp['date'] = (int)(time());
   $temp['poster_id'] = $_SESSION['id'];
   $temp['poster'] = $_SESSION['name'];
   $temp['views'] = 1;
   $temp['id'] = $page['posts'];
   $posts[$temp['id']] = $temp;
   save_data($path , json_encode($posts));
   edit_page($page, $page);
   return $temp['id'];
}
function edit_post($new_data , $page , $post_id){
   if($post_id > $page['posts']){
      return false;
   }
   $path = get_page_path($page['id']) . 'posts/';
   $path .= get_file_name($post_id , 'post');
   if(($all_posts = read_file($path)) == false){
      return false;
   }
   if(!isset($all_posts[$post_id])){
      return false;
   }
   $temp = $all_posts[$post_id];
   $temp['title'] = $new_data['title'];
   $temp['content'] = $new_data['content'];
   $temp['dir'] = $new_data['dir'];
   if(isset($new_data['url'])){
      $temp['url'] = $new_data['url'];
   }else{
      unset($temp['url']);
   }
   if(isset($new_data['code'])){
      $temp['code'] = $new_data['code'];
   }else{
      unset($temp['code']);
   }
   if(isset($new_data['views'])){
      $temp['views'] = $new_data['views'];
   }
   $temp['edit'] = (int)(time());
   $all_posts[$post_id] = $temp;
   save_data($path , json_encode($all_posts));
   return $temp['id'];
}
// function read_file($path){
//    if(!is_file($path)){
//       return false;
//    }
//    return json_decode(file_get_contents($path) , true);
// }
function getPostData($page_id ,$post_id){
   if($page_id == 'favorite'){
      $file_posts = get_favorite_path($_SESSION['id']);
   }else{
      $file_posts = get_page_path($page_id) . 'posts/' . get_file_name($post_id , 'post');
   }
   $posts = read_file($file_posts);
   if($posts == false){
      return false;
   }
   if(isset($posts[$post_id])){
      $post = $posts[$post_id];
      if(!empty($post['code'])){
         $post['type'] = 'code';
      }else if(!empty($post['url'])){
         $post['type'] = 'url';
      }else{
         $post['type'] = 'post';
      }
      $post['date'] = get_time($post['date']);
      return $post;
   }
}
function like($page_id , $post_id, $state){
   $user_id = $_SESSION['id'];
   $path = get_page_path($page_id) . 'posts/' . get_file_name($post_id , 'post');
   if(($posts = read_file($path)) == false){
      return ['return' => false , 'message' =>'wrong post id'];
   }
   if(!isset($posts[$post_id])){
      return ['return' => false , 'message' =>'wrong post id'];
   }
   $post = $posts[$post_id];
   if(isset($post['likes'][$user_id]) and ($state == $post['likes'][$user_id])){
      unset($post['likes'][$user_id]);
      $output = 'deleted';
   }else{
      $post['likes'][$user_id] = $state;
      $output = 'added';
   }
   $posts[$post_id] = $post;
   save_data($path , json_encode($posts));
   return ['return' => true , 'message' =>$output];
}
function add_to_favorite($page_id , $post_id){
   $user_id = $_SESSION['id'];
   $path = get_page_path($page_id) . 'posts/' . get_file_name($post_id , 'post');
   if(($posts = read_file($path)) == false){
      return ['return' => false , 'message' =>'wrong post id'];
   }
   if(!isset($posts[$post_id])){
      return ['return' => false , 'message' =>'wrong post id'];
   }
   if(($data = read_file(($path = get_favorite_path($user_id)))) == false){
      $data = [];
   }
   if(isset($data[$page_id . ':' . $post_id])){
      unset($data[$page_id . ':' . $post_id]);
      $output = 'deleted';
   }else{
      $posts[$post_id]['id'] = $page_id . ':' . $post_id;
      $data[$page_id . ':' . $post_id] = $posts[$post_id];
      $output = 'added';   
   }
   save_data($path , json_encode($data));
   return $output;
}

// static functions
function get_singular($num, $one, $plural, $tow){
   if($num == 1){
      return 'قبل ' . $one;
   }
   if($num == 2){
      return 'قبل '. $tow;
   }
   if($num <=  10){
      return 'منذ ' . $num . ' ' .$plural;
   }
   return 'منذ ' . $num . ' ' . $one;
}
function get_time($date){
   $now = time();
   $time = $now - $date;
   if($time < 60 ){
      return 'الان';
   }
   if($time < 60 * 60){
      $num = (int)($time / 60) ;
      $txt = get_singular($num, 'دقيقة','دقائق','دقيقتين');
   }else
   if($time < (60 * 60 * 24)){
      $num = (int)floor($time / (60 * 60 ));
      $txt = get_singular($num, 'ساعة','ساعات','ساعتين');
   }else
   if($time < 60 * 60 * 24 * 2){
      $txt = 'امس';
   }else
   if($time < 60 * 60 * 24 * 7){
      $num = (int)($time / (60 * 60 * 24 )) ;
      $txt = get_singular($num, 'يوم','ايام','يومين');
   }else
   if($time < 60 * 60 * 24 * 7 * 4){
      $num = (int)($time / (60 * 60 * 24 * 7)) ;
      $txt = get_singular($num, 'اسبوع','اسابيع','اسبوعين');
   }else
   if($time < 60 * 60 * 24 * 7 * 4 * 12){
      $d = getdate($date);
      $txt =  $d['mday'] . ' ' . $d['month'] . ' ' ;
   }else{
      $d = getdate($date);
      $txt =  $d['mday'] . ' ' . $d['month'] . ' ' . $d['year'];
   }
   return ' ' . $txt;
}
function get_file_name($id, $type){
   if($type == 'user'){
      return 'users' . floor($id/1000) . '.json';
   }else{
      return 'posts' . floor($id/100) . '.json';
   }
}
function save_data($file_name, $data){
   if(empty($data) or $data == '[]' or $data == '{}'){
      return false;
   }
   if(($temp_file = tempnam(sys_get_temp_dir(), 'json')) == false){
      return false;
   }
   file_put_contents($temp_file, $data);
   rename($temp_file, $file_name);
}
function read_file($file_path){
   if(!is_file($file_path)){
      return false;
   }
   if(($data = file_get_contents($file_path)) == false){
      return false;
   }
   return json_decode($data, true);
}
function has_permission($user , $status){
   if(isset($user['status']) and $user['status'] >= $status){
      return true;
   }else{
      return false;
   }
}
function get_id_type($id){
   if($id >= ID){
      return 'user';
   }elseif($id <= ID_PAGE){
      return 'page';
   }else{
      return false;
   }
}
function save_username($username, $id){
   $username = strtolower($username);
   if(($data = read_file(FILE_USERNAMES)) == false){
      $data = [];
   }
   $data[$username] = $id;
   save_data(FILE_USERNAMES, json_encode($data));
}
function check_status($status){
   if($status <= 10 and $status >= 1 or $status == -2){
      return 'user';
   }
   if($status <= 16 and $status >= 12 or $status == -1){
      return 'page';
   }
   if($status == -1){
      return 'an';
   }
   return false ;
}
function set_status($id , $status){
   $type_status = check_status($status);
   if($type_status == false){
      return false;
   }
   $type_id = get_id_type($id);
   if($type_id != $type_status){
      return false;
   }
   if($type_id == 'page'){
      if(($pages = get_all_pages()) == false){
         return false;
      }
      if(!isset($pages[$id])){
         return false;
      }
      $report = read_file(FILE_REPORT);
      unset($report[$pages[$id]['type']][$id]);
      $pages[$id]['type'] = $status;
      $report[$pages[$id]['type']][] = $id;
      save_data(FILE_REPORT, json_encode($report));
      save_data(FILE_PAGES , json_encode($pages));
      return true;
   }else if($type_id == 'user'){
      if(($users = read_file(($file_name = DATA_ROOT . get_file_name($id, 'user')))) == false){
         return false;
      }
      if(!isset($users[$id])){
         return false;
      }
      $report = read_file(FILE_REPORT);
      unset($report[$users[$id]['status']][$id]);
      $users[$id]['status'] = $status;
      $report[$users[$id]['status']][] = $id;
      save_data(FILE_REPORT, json_encode($report));
      save_data($file_name, json_encode($users));
      return true;
   }
   return false;
}
function get_short_nembers($number){
   $float = '';
   if($number < 900){
      return $number;
   }
   if($number < 1000000){
      if(($float = $number % 1000) > 100){
         $float = trim('.' . floor(($float*10)/1000)  , '0');
      }
      return floor($number / 1000) . $float . 'k';
   }
   if($number >= 1000000){
      if(($float = $number % 1000000) > 100000){
         $float = trim('.' . floor(($float*10)/1000000)  , '0');
      }
      return floor($number / 1000000) . $float . 'M';
   }
}
function getPageDataToDisplay($page_id, $post_id = 0){
   if(($page = get_page($page_id)) == false){
      return false;
   }
   if($page['id'] == 'favorite'){
      $file_posts = get_favorite_path($_SESSION['id']);
   }else{
      $file_posts = get_page_path($page_id) . 'posts/' . get_file_name($post_id , 'post');
   }
   if(($data_posts = getPostsList($file_posts)) == false){
      $data_posts = ['notify'=>['type'=>'notify', 'content'=>'no posts here']];
   }
   if(isset($data_posts[$post_id])){
      $output['post'] = $data_posts[$post_id];
   }
   $output['posts'] = $data_posts;
   $output['page'] = $page;
   return $output;
}
function getPostsList($file_posts){
   define('MAX_POST_SHORT_CONTENT' , 50);
   if(($posts = read_file($file_posts)) == false){
      return false;
   }
   foreach($posts as $id => $post){
      if(isset($post['type']) and $post['type'] == 'notify'){
         $output[$id] = $post;
         continue;
      }
      if(!empty($post['code'])){
         $output[$id]['type'] = 'code';
      }else if(!empty($post['url'])){
         $output[$id]['type'] = 'url';
      }else{
         $output[$id]['type'] = 'post';
      }
      $output[$id]['title'] = $post['title'];
      $output[$id]['content'] = substr($post['content'] , 0 , MAX_POST_SHORT_CONTENT) . '...';
      $output[$id]['poster'] = $post['poster'];
      $output[$id]['views'] = $post['views'];
      $output[$id]['id'] = $post['id'];
   }
   return $output;
}
function search($phrese){
   if(empty($phrese)){
      return false;
   }
   if(($pages = get_all_pages()) == false){
      return false;
   }
   foreach($pages as $id => $page){
      if(!isset($page['tags'])){
         return false;
      }
      if(in_array($phrese , $page['tags'])){
         $search[$id] = $page;
      }
   }
   if(isset($search)){
      return $search;
   }else{
      return false;
   }
}