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
  
  // Setup database
  echo "<p>Installing...</p><p>\n";
  
  echo "Actually do something here!!!<br />\n"; //TODO
  
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