<?php
include 'config.php';
include 'template.php';

template_head('Install Script', 'Jason Gassel');
?>
<section id="content">
<?php

if(isset($_GET['install']))
{
  // Just in case logged into old instance
  session_start();
  session_destroy();
  
  $success = "<font color=\"green\">SUCCESS</font><br />\n";
  $failure = "<font color=\"red\">FAILED</font><br />\n";
  $dne = "<font color=\"green\">DNE</font><br />\n";
  
  // Setup database
  echo "<p>Installing...</p><p>\n";
  
  echo 'Connect to database: ';
  $db = mysql_connect($db_server, $db_user, $db_password);
  if(!$db)
    echo $failure;
  else
  {
    echo $success;
    
    // Drop database to delete old data
    echo 'Drop old database: ';
    if(mysql_query("DROP DATABASE $db_database", $db))
      echo $success;
    else
      echo $dne;
    
    // Create and connect to database
    echo 'Creating database: ';
    if(mysql_query("CREATE DATABASE $db_database", $db))
      echo $success;
    else
      echo $failure;
    echo 'Connecting to database: ';
    if(!mysql_select_db($db_database, $db))
      echo $failure;
    else
    {
      echo $success;
      
      //TODO setup tables
    }
  }
  mysql_close($db);
  
  echo "</p><p>Done</p>\n";
}
else
{
  // Make sure user really wants to install
?>
    <br />
    <h3 align="center">WARNING: Install script will erase ALL existing data.</h3>
    <h1 align="center"><a href="install.php?install">INSTALL</a></h1>
    <br />
    <br />
<?php
}

template_footer();
?>