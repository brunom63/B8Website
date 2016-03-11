<?php
require 'include/B8Website.php';
$website = new B8Website();	

$query = "SELECT * FROM sdk_posts WHERE ".$website->db_id."=1";
$result = $website->sql_db($query);
$website->item = $website->fetch_array_db($result);
?>
<html>
<head>
<?php $website->call_metas(); ?>
<title>Title</title>
<?php $website->call_head(); ?>
</head>
<body>
<?php $website->call_header(); ?>

<?php $website->call_footer(); ?> 
<?php $website->call_scripts(); ?>
</body>
</html>
