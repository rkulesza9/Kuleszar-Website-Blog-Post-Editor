<?php
  include '../dbconfig.php';

  session_start();
  if(isset($_SESSION['user_id'])){
    $save = $_POST['save'];
    $preview = $_POST['preview'];
    $title = isset($_POST['title']) ? $_POST['title'] : $_COOKIE['title'];
    $author = isset($_POST['author']) ? $_POST['author'] : $_COOKIE['author'];
    $content = isset($_POST['content']) ? $_POST['content'] : $_COOKIE['content'];
    $bp_id = isset($_POST['bp_id']) ? $_POST['bp_id'] : $_COOKIE['bp_id'];
    $date_published = date("y-m-d");
    $archive = date("y-m-01");
    $tags = isset($_POST['tags']) ? $_POST['tags'] : $_COOKIE['tags'];

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
        $sql = "update articles set author=?, content=?, title=? where id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss",$author,$content,$title,$bp_id);
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
      setcookie("title",$title);
      setcookie("author",$author);
      setcookie("content",$content);
      setcookie("bp_id",$bp_id);
      setcookie("tags",$tags);
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
    <h1><?php echo($title); ?></h1>
    <p><?php echo "by $author"; ?></p>
    <?php echo($content); ?>

    <form action='save.php' method='post'>
      <input type='submit' name='save' value='save'>
    </form>

  </body>
  <footer>
  </footer>
</html>
