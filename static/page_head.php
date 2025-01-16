<!-- updated -->
<!DOCTYPE html>
<html lang="en">
<head>
<?php 
   $root =  LOCAL_ROOT;
   $temp = explode('/' , $_SERVER['PHP_SELF']);
   $this_page =  $temp[count($temp) - 1];
   $sign = isset($_SESSION['id']);

   if($sign){?>
      <script>
         const myData = {
            'name': <?php echo '\'' . $_SESSION['name'] . '\''?>,
            'id': <?php echo $_SESSION['id'] ?>,
            'status': <?php echo (isset($_SESSION['status']) ? $_SESSION['status'] : '1') ?>,
         };
      </script>
   <?php } ?>
   <script>
      const root = <?php echo '\'' . $root . '\'' ?>;
   </script>
   <title>سهل الطالب</title>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
   <link rel="stylesheet" href= <?php echo $root . 'css/style.css'?>  >
