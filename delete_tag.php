<?php
  include "../dbconfig.php";

  session_start();
  if(isset($_SESSION['user_id'])){
    $DELETE_ARTICLES_WITH_TAG = 1;

    if(isset($_POST['submit'])){
      $t_id = $_POST['t_id'];
      $option = $_POST['delete_options'];

      if($option == $DELETE_ARTICLES_WITH_TAG){
        $sql = "delete from articles where id in (select a_id from tagged where t_id=?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i",$t_id);
        $stmt->execute();
        $stmt->close();

        $sql = "delete from tagged where a_id not in (select id from articles)";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $stmt->close();
      }

      $sql = "delete from tags where id=?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("i",$t_id);
      $stmt->execute();
      $stmt->close();

      $sql = "delete from tagged where t_id=?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("i",$t_id);
      $stmt->execute();
      $stmt->close();

    }

    header("location: index.php");
  }else{
    header("location: login.php");
  }



?>
