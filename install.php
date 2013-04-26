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
  echo "<h3>Install Script</h3><p>\n";
  
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
      
      // Create database tables
      echo 'Creating users table: ';
      if(mysql_query('CREATE TABLE user(uid INT UNIQUE NOT NULL AUTO_INCREMENT PRIMARY KEY, username VARCHAR(20) UNIQUE NOT NULL, password CHAR(32) NOT NULL, avatar VARCHAR(10), firstName VARCHAR(20), lastName VARCHAR(20))'))
        echo $success;
      else
        echo $failure;
      
      echo 'Creating threads table: ';
      if(mysql_query('CREATE TABLE thread(tid INT UNIQUE NOT NULL AUTO_INCREMENT PRIMARY KEY, uid INT NOT NULL, title VARCHAR(40) NOT NULL, type INT NOT NULL, question VARCHAR(100), tag VARCHAR(10), FOREIGN KEY (uid) REFERENCES user(uid))'))
        echo $success;
      else
        echo $failure;
      
      echo 'Creating posts table: ';
      if(mysql_query('CREATE TABLE post(pid INT UNIQUE NOT NULL AUTO_INCREMENT, tid INT NOT NULL, uid INT NOT NULL, date DATE NOT NULL, time TIME NOT NULL, text VARCHAR(10000), PRIMARY KEY(pid, tid), FOREIGN KEY (tid) REFERENCES thread(tid), FOREIGN KEY (uid) REFERENCES user(uid))'))
        echo $success;
      else
        echo $failure;
      
      echo 'Creating poll options table: ';
      if(mysql_query('CREATE TABLE poll_option(tid INT NOT NULL, oid INT NOT NULL, option_text VARCHAR(60) NOT NULL, PRIMARY KEY(tid, oid), FOREIGN KEY (tid) REFERENCES thread(tid))'))
        echo $success;
      else
        echo $failure;
      
      echo 'Creating poll votes table: ';
      if(mysql_query('CREATE TABLE poll_vote(tid INT NOT NULL, uid INT NOT NULL, oid INT NOT NULL, PRIMARY KEY(tid, uid), FOREIGN KEY (tid) REFERENCES thread(tid), FOREIGN KEY (uid) REFERENCES user(uid))'))
        echo $success;
      else
        echo $failure;
    }
  }
  mysql_close($db);
  
  // Avatars
  echo 'Checking images directory: ';
  if (!is_dir('images'))
  {
    if(mkdir('images'))
      echo $success;
    else
      echo $failure;
  }
  else
    echo $success;
  
  echo 'Deleting old avatar directory: ';
  if (is_dir('images/avatars'))
  {
    if(rmdir('images/avatars'))
      echo $success;
    else
      echo $failure;
  }
  else
    echo $dne;
  
  echo 'Creating avatar directory: ';
  if(mkdir('images/avatars'))
    echo $success;
  else
    echo $failure;
  
  // Finished
  echo "</p><p>Done</p>\n";
}
else
{
  // Make sure user really wants to install
?>
    <br />
    <h3 style="text-align: center;">WARNING: Install script will erase ALL existing data.</h3>
    <h1 style="text-align: center;"><a href="install.php?install">INSTALL</a></h1>
    <br />
    <br />
<?php
}

template_footer();
?>