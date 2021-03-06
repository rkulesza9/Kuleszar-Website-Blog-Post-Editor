<?php

  include '../dbconfig.php';
include '../user_auth.php';

  if(sessionExistsForService("editor")){
    $submit = $_POST['submit'];
    $bp_id = $_POST['blogposts'];

    if(isset($submit)){
      if($bp_id == 'new'){
        $author = "";
        $tags = "";
        $content = "";
        $title = "New Blogpost";
      }else{
        $sql = "select author,content,title from articles where id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i",$bp_id);
        $stmt->bind_result($author,$content,$title);
        $stmt->execute();
        $stmt->fetch();
        $stmt->close();

        $sql = "select tag from tags where id in (select t_id from tagged where a_id=?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i",$bp_id);
        $stmt->bind_result($tag);
        $stmt->execute();
        $stmt->fetch();
        $tags = $tag;
        while($stmt->fetch()){
          $tags .= ";".$tag;
        }
        $stmt->close();
      }
    }
  }else{
    header("location: login.php");
  }


?>

<html>
  <head>
    <title>Edit Blogpost</title>
    <script type='text/javascript' src='jquery.js'></script>
    <style>
      input[type='text'] {
        width:300px;
      }
      textarea{
        width:500px;
        height:250px;
      }
    </style>
  </head>
  <body>
    <h1>Edit Blogpost: <?php echo $title; ?></h1>
    <form action='save.php' method='post'>
      <table>
        <tr><th>Title: </th><td><input type='text' name='title' value='<?php echo $title; ?>' placeholder='enter title here' /></td></tr>
        <tr><th>Author: </th><td><input type='text' name='author' value='<?php echo $author; ?>' placeholder='enter author here' /></td></tr>
        <tr><th>Tags: </th><td><input type='text' name='tags' value='<?php echo $tags; ?>' placeholder='enter tags here (tag1;tag2;etc)'/></td></tr>
        <tr><th>Content:</th><td><textarea name='content'><?php echo $content; ?></textarea></td></tr>
      </table>
      <input type='hidden' name='bp_id' value='<?php echo $bp_id; ?>'>
      <table>
        <tr><td><input type='submit' name='preview' value='preview'></td><td><input type='submit' name='save' value='save'></td></tr>
      </table>
  </form>
  </body>
  <footer>
  </footer>
</html>
