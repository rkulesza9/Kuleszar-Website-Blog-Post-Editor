<?php
  include '../dbconfig.php';
  include '../user_auth.php';

    if(sessionExistsForService("editor")){

    if($_POST['submit']){
      $archive = $_POST['archive'];
      $sql = "delete from tagged where a_id in (select id from articles where archive=?)";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("s",$archive);
      $stmt->execute();
      $stmt->close();

      $sql = "delete from articles where archive=?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("s",$archive);
      $stmt->execute();
      $stmt->close();
    }
    header("location: index.php");
  }else{
    header("location: login.php");
  }

?>
