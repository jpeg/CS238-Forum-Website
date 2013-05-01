<?php
include 'config.php';
include 'template.php';

template_head('Register', 'Jason Gassel, Josh Galan, Matthew McKeller');
template_forum_header();

// Received form data
$invalid = false;
if(isset($_POST['submit']))
{
  $db = new mysqli($db_server, $db_user, $db_password) or die('<div class="failure">ERROR: Database connection failed</div>');
  if($db->select_db($db_database))
  {
    // Save avatar
    /* Issues with upload, just do image URL for now
    $avatar = NULL;
    $fileExtension = NULL;
    $goodUpload = false;
    if(isset($_FILES['avatar']))
    {
      $fileExtension = end(explode('.', $_FILES['avatar']['name']));
      if($fileExtension == 'png' || $fileExtension = 'jpg' || $fileExtension == 'jpeg' || $fileExtension == 'gif')
      {
        $type = $_FILES['avatar']['type'];
        if($type == 'image/png' || $type == 'image/x-png' || $type == 'image/jpg' || $type == 'image/jpeg' || $type == 'image/gif')
        {
          if($_FILES['avatar']['size'] < $avatar_maxSize)
          {
            if ($_FILES["file"]["error"] > 0)
            {
              echo "Error: " . $_FILES["file"]["error"] . "<br />";
            }
            else
            {
              $goodUpload = true;
            }
          }
        }
      }
    }*/
    
    if($db->query('INSERT INTO user (username, password, firstName, lastName) VALUES("'.htmlspecialchars($db->real_escape_string($_POST['username']), ENT_HTML5).'", "'.md5($_POST['password']).'", "'.htmlspecialchars($db->real_escape_string($_POST['fname']), ENT_HTML5).'", "'.htmlspecialchars($db->real_escape_string($_POST['lname']), ENT_HTML5).'")'))
    {
      // Success, redirect to index
      session_auth($_POST['username'], $_POST['password'], $db);
?>
<script>
window.location = "index.php";
</script>
<?php
      die();
    }
    else
      $invalid = true;
    
    if(/*$goodUpload*/ isset($_POST['avatar']) && !$invalid)
    {
      //move_uploaded_file($_FILES['avatar']['tmp_name'], 'images/avatars/'.$_SESSION['uid'].'.'.$fileExtension);
      $fileExtension = end(explode('.', $_POST['avatar']));
      if($fileExtension == 'png' || $fileExtension = 'jpg' || $fileExtension == 'jpeg' || $fileExtension == 'gif')
      {
        if(!$db->query('UPDATE user SET avatar="'.$db->real_escape_string($_POST['avatar']).'" WHERE uid='.(int)$_SESSION['uid']))
          echo"    <h4 class=\"failure\">ERROR: Failed to set avater</h4>\n";
      }
    }
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
  var y = document.forms["registerForm"]["password2"].value;
  if (x != y)
  {
    alert("Passwords do not match");
    return false;
  }
}
</script>

    <form name="registerForm" action="register.php" onsubmit="return validateForm()" method="post" class="register"><!--enctype="multipart/form-data"-->
      <h2>Register account</h2>
<?php
if($invalid)
  echo "      <h4 class=\"failure\">Username already taken</h4>\n";
?>
      <label for="username">Username:</label><input type="text" name="username" required autofocus /><br />
      <label for="password">Password:</label><input type="password" name="password" required /><br />
      <label for="password2">Confirm Password:</label><input type="password" name="password2" required /><br />
      <label for="fname">First name:</label><input type="text" name="fname" /><br />
      <label for="lname">Last name:</label><input type="text" name="lname" /><br />
      <label for="avatar">Avatar URL:</label><input type="text" name="avatar" /><!--"file"--><br />
      <font style="font-style: italic;">Suggested avatar size 90x90.</font><br />
      <input type="submit" value="Submit" name="submit" />
    </form>
<?php
template_footer();
?>