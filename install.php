<?php
include 'config.php';
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Install Script</title>
  <meta name="description" content="Install Script">
  <meta name="author" content="Jason Gassel">
  <link rel="stylesheet" href="css/styles.css?v=1.0">
  <!--[if lt IE 9]>
  <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->
</head>
<body>
<?php
if(isset($_GET['install']))
{
  // Just in case logged into old instance
  session_start();
  session_destroy();
  
  $success = "<font color=\"gree\">SUCCESS</font><br />\n";
  $failure = "<font color=\"red\">FAILED</font><br />\n";
  
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
      echo "<font color=\"green\">DNE</font><br />\n";
    
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
<?php
}
?>
</html>