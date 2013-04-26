<?php
include 'config.php';
include 'template.php';

template_head('Forum Index', 'Jason Gassel');
template_forum_header();

$db = mysql_connect($db_server, $db_user, $db_password) or die('<div class="failure">ERROR: Database connection failed</div>');
if(mysql_select_db($db_database, $db))
{
?>
    <p>TODO</p>
<?php
}
else
  echo "  <h4 style=\"text-align: center;\">Database not found: <a href=\"install.php\">Install</a></h4>\n";

mysql_close();

template_footer();
?>