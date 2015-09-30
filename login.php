<?php
    session_start();
    $errorMsg="";
    if(count($_POST)>0){
      if($_POST['username']=="support" && $_POST['password']=="nimda123"){
        $_SESSION['username']=$_POST['username'];
        header('location: index.php ');
    }
      else
        $errorMsg = '<div style="background-color:#c10000;color:#fff" >Error in username or password</div>';

}
?>
<!DOCTYPE html>
<!--[if lt IE 7]> <html class="lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]> <html class="lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]> <html class="lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html lang="en"> <!--<![endif]-->

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title>Login Form</title>
  <link rel="stylesheet" href="css/style.css">
  <!--[if lt IE 9]><script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
</head>
<body>
<section class="container">
  <div class="login">
    <h1>Login to Extensions App</h1>
    <form method="post" action="login.php">
      <p><input type="text" name="username" value="" placeholder="Username or Email"></p>
      <p><input type="password" name="password" value="" placeholder="Password"></p>
      <?php if($errorMsg!="")
        echo $errorMsg.'</br>';
      ?>
      <p class="submit"><input type="submit" name="commit" value="Login"></p>
    </form>
  </div>
  <div class="login-help">
    <p>Powered By ShopGo <a href="shopgo.me">Click here to visit us.</a>.</p>
  </div>
</section>
</body>
</html>
