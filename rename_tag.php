<?php

  include '../dbconfig.php';
include '../user_auth.php';

  if(sessionExistsForService("editor")){
      if($_POST['submit']){
        $new_name = $_POST['new_name'];
        $t_id = $_POST['t_id'];

        $sql = "update tags set tag=? where id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si",$new_name,$t_id);
        $stmt->execute();
        $stmt->close();
      }

      header("Location: index.php");
  }else{
    header("location: login.php");
  }

?>
