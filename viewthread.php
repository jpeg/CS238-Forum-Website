<?php
include 'config.php';
include 'template.php';

$db = new mysqli($db_server, $db_user, $db_password) or die('<div class="failure">ERROR: Database connection failed</div>');
if($db->select_db($db_database))
{
  if(isset($_GET['thread']))
  {
    $result = $db->query('SELECT title, type, question, tag FROM thread WHERE tid='.(int)$_GET['thread']);
    $thread = $result->fetch_array();
    
    if($thread)
    {
      //TODO record vote
      
      // Build thread title
      $title = $thread['title'];
      if($thread['type'] & ThreadType::Sticky != 0)
        $title = '['.$thread['tag'].']'.$title;
      if($thread['type'] & ThreadType::Poll != 0)
        $title = 'Poll: '.$title;
      
      template_head($title, 'Jason Gassel, Josh Galan, Matthew McKeller');
      template_forum_header();
      
      echo "    <section class=\"thread\">\n";
      echo "      <h2>$title</h2>\n";
      
      if($thread['type'] & ThreadType::Poll)
      {
?>
    <form name="postForm" action="viewthread.php" method="post">
      <strong><?= $thread['question']; ?></strong><br />
<?php
        $result = $db->query('SELECT oid, option_text FROM poll_option WHERE tid='.(int)$_GET['thread'].' ORDER BY oid');
        $first = true;
        while($row = $result->fetch_array())
        {
          //TODO find # of votes for each option and total
?>
      <input type="radio" name="poll" id="option<?= $row['oid']; ?>" value="<?= $row['option_text']; ?>" <?= ($first ? 'checked = "checked" ' : '' ); ?>/><label for="option<?= $row['oid']; ?>"><?= $row['option_text']; ?></label><br />
<?php
          $first = false;
        }
?>
      <input type="submit" value="Vote" name="submit" />
    </form>
<?php
      }
      
      //TODO display posts
      
      echo "    </section>\n";
    }
  }
  else
  {
    template_head('TODO', 'Jason Gassel, Josh Galan, Matthew McKeller');
    template_forum_header();
    echo "    <h4 style=\"text-align: center;\">ERROR: Invalid Thread ID</h4>\n";
  }
}
else
{
  template_head('View Thread', 'Jason Gassel, Josh Galan, Matthew McKeller');
  template_forum_header();
  echo "    <h4 style=\"text-align: center;\">Database not found: <a href=\"install.php\">Install</a></h4>\n";
}

$db->close();

template_footer();
?>