<?php
  include "../dbconfig.php";

  session_start();
  if(isset($_SESSION['user_id'])){
      $otp_stuff = $_SESSION['otp_stuff'];
      $bp_table = '';
      $tags_table = '';
      $ar_table = '';

      $sql = "select id,title,author,date_published from articles order by date_published";
      $stmt = $conn->prepare($sql);
      $stmt->bind_result($id,$title,$author,$date_published);
      $stmt->execute();
      while($stmt->fetch()){
        $bp_table .=<<<HTML
          <tr><td>$id</td><td>$title</td><td>$author</td><td>$date_published</td>
            <td><form action='edit.php' method='POST'><input type='hidden' name='blogposts' value='$id'><input type='submit' name='submit' value='Edit'></form></td>
            <td><form action='delete.php' method='POST'><input type='hidden' name='bp_id' value='$id'><input type='submit' name='delete' value='Delete'></form></td>
          </tr>
HTML;
      }
      $stmt->close();

      $sql = "select id,tag,count(a_id) as ct from (select * from tags as t1 left join (select a_id,t_id from tagged) as t2 on t1.id=t2.t_id) as f group by id order by id";
      $stmt = $conn->prepare($sql);
      $stmt->bind_result($id,$tag,$count);
      $stmt->execute();
      while($stmt->fetch()){
        $tags_table .= <<<HTML
          <tr><td>$id</th><td>$tag</td><td>$count</td>
            <td><form action='rename_tag.php' method='POST'><input type='text' name='new_name' placeholder='new name here'><input type='hidden' name='t_id' value='$id'><input type='submit' name='submit' value='Rename'></form></td>
            <form action='delete_tag.php' method='POST'><input type='hidden' name='t_id' value='$id'><td><input type='submit' name='submit' value='Delete'></td>
            <td><input type='radio' name='delete_options' value=0 Checked>Delete this tag from all articles</td><td><input type='radio' name ='delete_options' value='1'>Delete all articles containing this tag.</td></form>
          </tr>
HTML;
      }
      $stmt->close();

      $sql = "select month(a.archive), year(a.archive), b.count from (select distinct archive from articles) as a, (select archive, count(archive) as count from articles group by archive) as b where a.archive=b.archive order by a.archive";
      $stmt = $conn->prepare($sql);
      $stmt->bind_result($month,$year,$count);
      $stmt->execute();
      while($stmt->fetch()){
        if(strlen($month) == 2)$archive = $year."-".$month;
        else $archive = $year."-0".$month;
        $ar_table .= <<<HTML
          <tr><td>$archive</td><td>$count</td>
            <td><form action='delete_archive.php' method='POST'><input type='hidden' name='archive' value='$archive-01'><input type='submit' name='submit' value='Delete'></form></td>
          </tr>
HTML;
      }
      $stmt->close();
  }else{
    header("location: login.php");
  }

?>
<html>
  <head>
    <title>Blogpost Editor</title>
    <style>
      table,tr,td,th {
        border-collapse:collapse;
        border: 1pt black solid;
      }
    </style>
    <a href='logout.php'>Log Out</a href>
    <?php echo $otp_stuff;  ?>
  </head>
  <body>
    <h1>Blogpost Options</h1>
    <table>
      <tr><th>ID</th><th>Title</th><th>Author</th><th>date published</th><th>Edit</th><th>Delete</th></tr>
      <?php echo $bp_table; ?>
    </table>
    <form action='edit.php' method='POST'>
      <input type='hidden' value='new' name='blogposts'>
      <input type='submit' value='New Blogpost' name='submit'>
    </form>

    <h1>Tags Options</h1>
    <table>
      <tr><th>ID</th><th>Tag</th><th># Articles</th><th>Rename</th><th>Remove</th><th colspan='2'>Options</th></tr>
      <?php echo $tags_table; ?>
    </table>

    <h1>Archives Options</h1>
    Deleting an archive deletes everything in that archive.
    <table>
      <tr><th>Archive</th><th># Articles</th><th>Remove</th></tr>
      <?php echo $ar_table; ?>
    </table>
  </body>
  <footer>
  </footer>
</html>
