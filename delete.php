<?php

  include '../dbconfig.php';
include '../user_auth.php';

  if(sessionExistsForService("editor")){
    $bp_id = $_POST['bp_id'];
    $delete = $_POST['delete'];

    if(isset($delete)){
      $sql = "delete from articles where id=?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("i",$bp_id);
      $stmt->execute();
      $stmt->close();

      $sql = "delete from tagged where a_id=?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("i",$bp_id);
      $stmt->execute();
      $stmt->close();

    }

    header("Location: index.php");
  }else{
    header("location: login.php");
  }


?>
