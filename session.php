<?php
function session_init()
{
  session_start();
  
  // Check if not logged in
  if(!isset($_SESSION['uid']))
  {
    $_SESSION['uid'] = 0;
    $_SESSION['username'] = 'Guest';
  }
}

function session_auth($username, $password)
{
  // TODO check auth and fill out $_SESSION if successful
  
  return false;
}

// Just use session_destroy() to end session
?>