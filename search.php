<?php
include 'config.php';
include 'template.php';

template_head('Search', 'Jason Gassel, Josh Galan, Matthew McKeller');
template_forum_header();

$db = new mysqli($db_server, $db_user, $db_password) or die('<div class="failure">ERROR: Database connection failed</div>');
if($db->select_db($db_database))
{
?>
    <p>TEST:</p>
<?php
  template_thread_info($db, 1, 1, 'Test thread, fake name but it links right...', 0);
}
else
  echo "    <h4 style=\"text-align: center;\">Database not found: <a href=\"install.php\">Install</a></h4>\n";

$db->close();

template_footer();
?>