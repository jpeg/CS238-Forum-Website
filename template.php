<?php
include 'session.php';

class ThreadType
{
  const Normal = 0;
  const Sticky = 1;
  const Poll = 2;
}

function template_head($title, $author)
{
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <!--[if lt IE 9]>
  <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->
  <title><?=$title;?></title>
  <meta name="description" content="<?=$title;?>">
  <meta name="author" content="<?=$author;?>">
  <link rel="stylesheet" href="styles.css?v=<?=md5_file('styles.css');?>">
</head>
<body>
<?php
}

function template_forum_header()
{
  session_init();
?>
  <header>
    <ul>
      <li><a href="index.php" id="logo">forum238</a></li>
      <li><a href="search.php" id="search">Search</a></li>
<?php
  if($_SESSION['uid'] == 0)
  {
    echo "      <li><a href=\"login.php\">Login</a></li>\n";
    echo "      <li><a href=\"register.php\">Register</a></li>\n";
  }
  else
  {
    echo "      <li><a href=\"profile.php?user=".$_SESSION['uid']."\">".$_SESSION['username']."</a></li>\n";
    echo "      <li><a href=\"?logout\">Logout</a></li>\n";
  }
?>
    </ul>
  </header>
  <section id="content">
<?php
}

function template_footer()
{
?>
  </section>
  <footer>2013 - Jason Gassel, Josh Galan, Matthew McKeller</footer>
</body>
</html>
<?php
}

function template_thread_title($title, $type=0, $tag=NULL)
{
  // Build thread title
  if($type & ThreadType::Sticky && $tag != NULL && $tag != '')
    $title = '['.$tag.']'.$title;
  if($type & ThreadType::Poll)
    $title = 'Poll: '.$title;
  return $title;
}

function template_post($tid, $pid, $uid, $date, $time, $text, $db)
{
  $text = str_replace("\n", '<br />', $text);
  
  $result = $db->query('SELECT username, avatar FROM user WHERE uid='.$uid);
  $user = $result->fetch_array();
  
  if($user)
  {
?>
      <article class="post">
        <div class="userInfo">
          <a href="profile.php?<?= $uid ?>"><img src="<?= $user['avatar']; ?>" alt="<?= $user['username'] ?>'s avatar" class="avatar" /></a>
          <br />
          <a href="profile.php?<?= $uid ?>" class="username"><?= $user['username']; ?></a>
        </div>
        <div class="text">
          <?= $text; ?>
        </div>
        <br />
        <div class="controls">
<?php
    if($uid == $_SESSION['uid'])
      echo '<a href="viewthread.php?thread='.$tid."&delete=".$pid."\">Delete</a>\n";
?>
          <a href="createpost.php?thread=<?= $tid; ?>">Reply</a>
        </div>
      </article>
<?php
  }
}
?>