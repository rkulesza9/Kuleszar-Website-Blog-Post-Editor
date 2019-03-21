<?php
  include '../dbconfig.php';

  session_start();
  if(isset($_SESSION['user_id'])){
    $save = $_POST['save'];
    $preview = $_POST['preview'];
    $title = $_POST['title'];
    $author = $_POST['author'];
    $content = $_POST['content'];
    $bp_id = $_POST['bp_id'];
    $date_published = date("y-m-d");
    $archive = date("y-m-01");
    $tags = $_POST['tags'];

    if(isset($save)){
      //save article
      if($bp_id == 'new'){
        $sql = "insert into articles (author,content,date_published,title,archive) values(?,?,?,?,?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss",$author,$content,$date_published,$title,$archive);
        $stmt->execute();
        $stmt->close();
        $sql = "select max(last_insert_id(id)) from articles";
        $stmt=$conn->prepare($sql);
        $stmt->bind_result($bp_id);
        $stmt->execute();
        $stmt->fetch();
        $stmt->close();
      }else {
        $sql = "update articles set author=?, content=?, date_published=?, title=?, archive=? where id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss",$author,$content,$date_published,$title,$archive,$bp_id);
        $stmt->execute();
        $stmt->close();
      }
      //save tags (if tag does not exist, add new tag to tags);
      $sql = "delete from tagged where a_id=?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("i",$bp_id);
      $stmt->execute();
      $stmt->close();
      if($tags != ""){
        $tags = preg_split("/;/",$tags);
        for($x=0;$x < count($tags); $x++){
          $tag = $tags[$x];

          $sql = "insert into tagged (a_id,t_id) values (?,(select id from tags where tag=?))";
          $stmt = $conn->prepare($sql);
          $stmt->bind_param("is",$bp_id,$tag);
          $stmt->execute();
          $tag_exists = ($stmt->affected_rows > 0);
          $stmt->close();
          if(!$tag_exists){
            $sql = "insert into tags (tag) values(?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s",$tag);
            $stmt->execute();
            $stmt->close();
            $x--;
          }
        }
      }
      header("location: index.php");
    } elseif(isset($preview)){

    }
  }else{
    header("location: login.php");
  }

?>

<html>
  <head>
    <title><?php echo $title; ?></title>
  </head>
  <body>
    <h1><?php echo $title; ?></h1>
    <p><?php echo "by $author"; ?></p>
    <?php echo $content; ?>

    <form action='save.php' method='post'>
      <input type='hidden' name='title' value='<?php echo $title; ?>'>
      <input type='hidden' name='author' value='<?php echo $author; ?>'>
      <input type='hidden' name='content' value='<?php echo $content; ?>'>
      <input type='hidden' name='bp_id' value='<?php echo $bp_id; ?>'>
      <input type='hidden' name='tags' value='<?php echo $tags; ?>'>
      <input type='submit' name='save' value='save'>
    </form>

  </body>
  <footer>
  </footer>
</html>
