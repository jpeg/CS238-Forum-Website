<?php
include 'config.php';
include 'template.php';

template_head('Create Post', 'Jason Gassel, Josh Galan, Matthew McKeller');
template_forum_header();

if(isset($_POST['submit']))
{
  $db = new mysqli($db_server, $db_user, $db_password) or die('<div class="failure">ERROR: Database connection failed</div>');
  if($db->select_db($db_database))
  {
    if(isset($_GET['new']))
    {
      $type = ThreadType::Normal;
      $question = 'NULL';
      if(isset($_POST['question']) && $_POST['question'] != NULL && $_POST['question'] != '')
      {
        $type += ThreadType::Poll;
        $question = $db->real_escape_string($_POST['question']);
        $question = '"'.$question.'"'; //string for mysql insert
      }
      $tag = 'NULL';
      if(isset($_POST['tag']) && $_POST['tag'] != NULL && $_POST['tag'] != '')
      {
        $type += ThreadType::Sticky;
        $tag = $db->real_escape_string($_POST['tag']);
        $tag = '"'.$tag.'"'; //string for mysql insert
      }
      $db->query('INSERT INTO thread (uid, title, type, question, tag) VALUES('.$_SESSION['uid'].', "'.$db->real_escape_string($_POST['title'])."\", $type, $question, $tag)");
      $tid = mysqli_insert_id($db);
      
      if(isset($_POST['question']) && $_POST['question'] != NULL && $_POST['question'] != '')
      {
        $j = 1;
        for($i=1; $i<=6; $i++)
        {
          if(isset($_POST['option'.$i]) && $_POST['option'.$i] != NULL && $_POST['option'.$i] != '')
          {
            $db->query("INSERT INTO poll_option (tid, oid, option_text) VALUES($tid, $j, \"".$db->real_escape_string($_POST['option'.$i]).'")');
            $j++;
          }
        }
      }
    }
    elseif(isset($_GET['thread']))
      $tid = (int)$_GET['thread'];
    else
      die("<h4 style=\"text-align: center;\">ERROR: You're not supposed to be here...\n");
    
    $db->query("INSERT INTO post (tid, uid, date, time, text) VALUES($tid, ".$_SESSION['uid'].', CURDATE(), CURTIME(), "'.$db->real_escape_string($_POST['post']).'")');
    $db->close();
?>
<script>
window.location = "viewthread.php?thread=<?= $tid; ?>";
</script>
<?php
    die();
  }
  else
  {
    echo "    <h4 style=\"text-align: center;\">Database not found: <a href=\"install.php\">Install</a></h4>\n";
    $db->close();
  }
}

echo "    <form name=\"postForm\" action=\"createpost.php?".(isset($_GET['new']) ? 'new' : '').(isset($_GET['thread']) ? '&thread='.$_GET['thread'] : '')."\" method=\"post\">\n";
if(isset($_GET['new']))
  echo "      <label for=\"title\">Thread Title:</label><input type=\"text\" name=\"title\" required autofocus /><br />\n";
?>
      <textarea name="post" rows=10 cols=50 maxlength=10000 required <?= isset($_GET['new']) ? '' : 'autofocus' ?>></textarea><br />
      <label for="question">Poll Question:</label><input type="text" name="question" /><br />
      <label for="option1">Option 1:</label><input type="text" name="option1" /><br />
      <label for="option2">Option 2:</label><input type="text" name="option2" /><br />
      <label for="option3">Option 3:</label><input type="text" name="option3" /><br />
      <label for="option4">Option 4:</label><input type="text" name="option4" /><br />
      <label for="option5">Option 5:</label><input type="text" name="option5" /><br />
      <label for="option6">Option 6:</label><input type="text" name="option6" /><br />
      <label for="tag">Sticky Tag:</label><input type="text" name="tag" /><br />
      <input type="submit" value="Submit" name="submit" />
    </form>
<?php

template_footer();
?>