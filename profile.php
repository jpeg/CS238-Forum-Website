<?php
include 'template.php';

template_head('Profile: NAME', 'Jason Gassel, Josh Galan, Matthew McKeller');
template_forum_header();

$user = NULL;
if(isset($_GET['user']))
{
  $db = new mysqli($db_server, $db_user, $db_password) or die('<div class="failure">ERROR: Database connection failed</div>');
  if($db->select_db($db_database))
  {
    $result = $db->query('SELECT username, avatar, firstName, lastName FROM user WHERE uid='.$_GET['user']);
    $user = $result->fetch_array();
  }
}

if($user)
{
?>
    <h2><?= $user['firstName']; ?> <?= $user['lastName']; ?> (<?= $user['username']; ?>)</h2>
    <img src="<?= $user['avatar']; ?>" alt="<?= $user['username'] ?>'s avatar" class="avatar" />
<?php
}
else
  echo "  <h4 style=\"text-align: center;\">ERROR: Invalid User ID</h4>\n";

template_footer();
?>