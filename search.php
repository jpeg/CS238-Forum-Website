<?php
include 'config.php';
include 'template.php';

template_head('Search', 'Jason Gassel, Josh Galan, Matthew McKeller');
template_forum_header();

$db = new mysqli($db_server, $db_user, $db_password) or die('<div class="failure">ERROR: Database connection failed</div>');
if($db->select_db($db_database))
{
?>
    <form name="searchForm" action="search.php" method="get" class="search">
      <h2>Search</h2>
      <label for="search">Keywords:</label><input type="text" name="search" value="<?= (isset($_GET['search']) ? $_GET['search'] : ''); ?>" /><br />
      <input type="submit" value="Submit" />
    </form>
<?php
  
  if(isset($_GET['search']))
  {
    // Seperate keywords and search for each induvidually in thread title and post text
    $keywords = explode(' ', htmlspecialchars($db->real_escape_string($_GET['search']), ENT_HTML5));
    $query = 'SELECT thread.tid AS tid, thread.uid AS uid, title, type, question, tag FROM thread, post WHERE thread.tid=post.tid AND (';
    $first = true;
    foreach($keywords as $keyword)
    {
      if(!$first)
        $query = $query.' OR ';
      $first = false;
      $query = $query."title LIKE \"%$keyword%\" OR text like \"%$keyword%\"";
    }
    $query = $query.') GROUP BY thread.tid ORDER BY COUNT(*) DESC, date DESC, time DESC';
    
    $result = $db->query($query);
    if($result->num_rows > 0)
    {
      while($row = $result->fetch_array())
        template_thread_info($db, $row['tid'], $row['uid'], $row['title'], $row['type'], $row['question'], $row['tag']);
    }
    else
    {
      echo "      <h4>Sorry, no threads or posts matching your search terms were found.</h4>\n";
    }
  }
}
else
  echo "    <h4 style=\"text-align: center;\">Database not found: <a href=\"install.php\">Install</a></h4>\n";

$db->close();

template_footer();
?>