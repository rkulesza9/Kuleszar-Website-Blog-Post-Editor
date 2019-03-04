<?php
  include "../dbconfig.php";

  $blogposts = "";

  $sql = "select id, title from articles";
  $stmt = $conn->prepare($sql);
  $stmt->bind_result($id,$title);
  $stmt->execute();

  $blogposts = "";
  while($stmt->fetch()){
    $blogposts .= "<option value='$id'>$title</option>";
  }

  $stmt->close();

?>
<html>
  <head>
    <title>Blogpost Editor</title>
    <script type='text/javascript' src='jquery.js'></script>
  </head>
  <body>
    
    <h1>Load Existing Blog Post</h1>
    <form action='edit.php' method='post'>
      <table>
        <tr><th>Load:</th><td>
          <select name='blogposts'  style='width:150px' onChange='onChange()'>
            <option value='new'>New</option>
            <?php echo $blogposts ?>
          </select>
        </td></tr>
        <tr><td><input type='submit' name='submit'  value='submit'></td><tr>
      </table>
    </form>
  </body>
  <footer>
  </footer>
</html>
