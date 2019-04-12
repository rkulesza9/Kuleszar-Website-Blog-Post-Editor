<?php
include '../user_auth.php';

  if(sessionExistsForService("editor")) header("location: index.php");
  else loginToService("editor",true);
    /* elseif(isset($_POST['new'])){
      $username = $_POST['username'];
      $password = $_POST['password'];
      $email = $_POST['email'];

      $email_hash = hash($email_hash_alg,$email.$username);
      $password_hash = hash($password_hash_alg,$password.$username);
      $last_otp = date("y-m-d h:m:s");

      $sql = "insert into users (username,password,email,last_otp) values(?,?,?,?)";
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
    <!-- <form action='login.php' method='post'>
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
