<?php
include 'template.php';

template_head('Login', 'Jason Gassel, Josh Galan, Matthew McKeller');
template_forum_header();

// Received form data
$invalid = false;
if(isset($_POST['username']))
{
  $invalid = !session_auth($_POST['username'], $_POST['password'], $db);
  
  if(!$invalid)
  {
?>
<script>
window.location = "index.php";
</script>
<?php
  }
}
?>
  <form name="loginForm" action="login.php" method="post" class="login">
    <h2>Login</h2>
<?php
if($invalid)
  echo "    <h4 class=\"failure\">Incorrect Username or password</h4>\n";
?>
    <label for="username">Username:</label><input type="text" name="username" required autofocus /><br />
    <label for="password">Password:</label><input type="password" name="password" required /><br />
    <input type="submit" value="Submit" />
  </form>
<?php
template_footer();
?>