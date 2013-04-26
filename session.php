<?php
include 'config.php';

function session_init()
{
  session_start();
  
  if(isset($_GET['logout']))
  {
    session_destroy();
    session_start();
  }
  
  // Check if not logged in
  if(!isset($_SESSION['uid']))
  {
    $_SESSION['uid'] = 0;
    $_SESSION['username'] = 'Guest';
  }
}

function session_auth($username, $password, &$db = NULL)
{
  global $db_server;
  global $db_user;
  global $db_password;
  global $db_database;
  
  $db_null = ($db == NULL);
  
  if($db_null)
    $db = new mysqli($db_server, $db_user, $db_password, $db_database) or die('<div class="failure">ERROR: Database connection failed</div>');
  
  $result = $db->query('SELECT uid, username FROM user WHERE username="'.$db->real_escape_string($username).'" AND password="'.md5($password).'"');
  while($row = $result->fetch_array())
  {
    $_SESSION['uid'] = $row['uid'];
    $_SESSION['username'] = $row['username'];
  }
  
  if($db_null)
    $db->close();
  
  if($_SESSION['username'] === $username)
    return true;
  return false;
}

// Just use session_destroy() to end session
?>