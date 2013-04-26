<?php
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
?>
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
?>