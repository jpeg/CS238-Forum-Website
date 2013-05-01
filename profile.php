<?php
include 'config.php';
include 'template.php';

template_head('Profile: NAME', 'Jason Gassel, Josh Galan, Matthew McKeller');
template_forum_header();

$user = NULL;
if(isset($_GET['user']))
  $uid = (int)$_GET['user'];
else
  $uid = $_SESSION['uid'];

$db = new mysqli($db_server, $db_user, $db_password) or die('<div class="failure">ERROR: Database connection failed</div>');
if($db->select_db($db_database))
{
  $result = $db->query('SELECT username, password, avatar, firstName, lastName FROM user WHERE uid='.$uid);
  $user = $result->fetch_array();
}

if($user)
{
  if(isset($_POST['submit']) && $uid == $_SESSION['uid'])
  {
    // Save profile edits
    if(isset($_POST['password']))
    {
      if($_POST['password'] != NULL && $_POST['password'] != '')
        $user['password'] = md5($_POST['password']);
    }
    
    if(isset($_POST['fname']))
    {
      if($_POST['fname'] != NULL && $_POST['fname'] != '')
        $user['firstName'] = $_POST['fname'];
    }
    
    if(isset($_POST['lname']))
    {
      if($_POST['lname'] != NULL && $_POST['lname'] != '')
        $user['lastName'] = $_POST['lname'];
    }
    
    if(isset($_POST['avatar']) && $_POST['avatar'] != NULL && $_POST['avatar'] != '')
    {
      if($_POST['avatar'] != NULL && $_POST['avatar'] != '')
      {
        $temp = explode('.', $_POST['avatar']);
        $fileExtension = end($temp);
        if($fileExtension == 'png' || $fileExtension = 'jpg' || $fileExtension == 'jpeg' || $fileExtension == 'gif')
        {
          $user['avatar'] = $_POST['avatar'];
        }
      }
    }
    else
      $user['avatar'] = $avatar_default;
    
    if(!$db->query('UPDATE user SET password="'.$user['password'].'", firstName="'.htmlspecialchars($db->real_escape_string($user['firstName']), ENT_HTML5).'", lastName="'.htmlspecialchars($db->real_escape_string($user['lastName']), ENT_HTML5).'", avatar="'.$db->real_escape_string($user['avatar']).'" WHERE uid='.$uid))
      echo"    <h4 class=\"failure\">ERROR: Failed to update info</h4>\n";
  }
  
  if(isset($_GET['edit']) && $uid == $_SESSION['uid'])
  {
    // Show profile edit form
?>
<script>
// Validate passwords match
function validateForm()
{
  var x = document.forms["registerForm"]["password"].value;
  var y = document.forms["registerForm"]["password2"].value;
  if (x != y)
  {
    alert("Passwords do not match");
    return false;
  }
}
</script>

    <form name="registerForm" action="profile.php?user=<?= $uid; ?>" onsubmit="return validateForm()" method="post" class="register"><!--enctype="multipart/form-data"-->
      <h2>Edit profile</h2>
      <label for="password">Password:</label><input type="password" name="password" autofocus /><br />
      <label for="password2">Confirm Password:</label><input type="password" name="password2" /><br />
      <label for="fname">First name:</label><input type="text" name="fname" placeholder="<?= $user['firstName']; ?>" /><br />
      <label for="lname">Last name:</label><input type="text" name="lname" placeholder="<?= $user['lastName']; ?>" /><br />
      <label for="avatar">Avatar URL:</label><input type="text" name="avatar" placeholder="<?= $user['avatar']; ?>" /><br />
      <font style="font-style: italic;">Suggested avatar size 90x90.</font><br />
      <input type="submit" value="Submit" name="submit" />
    </form>
<?php
  }
  else
  {
    // Show profile info
?>
    <h2><?= $user['firstName']; ?> <?= $user['lastName']; ?> (<?= $user['username']; ?>)</h2>
    <img src="<?= $user['avatar']; ?>" alt="<?= $user['username'] ?>'s avatar" class="avatar" />
<?php
    
    if($uid == $_SESSION['uid'])
      echo "<br /><a href=\"profile.php?user=$uid&edit\">Edit</a>\n";
  }
}
elseif($uid == 0)
  echo "    <h2>Guest (Guest)</h2>\n";
else
  echo "    <h4 style=\"text-align: center;\">ERROR: Invalid User ID</h4>\n";

$db->close();

template_footer();
?>