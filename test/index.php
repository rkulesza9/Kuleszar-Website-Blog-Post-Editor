<?php

  //Test Directory Methods
  // Look at standard directory and files in it
  $cwd = getcwd();
  echo "<p>current working directory: $cwd</p>";

  $files = scandir($cwd);

  echo "<ul>";
  foreach($files as $file){
    echo "<li>$file</li>";
  }
  echo "</ul>";
  // Look at new directories and files in them
  chdir("..");
  $dir = getcwd();
  $files = scandir($dir);
  echo "<p>current working directory: $dir</p>";

  $files = scandir($dir);

  echo "<ul>";
  foreach($files as $file){
    echo "<li>$file</li>";
  }
  echo "</ul>";
  // create new Directory
  mkdir($dir."\\test_dir");
  $files = scandir($dir);

  echo "<h3>After MKDIR()</h3>";

  echo "<ul>";
  foreach($files as $file){
    echo "<li>$file</li>";
  }
  echo "</ul>";

  // rename Directory
  echo "<h3>After Rename Directory</h3>";
  rename("test_dir","test_dir2");
  $files = scandir($dir);

  echo "<ul>";
  foreach($files as $file){
    echo "<li>$file</li>";
  }
  echo "</ul>";


  // move directory
  echo "<h3>After Move Directory</h3>";
  chdir("..");
  rename("editor/test_dir2","test_dir2");
  $dir = getcwd();
  $files = scandir($dir);

  echo "<ul>";
  foreach($files as $file){
    echo "<li>$file</li>";
  }
  echo "</ul>";

  // delete new Directory
  echo "<h3>After rmdir</h3>";
  rmdir("test_dir2");

  $files = scandir($dir);

  echo "<ul>";
  foreach($files as $file){
    echo "<li>$file</li>";
  }
  echo "</ul>";

?>
