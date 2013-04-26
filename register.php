<?php
include 'config.php';
include 'template.php';

template_head('Register', 'Jason Gassel');
template_forum_header();

// Received form data
if(isset($_POST['username']))
{
  $db = new mysqli($db_server, $db_user, $db_password) or die('<div class="failure">ERROR: Database connection failed</div>');
  if($db->select_db($db_database))
  {
    if($db->query('INSERT INTO user (username, password, avatar, firstName, lastName) VALUES("'.$db->real_escape_string($_POST['username']).'", "'.md5($_POST['password']).'", NULL, "'.$db->real_escape_string($_POST['fname']).'", "'.$db->real_escape_string($_POST['lname']).'")'))
    {
    // Success, redirect to index
    session_auth($_POST['username'], $_POST['password'], $db);
?>
<script>
window.location = "index.php";
</script>
<?php
    }
    else
      echo "  <h4 class=\"failure\">Username already taken</h4>\n";
  }
  else
    echo "  <h4 style=\"text-align: center;\">Database not found: <a href=\"install.php\">Install</a></h4>\n";
  $db->close();
}
?>

<script>
// Validate form backup in case browser doesn't suppport 'required'
function validateForm()
{
  var x = document.forms["registerForm"]["username"].value;
  if (x == null || x == "")
  {
    alert("Username cannot be blank");
    return false;
  }
  x = document.forms["registerForm"]["password"].value;
  if (x == null || x == "")
  {
    alert("Password cannot be blank");
    return false;
  }
  y = document.forms["registerForm"]["password2"].value;
  if (x != y)
  {
    alert("Passwords do not match");
    return false;
  }
}
</script>

  <form name="registerForm" action="register.php" onsubmit="return validateForm()" method="post">
    Username: <input type="text" name="username" required autofocus /><br />
    Password: <input type="password" name="password" required /><br />
    Confirm Password: <input type="password" name="password2" required /><br />
    First name: <input type="text" name="fname" /><br />
    Last name: <input type="text" name="lname" /><br />
    <input type="submit" value="Submit" />
  </form>
<?php
template_footer();
?>