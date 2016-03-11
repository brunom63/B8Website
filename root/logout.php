<?php
include 'include/include.php';

$website = new B8Admin();

$website->session_deactivate_cookie();

$website->page_redirect('/');
?>
