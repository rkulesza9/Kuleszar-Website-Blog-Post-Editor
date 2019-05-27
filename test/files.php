<?php

  function get_path_from($from_dir, $to_dir){
    if($from_dir == $to_dir) $result = "";
    else{
      if($from_dir{strlen($from_dir)-1} != "\\"){
        $from_dir .= "\\";
      }
      $result = str_replace($from_dir,"",$to_dir)."\\";
    }
    echo "from: $from_dir<br>to: $to_dir<br>result: $result<br>";
    return $result;
  }

  $orig_dir = getcwd();

  if(isset($_GET['dir_path'])){
    chdir($_GET['dir_path']);
  }

  $dir = getcwd();
  $files = scandir(getcwd());
  $table_content = "";

  for($x = 2; $x < count($files); $x++){
    $file = $files[$x];

    if(scandir($file)){
      chdir($file);
      $file_path = getcwd();
      chdir("..");
      $dir_path = get_path_from($orig_dir."\\",$file_path);
      $table_content .= "<tr><td>$file</td><td><a href='files.php?dir_path=$dir_path'>link</a></td></tr>";
    } else{
      $file_path = get_path_from($orig_dir,$dir);
      $table_content .= "<tr><td>$file</td><td><a href='$file_path$file'>link</a></td></tr>";

    }
  }

?>

<html>
  <head>
    <style>
      table, tr, th, td {
        border: 1px solid black;
        border-collapse: collapse;
      }
    </style>
  </head>
  <body>
    <table>
      <tr><th colspan='2'><?php echo getcwd(); ?></th></tr>
      <tr><th>Filename</th><th>link</th></tr>
      <?php echo $table_content; ?>
    </table>
  </body>
  <footer>
  </footer>
</html>
