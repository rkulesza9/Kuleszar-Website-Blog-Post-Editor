<?php
  include '../dbconfig.php';
  require_once 'random_compat-2.0.18/lib/random.php';

  session_start();
  if(isset($_SESSION['user_id'])) header("location: index.php");

  $email_hash_alg = "gost";
  $password_hash_alg = "gost";
  if(isset($_POST['submit'])){
    //detect when the last otp was generated (last_otp) (must be within 30 minutes)
    $username = $_POST['username'];
    $password = $_POST['password'];
    $now = date('Y-m-d H:i:s');
    $sql = "select id,last_otp,password from editor_users where username=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s",$username);
    $stmt->bind_result($id,$last_otp,$password_db);
    $stmt->execute();
    $stmt->fetch();
    $stmt->close();
    $last_otp = new DateTime($last_otp);
    $now = new DateTime($now);
    $diff = $now->getTimestamp() - $last_otp->getTimestamp();
    $time_limit = 30*60;
    if(0 <= $diff && $diff <= $time_limit){
      //detects if password is correct
      $password_hash = hash($password_hash_alg,$password.$username) ;
      if($password_hash == $password_db){
        //creates session (with user id)
        session_start(); //session_unset(), session_destroy()
        $_SESSION['user_id'] = $id;
        $_SESSION['otp_stuff'] = "<p>last otp: ".$last_otp->format("y-m-d h:i:s")."<br>login: ".$now->format("y-m-d h:i:s")."<br>Timeout: ".($time_limit/60 - $diff/60)."</p>";
        header("Location: index.php");
      }else {
        echo "<p style='color:red;'>Password was incorrect! Try requesting a new password!</p>";
      }
    }else{
      echo "<p style='color:red;'>Login Interval ended, request a new OTP</p>";
    }

  } elseif(isset($_POST['gen_otp'])){
    //check that email matches
    $username = $_POST['username'];
    $email = $_POST['email'];
    $sql = "select email from editor_users where username=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s",$username);
    $stmt->bind_result($email_fromdb);
    $stmt->execute();
    $stmt->fetch();
    $stmt->close();
    $email_hashed = hash($email_hash_alg,$email.$username);
    if($email_fromdb == $email_hashed){

          //set status to open
          //sets last otp to current DateTime
          $last_otp = date("Y-m-d H:i:s");

          $sql = "update editor_users set last_otp=? where username=?";
          $stmt = $conn->prepare($sql);
          $stmt->bind_param("ss",$last_otp,$username);
          $stmt->execute();
          $stmt->close();

          //generates OTp
          $otp = bin2hex(random_bytes(10));
          //echo "password: $otp";

          //saves otp
          $otp_hash = hash($password_hash_alg,$otp.$username);
          $sql = "update editor_users set password=? where username=?";
          $stmt = $conn->prepare($sql);
          $stmt->bind_param("ss",$otp_hash,$username);
          $stmt->execute();
          $stmt->close();

          //sends otp in email
          $toEmail = $_POST['email'];

          $fromEmail = "robertkulesza@kuleszar.com";
          $subject = "kuleszar.com/editor OTP Request";
          $message = "username: $username\npassword: $otp";
          $headers = "From: $fromEmail";


          mail($toEmail,$subject,$message,$headers);
        } else {
          echo "<p style='color:red;'>The email <b>$email</b> is not associated with the username <b>$username</b>";
        }
    }/* elseif(isset($_POST['new'])){
      $username = $_POST['username'];
      $password = $_POST['password'];
      $email = $_POST['email'];

      $email_hash = hash($email_hash_alg,$email.$username);
      $password_hash = hash($password_hash_alg,$password.$username);
      $last_otp = date("y-m-d h:m:s");

      $sql = "insert into editor_users (username,password,email,last_otp) values(?,?,?,?)";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("ssss",$username,$password_hash,$email_hash,$last_otp);
      $stmt->execute();
      echo $stmt->error;
      $stmt->close();
    }*/

?>

<html>
  <head>
    <title>Login Page</title>
  </head>
  <body>
    <form action='login.php' method='post'>
      <table>
        <tr><th>Username:</th><td><input type='text' name='username' placeholder='username'></td></tr>
        <tr><th>Password:</th><td><input type='password' name='password'></td></tr>
        <tr><td><input type='submit' name='submit' value='Login'></td></tr>
      </table>
    </form>
    <form action='login.php' method='post'>
      <table>
        <tr><th>Username:</th><td><input type='text' name='username' placeholder='username'></td></tr>
        <tr><th>Email:</th><td><input type='email' name='email' placeholder='eg. fred@kfc.com'></td></tr>
        <tr><td><input type='submit' value='Send OTP' name='gen_otp'></td></tr>
      </table>
    </form>
    <!--<form action='login.php' method='post'>
      <table>
        <tr><th>Username:</th><td><input type='text' name='username'></td></tr>
        <tr><th>Password:</th><td><input type='password' name='password'></td></tr>
        <tr><th>Email:</th><td><input type='email' name='email'></td></tr>
        <tr><td><input type='submit' name='new'></td></tr>
      </table>
    </form> -->
  </body>
  <footer>
  </footer>
</html>
