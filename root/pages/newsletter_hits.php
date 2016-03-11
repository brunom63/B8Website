<?php
require 'include/B8Website.php';
$website = new B8Website();	

$website->item_id = $website->db_validate('sdk_newsletters');

$sql = "UPDATE sdk_newsletters SET dt_hits = dt_hits + 1 WHERE ".$website->db_id."=".$website->item_id;
$website->sql_db($sql);

exit;
?>
