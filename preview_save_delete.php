<?php
  include '../dbconfig.php';

  $preview = $_POST['preview'];
  $save = $_POST['save'];
  $delete = $_POST['delete'];

  $title = $_POST['title'];
  $author = $_POST['author'];
  $tags = $_POST['tags'];
  $content = $_POST['content'];
  $bp_id = $_POST['bp_id'];

  if(isset($save)){
    if($bp_id == 'new'){
      $sql = "insert into articles (author,tags,content,date_published,title) values(?,?,?,?,?)";
      $stmt = $conn->prepare($sql);
      $date = date('Y-m-d', time());
      $stmt->bind_param("sssss",$author,$tags,$content,$date,$title);
      $stmt->execute();
      $stmt->fetch();
      if($stmt->affected_rows > 0){
        echo "<p>The blogpost <b>$title</b> was successfully added!<br>";
        echo $stmt->affected_rows." blogposts have been affected.</p>";
      }else{
        echo "<p>The blogpost <b>$title</b> was not successfully added.</p>";
      }
      $stmt->close();
    }else{
      $sql = "update articles set author=?, tags=?, content=?, title=? where id=?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("ssssi",$author,$tags,$content,$title,$bp_id);
      $stmt->execute();
      $stmt->fetch();
      if($stmt->affected_rows > 0){
        echo "<p>The blogpost <b>$title</b> was successfully saved!<br>";
        echo $stmt->affected_rows." blogposts have been affected.</p>";
      }else{
        echo "<p>The blogpost <b>$title</b> was not changed.</p>";
      }
      $stmt->close();
    }
  }elseif(isset($delete)){
    if($bp_id == 'new'){
      echo "<p>This blogpost is not yet in the database.</p>";
    }else{
      $sql = "delete from articles where id=?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("i",$bp_id);
      $stmt->execute();
      $stmt->fetch();
      if($stmt->affected_rows > 0 ){
        echo "<p>The blogpost <b>$title</b> was successfully deleted!<br>";
        echo $stmt->affected_rows." blogposts have been removed.</p>";
      } else {
        echo "<p>The blogpost <b>$title</b> was not changed.</p>";
      }
      $stmt->close();
    }
  }elseif(isset($preview)){
    echo "<h1>$title</h1>";
    echo "by $author";
    echo $content;
  }
?>
