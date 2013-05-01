<?php
include 'config.php';
include 'template.php';

template_head('Forum Index', 'Jason Gassel, Josh Galan, Matthew McKeller');
template_forum_header();

$db = new mysqli($db_server, $db_user, $db_password) or die('<div class="failure">ERROR: Database connection failed</div>');
if($db->select_db($db_database))
{
?>
    <h2 style="text-align: center;">Welcome to forum238!</h2>
    <font class="threadSection">Sticky Threads:</font><a href="createpost.php?new" class="newThread">Create Thread</a>
<?php
  // Display sticky posts at top
  $result = $db->query('SELECT thread.tid AS tid, thread.uid AS uid, title, type, question, tag FROM thread, post WHERE thread.tid=post.tid AND (type=1 OR type=3) GROUP BY thread.tid ORDER BY MAX(pid) DESC');
  while($row = $result->fetch_array())
    template_thread_info($db, $row['tid'], $row['uid'], $row['title'], $row['type'], $row['question'], $row['tag']);
  
?>
    <br />
    <font class="threadSection">Threads:</font><a href="createpost.php?new" class="newThread">Create Thread</a>
<?php
  // Display all posts
  $result = $db->query('SELECT thread.tid AS tid, thread.uid AS uid, title, type, question, tag FROM thread, post WHERE thread.tid=post.tid AND (type=0 OR type=2) GROUP BY thread.tid ORDER BY MAX(pid) DESC');
  while($row = $result->fetch_array())
    template_thread_info($db, $row['tid'], $row['uid'], $row['title'], $row['type'], $row['question']);

}
else
  echo "    <h4 style=\"text-align: center;\">Database not found: <a href=\"install.php\">Install</a></h4>\n";

$db->close();

template_footer();
?>