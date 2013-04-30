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
      $title = template_thread_title($thread['title'], $thread['type'], $thread['tag']);
      
      template_head($title, 'Jason Gassel, Josh Galan, Matthew McKeller');
      template_forum_header();
      
      echo "    <section class=\"thread\">\n";
      echo "      <h2>$title</h2>\n";
      
      // Record vote, vulnerable to tampering but don't care right now
      if(isset($_POST['submit']) && $_SESSION['uid'] != 0)
      {
        $chosen = intval($_POST['poll']);
        $success = $db->query('INSERT INTO poll_vote (tid, uid, oid) VALUES('.$_GET['thread'].', '.$_SESSION['uid'].', '.$chosen.')');
        if(!$success)
          $db->query("UPDATE poll_vote SET oid =$chosen WHERE tid=".$_GET['thread'].' AND uid='.$_SESSION['uid']);
      }
      
      // Display poll
      if($thread['type'] & ThreadType::Poll)
      {
?>
    <form name="postForm" action="viewthread.php?thread=<?= $_GET['thread']; ?>" method="post">
      <strong><?= $thread['question']; ?></strong><br />
<?php
        $result = $db->query('SELECT oid, option_text FROM poll_option WHERE tid='.(int)$_GET['thread'].' ORDER BY oid');
        $totalResult = $db->query('SELECT COUNT(uid) AS count FROM poll_vote WHERE tid='.(int)$_GET['thread']);
        $total = $totalResult->fetch_array();
        if($total)
          $totalCount = $total['count'];
        else
          $totalCount = 0;
        $first = true;
        while($row = $result->fetch_array())
        {
          // Doing one poll option at a time rather than using GROUP BY to simplify the case where there's no votes
          $votesResult = $db->query('SELECT COUNT(uid) AS count FROM poll_vote WHERE tid='.(int)$_GET['thread'].' AND oid='.$row['oid']);
          $votes = $votesResult->fetch_array();
          if($votes)
            $voteCount = $votes['count'];
          else
            $voteCount = 0;
?>
      <input type="radio" name="poll" value="<?= $row['oid']; ?>" <?= ($first ? 'checked = "checked" ' : '' ); ?>/><label for="option<?= $row['oid']; ?>">[<?= $voteCount; ?>/<?= $totalCount; ?>] <?= $row['option_text']; ?></label><br />
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